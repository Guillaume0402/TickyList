<div class="login-wrapper">
    <div class="login-card">

        <div class="login-card__header">
            <h1 class="login-card__title">Créer un compte</h1>
            <p class="login-card__subtitle">Rejoignez-nous, c'est gratuit !</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="flash-wrap">
                <div class="flash flash--error">
                    <?= htmlspecialchars($error) ?>
                </div>
            </div>
        <?php endif; ?>
        
        <form action="/register" method="POST" novalidate>
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
                    autocomplete="new-password"
                    required>
            </div>

            <div class="app-field">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input
                    class="app-input"
                    type="password"
                    id="password_confirm"
                    name="password_confirm"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required>
            </div>

            <button type="submit" class="app-btn app-btn--primary login-card__submit">
                Créer mon compte
            </button>
        </form>

        <div class="login-card__footer">
            Déjà un compte ? <a href="/login">Se connecter</a>
        </div>

    </div>
</div>