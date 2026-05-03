<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia sessão antes de qualquer output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/config/constants.php';
require_once BASE_PATH . '/app/Helpers/Helpers.php';

date_default_timezone_set(TIMEZONE);

// Mostrar erros só em desenvolvimento
if (defined('DEBUG') && DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

require BASE_PATH . '/bootstrap/app.php';
