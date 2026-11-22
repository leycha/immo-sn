<?php

namespace App\Models;

use Core\Database;
use PDO;

class Message
{
    public const TEMP_USER_ID = '197c4356-4eb4-4109-a07c-98AB100a9d88';

    public static function envoyer(
        string $bienId,
        string $expediteurId,
        string $destinataireId,
        string $sujet,
        string $contenu
    ): string {
        $sql = '
            INSERT INTO messages (
                bien_id, expediteur_id, destinataire_id, sujet, contenu
            )
            VALUES (
                :bien_id, :expediteur_id, :destinataire_id, :sujet, :contenu
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

    public static function recus(string $userId): array
    {
        $sql = '
            SELECT m.*,
                   u.nom AS expediteur_nom,
                   u.prenom AS expediteur_prenom,
                   b.titre AS bien_titre
            FROM messages m
            LEFT JOIN users u ON u.id = m.expediteur_id
            LEFT JOIN biens b ON b.id = m.bien_id
            WHERE m.destinataire_id = :user_id
            ORDER BY m.created_at DESC
        ';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function envoyes(string $userId): array
    {
        $sql = '
            SELECT m.*,
                   u.nom AS destinataire_nom,
                   u.prenom AS destinataire_prenom,
                   b.titre AS bien_titre
            FROM messages m
            LEFT JOIN users u ON u.id = m.destinataire_id
            LEFT JOIN biens b ON b.id = m.bien_id
            WHERE m.expediteur_id = :user_id
            ORDER BY m.created_at DESC
        ';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    /**
     * Retrouve une conversation à partir d'un message.
     * L'utilisateur doit être l'un des deux participants.
     */
    public static function conversationParMessage(
        string $messageId,
        string $userId
    ): ?array {
        $db = Database::getConnection();

        $sql = '
            SELECT
                m.id,
                m.bien_id,
                m.expediteur_id,
                m.destinataire_id,
                m.sujet,
                b.titre AS bien_titre,

                CASE
                    WHEN m.expediteur_id = CAST(:user_id AS UUID)
                    THEN m.destinataire_id
                    ELSE m.expediteur_id
                END AS interlocuteur_id,

                CASE
                    WHEN m.expediteur_id = CAST(:user_id AS UUID)
                    THEN receiver.prenom
                    ELSE sender.prenom
                END AS interlocuteur_prenom,

                CASE
                    WHEN m.expediteur_id = CAST(:user_id AS UUID)
                    THEN receiver.nom
                    ELSE sender.nom
                END AS interlocuteur_nom

            FROM messages m
            LEFT JOIN biens b ON b.id = m.bien_id
            LEFT JOIN users sender ON sender.id = m.expediteur_id
            LEFT JOIN users receiver ON receiver.id = m.destinataire_id

            WHERE m.id = CAST(:message_id AS UUID)
              AND (
                  m.expediteur_id = CAST(:user_id AS UUID)
                  OR m.destinataire_id = CAST(:user_id AS UUID)
              )
            LIMIT 1
        ';

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'message_id' => $messageId,
            'user_id' => $userId,
        ]);

        $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$conversation) {
            return null;
        }

        // Marquer comme lus les messages reçus de cet interlocuteur
        // pour ce bien uniquement.
        $stmtLu = $db->prepare('
            UPDATE messages
            SET lu = TRUE
            WHERE bien_id = CAST(:bien_id AS UUID)
              AND expediteur_id = CAST(:interlocuteur_id AS UUID)
              AND destinataire_id = CAST(:user_id AS UUID)
              AND lu = FALSE
        ');

        $stmtLu->execute([
            'bien_id' => $conversation['bien_id'],
            'interlocuteur_id' => $conversation['interlocuteur_id'],
            'user_id' => $userId,
        ]);

        // Historique complet de l'échange.
        $stmtHistorique = $db->prepare('
            SELECT
                m.id,
                m.bien_id,
                m.expediteur_id,
                m.destinataire_id,
                m.sujet,
                m.contenu,
                m.lu,
                m.created_at,
                u.prenom AS expediteur_prenom,
                u.nom AS expediteur_nom
            FROM messages m
            LEFT JOIN users u ON u.id = m.expediteur_id
            WHERE m.bien_id = CAST(:bien_id AS UUID)
              AND (
                (
                    m.expediteur_id = CAST(:user_id AS UUID)
                    AND m.destinataire_id = CAST(:interlocuteur_id AS UUID)
                )
                OR
                (
                    m.expediteur_id = CAST(:interlocuteur_id AS UUID)
                    AND m.destinataire_id = CAST(:user_id AS UUID)
                )
              )
            ORDER BY m.created_at ASC
        ');

        $stmtHistorique->execute([
            'bien_id' => $conversation['bien_id'],
            'user_id' => $userId,
            'interlocuteur_id' => $conversation['interlocuteur_id'],
        ]);

        $conversation['historique'] =
            $stmtHistorique->fetchAll(PDO::FETCH_ASSOC);

        return $conversation;
    }

    /**
     * Envoyer une réponse à une conversation existante.
     */
    public static function repondre(
        string $messageId,
        string $userId,
        string $contenu
    ): string {
        $conversation = self::conversationParMessage(
            $messageId,
            $userId
        );

        if (!$conversation) {
            throw new \RuntimeException(
                'Conversation introuvable ou accès non autorisé.'
            );
        }

        return self::envoyer(
            (string) $conversation['bien_id'],
            $userId,
            (string) $conversation['interlocuteur_id'],
            'Re: ' . ($conversation['sujet'] ?: 'Message'),
            $contenu
        );
    }
}