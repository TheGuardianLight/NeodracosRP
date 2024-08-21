<?php
global $pdo;
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

// Récupérer le nom d'utilisateur de l'utilisateur actuellement connecté.
$user_connected = $_SESSION['user'];

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_rp'])) {
    $new_etat = intval($_POST['etat']);
    $new_date_debut = $_POST['date_debut'] ? $_POST['date_debut'] : null;
    $new_date_fin = $_POST['date_fin'] ? $_POST['date_fin'] : null;
    $rp_id = intval($rp['rp_id']); // Prenez l'ID du RP en cours d'affichage

    $stmt = $pdo->prepare('UPDATE roleplay SET rp_etat = ?, rp_date_debut = ?, rp_date_fin = ? WHERE rp_id = ?');
    if ($stmt->execute([$new_etat, $new_date_debut, $new_date_fin, $rp_id])) {
        echo '<div class="alert alert-success" role="alert">Les détails du RP ont été mis à jour avec succès.</div>';
        // Mettre à jour les détails du RP dans l'affichage
        $rp['etat_name'] = $pdo->query('SELECT etat_name FROM etat_rp WHERE etat_id = ' . $new_etat)->fetchColumn();
        $rp['rp_date_debut'] = $new_date_debut;
        $rp['rp_date_fin'] = $new_date_fin;
    } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors de la mise à jour des détails du RP.</div>';
    }
}

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