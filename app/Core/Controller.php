<?php

/**
 * Base Controller
 * Parent class for all controllers
 */

class Controller {
    
    protected function view($viewPath, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            die("View $viewPath not found");
        }
        
        require_once $viewFile;
    }

    protected function redirect($path) {
        header("Location: $path");
        exit;
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth() {
        if (!Auth::check()) {
            Flash::set('error', 'Please login to continue');
            $this->redirect('/login');
        }
    }

    protected function requireGuest() {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
    }
}
