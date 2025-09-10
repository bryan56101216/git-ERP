<?php
session_start();
$con = new PDO('mysql:host=127.0.0.1;dbname=erpvision','root','');
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['reinitialiser'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $titre = htmlspecialchars($_POST['titre']);
    $nouveauMotDePasse = htmlspecialchars($_POST['nouveau_mdp']);
    $confirmerMotDePasse = htmlspecialchars($_POST['confirmer_mdp']);

    if ($nouveauMotDePasse === $confirmerMotDePasse) {
        if ($titre=='Administrateur') {
            $motDePasseHache = $nouveauMotDePasse;
            $verification1 = $con->prepare("SELECT * FROM administrateurs WHERE nom=? AND email=? AND titre=?");
            $verification1->execute([$nom, $email, $titre]);

            if ($verification1->rowCount() > 0) {
                // Mise à jour du mot de passe
                $miseAJour = $con->prepare("UPDATE administrateurs SET mdp=? WHERE nom=? AND email=? AND titre=?");
                $miseAJour->execute([$motDePasseHache, $nom, $email, $titre]);
                $succes = "Mot de passe réinitialisé avec succès !";
            } else {
                $erreur = "Utilisateur introuvable avec ces informations.";
            }
        } else {
            $motDePasseHache = sha1($nouveauMotDePasse);
            // Vérifier si utilisateur existe avec nom + email + rôle
            $verification = $con->prepare("SELECT * FROM utilisateurs WHERE nom=? AND email=? AND titre=?");
            $verification->execute([$nom, $email, $titre]);

            if ($verification->rowCount() > 0) {
                // Mise à jour du mot de passe
                $miseAJour = $con->prepare("UPDATE utilisateurs SET mdp=? WHERE nom=? AND email=? AND titre=?");
                $miseAJour->execute([$motDePasseHache, $nom, $email, $titre]);
                $succes = "Mot de passe réinitialisé avec succès !";
            } else {
                $erreur = "Utilisateur introuvable avec ces informations.";
            }
        }
    } else {
        $erreur = "Les mots de passe ne correspondent pas.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réinitialisation du Mot de Passe</title>
  <link rel="stylesheet" href="css/style_connexions.css">
  <link rel="stylesheet" href="css/style_forgot.css">
  <!-- Import des icônes Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="reset-container">
    <h1>Réinitialiser le Mot de Passe</h1>
    <form method="POST">
      <div class="form_group">
        <label for="nom"><i class="fa-solid fa-user icon"></i>Nom d'utilisateur :</label>
        <input type="text" class="input-field" name="nom" required>
      </div>

      <div class="form_group">
        <label for="email"><i class="fa-solid fa-envelope icon"></i>Email :</label>
        <input type="email" class="input-field" name="email" required>
      </div>

      <div class="form_group">
        <label for="titre"><i class="fa-solid fa-user-tag icon"></i>Titre :</label>
        <select name="titre" class="input-field" required>
          <option value="">Sélectionner un rôle</option>
          <option value="Administrateur">Administrateur</option>
          <option value="Client">Client</option>
          <option value="Chef de Projet">Chef de Projet</option>
          <option value="Comptable">Comptable</option>
          <option value="Agent Marketing">Agent Marketing</option>
        </select>
      </div>

      <div class="form_group">
        <label for="nouveau_mdp"><i class="fa-solid fa-lock icon"></i>Nouveau mot de passe :</label>
        <input type="password" class="input-field" name="nouveau_mdp" required>
      </div>

      <div class="form_group">
        <label for="confirmer_mdp"><i class="fa-solid fa-lock icon"></i>Confirmer le mot de passe :</label>
        <input type="password" class="input-field" name="confirmer_mdp" required>
      </div>

      <button type="submit" class="reset-btn" name="reinitialiser">
        <i class="fa-solid fa-rotate-right icon"></i>Réinitialiser le Mot de Passe
      </button>

      <?php if(isset($succes)) echo "<p class='msg success'>$succes</p>"; ?>
      <?php if(isset($erreur)) echo "<p class='msg error'>$erreur</p>"; ?>
    </form>

    <a href="connexion.php" class="back-link"><i class="fa-solid fa-arrow-left icon"></i>Retour à la connexion</a>
  </div>
</body>
</html>