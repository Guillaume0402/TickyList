<?php

/**
 * Database Configuration
 * Returns PDO connection instance
 */

require_once __DIR__ . '/env.php';

function getDbConnection() {
    static $pdo = null;

    if ($pdo === null) {
        $host = env('DB_HOST', 'localhost');
        $port = env('DB_PORT', '3306');
        $dbname = env('DB_NAME', 'ticklyst');
        $user = env('DB_USER', 'root');
        $pass = env('DB_PASS', '');

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}
