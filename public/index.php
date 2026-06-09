<?php

// PAKSA KONEKSI KE MYSQL
putenv('DB_CONNECTION=mysql');
putenv('DB_HOST=mysql.railway.internal');
putenv('DB_PORT=3306');
putenv('DB_DATABASE=railway');
putenv('DB_USERNAME=root');
putenv('DB_PASSWORD=wOHYxJpWRPdttHXREOxmXRiWWLmcYhwe');

use Illuminate\Contracts\Http\Kernel;
// ... (biarkan sisa kode di bawahnya tetap ada)

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
