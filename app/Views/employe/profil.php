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
        <div class="topbar-title">Mon profil</div>
        <div class="topbar-breadcrumb">
          <a href="/employe/dashboard">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Profil
        </div>
      </div>
    </div>

    <div class="content">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start">
        <div class="form-section" style="margin:0">
          <h3><i class="bi bi-person-circle" style="color:var(--forest);margin-right:6px"></i>Modifier mes informations</h3>
          <form method="POST" action="/employe/profil/update">
            <?= csrf_field() ?>
            <div class="form-grid-2" style="margin-bottom:1rem">
              <div class="f-group">
                <label class="f-label">Prénom</label>
                <input type="text" name="prenom" class="f-input" value="<?= esc($emp['prenom'] ?? '') ?>" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Nom</label>
                <input type="text" name="nom" class="f-input" value="<?= esc($emp['nom'] ?? '') ?>" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Email</label>
                <input type="email" class="f-input" value="<?= esc($emp['email'] ?? '') ?>" disabled/>
              </div>
              <div class="f-group">
                <label class="f-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="f-input" placeholder="••••••••"/>
              </div>
            </div>
            <div class="flash flash-info" style="margin-bottom:1rem">
              <i class="bi bi-info-circle-fill"></i>
              <span style="font-size:.82rem">Laisser le mot de passe vide permet de conserver l’ancien.</span>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn-forest"><i class="bi bi-check"></i> Enregistrer</button>
            </div>
          </form>
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3>Mes informations</h3></div>
            <div style="padding:1rem 1.1rem;display:flex;flex-direction:column;gap:.8rem">
              <div style="display:flex;align-items:center;gap:12px">
                <div class="avatar av-green" style="width:48px;height:48px;font-size:.95rem">
                  <?= strtoupper(substr($emp['prenom'] ?? 'U',0,1).substr($emp['nom'] ?? '',0,1)) ?>
                </div>
                <div>
                  <div style="font-weight:600"><?= esc(trim(($emp['prenom'] ?? '').' '.($emp['nom'] ?? ''))) ?></div>
                  <div class="td-muted" style="font-size:.82rem"><?= esc($emp['role'] ?? 'employe') ?></div>
                </div>
              </div>
              <div style="font-size:.85rem;color:var(--muted)">
                <div><strong>Email :</strong> <?= esc($emp['email'] ?? '—') ?></div>
                <div><strong>Département :</strong> <?= esc($emp['departement_nom'] ?? '—') ?></div>
                <div><strong>Date d'embauche :</strong> <?= !empty($emp['date_embauche']) ? date('d/m/Y', strtotime($emp['date_embauche'])) : '—' ?></div>
                <div><strong>Statut :</strong> <?= !empty($emp['actif']) ? 'Actif' : 'Inactif' ?></div>
              </div>
            </div>
          </div>

          <div class="flash flash-info" style="margin:0">
            <i class="bi bi-shield-check"></i>
            <span style="font-size:.8rem">Vos demandes de congé et vos soldes restent inchangés lorsque vous modifiez votre profil.</span>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</body>
</html>
