<div class="projects-layout">

    <?php require __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- ── Main ─────────────────────────────────────────────────────────── -->
    <main>
        <div class="page-header">
            <div>
                <h1 class="page-header__title">Mes projets</h1>
                <p class="page-header__sub">
                    <?= (int)$projectsCount ?> projets actifs · <?= (int)$tasksCount ?> tâches au total
                </p>
            </div>
        </div>
        <!-- Add new ─────────────────────────────────────────────────────── -->

        <div class="project-add">
            <form method="POST" action="/projects/create">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                <input type="text" name="name" required maxlength="255" placeholder="Nom du projet">
                <button type="submit" class="app-btn app-btn--primary">+ Nouveau projet</button>
            </form>
        </div>

        <div class="projects-grid">

            <?php
            foreach ($projects as $project):
                $progress = $project['task_count'] > 0 ? round($project['done_count'] / $project['task_count'] * 100) : 0;
            ?>
                <a class="project-card" href="/project?id=<?= (int)$project['id'] ?>">
                    <?php
                    $color = match ($project['name']) {
                        'EcoRide' => '#00e676',
                        'Clients' => '#448aff',
                        'Perso' => '#ff6d00',
                        default => '#9aa0a6',
                    };
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
                            <span><?= $project['done_count'] ?> / <?= $project['task_count'] ?></span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar__fill" style="width:<?= $progress ?>%"></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>

</div>