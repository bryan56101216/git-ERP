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
        $message = "Facture mise à jour avec succès.";
    } else {
        // INSERT : enregistre tout
        $stmt = $access->prepare("INSERT INTO facture (numeroFacture, nomClient, date, date_echeance, montant, statut, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$numero, $client, $date, $dueDate, $amount, $statut, $description]);
        $message = "Facture enregistrée avec succès.";
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
        $messageQuote = "Devis mis à jour avec succès.";
    } else {
        // INSERT
        $stmt = $access->prepare("INSERT INTO devis (nomDevis, nomClient, montantExtime, dateCreation, statut, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nomDevi, $client, $amount, $date, $statut, $description]);
        $messageQuote = "Devis enregistré avec succès.";
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
        $message = "Paiement mis à jour avec succès.";
     } else {
        // INSERT : enregistre tout
        $stmt = $access->prepare("INSERT INTO payements (numeroPayement, numeroFacture, nomClient, montantRecu, datePayement, methodePayement) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$numeroPayement,$numerofacture, $nomClient,  $amount, $date, $methodPayement]);
        $message = "Paiement enregistré avec succès.";
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord ComptePro</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
    <div class="container">
        <!-- Barre Latérale -->
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
                        Tableau de Bord
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="invoices">
                        <i class="fas fa-file-invoice nav-icon"></i>
                        Factures
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="quotes">
                        <i class="fas fa-file-contract nav-icon"></i>
                        Devis
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="payments">
                        <i class="fas fa-credit-card nav-icon"></i>
                        Paiements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="credits">
                        <i class="fas fa-undo nav-icon"></i>
                        Crédits & Remboursements
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
                        Rapports
                    </a>
                </li>
            </ul>

            <div class="nav-bottom" >
                <a href="../Authentifier/deconnexion.php"  style="text-decoration:none;"><button class="logout-btn" >
                    <i class="fas fa-sign-out-alt nav-icon" ></i>
                    Se Déconnecter
                </button></a>
            </div>
        </nav>

        <!-- Contenu Principal -->
        <main class="main-content">
            <header class="header">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher clients, factures..." id="globalSearch">
                </div>
                <div class="header-actions">
                    
                    <button class="btn btn-primary" onclick="openModal('quickActionModal')">
                        <i class="fas fa-plus"></i>
                        Action Rapide
                    </button>
                </div>
            </header>

            <!-- Section Tableau de Bord -->
            <section class="content-section active" id="dashboard">
                <div class="section-header">
                    <h2 class="section-title">Aperçu du Tableau de Bord</h2>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card" onclick="showSection('invoices')">
                        <div class="stat-number" id="totalInvoices">24</div>
                        <div class="stat-label">Total Factures</div>
                    </div>
                    <div class="stat-card" onclick="filterInvoicesByStatus('pending')">
                        <div class="stat-number" id="pendingAmount">15 420XAF</div>
                        <div class="stat-label">Montant en Attente</div>
                    </div>
                    <div class="stat-card" onclick="filterInvoicesByStatus('paid')">
                        <div class="stat-number" id="paidAmount">48 350XAF</div>
                        <div class="stat-label">Payé ce Mois</div>
                    </div>
                    <div class="stat-card" onclick="filterInvoicesByStatus('overdue')">
                        <div class="stat-number" id="overdueCount">3</div>
                        <div class="stat-label">Factures en Retard</div>
                    </div>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Client</th>
                                <th>Date Facture</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                  <tbody id="dashboardTable">
                        <?php foreach($factures as $facture): ?>
                        <tr>
                            <td><?= $facture->idFacture ?></td>
                            <td><?= $facture->nomClient ?></td>
                            <td><?= $facture->date ?></td>
                            <td><?= $facture->montant ?>XAF</td>
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

            <!-- Section Factures -->
            <section class="content-section" id="invoices">
                <div class="section-header">
                    <h2 class="section-title">Gestion des Factures</h2>
                    <button class="btn btn-primary" onclick="openModal('invoiceModal')">
                        <i class="fas fa-plus"></i>
                        Nouvelle Facture
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Facture</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Date Échéance</th>
                                <th>Montant</th>
                                <th>Statut</th>
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
                            <td><?= $facture->montant ?>XAF</td>
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

            <!-- Section Devis -->
            <section class="content-section" id="quotes">
                <div class="section-header">
                    <h2 class="section-title">Gestion des Devis</h2>
                    <button class="btn btn-primary" onclick="openModal('quoteModal')">
                        <i class="fas fa-plus"></i>
                        Nouveau Devis
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Devis</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($devis as $i => $quote): ?>
                                <tr>
                                    <td><?= htmlspecialchars($quote['nomDevis']) ?></td>
                                    <td><?= htmlspecialchars($quote['nomClient']) ?></td>
                                    <td><?= htmlspecialchars($quote['dateCreation']) ?></td>
                                    <td><?= htmlspecialchars($quote['montantExtime']) ?>XAF</td>
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

            <!-- Section Paiements -->
            <section class="content-section" id="payments">
                <div class="section-header">
                    <h2 class="section-title">Suivi des Paiements</h2>
                    <button class="btn btn-primary" onclick="openModal('paymentModal')">
                        <i class="fas fa-plus"></i>
                        Enregistrer Paiement
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Paiement</th>
                                <th>Facture</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Date Paiement</th>
                                <th>Méthode</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody >
                             <?php foreach($payements as $payement): ?>
                        <tr>
                            <td><?= $payement->idPayement ?></td>
                            <td><?= $payement->numeroPayement ?></td>
                            <td><?= $payement->numeroFacture ?></td>
                            <td><?= $payement->nomClient ?></td>
                            <td><?= $payement->montantRecu ?>XAF</td>
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

            <!-- Section Crédits -->
            <section class="content-section" id="credits">
                <div class="section-header">
                    <h2 class="section-title">Crédits & Remboursements</h2>
                    <button class="btn btn-primary" onclick="openModal('creditModal')">
                        <i class="fas fa-plus"></i>
                        Nouvelle Note de Crédit
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Crédit</th>
                                <th>Client</th>
                                <th>Facture Originale</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Motif</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="creditsTable">
                           
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Section Clients -->
            <section class="content-section" id="clients">
                <div class="section-header">
                    <h2 class="section-title">Gestion des Clients</h2>
                    <button class="btn btn-primary" onclick="openModal('clientModal')">
                        <i class="fas fa-plus"></i>
                        Nouveau Client
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nom Client</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>En Souffrance</th>
                                <th>Dernière Facture</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientsTable">
                            <!-- Les données seront peuplées par JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Section Rapports -->
            <section class="content-section" id="reports">
                <div class="section-header">
                    <h2 class="section-title">Rapports Financiers</h2>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">63 770XAF</div>
                        <div class="stat-label">Chiffre d'Affaires Total (YTD)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">15 420XAF</div>
                        <div class="stat-label">En Souffrance</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">89%</div>
                        <div class="stat-label">Taux de Recouvrement</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Délai Moy. Recouvrement</div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal Facture -->
    <div class="modal" id="invoiceModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('invoiceModal')">&times;</span>
            <h3 id="invoiceModalTitle">Créer Nouvelle Facture</h3>
        <form method="POST">
           <input type="hidden" id="editInvoiceId" name="editInvoiceId" value="">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Client</label>
                    <select class="form-input" id="invoiceClient" name="invoiceClient" required>
                        <option value="">Sélectionner Client</option>
                        <?php foreach($clients as $client): ?>
                            <option value="<?= htmlspecialchars($client['nom']) ?>"><?= htmlspecialchars($client['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Numéro facture</label>
                    <input type="text" class="form-input" id="invoiceNumber" name="invoiceNumber" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Date Facture</label>
                    <input type="date" class="form-input" id="invoiceDate" name="invoiceDate" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Date Échéance</label>
                    <input type="date" class="form-input" id="invoiceDueDate" name="invoiceDueDate" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Montant</label>
                    <input type="number" class="form-input" id="invoiceAmount" name="invoiceAmount" step="0.01" required>
                </div>
                 <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-input" id="invoiceStatut" name="statut" required>
                        <option value="">Sélectionner Statut</option>
                            <option value="Payée">Payée</option>
                            <option value="En Attente">En Attente</option>
                            <option value="En Retard">En Retard</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-input" id="invoiceDescription" name="invoiceDescription" rows="3" placeholder="Description du service..."></textarea>
            </div>
            <div class="form-row">
                <button type="button" class="btn btn-secondary" onclick="closeModal('invoiceModal')">Annuler</button>
                <button type="submit" class="btn btn-primary" name="saveInvoice">
                    <i class="fas fa-save"></i>
                    <span id="invoiceSubmitText">Créer Facture</span>
                </button>
                <?php if(isset($message)) { echo '<p style="color:green;text-align:center; font-weight: bold;margin-top:2%">'.$message.'</p>'; } ?>
            </div>
        </form>
        </div>
    </div>

    <!-- Modal Devis -->
    <div class="modal" id="quoteModal">
        <!-- Formulaire Modal Devis -->
<div class="modal-content">
    <h3 id="quoteModalTitle">Créer Nouveau Devis</h3>
    <form id="quoteForm" method="POST">
        <input type="hidden" id="editQuoteId" name="editQuoteId" value="">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nom Devis</label>
                <input type="text" class="form-input" id="quoteName" name="quoteName" required>
            </div>
            <div class="form-group">
                <label class="form-label">Client</label>
                <select class="form-input" id="quoteClient" name="quoteClient" required>
                    <option value="">Sélectionner Client</option>
                    <?php foreach($clients as $client): ?>
                        <option value="<?= htmlspecialchars($client['nom']) ?>"><?= htmlspecialchars($client['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Date Devis</label>
                <input type="date" class="form-input" id="quoteDate" name="quoteDate" required>
            </div>
            <div class="form-group">
                <label class="form-label">Montant</label>
                <input type="number" class="form-input" id="quoteAmount" name="quoteAmount" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Statut</label>
                <select class="form-input" id="quoteStatut" name="statut" required>
                    <option value="">Sélectionner Statut</option>
                    <option value="Payé">Payé</option>
                    <option value="En Attente">En Attente</option>
                    <option value="En Retard">En Retard</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Description Projet</label>
            <textarea class="form-input" id="quoteDescription" name="quoteDescription" rows="3" placeholder="Détails du projet..."></textarea>
        </div>
        <div class="form-row">
            <button type="button" class="btn btn-secondary" onclick="closeModal('quoteModal')">Annuler</button>
            <button type="submit" class="btn btn-primary" name="saveQuote">
                <i class="fas fa-save"></i>
                <span id="quoteSubmitText">Créer Devis</span>
            </button>
            <?php if(isset($messageQuote)) { echo '<p style="color:green;text-align:center; font-weight: bold;margin-top:2%">'.$messageQuote.'</p>'; } ?>
        </div>
    </form>
</div>
    </div>

    <!-- Modal Email -->
    <div class="modal" id="emailModal">
        <div class="modal-content email-modal-content">
            <span class="close-btn" onclick="closeModal('emailModal')">&times;</span>
            <h3 id="emailModalTitle">Envoyer Document</h3>
            
            <div class="email-preview">
                <div class="email-field">
                    <strong>À:</strong> <span id="emailRecipient"></span>
                </div>
                <div class="email-field">
                    <strong>De:</strong> comptabilite@votreentreprise.com
                </div>
                <div class="email-field">
                    <strong>Objet:</strong> <span id="emailSubject"></span>
                </div>
            </div>

            <form id="emailForm" onsubmit="sendEmail(event)">
                <input type="hidden" id="emailDocumentType" value="">
                <input type="hidden" id="emailDocumentId" value="">
                
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-input" id="emailMessage" rows="6" placeholder="Votre message ici..."></textarea>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="emailCopyToSelf" checked> 
                        M'envoyer une copie
                    </label>
                </div>

                <div class="form-row">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('emailModal')">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Envoyer Email
                    </button>
                    
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Paiement -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('paymentModal')">&times;</span>
            <h3>Enregistrer Paiement</h3>
            <form id="paymentForm" method="POST">
        <input type="hidden" id="editPaymentId" name="editPaymentId" value="">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Numéro paiement</label>
                        <input type="text" class="form-input" id="paymentNumber" name="paymentNumber" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Facture</label>
                        <select class="form-input" id="paymentInvoice" name="paymentInvoice" required>
                        <?php foreach($factures as $facture): ?>
                        <option value="<?=$facture->numeroFacture?>"><?= $facture->numeroFacture ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Paiement</label>
                        <input type="date" class="form-input" id="paymentDate" name="paymentDate" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                <label class="form-label">Client</label>
                <select class="form-input" id="paymentClient" name="paymentClient" required>
                    <option value="">Sélectionner Client</option>
                    <?php foreach($clients as $client): ?>
                        <option value="<?= htmlspecialchars($client['nom']) ?>"><?= htmlspecialchars($client['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                    <div class="form-group">
                        <label class="form-label">Montant Reçu</label>
                        <input type="number" class="form-input" id="paymentAmount" step="0.01" name="paymentAmount" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Méthode de Paiement</label>
                        <select class="form-input" id="paymentMethod" name="paymentMethod" required>
                            <option value="">Sélectionner Méthode</option>
                            <option value="virement_bancaire">Virement Bancaire</option>
                            <option value="cheque">Chèque</option>
                            <option value="especes">Espèces</option>
                            <option value="carte">Carte de Crédit</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                </div>


                <div class="form-row">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('paymentModal')">Annuler</button>
                    <button type="submit" class="btn btn-primary" name="savePayment">
                        <i class="fas fa-save"></i>
                        Enregistrer Paiement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Crédit -->
    <div class="modal" id="creditModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('creditModal')">&times;</span>
            <h3>Créer Note de Crédit</h3>
            <form id="creditForm" onsubmit="submitCredit(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Client</label>
                        <select class="form-input" id="creditClient" required>
                            <option value="">Sélectionner Client</option>
                            <option value="candice">Candice Sutton</option>
                            <option value="kristina">Kristina Townsend</option>
                            <option value="derrick">Derrick Carter</option>
                            <option value="elbert">Elbert Figueroa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Facture Originale</label>
                        <select class="form-input" id="creditInvoice" required>
                            <option value="">Sélectionner Facture</option>
                            <option value="INV-001">FAC-001</option>
                            <option value="INV-002">FAC-002</option>
                            <option value="INV-003">FAC-003</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Montant du Crédit</label>
                        <input type="number" class="form-input" id="creditAmount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date du Crédit</label>
                        <input type="date" class="form-input" id="creditDate" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Motif</label>
                    <textarea class="form-input" id="creditReason" rows="3" placeholder="Motif de la note de crédit..." required></textarea>
                </div>
                <div class="form-row">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('creditModal')">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Créer Note de Crédit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Client -->
    <div class="modal" id="clientModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('clientModal')">&times;</span>
            <h3>Ajouter Nouveau Client</h3>
            <form id="clientForm" onsubmit="submitClient(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom Client</label>
                        <input type="text" class="form-input" id="clientName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" id="clientEmail" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" class="form-input" id="clientPhone" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Entreprise</label>
                        <input type="text" class="form-input" id="clientCompany">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Adresse</label>
                    <textarea class="form-input" id="clientAddress" rows="3" placeholder="Adresse complète..."></textarea>
                </div>
                <div class="form-row">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('clientModal')">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Ajouter Client
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Action Rapide -->
    <div class="modal" id="quickActionModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('quickActionModal')">&times;</span>
            <h3>Actions Rapides</h3>
            <div style="display: grid; gap: 1rem; padding: 1rem 0;">
                <button class="btn btn-primary" onclick="openModal('invoiceModal'); closeModal('quickActionModal');">
                    <i class="fas fa-file-invoice"></i>
                    Créer Facture
                </button>
                <button class="btn btn-primary" onclick="openModal('quoteModal'); closeModal('quickActionModal');">
                    <i class="fas fa-file-contract"></i>
                    Créer Devis
                </button>
                <button class="btn btn-primary" onclick="openModal('paymentModal'); closeModal('quickActionModal');">
                    <i class="fas fa-credit-card"></i>
                    Enregistrer Paiement
                </button>
                <button class="btn btn-primary" onclick="openModal('creditModal'); closeModal('quickActionModal');">
                    <i class="fas fa-undo"></i>
                    Créer Note de Crédit
                </button>
                <button class="btn btn-primary" onclick="openModal('clientModal'); closeModal('quickActionModal');">
                    <i class="fas fa-users"></i>
                    Ajouter Client
                </button>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
    </body>
</html>