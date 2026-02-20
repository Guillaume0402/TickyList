<?php
use App\Controllers\HomeController;
use App\Http\Router;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
