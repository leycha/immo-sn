<?php

/**
 * Configuration du service e-mail IMMO-SN.
 * Compatible avec les variables d'environnement de Render
 * et le fichier .env en développement local.
 */

$readEnv = static function (string $name, string $default = ''): string {
    $value = $_ENV[$name]
        ?? $_SERVER[$name]
        ?? getenv($name);

    if ($value === false || $value === null) {
        return $default;
    }

    return trim((string) $value);
};

return [
    'api_key' => $readEnv('BREVO_API_KEY'),
    'from' => $readEnv('MAIL_FROM'),
    'from_name' => $readEnv('MAIL_FROM_NAME', 'IMMO-SN'),
];
