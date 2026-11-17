<?php
namespace App\Models;

use Core\Database;
use Core\Validator;
use PDO;
use RuntimeException;

class DemandeRenouvellement
{
    /**
     * Soumettre une demande de renouvellement.
     *
     * Un propriétaire ne peut demander le renouvellement
     * que de son propre bien expiré.
     */
    public static function soumettre(
        string $bienId,
        string $userId
    ): string {
        $db = Database::getConnection();

        $db->beginTransaction();

        try {
            // Vérifier que le bien appartient à l'utilisateur
            // et qu'il est effectivement expiré.
            $sqlBien = '
                SELECT id
                FROM public.biens
                WHERE id = :bien_id
                  AND user_id = :user_id
                  AND (
                      statut = \'expire\'
                      OR expire_at <= CURRENT_TIMESTAMP
                  )
                FOR UPDATE
            ';

            $stmtBien = $db->prepare($sqlBien);

            $stmtBien->execute([
                'bien_id' => $bienId,
                'user_id' => $userId,
            ]);

            if (!$stmtBien->fetch(PDO::FETCH_ASSOC)) {
                throw new RuntimeException(
                    'Ce bien est introuvable, ne vous appartient pas ou n’est pas expiré.'
                );
            }

            // Vérifier qu'aucune demande n'est déjà en attente.
            $sqlExistante = '
                SELECT id
                FROM public.demandes_renouvellement
                WHERE bien_id = :bien_id
                  AND statut = \'en_attente\'
                LIMIT 1
            ';

            $stmtExistante = $db->prepare($sqlExistante);

            $stmtExistante->execute([
                'bien_id' => $bienId,
            ]);

            if ($stmtExistante->fetch(PDO::FETCH_ASSOC)) {
                throw new RuntimeException(
                    'Une demande de renouvellement est déjà en attente.'
                );
            }

            // Enregistrer la demande.
            $sqlInsert = '
                INSERT INTO public.demandes_renouvellement (
                    bien_id,
                    user_id,
                    statut
                )
                VALUES (
                    :bien_id,
                    :user_id,
                    \'en_attente\'
                )
                RETURNING id
            ';

            $stmtInsert = $db->prepare($sqlInsert);

            $stmtInsert->execute([
                'bien_id' => $bienId,
                'user_id' => $userId,
            ]);

            $demandeId = (string) $stmtInsert->fetchColumn();

            $db->commit();

            return $demandeId;

        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Récupérer la dernière demande de renouvellement
     * pour chaque bien appartenant à un utilisateur.
     *
     * Le résultat est organisé par identifiant du bien.
     */
    public static function statutsParBiensUtilisateur(
        string $userId
    ): array {
        $sql = '
            SELECT DISTINCT ON (bien_id)
                bien_id,
                statut,
                motif_refus,
                date_demande,
                date_decision
            FROM public.demandes_renouvellement
            WHERE user_id = :user_id
            ORDER BY
                bien_id,
                date_demande DESC,
                id DESC
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId,
        ]);

        $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultat = [];

        foreach ($demandes as $demande) {
            $resultat[$demande['bien_id']] = $demande;
        }

        return $resultat;
    }

    /**
     * Lister les demandes de renouvellement en attente.
     */
    public static function listerEnAttente(): array
    {
        $sql = '
            SELECT
                d.id AS demande_id,
                d.bien_id,
                d.user_id,
                d.date_demande,
                b.titre AS titre_bien,
                b.statut AS statut_bien,
                b.expire_at,
                u.nom AS nom_proprietaire,
                u.prenom AS prenom_proprietaire,
                u.email AS email_proprietaire
            FROM public.demandes_renouvellement d
            INNER JOIN public.biens b
                ON b.id = d.bien_id
            INNER JOIN public.users u
                ON u.id = d.user_id
            WHERE d.statut = \'en_attente\'
            ORDER BY d.date_demande ASC
        ';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Approuver une demande de renouvellement.
     *
     * Le bien est réactivé pour 30 jours.
     */
    public static function approuver(
        string $demandeId,
        string $adminId
    ): void {
        $db = Database::getConnection();

        $db->beginTransaction();

        try {
            // Vérifier et verrouiller la demande et le bien.
            $sqlDemande = '
                SELECT
                    d.id,
                    d.bien_id,
                    d.statut,
                    b.statut AS statut_bien,
                    b.expire_at
                FROM public.demandes_renouvellement d
                INNER JOIN public.biens b
                    ON b.id = d.bien_id
                WHERE d.id = :demande_id
                FOR UPDATE OF d, b
            ';

            $stmtDemande = $db->prepare($sqlDemande);

            $stmtDemande->execute([
                'demande_id' => $demandeId,
            ]);

            $demande = $stmtDemande->fetch(PDO::FETCH_ASSOC);

            if (!$demande) {
                throw new RuntimeException(
                    'Demande de renouvellement introuvable.'
                );
            }

            if ($demande['statut'] !== 'en_attente') {
                throw new RuntimeException(
                    'Cette demande a déjà été traitée.'
                );
            }

            // Vérifier que le bien est toujours expiré.
            $bienExpire = (
                $demande['statut_bien'] === 'expire'
                || (
                    !empty($demande['expire_at'])
                    && strtotime((string) $demande['expire_at']) <= time()
                )
            );

            if (!$bienExpire) {
                throw new RuntimeException(
                    'Ce bien n’est plus expiré. Renouvellement impossible.'
                );
            }

            // Enregistrer l'approbation.
            $sqlDecision = '
                UPDATE public.demandes_renouvellement
                SET
                    statut = \'approuvee\',
                    date_decision = CURRENT_TIMESTAMP,
                    admin_id = :admin_id,
                    motif_refus = NULL
                WHERE id = :demande_id
            ';

            $stmtDecision = $db->prepare($sqlDecision);

            $stmtDecision->execute([
                'admin_id' => $adminId,
                'demande_id' => $demandeId,
            ]);

            // Réactiver le bien pendant 30 jours.
            $sqlBien = '
                UPDATE public.biens
                SET
                    statut = \'actif\',
                    expire_at = CURRENT_TIMESTAMP + INTERVAL \'30 days\',
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :bien_id
            ';

            $stmtBien = $db->prepare($sqlBien);

            $stmtBien->execute([
                'bien_id' => $demande['bien_id'],
            ]);

            $db->commit();

        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Refuser une demande de renouvellement.
     *
     * Le motif du refus est obligatoire.
     * Le bien reste dans son état actuel.
     */
    public static function refuser(
        string $demandeId,
        string $adminId,
        string $motif
    ): void {
        $motif = trim($motif);

        if (!Validator::required($motif)) {
            throw new RuntimeException(
                'Le motif du refus est obligatoire.'
            );
        }

        if (!Validator::maxLength($motif, 2000)) {
            throw new RuntimeException(
                'Le motif du refus ne doit pas dépasser 2000 caractères.'
            );
        }

        $db = Database::getConnection();

        $db->beginTransaction();

        try {
            // Vérifier et verrouiller la demande.
            $sqlDemande = '
                SELECT id, statut
                FROM public.demandes_renouvellement
                WHERE id = :demande_id
                FOR UPDATE
            ';

            $stmtDemande = $db->prepare($sqlDemande);

            $stmtDemande->execute([
                'demande_id' => $demandeId,
            ]);

            $demande = $stmtDemande->fetch(PDO::FETCH_ASSOC);

            if (!$demande) {
                throw new RuntimeException(
                    'Demande de renouvellement introuvable.'
                );
            }

            if ($demande['statut'] !== 'en_attente') {
                throw new RuntimeException(
                    'Cette demande a déjà été traitée.'
                );
            }

            // Enregistrer le refus et son motif.
            $sqlRefus = '
                UPDATE public.demandes_renouvellement
                SET
                    statut = \'refusee\',
                    date_decision = CURRENT_TIMESTAMP,
                    admin_id = :admin_id,
                    motif_refus = :motif_refus
                WHERE id = :demande_id
            ';

            $stmtRefus = $db->prepare($sqlRefus);

            $stmtRefus->execute([
                'admin_id' => $adminId,
                'motif_refus' => $motif,
                'demande_id' => $demandeId,
            ]);

            $db->commit();

        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }
    }
}
