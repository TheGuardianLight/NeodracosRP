<?php global $pdo;
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleplayeurName = $_POST['roleplayeur_name'] ?? '';
    $roleplayeurReseau = $_POST['roleplayeur_reseau'] ?? 0;

    if ($roleplayeurName !== '' && $roleplayeurReseau > 0) {
        $stmt = $pdo->prepare("INSERT INTO roleplayer (roleplayer_name, roleplayer_reseau) VALUES (:roleplayer_name, :roleplayer_reseau)");
        $stmt->execute(['roleplayer_name' => $roleplayeurName, 'roleplayer_reseau' => $roleplayeurReseau]);
    }
}

// Handle form submission for deleting a roleplayeur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_roleplayeur_id'])) {
    $roleplayeurId = $_POST['delete_roleplayeur_id'];

    if ($roleplayeurId !== '') {
        $stmt = $pdo->prepare("DELETE FROM roleplayer WHERE roleplayer_id = :id");
        $stmt->execute(['id' => $roleplayeurId]);
    }
}