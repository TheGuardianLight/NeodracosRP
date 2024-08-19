<?php global $config;
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

require 'vendor/autoload.php';
require 'php/db_connect.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Neodraco's Books</title>
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
            <h2 class="mb-3">Connexion</h2>
            <form id="loginForm" class="needs-validation mb-3" novalidate method="post" action="php/user_auth.php">
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Email est requis.
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Mot de passe est requis.
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Connexion</button>
                <?php if ($config['allowSignup'] == "true"): ?>
                    <a href="register.php" class="btn btn-secondary mb-3">S'inscrire</a>
                <?php endif; ?>
            </form>
            <?php if(isset($_SESSION['message'])): ?>
                <div id="message" class="alert alert-danger" role="alert">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<!-- JavaScript Bootstrap -->
<?php require 'js/bootstrap_script.html' ?>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')
        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

</body>

<?php require 'php/footer.php'?>

</html>