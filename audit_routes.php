<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$routes = Route::getRoutes();
$count = 0;
foreach ($routes as $route) {
    $uri = $route->uri();
    $name = $route->getName() ?? '';
    $action = $route->getActionName() ?? '';
    if (preg_match('/penilaian[-_]?(kewangan|teknikal)/i', $uri) || preg_match('/penilaian(Kewangan|Teknikal)/i', $name) || preg_match('/Penilaian(Kewangan|Teknikal)/i', $action)) {
        $count++;
        if ($count <= 40) {
            $methods = implode('|', $route->methods());
            echo "[$methods] $uri -> $name ($action)\n";
        }
    }
}
