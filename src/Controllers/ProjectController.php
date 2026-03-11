<?php

namespace App\Controllers;

use App\Http\AbstractController;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Services\Flash;
use App\Services\Csrf;

final class ProjectController extends AbstractController
{
    public function index(): string
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int) $_SESSION['user_id'];

        $projectRepo = new ProjectRepository();
        $taskRepo    = new TaskRepository(); // si tu as la méthode quick views

        $projects        = $projectRepo->findActiveWithStatsByUserId($userId);
        $sidebarProjects = $projectRepo->findSidebarByUserId($userId);

        $projectsCount = count($sidebarProjects);
        $tasksCount = 0;
        foreach ($sidebarProjects as $p) {
            $tasksCount += (int) ($p['task_count'] ?? 0);
        }

        $quick = $taskRepo->countQuickViewsByUserId($userId); // sinon mets le tableau par défaut

        return $this->render('pages/projects', [
            'pageTitle'       => 'Mes Projets',
            'projects'        => $projects,
            'sidebarProjects' => $sidebarProjects,
            'projectsCount'   => $projectsCount,
            'tasksCount'      => $tasksCount,
            'activeProjectId' => null,
            'quick'           => $quick,
        ]);
    }

    public function create(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        if (empty($_SESSION['user_id'])) {
            Flash::add('Vous devez être connecté pour créer un projet.', 'error');
            header('Location: /login');
            exit;
        }
        if (!isset($_POST['csrf_token']) || !Csrf::check($_POST['csrf_token'])) {
            Flash::add('Token CSRF invalide. Recharge la page.', 'error');
            header('Location: /projects');
            exit;
        }
        $name = trim((string)($_POST['name'] ?? ''));
        if ($name === '') {
            Flash::add('Le nom du projet est requis.', 'error');
            header('Location: /projects');
            exit;
        }
        if (mb_strlen($name, 'UTF-8') > 255) {
            Flash::add('Nom trop long (255 max).', 'error');
            header('Location: /projects');
            exit;
        }

        $repo = new ProjectRepository();
        $projectId = $repo->create((int)$_SESSION['user_id'], $name);
        Flash::add('Projet créé avec succès.', 'success');
        header('Location: /project?id=' . $projectId);
        exit;
    }

    public function show(): string
    {
        if (empty($_SESSION['user_id'])) {
            Flash::add('Vous devez être connecté pour accéder à ce projet.', 'error');
            header('Location: /login');
            exit;
        }
        $projectId = (int)($_GET['id'] ?? 0);
        if ($projectId <= 0) {
            Flash::add('ID de projet invalide.', 'error');
            header('Location: /projects');
            exit;
        }
        $repo = new ProjectRepository();
        $project = $repo->findByIdForUser($projectId, (int)$_SESSION['user_id']);
        if (!$project) {
            Flash::add('Projet non trouvé ou vous n\'avez pas accès à ce projet.', 'error');
            header('Location: /projects');
            exit;
        }
        $tasksRepo = new TaskRepository();
        $tasks = $tasksRepo->findActiveByProjectForUser($projectId, (int)$_SESSION['user_id']);

        $todo = [];
        $doing = [];
        $done = [];
        foreach ($tasks as $task) {
            $status = (int)$task['status'];
            switch ($status) {
                case 0:
                    $todo[] = $task;
                    break;
                case 1:
                    $doing[] = $task;
                    break;
                case 2:
                    $done[] = $task;
                    break;
            }
        }
        return $this->render('pages/project-task', [
            'pageTitle' => $project['name'],
            'title' => $project['name'],
            'project' => $project,
            'todo' => $todo,
            'doing' => $doing,
            'done' => $done,
        ]);
    }

    public function delete(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        if (empty($_SESSION['user_id'])) {
            Flash::add('Vous devez être connecté pour supprimer un projet.', 'error');
            header('Location: /login');
            exit;
        }
        if (!isset($_POST['csrf_token']) || !Csrf::check($_POST['csrf_token'])) {
            Flash::add('Token CSRF invalide. Recharge la page.', 'error');
            header('Location: /projects');
            exit;
        }
        $projectId = (int)($_POST['project_id'] ?? 0);
        if ($projectId <= 0) {
            Flash::add('ID de projet invalide.', 'error');
            header('Location: /projects');
            exit;
        }
        $repo = new ProjectRepository();
        $deleted = $repo->softDelete($projectId, (int)$_SESSION['user_id']);
        if ($deleted) {
            Flash::add('Projet supprimé avec succès.', 'success');
        } else {
            Flash::add('Projet non trouvé ou vous n\'avez pas accès à ce projet.', 'error');
        }
        header('Location: /projects');
        exit;
    }

    public function rename(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        if (empty($_SESSION['user_id'])) {
            Flash::add('Vous devez être connecté pour renommer un projet.', 'error');
            header('Location: /login');
            exit;
        }
        if (!isset($_POST['csrf_token']) || !Csrf::check($_POST['csrf_token'])) {
            Flash::add('Token CSRF invalide. Recharge la page.', 'error');
            header('Location: /projects');
            exit;
        }
        $projectId = (int)($_POST['project_id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        if ($projectId <= 0) {
            Flash::add('ID de projet invalide.', 'error');
            header('Location: /projects');
            exit;
        }
        if ($name === '') {
            Flash::add('Le nom du projet est requis.', 'error');
            header('Location: /projects');
            exit;
        }
        if (mb_strlen($name, 'UTF-8') > 255) {
            Flash::add('Nom trop long (255 max).', 'error');
            header('Location: /projects');
            exit;
        }

        $repo = new ProjectRepository();
        $updated = $repo->rename($projectId, (int)$_SESSION['user_id'], $name);

        if ($updated) {
            Flash::add('Projet renommé avec succès.', 'success');
        } else {
            Flash::add('Projet non trouvé ou déjà supprimé.', 'error');
        }
        header('Location: /project?id=' . (int)$projectId);
        exit;
    }
}
