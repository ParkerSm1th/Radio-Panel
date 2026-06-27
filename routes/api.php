<?php

use RadioPanel\Controllers\ApiController;
use RadioPanel\Controllers\PageController;

$router->get('api/page/check/{route}', PageController::class . '@check');
$router->get('api/page/{route}', PageController::class . '@show');

$router->match(['GET', 'POST'], 'api/{handler}', ApiController::class . '@dispatch');
