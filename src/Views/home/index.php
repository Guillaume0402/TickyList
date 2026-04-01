<?php

/** @var bool $isLogged */
/** @var array $recentProjects */
/** @var array $recentActivity */
/** @var array $stats */
/** @var string $userName */

$isLogged = $isLogged ?? false;
$recentProjects = $recentProjects ?? [];
$recentActivity = $recentActivity ?? [];
$stats = $stats ?? [];
$userName = $userName ?? 'Utilisateur';
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

    <?php
    $projectsInProgress = (int)($stats['projects_in_progress'] ?? count($recentProjects));
    $projectsLate = (int)($stats['projects_late'] ?? 0);
    $todayCount = (int)($stats['today'] ?? 0);
    $lateCount = (int)($stats['late'] ?? 0);
    $weeklyProgress = (int)($stats['weekly_progress'] ?? 0);
    ?>

    <div class="dashboard-grid">
        <header class="dashboard-hero app-card">
            <div class="dashboard-hero__main">
                <p class="dashboard-hero__eyebrow">Tableau de bord</p>
                <h1 class="dashboard-hero__title">Bon retour <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="dashboard-hero__subtitle">
                    Tu as <?= $projectsInProgress ?> projet<?= $projectsInProgress > 1 ? 's' : '' ?> en cours
                    et <?= $todayCount ?> tâche<?= $todayCount > 1 ? 's' : '' ?> à terminer aujourd'hui.
                </p>
                <div class="dashboard-hero__actions">
                    <a href="/projects" class="app-btn app-btn--primary">Créer une tâche</a>
                    <a href="/projects" class="app-btn">Créer un projet</a>
                </div>
            </div>

            <div class="dashboard-hero__stats">
                <article class="metric-card">
                    <span class="metric-card__icon">✓</span>
                    <span class="metric-card__value"><?= (int)($stats['done'] ?? 0) ?></span>
                    <span class="metric-card__label">Terminées</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__icon">◉</span>
                    <span class="metric-card__value"><?= (int)($stats['in_progress'] ?? 0) ?></span>
                    <span class="metric-card__label">En cours</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__icon">☼</span>
                    <span class="metric-card__value"><?= $todayCount ?></span>
                    <span class="metric-card__label">Aujourd'hui</span>
                </article>
                <article class="metric-card metric-card--alert">
                    <span class="metric-card__icon">!</span>
                    <span class="metric-card__value"><?= $lateCount ?></span>
                    <span class="metric-card__label">En retard</span>
                </article>
            </div>
        </header>

        <div class="dashboard-main">
            <section class="dashboard-projects">
                <div class="dashboard-section-header">
                    <h2 class="dashboard-section-title">Projets récents</h2>
                    <a href="/projects" class="app-btn">Voir tous</a>
                </div>

                <?php if (empty($recentProjects)): ?>
                    <div class="dashboard-empty-state app-card">
                        <h3>Commence ton premier projet</h3>
                        <p>Tu n'as pas encore de projet. Crée-en un pour structurer tes tâches.</p>
                        <a href="/projects" class="app-btn app-btn--primary">Créer un projet</a>
                    </div>
                <?php else: ?>
                    <div class="project-cards-grid">
                        <?php foreach ($recentProjects as $project): ?>
                            <?php
                            $total = (int)($project['task_count'] ?? 0);
                            $done = (int)($project['done_count'] ?? 0);
                            $pct = ($total > 0) ? (int)round(($done / $total) * 100) : 0;
                            $isLate = (int)($project['is_late'] ?? 0) === 1;
                            $isDone = $total > 0 && $done === $total;
                            $status = $isDone ? 'Terminé' : ($isLate ? 'En retard' : 'En cours');
                            $statusClass = $isDone ? 'done' : ($isLate ? 'late' : 'active');
                            $description = trim((string)($project['description'] ?? ''));
                            if ($description === '') {
                                $description = 'Projet actif - suivez les priorités et les prochaines tâches.';
                            }
                            ?>
                            <article class="project-card app-card">
                                <div class="project-card__header">
                                    <h3 class="project-card__title"><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <span class="project-card__status project-card__status--<?= $statusClass ?>"><?= $status ?></span>
                                </div>

                                <p class="project-card__description"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>

                                <div class="project-card__progressWrap" aria-label="Progression du projet">
                                    <div class="project-card__progressBar">
                                        <div class="project-card__progressFill" style="width: <?= $pct ?>%;"></div>
                                    </div>
                                    <div class="project-card__ratio"><?= $done ?>/<?= $total ?> tâches terminées</div>
                                </div>

                                <div class="project-card__footer">
                                    <span class="project-card__updated">Mis à jour le <?= date('d/m/Y', strtotime((string)$project['last_activity_at'])) ?></span>
                                    <a href="/project?id=<?= (int)$project['id'] ?>" class="app-btn app-btn--primary">Ouvrir</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <aside class="dashboard-activity app-card">
                <div class="dashboard-section-header">
                    <h2 class="dashboard-section-title">Activité récente</h2>
                </div>

                <?php if (empty($recentActivity)): ?>
                    <div class="dashboard-empty-state dashboard-empty-state--compact">
                        <p>Aucune activité récente. Crée un projet ou ajoute une tâche pour démarrer.</p>
                    </div>
                <?php else: ?>
                    <ul class="activity-list">
                        <?php foreach ($recentActivity as $item): ?>
                            <?php
                            $isDone = (int)($item['status'] ?? 0) === 2;
                            $verb = $isDone ? 'terminée' : 'mise à jour';
                            ?>
                            <li class="activity-item">
                                <p class="activity-item__title">Tâche "<?= htmlspecialchars((string)$item['title'], ENT_QUOTES, 'UTF-8') ?>" <?= $verb ?></p>
                                <p class="activity-item__meta">
                                    <?= htmlspecialchars((string)$item['project_name'], ENT_QUOTES, 'UTF-8') ?> ·
                                    <?= date('d/m H:i', strtotime((string)$item['updated_at'])) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </aside>
        </div>

        <section class="quick-view">
            <h2 class="quick-view__title">Vue rapide</h2>
            <div class="quick-view__grid">
                <article class="quick-widget app-card">
                    <h3 class="quick-widget__label">Tâches du jour</h3>
                    <p class="quick-widget__value"><?= $todayCount ?></p>
                    <p class="quick-widget__hint">À prioriser avant la fin de journée.</p>
                </article>

                <article class="quick-widget app-card">
                    <h3 class="quick-widget__label">Projets en retard</h3>
                    <p class="quick-widget__value"><?= $projectsLate ?></p>
                    <p class="quick-widget__hint">Concentre-toi sur les éléments bloquants.</p>
                </article>

                <article class="quick-widget app-card">
                    <h3 class="quick-widget__label">Progression hebdo</h3>
                    <p class="quick-widget__value"><?= $weeklyProgress ?>%</p>
                    <div class="quick-widget__bar">
                        <div class="quick-widget__barFill" style="width: <?= $weeklyProgress ?>%;"></div>
                    </div>
                </article>
            </div>
        </section>
    </div>

<?php endif; ?>