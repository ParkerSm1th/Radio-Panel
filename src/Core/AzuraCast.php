<?php

namespace RadioPanel\Core;

use PDO;

class AzuraCast
{
    
    private static $cache = null;

    
    private static $cacheTime = 0;

    
    private static $cacheTtl = 5;

    private static $lastFetchErrorLog = 0;

    
    public static function stats()
    {
        if (self::$cache !== null && (time() - self::$cacheTime) < self::$cacheTtl) {
            return self::$cache;
        }

        $payload = self::fetchNowPlaying();
        if ($payload === null) {
            if (time() - self::$lastFetchErrorLog > 300) {
                Logger::warning('AzuraCast now playing request failed or is not configured');
                self::$lastFetchErrorLog = time();
            }

            self::$cache = ['success' => false];
            self::$cacheTime = time();

            return self::$cache;
        }

        $listeners = isset($payload['listeners']) && is_array($payload['listeners'])
            ? $payload['listeners']
            : [];

        $current = (int) ($listeners['current'] ?? 0);
        $peak = (int) ($listeners['unique'] ?? $current);
        if ($peak < $current) {
            $peak = $current;
        }

        $live = isset($payload['live']) && is_array($payload['live']) ? $payload['live'] : [];
        $isLive = !empty($live['is_live']);
        $streamerName = trim((string) ($live['streamer_name'] ?? ''));

        $dj = self::resolveDj($streamerName, $isLive);

        self::$cache = [
            'success' => true,
            'listeners' => [
                'current' => $current,
                'peak' => $peak,
            ],
            'currentDJ' => $dj,
            'now_playing' => $payload['now_playing'] ?? null,
            'is_live' => $isLive,
        ];
        self::$cacheTime = time();

        return self::$cache;
    }

    
    public static function currentDjId()
    {
        $stats = self::stats();
        if (empty($stats['success'])) {
            return null;
        }

        $dj = $stats['currentDJ'] ?? [];

        if (!empty($dj['autoDJ'])) {
            return null;
        }

        return isset($dj['id']) ? (int) $dj['id'] : null;
    }

    
    private static function fetchNowPlaying()
    {
        $baseUrl = rtrim((string) Config::get('azuracast.url', ''), '/');
        if ($baseUrl === '') {
            return null;
        }

        $station = trim((string) Config::get('azuracast.station', ''));
        $path = $station !== '' ? '/api/nowplaying/' . rawurlencode($station) : '/api/nowplaying';

        $url = $baseUrl . $path;

        if (!function_exists('curl_init')) {
            return self::fetchNowPlayingFileGet($url);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $apiKey = Config::get('azuracast.api_key', '');
        $headers = ['Accept: application/json'];
        if ($apiKey !== '') {
            $headers[] = 'Authorization: Bearer ' . $apiKey;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($result === false || $httpCode >= 400) {
            return null;
        }

        return self::decodeResponse($result, $station === '');
    }

    
    private static function fetchNowPlayingFileGet($url)
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 8,
                'header' => "Accept: application/json\r\n",
            ],
        ]);

        $result = @file_get_contents($url, false, $context);
        if ($result === false) {
            return null;
        }

        return self::decodeResponse($result, false);
    }

    
    private static function decodeResponse($json, $pickFirstStation)
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return null;
        }

        if ($pickFirstStation) {
            if (isset($data[0]) && is_array($data[0])) {
                return $data[0];
            }

            return null;
        }

        return $data;
    }

    
    private static function resolveDj($streamerName, $isLive)
    {
        if (!$isLive || $streamerName === '') {
            return [
                'id' => null,
                'username' => '',
                'autoDJ' => true,
            ];
        }

        $conn = Database::connection();
        $stmt = $conn->prepare(
            'SELECT id, username FROM users WHERE UPPER(username) = :name OR UPPER(displayRole) = :name LIMIT 1'
        );
        $name = strtoupper($streamerName);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return [
                'id' => null,
                'username' => $streamerName,
                'autoDJ' => false,
            ];
        }

        return [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'autoDJ' => false,
        ];
    }
}
