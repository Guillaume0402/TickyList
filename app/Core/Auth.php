<?php

/**
 * Authentication Utility
 * Handles user authentication and session management
 */

class Auth {
    
    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function user() {
        if (!self::check()) {
            return null;
        }

        // Cache user in session to avoid repeated DB queries
        if (!isset($_SESSION['user_data'])) {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare("SELECT id, email, name FROM users WHERE id = ?");
            $stmt->execute([self::id()]);
            $_SESSION['user_data'] = $stmt->fetch();
        }

        return $_SESSION['user_data'];
    }

    public static function login($userId) {
        $_SESSION['user_id'] = $userId;
        
        // Load user data
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT id, email, name FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $_SESSION['user_data'] = $stmt->fetch();

        // Regenerate session ID for security
        session_regenerate_id(true);
    }

    public static function logout() {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }

    public static function attempt($email, $password) {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            self::login($user['id']);
            return true;
        }

        return false;
    }
}
