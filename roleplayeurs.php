<?php global $pdo;

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

/**
 * Page "roleplayeurs.php"
 */

session_start(); // Start the session

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/db_connect.php';
require 'php/roleplayeur_managment.php';

// Fetch all roleplayeurs
$stmt = $pdo->query("SELECT * FROM roleplayer");
$roleplayeurs = $stmt->fetchAll();

// Fetch all reseaux
$stmtReseaux = $pdo->query("SELECT reseau_id, reseau_name FROM reseaux");
$reseaux = $stmtReseaux->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Books | Roleplayeurs</title>
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
            <h2 class="h4 mb-0">Ajouter un roleplayeur</h2>
        </div>
        <div class="card-body">
            <form action="roleplayeurs.php" method="post">
                <div class="mb-3">
                    <label for="roleplayeur_name" class="form-label">Nom du roleplayeur</label>
                    <div class="input-group">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" class="form-control" id="roleplayeur_name" name="roleplayeur_name" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="roleplayeur_reseau" class="form-label">Réseau</label>
                    <select class="form-control" id="roleplayeur_reseau" name="roleplayeur_reseau" required>
                        <?php foreach ($reseaux as $reseau): ?>
                            <option value="<?= htmlspecialchars($reseau['reseau_id']) ?>"><?= htmlspecialchars($reseau['reseau_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h2 class="h4 mb-0">Liste des roleplayeurs</h2>
        </div>
        <div class="card-body">
            <?php if (empty($roleplayeurs)): ?>
                <div class="alert alert-warning" role="alert">
                    Aucun roleplayeur n'est enregistré
                </div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($roleplayeurs as $roleplayeur): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($roleplayeur['roleplayer_name']) ?>
                            <form action="roleplayeurs.php" method="post" class="m-0">
                                <input type="hidden" name="delete_roleplayeur_id"
                                       value="<?= $roleplayeur['roleplayer_id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
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