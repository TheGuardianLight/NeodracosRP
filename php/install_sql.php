<?php global $host, $port;

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

// Données reçues du formulaire
$dbname = $_POST['dbname'];
$dbuser = $_POST['dbuser'];
$dbpassword = $_POST['dbpassword'];
$dbhost = $_POST['dbhost'];
$dbport = $_POST['dbport'];

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $dbhost, $dbport, $dbname);

try {
    $connection = new PDO($dsn, $dbuser, $dbpassword);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
    CREATE TABLE `etat_rp`  (
      `etat_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID de l\'Etat',
      `etat_name` varchar(255) NOT NULL COMMENT 'Nom de l\'Etat',
      PRIMARY KEY (`etat_id`)
    ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
    
    CREATE TABLE `oc`  (
      `oc_id` int NOT NULL AUTO_INCREMENT,
      `oc_name` varchar(255) NULL,
      `oc_gender` varchar(255) NULL,
      `oc_owner` int NULL,
      `oc_type` varchar(255) NULL,
      PRIMARY KEY (`oc_id`)
    ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
    
    CREATE TABLE `reseaux`  (
      `reseau_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID du réseau social',
      `reseau_name` varchar(255) NOT NULL COMMENT 'Nom du réseau social',
      PRIMARY KEY (`reseau_id`)
    ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
    
    CREATE TABLE `roleplay`  (
      `rp_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID du rp',
      `rp_owner` varchar(255) NOT NULL COMMENT 'Propriétaire du RP (utilisateur connecté)',
      `rp_reseau` int NOT NULL COMMENT 'ID Réseau où se passe le rp',
      `rp_oc1_name` int NOT NULL COMMENT 'ID du Premier personnage du rp',
      `rp_oc2_name` int NOT NULL COMMENT 'ID du Second personnage du rp',
      `rp_type` int NULL COMMENT 'ID du Type de RP',
      `rp_roleplayer` int NULL COMMENT 'ID de la Personne avec qui je fait le RP',
      `rp_date_debut` datetime NULL COMMENT 'Date de début du RP',
      `rp_date_fin` datetime NULL COMMENT 'Date de fin du RP',
      `rp_etat` int NOT NULL COMMENT 'ID de l\'état du RP',
      PRIMARY KEY (`rp_id`)
    ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
    
    CREATE TABLE `roleplayer`  (
      `roleplayer_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID du roleplayer',
      `roleplayer_name` varchar(255) NULL COMMENT 'Nom d\'utilisateur du roleplayer',
      `roleplayer_reseau` int NULL COMMENT 'ID du Réseau social du roleplayer',
      PRIMARY KEY (`roleplayer_id`)
    ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
    
    CREATE TABLE `type_rp`  (
      `type_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID du type de rp',
      `type_name` varchar(255) NOT NULL COMMENT 'Nom du type de rp',
      PRIMARY KEY (`type_id`)
    ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
    
    CREATE TABLE `user_info`  (
      `info_id` int NOT NULL AUTO_INCREMENT,
      `info_username` varchar(255) NULL,
      `info_firstname` varchar(255) NULL,
      `info_lastname` varchar(255) NULL,
      PRIMARY KEY (`info_id`)
    ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
    
    CREATE TABLE `users`  (
      `users_username` varchar(255) NOT NULL,
      `users_email` varchar(255) NULL,
      `users_password` varchar(255) NULL,
      PRIMARY KEY (`users_username` DESC)
    ) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;
    
    ALTER TABLE `oc` ADD CONSTRAINT `oc_fk_1` FOREIGN KEY (`oc_owner`) REFERENCES `roleplayer` (`roleplayer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `roleplay` ADD CONSTRAINT `rp_fk_1` FOREIGN KEY (`rp_owner`) REFERENCES `users` (`users_username`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `roleplay` ADD CONSTRAINT `rp_fk_2` FOREIGN KEY (`rp_reseau`) REFERENCES `reseaux` (`reseau_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `roleplay` ADD CONSTRAINT `rp_fk_3` FOREIGN KEY (`rp_oc1_name`) REFERENCES `oc` (`oc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `roleplay` ADD CONSTRAINT `rp_fk_4` FOREIGN KEY (`rp_roleplayer`) REFERENCES `roleplayer` (`roleplayer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `roleplay` ADD CONSTRAINT `rp_fk_5` FOREIGN KEY (`rp_type`) REFERENCES `type_rp` (`type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `roleplay` ADD CONSTRAINT `rp_fk_6` FOREIGN KEY (`rp_etat`) REFERENCES `etat_rp` (`etat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `roleplay` ADD CONSTRAINT `rp_fk_7` FOREIGN KEY (`rp_oc2_name`) REFERENCES `oc` (`oc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `roleplayer` ADD CONSTRAINT `roleplayer_fk_1` FOREIGN KEY (`roleplayer_reseau`) REFERENCES `reseaux` (`reseau_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
    ALTER TABLE `user_info` ADD CONSTRAINT `info_fk_1` FOREIGN KEY (`info_username`) REFERENCES `users` (`users_username`) ON DELETE CASCADE ON UPDATE RESTRICT;

    INSERT INTO etat_rp (etat_name) VALUES
    ('Pas commencé'),
    ('En attente'),
    ('En cours'),
    ('Terminé'),
    ('Abandonné');

    INSERT INTO reseaux (reseau_name) VALUES
    ('Discord'),
    ('Telegram'),
    ('Instagram');

    INSERT INTO type_rp (type_name) VALUES
    ('SFW'),
    ('NSFW - Rape'),
    ('NSFW - Lust'),
    ('NSFW - Love');
";

    $connection->exec($sql);

    $envPath = __DIR__ . '/../.env';
    $env = [];

    if (file_exists($envPath)) {
        try {
            $dotenv = new Dotenv();
            $env = $dotenv->parse(file_get_contents($envPath), $envPath);
        } catch (PathException $exception) {
            throw new Exception('Erreur de chemin du fichier .env: ' . $exception->getMessage());
        }
    }

    $env['DB_HOST'] = $dbhost;
    $env['DB_PORT'] = $dbport;
    $env['DB_NAME'] = $dbname;
    $env['DB_USER'] = $dbuser;
    $env['DB_PASSWORD'] = $dbpassword;
    $env['ALLOW_SIGNUP'] = "true";

    $envData = '';
    foreach ($env as $key => $value) {
        $envData .= sprintf("%s=\"%s\"\n", $key, $value);
    }

    file_put_contents($envPath, $envData);

    touch('../install.lock');

    echo json_encode(['success' => 'La base de données a été configurée avec succès.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données', 'details' => 'Erreur de base de données: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur d\'installation', 'details' => $e->getMessage()]);
}