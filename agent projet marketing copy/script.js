 let missions = [
            {
                id: 1,
                title: "Q4 Social Media Campaign",
                description: "Create content for social media platforms",
                priority: "high",
                status: "in-progress",
                progress: 65,
                dueDate: "2025-01-15",
                assignedBy: "Project Manager",
                deliverables: [
                    { name: "Campaign Brief.pdf", type: "pdf", url: "#" },
                    { name: "Asset Guidelines.docx", type: "doc", url: "#" },
                    { name: "Brand Assets.zip", type: "zip", url: "#" }
                ]
            },
            {
                id: 2,
                title: "Email Performance Analysis",
                description: "Monthly report on email campaigns",
                priority: "medium",
                status: "pending",
                progress: 0,
                dueDate: "2025-01-20",
                assignedBy: "Marketing Director",
                deliverables: [
                    { name: "Email Template.html", type: "html", url: "#" },
                    { name: "Analytics Data.xlsx", type: "excel", url: "#" }
                ]
            },
            {
                id: 3,
                title: "Product Launch Event Preparation",
                description: "Organize product launch event",
                priority: "high",
                status: "in-progress",
                progress: 40,
                dueDate: "2025-01-10",
                assignedBy: "Project Manager",
                deliverables: [
                    { name: "Event Plan.pdf", type: "pdf", url: "#" },
                    { name: "Invitation Design.psd", type: "psd", url: "#" },
                    { name: "Venue Details.docx", type: "doc", url: "#" }
                ]
            },
            {
                id: 4,
                title: "Website SEO Optimization",
                description: "Improve organic search ranking",
                priority: "low",
                status: "completed",
                progress: 100,
                dueDate: "2025-01-05",
                assignedBy: "Digital Manager",
                deliverables: [
                    { name: "SEO Audit.pdf", type: "pdf", url: "#" },
                    { name: "Keyword Research.xlsx", type: "excel", url: "#" }
                ]
            },
            {
                id: 5,
                title: "Landing Page Creation",
                description: "New campaign landing page",
                priority: "medium",
                status: "in-progress",
                progress: 80,
                dueDate: "2025-01-18",
                assignedBy: "UX Designer",
                deliverables: [
                    { name: "Wireframes.sketch", type: "sketch", url: "#" },
                    { name: "Copy Guidelines.pdf", type: "pdf", url: "#" }
                ]
            }
        ];

        let currentMission = null;

        // --- Ajout: capture du contenu initial pour pouvoir le restaurer ---
        let initialContentHTML = '';
        function getContentArea() {
            // Priorité à la classe exacte présente dans ton code
            const area = document.querySelector('.content-area') || document.querySelector('.content-arera');
            return area;
        }

        function saveInitialContentIfNeeded() {
            const area = getContentArea();
            if (area && !initialContentHTML) {
                initialContentHTML = area.innerHTML;
            }
        }
        // -------------------------------------------------------------------

        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        }

        function renderMissions() {
            const container = document.getElementById('missionsList');
            if (!container) return;
            container.innerHTML = '';

            missions.forEach(mission => {
                const missionElement = document.createElement('div');
                missionElement.className = 'mission-item';
                missionElement.innerHTML = `
                    <div class="mission-priority priority-${mission.priority}"></div>
                    <div class="mission-content">
                        <div class="mission-title">${mission.title}</div>
                        <div class="mission-details">
                            ${mission.description} • Assigned by: ${mission.assignedBy} • Due: ${formatDate(mission.dueDate)}
                        </div>
                        <div class="mission-deliverables">
                            ${mission.deliverables.map(deliverable => `
                                <div class="deliverable-item">
                                    <span>${deliverable.name}</span>
                                    <button class="download-btn" onclick="downloadDeliverable('${deliverable.name}', '${deliverable.url}')" title="Download">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                                            <polyline points="14,2 14,8 20,8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                            <polyline points="10,9 9,9 8,9"/>
                                        </svg>
                                    </button>
                                </div>
                            `).join('')}
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${mission.progress}%"></div>
                            </div>
                            <div class="progress-text">${mission.progress}% completed</div>
                        </div>
                    </div>
                    <div class="mission-status">
                        <span class="status-badge status-${mission.status}">
                            ${getStatusText(mission.status)}
                        </span>
                    </div>
                    <div class="mission-actions">
                        <button class="action-btn btn-complete" onclick="openStatusModal(${mission.id})">
                            Update
                        </button>
                    </div>
                `;
                container.appendChild(missionElement);
            });

            updateStats();
        }

        function downloadDeliverable(filename, url) {
            // Simulate file download
            showNotification(`Downloading ${filename}...`, 'info');
            
            // In a real application, this would trigger an actual download
            setTimeout(() => {
                showNotification(`${filename} downloaded successfully!`, 'success');
            }, 1500);
            
            // For demo purposes, create a mock download
            const link = document.createElement('a');
            link.href = '#';
            link.download = filename;
            link.click();
        }

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        function getStatusText(status) {
            switch(status) {
                case 'completed': return 'Completed';
                case 'in-progress': return 'In Progress';
                case 'pending': return 'Pending';
                default: return status;
            }
        }

        function openStatusModal(missionId) {
            currentMission = missions.find(m => m.id === missionId);
            document.getElementById('newStatus').value = currentMission.status;
            document.getElementById('progressSlider').value = currentMission.progress;
            document.getElementById('progressValue').textContent = currentMission.progress + '%';
            document.getElementById('statusModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('statusModal').style.display = 'none';
            currentMission = null;
        }

        function updateMissionStatus() {
            if (!currentMission) return;

            const newStatus = document.getElementById('newStatus').value;
            const newProgress = parseInt(document.getElementById('progressSlider').value);

            currentMission.status = newStatus;
            currentMission.progress = newProgress;

            if (newStatus === 'completed') {
                currentMission.progress = 100;
            }

            renderMissions();
            closeModal();
            showNotification('Mission updated successfully!', 'success');
        }

        function updateStats() {
            const total = missions.length;
            const completed = missions.filter(m => m.status === 'completed').length;
            const pending = missions.filter(m => m.status === 'pending').length;
            const inProgress = missions.filter(m => m.status === 'in-progress').length;

            document.getElementById('totalMissions').textContent = total;
            document.getElementById('completedMissions').textContent = completed;
            document.getElementById('pendingMissions').textContent = pending + inProgress;
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            // Trigger animation
            setTimeout(() => notification.classList.add('show'), 100);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // --- MODES DE VUE DU HEADER (conservent ta structure) + injection dynamique ---

        function restoreDefaultContent() {
            const area = getContentArea();
            if (!area) return;
            area.innerHTML = initialContentHTML;
            // Après restauration, on relance le rendu des missions et stats
            renderMissions();
        }

        function showMissions() {
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            event.target.closest('.nav-item').classList.add('active');
            
            document.querySelector('.header-title').textContent = 'My Missions';
            document.querySelector('.header-subtitle').textContent = 'Manage your tasks and track progress';
            
            // Restaure le contenu initial (stats + missions)
            restoreDefaultContent();

            // Close mobile menu if open
            document.getElementById('sidebar').classList.remove('mobile-open');
        }

        function showCalendar() {
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            event.target.closest('.nav-item').classList.add('active');
            
            document.querySelector('.header-title').textContent = 'Schedule & Calendar';
            document.querySelector('.header-subtitle').textContent = 'View your events and campaigns';
            
            // Injecte un calendrier d'agent marketing (au hasard) dans .content-area
            const area = getContentArea();
            if (area) {
                area.innerHTML = `
                    <div class="section">
                        <div class="section-header">
                            <h2 class="section-title">Agent Calendar</h2>
                            <div class="calendar-nav">
                                <button class="btn-primary" id="prevMonthBtn">&lt;</button>
                                <button class="btn-primary" id="nextMonthBtn">&gt;</button>
                            </div>
                        </div>
                        <div class="calendar-view" style="display:block; padding: 20px;">
                            <div class="calendar-header" style="margin-top:0;">
                                <h3 id="agentCalTitle"></h3>
                            </div>
                            <div class="calendar-grid" id="agentCalendarGrid"></div>
                        </div>
                    </div>
                `;
                initAgentCalendar();
            }

            document.getElementById('sidebar').classList.remove('mobile-open');
        }

        function showAnalytics() {
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            event.target.closest('.nav-item').classList.add('active');
            
            document.querySelector('.header-title').textContent = 'Analytics';
            document.querySelector('.header-subtitle').textContent = 'Analyze your marketing performance';
            
            // Injecte des stats + un mini graphe inline dans .content-area
            const area = getContentArea();
            if (area) {
                area.innerHTML = `
                    <div class="stats-grid" style="margin-top:0;">
                        <div class="stat-card completed">
                            <div class="stat-number">68%</div>
                            <div class="stat-label">Avg. Engagement</div>
                        </div>
                        <div class="stat-card missions">
                            <div class="stat-number">124</div>
                            <div class="stat-label">Leads This Month</div>
                        </div>
                        <div class="stat-card pending">
                            <div class="stat-number">3.2%</div>
                            <div class="stat-label">CTR (All Channels)</div>
                        </div>
                        <div class="stat-card events">
                            <div class="stat-number">5</div>
                            <div class="stat-label">Active Campaigns</div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-header">
                            <h2 class="section-title">Campaign Performance (Random)</h2>
                            <div style="font-size:12px; color:#718096">Last 6 weeks</div>
                        </div>
                        <div style="padding:20px;">
                            <!-- Mini bar chart en SVG inline (pas de lib) -->
                            <div style="background:white; border-radius:12px;">
                                <svg viewBox="0 0 600 220" width="100%" height="220" preserveAspectRatio="none">
                                    <rect x="0" y="0" width="600" height="220" fill="#ffffff" />
                                    <!-- Axes -->
                                    <line x1="40" y1="180" x2="580" y2="180" stroke="#e2e8f0" stroke-width="2"/>
                                    <line x1="40" y1="20" x2="40" y2="180" stroke="#e2e8f0" stroke-width="2"/>
                                    <!-- Barres (valeurs au hasard) -->
                                    <rect x="70"  y="100" width="40" height="80" fill="#3498db" rx="6"/>
                                    <rect x="150" y="70"  width="40" height="110" fill="#27ae60" rx="6"/>
                                    <rect x="230" y="120" width="40" height="60" fill="#f39c12" rx="6"/>
                                    <rect x="310" y="60"  width="40" height="120" fill="#e74c3c" rx="6"/>
                                    <rect x="390" y="90"  width="40" height="90" fill="#8e44ad" rx="6"/>
                                    <rect x="470" y="130" width="40" height="50" fill="#2c3e50" rx="6"/>
                                    <!-- Légendes -->
                                    <text x="70"  y="200" font-size="12" fill="#718096">W1</text>
                                    <text x="150" y="200" font-size="12" fill="#718096">W2</text>
                                    <text x="230" y="200" font-size="12" fill="#718096">W3</text>
                                    <text x="310" y="200" font-size="12" fill="#718096">W4</text>
                                    <text x="390" y="200" font-size="12" fill="#718096">W5</text>
                                    <text x="470" y="200" font-size="12" fill="#718096">W6</text>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-header">
                            <h2 class="section-title">Top Channels (Random)</h2>
                        </div>
                        <div style="padding:20px;">
                            <div class="missions-grid" style="padding:0;">
                                <div class="mission-item">
                                    <div class="mission-priority priority-high"></div>
                                    <div class="mission-content">
                                        <div class="mission-title">Facebook Ads</div>
                                        <div class="mission-details">CPC: $0.42 • CTR: 3.8% • Conversions: 62</div>
                                    </div>
                                </div>
                                <div class="mission-item">
                                    <div class="mission-priority priority-medium"></div>
                                    <div class="mission-content">
                                        <div class="mission-title">Google Search</div>
                                        <div class="mission-details">CPC: $0.88 • CTR: 5.1% • Conversions: 41</div>
                                    </div>
                                </div>
                                <div class="mission-item">
                                    <div class="mission-priority priority-low"></div>
                                    <div class="mission-content">
                                        <div class="mission-title">Instagram Organic</div>
                                        <div class="mission-details">Reach: 18k • Saves: 1.2k • Leads: 21</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            document.getElementById('sidebar').classList.remove('mobile-open');
        }

        function showCampaigns() {
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            event.target.closest('.nav-item').classList.add('active');
            
            document.querySelector('.header-title').textContent = 'Campaigns';
            document.querySelector('.header-subtitle').textContent = 'Manage your marketing campaigns';
            
            document.getElementById('sidebar').classList.remove('mobile-open');
        }

        // --- Toggle interne Missions (inchangé) ---
        function showMissionsList() {
            const e = event; // conserver event local
            document.querySelector('#missionsList').style.display = 'block';
            document.querySelector('#missionsCalendar').style.display = 'none';
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
        }

        function showMissionsCalendar() {
            const e = event;
            document.querySelector('#missionsList').style.display = 'none';
            document.querySelector('#missionsCalendar').style.display = 'block';
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
            generateCalendar();
        }
        // ---------------------------------------------------------------

        function generateCalendar() {
            const calendarGrid = document.querySelector('.calendar-grid');
            if (!calendarGrid) return;
            calendarGrid.innerHTML = '';
            
            // Generate days headers
            const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            daysOfWeek.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'calendar-day';
                dayHeader.style.fontWeight = 'bold';
                dayHeader.style.background = '#f7fafc';
                dayHeader.style.minHeight = '40px';
                dayHeader.style.display = 'flex';
                dayHeader.style.alignItems = 'center';
                dayHeader.style.justifyContent = 'center';
                dayHeader.textContent = day;
                calendarGrid.appendChild(dayHeader);
            });
            
            // Generate calendar days
            for (let day = 1; day <= 31; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.innerHTML = `
                    <div class="day-number">${day}</div>
                    ${day % 7 === 0 ? '<div class="calendar-event">Team Meeting</div>' : ''}
                    ${day % 5 === 0 ? '<div class="calendar-event">Campaign Deadline</div>' : ''}
                    ${day === 15 ? '<div class="calendar-event">Q4 Social Media Due</div>' : ''}
                    ${day === 20 ? '<div class="calendar-event">Email Analysis Due</div>' : ''}
                `;
                calendarGrid.appendChild(dayElement);
            }
        }

        // --- Calendrier Agent (aléatoire) ---
        let agentCalRef = new Date(); // mois courant par défaut
        function initAgentCalendar() {
            const prevBtn = document.getElementById('prevMonthBtn');
            const nextBtn = document.getElementById('nextMonthBtn');
            if (prevBtn) prevBtn.addEventListener('click', () => { changeAgentMonth(-1); });
            if (nextBtn) nextBtn.addEventListener('click', () => { changeAgentMonth(1); });
            drawAgentCalendar();
        }

        function changeAgentMonth(delta) {
            agentCalRef.setMonth(agentCalRef.getMonth() + delta);
            drawAgentCalendar();
        }

        function drawAgentCalendar() {
            const grid = document.getElementById('agentCalendarGrid');
            const title = document.getElementById('agentCalTitle');
            if (!grid || !title) return;

            // entêtes
            grid.innerHTML = '';
            const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            days.forEach(d => {
                const h = document.createElement('div');
                h.className = 'calendar-day';
                h.style.fontWeight = 'bold';
                h.style.background = '#f7fafc';
                h.style.minHeight = '40px';
                h.style.display = 'flex';
                h.style.alignItems = 'center';
                h.style.justifyContent = 'center';
                h.textContent = d;
                grid.appendChild(h);
            });

            const year = agentCalRef.getFullYear();
            const month = agentCalRef.getMonth();
            title.textContent = agentCalRef.toLocaleString('en-US', { month: 'long', year: 'numeric' });

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // cases vides avant le 1er
            for (let i = 0; i < firstDay; i++) {
                const empty = document.createElement('div');
                empty.className = 'calendar-day';
                empty.style.background = '#fafafa';
                grid.appendChild(empty);
            }

            // événements "au hasard" pour un agent marketing
            const sampleEvents = [
                'Content Review',
                'Client Call',
                'Campaign Launch',
                'Design Handover',
                'Email Blast',
                'Lead Follow-up',
                'Weekly Report',
                'A/B Test'
            ];

            function randomEventsForDay(dayNum) {
                const list = [];
                if (dayNum % 5 === 0) list.push('Campaign Deadline');
                if (dayNum % 3 === 0) list.push('Client Call');
                if (dayNum % 2 === 0 && Math.random() > 0.5) list.push('Content Review');
                if (dayNum === 1) list.push('Monthly Planning');
                if (dayNum === 15) list.push('Mid-Month Check-in');
                if (Math.random() > 0.8) list.push(sampleEvents[Math.floor(Math.random()*sampleEvents.length)]);
                return list.slice(0,3);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const cell = document.createElement('div');
                cell.className = 'calendar-day';
                const evts = randomEventsForDay(d);
                cell.innerHTML = `
                    <div class="day-number">${d}</div>
                    ${evts.map(e => `<div class="calendar-event">${e}</div>`).join('')}
                `;
                grid.appendChild(cell);
            }
        }
        // ---------------------------------------------------------------

        // Progress slider update
        document.getElementById('progressSlider').addEventListener('input', function() {
            document.getElementById('progressValue').textContent = this.value + '%';
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('statusModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuButton = document.querySelector('.mobile-menu-toggle');
            
            if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
                sidebar.classList.remove('mobile-open');
            }
        });

        // Handle keyboard navigation
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
                document.getElementById('sidebar').classList.remove('mobile-open');
            }
        });

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Sauvegarde une fois le HTML initial de .content-area pour pouvoir revenir dessus
            saveInitialContentIfNeeded();

            renderMissions();
            
            // Set initial dashboard state
            document.querySelector('.dashboard-container').style.opacity = '0';
            document.querySelector('.dashboard-container').style.transform = 'translateY(20px)';
            document.querySelector('.dashboard-container').style.transition = 'all 0.6s ease';
            
            // Animate entrance
            setTimeout(() => {
                document.querySelector('.dashboard-container').style.opacity = '1';
                document.querySelector('.dashboard-container').style.transform = 'translateY(0)';
            }, 100);
            
            // Add scroll behavior for mobile
            if (window.innerWidth <= 768) {
                document.querySelector('.content-area').style.overflowX = 'auto';
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar').classList.remove('mobile-open');
            }
        });

  // Contenus fictifs pour chaque section
  const contents = {
    schedule: `
      <h2>Planning de l'agent marketing</h2>
      <ul>
        <li>Lundi : Analyse des performances Facebook Ads</li>
        <li>Mardi : Réunion avec le chef de projet</li>
        <li>Mercredi : Création de contenu Instagram</li>
        <li>Jeudi : Campagne emailing - envoi test</li>
        <li>Vendredi : Suivi des prospects et reporting</li>
      </ul>
    `,
    analytics: `
      <h2>Statistiques Marketing</h2>
      <ul>
        <li>Taux d’engagement global : 65%</li>
        <li>Conversions Facebook Ads : 120</li>
        <li>Nouveaux prospects cette semaine : 45</li>
        <li>Campagnes actives : 3</li>
      </ul>
    `,
    calendar: `
      <h2>Calendrier de la semaine</h2>
      <table border="1" cellspacing="0" cellpadding="5">
        <tr>
          <th>Lundi</th>
          <th>Mardi</th>
          <th>Mercredi</th>
          <th>Jeudi</th>
          <th>Vendredi</th>
        </tr>
        <tr>
          <td>Campagne Facebook Ads</td>
          <td>Réunion client</td>
          <td>Publication Instagram</td>
          <td>Emailing</td>
          <td>Rapport hebdo</td>
        </tr>
        <tr>
          <td>09h - 11h</td>
          <td>10h - 12h</td>
          <td>14h - 15h</td>
          <td>13h - 15h</td>
          <td>09h - 11h</td>
        </tr>
      </table>
    `
  };
  // Fonction pour changer le contenu de main-content
  function showSection(section) {
    const mainContent = document.getElementById('main-content');
    if (mainContent && contents[section]) {
      mainContent.innerHTML = contents[section];
    }
  }

  // Associer les boutons aux sections
  document.addEventListener('DOMContentLoaded', () => {
    const scheduleBtn = document.getElementById('schedule-btn');
    const analyticsBtn = document.getElementById('analytics-btn');
    const calendarBtn = document.getElementById('calendar-btn');

    if (scheduleBtn) scheduleBtn.addEventListener('click', () => showSection('schedule'));
    if (analyticsBtn) analyticsBtn.addEventListener('click', () => showSection('analytics'));
    if (calendarBtn) calendarBtn.addEventListener('click', () => showSection('calendar'));

    // Affichage par défaut : Dashboard
    showSection('schedule'); // ou une autre section par défaut si tu veux
  });
  window.addEventListener("load", function() {
    document.getElementById("loader").classList.add("hidden");
  });