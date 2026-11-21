
<?php
/**
 * Vue : Détail d'un bien
 * Emplacement : app/Views/pages/biens/show.php
 * Design : IMMO-SN — Signature Premium
 */

/* =========================================================
   1. SÉCURISATION DES DONNÉES
========================================================= */

$bien = is_array($bien ?? null) ? $bien : [];
$photos = is_array($photos ?? null) ? $photos : [];
$qrCode = is_array($qrCode ?? null) ? $qrCode : [];

$isOwner = ($isOwner ?? false) === true;
$isRejected = ($isRejected ?? false) === true;

$motifRejet = $isOwner && $isRejected
    ? trim((string) ($motifRejet ?? $bien['motif_rejet'] ?? ''))
    : '';

if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

/* =========================================================
   2. INFORMATIONS PRINCIPALES
========================================================= */

$bienId = $bien['id'] ?? '';

$titre = trim((string) (
    $bien['titre'] ?? $bien['Titre'] ?? 'Bien immobilier'
));

$description = trim((string) (
    $bien['description'] ?? $bien['Description'] ?? ''
));

$typeOffre = trim((string) (
    $bien['type_offre'] ?? $bien['Type_offre'] ?? ''
));

$categorie = trim((string) ($bien['categorie_nom'] ?? ''));
$ville = trim((string) ($bien['ville_nom'] ?? ''));
$quartier = trim((string) ($bien['quartier_nom'] ?? ''));
$region = trim((string) ($bien['region_nom'] ?? ''));

$prix = $bien['prix'] ?? $bien['Prix'] ?? null;
$surface = $bien['surface'] ?? $bien['Surface'] ?? null;
$pieces = $bien['pieces'] ?? $bien['Pièces'] ?? null;
$chambres = $bien['chambres'] ?? $bien['Chambres'] ?? null;

$sallesBain = $bien['salles_de_bain']
    ?? $bien['salles_bain']
    ?? null;

$statut = trim((string) (
    $bien['statut'] ?? $bien['Statut'] ?? ''
));

/* =========================================================
   3. PROPRIÉTAIRE
========================================================= */

$proprioPrenom = trim((string) ($bien['proprio_prenom'] ?? ''));
$proprioNom = trim((string) ($bien['proprio_nom'] ?? ''));

$proprioNomComplet = trim(
    $proprioPrenom . ' ' . $proprioNom
);

if ($proprioNomComplet === '') {
    $proprioNomComplet = 'Propriétaire';
}

$proprioEmail = trim((string) ($bien['proprio_email'] ?? ''));

$telephone = trim((string) (
    $bien['proprio_tel']
    ?? $bien['proprio_telephone']
    ?? $bien['telephone']
    ?? $bien['tel']
    ?? ''
));

/* =========================================================
   4. COORDONNÉES GPS
========================================================= */

$latitude = isset($bien['latitude']) && is_numeric($bien['latitude'])
    ? (float) $bien['latitude']
    : null;

$longitude = isset($bien['longitude']) && is_numeric($bien['longitude'])
    ? (float) $bien['longitude']
    : null;

$hasCoordinates =
    $latitude !== null &&
    $longitude !== null &&
    $latitude >= -90 &&
    $latitude <= 90 &&
    $longitude >= -180 &&
    $longitude <= 180;

/* =========================================================
   5. ADRESSE
========================================================= */

$adresseParts = array_filter([
    $quartier,
    $ville,
    $region
]);

$adresse = implode(', ', $adresseParts);

if ($adresse === '') {
    $adresse = 'Localisation non renseignée';
}

/* =========================================================
   6. PRIX
========================================================= */

$prixFormate = '';

if ($prix !== null && $prix !== '' && is_numeric($prix)) {
    $prixFormate = number_format(
        (float) $prix,
        0,
        ',',
        ' '
    ) . ' FCFA';
}

/* =========================================================
   7. PHOTOS
========================================================= */

$photosValides = [];

foreach ($photos as $photo) {
    if (!is_array($photo)) {
        continue;
    }

    $filePath = trim((string) (
        $photo['file_path']
        ?? $photo['path']
        ?? $photo['url']
        ?? ''
    ));

    if ($filePath === '') {
        continue;
    }

    $photosValides[] = [
        'id' => $photo['id'] ?? null,
        'file_path' => $filePath,
        'is_principal' => !empty($photo['is_principal']),
    ];
}

$defaultImage = '/immo-sn/assets/images/placeholder-property.jpg';

$mainImage = $defaultImage;

if (!empty($photosValides)) {
    $mainImage = $photosValides[0]['file_path'];
}

/* =========================================================
   8. QR CODE
========================================================= */

$qrPath = '';
$qrFileName = '';
$qrUrl = '';

if (!empty($qrCode)) {
    $qrPath = trim((string) (
        $qrCode['file_path']
        ?? $qrCode['path']
        ?? $qrCode['url']
        ?? ''
    ));

    if ($qrPath !== '') {
        $qrFileName = basename($qrPath);

        if ($qrFileName !== '' && $qrFileName !== '.') {
            $qrUrl = '/immo-sn/stockage/qrcodes/'
                . rawurlencode($qrFileName);
        }
    }
}

/* =========================================================
   9. INITIALES DU PROPRIÉTAIRE
========================================================= */

$initiales = '';

if ($proprioPrenom !== '') {
    $initiales .= function_exists('mb_substr')
        ? mb_substr($proprioPrenom, 0, 1)
        : substr($proprioPrenom, 0, 1);
}

if ($proprioNom !== '') {
    $initiales .= function_exists('mb_substr')
        ? mb_substr($proprioNom, 0, 1)
        : substr($proprioNom, 0, 1);
}

$initiales = strtoupper($initiales !== '' ? $initiales : 'P');

/* =========================================================
   10. URL ET MESSAGES
========================================================= */

$currentUrl = '/immo-sn/biens/' . rawurlencode((string) $bienId);

$messageSuccess = isset($_GET['message'])
    && $_GET['message'] === 'success';

$messageError = isset($_GET['message'])
    && $_GET['message'] === 'error';

$signalementSuccess = isset($_GET['signalement'])
    && $_GET['signalement'] === 'success';

$signalementError = isset($_GET['signalement'])
    && $_GET['signalement'] === 'error';

$errorMessage = trim((string) ($_GET['error'] ?? ''));

$isFavorite = !empty($isFavorite);

/* =========================================================
   11. STATUT DE L'ANNONCE
========================================================= */

$statutLabel = $statut !== ''
    ? ucfirst(str_replace('_', ' ', $statut))
    : '';

$statutClass = 'bg-[#F3F0E8] text-[#64748B]';

if (in_array(strtolower($statut), ['actif', 'valide', 'validé'], true)) {
    $statutClass = 'bg-[#EAF4EC] text-[#276238]';
} elseif (in_array(strtolower($statut), ['en_attente', 'pending'], true)) {
    $statutClass = 'bg-[#FFF5DF] text-[#946B12]';
} elseif (in_array(strtolower($statut), ['rejete', 'rejeté', 'refuse'], true)) {
    $statutClass = 'bg-[#FFF0ED] text-[#A53C32]';
}
?>

<style>
/* =========================================================
   IMMO-SN — DÉTAIL D'UN BIEN
========================================================= */

.property-detail {
    color: #101D33;
}

.property-detail .premium-card {
    border: 1px solid #E7E4DC;
    background: #FFFFFF;
    border-radius: 22px;
    box-shadow: 0 5px 22px rgba(16, 29, 51, 0.035);
}

.property-detail .gold-icon {
    background: #FFF5D8;
    color: #9A7100;
}

.property-detail .gold-text {
    color: #9A7100;
}

.property-detail .gold-button {
    background: #F5B800;
    color: #101D33;
    transition: all .25s ease;
}

.property-detail .gold-button:hover {
    background: #E5A900;
    transform: translateY(-1px);
}

.property-detail .navy-button {
    background: #101D33;
    color: #FFFFFF;
    transition: all .25s ease;
}

.property-detail .navy-button:hover {
    background: #1C3150;
    transform: translateY(-1px);
}

.property-detail .soft-panel {
    background: #F7F6F1;
}

.property-detail .detail-thumbnail {
    outline: 2px solid transparent;
    outline-offset: -2px;
    transition: all .2s ease;
}

.property-detail .detail-thumbnail:hover,
.property-detail .detail-thumbnail.active {
    outline-color: #D7A200;
}

.property-detail .detail-thumbnail img {
    transition: transform .35s ease;
}

.property-detail .detail-thumbnail:hover img {
    transform: scale(1.06);
}

.property-detail .detail-modal {
    animation: detailModalIn .22s ease-out;
}

@keyframes detailModalIn {
    from {
        opacity: 0;
        transform: translateY(12px) scale(.985);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.property-detail .custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.property-detail .custom-scrollbar::-webkit-scrollbar-track {
    background: #F1F0EA;
    border-radius: 20px;
}

.property-detail .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #D8D2C4;
    border-radius: 20px;
}

.property-detail .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #B99B55;
}

@media (max-width: 640px) {
    .property-detail .premium-card {
        border-radius: 17px;
    }
}
</style>

<div class="property-detail min-h-screen bg-[#F5F3ED]">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="sticky top-0 z-40 border-b border-[#E7E4DC] bg-white/95 shadow-sm backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 md:px-6">

            <a href="/immo-sn/biens"
               class="group inline-flex items-center gap-2 text-sm font-semibold text-[#526477] transition hover:text-[#9A7100]">

                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F7F6F1] transition group-hover:bg-[#FFF5D8]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </span>

                <span class="hidden sm:inline">Retour aux biens</span>
                <span class="sm:hidden">Retour</span>
            </a>

            <div class="flex items-center gap-2">

                <!-- FAVORIS -->

                <form action="<?= e(
                    $isFavorite
                        ? '/immo-sn/member/favoris/retirer'
                        : '/immo-sn/member/favoris/ajouter'
                ) ?>" method="POST">

                    <input type="hidden" name="bien_id" value="<?= e($bienId) ?>">
                    <input type="hidden" name="retour" value="<?= e($currentUrl) ?>">

                    <button id="favoriteButton"
                            type="submit"
                            class="inline-flex h-10 items-center gap-2 rounded-xl border px-3 text-sm font-semibold shadow-sm transition
                            <?= $isFavorite
                                ? 'border-[#101D33] bg-[#101D33] text-white'
                                : 'border-[#E7E4DC] bg-white text-[#526477] hover:border-[#D7A200] hover:bg-[#FFF9E8]' ?>"
                            aria-label="<?= $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>"
                            title="<?= $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">

                        <svg id="favoriteIcon"
                             xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="<?= $isFavorite ? 'currentColor' : 'none' ?>"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M20.84 8.95c0 5.2-8.84 10.05-8.84 10.05S3.16 14.15 3.16 8.95A4.95 4.95 0 0 1 8.1 4c1.68 0 3.2.84 4.1 2.13A4.95 4.95 0 0 1 20.84 8.95Z"/>
                        </svg>

                        <span class="hidden sm:inline">
                            <?= $isFavorite ? 'Dans mes favoris' : 'Ajouter aux favoris' ?>
                        </span>
                    </button>
                </form>

                <!-- SIGNALEMENT -->

                <button id="openReportButton"
                        type="button"
                        class="inline-flex h-10 items-center gap-2 rounded-xl border border-[#E7E4DC] bg-white px-3 text-sm font-semibold text-[#A53C32] shadow-sm transition hover:border-[#E6C1BC] hover:bg-[#FFF5F3]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                    </svg>

                    <span class="hidden sm:inline">Signaler</span>
                </button>
            </div>
        </div>
    </header>

    <!-- =====================================================
         MESSAGES DE RETOUR
    ====================================================== -->

    <div class="mx-auto max-w-7xl px-4 pt-5 md:px-6">

        <?php if ($messageSuccess): ?>
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-[#B9DEC4] bg-[#F1FAF3] p-4 text-[#276238]">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-lg font-bold">✓</span>
                <div>
                    <p class="font-bold">Message envoyé</p>
                    <p class="mt-1 text-sm">Votre message a bien été envoyé au propriétaire.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($messageError): ?>
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-[#E6C1BC] bg-[#FFF5F3] p-4 text-[#8B332B]">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-lg font-bold">!</span>
                <div>
                    <p class="font-bold">Envoi impossible</p>
                    <p class="mt-1 text-sm"><?= e($errorMessage !== '' ? $errorMessage : 'Une erreur est survenue.') ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($signalementSuccess): ?>
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-[#B9DEC4] bg-[#F1FAF3] p-4 text-[#276238]">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-lg font-bold">✓</span>
                <div>
                    <p class="font-bold">Signalement enregistré</p>
                    <p class="mt-1 text-sm">Merci. Votre signalement sera examiné par notre équipe.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($signalementError): ?>
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-[#E6C1BC] bg-[#FFF5F3] p-4 text-[#8B332B]">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-lg font-bold">!</span>
                <div>
                    <p class="font-bold">Signalement impossible</p>
                    <p class="mt-1 text-sm"><?= e($errorMessage !== '' ? $errorMessage : 'Une erreur est survenue.') ?></p>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- =====================================================
         ESPACE PROPRIÉTAIRE
    ====================================================== -->

    <?php if ($isOwner): ?>

        <section class="mx-auto mb-6 max-w-7xl px-4 md:px-6">

            <div class="overflow-hidden rounded-[22px] border border-[#E7E4DC] bg-white shadow-sm">

                <div class="flex flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between md:p-6">

                    <div class="flex items-start gap-4">

                        <div class="gold-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 15v2m-6 4h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2V3H8v2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.15em] gold-text">
                                Espace propriétaire
                            </p>

                            <h2 class="mt-1 text-lg font-bold text-[#101D33]">
                                Gestion de votre annonce
                            </h2>

                            <p class="mt-1 max-w-xl text-sm leading-6 text-[#71869A]">
                                Vous êtes le propriétaire de ce bien. Gérez votre annonce depuis cet espace.
                            </p>
                        </div>
                    </div>

                    <?php if ($isRejected): ?>
                        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-[#FFF0ED] px-3 py-2 text-xs font-bold text-[#A53C32]">
                            <span class="h-2 w-2 rounded-full bg-[#A53C32]"></span>
                            Annonce rejetée
                        </span>
                    <?php endif; ?>

                </div>

                <?php if ($isRejected): ?>

                    <div class="mx-5 mb-5 rounded-2xl border border-[#E6C1BC] bg-[#FFF5F3] p-4 md:mx-6">

                        <div class="flex items-start gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#A53C32]">
                                !
                            </div>

                            <div>
                                <h3 class="font-bold text-[#8B332B]">
                                    Votre annonce a été rejetée
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-[#75443F]">
                                    <?php if ($motifRejet !== ''): ?>
                                        <?= nl2br(e($motifRejet)) ?>
                                    <?php else: ?>
                                        Aucun motif détaillé n'a été renseigné. Vous pouvez corriger les informations de votre annonce.
                                    <?php endif; ?>
                                </p>
                            </div>

                        </div>
                    </div>

                <?php endif; ?>

                <div class="flex flex-col gap-3 border-t border-[#E7E4DC] bg-[#FAF9F6] p-5 sm:flex-row sm:items-center md:px-6">

                    <?php if ($isRejected): ?>

                        <a href="/immo-sn/member/biens/modifier/<?= rawurlencode((string) $bienId) ?>"
                           class="gold-button inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m16.862 4.487 2.651 2.651M4 20l4.5-1 11-11a2.121 2.121 0 0 0-3-3l-11 11L4 20Z"/>
                            </svg>

                            Corriger mon annonce
                        </a>

                    <?php endif; ?>

                    <form action="/immo-sn/member/biens/supprimer/<?= rawurlencode((string) $bienId) ?>"
                          method="POST"
                          class="sm:ml-auto"
                          onsubmit="return confirm('Voulez-vous vraiment supprimer définitivement cette annonce ? Cette action peut être irréversible.');">

                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#E6C1BC] bg-white px-5 py-3 text-sm font-bold text-[#A53C32] transition hover:bg-[#FFF5F3] sm:w-auto">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M3 6h18m-2 0-.9 14H5.9L5 6m4 0V4h6v2m-5 4v6m4-6v6"/>
                            </svg>

                            Supprimer mon annonce
                        </button>
                    </form>

                </div>
            </div>
        </section>

    <?php endif; ?>

    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->

    <main class="mx-auto max-w-7xl px-4 pb-12 pt-3 md:px-6 md:pt-5">

        <!-- =================================================
             GALERIE PHOTOS
        ================================================== -->

        <section class="overflow-hidden rounded-[24px] border border-[#E7E4DC] bg-white shadow-md">

            <div class="grid gap-2 md:h-[500px] md:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)]">

                <!-- IMAGE PRINCIPALE -->

                <div class="relative min-h-[320px] overflow-hidden bg-[#E7E4DC]">

                    <img id="mainPropertyImage"
                         src="<?= e($mainImage) ?>"
                         alt="<?= e($titre) ?>"
                         class="h-full w-full object-cover"
                         onerror="this.onerror=null;this.src='<?= e($defaultImage) ?>';">

                    <div class="absolute inset-0 bg-gradient-to-t from-[#071B36]/90 via-[#071B36]/15 to-transparent"></div>

                    <div class="absolute inset-x-0 bottom-0 p-5 md:p-8">

                        <?php if ($typeOffre !== ''): ?>

                            <span class="inline-flex items-center gap-2 rounded-full border border-[#F5B800]/50 bg-[#F5B800] px-3.5 py-1.5 text-xs font-extrabold text-[#101D33] shadow-lg">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#101D33]"></span>
                                <?= e($typeOffre) ?>
                            </span>

                        <?php endif; ?>

                        <h1 class="mt-3 max-w-3xl text-2xl font-extrabold leading-tight text-white md:text-4xl">
                            <?= e($titre) ?>
                        </h1>

                        <div class="mt-3 flex items-center gap-2 text-sm text-white/85">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 shrink-0 text-[#F5B800]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 21s7-5.25 7-12a7 7 0 1 0-14 0c0 6.75 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.5"/>
                            </svg>

                            <span><?= e($adresse) ?></span>
                        </div>

                    </div>

                    <?php if (!empty($photosValides)): ?>

                        <div class="absolute right-4 top-4 flex items-center gap-2 rounded-full border border-white/20 bg-[#071B36]/75 px-3.5 py-2 text-xs font-bold text-white backdrop-blur-md">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 text-[#F5B800]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="8.5" cy="10" r="1.5"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m21 15-5-5L5 19"/>
                            </svg>

                            <?= count($photosValides) ?> photo<?= count($photosValides) > 1 ? 's' : '' ?>
                        </div>

                    <?php endif; ?>

                </div>

                <!-- MINIATURES DESKTOP -->

                <div class="hidden gap-2 overflow-hidden bg-[#F7F6F1] p-2 md:grid">

                    <?php if (!empty($photosValides)): ?>

                        <?php $thumbs = array_slice($photosValides, 0, 4); ?>

                        <?php foreach ($thumbs as $index => $photo): ?>

                            <button type="button"
                                    class="photoThumbnail detail-thumbnail group relative min-h-0 overflow-hidden rounded-[17px] bg-[#E7E4DC]"
                                    data-image="<?= e($photo['file_path']) ?>"
                                    aria-label="Afficher la photo <?= $index + 1 ?>">

                                <img src="<?= e($photo['file_path']) ?>"
                                     alt="Photo <?= $index + 1 ?>"
                                     class="h-full w-full object-cover"
                                     onerror="this.onerror=null;this.src='<?= e($defaultImage) ?>';">

                                <?php if ($index === 3 && count($photosValides) > 4): ?>

                                    <div class="absolute inset-0 flex items-center justify-center bg-[#071B36]/65 text-lg font-extrabold text-white">
                                        +<?= count($photosValides) - 4 ?>
                                    </div>

                                <?php endif; ?>

                            </button>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="flex flex-col items-center justify-center rounded-[17px] bg-[#F1F0EA] text-center text-sm text-[#71869A]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mb-2 h-8 w-8"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.5">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="8.5" cy="10" r="1.5"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m21 15-5-5L5 19"/>
                            </svg>
                            Aucune photo disponible
                        </div>

                    <?php endif; ?>

                </div>
            </div>

            <!-- MINIATURES MOBILES -->

            <?php if (!empty($photosValides)): ?>

                <div class="flex gap-2 overflow-x-auto border-t border-[#E7E4DC] bg-white p-3 md:hidden">

                    <?php foreach ($photosValides as $index => $photo): ?>

                        <button type="button"
                                class="photoThumbnail detail-thumbnail h-20 w-24 shrink-0 overflow-hidden rounded-xl border border-[#E7E4DC]"
                                data-image="<?= e($photo['file_path']) ?>"
                                aria-label="Afficher la photo <?= $index + 1 ?>">

                            <img src="<?= e($photo['file_path']) ?>"
                                 alt="Photo <?= $index + 1 ?>"
                                 class="h-full w-full object-cover"
                                 onerror="this.onerror=null;this.src='<?= e($defaultImage) ?>';">

                        </button>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

        <!-- =================================================
             GRILLE DÉTAILS + CONTACT
        ================================================== -->

        <div class="mt-7 grid gap-7 lg:grid-cols-[minmax(0,1fr)_360px]">

            <!-- COLONNE GAUCHE -->

            <div class="space-y-7">

                <!-- PRIX ET CARACTÉRISTIQUES -->

                <section class="premium-card p-5 md:p-7">

                    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-start">

                        <div>

                            <p class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.17em] gold-text">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                                Prix du bien
                            </p>

                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-[#101D33] md:text-4xl">
                                <?= e($prixFormate !== '' ? $prixFormate : 'Prix non renseigné') ?>
                            </p>

                        </div>

                        <?php if ($statutLabel !== ''): ?>

                            <span class="inline-flex w-fit items-center gap-2 rounded-full px-3.5 py-2 text-xs font-bold <?= e($statutClass) ?>">
                                <span class="h-2 w-2 rounded-full bg-current"></span>
                                <?= e($statutLabel) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <div class="mt-7 grid grid-cols-2 gap-3 md:grid-cols-4">

                        <?php if ($surface !== null && $surface !== ''): ?>

                            <div class="soft-panel rounded-2xl p-4">

                                <div class="gold-icon flex h-10 w-10 items-center justify-center rounded-xl">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20 20 4"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M7 17h.01M10 14h.01M13 11h.01M16 8h.01M19 5h.01"/>
                                    </svg>

                                </div>

                                <p class="mt-3 text-xs text-[#71869A]">Surface</p>
                                <p class="mt-1 text-sm font-extrabold text-[#101D33]">
                                    <?= e($surface) ?> m²
                                </p>

                            </div>

                        <?php endif; ?>

                        <?php if ($pieces !== null && $pieces !== ''): ?>

                            <div class="soft-panel rounded-2xl p-4">

                                <div class="gold-icon flex h-10 w-10 items-center justify-center rounded-xl">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M3 21V8l9-5 9 5v13"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M7 21v-5h10v5"/>
                                    </svg>

                                </div>

                                <p class="mt-3 text-xs text-[#71869A]">Pièces</p>
                                <p class="mt-1 text-sm font-extrabold text-[#101D33]">
                                    <?= e($pieces) ?>
                                </p>

                            </div>

                        <?php endif; ?>

                        <?php if ($chambres !== null && $chambres !== ''): ?>

                            <div class="soft-panel rounded-2xl p-4">

                                <div class="gold-icon flex h-10 w-10 items-center justify-center rounded-xl">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M5 12V8a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v4"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M12 12V8a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v4"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12v5m18-5v5"/>
                                    </svg>

                                </div>

                                <p class="mt-3 text-xs text-[#71869A]">Chambres</p>
                                <p class="mt-1 text-sm font-extrabold text-[#101D33]">
                                    <?= e($chambres) ?>
                                </p>

                            </div>

                        <?php endif; ?>

                        <?php if ($sallesBain !== null && $sallesBain !== ''): ?>

                            <div class="soft-panel rounded-2xl p-4">

                                <div class="gold-icon flex h-10 w-10 items-center justify-center rounded-xl">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M6 12V8a3 3 0 0 1 6 0v4"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M4 12v4a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-4"/>
                                    </svg>

                                </div>

                                <p class="mt-3 text-xs text-[#71869A]">Salles de bain</p>
                                <p class="mt-1 text-sm font-extrabold text-[#101D33]">
                                    <?= e($sallesBain) ?>
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>
                </section>

                <!-- DESCRIPTION -->

                <section class="premium-card p-5 md:p-7">

                    <div class="flex items-center gap-3">

                        <div class="gold-icon flex h-11 w-11 items-center justify-center rounded-xl">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10"/>
                            </svg>

                        </div>

                        <div>
                            <h2 class="text-xl font-extrabold text-[#101D33]">Description</h2>
                            <p class="mt-0.5 text-xs text-[#71869A]">
                                Découvrez les détails de cette propriété
                            </p>
                        </div>

                    </div>

                    <?php if ($description !== ''): ?>

                        <div class="mt-6 whitespace-pre-line text-sm leading-8 text-[#526477]">
                            <?= e($description) ?>
                        </div>

                    <?php else: ?>

                        <div class="mt-6 rounded-2xl bg-[#F7F6F1] p-5 text-sm italic text-[#71869A]">
                            Aucune description disponible pour ce bien.
                        </div>

                    <?php endif; ?>

                </section>

                <!-- LOCALISATION -->

                <section class="premium-card p-5 md:p-7">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-3">

                            <div class="gold-icon flex h-11 w-11 items-center justify-center rounded-xl">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M12 21s7-5.25 7-12a7 7 0 1 0-14 0c0 6.75 7 12 7 12Z"/>
                                    <circle cx="12" cy="9" r="2.5"/>
                                </svg>

                            </div>

                            <div>
                                <h2 class="text-xl font-extrabold text-[#101D33]">
                                    Localisation
                                </h2>

                                <p class="mt-0.5 text-xs text-[#71869A]">
                                    <?= e($adresse) ?>
                                </p>
                            </div>

                        </div>

                        <?php if ($hasCoordinates): ?>

                            <div class="rounded-xl border border-[#E7E4DC] bg-[#F7F6F1] px-3 py-2 text-xs font-medium text-[#526477]">
                                <?= e(number_format($latitude, 6, '.', '')) ?>,
                                <?= e(number_format($longitude, 6, '.', '')) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="mt-6 overflow-hidden rounded-[18px] border border-[#E7E4DC]">

                        <?php if ($hasCoordinates): ?>

                            <div class="h-[360px]">

                                <iframe title="Localisation du bien"
                                        class="h-full w-full border-0"
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        src="https://www.openstreetmap.org/export/embed.html?bbox=<?= urlencode((string) ($longitude - 0.01)) ?>%2C<?= urlencode((string) ($latitude - 0.01)) ?>%2C<?= urlencode((string) ($longitude + 0.01)) ?>%2C<?= urlencode((string) ($latitude + 0.01)) ?>&layer=mapnik&marker=<?= urlencode((string) $latitude) ?>%2C<?= urlencode((string) $longitude) ?>">
                                </iframe>

                            </div>

                        <?php else: ?>

                            <div class="flex h-[300px] flex-col items-center justify-center bg-[#F7F6F1] px-6 text-center">

                                <div class="gold-icon flex h-16 w-16 items-center justify-center rounded-2xl">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-8 w-8"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.6">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M12 21s7-5.25 7-12a7 7 0 1 0-14 0c0 6.75 7 12 7 12Z"/>
                                        <circle cx="12" cy="9" r="2.5"/>
                                    </svg>

                                </div>

                                <p class="mt-4 font-bold text-[#101D33]">
                                    Position GPS non disponible
                                </p>

                                <p class="mt-1 max-w-md text-sm leading-6 text-[#71869A]">
                                    Les coordonnées géographiques de ce bien n'ont pas encore été renseignées.
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>

                </section>

            </div>

            <!-- =================================================
                 COLONNE DROITE
            ================================================== -->

            <aside class="space-y-5 lg:sticky lg:top-[100px] lg:self-start">

                <!-- CONTACT PROPRIÉTAIRE -->

                <section class="premium-card overflow-hidden">

                    <div class="relative overflow-hidden bg-[#101D33] p-6 text-white">

                        <div class="absolute -right-10 -top-10 h-36 w-36 rounded-full border border-white/10"></div>
                        <div class="absolute -right-5 -top-5 h-24 w-24 rounded-full border border-[#F5B800]/20"></div>

                        <div class="relative">

                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.17em] text-[#F5B800]">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                                Contact
                            </div>

                            <h2 class="mt-3 text-xl font-extrabold">
                                Intéressé par ce bien ?
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-white/70">
                                Contactez directement le propriétaire pour obtenir plus d'informations ou organiser une visite.
                            </p>

                        </div>

                    </div>

                    <div class="p-5">

                        <!-- IDENTITÉ -->

                        <div class="flex items-center gap-3">

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border-2 border-[#F5B800]/40 bg-[#101D33] text-base font-extrabold text-[#F5B800]">
                                <?= e($initiales) ?>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs text-[#71869A]">Propriétaire</p>

                                <p class="truncate text-sm font-extrabold text-[#101D33]">
                                    <?= e($proprioNomComplet) ?>
                                </p>

                                <p class="mt-1 text-xs text-[#9A7100]">
                                    Annonce IMMO-SN
                                </p>
                            </div>

                        </div>

                        <!-- TÉLÉPHONE -->

                        <?php if ($telephone !== ''): ?>

                            <a href="tel:<?= e($telephone) ?>"
                               class="mt-5 flex items-center gap-3 rounded-xl border border-[#E7E4DC] bg-[#F7F6F1] p-3 transition hover:border-[#D7A200] hover:bg-[#FFF9E8]">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#9A7100] shadow-sm">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">
                                    <p class="text-[11px] text-[#71869A]">Téléphone</p>
                                    <p class="text-sm font-bold text-[#101D33]">
                                        <?= e($telephone) ?>
                                    </p>
                                </div>

                            </a>

                        <?php else: ?>

                            <div class="mt-5 rounded-xl border border-[#E7E4DC] bg-[#F7F6F1] p-4">
                                <p class="text-xs text-[#71869A]">Téléphone</p>
                                <p class="mt-1 text-sm font-semibold text-[#101D33]">
                                    Numéro non renseigné
                                </p>
                            </div>

                        <?php endif; ?>

                        <!-- EMAIL -->

                        <?php if ($proprioEmail !== ''): ?>

                            <a href="mailto:<?= e($proprioEmail) ?>"
                               class="mt-3 flex items-center gap-3 rounded-xl border border-[#E7E4DC] bg-[#F7F6F1] p-3 transition hover:border-[#D7A200] hover:bg-[#FFF9E8]">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#71869A] shadow-sm">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 13l9-5.5"/>
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">
                                    <p class="text-[11px] text-[#71869A]">Adresse email</p>
                                    <p class="truncate text-sm font-bold text-[#101D33]">
                                        <?= e($proprioEmail) ?>
                                    </p>
                                </div>

                            </a>

                        <?php endif; ?>

                        <!-- BOUTON CONTACT -->

                        <?php if (!empty($bien['user_id'])): ?>

                            <button id="openContactButton"
                                    type="button"
                                    class="gold-button mt-5 flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3.5 text-sm font-extrabold shadow-sm">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"/>
                                </svg>

                                Contacter le propriétaire
                            </button>

                        <?php endif; ?>

                    </div>
                </section>

                <!-- QR CODE -->

                <?php if ($qrUrl !== ''): ?>

                    <section class="premium-card p-5">

                        <div class="flex items-center gap-3">

                            <div class="gold-icon flex h-11 w-11 items-center justify-center rounded-xl">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M14 14h3v3h-3zm4 4h3v3h-3zm-4 0h2"/>
                                </svg>

                            </div>

                            <div>
                                <h2 class="font-extrabold text-[#101D33]">
                                    QR Code du bien
                                </h2>
                                <p class="mt-0.5 text-xs text-[#71869A]">
                                    Scannez pour retrouver cette annonce
                                </p>
                            </div>

                        </div>

                        <div class="mt-5 flex justify-center rounded-2xl border border-[#E7E4DC] bg-[#F7F6F1] p-5">

                            <img src="<?= e($qrUrl) ?>"
                                 alt="QR Code du bien"
                                 class="h-44 w-44 rounded-xl object-contain"
                                 onerror="this.onerror=null;this.alt='QR Code indisponible';">

                        </div>

                        <?php if ($isOwner): ?>

                            <a href="/immo-sn/biens/<?= rawurlencode((string) $bienId) ?>/qr"
                               class="navy-button mt-4 flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-bold">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3"/>
                                </svg>

                                Télécharger mon QR Code
                            </a>

                        <?php else: ?>

                            <p class="mt-3 text-center text-xs leading-5 text-[#71869A]">
                                Ce QR Code permet de retrouver facilement cette annonce.
                            </p>

                        <?php endif; ?>

                    </section>

                <?php endif; ?>

                <!-- RÉSUMÉ LOCALISATION -->

                <section class="premium-card p-5">

                    <p class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.16em] gold-text">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                        Localisation
                    </p>

                    <p class="mt-3 font-bold leading-6 text-[#101D33]">
                        <?= e($adresse) ?>
                    </p>

                    <?php if ($categorie !== ''): ?>

                        <div class="mt-4 flex items-center justify-between border-t border-[#E7E4DC] pt-4">

                            <span class="text-xs text-[#71869A]">Catégorie</span>

                            <span class="rounded-full bg-[#FFF5D8] px-3 py-1.5 text-xs font-bold text-[#8A6500]">
                                <?= e($categorie) ?>
                            </span>

                        </div>

                    <?php endif; ?>

                </section>

            </aside>

        </div>

    </main>

</div>

<!-- =========================================================
     MODALE CONTACT
========================================================= -->

<div id="contactModal"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#071B36]/75 px-4 py-6 backdrop-blur-sm"
     aria-hidden="true">

    <div id="contactModalPanel"
         class="detail-modal relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-[24px] bg-white shadow-2xl custom-scrollbar"
         role="dialog"
         aria-modal="true"
         aria-labelledby="contactModalTitle">

        <!-- EN-TÊTE -->

        <div class="border-b border-[#E7E4DC] bg-[#F7F6F1] p-5 md:p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <div class="gold-icon flex h-12 w-12 items-center justify-center rounded-xl">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"/>
                        </svg>

                    </div>

                    <h2 id="contactModalTitle"
                        class="mt-4 text-xl font-extrabold text-[#101D33]">
                        Contacter le propriétaire
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-[#71869A]">
                        Votre message sera envoyé directement au propriétaire de ce bien.
                    </p>

                </div>

                <button id="closeContactButton"
                        type="button"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-[#71869A] transition hover:bg-[#E7E4DC] hover:text-[#101D33]"
                        aria-label="Fermer">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 6l12 12M18 6 6 18"/>
                    </svg>

                </button>

            </div>
        </div>

        <!-- CONTENU -->

        <div class="p-5 md:p-6">

            <div class="flex items-center gap-3 rounded-2xl bg-[#F7F6F1] p-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#101D33] text-sm font-extrabold text-[#F5B800]">
                    <?= e($initiales) ?>
                </div>

                <div class="min-w-0">
                    <p class="text-xs text-[#71869A]">Vous contactez</p>

                    <p class="truncate text-sm font-extrabold text-[#101D33]">
                        <?= e($proprioNomComplet) ?>
                    </p>

                    <p class="mt-1 truncate text-xs text-[#71869A]">
                        <?= e($titre) ?>
                    </p>
                </div>

            </div>

            <!-- APPEL TÉLÉPHONIQUE -->

            <?php if ($telephone !== ''): ?>

                <a href="tel:<?= e($telephone) ?>"
                   class="mt-4 flex items-center justify-between rounded-xl border border-[#E7E4DC] px-4 py-3 transition hover:bg-[#F7F6F1]">

                    <div class="flex items-center gap-3">

                        <div class="gold-icon flex h-9 w-9 items-center justify-center rounded-lg">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-[10px] text-[#71869A]">Téléphone</p>
                            <p class="text-sm font-bold text-[#101D33]">
                                <?= e($telephone) ?>
                            </p>
                        </div>

                    </div>

                    <span class="text-xs font-extrabold gold-text">Appeler</span>

                </a>

            <?php endif; ?>

            <!-- FORMULAIRE DE MESSAGE -->

            <form action="/immo-sn/messages/envoyer"
                  method="POST"
                  class="mt-5 space-y-4">

                <input type="hidden" name="bien_id" value="<?= e($bienId) ?>">

                <input type="hidden"
                       name="destinataire_id"
                       value="<?= e($bien['user_id'] ?? '') ?>">

                <div>

                    <label for="messageContent"
                           class="mb-2 block text-sm font-bold text-[#101D33]">
                        Votre message
                    </label>

                    <textarea id="messageContent"
                              name="contenu"
                              rows="6"
                              required
                              minlength="3"
                              placeholder="Bonjour, je suis intéressé(e) par ce bien. Est-il toujours disponible ?"
                              class="w-full resize-none rounded-xl border border-[#DCE2E5] bg-white px-4 py-3 text-sm leading-6 text-[#101D33] outline-none transition placeholder:text-[#9AA8B3] focus:border-[#D7A200] focus:ring-2 focus:ring-[#FFF5D8]"></textarea>

                    <p class="mt-1.5 text-[11px] text-[#71869A]">
                        Votre message sera enregistré dans la messagerie du propriétaire.
                    </p>

                </div>

                <button type="submit"
                        class="gold-button flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3.5 text-sm font-extrabold shadow-sm">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m22 2-7 20-4-9-9-4 20-7Z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M22 2 11 13"/>
                    </svg>

                    Envoyer le message
                </button>

            </form>

        </div>

    </div>
</div>

<!-- =========================================================
     MODALE SIGNALEMENT
========================================================= -->

<div id="reportModal"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#071B36]/75 px-4 py-6 backdrop-blur-sm"
     aria-hidden="true">

    <div id="reportModalPanel"
         class="detail-modal relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-[24px] bg-white shadow-2xl custom-scrollbar"
         role="dialog"
         aria-modal="true"
         aria-labelledby="reportModalTitle">

        <!-- EN-TÊTE -->

        <div class="border-b border-[#E7E4DC] bg-[#F7F6F1] p-5 md:p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF0ED] text-[#A53C32]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                        </svg>

                    </div>

                    <h2 id="reportModalTitle"
                        class="mt-4 text-xl font-extrabold text-[#101D33]">
                        Signaler ce bien
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-[#71869A]">
                        Aidez-nous à maintenir des annonces fiables et de qualité.
                    </p>

                </div>

                <button id="closeReportButton"
                        type="button"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-[#71869A] transition hover:bg-[#E7E4DC] hover:text-[#101D33]"
                        aria-label="Fermer">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 6l12 12M18 6 6 18"/>
                    </svg>

                </button>

            </div>
        </div>

        <!-- CONTENU -->

        <div class="p-5 md:p-6">

            <div class="rounded-2xl bg-[#F7F6F1] p-4">

                <p class="text-xs text-[#71869A]">Bien concerné</p>

                <p class="mt-1 font-extrabold text-[#101D33]">
                    <?= e($titre) ?>
                </p>

            </div>

            <form action="/immo-sn/signalements"
                  method="POST"
                  class="mt-5 space-y-5">

                <input type="hidden" name="bien_id" value="<?= e($bienId) ?>">

                <div>

                    <label for="reportReason"
                           class="mb-2 block text-sm font-bold text-[#101D33]">
                        Motif du signalement
                    </label>

                    <select id="reportReason"
                            name="motif"
                            required
                            class="w-full rounded-xl border border-[#DCE2E5] bg-white px-4 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#FFF5D8]">

                        <option value="">Sélectionner un motif</option>
                        <option value="Annonce frauduleuse">Annonce frauduleuse</option>
                        <option value="Informations incorrectes">Informations incorrectes</option>
                        <option value="Bien déjà vendu ou loué">Bien déjà vendu ou loué</option>
                        <option value="Prix incorrect">Prix incorrect</option>
                        <option value="Contenu inapproprié">Contenu inapproprié</option>
                        <option value="Fausse localisation">Fausse localisation</option>
                        <option value="Autre">Autre</option>

                    </select>

                </div>

                <div class="rounded-xl border border-[#E7E4DC] bg-[#F7F6F1] p-4">

                    <div class="flex gap-3">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="mt-0.5 h-5 w-5 shrink-0 text-[#9A7100]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01"/>
                            <circle cx="12" cy="12" r="9"/>
                        </svg>

                        <p class="text-xs leading-5 text-[#526477]">
                            Votre signalement sera transmis à l'administration pour vérification.
                            Le statut initial sera
                            <strong class="text-[#101D33]">en attente</strong>.
                        </p>

                    </div>

                </div>

                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#A53C32] px-5 py-3.5 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#8F3028]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                    </svg>

                    Signaler le bien
                </button>

            </form>

        </div>

    </div>
</div>

<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       GALERIE PHOTOS
    ====================================================== */

    const mainImage = document.getElementById('mainPropertyImage');
    const thumbnails = document.querySelectorAll('.photoThumbnail');

    thumbnails.forEach(function (thumbnail) {

        thumbnail.addEventListener('click', function () {

            const image = thumbnail.getAttribute('data-image');

            if (!image || !mainImage) {
                return;
            }

            mainImage.src = image;

            thumbnails.forEach(function (item) {
                item.classList.remove('active');
            });

            thumbnail.classList.add('active');
        });

    });

    /* =====================================================
       GESTION DES MODALES
    ====================================================== */

    const contactModal = document.getElementById('contactModal');
    const openContactButton = document.getElementById('openContactButton');
    const closeContactButton = document.getElementById('closeContactButton');

    const reportModal = document.getElementById('reportModal');
    const openReportButton = document.getElementById('openReportButton');
    const closeReportButton = document.getElementById('closeReportButton');

    let activeModal = null;

    function openModal(modal) {

        if (!modal) {
            return;
        }

        if (activeModal && activeModal !== modal) {
            closeModal(activeModal);
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');

        activeModal = modal;
    }

    function closeModal(modal) {

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');

        activeModal = null;

        document.body.classList.remove('overflow-hidden');
    }

    /* =====================================================
       MODALE CONTACT
    ====================================================== */

    if (openContactButton) {

        openContactButton.addEventListener('click', function () {

            openModal(contactModal);

            const textarea = document.getElementById('messageContent');

            if (textarea) {
                setTimeout(function () {
                    textarea.focus();
                }, 150);
            }

        });

    }

    if (closeContactButton) {

        closeContactButton.addEventListener('click', function () {
            closeModal(contactModal);
        });

    }

    /* =====================================================
       MODALE SIGNALEMENT
    ====================================================== */

    if (openReportButton) {

        openReportButton.addEventListener('click', function () {
            openModal(reportModal);
        });

    }

    if (closeReportButton) {

        closeReportButton.addEventListener('click', function () {
            closeModal(reportModal);
        });

    }

    /* =====================================================
       FERMETURE EN CLIQUANT SUR LE FOND
    ====================================================== */

    if (contactModal) {

        contactModal.addEventListener('click', function (event) {

            if (event.target === contactModal) {
                closeModal(contactModal);
            }

        });

    }

    if (reportModal) {

        reportModal.addEventListener('click', function (event) {

            if (event.target === reportModal) {
                closeModal(reportModal);
            }

        });

    }

    /* =====================================================
       FERMETURE AVEC ÉCHAP
    ====================================================== */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape' && activeModal) {
            closeModal(activeModal);
        }

    });

});
</script>
