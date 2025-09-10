<?php
session_start();
require("../Authentifier/bd.php");
  if (!$_SESSION['nom']) {
    header("Location: ../index.php");
  }
   
$recup= $access->prepare("SELECT * FROM utilisateurs WHERE nom=?");
$recup->execute([$_SESSION['nom']]);

if ($recup->rowCount() > 0) {
    $info= $recup->fetch(PDO::FETCH_ASSOC);
    //console.log("success");
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tableau de Bord Client</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <!-- En-tête -->
    <div class="header">
        <div class="logo"><img src="../image/logo8.png" alt=""></div>
        <div class="header-right">
            <div class="messages" id="messagesBtn">
                Messages
                <div class="message-badge">4</div>
            </div>
            <div class="contacts-link" id="contactsBtn">Mes Contacts</div>
        </div>
        
    </div>

    <!-- Contenu Principal -->
    <div class="main-content">
        <!-- Barre Latérale -->
        <div class="sidebar">
            <!-- Section Profil -->
            <div class="profile-section">
                   
                <div class="profile-image" style =" width: 80px;height: 80px;border-radius: 50%;background: linear-gradient(45deg, #f39c12, #e74c3c);display: flex;align-items: center;justify-content: center;color: white;font-weight: 600;foont-size:40px;margin-left:33%">
                    <?php
                        $noms = explode(' ', trim($_SESSION['nom']));
                        if (count($noms) >= 2) {
                            // Prend la première lettre de chaque nom
                            echo strtoupper(substr($noms[0], 0, 1) . substr($noms[1], 0, 1));
                        } else {
                            // Prend la première lettre du nom
                            echo strtoupper(substr($noms[0], 0, 1));
                        }
                    ?>
                </div>
                <h2 class="client-name"><?php echo $_SESSION['nom']; ?></h2>
                <p class="client-title"><?php echo $_SESSION['titre']; ?></p>
                <div class="rating">
                    <span class="rating-score">8.6</span>
                    <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary" id="sendMessageBtn">Envoyer un message</button>
                    <button class="btn btn-primary" id="contactsSidebarBtn">Contacts</button>
                </div>
                <div class="nav-bottom" >
                <a href="../Authentifier/deconnexion.php"  style="text-decoration:none;"><button class="logout-btn" >
                    <i class="fas fa-sign-out-alt nav-icon" ></i>
                    Se Déconnecter
                </button></a>
            </div>
            </div>
            <!-- Travail & Compétences ... garder l'original tel que vous l'avez envoyé ... -->
        </div>

        <!-- Contenu -->
        <div class="content" id="content">
            <div class="content-header">
                <h1 class="content-title">Tableau de Bord Client</h1>
            </div>

            <div class="tabs">
                <div class="tab active">À Propos</div>
            </div>

            <div class="contact-info">
                <!-- Informations de contact originales ici -->
                <div class="info-section">
                    <div class="info-title">Informations de Contact</div>
                    <div class="info-item"><span class="info-label">Téléphone:</span><span class="info-value"><?php echo $info['numero'] ?></span></div>
                    <div class="info-item"><span class="info-label">E-mail:</span><span class="info-value link"><a href="mailto:hello@jeremyrose.com" style="text-decoration:none;"><?php echo $info['email'] ?></a></span></div>
                    <div class="info-item"><span class="info-label">Site:</span><span class="info-value link"><a href="http://jeremyrose.com" style="text-decoration:none;">www.<?php echo $info['nom'] ?>.com</a></span></div>
                </div>
            </div>
            <div class="project-progress">
                    <h3 class="progress-title">Progression du Projet</h3>

                    <div class="project-item">
                        <div class="project-info"><h4>Identité de Marque</h4><p>Logo et directives de marque</p></div>
                        <div class="progress-container">
                            <div class="progress-percentage">100%</div>
                            <div class="progress-bar"><div class="progress-fill progress-completed" style="width: 100%"></div></div>
                        </div>
                    </div>

                    <div class="project-item">
                        <div class="project-info"><h4>Conception de Site Web</h4><p>UI/UX pour le site d'entreprise</p></div>
                        <div class="progress-container">
                            <div class="progress-percentage">75%</div>
                            <div class="progress-bar"><div class="progress-fill progress-in-progress" style="width: 75%"></div></div>
                        </div>
                    </div>

                    <div class="project-item">
                        <div class="project-info"><h4>Application Mobile</h4><p>Interface iOS et Android</p></div>
                        <div class="progress-container">
                            <div class="progress-percentage">45%</div>
                            <div class="progress-bar"><div class="progress-fill progress-in-progress" style="width: 45%"></div></div>
                        </div>
                    </div>

                    <div class="project-item">
                        <div class="project-info"><h4>Supports Marketing</h4><p>Brochures et cartes de visite</p></div>
                        <div class="progress-container">
                            <div class="progress-percentage">0%</div>
                            <div class="progress-bar"><div class="progress-fill progress-pending" style="width: 0%"></div></div>
                        </div>
                    </div>
                </div>
            

            <!-- Progression du Projet ... garder l'original ... -->
        </div>
    </div>
</div>
<script>
    const userInfo = <?php echo json_encode($info); ?>;
</script>

<script src="script.js"></script>
</body>
</html>