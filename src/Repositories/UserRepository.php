<?php

namespace App\Repositories;


final class UserRepository
{
    public function findByEmail(string $email): ?array
    {
        $stmt = db()->prepare('SELECT id, email, password_hash FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    // public function create(string $email, string $password): int
    // {
    //     $stmt = db()->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
    //     $stmt->execute([
    //         'email' => $email,
    //         'password' => password_hash($password, PASSWORD_DEFAULT),
    //     ]);
    //     return (int)db()->lastInsertId();
    // }
}
