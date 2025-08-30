<?php
session_start();
if (!$_SESSION['nom']) {
    header("Location: ../index.php");
}

?>
<!DOCTYPE html>
    <html lang="fr">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef de Projet Daschboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    </head>
        <body>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo" style="display: flex;justify-content: center;gap: 3px;margin-bottom: 20%;">
            <img src="../image/logo8.png" style="width: 90px;height: 70px;border-radius: 50%;margin-right: 2%;align-items: center;" alt="">
            <h2 style="padding-top: 7%;">ERP Vision</h2>
            </div>
        
            <div class="nav-item active"><i class="fas fa-home"></i><span>Dashboard</span></div>
            <div class="nav-item"><i class="fas fa-tasks"></i><span>Projects</span></div>
            <div class="nav-item"><i class="fas fa-users"></i><span>Team</span></div>
            <div class="nav-item"><i class="fas fa-user-tie"></i><span>Clients</span></div>
            <div class="nav-item"><i class="fas fa-wallet"></i><span>Budget</span></div>
            <div class="nav-item"><i class="fas fa-chart-line"></i><span>Reports</span></div>
             <a href="../Authentifier/deconnexion.php"   style="text-decoration:none;color:white;"><div class="nav-item"><i class="fas fa-sign-out-alt"></i><span>Log out</span></div></a>
        </div>

        <!-- Main Content -->
    <div class="main-content">
            <!-- Dashboard Section -->
        <div id="dashboard" class="content-section active">
            <div class="header">
                <div class="header-left">
                <h1>Dashboard Chef de Projet</h1>
                <p>Mardi 19 Août, 2025</p>
                </div>
                <div class="header-right">
                <div class="budget-indicator">Budget:  11 150 000 XAF XAF</div>
                <div class="notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </div>
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
                    <div style="font-weight: 600;"><?php echo $_SESSION['nom']; ?></div>
                    <div style="font-size: 0.8rem; color: #718096;"><?php echo $_SESSION['titre']; ?></div>
                    </div>
                </div>
                </div>
            </div>
            <div class="dashboard-content">
                <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <div class="stat-icon" style="background: linear-gradient(135deg, #4fd1c7, #38b2ac);">
                                        <i class="fas fa-project-diagram"></i>
                                    </div>
                                </div>
                                <div class="stat-number">5</div>
                                <div class="stat-label">Projets Actifs</div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-header">
                                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="stat-number">14</div>
                                <div class="stat-label">Membres d'Équipe</div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-header">
                                    <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="stat-number">72%</div>
                                <div class="stat-label">Taux de Complétion</div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-header">
                                    <div class="stat-icon" style="background: linear-gradient(135deg, #ffeaa7, #fab1a0);">
                                        <i class="fas fa-franc-sign"></i>
                                    </div>
                                </div>
                                <div class="stat-number">69%</div>
                                <div class="stat-label">Budget Utilisé</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <div class="action-btn" onclick="openModal('projectModal')">
                                <i class="fas fa-plus"></i>
                                <div class="action-btn-title">Nouveau Projet</div>
                                <div class="action-btn-desc">Créer un nouveau projet</div>
                            </div>
                            <div class="action-btn" onclick="openModal('teamModal')">
                                <i class="fas fa-user-plus"></i>
                                <div class="action-btn-title">Affecter Ressources</div>
                                <div class="action-btn-desc">Gérer les affectations</div>
                            </div>
                            <div class="action-btn" onclick="openModal('reportModal')">
                                <i class="fas fa-chart-bar"></i>
                                <div class="action-btn-title">Générer Rapport</div>
                                <div class="action-btn-desc">Créer un rapport client</div>
                            </div>
                            
                        </div>

                        <!-- Projects Overview -->
                        <div class="projects-section">
                            <div class="section-header">
                                <h2 class="section-title" style="color: #4fd1c7;">Projets en Cours</h2>
                                <a href="#" class="view-all-btn" style="margin-left: 2%;">Voir tout</a>
                            </div>
                            
                            <div class="project-list">
                                <div class="project-item">
                                    <div class="project-header">
                                        <div class="project-name">Campagne Digitale - TechCorp</div>
                                        <div class="project-status status-active">En cours</div>
                                    </div>
                                    <div class="project-progress">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 75%;"></div>
                                        </div>
                                        <div class="progress-text">75% complété • Deadline: 30 Août</div>
                                    </div>
                                </div>
                                
                                <div class="project-item">
                                    <div class="project-header">
                                        <div class="project-name">Refonte Site Web - StartupXYZ</div>
                                        <div class="project-status status-pending">En attente</div>
                                    </div>
                            
                                </div> 
                                
                            </div>
                        </div>        
                    
                    
            </div>
        </div>
            <!-- Projects Section -->
        <div id="projects" class="content-section">
            <div class="dashboard-content">
                <div class="projects-section">
                            <div class="section-header1" style="width: 1100px;text-align: center;margin-bottom: 3%;">
                                <h2 class="section-title"><h2  style="color: black;">Projets en Cours</h2></h2>
                            
                            </div>
                            
                            <div class="project-list">
                                <div class="project-item">
                                    <div class="project-header">
                                        <div class="project-name">Campagne Digitale - TechCorp</div>
                                        <div class="project-status status-active">En cours</div>
                                    </div>
                                    <div class="project-progress">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 75%;"></div>
                                        </div>
                                        <div class="progress-text">75% complété • Deadline: 30 Août</div>
                                    </div>
                                </div>
                                
                                <div class="project-item">
                                    <div class="project-header">
                                        <div class="project-name">Refonte Site Web - StartupXYZ</div>
                                        <div class="project-status status-pending">En attente</div>
                                    </div>
                            
                                </div> 
                                <div class="project-item">
                                    <div class="project-header">
                                        <div class="project-name">Refonte Site Web - StartupXYZ</div>
                                        <div class="project-status status-pending">En attente</div>
                                    </div>
                            
                                </div> 
                                <div class="project-item">
                                    <div class="project-header">
                                        <div class="project-name">Refonte Site Web - StartupXYZ</div>
                                        <div class="project-status status-pending">En attente</div>
                                    </div>
                            
                                </div> 
                                <div class="project-item">
                                    <div class="project-header">
                                        <div class="project-name">Refonte Site Web - StartupXYZ</div>
                                        <div class="project-status status-pending">En attente</div>
                                    </div>
                            
                                </div> 
                            </div>
                        </div>  
                    </div>      
            </div>

            <!-- Team Section -->
            <div id="team" class="content-section">
            <div class="dashboard-content">
    <div class="blocEquipe">
        <div class="enteteEquipe">
        <h2>Gestion des Membres et Missions</h2>
        </div>
        <div class="grilleMembres">
        
        <div class="personneBloc">
            <h3>Alice Dupont</h3>
            <ul class="listeMissions"></ul>
            <form class="formMission">
            <input type="text" placeholder="Nouvelle mission..." required>
            <button type="submit">Assigner</button>
            </form>
        </div>

        <div class="personneBloc">
            <h3>Jean Martin</h3>
            <ul class="listeMissions"></ul>
            <form class="formMission">
            <input type="text" placeholder="Nouvelle mission..." required>
            <button type="submit">Assigner</button>
            </form>
        </div>

        <div class="personneBloc">
            <h3>Sophie Leroy</h3>
            <ul class="listeMissions"></ul>
            <form class="formMission">
            <input type="text" placeholder="Nouvelle mission..." required>
            <button type="submit">Assigner</button>
            </form>
        </div>

        <div class="personneBloc"><h3>David Morel</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Laura Bernard</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Thomas Garnier</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Camille Richard</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Julien Robert</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Emma Fontaine</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Hugo Lefebvre</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Chloé Simon</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Lucas Petit</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Manon Marchand</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        <div class="personneBloc"><h3>Antoine Durand</h3><ul class="listeMissions"></ul><form class="formMission"><input type="text" placeholder="Nouvelle mission..." required><button type="submit">Assigner</button></form></div>
        
        </div>
    </div>
            </div>
            </div>

            <!-- Clients Section -->
            <div id="clients" class="content-section">
            <div class="dashboard-content">
                <div id="clients" class="zone-container">
    <h2 style="color: black;margin-bottom: 2%;text-align: center;">Gestion des Clients</h2>

    <!-- Liste des clients -->
    <div class="client-list">
        <div class="client-card">
        <h3>Client: Alpha Corp</h3>
        <p>Email: alpha@example.com</p>
        <button class="btn-report">Envoyer Rapport</button>
        </div>
        <div class="client-card">
        <h3>Client: Beta Agency</h3>
        <p>Email: beta@example.com</p>
        <button class="btn-report">Envoyer Rapport</button>
        </div>
        <div class="client-card">
        <h3>Client: Gamma Studio</h3>
        <p>Email: gamma@example.com</p>
        <button class="btn-report">Envoyer Rapport</button>
        </div>
        <div class="client-card">
        <h3>Client: Delta Group</h3>
        <p>Email: delta@example.com</p>
        <button class="btn-report">Envoyer Rapport</button>
        </div>
        <div class="client-card">
        <h3>Client: Omega Ltd</h3>
        <p>Email: omega@example.com</p>
        <button class="btn-report">Envoyer Rapport</button>
        </div>
    </div>
    <h2 style="color: black;margin-bottom: 2%;margin-top: 5%;text-align: center;">Feedbacks des Clients</h2>
    <div class="feedback-list" id="feedback-list">
        <div class="feedback-card">
        <p><strong>Alpha Corp :</strong> Merci d’ajouter plus de détails sur le rapport final.</p>
        <button class="btn-validate">Valider</button>
        <button class="btn-reject">Rejeter</button>
        </div>
        <div class="feedback-card">
        <p><strong>Beta Agency :</strong> Le design de la page d’accueil doit être modifié.</p>
        <button class="btn-validate">Valider</button>
        <button class="btn-reject">Rejeter</button>
        </div>
        <div class="feedback-card">
        <p><strong>Gamma Studio :</strong> Ajouter un graphique de performance dans le dashboard.</p>
        <button class="btn-validate">Valider</button>
        <button class="btn-reject">Rejeter</button>
        </div>
    </div>
    </div>

            </div>
            </div>

            <!-- Budget Section -->
            <div id="budget" class="content-section">
            <div class="dashboard-content">
                <section id="sectionBudget" class="budget-section">
    <h2 style="color: black ;">Gestion du Budget</h2>
    <table class="budget-table">
        <thead>
        <tr>
            <th>Projet</th>
            <th>Budget (XAF)</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="budgetTableBody">
        <tr>
            <td>Campagne Digitale 2025</td>
            <td class="budget-amount">2,500,000</td>
            <td><button class="update-btn">Modifier</button></td>
        </tr>
        <tr>
            <td>Lancement Produit Alpha</td>
            <td class="budget-amount">3,800,000</td>
            <td><button class="update-btn">Modifier</button></td>
        </tr>
        <tr>
            <td>Événement Vision Expo</td>
            <td class="budget-amount">1,750,000</td>
            <td><button class="update-btn">Modifier</button></td>
        </tr>
        <tr>
            <td>Campagne Réseaux Sociaux</td>
            <td class="budget-amount">900,000</td>
            <td><button class="update-btn">Modifier</button></td>
        </tr>
        <tr>
            <td>Stratégie SEO Premium</td>
            <td class="budget-amount">2,200,000</td>
            <td><button class="update-btn">Modifier</button></td>
        </tr>
        </tbody>
    </table>

    <div class="budget-total">
        <strong>Total : <span id="totalBudget"></span> XAF</strong>
    </div>
    </section>
            </div>
            </div>

            <!-- Reports Section -->
            <div id="reports" class="content-section">
            <div class="dashboard-content">
                <section id="sectionRapports" class="rapport-section">
    <h2 style="color: black;">Gestion des Rapports</h2>

    <!-- Formulaire pour ajouter un rapport -->
    <div class="rapport-form">
        <input type="text" id="rapportName" placeholder="Nom du rapport">
        <select id="rapportProject">
        <option value="Campagne Digitale 2025">Campagne Digitale 2025</option>
        <option value="Lancement Produit Alpha">Lancement Produit Alpha</option>
        <option value="Événement Vision Expo">Événement Vision Expo</option>
        <option value="Campagne Réseaux Sociaux">Campagne Réseaux Sociaux</option>
        <option value="Stratégie SEO Premium">Stratégie SEO Premium</option>
        </select>
        <label>Clients assignés :</label>
        <select id="rapportClients" multiple>
        <option value="Alpha Corp">Alpha Corp</option>
        <option value="Beta Agency">Beta Agency</option>
        <option value="Gamma Studio">Gamma Studio</option>
        <option value="Delta Group">Delta Group</option>
        <option value="Omega Ltd">Omega Ltd</option>
        </select>
        <input type="file" id="rapportFile" style="font-size: 16px;">
        <button id="addRapportBtn" style="font-weight: bold; font-size: 18px;margin-top: 2%;" >Ajouter Rapport</button>
    </div>

    <!-- Liste des rapports -->
    <div id="rapportList" class="rapport-list">
        <!-- Les rapports seront ajoutés ici dynamiquement -->
    </div>
    </section>
</div>
</div>
<script src="script.js"></script>
</body>
</html>
