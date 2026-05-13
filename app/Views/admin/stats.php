<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — RégimeSport Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<div class="app-wrapper">
    <?= view('partials/sidebar') ?>

    <div class="main-content">
        <div class="topbar">
            <span class="topbar-title">📊 Tableau de bord — Statistiques</span>
            <span class="badge badge-green">Admin</span>
        </div>

        <div class="page-body">
            <!-- KPIs -->
            <div class="stats-grid mb-32">
                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div class="stat-value"><?= $nbUsers ?></div>
                    <div class="stat-label">Utilisateurs inscrits</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon gold">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </div>
                    <div class="stat-value"><?= $nbGold ?></div>
                    <div class="stat-label">Membres GOLD</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div class="stat-value"><?= $nbProgrammes ?></div>
                    <div class="stat-label">Programmes vendus</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div class="stat-value" style="font-size:1.3rem"><?= number_format($revenuTotal, 0, ',', ' ') ?> Ar</div>
                    <div class="stat-label">Revenu total généré</div>
                </div>
            </div>

            <div class="grid-2 mb-32">
                <!-- Objectifs utilisateurs -->
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Répartition par objectif</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($statsObjectif)): ?>
                            <p class="text-muted text-center" style="padding:20px">Aucune donnée disponible</p>
                        <?php else: ?>
                            <?php
                            $total = array_sum(array_column($statsObjectif, 'nb_users'));
                            $colors = ['var(--green-500)', 'var(--blue)', 'var(--gold)'];
                            ?>
                            <?php foreach ($statsObjectif as $i => $stat): ?>
                            <div style="margin-bottom:16px">
                                <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:.85rem">
                                    <span style="font-weight:500"><?= esc($stat['objectif']) ?></span>
                                    <span style="color:var(--ink-muted)"><?= $stat['nb_users'] ?> utilisateur(s)</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width:<?= $total > 0 ? round($stat['nb_users']/$total*100) : 0 ?>%;background:<?= $colors[$i % 3] ?>"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Revenus mensuels -->
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Revenus mensuels</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($revenusMensuels)): ?>
                            <p class="text-muted text-center" style="padding:20px">Aucune donnée disponible</p>
                        <?php else: ?>
                            <?php $maxRevenu = max(array_column($revenusMensuels, 'total')); ?>
                            <?php foreach ($revenusMensuels as $mois): ?>
                            <div style="margin-bottom:12px">
                                <div style="display:flex;justify-content:space-between;margin-bottom:5px;font-size:.82rem">
                                    <span style="font-weight:500"><?= date('M Y', strtotime($mois['mois'] . '-01')) ?></span>
                                    <span style="color:var(--green-700);font-weight:600"><?= number_format($mois['total'], 0, ',', ' ') ?> Ar</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width:<?= $maxRevenu > 0 ? round($mois['total']/$maxRevenu*100) : 0 ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Liens rapides admin -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Accès rapide</span>
                </div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px">
                        <a href="<?= base_url('/admin/users') ?>" class="btn btn-secondary btn-lg" style="justify-content:center;flex-direction:column;height:80px;gap:6px">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            Utilisateurs
                        </a>
                        <a href="<?= base_url('/admin/regimes') ?>" class="btn btn-secondary btn-lg" style="justify-content:center;flex-direction:column;height:80px;gap:6px">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                            Régimes
                        </a>
                        <a href="<?= base_url('/admin/activites') ?>" class="btn btn-secondary btn-lg" style="justify-content:center;flex-direction:column;height:80px;gap:6px">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            Activités
                        </a>
                        <a href="<?= base_url('/admin/aliments') ?>" class="btn btn-secondary btn-lg" style="justify-content:center;flex-direction:column;height:80px;gap:6px">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/></svg>
                            Aliments
                        </a>
                        <a href="<?= base_url('/admin/codes') ?>" class="btn btn-secondary btn-lg" style="justify-content:center;flex-direction:column;height:80px;gap:6px">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            Codes recharge
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
