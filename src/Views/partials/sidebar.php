<?php
$activeProjectId ??= null;
$sidebarProjects ??= [];
$quick ??= ['today' => 0, 'late' => 0, 'upcoming' => 0];
?>

<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<!-- ── Sidebar ──────────────────────────────────────────────────────── -->
<aside class="sidebar" id="sidebar">

    <div class="sidebar__mobile-header">
        <span class="sidebar__mobile-title">Navigation</span>
        <button class="sidebar__close" id="sidebarClose" aria-label="Fermer la navigation">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
    </div>

    <p class="sidebar__section-title">Vues rapides</p>
    <ul class="sidebar__nav">
        <li>
            <a href="/today">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                Aujourd'hui
                <span class="sidebar__count sidebar__count--primary"><?= (int) $quick['today'] ?></span>
            </a>
        </li>
        <li>
            <a href="/late">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
                En retard
                <span class="sidebar__count sidebar__count--warning"><?= (int) $quick['late'] ?></span>
            </a>
        </li>
        <li>
            <a href="/upcoming">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                À venir
                <span class="sidebar__count sidebar__count--info"><?= (int) $quick['upcoming'] ?></span>
            </a>
        </li>
    </ul>

    <div class="sidebar__divider"></div>

    <p class="sidebar__section-title">Projets</p>
    <ul class="sidebar__nav">
        <?php foreach ($sidebarProjects as $project): ?>
            <li>
                <a href="/project?id=<?= (int)$project['id'] ?>"
                    <?= (int)$project['id'] === (int)$activeProjectId ? 'class="is-active"' : '' ?>>
                    <?php $color = !empty($project['color']) ? $project['color'] : '#00e676'; ?>
                    <span style="width:10px;height:10px;border-radius:50%;background:<?= htmlspecialchars($color, ENT_QUOTES, 'UTF-8') ?>;flex-shrink:0;"></span>
                    <?= htmlspecialchars($project['name']) ?>
                    <span class="sidebar__count"><?= (int) $project['task_count'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</aside>