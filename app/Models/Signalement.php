<?php

namespace App\Models;

use Core\Database;
use PDO;
use InvalidArgumentException;

class Signalement
{
    /**
     * Créer un signalement.
     */
    public static function creer(
        string $bienId,
        string $userId,
        string $motif
    ): string {
        $sql = '
            INSERT INTO signalements (
                bien_id,
                user_id,
                motif,
                statut
            )
            VALUES (
                :bien_id,
                :user_id,
                :motif,
                :statut
            )
            RETURNING id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'bien_id' => $bienId,
            'user_id' => $userId,
            'motif' => $motif,
            'statut' => 'en_attente',
        ]);

        return (string) $stmt->fetchColumn();
    }

    /**
     * Récupérer tous les signalements sans pagination.
     *
     * Cette méthode est conservée pour ne pas casser
     * les éventuels autres appels existants.
     */
    public static function tous(): array
    {
        $sql = '
            SELECT
                s.id,
                s.bien_id,
                s.user_id,
                s.motif,
                s.statut,
                s.created_at,

                b.titre AS bien_titre,

                proprietaire.nom AS proprietaire_nom,
                proprietaire.prenom AS proprietaire_prenom,
                proprietaire.email AS proprietaire_email,

                signaleur.nom AS user_nom,
                signaleur.prenom AS user_prenom,
                signaleur.email AS user_email

            FROM signalements s

            LEFT JOIN biens b
                ON b.id = s.bien_id

            LEFT JOIN users proprietaire
                ON proprietaire.id = b.user_id

            LEFT JOIN users signaleur
                ON signaleur.id = s.user_id
                  WHERE s.deleted_at IS NULL
            ORDER BY s.created_at DESC
        ';
        

        $stmt = Database::getConnection()->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Construire les conditions de recherche et de filtrage.
     *
     * La recherche porte sur :
     * - le motif du signalement ;
     * - le titre du bien ;
     * - le nom et le prénom du propriétaire ;
     * - le nom et le prénom du signaleur ;
     * - l'adresse e-mail du signaleur.
     */
    private static function construireFiltres(
        array $filtres
    ): array {
       $conditions = ['s.deleted_at IS NULL'];
       $parametres = [];

        $recherche = trim((string) ($filtres['q'] ?? ''));
        $statut = trim((string) ($filtres['statut'] ?? ''));

        if ($recherche !== '') {
            $conditions[] = "(
                s.motif ILIKE :q_motif
                OR COALESCE(b.titre, '') ILIKE :q_bien
                OR COALESCE(proprietaire.nom, '') ILIKE :q_prop_nom
                OR COALESCE(proprietaire.prenom, '') ILIKE :q_prop_prenom
                OR COALESCE(signaleur.nom, '') ILIKE :q_user_nom
                OR COALESCE(signaleur.prenom, '') ILIKE :q_user_prenom
                OR COALESCE(signaleur.email, '') ILIKE :q_user_email
            )";

            $valeurRecherche = '%' . $recherche . '%';

            $parametres['q_motif'] = $valeurRecherche;
            $parametres['q_bien'] = $valeurRecherche;
            $parametres['q_prop_nom'] = $valeurRecherche;
            $parametres['q_prop_prenom'] = $valeurRecherche;
            $parametres['q_user_nom'] = $valeurRecherche;
            $parametres['q_user_prenom'] = $valeurRecherche;
            $parametres['q_user_email'] = $valeurRecherche;
        }

        $statutsAutorises = [
            'en_attente',
            'traite',
            'rejete',
        ];

        if (
            $statut !== ''
            && in_array($statut, $statutsAutorises, true)
        ) {
            $conditions[] = 's.statut = :statut';
            $parametres['statut'] = $statut;
        }

        $where = '';

        if (!empty($conditions)) {
            $where = ' WHERE ' . implode(' AND ', $conditions);
        }

        return [
            'where' => $where,
            'parametres' => $parametres,
        ];
    }

    /**
     * Compter les signalements correspondant aux filtres.
     *
     * Le total sert à calculer le nombre de pages.
     */
    public static function adminCompter(
        array $filtres = []
    ): int {
        $conditions = self::construireFiltres($filtres);

        $sql = '
            SELECT COUNT(*)
            FROM signalements s

            LEFT JOIN biens b
                ON b.id = s.bien_id

            LEFT JOIN users proprietaire
                ON proprietaire.id = b.user_id

            LEFT JOIN users signaleur
                ON signaleur.id = s.user_id

            ' . $conditions['where'];

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute($conditions['parametres']);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Récupérer une page de signalements.
     *
     * @param array $filtres Filtres de recherche et de statut.
     * @param int   $limite  Nombre de résultats par page.
     * @param int   $offset  Position du premier résultat.
     */
    public static function adminLister(
        array $filtres = [],
        int $limite = 12,
        int $offset = 0
    ): array {
        $limite = max(1, min($limite, 100));
        $offset = max(0, $offset);

        $conditions = self::construireFiltres($filtres);

        $sql = '
            SELECT
                s.id,
                s.bien_id,
                s.user_id,
                s.motif,
                s.statut,
                s.created_at,

                b.titre AS bien_titre,

                proprietaire.nom AS proprietaire_nom,
                proprietaire.prenom AS proprietaire_prenom,
                proprietaire.email AS proprietaire_email,

                signaleur.nom AS user_nom,
                signaleur.prenom AS user_prenom,
                signaleur.email AS user_email

            FROM signalements s

            LEFT JOIN biens b
                ON b.id = s.bien_id

            LEFT JOIN users proprietaire
                ON proprietaire.id = b.user_id

            LEFT JOIN users signaleur
                ON signaleur.id = s.user_id

            ' . $conditions['where'] . '

            ORDER BY s.created_at DESC

            LIMIT :limite
            OFFSET :offset
        ';

        $stmt = Database::getConnection()->prepare($sql);

        foreach ($conditions['parametres'] as $nom => $valeur) {
            $stmt->bindValue(':' . $nom, $valeur, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Modifier le statut d'un signalement.
     */
    public static function changerStatut(
        string $id,
        string $statut
    ): bool {
        $statutsAutorises = [
            'en_attente',
            'traite',
            'rejete',
        ];

        if (!in_array($statut, $statutsAutorises, true)) {
            throw new InvalidArgumentException(
                'Le statut du signalement est invalide.'
            );
        }

        if (trim($id) === '') {
            return false;
        }

        $sql = '
            UPDATE signalements
            SET statut = :statut
            WHERE id = :id
              AND statut IS DISTINCT FROM :statut_comparaison
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'id' => $id,
            'statut' => $statut,
            'statut_comparaison' => $statut,
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Marquer un signalement comme traité.
     */
    public static function marquerTraite(string $id): bool
    {
        return self::changerStatut($id, 'traite');
    }

    /**
     * Rejeter un signalement.
     */
    public static function rejeter(string $id): bool
    {
        return self::changerStatut($id, 'rejete');
    }
    
/**
 * Supprimer logiquement un signalement traité ou rejeté.
 *
 * Le signalement reste conservé en base de données.
 * Un signalement en attente ne peut pas être supprimé.
 */
public static function supprimer(string $id): bool
{
    if (trim($id) === '') {
        return false;
    }

    $sql = '
        UPDATE public.signalements
        SET deleted_at = CURRENT_TIMESTAMP
        WHERE id = :id
          AND statut IN (:statut_traite, :statut_rejete)
          AND deleted_at IS NULL
    ';

    $stmt = Database::getConnection()->prepare($sql);

    $stmt->execute([
        'id' => $id,
        'statut_traite' => 'traite',
        'statut_rejete' => 'rejete',
    ]);

    return $stmt->rowCount() > 0;
}
/**
 * Statistiques globales des signalements non archivés.
 */
public static function adminStatistiques(): array
{
    $sql = "
        SELECT
            COUNT(*) AS total,

            COUNT(*) FILTER (
                WHERE LOWER(REPLACE(REPLACE(TRIM(COALESCE(statut, '')), '-', '_'), ' ', '_'))
                IN ('en_attente', 'pending', 'nouveau')
            ) AS en_attente,

            COUNT(*) FILTER (
                WHERE LOWER(REPLACE(REPLACE(TRIM(COALESCE(statut, '')), '-', '_'), ' ', '_'))
                IN ('traite', 'traité', 'resolu', 'résolu', 'resolved')
            ) AS traites,

            COUNT(*) FILTER (
                WHERE LOWER(REPLACE(REPLACE(TRIM(COALESCE(statut, '')), '-', '_'), ' ', '_'))
                IN ('rejete', 'rejeté', 'refuse', 'refusé', 'rejected')
            ) AS rejetes

        FROM public.signalements
        WHERE deleted_at IS NULL
    ";

    $stmt = Database::getConnection()->query($sql);

    $resultat = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    return [
        'total' => (int) ($resultat['total'] ?? 0),
        'en_attente' => (int) ($resultat['en_attente'] ?? 0),
        'traites' => (int) ($resultat['traites'] ?? 0),
        'rejetes' => (int) ($resultat['rejetes'] ?? 0),
    ];
}

}
