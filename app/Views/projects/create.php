<?php ob_start(); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-folder-plus"></i> Create New Project</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/projects">
                        <?= CSRF::field() ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Project Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= escape(old('name')) ?>" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="3"><?= escape(old('description')) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="color" 
                                   name="color" value="<?= escape(old('color', '#3498db')) ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/projects" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Create Project - TickLyst'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
