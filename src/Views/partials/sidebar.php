<?php

/**
 * Sidebar partial – layout projects-layout
 *
 * Variables attendues :
 *   array  $projects        Liste des projets. Chaque entrée :
 *                             ['id' => int, 'name' => string, 'color' => string, 'task_count' => int]
 *   int|null $activeProjectId  Id du projet courant (null sur la liste des projets).
 */

$activeProjectId ??= null;
$projects        ??= [];
?>

<!-- ── Sidebar ──────────────────────────────────────────────────────── -->
<aside class="sidebar">

    <p class="sidebar__section-title">Vues rapides</p>
    <ul class="sidebar__nav">
        <li>
            <a href="/today">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                Aujourd'hui
                <span class="sidebar__count sidebar__count--primary">4</span>
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
                <span class="sidebar__count sidebar__count--warning">2</span>
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
                <span class="sidebar__count">11</span>
            </a>
        </li>
    </ul>

    <div class="sidebar__divider"></div>

    <p class="sidebar__section-title">Projets</p>
    <ul class="sidebar__nav">
        <?php foreach ($projects as $project): ?>
            <li>
                <a href="/projects/<?= $project['id'] ?>"
                    <?= $project['id'] === $activeProjectId ? 'class="is-active"' : '' ?>>
                    <?php $color = !empty($project['color']) ? $project['color'] : '#00e676'; ?>
                    <span style="width:10px;height:10px;border-radius:50%;background:<?= htmlspecialchars($color, ENT_QUOTES, 'UTF-8') ?>;flex-shrink:0;"></span>
                    <?= htmlspecialchars($project['name']) ?>
                    <span class="sidebar__count"><?= (int) $project['task_count'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</aside>