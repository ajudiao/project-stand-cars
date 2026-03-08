<?php
declare(strict_types=1);

use App\Controllers\Public\HomeController;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';

date_default_timezone_set(TIMEZONE);

// iniciar aplicação
require __DIR__ . '/../bootstrap/app.php';
