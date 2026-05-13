<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<div class="app-wrapper">
    <?= view('partials/sidebar') ?>

    <div class="main-content">
        <div class="topbar">
            <span class="topbar-title">👥 Gestion des Utilisateurs</span>
            <div class="d-flex gap-8 align-center">
                <input type="text" id="search-input" class="form-control" placeholder="Rechercher un utilisateur..."
                       style="width:240px" oninput="filterUsers(this.value)">
            </div>
        </div>

        <div class="page-body">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Genre</th>
                                <th>IMC</th>
                                <th>Statut</th>
                                <th>Solde</th>
                                <th>Programmes</th>
                                <th>Inscrit le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users-table">
                        <?php if (empty($users)): ?>
                            <tr><td colspan="8" class="text-center" style="padding:32px;color:var(--ink-muted)">Aucun utilisateur enregistré</td></tr>
                        <?php endif; ?>
                        <?php foreach ($users as $u): ?>
                            <?php
                            $imc = null;
                            if (!empty($u['taille']) && !empty($u['poids_actuel'])) {
                                $imcVal = round($u['poids_actuel'] / ($u['taille'] * $u['taille']), 1);
                                $imcCat = $imcVal < 18.5 ? 'Sous-poids' : ($imcVal < 25 ? 'Normal' : ($imcVal < 30 ? 'Surpoids' : 'Obésité'));
                                $imcColor = $imcVal < 18.5 ? 'var(--blue)' : ($imcVal < 25 ? 'var(--green-500)' : ($imcVal < 30 ? 'var(--amber)' : 'var(--red)'));
                            }
                            ?>
                            <tr data-search="<?= strtolower($u['nom'] . ' ' . $u['email']) ?>">
                                <td>
                                    <div class="d-flex align-center gap-8">
                                        <div style="width:36px;height:36px;border-radius:50%;background:var(--green-500);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:.85rem;flex-shrink:0">
                                            <?= strtoupper(substr($u['nom'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:.875rem"><?= esc($u['nom']) ?></div>
                                            <div style="font-size:.75rem;color:var(--ink-muted)"><?= esc($u['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $u['genre'] === 'M' ? '♂️ Masculin' : '♀️ Féminin' ?></td>
                                <td>
                                    <?php if (!empty($u['taille'])): ?>
                                        <span style="font-weight:700;color:<?= $imcColor ?>">
                                            <?= $imcVal ?>
                                        </span>
                                        <div style="font-size:.7rem;color:var(--ink-muted)"><?= $imcCat ?></div>
                                    <?php else: ?>
                                        <span style="color:var(--ink-muted);font-size:.8rem">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u['is_gold']): ?>
                                        <span class="badge badge-gold">⭐ GOLD</span>
                                    <?php else: ?>
                                        <span class="badge badge-gray">Standard</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong style="color:var(--green-700)">
                                        <?= number_format($u['solde'] ?? 0, 0, ',', ' ') ?> Ar
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge badge-blue"><?= $u['nb_programmes'] ?> programme(s)</span>
                                </td>
                                <td style="font-size:.8rem;color:var(--ink-muted)">
                                    <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                </td>
                                <td>
                                    <form action="<?= site_url('/admin/users/gold/' . $u['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button class="btn <?= $u['is_gold'] ? 'btn-danger' : 'btn-gold' ?> btn-sm">
                                            <?= $u['is_gold'] ? '❌ Retirer GOLD' : '⭐ Passer GOLD' ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterUsers(query) {
    query = query.toLowerCase();
    document.querySelectorAll('#users-table tr[data-search]').forEach(row => {
        row.style.display = row.dataset.search.includes(query) ? '' : 'none';
    });
}
</script>
</body>
</html>
