<?php ob_start(); ?>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 fw-bold text-primary">
                            <i class="bi bi-check2-square"></i> TickLyst
                        </h1>
                        <p class="text-muted">Sign in to your account</p>
                    </div>

                    <form method="POST" action="/login">
                        <?= CSRF::field() ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= escape(old('email')) ?>" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Sign In
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">Don't have an account? <a href="/register">Register here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
