<?php
namespace App\Controllers;

use App\Http\AbstractController;

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

    public function register (): string
    {
        return $this->render('home/register', [
            'pageTitle' => 'Inscription',
            'title' => 'Inscription',
            'subtitle' => 'Page register OK',
        ]);
    }

    public function login (): string
    {
        return $this->render('auth/login', [
            'pageTitle' => 'Connexion',
            'title' => 'Connexion',
            'subtitle' => 'Page login OK',
        ]);
    }
}
