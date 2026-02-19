# Template PHP + MySQL (Docker) — Router MVC + Bootstrap SCSS

Template réutilisable pour démarrer rapidement des projets PHP (vanilla) avec :
- PHP 8.3 + Apache (Docker)
- MySQL 8.0
- phpMyAdmin
- Mailpit
- Routeur MVC simple (Front Controller)
- SCSS + Bootstrap (compilation vers `public/assets/app.css`)

---

## 1) Pré-requis

- Docker + Docker Compose
- Node.js + npm (pour SCSS/Bootstrap)

Vérification :

```bash
docker --version
docker compose version
node -v
npm -v
```

---

## 2) Créer un nouveau projet (méthode officielle)

Ce template est conçu pour être dupliqué via un script automatique.

### Emplacement attendu

- Template : `~/Projets/_template-php-mysql`
- Script : `new-project.sh` (lancé depuis l’endroit où tu l’as stocké)

### Créer un projet

```bash
./new-project.sh nom-du-projet
```

Le script :
- copie le template vers `~/Projets/nom-du-projet`
- crée `.env` à partir de `.env.example`
- trouve un bloc de ports libres (web/pma/db/mailpit)
- injecte les ports + `COMPOSE_PROJECT_NAME` dans `.env`
- affiche les URLs finales

---

## 3) Démarrer le projet

Après création :

```bash
cd ~/Projets/nom-du-projet
npm install
npm run dev
```

Dans un autre terminal (ou après) :

```bash
docker compose up -d --build
```

Accès (les ports exacts sont affichés par `new-project.sh`) :
- App : `http://localhost:WEB_PORT`
- phpMyAdmin : `http://localhost:PMA_PORT`
- Mailpit : `http://localhost:MAILPIT_UI_PORT`

---

## 4) Structure du projet

### Entrée unique (Front Controller)
- `public/index.php`
- `public/.htaccess`

### Routes
- `routes/web.php`

### Controllers
- `src/Controllers/`

### Core (infrastructure réutilisable)
- `src/Core/Router.php`
- `src/Core/AbstractController.php`

### Views
- Layout : `src/Views/layouts/main.php`
- Pages : `src/Views/...`

### Styles
- SCSS source : `assets/scss/app.scss` (+ dossiers)
- CSS généré : `public/assets/app.css`
- Bootstrap est importé via SCSS (npm)

---

## 5) Ajouter une page (route + controller + vue)

### Route
Dans `routes/web.php` :

```php
$router->get('/contact', [ContactController::class, 'index']);
```

### Controller
Créer `src/Controllers/ContactController.php` :

```php
<?php
namespace App\Controllers;

use App\Core\AbstractController;

final class ContactController extends AbstractController
{
    public function index(): string
    {
        return $this->render('contact/index', [
            'pageTitle' => 'Contact',
            'title' => 'Contact',
        ]);
    }
}
```

### Vue
Créer `src/Views/contact/index.php` :

```php
<h1><?= htmlspecialchars($title) ?></h1>
<p>Page contact</p>
```

---

## 6) Base de données

Dans Docker (depuis le container web), MySQL est accessible via :
- host : `db`
- port : `3306`
- db/user/password : via `.env`

Init SQL :
- placer les scripts dans `docker/mysql/initdb/`
- exécutés au premier démarrage du volume MySQL

---

## 7) Commandes utiles

Logs web :

```bash
docker compose logs -f web
```

Stop (garde la DB) :

```bash
docker compose down
```

Reset complet (supprime DB/volumes) :

```bash
docker compose down -v --remove-orphans
```

---

## 8) Problèmes fréquents

### “Controller introuvable”
Sous Linux, attention à la casse :
- `src/Controllers` (pluriel)
- `src/Core`
- `src/Views`

### SCSS ne compile pas / Bootstrap introuvable
- vérifier `npm install`
- build test :

```bash
npm run build
```

### Conflit de ports
Le script choisit des ports libres, mais si tu modifies `.env` à la main :

```bash
ss -ltnp | grep -E ':PORT'
```

---

## 9) Notes

- Ne jamais commit `.env` (utiliser `.env.example`)
- `COMPOSE_PROJECT_NAME` isole conteneurs/volumes par projet