<?php
namespace App\Controllers;

use App\Mail\Mailer;
use App\Models\Bien;
use App\Models\Signalement;
use App\Models\AuditLog;
use App\Models\DemandeRenouvellement;
use Core\Controller;
use Core\Database;
use App\Models\User;
use Core\Session;
use Core\Validator;
use App\Models\Agence;
use PDO;

class AdminController extends Controller
{
    /**
     * Protège les fonctionnalités réservées à l'administrateur.
     */
    private function verifierAdmin(): void
    {
        Session::requireAdmin('/immo-sn/login');
    }

    /**
     * Autorise uniquement l'administrateur ou le modérateur.
     */
    private function verifierModerateurOuAdmin(): void
    {
        Session::requireAdminOrModerator('/immo-sn/login');
    }

    public function index(): void
    {
        $this->verifierAdmin();

        $stats = [
            'total_biens' => 0,
            'biens_actifs' => 0,
            'biens_en_attente' => 0,
            'biens_rejetes' => 0,
            'biens_expires' => 0,
            'total_utilisateurs' => 0,
            'total_signalements' => 0,
            'signalements_en_attente' => 0,
            'total_agences' => 0,
        ];

        $activitesRecentes = [];

        try {
            $db = Database::getConnection();

            /*
             * 1. STATISTIQUES DES BIENS
             */
            $resultatBiens = Bien::adminStatistiques();

            $stats['total_biens'] =
                $resultatBiens['total_biens'] ?? 0;

            $stats['biens_actifs'] =
                $resultatBiens['biens_actifs'] ?? 0;

            $stats['biens_en_attente'] =
                $resultatBiens['biens_en_attente'] ?? 0;

            $stats['biens_rejetes'] =
                $resultatBiens['biens_rejetes'] ?? 0;

            $stats['biens_expires'] =
                $resultatBiens['biens_expires'] ?? 0;

            /*
             * 2. STATISTIQUES DES UTILISATEURS
             */
            $stmt = $db->query("
                SELECT COUNT(*)
                FROM users
            ");

            $stats['total_utilisateurs'] =
                (int) $stmt->fetchColumn();

            /*
             * 3. STATISTIQUES DES AGENCES
             */
            $stmt = $db->query("
                SELECT COUNT(*)
                FROM agences
            ");

            $stats['total_agences'] =
                (int) $stmt->fetchColumn();

            /*
             * 4. STATISTIQUES DES SIGNALEMENTS
             */
            $sqlSignalements = "
                SELECT
                    COUNT(*) AS total,

                    COUNT(*) FILTER (
                        WHERE LOWER(
                            REPLACE(
                                REPLACE(
                                    TRIM(COALESCE(statut, '')),
                                    '-',
                                    '_'
                                ),
                                ' ',
                                '_'
                            )
                        ) IN (
                            'en_attente',
                            'pending',
                            'nouveau'
                        )
                    ) AS en_attente

                FROM signalements
            ";

            $stmt = $db->query($sqlSignalements);

            $resultatSignalements =
                $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            $stats['total_signalements'] =
                (int) ($resultatSignalements['total'] ?? 0);

            $stats['signalements_en_attente'] =
                (int) ($resultatSignalements['en_attente'] ?? 0);

            /*
             * 5. SIX ACTIVITÉS LES PLUS RÉCENTES
             */
            $sqlActivites = "
                SELECT type, description, created_at
                FROM (
                    SELECT
                        'bien' AS type,
                        COALESCE(
                            NULLIF(BTRIM(titre), ''),
                            'Bien immobilier sans titre'
                        ) AS description,
                        created_at
                    FROM biens

                    UNION ALL

                    SELECT
                        'utilisateur' AS type,
                        COALESCE(
                            NULLIF(
                                BTRIM(
                                    CONCAT_WS(' ', prenom, nom)
                                ),
                                ''
                            ),
                            'Utilisateur sans nom'
                        ) AS description,
                        created_at
                    FROM users

                    UNION ALL

                    SELECT
                        'signalement' AS type,
                        COALESCE(
                            NULLIF(BTRIM(b.titre), ''),
                            NULLIF(BTRIM(s.motif), ''),
                            'Signalement sans description'
                        ) AS description,
                        s.created_at
                    FROM signalements s
                    LEFT JOIN biens b ON b.id = s.bien_id
                ) AS activites

                ORDER BY created_at DESC NULLS LAST
                LIMIT 6
            ";

            $stmt = $db->query($sqlActivites);

            $activitesRecentes =
                $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Throwable $e) {
            error_log(
                'Erreur dashboard administrateur IMMO-SN : '
                . $e->getMessage()
            );
        }

        /*
         * 6. AFFICHAGE DU DASHBOARD
         */
        $this->view('pages/admin/dashboard', [
            'title' => 'Tableau de bord — Administration IMMO-SN',
            'stats' => $stats,
            'activitesRecentes' => $activitesRecentes,
        ]);
    }

    /**
     * Afficher toutes les agences partenaires dans l'administration.
     */
    public function agences(): void
    {
        $this->verifierAdmin();

        try {
            $agences = Agence::toutesPourAdmin();

            $this->view('pages/admin/agences', [
                'title' => 'Gestion des agences partenaires',
                'agences' => $agences,
                'messageSucces' => Session::getFlash('success'),
                'messageErreur' => Session::getFlash('error'),
            ]);

        } catch (\Throwable $e) {
            error_log(
                'Erreur chargement agences admin : ' . $e->getMessage()
            );

            http_response_code(500);
            echo 'Une erreur est survenue lors du chargement des agences.';
        }
    }

    /**
     * Afficher les détails complets d'une agence partenaire.
     */
    public function detailsAgence(string $id): void
    {
        $this->verifierAdmin();

        if (!$this->isValidUuid($id)) {
            http_response_code(400);
            echo 'Identifiant de l’agence invalide.';
            return;
        }

        try {
            $agence = Agence::trouverDetailsPourAdmin($id);

            if (!$agence) {
                http_response_code(404);
                echo 'Agence introuvable.';
                return;
            }

            $parPage = 3;

            $page = filter_input(
                INPUT_GET,
                'page',
                FILTER_VALIDATE_INT
            );

            $page = max(1, $page ?: 1);

            $totalBiens = Bien::agenceCompterParUtilisateur(
                (string) $agence['user_id']
            );

            $totalPages = max(
                1,
                (int) ceil($totalBiens / $parPage)
            );

            $page = min($page, $totalPages);

            $offset = ($page - 1) * $parPage;

            $biens = Bien::agenceListerParUtilisateur(
                (string) $agence['user_id'],
                $parPage,
                $offset
            );

            $this->view('pages/admin/details-agence', [
                'title' => 'Détails de l’agence — Administration IMMO-SN',
                'agence' => $agence,
                'biens' => $biens,
                'page' => $page,
                'totalPages' => $totalPages,
                'totalBiens' => $totalBiens,
            ]);

        } catch (\Throwable $e) {
            error_log(
                'Erreur affichage détails agence : ' . $e->getMessage()
            );

            http_response_code(500);
            echo 'Impossible de charger les détails de cette agence.';
        }
    }

    /**
     * Approuver une agence en attente.
     */
    public function approuverAgence(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/agences');
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant de l’agence invalide.'
                );
            }

            $agence = Agence::trouverPourAdmin($id);

            if (!$agence || $agence['statut'] !== 'en_attente') {
                throw new \RuntimeException(
                    'Agence introuvable ou impossible à approuver.'
                );
            }

            if (!Agence::changerStatut($id, 'actif')) {
                throw new \RuntimeException(
                    'Impossible d’approuver cette agence.'
                );
            }

            Session::flash(
                'success',
                'Agence approuvée avec succès.'
            );

        } catch (\Throwable $e) {
            error_log('Erreur approbation agence : ' . $e->getMessage());

            Session::flash(
                'error',
                'Une erreur est survenue lors de l’approbation.'
            );
        }

        header('Location: /immo-sn/admin/agences');
        exit;
    }

    /**
     * Rejeter une agence en attente.
     */
    public function rejeterAgence(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/agences');
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant de l’agence invalide.'
                );
            }

            $agence = Agence::trouverPourAdmin($id);

            if (!$agence || $agence['statut'] !== 'en_attente') {
                throw new \RuntimeException(
                    'Agence introuvable ou impossible à rejeter.'
                );
            }

            if (!Agence::changerStatut($id, 'refusee')) {
                throw new \RuntimeException(
                    'Impossible de rejeter cette agence.'
                );
            }

            Session::flash(
                'success',
                'Agence rejetée avec succès.'
            );

        } catch (\Throwable $e) {
            error_log('Erreur rejet agence : ' . $e->getMessage());

            Session::flash(
                'error',
                'Une erreur est survenue lors du rejet.'
            );
        }

        header('Location: /immo-sn/admin/agences');
        exit;
    }

    /**
     * Suspendre une agence active.
     */
    public function suspendreAgence(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/agences');
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant de l’agence invalide.'
                );
            }

            $agence = Agence::trouverPourAdmin($id);

            if (!$agence || $agence['statut'] !== 'actif') {
                throw new \RuntimeException(
                    'Agence introuvable ou impossible à suspendre.'
                );
            }

            if (!Agence::changerStatut($id, 'suspendue')) {
                throw new \RuntimeException(
                    'Impossible de suspendre cette agence.'
                );
            }

            Session::flash(
                'success',
                'Agence suspendue avec succès.'
            );

        } catch (\Throwable $e) {
            error_log('Erreur suspension agence : ' . $e->getMessage());

            Session::flash(
                'error',
                'Une erreur est survenue lors de la suspension.'
            );
        }

        header('Location: /immo-sn/admin/agences');
        exit;
    }

    /**
     * Réactiver une agence suspendue.
     */
    public function reactiverAgence(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/agences');
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant de l’agence invalide.'
                );
            }

            $agence = Agence::trouverPourAdmin($id);

            if (!$agence || $agence['statut'] !== 'suspendue') {
                throw new \RuntimeException(
                    'Agence introuvable ou impossible à réactiver.'
                );
            }

            if (!Agence::changerStatut($id, 'actif')) {
                throw new \RuntimeException(
                    'Impossible de réactiver cette agence.'
                );
            }

            Session::flash(
                'success',
                'Agence réactivée avec succès.'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur réactivation agence : ' . $e->getMessage()
            );

            Session::flash(
                'error',
                'Une erreur est survenue lors de la réactivation.'
            );
        }

        header('Location: /immo-sn/admin/agences');
        exit;
    }

    /**
     * Modifier le badge de vérification d'une agence.
     */
    public function modifierBadgeAgence(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/agences');
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant de l’agence invalide.'
                );
            }

            $agence = Agence::trouverPourAdmin($id);

            if (!$agence) {
                throw new \RuntimeException('Agence introuvable.');
            }

            $badgeActuel = filter_var(
                $agence['badge_verifie'],
                FILTER_VALIDATE_BOOLEAN
            );

            if (!Agence::changerBadge($id, !$badgeActuel)) {
                throw new \RuntimeException(
                    'Impossible de modifier le badge.'
                );
            }

            Session::flash(
                'success',
                'Badge de vérification mis à jour.'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur modification badge agence : ' . $e->getMessage()
            );

            Session::flash(
                'error',
                'Une erreur est survenue lors de la modification du badge.'
            );
        }

        header('Location: /immo-sn/admin/agences');
        exit;
    }

    /**
     * Supprimer définitivement une agence refusée.
     * Le compte utilisateur associé est conservé.
     */
    public function supprimerAgence(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/agences');
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant de l’agence invalide.'
                );
            }

            $agence = Agence::trouverPourAdmin($id);

            if (!$agence || $agence['statut'] !== 'refusee') {
                throw new \RuntimeException(
                    'Seules les agences refusées peuvent être supprimées.'
                );
            }

            if (!Agence::supprimerRefusee($id)) {
                throw new \RuntimeException(
                    'La suppression de cette agence a échoué.'
                );
            }

            Session::flash(
                'success',
                'Agence refusée supprimée avec succès.'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur suppression agence ' . $id . ' : '
                . $e->getMessage()
            );

            Session::flash(
                'error',
                'Impossible de supprimer cette agence.'
            );
        }

        header('Location: /immo-sn/admin/agences');
        exit;
    }

    /**
     * Gestion des biens immobiliers.
     */
    public function biens(): void
    {
        $this->verifierModerateurOuAdmin();

        $filtres = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'statut' => trim((string) ($_GET['statut'] ?? '')),
            'category_id' => trim(
                (string) ($_GET['category_id'] ?? '')
            ),
        ];

        $stats = [
            'total' => 0,
            'actifs' => 0,
            'attente' => 0,
            'rejetes' => 0,
            'expires' => 0,
            'suspendus' => 0,
        ];

        try {
            $resultat = Bien::adminStatistiques();

            $stats['total'] =
                $resultat['total_biens'] ?? 0;

            $stats['actifs'] =
                $resultat['biens_actifs'] ?? 0;

            $stats['attente'] =
                $resultat['biens_en_attente'] ?? 0;

            $stats['rejetes'] =
                $resultat['biens_rejetes'] ?? 0;

            $stats['expires'] =
                $resultat['biens_expires'] ?? 0;

            $stats['suspendus'] =
                $resultat['biens_suspendus'] ?? 0;

        } catch (\Throwable $e) {
            error_log(
                'Erreur statistiques des biens IMMO-SN : '
                . $e->getMessage()
            );
        }

        $totalBiens = Bien::adminCompter($filtres);

        $parPage = 3;
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $totalPages = max(
            1,
            (int) ceil($totalBiens / $parPage)
        );

        $page = min($page, $totalPages);
        $offset = ($page - 1) * $parPage;

        $biens = Bien::adminLister(
            $filtres,
            $parPage,
            $offset
        );

        $this->view('pages/admin/biens', [
            'title' => 'Gestion des biens — IMMO-SN',
            'biens' => $biens,
            'filtres' => $filtres,
            'stats' => $stats,
            'categories' => Bien::categories(),
            'page' => $page,
            'parPage' => $parPage,
            'totalBiens' => $totalBiens,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Approuver un bien en attente.
     */
    public function approuverBien(string $id): void
    {
        $this->verifierModerateurOuAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /immo-sn/admin/biens');
            exit;
        }

        try {
            $approuve = Bien::adminApprouver($id);

            $message = $approuve
                ? 'Bien approuvé avec succès.'
                : 'Impossible d’approuver ce bien. Vérifiez son statut.';

            $type = $approuve ? 'succes' : 'erreur';

            if ($approuve) {
                try {
                    $bien = Bien::detail($id);

                    if ($bien !== null) {
                        $email = trim(
                            (string) ($bien['proprio_email'] ?? '')
                        );

                        // Validation centralisée de l'adresse e-mail.
                        if (Validator::email($email)) {
                            $prenom = trim(
                                (string) ($bien['proprio_prenom'] ?? '')
                            );

                            $titreBien = trim(
                                (string) ($bien['titre'] ?? '')
                            );

                            if ($prenom === '') {
                                $prenom = 'cher membre';
                            }

                            if ($titreBien === '') {
                                $titreBien = 'Votre annonce';
                            }

                            // Récupération de l'URL depuis la configuration centrale.
                            $appConfig = require BASE_PATH . '/config/app.php';
                            $baseUrl = $appConfig['url'];

                            $lienBien = $baseUrl
                                . '/biens/'
                                . rawurlencode($id);

                            $userId = isset($bien['user_id'])
                                ? (string) $bien['user_id']
                                : null;

                            $mailer = new Mailer();

                            $mailer->sendTemplate(
                                $email,
                                'Votre annonce est validée - IMMO-SN',
                                'bien-valide',
                                [
                                    'prenom' => $prenom,
                                    'titre_bien' => $titreBien,
                                    'lien_bien' => $lienBien,
                                ],
                                $userId,
                                "Félicitations {$prenom} !\n\n"
                                    . "Votre annonce « {$titreBien} » a été approuvée "
                                    . "et peut désormais être consultée sur IMMO-SN.\n\n"
                                    . "Voir votre annonce : {$lienBien}\n\n"
                                    . "L'équipe IMMO-SN"
                            );
                        } else {
                            error_log(
                                '[IMMO-SN BIEN VALIDE] Adresse e-mail invalide pour le bien : '
                                . $id
                            );
                        }
                    } else {
                        error_log(
                            '[IMMO-SN BIEN VALIDE] Bien introuvable après approbation : '
                            . $id
                        );
                    }
                } catch (\Throwable $mailError) {
                    error_log(
                        '[IMMO-SN BIEN VALIDE] Échec de l’envoi : '
                        . $mailError->getMessage()
                    );
                }
            }
        } catch (\Throwable $e) {
            error_log(
                'Erreur approbation bien IMMO-SN : ' . $e->getMessage()
            );

            $message = 'Une erreur est survenue lors de l’approbation.';
            $type = 'erreur';
        }

        header(
            'Location: /immo-sn/admin/biens?message='
            . rawurlencode($message)
            . '&type=' . $type
        );
        exit;
    }

    /**
     * Rejeter un bien avec un motif obligatoire.
     */
    public function rejeterBien(string $id): void
    {
        $this->verifierModerateurOuAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /immo-sn/admin/biens');
            exit;
        }

        $motif = trim((string) ($_POST['motif_rejet'] ?? ''));

        if ($motif === '') {
            header(
                'Location: /immo-sn/admin/biens?message='
                . rawurlencode('Le motif du rejet est obligatoire.')
                . '&type=erreur'
            );
            exit;
        }

        try {
            $rejete = Bien::adminRejeter($id, $motif);

            $message = $rejete
                ? 'Bien rejeté. Le motif a été enregistré.'
                : 'Impossible de rejeter ce bien. Vérifiez son statut.';

            $type = $rejete ? 'succes' : 'erreur';

        } catch (\Throwable $e) {
            error_log('Erreur rejet bien IMMO-SN : ' . $e->getMessage());

            $message = 'Une erreur est survenue lors du rejet.';
            $type = 'erreur';
        }

        header(
            'Location: /immo-sn/admin/biens?message='
            . rawurlencode($message)
            . '&type=' . $type
        );
        exit;
    }

    /**
     * Gestion paginée des signalements.
     */
    public function signalements(): void
    {
        $this->verifierAdmin();

        $filtres = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'statut' => trim((string) ($_GET['statut'] ?? '')),
        ];

        $statsSignalements = [
            'total' => 0,
            'en_attente' => 0,
            'traites' => 0,
            'rejetes' => 0,
        ];

        try {
            $statsSignalements = Signalement::adminStatistiques();
        } catch (\Throwable $e) {
            error_log(
                'Erreur statistiques signalements IMMO-SN : '
                . $e->getMessage()
            );
        }

        $parPage = 6;
        $page = max(1, (int) ($_GET['page'] ?? 1));

        try {
            $totalSignalements = Signalement::adminCompter($filtres);

            $totalPages = max(
                1,
                (int) ceil($totalSignalements / $parPage)
            );

            $page = min($page, $totalPages);

            $offset = ($page - 1) * $parPage;

            $signalements = Signalement::adminLister(
                $filtres,
                $parPage,
                $offset
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur pagination signalements IMMO-SN : '
                . $e->getMessage()
            );

            $signalements = [];
            $totalSignalements = 0;
            $totalPages = 1;
            $page = 1;
        }

        $this->view('pages/admin/signalements', [
            'title' => 'Signalements — Administration IMMO-SN',
            'signalements' => $signalements,
            'filtres' => $filtres,
            'page' => $page,
            'parPage' => $parPage,
            'totalSignalements' => $totalSignalements,
            'totalPages' => $totalPages,
            'statsSignalements' => $statsSignalements,
        ]);
    }

    /**
     * Marquer un signalement comme traité.
     */
    public function traiterSignalement(string $id): void
    {
        $this->verifierAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /immo-sn/admin/signalements');
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant du signalement invalide.'
                );
            }

            $traite = Signalement::marquerTraite($id);

            $message = $traite
                ? 'Signalement marqué comme traité avec succès.'
                : 'Signalement introuvable ou déjà traité.';

            $type = $traite ? 'succes' : 'erreur';

        } catch (\Throwable $e) {
            error_log(
                'Erreur traitement signalement IMMO-SN : '
                . $e->getMessage()
            );

            $message = 'Une erreur est survenue lors du traitement.';
            $type = 'erreur';
        }

        header(
            'Location: /immo-sn/admin/signalements?message='
            . rawurlencode($message)
            . '&type=' . $type
        );
        exit;
    }

    /**
     * Rejeter un signalement.
     */
    public function rejeterSignalement(string $id): void
    {
        $this->verifierAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /immo-sn/admin/signalements');
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant du signalement invalide.'
                );
            }

            $rejete = Signalement::rejeter($id);

            $message = $rejete
                ? 'Signalement rejeté avec succès.'
                : 'Signalement introuvable ou déjà rejeté.';

            $type = $rejete ? 'succes' : 'erreur';

        } catch (\Throwable $e) {
            error_log(
                'Erreur rejet signalement IMMO-SN : '
                . $e->getMessage()
            );

            $message = 'Une erreur est survenue lors du rejet.';
            $type = 'erreur';
        }

        header(
            'Location: /immo-sn/admin/signalements?message='
            . rawurlencode($message)
            . '&type=' . $type
        );
        exit;
    }

    /**
     * Supprimer logiquement un signalement traité ou rejeté.
     */
    public function supprimerSignalement(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/signalements');
            exit;
        }

        if (!$this->isValidUuid($id)) {
            header(
                'Location: /immo-sn/admin/signalements'
                . '?message=' . urlencode('Identifiant de signalement invalide.')
                . '&type=erreur'
            );
            exit;
        }

        try {
            $supprime = Signalement::supprimer($id);

            if ($supprime) {
                $message = 'Le signalement a été archivé avec succès.';
                $type = 'succes';
            } else {
                $message = 'Suppression impossible : signalement introuvable, déjà archivé ou encore en attente.';
                $type = 'erreur';
            }

        } catch (\Throwable $e) {
            error_log(
                'Erreur archivage signalement ' . $id . ' : '
                . $e->getMessage()
            );

            $message = 'Une erreur est survenue pendant l’archivage du signalement.';
            $type = 'erreur';
        }

        header(
            'Location: /immo-sn/admin/signalements'
            . '?message=' . urlencode($message)
            . '&type=' . urlencode($type)
        );
        exit;
    }

    /**
     * Vérifier le format UUID.
     */
    private function isValidUuid(string $uuid): bool
    {
        return (bool) preg_match(
            '/^[0-9a-fA-F]{8}-'
            . '[0-9a-fA-F]{4}-'
            . '[1-8][0-9a-fA-F]{3}-'
            . '[89abAB][0-9a-fA-F]{3}-'
            . '[0-9a-fA-F]{12}$/',
            $uuid
        );
    }

    /**
     * Vérifier le jeton CSRF des formulaires.
     */
    private function verifierJetonCsrf(): bool
    {
        return Session::verifyCsrfToken(
            $_POST['_csrf_token'] ?? null
        );
    }

    /**
     * Enregistrer une décision dans le journal d'audit.
     */
    private function enregistrerDecision(
        string $action,
        string $type,
        string $cibleId,
        array $details = []
    ): bool {
        $userId = Session::userId();

        if ($userId === null || $userId === '') {
            error_log(
                'Historique impossible : utilisateur non identifié.'
            );
            return false;
        }

        try {
            return AuditLog::enregistrer(
                $userId,
                $action,
                array_merge(
                    [
                        'type' => $type,
                        'cible_id' => $cibleId,
                    ],
                    $details
                )
            );
        } catch (\Throwable $e) {
            error_log(
                'Erreur enregistrement historique : '
                . $e->getMessage()
            );
            return false;
        }
    }

    /**
     * Afficher les demandes de renouvellement.
     */
    public function renouvellements(): void
    {
        $this->verifierAdmin();

        try {
            $demandes = DemandeRenouvellement::listerEnAttente();

            $this->view(
                'pages/admin/renouvellements',
                [
                    'title' => 'Demandes de renouvellement — IMMO-SN',
                    'demandes' => $demandes,
                ],
                'app'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur chargement renouvellements administrateur : '
                . $e->getMessage()
            );

            http_response_code(500);
            echo 'Impossible de charger les demandes de renouvellement.';
        }
    }

    /**
     * Approuver une demande de renouvellement.
     */
    public function approuverRenouvellement(string $id): void
    {
        $this->verifierAdmin();

        $destination = '/immo-sn/admin/renouvellements';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::flash(
                'error',
                'Méthode de requête non autorisée.'
            );
            header('Location: ' . $destination);
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            Session::flash(
                'error',
                'Votre session a expiré. Veuillez réessayer.'
            );
            header('Location: ' . $destination);
            exit;
        }

        if (!$this->isValidUuid($id)) {
            Session::flash(
                'error',
                'Identifiant de demande invalide.'
            );
            header('Location: ' . $destination);
            exit;
        }

        $userId = Session::userId();

        if ($userId === null || !$this->isValidUuid($userId)) {
            Session::flash(
                'error',
                'Administrateur non identifié.'
            );
            header('Location: ' . $destination);
            exit;
        }

        try {
            DemandeRenouvellement::approuver($id, $userId);

            $this->enregistrerDecision(
                'renouvellement_approuve',
                'renouvellement',
                $id
            );

            Session::flash(
                'success',
                'La demande a été approuvée. L’annonce est renouvelée pour 30 jours.'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur approbation renouvellement administrateur '
                . $id . ' : ' . $e->getMessage()
            );

            Session::flash(
                'error',
                'Impossible d’approuver cette demande de renouvellement.'
            );
        }

        header('Location: ' . $destination);
        exit;
    }

    /**
     * Refuser une demande de renouvellement.
     */
    public function refuserRenouvellement(string $id): void
    {
        $this->verifierAdmin();

        $destination = '/immo-sn/admin/renouvellements';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::flash(
                'error',
                'Méthode de requête non autorisée.'
            );
            header('Location: ' . $destination);
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            Session::flash(
                'error',
                'Votre session a expiré. Veuillez réessayer.'
            );
            header('Location: ' . $destination);
            exit;
        }

        if (!$this->isValidUuid($id)) {
            Session::flash(
                'error',
                'Identifiant de demande invalide.'
            );
            header('Location: ' . $destination);
            exit;
        }

        $motif = trim(
            (string) ($_POST['motif_refus'] ?? '')
        );

        if ($motif === '') {
            Session::flash(
                'error',
                'Veuillez renseigner le motif du refus.'
            );
            header('Location: ' . $destination);
            exit;
        }

        $userId = Session::userId();

        if ($userId === null || !$this->isValidUuid($userId)) {
            Session::flash(
                'error',
                'Administrateur non identifié.'
            );
            header('Location: ' . $destination);
            exit;
        }

        try {
            DemandeRenouvellement::refuser(
                $id,
                $userId,
                $motif
            );

            $this->enregistrerDecision(
                'renouvellement_refuse',
                'renouvellement',
                $id,
                ['motif_refus' => $motif]
            );

            Session::flash(
                'success',
                'La demande de renouvellement a été refusée.'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur refus renouvellement administrateur '
                . $id . ' : ' . $e->getMessage()
            );

            Session::flash(
                'error',
                'Impossible de refuser cette demande de renouvellement.'
            );
        }

        header('Location: ' . $destination);
        exit;
    }

    /**
     * Surveillance des tâches CRON.
     */
    public function cronMonitor(): void
    {
        $this->verifierAdmin();

        $cronLogs = [];

        $statsCron = [
            'total' => 0,
            'succes' => 0,
            'erreurs' => 0,
        ];

        $erreurCron = null;

        $recherche = isset($_GET['q']) && is_string($_GET['q'])
            ? trim($_GET['q'])
            : '';

        $statut = isset($_GET['statut']) && is_string($_GET['statut'])
            ? trim($_GET['statut'])
            : '';

        $statutsAutorises = [
            'success',
            'error',
            'running',
            'unknown',
        ];

        if (!in_array($statut, $statutsAutorises, true)) {
            $statut = '';
        }

        $optionsParPage = [8, 16, 32];

        $parPageDemande = isset($_GET['par_page'])
            && is_scalar($_GET['par_page'])
            ? (int) $_GET['par_page']
            : 8;

        $parPage = in_array(
            $parPageDemande,
            $optionsParPage,
            true
        ) ? $parPageDemande : 8;

        $page = isset($_GET['page']) && is_scalar($_GET['page'])
            ? max(1, (int) $_GET['page'])
            : 1;

        $totalLogs = 0;
        $totalPages = 1;

        try {
            $db = Database::getConnection();

            $sqlStats = "
                SELECT
                    COUNT(*) AS total,

                    COUNT(*) FILTER (
                        WHERE LOWER(TRIM(COALESCE(status, '')))
                        IN ('success', 'succes', 'succès')
                    ) AS succes,

                    COUNT(*) FILTER (
                        WHERE LOWER(TRIM(COALESCE(status, '')))
                        IN ('error', 'erreur', 'failed', 'failure')
                    ) AS erreurs

                FROM public.cron_logs
            ";

            $resultatStats = $db->query($sqlStats)->fetch(PDO::FETCH_ASSOC);

            if ($resultatStats) {
                $statsCron = [
                    'total' => (int) ($resultatStats['total'] ?? 0),
                    'succes' => (int) ($resultatStats['succes'] ?? 0),
                    'erreurs' => (int) ($resultatStats['erreurs'] ?? 0),
                ];
            }

            $conditions = [];
            $params = [];

            if ($recherche !== '') {
                $conditions[] = 'script ILIKE :recherche';
                $params[':recherche'] = '%' . $recherche . '%';
            }

            if ($statut === 'success') {
                $conditions[] = "
                    LOWER(TRIM(COALESCE(status, '')))
                    IN ('success', 'succes', 'succès')
                ";
            } elseif ($statut === 'error') {
                $conditions[] = "
                    LOWER(TRIM(COALESCE(status, '')))
                    IN ('error', 'erreur', 'failed', 'failure')
                ";
            } elseif ($statut === 'running') {
                $conditions[] = "
                    LOWER(TRIM(COALESCE(status, '')))
                    IN ('running', 'en_cours', 'en cours')
                ";
            } elseif ($statut === 'unknown') {
                $conditions[] = "
                    LOWER(TRIM(COALESCE(status, '')))
                    NOT IN (
                        'success', 'succes', 'succès',
                        'error', 'erreur', 'failed', 'failure',
                        'running', 'en_cours', 'en cours'
                    )
                ";
            }

            $where = $conditions
                ? ' WHERE ' . implode(' AND ', $conditions)
                : '';

            $sqlCount = "
                SELECT COUNT(*)
                FROM public.cron_logs
                $where
            ";

            $stmtCount = $db->prepare($sqlCount);
            $stmtCount->execute($params);

            $totalLogs = (int) $stmtCount->fetchColumn();

            $totalPages = max(
                1,
                (int) ceil($totalLogs / $parPage)
            );

            $page = min($page, $totalPages);
            $offset = ($page - 1) * $parPage;

            $sqlLogs = "
                SELECT
                    id,
                    script,
                    status,
                    items_processed,
                    duration_ms,
                    error_message,
                    executed_at
                FROM public.cron_logs
                $where
                ORDER BY executed_at DESC NULLS LAST
                LIMIT :limite OFFSET :decalage
            ";

            $stmtLogs = $db->prepare($sqlLogs);

            foreach ($params as $nomParam => $valeurParam) {
                $stmtLogs->bindValue(
                    $nomParam,
                    $valeurParam,
                    PDO::PARAM_STR
                );
            }

            $stmtLogs->bindValue(
                ':limite',
                $parPage,
                PDO::PARAM_INT
            );

            $stmtLogs->bindValue(
                ':decalage',
                $offset,
                PDO::PARAM_INT
            );

            $stmtLogs->execute();

            $cronLogs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Throwable $e) {
            error_log(
                'Erreur Cron Monitor IMMO-SN : '
                . $e->getMessage()
            );

            $erreurCron =
                'Impossible de charger les journaux des tâches CRON.';
        }

        $this->view('pages/admin/cron-monitor', [
            'title' => 'Cron Monitor — IMMO-SN',
            'cronLogs' => $cronLogs,
            'statsCron' => $statsCron,
            'erreurCron' => $erreurCron,
            'recherche' => $recherche,
            'statut' => $statut,
            'page' => $page,
            'parPage' => $parPage,
            'totalLogs' => $totalLogs,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Gestion des journaux d'e-mails.
     */
    public function mailLogs(): void
    {
        $this->verifierAdmin();

        $mailLogs = [];
        $erreurMail = null;

        $statsMail = [
            'total' => 0,
            'envoyes' => 0,
            'echecs' => 0,
            'aujourd_hui' => 0,
        ];

        $recherche = isset($_GET['q']) && is_string($_GET['q'])
            ? trim($_GET['q'])
            : '';

        $statut = isset($_GET['statut']) && is_string($_GET['statut'])
            ? trim($_GET['statut'])
            : '';

        $statutsAutorises = [
            '',
            'envoye',
            'envoyé',
            'echec',
            'échec',
        ];

        if (!in_array($statut, $statutsAutorises, true)) {
            $statut = '';
        }

        $parPage = 5;

        $page = isset($_GET['page']) && is_scalar($_GET['page'])
            ? max(1, (int) $_GET['page'])
            : 1;

        $totalLogs = 0;
        $totalPages = 1;

        try {
            $db = Database::getConnection();

            $sqlStats = "
                SELECT
                    COUNT(*) AS total,

                    COUNT(*) FILTER (
                        WHERE LOWER(TRIM(COALESCE(status, '')))
                        IN (
                            'envoye', 'envoyé',
                            'sent', 'success', 'succes', 'succès'
                        )
                    ) AS envoyes,

                    COUNT(*) FILTER (
                        WHERE LOWER(TRIM(COALESCE(status, '')))
                        IN (
                            'echec', 'échec',
                            'error', 'erreur', 'failed', 'failure'
                        )
                    ) AS echecs,

                    COUNT(*) FILTER (
                        WHERE sent_at::date = CURRENT_DATE
                    ) AS aujourd_hui

                FROM public.mail_logs
            ";

            $resultatStats = $db->query($sqlStats)->fetch(PDO::FETCH_ASSOC);

            if ($resultatStats) {
                $statsMail = [
                    'total' => (int) ($resultatStats['total'] ?? 0),
                    'envoyes' => (int) ($resultatStats['envoyes'] ?? 0),
                    'echecs' => (int) ($resultatStats['echecs'] ?? 0),
                    'aujourd_hui' => (int) ($resultatStats['aujourd_hui'] ?? 0),
                ];
            }

            $conditions = [];
            $params = [];

            if ($recherche !== '') {
                $conditions[] = "(
                    email_to ILIKE :recherche
                    OR COALESCE(subject, '') ILIKE :recherche
                    OR COALESCE(template, '') ILIKE :recherche
                )";

                $params[':recherche'] = '%' . $recherche . '%';
            }

            if (in_array($statut, ['envoye', 'envoyé'], true)) {
                $conditions[] = "
                    LOWER(TRIM(COALESCE(status, '')))
                    IN (
                        'envoye', 'envoyé',
                        'sent', 'success', 'succes', 'succès'
                    )
                ";
            } elseif (in_array($statut, ['echec', 'échec'], true)) {
                $conditions[] = "
                    LOWER(TRIM(COALESCE(status, '')))
                    IN (
                        'echec', 'échec',
                        'error', 'erreur', 'failed', 'failure'
                    )
                ";
            }

            $where = $conditions
                ? ' WHERE ' . implode(' AND ', $conditions)
                : '';

            $stmtCount = $db->prepare("
                SELECT COUNT(*)
                FROM public.mail_logs
                $where
            ");

            $stmtCount->execute($params);

            $totalLogs = (int) $stmtCount->fetchColumn();

            $totalPages = max(
                1,
                (int) ceil($totalLogs / $parPage)
            );

            $page = min($page, $totalPages);
            $offset = ($page - 1) * $parPage;

            $sqlLogs = "
                SELECT
                    id,
                    user_id,
                    email_to,
                    subject,
                    template,
                    status,
                    error,
                    sent_at
                FROM public.mail_logs
                $where
                ORDER BY sent_at DESC NULLS LAST, id DESC
                LIMIT :limite OFFSET :decalage
            ";

            $stmtLogs = $db->prepare($sqlLogs);

            foreach ($params as $nomParam => $valeurParam) {
                $stmtLogs->bindValue(
                    $nomParam,
                    $valeurParam,
                    PDO::PARAM_STR
                );
            }

            $stmtLogs->bindValue(
                ':limite',
                $parPage,
                PDO::PARAM_INT
            );

            $stmtLogs->bindValue(
                ':decalage',
                $offset,
                PDO::PARAM_INT
            );

            $stmtLogs->execute();

            $mailLogs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Throwable $e) {
            error_log(
                'Erreur Mail Logs IMMO-SN : ' . $e->getMessage()
            );

            $erreurMail =
                'Impossible de charger les journaux des emails. Vérifiez la structure de la table mail_logs.';
        }

        $this->view('pages/admin/mail-logs', [
            'title' => 'Mail Logs — IMMO-SN',
            'stats' => $statsMail,
            'logs' => $mailLogs,
            'recherche' => $recherche,
            'statut' => $statut,
            'page' => $page,
            'parPage' => $parPage,
            'totalLogs' => $totalLogs,
            'totalPages' => $totalPages,
            'erreurMailLogs' => $erreurMail,
        ]);
    }

    /**
     * Gestion des comptes utilisateurs.
     */
    public function utilisateurs(): void
    {
        $this->verifierAdmin();

        $utilisateurs = [];
        $erreurUtilisateurs = null;

        $recherche = isset($_GET['q']) && is_string($_GET['q'])
            ? trim($_GET['q'])
            : '';

        $role = isset($_GET['role']) && is_string($_GET['role'])
            ? trim($_GET['role'])
            : '';

        $statut = isset($_GET['statut']) && is_string($_GET['statut'])
            ? trim($_GET['statut'])
            : '';

        $pageDemandee = filter_input(
            INPUT_GET,
            'page',
            FILTER_VALIDATE_INT
        );

        $page = is_int($pageDemandee) && $pageDemandee > 0
            ? $pageDemandee
            : 1;

        $limite = 6;
        $totalPages = 1;

        $statistiques = [
            'totalUtilisateurs' => 0,
            'totalAdmins' => 0,
            'totalAgences' => 0,
            'totalParticuliers' => 0,
            'totalModerateurs' => 0,
            'totalSuspendus' => 0,
        ];

        try {
            $statistiques = User::statistiquesAdministration();

            $resultat = User::tousPourAdministrationPagines(
                $recherche,
                $role,
                $statut,
                $limite,
                $page
            );

            $totalPages = $resultat['totalPages'];

            if ($page > $totalPages) {
                $page = $totalPages;

                $resultat = User::tousPourAdministrationPagines(
                    $recherche,
                    $role,
                    $statut,
                    $limite,
                    $page
                );

                $totalPages = $resultat['totalPages'];
            }

            $utilisateurs = $resultat['utilisateurs'];

        } catch (\Throwable $e) {
            error_log(
                'Erreur gestion utilisateurs : ' . $e->getMessage()
            );

            $erreurUtilisateurs =
                'Impossible de charger les utilisateurs pour le moment.';
        }

        $this->view('pages/admin/utilisateurs', [
            'utilisateurs' => $utilisateurs,
            'recherche' => $recherche,
            'roleSelectionne' => $role,
            'statutSelectionne' => $statut,
            'erreurUtilisateurs' => $erreurUtilisateurs,
            'page' => $page,
            'limite' => $limite,
            'totalPages' => $totalPages,
            'totalUtilisateurs' => $statistiques['totalUtilisateurs'],
            'totalAdmins' => $statistiques['totalAdmins'],
            'totalAgences' => $statistiques['totalAgences'],
            'totalParticuliers' => $statistiques['totalParticuliers'],
            'totalModerateurs' => $statistiques['totalModerateurs'],
            'totalSuspendus' => $statistiques['totalSuspendus'],
        ]);
    }

    /**
     * Modifie le rôle d'un utilisateur.
     */
    public function modifierRoleUtilisateur(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/utilisateurs');
            exit;
        }

        $role = isset($_POST['role']) && is_string($_POST['role'])
            ? strtolower(trim($_POST['role']))
            : '';

        $rolesAutorises = [
            'particulier',
            'agence',
            'moderateur',
        ];

        if (!in_array($role, $rolesAutorises, true)) {
            header('Location: /immo-sn/admin/utilisateurs?erreur=role_invalide');
            exit;
        }

        $idConnecte = Session::userId();

        if ($idConnecte !== null && $idConnecte === $id) {
            header('Location: /immo-sn/admin/utilisateurs?erreur=modification_personnelle');
            exit;
        }

        try {
            $utilisateur = User::findById($id);

            if ($utilisateur === null) {
                header('Location: /immo-sn/admin/utilisateurs?erreur=utilisateur_introuvable');
                exit;
            }

            if (
                strtolower(trim((string) ($utilisateur['role'] ?? ''))) === 'admin'
                && $role !== 'admin'
            ) {
                $db = Database::getConnection();

                $stmt = $db->query("
                    SELECT COUNT(*)
                    FROM users
                    WHERE LOWER(TRIM(role)) = 'admin'
                ");

                $nombreAdmins = (int) $stmt->fetchColumn();

                if ($nombreAdmins <= 1) {
                    header('Location: /immo-sn/admin/utilisateurs?erreur=dernier_admin');
                    exit;
                }
            }

            $modifie = User::modifierRole($id, $role);

            $resultat = $modifie ? 'role_modifie' : 'role_inchange';

            header('Location: /immo-sn/admin/utilisateurs?resultat=' . $resultat);
            exit;

        } catch (\Throwable $e) {
            error_log(
                'Erreur modification rôle utilisateur : ' . $e->getMessage()
            );

            header('Location: /immo-sn/admin/utilisateurs?erreur=modification_impossible');
            exit;
        }
    }

    /**
     * Affiche le formulaire ou enregistre les modifications
     * d'un utilisateur depuis l'administration.
     */
    public function modifierUtilisateur(string $id): void
    {
        $this->verifierAdmin();

        $estAjax = strtolower(
            (string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')
        ) === 'xmlhttprequest';

        $repondre = static function (
            bool $succes,
            string $message,
            int $code = 200
        ) use ($estAjax): void {
            if ($estAjax) {
                http_response_code($code);
                header('Content-Type: application/json; charset=utf-8');

                echo json_encode([
                    'success' => $succes,
                    'message' => $message,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                exit;
            }

            $parametre = $succes ? 'resultat' : 'erreur';

            $valeur = $succes
                ? 'utilisateur_modifie'
                : $message;

            header(
                'Location: /immo-sn/admin/utilisateurs?'
                . $parametre . '=' . rawurlencode($valeur)
            );

            exit;
        };

        if (!$this->isValidUuid($id)) {
            $repondre(false, 'utilisateur_invalide', 400);
        }

        try {
            $utilisateur = User::findById($id);

            if ($utilisateur === null) {
                $repondre(false, 'utilisateur_introuvable', 404);
            }

            if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
                $this->view('pages/admin/modifier-utilisateur', [
                    'utilisateur' => $utilisateur,
                ]);
                return;
            }

            $nom = trim((string) ($_POST['nom'] ?? ''));
            $prenom = trim((string) ($_POST['prenom'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $telephone = trim((string) ($_POST['telephone'] ?? ''));
            $ville = trim((string) ($_POST['ville'] ?? ''));
            $role = strtolower(trim((string) ($_POST['role'] ?? '')));
            $status = strtolower(trim((string) ($_POST['status'] ?? '')));

            if ($nom === '' || $prenom === '' || $email === '') {
                $repondre(
                    false,
                    'Les champs nom, prénom et e-mail sont obligatoires.',
                    422
                );
            }

            // Validation centralisée de l'adresse e-mail.
            if (!Validator::email($email)) {
                $repondre(false, 'Adresse e-mail invalide.', 422);
            }

            $idConnecte = Session::userId();

            if ($idConnecte !== null && $idConnecte === $id) {
                $role = (string) ($utilisateur['role'] ?? '');
                $status = (string) ($utilisateur['status'] ?? '');
            }

            $donnees = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'ville' => $ville,
                'role' => $role,
                'status' => $status,
            ];

            User::modifierInformations($id, $donnees);

            $repondre(true, 'Utilisateur modifié avec succès.');

        } catch (\InvalidArgumentException $e) {
            error_log(
                'Validation modification utilisateur : '
                . $e->getMessage()
            );

            $message = str_contains(
                strtolower($e->getMessage()),
                'email'
            )
                ? 'Cette adresse e-mail est déjà utilisée ou invalide.'
                : 'Les informations fournies ne sont pas valides.';

            $repondre(false, $message, 422);

        } catch (\Throwable $e) {
            error_log(
                'Erreur modification utilisateur : '
                . $e->getMessage()
            );

            $repondre(
                false,
                'Une erreur est survenue pendant la modification.',
                500
            );
        }
    }

    /**
     * Supprimer un utilisateur depuis l'administration.
     *
     * Les comptes administrateurs et les utilisateurs possédant
     * des biens ne peuvent pas être supprimés par cette action.
     */
    public function supprimerUtilisateur(string $id): void
    {
        $this->verifierAdmin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/admin/utilisateurs');
            exit;
        }

        if (!$this->isValidUuid($id)) {
            header(
                'Location: /immo-sn/admin/utilisateurs'
                . '?erreur=utilisateur_invalide'
            );
            exit;
        }

        $idConnecte = Session::userId();

        if ($idConnecte !== null && $idConnecte === $id) {
            header(
                'Location: /immo-sn/admin/utilisateurs'
                . '?erreur=suppression_compte_connecte'
            );
            exit;
        }

        $db = null;

        try {
            $db = Database::getConnection();
            $db->beginTransaction();

            $stmt = $db->prepare("
                SELECT id, role
                FROM users
                WHERE id = :id
                FOR UPDATE
            ");

            $stmt->execute([':id' => $id]);

            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$utilisateur) {
                $db->rollBack();

                header(
                    'Location: /immo-sn/admin/utilisateurs'
                    . '?erreur=utilisateur_introuvable'
                );
                exit;
            }

            if (
                strtolower(trim((string) $utilisateur['role'])) === 'admin'
            ) {
                $db->rollBack();

                header(
                    'Location: /immo-sn/admin/utilisateurs'
                    . '?erreur=suppression_admin_interdite'
                );
                exit;
            }

            $stmt = $db->prepare("
                SELECT COUNT(*)
                FROM biens
                WHERE user_id = :id
            ");

            $stmt->execute([':id' => $id]);

            if ((int) $stmt->fetchColumn() > 0) {
                $db->rollBack();

                header(
                    'Location: /immo-sn/admin/utilisateurs'
                    . '?erreur=utilisateur_possede_biens'
                );
                exit;
            }

            $stmt = $db->prepare("
                DELETE FROM users
                WHERE id = :id
            ");

            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() !== 1) {
                throw new \RuntimeException(
                    'La suppression du compte n’a pas abouti.'
                );
            }

            $db->commit();

            header(
                'Location: /immo-sn/admin/utilisateurs'
                . '?resultat=utilisateur_supprime'
            );
            exit;

        } catch (\Throwable $e) {
            if ($db instanceof PDO && $db->inTransaction()) {
                $db->rollBack();
            }

            error_log(
                'Erreur suppression utilisateur '
                . $id . ' : ' . $e->getMessage()
            );

            header(
                'Location: /immo-sn/admin/utilisateurs'
                . '?erreur=suppression_impossible'
            );
            exit;
        }
    }
}
