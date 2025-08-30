<?php
session_start();
$con = new PDO('mysql:host=127.0.0.1;dbname=erpvision','root','');
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['reset'])) {
    $nom = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $titre = htmlspecialchars($_POST['title']);
    $newPassword = htmlspecialchars($_POST['new_mdp']);
    $confirmPassword = htmlspecialchars($_POST['confirm_mdp']);

    if ($newPassword === $confirmPassword) {
        if ($titre=='Administrator') {
            $hashedPassword = $newPassword;
             $check1 = $con->prepare("SELECT * FROM administrateurs WHERE nom=? AND email=? AND titre=?");
        $check1->execute([$nom, $email, $titre]);

        if ($check1->rowCount() > 0) {
            // Mise à jour du mot de passe
            $update = $con->prepare("UPDATE administrateurs SET mdp=? WHERE nom=? AND email=? AND titre=?");
            $update->execute([$hashedPassword, $nom, $email, $titre]);
            $success = "Mot de passe réinitialisé avec succès !";
        } else {
         $error = "Utilisateur introuvable avec ces informations.";
       }
         }
    else{
            $hashedPassword = sha1($newPassword);
        // Vérifier si utilisateur existe avec nom + email + rôle
        $check = $con->prepare("SELECT * FROM utilisateurs WHERE nom=? AND email=? AND titre=?");
        $check->execute([$nom, $email, $titre]);

        if ($check->rowCount() > 0) {
            // Mise à jour du mot de passe
            $update = $con->prepare("UPDATE utilisateurs SET mdp=? WHERE nom=? AND email=? AND titre=?");
            $update->execute([$hashedPassword, $nom, $email, $titre]);
            $success = "Mot de passe réinitialisé avec succès !";
        } else {
             $error = "Utilisateur introuvable avec ces informations.";
        }
        }
    } else {
        $error = "Les mots de passe ne correspondent pas.";
    }
 
      
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset</title>
  <link rel="stylesheet" href="css/style_connexion.css">
    <link rel="stylesheet" href="css/style_forgot.css">
  <!-- Import des icônes Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="reset-container">
    <h1>Reset Password</h1>
    <form method="POST">
      <div class="form_group">
        <label for="name"><i class="fa-solid fa-user icon"></i>Username:</label>
        <input type="text" class="input-field" name="name" required>
      </div>

      <div class="form_group">
        <label for="email"><i class="fa-solid fa-envelope icon"></i>Email:</label>
        <input type="email" class="input-field" name="email" required>
      </div>

      <div class="form_group">
        <label for="title"><i class="fa-solid fa-user-tag icon"></i>Titre:</label>
        <select name="title" class="input-field" required>
          <option value="">Select Role</option>
          <option value="Administrator">Administrator</option>
          <option value="Customer">Customer</option>
          <option value="Marketing Project Manager">Marketing Project Manager</option>
          <option value="Accountant">Accountant</option>
          <option value="Marketing Agent">Marketing Agent</option>
        </select>
      </div>

      <div class="form_group">
        <label for="new_mdp"><i class="fa-solid fa-lock icon"></i>New Password:</label>
        <input type="password" class="input-field" name="new_mdp" required>
      </div>

      <div class="form_group">
        <label for="confirm_mdp"><i class="fa-solid fa-lock icon"></i>Confirm Password:</label>
        <input type="password" class="input-field" name="confirm_mdp" required>
      </div>

      <button type="submit" class="reset-btn" name="reset">
        <i class="fa-solid fa-rotate-right icon"></i>Reset Password
      </button>

      <?php if(isset($success)) echo "<p class='msg success'></i>$success</p>"; ?>
      <?php if(isset($error)) echo "<p class='msg error'></i>$error</p>"; ?>
    </form>

    <a href="connexion.php" class="back-link"><i class="fa-solid fa-arrow-left icon"></i>Back to Login</a>
  </div>
</body>
</html>
