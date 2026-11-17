<?php

namespace App\Controllers;

use Core\Controller;
use Core\Database;
use App\Models\Bien;
use App\Models\Favori;
use Core\Session;

class BienController extends Controller
{
    /**
     * Liste des biens avec filtres et pagination
     */
    public function index(): void
    {
        $filtres = [
            'q'           => trim($_GET['q'] ?? ''),
            'type_offre'  => $_GET['type_offre'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'ville_id'    => $_GET['ville_id'] ?? '',
            'chambres'    => $_GET['chambres'] ?? '',
            'prix_min'    => $_GET['prix_min'] ?? '',
            'prix_max'    => $_GET['prix_max'] ?? '',
            'agence'      => trim($_GET['agence'] ?? ''),
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));

        $tri = $_GET['tri'] ?? 'recent';

        $trisAutorises = [
            'recent',
            'ancien',
            'prix_asc',
            'prix_desc',
            'surface_desc',
        ];

        if (!in_array($tri, $trisAutorises, true)) {
            $tri = 'recent';
        }

        $resultat = Bien::search($filtres, $page, 12, $tri);

        $categories = Bien::categories();
        $villes = Bien::villes();

        $this->view('pages/biens/index', [
            'title'      => 'Tous les biens — ImmoSn',
            'biens'      => $resultat['biens'],
            'total'      => $resultat['total'],
            'pages'      => $resultat['pages'],
            'page'       => $resultat['page'],
            'filtres'    => $filtres,
            'tri'        => $tri,
            'categories' => $categories,
            'villes'     => $villes,
        ]);
    }
    /**
 * Télécharger le QR code d'un bien.
 * Seul le propriétaire peut effectuer le téléchargement.
 */
public function telechargerQr(string $id): void
{
    // 1. Vérifier que l'utilisateur est connecté.
    $userId = Session::userId();

    if (empty($userId)) {
        http_response_code(401);
        exit('Vous devez être connecté pour télécharger ce QR code.');
    }

    // 2. Vérifier que le bien appartient à l'utilisateur connecté.
    if (!Bien::appartientA($id, (string) $userId)) {
        http_response_code(403);
        exit('Vous n\'êtes pas autorisé à télécharger ce QR code.');
    }

    // 3. Récupérer le QR code du bien.
    $qrCode = Bien::qrCode($id);

    if (!$qrCode || empty($qrCode['file_path'])) {
        http_response_code(404);
        exit('QR code introuvable.');
    }

    // 4. Sécuriser le chemin du fichier.
    $nomFichier = basename((string) $qrCode['file_path']);

    $dossier = realpath(BASE_PATH . '/stockage/qrcodes');

    if ($dossier === false || $nomFichier === '') {
        http_response_code(404);
        exit('Fichier QR code introuvable.');
    }

    $cheminFichier = realpath(
        $dossier . DIRECTORY_SEPARATOR . $nomFichier
    );

    if (
        $cheminFichier === false
        || dirname($cheminFichier) !== $dossier
        || !is_file($cheminFichier)
        || !is_readable($cheminFichier)
    ) {
        http_response_code(404);
        exit('Fichier QR code introuvable.');
    }

    // 5. Envoyer le fichier au navigateur.
    $nomTelechargement = 'qr-code-bien-' . $id . '.png';

    header('Content-Type: image/png');
    header('Content-Length: ' . filesize($cheminFichier));
    header(
        'Content-Disposition: attachment; filename="' .
        $nomTelechargement . '"'
    );
    header('Cache-Control: private, no-store, no-cache, must-revalidate');

    readfile($cheminFichier);
    exit;
}

    /**
     * Détail d'un bien
     */
   /**
 * Détail d'un bien
 */
public function show(string $id): void
{
    // 1. Récupérer le bien
 // 1. Récupérer le bien
$bien = Bien::detail($id);

if (!$bien) {
    http_response_code(404);
    echo 'Bien introuvable';
    return;
}

// 2. Vérifier si l'utilisateur est le propriétaire
$userId = Session::userId();

$proprietaireId = (string) ($bien['user_id'] ?? '');

$isOwner = (
    $userId !== null
    && $userId !== ''
    && $proprietaireId !== ''
    && (string) $userId === $proprietaireId
);

// 3. Vérifier les droits administrateur et modérateur
$isAdminOrModerator = Session::isAdminOrModerator();

// 4. Vérifier le statut et la date d'expiration
$statut = strtolower(trim((string) ($bien['statut'] ?? '')));

$dateExpiration = $bien['expire_at'] ?? null;

$isExpired = (
    $statut === 'expire'
    || (
        !empty($dateExpiration)
        && strtotime((string) $dateExpiration) <= time()
    )
);

// 5. Refuser l'accès aux annonces expirées
// sauf au propriétaire, à l'administrateur et au modérateur.
if ($isExpired && !$isOwner && !$isAdminOrModerator) {
    http_response_code(404);
    echo 'Cette annonce n’est plus disponible.';
    return;
}

    // 2. Récupérer les photos
    $photos = Bien::photos($id);

    // 3. Récupérer le QR code
    $qrCode = Bien::qrCode($id);

    // 4. Enregistrer la consultation
    $db = Database::getConnection();

    $stmtVue = $db->prepare("
        INSERT INTO vues_biens (bien_id)
        VALUES (:bien_id)
    ");
    $stmtVue->execute([
        'bien_id' => $id,
    ]);

    $stmtCompteur = $db->prepare("
        UPDATE biens
        SET nb_vues = COALESCE(nb_vues, 0) + 1
        WHERE id = :id
    ");
    $stmtCompteur->execute([
        'id' => $id,
    ]);

    // 5. Identifier le membre connecté
    $userId = Session::userId();

    // 6. Vérifier si le membre est propriétaire du bien
    $proprietaireId = (string) ($bien['user_id'] ?? '');

    $isOwner = (
        $userId !== null
        && $userId !== ''
        && $proprietaireId !== ''
        && (string) $userId === $proprietaireId
    );

    // 7. Vérifier si l'annonce a été rejetée
    $statut = strtolower(trim((string) ($bien['statut'] ?? '')));

    $statutNormalise = strtr($statut, [
        'é' => 'e',
        'è' => 'e',
        'ê' => 'e',
        'à' => 'a',
        'û' => 'u',
    ]);

    $isRejected = in_array(
        $statutNormalise,
        ['rejete', 'refuse'],
        true
    );

    // 8. Le motif du rejet est réservé au propriétaire
    $motifRejet = $isOwner && $isRejected
        ? (string) ($bien['motif_rejet'] ?? '')
        : '';

   
    // 9. Vérifier si le bien est dans les favoris du membre connecté
    $isFavorite = false;

    if ($userId !== null && $userId !== '') {
        $isFavorite = Favori::existe(
            (string) $userId,
            $id
        );
    }

    // 10. Afficher le détail
    $this->view('pages/biens/show', [
        'title'      => $bien['titre'] . ' — ImmoSn',
        'bien'       => $bien,
        'photos'     => $photos,
        'qrCode'     => $qrCode,
        'isFavorite' => $isFavorite,
        'isOwner'    => $isOwner,
        'isRejected' => $isRejected,
        'motifRejet' => $motifRejet,
    ]);
}
    /**
     * Carte interactive
     */
    public function carte(): void
    {
        $biens = Bien::carte(50);

        $this->view('pages/biens/carte', [
            'title' => 'Carte Interactive — ImmoSn',
            'biens' => $biens,
        ]);
    }
}

