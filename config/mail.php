
<?php

/**
 * Configuration du service de messagerie IMMO-SN.
 *
 * Les informations sensibles restent dans le fichier .env
 * ou dans les variables d'environnement de Render.
 */

return [
    'api_key' => trim(
        (string) ($_ENV['BREVO_API_KEY'] ?? '')
    ),

    'from' => trim(
        (string) ($_ENV['MAIL_FROM'] ?? '')
    ),

    'from_name' => trim(
        (string) ($_ENV['MAIL_FROM_NAME'] ?? 'IMMO-SN')
    ),
];
