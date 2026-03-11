<?php

namespace App\Http;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;

abstract class AbstractController
{
    protected function render(string $view, array $params = [], string $layout = 'layouts/main'): string
    {
        $viewFile = ROOT . '/src/Views/' . $view . '.php';
        $layoutFile = ROOT . '/src/Views/' . $layout . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            return "Vue introuvable: " . htmlspecialchars($viewFile, ENT_QUOTES, 'UTF-8');
        }

        if (!is_file($layoutFile)) {
            http_response_code(500);
            return "Layout introuvable: " . htmlspecialchars($layoutFile, ENT_QUOTES, 'UTF-8');
        }

        // ── Sidebar shared data (only if logged) ─────────────────────────────
        $sidebarProjects = [];
        $quick = ['today' => 0, 'late' => 0, 'upcoming' => 0];
        $activeProjectId = null;

        // active project id for /project?id=XX
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $activeProjectId = (int) $_GET['id'];
        }

        if (!empty($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];

            $projectRepo = new ProjectRepository();
            $taskRepo = new TaskRepository();

            $sidebarProjects = $projectRepo->findSidebarByUserId($userId);
            $quick = $taskRepo->countQuickViewsByUserId($userId);
        }

        // IMPORTANT: avoid collision with page $projects
        $params = array_merge($params, [
            'sidebarProjects' => $sidebarProjects,
            'quick' => $quick,
            'activeProjectId' => $activeProjectId,
        ]);

        // 1) Render de la vue -> $content
        extract($params, EXTR_SKIP);
        ob_start();
        require $viewFile;
        $content = (string) ob_get_clean();

        // 2) Render du layout -> output final
        ob_start();
        require $layoutFile;
        return (string) ob_get_clean();
    }
}
