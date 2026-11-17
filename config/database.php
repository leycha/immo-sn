
<?php

/**
 * Configuration de la base de données IMMO-SN.
 *
 * Les identifiants restent dans le fichier .env.
 */

return [
    'host' => (string) ($_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: ''),

    'port' => (string) ($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '5432'),

    'database' => (string) ($_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: ''),

    'username' => (string) ($_ENV['DB_USER'] ?? getenv('DB_USER') ?: ''),

    'password' => (string) ($_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: ''),
];
