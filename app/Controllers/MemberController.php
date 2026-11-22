<?php
namespace App\Controllers;
use Core\Controller;
use Core\Database;
use Core\Uploader;
use Core\QrGenerator;
use Core\Session;
use Core\Validator;

use App\Models\Bien;
use App\Models\Message;
use App\Models\User;
use App\Models\Favori;
use App\Models\DemandeRenouvellement;

use PDO;

class MemberController extends Controller
{
    /**
     * =========================================================
     * VÉRIFICATION UTILISATEUR CONNECTÉ
     * =========================================================
     */
    private function utilisateurConnecte(): string
    {
        Session::requireAuth('/immo-sn/login');

        $userId = Session::userId();

        if (!$userId) {
            Session::destroy();

            header('Location: /immo-sn/login');
            exit;
        }

        return $userId;
    }

    /**
     * =========================================================
     * TABLEAU DE BORD MEMBRE
     * =========================================================
     */
    public function dashboard(): void
    {
        $userId = $this->utilisateurConnecte();

        $user = User::findById($userId);

        if (!$user) {
            Session::destroy();

            header('Location: /immo-sn/login');
            exit;
        }

        // Statistiques.
        $nombreBiens = Bien::countByUser($userId);
        $nombreVues = Bien::totalVuesByUser($userId);
        $nombreFavoris = Bien::countFavorisByUser($userId);
        $activite30Jours = Bien::vuesDerniers30Jours($userId);

        $db = Database::getConnection();

        // Nombre d'alertes actives.
        $stmtAlertes = $db->prepare('
            SELECT COUNT(*)
            FROM alertes
            WHERE user_id = :user_id
              AND is_active = TRUE
        ');

        $stmtAlertes->execute([
            'user_id' => $userId
        ]);

        $nombreAlertes = (int) $stmtAlertes->fetchColumn();

        // Nombre total de messages reçus.
        $messagesRecus = Message::recus($userId);
        $nombreMessages = count($messagesRecus);

        // Nombre de messages non lus.
        $nombreMessagesNonLus = 0;

        foreach ($messagesRecus as $message) {
            if (empty($message['lu'])) {
                $nombreMessagesNonLus++;
            }
        }

        $stats = [
            'biens' => $nombreBiens,
            'favoris' => $nombreFavoris,
            'alertes' => $nombreAlertes,
            'messages' => $nombreMessages,
            'messages_non_lus' => $nombreMessagesNonLus,
            'vues' => $nombreVues,
            'activite30Jours' => $activite30Jours
        ];

        // Derniers biens.
        $derniersBiens = Bien::derniersParUtilisateur(
            $userId,
            5
        );

        // Dernières alertes.
        $stmtDernieresAlertes = $db->prepare('
            SELECT
                a.id,
                a.category_id,
                a.ville_id,
                a.budget_max,
                a.is_active,
                a.created_at,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom

            FROM alertes a

            LEFT JOIN categories_biens c
                ON c.id = a.category_id

            LEFT JOIN villes v
                ON v.id = a.ville_id

            WHERE a.user_id = :user_id

            ORDER BY a.created_at DESC

            LIMIT 3
        ');

        $stmtDernieresAlertes->execute([
            'user_id' => $userId
        ]);

        $dernieresAlertes = $stmtDernieresAlertes
            ->fetchAll(PDO::FETCH_ASSOC);

        $this->view('pages/member/dashboard', [
            'title' => 'Mon espace — ImmoSn',
            'user' => $user,
            'stats' => $stats,
            'derniersBiens' => $derniersBiens,
            'dernieresAlertes' => $dernieresAlertes,
        ]);
    }

    /**
     * =========================================================
     * MES BIENS
     * =========================================================
     */
    public function mesBiens(): void
    {
        $userId = $this->utilisateurConnecte();

        $success = Session::getFlash('publication_success');
        $error = Session::getFlash('publication_error');

        try {
            $biens = Bien::parUtilisateur($userId);

            if (!is_array($biens)) {
                $biens = [];
            }

            $renouvellements =
                DemandeRenouvellement::statutsParBiensUtilisateur($userId);

            $this->view(
                'pages/member/mes-biens',
                [
                    'title' => 'Mes biens — ImmoSn',
                    'biens' => $biens,
                    'success' => $success,
                    'error' => $error,
                    'renouvellements' => $renouvellements,
                ]
            );

        } catch (\Throwable $e) {
            error_log(
                'Erreur mesBiens : ' . $e->getMessage()
            );

            $this->view(
                'pages/member/mes-biens',
                [
                    'title' => 'Mes biens — ImmoSn',
                    'biens' => [],
                    'success' => $success,
                    'error' => 'Impossible de récupérer vos annonces pour le moment.',
                    'renouvellements' => [],
                ]
            );
        }
    }

    /**
     * =========================================================
     * MESSAGERIE
     * =========================================================
     */
    public function messages(): void
    {
        $userId = $this->utilisateurConnecte();

        $messagesRecus = Message::recus($userId);

        $messages = [];

        foreach ($messagesRecus as $message) {
            $prenom = trim(
                $message['expediteur_prenom'] ?? ''
            );

            $nom = trim(
                $message['expediteur_nom'] ?? ''
            );

            $nomComplet = trim(
                $prenom . ' ' . $nom
            );

            if ($nomComplet === '') {
                $nomComplet = 'Utilisateur';
            }

            $date = '';
            $heure = '';

            if (!empty($message['created_at'])) {
                $timestamp = strtotime(
                    $message['created_at']
                );

                if ($timestamp !== false) {
                    $date = date('d/m/Y', $timestamp);
                    $heure = date('H:i', $timestamp);
                }
            }

            $messages[] = [
                'id' => $message['id'],
                'nom' => $nomComplet,
                'objet' => $message['sujet'] ?? 'Message',
                'message' => $message['contenu'] ?? '',
                'date' => $date,
                'heure' => $heure,
                'lu' => !empty($message['lu']),
                'bien_id' => $message['bien_id'] ?? null,
                'bien_titre' => $message['bien_titre'] ?? '',
            ];
        }

        $this->view(
            'pages/member/messages',
            [
                'title' => 'Messagerie — ImmoSn',
                'messages' => $messages,
            ]
        );
    }

    /**
     * =========================================================
     * MES ALERTES
     * =========================================================
     */
    public function alertes(): void
    {
        $userId = $this->utilisateurConnecte();

        $db = Database::getConnection();

        $stmt = $db->prepare('
            SELECT
                a.id,
                a.category_id,
                a.ville_id,
                a.budget_max,
                a.is_active,
                a.created_at,

                c.libelle AS categorie_nom,

                v.ville AS ville_nom,
                v.quartier AS quartier_nom

            FROM alertes a

            LEFT JOIN categories_biens c
                ON c.id = a.category_id

            LEFT JOIN villes v
                ON v.id = a.ville_id

            WHERE a.user_id = :user_id

            ORDER BY a.created_at DESC
        ');

        $stmt->execute([
            'user_id' => $userId
        ]);

        $alertesDb = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $alertes = [];

        foreach ($alertesDb as $alerte) {
            $categorie = trim(
                $alerte['categorie_nom'] ?? ''
            );

            $ville = trim(
                $alerte['ville_nom'] ?? ''
            );

            $quartier = trim(
                $alerte['quartier_nom'] ?? ''
            );

            $descriptionParts = [];

            if ($categorie !== '') {
                $descriptionParts[] =
                    'Catégorie : ' . $categorie;
            }

            if ($ville !== '') {
                $descriptionParts[] =
                    'Ville : ' . $ville;
            }

            if ($quartier !== '') {
                $descriptionParts[] =
                    'Quartier : ' . $quartier;
            }

            if (
                isset($alerte['budget_max'])
                && $alerte['budget_max'] !== null
            ) {
                $descriptionParts[] =
                    'Budget max : ' .
                    number_format(
                        (float) $alerte['budget_max'],
                        0,
                        ',',
                        ' '
                    ) .
                    ' FCFA';
            }

            $description = !empty($descriptionParts)
                ? implode(' — ', $descriptionParts)
                : 'Aucun critère particulier.';

            $date = '';

            if (!empty($alerte['created_at'])) {
                $timestamp = strtotime(
                    $alerte['created_at']
                );

                if ($timestamp !== false) {
                    $date = date('d/m/Y', $timestamp);
                }
            }

            $alertes[] = [
                'id' => $alerte['id'],

                'titre' =>
                    $categorie !== ''
                        ? 'Alerte ' . $categorie
                        : 'Nouvelle alerte',

                'description' => $description,
                'date' => $date,

                // La table alertes ne possède pas de colonne "lu".
                'lu' => empty($alerte['is_active']),

                'type' => 'recherche',

                'is_active' =>
                    !empty($alerte['is_active']),
            ];
        }

        $this->view('pages/member/alertes', [
            'title' => 'Mes alertes — ImmoSn',
            'alertes' => $alertes,
        ]);
    }

    /**
     * =========================================================
     * PUBLIER UN BIEN
     * GET  : affiche le formulaire
     * POST : enregistre le bien, les photos et le QR Code
     * =========================================================
     */
   
public function publier(): void
{
    $userId = $this->utilisateurConnecte();

    $categories = Bien::categories();
    $villes = Bien::villes();

    // Affichage du formulaire.
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $this->view(
            'pages/member/publier',
            [
                'title' => 'Publier un bien — ImmoSn',
                'categories' => $categories,
                'villes' => $villes,
                'success' => Session::getFlash('publication_success'),
                'error' => Session::getFlash('publication_error'),
            ]
        );

        return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    try {
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $categoryId = !empty($_POST['category_id'])
            ? (int) $_POST['category_id']
            : null;

        $villeId = !empty($_POST['ville_id'])
            ? (int) $_POST['ville_id']
            : null;

        $prix = !empty($_POST['prix'])
            ? (float) $_POST['prix']
            : null;

        $surface = !empty($_POST['surface'])
            ? (float) $_POST['surface']
            : null;

        $pieces = !empty($_POST['pieces'])
            ? (int) $_POST['pieces']
            : null;

        $chambres = !empty($_POST['chambres'])
            ? (int) $_POST['chambres']
            : null;

        $sallesBain = !empty($_POST['salles_bain'])
            ? (int) $_POST['salles_bain']
            : null;

        $latitude = !empty($_POST['latitude'])
            ? (float) $_POST['latitude']
            : null;

        $longitude = !empty($_POST['longitude'])
            ? (float) $_POST['longitude']
            : null;

        $typeOffre = trim($_POST['type_offre'] ?? '');

        if (!in_array($typeOffre, ['vente', 'location'], true)) {
            $typeOffre = 'vente';
        }

        // Validations.
        if ($titre === '') {
            throw new \Exception('Le titre du bien est obligatoire.');
        }

        if ($description === '') {
            throw new \Exception('La description du bien est obligatoire.');
        }

        if ($categoryId === null) {
            throw new \Exception('Veuillez sélectionner une catégorie.');
        }

        if ($villeId === null) {
            throw new \Exception('Veuillez sélectionner une ville.');
        }

        if ($prix === null || $prix <= 0) {
            throw new \Exception('Veuillez saisir un prix valide.');
        }

        if ($surface === null || $surface <= 0) {
            throw new \Exception('Veuillez saisir une surface valide.');
        }

        if ($latitude !== null && ($latitude < -90 || $latitude > 90)) {
            throw new \Exception('La latitude est invalide.');
        }

        if ($longitude !== null && ($longitude < -180 || $longitude > 180)) {
            throw new \Exception('La longitude est invalide.');
        }

        // Préparation des données.
        $data = [
            'user_id' => $userId,
            'category_id' => $categoryId,
            'ville_id' => $villeId,
            'Titre' => $titre,
            'Description' => $description,
            'type_offre' => $typeOffre,
            'Prix' => $prix,
            'Surface' => $surface,
            'Pièces' => $pieces,
            'Chambres' => $chambres,
            'salles_de_bain' => $sallesBain,
            'Latitude' => $latitude,
            'Longitude' => $longitude,
            'Statut' => 'en_attente',
            'nb_vues' => 0,
            'expire_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Enregistrement du bien.
        $bienId = Bien::publier($data);

        // Upload des photos.
        $photosUploades = [];

        if (isset($_FILES['photos']) && is_array($_FILES['photos'])) {
            $photosUploades = Uploader::uploadPhotos(
                $_FILES['photos'],
                $bienId
            );
        }

        // Enregistrement des photos en base.
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
                $stmt->bindValue(':bien_id', $bienId, \PDO::PARAM_STR);
                $stmt->bindValue(':file_path', $photo['file_path'], \PDO::PARAM_STR);
                $stmt->bindValue(
                    ':is_principal',
                    !empty($photo['is_principal']) ? 'true' : 'false',
                    \PDO::PARAM_STR
                );

                $stmt->execute();
            }
        }

        // Le QR Code ne doit pas annuler une publication déjà enregistrée.
        try {
            QrGenerator::generate($bienId);
        } catch (\Throwable $qrError) {
            error_log(
                '[IMMO-SN] Erreur génération QR du bien '
                . $bienId . ' : ' . $qrError->getMessage()
            );
        }

        // Message de confirmation.
        $messageSucces = 'Votre annonce a été envoyée pour modération.';

        if (!empty($photosUploades)) {
            $messageSucces .= ' '
                . count($photosUploades)
                . ' photo(s) enregistrée(s).';
        }

        Session::flash(
            'publication_success',
            $messageSucces . ' Elle sera visible publiquement après validation.'
        );

        header('Location: /immo-sn/member/publier');
        exit;

    } catch (\PDOException $e) {
        error_log('[IMMO-SN] Erreur SQL publication membre : ' . $e->getMessage());

        Session::flash(
            'publication_error',
            'Une erreur est survenue lors de l’enregistrement. Veuillez réessayer.'
        );

        header('Location: /immo-sn/member/publier');
        exit;

    } catch (\Throwable $e) {
        error_log('[IMMO-SN] Erreur publication membre : ' . $e->getMessage());

        $message = $e instanceof \Exception
            ? $e->getMessage()
            : 'Une erreur inattendue est survenue.';

        Session::flash('publication_error', $message);

        header('Location: /immo-sn/member/publier');
        exit;
    }
}

    /**
     * =========================================================
     * AJOUTER UN FAVORI
     * =========================================================
     */
    public function ajouterFavori(): void
    {
        $userId = $this->utilisateurConnecte();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        $bienId = trim($_POST['bien_id'] ?? '');

        if ($bienId === '') {
            header('Location: /immo-sn/biens');
            exit;
        }

        try {
            Favori::ajouter($userId, $bienId);
        } catch (\Throwable $e) {
            error_log(
                'Erreur ajout favori : ' . $e->getMessage()
            );
        }

        $retour = $_POST['retour'] ?? '/immo-sn/biens';

        // Autoriser uniquement les chemins internes au projet.
        if (
            !is_string($retour)
            || !str_starts_with($retour, '/immo-sn/')
            || str_starts_with($retour, '//')
        ) {
            $retour = '/immo-sn/biens';
        }

        header('Location: ' . $retour);
        exit;
    }

    /**
     * =========================================================
     * RETIRER UN FAVORI
     * =========================================================
     */
    public function retirerFavori(): void
    {
        $userId = $this->utilisateurConnecte();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        $bienId = trim($_POST['bien_id'] ?? '');

        if ($bienId === '') {
            header('Location: /immo-sn/member/favoris');
            exit;
        }

        try {
            Favori::retirer($userId, $bienId);
        } catch (\Throwable $e) {
            error_log(
                'Erreur retrait favori : ' . $e->getMessage()
            );
        }

        header('Location: /immo-sn/member/favoris');
        exit;
    }

    /**
     * =========================================================
     * MES FAVORIS
     * =========================================================
     */
    public function favoris(): void
    {
        $userId = $this->utilisateurConnecte();

        $favoris = Bien::favorisParUtilisateur($userId);

        $this->view(
            'pages/member/favoris',
            [
                'title' => 'Mes favoris — ImmoSn',
                'favoris' => $favoris,
            ]
        );
    }

    /**
     * =========================================================
     * MON PROFIL — AFFICHAGE ET MODIFICATION
     * =========================================================
     */
    public function profil(): void
    {
        $userId = $this->utilisateurConnecte();
        $db = Database::getConnection();

        $success = null;
        $error = null;

        $passwordSuccess = Session::getFlash('password_success');
        $passwordError = Session::getFlash('password_error');

        // Traitement du formulaire.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prenom = trim($_POST['prenom'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $ville = trim($_POST['ville'] ?? '');

            $nouveauFichier = null;
            $anciennePhoto = null;
            $photoChemin = null;

            try {
                // 1. Validation des informations personnelles.
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
                        'Le prénom ou le nom est trop long.'
                    );
                }

                // 2. Vérifier si l'e-mail est déjà utilisé.
                $stmtEmail = $db->prepare('
                    SELECT id
                    FROM public.users
                    WHERE LOWER(email) = LOWER(:email)
                      AND id <> CAST(:id AS UUID)
                    LIMIT 1
                ');

                $stmtEmail->execute([
                    'email' => $email,
                    'id' => $userId,
                ]);

                if ($stmtEmail->fetchColumn() !== false) {
                    throw new \RuntimeException(
                        'Cette adresse e-mail est déjà utilisée.'
                    );
                }

                // 3. Vérifier si une nouvelle photo a été envoyée.
                if (
                    isset($_FILES['photo_profil'])
                    && is_array($_FILES['photo_profil'])
                    && ($_FILES['photo_profil']['error'] ?? UPLOAD_ERR_NO_FILE)
                        !== UPLOAD_ERR_NO_FILE
                ) {
                    $fichier = $_FILES['photo_profil'];

                    $codeErreur = (int) (
                        $fichier['error'] ?? UPLOAD_ERR_NO_FILE
                    );

                    if ($codeErreur !== UPLOAD_ERR_OK) {
                        throw new \RuntimeException(
                            'Une erreur est survenue pendant l’envoi de la photo.'
                        );
                    }

                    $tmpName = $fichier['tmp_name'] ?? '';
                    $taille = (int) ($fichier['size'] ?? 0);

                    if (!is_uploaded_file($tmpName)) {
                        throw new \RuntimeException(
                            'Le fichier envoyé n’est pas un upload valide.'
                        );
                    }

                    if ($taille <= 0 || $taille > 5 * 1024 * 1024) {
                        throw new \RuntimeException(
                            'La photo doit peser au maximum 5 Mo.'
                        );
                    }

                    // Vérification réelle du type de fichier.
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

                    // 4. Créer le dossier de stockage.
                    $basePath = defined('BASE_PATH')
                        ? BASE_PATH
                        : dirname(__DIR__, 2);

                    $dossier = $basePath . '/stockage/photos';

                    if (!is_dir($dossier)) {
                        if (
                            !mkdir($dossier, 0775, true)
                            && !is_dir($dossier)
                        ) {
                            throw new \RuntimeException(
                                'Impossible de créer le dossier de stockage des photos.'
                            );
                        }
                    }

                    // 5. Générer un nom de fichier unique.
                    $nomFichier = 'profil_'
                        . bin2hex(random_bytes(16))
                        . '.'
                        . $typesAutorises[$mime];

                    $destination = $dossier . '/' . $nomFichier;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        throw new \RuntimeException(
                            'Impossible d’enregistrer la photo sélectionnée.'
                        );
                    }

                    $nouveauFichier = $destination;

                    $photoChemin =
                        '/immo-sn/stockage/photos/' . $nomFichier;
                }

                // 6. Récupérer l'ancienne photo.
                $stmtAnciennePhoto = $db->prepare('
                    SELECT photo_profil
                    FROM public.users
                    WHERE id = CAST(:id AS UUID)
                    LIMIT 1
                ');

                $stmtAnciennePhoto->execute([
                    'id' => $userId,
                ]);

                $anciennePhoto = $stmtAnciennePhoto->fetchColumn();

                // 7. Enregistrer les informations et la photo.
                $stmtUpdate = $db->prepare('
                    UPDATE public.users
                    SET
                        prenom = :prenom,
                        nom = :nom,
                        email = :email,
                        telephone = :telephone,
                        ville = :ville,
                        photo_profil = COALESCE(:photo_profil, photo_profil),
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = CAST(:id AS UUID)
                ');

                $stmtUpdate->execute([
                    'prenom' => $prenom,
                    'nom' => $nom,
                    'email' => $email,
                    'telephone' => $telephone !== '' ? $telephone : null,
                    'ville' => $ville !== '' ? $ville : null,
                    'photo_profil' => $photoChemin,
                    'id' => $userId,
                ]);

                // 8. Supprimer l'ancienne photo après mise à jour.
                if (
                    $photoChemin !== null
                    && is_string($anciennePhoto)
                    && str_starts_with(
                        $anciennePhoto,
                        '/immo-sn/stockage/photos/'
                    )
                ) {
                    $ancienNom = basename($anciennePhoto);

                    $ancienChemin =
                        $dossier . DIRECTORY_SEPARATOR . $ancienNom;

                    if (
                        $ancienNom !== ''
                        && is_file($ancienChemin)
                        && $ancienChemin !== $nouveauFichier
                    ) {
                        @unlink($ancienChemin);
                    }
                }

                $success = $photoChemin !== null
                    ? 'Votre profil et votre photo ont été mis à jour avec succès.'
                    : 'Votre profil a été mis à jour avec succès.';

            } catch (\RuntimeException $e) {
                if (
                    $nouveauFichier !== null
                    && is_file($nouveauFichier)
                ) {
                    @unlink($nouveauFichier);
                }

                $error = $e->getMessage();

            } catch (\PDOException $e) {
                if (
                    $nouveauFichier !== null
                    && is_file($nouveauFichier)
                ) {
                    @unlink($nouveauFichier);
                }

                error_log(
                    'Erreur SQL modification profil : ' . $e->getMessage()
                );

                $error =
                    'Impossible d’enregistrer les modifications. Vérifiez les informations et réessayez.';

            } catch (\Throwable $e) {
                if (
                    $nouveauFichier !== null
                    && is_file($nouveauFichier)
                ) {
                    @unlink($nouveauFichier);
                }

                error_log(
                    'Erreur modification profil : ' . $e->getMessage()
                );

                $error = 'Une erreur inattendue est survenue.';
            }
        }

        // Récupérer le profil depuis la base.
        try {
            $stmtUser = $db->prepare('
                SELECT
                    id,
                    prenom,
                    nom,
                    email,
                    telephone,
                    ville,
                    role,
                    status,
                    photo_profil
                FROM public.users
                WHERE id = CAST(:id AS UUID)
                LIMIT 1
            ');

            $stmtUser->execute([
                'id' => $userId,
            ]);

            $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            error_log(
                'Erreur SQL récupération profil : ' . $e->getMessage()
            );

            http_response_code(500);
            exit('Impossible de charger le profil. Consultez le journal PHP.');
        }

        // Si le compte n'existe plus, fermer la session.
        if (!$user) {
            Session::destroy();

            header('Location: /immo-sn/login');
            exit;
        }

        // Préparer les données pour la vue.
        $profil = [
            'id' => $user['id'] ?? '',
            'prenom' => $user['prenom'] ?? '',
            'nom' => $user['nom'] ?? '',
            'email' => $user['email'] ?? '',
            'telephone' => $user['telephone'] ?? '',
            'ville' => $user['ville'] ?? '',
            'quartier' => '',
            'role' => $user['role'] ?? 'Membre',
            'status' => $user['status'] ?? '',
            'photo_profil' => $user['photo_profil'] ?? '',
        ];

        // Afficher la page.
        $this->view('pages/member/profil', [
            'title' => 'Mon profil — ImmoSn',
            'profil' => $profil,
            'success' => $success,
            'error' => $error,
            'passwordSuccess' => $passwordSuccess,
            'passwordError' => $passwordError,
        ]);
    }

    /**
     * =========================================================
     * CHANGER LE MOT DE PASSE
     * =========================================================
     */
    public function changerMotDePasse(): void
    {
        $userId = $this->utilisateurConnecte();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /immo-sn/member/profil');
            exit;
        }

        $ancien = $_POST['ancien_mot_de_passe'] ?? '';
        $nouveau = $_POST['nouveau_mot_de_passe'] ?? '';
        $confirmation = $_POST['confirmation_mot_de_passe'] ?? '';

        try {
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

            $db = Database::getConnection();

            // Récupérer le mot de passe actuel.
            $stmt = $db->prepare(
                'SELECT password
                 FROM public.users
                 WHERE id = CAST(:id AS UUID)
                 LIMIT 1'
            );

            $stmt->execute([
                'id' => $userId
            ]);

            $hashActuel = $stmt->fetchColumn();

            if (
                $hashActuel === false
                || !User::verifyPassword(
                    $ancien,
                    (string) $hashActuel
                )
            ) {
                throw new \RuntimeException(
                    'Votre ancien mot de passe est incorrect.'
                );
            }

            // Chiffrer le nouveau mot de passe.
            $nouveauHash = password_hash(
                $nouveau,
                PASSWORD_DEFAULT
            );

            if ($nouveauHash === false) {
                throw new \RuntimeException(
                    'Impossible de sécuriser le nouveau mot de passe.'
                );
            }

            // Enregistrer le nouveau mot de passe.
            $stmt = $db->prepare(
                'UPDATE public.users
                 SET password = :password,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = CAST(:id AS UUID)'
            );

            $stmt->execute([
                'password' => $nouveauHash,
                'id' => $userId
            ]);

            if ($stmt->rowCount() !== 1) {
                throw new \RuntimeException(
                    'Le changement de mot de passe n’a pas abouti.'
                );
            }

            Session::flash(
                'password_success',
                'Votre mot de passe a été modifié avec succès.'
            );

        } catch (\RuntimeException $e) {
            Session::flash(
                'password_error',
                $e->getMessage()
            );

        } catch (\Throwable $e) {
            error_log($e->getMessage());

            Session::flash(
                'password_error',
                'Une erreur est survenue. Veuillez réessayer.'
            );
        }

        header('Location: /immo-sn/member/profil');
        exit;
    }

    /**
     * =========================================================
     * MODIFIER UNE ANNONCE
     * GET  : afficher le formulaire prérempli
     * POST : enregistrer les corrections
     * =========================================================
     */
    public function modifierBien(string $id): void
    {
        $userId = $this->utilisateurConnecte();

        if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        try {
            // Vérifier que le bien appartient au membre connecté.
            if (!Bien::appartientA($id, $userId)) {
                http_response_code(403);
                exit('Vous ne pouvez pas modifier cette annonce.');
            }

            $bien = Bien::detail($id);

            if (!$bien) {
                http_response_code(404);
                exit('Annonce introuvable.');
            }

            $statut = mb_strtolower(
                trim((string) ($bien['statut'] ?? '')),
                'UTF-8'
            );

            $statutsModifiables = [
                'actif',
                'en_attente',
                'pending',
                'rejete',
                'rejeté',
                'refuse',
                'refusé',
                'expire',
                'expiré'
            ];

            if (!in_array($statut, $statutsModifiables, true)) {
                Session::flash(
                    'publication_error',
                    'Cette annonce ne peut pas être modifiée dans son état actuel.'
                );

                header('Location: /immo-sn/member/mes-biens');
                exit;
            }

            $categories = Bien::categories();
            $villes = Bien::villes();

            // Affichage du formulaire avec les données existantes.
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->view('pages/member/publier', [
                    'title' => 'Corriger mon annonce — ImmoSn',
                    'categories' => $categories,
                    'villes' => $villes,
                    'bien' => $bien,
                    'bienId' => $id,
                    'modeModification' => true,
                    'motifRejet' => $bien['motif_rejet'] ?? '',
                    'success' => null,
                    'error' => null,
                ]);

                return;
            }

            // Récupération des champs du formulaire.
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');

            $categoryId = filter_var(
                $_POST['category_id'] ?? null,
                FILTER_VALIDATE_INT
            );

            $villeId = filter_var(
                $_POST['ville_id'] ?? null,
                FILTER_VALIDATE_INT
            );

            $prix = $_POST['prix'] ?? '';
            $surface = $_POST['surface'] ?? '';
            $pieces = $_POST['pieces'] ?? '';
            $chambres = $_POST['chambres'] ?? '';
            $sallesBain = $_POST['salles_bain'] ?? '';
            $latitude = $_POST['latitude'] ?? '';
            $longitude = $_POST['longitude'] ?? '';
            $typeOffre = trim($_POST['type_offre'] ?? '');

            // Validation des champs obligatoires.
            if (
                !Validator::required($titre)
                || !Validator::required($description)
            ) {
                throw new \RuntimeException(
                    'Le titre et la description sont obligatoires.'
                );
            }

            if (
                $categoryId === false
                || $categoryId === null
                || $categoryId <= 0
            ) {
                throw new \RuntimeException(
                    'Veuillez choisir une catégorie.'
                );
            }

            if (
                $villeId === false
                || $villeId === null
                || $villeId <= 0
            ) {
                throw new \RuntimeException(
                    'Veuillez choisir une ville.'
                );
            }

            if (!is_numeric($prix) || (float) $prix <= 0) {
                throw new \RuntimeException(
                    'Veuillez saisir un prix valide.'
                );
            }

            if (!is_numeric($surface) || (float) $surface <= 0) {
                throw new \RuntimeException(
                    'Veuillez saisir une surface valide.'
                );
            }

            if (!in_array($typeOffre, ['vente', 'location'], true)) {
                throw new \RuntimeException(
                    'Le type d’offre sélectionné est invalide.'
                );
            }

            // Vérification des coordonnées si elles sont renseignées.
            $latitude = $latitude === '' ? null : $latitude;
            $longitude = $longitude === '' ? null : $longitude;

            if (
                $latitude !== null
                && (
                    !is_numeric($latitude)
                    || (float) $latitude < -90
                    || (float) $latitude > 90
                )
            ) {
                throw new \RuntimeException(
                    'La latitude est invalide.'
                );
            }

            if (
                $longitude !== null
                && (
                    !is_numeric($longitude)
                    || (float) $longitude < -180
                    || (float) $longitude > 180
                )
            ) {
                throw new \RuntimeException(
                    'La longitude est invalide.'
                );
            }

            // Les champs facultatifs vides restent à NULL.
            $convertirEntier = static function ($valeur): ?int {
                if ($valeur === '' || $valeur === null) {
                    return null;
                }

                if (
                    filter_var($valeur, FILTER_VALIDATE_INT) === false
                    || (int) $valeur < 0
                ) {
                    throw new \RuntimeException(
                        'Le nombre de pièces, chambres ou salles de bain est invalide.'
                    );
                }

                return (int) $valeur;
            };

            $data = [
                'category_id' => $categoryId,
                'ville_id' => $villeId,
                'titre' => $titre,
                'description' => $description,
                'type_offre' => $typeOffre,
                'prix' => (float) $prix,
                'superficie' => (float) $surface,
                'pieces' => $convertirEntier($pieces),
                'chambres' => $convertirEntier($chambres),
                'salles_de_bain' => $convertirEntier($sallesBain),
                'latitude' => $latitude === null ? null : (float) $latitude,
                'longitude' => $longitude === null ? null : (float) $longitude,
            ];

            // Vérifier le propriétaire et enregistrer les modifications.
            if (!Bien::modifierBienProprietaire($id, $userId, $data)) {
                throw new \RuntimeException(
                    'Les modifications n’ont pas été enregistrées. Vérifiez le statut de l’annonce.'
                );
            }

            Session::flash(
                'publication_success',
                'Votre annonce a été modifiée avec succès. Elle est en attente de validation si une modération est nécessaire.'
            );

            header('Location: /immo-sn/member/mes-biens');
            exit;

        } catch (\RuntimeException $e) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $bien = Bien::detail($id);

                $this->view('pages/member/publier', [
                    'title' => 'Corriger mon annonce — ImmoSn',
                    'categories' => Bien::categories(),
                    'villes' => Bien::villes(),
                    'bien' => $bien ?? [],
                    'bienId' => $id,
                    'modeModification' => true,
                    'motifRejet' => $bien['motif_rejet'] ?? '',
                    'success' => null,
                    'error' => $e->getMessage(),
                ]);

                return;
            }

            throw $e;
        }
    }

    /**
     * =========================================================
     * SUPPRIMER UNE ANNONCE
     * Suppression réservée au propriétaire
     * =========================================================
     */
    public function supprimerBien(string $id): void
    {
        $userId = $this->utilisateurConnecte();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        try {
            $db = Database::getConnection();

            // Vérifier la propriété avant toute suppression.
            if (!Bien::appartientA($id, $userId)) {
                http_response_code(403);
                exit('Vous ne pouvez pas supprimer cette annonce.');
            }

            $db->beginTransaction();

            $stmt = $db->prepare('
                DELETE FROM public.biens
                WHERE id = CAST(:bien_id AS UUID)
                  AND user_id = CAST(:user_id AS UUID)
            ');

            $stmt->execute([
                'bien_id' => $id,
                'user_id' => $userId,
            ]);

            if ($stmt->rowCount() !== 1) {
                throw new \RuntimeException(
                    'L’annonce n’a pas pu être supprimée.'
                );
            }

            $db->commit();

            Session::flash(
                'publication_success',
                'Votre annonce a été supprimée avec succès.'
            );

            header('Location: /immo-sn/member/mes-biens');
            exit;

        } catch (\Throwable $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }

            error_log(
                'Erreur suppression annonce : ' . $e->getMessage()
            );

            Session::flash(
                'publication_error',
                'Impossible de supprimer cette annonce pour le moment.'
            );

            header('Location: /immo-sn/biens/' . rawurlencode($id));
            exit;
        }
    }

    /**
     * =========================================================
     * DEMANDE DE RENOUVELLEMENT D'UNE ANNONCE EXPIRÉE
     * =========================================================
     */
    public function demanderRenouvellement(string $id): void
    {
        $userId = $this->utilisateurConnecte();

        // Vérifier que la requête utilise POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        // Vérifier le jeton de sécurité CSRF.
        if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
            Session::flash(
                'publication_error',
                'Votre session a expiré. Veuillez réessayer.'
            );

            header('Location: /immo-sn/member/mes-biens');
            exit;
        }

        try {
            DemandeRenouvellement::soumettre($id, $userId);

            Session::flash(
                'publication_success',
                'Votre demande de renouvellement a été envoyée. Elle sera examinée par notre équipe.'
            );

            header('Location: /immo-sn/member/mes-biens');
            exit;

        } catch (\RuntimeException $e) {
            Session::flash(
                'publication_error',
                $e->getMessage()
            );

            header('Location: /immo-sn/member/mes-biens');
            exit;

        } catch (\Throwable $e) {
            error_log(
                'Erreur demande renouvellement : ' . $e->getMessage()
            );

            Session::flash(
                'publication_error',
                'Impossible d’envoyer votre demande pour le moment. Veuillez réessayer.'
            );

            header('Location: /immo-sn/member/mes-biens');
            exit;
        }
    }

    /**
     * =========================================================
     * TÉLÉCHARGER LE QR CODE D'UN BIEN
     * =========================================================
     */
    public function telechargerQr(string $id): void
    {
        $userId = $this->utilisateurConnecte();

        // Vérifier que le bien appartient au membre connecté.
        if (!Bien::appartientA($id, $userId)) {
            http_response_code(404);
            exit('Bien introuvable ou accès non autorisé.');
        }

        // Récupérer le QR code enregistré.
        $qrCode = Bien::qrCode($id);

        if (!$qrCode || empty($qrCode['file_path'])) {
            http_response_code(404);
            exit('QR code introuvable.');
        }

        $qrDirectory = realpath(
            BASE_PATH . '/stockage/qrcodes'
        );

        if ($qrDirectory === false) {
            http_response_code(404);
            exit('Dossier des QR codes introuvable.');
        }

        $fileName = basename(
            (string) $qrCode['file_path']
        );

        $filePath = realpath(
            $qrDirectory . DIRECTORY_SEPARATOR . $fileName
        );

        // Vérifier que le fichier existe et reste dans le dossier QR.
        if (
            $filePath === false
            || !is_file($filePath)
            || !str_starts_with(
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

    /**
     * =========================================================
     * TÉLÉCHARGER TOUS LES QR CODES DANS UN ZIP
     * =========================================================
     */
    public function telechargerQrZip(): void
    {
        $userId = $this->utilisateurConnecte();

        // Récupérer uniquement les biens du membre connecté.
        $biens = Bien::parUtilisateur($userId);

        if (empty($biens)) {
            http_response_code(404);
            exit('Vous ne possédez aucun bien.');
        }

        // Vérifier que l'extension ZIP est disponible.
        if (!class_exists(\ZipArchive::class)) {
            http_response_code(500);
            exit('Le téléchargement ZIP n’est pas disponible sur le serveur.');
        }

        // Vérifier le dossier contenant les QR codes.
        $qrDirectory = realpath(
            BASE_PATH . '/stockage/qrcodes'
        );

        if ($qrDirectory === false) {
            http_response_code(404);
            exit('Dossier des QR codes introuvable.');
        }

        // Créer une archive temporaire.
        $zipPath = tempnam(
            sys_get_temp_dir(),
            'immo_qr_'
        );

        if ($zipPath === false) {
            http_response_code(500);
            exit('Impossible de créer l’archive ZIP.');
        }

        $zip = new \ZipArchive();

        if (
            $zip->open(
                $zipPath,
                \ZipArchive::CREATE | \ZipArchive::OVERWRITE
            ) !== true
        ) {
            @unlink($zipPath);

            http_response_code(500);
            exit('Impossible d’ouvrir l’archive ZIP.');
        }

        $nombreAjoute = 0;

        foreach ($biens as $bien) {
            $bienId = (string) ($bien['id'] ?? '');

            if ($bienId === '') {
                continue;
            }

            // Récupérer le QR code du bien.
            $qrCode = Bien::qrCode($bienId);

            if (!$qrCode || empty($qrCode['file_path'])) {
                continue;
            }

            // Résoudre le fichier dans le dossier autorisé.
            $fileName = basename(
                (string) $qrCode['file_path']
            );

            $filePath = realpath(
                $qrDirectory . DIRECTORY_SEPARATOR . $fileName
            );

            if (
                $filePath === false
                || !is_file($filePath)
                || !str_starts_with(
                    strtolower($filePath),
                    strtolower($qrDirectory . DIRECTORY_SEPARATOR)
                )
            ) {
                continue;
            }

            $nomFichier = 'QR_' .
                preg_replace('/[^a-zA-Z0-9_-]/', '_', $bienId) .
                '.png';

            if ($zip->addFile($filePath, $nomFichier)) {
                $nombreAjoute++;
            }
        }

        $zip->close();

        if ($nombreAjoute === 0) {
            @unlink($zipPath);

            http_response_code(404);
            exit('Aucun QR code valide à télécharger.');
        }

        // Préparer le téléchargement.
        $nomZip = 'QR_Codes_Mes_Biens.zip';

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/zip');

        header(
            'Content-Disposition: attachment; filename="' . $nomZip . '"'
        );

        header('Content-Length: ' . filesize($zipPath));
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('X-Content-Type-Options: nosniff');

        readfile($zipPath);

        @unlink($zipPath);
        exit;
    }
}
