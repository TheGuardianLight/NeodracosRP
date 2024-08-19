<?php global $pdo;
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $personnageName = $_POST['personnage_name'] ?? '';
    $personnageGender = $_POST['personnage_gender'] ?? '';
    $personnageType = $_POST['personnage_type'] ?? '';
    $personnageOwner = $_POST['personnage_owner'] ?? 0;

    if ($personnageName !== '' && $personnageGender !== '' && $personnageType !== '' && $personnageOwner > 0) {
        $stmt = $pdo->prepare("INSERT INTO oc (oc_name, oc_gender, oc_type, oc_owner) VALUES (:oc_name, :oc_gender, :oc_type, :oc_owner)");
        $stmt->execute([
            'oc_name' => $personnageName,
            'oc_gender' => $personnageGender,
            'oc_type' => $personnageType,
            'oc_owner' => $personnageOwner
        ]);
    }
}

// Handle form submission for deleting a character
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_personnage_id'])) {
    $personnageId = $_POST['delete_personnage_id'] ?? '';

    if ($personnageId !== '') {
        $stmt = $pdo->prepare("DELETE FROM oc WHERE oc_id = :id");
        $stmt->execute(['id' => $personnageId]);
    }
}