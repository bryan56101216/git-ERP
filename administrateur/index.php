<?php
session_start();

require("../Authentifier/bd.php");

$recup= $access->prepare("SELECT * FROM administrateurs WHERE nom=?");
$recup->execute([$_SESSION['nom']]);

if ($recup->rowCount() > 0) {
    $info= $recup->fetch(PDO::FETCH_ASSOC);
    //console.log("success");
}
require("../Authentifier/utilisateurs.php");
$Utilisateur=afficher();

$con= new PDO('mysql:host=127.0.0.1;dbname=erpvision','root','');
if(isset($_POST['valider'])){
   $nom=htmlspecialchars($_POST['name']);
   $email=htmlspecialchars($_POST['email']);
   $mdp=sha1($_POST['mdp']);
   $titre=htmlspecialchars($_POST['title']);
   $statut=htmlspecialchars($_POST['statut']);
   $permission = htmlspecialchars($_POST['permission']);
   $editUserId = isset($_POST['editUserId']) ? intval($_POST['editUserId']) : 0;

   if($editUserId > 0){
      // UPDATE
      $update = $con->prepare('UPDATE utilisateurs SET nom=?, email=?, mdp=?, titre=?, statut=?, permission=? WHERE id=?');
      $update->execute([$nom, $email, $mdp, $titre, $statut, $permission, $editUserId]);
      header("Refresh:0; url=". $_SERVER['PHP_SELF']);
   } else {
      // INSERT (comme avant)
      $testmail = $con->prepare("SELECT * FROM utilisateurs WHERE email= ?");
      $testmail->execute([$email]);
      $controlmail= $testmail->rowCount();
      if($controlmail==0){
         $insertion = $con -> prepare('INSERT INTO utilisateurs(nom,email,mdp,titre,statut,permission) VALUES(?,?,?,?,?,?)');
         $insertion -> execute([$nom,$email,$mdp,$titre,$statut,$permission]);
         header("Refresh:0; url=". $_SERVER['PHP_SELF']);
      }
      else{
         $message= "Désolé mais votre adresse à déjà un compte.";
      }
   }
}
if (isset($_POST['saveProfile'])) {
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);

    $update = $con->prepare('UPDATE administrateurs SET nom=?, email=?, numero=? WHERE nom=?');
    $update->execute([$fullName, $email, $phone, $_SESSION['nom']]);
    $_SESSION['nom'] = $fullName; // Met à jour la session si besoin
    header("Refresh:0; url=" . $_SERVER['PHP_SELF']);
}

if (isset($_POST['changePassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Vérifie que le nouveau mot de passe et la confirmation sont identiques
    if ($newPassword !== $confirmPassword) {
        $message = "Les nouveaux mots de passe ne correspondent pas.";
    } else {
        // Vérifie que l'ancien mot de passe est correct
        $check = $con->prepare("SELECT * FROM administrateurs WHERE nom=? AND mdp=?");
        $check->execute([$_SESSION['nom'], $currentPassword]);
        if ($check->rowCount() == 1) {
            // Met à jour le mot de passe
            $update = $con->prepare("UPDATE administrateurs SET mdp=? WHERE nom=?");
            $update->execute([$newPassword, $_SESSION['nom']]);
            $message = "Mot de passe modifié avec succès.";
        } else {
            $message = "Mot de passe actuel incorrect.";
        }
    }
}
$con= new PDO('mysql:host=127.0.0.1;dbname=erpvision','root','');
if (isset($_POST['valide']) && isset($_POST['reportType'])) {
    $reportType = htmlspecialchars($_POST['reportType']);
    $startDate = htmlspecialchars($_POST['startDate']);
    $endDate = htmlspecialchars($_POST['endDate']);
    $createdBy = $_SESSION['nom']; // ou $_SESSION['id'] si tu as l'id de l'utilisateur

    // Prépare et exécute l'insertion
    $insert = $con->prepare("INSERT INTO rapports (type, date_debut, date_fin, nom_admin) VALUES (?, ?, ?, ?)");
    $insert->execute([$reportType, $startDate, $endDate, $createdBy]);
    $message = "Le rapport a été enregistré avec succès.";
}
if (!$_SESSION['nom']) {
    header("Location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo" style="display: flex;justify-content: center;gap: 15px;margin-bottom: 20%;">
            <img src="../image/logo8.png" style="width: 90px;height: 70px;border-radius: 50%;margin-right: 2%;align-items: center;" alt="">
            <h2 style="padding-top: 7%;">ERP Vision</h2>
             </div>
            <nav class="nav-menu">
                <a href="#" class="nav-item active" onclick="showSection('users')">
                    <i class="fas fa-users"></i>
                    <span>Gestion des Utilisateurs</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('roles')">
                    <i class="fas fa-shield-alt"></i>
                    <span>Rôles et Permissions</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('modules')">
                    <i class="fas fa-cogs"></i>
                    <span>Modules Système</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('settings')">
                    <i class="fas fa-globe"></i>
                    <span>Paramètres Langue</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('profile')">
                    <i class="fas fa-user-circle"></i>
                    <span>Paramètres Profil</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('reports')">
                    <i class="fas fa-chart-line"></i>
                    <span>Rapports</span>
                </a>
                 <a href="../Authentifier/deconnexion.php"  class="nav-item"  style="text-decoration:none;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </nav>  
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher des utilisateurs, rôles ou paramètres..." id="searchInput">
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary" onclick="exportData()">
                        <i class="fas fa-download"></i> Exporter Données
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openModal('createUser')">
                        <i class="fas fa-plus"></i> Créer Utilisateur
                    </button>
                    <div class="header-right">
                    <div class="user-profile">
                       
                    <div class="user-avatar">
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
                        <div>
                            <div style="font-weight: 600;"><?php echo $_SESSION['nom']; ?></div>
                            <div style="font-size: 12px; color: #718096;"><?php echo $_SESSION['titre']; ?></div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <!-- User Management Section -->
                <div id="users-section" class="content-section">
                    <div class="content-header">
                        <h1 class="content-title">Gestion des Utilisateurs</h1>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">247</div>
                            <div class="stat-label">Total Utilisateurs</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">189</div>
                            <div class="stat-label">Utilisateurs Actifs</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">12</div>
                            <div class="stat-label">Nouveaux ce Mois</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">5</div>
                            <div class="stat-label">En Attente d'Approbation</div>
                        </div>
                    </div>

                    <div class="tabs">
                        <button class="tab active" onclick="switchTab('all-users')">Tous les Utilisateurs</button>
                        <button class="tab" onclick="switchTab('project-manager')">Chefs de Projet</button>
                        <button class="tab" onclick="switchTab('marketing-agent')">Agents Marketing</button>
                        <button class="tab" onclick="switchTab('accountant')">Comptables</button>
                        <button class="tab" onclick="switchTab('customer')">Clients</button>
                    </div>

                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Répertoire des Utilisateurs</h3>
                            <div class="table-filters">
                                <button class="filter-btn active" onclick="filterUsers('all')">Tous</button>
                                <button class="filter-btn" onclick="filterUsers('active')">Actifs</button>
                                <button class="filter-btn" onclick="filterUsers('inactive')">Inactifs</button>
                                <button class="filter-btn" onclick="filterUsers('pending')">En Attente</button>
                            </div>
                        </div>
                        <table id="usersTable">
                            <thead>
                                <tr>
                                     <th>N°</th>
                                    <th>Nom</th>
                                    <th>Rôle</th>
                                    <th>Email</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($Utilisateur as $utilisateur): ?>
                               <tr 
                                data-id="<?= $utilisateur->id ?>"
                                data-nom="<?= htmlspecialchars($utilisateur->nom) ?>"
                                data-email="<?= htmlspecialchars($utilisateur->email) ?>"
                                data-titre="<?= htmlspecialchars($utilisateur->titre) ?>"
                                data-statut="<?= htmlspecialchars($utilisateur->statut) ?>"
                            >
                                <td><?= $utilisateur->id ?></td>
                                <td><?= $utilisateur->nom ?></td>
                                <td><?= $utilisateur->titre ?></td>
                                <td><?= $utilisateur->email ?></td>
                                <td><span class="status-badge status-active"><?= $utilisateur->statut ?></span></td>
                                <td>
                                        <button class="action-btn btn-edit" onclick="editUser(<?= $utilisateur->id ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="../Authentifier/utilisateurs.php?supprimer=<?=$utilisateur->id ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')"><button class="action-btn btn-delete" id="id_utilisateur"> <i class="fas fa-trash"></i>
                                        </button></a>
                                    </td>
                                </tr>
                                 <?php endforeach;  ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Roles & Permissions Section -->
                <div id="roles-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">Rôles et Permissions</h1>
                    </div>
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">Rôles du Système</h3>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom du Rôle</th>
                                    <th>Utilisateurs</th>
                                    <th>Permissions</th>
                                    <th>Créé</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-crown" style="color: #f39c12; margin-right: 10px;"></i>Administrateur</td>
                                    <td>3</td>
                                    <td>Accès Complet</td>
                                    <td>Jan 2024</td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="openModal('createRole')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-project-diagram" style="color: #3498db; margin-right: 10px;"></i>Chef de Projet</td>
                                    <td>15</td>
                                    <td>Gestion de Projet, Accès Équipe</td>
                                    <td>Jan 2024</td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="openModal('createRole')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-bullhorn" style="color: #e74c3c; margin-right: 10px;"></i>Agent Marketing</td>
                                    <td>45</td>
                                    <td>Gestion Campagnes, Analyses</td>
                                    <td>Jan 2024</td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="openModal('createRole')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user" style="color: #95a5a6; margin-right: 10px;"></i>Client</td>
                                    <td>89</td>
                                    <td>Voir Projets, Rapports</td>
                                    <td>Jan 2024</td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="openModal('createRole')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-calculator" style="color: #27ae60; margin-right: 10px;"></i>Comptable</td>
                                    <td>8</td>
                                    <td>Rapports Financiers, Facturation</td>
                                    <td>Jan 2024</td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="openModal('createRole')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- System Modules Section -->
                <div id="modules-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">Modules Système</h1>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-puzzle-piece"></i> Configuration des Modules</h3>
                        <p>Activez ou désactivez les modules système selon les besoins de votre agence.</p>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-projects" checked>
                                <label for="module-projects"><i class="fas fa-project-diagram"></i> Gestion de Projets</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-campaigns" checked>
                                <label for="module-campaigns"><i class="fas fa-bullhorn"></i> Gestion de Campagnes</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-finance" checked>
                                <label for="module-finance"><i class="fas fa-dollar-sign"></i> Gestion Financière</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-analytics" checked>
                                <label for="module-analytics"><i class="fas fa-chart-bar"></i> Analyses et Rapports</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-crm" checked>
                                <label for="module-crm"><i class="fas fa-handshake"></i> Relations Clients</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-inventory">
                                <label for="module-inventory"><i class="fas fa-boxes"></i> Gestion d'Inventaire</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-hr">
                                <label for="module-hr"><i class="fas fa-users-cog"></i> Ressources Humaines</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-support" checked>
                                <label for="module-support"><i class="fas fa-life-ring"></i> Tickets de Support</label>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="margin-top: 20px;" onclick="saveModules()">
                            <i class="fas fa-save"></i> Enregistrer Paramètres Modules
                        </button>
                    </div>
                </div>

                <!-- Language Settings Section -->
                <div id="settings-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">Paramètres de Langue</h1>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-globe"></i> Configuration de Langue du Système</h3>
                        <div class="form-group">
                            <label class="form-label">Langue Par Défaut du Système</label>
                            <select class="form-select" id="systemLanguage">
                                <option value="en">🇺🇸 Anglais</option>
                                <option value="fr" selected>🇫🇷 Français</option>
                                <option value="es">🇪🇸 Espagnol</option>
                                <option value="de">🇩🇪 Allemand</option>
                                <option value="it">🇮🇹 Italien</option>
                                <option value="pt">🇵🇹 Portugais</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Langues Disponibles pour les Utilisateurs</label>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-en" checked>
                                    <label for="lang-en">Anglais</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-fr" checked>
                                    <label for="lang-fr">Français</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-es">
                                    <label for="lang-es">Espagnol</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-de">
                                    <label for="lang-de">Allemand</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-it">
                                    <label for="lang-it">Italien</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-pt">
                                    <label for="lang-pt">Portugais</label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" onclick="saveLanguageSettings()">
                            <i class="fas fa-save"></i> Enregistrer Paramètres de Langue
                        </button>
                    </div>
                </div>

                <!-- Profile Settings Section -->
                <div id="profile-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">Paramètres du Profil</h1>
                    </div>
                    <div class="profile-section">
                        <div class="profile-card">
                   <div class="user-avatar" style=" width: 110px;height: 110px;align-items: center;justify-content: center;font-size:40px;margin-left:32%;margin-bottom:7%">
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
                            <h3 style="margin-bottom: 4%;">Administrateur</h3>
                            <p><?php echo $info['nom'] ?></p>
                        </div>
                        <div class="setting-card">
                            <h3><i class="fas fa-user-edit"></i> Informations Personnelles</h3>
                            <form method="POST">
                            <div class="form-group">
                                <label class="form-label">Nom Complet</label>
                                <input type="text" class="form-input" value="<?php echo $info['nom'] ?>" id="fullName"name="fullName">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Adresse Email</label>
                                <input type="email" class="form-input" value="<?php echo $info['email'] ?>" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Numéro de Téléphone</label>
                                <input type="tel" class="form-input" value="<?php echo $info['numero'] ?>" id="phone" name="phone">
                            </div>

                            <button type="submit" name="saveProfile" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer Profil
                            </button>
                            </form>
                        </div>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-key"></i> Mot de Passe et Sécurité</h3>
                        <form method="POST">
                        <div class="form-group">
                            <label class="form-label">Mot de Passe Actuel</label>
                            <input type="password" class="form-input" id="currentPassword" name="currentPassword" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nouveau Mot de Passe</label>
                            <input type="password" class="form-input" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirmer le Nouveau Mot de Passe</label>
                            <input type="password" class="form-input" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <div class="checkbox-item" style="margin: 15px 0;">
                            <input type="checkbox" id="twoFactor" required>
                            <label for="twoFactor">Activer l'Authentification à Deux Facteurs</label>
                        </div>
                        <button type="submit" name="changePassword" class="btn btn-primary">
                            <i class="fas fa-lock"></i> Changer Mot de Passe
                        </button>
                        <?php if(isset($message)) { echo '<p style="color:red;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>'; } ?>
                    </form>
                    </div>
                </div>

                <!-- Reports Section -->
                <div id="reports-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">Rapports Système</h1>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">98.5%</div>
                            <div class="stat-label">Temps de Fonctionnement</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">1,247</div>
                            <div class="stat-label">Connexions Aujourd'hui</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">15.2Go</div>
                            <div class="stat-label">Utilisation Données</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Alertes Sécurité</div>
                        </div>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-file-alt"></i> Générer des Rapports</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Type de Rapport</label>
                        <select class="form-select" id="reportType" name="reportType" required>
                            <option value="user-activity">Rapport d'Activité Utilisateurs</option>
                            <option value="system-usage">Rapport d'Utilisation Système</option>
                            <option value="security-log">Rapport Journal de Sécurité</option>
                            <option value="performance">Rapport de Performance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Plage de Dates</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="date" class="form-input" id="startDate" name="startDate" style="flex: 1;" required>
                            <input type="date" class="form-input" id="endDate" name="endDate" style="flex: 1;" required>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" name="valide">
                        <i class="fas fa-file-download"></i> Générer Rapport
                    </button>
                    <?php if(isset($message)) { echo '<p style="color:green;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>'; } ?>
                </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Créer un Nouvel Utilisateur</h3>
                <button class="close-btn" onclick="closeModal('createUserModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createUserForm" method="POST">
                 <input type="hidden" name="editUserId" id="editUserId">
                <div class="form-group">
                    <label class="form-label">Nom Complet</label>
                    <input type="text" class="form-input" name="name" id="newUserName" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Adresse Email</label>
                    <input type="email" class="form-input" name="email" id="newUserEmail" required>
                    <?php
                    if(isset($message)){
                        echo '<p style="color:red;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>';
                    } 
                    ?>
                </div>
                   <div class="form-group">
                    <label class="form-label">Mot de Passe</label>
                    <input type="password" class="form-input" name="mdp" id="newUserPassword" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Rôle</label>
                    <select class="form-select" name="title" id="newUserRole" required>
                        <option value="">Sélectionner Rôle</option>
                        <option value="Chef de Projet">Chef de Projet</option>
                        <option value="Agent Marketing">Agent Marketing</option>
                        <option value="Comptable">Comptable</option>
                        <option value="Client">Client</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="statut" id="newUserStatut" required>
                        <option value="">Sélectionner statut</option>
                        <option value="Actif">Actif</option>
                        <option value="Inactif">Inactif</option>
                        <option value="En Attente">En Attente</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Permissions</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" value="Accès Lecture" id="perm-read">
                            <label for="perm-read">Accès Lecture</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" value="Accès Écriture" id="perm-write">
                            <label for="perm-write">Accès Écriture</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" value="Accès Suppression" id="perm-delete">
                            <label for="perm-delete">Accès Suppression</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" value="Accès Admin" id="perm-admin">
                            <label for="perm-admin">Accès Admin</label>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createUserModal')">
                        Annuler
                    </button>
                    <button type="submit" name="valider" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Créer Utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div id="createRoleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Mettre à Jour le Rôle</h3>
                <button class="close-btn" onclick="closeModal('createRoleModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Nom du Rôle</label>
                    <input type="text" class="form-input" name="role" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Permissions</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" id="role-user-management">
                            <label for="role-user-management">Gestion des Utilisateurs</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" id="role-project-management">
                            <label for="role-project-management">Gestion de Projets</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" id="role-financial-access">
                            <label for="role-financial-access">Accès Financier</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" id="role-system-settings">
                            <label for="role-system-settings">Paramètres Système</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" id="role-reports">
                            <label for="role-reports">Générer des Rapports</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" id="role-campaigns">
                            <label for="role-campaigns">Gestion de Campagnes</label>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createRoleModal')">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" name="valid_role">
                        <i class="fas fa-plus"></i> Mettre à Jour le Rôle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification">
        <span id="notificationMessage"></span>
    </div>
    <script src="scripts.js"></script>
   </body>
</html>
        