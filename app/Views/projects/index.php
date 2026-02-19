<?php ob_start(); ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold"><i class="bi bi-folder"></i> Projects</h2>
        </div>
        <div class="col-auto">
            <a href="/projects/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Project
            </a>
        </div>
    </div>

    <?php if (empty($projects)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-folder-x display-1 text-muted"></i>
            <h5 class="mt-3">No Projects Yet</h5>
            <p class="text-muted">Create your first project to get started</p>
            <a href="/projects/create" class="btn btn-primary">Create Project</a>
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <?php foreach ($projects as $project): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle me-2" 
                             style="width: 20px; height: 20px; background-color: <?= escape($project['color']) ?>"></div>
                        <h5 class="mb-0"><?= escape($project['name']) ?></h5>
                    </div>
                    <p class="text-muted"><?= escape($project['description']) ?></p>
                    
                    <div class="progress mb-2" style="height: 10px;">
                        <?php 
                        $percentage = $project['total_tasks'] > 0 
                            ? round(($project['completed_tasks'] / $project['total_tasks']) * 100) 
                            : 0;
                        ?>
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: <?= $percentage ?>%"></div>
                    </div>
                    <small class="text-muted">
                        <?= $project['completed_tasks'] ?> / <?= $project['total_tasks'] ?> tasks completed
                    </small>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="/projects/<?= $project['id'] ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <div>
                            <a href="/projects/<?= $project['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="/projects/<?= $project['id'] ?>/delete" 
                                  class="d-inline" onsubmit="return confirm('Delete this project and all its tasks?')">
                                <?= CSRF::field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Projects - TickLyst'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
