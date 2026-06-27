<?php

namespace RadioPanel\Controllers;

use RadioPanel\Core\Auth;
use RadioPanel\Core\Config;
use RadioPanel\Core\Logger;
use RadioPanel\Core\PageRouter;
use RadioPanel\Http\Request;
use RadioPanel\Http\Response;

class PageController
{
    public function check(Request $request, array $params)
    {
        Auth::requireLogin();

        $route = isset($params['route']) ? $params['route'] : $request->query('route', '');
        $path = PageRouter::routeToPath($route);

        if ($path === null) {
            return Response::text('error');
        }

        $resolved = PageRouter::resolvePage($path);

        return Response::text($resolved !== null ? 'true' : 'error', $resolved !== null ? 200 : 404);
    }

    public function show(Request $request, array $params)
    {
        Auth::requireLogin();

        $route = isset($params['route']) ? $params['route'] : '';
        $path = PageRouter::routeToPath($route);

        if ($path === null) {
            return Response::text('Page not found', 404);
        }

        $resolved = PageRouter::resolvePage($path);
        if ($resolved === null) {
            return Response::text('Page not found', 404);
        }

        require_once APP_INCLUDES . '/helpers.php';

        global $conn;
        if (!isset($conn)) {
            $conn = \RadioPanel\Core\Database::connection();
        }

        ob_start();
        try {
            include $resolved;
        } catch (\Throwable $e) {
            ob_end_clean();
            Logger::exception($e, ['route' => $route, 'page' => $path]);

            if (Config::get('app.debug', false)) {
                return Response::html('<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>', 500);
            }

            return Response::html('<div class="alert alert-danger">This page could not be loaded.</div>', 500);
        }
        $html = ob_get_clean();

        if ($html === '') {
            return Response::html('<div class="alert alert-warning">This page returned no content.</div>');
        }

        return Response::html($html);
    }
}
