<?php // app/Views/partials/sidebar.php ?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark">
            <div class="icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </div>
            <div>
                <h1>RégimeSport</h1>
                <span>Votre santé, notre priorité</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if (session()->get('userRole') === 'admin'): ?>
        <div class="nav-section">
            <div class="nav-label">Back Office</div>
            <a href="<?= base_url('/admin/stats') ?>" class="nav-item <?= uri_string() === 'admin/stats' ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                Statistiques
            </a>
            <a href="<?= base_url('/admin/users') ?>" class="nav-item <?= str_contains(uri_string(), 'admin/users') ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Utilisateurs
            </a>
            <a href="<?= base_url('/admin/regimes') ?>" class="nav-item <?= str_contains(uri_string(), 'admin/regimes') ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M12 8v4l3 3"/></svg>
                Régimes
            </a>
            <a href="<?= base_url('/admin/activites') ?>" class="nav-item <?= str_contains(uri_string(), 'admin/activites') ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Activités
            </a>
            <a href="<?= base_url('/admin/aliments') ?>" class="nav-item <?= str_contains(uri_string(), 'admin/aliments') ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"/></svg>
                Aliments
            </a>
            <a href="<?= base_url('/admin/codes') ?>" class="nav-item <?= str_contains(uri_string(), 'admin/codes') ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Codes de recharge
            </a>
        </div>
        <?php else: ?>
        <div class="nav-section">
            <div class="nav-label">Mon Espace</div>
            <a href="<?= base_url('/dashboard') ?>" class="nav-item <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="<?= base_url('/suggestions') ?>" class="nav-item <?= uri_string() === 'suggestions' ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Trouver un programme
            </a>
            <a href="<?= base_url('/mes-programmes') ?>" class="nav-item <?= uri_string() === 'mes-programmes' ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Mes Programmes
            </a>
            <a href="<?= base_url('/portefeuille') ?>" class="nav-item <?= uri_string() === 'portefeuille' ? 'active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Porte-monnaie
            </a>
        </div>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= strtoupper(substr(session()->get('userName') ?? 'U', 0, 1)) ?></div>
            <div class="user-info">
                <div class="user-name"><?= esc(session()->get('userName')) ?></div>
                <div class="user-role">
                    <?= session()->get('userRole') === 'admin' ? 'Administrateur' : 'Membre' ?>
                    <?php if (session()->get('isGold')): ?>
                        <span class="gold-badge">GOLD</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <a href="<?= base_url('/logout') ?>" class="btn btn-outline btn-sm btn-full mt-16" style="color:#fff;border-color:rgba(255,255,255,.2);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Déconnexion
        </a>
    </div>
</aside>
