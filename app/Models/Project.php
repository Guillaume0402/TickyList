<?php

/**
 * Project Model
 */

class Project {
    private $pdo;

    public function __construct() {
        $this->pdo = getDbConnection();
    }

    public function create($userId, $name, $description = null, $color = '#3498db') {
        $stmt = $this->pdo->prepare(
            "INSERT INTO projects (user_id, name, description, color) VALUES (?, ?, ?, ?)"
        );
        
        $stmt->execute([$userId, $name, $description, $color]);
        return $this->pdo->lastInsertId();
    }

    public function findById($id, $userId) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM projects WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public function getAll($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, 
                    COUNT(DISTINCT t.id) as total_tasks,
                    COUNT(DISTINCT CASE WHEN t.status = 'done' AND t.deleted_at IS NULL THEN t.id END) as completed_tasks
             FROM projects p
             LEFT JOIN tasks t ON p.id = t.project_id AND t.deleted_at IS NULL
             WHERE p.user_id = ?
             GROUP BY p.id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function update($id, $userId, $name, $description = null, $color = null) {
        $sql = "UPDATE projects SET name = ?, description = ?";
        $params = [$name, $description];
        
        if ($color !== null) {
            $sql .= ", color = ?";
            $params[] = $color;
        }
        
        $sql .= " WHERE id = ? AND user_id = ?";
        $params[] = $id;
        $params[] = $userId;
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare(
            "DELETE FROM projects WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }

    public function getTaskCount($projectId, $userId) {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM tasks WHERE project_id = ? AND user_id = ? AND deleted_at IS NULL"
        );
        $stmt->execute([$projectId, $userId]);
        return $stmt->fetchColumn();
    }
}
