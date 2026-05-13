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
        <div class="topbar-title">Demandes à traiter</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.8rem;color:var(--muted)"><?= count($conges) ?> demande(s)</span>
      </div>
    </div>

    <div class="content">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <div class="data-card">
        <div class="data-card-head">
          <h3>Demandes en attente</h3>
          <span style="color:var(--muted);font-size:.8rem"><?= count($conges) ?> demande(s)</span>
        </div>
        <?php if (empty($conges)): ?>
          <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande à traiter.</p></div>
        <?php else: ?>
          <table class="tbl">
            <thead>
              <tr>
                <th>Employé</th>
                <th>Type</th>
                <th>Période</th>
                <th>Durée</th>
                <th>Solde dispo</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($conges as $c): ?>
                <?php
                  $soldeDispo = isset($c['solde_dispo']) ? (int)$c['solde_dispo'] : 0;
                  $isBlocked  = $soldeDispo < (int)$c['nb_jours'];
                ?>
                <tr>
                  <td>
                    <div style="font-weight:600"><?= esc($c['prenom'].' '.$c['nom']) ?></div>
                    <div class="td-muted" style="font-size:.75rem"><?= esc($c['dept_nom'] ?? '—') ?></div>
                  </td>
                  <td><span class="type-badge"><?= esc($c['type_libelle']) ?></span></td>
                  <td class="td-muted"><?= date('d/m/Y', strtotime($c['date_debut'])) ?> - <?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
                  <td class="td-mono"><?= (int)$c['nb_jours'] ?> j</td>
                  <td class="td-mono" style="color:<?= $isBlocked ? 'var(--danger)' : 'var(--forest)' ?>"><?= $soldeDispo ?> j</td>
                  <td><span class="statut s-attente">en attente</span></td>
                  <td>
                    <div class="action-btns" style="margin-bottom:6px">
                      <form method="POST" action="/rh/approuver/<?= $c['id'] ?>" style="display:inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="commentaire_rh" value="">
                        <button type="submit" class="btn-sm btn-approve" <?= $isBlocked ? 'disabled style="opacity:.4;cursor:not-allowed"' : '' ?>>
                          <i class="bi bi-check-lg"></i> Approuver
                        </button>
                      </form>
                      <button type="button" class="btn-sm btn-refuse" onclick="ouvrirCommentaire('refuser', <?= $c['id'] ?>, '<?= esc($c['prenom'].' '.$c['nom'], 'attr') ?>')">
                        <i class="bi bi-x-lg"></i> Refuser
                      </button>
                      <button type="button" class="btn-sm btn-del" onclick="ouvrirCommentaire('annuler', <?= $c['id'] ?>, '<?= esc($c['prenom'].' '.$c['nom'], 'attr') ?>')">
                        <i class="bi bi-slash-circle"></i> Annuler
                      </button>
                    </div>
                    <button type="button" class="btn-sm btn-view" onclick="ouvrirCommentaire('approuver', <?= $c['id'] ?>, '<?= esc($c['prenom'].' '.$c['nom'], 'attr') ?>')" <?= $isBlocked ? 'disabled style="opacity:.4;cursor:not-allowed"' : '' ?>>
                      <i class="bi bi-chat-left-text"></i> Commentaire / validation
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

      <div id="modal-action" class="form-section" style="display:none;border-color:var(--forest)">
        <h3 id="modal-title"><i class="bi bi-chat-left-text"></i> Action RH</h3>
        <div id="modal-meta" style="font-size:.85rem;color:var(--muted);margin-bottom:1rem"></div>
        <form method="POST" id="form-action" action="">
          <?= csrf_field() ?>
          <div class="f-group">
            <label class="f-label">Commentaire pour l'employé</label>
            <textarea name="commentaire_rh" id="commentaire_rh" class="f-textarea" placeholder="Optionnel"></textarea>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn-forest" id="btn-submit"><i class="bi bi-check"></i> Confirmer</button>
            <button type="button" class="btn-secondary" onclick="fermerCommentaire()"><i class="bi bi-x"></i> Annuler</button>
          </div>
        </form>
      </div>
    </div>

    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>
</div>

<script>
function ouvrirCommentaire(action, id, nom) {
  const form = document.getElementById('form-action');
  const modal = document.getElementById('modal-action');
  const title = document.getElementById('modal-title');
  const meta = document.getElementById('modal-meta');
  const btn = document.getElementById('btn-submit');

  form.action = '/rh/' + action + '/' + id;
  modal.style.display = 'block';
  meta.textContent = 'Demande de ' + nom + ' - action: ' + action;
  btn.innerHTML = action === 'approuver'
    ? '<i class="bi bi-check"></i> Approuver'
    : (action === 'refuser' ? '<i class="bi bi-x"></i> Refuser' : '<i class="bi bi-slash-circle"></i> Annuler');
  title.innerHTML = action === 'approuver'
    ? '<i class="bi bi-check-circle"></i> Approuver la demande'
    : (action === 'refuser' ? '<i class="bi bi-x-circle"></i> Refuser la demande' : '<i class="bi bi-slash-circle"></i> Annuler la demande');
  modal.scrollIntoView({behavior:'smooth'});
}
function fermerCommentaire() {
  document.getElementById('modal-action').style.display = 'none';
}
</script>
</body>
</html>
