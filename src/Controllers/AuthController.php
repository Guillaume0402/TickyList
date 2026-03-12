<?php

namespace App\Controllers;

use App\Http\AbstractController;
use App\Services\Csrf;
use App\Repositories\UserRepository;
use App\Services\Flash;

final class AuthController extends AbstractController
{
    public function loginPost(): string
    {
        // 1) CSRF d'abord (toujours sur un POST)
        $email = mb_strtolower(trim($_POST['email'] ?? ''), 'UTF-8');
        $token = $_POST['csrf_token'] ?? '';
        if (!Csrf::check($token)) {
            return $this->renderLoginError('Token CSRF invalide. Recharge la page.', $email);
        }

        // 2) Récupérer / nettoyer les champs        
        $password = $_POST['password'] ?? '';

        // (optionnel mais utile) : validation minimum
        if ($email === '' || $password === '') {
            return $this->renderLoginError('Veuillez remplir tous les champs.', $email);
        }

        // 3) Chercher l'utilisateur
        $repo = new UserRepository();
        $user = $repo->findByEmail($email);

        // 4) Vérifier le mot de passe
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return $this->renderLoginError('Email ou mot de passe incorrect.', $email);
        }

        // 5) Login OK -> session
        session_regenerate_id(true);
        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['user_email'] = $user['email'];

        Flash::add('Connexion réussie !', 'success');
        header('Location: /');
        exit;
    }
    public function logout(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!Csrf::check($token)) {
            Flash::add('Token CSRF invalide.', 'error');
            header('Location: /');
            exit;
        }

        $flashMessage = ['message' => 'Déconnexion réussie.', 'type' => 'success'];

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        session_start();
        session_regenerate_id(true);

        $_SESSION['flash'] = $_SESSION['flash'] ?? [];
        $_SESSION['flash'][] = $flashMessage;

        header('Location: /login');
        exit;
    }

    public function registerPost(): string
    {
        // 1) CSRF d'abord
        $email = mb_strtolower(trim($_POST['email'] ?? ''), 'UTF-8');
        $token = $_POST['csrf_token'] ?? '';
        if (!Csrf::check($token)) {
            return $this->renderRegisterError('Token CSRF invalide. Recharge la page.', $email);
        }

        // 2) Inputs        
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // 3) Validations
        if ($email === '' || $password === '' || $passwordConfirm === '') {
            return $this->renderRegisterError('Veuillez remplir tous les champs.', $email);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->renderRegisterError('Email invalide.', $email);
        }

        if ($password !== $passwordConfirm) {
            return $this->renderRegisterError('Les mots de passe ne correspondent pas.', $email);
        }

        if (strlen($password) < 8) {
            return $this->renderRegisterError('Mot de passe trop court (8 caractères minimum).', $email);
        }

        // 4) Email déjà utilisé ?
        $repo = new UserRepository();
        if ($repo->findByEmail($email)) {
            return $this->renderRegisterError('Cet email est déjà utilisé.', $email);
        }

        // 5) Create user
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $repo->create($email, $hash);

        // 6) Auto-login + flash
        session_regenerate_id(true);
        $_SESSION['user_id']    = $userId;
        $_SESSION['user_email'] = $email;

        Flash::add('✅ Compte créé ! Bienvenue 👋', 'success');
        header('Location: /');
        exit;
    }

    private function renderLoginError(string $message, string $email): string
    {
        return $this->render('auth/login', [
            'pageTitle' => 'Connexion',
            'title' => 'Connexion',
            'error' => $message,
            'csrfToken' => Csrf::token(),
            'old' => ['email' => $email],
        ]);
    }

    private function renderRegisterError(string $message, string $email): string
    {
        return $this->render('auth/register', [
            'pageTitle' => 'Inscription',
            'title' => 'Inscription',
            'error' => $message,
            'csrfToken' => Csrf::token(),
            'old' => ['email' => $email],
        ]);
    }
}
