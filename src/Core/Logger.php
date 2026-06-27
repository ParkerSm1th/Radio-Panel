<?php

namespace RadioPanel\Core;

class Logger
{
    private static $levels = [
        'debug' => 0,
        'info' => 1,
        'notice' => 2,
        'warning' => 3,
        'error' => 4,
        'critical' => 5,
        'alert' => 6,
        'emergency' => 7,
    ];

    public static function debug($message, array $context = [])
    {
        self::log('debug', $message, $context);
    }

    public static function info($message, array $context = [])
    {
        self::log('info', $message, $context);
    }

    public static function notice($message, array $context = [])
    {
        self::log('notice', $message, $context);
    }

    public static function warning($message, array $context = [])
    {
        self::log('warning', $message, $context);
    }

    public static function error($message, array $context = [])
    {
        self::log('error', $message, $context);
    }

    public static function critical($message, array $context = [])
    {
        self::log('critical', $message, $context);
    }

    public static function exception(\Throwable $e, array $context = [])
    {
        $context = array_merge($context, [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        if (Config::get('app.debug', false)) {
            $context['trace'] = $e->getTraceAsString();
        }

        self::log('error', $e->getMessage(), $context);
    }

    public static function log($level, $message, array $context = [])
    {
        if (!self::enabled()) {
            return;
        }

        $level = strtolower((string) $level);
        if (!isset(self::$levels[$level])) {
            $level = 'error';
        }

        if (self::$levels[$level] < self::$levels[self::minimumLevel()]) {
            return;
        }

        $line = sprintf(
            '[%s] %s: %s',
            date('Y-m-d H:i:s'),
            strtoupper($level),
            self::formatMessage($message, $context)
        );

        self::write($line);
    }

    public static function pruneOldLogs()
    {
        if (!self::enabled()) {
            return;
        }

        $days = (int) Config::get('logging.retention_days', Config::get('gdpr.log_retention_days', 90));
        if ($days <= 0) {
            return;
        }

        $dir = self::logDirectory();
        if (!is_dir($dir)) {
            return;
        }

        $cutoff = time() - ($days * 86400);
        $files = glob($dir . '/app-*.log');
        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $cutoff) {
                @unlink($file);
            }
        }
    }

    private static function enabled()
    {
        return (bool) Config::get('logging.enabled', true);
    }

    private static function minimumLevel()
    {
        $level = strtolower((string) Config::get('logging.level', 'error'));
        return isset(self::$levels[$level]) ? $level : 'error';
    }

    private static function logDirectory()
    {
        $path = Config::get('logging.path', 'auto');
        if ($path === null || $path === '' || $path === 'auto') {
            return Paths::storageLogsPath();
        }

        return rtrim((string) $path, '/');
    }

    private static function formatMessage($message, array $context)
    {
        $message = self::stringify($message);
        $context = self::sanitizeContext($context);

        if (empty($context)) {
            return $message;
        }

        $encoded = json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            return $message;
        }

        return $message . ' ' . $encoded;
    }

    private static function sanitizeContext(array $context)
    {
        $hidden = ['pass', 'password', 'csrf_token', 'api_key', 'token', 'secret'];

        foreach ($context as $key => $value) {
            $normalized = strtolower((string) $key);
            foreach ($hidden as $needle) {
                if (strpos($normalized, $needle) !== false) {
                    $context[$key] = '[redacted]';
                    continue 2;
                }
            }

            if (is_array($value)) {
                $context[$key] = self::sanitizeContext($value);
            }
        }

        return $context;
    }

    private static function stringify($message)
    {
        if (is_string($message)) {
            return preg_replace('/\s+/', ' ', trim($message));
        }

        if (is_scalar($message) || $message === null) {
            return (string) $message;
        }

        if ($message instanceof \Throwable) {
            return $message->getMessage();
        }

        return print_r($message, true);
    }

    private static function write($line)
    {
        $dir = self::logDirectory();
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
            error_log($line);
            return;
        }

        $file = $dir . '/app-' . date('Y-m-d') . '.log';
        @file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
