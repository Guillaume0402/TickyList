<?php
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProjectController;


// Routes de l’application - à déclarer ici
// Exemple de route GET pour la page d’accueil

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/register', [HomeController::class, 'register']);
$router->post('/register', [AuthController::class, 'registerPost']);
$router->get('/login', [HomeController::class, 'login']);
$router->post('/login', [AuthController::class, 'loginPost']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/projects', [ProjectController::class, 'index']);
$router->post('/projects/create', [ProjectController::class, 'create']);
$router->get('/project', [ProjectController::class, 'show']);
$router->post('/projects/delete', [ProjectController::class, 'delete']);


