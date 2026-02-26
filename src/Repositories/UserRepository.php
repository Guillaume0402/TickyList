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

    public function create(string $email, string $passwordHash): int
    {
        $stmt = db()->prepare("
        INSERT INTO users (email, password_hash, created_at)
        VALUES (:email, :password_hash, NOW())
    ");
        $stmt->execute([
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        return (int) db()->lastInsertId();
    }
}
