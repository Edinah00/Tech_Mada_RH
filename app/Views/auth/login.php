<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — TechMadaRH</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <section id="page-login">
<div class="auth-page geo-bg">
<div class="auth-split">

  <!-- Panneau gauche -->
  <div class="auth-left">
    <div>
      <p class="auth-left-brand">TechMada RH<span>Gestion des congés</span></p>
      <p class="auth-left-text" style="margin-top:2rem">
        <strong>Bienvenue sur votre espace RH.</strong>
        Gérez vos demandes de congés, consultez votre solde et suivez l'état de vos demandes en temps réel.
      </p>
    </div>
    <div class="auth-roles">
      <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.25);margin-bottom:4px">Comptes de démonstration</div>
      <div class="role-pill">
        <i class="bi bi-shield-check"></i>
        <div><div class="role-pill-name">Administrateur</div><div class="role-pill-cred">admin@techmada.mg · admin123</div></div>
      </div>
      <div class="role-pill">
        <i class="bi bi-person-check"></i>
        <div><div class="role-pill-name">Responsable RH</div><div class="role-pill-cred">rh@techmada.mg · rh123</div></div>
      </div>
      <div class="role-pill">
        <i class="bi bi-person"></i>
        <div><div class="role-pill-name">Employé</div><div class="role-pill-cred">employe@techmada.mg · emp123</div></div>
      </div>
    </div>
  </div>

  <!-- Panneau droit -->
  <div class="auth-right">
    <p class="auth-title">Connexion</p>
    <p class="auth-sub">Entrez vos identifiants pour accéder à votre espace.</p>

    <!-- Flashdata CI4 — erreur -->
    <div class="flash flash-error">
      <i class="bi bi-exclamation-circle-fill"></i>
      Identifiants incorrects. Veuillez réessayer.
    </div>

    <form>
      <div class="f-group">
        <label class="f-label">Adresse email</label>
        <input type="email" class="f-input" placeholder="vous@techmada.mg" value="employe@techmada.mg"/>
      </div>
      <div class="f-group">
        <label class="f-label">Mot de passe</label>
        <input type="password" class="f-input" placeholder="••••••••" value="emp123"/>
      </div>
      <button type="submit" class="btn-primary" style="margin-top:.5rem">
        Se connecter <i class="bi bi-arrow-right-short"></i>
      </button>
    </form>
  </div>

</div>
</div>
</section>
</body>
</html>
