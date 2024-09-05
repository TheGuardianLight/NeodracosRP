<?php
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Books | Register</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <?php require 'php/favicon.php' ?>
</head>

<?php require 'php/menu.php' ?>

<body>

<div class="container login-register">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-3">Inscription</h2>
            <form id="registerForm" class="needs-validation mb-3" novalidate method="post">
                <div class="form-group mb-3">
                    <!-- Username input -->
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username"
                           placeholder="Nom d'utilisateur" required>
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">Le nom d'utilisateur est requis.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="firstname" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom"
                           required>
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">Le prénom est requis.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="lastname" class="form-label">Nom de famille</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nom de famille"
                           required>
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">Le nom de famille est requis.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">L'email est requis.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe"
                           required>
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">Le mot de passe est requis.</div>
                </div>
                <div class="form-group mb-3">
                    <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="password-confirm" name="password-confirm"
                           placeholder="Confirmer le mot de passe" required>
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">La confirmation du mot de passe est requise.</div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">S'inscrire</button>
            </form>
            <div id="message"></div>
        </div>
    </div>
</div>

<?php require 'js/bootstrap_script.html' ?>

<script>
    $(document).ready(function () {
        $("#registerForm").on("submit", function (event) {
            event.preventDefault();
            $.ajax({
                url: "php/register_user.php",
                type: "post",
                data: $(this).serialize(),
                complete: function (xhr, textStatus) {
                    var message = '';
                    if (xhr.status === 200) {
                        message = "Enregistrement effectué ! Redirection en cours...";
                        $("#message").html('<div class="alert alert-success" role="alert">' + message + '</div>');
                        setTimeout(function () {
                            window.location.href = "login.php";
                        }, 3000);
                    } else {
                        switch (xhr.status) {
                            case 400:
                                message = "Les mots de passe ne correspondent pas.";
                                break;
                            case 409:
                                message = "Echec de l'enregistrement : le nom d'utilisateur a déjà été pris.";
                                break;
                            case 405:
                                message = "Cette méthode n'accepte que les données POST.";
                                break;
                            default:
                                message = "Erreur de connexion au serveur.";
                                break;
                        }
                        $("#message").html('<div class="alert alert-danger" role="alert">' + message + '</div>');
                    }
                }
            });
        });
    });
</script>

</body>

<?php require 'php/footer.php' ?>

</html>