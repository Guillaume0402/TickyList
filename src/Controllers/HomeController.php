<?php

namespace App\Controllers;

use App\Http\AbstractController;
use App\Services\Csrf;
use App\Services\Flash;

final class HomeController extends AbstractController
{
    public function index(): string
    {
        return $this->render('home/index', [
            'pageTitle' => 'Accueil',
            'title' => 'Accueil',
            'subtitle' => 'Routeur + layout OK',
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

   

    public function projectTasks(): string
    {
        return $this->render('pages/project-task', [
            'pageTitle' => 'Projets & Tâches',
            'title' => 'Projets & Tâches',
            'subtitle' => 'Page projet-tasks OK',
        ]);
    }
}
