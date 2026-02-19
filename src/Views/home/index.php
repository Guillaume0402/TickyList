<section class="app-card home-hero">
  <h1><?= htmlspecialchars($title ?? 'Accueil') ?></h1>
  <p><?= htmlspecialchars($subtitle ?? 'Routeur OK') ?></p>

  <div class="d-flex gap-2 mt-3">
    <a class="btn btn-primary app-btn" href="/about">Voir about</a>
    <a class="btn btn-outline-light app-btn" href="/">Reload</a>
  </div>
</section>

