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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin Dashboard</title>
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
                    <span>User Management</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('roles')">
                    <i class="fas fa-shield-alt"></i>
                    <span>Roles & Permissions</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('modules')">
                    <i class="fas fa-cogs"></i>
                    <span>System Modules</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('settings')">
                    <i class="fas fa-globe"></i>
                    <span>Language Settings</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('profile')">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile Settings</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('reports')">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports</span>
                </a>
                 <a href="../Authentifier/deconnexion.php"  class="nav-item"  style="text-decoration:none;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log out</span>
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
                    <input type="text" placeholder="Search users, roles, or settings..." id="searchInput">
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary" onclick="exportData()">
                        <i class="fas fa-download"></i> Export Data
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openModal('createUser')">
                        <i class="fas fa-plus"></i> Create User
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
                        <h1 class="content-title">User Management</h1>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">247</div>
                            <div class="stat-label">Total Users</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">189</div>
                            <div class="stat-label">Active Users</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">12</div>
                            <div class="stat-label">New This Month</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">5</div>
                            <div class="stat-label">Pending Approval</div>
                        </div>
                    </div>

                    <div class="tabs">
                        <button class="tab active" onclick="switchTab('all-users')">All Users</button>
                        <button class="tab" onclick="switchTab('project-manager')">Project Managers</button>
                        <button class="tab" onclick="switchTab('marketing-agent')">Marketing Agent</button>
                        <button class="tab" onclick="switchTab('accountant')">Accountants</button>
                        <button class="tab" onclick="switchTab('customer')">Clients</button>
                    </div>

                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">User Directory</h3>
                            <div class="table-filters">
                                <button class="filter-btn active" onclick="filterUsers('all')">All</button>
                                <button class="filter-btn" onclick="filterUsers('active')">Active</button>
                                <button class="filter-btn" onclick="filterUsers('inactive')">Inactive</button>
                                <button class="filter-btn" onclick="filterUsers('pending')">Pending</button>
                            </div>
                        </div>
                        <table id="usersTable">
                            <thead>
                                <tr>
                                     <th>N¤</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Status</th>
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
                        <h1 class="content-title">Roles & Permissions</h1>
                    </div>
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="table-title">System Roles</h3>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Users</th>
                                    <th>Permissions</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-crown" style="color: #f39c12; margin-right: 10px;"></i>Administrator</td>
                                    <td>3</td>
                                    <td>Full Access</td>
                                    <td>Jan 2024</td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="openModal('createRole')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-project-diagram" style="color: #3498db; margin-right: 10px;"></i>Project Manager</td>
                                    <td>15</td>
                                    <td>Project Management, Team Access</td>
                                    <td>Jan 2024</td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="openModal('createRole')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-bullhorn" style="color: #e74c3c; margin-right: 10px;"></i>Marketing Agent</td>
                                    <td>45</td>
                                    <td>Campaign Management, Analytics</td>
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
                                    <td>View Projects, Reports</td>
                                    <td>Jan 2024</td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="openModal('createRole')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-calculator" style="color: #27ae60; margin-right: 10px;"></i>Accountant</td>
                                    <td>8</td>
                                    <td>Financial Reports, Billing</td>
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
                        <h1 class="content-title">System Modules</h1>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-puzzle-piece"></i> Module Configuration</h3>
                        <p>Enable or disable system modules based on your agency's needs.</p>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-projects" checked>
                                <label for="module-projects"><i class="fas fa-project-diagram"></i> Project Management</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-campaigns" checked>
                                <label for="module-campaigns"><i class="fas fa-bullhorn"></i> Campaign Management</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-finance" checked>
                                <label for="module-finance"><i class="fas fa-dollar-sign"></i> Financial Management</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-analytics" checked>
                                <label for="module-analytics"><i class="fas fa-chart-bar"></i> Analytics & Reports</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-crm" checked>
                                <label for="module-crm"><i class="fas fa-handshake"></i> Customer Relations</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-inventory">
                                <label for="module-inventory"><i class="fas fa-boxes"></i> Inventory Management</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-hr">
                                <label for="module-hr"><i class="fas fa-users-cog"></i> Human Resources</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="module-support" checked>
                                <label for="module-support"><i class="fas fa-life-ring"></i> Support Tickets</label>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="margin-top: 20px;" onclick="saveModules()">
                            <i class="fas fa-save"></i> Save Module Settings
                        </button>
                    </div>
                </div>

                <!-- Language Settings Section -->
                <div id="settings-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">Language Settings</h1>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-globe"></i> System Language Configuration</h3>
                        <div class="form-group">
                            <label class="form-label">Default System Language</label>
                            <select class="form-select" id="systemLanguage">
                                <option value="en"> English</option>
                                <option value="fr"> French</option>
                                <option value="es"> Spanish</option>
                                <option value="de"> German</option>
                                <option value="it"> Italian</option>
                                <option value="pt"> Portuguese</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Available Languages for Users</label>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-en" checked>
                                    <label for="lang-en">English</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-fr" checked>
                                    <label for="lang-fr">French</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-es">
                                    <label for="lang-es">Spanish</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-de">
                                    <label for="lang-de">German</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-it">
                                    <label for="lang-it">Italian</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="lang-pt">
                                    <label for="lang-pt">Portuguese</label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" onclick="saveLanguageSettings()">
                            <i class="fas fa-save"></i> Save Language Settings
                        </button>
                    </div>
                </div>

                <!-- Profile Settings Section -->
                <div id="profile-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">Profile Settings</h1>
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
                            <h3 style="margin-bottom: 4%;">Administrator</h3>
                            <p><?php echo $info['nom'] ?></p>
                        </div>
                        <div class="setting-card">
                            <h3><i class="fas fa-user-edit"></i> Personal Information</h3>
                            <form method="POST">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-input" value="<?php echo $info['nom'] ?>" id="fullName"name="fullName">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-input" value="<?php echo $info['email'] ?>" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-input" value="<?php echo $info['numero'] ?>" id="phone" name="phone">
                            </div>

                            <button type="submit" name="saveProfile" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Profile
                            </button>
                            </form>
                        </div>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-key"></i> Password & Security</h3>
                        <form method="POST">
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-input" id="currentPassword" name="currentPassword" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-input" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-input" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <div class="checkbox-item" style="margin: 15px 0;">
                            <input type="checkbox" id="twoFactor" required>
                            <label for="twoFactor">Enable Two-Factor Authentication</label>
                        </div>
                        <button type="submit" name="changePassword" class="btn btn-primary">
                            <i class="fas fa-lock"></i> Change Password
                        </button>
                        <?php if(isset($message)) { echo '<p style="color:red;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>'; } ?>
                    </form>
                    </div>
                </div>

                <!-- Reports Section -->
                <div id="reports-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">System Reports</h1>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">98.5%</div>
                            <div class="stat-label">System Uptime</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">1,247</div>
                            <div class="stat-label">Total Logins Today</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">15.2GB</div>
                            <div class="stat-label">Data Usage</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Security Alerts</div>
                        </div>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-file-alt"></i> Generate Reports</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Report Type</label>
                        <select class="form-select" id="reportType" name="reportType" required>
                            <option value="user-activity">User Activity Report</option>
                            <option value="system-usage">System Usage Report</option>
                            <option value="security-log">Security Log Report</option>
                            <option value="performance">Performance Report</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Range</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="date" class="form-input" id="startDate" name="startDate" style="flex: 1;" required>
                            <input type="date" class="form-input" id="endDate" name="endDate" style="flex: 1;" required>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" name="valide">
                        <i class="fas fa-file-download"></i> Generate Report
                    </button>
                    <?php if(isset($message)) { echo '<p style="color:green;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>'; } ?>
                </form>
                    </div> 
                </div>

                <!-- Security Section
                <div id="security-section" class="content-section hidden">
                    <div class="content-header">
                        <h1 class="content-title">Security Settings</h1>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-shield-alt"></i> Password Policy</h3>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="policy-length" checked>
                                <label for="policy-length">Minimum 8 characters</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="policy-uppercase" checked>
                                <label for="policy-uppercase">Require uppercase letters</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="policy-numbers" checked>
                                <label for="policy-numbers">Require numbers</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="policy-special">
                                <label for="policy-special">Require special characters</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password Expiry (days)</label>
                            <input type="number" class="form-input" value="90" id="passwordExpiry">
                        </div>
                        <button class="btn btn-primary" onclick="saveSecuritySettings()">
                            <i class="fas fa-save"></i> Save Security Settings
                        </button>
                    </div>
                    <div class="setting-card">
                        <h3><i class="fas fa-clock"></i> Session Management</h3>
                        <div class="form-group">
                            <label class="form-label">Session Timeout (minutes)</label>
                            <input type="number" class="form-input" value="60" id="sessionTimeout">
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="concurrent-sessions">
                            <label for="concurrent-sessions">Allow concurrent sessions</label>
                        </div>
                        <button class="btn btn-primary" onclick="saveSessionSettings()">
                            <i class="fas fa-save"></i> Save Session Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Create User Modal -->
    <div id="createUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create New User</h3>
                <button class="close-btn" onclick="closeModal('createUserModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createUserForm" method="POST">
                 <input type="hidden" name="editUserId" id="editUserId">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-input" name="name" id="newUserName" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-input" name="email" id="newUserEmail" required>
                    <?php
                    if(isset($message)){
                        echo '<p style="color:red;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>';
                    } 
                    ?>
                </div>
                   <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-input" name="mdp" id="newUserName" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select class="form-select" name="title" id="newUserRole" required>
    <option value="">Select Role</option>
    <option value="Project Manager">Project Manager</option>
    <option value="Marketing Agent">Marketing Agent</option>
    <option value="Accountant">Accountant</option>
    <option value="Customer">Customer</option>
</select>
                </div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="statut" id="newUserStatut" required>
                        <option value="">Select statut</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Permissions</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" value="Read Access" id="perm-read">
                            <label for="perm-read" value="Read Access">Read Access</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" value="Write Access" id="perm-write">
                            <label for="perm-write" value="Write Access" >Write Access</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" value="Delete Access"  id="perm-delete">
                            <label for="perm-delete" value="Delete Access">Delete Access</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission" value="Admin Access" id="perm-admin">
                            <label for="perm-admin" value="Admin Access">Admin Access</label>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createUserModal')">
                        Cancel
                    </button>
                    <button type="submit" name="valider" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div id="createRoleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Update Role</h3>
                <button class="close-btn" onclick="closeModal('createRoleModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Role Name</label>
                    <input type="text" class="form-input" name="role" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Permissions</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission">
                            <label for="role-user-management" value="User Management">User Management</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox"  name="permission">
                            <label for="role-project-management" value="Project Management">Project Management</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="permission">
                            <label for="role-financial-access" value="Financial Access">Financial Access</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox"  name="permission">
                            <label for="role-system-settings" value="System Settings">System Settings</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox"  name="permission">
                            <label for="role-reports" value="Generate Reports">Generate Reports</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox"  name="permission">
                            <label for="role-campaigns" value="Campaign Management">Campaign Management</label>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createRoleModal')">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" name="valid_role">
                        <i class="fas fa-plus"></i> Update Role
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

