let payments = [
            { id: 1, number: 'PAY-001', invoice: 'FAC-001', client: 'Candice Sutton', amount: 25490, date: '2024-08-22', method: 'Virement Bancaire' },
            { id: 2, number: 'PAY-002', invoice: 'FAC-004', client: 'Elbert Figueroa', amount: 11040, date: '2024-08-20', method: 'Chèque' },
            { id: 3, number: 'PAY-003', invoice: 'FAC-006', client: 'John Marshall', amount: 25200, date: '2024-08-19', method: 'Carte de Crédit' }
        ];

        let credits = [
            { id: 1, number: 'CR-001', client: 'Kristina Townsend', invoice: 'FAC-002', amount: 5000, date: '2024-08-21', reason: 'Ajustement de service' },
            { id: 2, number: 'CR-002', client: 'Bernadette Green', invoice: 'FAC-008', amount: 2500, date: '2024-08-18', reason: 'Erreur de facturation' }
        ];

        let clients = [
            { id: 1, name: 'Candice Sutton', email: 'candice@example.com', phone: '+237-690062374', outstanding: 0, lastInvoice: '22 Juin 2017' },
            { id: 2, name: 'Kristina Townsend', email: 'kristina@example.com', phone: '+237-655468935', outstanding: 27000, lastInvoice: '20 Juin 2017' },
            { id: 3, name: 'Derrick Carter', email: 'derrick@example.com', phone: '+237-696357652', outstanding: 24600, lastInvoice: '12 Juin 2017' },
            { id: 4, name: 'Elbert Figueroa', email: 'elbert@example.com', phone: '+237-677895612', outstanding: 0, lastInvoice: '16 Juin 2017' },
            { id: 5, name: 'Rhonda Wells', email: 'rhonda@example.com', phone: '++237-653506199', outstanding: 52970, lastInvoice: '15 Juin 2017' }
        ];

        // Fonction utilitaire pour obtenir l'email du client
        function getClientEmail(clientName) {
            const client = clients.find(c => c.name === clientName);
            return client ? client.email : 'client@example.com';
        }

        // Fonctions de navigation
        function showSection(section) {
            // Masquer toutes les sections
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            // Afficher la section sélectionnée
            document.getElementById(section).classList.add('active');
            
            // Mettre à jour les liens de navigation
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-section') === section) {
                    link.classList.add('active');
                }
            });

            // Charger les données de la section
            loadSectionData(section);
        }

        // Écouteurs d'événements pour la navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                showSection(section);
            });
        });

        // Charger les données pour des sections spécifiques
        function loadSectionData(section) {
            switch(section) {
                case 'invoices':
                    loadInvoicesTable();
                    break;
                case 'quotes':
                    loadQuotesTable();
                    break;
                case 'payments':
                    loadPaymentsTable();
                    break;
                case 'credits':
                    loadCreditsTable();
                    break;
                case 'clients':
                    loadClientsTable();
                    break;
            }
        }

        // Charger les tableaux
        function loadInvoicesTable() {
            const tbody = document.getElementById('invoicesTable');
            tbody.innerHTML = invoices.map(invoice => `
                <tr>
                    <td>${invoice.number}</td>
                    <td>${invoice.client}</td>
                    <td>${invoice.date}</td>
                    <td>${invoice.dueDate}</td>
                    <td>${invoice.amount.toLocaleString()}XAF</td>
                    <td><span class="status-badge status-${invoice.status}">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editInvoice(${invoice.id})" title="Modifier Facture">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-send" onclick="sendInvoice(${invoice.id})" title="Envoyer Facture">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteInvoice(${invoice.id})" title="Supprimer Facture">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function loadQuotesTable() {
            const tbody = document.getElementById('quotesTable');
            tbody.innerHTML = quotes.map(quote => `
                <tr>
                    <td>${quote.number}</td>
                    <td>${quote.client}</td>
                    <td>${quote.date}</td>
                    <td>${quote.expiryDate}</td>
                    <td>${quote.amount.toLocaleString()}XAF</td>
                    <td><span class="status-badge status-${quote.status}">${quote.status.charAt(0).toUpperCase() + quote.status.slice(1)}</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editQuote(${quote.id})" title="Modifier Devis">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-send" onclick="sendQuote(${quote.id})" title="Envoyer Devis">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteQuote(${quote.id})" title="Supprimer Devis">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function loadPaymentsTable() {
            const tbody = document.getElementById('paymentsTable');
            tbody.innerHTML = payments.map(payment => `
                <tr>
                    <td>${payment.number}</td>
                    <td>${payment.invoice}</td>
                    <td>${payment.client}</td>
                    <td>${payment.amount.toLocaleString()}XAF</td>
                    <td>${payment.date}</td>
                    <td>${payment.method}</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editPayment(${payment.id})" title="Modifier Paiement">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deletePayment(${payment.id})" title="Supprimer Paiement">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function loadCreditsTable() {
            const tbody = document.getElementById('creditsTable');
            tbody.innerHTML = credits.map(credit => `
                <tr>
                    <td>${credit.number}</td>
                    <td>${credit.client}</td>
                    <td>${credit.invoice}</td>
                    <td>${credit.amount.toLocaleString()}XAF</td>
                    <td>${credit.date}</td>
                    <td>${credit.reason}</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editCredit(${credit.id})" title="Modifier Crédit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteCredit(${credit.id})" title="Supprimer Crédit">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function loadClientsTable() {
            const tbody = document.getElementById('clientsTable');
            tbody.innerHTML = clients.map(client => `
                <tr>
                    <td>${client.name}</td>
                    <td>${client.email}</td>
                    <td>${client.phone}</td>
                    <td>${client.outstanding.toLocaleString()}XAF</td>
                    <td>${client.lastInvoice}</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editClient(${client.id})" title="Modifier Client">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteClient(${client.id})" title="Supprimer Client">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Fonctions des modales
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            // Réinitialiser les formulaires lors de la fermeture
            const modal = document.getElementById(modalId);
            const forms = modal.querySelectorAll('form');
            forms.forEach(form => form.reset());
            
            // Réinitialiser les IDs d'édition
            if (modalId === 'invoiceModal') {
                document.getElementById('editInvoiceId').value = '';
                document.getElementById('invoiceModalTitle').textContent = 'Créer Nouvelle Facture';
                document.getElementById('invoiceSubmitText').textContent = 'Créer Facture';
            } else if (modalId === 'quoteModal') {
                document.getElementById('editQuoteId').value = '';
                document.getElementById('quoteModalTitle').textContent = 'Créer Nouveau Devis';
                document.getElementById('quoteSubmitText').textContent = 'Créer Devis';
            }
        }

        // Fonctions d'édition
    function editInvoice(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;
    const cells = row.querySelectorAll("td");

    document.getElementById('editInvoiceId').value = id;
    document.getElementById('invoiceNumber').value = cells[1].textContent.trim();
    document.getElementById('invoiceClient').value = cells[2].textContent.trim();

    // Formater la date
    function formatDateForInput(dateStr) {
        const d = new Date(dateStr);
        if (isNaN(d)) return '';
        return d.toISOString().split('T')[0]; // "YYYY-MM-DD"
    }

    document.getElementById('invoiceDate').value = formatDateForInput(cells[3].textContent.trim());
    document.getElementById('invoiceDueDate').value = formatDateForInput(cells[4].textContent.trim());

    document.getElementById('invoiceAmount').value = cells[5].textContent.trim().replace(/[^\d.-]/g, "");
    document.getElementById('invoiceDescription').value = cells[6] ? cells[6].textContent.trim() : '';

    // Ouvrir le modal
    openModal('invoiceModal');
    
}

function editQuote(id, nomDevis, nomClient, dateCreation, montantExtime, statut, description) {
    // Remplit les champs du formulaire du modal avec les valeurs du devis sélectionné
    document.getElementById('editQuoteId').value = id;
    document.getElementById('quoteName').value = nomDevis;
    document.getElementById('quoteClient').value = nomClient;
    document.getElementById('quoteDate').value = dateCreation;
    document.getElementById('quoteAmount').value = montantExtime;
    document.getElementById('quoteStatut').value = statut;
    document.getElementById('quoteDescription').value = description;

    // Change le titre et le texte du bouton pour indiquer l'édition
    document.getElementById('quoteModalTitle').textContent = 'Modifier Devis';
    document.getElementById('quoteSubmitText').textContent = 'Mettre à Jour Devis';

    // Ouvre le modal
    openModal('quoteModal');
}
    
function deleteInvoice(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette facture ?")) {
        // Redirige vers la même page avec un paramètre GET pour supprimer
        window.location.href = "?deleteInvoice=" + id;
    }
}
function deleteQuote(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce devis ?")) {
        window.location.href = "?deleteQuote=" + id;
    }
}
  window.addEventListener("load", function() {
    document.getElementById("loader").classList.add("hidden");
  });
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active'); // utilise "active" comme on a corrigé
}

// Fermer le menu dès qu'on clique sur un lien
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        const sidebar = document.getElementById('sidebar');
        // Vérifie si le sidebar est ouvert en mode mobile
        if (sidebar.classList.contains('active') && window.innerWidth <= 768) {
            sidebar.classList.remove('active'); // ferme le sidebar
        }
    });
});