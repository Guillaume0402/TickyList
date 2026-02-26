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
}