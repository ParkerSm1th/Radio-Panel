<?php

namespace RadioPanel\Controllers;

use RadioPanel\Core\Auth;
use RadioPanel\Core\Config;
use RadioPanel\Core\Paths;
use RadioPanel\Http\Request;
use RadioPanel\Http\Response;

class AuthController
{
    public function logout(Request $request, array $params)
    {
        Auth::logout();

        return Response::redirect(Paths::absolute('index.php'));
    }
}
