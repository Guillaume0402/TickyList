<?php

/**
 * Front Controller - TickLyst
 * Entry point for the application
 */

// Start session
session_start();

// Load configuration
require_once __DIR__ . '/../config/env.php';
loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../config/db.php';

// Load core classes
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/CSRF.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Core/Flash.php';
require_once __DIR__ . '/../app/Core/helpers.php';

// Load models
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Project.php';
require_once __DIR__ . '/../app/Models/Task.php';

// Load controllers
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/ProjectController.php';
require_once __DIR__ . '/../app/Controllers/TaskController.php';
require_once __DIR__ . '/../app/Controllers/NotificationController.php';

// Create router
$router = new Router();

// Public routes
$router->get('/', function() {
    if (Auth::check()) {
        header('Location: /dashboard');
    } else {
        header('Location: /login');
    }
    exit;
});

$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Dashboard
$router->get('/dashboard', 'DashboardController@index');

// Projects
$router->get('/projects', 'ProjectController@index');
$router->get('/projects/create', 'ProjectController@create');
$router->post('/projects', 'ProjectController@store');
$router->get('/projects/:id', 'ProjectController@show');
$router->get('/projects/:id/edit', 'ProjectController@edit');
$router->post('/projects/:id', 'ProjectController@update');
$router->post('/projects/:id/delete', 'ProjectController@delete');

// Tasks
$router->get('/tasks', 'TaskController@index');
$router->get('/tasks/create', 'TaskController@create');
$router->post('/tasks', 'TaskController@store');
$router->get('/tasks/trash', 'TaskController@trash');
$router->get('/tasks/:id', 'TaskController@show');
$router->get('/tasks/:id/edit', 'TaskController@edit');
$router->post('/tasks/:id', 'TaskController@update');
$router->post('/tasks/:id/delete', 'TaskController@delete');
$router->post('/tasks/:id/restore', 'TaskController@restore');
$router->post('/tasks/:id/permanent-delete', 'TaskController@permanentDelete');
$router->post('/tasks/:id/toggle-status', 'TaskController@toggleStatus');

// Notifications
$router->get('/notifications', 'NotificationController@index');

// 404 handler
$router->setNotFound(function() {
    http_response_code(404);
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="display-1">404</h1>
                <p class="lead">Page Not Found</p>
                <a href="/" class="btn btn-primary">Go Home</a>
            </div>
        </div>
    </div>
</body>
</html>';
});

// Dispatch the request
$router->dispatch();
