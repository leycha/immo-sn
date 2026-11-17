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
                require_once dirname(__DIR__) . '/config/bootstrap.php';

                $config = require BASE_PATH . '/config/database.php';

                $host = trim((string) ($config['host'] ?? ''));
                $port = trim((string) ($config['port'] ?? '5432'));
                $database = trim((string) ($config['database'] ?? ''));
                $username = trim((string) ($config['username'] ?? ''));
                $password = (string) ($config['password'] ?? '');

                $parametres = [
                    'DB_HOST' => $host,
                    'DB_NAME' => $database,
                    'DB_USER' => $username,
                    'DB_PASSWORD' => $password,
                ];

                $manquants = [];

                foreach ($parametres as $nom => $valeur) {
                    if ($valeur === '') {
                        $manquants[] = $nom;
                    }
                }

                if ($manquants !== []) {
                    throw new PDOException(
                        'Paramètres manquants : ' .
                        implode(', ', $manquants)
                    );
                }

                // Récupérer automatiquement l'identifiant Neon.
                $endpoint = null;

                if (preg_match(
                    '/^(ep-[^.]+?)(?:-pooler)?\./',
                    $host,
                    $matches
                )) {
                    $endpoint = $matches[1];
                }

                if ($endpoint === null) {
                    throw new PDOException(
                        'Impossible de récupérer l’identifiant endpoint Neon depuis DB_HOST.'
                    );
                }

                // Transmettre l'endpoint à Neon via libpq.
                $dsn = "pgsql:"
                    . "host={$host};"
                    . "port={$port};"
                    . "dbname={$database} options=endpoint={$endpoint};"
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
                error_log(
                    'IMMO-SN Database error: ' . $e->getMessage()
                );

                http_response_code(500);

                exit(
                    'Erreur de connexion à la base de données : '
                    . htmlspecialchars(
                        $e->getMessage(),
                        ENT_QUOTES,
                        'UTF-8'
                    )
                );
            }
        }

        return self::$instance;
    }
}
