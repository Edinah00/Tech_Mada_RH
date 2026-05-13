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
      <div><div class="topbar-title">Demandes à traiter</div><div class="topbar-breadcrumb">Accueil</div></div>
    </div>
    <div class="content">
      <div class="data-card">
        <div class="data-card-head"><h3>Demandes en attente</h3><span style="color:var(--muted);font-size:.8rem"><?= count($conges) ?> demande(s)</span></div>
        <table class="tbl">
          <thead><tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Statut</th></tr></thead>
          <tbody>
            <?php foreach ($conges as $c): ?>
              <tr>
                <td><span class="td-name"><?= esc($c['prenom'].' '.$c['nom']) ?></span><div class="td-muted" style="font-size:.75rem"><?= esc($c['dept_nom'] ?? '—') ?></div></td>
                <td><span class="type-badge"><?= esc($c['type_libelle']) ?></span></td>
                <td class="td-muted"><?= date('d/m/Y', strtotime($c['date_debut'])) ?> - <?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
                <td class="td-mono"><?= (int)$c['nb_jours'] ?> j</td>
                <td class="td-mono"><?= isset($c['solde_dispo']) ? (int)$c['solde_dispo'].' j' : '—' ?></td>
                <td><span class="statut s-attente">en attente</span></td>
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
