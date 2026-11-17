<?php

/**
 * Configuration du service de messagerie IMMO-SN.
 *
 * Les informations sensibles restent dans le fichier .env.
 */

return [
    'host' => trim((string) ($_ENV['MAIL_HOST'] ?? '')),
    'port' => (int) ($_ENV['MAIL_PORT'] ?? 587),
    'username' => trim((string) ($_ENV['MAIL_USER'] ?? '')),
    'password' => (string) ($_ENV['MAIL_PASS'] ?? ''),
    'from' => trim((string) ($_ENV['MAIL_FROM'] ?? '')),
    'from_name' => trim(
        (string) ($_ENV['MAIL_FROM_NAME'] ?? 'IMMO-SN')
    ),
];