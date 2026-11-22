<?php
/**
 * Configuration générale de IMMO-SN.
 *
 * Les valeurs sensibles et variables d'environnement
 * sont conservées dans le fichier .env.
 */

return [
    'name' => 'IMMO-SN',

    'env' => $_ENV['APP_ENV'] ?? 'production',

    'url' => rtrim(
        (string) ($_ENV['APP_URL'] ?? 'http://localhost/immo-sn'),
        '/'
    ),

    'debug' => ($_ENV['APP_ENV'] ?? 'production') === 'local',

    'timezone' => 'Africa/Dakar',
];
