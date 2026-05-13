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
        <div class="topbar-title">Vue d'ensemble RH</div>
        <div class="topbar-breadcrumb">
          <a href="/admin/stats">Admin</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Tableau de bord
        </div>
      </div>
      <div class="topbar-actions">
        <a href="/admin/historique" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
          <i class="bi bi-inbox"></i> Voir les demandes
        </a>
      </div>
    </div>

    <div class="content">

      <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem;margin-bottom:1.5rem">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-people"></i></div></div>
          <div class="metric-val"><?= (int) $nbEmployes ?></div>
          <div class="metric-label">Employés actifs</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-person-badge"></i></div></div>
          <div class="metric-val"><?= (int) $nbRh ?></div>
          <div class="metric-label">Responsables RH</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
          <div class="metric-val"><?= (int) $nbDepts ?></div>
          <div class="metric-label">Départements</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val"><?= (int) ($stats['en_attente'] ?? 0) ?></div>
          <div class="metric-label">Demandes en attente</div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:1.5rem;align-items:start">
        <div style="display:flex;flex-direction:column;gap:1.5rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3>Etat des demandes</h3></div>
            <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem">
              <div class="metric" style="margin:0">
                <div class="metric-val"><?= (int) ($stats['total'] ?? 0) ?></div>
                <div class="metric-label">Total</div>
              </div>
              <div class="metric" style="margin:0">
                <div class="metric-val"><?= (int) ($stats['approuve'] ?? 0) ?></div>
                <div class="metric-label">Approuvées</div>
              </div>
              <div class="metric" style="margin:0">
                <div class="metric-val"><?= (int) ($stats['refuse'] ?? 0) ?></div>
                <div class="metric-label">Refusées</div>
              </div>
              <div class="metric" style="margin:0">
                <div class="metric-val"><?= (int) ($stats['annule'] ?? 0) ?></div>
                <div class="metric-label">Annulées</div>
              </div>
            </div>
          </div>

          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3>Demandes récentes</h3></div>
            <?php if (empty($recentes)): ?>
              <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande récente.</p></div>
            <?php else: ?>
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Employé</th><th>Département</th><th>Type</th><th>Durée</th><th>Statut</th><th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recentes as $c): ?>
                  <tr>
                    <td style="font-weight:500"><?= esc($c['prenom'] . ' ' . $c['nom']) ?></td>
                    <td class="td-muted"><?= esc($c['dept_nom'] ?? '—') ?></td>
                    <td><span class="type-badge"><?= esc($c['type_libelle']) ?></span></td>
                    <td class="td-mono"><?= (int) $c['nb_jours'] ?> j</td>
                    <td>
                      <?php
                        $scls = match($c['statut']) {
                          'en_attente' => 's-attente',
                          'approuve'   => 's-approuvee',
                          'refuse'     => 's-refusee',
                          default      => 's-annulee',
                        };
                      ?>
                      <span class="statut <?= $scls ?>"><?= esc(str_replace('_', ' ', $c['statut'])) ?></span>
                    </td>
                    <td class="td-muted td-mono"><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:1.5rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3>Absents aujourd'hui</h3></div>
            <?php if (empty($absentsAuj)): ?>
              <div class="empty"><i class="bi bi-calendar-check"></i><p>Aucun absent aujourd'hui.</p></div>
            <?php else: ?>
              <div style="display:flex;flex-direction:column;gap:.75rem">
                <?php foreach ($absentsAuj as $absence): ?>
                  <div style="padding:.9rem 1rem;border:1px solid var(--border);border-radius:14px">
                    <div style="font-weight:600"><?= esc($absence['prenom'] . ' ' . $absence['nom']) ?></div>
                    <div class="td-muted" style="font-size:.82rem"><?= esc($absence['type_libelle']) ?></div>
                    <div class="td-muted td-mono" style="font-size:.78rem">Retour prévu le <?= date('d/m/Y', strtotime($absence['date_fin'])) ?></div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3>Soldes critiques</h3></div>
            <?php if (empty($soldesCritiques)): ?>
              <div class="empty"><i class="bi bi-shield-check"></i><p>Aucun solde critique détecté.</p></div>
            <?php else: ?>
              <table class="tbl">
                <thead>
                  <tr><th>Employé</th><th>Département</th><th>Restant</th></tr>
                </thead>
                <tbody>
                  <?php foreach ($soldesCritiques as $solde): ?>
                  <tr>
                    <td style="font-weight:500"><?= esc($solde['prenom'] . ' ' . $solde['nom']) ?></td>
                    <td class="td-muted"><?= esc($solde['dept_nom'] ?? '—') ?></td>
                    <td class="td-mono" style="color:var(--danger)"><?= (int) $solde['restant'] ?> j</td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</body>
</html>
