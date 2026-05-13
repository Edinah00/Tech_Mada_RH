

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<!-- ╔══════════════════════════════════════════════════════════════╗
     ║  PAGE 6 — DASHBOARD ADMIN  (admin/dashboard.php)            ║
     ╚══════════════════════════════════════════════════════════════╝ -->
<section id="page-dashboard-admin" style="margin-top:3rem">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div>
      <div class="sidebar-brand-name">TechMada RH
        <span>Administration</span>
      </div>
    </div>
    <div class="sidebar-section">Gestion</div>
    <ul class="sidebar-nav">
      <li><a href="#page-dashboard-admin" class="active"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li>
        <a href="#page-liste-rh">
          <i class="bi bi-inbox"></i> Toutes les demandes
          <span class="nav-badge alert">4</span>
        </a>
      </li>
      <li><a href="#page-admin-employes"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="#page-admin-employes"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="#page-admin-employes"><i class="bi bi-tags"></i> Types de congé</a></li>
      <li><a href="#page-admin-employes"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar" style="background:#5a2d82;width:32px;height:32px;font-size:.7rem">AD</div>
        <div><div class="user-name">Administrateur</div><div class="user-role">Admin système</div></div>
        <a href="#page-login" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Vue d'ensemble</div>
        <div class="topbar-breadcrumb">Administration</div>
      </div>
      <div class="topbar-actions">
        <a href="#page-admin-employes" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employé</a>
      </div>
    </div>

    <div class="content">

      <!-- Métriques admin -->
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
          <div class="metric-val">24</div>
          <div class="metric-label">Employés actifs</div>
          <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> +2 ce mois</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val">4</div>
          <div class="metric-label">Demandes en attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val">31</div>
          <div class="metric-label">Approuvées ce mois</div>
          <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> +6 vs mois dernier</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
          <div class="metric-val">4</div>
          <div class="metric-label">Départements</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div></div>
          <div class="metric-val">3</div>
          <div class="metric-label">Absents aujourd'hui</div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">

        <!-- Demandes récentes -->
        <div class="data-card" style="margin:0">
          <div class="data-card-head">
            <h3>Demandes récentes</h3>
            <a href="#page-liste-rh" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
          </div>
          <table class="tbl">
            <thead>
              <tr><th>Employé</th><th>Type</th><th>Durée</th><th>Statut</th></tr>
            </thead>
            <tbody>
              <tr>
                <td><div style="display:flex;align-items:center;gap:7px"><div class="avatar av-green" style="width:28px;height:28px;font-size:.62rem">SR</div><span class="td-name" style="font-size:.84rem">Soa Rakoto</span></div></td>
                <td><span class="type-badge t-annuel">Annuel</span></td>
                <td class="td-mono">5 j</td>
                <td><span class="statut s-attente">en attente</span></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:7px"><div class="avatar av-amber" style="width:28px;height:28px;font-size:.62rem">TF</div><span class="td-name" style="font-size:.84rem">Tsiry Fidy</span></div></td>
                <td><span class="type-badge t-maladie">Maladie</span></td>
                <td class="td-mono">2 j</td>
                <td><span class="statut s-attente">en attente</span></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:7px"><div class="avatar av-blue" style="width:28px;height:28px;font-size:.62rem">HA</div><span class="td-name" style="font-size:.84rem">Haja Andria</span></div></td>
                <td><span class="type-badge t-annuel">Annuel</span></td>
                <td class="td-mono">5 j</td>
                <td><span class="statut s-approuvee">approuvée</span></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Absents du jour + soldes critiques -->
        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-person-slash" style="color:var(--muted);margin-right:5px"></i>Absents aujourd'hui</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
              <div style="display:flex;align-items:center;gap:8px">
                <div class="avatar av-green" style="width:30px;height:30px;font-size:.65rem">SR</div>
                <div><div style="font-size:.83rem;font-weight:500;color:var(--ink)">Soa Rakoto</div><div style="font-size:.72rem;color:var(--muted)">Congé annuel · retour 28/06</div></div>
              </div>
              <div style="display:flex;align-items:center;gap:8px">
                <div class="avatar" style="width:30px;height:30px;font-size:.65rem;background:#993556">NR</div>
                <div><div style="font-size:.83rem;font-weight:500;color:var(--ink)">Noro Ramarao</div><div style="font-size:.72rem;color:var(--muted)">Maladie · retour 17/06</div></div>
              </div>
              <div style="display:flex;align-items:center;gap:8px">
                <div class="avatar av-amber" style="width:30px;height:30px;font-size:.65rem">KF</div>
                <div><div style="font-size:.83rem;font-weight:500;color:var(--ink)">Ketaka Feno</div><div style="font-size:.72rem;color:var(--muted)">Congé spécial · retour 16/06</div></div>
              </div>
            </div>
          </div>
          <div class="flash flash-warn" style="margin:0">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span style="font-size:.8rem">2 employés ont un solde critique (≤ 2 jours). <a href="#" style="color:var(--warn);font-weight:500">Voir les soldes →</a></span>
          </div>
        </div>

      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>
</body>
</html>
