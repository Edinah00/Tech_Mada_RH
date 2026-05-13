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
        <div class="topbar-title">Types de congé</div>
        <div class="topbar-breadcrumb">
          <a href="/admin/dashboard">Admin</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Types de congé
        </div>
      </div>
    </div>

    <div class="content">

      <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start">

        <!-- Liste des types -->
        <div class="data-card" style="margin:0">
          <div class="data-card-head"><h3>Types existants</h3></div>
          <?php if (empty($types)): ?>
            <div class="empty"><i class="bi bi-tags"></i><p>Aucun type de congé configuré.</p></div>
          <?php else: ?>
          <table class="tbl">
            <thead>
              <tr><th>Libellé</th><th>Jours/an</th><th>Déductible</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <?php foreach ($types as $t): ?>
              <tr>
                <td style="font-weight:500"><?= esc($t['libelle']) ?></td>
                <td class="td-mono"><?= (int)$t['jours_annuels'] ?> j</td>
                <td>
                  <?php if ($t['deductible']): ?>
                    <span class="statut s-approuvee">oui</span>
                  <?php else: ?>
                    <span class="statut s-annulee">non</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="action-btns">
                    <button class="btn-sm btn-edit"
                            onclick="remplirEdit(<?= $t['id'] ?>, '<?= esc($t['libelle'],  'attr') ?>', <?= $t['jours_annuels'] ?>, <?= $t['deductible'] ?>)">
                      <i class="bi bi-pencil"></i> Éditer
                    </button>
                    <form method="POST" action="/admin/type-conge/delete/<?= $t['id'] ?>"
                          onsubmit="return confirm('Supprimer ce type ?')" style="display:inline">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn-sm btn-del"><i class="bi bi-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>

        <!-- Formulaire ajout / édition -->
        <div style="display:flex;flex-direction:column;gap:1rem">

          <!-- Ajout -->
          <div class="form-section" style="margin:0">
            <h3><i class="bi bi-plus-circle" style="color:var(--forest);margin-right:6px"></i>Nouveau type</h3>
            <form method="POST" action="/admin/type-conge/store">
              <?= csrf_field() ?>
              <div class="f-group">
                <label class="f-label">Libellé <span style="color:var(--danger)">*</span></label>
                <input type="text" name="libelle" class="f-input" placeholder="Ex : Congé annuel" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Jours attribués par an</label>
                <input type="number" name="jours_annuels" class="f-input" value="30" min="0" max="365"/>
              </div>
              <div class="f-group">
                <label class="f-label">Déductible du solde ?</label>
                <select name="deductible" class="f-select">
                  <option value="1">Oui — déduit du solde</option>
                  <option value="0">Non — sans solde</option>
                </select>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn-forest"><i class="bi bi-plus"></i> Créer</button>
              </div>
            </form>
          </div>

          <!-- Édition (masqué par défaut) -->
          <div class="form-section" id="form-edit" style="margin:0;display:none;border-color:var(--forest)">
            <h3><i class="bi bi-pencil" style="color:var(--forest);margin-right:6px"></i>Modifier le type</h3>
            <form method="POST" id="form-edit-action" action="">
              <?= csrf_field() ?>
              <div class="f-group">
                <label class="f-label">Libellé</label>
                <input type="text" name="libelle" id="edit-libelle" class="f-input" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Jours / an</label>
                <input type="number" name="jours_annuels" id="edit-jours" class="f-input" min="0"/>
              </div>
              <div class="f-group">
                <label class="f-label">Déductible ?</label>
                <select name="deductible" id="edit-deductible" class="f-select">
                  <option value="1">Oui</option>
                  <option value="0">Non</option>
                </select>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn-forest"><i class="bi bi-check"></i> Enregistrer</button>
                <button type="button" class="btn-secondary" onclick="document.getElementById('form-edit').style.display='none'">
                  <i class="bi bi-x"></i> Annuler
                </button>
              </div>
            </form>
          </div>

          <div class="flash flash-info" style="margin:0">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size:.8rem">
              Les types déductibles diminuent le solde de l'employé lors de l'approbation.
              Les types "Sans solde" n'ont pas de compteur de jours.
            </span>
          </div>
        </div>

      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>

<script>
function remplirEdit(id, libelle, jours, deductible) {
  document.getElementById('form-edit').style.display = 'block';
  document.getElementById('form-edit-action').action = '/admin/type-conge/update/' + id;
  document.getElementById('edit-libelle').value      = libelle;
  document.getElementById('edit-jours').value        = jours;
  document.getElementById('edit-deductible').value   = deductible;
  document.getElementById('form-edit').scrollIntoView({behavior:'smooth'});
}
</script>
</body>
</html>