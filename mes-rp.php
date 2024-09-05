<?php
global $pdo, $personnages, $roleplayers, $etatsRp, $typesRp, $reseaux;

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

session_start(); // Start the session

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Récupérer le nom d'utilisateur de la personne actuellement connecté.
$user_connected = $_SESSION['user'];

require 'vendor/autoload.php';
require 'php/db_connect.php';

// Fonction pour obtenir la classe du badge
function getBadgeClass($etat)
{
    switch ($etat) {
        case 'Pas commencé':
            return 'bg-secondary';
        case 'En attente':
            return 'bg-warning';
        case 'En cours':
            return 'bg-primary';
        case 'Terminé':
            return 'bg-success';
        case 'Abandonné':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

// Fonction pour lutter les détails du RP
function rpDetail($label, $value)
{
    return "<span><strong>" . htmlspecialchars($label) . " :</strong> " . htmlspecialchars($value) . "</span>";
}

// Récupérer les RP de l'utilisateur connecté
$rpStmt = $pdo->prepare("SELECT rp.*, rs.reseau_name, oc1.oc_name as oc1_name, oc2.oc_name as oc2_name, tr.type_name, er.etat_name, rl.roleplayer_name 
                        FROM roleplay rp
                        JOIN reseaux rs ON rp.rp_reseau = rs.reseau_id
                        JOIN oc oc1 ON rp.rp_oc1_name = oc1.oc_id
                        JOIN oc oc2 ON rp.rp_oc2_name = oc2.oc_id
                        JOIN type_rp tr ON rp.rp_type = tr.type_id
                        JOIN etat_rp er ON rp.rp_etat = er.etat_id
                        JOIN roleplayer rl ON rp.rp_roleplayer = rl.roleplayer_id
                        WHERE rp.rp_owner = :user_connected");
$rpStmt->execute([':user_connected' => $user_connected]);
$rps = $rpStmt->fetchAll();
$groupedRps = [];

foreach ($rps as $rp) {
    $groupedRps[$rp['etat_name']][] = $rp;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's RP | Mes RP</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <?php require 'php/favicon.php' ?>
</head>

<body>
<?php require 'php/menu.php' ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h2 class="h4 mb-0"><i class="fas fa-list"></i> Mes RP</h2>
        </div>
        <div class="card-body">
            <?php if (empty($rps)): ?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> Aucun RP n'est enregistré
                </div>
            <?php else: ?>
                <?php foreach (['Pas commencé', 'En attente', 'En cours', 'Terminé', 'Abandonné'] as $etat): ?>
                    <?php if (!empty($groupedRps[$etat])): ?>
                        <h3 class="mt-4"><?= $etat ?></h3>
                        <ul class="list-group mb-4">
                            <?php foreach ($groupedRps[$etat] as $rp): ?>
                                <li class="list-group-item">
                                    <a href="detail-rp.php?id=<?= htmlspecialchars($rp['rp_id']) ?>"
                                       class="stretched-link text-decoration-none text-dark">
                                        <div>
                                            <div>
                                                <strong><?= htmlspecialchars($rp['oc1_name']) ?>
                                                    x <?= htmlspecialchars($rp['oc2_name']) ?></strong> |
                                                Sur <?= htmlspecialchars($rp['reseau_name']) ?>, avec
                                                <strong>&#64;<?= htmlspecialchars($rp['roleplayer_name']) ?></strong>
                                            </div>
                                            <div>
                                                Le RP est de type
                                                <strong><?= htmlspecialchars($rp['type_name']) ?></strong> et a commencé
                                                le
                                                <strong><?= $rp['rp_date_debut'] ? date("d/m/Y", strtotime($rp['rp_date_debut'])) : 'N/A' ?></strong>
                                                et a été terminé le
                                                <strong><?= $rp['rp_date_fin'] ? date("d/m/Y", strtotime($rp['rp_date_fin'])) : 'N/A' ?></strong>.
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <h3 class="mt-4"><?= $etat ?></h3>
                        <div class="alert alert-info">
                            Aucun RP dans l'état "<?= $etat ?>".
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'php/footer.php' ?>
<?php require 'js/bootstrap_script.html' ?>
</body>
</html>