<?php

namespace RadioPanel\Controllers;

use RadioPanel\Core\Auth;
use RadioPanel\Core\Config;
use RadioPanel\Core\Logger;
use RadioPanel\Core\Paths;
use RadioPanel\Core\Security;
use RadioPanel\Http\Request;
use RadioPanel\Http\Response;
use RadioPanel\View\View;

class DashboardController
{
    public function index(Request $request, array $params)
    {
        Auth::requireLogin();

        $user = Auth::user();
        $appPath = Config::get('app.app_path', Paths::appPath());
        $appBaseUrl = rtrim(Config::get('app.url', Paths::origin()), '/') . $appPath;

        ob_start();
        try {
            View::render('layouts/panel.php', [
                'user' => $user,
                'hasDiscord' => !empty($user['discord']),
                'displayOverlay' => $request->query('welcome') !== null ? 'block' : 'none',
                'appPath' => $appPath,
                'appBaseUrl' => $appBaseUrl,
                'csrfToken' => Security::csrfToken(),
                'apiBase' => View::url('api'),
            ]);
        } catch (\Throwable $e) {
            ob_end_clean();
            Logger::exception($e, ['component' => 'panel_layout']);

            if (Config::get('app.debug', false)) {
                return Response::html('<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>', 500);
            }

            return Response::html('<div class="alert alert-danger">The panel could not be loaded.</div>', 500);
        }
        $html = ob_get_clean();

        return Response::html($html);
    }
}
