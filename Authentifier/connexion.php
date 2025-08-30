<?php
session_start();
$con = new PDO('mysql:host=127.0.0.1;dbname=erpvision','root','');
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST["login-button"])){
  if(!empty($_POST["name"]) && !empty($_POST["mdp"]) && !empty($_POST["title"])){
    $nom = htmlspecialchars($_POST["name"]);
    $mdp = sha1($_POST["mdp"]);
    $titre =htmlspecialchars($_POST["title"]);
    
    if ($titre=='Administrator') {
        $mdp= $_POST["mdp"];
           $reqAd = $con -> prepare("SELECT * FROM administrateurs WHERE  nom =?  AND titre =? AND mdp =?");
        $reqAd -> execute([$nom,$titre,$mdp]);
          $num_ligne1 = $reqAd->rowCount();
          if ($reqAd->rowCount() > 0) {
          header("Location: ../administrateur/index.php"); 
           
        } 
    $_SESSION['nom']=$nom;
    $_SESSION['titre']=$titre;
          
    }
    $req = $con -> prepare("SELECT * FROM utilisateurs WHERE  nom =? AND titre =? AND mdp =?");
    $req -> execute([$nom,$titre,$mdp]);
    $num_ligne = $req->rowCount();


   if ($req->rowCount() > 0) {
    // if($titre=='Administrator'){
    //   header("Location: ../administrateur/index.php");
    // }
     if($titre=='Customer'){
      header("Location: ../client/index.php");
    }
     if($titre=='Marketing Project Manager'){
      header("Location: ../chef projet marketing/index.php");
    }
     if($titre=='Marketing Agent'){
      header("Location: ../agent projet marketing/index.php");
    }
     if($titre=='Accountant'){
      header("Location: ../comptable/index.php");
    }
     $_SESSION['nom']=$nom;
    $_SESSION['titre']=$titre;
    exit;
     }else{
       $error = "identifiant incorrect!";
     }
 } 

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/style_connexion.css">

</head>
<body>
  <div class="container">
    <h1>MEMBER LOGIN</h1>
    <form action="" method="POST">
      <div class="form_group">
        <label for="username"><i class="fa-solid fa-user icon" style=" margin-right: 2%; "></i>Name:</label>
        <input type="text" class="input-field" name="name" placeholder="Enter your username" required>
      </div>

      <div class="form_group">
        <label for="password"><i class="fa-solid fa-lock icon" style=" margin-right: 2%; "></i>Password:</label>
        <input type="password" class="input-field" name="mdp" placeholder="Enter your password" required>
      </div>

      <div class="form_group">
        <label for="title"><i class="fa-solid fa-user-tag icon" style=" margin-right: 2%; "></i>Title:</label>
        <select name="title" class="input-field" >
          <option value="">Select Role</option>
          <option value="Administrator">Administrator</option>
          <option value="Customer">Customer</option>
          <option value="Marketing Project Manager">Marketing Project Manager</option>
          <option value="Accountant" >Accountant</option>
          <option value="Marketing Agent">Marketing Agent</option>
        </select>
      </div>

      <div class="union">
        <div class="remember-me">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember" style="padding-top: 2%;">Remember me</label>
        </div>
        <div class="forgot">
          <a href="forgot_password.php">Forgot password?</a>
        </div>
      </div>

      <button type="submit" class="login-button" name="login-button">Login</button>
          <?php
        if(isset($error)){
            echo '<p style="color:red;text-align:center; font-weight: bold;margin-top:2%">'.$error.'</p>';
        } 
        ?>
    </form>

    <div class="union2">
      <div class="ins1">Not a member?</div>
      <a href="inscription.php" class="abtn">
        <button class="btn">Sign In</button>
      </a>
    </div>
  </div>
  <script>

</script>

</body>
</html>
