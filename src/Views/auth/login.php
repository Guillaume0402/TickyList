<div class="login-wrapper">
    <div class="login-card">

        <div class="login-card__header">
            <h1 class="login-card__title">Connexion</h1>
            <p class="login-card__subtitle">Content de vous revoir !</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="flash-wrap">
                <div class="flash flash--error">
                    <?= htmlspecialchars($error) ?>
                </div>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

            <div class="app-field">
                <label for="email">Adresse e-mail</label>
                <input
                    class="app-input"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="vous@exemple.com"
                    autocomplete="email"
                    required
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>">

            </div>

            <div class="app-field">
                <label for="password">Mot de passe</label>
                <input
                    class="app-input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required>
            </div>

            <button type="submit" class="app-btn app-btn--primary login-card__submit">
                Se connecter
            </button>
        </form>

        <div class="login-card__footer">
            Pas encore de compte ? <a href="/register">Créer un compte</a>
        </div>

    </div>
</div>