<div class="projects-layout">

    <?php

    require __DIR__ . '/../partials/sidebar.php';

    $todoCount     = count($todo);
    $doingCount    = count($doing);
    $doneCount     = count($done);
    $total         = $todoCount + $doingCount + $doneCount;
    $remainingCount = $todoCount + $doingCount;

    ?>

    <!-- ── Main ─────────────────────────────────────────────────────────── -->
    <main>

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
                            <?= $doneCount ?> terminées
                        </span>
                        <span class="project-header__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            <?= $remainingCount ?> restantes
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
            <button class="task-filter-btn is-active">Tout (<?= $total ?>)</button>
            <button class="task-filter-btn">À faire (<?= $todoCount ?>)</button>
            <button class="task-filter-btn">En cours (<?= $doingCount ?>)</button>
            <button class="task-filter-btn">Terminé (<?= $doneCount ?>)</button>
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


        <!-- ── Section : À faire ────────────────────────────────────────── -->
        <section class="task-section">
            <p class="task-section__label">À faire</p>
            <div class="task-list">
                <?php if (empty($todo)): ?>
                    <div class="task-card task-card--todo task-card--empty">
                        <div class="task-body">
                            <p class="task-title">Aucune tâche à faire</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($todo as $task): ?>
                        <div class="task-card task-card--todo">
                            <div class="task-check"></div>
                            <div class="task-body">
                                <p class="task-title"><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-desc"><?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                                <div class="task-chips">
                                    <span class="chip chip--status-todo">À faire</span>
                                </div>
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


        <!-- ── Section : En cours ───────────────────────────────────────── -->
        <section class="task-section">
            <p class="task-section__label">En cours</p>
            <div class="task-list">
                <?php if (empty($doing)): ?>
                    <div class="task-card task-card--doing task-card--empty">
                        <div class="task-body">
                            <p class="task-title">Aucune tâche en cours</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($doing as $task): ?>
                        <div class="task-card task-card--doing">
                            <div class="task-check"></div>
                            <div class="task-body">
                                <p class="task-title"><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-desc"><?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                                <div class="task-chips">
                                    <span class="chip chip--status-doing">En cours</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>


        <!-- ── Section : Terminé ────────────────────────────────────────── -->
        <section class="task-section">
            <p class="task-section__label">Terminé</p>
            <div class="task-list">
                <?php if (empty($done)): ?>
                    <div class="task-card task-card--done task-card--empty">
                        <div class="task-body">
                            <p class="task-title">Aucune tâche terminée</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($done as $task): ?>
                        <div class="task-card task-card--done">
                            <div class="task-check"></div>
                            <div class="task-body">
                                <p class="task-title"><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-desc"><?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                                <div class="task-chips">
                                    <span class="chip chip--status-done">Terminé</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>

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
</script>