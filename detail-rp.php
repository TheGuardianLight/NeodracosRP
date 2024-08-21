<?php
global $pdo, $rp;

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
            <div class="mb-4">
                <h3 class="mb-3"><?= htmlspecialchars($rp['oc1_name']) ?> <span class="text-muted">x</span> <?= htmlspecialchars($rp['oc2_name']) ?></h3>
                <ul class="list-unstyled">
                    <li><strong>Sur :</strong> <?= htmlspecialchars($rp['reseau_name']) ?></li>
                    <li><strong>Type de RP :</strong> <?= htmlspecialchars($rp['type_name']) ?></li>
                    <li><strong>Avec :</strong> <?= htmlspecialchars($rp['roleplayer_name']) ?></li>

                    <li><strong>Date de début :</strong> <?= $rp['rp_date_debut'] ? date("d/m/Y", strtotime($rp['rp_date_debut'])) : 'N/A' ?></strong></li>
                    <li><strong>Date de fin :</strong> <?= $rp['rp_date_fin'] ? date("d/m/Y", strtotime($rp['rp_date_fin'])) : 'N/A' ?></strong></li>
                    <li><strong>État :</strong> <span class="badge <?= getBadgeClass($rp['etat_name']) ?>"><?= htmlspecialchars($rp['etat_name']) ?></span></li>
                </ul>
            </div>
            <hr>
            <div>
                <h4><i class="fas fa-users"></i> Détails des Personnages</h4>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="card border-light">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($rp['oc1_name']) ?></h5>
                                <p class="card-text"><strong>Genre :</strong> <?= htmlspecialchars($rp['oc1_gender']) ?></p>
                                <p class="card-text"><strong>Propriétaire :</strong> <?= htmlspecialchars($rp['oc1_owner']) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-light">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($rp['oc2_name']) ?></h5>
                                <p class="card-text"><strong>Genre :</strong> <?= htmlspecialchars($rp['oc2_gender']) ?></p>
                                <p class="card-text"><strong>Propriétaire :</strong> <?= htmlspecialchars($rp['oc2_owner']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div>
                <h4><i class="fas fa-edit"></i> Modifier le RP</h4>
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
                    <div class="form-group mt-3">
                        <label for="date_debut">Nouvelle date de début :</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control" value="<?= htmlspecialchars($rp['rp_date_debut']) ?>">
                    </div>
                    <div class="form-group mt-3">
                        <label for="date_fin">Nouvelle date de fin :</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control" value="<?= htmlspecialchars($rp['rp_date_fin']) ?>">
                    </div>
                    <button type="submit" name="modifier_rp" class="btn btn-primary mt-3">Modifier le RP</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'php/footer.php' ?>
<?php require 'js/bootstrap_script.html' ?>
</body>
</html>