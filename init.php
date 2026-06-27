<?php

if (!defined('RADIO_PANEL_ROOT')) {
    define('RADIO_PANEL_ROOT', __DIR__);
}

define('APPLICATION_DIR', RADIO_PANEL_ROOT . '/application');
define('APP_INCLUDES', APPLICATION_DIR . '/Includes');
define('HANDLERS_DIR', RADIO_PANEL_ROOT . '/handlers');

require_once RADIO_PANEL_ROOT . '/src/bootstrap.php';
