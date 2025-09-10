<?php
session_start();
$con = new PDO('mysql:host=127.0.0.1;dbname=erpvision','root','');
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST["bouton-connexion"])){
  if(!empty($_POST["nom"]) && !empty($_POST["mdp"]) && !empty($_POST["titre"])){
    $nom = htmlspecialchars($_POST["nom"]);
    $mdp = sha1($_POST["mdp"]);
    $titre = htmlspecialchars($_POST["titre"]);
    
    if ($titre=='Administrateur') {
        $mdp = $_POST["mdp"];
        $reqAd = $con -> prepare("SELECT * FROM administrateurs WHERE nom =? AND titre =? AND mdp =?");
        $reqAd -> execute([$nom,$titre,$mdp]);
        $num_ligne1 = $reqAd->rowCount();
        if ($reqAd->rowCount() > 0) {
            header("Location: ../administrateur/index.php"); 
        } 
        $_SESSION['nom'] = $nom;
        $_SESSION['titre'] = $titre;
    }
    
    $req = $con -> prepare("SELECT * FROM utilisateurs WHERE nom =? AND titre =? AND mdp =?");
    $req -> execute([$nom,$titre,$mdp]);
    $num_ligne = $req->rowCount();

    if ($req->rowCount() > 0) {
        if($titre=='Client'){
            header("Location: ../client/index.php");
        }
        if($titre=='Chef de Projet'){
            header("Location: ../chef projet marketing/index.php");
        }
        if($titre=='Agent Marketing'){
            header("Location: ../agent projet marketing/index.php");
        }
        if($titre=='Comptable'){
            header("Location: ../comptable/index.php");
        }
        $_SESSION['nom'] = $nom;
        $_SESSION['titre'] = $titre;
        exit;
    } else {
        $erreur = "Identifiants incorrects!";
    }
  } 
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/style_connexions.css">
</head>
<body>
  <div class="container">
    <h1>CONNEXION MEMBRE</h1>
    <form action="" method="POST">
      <div class="form_group">
        <label for="nom"><i class="fa-solid fa-user icon" style="margin-right: 2%;"></i>Nom :</label>
        <input type="text" class="input-field" name="nom" placeholder="Entrez votre nom d'utilisateur" required>
      </div>

      <div class="form_group">
        <label for="password"><i class="fa-solid fa-lock icon" style="margin-right: 2%;"></i>Mot de passe :</label>
        <input type="password" class="input-field" name="mdp" placeholder="Entrez votre mot de passe" required>
      </div>

      <div class="form_group">
        <label for="titre"><i class="fa-solid fa-user-tag icon" style="margin-right: 2%;"></i>Titre :</label>
        <select name="titre" class="input-field">
          <option value="">Sélectionner un rôle</option>
          <option value="Administrateur">Administrateur</option>
          <option value="Client">Client</option>
          <option value="Chef de Projet">Chef de Projet</option>
          <option value="Comptable">Comptable</option>
          <option value="Agent Marketing">Agent Marketing</option>
        </select>
      </div>

      <div class="union">
        <div class="remember-me">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember" style="padding-top: 2%;">Se souvenir de moi</label>
        </div>
        <div class="forgot">
          <a href="forgot_password.php">Mot de passe oublié ?</a>
        </div>
      </div>

      <button type="submit" class="login-button" name="bouton-connexion">Se connecter</button>
      <?php
        if(isset($erreur)){
            echo '<p style="color:red;text-align:center; font-weight: bold;margin-top:2%">'.$erreur.'</p>';
        } 
      ?>
    </form>

    <div class="union2">
      <div class="ins1">Pas encore membre ?</div>
      <a href="inscription.php" class="abtn">
        <button class="btn">S'inscrire</button>
      </a>
    </div>
  </div>
  <script>
    // Scripts JavaScript si nécessaire
  </script>
</body>
</html>