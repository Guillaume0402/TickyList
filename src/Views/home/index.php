<?php

/** @var bool $isLogged */
/** @var array $recentProjects */

$isLogged = $isLogged ?? false;
$recentProjects = $recentProjects ?? [];
?>

<?php if (!$isLogged): ?>

    <section class="home-hero">
        <div class="home-hero__content">
            <span class="home-hero__badge">Productivité simplifiée</span>
            <h1 class="home-hero__title">Organisez vos tâches,<br>libérez votre esprit.</h1>
            <p class="home-hero__desc">
                TickyList vous aide à capturer, organiser et accomplir tout ce que vous avez à faire,
                sans friction.
            </p>
            <div class="home-hero__actions">
                <a class="app-btn app-btn--primary home-hero__cta" href="/register">Commencer gratuitement</a>
                <a class="app-btn home-hero__secondary" href="/login">Se connecter</a>
            </div>
        </div>
    </section>

    <section class="home-features">
        <h2 class="home-features__title">Pourquoi TickyList ?</h2>
        <div class="home-features__grid">
            <div class="app-card home-feature-card">
                <div class="home-feature-card__icon">✓</div>
                <h3 class="home-feature-card__title">Simple &amp; rapide</h3>
                <p class="home-feature-card__desc">Ajoutez une tâche en quelques secondes. Aucune configuration.</p>
            </div>
            <div class="app-card home-feature-card">
                <div class="home-feature-card__icon">◎</div>
                <h3 class="home-feature-card__title">Focalisé</h3>
                <p class="home-feature-card__desc">Une interface épurée qui vous garde concentré.</p>
            </div>
            <div class="app-card home-feature-card">
                <div class="home-feature-card__icon">⟳</div>
                <h3 class="home-feature-card__title">Toujours disponible</h3>
                <p class="home-feature-card__desc">Vos listes accessibles depuis n’importe quel appareil.</p>
            </div>
        </div>
    </section>

    <section class="home-cta app-card">
        <h2 class="home-cta__title">Prêt à vous organiser ?</h2>
        <p class="home-cta__desc">Créez votre compte en quelques secondes, c'est gratuit.</p>
        <a class="app-btn app-btn--primary home-cta__btn" href="/register">Créer mon compte</a>
    </section>

<?php else: ?>

    <section class="home-hero">
        <div class="home-hero__content">
            <span class="home-hero__badge">Bienvenue</span>
            <h1 class="home-hero__title">On reprend où tu t'étais arrêté ?</h1>
            <p class="home-hero__desc">
                Accède à tes projets, continue tes tâches, et garde ton board à jour.
            </p>
            <div class="home-hero__actions">
                <a class="app-btn app-btn--primary home-hero__cta" href="/projects">Tous mes projets</a>
            </div>
        </div>
    </section>

    <section class="home-features">
        <h2 class="home-features__title">Projets récents</h2>
        <div class="home-features__grid">

            <?php if (empty($recentProjects)): ?>

                <div class="app-card home-feature-card">
                    <div class="home-feature-card__icon">📋</div>
                    <h3 class="home-feature-card__title">Aucun projet pour l'instant</h3>
                    <p class="home-feature-card__desc">Crée ton premier projet pour commencer à organiser tes tâches.</p>
                    <a class="app-btn app-btn--primary" href="/projects">Créer un projet</a>
                </div>

            <?php else: ?>

                <?php foreach ($recentProjects as $project): ?>
                    <?php
                    $total  = (int)($project['task_count'] ?? 0);
                    $done   = (int)($project['done_count'] ?? 0);
                    $remain = max(0, $total - $done);
                    $pct    = ($total > 0) ? (int) round(($done / $total) * 100) : 0;
                    ?>

                    <div class="app-card home-project-card">
                        <div class="home-project-card__top">
                            <div class="home-project-card__icon">📌</div>
                            <div class="home-project-card__head">
                                <h3 class="home-project-card__title"><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></h3>

                                <?php if ($total === 0): ?>
                                    <p class="home-project-card__meta">Aucune tâche pour le moment.</p>
                                <?php else: ?>
                                    <p class="home-project-card__meta">
                                        <?= $done ?> terminée<?= $done !== 1 ? 's' : '' ?> ·
                                        <?= $remain ?> restante<?= $remain !== 1 ? 's' : '' ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="home-project-card__progress" aria-label="Progression">
                            <div class="home-project-card__bar">
                                <div class="home-project-card__barFill" style="width: <?= $pct ?>%"></div>
                            </div>
                            <div class="home-project-card__pct"><?= $pct ?>%</div>
                        </div>

                        <div class="home-project-card__actions">
                            <a class="app-btn app-btn--primary" href="/project?id=<?= (int)$project['id'] ?>">Ouvrir</a>
                            <a class="app-btn" href="/projects">Créer un projet</a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!empty($recentProjects)): ?>
                    <div class="app-card home-project-card home-project-card--create">
                        <div class="home-project-card__top">
                            <div class="home-project-card__icon">＋</div>
                            <div class="home-project-card__head">
                                <h3 class="home-project-card__title">Créer / voir tous</h3>
                                <p class="home-project-card__meta">Nouveau projet ou accès à la liste complète.</p>
                            </div>
                        </div>

                        <div class="home-project-card__actions">
                            <a class="app-btn app-btn--primary" href="/projects">Créer un projet</a>
                            <a class="app-btn" href="/projects">Voir tous</a>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </section>

<?php endif; ?>