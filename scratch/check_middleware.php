<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$router = app('router');
$groups = $router->getMiddlewareGroups();

echo "Middleware Groups:\n";
print_r(array_keys($groups));

$aliases = $router->getMiddleware();
echo "\nMiddleware Aliases:\n";
print_r(array_keys($aliases));
