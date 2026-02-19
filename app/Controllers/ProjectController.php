<?php

/**
 * Project Controller
 * CRUD operations for projects
 */

class ProjectController extends Controller {

    public function index() {
        $this->requireAuth();

        $projectModel = new Project();
        $projects = $projectModel->getAll(Auth::id());

        $this->view('projects/index', [
            'projects' => $projects,
            'user' => Auth::user()
        ]);
    }

    public function show($id) {
        $this->requireAuth();

        $projectModel = new Project();
        $taskModel = new Task();
        $userId = Auth::id();

        $project = $projectModel->findById($id, $userId);
        
        if (!$project) {
            Flash::set('error', 'Project not found');
            $this->redirect('/projects');
        }

        $tasks = $taskModel->getByProject($id, $userId);

        $this->view('projects/show', [
            'project' => $project,
            'tasks' => $tasks,
            'user' => Auth::user()
        ]);
    }

    public function create() {
        $this->requireAuth();
        
        $this->view('projects/create', [
            'user' => Auth::user()
        ]);
    }

    public function store() {
        $this->requireAuth();
        CSRF::verify();

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $color = $_POST['color'] ?? '#3498db';

        if (empty($name)) {
            Flash::set('error', 'Project name is required');
            setOld($_POST);
            $this->redirect('/projects/create');
        }

        $projectModel = new Project();
        $projectId = $projectModel->create(Auth::id(), $name, $description, $color);

        if ($projectId) {
            clearOld();
            Flash::set('success', 'Project created successfully');
            $this->redirect('/projects/' . $projectId);
        } else {
            Flash::set('error', 'Failed to create project');
            setOld($_POST);
            $this->redirect('/projects/create');
        }
    }

    public function edit($id) {
        $this->requireAuth();

        $projectModel = new Project();
        $project = $projectModel->findById($id, Auth::id());

        if (!$project) {
            Flash::set('error', 'Project not found');
            $this->redirect('/projects');
        }

        $this->view('projects/edit', [
            'project' => $project,
            'user' => Auth::user()
        ]);
    }

    public function update($id) {
        $this->requireAuth();
        CSRF::verify();

        $projectModel = new Project();
        $project = $projectModel->findById($id, Auth::id());

        if (!$project) {
            Flash::set('error', 'Project not found');
            $this->redirect('/projects');
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $color = $_POST['color'] ?? $project['color'];

        if (empty($name)) {
            Flash::set('error', 'Project name is required');
            setOld($_POST);
            $this->redirect('/projects/' . $id . '/edit');
        }

        if ($projectModel->update($id, Auth::id(), $name, $description, $color)) {
            clearOld();
            Flash::set('success', 'Project updated successfully');
            $this->redirect('/projects/' . $id);
        } else {
            Flash::set('error', 'Failed to update project');
            setOld($_POST);
            $this->redirect('/projects/' . $id . '/edit');
        }
    }

    public function delete($id) {
        $this->requireAuth();
        CSRF::verify();

        $projectModel = new Project();
        $project = $projectModel->findById($id, Auth::id());

        if (!$project) {
            Flash::set('error', 'Project not found');
            $this->redirect('/projects');
        }

        if ($projectModel->delete($id, Auth::id())) {
            Flash::set('success', 'Project deleted successfully');
        } else {
            Flash::set('error', 'Failed to delete project');
        }

        $this->redirect('/projects');
    }
}
