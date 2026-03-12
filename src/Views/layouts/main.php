<?php

use App\Services\Flash;
use App\Services\Csrf;

$flashes = Flash::pull();

$isLoggedIn  = !empty($_SESSION['user_id']);
$userEmail   = $_SESSION['user_email'] ?? '';
$userInitial = $userEmail !== '' ? strtoupper(mb_substr($userEmail, 0, 1, 'UTF-8')) : '?';

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'TickyList') ?></title>
    <link rel="stylesheet" href="/assets/app.css">
    <script src="/js/app.js" defer></script>
</head>

<body>

    <header class="header" id="siteHeader">
        <div class="app-container header__inner">

            <a class="header__brand" href="/">
                <span class="header__brand-icon" aria-hidden="true">✓</span>
                Ticky<span class="header__brand-accent">List</span>
            </a>

            <button class="header__toggle" id="navToggle"
                aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mainNav">
                <span></span><span></span><span></span>
            </button>

            <nav class="header__nav" id="mainNav" aria-label="Navigation principale">

                <a class="header__nav-link<?= $currentPath === '/' ? ' is-active' : '' ?>" href="/">Accueil</a>

                <?php if ($isLoggedIn): ?>
                    <a class="header__nav-link<?= str_starts_with($currentPath, '/projects') ? ' is-active' : '' ?>"
                        href="/projects">Mes projets</a>
                <?php endif; ?>

                <div class="header__nav-sep" aria-hidden="true"></div>

                <?php if (!$isLoggedIn): ?>
                    <a class="app-btn app-btn--ghost" href="/login">Connexion</a>
                    <a class="app-btn app-btn--primary" href="/register">Commencer&nbsp;&rarr;</a>
                <?php else: ?>
                    <div class="header__user" id="headerUser">
                        <button class="header__avatar" id="userMenuBtn"
                            aria-haspopup="true" aria-expanded="false"
                            title="<?= htmlspecialchars($userEmail) ?>">
                            <?= htmlspecialchars($userInitial) ?>
                        </button>
                        <div class="header__dropdown" id="userDropdown" aria-hidden="true">
                            <?php if ($userEmail !== ''): ?>
                                <div class="header__dropdown-email"><?= htmlspecialchars($userEmail) ?></div>
                            <?php endif; ?>
                            <form action="/logout" method="POST">
                                <input type="hidden" name="csrf_token"
                                    value="<?= htmlspecialchars(Csrf::token()) ?>">
                                <button type="submit" class="header__dropdown-logout">
                                    <span aria-hidden="true">⎋</span> Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

            </nav>
        </div>
    </header>

    <main class="app-container py-4">

        <?php if (!empty($flashes)): ?>
            <div class="flash-wrap">
                <?php foreach ($flashes as $f): ?>
                    <div class="flash flash--<?= htmlspecialchars($f['type']) ?>">
                        <?= htmlspecialchars($f['message']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>

    </main>

    <footer class="footer">
        <div class="footer__glow" aria-hidden="true"></div>
        <div class="app-container">
            <div class="footer__body">

                <div class="footer__col footer__col--brand">
                    <a class="footer__brand" href="/">
                        <span class="footer__brand-icon" aria-hidden="true">✓</span>
                        Ticky<span>List</span>
                    </a>
                    <p class="footer__tagline">
                        Capturez, organisez et accomplissez&nbsp;—<br>sans friction.
                    </p>
                </div>

                <div class="footer__col">
                    <p class="footer__col-title">Navigation</p>
                    <ul class="footer__links">
                        <li><a class="footer__link" href="/">Accueil</a></li>
                        <?php if ($isLoggedIn): ?>
                            <li><a class="footer__link" href="/projects">Mes projets</a></li>
                        <?php else: ?>
                            <li><a class="footer__link" href="/register">Inscription</a></li>
                            <li><a class="footer__link" href="/login">Connexion</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="footer__col">
                    <p class="footer__col-title">Stack</p>
                    <ul class="footer__links">
                        <li><a class="footer__link" href="#">PHP MVC</a></li>
                        <li><a class="footer__link" href="#">MySQL</a></li>
                        <li><a class="footer__link" href="#">Docker</a></li>
                    </ul>
                </div>

            </div>

            <div class="footer__bottom">
                <small>&copy; <?= date('Y') ?> TickyList &mdash; Tous droits réservés.</small>
                <?php if ($isLoggedIn && $userEmail !== ''): ?>
                    <small class="footer__bottom-user">
                        Connecté en tant que <strong><?= htmlspecialchars($userEmail) ?></strong>
                    </small>
                <?php endif; ?>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>