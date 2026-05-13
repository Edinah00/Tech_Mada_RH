<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — TechMada RH</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="app-wrap">

  <?= view('layout/sidebar') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Gestion des employés</div>
        <div class="topbar-breadcrumb">
          <a href="/admin/stats">Admin</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employés
        </div>
      </div>
      <div class="topbar-actions">
        <button class="btn-forest" style="padding:7px 14px;font-size:.82rem"
                onclick="document.getElementById('form-ajout').scrollIntoView({behavior:'smooth'})">
          <i class="bi bi-person-plus"></i> Ajouter
        </button>
      </div>
    </div>

    <div class="content">

      <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <!-- Liste des employés -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Tous les employés (<?= count($employes) ?>)</h3>
          <input type="text" id="search-emp" class="f-input" placeholder="Rechercher..."
                 style="width:200px;padding:6px 10px;font-size:.8rem"
                 oninput="filtrerEmployes(this.value)"/>
        </div>
        <table class="tbl" id="tbl-employes">
          <thead>
            <tr>
              <th>Employé</th><th>Email</th><th>Département</th>
              <th>Rôle</th><th>Embauche</th><th>Statut</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($employes as $e): ?>
            <tr data-search="<?= strtolower(esc($e['prenom'].' '.$e['nom'].' '.$e['email'], 'attr')) ?>">
              <td>
                <div style="display:flex;align-items:center;gap:8px">
                  <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem">
                    <?= strtoupper(substr($e['prenom'],0,1).substr($e['nom'],0,1)) ?>
                  </div>
                  <div>
                    <div style="font-weight:500;font-size:.875rem"><?= esc($e['prenom'].' '.$e['nom']) ?></div>
                  </div>
                </div>
              </td>
              <td class="td-muted" style="font-size:.8rem"><?= esc($e['email']) ?></td>
              <td class="td-muted"><?= esc($e['departement_nom'] ?? '—') ?></td>
              <td>
                <?php
                  $roleCls = match($e['role']) {
                    'admin'   => 'background:#f0e8fb;color:#5a2d82',
                    'rh'      => 'background:var(--info-bg);color:var(--info)',
                    default   => 'background:var(--cream);color:var(--muted)',
                  };
                ?>
                <span class="type-badge" style="<?= $roleCls ?>"><?= esc($e['role']) ?></span>
              </td>
              <td class="td-muted td-mono" style="font-size:.78rem">
                <?= $e['date_embauche'] ? date('d/m/Y', strtotime($e['date_embauche'])) : '—' ?>
              </td>
              <td>
                <?php if ($e['actif']): ?>
                  <span class="statut s-approuvee" style="font-size:.68rem">actif</span>
                <?php else: ?>
                  <span class="statut s-annulee" style="font-size:.68rem">inactif</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="action-btns">
                  <button class="btn-sm btn-edit"
                          onclick="remplirEdit(<?= $e['id'] ?>, '<?= esc($e['nom'],'attr') ?>', '<?= esc($e['prenom'],'attr') ?>', '<?= esc($e['email'],'attr') ?>', '<?= $e['role'] ?>', '<?= $e['departement_id'] ?>', '<?= $e['date_embauche'] ?>')">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn-sm btn-view"
                          onclick="remplirSolde(<?= $e['id'] ?>, '<?= esc($e['prenom'].' '.$e['nom'], 'attr') ?>')">
                    <i class="bi bi-calendar-check"></i> Solde
                  </button>
                  <form method="POST" action="/admin/employe/desactiver/<?= $e['id'] ?>"
                        onsubmit="return confirm('<?= $e['actif'] ? 'Désactiver' : 'Réactiver' ?> cet employé ?')"
                        style="display:inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-sm <?= $e['actif'] ? 'btn-del' : 'btn-approve' ?>">
                      <i class="bi bi-<?= $e['actif'] ? 'slash-circle' : 'arrow-counterclockwise' ?>"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Formulaire ajout -->
      <div class="form-section" id="form-ajout">
        <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>
        <form method="POST" action="/admin/employe/store">
          <?= csrf_field() ?>
          <div class="form-grid-2" style="margin-bottom:1rem">
            <div class="f-group">
              <label class="f-label">Prénom <span style="color:var(--danger)">*</span></label>
              <input type="text" name="prenom" class="f-input" required/>
            </div>
            <div class="f-group">
              <label class="f-label">Nom <span style="color:var(--danger)">*</span></label>
              <input type="text" name="nom" class="f-input" required/>
            </div>
            <div class="f-group">
              <label class="f-label">Email <span style="color:var(--danger)">*</span></label>
              <input type="email" name="email" class="f-input" required/>
            </div>
            <div class="f-group">
              <label class="f-label">Mot de passe initial <span style="color:var(--danger)">*</span></label>
              <input type="password" name="password" class="f-input" required/>
            </div>
            <div class="f-group">
              <label class="f-label">Département</label>
              <select name="departement_id" class="f-select">
                <option value="">— Choisir —</option>
                <?php foreach ($depts as $d): ?>
                  <option value="<?= $d['id'] ?>"><?= esc($d['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">Rôle</label>
              <select name="role" class="f-select">
                <option value="employe">Employé</option>
                <option value="rh">Responsable RH</option>
                <option value="admin">Administrateur</option>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">Date d'embauche</label>
              <input type="date" name="date_embauche" class="f-input" value="<?= date('Y-m-d') ?>"/>
            </div>
          </div>
          <div class="flash flash-info" style="margin-bottom:1rem">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size:.82rem">Les soldes seront initialisés automatiquement selon les types de congé configurés.</span>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn-forest"><i class="bi bi-plus"></i> Créer l'employé</button>
          </div>
        </form>
      </div>

      <!-- Modal édition employé -->
      <div id="modal-edit" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:100;display:none;align-items:center;justify-content:center">
        <div style="background:var(--white);border-radius:14px;padding:2rem;width:520px;max-width:95vw;max-height:90vh;overflow-y:auto">
          <h3 style="font-family:'Playfair Display',serif;margin:0 0 1.25rem">Modifier l'employé</h3>
          <form method="POST" id="form-edit-emp" action="">
            <?= csrf_field() ?>
            <div class="form-grid-2">
              <div class="f-group">
                <label class="f-label">Prénom</label>
                <input type="text" name="prenom" id="edit-prenom" class="f-input" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Nom</label>
                <input type="text" name="nom" id="edit-nom" class="f-input" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Email</label>
                <input type="email" name="email" id="edit-email" class="f-input" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Nouveau mot de passe <span style="font-size:.72rem;color:var(--muted)">(laisser vide = inchangé)</span></label>
                <input type="password" name="password" class="f-input" placeholder="••••••••"/>
              </div>
              <div class="f-group">
                <label class="f-label">Département</label>
                <select name="departement_id" id="edit-dept" class="f-select">
                  <option value="">— Choisir —</option>
                  <?php foreach ($depts as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= esc($d['nom']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="f-group">
                <label class="f-label">Rôle</label>
                <select name="role" id="edit-role" class="f-select">
                  <option value="employe">Employé</option>
                  <option value="rh">Responsable RH</option>
                  <option value="admin">Administrateur</option>
                </select>
              </div>
              <div class="f-group">
                <label class="f-label">Date d'embauche</label>
                <input type="date" name="date_embauche" id="edit-embauche" class="f-input"/>
              </div>
            </div>
            <div class="form-actions" style="margin-top:1rem">
              <button type="submit" class="btn-forest"><i class="bi bi-check"></i> Enregistrer</button>
              <button type="button" class="btn-secondary" onclick="fermerModal('modal-edit')">
                <i class="bi bi-x"></i> Annuler
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal ajustement solde -->
      <div id="modal-solde" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:100;align-items:center;justify-content:center">
        <div style="background:var(--white);border-radius:14px;padding:2rem;width:420px;max-width:95vw">
          <h3 style="font-family:'Playfair Display',serif;margin:0 0 .5rem">Ajuster le solde</h3>
          <p id="solde-nom" style="color:var(--muted);font-size:.85rem;margin:0 0 1.25rem"></p>
          <form method="POST" id="form-solde" action="">
            <?= csrf_field() ?>
            <div class="f-group">
              <label class="f-label">Type de congé</label>
              <select name="type_conge_id" class="f-select">
                <?php
                  $types = (new \App\Models\TypeCongeModel())->findAll();
                  foreach ($types as $t):
                ?>
                  <option value="<?= $t['id'] ?>"><?= esc($t['libelle']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">Année</label>
              <input type="number" name="annee" class="f-input" value="<?= date('Y') ?>"/>
            </div>
            <div class="f-group">
              <label class="f-label">Jours attribués (nouveau total)</label>
              <input type="number" name="jours_attribues" class="f-input" value="30" min="0"/>
            </div>
            <div class="flash flash-warn" style="margin-bottom:1rem">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <span style="font-size:.8rem">Les jours déjà pris ne sont pas modifiés. Seul le quota total change.</span>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn-forest"><i class="bi bi-check"></i> Appliquer</button>
              <button type="button" class="btn-secondary" onclick="fermerModal('modal-solde')">
                <i class="bi bi-x"></i> Annuler
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>

<script>
function remplirEdit(id, nom, prenom, email, role, deptId, embauche) {
  document.getElementById('form-edit-emp').action = '/admin/employe/update/' + id;
  document.getElementById('edit-nom').value      = nom;
  document.getElementById('edit-prenom').value   = prenom;
  document.getElementById('edit-email').value    = email;
  document.getElementById('edit-role').value     = role;
  document.getElementById('edit-dept').value     = deptId;
  document.getElementById('edit-embauche').value = embauche;
  ouvrirModal('modal-edit');
}

function remplirSolde(id, nom) {
  document.getElementById('form-solde').action = '/admin/employe/solde/' + id;
  document.getElementById('solde-nom').textContent = nom;
  ouvrirModal('modal-solde');
}

function ouvrirModal(id) {
  const m = document.getElementById(id);
  m.style.display = 'flex';
}

function fermerModal(id) {
  document.getElementById(id).style.display = 'none';
}

// Fermer en cliquant hors de la modal
['modal-edit','modal-solde'].forEach(id => {
  document.getElementById(id).addEventListener('click', function(e) {
    if (e.target === this) fermerModal(id);
  });
});

function filtrerEmployes(q) {
  q = q.toLowerCase();
  document.querySelectorAll('#tbl-employes tbody tr').forEach(tr => {
    tr.style.display = tr.dataset.search.includes(q) ? '' : 'none';
  });
}
</script>
</body>
</html>
