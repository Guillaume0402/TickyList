<div class="projects-layout">

    <?php require __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- ── Main ─────────────────────────────────────────────────────────── -->
    <main>
        <div class="page-header">
            <div>
                <h1 class="page-header__title">Mes projets</h1>
                <p class="page-header__sub">3 projets actifs · 17 tâches au total</p>
            </div>
            <a href="#" class="app-btn app-btn--primary">+ Nouveau projet</a>
        </div>

        <div class="projects-grid">

            <?php
            foreach ($projects as $project):
                $progress = round(($project['task_count'] / 10) * 100); // Exemple de calcul de progression
            ?>
                <a class="project-card" href="/projects/<?= $project['id'] ?>">
                    <?php
                    $color = (!empty($project['color'])) ? $project['color'] : '#00e676';
                    ?>
                    <div class="project-card__color"
                        style="background:<?= htmlspecialchars($color, ENT_QUOTES, 'UTF-8') ?>20;color:<?= htmlspecialchars($color, ENT_QUOTES, 'UTF-8') ?>;">
                        <?= match ($project['name']) {
                            'EcoRide' => '🌿',
                            'Clients' => '👥',
                            'Perso' => '🏠',
                            default => '📁',
                        } ?>
                    </div>
                    <div>
                        <p class="project-card__name"><?= htmlspecialchars($project['name']) ?></p>
                        <p class="project-card__meta"><?= $project['task_count'] ?> tâches · modifié il y a 2 h</p>
                    </div>
                    <div class="project-card__progress">
                        <div class="progress-label">
                            <span>Progression</span>
                            <span><?= $project['task_count'] ?> / 10</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar__fill" style="width:<?= $progress ?>%"></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>

            <!-- Add new ─────────────────────────────────────────────────── -->
            <a class="project-card project-card--new" href="#">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Nouveau projet
            </a>

        </div>
    </main>

</div>