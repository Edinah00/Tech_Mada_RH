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
        <div class="topbar-title">Soldes des employés</div>
        <div class="topbar-breadcrumb">
          <a href="/rh">RH</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Soldes
        </div>
      </div>
      <div class="topbar-actions">
        <form method="GET" action="/rh/soldes" style="display:flex;align-items:center;gap:8px">
          <label for="annee" style="font-size:.8rem;color:var(--muted)">Année</label>
          <input id="annee" name="annee" type="number" class="f-input" value="<?= esc($annee) ?>" min="2000" max="2100" style="width:110px;padding:6px 10px;font-size:.8rem"/>
          <button type="submit" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
      </div>
    </div>

    <div class="content">
      <div class="data-card">
        <div class="data-card-head">
          <h3>Soldes pour l'année <?= esc($annee) ?></h3>
          <span style="font-size:.8rem;color:var(--muted)"><?= count($soldes) ?> ligne(s)</span>
        </div>

        <?php if (empty($soldes)): ?>
          <div class="empty"><i class="bi bi-wallet2"></i><p>Aucun solde trouvé pour cette année.</p></div>
        <?php else: ?>
          <table class="tbl">
            <thead>
              <tr>
                <th>Employé</th>
                <th>Département</th>
                <th>Type</th>
                <th>Attribués</th>
                <th>Pris</th>
                <th>Restant</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($soldes as $s): ?>
                <?php $restant = (int)$s['jours_attribues'] - (int)$s['jours_pris']; ?>
                <tr>
                  <td>
                    <div style="display:flex;align-items:center;gap:8px">
                      <div class="avatar av-green" style="width:30px;height:30px;font-size:.66rem">
                        <?= strtoupper(substr($s['prenom'],0,1).substr($s['nom'],0,1)) ?>
                      </div>
                      <span style="font-weight:500;font-size:.88rem"><?= esc($s['prenom'].' '.$s['nom']) ?></span>
                    </div>
                  </td>
                  <td class="td-muted" style="font-size:.8rem"><?= esc($s['dept_nom'] ?? '—') ?></td>
                  <td><span class="type-badge"><?= esc($s['libelle']) ?></span></td>
                  <td class="td-mono"><?= (int)$s['jours_attribues'] ?> j</td>
                  <td class="td-mono"><?= (int)$s['jours_pris'] ?> j</td>
                  <td class="td-mono" style="color:<?= $restant <= 2 ? 'var(--danger)' : 'var(--forest)' ?>"><?= $restant ?> j</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

      <div class="flash flash-info" style="margin-top:1rem">
        <i class="bi bi-info-circle-fill"></i>
        <span style="font-size:.8rem">Les soldes affichés ici reflètent les congés déduits après approbation.</span>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</body>
</html>
