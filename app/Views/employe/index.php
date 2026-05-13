

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — RégimeSport Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<section id="page-mes-conges" style="margin-top:3rem">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
      <li><a href="#page-dashboard-employe"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="#page-form-conge"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="#page-mes-conges" class="active"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
      <li><a href="#page-profil-employe"><i class="bi bi-person"></i> Mon profil</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green">SR</div>
        <div><div class="user-name">Soa Rakoto</div><div class="user-role">Employé · IT</div></div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mes demandes de congé</div>
        <div class="topbar-breadcrumb"><a href="#page-dashboard-employe">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes</div>
      </div>
      <div class="topbar-actions">
        <a href="#page-form-conge" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
      </div>
    </div>

    <div class="content">
      <div class="data-card">
        <div class="data-card-head">
          <h3>Toutes mes demandes</h3>
          <div style="display:flex;gap:6px">
            <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option>Tous les statuts</option>
              <option>En attente</option>
              <option>Approuvée</option>
              <option>Refusée</option>
              <option>Annulée</option>
            </select>
          </div>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr>
          </thead>
          <tbody>
            <tr>
              <td><span class="type-badge t-annuel">Annuel</span></td>
              <td class="td-muted">23 juin 2025</td>
              <td class="td-muted">27 juin 2025</td>
              <td class="td-mono">5 j</td>
              <td><span class="statut s-attente">en attente</span></td>
              <td class="td-muted" style="font-size:.78rem">—</td>
              <td><button class="btn-sm btn-cancel"><i class="bi bi-x"></i> Annuler</button></td>
            </tr>
            <tr>
              <td><span class="type-badge t-maladie">Maladie</span></td>
              <td class="td-muted">2 juin 2025</td>
              <td class="td-muted">3 juin 2025</td>
              <td class="td-mono">2 j</td>
              <td><span class="statut s-approuvee">approuvée</span></td>
              <td style="font-size:.78rem;color:var(--success)"><i class="bi bi-check-circle"></i> Validé</td>
              <td><span class="td-muted" style="font-size:.75rem">—</span></td>
            </tr>
            <tr>
              <td><span class="type-badge t-annuel">Annuel</span></td>
              <td class="td-muted">12 mai 2025</td>
              <td class="td-muted">16 mai 2025</td>
              <td class="td-mono">5 j</td>
              <td><span class="statut s-approuvee">approuvée</span></td>
              <td style="font-size:.78rem;color:var(--success)"><i class="bi bi-check-circle"></i> OK</td>
              <td><span class="td-muted" style="font-size:.75rem">—</span></td>
            </tr>
            <tr>
              <td><span class="type-badge t-special">Spécial</span></td>
              <td class="td-muted">5 avr. 2025</td>
              <td class="td-muted">5 avr. 2025</td>
              <td class="td-mono">1 j</td>
              <td><span class="statut s-refusee">refusée</span></td>
              <td style="font-size:.78rem;color:var(--danger)">Chevauchement détecté</td>
              <td><span class="td-muted" style="font-size:.75rem">—</span></td>
            </tr>
            <tr>
              <td><span class="type-badge t-sans-solde">Sans solde</span></td>
              <td class="td-muted">10 mars 2025</td>
              <td class="td-muted">12 mars 2025</td>
              <td class="td-mono">3 j</td>
              <td><span class="statut s-annulee">annulée</span></td>
              <td class="td-muted" style="font-size:.78rem">Annulé par l'employé</td>
              <td><span class="td-muted" style="font-size:.75rem">—</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>
</body>
</html>