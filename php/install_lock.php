<?php
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

$lockFile = '../install.lock';

if (!file_exists($lockFile)) {
    if (file_put_contents($lockFile, 'Installation terminée.') !== false) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => true, 'details' => 'Erreur de création du fichier lock.']);
    }
} else {
    echo json_encode(['success' => true, 'details' => 'Le fichier lock existe déjà.']);
}
