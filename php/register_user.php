<?php
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */
// register_user.php

// Use this library to work with .env file
global $pdo;

require __DIR__ . '/../vendor/autoload.php';
require 'db_connect.php';

// Get POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clean/prepare data to prevent XSS attack
    $firstname = htmlspecialchars($_POST["firstname"]);
    $lastname = htmlspecialchars($_POST["lastname"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $confirmpassword = htmlspecialchars($_POST["password-confirm"]);

    // Check if passwords match
    if ($password === $confirmpassword) {
        // Hash the password using Argon2
        $password = password_hash($password, PASSWORD_ARGON2ID);

        try {
            // Prepare our SQL query to prevent SQL injection
            $stmt = $pdo->prepare("INSERT INTO users (users_username, users_email, users_password)
                                   VALUES (:username, :email, :password)");

            // Execution of the SQL query
            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":password" => $password
            ]);

            $stmt = $pdo->prepare("INSERT INTO user_info (info_username, info_firstname, info_lastname)
                                   VALUES (:username, :firstname, :lastname)");

            // Execution of the SQL query
            $stmt->execute([
                ":username" => $username,
                ":firstname" => $firstname,
                ":lastname" => $lastname
            ]);

            // Registration successful: send a success status
            http_response_code(200);
        } catch (PDOException $e) {
            // Check for unique constraint violation
            if ($e->errorInfo[1] == 1062) {
                http_response_code(409);  // Conflict: Username already taken
            } else {
                http_response_code(500);  // Internal server error
            }
        }
    } else {
        http_response_code(400);  // Bad Request: Passwords do not match
    }
} else {
    http_response_code(405); // Method Not Allowed: Only POST requests are accepted
}

exit;