<?php

namespace App\Models;

use Core\Database;
use PDO;

class Message
{
    /**
     * Utilisateur actuellement utilisé temporairement
     * tant que l'authentification n'est pas encore créée.
     */
    public const TEMP_USER_ID = '197c4356-4eb4-4109-a07c-98AB100a9d88';


    /**
     * Envoyer un message concernant un bien.
     */
    public static function envoyer(
        string $bienId,
        string $expediteurId,
        string $destinataireId,
        string $sujet,
        string $contenu
    ): string {

        $sql = '
            INSERT INTO messages (
                bien_id,
                expediteur_id,
                destinataire_id,
                sujet,
                contenu
            )
            VALUES (
                :bien_id,
                :expediteur_id,
                :destinataire_id,
                :sujet,
                :contenu
            )
            RETURNING id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'bien_id' => $bienId,
            'expediteur_id' => $expediteurId,
            'destinataire_id' => $destinataireId,
            'sujet' => $sujet,
            'contenu' => $contenu,
        ]);

        return (string) $stmt->fetchColumn();
    }


    /**
     * Messages reçus par un utilisateur.
     */
    public static function recus(string $userId): array
    {
        $sql = '
            SELECT
                m.id,
                m.bien_id,
                m.expediteur_id,
                m.destinataire_id,
                m.sujet,
                m.contenu,
                m.lu,
                m.created_at,

                u.nom AS expediteur_nom,
                u.prenom AS expediteur_prenom,

                b.titre AS bien_titre

            FROM messages m

            LEFT JOIN users u
                ON u.id = m.expediteur_id

            LEFT JOIN biens b
                ON b.id = m.bien_id

            WHERE m.destinataire_id = :user_id

            ORDER BY m.created_at DESC
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Messages envoyés par un utilisateur.
     */
    public static function envoyes(string $userId): array
    {
        $sql = '
            SELECT
                m.id,
                m.bien_id,
                m.expediteur_id,
                m.destinataire_id,
                m.sujet,
                m.contenu,
                m.lu,
                m.created_at,

                u.nom AS destinataire_nom,
                u.prenom AS destinataire_prenom,

                b.titre AS bien_titre

            FROM messages m

            LEFT JOIN users u
                ON u.id = m.destinataire_id

            LEFT JOIN biens b
                ON b.id = m.bien_id

            WHERE m.expediteur_id = :user_id

            ORDER BY m.created_at DESC
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Marquer un message comme lu.
     */
    public static function marquerCommeLu(
        string $messageId,
        string $userId
    ): bool {

        $sql = '
            UPDATE messages
            SET lu = TRUE

            WHERE id = :id
            AND destinataire_id = :user_id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        return $stmt->execute([
            'id' => $messageId,
            'user_id' => $userId,
        ]);
    }
}