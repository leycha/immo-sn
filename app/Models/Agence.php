<?php
namespace App\Models;
use Core\Model;
use Core\Database;
use PDO;

class Agence extends Model
{
    protected static string $table = 'agences';

    /**
     * Liste publique des agences actives.
     */
    public static function toutes(): array
    {
        $sql = "
            SELECT
                a.id,
                a.user_id,
                a.nom_agence,
                a.logo,
                a.description,
                a.adresse,
                a.ninea,
                a.badge_verifie,
                a.created_at,
                a.statut,

                a.email AS email,
                a.telephone AS telephone,
                COUNT(b.id) AS nombre_biens

            FROM agences a

            LEFT JOIN users u
                ON u.id = a.user_id

            LEFT JOIN biens b
                ON b.user_id = a.user_id
                AND b.statut = 'actif'
                AND b.expire_at > NOW()

            WHERE a.statut = 'actif'

            GROUP BY
                a.id,
                a.user_id,
                a.nom_agence,
                a.logo,
                a.description,
                a.adresse,
                a.ninea,
                a.badge_verifie,
                a.created_at,
                a.statut,
                a.email,
                a.telephone
            ORDER BY a.nom_agence ASC
        ";

        return Database::getConnection()
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Trouver une agence publique active.
     */
    public static function trouver(string $id): ?array
    {
        $sql = "
            SELECT
                a.id,
                a.user_id,
                a.nom_agence,
                a.logo,
                a.description,
                a.adresse,
                a.ninea,
                a.badge_verifie,
                a.created_at,
                a.statut,

               a.email AS email,
               a.telephone AS telephone,
                COUNT(b.id) AS nombre_biens

            FROM agences a

            LEFT JOIN users u
                ON u.id = a.user_id

            LEFT JOIN biens b
                ON b.user_id = a.user_id
                AND b.statut = 'actif'
                AND b.expire_at > NOW()

            WHERE a.id = :id
              AND a.statut = 'actif'

            GROUP BY
                a.id,
                a.user_id,
                a.nom_agence,
                a.logo,
                a.description,
                a.adresse,
                a.ninea,
                a.badge_verifie,
                a.created_at,
                a.statut,
               a.email,
               a.telephone
            LIMIT 1
        ";

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Liste complète des agences pour l'administration.
     * Tous les statuts sont visibles.
     */
   /**
 * Liste complète des agences pour l'administration.
 * Inclut les informations personnelles du responsable.
 */
public static function toutesPourAdmin(): array
{
    $sql = "
        SELECT
            a.id,
            a.user_id,
            a.nom_agence,
            a.logo,
            a.description,
            a.adresse,
            a.ninea,
            a.badge_verifie,
            a.created_at,
            a.statut,

            a.email AS email,
            a.telephone AS telephone,

            u.nom AS responsable_nom,
            u.prenom AS responsable_prenom,
            u.email AS responsable_email,
            u.telephone AS responsable_telephone,
            u.ville AS responsable_ville,

            COUNT(b.id) AS nombre_biens

        FROM agences a

        LEFT JOIN users u
            ON u.id = a.user_id

        LEFT JOIN biens b
            ON b.user_id = a.user_id
            AND b.statut = 'actif'
            AND b.expire_at > NOW()

        GROUP BY
            a.id,
            u.id

        ORDER BY
            CASE a.statut
                WHEN 'en_attente' THEN 1
                WHEN 'actif' THEN 2
                WHEN 'suspendue' THEN 3
                WHEN 'refusee' THEN 4
                ELSE 5
            END,
            a.created_at DESC
    ";

    return Database::getConnection()
        ->query($sql)
        ->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Trouver une agence quel que soit son statut.
     * Réservé à l'administration.
     */
    public static function trouverPourAdmin(string $id): ?array
    {
        $sql = "
            SELECT
                a.*,
               a.email AS email,
               a.telephone AS telephone,
                COUNT(b.id) AS nombre_biens

            FROM agences a

            LEFT JOIN users u
                ON u.id = a.user_id

            LEFT JOIN biens b
                ON b.user_id = a.user_id
                AND b.statut = 'actif'
                AND b.expire_at > NOW()

            WHERE a.id = :id

            GROUP BY
                a.id,
                a.email,
                a.telephone
            LIMIT 1
        ";

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Récupérer toutes les informations d'une agence
     * et de son responsable pour l'administration.
     */
    public static function trouverDetailsPourAdmin(string $id): ?array
    {
        $sql = "
            SELECT
                a.id,
                a.user_id,
                a.nom_agence,
                a.logo,
                a.description,
                a.adresse,
                a.ninea,
                a.email AS email_agence,
                a.telephone AS telephone_agence,
                a.statut,
                a.badge_verifie,
                a.created_at,

                u.nom AS responsable_nom,
                u.prenom AS responsable_prenom,
                u.email AS responsable_email,
                u.telephone AS responsable_telephone,
                u.ville AS responsable_ville,

                COUNT(b.id) AS nombre_biens

            FROM agences a

            LEFT JOIN users u
                ON u.id = a.user_id

            LEFT JOIN biens b
                ON b.user_id = a.user_id
                AND b.statut = 'actif'
                AND b.expire_at > NOW()

            WHERE a.id = :id

            GROUP BY
                a.id,
                u.id

            LIMIT 1
        ";

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Modifier le statut d'une agence.
     */
    public static function changerStatut(
        string $id,
        string $statut
    ): bool {
        $statutsAutorises = [
            'en_attente',
            'actif',
            'suspendue',
            'refusee'
        ];

        if (!in_array($statut, $statutsAutorises, true)) {
            return false;
        }

        $sql = "
            UPDATE agences
            SET statut = :statut
            WHERE id = :id
        ";

        $stmt = Database::getConnection()->prepare($sql);

        return $stmt->execute([
            'statut' => $statut,
            'id' => $id
        ]);
    }

    /**
     * Modifier le badge de vérification.
     */
public static function changerBadge(
    string $id,
    bool $badge
): bool {
    $sql = "
        UPDATE agences
        SET badge_verifie = CAST(:badge AS BOOLEAN)
        WHERE id = :id
    ";

    $stmt = Database::getConnection()->prepare($sql);

    return $stmt->execute([
        'badge' => $badge ? 'true' : 'false',
        'id' => $id
    ]);
}

    /**
     * Biens actifs appartenant à une agence.
     */
    public static function biens(string $agenceId): array
    {
        $sql = "
            SELECT
                b.*,

                c.libelle AS categorie_nom,
                v.ville AS ville_nom,
                v.quartier AS quartier_nom,

                (
                    SELECT file_path
                    FROM photos
                    WHERE bien_id = b.id
                    ORDER BY is_principal DESC, id
                    LIMIT 1
                ) AS photo

            FROM biens b

            INNER JOIN agences a
                ON a.user_id = b.user_id

            LEFT JOIN categories_biens c
                ON c.id = b.category_id

            LEFT JOIN villes v
                ON v.id = b.ville_id

            WHERE a.id = :agence_id
              AND a.statut = 'actif'
              AND b.statut = 'actif'
              AND b.expire_at > NOW()

            ORDER BY b.created_at DESC
        ";

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'agence_id' => $agenceId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
 * Récupérer les QR Codes des biens actifs d'une agence.
 */
/**
 * Récupérer les QR Codes de tous les biens d'une agence.
 * Inclut les biens actifs, en attente, rejetés et expirés.
 */
public static function qrCodesBiens(string $agenceId): array
{
    $sql = "
        SELECT
            b.id AS bien_id,
            b.titre AS titre,
            b.statut AS statut,
            q.id AS qr_id,
            q.token AS token,
            q.file_path AS file_path,
            COALESCE(q.nb_scans, 0) AS nb_scans,
            q.last_scan_at AS last_scan_at,
            q.created_at AS qr_created_at

        FROM biens b

        INNER JOIN agences a
            ON a.user_id = b.user_id

        INNER JOIN qr_codes q
            ON q.bien_id = b.id

        WHERE a.id = :agence_id

        ORDER BY b.created_at DESC
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'agence_id' => $agenceId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Créer une agence à partir d'une inscription.
     */
     
/**
 * Créer une agence à partir d'une inscription.
 */
public static function creer(array $donnees): string
{
    $sql = "
        INSERT INTO agences (
            user_id,
            nom_agence,
            logo,
            description,
            adresse,
            ninea,
            email,
            telephone,
            statut
        )
        VALUES (
            :user_id,
            :nom_agence,
            :logo,
            :description,
            :adresse,
            :ninea,
            :email,
            :telephone,
            'en_attente'
        )
        RETURNING id
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'user_id' => $donnees['user_id'],
        'nom_agence' => trim($donnees['nom_agence']),
        'logo' => $donnees['logo'] ?? null,
        'description' => $donnees['description'] ?? null,
        'adresse' => trim($donnees['adresse']),
        'ninea' => trim($donnees['ninea']),
        'email' => $donnees['email'] ?? null,
        'telephone' => $donnees['telephone'] ?? null,
    ]);

    return (string) $stmt->fetchColumn();
}

    
/**
 * Supprimer uniquement une agence refusée.
 * Le compte utilisateur associé n'est pas supprimé.
 */
public static function supprimerRefusee(string $id): bool
{
    $sql = "
        DELETE FROM agences
        WHERE id = :id
        AND statut = 'refusee'
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        ':id' => $id,
    ]);

    return $stmt->rowCount() === 1;
}
/**
 * Récupérer l'agence du responsable connecté.
 */
public static function trouverParResponsable(string $userId): ?array
{
    $sql = "
        SELECT
            a.*,
            u.nom AS responsable_nom,
            u.prenom AS responsable_prenom,
            u.email AS responsable_email,
            u.telephone AS responsable_telephone
        FROM agences a
        INNER JOIN users u
            ON u.id = a.user_id
        WHERE a.user_id = :user_id
        LIMIT 1
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'user_id' => $userId
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
/**
 * Modifier les informations de l'agence du responsable connecté.
 * Le responsable ne peut modifier que sa propre agence.
 */
public static function modifierParResponsable(
    string $userId,
    array $donnees
): bool {
    $sql = "
        UPDATE agences
        SET
            nom_agence = :nom_agence,
            description = :description,
            adresse = :adresse,
            ninea = :ninea,
            email = :email,
            telephone = :telephone
        WHERE user_id = :user_id
    ";

    $stmt = Database::getConnection()->prepare($sql);

    return $stmt->execute([
        'nom_agence' => trim($donnees['nom_agence']),
        'description' => trim($donnees['description'] ?? ''),
        'adresse' => trim($donnees['adresse']),
        'ninea' => trim($donnees['ninea']),
        'email' => trim($donnees['email'] ?? ''),
        'telephone' => trim($donnees['telephone'] ?? ''),
        'user_id' => $userId,
    ]);
}
/**
 * Récupérer tous les biens de l'agence du responsable connecté.
 * Inclut tous les statuts : actifs, en attente, rejetés et expirés.
 */
public static function mesBiensParResponsable(string $userId): array
{
    $sql = "
        SELECT
            b.*,
            c.libelle AS categorie_nom,
            v.ville AS ville_nom,
            v.quartier AS quartier_nom,

            (
                SELECT file_path
                FROM photos
                WHERE bien_id = b.id
                ORDER BY is_principal DESC, id
                LIMIT 1
            ) AS photo

        FROM biens b

        INNER JOIN agences a
            ON a.user_id = b.user_id

        LEFT JOIN categories_biens c
            ON c.id = b.category_id

        LEFT JOIN villes v
            ON v.id = b.ville_id

        WHERE a.user_id = :user_id

        ORDER BY b.created_at DESC
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'user_id' => $userId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
