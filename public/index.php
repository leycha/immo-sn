
<?php

// Affichage des erreurs en environnement de développement.
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Initialisation centrale de IMMO-SN.
require_once dirname(__DIR__) . '/config/bootstrap.php';

// Chargement des routes.
require_once BASE_PATH . '/config/routes.php';

use Core\Router;

// Création du routeur.
$router = new Router();

// Enregistrement des routes.
registerRoutes($router);

// Exécution de la route demandée.
$router->dispatch(
    $_SERVER['REQUEST_URI'],
    $_SERVER['REQUEST_METHOD']
);
