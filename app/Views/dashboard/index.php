<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — RégimeSport</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<div class="app-wrapper">
    <?= view('partials/sidebar') ?>

    <div class="main-content">
        <div class="topbar">
            <span class="topbar-title">Bonjour, <?= esc(session()->get('userName')) ?> 👋</span>
            <div class="topbar-actions">
                <?php if (session()->get('isGold')): ?>
                <span class="badge badge-gold">⭐ Membre GOLD</span>
                <?php endif; ?>
                s<a href="<?= base_url('/portefeuille') ?>" class="btn btn-secondary btn-sm">
                    💰 <?= number_format($solde, 0, ',', ' ') ?> Ar
                </a>
            </div>
        </div>

        <div class="page-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <!-- IMC Section -->
            <?php if ($detail && $imc): ?>
            <div class="grid-2 mb-32">
                <div class="imc-card">
                    <div style="font-size:.75rem;opacity:.7;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px">Votre IMC actuel</div>
                    <div class="imc-value"><?= $imc['imc'] ?></div>
                    <div class="imc-category"><?= esc($imc['categorie']) ?></div>
                    <div class="imc-conseil" style="margin-top:12px"><?= esc($imc['conseil']) ?></div>
                    <hr style="border:none;border-top:1px solid rgba(255,255,255,.2);margin:16px 0">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:.85rem">
                        <div>
                            <div style="opacity:.6;margin-bottom:4px">Taille</div>
                            <div style="font-weight:700"><?= $detail['taille'] ?> m</div>
                        </div>
                        <div>
                            <div style="opacity:.6;margin-bottom:4px">Poids</div>
                            <div style="font-weight:700"><?= $detail['poids_actuel'] ?> kg</div>
                        </div>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
                        </div>
                        <div class="stat-value"><?= count($programmes) ?></div>
                        <div class="stat-label">Programme(s) actif(s)</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon gold">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        </div>
                        <div class="stat-value"><?= number_format($solde, 0, ',', ' ') ?> Ar</div>
                        <div class="stat-label">Solde porte-monnaie</div>
                    </div>

                    <a href="<?= base_url('/suggestions') ?>" class="btn btn-primary btn-lg" style="justify-content:center">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Trouver mon programme
                    </a>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-info mb-24">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Votre profil est incomplet. Complétez vos données de santé pour voir votre IMC.
            </div>
            <?php endif; ?>

            <!-- Mes derniers programmes -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Mes derniers programmes</span>
                    <a href="<?= base_url('/suggestions') ?>" class="btn btn-primary btn-sm">+ Nouveau</a>
                </div>
                <?php if (empty($programmes)): ?>
                <div class="card-body text-center" style="padding:40px">
                    <div style="font-size:3rem;margin-bottom:12px">🌱</div>
                    <h3 style="font-size:1rem;color:var(--ink-soft);font-family:var(--font-body);font-weight:400">
                        Vous n'avez pas encore de programme.<br>
                        <a href="<?= base_url('/suggestions') ?>">Trouvez celui qui vous correspond →</a>
                    </h3>
                </div>
                <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Régime</th>
                                <th>Activité</th>
                                <th>Date</th>
                                <th>Prix payé</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_slice($programmes, 0, 5) as $prog): ?>
                            <tr>
                                <td><strong><?= esc($prog['nom_regime']) ?></strong></td>
                                <td><?= esc($prog['nom_activite']) ?></td>
                                <td><?= date('d/m/Y', strtotime($prog['date_achat'])) ?></td>
                                <td><?= number_format($prog['prix_total_paye'], 0, ',', ' ') ?> Ar</td>
                                <td>
                                    <a href="<?= base_url('/programme/pdf/' . $prog['id']) ?>" class="btn btn-secondary btn-sm">
                                        📄 PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>