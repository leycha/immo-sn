<?php
namespace Core;

use PDO;
use PDOException;
use Throwable;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {

            try {

                // Initialisation centrale de IMMO-SN.
                require_once dirname(__DIR__) . '/config/bootstrap.php';

                // Chargement de la configuration PostgreSQL.
                $config = require BASE_PATH . '/config/database.php';

                // Récupération des paramètres.
                $host = $config['host'];
                $port = $config['port'];
                $database = $config['database'];
                $username = $config['username'];
                $password = $config['password'];

                // Vérification des paramètres obligatoires.
                $parametres = [
                    'DB_HOST' => $host,
                    'DB_NAME' => $database,
                    'DB_USER' => $username,
                    'DB_PASSWORD' => $password,
                ];

                $parametresManquants = [];

                foreach ($parametres as $nom => $valeur) {
                    if (trim((string) $valeur) === '') {
                        $parametresManquants[] = $nom;
                    }
                }

                if ($parametresManquants !== []) {
                    throw new PDOException(
                        'Paramètres de connexion manquants : '
                        . implode(', ', $parametresManquants)
                    );
                }

                // Connexion PostgreSQL / Neon avec SSL obligatoire.
                $dsn = "pgsql:"
                    . "host={$host};"
                    . "port={$port};"
                    . "dbname={$database};"
                    . "sslmode=require";

                self::$instance = new PDO(
                    $dsn,
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );

            } catch (Throwable $e) {

                die(
                    "Erreur de connexion à la base de données : "
                    . $e->getMessage()
                );
            }
        }

        return self::$instance;
    }
}
