<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure we're in the right directory
chdir(__DIR__ . '/../');

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
