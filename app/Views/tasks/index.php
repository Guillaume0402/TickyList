<?php ob_start(); ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold"><i class="bi bi-list-task"></i> All Tasks</h2>
        </div>
        <div class="col-auto">
            <a href="/tasks/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Task
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/tasks" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Project</label>
                    <select name="project" class="form-select">
                        <option value="">All Projects</option>
                        <?php foreach ($projects as $project): ?>
                        <option value="<?= $project['id'] ?>" 
                                <?= ($filters['project_id'] ?? '') == $project['id'] ? 'selected' : '' ?>>
                            <?= escape($project['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="todo" <?= ($filters['status'] ?? '') === 'todo' ? 'selected' : '' ?>>To Do</option>
                        <option value="doing" <?= ($filters['status'] ?? '') === 'doing' ? 'selected' : '' ?>>In Progress</option>
                        <option value="done" <?= ($filters['status'] ?? '') === 'done' ? 'selected' : '' ?>>Done</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="">All Priority</option>
                        <option value="1" <?= ($filters['priority'] ?? '') == '1' ? 'selected' : '' ?>>High</option>
                        <option value="2" <?= ($filters['priority'] ?? '') == '2' ? 'selected' : '' ?>>Medium</option>
                        <option value="3" <?= ($filters['priority'] ?? '') == '3' ? 'selected' : '' ?>>Low</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search tasks..." value="<?= escape($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="/tasks" class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks List -->
    <?php if (empty($tasks)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-list-task display-1 text-muted"></i>
            <h5 class="mt-3">No Tasks Found</h5>
            <p class="text-muted">Create a task or adjust your filters</p>
            <a href="/tasks/create" class="btn btn-primary">Create Task</a>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="list-group list-group-flush">
            <?php foreach ($tasks as $task): ?>
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input me-3 task-toggle" 
                           data-task-id="<?= $task['id'] ?>" 
                           <?= $task['status'] === 'done' ? 'checked' : '' ?>>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 <?= $task['status'] === 'done' ? 'text-decoration-line-through' : '' ?>">
                            <?= escape($task['title']) ?>
                        </h6>
                        <div>
                            <span class="badge" style="background-color: <?= escape($task['project_color']) ?>">
                                <?= escape($task['project_name']) ?>
                            </span>
                            <?= statusBadge($task['status']) ?>
                            <?= priorityBadge($task['priority']) ?>
                            <?php if ($task['due_date']): ?>
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> <?= formatDate($task['due_date']) ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1">
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
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Tasks - TickLyst'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
