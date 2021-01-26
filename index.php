<?php

define('VG_ACCESS', true);
header('Content-Type:text/html;charset=itf-8');
session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';

use core\base\exception\RouteException;
use core\base\controllers\RouteController;


try {
    RouteController::getInstance();

} catch (RouteException $e) {
    exit($e->getMessage());
}

