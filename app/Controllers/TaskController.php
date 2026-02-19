<?php

/**
 * Task Controller
 * CRUD operations for tasks including soft delete
 */

class TaskController extends Controller {

    public function index() {
        $this->requireAuth();

        $taskModel = new Task();
        $projectModel = new Project();
        $userId = Auth::id();

        // Get filter parameters
        $projectId = $_GET['project'] ?? null;
        $status = $_GET['status'] ?? null;
        $priority = $_GET['priority'] ?? null;
        $search = $_GET['search'] ?? null;

        $filters = array_filter([
            'project_id' => $projectId,
            'status' => $status,
            'priority' => $priority,
            'search' => $search
        ]);

        $tasks = $taskModel->search($userId, $filters);
        $projects = $projectModel->getAll($userId);

        $this->view('tasks/index', [
            'tasks' => $tasks,
            'projects' => $projects,
            'filters' => $filters,
            'user' => Auth::user()
        ]);
    }

    public function create() {
        $this->requireAuth();

        $projectModel = new Project();
        $projects = $projectModel->getAll(Auth::id());

        $projectId = $_GET['project'] ?? null;

        $this->view('tasks/create', [
            'projects' => $projects,
            'selectedProject' => $projectId,
            'user' => Auth::user()
        ]);
    }

    public function store() {
        $this->requireAuth();
        CSRF::verify();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $projectId = $_POST['project_id'] ?? null;
        $status = $_POST['status'] ?? 'todo';
        $priority = $_POST['priority'] ?? 2;
        $dueDate = $_POST['due_date'] ?? null;
        $remindAt = $_POST['remind_at'] ?? null;

        // Validation
        $errors = [];

        if (empty($title)) {
            $errors[] = 'Task title is required';
        }

        if (empty($projectId)) {
            $errors[] = 'Project is required';
        } else {
            // Verify project belongs to user
            $projectModel = new Project();
            $project = $projectModel->findById($projectId, Auth::id());
            if (!$project) {
                $errors[] = 'Invalid project';
            }
        }

        if (!empty($errors)) {
            Flash::set('error', implode('<br>', $errors));
            setOld($_POST);
            $this->redirect('/tasks/create');
        }

        // Create task
        $taskModel = new Task();
        $taskId = $taskModel->create([
            'user_id' => Auth::id(),
            'project_id' => $projectId,
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'priority' => $priority,
            'due_date' => $dueDate ?: null,
            'remind_at' => $remindAt ?: null
        ]);

        if ($taskId) {
            clearOld();
            Flash::set('success', 'Task created successfully');
            $this->redirect('/projects/' . $projectId);
        } else {
            Flash::set('error', 'Failed to create task');
            setOld($_POST);
            $this->redirect('/tasks/create');
        }
    }

    public function show($id) {
        $this->requireAuth();

        $taskModel = new Task();
        $task = $taskModel->findById($id, Auth::id());

        if (!$task) {
            Flash::set('error', 'Task not found');
            $this->redirect('/tasks');
        }

        $this->view('tasks/show', [
            'task' => $task,
            'user' => Auth::user()
        ]);
    }

    public function edit($id) {
        $this->requireAuth();

        $taskModel = new Task();
        $projectModel = new Project();
        
        $task = $taskModel->findById($id, Auth::id());

        if (!$task) {
            Flash::set('error', 'Task not found');
            $this->redirect('/tasks');
        }

        $projects = $projectModel->getAll(Auth::id());

        $this->view('tasks/edit', [
            'task' => $task,
            'projects' => $projects,
            'user' => Auth::user()
        ]);
    }

    public function update($id) {
        $this->requireAuth();
        CSRF::verify();

        $taskModel = new Task();
        $task = $taskModel->findById($id, Auth::id());

        if (!$task) {
            Flash::set('error', 'Task not found');
            $this->redirect('/tasks');
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $projectId = $_POST['project_id'] ?? null;
        $status = $_POST['status'] ?? 'todo';
        $priority = $_POST['priority'] ?? 2;
        $dueDate = $_POST['due_date'] ?? null;
        $remindAt = $_POST['remind_at'] ?? null;

        // Validation
        $errors = [];

        if (empty($title)) {
            $errors[] = 'Task title is required';
        }

        if (empty($projectId)) {
            $errors[] = 'Project is required';
        } else {
            $projectModel = new Project();
            $project = $projectModel->findById($projectId, Auth::id());
            if (!$project) {
                $errors[] = 'Invalid project';
            }
        }

        if (!empty($errors)) {
            Flash::set('error', implode('<br>', $errors));
            setOld($_POST);
            $this->redirect('/tasks/' . $id . '/edit');
        }

        // Update task
        $updated = $taskModel->update($id, Auth::id(), [
            'title' => $title,
            'description' => $description,
            'project_id' => $projectId,
            'status' => $status,
            'priority' => $priority,
            'due_date' => $dueDate ?: null,
            'remind_at' => $remindAt ?: null
        ]);

        if ($updated) {
            clearOld();
            Flash::set('success', 'Task updated successfully');
            $this->redirect('/projects/' . $projectId);
        } else {
            Flash::set('error', 'Failed to update task');
            setOld($_POST);
            $this->redirect('/tasks/' . $id . '/edit');
        }
    }

    public function delete($id) {
        $this->requireAuth();
        CSRF::verify();

        $taskModel = new Task();
        $task = $taskModel->findById($id, Auth::id());

        if (!$task) {
            Flash::set('error', 'Task not found');
            $this->redirect('/tasks');
        }

        if ($taskModel->softDelete($id, Auth::id())) {
            Flash::set('success', 'Task moved to trash');
        } else {
            Flash::set('error', 'Failed to delete task');
        }

        $this->redirect('/projects/' . $task['project_id']);
    }

    public function trash() {
        $this->requireAuth();

        $taskModel = new Task();
        $tasks = $taskModel->getTrashed(Auth::id());

        $this->view('tasks/trash', [
            'tasks' => $tasks,
            'user' => Auth::user()
        ]);
    }

    public function restore($id) {
        $this->requireAuth();
        CSRF::verify();

        $taskModel = new Task();
        
        if ($taskModel->restore($id, Auth::id())) {
            Flash::set('success', 'Task restored successfully');
        } else {
            Flash::set('error', 'Failed to restore task');
        }

        $this->redirect('/tasks/trash');
    }

    public function permanentDelete($id) {
        $this->requireAuth();
        CSRF::verify();

        $taskModel = new Task();
        
        if ($taskModel->permanentDelete($id, Auth::id())) {
            Flash::set('success', 'Task permanently deleted');
        } else {
            Flash::set('error', 'Failed to delete task');
        }

        $this->redirect('/tasks/trash');
    }

    public function toggleStatus($id) {
        $this->requireAuth();

        // This is for AJAX requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Invalid request method'], 405);
        }

        $taskModel = new Task();
        $task = $taskModel->findById($id, Auth::id());

        if (!$task) {
            $this->json(['error' => 'Task not found'], 404);
        }

        // Toggle between done and todo
        $newStatus = $task['status'] === 'done' ? 'todo' : 'done';
        
        if ($taskModel->updateStatus($id, Auth::id(), $newStatus)) {
            $this->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Task status updated'
            ]);
        } else {
            $this->json(['error' => 'Failed to update status'], 500);
        }
    }
}
