<?php ob_start(); ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold"><i class="bi bi-trash"></i> Trash</h2>
            <p class="text-muted">Deleted tasks can be restored or permanently deleted</p>
        </div>
    </div>

    <?php if (empty($tasks)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-trash display-1 text-muted"></i>
            <h5 class="mt-3">Trash is Empty</h5>
            <p class="text-muted">No deleted tasks</p>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="list-group list-group-flush">
            <?php foreach ($tasks as $task): ?>
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-muted">
                            <?= escape($task['title']) ?>
                        </h6>
                        <div>
                            <span class="badge" style="background-color: <?= escape($task['project_color']) ?>">
                                <?= escape($task['project_name']) ?>
                            </span>
                            <?= statusBadge($task['status']) ?>
                            <?= priorityBadge($task['priority']) ?>
                            <small class="text-muted">
                                <i class="bi bi-trash"></i> Deleted <?= timeAgo($task['deleted_at']) ?>
                            </small>
                        </div>
                    </div>
                    <div>
                        <form method="POST" action="/tasks/<?= $task['id'] ?>/restore" class="d-inline">
                            <?= CSRF::field() ?>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                            </button>
                        </form>
                        <form method="POST" action="/tasks/<?= $task['id'] ?>/permanent-delete" 
                              class="d-inline" onsubmit="return confirm('Permanently delete this task? This cannot be undone!')">
                            <?= CSRF::field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-x-circle"></i> Delete Forever
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Trash - TickLyst'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
