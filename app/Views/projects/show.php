<?php ob_start(); ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center">
                <div class="rounded-circle me-3" 
                     style="width: 30px; height: 30px; background-color: <?= escape($project['color']) ?>"></div>
                <div>
                    <h2 class="fw-bold mb-0"><?= escape($project['name']) ?></h2>
                    <?php if ($project['description']): ?>
                    <p class="text-muted mb-0"><?= escape($project['description']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <a href="/tasks/create?project=<?= $project['id'] ?>" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> New Task
            </a>
            <a href="/projects/<?= $project['id'] ?>/edit" class="btn btn-outline-secondary">
                <i class="bi bi-pencil"></i> Edit Project
            </a>
        </div>
    </div>

    <?php if (empty($tasks)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-list-task display-1 text-muted"></i>
            <h5 class="mt-3">No Tasks Yet</h5>
            <p class="text-muted">Create your first task in this project</p>
            <a href="/tasks/create?project=<?= $project['id'] ?>" class="btn btn-primary">Create Task</a>
        </div>
    </div>
    <?php else: ?>
    
    <!-- Group tasks by status -->
    <?php
    $todoTasks = array_filter($tasks, fn($t) => $t['status'] === 'todo');
    $doingTasks = array_filter($tasks, fn($t) => $t['status'] === 'doing');
    $doneTasks = array_filter($tasks, fn($t) => $t['status'] === 'done');
    ?>

    <div class="row">
        <!-- To Do Column -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-circle"></i> To Do
                        <span class="badge bg-white text-dark float-end"><?= count($todoTasks) ?></span>
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($todoTasks as $task): ?>
                    <div class="list-group-item">
                        <div class="d-flex align-items-start">
                            <input type="checkbox" class="form-check-input me-2 mt-1 task-toggle" 
                                   data-task-id="<?= $task['id'] ?>">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= escape($task['title']) ?></h6>
                                <div class="mb-2">
                                    <?= priorityBadge($task['priority']) ?>
                                    <?php if ($task['due_date']): ?>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> <?= formatDate($task['due_date']) ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/tasks/<?= $task['id'] ?>/delete" 
                                          class="d-inline" onsubmit="return confirm('Move to trash?')">
                                        <?= CSRF::field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($todoTasks)): ?>
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-check-circle"></i> No tasks
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Doing Column -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-arrow-repeat"></i> In Progress
                        <span class="badge bg-white text-dark float-end"><?= count($doingTasks) ?></span>
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($doingTasks as $task): ?>
                    <div class="list-group-item">
                        <div class="d-flex align-items-start">
                            <input type="checkbox" class="form-check-input me-2 mt-1 task-toggle" 
                                   data-task-id="<?= $task['id'] ?>">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= escape($task['title']) ?></h6>
                                <div class="mb-2">
                                    <?= priorityBadge($task['priority']) ?>
                                    <?php if ($task['due_date']): ?>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> <?= formatDate($task['due_date']) ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/tasks/<?= $task['id'] ?>/delete" 
                                          class="d-inline" onsubmit="return confirm('Move to trash?')">
                                        <?= CSRF::field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($doingTasks)): ?>
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-check-circle"></i> No tasks
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Done Column -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-check-circle"></i> Done
                        <span class="badge bg-white text-dark float-end"><?= count($doneTasks) ?></span>
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($doneTasks as $task): ?>
                    <div class="list-group-item">
                        <div class="d-flex align-items-start">
                            <input type="checkbox" class="form-check-input me-2 mt-1 task-toggle" 
                                   data-task-id="<?= $task['id'] ?>" checked>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-decoration-line-through"><?= escape($task['title']) ?></h6>
                                <div class="mb-2">
                                    <?= priorityBadge($task['priority']) ?>
                                    <?php if ($task['due_date']): ?>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> <?= formatDate($task['due_date']) ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/tasks/<?= $task['id'] ?>/delete" 
                                          class="d-inline" onsubmit="return confirm('Move to trash?')">
                                        <?= CSRF::field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($doneTasks)): ?>
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-check-circle"></i> No tasks
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php $title = escape($project['name']) . ' - TickLyst'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
