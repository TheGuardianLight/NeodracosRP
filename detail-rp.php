<?php
global $pdo;

session_start(); // Start the session

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Récupérer le nom d'utilisateur de l'utilisateur actuellement connecté.
$user_connected = $_SESSION['user'];

require 'vendor/autoload.php';
require 'php/db_connect.php';

// Récupérer l'ID du RP depuis l'URL
$rp_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($rp_id <= 0) {
    echo 'Invalid RP ID';
    exit;
}

// Récupérer les détails du RP en fonction de l'ID et du propriétaire du RP
$rpStmt = $pdo->prepare("SELECT rp.*, 
                                rs.reseau_name, 
                                oc1.oc_name AS oc1_name, 
                                oc1.oc_gender AS oc1_gender,
                                oc1.oc_owner AS oc1_owner_id,
                                oc2.oc_name AS oc2_name, 
                                oc2.oc_gender AS oc2_gender,
                                oc2.oc_owner AS oc2_owner_id,
                                tr.type_name, 
                                er.etat_name, 
                                rl.roleplayer_name,
                                rl1.roleplayer_name AS oc1_owner,
                                rl2.roleplayer_name AS oc2_owner
                        FROM roleplay rp
                        JOIN reseaux rs ON rp.rp_reseau = rs.reseau_id
                        JOIN oc oc1 ON rp.rp_oc1_name = oc1.oc_id
                        JOIN oc oc2 ON rp.rp_oc2_name = oc2.oc_id
                        JOIN type_rp tr ON rp.rp_type = tr.type_id
                        JOIN etat_rp er ON rp.rp_etat = er.etat_id
                        JOIN roleplayer rl ON rp.rp_roleplayer = rl.roleplayer_id
                        JOIN roleplayer rl1 ON oc1.oc_owner = rl1.roleplayer_id
                        JOIN roleplayer rl2 ON oc2.oc_owner = rl2.roleplayer_id
                        WHERE rp.rp_id = :rp_id AND rp.rp_owner = :user_connected LIMIT 1");
$rpStmt->execute([':rp_id' => $rp_id, ':user_connected' => $user_connected]);
$rp = $rpStmt->fetch();

if (!$rp) {
    echo 'RP not found or you do not have permission to access this RP';
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's RP | Détails du RP</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <?php require 'php/favicon.php' ?>
</head>

<body>
<?php require 'php/menu.php' ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
            <h2 class="h4 mb-0 me-2"><i class="fas fa-info-circle"></i></h2>
            <h2 class="h4 mb-0">Détails du RP</h2>
        </div>
        <div class="card-body">
            <div>
                <h3 class="fw-bold"><?= htmlspecialchars($rp['oc1_name']) ?> x <?= htmlspecialchars($rp['oc2_name']) ?></h3>
                <p><strong>Sur :</strong> <?= htmlspecialchars($rp['reseau_name']) ?></p>
                <p><strong>Type de RP :</strong> <?= htmlspecialchars($rp['type_name']) ?></p>
                <p><strong>Avec :</strong> <?= htmlspecialchars($rp['roleplayer_name']) ?></p>
                <p><strong>Date de début :</strong> <?= date("d/m/Y", strtotime($rp['rp_date_debut'])) ?></p>
                <p><strong>Date de fin :</strong> <?= $rp['rp_date_fin'] ? date("d/m/Y", strtotime($rp['rp_date_fin'])) : 'N/A' ?></p>
                <p><strong>État :</strong> <?= htmlspecialchars($rp['etat_name']) ?></p>
            </div>
            <hr>
            <div>
                <h4 class="fw-bold">Détails des Personnages</h4>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="fw-semibold"><?= htmlspecialchars($rp['oc1_name']) ?></h5>
                        <p><strong>Genre :</strong> <?= htmlspecialchars($rp['oc1_gender']) ?></p>
                        <p><strong>Propriétaire :</strong> <?= htmlspecialchars($rp['oc1_owner']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-semibold"><?= htmlspecialchars($rp['oc2_name']) ?></h5>
                        <p><strong>Genre :</strong> <?= htmlspecialchars($rp['oc2_gender']) ?></p>
                        <p><strong>Propriétaire :</strong> <?= htmlspecialchars($rp['oc2_owner']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'php/footer.php' ?>
<?php require 'js/bootstrap_script.html' ?>
</body>
</html>