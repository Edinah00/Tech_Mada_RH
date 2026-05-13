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
        <div class="topbar-title">Nouvelle demande de congé</div>
        <div class="topbar-breadcrumb"><a href="/employe/dashboard">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande</div>
      </div>
    </div>
    <div class="content">
      <?= view('layout/flash') ?>
      <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">
        <div class="form-section">
          <h3>Détails de la demande</h3>
          <form method="POST" action="/employe/conge/store">
            <?= csrf_field() ?>
            <div class="f-group">
              <label class="f-label">Type de congé</label>
              <select name="type_conge_id" class="f-select" required>
                <option value="">-- Choisir un type --</option>
                <?php foreach ($types as $t): ?>
                  <option value="<?= $t['id'] ?>" <?= old('type_conge_id') == $t['id'] ? 'selected' : '' ?>><?= esc($t['libelle']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-grid-2">
              <div class="f-group"><label class="f-label">Date de début</label><input type="date" name="date_debut" class="f-input" value="<?= esc(old('date_debut')) ?>" required></div>
              <div class="f-group"><label class="f-label">Date de fin</label><input type="date" name="date_fin" class="f-input" value="<?= esc(old('date_fin')) ?>" required></div>
            </div>
            <div class="f-group">
              <label class="f-label">Motif</label>
              <textarea name="motif" class="f-textarea" placeholder="Précisez le motif..."><?= esc(old('motif')) ?></textarea>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn-forest"><i class="bi bi-send"></i> Soumettre la demande</button>
            </div>
          </form>
        </div>
        <div class="data-card" style="margin:0">
          <div class="data-card-head"><h3>Vos soldes actuels</h3></div>
          <div style="padding:1rem 1.1rem">
            <?php foreach ($soldes as $s): ?>
              <?php $restant = (int)$s['jours_attribues'] - (int)$s['jours_pris']; $pct = $s['jours_attribues'] > 0 ? (($restant / $s['jours_attribues']) * 100) : 0; ?>
              <div class="solde-card" style="margin:0 0 1rem">
                <div class="solde-header"><span class="solde-type"><?= esc($s['libelle']) ?></span><span class="solde-nums"><strong><?= $restant ?></strong> / <?= (int)$s['jours_attribues'] ?> j</span></div>
                <div class="solde-bar"><div class="solde-fill" style="width:<?= max(0, min(100, $pct)) ?>%"></div></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>
</div>
</body>
</html>
