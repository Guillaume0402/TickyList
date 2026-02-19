<?php ob_start(); ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold"><i class="bi bi-bell"></i> Notifications</h2>
            <p class="text-muted">Tasks with reminders that need your attention</p>
        </div>
    </div>

    <?php if (empty($reminders)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-bell-slash display-1 text-muted"></i>
            <h5 class="mt-3">No Reminders</h5>
            <p class="text-muted">You're all caught up!</p>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-header bg-warning">
            <h5 class="mb-0">
                <i class="bi bi-bell-fill"></i> Active Reminders
                <span class="badge bg-dark float-end"><?= count($reminders) ?></span>
            </h5>
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($reminders as $task): ?>
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input me-3 task-toggle" 
                           data-task-id="<?= $task['id'] ?>" 
                           <?= $task['status'] === 'done' ? 'checked' : '' ?>>
                    <div class="flex-grow-1">
                        <h6 class="mb-1"><?= escape($task['title']) ?></h6>
                        <div class="mb-1">
                            <span class="badge" style="background-color: <?= escape($task['project_color']) ?>">
                                <?= escape($task['project_name']) ?>
                            </span>
                            <?= statusBadge($task['status']) ?>
                            <?= priorityBadge($task['priority']) ?>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Reminder: <?= formatDateTime($task['remind_at']) ?>
                            <?php if ($task['due_date']): ?>
                            | <i class="bi bi-calendar"></i> Due: <?= formatDate($task['due_date']) ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    <div>
                        <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Notifications - TickLyst'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
