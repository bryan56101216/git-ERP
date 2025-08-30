<?php
session_start();
if (!$_SESSION['nom']) {
    header("Location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing Agent Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>
    
    <div class="dashboard-container">
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <img src="../image/logo8.png" alt="">
                <h2>ERP Vision</h2>
            </div>
            
            <div class="nav-menu">
                <div class="nav-item active" onclick="showMissions()">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    My Missions
                </div>
                
                <div class="nav-item" onclick="showCalendar()">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Schedule
                </div>
                
                <div class="nav-item" onclick="showAnalytics()">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Analytics
                </div>
                <a href="../Authentifier/deconnexion.php"   style="text-decoration:none;color:white;">
                      <div class="nav-item" >
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Log out
                </div>
                </a>
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1 class="header-title">Marketing Agent Dashboard</h1>
                    <p class="header-subtitle">Manage your missions and campaigns efficiently</p>
                </div>
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
                            <div style="font-weight: 600;"><?php echo $_SESSION['nom']; ?></div>
                            <div style="font-size: 12px; color: #718096;"><?php echo $_SESSION['titre']; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-area">
                <div class="stats-grid">
                    <div class="stat-card missions">
                        <div class="stat-number" id="totalMissions">12</div>
                        <div class="stat-label">Total Missions</div>
                    </div>
                    <div class="stat-card completed">
                        <div class="stat-number" id="completedMissions">7</div>
                        <div class="stat-label">Completed</div>
                    </div>
                    <div class="stat-card pending">
                        <div class="stat-number" id="pendingMissions">3</div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-card events">
                        <div class="stat-number" id="upcomingEvents">5</div>
                        <div class="stat-label">Events</div>
                    </div>
                </div>

                <div class="section" id="missionsSection">
                    <div class="section-header">
                        <h2 class="section-title">Assigned Missions</h2>
                        <div class="view-toggle">
                            <button class="toggle-btn active" onclick="showMissionsList()">List</button>
                            <button class="toggle-btn" onclick="showMissionsCalendar()">Calendar</button>
                        </div>
                    </div>
                    
                    <div class="missions-grid" id="missionsList">
                        <!-- Missions will be populated by JavaScript -->
                    </div>
                    
                    <div class="calendar-view" id="missionsCalendar">
                        <div class="calendar-header">
                            <h3>December 2025</h3>
                            <div class="calendar-nav">
                                <button class="btn-primary">&lt;</button>
                                <button class="btn-primary">&gt;</button>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            <!-- Calendar will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for status update -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Mission</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-select" id="newStatus">
                    <option value="pending">Pending</option>
                    <option value="in-progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Progress (%)</label>
                <input type="range" id="progressSlider" min="0" max="100" value="0" class="form-input">
                <span id="progressValue">0%</span>
            </div>
            <button class="btn-primary" onclick="updateMissionStatus()">Update Mission</button>
        </div>
    </div>
    <script src="script.js"></script>

</body>
</html>
