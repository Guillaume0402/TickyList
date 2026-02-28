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
}
