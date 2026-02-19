<?php

/**
 * Router - Front Controller Pattern
 * Handles routing for the application
 */

class Router {
    private $routes = [];
    private $notFoundHandler;

    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function setNotFound($handler) {
        $this->notFoundHandler = $handler;
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        
        // Remove trailing slash
        $requestUri = rtrim($requestUri, '/');
        if (empty($requestUri)) {
            $requestUri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                $pattern = $this->convertToRegex($route['path']);
                
                if (preg_match($pattern, $requestUri, $matches)) {
                    array_shift($matches); // Remove full match
                    return $this->callHandler($route['handler'], $matches);
                }
            }
        }

        // No route found
        if ($this->notFoundHandler) {
            return $this->callHandler($this->notFoundHandler, []);
        } else {
            http_response_code(404);
            echo "404 - Page Not Found";
        }
    }

    private function convertToRegex($path) {
        // Convert :param to capture group
        $pattern = preg_replace('/\/:([^\/]+)/', '/([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function callHandler($handler, $params) {
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        } elseif (is_string($handler)) {
            // Format: 'ControllerName@method'
            list($controller, $method) = explode('@', $handler);
            $controllerClass = $controller;
            
            if (!class_exists($controllerClass)) {
                die("Controller $controllerClass not found");
            }
            
            $controllerInstance = new $controllerClass();
            
            if (!method_exists($controllerInstance, $method)) {
                die("Method $method not found in $controllerClass");
            }
            
            return call_user_func_array([$controllerInstance, $method], $params);
        }
    }
}
