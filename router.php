<?php

require_once __DIR__ . '/init.php';

use RadioPanel\Http\Kernel;
use RadioPanel\Http\Request;

$kernel = new Kernel();
$kernel->handle(Request::capture());
