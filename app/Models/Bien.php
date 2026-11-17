<?php

namespace App\Models;

use Core\Model;
use Core\Database;
use PDO;

class Bien extends Model
{
    protected static string $table = 'biens';

    /**
     * =========================================================
     * ANNONCES ACTIVES
     * =========================================================
     */

    /**
     * Récupérer les annonces actives pour l'accueil.
     */
    public static function actifs(int $limit = 6): array
    {
        $limit = max(1, min($limit, 50));

        $sql = '
            SELECT
                b.*,

                b.titre AS titre,
                b.description AS description,
                b.prix AS prix,
                b.superficie AS surface,
                b.pieces AS pieces,
                b.chambres AS chambres,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,
                v.region AS region_nom,

                (
                    SELECT p.file_path
                    FROM photos p
                    WHERE p.bien_id = b.id
                    ORDER BY p.is_principal DESC, p.created_at DESC
                    LIMIT 1
                ) AS photo

            FROM biens b

            LEFT JOIN categories_biens c
                ON c.id = b.category_id

            LEFT JOIN villes v
                ON v.id = b.ville_id

            WHERE b.statut = \'actif\'
              AND (
                    b.expire_at IS NULL
                    OR b.expire_at > NOW()
              )

            ORDER BY b.created_at DESC

            LIMIT :limit
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =========================================================
     * DETAIL D'UN BIEN
     * =========================================================
     */

    /**
     * Récupérer le détail d'une annonce.
     */
    public static function detail(string $id): ?array
    {
        $sql = '
            SELECT
                b.*,

                b.titre AS titre,
                b.description AS description,
                b.prix AS prix,
                b.superficie AS surface,
                b.pieces AS pieces,
                b.chambres AS chambres,

                b.latitude AS latitude,
                b.longitude AS longitude,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,
                v.region AS region_nom,

                u.nom AS proprio_nom,
                u.prenom AS proprio_prenom,
                u.email AS proprio_email,
                u.telephone AS proprio_tel

            FROM biens b

            LEFT JOIN categories_biens c
                ON c.id = b.category_id

            LEFT JOIN villes v
                ON v.id = b.ville_id

            LEFT JOIN users u
                ON u.id = b.user_id

            WHERE b.id = :id

            LIMIT 1
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $bien = $stmt->fetch(PDO::FETCH_ASSOC);

        return $bien ?: null;
    }

    /**
     * =========================================================
     * BIENS D'UN UTILISATEUR
     * =========================================================
     */

    /**
     * Récupérer les biens appartenant à un utilisateur.
     *
     * Cette méthode est utilisée dans l'espace membre.
     */
    public static function parUtilisateur(
        string $userId,
        ?int $limit = null
    ): array {
        $sql = '
            SELECT
                b.*,

                b.titre AS titre,
                b.description AS description,
                b.prix AS prix,
                b.superficie AS surface,
                b.pieces AS pieces,
                b.chambres AS chambres,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,
                v.region AS region_nom,

                (
                    SELECT p.file_path
                    FROM photos p
                    WHERE p.bien_id = b.id
                    ORDER BY p.is_principal DESC, p.created_at DESC
                    LIMIT 1
                ) AS photo,

                (
                    SELECT COUNT(*)
                    FROM qr_codes q
                    WHERE q.bien_id = b.id
                ) AS qr_code

            FROM biens b

            LEFT JOIN categories_biens c
                ON c.id = b.category_id

            LEFT JOIN villes v
                ON v.id = b.ville_id

            WHERE b.user_id = :user_id

            ORDER BY b.created_at DESC
        ';

        if ($limit !== null) {
            $limit = max(1, min($limit, 100));
            $sql .= ' LIMIT :limit';
        }

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_STR
        );

        if ($limit !== null) {
            $stmt->bindValue(
                ':limit',
                $limit,
                PDO::PARAM_INT
            );
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =========================================================
     * NOMBRE DE BIENS D'UN UTILISATEUR
     * =========================================================
     */

    /**
     * Compter les biens appartenant à un utilisateur.
     */
    public static function countByUser(string $userId): int
    {
        $sql = '
            SELECT COUNT(*)
            FROM biens
            WHERE user_id = :user_id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
       
     * =========================================================
     * TOTAL DES VUES
     * =========================================================
     */

    /**
     * Calculer le nombre total de vues des biens
     * appartenant à un utilisateur.
     */
    public static function totalVuesByUser(string $userId): int
    {
        $sql = '
            SELECT COALESCE(SUM(nb_vues), 0)
            FROM biens
            WHERE user_id = :user_id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return (int) $stmt->fetchColumn();
    }


    /**
     * =========================================================
     * VUES DES 30 DERNIERS JOURS
     * =========================================================
     */

    /**
     * Récupérer le nombre de vues des biens d'un utilisateur
     * pour chacun des 30 derniers jours.
     *
     * Même les jours sans aucune vue sont retournés avec 0.
     */
    public static function vuesDerniers30Jours(string $userId): array
    {
        $sql = '
            SELECT
                jours.jour::date AS jour,
                COALESCE(vues.total, 0) AS total

            FROM generate_series(
                CURRENT_DATE - INTERVAL \'29 days\',
                CURRENT_DATE,
                INTERVAL \'1 day\'
            ) AS jours(jour)

            LEFT JOIN (
                SELECT
                    v.created_at::date AS jour,
                    COUNT(*) AS total

                FROM vues_biens v

                INNER JOIN biens b
                    ON b.id = v.bien_id

                WHERE b.user_id = :user_id

                  AND v.created_at >= CURRENT_DATE - INTERVAL \'29 days\'

                  AND v.created_at < CURRENT_DATE + INTERVAL \'1 day\'

                GROUP BY v.created_at::date
            ) AS vues
                ON vues.jour = jours.jour::date

            ORDER BY jours.jour ASC
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * =========================================================
     * DERNIERS BIENS D'UN UTILISATEUR
     * =========================================================
     */

    /**
     * Récupérer les dernières annonces du membre.
     *
     * Utilisé notamment dans le Dashboard.
     */
    public static function derniersParUtilisateur(
        string $userId,
        int $limit = 5
    ): array {
        $limit = max(1, min($limit, 20));

        $sql = '
            SELECT
                b.id,
                b.user_id,
                b.titre,
                b.description,
                b.prix,
                b.superficie AS surface,
                b.pieces,
                b.chambres,
                b.statut,
                b.nb_vues,
                b.created_at,
                b.updated_at,
                b.expire_at,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,

                (
                    SELECT p.file_path
                    FROM photos p
                    WHERE p.bien_id = b.id
                    ORDER BY p.is_principal DESC, p.created_at DESC
                    LIMIT 1
                ) AS photo

            FROM biens b

            LEFT JOIN categories_biens c
                ON c.id = b.category_id

            LEFT JOIN villes v
                ON v.id = b.ville_id

            WHERE b.user_id = :user_id

            ORDER BY b.created_at DESC

            LIMIT :limit
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =========================================================
     * FAVORIS D'UN UTILISATEUR
     * =========================================================
     */

    /**
     * Récupérer les biens favoris d'un utilisateur.
     */
    public static function favorisParUtilisateur(
        string $userId,
        ?int $limit = null
    ): array {
        $sql = '
            SELECT
                b.*,

                b.titre AS titre,
                b.description AS description,
                b.prix AS prix,
                b.superficie AS surface,
                b.pieces AS pieces,
                b.chambres AS chambres,

                f.created_at AS favori_created_at,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,
                v.region AS region_nom,

                (
                    SELECT p.file_path
                    FROM photos p
                    WHERE p.bien_id = b.id
                    ORDER BY p.is_principal DESC, p.created_at DESC
                    LIMIT 1
                ) AS photo

            FROM favoris f

            INNER JOIN biens b
                ON b.id = f.bien_id

            LEFT JOIN categories_biens c
                ON c.id = b.category_id

            LEFT JOIN villes v
                ON v.id = b.ville_id

            WHERE f.user_id = :user_id

            ORDER BY f.created_at DESC
        ';

        if ($limit !== null) {
            $limit = max(1, min($limit, 100));
            $sql .= ' LIMIT :limit';
        }

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->bindValue(
            ':user_id',
            $userId,
            PDO::PARAM_STR
        );

        if ($limit !== null) {
            $stmt->bindValue(
                ':limit',
                $limit,
                PDO::PARAM_INT
            );
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =========================================================
     * NOMBRE DE FAVORIS D'UN UTILISATEUR
     * =========================================================
     */

    /**
     * Compter les favoris d'un utilisateur.
     */
    public static function countFavorisByUser(string $userId): int
    {
        $sql = '
            SELECT COUNT(*)
            FROM favoris
            WHERE user_id = :user_id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * =========================================================
     * RECHERCHE
     * =========================================================
     */

    /**
     * Recherche des annonces avec filtres et pagination.
     */
    public static function search(
        array $filtres = [],
        int $page = 1,
        int $parPage = 12,
        string $tri = 'recent'
    ): array {
        $page = max(1, $page);
        $parPage = max(1, min($parPage, 100));

        $where = [
            "b.statut = 'actif'",
            "(
                b.expire_at IS NULL
                OR b.expire_at > NOW()
            )"
        ];

        $params = [];
        /**
 * Filtre par agence
 */
if (!empty($filtres['agence'])) {
    $where[] = 'b.user_id IN (
        SELECT a.user_id
        FROM agences a
        WHERE a.id = :agence
          AND a.statut = \'actif\'
    )';

    $params['agence'] = $filtres['agence'];
}

        /**
         * Filtre type d'offre
         */
        if (!empty($filtres['type_offre'])) {
            $where[] = 'b.type_offre = :type_offre';

            $params['type_offre'] = $filtres['type_offre'];
        }

        /**
         * Filtre catégorie
         */
        if (!empty($filtres['category_id'])) {
            $where[] = 'b.category_id = :category_id';

            $params['category_id'] = (int) $filtres['category_id'];
        }

        /**
         * Filtre ville
         */
        if (!empty($filtres['ville_id'])) {
            $where[] = 'b.ville_id = :ville_id';

            $params['ville_id'] = (int) $filtres['ville_id'];
        }

        /**
         * Prix minimum
         */
        if (
            isset($filtres['prix_min'])
            && $filtres['prix_min'] !== ''
        ) {
            $where[] = 'b.prix >= :prix_min';

            $params['prix_min'] = (float) $filtres['prix_min'];
        }

        /**
         * Prix maximum
         */
        if (
            isset($filtres['prix_max'])
            && $filtres['prix_max'] !== ''
        ) {
            $where[] = 'b.prix <= :prix_max';

            $params['prix_max'] = (float) $filtres['prix_max'];
        }

        /**
         * Nombre de chambres minimum
         */
        if (
            isset($filtres['chambres'])
            && $filtres['chambres'] !== ''
        ) {
            $where[] = 'b.chambres >= :chambres';

            $params['chambres'] = (int) $filtres['chambres'];
        }

        /**
         * Recherche texte
         */
        if (!empty($filtres['q'])) {
            $where[] = '(
                b.titre ILIKE :q
                OR b.description ILIKE :q
            )';

            $params['q'] = '%' . trim($filtres['q']) . '%';
        }

        $whereSql = implode(' AND ', $where);

        /**
         * Tri autorisé
         */
        $ordres = [
            'recent' => 'b.created_at DESC',
            'ancien' => 'b.created_at ASC',
            'prix_asc' => 'b.prix ASC',
            'prix_desc' => 'b.prix DESC',
            'surface_desc' => 'b.superficie DESC'
        ];

        $orderBy = $ordres[$tri] ?? $ordres['recent'];

        /**
         * Pagination
         */
        $offset = ($page - 1) * $parPage;

        /**
         * Requête principale
         */
        $sql = "
            SELECT
                b.*,

                b.titre AS titre,
                b.description AS description,
                b.prix AS prix,
                b.superficie AS surface,
                b.pieces AS pieces,
                b.chambres AS chambres,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,
                v.region AS region_nom,

                (
                    SELECT p.file_path
                    FROM photos p
                    WHERE p.bien_id = b.id
                    ORDER BY p.is_principal DESC, p.created_at DESC
                    LIMIT 1
                ) AS photo

            FROM biens b

            LEFT JOIN categories_biens c
                ON c.id = b.category_id

            LEFT JOIN villes v
                ON v.id = b.ville_id

            WHERE $whereSql

            ORDER BY $orderBy

            LIMIT :limit
            OFFSET :offset
        ";

        $db = Database::getConnection();

        $stmt = $db->prepare($sql);

        foreach ($params as $key => $value) {

            if (is_int($value)) {
                $type = PDO::PARAM_INT;
            } else {
                $type = PDO::PARAM_STR;
            }

            $stmt->bindValue(
                ':' . $key,
                $value,
                $type
            );
        }

        $stmt->bindValue(
            ':limit',
            $parPage,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $biens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /**
         * Nombre total d'annonces.
         */
        $sqlCount = "
            SELECT COUNT(*)
            FROM biens b
            WHERE $whereSql
        ";

        $stmtCount = $db->prepare($sqlCount);

        foreach ($params as $key => $value) {

            if (is_int($value)) {
                $type = PDO::PARAM_INT;
            } else {
                $type = PDO::PARAM_STR;
            }

            $stmtCount->bindValue(
                ':' . $key,
                $value,
                $type
            );
        }

        $stmtCount->execute();

        $total = (int) $stmtCount->fetchColumn();

        return [
            'biens' => $biens,
            'total' => $total,
            'pages' => (int) ceil($total / $parPage),
            'page' => $page
        ];
    }

    /**
     * =========================================================
     * CATEGORIES
     * =========================================================
     */

    /**
     * Liste des catégories.
     */
    public static function categories(): array
    {
        $sql = '
            SELECT
                id,
                libelle,
                slug
            FROM categories_biens
            ORDER BY libelle ASC
        ';

        return Database::getConnection()
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =========================================================
     * VILLES
     * =========================================================
     */

    /**
     * Liste des villes et quartiers.
     */
    public static function villes(): array
    {
        $sql = '
            SELECT
                id,
                region,
                ville,
                quartier
            FROM villes
            ORDER BY ville ASC, quartier ASC
        ';

        return Database::getConnection()
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =========================================================
     * CARTE
     * =========================================================
     */

    public static function carte(int $limit = 50): array
    {
        $limit = max(1, min($limit, 200));

        $sql = '
            SELECT
                b.id,
                b.titre,
                b.description,
                b.prix,
                b.type_offre,
                b.superficie,
                b.pieces,
                b.chambres,
                b.latitude,
                b.longitude,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,

                (
                    SELECT p.file_path
                    FROM photos p
                    WHERE p.bien_id = b.id
                    ORDER BY p.is_principal DESC, p.created_at DESC
                    LIMIT 1
                ) AS photo

            FROM biens b

            LEFT JOIN categories_biens c
                ON c.id = b.category_id

            LEFT JOIN villes v
                ON v.id = b.ville_id

            WHERE b.statut = \'actif\'
              AND b.latitude IS NOT NULL
              AND b.longitude IS NOT NULL
              AND b.latitude BETWEEN -90 AND 90
              AND b.longitude BETWEEN -180 AND 180
              AND (
                  b.expire_at IS NULL
                  OR b.expire_at > NOW()
              )

            ORDER BY b.created_at DESC

            LIMIT :limit
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =========================================================
     * PUBLICATION
     * =========================================================
     */

    /**
     * Publier un nouveau bien.
     *
     * Utilise une requête SQL explicite afin de respecter
     * exactement les noms des colonnes PostgreSQL.
     */
   

public static function publier(array $data): string
{
    $sql = '
        INSERT INTO biens (
            user_id,
            category_id,
            ville_id,
            titre,
            description,
            type_offre,
            prix,
            superficie,
            pieces,
            chambres,
            salles_de_bain,
            latitude,
            longitude,
            statut,
            nb_vues,
            expire_at,
            created_at,
            updated_at
        )
        VALUES (
            :user_id,
            :category_id,
            :ville_id,
            :titre,
            :description,
            :type_offre,
            :prix,
            :surface,
            :pieces,
            :chambres,
            :salles_de_bain,
            :latitude,
            :longitude,
            :statut,
            :nb_vues,
            :expire_at,
            :created_at,
            :updated_at
        )
        RETURNING id
    ';

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'user_id' => $data['user_id'],
        'category_id' => $data['category_id'],
        'ville_id' => $data['ville_id'],
        'titre' => $data['Titre'],
        'description' => $data['Description'],
        'type_offre' => $data['type_offre'],
        'prix' => $data['Prix'],
        'surface' => $data['Surface'],
        'pieces' => $data['Pièces'],
        'chambres' => $data['Chambres'],
        'salles_de_bain' => $data['salles_de_bain'],
        'latitude' => $data['Latitude'],
        'longitude' => $data['Longitude'],
        'statut' => $data['Statut'],
        'nb_vues' => $data['nb_vues'],
        'expire_at' => $data['expire_at'],
        'created_at' => $data['created_at'],
        'updated_at' => $data['updated_at'],
    ]);

    return (string) $stmt->fetchColumn();
}

    /**
     * =========================================================
     * PHOTOS
     * =========================================================
     */

    public static function photos(string $bienId): array
    {
        $sql = '
            SELECT
                id,
                bien_id,
                file_path,
                is_principal,
                created_at
            FROM photos
            WHERE bien_id = :bien_id
            ORDER BY
                is_principal DESC,
                created_at ASC
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'bien_id' => $bienId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =========================================================
     * QR CODE
     * =========================================================
     */

    public static function qrCode(string $bienId): ?array
    {
        $sql = '
            SELECT
                id,
                bien_id,
                file_path,
                created_at
            FROM qr_codes
            WHERE bien_id = :bien_id
            ORDER BY created_at DESC
            LIMIT 1
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'bien_id' => $bienId
        ]);

        $qrCode = $stmt->fetch(PDO::FETCH_ASSOC);

        return $qrCode ?: null;
    }



public static function modifierBienAgence(
    string $bienId,
    string $userId,
    array $data
): bool {
    $sql = "
        UPDATE public.biens
        SET
            category_id = :category_id,
            ville_id = :ville_id,
            titre = :titre,
            description = :description,
            type_offre = :type_offre,
            prix = :prix,
            superficie = :superficie,
            pieces = :pieces,
            chambres = :chambres,
            salles_de_bain = :salles_de_bain,
            latitude = :latitude,
            longitude = :longitude,
            video_url = :video_url,
            statut = 'en_attente',
            motif_rejet = NULL,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = CAST(:bien_id AS UUID)
          AND user_id = CAST(:user_id AS UUID)
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'category_id' => $data['category_id'],
        'ville_id' => $data['ville_id'],
        'titre' => $data['titre'],
        'description' => $data['description'],
        'type_offre' => $data['type_offre'],
        'prix' => $data['prix'],
        'superficie' => $data['surface'],
        'pieces' => $data['pieces'],
        'chambres' => $data['chambres'],
        'salles_de_bain' => $data['salles_de_bain'],
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'video_url' => $data['video_url'],
        'bien_id' => $bienId,
        'user_id' => $userId,
    ]);

    return $stmt->rowCount() === 1;
}


    
/**
 * =========================================================
 * MODIFIER UN BIEN REJETÉ
 * =========================================================
 *
 * Seul le propriétaire peut corriger son annonce rejetée.
 * Après modification, elle repasse en attente de validation.
 */
public static function modifierBienRejete(
    string $bienId,
    string $userId,
    array $data
): bool {
    $sql = "
       UPDATE biens 
        SET
            category_id = :category_id,
            ville_id = :ville_id,
            titre = :titre,
            description = :description,
            type_offre = :type_offre,
            prix = :prix,
            superficie = :superficie,
            pieces = :pieces,
            chambres = :chambres,
            salles_de_bain = :salles_de_bain,
            latitude = :latitude,
            longitude = :longitude,
            statut = 'en_attente',
            motif_rejet = NULL,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = CAST(:bien_id AS UUID)
          AND user_id = CAST(:user_id AS UUID)
          AND LOWER(statut) IN ('rejete', 'rejeté', 'refuse', 'refusé')
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'category_id' => $data['category_id'],
        'ville_id' => $data['ville_id'],
        'titre' => $data['titre'],
        'description' => $data['description'],
        'type_offre' => $data['type_offre'],
        'prix' => $data['prix'],
        'superficie' => $data['superficie'],
        'pieces' => $data['pieces'],
        'chambres' => $data['chambres'],
        'salles_de_bain' => $data['salles_de_bain'],
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'bien_id' => $bienId,
        'user_id' => $userId,
    ]);

    return $stmt->rowCount() === 1;
}

/**
 * =========================================================
 * MODIFIER UN BIEN PAR SON PROPRIÉTAIRE
 * =========================================================
 *
 * Une annonce active repasse en attente de modération.
 * Une annonce en attente reste en attente.
 * Une annonce rejetée repasse en attente.
 * Une annonce expirée reste expirée.
 */
public static function modifierBienProprietaire(
    string $bienId,
    string $userId,
    array $data
): bool {
    $sql = "
        UPDATE biens
        SET
            category_id = :category_id,
            ville_id = :ville_id,
            titre = :titre,
            description = :description,
            type_offre = :type_offre,
            prix = :prix,
            superficie = :superficie,
            pieces = :pieces,
            chambres = :chambres,
            salles_de_bain = :salles_de_bain,
           latitude = :latitude,
longitude = :longitude,

statut = CASE
                WHEN LOWER(statut) IN (
                    'actif',
                    'rejete',
                    'refuse',
                    'en_attente',
                    'pending'
                ) THEN 'en_attente'
                ELSE statut
            END,

            motif_rejet = CASE
                WHEN LOWER(statut) IN (
                    'rejete',
                    'refuse'
                ) THEN NULL
                ELSE motif_rejet
            END,

            updated_at = CURRENT_TIMESTAMP

        WHERE id = CAST(:bien_id AS UUID)
          AND user_id = CAST(:user_id AS UUID)
          AND LOWER(statut) IN (
              'actif',
              'en_attente',
              'pending',
              'rejete',
              'refuse',
              'expire'
          )
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'category_id' => $data['category_id'],
        'ville_id' => $data['ville_id'],
        'titre' => $data['titre'],
        'description' => $data['description'],
        'type_offre' => $data['type_offre'],
        'prix' => $data['prix'],
        'superficie' => $data['superficie'],
        'pieces' => $data['pieces'],
        'chambres' => $data['chambres'],
        'salles_de_bain' => $data['salles_de_bain'],
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'bien_id' => $bienId,
        'user_id' => $userId,
    ]);

    return $stmt->rowCount() === 1;
}

/**
 * =========================================================
 * VÉRIFIER LE PROPRIÉTAIRE D'UN BIEN
 * =========================================================
 */
public static function appartientA(
    string $bienId,
    string $userId
): bool {
    $sql = "
        SELECT 1
        FROM biens
        WHERE id = CAST(:bien_id AS UUID)
          AND user_id = CAST(:user_id AS UUID)
        LIMIT 1
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'bien_id' => $bienId,
        'user_id' => $userId,
    ]);

    return $stmt->fetchColumn() !== false;
}


/**
 * Gérer les photos d'un bien appartenant à une agence.
 *
 * - Ajouter les nouvelles photos.
 * - Supprimer les photos sélectionnées.
 * - Définir une photo principale.
 * - Garantir une seule photo principale.
 */
public static function modifierPhotosAgence(
    string $bienId,
    string $userId,
    array $photosAjoutees,
    array $photosASupprimer,
    ?string $photoPrincipale
): bool {
    $db = Database::getConnection();

    $fichiersASupprimer = [];

    try {
        $db->beginTransaction();

        // Vérifier que le bien appartient toujours à l'agence.
        $stmt = $db->prepare("
            SELECT id
            FROM public.biens
            WHERE id = CAST(:bien_id AS UUID)
              AND user_id = CAST(:user_id AS UUID)
            FOR UPDATE
        ");

        $stmt->execute([
            'bien_id' => $bienId,
            'user_id' => $userId,
        ]);

        if (!$stmt->fetchColumn()) {
            throw new \RuntimeException(
                'Bien introuvable ou accès non autorisé.'
            );
        }

        // Récupérer les photos actuelles.
        $stmt = $db->prepare("
            SELECT id, file_path, is_principal
            FROM public.photos
            WHERE bien_id = CAST(:bien_id AS UUID)
            FOR UPDATE
        ");

        $stmt->execute(['bien_id' => $bienId]);

        $photosExistantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $photosParId = [];

        foreach ($photosExistantes as $photo) {
            $photosParId[(string) $photo['id']] = $photo;
        }

        // Normaliser les identifiants à supprimer.
        $idsASupprimer = [];

        foreach ($photosASupprimer as $photoId) {
            $photoId = trim((string) $photoId);

            if (isset($photosParId[$photoId])) {
                $idsASupprimer[$photoId] = true;
                $fichiersASupprimer[] =
                    $photosParId[$photoId]['file_path'];
            }
        }

        // Supprimer uniquement les photos appartenant à ce bien.
        if (!empty($idsASupprimer)) {
            $placeholders = [];
            $params = ['bien_id' => $bienId];

            foreach (array_keys($idsASupprimer) as $index => $photoId) {
                $key = 'photo_' . $index;
                $placeholders[] = ':' . $key;
                $params[$key] = $photoId;
            }

            $sql = "
                DELETE FROM public.photos
                WHERE bien_id = CAST(:bien_id AS UUID)
                  AND id IN (" . implode(', ', $placeholders) . ")
            ";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
        }

        // Insérer les nouvelles photos.
        foreach ($photosAjoutees as $photo) {
            if (empty($photo['file_path'])) {
                continue;
            }

            $stmt = $db->prepare("
                INSERT INTO public.photos (
                    id,
                    bien_id,
                    file_path,
                    is_principal,
                    created_at
                )
                VALUES (
                    gen_random_uuid(),
                    CAST(:bien_id AS UUID),
                    :file_path,
                    FALSE,
                    NOW()
                )
            ");

            $stmt->execute([
                'bien_id' => $bienId,
                'file_path' => $photo['file_path'],
            ]);
        }

        // Toutes les photos restantes.
        $stmt = $db->prepare("
            SELECT id
            FROM public.photos
            WHERE bien_id = CAST(:bien_id AS UUID)
            ORDER BY created_at ASC
        ");

        $stmt->execute(['bien_id' => $bienId]);

        $photosRestantes = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Déterminer la photo principale.
        $photoChoisie = null;

        if (
            $photoPrincipale !== null &&
            in_array($photoPrincipale, $photosRestantes, true)
        ) {
            $photoChoisie = $photoPrincipale;
        }

        // Si aucune sélection valide, conserver l'ancienne principale.
        if ($photoChoisie === null) {
            foreach ($photosExistantes as $photo) {
                $ancienId = (string) $photo['id'];

                if (
                    !empty($photo['is_principal']) &&
                    !isset($idsASupprimer[$ancienId]) &&
                    in_array($ancienId, $photosRestantes, true)
                ) {
                    $photoChoisie = $ancienId;
                    break;
                }
            }
        }

        // Si nécessaire, choisir la première photo restante.
        if ($photoChoisie === null && !empty($photosRestantes)) {
            $photoChoisie = (string) $photosRestantes[0];
        }

        // Réinitialiser puis définir la photo principale.
        $stmt = $db->prepare("
            UPDATE public.photos
            SET is_principal = FALSE
            WHERE bien_id = CAST(:bien_id AS UUID)
        ");

        $stmt->execute(['bien_id' => $bienId]);

        if ($photoChoisie !== null) {
            $stmt = $db->prepare("
                UPDATE public.photos
                SET is_principal = TRUE
                WHERE id = CAST(:photo_id AS UUID)
                  AND bien_id = CAST(:bien_id AS UUID)
            ");

            $stmt->execute([
                'photo_id' => $photoChoisie,
                'bien_id' => $bienId,
            ]);
        }

        $db->commit();

        // Supprimer les fichiers physiques après validation SQL.
        $dossierPhotos = realpath(BASE_PATH . '/stockage/photos');

        if ($dossierPhotos !== false) {
            foreach ($fichiersASupprimer as $cheminWeb) {
                $nomFichier = basename((string) $cheminWeb);
                $cheminPhysique = realpath(
                    $dossierPhotos . DIRECTORY_SEPARATOR . $nomFichier
                );

                if (
                    $cheminPhysique !== false &&
                    dirname($cheminPhysique) === $dossierPhotos &&
                    is_file($cheminPhysique)
                ) {
                    @unlink($cheminPhysique);
                }
            }
        }

        return true;

    } catch (\Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        throw $e;
    }
}


/**
 * =========================================================
 * FILTRES COMMUNS À LA GESTION ADMINISTRATEUR
 * =========================================================
 */
private static function adminConstruireFiltres(array $filtres): array
{
    // Les anciens brouillons sont exclus de la gestion admin.
    $where = [
        "LOWER(BTRIM(COALESCE(b.statut, ''))) <> 'brouillon'"
    ];

    $params = [];
    $statut = strtolower(trim((string) ($filtres['statut'] ?? '')));

    if ($statut !== '') {
        switch ($statut) {
            case 'actif':
            case 'active':
            case 'valide':
            case 'validé':
                $where[] = "
                    LOWER(BTRIM(b.statut))
                        IN ('actif', 'active', 'valide', 'validé')
                    AND (
                        b.expire_at IS NULL
                        OR b.expire_at > CURRENT_TIMESTAMP
                    )
                ";
                break;

            case 'en_attente':
            case 'en-attente':
            case 'pending':
                $where[] = "
                    LOWER(BTRIM(b.statut))
                        IN ('en_attente', 'en-attente', 'pending')
                ";
                break;

            case 'rejete':
            case 'rejeté':
            case 'refuse':
            case 'refusé':
                $where[] = "
                    LOWER(BTRIM(b.statut))
                        IN ('rejete', 'rejeté', 'refuse', 'refusé')
                ";
                break;

            case 'expire':
            case 'expiré':
            case 'expired':
                $where[] = "(
                    (
                        LOWER(BTRIM(b.statut))
                            IN ('actif', 'active', 'valide', 'validé')
                        AND b.expire_at IS NOT NULL
                        AND b.expire_at <= CURRENT_TIMESTAMP
                    )
                    OR LOWER(BTRIM(b.statut))
                        IN ('expire', 'expiré', 'expired')
                )";
                break;

            case 'suspendu':
            case 'suspendue':
                $where[] = "
                    LOWER(BTRIM(b.statut))
                        IN ('suspendu', 'suspendue', 'suspended')
                ";
                break;

            default:
                $where[] = '1 = 0';
                break;
        }
    }

    // Filtre par catégorie
    $categoryId = trim((string) ($filtres['category_id'] ?? ''));

    if ($categoryId !== '') {
        if (ctype_digit($categoryId)) {
            $where[] = 'b.category_id = :category_id';
            $params['category_id'] = (int) $categoryId;
        } else {
            $where[] = '1 = 0';
        }
    }

    // Recherche par annonce, propriétaire ou localisation
    $q = trim((string) ($filtres['q'] ?? ''));

    if ($q !== '') {
        $where[] = "(
            b.titre ILIKE :q_titre
            OR b.description ILIKE :q_description
            OR u.nom ILIKE :q_nom
            OR u.prenom ILIKE :q_prenom
            OR u.email ILIKE :q_email
            OR v.ville ILIKE :q_ville
            OR v.quartier ILIKE :q_quartier
        )";

        $recherche = '%' . $q . '%';

        foreach ([
            'q_titre',
            'q_description',
            'q_nom',
            'q_prenom',
            'q_email',
            'q_ville',
            'q_quartier',
        ] as $cle) {
            $params[$cle] = $recherche;
        }
    }

    return [
        'WHERE ' . implode(' AND ', $where),
        $params
    ];
}

/**
 * =========================================================
 * LISTE DES ANNONCES EN ATTENTE POUR LE MODÉRATEUR
 * =========================================================
 */
public static function moderateurLister(
    array $filtres = [],
    int $parPage = 6,
    int $offset = 0
): array {
    // Le modérateur ne consulte que les annonces en attente.
    $filtres['statut'] = 'en_attente';

    return self::adminLister($filtres, $parPage, $offset);
}

/**
 * =========================================================
 * COMPTER LES ANNONCES EN ATTENTE POUR LE MODÉRATEUR
 * =========================================================
 */
public static function moderateurCompter(array $filtres = []): int
{
    // On impose le statut en attente.
    $filtres['statut'] = 'en_attente';

    return self::adminCompter($filtres);
}

/**
 * =========================================================
 * LISTE DES BIENS POUR L'ADMINISTRATION
 * =========================================================
 */
public static function adminLister(
    array $filtres = [],
    int $parPage = 6,
    int $offset = 0
): array {
    $parPage = max(1, min($parPage, 100));
    $offset = max(0, $offset);

    [$whereSql, $params] = self::adminConstruireFiltres($filtres);

    $sql = "
        SELECT
            b.*,
            c.libelle AS categorie_nom,
            v.ville AS ville_nom,
            v.quartier AS quartier_nom,
            v.region AS region_nom,
            u.nom AS proprio_nom,
            u.prenom AS proprio_prenom,
            u.email AS proprio_email,
            u.telephone AS proprio_tel,

            (
                SELECT p.file_path
                FROM photos p
                WHERE p.bien_id = b.id
                ORDER BY p.is_principal DESC, p.created_at DESC
                LIMIT 1
            ) AS photo

        FROM biens b
        LEFT JOIN categories_biens c ON c.id = b.category_id
        LEFT JOIN villes v ON v.id = b.ville_id
        LEFT JOIN users u ON u.id = b.user_id

        $whereSql

        ORDER BY
            CASE
                WHEN LOWER(BTRIM(b.statut))
                    IN ('en_attente', 'en-attente', 'pending')
                    THEN 0
                WHEN LOWER(BTRIM(b.statut))
                    IN ('actif', 'active', 'valide', 'validé')
                    AND (
                        b.expire_at IS NULL
                        OR b.expire_at > CURRENT_TIMESTAMP
                    )
                    THEN 1
                ELSE 2
            END,
            b.created_at DESC

        LIMIT :limite OFFSET :decalage
    ";

    $stmt = Database::getConnection()->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue(
            ':' . $key,
            $value,
            is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
        );
    }

    $stmt->bindValue(':limite', $parPage, PDO::PARAM_INT);
    $stmt->bindValue(':decalage', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * =========================================================
 * COMPTER LES BIENS POUR L'ADMINISTRATION
 * =========================================================
 */
public static function adminCompter(array $filtres = []): int
{
    [$whereSql, $params] = self::adminConstruireFiltres($filtres);

    $sql = "
        SELECT COUNT(*)
        FROM biens b
        LEFT JOIN villes v ON v.id = b.ville_id
        LEFT JOIN users u ON u.id = b.user_id
        $whereSql
    ";

    $stmt = Database::getConnection()->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue(
            ':' . $key,
            $value,
            is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
        );
    }

    $stmt->execute();

    return (int) $stmt->fetchColumn();
}

/**
 * =========================================================
 * STATISTIQUES ADMINISTRATEUR
 * =========================================================
 */
public static function adminStatistiques(): array
{
    $sql = "
        SELECT
            COUNT(*) AS total_biens,

            COUNT(*) FILTER (
                WHERE LOWER(BTRIM(statut))
                    IN ('actif', 'active', 'valide', 'validé')
                AND (
                    expire_at IS NULL
                    OR expire_at > CURRENT_TIMESTAMP
                )
            ) AS biens_actifs,

            COUNT(*) FILTER (
                WHERE LOWER(BTRIM(statut))
                    IN ('en_attente', 'en-attente', 'pending')
            ) AS biens_en_attente,

            COUNT(*) FILTER (
                WHERE LOWER(BTRIM(statut))
                    IN ('rejete', 'rejeté', 'refuse', 'refusé')
            ) AS biens_rejetes,

            COUNT(*) FILTER (
                WHERE (
                    LOWER(BTRIM(statut))
                        IN ('actif', 'active', 'valide', 'validé')
                    AND expire_at IS NOT NULL
                    AND expire_at <= CURRENT_TIMESTAMP
                )
                OR LOWER(BTRIM(statut))
                    IN ('expire', 'expiré', 'expired')
            ) AS biens_expires,

            COUNT(*) FILTER (
                WHERE LOWER(BTRIM(statut))
                    IN ('suspendu', 'suspendue', 'suspended')
            ) AS biens_suspendus

        FROM biens
        WHERE LOWER(BTRIM(COALESCE(statut, ''))) <> 'brouillon'
    ";

    $result = Database::getConnection()
        ->query($sql)
        ->fetch(PDO::FETCH_ASSOC);

    return array_map(
        'intval',
        $result ?: [
            'total_biens' => 0,
            'biens_actifs' => 0,
            'biens_en_attente' => 0,
            'biens_rejetes' => 0,
            'biens_expires' => 0,
            'biens_suspendus' => 0,
        ]
    );
}

/**
 * =========================================================
 * APPROUVER UNE ANNONCE EN ATTENTE
 * =========================================================
 */
public static function adminApprouver(string $bienId): bool
{
    $sql = "
        UPDATE biens
        SET
            statut = 'actif',
            motif_rejet = NULL,
            expire_at = CURRENT_TIMESTAMP + INTERVAL '30 days',
            updated_at = CURRENT_TIMESTAMP
        WHERE id = CAST(:bien_id AS UUID)
          AND LOWER(BTRIM(statut))
              IN ('en_attente', 'en-attente', 'pending')
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute(['bien_id' => $bienId]);

    return $stmt->rowCount() === 1;
}

/**
 * =========================================================
 * REJETER UNE ANNONCE EN ATTENTE
 * =========================================================
 */
public static function adminRejeter(
    string $bienId,
    string $motif
): bool {
    $motif = trim($motif);

    if ($motif === '') {
        throw new \InvalidArgumentException(
            'Le motif du rejet est obligatoire.'
        );
    }

    $sql = "
        UPDATE biens
        SET
            statut = 'rejete',
            motif_rejet = :motif,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = CAST(:bien_id AS UUID)
          AND LOWER(BTRIM(statut))
              IN ('en_attente', 'en-attente', 'pending')
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'motif' => $motif,
        'bien_id' => $bienId,
    ]);

    return $stmt->rowCount() === 1;
}

/**
 * =========================================================
 * BIENS D'UNE AGENCE POUR L'ADMINISTRATION
 * =========================================================
 */
public static function agenceListerParUtilisateur(
    string $userId,
    int $parPage = 3,
    int $offset = 0
): array {
    $parPage = max(1, min($parPage, 3));
    $offset = max(0, $offset);

    $sql = "
        SELECT
            b.id,
            b.user_id,
            b.titre,
            b.description,
            b.prix,
            b.superficie AS surface,
            b.pieces,
            b.chambres,
            b.type_offre,
            b.statut,
            b.nb_vues,
            b.created_at,
            b.expire_at,

            c.libelle AS categorie_nom,

            v.ville AS ville_nom,
            v.quartier AS quartier_nom,
            v.region AS region_nom,

            (
                SELECT p.file_path
                FROM photos p
                WHERE p.bien_id = b.id
                ORDER BY p.is_principal DESC, p.created_at DESC
                LIMIT 1
            ) AS photo

        FROM biens b

        LEFT JOIN categories_biens c
            ON c.id = b.category_id

        LEFT JOIN villes v
            ON v.id = b.ville_id

        WHERE b.user_id = :user_id

        ORDER BY b.created_at DESC

        LIMIT :limite
        OFFSET :decalage
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
    $stmt->bindValue(':limite', $parPage, PDO::PARAM_INT);
    $stmt->bindValue(':decalage', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Compter tous les biens d'une agence.
 */
public static function agenceCompterParUtilisateur(
    string $userId
): int {
    $sql = "
        SELECT COUNT(*)
        FROM biens
        WHERE user_id = :user_id
    ";

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'user_id' => $userId
    ]);

    return (int) $stmt->fetchColumn();
}

}