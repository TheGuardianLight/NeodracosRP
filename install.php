<?php
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Installation</title>
</head>
<body>

<div id="install-alert" class="alert alert-danger d-none" role="alert">
    <h1 class="text-center">Installation non autorisée !</h1>
    <p class="text-center">Présence du fichier <code>install.lock</code> détecté !</p>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div id="install-form" class="container mt-3">
    <h1 class="text-center">Configuration de la base de données</h1>

    <form id="database-config-form">
        <div class="mb-3">
            <label for="dbname" class="form-label">Nom de la base de données</label>
            <input type="text" class="form-control" id="dbname" required>
        </div>
        <div class="mb-3">
            <label for="dbuser" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="dbuser" required>
        </div>
        <div class="mb-3">
            <label for="dbpassword" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="dbpassword" required>
        </div>
        <div class="mb-3">
            <label for="dbhost" class="form-label">Hôte de la base de données</label>
            <input type="text" class="form-control" id="dbhost" required>
        </div>
        <div class="mb-3">
            <label for="dbport" class="form-label">Port</label>
            <input type="number" class="form-control" id="dbport" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre en place la base de données</button>
    </form>

    <div id="error-message" class="alert alert-danger d-none mt-3"></div>
    <div id="success-message" class="alert alert-success d-none mt-3"></div>
</div>

<script type="text/javascript">
    const installForm = document.getElementById('install-form');
    const installAlerte = document.getElementById('install-alert');

    $(document).ready(function () {
        // Effectue une requête AJAX pour vérifier si le fichier "install.lock" existe
        $.ajax({
            url: 'install.lock',
            type: 'HEAD',
            error: function () {
                // Le fichier "install.lock" n'existe pas, affiche 'installateur'
                installForm.classList.remove('d-none');
                installAlerte.classList.add('d-none');
            },
            success: function () {
                // Le fichier "install.lock" existe, affiche 'Installation non autorisée !'
                installForm.classList.add('d-none');
                installAlerte.classList.remove('d-none');
            }
        });
    });
</script>

<script>
    document.getElementById('database-config-form').addEventListener('submit', function (event) {
        event.preventDefault();

        const params = new URLSearchParams({
            dbname: document.getElementById('dbname').value,
            dbuser: document.getElementById('dbuser').value,
            dbpassword: document.getElementById('dbpassword').value,
            dbhost: document.getElementById('dbhost').value,
            dbport: document.getElementById('dbport').value,
        });

        let errorMessageElement = document.getElementById('error-message');
        let successMessageElement = document.getElementById('success-message');

        fetch('php/install_sql.php', {
            method: 'POST',
            body: params,
        }).then(response => {
            return response.text()
                .then(text => text ? JSON.parse(text) : {})
        }).then(data => {
            if (data.error) {
                let message = data.details;
                errorMessageElement.classList.remove('d-none');
                errorMessageElement.textContent = message;
            } else if (data.success) {
                successMessageElement.classList.remove('d-none');
                successMessageElement.textContent = data.success;

                setTimeout(function () {
                    window.location.href = "login.php";
                }, 5000);
            }
        }).catch(error => {
            errorMessageElement.classList.remove('d-none');
            errorMessageElement.textContent = "Erreur de connexion au serveur.";
        });
    });
</script>

</body>
</html>