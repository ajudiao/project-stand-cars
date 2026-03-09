<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';
include_once __DIR__ . '/../app/Helpers/Helpers.php';

date_default_timezone_set(TIMEZONE);

// iniciar aplicação
require __DIR__ . '/../bootstrap/app.php';
