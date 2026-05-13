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
     ║  PAGE 7 — GESTION EMPLOYÉS  (admin/employes.php)            ║
     ╚══════════════════════════════════════════════════════════════╝ -->
<section id="page-admin-employes" style="margin-top:3rem">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Administration</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
      <li><a href="#page-dashboard-admin"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li><a href="#page-liste-rh"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
      <li><a href="#page-admin-employes" class="active"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="#page-admin-employes"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="#page-admin-employes"><i class="bi bi-tags"></i> Types de congé</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar" style="background:#5a2d82;width:32px;height:32px;font-size:.7rem">AD</div>
        <div><div class="user-name">Administrateur</div><div class="user-role">Admin système</div></div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Gestion des employés</div>
        <div class="topbar-breadcrumb"><a href="#page-dashboard-admin">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employés</div>
      </div>
      <div class="topbar-actions">
        <a href="#" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter</a>
      </div>
    </div>

    <div class="content">

      <!-- Formulaire ajout -->
      <div class="form-section">
        <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>
        <div class="form-grid-2" style="margin-bottom:1rem">
          <div class="f-group">
            <label class="f-label">Prénom</label>
            <input type="text" class="f-input" placeholder="Jean"/>
          </div>
          <div class="f-group">
            <label class="f-label">Nom</label>
            <input type="text" class="f-input" placeholder="Rakoto"/>
          </div>
          <div class="f-group">
            <label class="f-label">Email</label>
            <input type="email" class="f-input" placeholder="jean.rakoto@techmada.mg"/>
          </div>
          <div class="f-group">
            <label class="f-label">Mot de passe initial</label>
            <input type="password" class="f-input" placeholder="À communiquer à l'employé"/>
          </div>
          <div class="f-group">
            <label class="f-label">Département</label>
            <select class="f-select">
              <option>IT</option>
              <option>Finance</option>
              <option>Marketing</option>
              <option>RH</option>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Rôle</label>
            <select class="f-select">
              <option value="employe">Employé</option>
              <option value="rh">Responsable RH</option>
              <option value="admin">Administrateur</option>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Date d'embauche</label>
            <input type="date" class="f-input" value="2025-06-13"/>
          </div>
        </div>
        <div class="flash flash-info" style="margin-bottom:1rem">
          <i class="bi bi-info-circle-fill"></i>
          <span style="font-size:.82rem">Les soldes de congés seront initialisés automatiquement selon les types de congé configurés.</span>
        </div>
        <div class="form-actions">
          <button class="btn-forest"><i class="bi bi-plus"></i> Créer l'employé</button>
          <button class="btn-secondary">Réinitialiser</button>
        </div>
      </div>

      <!-- Liste employés -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Tous les employés</h3>
          <div style="display:flex;gap:6px">
            <input type="text" class="f-input" placeholder="Rechercher..." style="width:200px;padding:6px 10px;font-size:.8rem"/>
            <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option>Tous les depts</option>
              <option>IT</option>
              <option>Finance</option>
            </select>
          </div>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Département</th><th>Rôle</th><th>Embauche</th><th>Statut</th><th>Solde annuel</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="profile-row">
                  <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem">SR</div>
                  <div class="profile-info"><div class="pname">Soa Rakoto</div><div class="pdept">soa@techmada.mg</div></div>
                </div>
              </td>
              <td class="td-muted">IT</td>
              <td><span class="type-badge" style="background:#f1efe8;color:#444441">employe</span></td>
              <td class="td-muted td-mono" style="font-size:.78rem">2022-03-01</td>
              <td><span class="statut s-approuvee" style="font-size:.68rem">actif</span></td>
              <td><span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)">18 / 30 j</span></td>
              <td>
                <div class="action-btns">
                  <button class="btn-sm btn-edit"><i class="bi bi-pencil"></i> Éditer</button>
                  <button class="btn-sm btn-del"><i class="bi bi-slash-circle"></i></button>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="profile-row">
                  <div class="avatar av-blue" style="width:32px;height:32px;font-size:.68rem">MR</div>
                  <div class="profile-info"><div class="pname">Marie Rabe</div><div class="pdept">rh@techmada.mg</div></div>
                </div>
              </td>
              <td class="td-muted">RH</td>
              <td><span class="type-badge t-maladie">rh</span></td>
              <td class="td-muted td-mono" style="font-size:.78rem">2020-01-15</td>
              <td><span class="statut s-approuvee" style="font-size:.68rem">actif</span></td>
              <td><span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)">25 / 30 j</span></td>
              <td>
                <div class="action-btns">
                  <button class="btn-sm btn-edit"><i class="bi bi-pencil"></i> Éditer</button>
                  <button class="btn-sm btn-del"><i class="bi bi-slash-circle"></i></button>
                </div>
              </td>
            </tr>
            <tr style="opacity:.5">
              <td>
                <div class="profile-row">
                  <div class="avatar av-amber" style="width:32px;height:32px;font-size:.68rem">TF</div>
                  <div class="profile-info"><div class="pname">Tsiry Fidy</div><div class="pdept">tsiry@techmada.mg</div></div>
                </div>
              </td>
              <td class="td-muted">Finance</td>
              <td><span class="type-badge" style="background:#f1efe8;color:#444441">employe</span></td>
              <td class="td-muted td-mono" style="font-size:.78rem">2019-07-10</td>
              <td><span class="statut s-annulee" style="font-size:.68rem">inactif</span></td>
              <td><span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--muted)">— / — j</span></td>
              <td>
                <div class="action-btns">
                  <button class="btn-sm btn-view"><i class="bi bi-arrow-counterclockwise"></i> Réactiver</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>
<script>
function filterUsers(query) {
    query = query.toLowerCase();
    document.querySelectorAll('#users-table tr[data-search]').forEach(row => {
        row.style.display = row.dataset.search.includes(query) ? '' : 'none';
    });
}
</script>
</body>
</html>
