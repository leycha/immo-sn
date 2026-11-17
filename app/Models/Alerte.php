<?php

namespace App\Models;

use Core\Database;
use PDO;

class Alerte
{
    /**
     * =========================================================
     * RÉCUPÉRER TOUTES LES ALERTES D'UN UTILISATEUR
     * =========================================================
     */
    public static function parUtilisateur(string $userId): array
    {
        $sql = '
            SELECT
                a.id,
                a.user_id,
                a.category_id,
                a.ville_id,
                a.budget_max,
                a.is_active,
                a.created_at,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,
                v.region AS region_nom

            FROM alertes a

            LEFT JOIN categories_biens c
                ON c.id = a.category_id

            LEFT JOIN villes v
                ON v.id = a.ville_id

            WHERE a.user_id = :user_id

            ORDER BY a.created_at DESC
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * =========================================================
     * RÉCUPÉRER UNE ALERTE APPARTENANT À UN UTILISATEUR
     * =========================================================
     */
    public static function trouverPourUtilisateur(
        string $id,
        string $userId
    ): ?array {
        $sql = '
            SELECT
                a.id,
                a.user_id,
                a.category_id,
                a.ville_id,
                a.budget_max,
                a.is_active,
                a.created_at,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom,
                v.region AS region_nom

            FROM alertes a

            LEFT JOIN categories_biens c
                ON c.id = a.category_id

            LEFT JOIN villes v
                ON v.id = a.ville_id

            WHERE a.id = :id
              AND a.user_id = :user_id

            LIMIT 1
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);

        $alerte = $stmt->fetch(PDO::FETCH_ASSOC);

        return $alerte ?: null;
    }


    /**
     * =========================================================
     * CRÉER UNE ALERTE
     * =========================================================
     */
    public static function creer(
        string $userId,
        ?int $categoryId,
        ?int $villeId,
        float $budgetMax
    ): string {
        $sql = '
            INSERT INTO alertes (
                user_id,
                category_id,
                ville_id,
                budget_max,
                is_active,
                created_at
            )
            VALUES (
                :user_id,
                :category_id,
                :ville_id,
                :budget_max,
                TRUE,
                NOW()
            )
            RETURNING id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'ville_id' => $villeId,
            'budget_max' => $budgetMax
        ]);

        return (string) $stmt->fetchColumn();
    }


    /**
     * =========================================================
     * MODIFIER UNE ALERTE
     * =========================================================
     */
    public static function modifier(
        string $id,
        string $userId,
        ?int $categoryId,
        ?int $villeId,
        float $budgetMax
    ): bool {
        $sql = '
            UPDATE alertes

            SET
                category_id = :category_id,
                ville_id = :ville_id,
                budget_max = :budget_max

            WHERE id = :id
              AND user_id = :user_id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
            'category_id' => $categoryId,
            'ville_id' => $villeId,
            'budget_max' => $budgetMax
        ]);

        return $stmt->rowCount() > 0;
    }


    /**
     * =========================================================
     * SUPPRIMER UNE ALERTE
     * =========================================================
     */
    public static function supprimer(
        string $id,
        string $userId
    ): bool {
        $sql = '
            DELETE FROM alertes

            WHERE id = :id
              AND user_id = :user_id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);

        return $stmt->rowCount() > 0;
    }


    /**
     * =========================================================
     * ACTIVER / DÉSACTIVER UNE ALERTE
     * =========================================================
     */
    public static function changerStatut(
    string $id,
    string $userId,
    bool $active
): bool {
    $sql = '
        UPDATE alertes

        SET is_active = :is_active

        WHERE id = :id
          AND user_id = :user_id
    ';

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->bindValue(
        ':id',
        $id,
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':user_id',
        $userId,
        PDO::PARAM_STR
    );

    $stmt->bindValue(
        ':is_active',
        $active,
        PDO::PARAM_BOOL
    );

    $stmt->execute();

    return $stmt->rowCount() > 0;
}

    /**
     * =========================================================
     * COMPTER LES ALERTES ACTIVES
     * =========================================================
     */
    public static function compterActives(string $userId): int
    {
        $sql = '
            SELECT COUNT(*)

            FROM alertes

            WHERE user_id = :user_id
              AND is_active = TRUE
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return (int) $stmt->fetchColumn();
    }
}