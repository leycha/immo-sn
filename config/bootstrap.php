
<?php
/**
 * IMMO-SN — Initialisation centrale de l'application.
 *
 * Responsabilités :
 * - Définir le chemin racine du projet.
 * - Charger l'autoloader Composer.
 * - Charger les variables d'environnement (.env).
 */

// Racine du projet.
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Charger Composer.
$autoload = BASE_PATH . '/vendor/autoload.php';

if (!is_file($autoload)) {
    throw new RuntimeException(
        'Le fichier vendor/autoload.php est introuvable.'
    );
}

require_once $autoload;

// Vérifier la disponibilité de phpdotenv.
if (!class_exists(\Dotenv\Dotenv::class)) {
    throw new RuntimeException(
        'La bibliothèque phpdotenv est introuvable.'
    );
}

// Charger les variables d'environnement une seule fois.
if (!defined('ENV_LOADED')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();

    define('ENV_LOADED', true);
}
