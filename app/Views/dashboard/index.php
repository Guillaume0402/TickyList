<?php ob_start(); ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-speedometer2"></i> Dashboard
            </h2>
            <p class="text-muted">Welcome back, <?= escape($user['name']) ?>!</p>
        </div>
        <div class="col-auto">
            <a href="/tasks/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Task
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Overdue</h6>
                    <h3 class="mb-0"><?= count($overdueTasks) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-primary"><i class="bi bi-calendar-day"></i> Today</h6>
                    <h3 class="mb-0"><?= count($todayTasks) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-success"><i class="bi bi-calendar-check"></i> Upcoming</h6>
                    <h3 class="mb-0"><?= count($upcomingTasks) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Tasks -->
    <?php if (!empty($overdueTasks)): ?>
    <div class="card mb-4 border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Overdue Tasks</h5>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <?php foreach ($overdueTasks as $task): ?>
                <div class="list-group-item">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-3 task-toggle" 
                               data-task-id="<?= $task['id'] ?>" 
                               <?= $task['status'] === 'done' ? 'checked' : '' ?>>
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?= escape($task['title']) ?></h6>
                            <small class="text-muted">
                                <span class="badge" style="background-color: <?= escape($task['project_color']) ?>">
                                    <?= escape($task['project_name']) ?>
                                </span>
                                <?= priorityBadge($task['priority']) ?>
                                Due: <?= formatDate($task['due_date']) ?>
                            </small>
                        </div>
                        <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Today's Tasks -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Today's Tasks</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($todayTasks)): ?>
            <div class="p-4 text-center text-muted">
                <i class="bi bi-check-circle display-4"></i>
                <p class="mt-2">No tasks due today</p>
            </div>
            <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($todayTasks as $task): ?>
                <div class="list-group-item">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-3 task-toggle" 
                               data-task-id="<?= $task['id'] ?>" 
                               <?= $task['status'] === 'done' ? 'checked' : '' ?>>
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?= escape($task['title']) ?></h6>
                            <small class="text-muted">
                                <span class="badge" style="background-color: <?= escape($task['project_color']) ?>">
                                    <?= escape($task['project_name']) ?>
                                </span>
                                <?= priorityBadge($task['priority']) ?>
                                <?= statusBadge($task['status']) ?>
                            </small>
                        </div>
                        <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Upcoming Tasks -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Upcoming Tasks (Next 7 Days)</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($upcomingTasks)): ?>
            <div class="p-4 text-center text-muted">
                <i class="bi bi-calendar-x display-4"></i>
                <p class="mt-2">No upcoming tasks</p>
            </div>
            <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($upcomingTasks as $task): ?>
                <div class="list-group-item">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-3 task-toggle" 
                               data-task-id="<?= $task['id'] ?>" 
                               <?= $task['status'] === 'done' ? 'checked' : '' ?>>
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?= escape($task['title']) ?></h6>
                            <small class="text-muted">
                                <span class="badge" style="background-color: <?= escape($task['project_color']) ?>">
                                    <?= escape($task['project_name']) ?>
                                </span>
                                <?= priorityBadge($task['priority']) ?>
                                <?= statusBadge($task['status']) ?>
                                Due: <?= formatDate($task['due_date']) ?>
                            </small>
                        </div>
                        <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Projects Overview -->
    <?php if (!empty($projects)): ?>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-folder"></i> Your Projects</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($projects as $project): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle me-2" 
                                     style="width: 15px; height: 15px; background-color: <?= escape($project['color']) ?>"></div>
                                <h6 class="mb-0"><?= escape($project['name']) ?></h6>
                            </div>
                            <p class="text-muted small mb-2"><?= escape($project['description']) ?></p>
                            <div class="progress mb-2" style="height: 5px;">
                                <?php 
                                $percentage = $project['total_tasks'] > 0 
                                    ? round(($project['completed_tasks'] / $project['total_tasks']) * 100) 
                                    : 0;
                                ?>
                                <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <small class="text-muted">
                                <?= $project['completed_tasks'] ?> / <?= $project['total_tasks'] ?> tasks completed
                            </small>
                            <div class="mt-2">
                                <a href="/projects/<?= $project['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Dashboard - TickLyst'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
