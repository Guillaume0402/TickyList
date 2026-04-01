<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;


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
$router->post('/projects/rename', [ProjectController::class, 'rename']);
$router->post('/tasks/create', [TaskController::class, 'create']);
$router->post('/tasks/delete', [TaskController::class, 'delete']);
$router->post('/tasks/status', [TaskController::class, 'updateStatus']);
$router->post('/tasks/update', [TaskController::class, 'update']);
$router->post('/tasks/status-ajax', [TaskController::class, 'updateStatusAjax']);
$router->get('/today', [ProjectController::class, 'quickView']);
$router->get('/late', [ProjectController::class, 'quickView']);
$router->get('/later', [ProjectController::class, 'quickView']);
$router->get('/upcoming', [ProjectController::class, 'quickView']);
