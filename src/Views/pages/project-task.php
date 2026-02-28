<div class="projects-layout">

    <?php require __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- ── Main ─────────────────────────────────────────────────────────── -->
    <main>

        <!-- Project header ─────────────────────────────────────────────── -->
        <div class="project-header">
            <div style="display:flex;gap:14px;align-items:flex-start;">
                <div class="project-header__icon">🌿</div>
                <div>
                    <h1 class="project-header__title">EcoRide</h1>
                    <div class="project-header__meta">
                        <span class="project-header__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 11 12 14 22 4" />
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                            </svg>
                            5 terminées
                        </span>
                        <span class="project-header__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            3 restantes
                        </span>
                        <span class="project-header__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            </svg>
                            1 en retard
                        </span>
                    </div>
                </div>
            </div>
            <a href="#" class="app-btn app-btn--primary">+ Ajouter une tâche</a>
        </div>

        <!-- Filter tabs ────────────────────────────────────────────────── -->
        <div class="task-filters">
            <button class="task-filter-btn is-active">Tout (8)</button>
            <button class="task-filter-btn">À faire (2)</button>
            <button class="task-filter-btn">En cours (1)</button>
            <button class="task-filter-btn">Terminé (5)</button>
            <div style="flex:1"></div>
            <button class="task-filter-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px;">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                Aujourd'hui
            </button>
            <button class="task-filter-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px;">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
                En retard
            </button>
            <button class="task-filter-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                À venir
            </button>
        </div>

        <!-- ── Section : À faire ─────────────────────────────────────────── -->
        <section class="task-section">
            <p class="task-section__label">À faire</p>
            <div class="task-list">

                <!-- Task 1 : high priority, late ─────────────────────────── -->
                <div class="task-card task-card--todo">
                    <div class="task-check"></div>
                    <div class="task-body">
                        <p class="task-title">Rédiger le cahier des charges V2</p>
                        <p class="task-desc">Inclure les nouvelles exigences du client sur l'interface mobile.</p>
                        <div class="task-chips">
                            <span class="chip chip--status-todo">À faire</span>
                            <span class="chip chip--p1">P1</span>
                            <span class="chip chip--date is-late">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                25 févr. 2026
                            </span>
                            <span class="chip chip--reminder">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                </svg>
                                28 févr. 09:00
                            </span>
                        </div>
                    </div>
                    <div class="task-meta-aside">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--muted)">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="19" cy="12" r="1" />
                            <circle cx="5" cy="12" r="1" />
                        </svg>
                    </div>
                </div>

                <!-- Task 2 : medium priority, today ──────────────────────── -->
                <div class="task-card task-card--todo">
                    <div class="task-check"></div>
                    <div class="task-body">
                        <p class="task-title">Mettre à jour les visuels marketing</p>
                        <div class="task-chips">
                            <span class="chip chip--status-todo">À faire</span>
                            <span class="chip chip--p2">P2</span>
                            <span class="chip chip--date is-today">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                Aujourd'hui
                            </span>
                        </div>
                    </div>
                    <div class="task-meta-aside">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--muted)">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="19" cy="12" r="1" />
                            <circle cx="5" cy="12" r="1" />
                        </svg>
                    </div>
                </div>

            </div>
            <div class="add-task-row">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Ajouter une tâche
            </div>
        </section>

        <!-- ── Section : En cours ─────────────────────────────────────────── -->
        <section class="task-section">
            <p class="task-section__label">En cours</p>
            <div class="task-list">

                <!-- Task 3 ────────────────────────────────────────────────── -->
                <div class="task-card task-card--doing">
                    <div class="task-check" style="border-color:#448aff;background:rgba(68,138,255,0.1);">
                        <div style="width:6px;height:6px;border-radius:50%;background:#448aff;"></div>
                    </div>
                    <div class="task-body">
                        <p class="task-title">Intégrer l'API de géolocalisation</p>
                        <p class="task-desc">Connecter le service de trajet avec OpenStreetMap, tester les temps de réponse.</p>
                        <div class="task-chips">
                            <span class="chip chip--status-doing">En cours</span>
                            <span class="chip chip--p1">P1</span>
                            <span class="chip chip--date">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                5 mars 2026
                            </span>
                            <span class="chip chip--reminder">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                </svg>
                                3 mars 14:00
                            </span>
                        </div>
                    </div>
                    <div class="task-meta-aside">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--muted)">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="19" cy="12" r="1" />
                            <circle cx="5" cy="12" r="1" />
                        </svg>
                    </div>
                </div>

            </div>
        </section>

        <!-- ── Section : Terminé ─────────────────────────────────────────── -->
        <section class="task-section">
            <p class="task-section__label">Terminé</p>
            <div class="task-list">

                <!-- Task 4 ────────────────────────────────────────────────── -->
                <div class="task-card task-card--done">
                    <div class="task-check"></div>
                    <div class="task-body">
                        <p class="task-title">Créer la maquette Figma de l'app</p>
                        <div class="task-chips">
                            <span class="chip chip--status-done">Terminé</span>
                            <span class="chip chip--p2">P2</span>
                            <span class="chip chip--date">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                20 févr. 2026
                            </span>
                        </div>
                    </div>
                    <div class="task-meta-aside">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--muted)">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="19" cy="12" r="1" />
                            <circle cx="5" cy="12" r="1" />
                        </svg>
                    </div>
                </div>

                <!-- Task 5 ────────────────────────────────────────────────── -->
                <div class="task-card task-card--done">
                    <div class="task-check"></div>
                    <div class="task-body">
                        <p class="task-title">Rédiger les user stories sprint 1</p>
                        <p class="task-desc">Couverture des fonctionnalités réservation, notification et historique.</p>
                        <div class="task-chips">
                            <span class="chip chip--status-done">Terminé</span>
                            <span class="chip chip--p3">P3</span>
                            <span class="chip chip--date">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                15 févr. 2026
                            </span>
                        </div>
                    </div>
                    <div class="task-meta-aside">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--muted)">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="19" cy="12" r="1" />
                            <circle cx="5" cy="12" r="1" />
                        </svg>
                    </div>
                </div>

                <!-- Task 6 ────────────────────────────────────────────────── -->
                <div class="task-card task-card--done">
                    <div class="task-check"></div>
                    <div class="task-body">
                        <p class="task-title">Configurer le repo Git et CI/CD</p>
                        <div class="task-chips">
                            <span class="chip chip--status-done">Terminé</span>
                            <span class="chip chip--p2">P2</span>
                            <span class="chip chip--date">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                10 févr. 2026
                            </span>
                        </div>
                    </div>
                    <div class="task-meta-aside">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--muted)">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="19" cy="12" r="1" />
                            <circle cx="5" cy="12" r="1" />
                        </svg>
                    </div>
                </div>

                <!-- Task 7 ────────────────────────────────────────────────── -->
                <div class="task-card task-card--done">
                    <div class="task-check"></div>
                    <div class="task-body">
                        <p class="task-title">Interview utilisateurs (5 participants)</p>
                        <p class="task-desc">Recueil des besoins et pain-points pour la v1.</p>
                        <div class="task-chips">
                            <span class="chip chip--status-done">Terminé</span>
                            <span class="chip chip--p1">P1</span>
                            <span class="chip chip--date">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                5 févr. 2026
                            </span>
                            <span class="chip chip--reminder">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                </svg>
                                4 févr. 10:30
                            </span>
                        </div>
                    </div>
                    <div class="task-meta-aside">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--muted)">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="19" cy="12" r="1" />
                            <circle cx="5" cy="12" r="1" />
                        </svg>
                    </div>
                </div>

                <!-- Task 8 ────────────────────────────────────────────────── -->
                <div class="task-card task-card--done">
                    <div class="task-check"></div>
                    <div class="task-body">
                        <p class="task-title">Élaborer le pitch deck investisseur</p>
                        <div class="task-chips">
                            <span class="chip chip--status-done">Terminé</span>
                            <span class="chip chip--p3">P3</span>
                            <span class="chip chip--date">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                1 févr. 2026
                            </span>
                        </div>
                    </div>
                    <div class="task-meta-aside">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--muted)">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="19" cy="12" r="1" />
                            <circle cx="5" cy="12" r="1" />
                        </svg>
                    </div>
                </div>

            </div>
        </section>

    </main>

</div>