<div class="projects-layout">

    <?php

    /** @var array<int, array<string, mixed>> $tasks */
    /** @var string $viewTitle */
    /** @var string $quickViewType */

    require __DIR__ . '/../partials/sidebar.php';

    $viewTitle = $viewTitle ?? 'Vue rapide';
    $quickViewType = $quickViewType ?? 'today';
    $taskCount = count($tasks);

    $iconByType = [
        'today' => 'clock',
        'late' => 'alert',
        'upcoming' => 'calendar',
    ];
    $iconType = $iconByType[$quickViewType] ?? 'clock';

    $dateFormat = static function (?string $date): string {
        if ($date === null || $date === '') {
            return '-';
        }
        $dt = \DateTime::createFromFormat('Y-m-d', $date);
        if (!$dt) {
            return $date;
        }
        return $dt->format('d/m/Y');
    };

    ?>

    <main class="quick-view" data-quick-view="<?= htmlspecialchars($quickViewType, ENT_QUOTES, 'UTF-8') ?>">
        <div class="mobile-nav-bar">
            <button class="sidebar-toggle" id="sidebarToggle"
                aria-label="Ouvrir la navigation" aria-expanded="false" aria-controls="sidebar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <span class="mobile-nav-bar__breadcrumb"><?= htmlspecialchars($viewTitle, ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <header class="quick-view__hero">
            <div class="quick-view__heroLeft">
                <div class="quick-view__heroIcon" aria-hidden="true">
                    <?php if ($iconType === 'alert'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    <?php elseif ($iconType === 'calendar'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    <?php else: ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 class="quick-view__title"><?= htmlspecialchars($viewTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                    <p class="quick-view__subtitle">Vue dynamique basee sur les dates d'echeance</p>
                </div>
            </div>
            <div class="quick-view__count">
                <strong><?= (int)$taskCount ?></strong>
                <span><?= $taskCount > 1 ? 'taches' : 'tache' ?></span>
            </div>
        </header>

        <section class="quick-view__content">
            <?php if (empty($tasks)): ?>
                <div class="quick-view__empty">
                    <div class="quick-view__emptyIcon" aria-hidden="true">•</div>
                    <div>
                        <h2>Aucune tâche dans cette vue</h2>
                        <p>Ajoute ou modifie une date d'echeance sur tes tâches pour alimenter cette liste.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="quick-view__grid">
                    <?php foreach ($tasks as $task): ?>
                        <?php
                        $status = (int)($task['status'] ?? 0);
                        $statusText = match ($status) {
                            1 => 'En cours',
                            2 => 'Terminee',
                            default => 'A faire',
                        };
                        $statusClass = match ($status) {
                            1 => 'is-doing',
                            2 => 'is-done',
                            default => 'is-todo',
                        };
                        ?>
                        <a class="quick-task-card" href="/project?id=<?= (int)($task['project_id'] ?? 0) ?>">
                            <div class="quick-task-card__top">
                                <span class="quick-task-card__project"><?= htmlspecialchars((string)($task['project_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="quick-task-card__status <?= $statusClass ?>"><?= $statusText ?></span>
                            </div>
                            <h3 class="quick-task-card__title"><?= htmlspecialchars((string)($task['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if (!empty($task['description'])): ?>
                                <p class="quick-task-card__desc"><?= htmlspecialchars((string)$task['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                            <div class="quick-task-card__meta">
                                <span>Echeance: <?= htmlspecialchars($dateFormat((string)($task['due_date'] ?? '')), ENT_QUOTES, 'UTF-8') ?></span>
                                <span>Ouvrir le projet</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

</div>