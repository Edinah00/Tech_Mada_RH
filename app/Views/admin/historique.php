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
        <div class="topbar-title">Historique des demandes</div>
        <div class="topbar-breadcrumb">
          <a href="/admin/stats">Admin</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Historique
        </div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.78rem;color:var(--muted)"><?= count($conges) ?> demande(s) affichée(s)</span>
      </div>
    </div>

    <div class="content">

      <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <!-- Filtres -->
      <div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap;align-items:center">
        <?php
          $filtres = [
            ''           => "Toutes ({$counts['total']})",
            'en_attente' => "En attente ({$counts['en_attente']})",
            'approuve'   => "Approuvées ({$counts['approuve']})",
            'refuse'     => "Refusées ({$counts['refuse']})",
            'annule'     => "Annulées ({$counts['annule']})",
          ];
          foreach ($filtres as $val => $label):
            $actif = $statut === $val || ($val === '' && !$statut);
        ?>
        <a href="?statut=<?= $val ?><?= $deptId ? '&dept='.$deptId : '' ?>"
           style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid <?= $actif ? 'var(--forest)' : 'var(--border)' ?>;background:<?= $actif ? 'var(--forest)' : 'var(--white)' ?>;color:<?= $actif ? 'var(--white)' : 'var(--muted)' ?>;cursor:pointer;text-decoration:none">
          <?= $label ?>
        </a>
        <?php endforeach; ?>

        <select onchange="location='?statut=<?= esc($statut) ?>&dept='+this.value"
                class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto;margin-left:auto">
          <option value="">Tous les départements</option>
          <?php foreach ($depts as $d): ?>
            <option value="<?= $d['id'] ?>" <?= $deptId == $d['id'] ? 'selected' : '' ?>>
              <?= esc($d['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Toutes les demandes</h3></div>
        <?php if (empty($conges)): ?>
          <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande pour ce filtre.</p></div>
        <?php else: ?>
        <table class="tbl">
          <thead>
            <tr>
              <th>Employé</th><th>Département</th><th>Type</th>
              <th>Période</th><th>Durée</th><th>Statut</th>
              <th>Commentaire RH</th><th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($conges as $c): ?>
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:7px">
                  <div class="avatar av-green" style="width:28px;height:28px;font-size:.6rem">
                    <?= strtoupper(substr($c['prenom'],0,1).substr($c['nom'],0,1)) ?>
                  </div>
                  <span style="font-size:.84rem;font-weight:500"><?= esc($c['prenom'].' '.$c['nom']) ?></span>
                </div>
              </td>
              <td class="td-muted" style="font-size:.8rem"><?= esc($c['dept_nom'] ?? '—') ?></td>
              <td>
                <?php
                  $cls = match($c['type_libelle']) {
                    'Congé annuel'  => 't-annuel',
                    'Congé maladie' => 't-maladie',
                    'Congé spécial' => 't-special',
                    default         => 't-sans-solde',
                  };
                ?>
                <span class="type-badge <?= $cls ?>"><?= esc($c['type_libelle']) ?></span>
              </td>
              <td class="td-muted" style="font-size:.8rem">
                <?= date('d/m/Y', strtotime($c['date_debut'])) ?> – <?= date('d/m/Y', strtotime($c['date_fin'])) ?>
              </td>
              <td class="td-mono"><?= (int)$c['nb_jours'] ?> j</td>
              <td>
                <?php
                  $scls = match($c['statut']) {
                    'en_attente' => 's-attente',
                    'approuve'   => 's-approuvee',
                    'refuse'     => 's-refusee',
                    default      => 's-annulee',
                  };
                  $slbl = match($c['statut']) {
                    'en_attente' => 'en attente',
                    'approuve'   => 'approuvée',
                    'refuse'     => 'refusée',
                    default      => 'annulée',
                  };
                ?>
                <span class="statut <?= $scls ?>"><?= $slbl ?></span>
              </td>
              <td style="font-size:.78rem;color:var(--muted);max-width:160px">
                <?= $c['commentaire_rh'] ? esc($c['commentaire_rh']) : '—' ?>
              </td>
              <td class="td-muted td-mono" style="font-size:.75rem">
                <?= date('d/m/Y', strtotime($c['created_at'])) ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</body>
</html>
