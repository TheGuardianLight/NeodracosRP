<?php
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */


global $pdo;
session_start();

require __DIR__ . '/../vendor/autoload.php';
require 'db_connect.php';

// Assign $pdo to $db
$db = isset($pdo) ? $pdo : null;

// Récupérez les données du formulaire
$email = $_POST['email'];
$password = $_POST['password'];

// Recherchez l'utilisateur dans la base de données
$query = $db->prepare('SELECT * FROM users WHERE users_email = :email');
$query->execute(['email' => $email]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Vérifiez si l'utilisateur existe et si le mot de passe est correct
if ($user && password_verify($password, $user['users_password'])) {
    // L'utilisateur est connecté : créez une nouvelle session
    $_SESSION['user'] = $user['users_username']; // Store the username to session
    $_SESSION['message'] = "Connexion réussie";
    header("Location: ../index.php");
} else {
    // L'authentification a échoué
    $_SESSION['message'] = "E-mail ou mot de passe incorrect";
    header("Location: ../login.php");
}
die();