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
                    $total = (int) $project['task_count'];
                    $done  = (int) $project['done_count'];
                    ?>
                    <div class="app-card home-feature-card">
                        <div class="home-feature-card__icon">📌</div>
                        <h3 class="home-feature-card__title"><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="home-feature-card__desc">
                            <?php if ($total === 0): ?>
                                Aucune tâche pour le moment.
                            <?php else: ?>
                                <?= $done ?> / <?= $total ?> tâche<?= $total > 1 ? 's' : '' ?> terminée<?= $done !== 1 ? 's' : '' ?>
                            <?php endif; ?>
                        </p>
                        <a class="app-btn app-btn--primary" href="/project?id=<?= (int) $project['id'] ?>">Ouvrir le board</a>
                    </div>
                <?php endforeach; ?>

                <?php if (count($recentProjects) >= 3): ?>
                    <div class="app-card home-feature-card">
                        <div class="home-feature-card__icon">＋</div>
                        <h3 class="home-feature-card__title">Voir tout</h3>
                        <p class="home-feature-card__desc">Retrouve l'ensemble de tes projets ou crées-en un nouveau.</p>
                        <a class="app-btn" href="/projects">Gérer mes projets</a>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </section>

<?php endif; ?>