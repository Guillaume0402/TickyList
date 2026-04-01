<?php

namespace App\Repositories;


final class TaskRepository
{
    public function findActiveByProjectForUser(int $projectId, int $userId): array
    {
        $stmt = db()->prepare(
            "SELECT t.id, t.title, t.description, t.status, t.due_date, t.created_at, t.updated_at 
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

    public function create(int $projectId, string $title, ?string $description, ?string $dueDate): int
    {
        $pdo = db();
        $stmt = $pdo->prepare(
            "INSERT INTO tasks (project_id, title, description, status, due_date) 
                VALUES (:project_id, :title, :description, 0, :due_date)"
        );
        $stmt->execute(['project_id' => $projectId, 'title' => $title, 'description' => $description, 'due_date' => $dueDate]);
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

    public function updateStatusForUser(int $taskId, int $newStatus, int $userId): bool
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

    public function updateForUser(int $taskId, string $newTitle, ?string $newDescription, ?string $newDueDate, int $userId): bool
    {
        $stmt = db()->prepare(
            "UPDATE tasks t
         JOIN projects p ON p.id = t.project_id
         SET t.title = :new_title,
             t.description = :new_description,
             t.due_date = :new_due_date,
             t.updated_at = NOW()
         WHERE t.id = :task_id
           AND t.deleted_at IS NULL
           AND p.user_id = :user_id
           AND p.deleted_at IS NULL"
        );

        $stmt->execute(['new_title' => $newTitle, 'new_description' => $newDescription, 'new_due_date' => $newDueDate, 'task_id' => $taskId, 'user_id' => $userId]);
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

    public function getGlobalStatsByUserId(int $userId): array
    {
        $sql = "
        SELECT
            SUM(CASE WHEN t.status = 2 THEN 1 ELSE 0 END) AS done_count,
            SUM(CASE WHEN t.status = 1 THEN 1 ELSE 0 END) AS in_progress_count,
            SUM(CASE WHEN t.due_date < CURDATE() AND t.status <> 2 THEN 1 ELSE 0 END) AS late_count,
            SUM(CASE WHEN t.due_date = CURDATE() AND t.status <> 2 THEN 1 ELSE 0 END) AS today_count,
            COUNT(DISTINCT CASE WHEN t.status <> 2 THEN t.project_id END) AS projects_in_progress,
            COUNT(DISTINCT CASE WHEN t.due_date < CURDATE() AND t.status <> 2 THEN t.project_id END) AS projects_late,
            SUM(CASE WHEN t.updated_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS weekly_total,
            SUM(CASE WHEN t.status = 2 AND t.updated_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS weekly_done
        FROM tasks t
        INNER JOIN projects p ON p.id = t.project_id
        WHERE p.user_id = :user_id
          AND p.deleted_at IS NULL
          AND t.deleted_at IS NULL
    ";
        $stmt = db()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
        $weeklyTotal = (int)($row['weekly_total'] ?? 0);
        $weeklyDone = (int)($row['weekly_done'] ?? 0);

        return [
            'done' => (int)($row['done_count'] ?? 0),
            'in_progress' => (int)($row['in_progress_count'] ?? 0),
            'late' => (int)($row['late_count'] ?? 0),
            'today' => (int)($row['today_count'] ?? 0),
            'projects_in_progress' => (int)($row['projects_in_progress'] ?? 0),
            'projects_late' => (int)($row['projects_late'] ?? 0),
            'weekly_progress' => $weeklyTotal > 0 ? (int)round(($weeklyDone / $weeklyTotal) * 100) : 0,
        ];
    }

    public function getTodayTasksByUserId(int $userId, int $limit = 5): array
    {
        $sql = "
            SELECT t.id, t.title, p.name as project_name
            FROM tasks t
            INNER JOIN projects p ON p.id = t.project_id
            WHERE p.user_id = :user_id
              AND t.due_date = CURDATE()
              AND t.status <> 2
              AND t.deleted_at IS NULL
              AND p.deleted_at IS NULL
            ORDER BY t.created_at DESC
            LIMIT :limit
        ";
        $stmt = db()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRecentActivityByUserId(int $userId, int $limit = 5): array
    {
        $sql = "
            SELECT
                t.title,
                p.name AS project_name,
                t.status,
                t.updated_at
            FROM tasks t
            INNER JOIN projects p ON p.id = t.project_id
            WHERE p.user_id = :user_id
              AND p.deleted_at IS NULL
              AND t.deleted_at IS NULL
            ORDER BY t.updated_at DESC
            LIMIT :limit
        ";

        $stmt = db()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function findByDueFilterByUserId(int $userId, string $filter): array
    {
        $sql = "
                        SELECT t.id, t.project_id, t.title, t.description, t.status, t.due_date, p.name as project_name
            FROM tasks t
            INNER JOIN projects p ON p.id = t.project_id
            WHERE p.user_id = :user_id
              AND t.deleted_at IS NULL
              AND p.deleted_at IS NULL
        ";

        switch ($filter) {
            case 'today':
                $sql .= " AND t.due_date = CURDATE() AND t.status <> 2";
                break;
            case 'late':
                $sql .= " AND t.due_date < CURDATE() AND t.status <> 2";
                break;
            case 'upcoming':
                $sql .= " AND t.due_date > CURDATE() AND t.status <> 2";
                break;
            default:
                return [];
        }

        $sql .= " ORDER BY t.created_at DESC";

        $stmt = db()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
