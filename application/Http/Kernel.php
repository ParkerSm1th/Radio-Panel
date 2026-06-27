<?php

namespace RadioPanel\Http;

use RadioPanel\Core\ErrorHandler;
use RadioPanel\Core\Logger;

class Kernel
{
    
    private $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->registerRoutes();
    }

    public function handle(Request $request)
    {
        try {
            $match = $this->router->dispatch($request);

            if ($match === null) {
                Logger::info('Route not found', ErrorHandler::requestContext(404));
                Response::text('Not Found', 404)->send();
                return;
            }

            $handler = $match['handler'];
            $params = $match['params'];

            if (is_string($handler) && strpos($handler, '@') !== false) {
                list($class, $method) = explode('@', $handler, 2);
                $controller = new $class();

                if (!method_exists($controller, $method)) {
                    Logger::error('Controller method missing', [
                        'controller' => $class,
                        'method' => $method,
                    ] + ErrorHandler::requestContext(500));
                    Response::text('Handler not found', 500)->send();
                    return;
                }

                $response = $controller->$method($request, $params);
            } elseif (is_callable($handler)) {
                $response = call_user_func($handler, $request, $params);
            } else {
                Logger::error('Invalid route handler', ErrorHandler::requestContext(500));
                Response::text('Invalid handler', 500)->send();
                return;
            }

            if ($response instanceof Response) {
                $response->send();
            }
        } catch (\Throwable $e) {
            ErrorHandler::handleException($e);
        }
    }

    private function registerRoutes()
    {
        $router = $this->router;
        require RADIO_PANEL_ROOT . '/routes/web.php';
        require RADIO_PANEL_ROOT . '/routes/api.php';
    }

    public function getRouter()
    {
        return $this->router;
    }
}
