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
        $token = $_POST['csrf_token'] ?? '';
        if (!Csrf::check($token)) {
            return $this->render('auth/login', [
                'pageTitle' => 'Connexion',
                'title' => 'Connexion',
                'error' => 'Token CSRF invalide. Recharge la page.',
                'csrfToken' => Csrf::token(),
            ]);
        }

        // 2) Récupérer / nettoyer les champs
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // (optionnel mais utile) : validation minimum
        if ($email === '' || $password === '') {
            return $this->render('auth/login', [
                'pageTitle' => 'Connexion',
                'title' => 'Connexion',
                'error' => 'Veuillez remplir tous les champs.',
                'csrfToken' => Csrf::token(),
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
}
