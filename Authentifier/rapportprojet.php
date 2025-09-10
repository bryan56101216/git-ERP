<?php
require("bd.php");
if(isset($_POST['valider']))

$nomRapport = $_POST['rapportName'] ?? '';
$projet     = $_POST['rapportProject'] ?? '';
$clients    = isset($_POST['rapportClients']) ? implode(", ", $_POST['rapportClients']) : '';
$fichier    = "";

// --- Gestion de l’upload du fichier ---
if (isset($_FILES['rapportFile']) && $_FILES['rapportFile']['error'] == 0) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['rapportFile']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['rapportFile']['tmp_name'], $targetFile)) {
        $fichier = $fileName;
    }
}

// --- Insertion dans la base ---
$sql = "INSERT INTO rapportprojet (nom_rapport, projet, clients, fichier) VALUES (nomRapport,nomProjet,nomClient,fichierRapport)";
$stmt = $access->prepare($sql);
$stmt->execute([$nomRapport,$projet,$clients,$fichier]);

// --- Redirection ou message ---
header("Location: ../chef projet marketing/index.php");
exit;
?>