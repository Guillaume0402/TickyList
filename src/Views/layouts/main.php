<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'App') ?></title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>

<header class="header border-bottom">
  <div class="app-container py-3 d-flex align-items-center justify-content-between">
    <div class="brand fw-bold">Template</div>

    <nav class="d-flex gap-2">
      <a class="text-decoration-none" href="/">Accueil</a>
      <a class="text-decoration-none" href="/about">À propos</a>
    </nav>
  </div>
</header>

<main class="app-container py-4">
  <?= $content ?? '' ?>
</main>

<footer class="footer border-top py-3">
  <div class="app-container">
    <small class="text-muted">Template MVC - Docker</small>
  </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
