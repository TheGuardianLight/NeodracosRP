<?php

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

// Handle form submission
global $pdo;

/**
 * Récupère le nom d'utilisateur de la personne actuellement connecté.
 */
$user_connected = $_SESSION['user'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete_rp'])) {
        // Suppression du RP
        $rpId = $_POST['rp_id'];
        $stmt = $pdo->prepare("DELETE FROM roleplay WHERE rp_id = :rp_id");
        $stmt->execute([':rp_id' => $rpId]);

        // Redirection pour éviter la resoumission du formulaire sur rafraîchissement
        header("Location: mes-rp.php");
        exit;
    } else {
        // Ajout d'un RP
        $reseauId = $_POST['reseau_id'];
        $oc1Id = $_POST['oc1_id'];
        $oc2Id = $_POST['oc2_id'];
        $typeId = $_POST['type_id'];
        $dateDebut = $_POST['date_debut'];
        $dateFin = !empty($_POST['date_fin']) ? $_POST['date_fin'] : null;
        $etatId = $_POST['etat_id'];
        $roleplayerId = $_POST['rp_roleplayer']; // Nouveau champ

        // Validate inputs
        if ($reseauId && $oc1Id && $oc2Id && $typeId && $dateDebut && $etatId && $roleplayerId) {
            // Insert data into database
            $stmt = $pdo->prepare("INSERT INTO roleplay (rp_reseau, rp_oc1_name, rp_oc2_name, rp_type, rp_date_debut, rp_date_fin, rp_etat, rp_owner, rp_roleplayer) 
                                   VALUES (:reseau_id, :oc1_id, :oc2_id, :type_id, :date_debut, :date_fin, :etat_id, :rp_owner, :rp_roleplayer)");
            $stmt->execute([
                ':reseau_id' => $reseauId,
                ':oc1_id' => $oc1Id,
                ':oc2_id' => $oc2Id,
                ':type_id' => $typeId,
                ':date_debut' => $dateDebut,
                ':date_fin' => $dateFin,
                ':etat_id' => $etatId,
                ':rp_owner' => $user_connected,
                ':rp_roleplayer' => $roleplayerId
            ]);

            // Redirect to avoid resubmission on refresh
            header("Location: add-rp.php");
            exit;
        } else {
            $error = "Veuillez remplir tous les champs nécessaires.";
        }
    }
}

// Fetch data for dropdowns
$reseauxStmt = $pdo->query("SELECT reseau_id, reseau_name FROM reseaux");
$reseaux = $reseauxStmt->fetchAll();

$personnagesStmt = $pdo->query("SELECT oc_id, oc_name FROM oc");
$personnages = $personnagesStmt->fetchAll();

$typesRpStmt = $pdo->query("SELECT type_id, type_name FROM type_rp");
$typesRp = $typesRpStmt->fetchAll();

$etatsRpStmt = $pdo->query("SELECT etat_id, etat_name FROM etat_rp");
$etatsRp = $etatsRpStmt->fetchAll();

// Nouveau : Fetch des roleplayers pour le dropdown
$roleplayersStmt = $pdo->query("SELECT roleplayer_id, roleplayer_name FROM roleplayer");
$roleplayers = $roleplayersStmt->fetchAll();

// Fetch all RP
$rpStmt = $pdo->query("SELECT rp.*, rs.reseau_name, oc1.oc_name as oc1_name, oc2.oc_name as oc2_name, tr.type_name, er.etat_name, rl.roleplayer_name 
                       FROM roleplay rp
                       JOIN reseaux rs ON rp.rp_reseau = rs.reseau_id
                       JOIN oc oc1 ON rp.rp_oc1_name = oc1.oc_id
                       JOIN oc oc2 ON rp.rp_oc2_name = oc2.oc_id
                       JOIN type_rp tr ON rp.rp_type = tr.type_id
                       JOIN etat_rp er ON rp.rp_etat = er.etat_id
                       JOIN roleplayer rl ON rp.rp_roleplayer = rl.roleplayer_id");
$rps = $rpStmt->fetchAll();

/**
 * Fonction pour afficher une couleur de badge en fonction de l'état du RP.
 */
function getBadgeClass($etat) {
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