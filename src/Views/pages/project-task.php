<div class="projects-layout">

    <?php

    /** @var array<int, array<string, mixed>> $todo */
    /** @var array<int, array<string, mixed>> $doing */
    /** @var array<int, array<string, mixed>> $done */
    /** @var array<string, mixed> $project */

    require __DIR__ . '/../partials/sidebar.php';

    $todoCount     = count($todo);
    $doingCount    = count($doing);
    $doneCount     = count($done);
    $total         = $todoCount + $doingCount + $doneCount;
    $remainingCount = $todoCount + $doingCount;

    ?>

    <!-- ── Main ─────────────────────────────────────────────────────────── -->
    <main data-project-id="<?= (int)$project['id'] ?>">

        <div class="mobile-nav-bar">
            <button class="sidebar-toggle" id="sidebarToggle"
                aria-label="Ouvrir la navigation" aria-expanded="false" aria-controls="sidebar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <span class="mobile-nav-bar__breadcrumb"><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <!-- ── Project header ──────────────────────────────────────────── -->
        <div class="project-header">

            <!-- Title + stats -->
            <div class="project-header__left">
                <div class="project-header__icon">🌿</div>
                <div>
                    <h1 class="project-header__title"><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></h1>
                    <div class="project-header__meta">
                        <span class="project-header__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 11 12 14 22 4" />
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                            </svg>
                            <span id="count-done"><?= (int) $doneCount ?></span> terminées
                        </span>
                        <span class="project-header__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            <span id="count-remaining"><?= (int) $remainingCount ?></span> restantes
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions : nouvelle tâche + gestion projet -->
            <div class="project-header__actions">
                <button
                    type="button"
                    class="app-btn app-btn--primary"
                    id="btn-add-task"
                    aria-expanded="false"
                    aria-controls="panel-add-task">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Nouvelle tâche
                </button>

                <div class="project-btn">
                    <!-- Rename -->
                    <div class="project-rename">
                        <form method="POST" action="/projects/rename">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                            <input
                                type="text"
                                name="name"
                                class="app-input app-input--sm"
                                required
                                maxlength="255"
                                value="<?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?>"
                                autocomplete="off">
                            <button type="submit" class="app-btn app-btn--secondary">Renommer</button>
                        </form>
                    </div>
                    <!-- Delete -->
                    <div class="project-delete">
                        <form method="POST" action="/projects/delete">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                            <button type="submit" class="app-btn app-btn--danger">Supprimer le projet</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- ── Add-task panel (toggle) ──────────────────────────────────── -->
        <div class="task-form-panel" id="panel-add-task" hidden>
            <form action="/tasks/create" method="post" class="task-form-panel__form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">

                <div class="task-form-panel__fields">
                    <div class="app-field">
                        <label for="task-title">Titre</label>
                        <input
                            type="text"
                            id="task-title"
                            name="title"
                            class="app-input"
                            required
                            maxlength="255"
                            placeholder="Nom de la tâche…"
                            autocomplete="off"
                            autofocus>
                    </div>
                    <div class="app-field">
                        <label for="task-desc">Description <span class="app-field__optional">(optionnelle)</span></label>
                        <textarea
                            id="task-desc"
                            name="description"
                            class="app-input app-textarea"
                            maxlength="1000"
                            placeholder="Quelques détails…"
                            rows="3"
                            autocomplete="off"></textarea>
                    </div>
                    <div class="app-field">
                        <label for="task-due-date">Date d'echeance <span class="app-field__optional">(optionnelle)</span></label>
                        <input
                            type="date"
                            id="task-due-date"
                            name="due_date"
                            class="app-input">
                    </div>
                </div>

                <div class="task-form-panel__footer">
                    <button type="submit" class="app-btn app-btn--primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Ajouter
                    </button>
                    <button type="button" class="app-btn" id="btn-cancel-task">Annuler</button>
                </div>
            </form>
        </div>


        <!-- ── Filter tabs ──────────────────────────────────────────────── -->
        <div class="task-filters">
            <button class="task-filter-btn is-active" data-filter="all">Tout (<?= $total ?>)</button>
            <button class="task-filter-btn" data-filter="todo">À faire (<?= $todoCount ?>)</button>
            <button class="task-filter-btn" data-filter="doing">En cours (<?= $doingCount ?>)</button>
            <button class="task-filter-btn" data-filter="done">Terminé (<?= $doneCount ?>)</button>
            <div style="flex:1"></div>
            <button class="task-filter-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                Aujourd'hui
            </button>
            <button class="task-filter-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
                En retard
            </button>
            <button class="task-filter-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                À venir
            </button>
        </div>


        <!-- ── Section : $todo ────────────────────────────────────────── -->
        <section class="task-section" data-section="todo">
            <p class="task-section__label">À faire</p>
            <div class="task-list" id="list-todo">
                <?php if (empty($todo)): ?>
                    <div class="task-card task-card--todo task-card--empty">
                        <div class="task-body">
                            <p class="task-title">Aucune tâche à faire</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($todo as $task): ?>
                        <div class="task-card task-card--todo" data-task-id="<?= (int)$task['id'] ?>">
                            <div class="task-status-col">
                                <form class="js-status-form js-to-doing" action="/tasks/status-ajax" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= (int)$task['id'] ?>">
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" class="task-complete-btn" aria-label="Démarrer">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 6L9 17l-5-5" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="task-body">
                                <p class="task-title"><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-desc"><?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                                <div class="task-chips">
                                    <span class="chip chip--status-todo">À faire</span>
                                </div>
                            </div>
                            <div class="task-card__actions">
                                <button
                                    type="button"
                                    class="task-edit-btn"
                                    aria-label="Modifier la tâche"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit-task"
                                    data-task-id="<?= (int)$task['id'] ?>"
                                    data-task-title="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-task-desc="<?= htmlspecialchars($task['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-task-due-date="<?= htmlspecialchars($task['due_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <form action="/tasks/delete" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= (int)$task['id'] ?>">
                                    <button type="submit" class="task-delete-btn" aria-label="Supprimer la tâche">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="add-task-row" id="add-task-shortcut">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Ajouter une tâche
            </div>
        </section>


        <!-- ── Section : $doing ───────────────────────────────────────── -->
        <section class="task-section" data-section="doing">
            <p class="task-section__label">En cours</p>
            <div class="task-list" id="list-doing">
                <?php if (empty($doing)): ?>
                    <div class="task-card task-card--doing task-card--empty">
                        <div class="task-body">
                            <p class="task-title">Aucune tâche en cours</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($doing as $task): ?>
                        <div class="task-card task-card--doing" data-task-id="<?= (int)$task['id'] ?>">
                            <div class="task-status-col">
                                <form class="js-status-form js-to-done" action="/tasks/status-ajax" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= (int)$task['id'] ?>">
                                    <input type="hidden" name="status" value="2">
                                    <button type="submit" class="task-complete-btn" aria-label="Terminer">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 6L9 17l-5-5" />
                                        </svg>
                                    </button>
                                </form>
                                <form class="js-status-form js-to-todo" action="/tasks/status-ajax" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= (int)$task['id'] ?>">
                                    <input type="hidden" name="status" value="0">
                                    <button type="submit" class="task-back-btn" aria-label="Revenir à faire">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 14 4 9 9 4" />
                                            <path d="M20 20v-7a4 4 0 0 0-4-4H4" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="task-body">
                                <p class="task-title"><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-desc"><?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                                <div class="task-chips">
                                    <span class="chip chip--status-doing">En cours</span>
                                </div>
                            </div>
                            <div class="task-card__actions">
                                <button
                                    type="button"
                                    class="task-edit-btn"
                                    aria-label="Modifier la tâche"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit-task"
                                    data-task-id="<?= (int)$task['id'] ?>"
                                    data-task-title="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-task-desc="<?= htmlspecialchars($task['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-task-due-date="<?= htmlspecialchars($task['due_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <form action="/tasks/delete" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= (int)$task['id'] ?>">
                                    <button type="submit" class="task-delete-btn" aria-label="Supprimer la tâche">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>


        <!-- ── Section : $done ────────────────────────────────────────── -->
        <section class="task-section" data-section="done">
            <p class="task-section__label">Terminé</p>
            <div class="task-list" id="list-done">
                <?php if (empty($done)): ?>
                    <div class="task-card task-card--done task-card--empty">
                        <div class="task-body">
                            <p class="task-title">Aucune tâche terminée</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($done as $task): ?>
                        <div class="task-card task-card--done" data-task-id="<?= (int)$task['id'] ?>">
                            <div class="task-status-col">
                                <form class="js-status-form js-to-doing" action="/tasks/status-ajax" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= (int)$task['id'] ?>">
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" class="task-back-btn" aria-label="Reprendre">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 14 4 9 9 4" />
                                            <path d="M20 20v-7a4 4 0 0 0-4-4H4" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="task-body">
                                <p class="task-title"><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-desc"><?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                                <div class="task-chips">
                                    <span class="chip chip--status-done">Terminé</span>
                                </div>
                            </div>
                            <div class="task-card__actions">
                                <button
                                    type="button"
                                    class="task-edit-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit-task"
                                    aria-label="Modifier la tâche"
                                    data-task-id="<?= (int)$task['id'] ?>"
                                    data-task-title="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-task-desc="<?= htmlspecialchars($task['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-task-due-date="<?= htmlspecialchars($task['due_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <form action="/tasks/delete" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= (int)$task['id'] ?>">
                                    <button type="submit" class="task-delete-btn" aria-label="Supprimer la tâche">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <!-- ── Modal : modifier une tâche ───────────────────────────────────── -->
    <div class="modal fade" id="modal-edit-task" tabindex="-1" aria-labelledby="modal-edit-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modal-edit-title">Modifier la tâche</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form id="form-edit-task" method="post" action="/tasks/update">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                        <input type="hidden" name="task_id" id="edit-task-id">
                        <div class="app-field">
                            <label for="edit-task-title">Titre</label>
                            <input type="text" id="edit-task-title" name="title" class="app-input" placeholder="Titre de la tâche" required>
                        </div>
                        <div class="app-field">
                            <label for="edit-task-desc">Description <span class="app-field__optional">optionnelle</span></label>
                            <textarea id="edit-task-desc" name="description" class="app-input app-textarea" placeholder="Décrivez la tâche…"></textarea>
                        </div>
                        <div class="app-field">
                            <label for="edit-task-due-date">Date d'echeance <span class="app-field__optional">(optionnelle)</span></label>
                            <input type="date" id="edit-task-due-date" name="due_date" class="app-input">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="app-btn app-btn--secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="form-edit-task" class="app-btn app-btn--primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    (function() {
        const btnOpen = document.getElementById('btn-add-task');
        const btnCancel = document.getElementById('btn-cancel-task');
        const btnShort = document.getElementById('add-task-shortcut');
        const panel = document.getElementById('panel-add-task');
        const titleInput = panel ? panel.querySelector('#task-title') : null;

        function openPanel() {
            panel.hidden = false;
            btnOpen.setAttribute('aria-expanded', 'true');
            if (titleInput) titleInput.focus();
            panel.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        function closePanel() {
            panel.hidden = true;
            btnOpen.setAttribute('aria-expanded', 'false');
            panel.querySelector('form').reset();
        }

        if (btnOpen) btnOpen.addEventListener('click', openPanel);
        if (btnCancel) btnCancel.addEventListener('click', closePanel);
        if (btnShort) btnShort.addEventListener('click', openPanel);
    })();

    // ── Modal modifier tâche (Bootstrap) ────────────────────────────────

    (function() {
        const modalEl = document.getElementById('modal-edit-task');

        modalEl.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget; // le bouton qui a déclenché la modale
            if (!btn) return;

            document.getElementById('edit-task-id').value = btn.dataset.taskId || '';
            document.getElementById('edit-task-title').value = btn.dataset.taskTitle || '';
            document.getElementById('edit-task-desc').value = btn.dataset.taskDesc || '';
            document.getElementById('edit-task-due-date').value = btn.dataset.taskDueDate || '';
        });
    })();

    // ── Mise à jour des compteurs dans le header (après changement de statut) ────────────────────────────────
    function countRealCards(listEl) {
        if (!listEl) return 0;
        return listEl.querySelectorAll('.task-card:not(.task-card--empty)').length;
    }

    function refreshHeaderCounts() {
        const todoList = document.getElementById('list-todo');
        const doingList = document.getElementById('list-doing');
        const doneList = document.getElementById('list-done');

        const todoCount = countRealCards(todoList);
        const doingCount = countRealCards(doingList);
        const doneCount = countRealCards(doneList);
        const remainingCount = todoCount + doingCount;

        const doneEl = document.getElementById('count-done');
        const remainingEl = document.getElementById('count-remaining');

        if (doneEl) doneEl.textContent = String(doneCount);
        if (remainingEl) remainingEl.textContent = String(remainingCount);
    }
    refreshHeaderCounts();
    // ── Gestion du changement de statut (AJAX) ─────────────────────────
    (function() {
        const listByStatus = {
            0: document.getElementById('list-todo'),
            1: document.getElementById('list-doing'),
            2: document.getElementById('list-done'),
        };

        const statusInfo = {
            0: {
                cardClass: 'task-card--todo',
                chipClass: 'chip--status-todo',
                label: 'À faire'
            },
            1: {
                cardClass: 'task-card--doing',
                chipClass: 'chip--status-doing',
                label: 'En cours'
            },
            2: {
                cardClass: 'task-card--done',
                chipClass: 'chip--status-done',
                label: 'Terminé'
            },
        };

        const ICON_CHECK = `
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M20 6L9 17l-5-5" />
    </svg>
  `.trim();

        const ICON_BACK = `
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <polyline points="9 14 4 9 9 4" />
      <path d="M20 20v-7a4 4 0 0 0-4-4H4" />
    </svg>
  `.trim();

        function setCardUi(card, newStatus) {
            const info = statusInfo[newStatus];
            if (!info) return;

            // classes card
            card.classList.remove('task-card--todo', 'task-card--doing', 'task-card--done');
            card.classList.add(info.cardClass);

            // chip
            const chip = card.querySelector('.task-chips .chip');
            if (chip) {
                chip.classList.remove('chip--status-todo', 'chip--status-doing', 'chip--status-done');
                chip.classList.add(info.chipClass);
                chip.textContent = info.label;
            }
        }

        function statusFormHtml({
            csrf,
            projectId,
            taskId,
            nextStatus,
            btnClass,
            aria,
            icon
        }) {
            return `
      <form class="js-status-form" action="/tasks/status-ajax" method="post">
        <input type="hidden" name="csrf_token" value="${csrf}">
        <input type="hidden" name="project_id" value="${projectId}">
        <input type="hidden" name="task_id" value="${taskId}">
        <input type="hidden" name="status" value="${nextStatus}">
        <button type="submit" class="${btnClass}" aria-label="${aria}">
          ${icon}
        </button>
      </form>
    `.trim();
        }

        function rebuildStatusCol(card, newStatus, csrf, projectId, taskId) {
            const col = card.querySelector('.task-status-col');
            if (!col) return;

            let html = '';

            // TODO -> DOING
            if (newStatus === 0) {
                html = statusFormHtml({
                    csrf,
                    projectId,
                    taskId,
                    nextStatus: 1,
                    btnClass: 'task-complete-btn',
                    aria: 'Démarrer',
                    icon: ICON_CHECK
                });
            }

            // DOING -> DONE  +  DOING -> TODO
            if (newStatus === 1) {
                html = [
                    statusFormHtml({
                        csrf,
                        projectId,
                        taskId,
                        nextStatus: 2,
                        btnClass: 'task-complete-btn',
                        aria: 'Terminer',
                        icon: ICON_CHECK
                    }),
                    statusFormHtml({
                        csrf,
                        projectId,
                        taskId,
                        nextStatus: 0,
                        btnClass: 'task-back-btn',
                        aria: 'Revenir à faire',
                        icon: ICON_BACK
                    })
                ].join('');
            }

            // DONE -> DOING
            if (newStatus === 2) {
                html = statusFormHtml({
                    csrf,
                    projectId,
                    taskId,
                    nextStatus: 1,
                    btnClass: 'task-back-btn',
                    aria: 'Reprendre',
                    icon: ICON_BACK
                });
            }

            col.innerHTML = html;
        }

        document.addEventListener('submit', async (e) => {
            const form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (!form.classList.contains('js-status-form')) return;

            e.preventDefault();

            const taskId = Number(form.querySelector('input[name="task_id"]')?.value || 0);
            const newStatus = Number(form.querySelector('input[name="status"]')?.value || -1);
            const projectId = String(form.querySelector('input[name="project_id"]')?.value || '');
            const csrf = String(form.querySelector('input[name="csrf_token"]')?.value || '');

            if (!taskId || !projectId || !csrf || ![0, 1, 2].includes(newStatus)) return;

            const card = form.closest('.task-card');
            if (!card) return;

            const btn = form.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json().catch(() => null);

                if (!res.ok || !data || data.success !== true) {
                    alert((data && data.message) ? data.message : 'Erreur lors de la mise à jour.');
                    return;
                }

                // Move DOM
                const targetList = listByStatus[newStatus];
                if (targetList) targetList.prepend(card);

                // Update UI
                setCardUi(card, newStatus);
                rebuildStatusCol(card, newStatus, csrf, projectId, taskId);
                refreshHeaderCounts();
            } finally {
                if (btn) btn.disabled = false;
            }
        }, true);

    })();

    // ── Gestion du filtrage des tâches ────────────────────────────────
    (function() {
        const filters = document.getElementById('task-filters');
        if (!filters) return;

        const buttons = Array.from(filters.querySelectorAll('.task-filter-btn[data-filter]'));
        const sections = Array.from(document.querySelectorAll('.task-section[data-section]'));

        function setActive(btn) {
            buttons.forEach(b => b.classList.toggle('is-active', b === btn));
        }

        function applyFilter(filter) {
            if (filter === 'all') {
                sections.forEach(s => s.style.display = '');
                return;
            }
            sections.forEach(s => {
                s.style.display = (s.dataset.section === filter) ? '' : 'none';
            });
        }

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.dataset.filter;
                setActive(btn);
                applyFilter(filter);
            });
        });
    })();
</script>