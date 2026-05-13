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
        <div class="topbar-title">Tableau de bord</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions">
        <a href="/employe/conge/create" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
      </div>
    </div>
    <div class="content">
      <div class="metrics">
        <div class="metric"><div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div><div class="metric-val"><?= (int)$stats['en_attente'] ?></div><div class="metric-label">En attente</div></div>
        <div class="metric"><div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div><div class="metric-val"><?= (int)$stats['approuvee'] ?></div><div class="metric-label">Approuvées</div></div>
        <div class="metric"><div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div><div class="metric-val"><?= (int)$joursRestants ?></div><div class="metric-label">Jours restants</div></div>
        <div class="metric"><div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div><div class="metric-val"><?= (int)$stats['refusee'] ?></div><div class="metric-label">Refusées</div></div>
      </div>
      <div class="data-card">
        <div class="data-card-head"><h3>Mes soldes de congés</h3></div>
        <table class="tbl">
          <thead><tr><th>Type</th><th>Attribués</th><th>Pris</th><th>Restant</th></tr></thead>
          <tbody>
            <?php foreach ($soldes as $s): $restant = (int)$s['jours_attribues'] - (int)$s['jours_pris']; ?>
              <tr>
                <td><span class="type-badge"><?= esc($s['libelle']) ?></span></td>
                <td class="td-mono"><?= (int)$s['jours_attribues'] ?> j</td>
                <td class="td-mono"><?= (int)$s['jours_pris'] ?> j</td>
                <td class="td-mono"><?= $restant ?> j</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="data-card">
        <div class="data-card-head"><h3>Dernières demandes</h3></div>
        <table class="tbl">
          <thead><tr><th>Type</th><th>Période</th><th>Durée</th><th>Statut</th></tr></thead>
          <tbody>
            <?php foreach ($conges as $c): ?>
              <tr>
                <td><span class="type-badge"><?= esc($c['type_libelle']) ?></span></td>
                <td class="td-muted"><?= date('d/m/Y', strtotime($c['date_debut'])) ?> - <?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
                <td class="td-mono"><?= (int)$c['nb_jours'] ?> j</td>
                <td><span class="statut <?= $c['statut'] === 'en_attente' ? 's-attente' : ($c['statut'] === 'approuvee' ? 's-approuvee' : 's-refusee') ?>"><?= esc($c['statut']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>
</div>
</body>
</html>
