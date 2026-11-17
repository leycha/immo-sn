<?php
namespace App\Controllers;

use Core\Controller;
use Core\Session;
use Core\Validator;

use App\Models\Bien;
use App\Models\Signalement;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\DemandeRenouvellement;

class ModerateurController extends Controller
{
    /**
     * Vérifie que l'utilisateur connecté est bien modérateur.
     */
    private function verifierModerateur(): void
    {
        Session::requireModerator('/immo-sn/login');
    }

    /**
     * Vérifie le jeton CSRF envoyé par un formulaire.
     */
    private function verifierJetonCsrf(): bool
    {
        return Session::verifyCsrfToken(
            $_POST['_csrf_token'] ?? null
        );
    }

    /**
     * Vérifie le format UUID.
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
     * Enregistre une décision dans l'historique.
     *
     * Une erreur d'historique ne doit pas annuler
     * une décision déjà appliquée.
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
     * Tableau de bord du modérateur.
     */
    public function index(): void
    {
        $this->verifierModerateur();

        $statsBiens = Bien::adminStatistiques();

        $statsSignalements = Signalement::adminStatistiques();

        $stats = [
            'annonces_en_attente' =>
                $statsBiens['biens_en_attente'] ?? 0,

            'signalements_en_attente' =>
                $statsSignalements['en_attente'] ?? 0,

            'annonces_traitees' =>
                ($statsBiens['biens_actifs'] ?? 0)
                + ($statsBiens['biens_rejetes'] ?? 0),

            'signalements_traites' =>
                ($statsSignalements['traites'] ?? 0)
                + ($statsSignalements['rejetes'] ?? 0),
        ];

        $this->view(
            'pages/moderateur/index',
            [
                'stats' => $stats,
            ],
            'app'
        );
    }

    /**
     * Liste des annonces en attente de validation.
     */
    public function annonces(): void
    {
        $this->verifierModerateur();

        $filtres = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'category_id' => trim(
                (string) ($_GET['category_id'] ?? '')
            ),
        ];

        $parPage = 3;
        $page = max(1, (int) ($_GET['page'] ?? 1));

        try {
            $totalBiens = Bien::moderateurCompter($filtres);

            $totalPages = max(
                1,
                (int) ceil($totalBiens / $parPage)
            );

            $page = min($page, $totalPages);

            $offset = ($page - 1) * $parPage;

            $biens = Bien::moderateurLister(
                $filtres,
                $parPage,
                $offset
            );

            $this->view(
                'pages/moderateur/annonces',
                [
                    'title' => 'Validation des annonces — IMMO-SN',
                    'biens' => $biens,
                    'filtres' => $filtres,
                    'categories' => Bien::categories(),
                    'page' => $page,
                    'parPage' => $parPage,
                    'totalBiens' => $totalBiens,
                    'totalPages' => $totalPages,
                ],
                'app'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur chargement annonces modérateur : '
                . $e->getMessage()
            );

            http_response_code(500);

            echo 'Une erreur est survenue lors du chargement des annonces.';
        }
    }

    /**
     * Liste des demandes de renouvellement en attente.
     */
    public function renouvellements(): void
    {
        $this->verifierModerateur();

        try {
            $demandes = DemandeRenouvellement::listerEnAttente();

            $this->view(
                'pages/moderateur/renouvellements',
                [
                    'title' => 'Demandes de renouvellement — IMMO-SN',
                    'demandes' => $demandes,
                ],
                'app'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur chargement renouvellements modérateur : '
                . $e->getMessage()
            );

            http_response_code(500);

            echo 'Impossible de charger les demandes de renouvellement.';
        }
    }

    /**
     * Gestion paginée des signalements.
     */
    public function signalements(): void
    {
        $this->verifierModerateur();

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

        $signalements = [];
        $totalSignalements = 0;
        $totalPages = 1;
        $parPage = 6;

        $page = max(1, (int) ($_GET['page'] ?? 1));

        try {
            $statsSignalements = Signalement::adminStatistiques();

            $totalSignalements = Signalement::adminCompter(
                $filtres
            );

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
                'Erreur chargement signalements modérateur : '
                . $e->getMessage()
            );

            Session::flash(
                'error',
                'Impossible de charger les signalements pour le moment.'
            );
        }

        $this->view(
            'pages/moderateur/signalements',
            [
                'title' => 'Gestion des signalements — IMMO-SN',
                'signalements' => $signalements,
                'filtres' => $filtres,
                'page' => $page,
                'parPage' => $parPage,
                'totalSignalements' => $totalSignalements,
                'totalPages' => $totalPages,
                'statsSignalements' => $statsSignalements,
            ],
            'app'
        );
    }

    /**
     * Marquer un signalement comme traité.
     */
    public function traiterSignalement(string $id): void
    {
        $this->verifierModerateur();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/moderateur/signalements');
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            header(
                'Location: /immo-sn/moderateur/signalements?message='
                . rawurlencode(
                    'Votre session a expiré. Veuillez réessayer.'
                )
                . '&type=erreur'
            );
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant du signalement invalide.'
                );
            }

            $traite = Signalement::marquerTraite($id);

            if ($traite) {
                $this->enregistrerDecision(
                    'signalement_traite',
                    'signalement',
                    $id
                );
            }

            $message = $traite
                ? 'Signalement marqué comme traité avec succès.'
                : 'Signalement introuvable ou déjà traité.';

            $type = $traite ? 'succes' : 'erreur';

        } catch (\Throwable $e) {
            error_log(
                'Erreur traitement signalement modérateur : '
                . $e->getMessage()
            );

            $message = 'Une erreur est survenue lors du traitement.';
            $type = 'erreur';
        }

        header(
            'Location: /immo-sn/moderateur/signalements?message='
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
        $this->verifierModerateur();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/moderateur/signalements');
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            header(
                'Location: /immo-sn/moderateur/signalements?message='
                . rawurlencode(
                    'Votre session a expiré. Veuillez réessayer.'
                )
                . '&type=erreur'
            );
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant du signalement invalide.'
                );
            }

            $rejete = Signalement::rejeter($id);

            if ($rejete) {
                $this->enregistrerDecision(
                    'signalement_rejete',
                    'signalement',
                    $id
                );
            }

            $message = $rejete
                ? 'Signalement rejeté avec succès.'
                : 'Signalement introuvable ou déjà rejeté.';

            $type = $rejete ? 'succes' : 'erreur';

        } catch (\Throwable $e) {
            error_log(
                'Erreur rejet signalement modérateur : '
                . $e->getMessage()
            );

            $message = 'Une erreur est survenue lors du rejet.';
            $type = 'erreur';
        }

        header(
            'Location: /immo-sn/moderateur/signalements?message='
            . rawurlencode($message)
            . '&type=' . $type
        );

        exit;
    }

    /**
     * Approuver une annonce depuis l'espace modérateur.
     */
    public function approuverBien(string $id): void
    {
        $this->verifierModerateur();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/moderateur/annonces');
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            header('Location: /immo-sn/moderateur/annonces?erreur=csrf');
            exit;
        }

        if (!$this->isValidUuid($id)) {
            header('Location: /immo-sn/moderateur/annonces?erreur=id_invalide');
            exit;
        }

        try {
            $success = Bien::adminApprouver($id);

            if ($success) {
                $this->enregistrerDecision(
                    'annonce_approuvee',
                    'annonce',
                    $id
                );

                header('Location: /immo-sn/moderateur/annonces?message=annonce_approuvee&type=success');
            } else {
                header('Location: /immo-sn/moderateur/annonces?message=annonce_non_approuvee&type=error');
            }

        } catch (\Throwable $e) {
            error_log('Erreur approbation annonce : ' . $e->getMessage());

            header('Location: /immo-sn/moderateur/annonces?message=erreur_approbation&type=error');
        }

        exit;
    }

    /**
     * Approuver une demande de renouvellement.
     */
    public function approuverRenouvellement(string $id): void
    {
        $this->verifierModerateur();

        $destination = '/immo-sn/moderateur/renouvellements';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
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

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant de demande invalide.'
                );
            }

            $userId = Session::userId();

            if ($userId === null || !$this->isValidUuid($userId)) {
                throw new \RuntimeException(
                    'Identifiant du modérateur invalide.'
                );
            }

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
                'Erreur approbation renouvellement : '
                . $e->getMessage()
            );

            Session::flash(
                'error',
                'Impossible d’approuver cette demande. Vérifiez son statut et réessayez.'
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
        $this->verifierModerateur();

        $destination = '/immo-sn/moderateur/renouvellements';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
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

        $motif = trim((string) ($_POST['motif_refus'] ?? ''));

        if (!Validator::required($motif)) {
            Session::flash(
                'error',
                'Veuillez indiquer le motif du refus.'
            );

            header('Location: ' . $destination);
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant de demande invalide.'
                );
            }

            $userId = Session::userId();

            if ($userId === null || !$this->isValidUuid($userId)) {
                throw new \RuntimeException(
                    'Identifiant du modérateur invalide.'
                );
            }

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
                'Erreur refus renouvellement : '
                . $e->getMessage()
            );

            Session::flash(
                'error',
                'Impossible de refuser cette demande. Vérifiez son statut et réessayez.'
            );
        }

        header('Location: ' . $destination);
        exit;
    }

    /**
     * Rejeter une annonce depuis l'espace modérateur.
     */
    public function rejeterBien(string $id): void
    {
        $this->verifierModerateur();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/moderateur/annonces');
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            header('Location: /immo-sn/moderateur/annonces?erreur=csrf');
            exit;
        }

        if (!$this->isValidUuid($id)) {
            header('Location: /immo-sn/moderateur/annonces?erreur=id_invalide');
            exit;
        }

        $motif = trim((string) ($_POST['motif_rejet'] ?? ''));

        if (!Validator::required($motif)) {
            header('Location: /immo-sn/moderateur/annonces?message=motif_obligatoire&type=error');
            exit;
        }

        try {
            $success = Bien::adminRejeter($id, $motif);

            if ($success) {
                $this->enregistrerDecision(
                    'annonce_rejetee',
                    'annonce',
                    $id,
                    ['motif_rejet' => $motif]
                );

                header('Location: /immo-sn/moderateur/annonces?message=annonce_rejetee&type=success');
            } else {
                header('Location: /immo-sn/moderateur/annonces?message=annonce_non_rejetee&type=error');
            }

        } catch (\Throwable $e) {
            error_log('Erreur rejet annonce : ' . $e->getMessage());

            header('Location: /immo-sn/moderateur/annonces?message=erreur_rejet&type=error');
        }

        exit;
    }

    /**
     * Archiver un signalement traité ou rejeté.
     */
    public function supprimerSignalement(string $id): void
    {
        $this->verifierModerateur();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/moderateur/signalements');
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            header(
                'Location: /immo-sn/moderateur/signalements?message='
                . rawurlencode(
                    'Votre session a expiré. Veuillez réessayer.'
                )
                . '&type=erreur'
            );
            exit;
        }

        try {
            if (!$this->isValidUuid($id)) {
                throw new \InvalidArgumentException(
                    'Identifiant du signalement invalide.'
                );
            }

            $supprime = Signalement::supprimer($id);

            if ($supprime) {
                $this->enregistrerDecision(
                    'signalement_archive',
                    'signalement',
                    $id
                );
            }

            $message = $supprime
                ? 'Signalement archivé avec succès.'
                : 'Archivage impossible : signalement introuvable, déjà archivé ou encore en attente.';

            $type = $supprime ? 'succes' : 'erreur';

        } catch (\Throwable $e) {
            error_log(
                'Erreur archivage signalement modérateur : '
                . $e->getMessage()
            );

            $message = 'Une erreur est survenue pendant l’archivage.';
            $type = 'erreur';
        }

        header(
            'Location: /immo-sn/moderateur/signalements?message='
            . rawurlencode($message)
            . '&type=' . $type
        );

        exit;
    }

    /**
     * Afficher l'historique des décisions de modération.
     */
    public function historique(): void
    {
        $this->verifierModerateur();

        try {
            $filtres = [
                'action' => trim((string) ($_GET['action'] ?? '')),
                'recherche' => trim((string) ($_GET['recherche'] ?? '')),
                'date_debut' => trim((string) ($_GET['date_debut'] ?? '')),
                'date_fin' => trim((string) ($_GET['date_fin'] ?? '')),
            ];

            // Validation des dates.
            foreach (['date_debut', 'date_fin'] as $champ) {
                if (
                    $filtres[$champ] !== ''
                    && (
                        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filtres[$champ])
                        || !checkdate(
                            (int) substr($filtres[$champ], 5, 2),
                            (int) substr($filtres[$champ], 8, 2),
                            (int) substr($filtres[$champ], 0, 4)
                        )
                    )
                ) {
                    $filtres[$champ] = '';
                }
            }

            if (
                $filtres['date_debut'] !== ''
                && $filtres['date_fin'] !== ''
                && $filtres['date_debut'] > $filtres['date_fin']
            ) {
                [
                    $filtres['date_debut'],
                    $filtres['date_fin']
                ] = [
                    $filtres['date_fin'],
                    $filtres['date_debut'],
                ];
            }

            $parPage = 10;
            $page = max(1, (int) ($_GET['page'] ?? 1));

            $total = AuditLog::compter($filtres);

            $totalPages = max(
                1,
                (int) ceil($total / $parPage)
            );

            $page = min($page, $totalPages);
            $offset = ($page - 1) * $parPage;

            $decisions = AuditLog::lister(
                $filtres,
                $parPage,
                $offset
            );

            $this->view(
                'pages/moderateur/historique',
                [
                    'title' => 'Historique des décisions — IMMO-SN',
                    'decisions' => $decisions,
                    'filtres' => $filtres,
                    'total' => $total,
                    'page' => $page,
                    'parPage' => $parPage,
                    'totalPages' => $totalPages,
                ],
                'app'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur historique modérateur : ' . $e->getMessage()
            );

            http_response_code(500);

            echo 'Impossible de charger l’historique des décisions.';
        }
    }

    /**
     * Afficher le profil du modérateur.
     */
    public function profil(): void
    {
        $this->verifierModerateur();

        $userId = Session::userId();

        if ($userId === null || !$this->isValidUuid($userId)) {
            header('Location: /immo-sn/login');
            exit;
        }

        $db = \Core\Database::getConnection();

        try {
            $stmt = $db->prepare("
                SELECT
                    id,
                    prenom,
                    nom,
                    email,
                    telephone,
                    ville,
                    quartier,
                    bio,
                    photo_profil,
                    role,
                    status,
                    is_verified,
                    created_at,
                    updated_at,
                    last_login
                FROM public.users
                WHERE id = CAST(:id AS UUID)
                LIMIT 1
            ");

            $stmt->execute([
                'id' => $userId
            ]);

            $profil = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$profil) {
                throw new \RuntimeException(
                    'Impossible de retrouver votre compte.'
                );
            }

            $this->view(
                'pages/moderateur/profil',
                [
                    'profil' => $profil,
                    'passwordSuccess' => Session::getFlash('password_success'),
                    'passwordError' => Session::getFlash('password_error'),
                    'success' => Session::getFlash('profil_success'),
                    'error' => Session::getFlash('profil_error')
                ],
                'app'
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur chargement profil modérateur : '
                . $e->getMessage()
            );

            http_response_code(500);

            echo 'Une erreur est survenue lors du chargement du profil.';
        }
    }

    /**
     * Changement du mot de passe du modérateur.
     */
    public function changerMotDePasse(): void
    {
        $this->verifierModerateur();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/moderateur/profil');
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            Session::flash(
                'password_error',
                'Votre session de sécurité a expiré. Veuillez réessayer.'
            );

            header('Location: /immo-sn/moderateur/profil');
            exit;
        }

        $userId = Session::userId();

        $ancien = $_POST['ancien_mot_de_passe'] ?? '';
        $nouveau = $_POST['nouveau_mot_de_passe'] ?? '';
        $confirmation = $_POST['confirmation_mot_de_passe'] ?? '';

        try {
            if ($userId === null || !$this->isValidUuid($userId)) {
                throw new \RuntimeException(
                    'Identifiant du compte invalide.'
                );
            }

            if (
                !Validator::required($ancien)
                || !Validator::required($nouveau)
                || !Validator::required($confirmation)
            ) {
                throw new \RuntimeException(
                    'Veuillez remplir tous les champs.'
                );
            }

            if (!Validator::minLength($nouveau, 8)) {
                throw new \RuntimeException(
                    'Le nouveau mot de passe doit contenir au moins 8 caractères.'
                );
            }

            if (!Validator::matches($nouveau, $confirmation)) {
                throw new \RuntimeException(
                    'Les deux nouveaux mots de passe ne correspondent pas.'
                );
            }

            if ($ancien === $nouveau) {
                throw new \RuntimeException(
                    'Le nouveau mot de passe doit être différent de l’ancien.'
                );
            }

            $db = \Core\Database::getConnection();

            $stmt = $db->prepare("
                SELECT password
                FROM public.users
                WHERE id = CAST(:id AS UUID)
                LIMIT 1
            ");

            $stmt->execute(['id' => $userId]);

            $hashActuel = $stmt->fetchColumn();

            if (
                $hashActuel === false
                || !User::verifyPassword($ancien, (string) $hashActuel)
            ) {
                throw new \RuntimeException(
                    'Votre ancien mot de passe est incorrect.'
                );
            }

            if (!User::updatePassword($userId, $nouveau)) {
                throw new \RuntimeException(
                    'Le changement de mot de passe n’a pas abouti.'
                );
            }

            Session::flash(
                'password_success',
                'Votre mot de passe a été modifié avec succès.'
            );

        } catch (\RuntimeException $e) {
            Session::flash('password_error', $e->getMessage());

        } catch (\Throwable $e) {
            error_log(
                'Erreur changement mot de passe modérateur : '
                . $e->getMessage()
            );

            Session::flash(
                'password_error',
                'Une erreur est survenue. Veuillez réessayer.'
            );
        }

        header('Location: /immo-sn/moderateur/profil');
        exit;
    }

    /**
     * Modification des informations personnelles du modérateur.
     */
    public function modifierProfil(): void
    {
        $this->verifierModerateur();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: /immo-sn/moderateur/profil');
            exit;
        }

        if (!$this->verifierJetonCsrf()) {
            Session::flash(
                'profil_error',
                'Votre session de sécurité a expiré. Veuillez réessayer.'
            );

            header('Location: /immo-sn/moderateur/profil');
            exit;
        }

        $userId = Session::userId();

        $nouveauFichier = null;
        $dossier = null;

        try {
            if ($userId === null || !$this->isValidUuid($userId)) {
                throw new \RuntimeException('Identifiant du compte invalide.');
            }

            // 1. Récupération et nettoyage des données.
            $prenom = trim((string) ($_POST['prenom'] ?? ''));
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $telephone = trim((string) ($_POST['telephone'] ?? ''));
            $ville = trim((string) ($_POST['ville'] ?? ''));
            $quartier = trim((string) ($_POST['quartier'] ?? ''));
            $bio = trim((string) ($_POST['bio'] ?? ''));

            // 2. Validation des informations obligatoires.
            if (
                !Validator::required($prenom)
                || !Validator::required($nom)
            ) {
                throw new \RuntimeException(
                    'Le prénom et le nom sont obligatoires.'
                );
            }

            if (!Validator::email($email)) {
                throw new \RuntimeException(
                    'Veuillez saisir une adresse e-mail valide.'
                );
            }

            if (
                !Validator::maxLength($prenom, 100)
                || !Validator::maxLength($nom, 100)
            ) {
                throw new \RuntimeException(
                    'Le prénom et le nom ne doivent pas dépasser 100 caractères.'
                );
            }

            if (!Validator::maxLength($telephone, 30)) {
                throw new \RuntimeException('Le numéro de téléphone est trop long.');
            }

            if (
                !Validator::maxLength($ville, 100)
                || !Validator::maxLength($quartier, 100)
            ) {
                throw new \RuntimeException(
                    'La ville ou le quartier est trop long.'
                );
            }

            if (!Validator::maxLength($bio, 1000)) {
                throw new \RuntimeException(
                    'La biographie ne doit pas dépasser 1000 caractères.'
                );
            }

            $db = \Core\Database::getConnection();

            // 3. Vérifier que l'adresse e-mail n'appartient pas à un autre compte.
            $stmtEmail = $db->prepare("
                SELECT id
                FROM public.users
                WHERE LOWER(email) = LOWER(:email)
                  AND id <> CAST(:id AS UUID)
                LIMIT 1
            ");

            $stmtEmail->execute([
                'email' => $email,
                'id' => $userId,
            ]);

            if ($stmtEmail->fetchColumn() !== false) {
                throw new \RuntimeException(
                    'Cette adresse e-mail est déjà utilisée.'
                );
            }

            // 4. Récupérer l'ancienne photo.
            $stmtAnciennePhoto = $db->prepare("
                SELECT photo_profil
                FROM public.users
                WHERE id = CAST(:id AS UUID)
                LIMIT 1
            ");

            $stmtAnciennePhoto->execute(['id' => $userId]);

            $anciennePhoto = $stmtAnciennePhoto->fetchColumn();

            if ($anciennePhoto === false) {
                throw new \RuntimeException('Compte modérateur introuvable.');
            }

            $photoChemin = null;

            // 5. Traitement éventuel de la nouvelle photo.
            if (
                isset($_FILES['photo_profil'])
                && is_array($_FILES['photo_profil'])
                && ($_FILES['photo_profil']['error'] ?? UPLOAD_ERR_NO_FILE)
                    !== UPLOAD_ERR_NO_FILE
            ) {
                $fichier = $_FILES['photo_profil'];

                if (($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    throw new \RuntimeException(
                        'Une erreur est survenue pendant l’envoi de la photo.'
                    );
                }

                $tmpName = $fichier['tmp_name'] ?? '';
                $taille = (int) ($fichier['size'] ?? 0);

                if (!is_uploaded_file($tmpName)) {
                    throw new \RuntimeException('Fichier envoyé invalide.');
                }

                if ($taille <= 0 || $taille > 5 * 1024 * 1024) {
                    throw new \RuntimeException(
                        'La photo doit peser au maximum 5 Mo.'
                    );
                }

                $imageInfo = @getimagesize($tmpName);

                if ($imageInfo === false) {
                    throw new \RuntimeException(
                        'Le fichier sélectionné n’est pas une image valide.'
                    );
                }

                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                $mime = $finfo !== false
                    ? finfo_file($finfo, $tmpName)
                    : '';

                if ($finfo !== false) {
                    finfo_close($finfo);
                }

                $typesAutorises = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                ];

                if (
                    !isset($typesAutorises[$mime])
                    || ($imageInfo['mime'] ?? '') !== $mime
                ) {
                    throw new \RuntimeException(
                        'La photo doit être au format JPG ou PNG.'
                    );
                }

                $basePath = defined('BASE_PATH')
                    ? BASE_PATH
                    : dirname(__DIR__, 2);

                $dossier = $basePath . '/stockage/photos';

                if (!is_dir($dossier)) {
                    if (!mkdir($dossier, 0775, true) && !is_dir($dossier)) {
                        throw new \RuntimeException(
                            'Impossible de créer le dossier de stockage.'
                        );
                    }
                }

                $nomFichier = 'profil_' . bin2hex(random_bytes(16))
                    . '.' . $typesAutorises[$mime];

                $destination = $dossier . DIRECTORY_SEPARATOR . $nomFichier;

                if (!move_uploaded_file($tmpName, $destination)) {
                    throw new \RuntimeException(
                        'Impossible d’enregistrer la nouvelle photo.'
                    );
                }

                $nouveauFichier = $destination;
                $photoChemin = '/immo-sn/stockage/photos/' . $nomFichier;
            }

            // 6. Mise à jour des informations du modérateur.
            $stmtUpdate = $db->prepare("
                UPDATE public.users
                SET
                    prenom = :prenom,
                    nom = :nom,
                    email = :email,
                    telephone = :telephone,
                    ville = :ville,
                    quartier = :quartier,
                    bio = :bio,
                    photo_profil = COALESCE(:photo_profil, photo_profil),
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = CAST(:id AS UUID)
            ");

            $stmtUpdate->execute([
                'prenom' => $prenom,
                'nom' => $nom,
                'email' => $email,
                'telephone' => $telephone !== '' ? $telephone : null,
                'ville' => $ville !== '' ? $ville : null,
                'quartier' => $quartier !== '' ? $quartier : null,
                'bio' => $bio !== '' ? $bio : null,
                'photo_profil' => $photoChemin,
                'id' => $userId,
            ]);

            if ($stmtUpdate->rowCount() < 1) {
                throw new \RuntimeException(
                    'Aucune modification n’a été enregistrée.'
                );
            }

            // 7. Supprimer l'ancienne photo après la mise à jour réussie.
            if (
                $photoChemin !== null
                && is_string($anciennePhoto)
                && str_starts_with(
                    $anciennePhoto,
                    '/immo-sn/stockage/photos/'
                )
            ) {
                $ancienNom = basename($anciennePhoto);
                $ancienChemin = $dossier . DIRECTORY_SEPARATOR . $ancienNom;

                if (
                    $ancienNom !== ''
                    && is_file($ancienChemin)
                    && $ancienChemin !== $nouveauFichier
                ) {
                    @unlink($ancienChemin);
                }
            }

            Session::flash(
                'profil_success',
                'Vos informations personnelles ont été mises à jour avec succès.'
            );

        } catch (\RuntimeException $e) {
            if ($nouveauFichier !== null && is_file($nouveauFichier)) {
                @unlink($nouveauFichier);
            }

            Session::flash('profil_error', $e->getMessage());

        } catch (\Throwable $e) {
            if ($nouveauFichier !== null && is_file($nouveauFichier)) {
                @unlink($nouveauFichier);
            }

            error_log(
                'Erreur modification profil modérateur : ' . $e->getMessage()
            );

            Session::flash(
                'profil_error',
                'Une erreur est survenue pendant la modification du profil.'
            );
        }

        header('Location: /immo-sn/moderateur/profil');
        exit;
    }
}
