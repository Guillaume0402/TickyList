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

    public function rename(int $projectId, int $userId, string $newName): bool
    {
        $stmt = db()->prepare("UPDATE projects SET name = :name, updated_at = NOW() WHERE id = :id AND user_id = :user_id AND deleted_at IS NULL");
        $stmt->execute(['name' => $newName, 'id' => $projectId, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }


    public function findRecentWithStatsByUserId(int $userId, int $limit = 3): array
    {
        $limit = max(1, min($limit, 10));

        $sql = "
        SELECT
            p.id,
            p.name,
            CAST(COUNT(t.id) AS UNSIGNED) AS task_count,
            CAST(COALESCE(SUM(CASE WHEN t.status = 2 THEN 1 ELSE 0 END), 0) AS UNSIGNED) AS done_count
        FROM projects p
        LEFT JOIN tasks t
            ON t.project_id = p.id
           AND t.deleted_at IS NULL
        WHERE p.user_id = :user_id
          AND p.deleted_at IS NULL
        GROUP BY p.id, p.name
        ORDER BY COALESCE(p.updated_at, p.created_at) DESC, p.id DESC
        LIMIT {$limit}
    ";

        $stmt = db()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function findSidebarByUserId(int $userId): array
    {
        $sql = "
        SELECT
            p.id,
            p.name,
            p.color,
            COUNT(t.id) AS task_count
        FROM projects p
        LEFT JOIN tasks t
            ON t.project_id = p.id
           AND t.deleted_at IS NULL
        WHERE p.user_id = :user_id
          AND p.deleted_at IS NULL
        GROUP BY p.id, p.name, p.color
        ORDER BY COALESCE(p.updated_at, p.created_at) DESC, p.id DESC
        LIMIT 50
    ";
        $stmt = db()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
