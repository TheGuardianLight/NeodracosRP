<?php global $pdo, $personnages, $roleplayers, $etatsRp, $typesRp, $reseaux;

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

/**
 * Page "add-rp.php"
 */

session_start(); // Start the session

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/db_connect.php';
require 'php/rp_managment.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's RP | Ajout de RP</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <?php require 'php/favicon.php' ?>
</head>

<?php require 'php/menu.php' ?>

<body>

<main class="container mt-5">
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">Ajouter un RP</h2>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form action="add-rp.php" method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="reseau_id" class="form-label">Réseau</label>
                        <select class="form-select" id="reseau_id" name="reseau_id" required
                                aria-label="Sélectionner un réseau">
                            <option value="" disabled selected>Sélectionnez un réseau</option>
                            <?php foreach ($reseaux as $reseau): ?>
                                <option value="<?= htmlspecialchars($reseau['reseau_id']) ?>"><?= htmlspecialchars($reseau['reseau_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="type_id" class="form-label">Type de RP</label>
                        <select class="form-select" id="type_id" name="type_id" required
                                aria-label="Sélectionner le type de RP">
                            <option value="" disabled selected>Sélectionnez le type de RP</option>
                            <?php foreach ($typesRp as $type): ?>
                                <option value="<?= htmlspecialchars($type['type_id']) ?>"><?= htmlspecialchars($type['type_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="oc1_id" class="form-label">Premier personnage</label>
                        <select class="form-select" id="oc1_id" name="oc1_id" required
                                aria-label="Sélectionner le premier personnage">
                            <option value="" disabled selected>Sélectionnez le premier personnage</option>
                            <?php foreach ($personnages as $personnage): ?>
                                <option value="<?= htmlspecialchars($personnage['oc_id']) ?>"><?= htmlspecialchars($personnage['oc_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="oc2_id" class="form-label">Second personnage</label>
                        <select class="form-select" id="oc2_id" name="oc2_id" required
                                aria-label="Sélectionner le second personnage">
                            <option value="" disabled selected>Sélectionnez le second personnage</option>
                            <?php foreach ($personnages as $personnage): ?>
                                <option value="<?= htmlspecialchars($personnage['oc_id']) ?>"><?= htmlspecialchars($personnage['oc_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <!-- Nouveau champ pour sélectionner le roleplayer -->
                    <div class="col-md-6">
                        <label for="rp_roleplayer" class="form-label">Avec qui je fais le RP</label>
                        <select class="form-select" id="rp_roleplayer" name="rp_roleplayer" required
                                aria-label="Sélectionner le roleplayer">
                            <option value="" disabled selected>Sélectionnez un roleplayer</option>
                            <?php foreach ($roleplayers as $roleplayer): ?>
                                <option value="<?= htmlspecialchars($roleplayer['roleplayer_id']) ?>"><?= htmlspecialchars($roleplayer['roleplayer_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut"
                               aria-describedby="dateDebutHelp">
                        <div id="dateDebutHelp" class="form-text">Format attendu : JJ/MM/AAAA</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin"
                               aria-describedby="dateFinHelp">
                        <div id="dateFinHelp" class="form-text">Format attendu : JJ/MM/AAAA</div>
                    </div>
                    <div class="col-md-6">
                        <label for="etat_id" class="form-label">État actuel</label>
                        <select class="form-select" id="etat_id" name="etat_id" required
                                aria-label="Sélectionner l'état actuel">
                            <option value="" disabled selected>Sélectionnez l'état actuel</option>
                            <?php foreach ($etatsRp as $etat): ?>
                                <option value="<?= htmlspecialchars($etat['etat_id']) ?>"><?= htmlspecialchars($etat['etat_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h2 class="h4 mb-0">
                <i class="fas fa-list"></i> Liste des RP
            </h2>
        </div>
        <div class="card-body">
            <?php if (empty($rps)): ?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> Aucun RP n'est enregistré
                </div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($rps as $rp): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div>
                                    <strong><?= htmlspecialchars($rp['oc1_name']) ?>
                                        x <?= htmlspecialchars($rp['oc2_name']) ?></strong> |
                                    Sur <?= htmlspecialchars($rp['reseau_name']) ?> avec
                                    <strong>@<?= htmlspecialchars($rp['roleplayer_name']) ?></strong>.
                                </div>
                                <div>
                                    Le RP est de type <strong><?= htmlspecialchars($rp['type_name']) ?></strong> et a
                                    commencé le
                                    <strong><?= $rp['rp_date_debut'] ? date("d/m/Y", strtotime($rp['rp_date_debut'])) : 'N/A' ?></strong>.
                                    Il a été terminé le
                                    <strong><?= $rp['rp_date_fin'] ? date("d/m/Y", strtotime($rp['rp_date_fin'])) : 'N/A' ?></strong>.
                                </div>
                                <div>
                                    État actuel : <span
                                            class="badge <?= getBadgeClass($rp['etat_name']) ?>"><?= htmlspecialchars($rp['etat_name']) ?></span>
                                </div>
                            </div>
                            <div>
                                <!-- Formulaire de suppression -->
                                <form action="add-rp.php" method="post" class="d-inline">
                                    <input type="hidden" name="rp_id" value="<?= htmlspecialchars($rp['rp_id']) ?>">
                                    <button type="submit" name="delete_rp" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce RP ?');">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require 'php/footer.php' ?>
<?php require 'js/bootstrap_script.html' ?>

</body>
</html>