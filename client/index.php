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
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Client Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="logo"><img src="../image/logo8.png" alt=""></div>
        <div class="header-right">
            <div class="messages" id="messagesBtn">
                Messages
                <div class="message-badge">4</div>
            </div>
            <div class="contacts-link" id="contactsBtn">My Contacts</div>
        </div>
        
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Profile Section -->
            <div class="profile-section">
                   
                <div class="profile-image" style =" width: 80px;height: 80px;border-radius: 50%;background: linear-gradient(45deg, #f39c12, #e74c3c);display: flex;align-items: center;justify-content: center;color: white;font-weight: 600;foont-size:40px">
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
                    <button class="btn btn-primary" id="sendMessageBtn">Send message</button>
                    <button class="btn btn-primary" id="contactsSidebarBtn">Contacts</button>
                </div>
                <div class="nav-bottom" >
                <a href="../Authentifier/deconnexion.php"  style="text-decoration:none;"><button class="logout-btn" >
                    <i class="fas fa-sign-out-alt nav-icon" ></i>
                    Log Out
                </button></a>
            </div>
            </div>
            <!-- Work & Skills ... keep original as you sent ... -->
        </div>

        <!-- Content -->
        <div class="content" id="content">
            <div class="content-header">
                <h1 class="content-title">Customer Dashboard</h1>
            </div>

            <div class="tabs">
                <div class="tab active">About</div>
            </div>

            <div class="contact-info">
                <!-- Original contact info here -->
                <div class="info-section">
                    <div class="info-title">Contact Information</div>
                    <div class="info-item"><span class="info-label">Phone:</span><span class="info-value"><?php echo $info['numero'] ?></span></div>
                    <div class="info-item"><span class="info-label">E-mail:</span><span class="info-value link"><a href="mailto:hello@jeremyrose.com" style="text-decoration:none;"><?php echo $info['email'] ?></a></span></div>
                    <div class="info-item"><span class="info-label">Site:</span><span class="info-value link"><a href="http://jeremyrose.com" style="text-decoration:none;">www.<?php echo $info['nom'] ?>.com</a></span></div>
                </div>
            </div>
            <div class="project-progress">
                    <h3 class="progress-title">Project Progress</h3>

                    <div class="project-item">
                        <div class="project-info"><h4>Brand Identity</h4><p>Logo and brand guidelines</p></div>
                        <div class="progress-container">
                            <div class="progress-percentage">100%</div>
                            <div class="progress-bar"><div class="progress-fill progress-completed" style="width: 100%"></div></div>
                        </div>
                    </div>

                    <div class="project-item">
                        <div class="project-info"><h4>Website Design</h4><p>UI/UX for company website</p></div>
                        <div class="progress-container">
                            <div class="progress-percentage">75%</div>
                            <div class="progress-bar"><div class="progress-fill progress-in-progress" style="width: 75%"></div></div>
                        </div>
                    </div>

                    <div class="project-item">
                        <div class="project-info"><h4>Mobile App</h4><p>iOS and Android interface</p></div>
                        <div class="progress-container">
                            <div class="progress-percentage">45%</div>
                            <div class="progress-bar"><div class="progress-fill progress-in-progress" style="width: 45%"></div></div>
                        </div>
                    </div>

                    <div class="project-item">
                        <div class="project-info"><h4>Marketing Materials</h4><p>Brochures and business cards</p></div>
                        <div class="progress-container">
                            <div class="progress-percentage">0%</div>
                            <div class="progress-bar"><div class="progress-fill progress-pending" style="width: 0%"></div></div>
                        </div>
                    </div>
                </div>
            

            <!-- Project Progress ... keep original ... -->
        </div>
    </div>
</div>
<script>
    const userInfo = <?php echo json_encode($info); ?>;
</script>

<script src="script.js"></script>
</body>
</html>
