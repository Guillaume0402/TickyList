<?php

/**
 * Task Model
 */

class Task {
    private $pdo;

    public function __construct() {
        $this->pdo = getDbConnection();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO tasks (user_id, project_id, title, description, status, priority, due_date, remind_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->execute([
            $data['user_id'],
            $data['project_id'],
            $data['title'],
            $data['description'] ?? null,
            $data['status'] ?? 'todo',
            $data['priority'] ?? 2,
            $data['due_date'] ?? null,
            $data['remind_at'] ?? null
        ]);
        
        return $this->pdo->lastInsertId();
    }

    public function findById($id, $userId) {
        $stmt = $this->pdo->prepare(
            "SELECT t.*, p.name as project_name, p.color as project_color
             FROM tasks t
             JOIN projects p ON t.project_id = p.id
             WHERE t.id = ? AND t.user_id = ?"
        );
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public function getAll($userId, $includeDeleted = false) {
        $sql = "SELECT t.*, p.name as project_name, p.color as project_color
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                WHERE t.user_id = ?";
        
        if (!$includeDeleted) {
            $sql .= " AND t.deleted_at IS NULL";
        }
        
        $sql .= " ORDER BY t.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getByProject($projectId, $userId) {
        $stmt = $this->pdo->prepare(
            "SELECT t.*, p.name as project_name, p.color as project_color
             FROM tasks t
             JOIN projects p ON t.project_id = p.id
             WHERE t.project_id = ? AND t.user_id = ? AND t.deleted_at IS NULL
             ORDER BY 
                CASE t.status 
                    WHEN 'doing' THEN 1
                    WHEN 'todo' THEN 2
                    WHEN 'done' THEN 3
                END,
                t.priority ASC,
                t.due_date ASC"
        );
        $stmt->execute([$projectId, $userId]);
        return $stmt->fetchAll();
    }

    public function search($userId, $filters = []) {
        $sql = "SELECT t.*, p.name as project_name, p.color as project_color
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                WHERE t.user_id = ? AND t.deleted_at IS NULL";
        $params = [$userId];

        if (!empty($filters['project_id'])) {
            $sql .= " AND t.project_id = ?";
            $params[] = $filters['project_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND t.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['priority'])) {
            $sql .= " AND t.priority = ?";
            $params[] = $filters['priority'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (t.title LIKE ? OR t.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY t.due_date ASC, t.priority ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getToday($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT t.*, p.name as project_name, p.color as project_color
             FROM tasks t
             JOIN projects p ON t.project_id = p.id
             WHERE t.user_id = ? AND t.due_date = CURDATE() AND t.deleted_at IS NULL
             ORDER BY t.priority ASC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getOverdue($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT t.*, p.name as project_name, p.color as project_color
             FROM tasks t
             JOIN projects p ON t.project_id = p.id
             WHERE t.user_id = ? AND t.due_date < CURDATE() AND t.status != 'done' AND t.deleted_at IS NULL
             ORDER BY t.due_date ASC, t.priority ASC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getUpcoming($userId, $days = 7) {
        $stmt = $this->pdo->prepare(
            "SELECT t.*, p.name as project_name, p.color as project_color
             FROM tasks t
             JOIN projects p ON t.project_id = p.id
             WHERE t.user_id = ? 
               AND t.due_date > CURDATE() 
               AND t.due_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
               AND t.deleted_at IS NULL
             ORDER BY t.due_date ASC, t.priority ASC"
        );
        $stmt->execute([$userId, $days]);
        return $stmt->fetchAll();
    }

    public function getReminders($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT t.*, p.name as project_name, p.color as project_color
             FROM tasks t
             JOIN projects p ON t.project_id = p.id
             WHERE t.user_id = ? 
               AND t.remind_at <= NOW() 
               AND t.status != 'done' 
               AND t.deleted_at IS NULL
             ORDER BY t.remind_at ASC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getTrashed($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT t.*, p.name as project_name, p.color as project_color
             FROM tasks t
             JOIN projects p ON t.project_id = p.id
             WHERE t.user_id = ? AND t.deleted_at IS NOT NULL
             ORDER BY t.deleted_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function update($id, $userId, $data) {
        $fields = [];
        $params = [];

        $allowedFields = ['title', 'description', 'status', 'priority', 'due_date', 'remind_at', 'project_id'];
        
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE tasks SET " . implode(', ', $fields) . " WHERE id = ? AND user_id = ?";
        $params[] = $id;
        $params[] = $userId;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateStatus($id, $userId, $status) {
        $stmt = $this->pdo->prepare(
            "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ? AND deleted_at IS NULL"
        );
        return $stmt->execute([$status, $id, $userId]);
    }

    public function softDelete($id, $userId) {
        $stmt = $this->pdo->prepare(
            "UPDATE tasks SET deleted_at = NOW() WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }

    public function restore($id, $userId) {
        $stmt = $this->pdo->prepare(
            "UPDATE tasks SET deleted_at = NULL WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }

    public function permanentDelete($id, $userId) {
        $stmt = $this->pdo->prepare(
            "DELETE FROM tasks WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }
}
