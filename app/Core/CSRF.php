<?php

/**
 * CSRF Protection Utility
 * Generates and validates CSRF tokens
 */

class CSRF {
    
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function getToken() {
        return self::generateToken();
    }

    public static function validateToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function field() {
        $token = self::getToken();
        $name = env('CSRF_TOKEN_NAME', 'csrf_token');
        return '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($token) . '">';
    }

    public static function verify() {
        $name = env('CSRF_TOKEN_NAME', 'csrf_token');
        $token = $_POST[$name] ?? '';
        
        if (!self::validateToken($token)) {
            http_response_code(403);
            die('CSRF token validation failed');
        }
    }
}
