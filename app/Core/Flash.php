<?php

/**
 * Flash Messages Utility
 * Stores temporary messages in session
 */

class Flash {
    
    public static function set($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }

    public static function has($type) {
        return isset($_SESSION['flash'][$type]);
    }

    public static function get($type) {
        if (self::has($type)) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    public static function display() {
        $types = ['success', 'error', 'warning', 'info'];
        $output = '';

        foreach ($types as $type) {
            if (self::has($type)) {
                $message = self::get($type);
                $alertClass = $type === 'error' ? 'danger' : $type;
                $output .= '<div class="alert alert-' . $alertClass . ' alert-dismissible fade show" role="alert">';
                $output .= htmlspecialchars($message);
                $output .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                $output .= '</div>';
            }
        }

        return $output;
    }
}
