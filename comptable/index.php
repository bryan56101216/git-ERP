<?php
session_start();

require("../Authentifier/factures.php");
   $factures = afficher();
require("../Authentifier/payements.php");
 $payements = afficherPayement();


require("../Authentifier/bd.php");
$clients = $access->query("SELECT nom FROM utilisateurs WHERE titre = 'customer'")->fetchAll(PDO::FETCH_ASSOC);
// $clients = $con->query("SELECT id, nom, email FROM utilisateurs WHERE titre = 'client'")->fetchAll(PDO::FETCH_ASSOC);

$access = new PDO('mysql:host=127.0.0.1;dbname=erpvision','root','');
if (isset($_POST['saveInvoice'])) {
    $client = htmlspecialchars($_POST['invoiceClient']);
    $statut = htmlspecialchars($_POST['statut']);
    $numero = htmlspecialchars($_POST['invoiceNumber']);
    $date = $_POST['invoiceDate'];
    $dueDate = $_POST['invoiceDueDate'];
    $amount = floatval($_POST['invoiceAmount']);
    $description = htmlspecialchars($_POST['invoiceDescription']);
    $invoiceId = isset($_POST['editInvoiceId']) ? intval($_POST['editInvoiceId']) : 0;

    if ($invoiceId > 0) {
        // UPDATE : ne modifie PAS la date d'échéance ni la description
$stmt = $access->prepare("UPDATE facture SET numeroFacture=?, nomClient=?, date=?, montant=?, statut=? WHERE idFacture=?");
        $stmt->execute([$numero,$client, $date, $amount, $statut, $invoiceId]);
        $message = "Invoice updated successfully.";
    } else {
        // INSERT : enregistre tout
        $stmt = $access->prepare("INSERT INTO facture (numeroFacture, nomClient, date, date_echeance, montant, statut, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$numero, $client, $date, $dueDate, $amount, $statut, $description]);
        $message = "Invoice successfully registered.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
// ...existing code...
if (isset($_POST['saveQuote'])) {
    $id = isset($_POST['editQuoteId']) ? intval($_POST['editQuoteId']) : 0;
    $nomDevi = htmlspecialchars($_POST['quoteName']);
    $statut = htmlspecialchars($_POST['statut']);
    $client = htmlspecialchars($_POST['quoteClient']);
    $date = $_POST['quoteDate'];
    $amount = floatval($_POST['quoteAmount']);
    $description = htmlspecialchars($_POST['quoteDescription']);

    if ($id > 0) {
        // UPDATE
        $stmt = $access->prepare("UPDATE devis SET nomDevis=?, nomClient=?, montantExtime=?, dateCreation=?, statut=?, description=? WHERE idDevis=?");
        $stmt->execute([$nomDevi, $client, $amount, $date, $statut, $description, $id]);
        $messageQuote = "Quote updated successfully.";
    } else {
        // INSERT
        $stmt = $access->prepare("INSERT INTO devis (nomDevis, nomClient, montantExtime, dateCreation, statut, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nomDevi, $client, $amount, $date, $statut, $description]);
        $messageQuote = "Quote successfully registered.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
if (isset($_GET['deleteQuote'])) {
    $id = intval($_GET['deleteQuote']);
    $stmt = $access->prepare("DELETE FROM devis WHERE idDevis = ?");
    $stmt->execute([$id]);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}
if (isset($_GET['deleteInvoice'])) {
    $id = intval($_GET['deleteInvoice']);
    $stmt = $access->prepare("DELETE FROM facture WHERE idFacture = ?");
    $stmt->execute([$id]);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // Recharge la page sans le paramètre
    exit();
}
if (isset($_POST['savePayment'])) {
    $numeroPayement = htmlspecialchars($_POST['paymentNumber']);
    $numerofacture = $_POST['paymentInvoice'];
    $nomClient = htmlspecialchars($_POST['paymentClient']);
    $date = $_POST['paymentDate'];
    $amount = floatval($_POST['paymentAmount']);
    $methodPayement = htmlspecialchars($_POST['paymentMethod']);
    $payementId = isset($_POST['editPaymentId']) ? intval($_POST['editPaymentId']) : 0;

     if ($paymentId > 0) {
         // UPDATE : ne modifie PAS la date d'échéance ni la description
     $stmt = $access->prepare("UPDATE payements SET numeroPayement=?, numeroFacture=?, nomClient=?, montantRecu=?, datePayement=?,  methodePayement=? WHERE idPayement=?");
        $stmt->execute([$numeroPayement,$numerofacture, $nomClient,  $amount, $date, $methodPayement, $payementId]);
        $message = "Invoice updated successfully.";
     } else {
        // INSERT : enregistre tout
        $stmt = $access->prepare("INSERT INTO payements (numeroPayement, numeroFacture, nomClient, montantRecu, datePayement, methodePayement) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$numeroPayement,$numerofacture, $nomClient,  $amount, $date, $methodPayement]);
        $message = "Invoice successfully registered.";
     }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
$devis = $access->query("SELECT * FROM devis")->fetchAll(PDO::FETCH_ASSOC);
// ...existing code...
if (!$_SESSION['nom']) {
   header("Location: ../index.php");

   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AccountPro Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="profile">
                <div class="profile-img">
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
                <div class="profile-info">
                    <h3><?php echo $_SESSION['nom']; ?></h3>
                    <p> <?php echo $_SESSION['titre']; ?></p>
                </div>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a class="nav-link active" data-section="dashboard">
                        <i class="fas fa-chart-bar nav-icon"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="invoices">
                        <i class="fas fa-file-invoice nav-icon"></i>
                        Invoices
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="quotes">
                        <i class="fas fa-file-contract nav-icon"></i>
                        Quotes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="payments">
                        <i class="fas fa-credit-card nav-icon"></i>
                        Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="credits">
                        <i class="fas fa-undo nav-icon"></i>
                        Credits & Refunds
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="clients">
                        <i class="fas fa-users nav-icon"></i>
                        Clients
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="reports">
                        <i class="fas fa-chart-line nav-icon"></i>
                        Reports
                    </a>
                </li>
            </ul>

            <div class="nav-bottom" >
                <a href="../Authentifier/deconnexion.php"  style="text-decoration:none;"><button class="logout-btn" >
                    <i class="fas fa-sign-out-alt nav-icon" ></i>
                    Log Out
                </button></a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search clients, invoices..." id="globalSearch">
                </div>
                <div class="header-actions">
                    
                    <button class="btn btn-primary" onclick="openModal('quickActionModal')">
                        <i class="fas fa-plus"></i>
                        Quick Action
                    </button>
                </div>
            </header>

            <!-- Dashboard Section -->
            <section class="content-section active" id="dashboard">
                <div class="section-header">
                    <h2 class="section-title">Dashboard Overview</h2>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card" onclick="showSection('invoices')">
                        <div class="stat-number" id="totalInvoices">24</div>
                        <div class="stat-label">Total Invoices</div>
                    </div>
                    <div class="stat-card" onclick="filterInvoicesByStatus('pending')">
                        <div class="stat-number" id="pendingAmount">$15,420</div>
                        <div class="stat-label">Pending Amount</div>
                    </div>
                    <div class="stat-card" onclick="filterInvoicesByStatus('paid')">
                        <div class="stat-number" id="paidAmount">$48,350</div>
                        <div class="stat-label">Paid This Month</div>
                    </div>
                    <div class="stat-card" onclick="filterInvoicesByStatus('overdue')">
                        <div class="stat-number" id="overdueCount">3</div>
                        <div class="stat-label">Overdue Invoices</div>
                    </div>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>n</th>
                                <th>Client</th>
                                <th>Invoice Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                  <tbody id="dashboardTable">
                        <?php foreach($factures as $facture): ?>
                        <tr>
                            <td><?= $facture->idFacture ?></td>
                            <td><?= $facture->nomClient ?></td>
                            <td><?= $facture->date ?></td>
                            <td>$<?= $facture->montant ?></td>
                            <td><?= $facture->statut ?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn btn-edit" type="button" onclick="editInvoice(<?= $facture->idFacture ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn btn-send"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </table>
                </div>
            </section>

            <!-- Invoices Section -->
            <section class="content-section" id="invoices">
                <div class="section-header">
                    <h2 class="section-title">Invoice Management</h2>
                    <button class="btn btn-primary" onclick="openModal('invoiceModal')">
                        <i class="fas fa-plus"></i>
                        New Invoice
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Invoice </th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($factures as $facture): ?>
                       <tr data-id="<?= $facture->idFacture ?>">
                            <td><?= $facture->idFacture ?></td>
                            <td><?= $facture->numeroFacture ?></td>
                            <td><?= $facture->nomClient ?></td>
                            <td><?= $facture->date ?></td>
                            <td><?= $facture->date_echeance ?></td>
                            <td>$<?= $facture->montant ?></td>
                            <td><?= $facture->statut ?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn btn-edit" type="button" onclick="editInvoice(<?= $facture->idFacture ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn btn-send"><i class="fas fa-paper-plane"></i></button>
                                    <button class="action-btn btn-delete" type="button" onclick="deleteInvoice(<?= $facture->idFacture ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Quotes Section -->
            <section class="content-section" id="quotes">
                <div class="section-header">
                    <h2 class="section-title">Quote Management</h2>
                    <button class="btn btn-primary" onclick="openModal('quoteModal')">
                        <i class="fas fa-plus"></i>
                        New Quote
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Quote </th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($devis as $i => $quote): ?>
                                <tr>
                                    <td><?= htmlspecialchars($quote['nomDevis']) ?></td>
                                    <td><?= htmlspecialchars($quote['nomClient']) ?></td>
                                    <td><?= htmlspecialchars($quote['dateCreation']) ?></td>
                                    <td><?= htmlspecialchars($quote['montantExtime']) ?></td>
                                    <td><?= htmlspecialchars($quote['statut']) ?></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn btn-edit" type="button"
                                                onclick="editQuote(
                                                    <?= $quote['idDevis'] ?>,
                                                    '<?= htmlspecialchars(addslashes($quote['nomDevis'])) ?>',
                                                    '<?= htmlspecialchars(addslashes($quote['nomClient'])) ?>',
                                                    '<?= $quote['dateCreation'] ?>',
                                                    '<?= $quote['montantExtime'] ?>',
                                                    '<?= htmlspecialchars(addslashes($quote['statut'])) ?>',
                                                    '<?= htmlspecialchars(addslashes($quote['description'] ?? '')) ?>'
                                                )">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn btn-send"><i class="fas fa-paper-plane"></i></button>
                                            <button class="action-btn btn-delete" type="button" onclick="deleteQuote(<?= $quote['idDevis'] ?>)">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Payments Section -->
            <section class="content-section" id="payments">
                <div class="section-header">
                    <h2 class="section-title">Payment Tracking</h2>
                    <button class="btn btn-primary" onclick="openModal('paymentModal')">
                        <i class="fas fa-plus"></i>
                        Record Payment
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Payment</th>
                                <th>Invoice</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody >
                             <?php foreach($payements as $payement): ?>
                        <tr>
                            <td><?= $payement->idPayement ?></td>
                            <td><?= $payement->numeroPayement ?></td>
                            <td><?= $payement->numeroFacture ?></td>
                            <td>$<?= $payement->nomClient ?></td>
                            <td><?= $payement->montantRecu?></td>
                            <td><?= $payement->datePayement?></td>
                            <td><?= $payement->methodePayement?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn btn-edit" type="button" onclick="editInvoice(<?= $facture->idFacture ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn btn-send"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Credits Section -->
            <section class="content-section" id="credits">
                <div class="section-header">
                    <h2 class="section-title">Credits & Refunds</h2>
                    <button class="btn btn-primary" onclick="openModal('creditModal')">
                        <i class="fas fa-plus"></i>
                        New Credit Note
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Credit</th>
                                <th>Client</th>
                                <th>Original Invoice</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="creditsTable">
                           
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Clients Section -->
            <section class="content-section" id="clients">
                <div class="section-header">
                    <h2 class="section-title">Client Management</h2>
                    <button class="btn btn-primary" onclick="openModal('clientModal')">
                        <i class="fas fa-plus"></i>
                        New Client
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Outstanding</th>
                                <th>Last Invoice</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientsTable">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Reports Section -->
            <section class="content-section" id="reports">
                <div class="section-header">
                    <h2 class="section-title">Financial Reports</h2>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">$63,770</div>
                        <div class="stat-label">Total Revenue (YTD)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">$15,420</div>
                        <div class="stat-label">Outstanding</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">89%</div>
                        <div class="stat-label">Collection Rate</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Days Avg Collection</div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Invoice Modal -->
    <div class="modal" id="invoiceModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('invoiceModal')">&times;</span>
            <h3 id="invoiceModalTitle">Create New Invoice</h3>
        <form method="POST">
           <input type="hidden" id="editInvoiceId" name="editInvoiceId" value="">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Client</label>
                    <select class="form-input" id="invoiceClient" name="invoiceClient" required>
                        <option value="">Select Client</option>
                        <?php foreach($clients as $client): ?>
                            <option value="<?= htmlspecialchars($client['nom']) ?>"><?= htmlspecialchars($client['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Invoice number</label>
                    <input type="text" class="form-input" id="invoiceNumber" name="invoiceNumber" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" class="form-input" id="invoiceDate" name="invoiceDate" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Due Date</label>
                    <input type="date" class="form-input" id="invoiceDueDate" name="invoiceDueDate" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input type="number" class="form-input" id="invoiceAmount" name="invoiceAmount" step="0.01" required>
                </div>
                 <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-input" id="invoiceStatut" name="statut" required>
                        <option value="">Select Statut</option>
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                            <option value="Overdue">Overdue</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-input" id="invoiceDescription" name="invoiceDescription" rows="3" placeholder="Service description..."></textarea>
            </div>
            <div class="form-row">
                <button type="button" class="btn btn-secondary" onclick="closeModal('invoiceModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" name="saveInvoice">
                    <i class="fas fa-save"></i>
                    <span id="invoiceSubmitText">Create Invoice</span>
                </button>
                <?php if(isset($message)) { echo '<p style="color:green;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>'; } ?>
            </div>
        </form>
        </div>
    </div>

    <!-- Quote Modal -->
    <div class="modal" id="quoteModal">
        <!-- Modal Quote Form -->
<div class="modal-content">
    <h3 id="quoteModalTitle">Create New Quote</h3>
    <form id="quoteForm" method="POST">
        <input type="hidden" id="editQuoteId" name="editQuoteId" value="">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Name Quote</label>
                <input type="text" class="form-input" id="quoteName" name="quoteName" required>
            </div>
            <div class="form-group">
                <label class="form-label">Client</label>
                <select class="form-input" id="quoteClient" name="quoteClient" required>
                    <option value="">Select Client</option>
                    <?php foreach($clients as $client): ?>
                        <option value="<?= htmlspecialchars($client['nom']) ?>"><?= htmlspecialchars($client['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Quote Date</label>
                <input type="date" class="form-input" id="quoteDate" name="quoteDate" required>
            </div>
            <div class="form-group">
                <label class="form-label">Amount</label>
                <input type="number" class="form-input" id="quoteAmount" name="quoteAmount" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Statut</label>
                <select class="form-input" id="quoteStatut" name="statut" required>
                    <option value="">Select Statut</option>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Overdue">Overdue</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Project Description</label>
            <textarea class="form-input" id="quoteDescription" name="quoteDescription" rows="3" placeholder="Project details..."></textarea>
        </div>
        <div class="form-row">
            <button type="button" class="btn btn-secondary" onclick="closeModal('quoteModal')">Cancel</button>
            <button type="submit" class="btn btn-primary" name="saveQuote">
                <i class="fas fa-save"></i>
                <span id="quoteSubmitText">Create Quote</span>
            </button>
            <?php if(isset($messageQuote)) { echo '<p style="color:green;text-align:center; font-weight: bold;margin-top:2%">'.$messageQuote.'</p>'; } ?>
        </div>
    </form>
</div>
    </div>

    <!-- Email Modal -->
    <div class="modal" id="emailModal">
        <div class="modal-content email-modal-content">
            <span class="close-btn" onclick="closeModal('emailModal')">&times;</span>
            <h3 id="emailModalTitle">Send Document</h3>
            
            <div class="email-preview">
                <div class="email-field">
                    <strong>To:</strong> <span id="emailRecipient"></span>
                </div>
                <div class="email-field">
                    <strong>From:</strong> accounting@yourcompany.com
                </div>
                <div class="email-field">
                    <strong>Subject:</strong> <span id="emailSubject"></span>
                </div>
            </div>

            <form id="emailForm" onsubmit="sendEmail(event)">
                <input type="hidden" id="emailDocumentType" value="">
                <input type="hidden" id="emailDocumentId" value="">
                
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-input" id="emailMessage" rows="6" placeholder="Your message here..."></textarea>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="emailCopyToSelf" checked> 
                        Send a copy to myself
                    </label>
                </div>

                <div class="form-row">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('emailModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Send Email
                    </button>
                    
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('paymentModal')">&times;</span>
            <h3>Record Payment</h3>
            <form id="paymentForm" method="POST">
        <input type="hidden" id="editPaymentId" name="editPaymentId" value="">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Payment number</label>
                        <input type="text" class="form-input" id="paymentDate" name="paymentNumber" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Invoice</label>
                        <select class="form-input" id="paymentInvoice" name="paymentInvoice" required>
                        <?php foreach($factures as $facture): ?>
                        <option value="<?=$facture->numeroFacture?>"><?= $facture->numeroFacture ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Date</label>
                        <input type="date" class="form-input" id="paymentDate" name="paymentDate" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                <label class="form-label">Client</label>
                <select class="form-input" id="paymentClient" name="paymentClient" required>
                    <option value="">Select Client</option>
                    <?php foreach($clients as $client): ?>
                        <option value="<?= htmlspecialchars($client['nom']) ?>"><?= htmlspecialchars($client['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                    <div class="form-group">
                        <label class="form-label">Amount Received</label>
                        <input type="number" class="form-input" id="paymentAmount" step="0.01" name="paymentAmount" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <select class="form-input" id="paymentMethod" name="paymentMethod" required>
                            <option value="">Select Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                            <option value="cash">Cash</option>
                            <option value="card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                </div>


                <div class="form-row">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('paymentModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="savePayment">
                        <i class="fas fa-save"></i>
                        Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Credit Modal -->
    <div class="modal" id="creditModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('creditModal')">&times;</span>
            <h3>Create Credit Note</h3>
            <form id="creditForm" onsubmit="submitCredit(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Client</label>
                        <select class="form-input" id="creditClient" required>
                            <option value="">Select Client</option>
                            <option value="candice">Candice Sutton</option>
                            <option value="kristina">Kristina Townsend</option>
                            <option value="derrick">Derrick Carter</option>
                            <option value="elbert">Elbert Figueroa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Original Invoice</label>
                        <select class="form-input" id="creditInvoice" required>
                            <option value="">Select Invoice</option>
                            <option value="INV-001">INV-001</option>
                            <option value="INV-002">INV-002</option>
                            <option value="INV-003">INV-003</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Credit Amount</label>
                        <input type="number" class="form-input" id="creditAmount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Credit Date</label>
                        <input type="date" class="form-input" id="creditDate" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason</label>
                    <textarea class="form-input" id="creditReason" rows="3" placeholder="Reason for credit note..." required></textarea>
                </div>
                <div class="form-row">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('creditModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Create Credit Note
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Client Modal -->
    <div class="modal" id="clientModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('clientModal')">&times;</span>
            <h3>Add New Client</h3>
            <form id="clientForm" onsubmit="submitClient(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Client Name</label>
                        <input type="text" class="form-input" id="clientName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" id="clientEmail" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-input" id="clientPhone" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company</label>
                        <input type="text" class="form-input" id="clientCompany">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea class="form-input" id="clientAddress" rows="3" placeholder="Complete address..."></textarea>
                </div>
                <div class="form-row">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('clientModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Add Client
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Action Modal -->
    <div class="modal" id="quickActionModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('quickActionModal')">&times;</span>
            <h3>Quick Actions</h3>
            <div style="display: grid; gap: 1rem; padding: 1rem 0;">
                <button class="btn btn-primary" onclick="openModal('invoiceModal'); closeModal('quickActionModal');">
                    <i class="fas fa-file-invoice"></i>
                    Create Invoice
                </button>
                <button class="btn btn-primary" onclick="openModal('quoteModal'); closeModal('quickActionModal');">
                    <i class="fas fa-file-contract"></i>
                    Create Quote
                </button>
                <button class="btn btn-primary" onclick="openModal('paymentModal'); closeModal('quickActionModal');">
                    <i class="fas fa-credit-card"></i>
                    Record Payment
                </button>
                <button class="btn btn-primary" onclick="openModal('creditModal'); closeModal('quickActionModal');">
                    <i class="fas fa-undo"></i>
                    Create Credit Note
                </button>
                <button class="btn btn-primary" onclick="openModal('clientModal'); closeModal('quickActionModal');">
                    <i class="fas fa-users"></i>
                    Add Client
                </button>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
    </body>
</html>