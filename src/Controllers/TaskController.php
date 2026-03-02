<?php

namespace App\Controllers;

use App\Http\AbstractController;
use App\Repositories\TaskRepository;
use App\Repositories\ProjectRepository;
use App\Services\Flash;
use App\Services\Csrf;



final class TaskController extends AbstractController
{
    public function create(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        if (empty($_SESSION['user_id'])) {
            Flash::add('Vous devez être connecté pour créer une tâche.', 'error');
            header('Location: /login');
            exit;
        }
        if (!isset($_POST['csrf_token']) || !Csrf::check($_POST['csrf_token'])) {
            Flash::add('Token CSRF invalide. Recharge la page.', 'error');
            header('Location: /projects');
            exit;
        }

        $projectId = (int)($_POST['project_id'] ?? 0);
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $description = ($description === '') ? null : $description;

        if ($projectId <= 0) {
            Flash::add('ID de projet invalide.', 'error');
            header('Location: /projects');
            exit;
        }
        if ($title === '') {
            Flash::add('Le titre de la tâche est requis.', 'error');
            header('Location: /project?id=' . $projectId);
            exit;
        }
        if (mb_strlen($title, 'UTF-8') > 255) {
            Flash::add('Titre trop long (255 max).', 'error');
            header('Location: /project?id=' . $projectId);
            exit;
        }
        if ($description !== null && mb_strlen($description, 'UTF-8') > 1000) {
            Flash::add('Description trop longue (1000 max).', 'error');
            header('Location: /project?id=' . $projectId);
            exit;
        }
        $projectRepo = new ProjectRepository();
        $project = $projectRepo->findByIdForUser($projectId, (int)$_SESSION['user_id']);

        if (!$project) {
            Flash::add('Ce projet n’existe pas ou ne vous appartient pas.', 'error');
            header('Location: /projects');
            exit;
        }

        $repo = new TaskRepository();
        $taskId = $repo->create($projectId, $title, $description);
        Flash::add('Tâche créée avec succès.', 'success');
        header('Location: /project?id=' . $projectId);
        exit;
    }

    public function delete(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        if (empty($_SESSION['user_id'])) {
            Flash::add('Vous devez être connecté pour supprimer une tâche.', 'error');
            header('Location: /login');
            exit;
        }
        if (!isset($_POST['csrf_token']) || !Csrf::check($_POST['csrf_token'])) {
            Flash::add('Token CSRF invalide. Recharge la page.', 'error');
            header('Location: /projects');
            exit;
        }

        $taskId = (int)($_POST['task_id'] ?? 0);

        if ($taskId <= 0) {
            Flash::add('ID de tâche invalide.', 'error');
            header('Location: /projects');
            exit;
        }

        $projectId = (int)($_POST['project_id'] ?? 0);
        if ($projectId <= 0) {
            Flash::add('ID de projet invalide.', 'error');
            header('Location: /projects');
            exit;
        }

        $repo = new TaskRepository();
        $deleted = $repo->softDeleteForUser($taskId, (int)$_SESSION['user_id']);

        if ($deleted) {
            Flash::add('Tâche supprimée avec succès.', 'success');
        } else {
            Flash::add('Tâche introuvable ou non autorisée.', 'error');
        }

        header('Location: /project?id=' . $projectId);
        exit;
    }

    public function updateStatus(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        if (empty($_SESSION['user_id'])) {
            Flash::add('Vous devez être connecté pour mettre à jour une tâche.', 'error');
            header('Location: /login');
            exit;
        }
        if (!isset($_POST['csrf_token']) || !Csrf::check($_POST['csrf_token'])) {
            Flash::add('Token CSRF invalide. Recharge la page.', 'error');
            header('Location: /projects');
            exit;
        }

        $taskId = (int)($_POST['task_id'] ?? 0);
        $newStatus = (int)($_POST['status'] ?? -1);
        $projectId = (int)($_POST['project_id'] ?? 0);

        if ($taskId <= 0 || $projectId <= 0 || !in_array($newStatus, [0, 1, 2], true)) {
            Flash::add('Données invalides pour la mise à jour de la tâche.', 'error');
            header('Location: /projects');
            exit;
        }

        $repo = new TaskRepository();
        $updated = $repo->updateStatusForUser($taskId, $newStatus, (int)$_SESSION['user_id']);

        if ($updated) {
            Flash::add('Tâche mise à jour avec succès.', 'success');
        } else {
            Flash::add('Tâche introuvable ou non autorisée.', 'error');
        }

        header('Location: /project?id=' . $projectId);
        exit;
    }
}
