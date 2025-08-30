     // Global variables
        let currentSection = 'users';
        let currentTab = 'all-users';

        // Sidebar functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            
            const toggleBtn = sidebar.querySelector('.toggle-btn i');
            if (sidebar.classList.contains('collapsed')) {
                toggleBtn.className = 'fas fa-chevron-right';
            } else {
                toggleBtn.className = 'fas fa-chevron-left';
            }
        }

        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Section management
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Show selected section
            document.getElementById(sectionName + '-section').classList.remove('hidden');
            
            // Update active nav item
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.classList.add('active');
            
            currentSection = sectionName;
        }

        // Tab functionality
       // ...existing code...

function switchTab(tabName) {
    // Onglet actif
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');

    // Filtrage des lignes
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(row => {
        const roleCell = row.cells[2].textContent.trim();
        if (tabName === 'all-users') {
            row.style.display = '';
        } else if (tabName === 'Project manager' && roleCell === 'Project Manager') {
            row.style.display = '';
        } else if (tabName === 'Marketing agent' && roleCell === 'Marketing Agent') {
            row.style.display = '';
        } else if (tabName === 'Accountant' && roleCell === 'Accountant') {
            row.style.display = '';
        } else if (tabName === 'Customer' && (roleCell === 'Customer' || roleCell === 'client')) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterUsersByRole(role) {
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(row => {
        if (role === 'all-users') {
            row.style.display = '';
        } else {
            // Adapter ici selon la valeur exacte du rôle dans la colonne
            const userRole = row.cells[2].textContent.trim()
        }
    });
}

// ...existing code...

        // Filter functionality
       function filterUsers(status) {
    // Met à jour le bouton actif
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Filtre les lignes du tableau selon le statut
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(row => {
        const statusElement = row.querySelector('.status-badge');
        const userStatus = statusElement ? statusElement.textContent.trim().toLowerCase() : '';
        if (status === 'all') {
            row.style.display = '';
        } else if (userStatus === status.toLowerCase()) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
        // Modal functionality
      function openModal(modalType) {
    const modal = document.getElementById(modalType + 'Modal');
    if (!modal) return;
    
    modal.style.display = 'block';
    
    // Réinitialiser seulement si c'est une NOUVELLE création
    if (modalType === 'createUser') {
        const editId = document.getElementById('editUserId').value;
        
        if (!editId || editId === '') {
            // MODE CRÉATION - Réinitialiser le formulaire
            document.getElementById('createUserForm').reset();
            document.getElementById('editUserId').value = '';
            document.querySelector('#createUserModal .modal-title').textContent = 'Créer un nouvel utilisateur';
            const submitBtn = document.querySelector('#createUserForm button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-plus"></i> Créer utilisateur';
        }
        // Si editId existe, on est en mode édition, ne pas réinitialiser
    }
}
// Ajoutez cette fonction pour tester
function testEditUser() {
    // Test avec le premier utilisateur du tableau
    const firstRow = document.querySelector('#usersTable tbody tr');
    if (firstRow) {
        const id = firstRow.getAttribute('data-id');
        console.log('Test avec ID:', id);
        editUser(id);
    }
}

// Vous pouvez appeler testEditUser() dans la console pour tester
        function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
}

        // User management functions
        function viewUser(id) {
            showNotification('Viewing user details for ID: ' + id);
        }

   function editUser(id) {
    console.log('=== MODE EDITION ===');
    console.log('ID utilisateur à modifier:', id);
    
    const row = document.querySelector(`#usersTable tbody tr[data-id='${id}']`);
    if (!row) {
        console.error('Ligne introuvable pour ID:', id);
        return;
    }
    
    // Récupérer les données de la ligne
    const nom = row.getAttribute('data-nom');
    const email = row.getAttribute('data-email');
    const titre = row.getAttribute('data-titre');
    const statut = row.getAttribute('data-statut');
    
    console.log('Données récupérées:', { nom, email, titre, statut });
    
    // Remplir le formulaire
    document.getElementById('newUserName').value = nom || '';
    document.getElementById('newUserEmail').value = email || '';
    document.getElementById('newUserRole').value = titre || '';
    document.getElementById('newUserStatut').value = statut || '';
    
    // TRÈS IMPORTANT: Définir l'ID pour le mode édition
    document.getElementById('editUserId').value = id;
    
    console.log('ID défini dans le champ caché:', document.getElementById('editUserId').value);
    
    // Changer le titre du modal et le texte du bouton
    document.querySelector('#createUserModal .modal-title').textContent = 'Modifier l\'utilisateur';
    const submitBtn = document.querySelector('#createUserForm button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save"></i> Mettre à jour';
    
    // Ouvrir le modal
    openModal('createUser');
}
        
        // Role management functions
        function editRole(id) {
            showNotification('Editing role with ID: ' + id);
        }

        function viewPermissions(id) {
            showNotification('Viewing permissions for role ID: ' + id);
        }

        // Settings functions
        function saveModules() {
            showNotification('Module settings saved successfully', 'success');
        }

        function saveLanguageSettings() {
            const language = document.getElementById('systemLanguage').value;
            showNotification('Language settings saved. System language set to: ' + language, 'success');
        }

        function saveProfile() {
            showNotification('Profile updated successfully', 'success');
        }

        function changePassword() {
            const current = document.getElementById('currentPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            
            if (!current || !newPass || !confirm) {
                showNotification('Please fill in all password fields', 'error');
                return;
            }
            
            if (newPass !== confirm) {
                showNotification('New passwords do not match', 'error');
                return;
            }
            
            showNotification('Password changed successfully', 'success');
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        }

        function changeAvatar() {
            showNotification('Avatar upload functionality would be implemented here');
        }

        function generateReport() {
            const reportType = document.getElementById('reportType').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            if (!startDate || !endDate) {
                showNotification('Please select date range', 'error');
                return;
            }
            
            showNotification('Generating ' + reportType + ' report...', 'success');
            
            // Simulate report generation
            setTimeout(() => {
                showNotification('Report generated and downloaded', 'success');
            }, 2000);
        }

        function saveSecuritySettings() {
            showNotification('Security settings saved successfully', 'success');
        }

        function saveSessionSettings() {
            showNotification('Session settings saved successfully', 'success');
        }

        function exportData() {
            showNotification('Exporting data...', 'success');
            setTimeout(() => {
                showNotification('Data exported successfully', 'success');
            }, 1500);
        }

        // Notification system
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notificationMessage');
            
            messageEl.textContent = message;
            notification.className = `notification ${type}`;
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
            }
        });

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date as default for report generation
            const today = new Date().toISOString().split('T')[0];
            const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            
            if (startDate && endDate) {
                startDate.value = weekAgo;
                endDate.value = today;
            }
            
            
        });
  
  window.addEventListener("load", function() {
    document.getElementById("loader").classList.add("hidden");
  });