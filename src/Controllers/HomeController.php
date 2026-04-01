<?php

namespace App\Controllers;

use App\Http\AbstractController;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Services\Csrf;
use App\Services\Flash;

final class HomeController extends AbstractController
{
    public function index(): string
    {
        $isLogged = !empty($_SESSION['user_id']);
        $recentProjects = [];
        $recentActivity = [];
        $stats = [
            'done' => 0,
            'in_progress' => 0,
            'today' => 0,
            'late' => 0,
            'projects_in_progress' => 0,
            'weekly_progress' => 0,
        ];
        $userName = $_SESSION['user_email'] ?? 'Utilisateur';

        if ($isLogged) {
            $userId = (int)$_SESSION['user_id'];
            $projectRepo = new ProjectRepository();
            $taskRepo = new TaskRepository();

            $recentProjects = $projectRepo->findRecentWithStatsByUserId($userId, 3);
            $stats = $taskRepo->getGlobalStatsByUserId($userId);
            $recentActivity = $taskRepo->getRecentActivityByUserId($userId, 5);

            if ($userName !== 'Utilisateur' && str_contains($userName, '@')) {
                $userName = explode('@', $userName)[0];
            }
        }

        return $this->render('home/index', [
            'isLogged' => $isLogged,
            'recentProjects' => $recentProjects,
            'recentActivity' => $recentActivity,
            'stats' => $stats,
            'userName' => $userName,
            'pageTitle' => 'Tableau de bord'
        ]);
    }

    public function about(): string
    {
        return $this->render('home/index', [
            'pageTitle' => 'À propos',
            'title' => 'À propos',
            'subtitle' => 'Page about OK',
        ]);
    }

    public function register(): string
    {
        if (!empty($_SESSION['user_id'])) {
            Flash::add('Vous êtes déjà connecté.', 'info');
            header('Location: /');
            exit;
        }

        return $this->render('auth/register', [
            'pageTitle' => 'Inscription',
            'title' => 'Inscription',
            'subtitle' => 'Page register OK',
            'csrfToken' => Csrf::token(),
            'error' => null,
            'old' => ['email' => ''],
        ]);
    }

    public function login(): string
    {
        if (!empty($_SESSION['user_id'])) {
            Flash::add('Vous êtes déjà connecté.', 'info');
            header('Location: /');
            exit;
        }

        return $this->render('auth/login', [
            'pageTitle' => 'Connexion',
            'title' => 'Connexion',
            'subtitle' => 'Page login OK',
            'csrfToken' => Csrf::token(),
            'error' => null,
            'old' => ['email' => ''],
        ]);
    }
}
