<?php

namespace App\Models;

use Core\Database;
use PDO;

class Favori
{
    /**
     * Récupérer les favoris d'un utilisateur.
     */
    public static function parUtilisateur(string $userId): array
    {
        $db = Database::getConnection();

        $sql = "
            SELECT
                f.user_id,
                f.bien_id,
                f.created_at,
                b.*
            FROM favoris f
            INNER JOIN biens b ON b.id = f.bien_id
            WHERE f.user_id = :user_id
            ORDER BY f.created_at DESC NULLS LAST
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifier si un bien est dans les favoris.
     */
    public static function existe(
        string $userId,
        string $bienId
    ): bool {
        $db = Database::getConnection();

        $sql = "
            SELECT 1
            FROM favoris
            WHERE user_id = :user_id
              AND bien_id = :bien_id
            LIMIT 1
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'bien_id' => $bienId
        ]);

        return $stmt->fetchColumn() !== false;
    }

    /**
     * Ajouter un bien aux favoris.
     */
    public static function ajouter(
        string $userId,
        string $bienId
    ): bool {
        $db = Database::getConnection();

        $sql = "
            INSERT INTO favoris (user_id, bien_id, created_at)
            SELECT :user_id, b.id, CURRENT_TIMESTAMP
            FROM biens b
            WHERE b.id = :bien_id
              AND NOT EXISTS (
                  SELECT 1
                  FROM favoris f
                  WHERE f.user_id = :user_id_check
                    AND f.bien_id = b.id
              )
        ";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            'user_id' => $userId,
            'bien_id' => $bienId,
            'user_id_check' => $userId
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Retirer un bien des favoris.
     */
    public static function retirer(
        string $userId,
        string $bienId
    ): bool {
        $db = Database::getConnection();

        $sql = "
            DELETE FROM favoris
            WHERE user_id = :user_id
              AND bien_id = :bien_id
        ";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            'user_id' => $userId,
            'bien_id' => $bienId
        ]);

        return $stmt->rowCount() > 0;
    }
}

