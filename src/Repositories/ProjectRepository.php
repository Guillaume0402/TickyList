<?php
namespace App\Repositories;


final class ProjectRepository
{
    public function findActiveWithStatsByUserId(int $userId): array
    {
        $stmt = db()->prepare("
            SELECT p.id, p.name, COUNT(t.id) AS task_count, SUM(CASE WHEN t.status = 2 THEN 1 ELSE 0 END) AS done_count
            FROM projects p
            LEFT JOIN tasks t ON t.project_id = p.id AND t.deleted_at IS NULL
            WHERE p.user_id = :user_id AND p.deleted_at IS NULL
            GROUP BY p.id, p.name
            ORDER BY p.created_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function create(int $userId, string $name): int
    {
        $pdo = db();
        $stmt = $pdo->prepare("INSERT INTO projects (user_id, name) VALUES (:user_id, :name)");
        $stmt->execute(['user_id' => $userId, 'name' => $name]);
        return (int) $pdo->lastInsertId();
    }

    public function findByIdForUser(int $projectId, int $userId): ?array
    {
        $stmt = db()->prepare("SELECT id, name FROM projects WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['id' => $projectId, 'user_id' => $userId]);
        $project = $stmt->fetch();
        return $project ?: null;
    }

    public function softDelete(int $projectId, int $userId): bool
    {
        $stmt = db()->prepare("UPDATE projects SET deleted_at = NOW(), updated_at = NOW() WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL");
        $stmt->execute(['id' => $projectId, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
    
}
