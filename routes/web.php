<?php

use RadioPanel\Controllers\AuthController;
use RadioPanel\Controllers\DashboardController;

$router->get('app', DashboardController::class . '@index');
$router->get('app/{route}', DashboardController::class . '@index');
$router->get('logout', AuthController::class . '@logout');
