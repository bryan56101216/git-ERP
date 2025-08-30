

        let payments = [
            { id: 1, number: 'PAY-001', invoice: 'INV-001', client: 'Candice Sutton', amount: 25490, date: '2024-08-22', method: 'Bank Transfer' },
            { id: 2, number: 'PAY-002', invoice: 'INV-004', client: 'Elbert Figueroa', amount: 11040, date: '2024-08-20', method: 'Check' },
            { id: 3, number: 'PAY-003', invoice: 'INV-006', client: 'John Marshall', amount: 25200, date: '2024-08-19', method: 'Credit Card' }
        ];

        let credits = [
            { id: 1, number: 'CR-001', client: 'Kristina Townsend', invoice: 'INV-002', amount: 5000, date: '2024-08-21', reason: 'Service adjustment' },
            { id: 2, number: 'CR-002', client: 'Bernadette Green', invoice: 'INV-008', amount: 2500, date: '2024-08-18', reason: 'Billing error' }
        ];

        let clients = [
            { id: 1, name: 'Candice Sutton', email: 'candice@example.com', phone: '+237-690062374', outstanding: 0, lastInvoice: 'Jun 22, 2017' },
            { id: 2, name: 'Kristina Townsend', email: 'kristina@example.com', phone: '+237-655468935', outstanding: 27000, lastInvoice: 'Jun 20, 2017' },
            { id: 3, name: 'Derrick Carter', email: 'derrick@example.com', phone: '+237-696357652', outstanding: 24600, lastInvoice: 'Jun 12, 2017' },
            { id: 4, name: 'Elbert Figueroa', email: 'elbert@example.com', phone: '+237-677895612', outstanding: 0, lastInvoice: 'Jun 16, 2017' },
            { id: 5, name: 'Rhonda Wells', email: 'rhonda@example.com', phone: '++237-653506199', outstanding: 52970, lastInvoice: 'Jun 15, 2017' }
        ];

        // Utility function to get client email
        function getClientEmail(clientName) {
            const client = clients.find(c => c.name === clientName);
            return client ? client.email : 'client@example.com';
        }

        // Navigation functions
        function showSection(section) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            // Show selected section
            document.getElementById(section).classList.add('active');
            
            // Update nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-section') === section) {
                    link.classList.add('active');
                }
            });

            // Load section data
            loadSectionData(section);
        }

        // Event listeners for navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                showSection(section);
            });
        });

        // Load data for specific sections
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

        // Load tables
        function loadInvoicesTable() {
            const tbody = document.getElementById('invoicesTable');
            tbody.innerHTML = invoices.map(invoice => `
                <tr>
                    <td>${invoice.number}</td>
                    <td>${invoice.client}</td>
                    <td>${invoice.date}</td>
                    <td>${invoice.dueDate}</td>
                    <td>${invoice.amount.toLocaleString()}</td>
                    <td><span class="status-badge status-${invoice.status}">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editInvoice(${invoice.id})" title="Edit Invoice">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-send" onclick="sendInvoice(${invoice.id})" title="Send Invoice">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteInvoice(${invoice.id})" title="Delete Invoice">
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
                    <td>${quote.amount.toLocaleString()}</td>
                    <td><span class="status-badge status-${quote.status}">${quote.status.charAt(0).toUpperCase() + quote.status.slice(1)}</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editQuote(${quote.id})" title="Edit Quote">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-send" onclick="sendQuote(${quote.id})" title="Send Quote">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteQuote(${quote.id})" title="Delete Quote">
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
                    <td>${payment.amount.toLocaleString()}</td>
                    <td>${payment.date}</td>
                    <td>${payment.method}</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editPayment(${payment.id})" title="Edit Payment">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deletePayment(${payment.id})" title="Delete Payment">
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
                    <td>${credit.amount.toLocaleString()}</td>
                    <td>${credit.date}</td>
                    <td>${credit.reason}</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editCredit(${credit.id})" title="Edit Credit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteCredit(${credit.id})" title="Delete Credit">
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
                    <td>${client.outstanding.toLocaleString()}</td>
                    <td>${client.lastInvoice}</td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn btn-edit" onclick="editClient(${client.id})" title="Edit Client">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" onclick="deleteClient(${client.id})" title="Delete Client">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            // Reset forms when closing
            const modal = document.getElementById(modalId);
            const forms = modal.querySelectorAll('form');
            forms.forEach(form => form.reset());
            
            // Reset edit IDs
            if (modalId === 'invoiceModal') {
                document.getElementById('editInvoiceId').value = '';
                document.getElementById('invoiceModalTitle').textContent = 'Create New Invoice';
                document.getElementById('invoiceSubmitText').textContent = 'Create Invoice';
            } else if (modalId === 'quoteModal') {
                document.getElementById('editQuoteId').value = '';
                document.getElementById('quoteModalTitle').textContent = 'Create New Quote';
                document.getElementById('quoteSubmitText').textContent = 'Create Quote';
            }
        }

        // Edit functions
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
    document.getElementById('quoteModalTitle').textContent = 'Edit Quote';
    document.getElementById('quoteSubmitText').textContent = 'Update Quote';

    // Ouvre le modal
    openModal('quoteModal');
}
    
function deleteInvoice(id) {
    if (confirm("Are you sure you want to delete this invoice?")) {
        // Redirige vers la même page avec un paramètre GET pour supprimer
        window.location.href = "?deleteInvoice=" + id;
    }
}
function deleteQuote(id) {
    if (confirm("Are you sure you want to delete this quote?")) {
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