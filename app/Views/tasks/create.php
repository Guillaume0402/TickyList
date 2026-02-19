<?php ob_start(); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Task</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/tasks">
                        <?= CSRF::field() ?>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Task Title *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= escape(old('title')) ?>" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="3"><?= escape(old('description')) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="project_id" class="form-label">Project *</label>
                                <select class="form-select" id="project_id" name="project_id" required>
                                    <option value="">Select Project</option>
                                    <?php foreach ($projects as $project): ?>
                                    <option value="<?= $project['id'] ?>" 
                                            <?= ($selectedProject == $project['id'] || old('project_id') == $project['id']) ? 'selected' : '' ?>>
                                        <?= escape($project['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="todo" <?= old('status', 'todo') === 'todo' ? 'selected' : '' ?>>To Do</option>
                                    <option value="doing" <?= old('status') === 'doing' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="done" <?= old('status') === 'done' ? 'selected' : '' ?>>Done</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="1" <?= old('priority') == '1' ? 'selected' : '' ?>>High</option>
                                    <option value="2" <?= old('priority', '2') == '2' ? 'selected' : '' ?>>Medium</option>
                                    <option value="3" <?= old('priority') == '3' ? 'selected' : '' ?>>Low</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" 
                                       value="<?= escape(old('due_date')) ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="remind_at" class="form-label">Remind At</label>
                                <input type="datetime-local" class="form-control" id="remind_at" name="remind_at" 
                                       value="<?= escape(old('remind_at')) ?>">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="javascript:history.back()" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Create Task - TickLyst'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
