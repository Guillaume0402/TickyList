<?php
use App\Controllers\HomeController;
use App\Http\Router;

// Routes de l’application - à déclarer ici
// Exemple de route GET pour la page d’accueil

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/register', [HomeController::class, 'register']);
$router->get('/login', [HomeController::class, 'login']);
