<?php
$role = session()->get('userRole');
$uri  = uri_string();

$userName = session()->get('userName') ?? 'Utilisateur';
$initials  = strtoupper(substr($userName, 0, 1));

function active_class(string $needle, string $uri): string {
    return str_contains($uri, $needle) ? 'active' : '';
}
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-logo-icon">
      <i class="bi bi-briefcase<?= $role === 'admin' ? '-shield' : ($role === 'rh' ? '-person-check' : '') ?>"></i>
    </div>
    <div class="sidebar-brand-name">
      TechMada RH
      <span>
        <?= $role === 'admin' ? 'Administration' : ($role === 'rh' ? 'Espace responsable' : 'Espace employé') ?>
      </span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <?php if ($role === 'admin' || $role === 'rh'): ?>
      <div class="sidebar-section">Gestion</div>
      <li><a href="<?= base_url('/admin/stats') ?>" class="<?= active_class('admin/stats', $uri) ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li><a href="<?= base_url('/admin/historique') ?>" class="<?= active_class('admin/historique', $uri) . ' ' . active_class('rh', $uri) ?>"><i class="bi bi-inbox"></i> Toutes les demandes <span class="nav-badge alert">4</span></a></li>
      <li><a href="<?= base_url('/admin/employe') ?>" class="<?= active_class('admin/employe', $uri) ?>"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="<?= base_url('/admin/departement') ?>" class="<?= active_class('admin/departement', $uri) ?>"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="<?= base_url('/admin/type-conge') ?>" class="<?= active_class('admin/type-conge', $uri) ?>"><i class="bi bi-tags"></i> Types de congé</a></li>
      <li><a href="<?= base_url('/rh/soldes') ?>" class="<?= active_class('rh/soldes', $uri) ?>"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
    <?php else: ?>
      <div class="sidebar-section">Menu</div>
      <li><a href="<?= base_url('/employe/dashboard') ?>" class="<?= active_class('employe/dashboard', $uri) ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= base_url('/employe/conge/create') ?>" class="<?= active_class('employe/conge/create', $uri) ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= base_url('/employe/conge') ?>" class="<?= active_class('employe/conge', $uri) ?>"><i class="bi bi-calendar3"></i> Mes demandes <span class="nav-badge alert"><?= (int)(session()->get('pendingCount') ?? 2) ?></span></a></li>
      <li><a href="<?= base_url('/employe/profil') ?>" class="<?= active_class('employe/profil', $uri) ?>"><i class="bi bi-person"></i> Mon profil</a></li>
    <?php endif; ?>
  </nav>

  <div class="sidebar-user">
    <div class="s-user-row">
      <div class="avatar av-green"><?= esc($initials) ?></div>
      <div>
        <div class="user-name"><?= esc($userName) ?></div>
        <div class="user-role">
          <?= $role === 'admin' ? 'Administrateur' : ($role === 'rh' ? 'Responsable RH' : 'Employé') ?>
        </div>
      </div>
    </div>
    <a href="<?= base_url('/logout') ?>" class="btn-secondary" style="width:100%;justify-content:center;margin-top:12px;background:rgba(255,255,255,.04);color:#fff;border-color:rgba(255,255,255,.08)">
      <i class="bi bi-box-arrow-right"></i> Déconnexion
    </a>
  </div>
</aside>
