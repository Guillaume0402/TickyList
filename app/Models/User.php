<?php

/**
 * User Model
 */

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = getDbConnection();
    }

    public function create($email, $password, $name) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (email, password, name) VALUES (?, ?, ?)"
        );
        
        try {
            $stmt->execute([$email, $hashedPassword, $name]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}
