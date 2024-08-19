<?php global $pdo;
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

/**
 * Page "personnages.php"
 */

session_start(); // Start the session

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/db_connect.php';
require 'php/oc_managment.php';

// Fetch all personnages
$stmt = $pdo->query("SELECT * FROM oc");
$personnages = $stmt->fetchAll();

// Fetch all rolepayers for the owner dropdown
$stmtRoleplayers = $pdo->query("SELECT roleplayer_id, roleplayer_name FROM roleplayer");
$roleplayers = $stmtRoleplayers->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Books | Personnages</title>
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
            <h2 class="h4 mb-0">Ajouter un personnage</h2>
        </div>
        <div class="card-body">
            <form action="personnages.php" method="post">
                <div class="mb-3">
                    <label for="personnage_name" class="form-label">Nom du personnage</label>
                    <input type="text" class="form-control" id="personnage_name" name="personnage_name" required>
                </div>
                <div class="mb-3">
                    <label for="personnage_gender" class="form-label">Genre du personnage</label>
                    <input type="text" class="form-control" id="personnage_gender" name="personnage_gender" required>
                </div>
                <div class="mb-3">
                    <label for="personnage_type" class="form-label">Type du personnage</label>
                    <input type="text" class="form-control" id="personnage_type" name="personnage_type" required>
                </div>
                <div class="mb-3">
                    <label for="personnage_owner" class="form-label">Propriétaire</label>
                    <select class="form-control" id="personnage_owner" name="personnage_owner" required>
                        <?php foreach ($roleplayers as $roleplayer): ?>
                            <option value="<?= htmlspecialchars($roleplayer['roleplayer_id']) ?>"><?= htmlspecialchars($roleplayer['roleplayer_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h2 class="h4 mb-0">Liste des personnages</h2>
        </div>
        <div class="card-body">
            <?php if (empty($personnages)): ?>
                <div class="alert alert-warning" role="alert">
                    Aucun personnage n'est enregistré
                </div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($personnages as $personnage): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Nom :</strong> <?= htmlspecialchars($personnage['oc_name']) ?><br>
                                <strong>Genre :</strong> <?= htmlspecialchars($personnage['oc_gender']) ?><br>
                                <strong>Type :</strong> <?= htmlspecialchars($personnage['oc_type']) ?><br>
                                <strong>Propriétaire :</strong>
                                <?php
                                $stmtOwner = $pdo->prepare("SELECT roleplayer_name FROM roleplayer WHERE roleplayer_id = :id");
                                $stmtOwner->execute(['id' => $personnage['oc_owner']]);
                                $owner = $stmtOwner->fetch();
                                echo htmlspecialchars($owner['roleplayer_name']);
                                ?>
                            </div>
                            <form action="personnages.php" method="post" class="m-0">
                                <input type="hidden" name="delete_personnage_id" value="<?= $personnage['oc_id'] ?>">
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