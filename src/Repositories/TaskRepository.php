<?php

namespace App\Repositories;


final class TaskRepository
{
    public function findActiveByProjectForUser(int $projectId, int $userId): array
    {
        $stmt = db()->prepare(
            "SELECT t.id, t.title, t.description, t.status, t.created_at, t.updated_at 
                FROM tasks t 
                JOIN projects p 
                ON p.id = t.project_id
                WHERE t.project_id = :project_id
                AND t.deleted_at IS NULL 
                AND p.user_id = :user_id
                AND p.deleted_at IS NULL 
                ORDER BY t.created_at DESC"
        );
        $stmt->execute(['project_id' => $projectId, 'user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function create(int $projectId, string $title, ?string $description): int
    {
        $pdo = db();
        $stmt = $pdo->prepare(
            "INSERT INTO tasks (project_id, title, description, status) 
                VALUES (:project_id, :title, :description, 0)"
        );
        $stmt->execute(['project_id' => $projectId, 'title' => $title, 'description' => $description]);
        return (int)$pdo->lastInsertId();
    }
}
