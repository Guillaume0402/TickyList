<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TickLyst - Task Management' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php if (Auth::check()): ?>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="/dashboard">
                    <i class="bi bi-check2-square"></i> TickLyst
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('/dashboard') ?>" href="/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('/projects') ?>" href="/projects">
                                <i class="bi bi-folder"></i> Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('/tasks') ?>" href="/tasks">
                                <i class="bi bi-list-task"></i> Tasks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('/notifications') ?>" href="/notifications">
                                <i class="bi bi-bell"></i> Notifications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('/tasks/trash') ?>" href="/tasks/trash">
                                <i class="bi bi-trash"></i> Trash
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= escape(Auth::user()['name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Flash Messages -->
    <div class="container mt-3">
        <?= Flash::display() ?>
    </div>

    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
