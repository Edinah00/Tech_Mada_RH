

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — RégimeSport Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<section id="page-liste-rh" style="margin-top:3rem">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="#page-dashboard-rh"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li>
        <a href="#page-liste-rh" class="active">
          <i class="bi bi-inbox"></i> Demandes à traiter
          <span class="nav-badge alert">4</span>
        </a>
      </li>
      <li><a href="#page-liste-rh"><i class="bi bi-archive"></i> Historique</a></li>
      <li><a href="#page-liste-rh"><i class="bi bi-people"></i> Soldes employés</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-blue">MR</div>
        <div><div class="user-name">Marie Rabe</div><div class="user-role">Responsable RH</div></div>
        <a href="#page-login" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Demandes à traiter</div>
        <div class="topbar-breadcrumb"><a href="#page-dashboard-rh">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.8rem;color:var(--muted);background:var(--warn-bg);border:1px solid var(--warn-br);border-radius:6px;padding:5px 10px;display:flex;align-items:center;gap:5px;color:var(--warn)">
          <i class="bi bi-hourglass-split"></i> 4 en attente
        </span>
      </div>
    </div>

    <div class="content">

      <!-- Flash -->
      <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        Demande de Soa Rakoto approuvée. Son solde a été mis à jour automatiquement.
      </div>

      <!-- Filtre -->
      <div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--forest);background:var(--forest);color:var(--white);cursor:pointer">Tous (8)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">En attente (4)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">Approuvées (3)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">Refusées (1)</button>
        <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto;margin-left:auto">
          <option>Tous les départements</option>
          <option>IT</option>
          <option>Finance</option>
          <option>Marketing</option>
        </select>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Toutes les demandes</h3></div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <!-- En attente — actions disponibles -->
            <tr>
              <td>
                <div class="profile-row">
                  <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem">SR</div>
                  <div class="profile-info">
                    <div class="pname">Soa Rakoto</div>
                    <div class="pdept">IT · 23 juin → 27 juin</div>
                  </div>
                </div>
              </td>
              <td><span class="type-badge t-annuel">Annuel</span></td>
              <td class="td-muted" style="font-size:.8rem">23/06 – 27/06/2025</td>
              <td class="td-mono">5 j</td>
              <td>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--success);font-weight:500">18 j</span>
                <span style="font-size:.72rem;color:var(--muted)"> dispo</span>
              </td>
              <td><span class="statut s-attente">en attente</span></td>
              <td>
                <div class="action-btns">
                  <button class="btn-sm btn-approve"><i class="bi bi-check-lg"></i> Approuver</button>
                  <button class="btn-sm btn-refuse"><i class="bi bi-x-lg"></i> Refuser</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="profile-row">
                  <div class="avatar av-amber" style="width:32px;height:32px;font-size:.7rem">TF</div>
                  <div class="profile-info">
                    <div class="pname">Tsiry Fidy</div>
                    <div class="pdept">Finance</div>
                  </div>
                </div>
              </td>
              <td><span class="type-badge t-maladie">Maladie</span></td>
              <td class="td-muted" style="font-size:.8rem">18/06 – 19/06/2025</td>
              <td class="td-mono">2 j</td>
              <td>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--warn);font-weight:500">1 j</span>
                <span style="font-size:.72rem;color:var(--danger)"> ⚠ insuffisant</span>
              </td>
              <td><span class="statut s-attente">en attente</span></td>
              <td>
                <div class="action-btns">
                  <button class="btn-sm btn-approve" disabled style="opacity:.4;cursor:not-allowed"><i class="bi bi-check-lg"></i> Approuver</button>
                  <button class="btn-sm btn-refuse"><i class="bi bi-x-lg"></i> Refuser</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="profile-row">
                  <div class="avatar av-blue" style="width:32px;height:32px;font-size:.7rem">HA</div>
                  <div class="profile-info">
                    <div class="pname">Haja Andria</div>
                    <div class="pdept">Marketing</div>
                  </div>
                </div>
              </td>
              <td><span class="type-badge t-annuel">Annuel</span></td>
              <td class="td-muted" style="font-size:.8rem">30/06 – 04/07/2025</td>
              <td class="td-mono">5 j</td>
              <td>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--success);font-weight:500">22 j</span>
                <span style="font-size:.72rem;color:var(--muted)"> dispo</span>
              </td>
              <td><span class="statut s-attente">en attente</span></td>
              <td>
                <div class="action-btns">
                  <button class="btn-sm btn-approve"><i class="bi bi-check-lg"></i> Approuver</button>
                  <button class="btn-sm btn-refuse"><i class="bi bi-x-lg"></i> Refuser</button>
                </div>
              </td>
            </tr>
            <!-- Déjà traitées -->
            <tr>
              <td>
                <div class="profile-row">
                  <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem">SR</div>
                  <div class="profile-info"><div class="pname">Soa Rakoto</div><div class="pdept">IT</div></div>
                </div>
              </td>
              <td><span class="type-badge t-maladie">Maladie</span></td>
              <td class="td-muted" style="font-size:.8rem">02/06 – 03/06/2025</td>
              <td class="td-mono">2 j</td>
              <td><span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--muted)">—</span></td>
              <td><span class="statut s-approuvee">approuvée</span></td>
              <td><span class="td-muted" style="font-size:.75rem">Traité par Marie R.</span></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal refus (inline, visible ici pour le template) -->
      <div style="margin-top:1.5rem">
        <div class="form-section" style="border-color:var(--danger-br);background:var(--danger-bg)">
          <h3 style="color:var(--danger)"><i class="bi bi-x-circle"></i> Confirmer le refus — Tsiry Fidy</h3>
          <div style="font-size:.875rem;color:var(--ink);margin-bottom:1rem">
            Demande de <strong>2 jours</strong> du 18 au 19 juin 2025 · Type : Maladie<br>
            <span style="font-size:.8rem;color:var(--danger)"><i class="bi bi-exclamation-triangle"></i> Solde insuffisant : 1 jour disponible, 2 demandés.</span>
          </div>
          <div class="f-group">
            <label class="f-label">Commentaire pour l'employé (optionnel)</label>
            <textarea class="f-textarea" placeholder="Ex : Solde insuffisant, veuillez contacter les RH pour un congé sans solde.">Solde insuffisant. Solde maladie restant : 1 jour.</textarea>
          </div>
          <div class="form-actions">
            <button class="btn-sm btn-refuse" style="padding:9px 16px;font-size:.875rem"><i class="bi bi-x-lg"></i> Confirmer le refus</button>
            <button class="btn-secondary"><i class="bi bi-arrow-left"></i> Annuler</button>
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