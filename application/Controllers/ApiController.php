<?php

namespace RadioPanel\Controllers;

use RadioPanel\Core\Auth;
use RadioPanel\Core\Config;
use RadioPanel\Core\Logger;
use RadioPanel\Core\Security;
use RadioPanel\Http\Request;
use RadioPanel\Http\Response;

class ApiController
{
    
    private static $publicHandlers = [
        'login',
        'loginSearch',
    ];

    
    private static $apiKeyHandlers = [
        'logSong',
        'logListeners',
        'setDiscord',
    ];

    public function dispatch(Request $request, array $params)
    {
        $handler = isset($params['handler']) ? $params['handler'] : '';

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $handler)) {
            return Response::text('Not Found', 404);
        }

        $file = HANDLERS_DIR . '/api/' . $handler . '.php';
        if (!is_file($file)) {
            return Response::text('Not Found', 404);
        }

        if (!in_array($handler, self::$publicHandlers, true)) {
            if (in_array($handler, self::$apiKeyHandlers, true)) {
                $key = $request->input('api', $request->query('api', ''));
                if (!Security::validateApiKey((string) $key)) {
                    return Response::text('forbidden', 403);
                }
            } else {
                Auth::requireLogin();
            }
        }

        require_once APP_INCLUDES . '/helpers.php';

        global $conn;
        if (!isset($conn)) {
            $conn = \RadioPanel\Core\Database::connection();
        }

        ob_start();
        try {
            include $file;
        } catch (\Throwable $e) {
            ob_end_clean();
            Logger::exception($e, ['handler' => $handler]);

            if (Config::get('app.debug', false)) {
                return Response::error($e->getMessage(), 500, [
                    'handler' => $handler,
                ]);
            }

            return Response::error('Handler failed', 500);
        }
        $output = ob_get_clean();

        if (self::returnsHtml($handler)) {
            return Response::html($output);
        }

        return Response::text($output);
    }

    private static function returnsHtml($handler)
    {
        static $htmlHandlers = [
            'navbar',
            'notifications',
            'notificationCount',
            'getProfile',
            'newestRequest',
            'stats',
        ];

        return in_array($handler, $htmlHandlers, true);
    }
}
