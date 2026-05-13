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
        <div class="topbar-title">Mes demandes de congé</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions"><a href="/employe/conge/create" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a></div>
    </div>
    <div class="content">
      <div class="data-card">
        <div class="data-card-head"><h3>Mes demandes</h3></div>
        <table class="tbl">
          <thead><tr><th>Type</th><th>Période</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th></tr></thead>
          <tbody>
            <?php foreach ($conges as $c): ?>
              <tr>
                <td><span class="type-badge"><?= esc($c['type_libelle']) ?></span></td>
                <td class="td-muted"><?= date('d/m/Y', strtotime($c['date_debut'])) ?> - <?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
                <td class="td-mono"><?= (int)$c['nb_jours'] ?> j</td>
                <td><span class="statut <?= $c['statut'] === 'en_attente' ? 's-attente' : ($c['statut'] === 'approuvee' ? 's-approuvee' : 's-refusee') ?>"><?= esc($c['statut']) ?></span></td>
                <td class="td-muted"><?= esc($c['commentaire_rh'] ?? '—') ?></td>
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
