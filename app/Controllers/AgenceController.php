<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Agence;
use App\Models\Bien;
use Core\Session;
use Core\Uploader;
use Core\Database;
use Core\QrGenerator;
use App\Models\User;
use App\Models\DemandeRenouvellement;
use Core\Validator;
use PDO;

class AgenceController extends Controller
{
    /**
     * Liste des agences.
     */
    public function index(): void
    {
        $agences = Agence::toutes();

        $this->view('pages/agences/index', [
            'title' => 'Agences — ImmoSn',
            'agences' => $agences,
        ]);
    }

    /**
     * Profil détaillé d'une agence.
     */
    public function show(string $id): void
    {
        $agence = Agence::trouver($id);

        if (!$agence) {
            http_response_code(404);
            echo "Agence introuvable";
            return;
        }

        $biens = Agence::biens($id);

        $this->view('pages/agences/show', [
            'title' => $agence['nom_agence'] . ' — ImmoSn',
            'agence' => $agence,
            'biens' => $biens,
        ]);
    }

    /**
     * Télécharger tous les QR Codes des biens
     * de l'agence du responsable connecté.
     */
    public function telechargerQrZip(): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès interdit.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        $agence = Agence::trouverParResponsable($userId);

        if (!$agence) {
            http_response_code(404);
            exit('Aucune agence associée à ce compte.');
        }

        $agenceId = (string) $agence['id'];

        $qrCodes = Agence::qrCodesBiens($agenceId);

        if (empty($qrCodes)) {
            http_response_code(404);
            exit('Aucun QR Code disponible pour cette agence.');
        }

        if (!class_exists(\ZipArchive::class)) {
            http_response_code(500);
            exit('L’extension PHP ZipArchive n’est pas activée.');
        }

        $qrDirectory = realpath(
            BASE_PATH . '/stockage/qrcodes'
        );

        if ($qrDirectory === false) {
            http_response_code(500);
            exit('Le dossier des QR Codes est introuvable.');
        }

        $zipPath = tempnam(sys_get_temp_dir(), 'immo_qr_');

        if ($zipPath === false) {
            http_response_code(500);
            exit('Impossible de créer le fichier ZIP.');
        }

        $zip = new \ZipArchive();

        if ($zip->open(
            $zipPath,
            \ZipArchive::CREATE | \ZipArchive::OVERWRITE
        ) !== true) {
            @unlink($zipPath);
            http_response_code(500);
            exit('Impossible de créer l’archive ZIP.');
        }

        $nombreAjoute = 0;

        foreach ($qrCodes as $qr) {
            $filePath = BASE_PATH . '/' . ltrim(
                $qr['file_path'],
                '/\\'
            );

            $realFile = realpath($filePath);

            if (
                $realFile === false ||
                !is_file($realFile) ||
                !str_starts_with(
                    $realFile,
                    $qrDirectory . DIRECTORY_SEPARATOR
                )
            ) {
                continue;
            }

            $nomFichier = 'QR_' .
                preg_replace(
                    '/[^a-zA-Z0-9_-]/',
                    '_',
                    (string) $qr['bien_id']
                ) . '.png';

            if ($zip->addFile($realFile, $nomFichier)) {
                $nombreAjoute++;
            }
        }

        $zip->close();

        if ($nombreAjoute === 0) {
            @unlink($zipPath);
            http_response_code(404);
            exit('Aucun fichier QR Code valide à télécharger.');
        }

        $nomAgence = preg_replace(
            '/[^a-zA-Z0-9_-]/',
            '_',
            (string) $agence['nom_agence']
        );

        $nomZip = 'QR_Codes_' . $nomAgence . '.zip';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/zip');
        header(
            'Content-Disposition: attachment; filename="' .
            $nomZip . '"'
        );
        header('Content-Length: ' . filesize($zipPath));
        header('Cache-Control: no-store, no-cache, must-revalidate');

        readfile($zipPath);

        @unlink($zipPath);
        exit;
    }

    /**
     * Espace privé du responsable d'agence.
     */
    public function monAgence(): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            echo "Accès interdit : cette page est réservée aux responsables d'agence.";
            return;
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        $agence = Agence::trouverParResponsable($userId);

        if (!$agence) {
            http_response_code(404);
            echo "Aucune agence n'est associée à ce compte.";
            return;
        }

        $biens = Agence::mesBiensParResponsable($userId);

        $statistiques = [
            'total' => count($biens),
            'actifs' => 0,
            'en_attente' => 0,
            'refuses' => 0,
            'expires' => 0,
        ];

        foreach ($biens as $bien) {
            $statutBien = strtolower(trim($bien['statut'] ?? ''));

            if ($statutBien === 'actif') {
                $statistiques['actifs']++;
            } elseif ($statutBien === 'en_attente') {
                $statistiques['en_attente']++;
            } elseif (in_array(
                $statutBien,
                ['rejete', 'refuse', 'refusee'],
                true
            )) {
                $statistiques['refuses']++;
            } elseif ($statutBien === 'expire') {
                $statistiques['expires']++;
            }
        }

        $this->view('pages/agences/mon-agence', [
            'title' => 'Mon agence — ImmoSn',
            'agence' => $agence,
            'statistiques' => $statistiques,
            'biens' => $biens,
        ]);
    }

    /**
     * Afficher le profil personnel du responsable d'agence.
     */
    public function profil(): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            echo "Accès interdit.";
            return;
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        $responsable = User::findById($userId);

        if (!$responsable) {
            http_response_code(404);
            echo "Profil du responsable introuvable.";
            return;
        }

        $this->view('pages/agences/profil', [
            'title' => 'Mon profil — ImmoSn',
            'responsable' => $responsable,
        ]);
    }

    /**
     * Modifier le profil personnel du responsable d'agence.
     */
    public function modifierProfil(): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            echo "Accès interdit.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée.";
            return;
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
            Session::flash(
                'error',
                'Votre session a expiré. Veuillez réessayer.'
            );

            header('Location: /immo-sn/agence/profil');
            exit;
        }

        $donnees = [
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telephone' => $_POST['telephone'] ?? '',
            'ville' => $_POST['ville'] ?? '',
            'quartier' => $_POST['quartier'] ?? '',
            'bio' => $_POST['bio'] ?? '',
        ];

        try {
            $modification = User::modifierProfilPersonnel(
                $userId,
                $donnees
            );

            if ($modification) {
                Session::flash(
                    'success',
                    'Votre profil personnel a été mis à jour.'
                );
            } else {
                Session::flash(
                    'error',
                    'Aucune modification effectuée ou profil introuvable.'
                );
            }
        } catch (\InvalidArgumentException $e) {
            Session::flash('error', $e->getMessage());
        } catch (\Throwable $e) {
            Session::flash(
                'error',
                'Une erreur est survenue lors de la modification du profil.'
            );
        }

        header('Location: /immo-sn/agence/profil');
        exit;
    }

    /**
     * Enregistrer les modifications de l'agence du responsable connecté.
     */
    public function modifierMonAgence(): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès interdit.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
            http_response_code(403);
            exit('Jeton de sécurité invalide. Veuillez recharger la page.');
        }

        $nomAgence = trim($_POST['nom_agence'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $ninea = trim($_POST['ninea'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');

        if ($nomAgence === '' || $adresse === '' || $ninea === '') {
            Session::flash(
                'error',
                'Le nom, l’adresse et le NINEA sont obligatoires.'
            );

            header('Location: /immo-sn/agence/mon-agence');
            exit;
        }

        // L'adresse e-mail est facultative, mais doit être valide si renseignée.
        if (
            $email !== '' &&
            !Validator::email($email)
        ) {
            Session::flash(
                'error',
                'Veuillez saisir une adresse e-mail valide.'
            );

            header('Location: /immo-sn/agence/mon-agence');
            exit;
        }

        $donnees = [
            'nom_agence' => $nomAgence,
            'description' => $description,
            'adresse' => $adresse,
            'ninea' => $ninea,
            'email' => $email,
            'telephone' => $telephone,
        ];

        try {
            $modification = Agence::modifierParResponsable(
                $userId,
                $donnees
            );

            if (!$modification) {
                throw new \RuntimeException(
                    'La modification de l’agence a échoué.'
                );
            }

            Session::flash(
                'success',
                'Les informations de votre agence ont été mises à jour.'
            );
        } catch (\Throwable $e) {
            error_log(
                'Modification agence : ' . $e->getMessage()
            );

            Session::flash(
                'error',
                'Une erreur est survenue pendant la modification.'
            );
        }

        header('Location: /immo-sn/agence/profil');
        exit;
    }

    /**
     * Afficher tous les biens de l'agence du responsable connecté.
     * Tous les statuts sont inclus.
     */
    public function mesBiens(): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès interdit : cette page est réservée aux agences.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        $agence = Agence::trouverParResponsable($userId);

        if (!$agence) {
            http_response_code(404);
            exit('Aucune agence n’est associée à ce compte.');
        }

        $biens = Agence::mesBiensParResponsable($userId);

        $renouvellements =
            DemandeRenouvellement::statutsParBiensUtilisateur($userId);

        $success = Session::getFlash('publication_success');
        $error = Session::getFlash('publication_error');

        $this->view('pages/agences/biens', [
            'title' => 'Mes biens — ImmoSn',
            'agence' => $agence,
            'biens' => $biens,
            'renouvellements' => $renouvellements,
            'success' => $success,
            'error' => $error,
        ]);
    }

    /**
     * Envoyer une demande de renouvellement pour un bien expiré.
     * Réservé au responsable d'une agence.
     */
    public function demanderRenouvellement(string $id): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès interdit : cette action est réservée aux agences.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        $agence = Agence::trouverParResponsable($userId);

        if (!$agence) {
            http_response_code(404);
            exit('Aucune agence associée à ce compte.');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
            Session::flash(
                'publication_error',
                'Votre session a expiré. Veuillez réessayer.'
            );

            header('Location: /immo-sn/agence/biens');
            exit;
        }

        try {
            DemandeRenouvellement::soumettre($id, $userId);

            Session::flash(
                'publication_success',
                'Votre demande de renouvellement a été envoyée. Elle sera examinée par notre équipe.'
            );

            header('Location: /immo-sn/agence/biens');
            exit;
        } catch (\RuntimeException $e) {
            Session::flash(
                'publication_error',
                $e->getMessage()
            );

            header('Location: /immo-sn/agence/biens');
            exit;
        } catch (\Throwable $e) {
            error_log(
                'Erreur demande renouvellement agence : ' . $e->getMessage()
            );

            Session::flash(
                'publication_error',
                'Impossible d’envoyer votre demande pour le moment. Veuillez réessayer.'
            );

            header('Location: /immo-sn/agence/biens');
            exit;
        }
    }

    /**
     * Afficher les QR Codes des biens de l'agence connectée.
     */
    public function mesQrCodes(): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès interdit : cette page est réservée aux agences.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        $agence = Agence::trouverParResponsable($userId);

        if (!$agence) {
            http_response_code(404);
            exit('Aucune agence associée à ce compte.');
        }

        $qrCodes = Agence::qrCodesBiens((string) $agence['id']);

        $this->view('pages/agences/qr-codes', [
            'title' => 'Mes QR Codes — ImmoSn',
            'agence' => $agence,
            'qrCodes' => $qrCodes,
        ]);
    }

    /**
     * Consulter les détails d'un bien appartenant
     * au responsable de l'agence connecté.
     */
    public function voirBien(string $id): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès interdit.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        if (!Bien::appartientA($id, $userId)) {
            http_response_code(404);
            exit('Bien introuvable ou accès non autorisé.');
        }

        $bien = Bien::detail($id);

        if (!$bien) {
            http_response_code(404);
            exit('Bien introuvable.');
        }

        $photos = Bien::photos($id);
        $categories = Bien::categories();
        $villes = Bien::villes();
        $qrCode = Bien::qrCode($id);

        $agence = Agence::trouverParResponsable($userId);

        if (!$agence) {
            http_response_code(404);
            exit('Agence introuvable.');
        }

        $this->view('pages/agences/detail-bien', [
            'title' => 'Détails du bien — ImmoSn',
            'agence' => $agence,
            'bien' => $bien,
            'photos' => $photos,
            'categories' => $categories,
            'villes' => $villes,
            'qrCode' => $qrCode,
        ]);
    }

    /**
     * Enregistrer les modifications d'un bien appartenant
     * à l'agence connectée.
     */
    public function modifierBien(string $id): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès interdit.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
            http_response_code(403);
            exit('Jeton de sécurité invalide. Rechargez la page.');
        }

        if (!Bien::appartientA($id, $userId)) {
            http_response_code(404);
            exit('Bien introuvable ou accès non autorisé.');
        }

        $donnees = [
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'ville_id' => (int) ($_POST['ville_id'] ?? 0),
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'type_offre' => trim($_POST['type_offre'] ?? ''),
            'prix' => $_POST['prix'] ?? '',
            'surface' => $_POST['surface'] ?? '',
            'pieces' => $_POST['pieces'] ?? null,
            'chambres' => $_POST['chambres'] ?? null,
            'salles_de_bain' => $_POST['salles_de_bain'] ?? null,
            'latitude' => $_POST['latitude'] ?? null,
            'longitude' => $_POST['longitude'] ?? null,
            'video_url' => trim($_POST['video_url'] ?? ''),
        ];

        if (
            $donnees['category_id'] <= 0 ||
            $donnees['ville_id'] <= 0 ||
            $donnees['titre'] === '' ||
            $donnees['description'] === '' ||
            !in_array($donnees['type_offre'], ['vente', 'location'], true) ||
            !is_numeric($donnees['prix']) ||
            (float) $donnees['prix'] <= 0 ||
            !is_numeric($donnees['surface']) ||
            (float) $donnees['surface'] <= 0
        ) {
            Session::flash(
                'error',
                'Veuillez vérifier les informations obligatoires du bien.'
            );

            header('Location: /immo-sn/agence/biens/' . rawurlencode($id));
            exit;
        }

        $photosAjoutees = [];

        try {
            $modification = Bien::modifierBienAgence(
                $id,
                $userId,
                $donnees
            );

            if (!$modification) {
                throw new \RuntimeException(
                    'Aucune modification des informations du bien n’a été enregistrée.'
                );
            }

            if (isset($_FILES['photos'])) {
                $photosAjoutees = Uploader::uploadPhotos(
                    $_FILES['photos'],
                    $id
                );
            }

            $photosASupprimer = $_POST['photos_a_supprimer'] ?? [];

            if (!is_array($photosASupprimer)) {
                $photosASupprimer = [];
            }

            $photoPrincipale = $_POST['photo_principale'] ?? null;

            if ($photoPrincipale !== null) {
                $photoPrincipale = trim((string) $photoPrincipale);

                if ($photoPrincipale === '') {
                    $photoPrincipale = null;
                }
            }

            Bien::modifierPhotosAgence(
                $id,
                $userId,
                $photosAjoutees,
                $photosASupprimer,
                $photoPrincipale
            );

            Session::flash(
                'success',
                'Votre bien et ses photos ont été modifiés et envoyés en attente de validation.'
            );
        } catch (\Throwable $e) {
            foreach ($photosAjoutees as $photo) {
                $nomFichier = basename($photo['file_path'] ?? '');

                if ($nomFichier !== '') {
                    $chemin = BASE_PATH . '/stockage/photos/' . $nomFichier;

                    if (is_file($chemin)) {
                        @unlink($chemin);
                    }
                }
            }

            error_log('Modification du bien : ' . $e->getMessage());

            Session::flash(
                'error',
                'Une erreur est survenue pendant la modification du bien.'
            );
        }

        header('Location: /immo-sn/agence/biens/' . rawurlencode($id));
        exit;
    }

    /**
     * Supprimer un bien depuis l'espace agence.
     */
    public function supprimerBien(string $id): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès interdit.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        $agence = Agence::trouverParResponsable($userId);

        if (!$agence) {
            http_response_code(404);
            exit('Aucune agence n’est associée à ce compte.');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
            http_response_code(403);
            exit('Jeton de sécurité invalide. Rechargez la page.');
        }

        $db = null;

        try {
            $db = Database::getConnection();
            $db->beginTransaction();

            // Vérifier et verrouiller le bien appartenant à l'agence.
            $stmt = $db->prepare("
                SELECT id
                FROM public.biens
                WHERE id = CAST(:bien_id AS UUID)
                  AND user_id = CAST(:user_id AS UUID)
                FOR UPDATE
            ");

            $stmt->execute([
                'bien_id' => $id,
                'user_id' => $userId,
            ]);

            if (!$stmt->fetchColumn()) {
                $db->rollBack();

                Session::flash(
                    'publication_error',
                    'Bien introuvable ou accès non autorisé.'
                );

                header('Location: /immo-sn/agence/biens');
                exit;
            }

            // Préserver l'historique des renouvellements.
            $stmt = $db->prepare("
                SELECT 1
                FROM public.demandes_renouvellement
                WHERE bien_id = CAST(:bien_id AS UUID)
                LIMIT 1
            ");

            $stmt->execute(['bien_id' => $id]);

            if ($stmt->fetchColumn()) {
                $db->rollBack();

                Session::flash(
                    'publication_error',
                    'Suppression impossible : cette annonce possède un historique de demandes de renouvellement.'
                );

                header('Location: /immo-sn/agence/biens');
                exit;
            }

            // Récupérer les photos avant la suppression.
            $stmt = $db->prepare("
                SELECT file_path
                FROM public.photos
                WHERE bien_id = CAST(:bien_id AS UUID)
            ");

            $stmt->execute(['bien_id' => $id]);
            $photos = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Récupérer les QR Codes avant la suppression.
            $stmt = $db->prepare("
                SELECT file_path
                FROM public.qr_codes
                WHERE bien_id = CAST(:bien_id AS UUID)
            ");

            $stmt->execute(['bien_id' => $id]);
            $qrCodes = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Supprimer uniquement le bien de ce responsable.
            $stmt = $db->prepare("
                DELETE FROM public.biens
                WHERE id = CAST(:bien_id AS UUID)
                  AND user_id = CAST(:user_id AS UUID)
            ");

            $stmt->execute([
                'bien_id' => $id,
                'user_id' => $userId,
            ]);

            if ($stmt->rowCount() !== 1) {
                throw new \RuntimeException(
                    'La suppression du bien a échoué.'
                );
            }

            $db->commit();

            // Supprimer les fichiers physiques des photos.
            $photoDirectory = realpath(
                BASE_PATH . '/stockage/photos'
            );

            if ($photoDirectory !== false) {
                foreach ($photos as $photoPath) {
                    $photoPath = (string) $photoPath;

                    if (!str_starts_with(
                        $photoPath,
                        '/immo-sn/stockage/photos/'
                    )) {
                        continue;
                    }

                    $filename = basename($photoPath);

                    if (
                        $filename === '' ||
                        !str_starts_with($filename, $id . '_')
                    ) {
                        continue;
                    }

                    $candidate = $photoDirectory
                        . DIRECTORY_SEPARATOR
                        . $filename;

                    $realFile = realpath($candidate);

                    if (
                        $realFile !== false &&
                        dirname($realFile) === $photoDirectory &&
                        is_file($realFile) &&
                        !@unlink($realFile)
                    ) {
                        error_log(
                            'Impossible de supprimer la photo : ' . $realFile
                        );
                    }
                }
            }

            // Supprimer le fichier QR Code associé au bien.
            $qrDirectory = realpath(
                BASE_PATH . '/stockage/qrcodes'
            );

            if ($qrDirectory !== false) {
                $expectedPath = '/stockage/qrcodes/' . $id . '.png';

                foreach ($qrCodes as $qrPath) {
                    if ((string) $qrPath !== $expectedPath) {
                        continue;
                    }

                    $candidate = $qrDirectory
                        . DIRECTORY_SEPARATOR
                        . $id
                        . '.png';

                    $realFile = realpath($candidate);

                    if (
                        $realFile !== false &&
                        dirname($realFile) === $qrDirectory &&
                        is_file($realFile) &&
                        !@unlink($realFile)
                    ) {
                        error_log(
                            'Impossible de supprimer le QR Code : ' . $realFile
                        );
                    }
                }
            }

            Session::flash(
                'publication_success',
                'Votre annonce et ses fichiers associés ont été supprimés avec succès.'
            );

            header('Location: /immo-sn/agence/biens');
            exit;
        } catch (\Throwable $e) {
            if ($db instanceof PDO && $db->inTransaction()) {
                $db->rollBack();
            }

            error_log(
                'Erreur suppression bien agence '
                . $id . ' : ' . $e->getMessage()
            );

            Session::flash(
                'publication_error',
                'Impossible de supprimer cette annonce pour le moment.'
            );

            header('Location: /immo-sn/agence/biens');
            exit;
        }
    }

    /**
     * Publier un bien depuis l'espace agence.
     */
    

public function publierBien(): void
{
    Session::requireAuth('/immo-sn/login');

    $utilisateur = Session::user();
    $userId = Session::userId();

    if (!$utilisateur || ($utilisateur['role'] ?? '') !== 'agence') {
        http_response_code(403);
        exit('Accès interdit.');
    }

    if (!$userId) {
        header('Location: /immo-sn/login');
        exit;
    }

    $agence = Agence::trouverParResponsable($userId);

    if (!$agence) {
        http_response_code(404);
        exit('Aucune agence associée à ce compte.');
    }

    $categories = Bien::categories();
    $villes = Bien::villes();

    $success = null;
    $error = null;

    // Affichage initial du formulaire.
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $this->view('pages/agences/publier', [
            'title' => 'Publier un bien — Espace agence',
            'agence' => $agence,
            'categories' => $categories,
            'villes' => $villes,
            'success' => null,
            'error' => null,
        ]);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Méthode non autorisée.');
    }

    try {
        // Vérification de sécurité.
        if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
            throw new \RuntimeException(
                'Votre session a expiré. Rechargez le formulaire et réessayez.'
            );
        }

        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $villeId = (int) ($_POST['ville_id'] ?? 0);

        $prix = $_POST['prix'] ?? '';
        $surface = $_POST['surface'] ?? '';

        $pieces = !empty($_POST['pieces'])
            ? (int) $_POST['pieces'] : null;

        $chambres = !empty($_POST['chambres'])
            ? (int) $_POST['chambres'] : null;

        $sallesBain = !empty($_POST['salles_bain'])
            ? (int) $_POST['salles_bain'] : null;

        $latitude = ($_POST['latitude'] ?? '') !== ''
            ? (float) $_POST['latitude'] : null;

        $longitude = ($_POST['longitude'] ?? '') !== ''
            ? (float) $_POST['longitude'] : null;

        $typeOffre = trim($_POST['type_offre'] ?? '');

        // Validation des champs.
        if ($titre === '') {
            throw new \RuntimeException('Le titre du bien est obligatoire.');
        }

        if ($description === '') {
            throw new \RuntimeException('La description du bien est obligatoire.');
        }

        if ($categoryId <= 0 || $villeId <= 0) {
            throw new \RuntimeException(
                'Veuillez sélectionner une catégorie et une ville.'
            );
        }

        if (!in_array($typeOffre, ['vente', 'location'], true)) {
            throw new \RuntimeException('Veuillez sélectionner un type d’offre valide.');
        }

        if (!is_numeric($prix) || (float) $prix <= 0) {
            throw new \RuntimeException('Veuillez saisir un prix valide.');
        }

        if (!is_numeric($surface) || (float) $surface <= 0) {
            throw new \RuntimeException('Veuillez saisir une surface valide.');
        }

        if ($latitude !== null && ($latitude < -90 || $latitude > 90)) {
            throw new \RuntimeException('La latitude est invalide.');
        }

        if ($longitude !== null && ($longitude < -180 || $longitude > 180)) {
            throw new \RuntimeException('La longitude est invalide.');
        }

        $dateActuelle = date('Y-m-d H:i:s');

        $data = [
            'user_id' => $userId,
            'category_id' => $categoryId,
            'ville_id' => $villeId,
            'Titre' => $titre,
            'Description' => $description,
            'type_offre' => $typeOffre,
            'Prix' => (float) $prix,
            'Surface' => (float) $surface,
            'Pièces' => $pieces,
            'Chambres' => $chambres,
            'salles_de_bain' => $sallesBain,
            'Latitude' => $latitude,
            'Longitude' => $longitude,
            'Statut' => 'en_attente',
            'nb_vues' => 0,
            'expire_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'created_at' => $dateActuelle,
            'updated_at' => $dateActuelle,
        ];

        // Création du bien.
        $bienId = Bien::publier($data);

        if (!$bienId) {
            throw new \RuntimeException(
                'La création du bien a échoué.'
            );
        }

        // Téléchargement des photos.
        $photosUploades = [];

        if (isset($_FILES['photos']) && is_array($_FILES['photos'])) {
            $photosUploades = Uploader::uploadPhotos(
                $_FILES['photos'],
                $bienId
            );
        }

        // Enregistrement des photos.
        if (!empty($photosUploades)) {
            $db = Database::getConnection();

            $stmt = $db->prepare('
                INSERT INTO photos (
                    id,
                    bien_id,
                    file_path,
                    is_principal,
                    created_at
                )
                VALUES (
                    gen_random_uuid(),
                    :bien_id,
                    :file_path,
                    :is_principal,
                    NOW()
                )
            ');

            foreach ($photosUploades as $photo) {
                $stmt->execute([
                    'bien_id' => $bienId,
                    'file_path' => $photo['file_path'],
                    'is_principal' => !empty($photo['is_principal']),
                ]);
            }
        }

        // Le QR Code ne doit pas bloquer la publication.
        try {
            QrGenerator::generate($bienId);
        } catch (\Throwable $qrError) {
            error_log('QR agence : ' . $qrError->getMessage());
        }

        $success = 'Votre annonce a été envoyée pour modération.';

        if (!empty($photosUploades)) {
            $success .= ' ' . count($photosUploades) . ' photo(s) enregistrée(s).';
        }

        $success .= ' Elle sera visible publiquement après validation.';

    } catch (\Throwable $e) {
        error_log('[IMMO-SN] Publication agence : ' . $e->getMessage());
        $error = $e->getMessage();
    }

    // Réaffichage direct du formulaire, sans redirection.
    $this->view('pages/agences/publier', [
        'title' => 'Publier un bien — Espace agence',
        'agence' => $agence,
        'categories' => $categories,
        'villes' => $villes,
        'success' => $success,
        'error' => $error,
    ]);
}


    /**
     * Télécharger le QR code d'un bien appartenant à l'agence connectée.
     */
    public function telechargerQr(string $id): void
    {
        Session::requireAuth('/immo-sn/login');

        $utilisateur = Session::user();
        $userId = Session::userId();

        if (
            !$utilisateur ||
            ($utilisateur['role'] ?? '') !== 'agence'
        ) {
            http_response_code(403);
            exit('Accès réservé aux agences.');
        }

        if (!$userId) {
            header('Location: /immo-sn/login');
            exit;
        }

        if (!Bien::appartientA($id, $userId)) {
            http_response_code(404);
            exit('Bien introuvable ou accès non autorisé.');
        }

        $qrCode = Bien::qrCode($id);

        if (!$qrCode || empty($qrCode['file_path'])) {
            http_response_code(404);
            exit('QR code introuvable.');
        }

        $qrDirectory = realpath(BASE_PATH . '/stockage/qrcodes');

        if ($qrDirectory === false) {
            http_response_code(404);
            exit('Dossier des QR codes introuvable.');
        }

        $fileName = basename((string) $qrCode['file_path']);

        $filePath = realpath(
            $qrDirectory . DIRECTORY_SEPARATOR . $fileName
        );

        if (
            $filePath === false ||
            !is_file($filePath) ||
            !str_starts_with(
                strtolower($filePath),
                strtolower($qrDirectory . DIRECTORY_SEPARATOR)
            )
        ) {
            http_response_code(404);
            exit('Fichier QR code introuvable.');
        }

        if (ob_get_level() > 0) {
            ob_clean();
        }

        header('Content-Type: image/png');
        header(
            'Content-Disposition: attachment; filename="' .
            $fileName . '"'
        );
        header('Content-Length: ' . filesize($filePath));
        header('X-Content-Type-Options: nosniff');

        readfile($filePath);
        exit;
    }
}
