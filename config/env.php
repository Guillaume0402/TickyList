<?php

/**
 * Environment Configuration Loader
 * Loads configuration from .env file
 */

if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path)) {
            die("Environment file not found. Please copy .env.example to .env and configure it.");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // Set in $_ENV and putenv
                $_ENV[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
}

function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}
