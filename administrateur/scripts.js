// Variables globales
let currentSection = 'users';
let currentTab = 'all-users';

// Fonctionnalité de la barre latérale
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

// Gestion des sections
function showSection(sectionName) {
    // Masquer toutes les sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Afficher la section sélectionnée
    document.getElementById(sectionName + '-section').classList.remove('hidden');
    
    // Mettre à jour l'élément de navigation actif
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.classList.add('active');
    
    currentSection = sectionName;
}

// Fonctionnalité des onglets
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
        } else if (tabName === 'project-manager' && roleCell === 'Chef de Projet') {
            row.style.display = '';
        } else if (tabName === 'marketing-agent' && roleCell === 'Agent Marketing') {
            row.style.display = '';
        } else if (tabName === 'accountant' && roleCell === 'Comptable') {
            row.style.display = '';
        } else if (tabName === 'customer' && (roleCell === 'Client' || roleCell === 'Customer')) {
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

// Fonctionnalité de filtrage
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
        
        // Mapping des statuts français vers anglais pour la compatibilité
        let statusToCheck = status.toLowerCase();
        if (status === 'active') statusToCheck = 'actif';
        else if (status === 'inactive') statusToCheck = 'inactif';
        else if (status === 'pending') statusToCheck = 'en attente';
        
        if (status === 'all') {
            row.style.display = '';
        } else if (userStatus === statusToCheck || userStatus === status.toLowerCase()) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Fonctionnalité des modales
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
            document.querySelector('#createUserModal .modal-title').textContent = 'Créer un Nouvel Utilisateur';
            const submitBtn = document.querySelector('#createUserForm button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-plus"></i> Créer Utilisateur';
        }
        // Si editId existe, on est en mode édition, ne pas réinitialiser
    }
}

// Fonction de test pour l'édition
function testEditUser() {
    // Test avec le premier utilisateur du tableau
    const firstRow = document.querySelector('#usersTable tbody tr');
    if (firstRow) {
        const id = firstRow.getAttribute('data-id');
        console.log('Test avec ID:', id);
        editUser(id);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Fonctions de gestion des utilisateurs
function viewUser(id) {
    showNotification('Affichage des détails utilisateur pour ID: ' + id);
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
    document.querySelector('#createUserModal .modal-title').textContent = 'Modifier l\'Utilisateur';
    const submitBtn = document.querySelector('#createUserForm button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save"></i> Mettre à Jour';
    
    // Ouvrir le modal
    openModal('createUser');
}

// Fonctions de gestion des rôles
function editRole(id) {
    showNotification('Modification du rôle avec ID: ' + id);
}

function viewPermissions(id) {
    showNotification('Affichage des permissions pour le rôle ID: ' + id);
}

// Fonctions de paramètres
function saveModules() {
    showNotification('Paramètres des modules enregistrés avec succès', 'success');
}

function saveLanguageSettings() {
    const language = document.getElementById('systemLanguage').value;
    const languageNames = {
        'en': 'Anglais',
        'fr': 'Français',
        'es': 'Espagnol',
        'de': 'Allemand',
        'it': 'Italien',
        'pt': 'Portugais'
    };
    showNotification('Paramètres de langue enregistrés. Langue système définie sur: ' + languageNames[language], 'success');
}

function saveProfile() {
    showNotification('Profil mis à jour avec succès', 'success');
}

function changePassword() {
    const current = document.getElementById('currentPassword').value;
    const newPass = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmPassword').value;
    
    if (!current || !newPass || !confirm) {
        showNotification('Veuillez remplir tous les champs de mot de passe', 'error');
        return;
    }
    
    if (newPass !== confirm) {
        showNotification('Les nouveaux mots de passe ne correspondent pas', 'error');
        return;
    }
    
    showNotification('Mot de passe modifié avec succès', 'success');
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
}

function changeAvatar() {
    showNotification('La fonctionnalité de téléchargement d\'avatar serait implémentée ici');
}

function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate) {
        showNotification('Veuillez sélectionner une plage de dates', 'error');
        return;
    }
    
    const reportTypeNames = {
        'user-activity': 'rapport d\'activité utilisateurs',
        'system-usage': 'rapport d\'utilisation système',
        'security-log': 'rapport journal de sécurité',
        'performance': 'rapport de performance'
    };
    
    showNotification('Génération du ' + reportTypeNames[reportType] + '...', 'success');
    
    // Simuler la génération de rapport
    setTimeout(() => {
        showNotification('Rapport généré et téléchargé', 'success');
    }, 2000);
}

function saveSecuritySettings() {
    showNotification('Paramètres de sécurité enregistrés avec succès', 'success');
}

function saveSessionSettings() {
    showNotification('Paramètres de session enregistrés avec succès', 'success');
}

function exportData() {
    showNotification('Exportation des données...', 'success');
    setTimeout(() => {
        showNotification('Données exportées avec succès', 'success');
    }, 1500);
}

// Système de notifications
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

// Fonctionnalité de recherche
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

// Fermer les modales en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});

// Initialiser le tableau de bord
document.addEventListener('DOMContentLoaded', function() {
    // Définir la date d'aujourd'hui par défaut pour la génération de rapports
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
    const loader = document.getElementById("loader");
    if (loader) {
        loader.classList.add("hidden");
    }
});