<?php
global $pdo;

session_start(); // Start the session

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/db_connect.php';
require 'php/detail-rp_managment.php';

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
        <div class="card-header bg-secondary text-white">
            <h2 class="h4 mb-0"><i class="fas fa-info-circle"></i> Détails du RP</h2>
        </div>
        <div class="card-body">
            <div>
                <h3><?= htmlspecialchars($rp['oc1_name']) ?> x <?= htmlspecialchars($rp['oc2_name']) ?></h3>
                <p>Sur : <?= htmlspecialchars($rp['reseau_name']) ?></p>
                <p>Type de RP : <strong><?= htmlspecialchars($rp['type_name']) ?></strong></p>
                <p>Avec : <strong><?= htmlspecialchars($rp['roleplayer_name']) ?></strong></p>
                <p>Date de début : <strong><?= date("d/m/Y", strtotime($rp['rp_date_debut'])) ?></strong></p>
                <p>Date de fin : <strong><?= $rp['rp_date_fin'] ? date("d/m/Y", strtotime($rp['rp_date_fin'])) : 'N/A' ?></strong></p>
                <p>État : <strong><?= htmlspecialchars($rp['etat_name']) ?></strong></p>
            </div>
            <hr>
            <div>
                <h4>Détails des Personnages</h4>
                <div>
                    <h5><?= htmlspecialchars($rp['oc1_name']) ?></h5>
                    <p>Genre : <?= htmlspecialchars($rp['oc1_gender']) ?></p>
                    <p>Propriétaire : <?= htmlspecialchars($rp['oc1_owner']) ?></p>
                </div>
                <div>
                    <h5><?= htmlspecialchars($rp['oc2_name']) ?></h5>
                    <p>Genre : <?= htmlspecialchars($rp['oc2_gender']) ?></p>
                    <p>Propriétaire : <?= htmlspecialchars($rp['oc2_owner']) ?></p>
                </div>
            </div>
            <hr>
            <div>
                <h4>Changer l'état du RP</h4>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="etat">Sélectionner le nouvel état :</label>
                        <select name="etat" id="etat" class="form-control">
                            <?php
                            $stmt = $pdo->query('SELECT * FROM etat_rp');
                            while ($row = $stmt->fetch()) {
                                echo '<option value="' . htmlspecialchars($row['etat_id']) . '">' . htmlspecialchars($row['etat_name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="change_etat" class="btn btn-primary mt-2">Modifier l'état</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'php/footer.php' ?>
<?php require 'js/bootstrap_script.html' ?>
</body>
</html>