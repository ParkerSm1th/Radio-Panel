<?php

namespace RadioPanel\Core;

class ErrorHandler
{
    private static $registered = false;

    public static function register()
    {
        if (self::$registered) {
            return;
        }

        self::$registered = true;

        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);

        Logger::pruneOldLogs();
    }

    public static function handleException(\Throwable $e)
    {
        if ($e instanceof HttpException) {
            Logger::warning($e->getMessage(), self::requestContext($e->getStatusCode()));
            self::respond($e->getMessage(), $e->getStatusCode(), $e);
            return;
        }

        Logger::exception($e, self::requestContext(500));
        self::respond(self::publicMessage($e), 500, $e);
    }

    public static function handleError($severity, $message, $file, $line)
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        $level = self::severityToLevel($severity);
        Logger::log($level, $message, [
            'file' => $file,
            'line' => $line,
            'severity' => $severity,
        ] + self::requestContext());

        if (Config::get('app.debug', false) && self::isApiRequest()) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        }

        return true;
    }

    public static function handleShutdown()
    {
        $error = error_get_last();
        if ($error === null) {
            return;
        }

        $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
        if (!in_array($error['type'], $fatalTypes, true)) {
            return;
        }

        Logger::critical($error['message'], [
            'file' => $error['file'],
            'line' => $error['line'],
            'severity' => $error['type'],
        ] + self::requestContext(500));

        if (headers_sent()) {
            return;
        }

        self::respond(self::publicMessage(null), 500);
    }

    public static function respond($message, $statusCode = 500, \Throwable $e = null)
    {
        if (headers_sent()) {
            return;
        }

        http_response_code($statusCode);

        if (self::isApiRequest()) {
            header('Content-Type: application/json; charset=utf-8');
            $payload = [
                'error' => (string) $message,
                'status' => (int) $statusCode,
            ];

            if (Config::get('app.debug', false) && $e !== null) {
                $payload['debug'] = [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
            }

            echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (self::isAjaxRequest()) {
            header('Content-Type: text/plain; charset=utf-8');
            echo (string) $message;
            exit;
        }

        header('Content-Type: text/html; charset=utf-8');
        $safeMessage = Security::escape((string) $message);
        $title = Security::escape(self::statusTitle($statusCode));

        echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>' . $title . '</title>'
            . '<style>body{font-family:system-ui,sans-serif;background:#0c1b2d;color:#fff;display:flex;'
            . 'align-items:center;justify-content:center;min-height:100vh;margin:0;padding:24px;}'
            . '.box{max-width:520px;background:#132743;border:1px solid #1f3a5c;border-radius:12px;'
            . 'padding:24px}h1{margin:0 0 12px;font-size:1.4rem}a{color:#2989eb}</style></head><body>'
            . '<div class="box"><h1>' . $title . '</h1><p>' . $safeMessage . '</p>'
            . '<p><a href="' . Security::escape(Paths::webPath('index.php')) . '">Return to login</a></p>'
            . '</div></body></html>';
        exit;
    }

    public static function isApiRequest()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
        return strpos($uri, '/api/') !== false;
    }

    public static function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public static function requestContext($statusCode = null)
    {
        $context = [
            'method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null,
            'uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null,
            'ip' => Security::clientIp(),
        ];

        if ($statusCode !== null) {
            $context['status'] = (int) $statusCode;
        }

        $user = Auth::user();
        if ($user !== null && isset($user['id'])) {
            $context['user_id'] = (int) $user['id'];
        }

        return $context;
    }

    public static function publicMessage(\Throwable $e = null)
    {
        if (Config::get('app.debug', false) && $e !== null) {
            return $e->getMessage();
        }

        return 'Something went wrong. Please try again later.';
    }

    private static function severityToLevel($severity)
    {
        if (in_array($severity, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR], true)) {
            return 'error';
        }

        if (in_array($severity, [E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING], true)) {
            return 'warning';
        }

        return 'notice';
    }

    private static function statusTitle($statusCode)
    {
        $map = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Server Error',
            503 => 'Service Unavailable',
        ];

        return isset($map[$statusCode]) ? $map[$statusCode] : 'Error';
    }
}
