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
        <div class="topbar-title">Gestion des départements</div>
        <div class="topbar-breadcrumb">
          <a href="/admin/stats">Admin</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Départements
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

        <div class="data-card" style="margin:0">
          <div class="data-card-head"><h3>Départements (<?= count($depts) ?>)</h3></div>
          <?php if (empty($depts)): ?>
            <div class="empty"><i class="bi bi-building"></i><p>Aucun département.</p></div>
          <?php else: ?>
          <table class="tbl">
            <thead>
              <tr><th>Nom</th><th>Description</th><th>Employés actifs</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <?php foreach ($depts as $d): ?>
              <tr>
                <td style="font-weight:500"><?= esc($d['nom']) ?></td>
                <td class="td-muted" style="font-size:.8rem"><?= esc($d['description'] ?? '—') ?></td>
                <td class="td-mono"><?= (int)$d['nb_employes'] ?></td>
                <td>
                  <div class="action-btns">
                    <button class="btn-sm btn-edit"
                            onclick="remplirEdit(<?= $d['id'] ?>, '<?= esc($d['nom'], 'attr') ?>', '<?= esc($d['description'] ?? '', 'attr') ?>')">
                      <i class="bi bi-pencil"></i> Éditer
                    </button>
                    <?php if ((int)$d['nb_employes'] === 0): ?>
                    <form method="POST" action="/admin/departement/delete/<?= $d['id'] ?>"
                          onsubmit="return confirm('Supprimer ce département ?')" style="display:inline">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn-sm btn-del"><i class="bi bi-trash"></i></button>
                    </form>
                    <?php else: ?>
                      <span class="td-muted" style="font-size:.72rem" title="Des employés sont rattachés">
                        <i class="bi bi-lock"></i>
                      </span>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="form-section" style="margin:0">
            <h3><i class="bi bi-building-add" style="color:var(--forest);margin-right:6px"></i>Nouveau département</h3>
            <form method="POST" action="/admin/departement/store">
              <?= csrf_field() ?>
              <div class="f-group">
                <label class="f-label">Nom <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nom" class="f-input" placeholder="Ex : Marketing" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Description</label>
                <input type="text" name="description" class="f-input" placeholder="Optionnel"/>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn-forest"><i class="bi bi-plus"></i> Créer</button>
              </div>
            </form>
          </div>

          <div class="form-section" id="form-edit" style="margin:0;display:none;border-color:var(--forest)">
            <h3><i class="bi bi-pencil" style="color:var(--forest);margin-right:6px"></i>Modifier</h3>
            <form method="POST" id="form-edit-action" action="">
              <?= csrf_field() ?>
              <div class="f-group">
                <label class="f-label">Nom</label>
                <input type="text" name="nom" id="edit-nom" class="f-input" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Description</label>
                <input type="text" name="description" id="edit-desc" class="f-input"/>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn-forest"><i class="bi bi-check"></i> Enregistrer</button>
                <button type="button" class="btn-secondary"
                        onclick="document.getElementById('form-edit').style.display='none'">
                  <i class="bi bi-x"></i> Annuler
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>

<script>
function remplirEdit(id, nom, desc) {
  document.getElementById('form-edit').style.display = 'block';
  document.getElementById('form-edit-action').action = '/admin/departement/update/' + id;
  document.getElementById('edit-nom').value = nom;
  document.getElementById('edit-desc').value = desc;
  document.getElementById('form-edit').scrollIntoView({behavior:'smooth'});
}
</script>
</body>
</html>
