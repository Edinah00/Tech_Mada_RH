

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — RégimeSport Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<section id="page-dashboard-employe" style="margin-top:3rem">
<div class="app-wrap">

  <!-- SIDEBAR EMPLOYÉ -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="#page-dashboard-employe" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="#page-form-conge"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li>
        <a href="#page-mes-conges">
          <i class="bi bi-calendar3"></i> Mes demandes
          <span class="nav-badge alert">2</span>
        </a>
      </li>
      <li><a href="#page-profil-employe"><i class="bi bi-person"></i> Mon profil</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green">SR</div>
        <div>
          <div class="user-name">Soa Rakoto</div>
          <div class="user-role">Employé · IT</div>
        </div>
        <a href="#page-login" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Tableau de bord</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions">
        <a href="#page-form-conge" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
          <i class="bi bi-plus-lg"></i> Nouvelle demande
        </a>
      </div>
    </div>

    <div class="content">

      <!-- Flash succès -->
      <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        Votre demande de congé a bien été soumise. Elle est en attente de validation.
      </div>

      <!-- Métriques -->
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val">2</div>
          <div class="metric-label">En attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
          <div class="metric-val">5</div>
          <div class="metric-label">Approuvées</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val">18</div>
          <div class="metric-label">Jours restants</div>
          <div class="metric-sub">sur 30 cette année</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
          <div class="metric-val">1</div>
          <div class="metric-label">Refusée</div>
        </div>
      </div>

      <!-- Soldes de congés -->
      <div class="data-card">
        <div class="data-card-head"><h3>Mes soldes de congés — 2025</h3></div>
        <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <div class="solde-card" style="margin:0">
            <div class="solde-header">
              <span class="solde-type">Congé annuel</span>
              <span class="solde-nums"><strong>18</strong> / 30 j</span>
            </div>
            <div class="solde-bar"><div class="solde-fill" style="width:60%"></div></div>
            <div class="solde-label">18 jours restants · 12 pris</div>
          </div>
          <div class="solde-card" style="margin:0">
            <div class="solde-header">
              <span class="solde-type">Congé maladie</span>
              <span class="solde-nums"><strong>8</strong> / 10 j</span>
            </div>
            <div class="solde-bar"><div class="solde-fill" style="width:80%"></div></div>
            <div class="solde-label">8 jours restants · 2 pris</div>
          </div>
          <div class="solde-card" style="margin:0">
            <div class="solde-header">
              <span class="solde-type">Congé spécial</span>
              <span class="solde-nums"><strong>1</strong> / 5 j</span>
            </div>
            <div class="solde-bar"><div class="solde-fill warn" style="width:20%"></div></div>
            <div class="solde-label">1 jour restant · 4 pris</div>
          </div>
        </div>
      </div>

      <!-- Dernières demandes -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Mes dernières demandes</h3>
          <a href="#page-mes-conges" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Du</th><th>Au</th><th>Durée</th><th>Statut</th><th>Action</th></tr>
          </thead>
          <tbody>
            <tr>
              <td><span class="type-badge t-annuel">Annuel</span></td>
              <td class="td-muted">16 juin 2025</td>
              <td class="td-muted">20 juin 2025</td>
              <td class="td-mono">5 j</td>
              <td><span class="statut s-attente">en attente</span></td>
              <td><button class="btn-sm btn-cancel"><i class="bi bi-x"></i> Annuler</button></td>
            </tr>
            <tr>
              <td><span class="type-badge t-maladie">Maladie</span></td>
              <td class="td-muted">2 juin 2025</td>
              <td class="td-muted">3 juin 2025</td>
              <td class="td-mono">2 j</td>
              <td><span class="statut s-approuvee">approuvée</span></td>
              <td><span class="td-muted" style="font-size:.75rem">—</span></td>
            </tr>
            <tr>
              <td><span class="type-badge t-annuel">Annuel</span></td>
              <td class="td-muted">12 mai 2025</td>
              <td class="td-muted">16 mai 2025</td>
              <td class="td-mono">5 j</td>
              <td><span class="statut s-approuvee">approuvée</span></td>
              <td><span class="td-muted" style="font-size:.75rem">—</span></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4</div>
  </div>

</div>
</section>
</body>
</html>