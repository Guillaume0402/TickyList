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

    public function softDeleteForUser(int $taskId, int $userId): bool
    {
        $stmt = db()->prepare(
            "UPDATE tasks t
         JOIN projects p ON p.id = t.project_id
         SET t.deleted_at = NOW(),
             t.updated_at = NOW()
         WHERE t.id = :task_id
           AND t.deleted_at IS NULL
           AND p.user_id = :user_id
           AND p.deleted_at IS NULL"
        );

        $stmt->execute(['task_id' => $taskId, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
    
    public function updateStatusForUser (int $taskId, int $newStatus, int $userId): bool
    {
        $stmt = db()->prepare(
            "UPDATE tasks t
         JOIN projects p ON p.id = t.project_id
         SET t.status = :new_status,
             t.updated_at = NOW()
         WHERE t.id = :task_id
           AND t.deleted_at IS NULL
           AND p.user_id = :user_id
           AND p.deleted_at IS NULL"
        );

        $stmt->execute(['new_status' => $newStatus, 'task_id' => $taskId, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }

    public function updateForUser (int $taskId, string $newTitle, ?string $newDescription, int $userId): bool
    {
        $stmt = db()->prepare(
            "UPDATE tasks t
         JOIN projects p ON p.id = t.project_id
         SET t.title = :new_title,
             t.description = :new_description,
             t.updated_at = NOW()
         WHERE t.id = :task_id
           AND t.deleted_at IS NULL
           AND p.user_id = :user_id
           AND p.deleted_at IS NULL"
        );

        $stmt->execute(['new_title' => $newTitle, 'new_description' => $newDescription, 'task_id' => $taskId, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }

    public function countQuickViewsByUserId(int $userId): array
{
    $sql = "
        SELECT
            SUM(CASE WHEN t.due_date = CURDATE() THEN 1 ELSE 0 END) AS today_count,
            SUM(CASE WHEN t.due_date < CURDATE() THEN 1 ELSE 0 END) AS late_count,
            SUM(CASE WHEN t.due_date > CURDATE() THEN 1 ELSE 0 END) AS upcoming_count
        FROM tasks t
        INNER JOIN projects p ON p.id = t.project_id
        WHERE p.user_id = :user_id
          AND p.deleted_at IS NULL
          AND t.deleted_at IS NULL
          AND t.status <> 2
          AND t.due_date IS NOT NULL
    ";
    $stmt = db()->prepare($sql);
    $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];

    return [
        'today' => (int)($row['today_count'] ?? 0),
        'late' => (int)($row['late_count'] ?? 0),
        'upcoming' => (int)($row['upcoming_count'] ?? 0),
    ];
}
}
