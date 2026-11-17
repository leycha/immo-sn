<?php
declare(strict_types=1);
namespace App\Models;
use Core\Database;
use InvalidArgumentException;
use JsonException;

class AuditLog
{
    /**
     * Enregistrer une décision ou une action de modération.
     */
    public static function enregistrer(
        string $userId,
        string $action,
        array $details = []
    ): bool {
        if ($userId === '') {
            throw new InvalidArgumentException(
                'L’identifiant de l’utilisateur est obligatoire.'
            );
        }

        if ($action === '') {
            throw new InvalidArgumentException(
                'Le type de décision est obligatoire.'
            );
        }

        try {
            $detailsJson = json_encode(
                $details,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new InvalidArgumentException(
                'Impossible de convertir les détails en JSON.',
                0,
                $e
            );
        }

        $sql = '
            INSERT INTO audit_logs (
                user_id,
                action,
                details,
                ip_address
            )
            VALUES (
                :user_id,
                :action,
                :details,
                :ip_address
            )
        ';

        $stmt = Database::getConnection()->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'details' => $detailsJson,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }

    /**
     * Construire les conditions de filtrage de l'historique.
     */
    private static function construireFiltres(
        array $filtres
    ): array {
        $conditions = [];
        $parametres = [];

        if (!empty($filtres['action'])) {
            $conditions[] = 'a.action = :action';
            $parametres['action'] = $filtres['action'];
        }

        if (!empty($filtres['recherche'])) {
            $conditions[] = "(
                u.nom ILIKE :recherche
                OR u.prenom ILIKE :recherche
                OR u.email ILIKE :recherche
                OR a.action ILIKE :recherche
                OR a.details ILIKE :recherche
                OR a.ip_address ILIKE :recherche
            )";

            $parametres['recherche'] =
                '%' . trim($filtres['recherche']) . '%';
        }

        if (!empty($filtres['date_debut'])) {
            $conditions[] = 'a.created_at >= :date_debut';
            $parametres['date_debut'] =
                $filtres['date_debut'] . ' 00:00:00';
        }

        if (!empty($filtres['date_fin'])) {
            $conditions[] = 'a.created_at < :date_fin';
            $parametres['date_fin'] =
                date(
                    'Y-m-d',
                    strtotime($filtres['date_fin'] . ' +1 day')
                ) . ' 00:00:00';
        }

        $where = $conditions
            ? ' WHERE ' . implode(' AND ', $conditions)
            : '';

        return [$where, $parametres];
    }

    /**
     * Compter le nombre de décisions correspondant aux filtres.
     */
    public static function compter(array $filtres = []): int
    {
        [$where, $parametres] =
            self::construireFiltres($filtres);

        $sql = '
            SELECT COUNT(*)
            FROM audit_logs a
            INNER JOIN users u ON u.id = a.user_id
        ' . $where;

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($parametres);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Récupérer les décisions avec les informations du modérateur.
     */
    public static function lister(
        array $filtres = [],
        int $parPage = 10,
        int $offset = 0
    ): array {
        $parPage = max(1, min(100, $parPage));
        $offset = max(0, $offset);

        [$where, $parametres] =
            self::construireFiltres($filtres);

        $sql = '
            SELECT
                a.id,
                a.user_id,
                a.action,
                a.details,
                a.ip_address,
                a.created_at,
                u.nom AS moderateur_nom,
                u.prenom AS moderateur_prenom,
                u.email AS moderateur_email,
                u.role AS moderateur_role
            FROM audit_logs a
            INNER JOIN users u ON u.id = a.user_id
        ' . $where . '
            ORDER BY a.created_at DESC NULLS LAST
            LIMIT :limite
            OFFSET :decalage
        ';

        $stmt = Database::getConnection()->prepare($sql);

        foreach ($parametres as $cle => $valeur) {
            $stmt->bindValue(':' . $cle, $valeur);
        }

        $stmt->bindValue(
            ':limite',
            $parPage,
            \PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':decalage',
            $offset,
            \PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
