<?php 
$con = new PDO('mysql:host=127.0.0.1;dbname=erpvision','root','');  

if(isset($_POST['valider'])){    
    $nom = htmlspecialchars($_POST['nom']);    
    $email = htmlspecialchars($_POST['email']);    
    $numero = htmlspecialchars($_POST['numero']);    
    $mdp = sha1($_POST['mdp']);   
    $confirm_mdp = sha1($_POST['confirm_mdp']);   
    $titre = htmlspecialchars($_POST['titre']);   
    
    if(strlen($_POST['mdp']) < 7){     
        $message = "Votre mot de passe est trop court.";       
    }     
    elseif(strlen($_POST['mdp']) > 40){     
        $message = "Votre mot de passe est trop long.";       
    }   
    elseif($mdp != $confirm_mdp){     
        $message = "Confirmation de mot de passe incorrecte.";   
    }   
    else{     
        $testmail = $con->prepare("SELECT * FROM utilisateurs WHERE email = ?");    
        $testmail->execute([$email]);       
        $controlmail = $testmail->rowCount();     
        
        if($controlmail == 0){       
            $insertion = $con->prepare('INSERT INTO utilisateurs(nom,email,numero,mdp,confirm_mdp,titre) VALUES(?,?,?,?,?,?)');       
            $insertion->execute([$nom,$email,$numero,$mdp,$confirm_mdp,$titre]);       
            $message = "Votre compte a été créé avec succès.";     
        }     
        else{       
            $message = "Désolé mais votre adresse a déjà un compte.";     
        }   
    }      
} 
?>  

<!DOCTYPE html> 
<html lang="fr"> 
<head>   
    <meta charset="UTF-8">   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <title>INSCRIPTION</title>   
    <link rel="stylesheet" href="css/style_inscription.css">     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
</head> 
<body>   
    <form class="signup-form" method="POST" action="">     
        <h1>S'INSCRIRE</h1>     
        
        <label><i class="fa-solid fa-user icon" style="margin-right: 2%;"></i>Nom</label>     
        <input type="text" name="nom" placeholder="" required>     
        
        <label><i class="fa-solid fa-envelope icon" style="margin-right: 2%;"></i>Adresse email</label>     
        <input type="email" name="email" placeholder="" required>     
        
        <label><i class="fa-solid fa-phone" style="margin-right: 2%;"></i>Numéro de téléphone</label>     
        <input type="number" name="numero" placeholder="" required>     
        
        <label><i class="fa-solid fa-lock icon" style="margin-right: 2%;"></i>Mot de passe</label>     
        <input type="password" name="mdp" placeholder="" required>     
        
        <label><i class="fa-solid fa-lock icon" style="margin-right: 2%;"></i>Confirmer le mot de passe</label>     
        <input type="password" name="confirm_mdp" placeholder="" required>     
        
        <label><i class="fa-solid fa-user-tag icon" style="margin-right: 2%;"></i>Titre</label>    
        <select name="titre" class="select" style="width: 100%; padding: 12px; margin-top: 6px; border-radius: 6px; border: 1px solid #ccc; outline: none; font-size: 14px; background-color: rgb(245, 245, 245); transition: 0.3s;" required>           
            <option value="">Sélectionner un rôle</option>           
            <option value="Client">Client</option>           
            <option value="Chef de Projet">Chef de Projet</option>           
            <option value="Comptable">Comptable</option>           
            <option value="Agent Marketing">Agent Marketing</option>         
        </select>     
        
        <label class="checkbox-label">       
            <input type="checkbox" required>       
            J'accepte vos conditions d'utilisation et votre politique de confidentialité     
        </label>      
        
        <button type="submit" name="valider">S'inscrire</button>         
        
        <?php         
            if(isset($message)){             
                echo '<p style="color:red;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>';         
            }          
        ?>       
        
        <p class="terms">       
            Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a>     
        </p>   
    </form> 
</body> 
</html>