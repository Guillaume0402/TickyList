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
        $email = trim($_POST['email'] ?? '');
        $token = $_POST['csrf_token'] ?? '';
        if (!Csrf::check($token)) {
            return $this->render('auth/login', [
                'pageTitle' => 'Connexion',
                'title' => 'Connexion',
                'error' => 'Token CSRF invalide. Recharge la page.',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
            ]);
        }

        // 2) Récupérer / nettoyer les champs        
        $password = $_POST['password'] ?? '';

        // (optionnel mais utile) : validation minimum
        if ($email === '' || $password === '') {
            return $this->render('auth/login', [
                'pageTitle' => 'Connexion',
                'title' => 'Connexion',
                'error' => 'Veuillez remplir tous les champs.',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
            ]);
        }

        // 3) Chercher l'utilisateur
        $repo = new UserRepository();
        $user = $repo->findByEmail($email);

        // 4) Vérifier le mot de passe
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return $this->render('auth/login', [
                'pageTitle' => 'Connexion',
                'title' => 'Connexion',
                'error' => 'Email ou mot de passe incorrect.',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
            ]);
        }

        // 5) Login OK -> session
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];

        Flash::add('Connexion réussie !', 'success');
        header('Location: /');
        exit;
    }

    public function logout(): void
    {
        // CSRF (POST obligatoire)
        $token = $_POST['csrf_token'] ?? '';
        if (!Csrf::check($token)) {
            // option simple : on renvoie sur accueil
            Flash::add('Token CSRF invalide.', 'error');
            header('Location: /');
            exit;
        }

        // On vide la session
        $_SESSION = [];

        // On détruit le cookie de session si existant (propre)
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

        Flash::add('👋 Déconnexion réussie.', 'success');
        header('Location: /login');
        exit;
    }

    public function registerPost(): string
    {      
        // 1) CSRF d'abord
        $email = trim($_POST['email'] ?? '');
        $token = $_POST['csrf_token'] ?? '';
        if (!Csrf::check($token)) {
            return $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'title' => 'Inscription',
                'error' => 'Token CSRF invalide. Recharge la page.',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
                
            ]);
        }

        // 2) Inputs        
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // 3) Validations
        if ($email === '' || $password === '' || $passwordConfirm === '') {
            return $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'title' => 'Inscription',
                'error' => 'Veuillez remplir tous les champs.',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
            ]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'title' => 'Inscription',
                'error' => 'Email invalide.',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
            ]);
        }

        if ($password !== $passwordConfirm) {
            return $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'title' => 'Inscription',
                'error' => 'Les mots de passe ne correspondent pas.',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
            ]);
        }

        if (strlen($password) < 8) {
            return $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'title' => 'Inscription',
                'error' => 'Mot de passe trop court (8 caractères minimum).',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
            ]);
        }

        // 4) Email déjà utilisé ?
        $repo = new UserRepository();
        if ($repo->findByEmail($email)) {
            return $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'title' => 'Inscription',
                'error' => 'Cet email est déjà utilisé.',
                'csrfToken' => Csrf::token(),
                'old' => ['email' => $email],
            ]);
        }

        // 5) Create user
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $repo->create($email, $hash);

        // 6) Auto-login + flash
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;

        Flash::add('✅ Compte créé ! Bienvenue 👋', 'success');
        header('Location: /');
        exit;
    }
}
