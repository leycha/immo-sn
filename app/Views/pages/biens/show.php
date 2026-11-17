<?php
/**
 * Vue : Détail d'un bien
 *
 * Emplacement :
 * app/Views/pages/biens/show.php
 *
 * Variables fournies par BienController :
 * - $bien
 * - $photos
 * - $qrCode
 * - $title
 */

/*
|--------------------------------------------------------------------------
| Sécurité
|--------------------------------------------------------------------------
*/

$bien = is_array($bien ?? null) ? $bien : [];
$photos = is_array($photos ?? null) ? $photos : [];
$qrCode = is_array($qrCode ?? null) ? $qrCode : [];
/*
|--------------------------------------------------------------------------
| Actions du propriétaire
|--------------------------------------------------------------------------
| Ces variables doivent être calculées dans BienController.
| La vérification côté contrôleur reste indispensable.
*/

$isOwner = ($isOwner ?? false) === true;
$isRejected = ($isRejected ?? false) === true;

$motifRejet = $isOwner && $isRejected
    ? trim((string) ($motifRejet ?? $bien['motif_rejet'] ?? ''))
    : '';


/*
|--------------------------------------------------------------------------
| Fonction d'échappement
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| Informations principales
|--------------------------------------------------------------------------
*/

$bienId = $bien['id'] ?? '';

$titre = trim(
    $bien['titre']
    ?? $bien['Titre']
    ?? 'Bien immobilier'
);

$description = trim(
    $bien['description']
    ?? $bien['Description']
    ?? ''
);

$typeOffre = trim(
    $bien['type_offre']
    ?? $bien['Type_offre']
    ?? ''
);

$categorie = trim(
    $bien['categorie_nom']
    ?? ''
);

$ville = trim(
    $bien['ville_nom']
    ?? ''
);

$quartier = trim(
    $bien['quartier_nom']
    ?? ''
);

$region = trim(
    $bien['region_nom']
    ?? ''
);

$prix = $bien['prix']
    ?? $bien['Prix']
    ?? null;

$surface = $bien['surface']
    ?? $bien['Surface']
    ?? null;

$pieces = $bien['pieces']
    ?? $bien['Pièces']
    ?? null;

$chambres = $bien['chambres']
    ?? $bien['Chambres']
    ?? null;

$sallesBain = $bien['salles_de_bain']
    ?? $bien['salles_bain']
    ?? null;

$statut = trim(
    $bien['statut']
    ?? $bien['Statut']
    ?? ''
);


/*
|--------------------------------------------------------------------------
| Propriétaire
|--------------------------------------------------------------------------
*/

$proprioPrenom = trim(
    $bien['proprio_prenom']
    ?? ''
);

$proprioNom = trim(
    $bien['proprio_nom']
    ?? ''
);

$proprioNomComplet = trim(
    $proprioPrenom . ' ' . $proprioNom
);

if ($proprioNomComplet === '') {
    $proprioNomComplet = 'Propriétaire';
}

$proprioEmail = trim(
    $bien['proprio_email']
    ?? ''
);


/*
|--------------------------------------------------------------------------
| Téléphone
|--------------------------------------------------------------------------
|
| Bien::detail() utilise :
|
| u.telephone AS proprio_tel
|
*/

$telephone = trim(
    $bien['proprio_tel']
    ?? $bien['proprio_telephone']
    ?? $bien['telephone']
    ?? $bien['tel']
    ?? ''
);


/*
|--------------------------------------------------------------------------
| GPS
|--------------------------------------------------------------------------
*/

$latitude = isset($bien['latitude'])
    && is_numeric($bien['latitude'])
    ? (float) $bien['latitude']
    : null;

$longitude = isset($bien['longitude'])
    && is_numeric($bien['longitude'])
    ? (float) $bien['longitude']
    : null;

$hasCoordinates =
    $latitude !== null &&
    $longitude !== null &&
    $latitude >= -90 &&
    $latitude <= 90 &&
    $longitude >= -180 &&
    $longitude <= 180;


/*
|--------------------------------------------------------------------------
| Adresse
|--------------------------------------------------------------------------
*/

$adresseParts = array_filter([
    $quartier,
    $ville,
    $region,
]);

$adresse = implode(', ', $adresseParts);

if ($adresse === '') {
    $adresse = 'Localisation non renseignée';
}


/*
|--------------------------------------------------------------------------
| Prix
|--------------------------------------------------------------------------
*/

$prixFormate = '';

if ($prix !== null && $prix !== '' && is_numeric($prix)) {

    $prixFormate = number_format(
        (float) $prix,
        0,
        ',',
        ' '
    ) . ' FCFA';
}


/*
|--------------------------------------------------------------------------
| Photos
|--------------------------------------------------------------------------
*/

$photosValides = [];

foreach ($photos as $photo) {

    if (!is_array($photo)) {
        continue;
    }

    $filePath = trim(
        $photo['file_path']
        ?? $photo['path']
        ?? $photo['url']
        ?? ''
    );

    if ($filePath === '') {
        continue;
    }

    $photosValides[] = [
        'id' => $photo['id'] ?? null,
        'file_path' => $filePath,
        'is_principal' => !empty($photo['is_principal']),
    ];
}


/*
|--------------------------------------------------------------------------
| Image principale
|--------------------------------------------------------------------------
*/

$defaultImage =
    '/immo-sn/assets/images/placeholder-property.jpg';

$mainImage = $defaultImage;

if (!empty($photosValides)) {
    $mainImage = $photosValides[0]['file_path'];
}


/*
|--------------------------------------------------------------------------
| QR Code
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| QR Code
|--------------------------------------------------------------------------
*/

$qrPath = '';
$qrFileName = '';
$qrUrl = '';

if (!empty($qrCode)) {

    $qrPath = trim(
        (string) (
            $qrCode['file_path']
            ?? $qrCode['path']
            ?? $qrCode['url']
            ?? ''
        )
    );

    if ($qrPath !== '') {

        $qrFileName = basename($qrPath);

        if ($qrFileName !== '' && $qrFileName !== '.') {

            $qrUrl = '/immo-sn/stockage/qrcodes/'
                . rawurlencode($qrFileName);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Initiales du propriétaire
|--------------------------------------------------------------------------
*/

$initiales = '';

if ($proprioPrenom !== '') {
    $initiales .= mb_substr(
        $proprioPrenom,
        0,
        1
    );
}

if ($proprioNom !== '') {
    $initiales .= mb_substr(
        $proprioNom,
        0,
        1
    );
}

$initiales = strtoupper(
    $initiales !== ''
        ? $initiales
        : 'P'
);


/*
|--------------------------------------------------------------------------
| URL actuelle
|--------------------------------------------------------------------------
*/

$currentUrl =
    '/immo-sn/biens/'
    . rawurlencode((string) $bienId);


/*
|--------------------------------------------------------------------------
| Messages de retour
|--------------------------------------------------------------------------
*/

$messageSuccess =
    isset($_GET['message'])
    && $_GET['message'] === 'success';

$messageError =
    isset($_GET['message'])
    && $_GET['message'] === 'error';

$signalementSuccess =
    isset($_GET['signalement'])
    && $_GET['signalement'] === 'success';

$signalementError =
    isset($_GET['signalement'])
    && $_GET['signalement'] === 'error';

$errorMessage = trim(
    $_GET['error'] ?? ''
);

?>

<div class="min-h-screen bg-[#faf7f2] text-[#30221b]">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <header class="sticky top-0 z-30 border-b border-[#eadfd5] bg-[#fffdf9]/95 backdrop-blur">

        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 md:px-6">

            <a
                href="/immo-sn/biens"
                class="inline-flex items-center gap-2 text-sm font-semibold text-[#725238] transition hover:text-[#30221b]"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 18l-6-6 6-6"
                    />
                </svg>

                Retour aux biens

            </a>


            <div class="flex items-center gap-2">

               <?php
$isFavorite = !empty($isFavorite);
?>

<form
    action="<?= e(
        $isFavorite
            ? '/immo-sn/member/favoris/retirer'
            : '/immo-sn/member/favoris/ajouter'
    ) ?>"
    method="POST"
>
    <input
        type="hidden"
        name="bien_id"
        value="<?= e($bienId) ?>"
    >


<input
    type="hidden"
    name="retour"
    value="<?= e($currentUrl) ?>"
>

<button
    id="favoriteButton"
    type="submit"
    class="inline-flex h-10 items-center gap-2 rounded-xl border border-[#eadfd5] px-3 text-sm font-semibold shadow-sm transition hover:bg-[#f7f0e8] <?= $isFavorite ? 'bg-[#30221b] text-[#f7b500]' : 'bg-white text-[#725238]' ?>"
    aria-label="<?= $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>"
    title="<?= $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>"
>
    <svg
        id="favoriteIcon"
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5"
        fill="<?= $isFavorite ? 'currentColor' : 'none' ?>"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="1.8"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M20.84 8.95c0 5.2-8.84 10.05-8.84 10.05S3.16 14.15 3.16 8.95A4.95 4.95 0 0 1 8.1 4c1.68 0 3.2.84 4.1 2.13A4.95 4.95 0 0 1 20.84 8.95Z"
        />
    </svg>

    <span class="hidden sm:inline">
        <?= $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>
    </span>
</button>


</form>


                <button
                    id="openReportButton"
                    type="button"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-[#eadfd5] bg-white px-3 text-sm font-semibold text-[#8b3b32] shadow-sm transition hover:border-[#d7b9b3] hover:bg-[#fff7f5]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v4"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 17h.01"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                        />
                    </svg>

                    <span class="hidden sm:inline">
                        Signaler
                    </span>

                </button>

            </div>

        </div>

    </header>


    <!-- =========================================================
         ALERTES
    ========================================================== -->

    <div class="mx-auto max-w-7xl px-4 pt-5 md:px-6">

        <?php if ($messageSuccess): ?>

            <div
                class="mb-4 flex items-start gap-3 rounded-2xl border border-[#b9dec4] bg-[#f1faf3] p-4 text-[#276238]"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m5 12 4 4L19 6"
                    />
                </svg>

                <div>
                    <p class="font-bold">
                        Message envoyé
                    </p>

                    <p class="mt-1 text-sm">
                        Votre message a bien été envoyé au propriétaire.
                    </p>
                </div>

            </div>

        <?php endif; ?>


        <?php if ($messageError): ?>

            <div
                class="mb-4 flex items-start gap-3 rounded-2xl border border-[#e6c1bc] bg-[#fff5f3] p-4 text-[#8b332b]"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v4"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 17h.01"
                    />

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />
                </svg>

                <div>

                    <p class="font-bold">
                        Envoi impossible
                    </p>

                    <p class="mt-1 text-sm">
                        <?= e(
                            $errorMessage !== ''
                                ? $errorMessage
                                : 'Une erreur est survenue.'
                        ) ?>
                    </p>

                </div>

            </div>

        <?php endif; ?>


        <?php if ($signalementSuccess): ?>

            <div
                class="mb-4 flex items-start gap-3 rounded-2xl border border-[#b9dec4] bg-[#f1faf3] p-4 text-[#276238]"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m5 12 4 4L19 6"
                    />
                </svg>

                <div>

                    <p class="font-bold">
                        Signalement enregistré
                    </p>

                    <p class="mt-1 text-sm">
                        Merci. Votre signalement sera examiné par notre équipe.
                    </p>

                </div>

            </div>

        <?php endif; ?>


        <?php if ($signalementError): ?>

            <div
                class="mb-4 flex items-start gap-3 rounded-2xl border border-[#e6c1bc] bg-[#fff5f3] p-4 text-[#8b332b]"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v4"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 17h.01"
                    />

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />
                </svg>

                <div>

                    <p class="font-bold">
                        Signalement impossible
                    </p>

                    <p class="mt-1 text-sm">
                        <?= e(
                            $errorMessage !== ''
                                ? $errorMessage
                                : 'Une erreur est survenue.'
                        ) ?>
                    </p>

                </div>

            </div>

        <?php endif; ?>

    </div>

    <!-- =========================================================
         ACTIONS RÉSERVÉES AU PROPRIÉTAIRE
    ========================================================== -->

    <?php if ($isOwner): ?>

        <section class="mb-6 rounded-[22px] border border-[#eadfd5] bg-white p-5 shadow-sm md:p-6">

            <div class="flex items-start gap-3">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#f4eadf] text-[#725238]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2V3H8v2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"
                        />
                    </svg>

                </div>

                <div class="min-w-0 flex-1">

                    <h2 class="text-lg font-bold text-[#30221b]">
                        Gestion de votre annonce
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-[#8f8176]">
                        Vous êtes le propriétaire de ce bien. Vous pouvez gérer votre annonce depuis cet espace.
                    </p>

                </div>

            </div>


            <!-- Motif du rejet : visible uniquement au propriétaire -->

            <?php if ($isRejected): ?>

                <div class="mt-5 rounded-2xl border border-[#e6c1bc] bg-[#fff5f3] p-4">

                    <div class="flex items-start gap-3">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mt-0.5 h-6 w-6 shrink-0 text-[#a53c32]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4m0 4h.01"
                            />
                            <circle cx="12" cy="12" r="9" />
                        </svg>

                        <div>

                            <h3 class="font-bold text-[#8b332b]">
                                Votre annonce a été rejetée
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#75443f]">
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


            <!-- Boutons d'action -->

            <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">

                <?php if ($isRejected): ?>

                    <a
                        href="/immo-sn/member/biens/modifier/<?= rawurlencode((string) $bienId) ?>"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-bold text-[#30221b] transition hover:bg-[#ffc72c]"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m16.862 4.487 2.651 2.651M4 20l4.5-1 11-11a2.121 2.121 0 0 0-3-3l-11 11L4 20Z"
                            />
                        </svg>

                        Corriger mon annonce

                    </a>

                <?php endif; ?>


                <form
                    action="/immo-sn/member/biens/supprimer/<?= rawurlencode((string) $bienId) ?>"
                    method="POST"
                    class="sm:ml-auto"
                    onsubmit="return confirm('Voulez-vous vraiment supprimer définitivement cette annonce ? Cette action peut être irréversible.');"
                >

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#e6c1bc] bg-white px-5 py-3 text-sm font-bold text-[#a53c32] transition hover:bg-[#fff5f3] sm:w-auto"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 6h18m-2 0-.9 14H5.9L5 6m4 0V4h6v2m-5 4v6m4-6v6"
                            />
                        </svg>

                        Supprimer mon annonce

                    </button>

                </form>

            </div>

        </section>

    <?php endif; ?>
    <!-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== -->

    <main class="mx-auto max-w-7xl px-4 py-6 md:px-6 md:py-8">


        <!-- =====================================================
             GALERIE
        ====================================================== -->

        <section class="overflow-hidden rounded-[24px] border border-[#eadfd5] bg-white shadow-sm">

            <div class="grid gap-2 md:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)] md:h-[500px]">

                <!-- IMAGE PRINCIPALE -->

                <div class="relative min-h-[320px] overflow-hidden bg-[#f4eadf]">

                    <img
                        id="mainPropertyImage"
                        src="<?= e($mainImage) ?>"
                        alt="<?= e($titre) ?>"
                        class="h-full w-full object-cover"
                        onerror="this.onerror=null;this.src='<?= e($defaultImage) ?>';"
                    >


                    <!-- Overlay -->

                    <div
                        class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent p-5 md:p-7"
                    >

                        <div class="flex flex-wrap items-end justify-between gap-4">

                            <div>

                                <?php if ($typeOffre !== ''): ?>

                                    <span
                                        class="inline-flex rounded-full bg-[#f7b500] px-3 py-1.5 text-xs font-bold text-[#30221b]"
                                    >
                                        <?= e($typeOffre) ?>
                                    </span>

                                <?php endif; ?>

                                <h1 class="mt-3 max-w-3xl text-2xl font-bold leading-tight text-white md:text-4xl">
                                    <?= e($titre) ?>
                                </h1>

                                <div class="mt-2 flex items-center gap-2 text-sm text-white/85">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 21s7-5.25 7-12a7 7 0 1 0-14 0c0 6.75 7 12 7 12Z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="9"
                                            r="2.5"
                                        />
                                    </svg>

                                    <?= e($adresse) ?>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Compteur photos -->

                    <?php if (!empty($photosValides)): ?>

                        <div
                            class="absolute right-4 top-4 rounded-full bg-black/55 px-3 py-1.5 text-xs font-semibold text-white backdrop-blur"
                        >
                            <?= count($photosValides) ?> photo<?= count($photosValides) > 1 ? 's' : '' ?>
                        </div>

                    <?php endif; ?>

                </div>


                <!-- MINIATURES -->

                <div class="hidden gap-2 overflow-hidden bg-[#faf7f2] p-2 md:grid">

                    <?php if (!empty($photosValides)): ?>

                        <?php
                        $thumbs = array_slice(
                            $photosValides,
                            0,
                            4
                        );
                        ?>

                        <?php foreach ($thumbs as $index => $photo): ?>

                            <button
                                type="button"
                                class="photoThumbnail group relative min-h-0 overflow-hidden rounded-[18px] bg-[#f4eadf]"
                                data-image="<?= e($photo['file_path']) ?>"
                            >

                                <img
                                    src="<?= e($photo['file_path']) ?>"
                                    alt="Photo <?= $index + 1 ?>"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    onerror="this.onerror=null;this.src='<?= e($defaultImage) ?>';"
                                >

                                <?php if ($index === 3 && count($photosValides) > 4): ?>

                                    <div class="absolute inset-0 flex items-center justify-center bg-black/45 text-sm font-bold text-white">
                                        +<?= count($photosValides) - 4 ?>
                                    </div>

                                <?php endif; ?>

                            </button>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="flex items-center justify-center rounded-[18px] bg-[#f4eadf] text-sm text-[#9b8d82]">
                            Aucune photo
                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Miniatures mobile -->

            <?php if (!empty($photosValides)): ?>

                <div class="flex gap-2 overflow-x-auto border-t border-[#eadfd5] bg-[#fffdf9] p-3 md:hidden">

                    <?php foreach ($photosValides as $index => $photo): ?>

                        <button
                            type="button"
                            class="photoThumbnail h-20 w-24 shrink-0 overflow-hidden rounded-xl border border-[#eadfd5]"
                            data-image="<?= e($photo['file_path']) ?>"
                        >

                            <img
                                src="<?= e($photo['file_path']) ?>"
                                alt="Photo <?= $index + 1 ?>"
                                class="h-full w-full object-cover"
                                onerror="this.onerror=null;this.src='<?= e($defaultImage) ?>';"
                            >

                        </button>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>


        <!-- =====================================================
             GRID PRINCIPAL
        ====================================================== -->

        <div class="mt-7 grid gap-7 lg:grid-cols-[minmax(0,1fr)_360px]">


            <!-- =================================================
                 COLONNE GAUCHE
            ================================================== -->

            <div class="space-y-7">


                <!-- =================================================
                     PRIX + CARACTÉRISTIQUES
                ================================================== -->

                <section class="rounded-[22px] border border-[#eadfd5] bg-white p-5 shadow-sm md:p-7">

                    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-start">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                                Prix
                            </p>

                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-[#30221b] md:text-4xl">
                                <?= e(
                                    $prixFormate !== ''
                                        ? $prixFormate
                                        : 'Prix non renseigné'
                                ) ?>
                            </p>

                        </div>


                        <?php if ($statut !== ''): ?>

                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full bg-[#edf8ef] px-3 py-1.5 text-xs font-bold text-[#347347]"
                            >

                                <span class="h-2 w-2 rounded-full bg-[#5caa70]"></span>

                                <?= e(ucfirst($statut)) ?>

                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- Caractéristiques -->

                    <div class="mt-7 grid grid-cols-2 gap-3 md:grid-cols-4">

                        <?php if ($surface !== null && $surface !== ''): ?>

                            <div class="rounded-2xl bg-[#faf7f2] p-4">

                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f4eadf] text-[#a98260]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4 20 20 4"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M7 17h.01M10 14h.01M13 11h.01M16 8h.01M19 5h.01"
                                        />
                                    </svg>

                                </div>

                                <p class="mt-3 text-xs text-[#9b8d82]">
                                    Surface
                                </p>

                                <p class="mt-1 text-sm font-bold text-[#30221b]">
                                    <?= e($surface) ?> m²
                                </p>

                            </div>

                        <?php endif; ?>


                        <?php if ($pieces !== null && $pieces !== ''): ?>

                            <div class="rounded-2xl bg-[#faf7f2] p-4">

                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f4eadf] text-[#a98260]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 21V8l9-5 9 5v13"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M7 21v-5h10v5"
                                        />

                                    </svg>

                                </div>

                                <p class="mt-3 text-xs text-[#9b8d82]">
                                    Pièces
                                </p>

                                <p class="mt-1 text-sm font-bold text-[#30221b]">
                                    <?= e($pieces) ?>
                                </p>

                            </div>

                        <?php endif; ?>


                        <?php if ($chambres !== null && $chambres !== ''): ?>

                            <div class="rounded-2xl bg-[#faf7f2] p-4">

                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f4eadf] text-[#a98260]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 12h18"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 12V8a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v4"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 12V8a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v4"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 12v5m18-5v5"
                                        />

                                    </svg>

                                </div>

                                <p class="mt-3 text-xs text-[#9b8d82]">
                                    Chambres
                                </p>

                                <p class="mt-1 text-sm font-bold text-[#30221b]">
                                    <?= e($chambres) ?>
                                </p>

                            </div>

                        <?php endif; ?>


                        <?php if ($sallesBain !== null && $sallesBain !== ''): ?>

                            <div class="rounded-2xl bg-[#faf7f2] p-4">

                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f4eadf] text-[#a98260]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4 12h16"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6 12V8a3 3 0 0 1 6 0v4"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4 12v4a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-4"
                                        />

                                    </svg>

                                </div>

                                <p class="mt-3 text-xs text-[#9b8d82]">
                                    Salles de bain
                                </p>

                                <p class="mt-1 text-sm font-bold text-[#30221b]">
                                    <?= e($sallesBain) ?>
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>

                </section>


                <!-- =================================================
                     DESCRIPTION
                ================================================== -->

                <section class="rounded-[22px] border border-[#eadfd5] bg-white p-5 shadow-sm md:p-7">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4eadf] text-[#a98260]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 6h16M4 12h16M4 18h10"
                                />
                            </svg>

                        </div>

                        <div>

                            <h2 class="text-xl font-bold text-[#30221b]">
                                Description
                            </h2>

                            <p class="text-xs text-[#9b8d82]">
                                Informations détaillées sur le bien
                            </p>

                        </div>

                    </div>


                    <?php if ($description !== ''): ?>

                        <div class="mt-6 whitespace-pre-line text-sm leading-7 text-[#65564d]">
                            <?= e($description) ?>
                        </div>

                    <?php else: ?>

                        <p class="mt-6 text-sm italic text-[#9b8d82]">
                            Aucune description disponible.
                        </p>

                    <?php endif; ?>

                </section>


                <!-- =================================================
                     LOCALISATION
                ================================================== -->

                <section class="rounded-[22px] border border-[#eadfd5] bg-white p-5 shadow-sm md:p-7">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4eadf] text-[#a98260]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 21s7-5.25 7-12a7 7 0 1 0-14 0c0 6.75 7 12 7 12Z"
                                    />

                                    <circle
                                        cx="12"
                                        cy="9"
                                        r="2.5"
                                    />
                                </svg>

                            </div>

                            <div>

                                <h2 class="text-xl font-bold text-[#30221b]">
                                    Localisation
                                </h2>

                                <p class="text-xs text-[#9b8d82]">
                                    <?= e($adresse) ?>
                                </p>

                            </div>

                        </div>


                        <?php if ($hasCoordinates): ?>

                            <div class="rounded-xl bg-[#faf7f2] px-3 py-2 text-xs font-medium text-[#725238]">

                                <?= e(number_format($latitude, 6)) ?>,
                                <?= e(number_format($longitude, 6)) ?>

                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="mt-6 overflow-hidden rounded-[18px] border border-[#eadfd5]">

                        <?php if ($hasCoordinates): ?>

                            <div class="h-[360px]">

                                <iframe
                                    title="Localisation du bien"
                                    class="h-full w-full border-0"
                                    loading="lazy"
                                    src="https://www.openstreetmap.org/export/embed.html?bbox=<?= urlencode((string) ($longitude - 0.01)) ?>%2C<?= urlencode((string) ($latitude - 0.01)) ?>%2C<?= urlencode((string) ($longitude + 0.01)) ?>%2C<?= urlencode((string) ($latitude + 0.01)) ?>&layer=mapnik&marker=<?= urlencode((string) $latitude) ?>%2C<?= urlencode((string) $longitude) ?>"
                                ></iframe>

                            </div>

                        <?php else: ?>

                            <div class="flex h-[300px] flex-col items-center justify-center bg-[#faf7f2] px-6 text-center">

                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f4eadf] text-[#a98260]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-7 w-7"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 21s7-5.25 7-12a7 7 0 1 0-14 0c0 6.75 7 12 7 12Z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="9"
                                            r="2.5"
                                        />

                                    </svg>

                                </div>

                                <p class="mt-4 font-bold text-[#30221b]">
                                    Position GPS non disponible
                                </p>

                                <p class="mt-1 max-w-md text-sm leading-6 text-[#9b8d82]">
                                    Les coordonnées géographiques de ce bien
                                    n'ont pas encore été renseignées.
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


                <!-- =================================================
                     CONTACT PROPRIÉTAIRE
                ================================================== -->

                <section class="overflow-hidden rounded-[22px] border border-[#eadfd5] bg-white shadow-sm">

                    <div class="bg-[#30221b] p-6 text-white">

                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#d8c4b2]">
                            Contact
                        </p>

                        <h2 class="mt-2 text-xl font-bold">
                            Intéressé par ce bien ?
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-white/70">
                            Contactez directement le propriétaire pour obtenir
                            plus d'informations ou organiser une visite.
                        </p>

                    </div>


                    <div class="p-5">


                        <!-- Propriétaire -->

                        <div class="flex items-center gap-3">

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#f4eadf] text-sm font-extrabold text-[#725238]">
                                <?= e($initiales) ?>
                            </div>

                            <div class="min-w-0">

                                <p class="text-xs text-[#9b8d82]">
                                    Propriétaire
                                </p>

                                <p class="truncate text-sm font-bold text-[#30221b]">
                                    <?= e($proprioNomComplet) ?>
                                </p>

                            </div>

                        </div>


                        <!-- Téléphone -->

                        <?php if ($telephone !== ''): ?>

                            <a
                                href="tel:<?= e($telephone) ?>"
                                class="mt-5 flex items-center gap-3 rounded-xl border border-[#eadfd5] bg-[#faf7f2] p-3 transition hover:border-[#cdb9a5] hover:bg-[#f4eadf]"
                            >

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#5caa70] shadow-sm">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-[11px] text-[#9b8d82]">
                                        Téléphone
                                    </p>

                                    <p class="text-sm font-bold text-[#30221b]">
                                        <?= e($telephone) ?>
                                    </p>

                                </div>

                            </a>

                        <?php else: ?>

                            <div class="mt-5 rounded-xl border border-[#eadfd5] bg-[#faf7f2] p-4">

                                <p class="text-xs text-[#9b8d82]">
                                    Téléphone
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#30221b]">
                                    Numéro non renseigné
                                </p>

                            </div>

                        <?php endif; ?>


                        <!-- Email -->

                        <?php if ($proprioEmail !== ''): ?>

                            <a
                                href="mailto:<?= e($proprioEmail) ?>"
                                class="mt-3 flex items-center gap-3 rounded-xl border border-[#eadfd5] bg-[#faf7f2] p-3 transition hover:border-[#cdb9a5] hover:bg-[#f4eadf]"
                            >

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#a98260] shadow-sm">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 7.5 12 13l9-5.5"
                                        />

                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="14"
                                            rx="2"
                                        />

                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-[11px] text-[#9b8d82]">
                                        Email
                                    </p>

                                    <p class="truncate text-sm font-bold text-[#30221b]">
                                        <?= e($proprioEmail) ?>
                                    </p>

                                </div>

                            </a>

                        <?php endif; ?>


                        <!-- Bouton message -->

                        <?php if (!empty($bien['user_id'])): ?>

                            <button
                                id="openContactButton"
                                type="button"
                                class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-[#f7b500] px-5 py-3.5 text-sm font-extrabold text-[#30221b] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#ffc72c]"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"
                                    />
                                </svg>

                                Contacter le propriétaire

                            </button>

                        <?php endif; ?>

                    </div>

                </section>


                <!-- =================================================
                     QR CODE
                ================================================== -->
<!-- =================================================
     QR CODE
================================================== -->

<?php if ($qrUrl !== ''): ?>

    <section class="rounded-[22px] border border-[#eadfd5] bg-white p-5 shadow-sm">

        <div class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4eadf] text-[#a98260]">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M14 14h3v3h-3zm4 4h3v3h-3zm-4 0h2"
                    />
                </svg>

            </div>

            <div>
                <h2 class="font-bold text-[#30221b]">
                    QR Code du bien
                </h2>

                <p class="text-xs text-[#9b8d82]">
                    Scannez pour retrouver cette annonce
                </p>
            </div>

        </div>

        <div class="mt-5 flex justify-center rounded-2xl border border-[#eadfd5] bg-[#faf7f2] p-5">

            <img
                src="<?= e($qrUrl) ?>"
                alt="QR Code du bien"
                class="h-44 w-44 rounded-xl object-contain"
                onerror="this.onerror=null;this.alt='QR Code indisponible';"
            >

        </div>

        <?php if ($isOwner): ?>

            <a
                href="/immo-sn/biens/<?= rawurlencode((string) $bienId) ?>/qr"
                class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#49362b]"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3"
                    />
                </svg>

                Télécharger mon QR Code

            </a>

        <?php else: ?>

            <p class="mt-3 text-center text-xs leading-5 text-[#9b8d82]">
                Ce QR Code permet de retrouver facilement cette annonce.
            </p>

        <?php endif; ?>

    </section>

<?php endif; ?>


                <!-- =================================================
                     RÉSUMÉ LOCALISATION
                ================================================== -->

                <section class="rounded-[22px] border border-[#eadfd5] bg-white p-5 shadow-sm">

                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                        Localisation
                    </p>

                    <p class="mt-2 font-bold text-[#30221b]">
                        <?= e($adresse) ?>
                    </p>

                    <?php if ($categorie !== ''): ?>

                        <div class="mt-4 flex items-center justify-between border-t border-[#eee5dc] pt-4">

                            <span class="text-xs text-[#9b8d82]">
                                Catégorie
                            </span>

                            <span class="text-sm font-semibold text-[#30221b]">
                                <?= e($categorie) ?>
                            </span>

                        </div>

                    <?php endif; ?>

                </section>

            </aside>

        </div>

    </main>

</div>


<!-- =============================================================
     POPUP CONTACT
============================================================== -->

<div
    id="contactModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#30221b]/60 px-4 py-6 backdrop-blur-sm"
    aria-hidden="true"
>

    <div
        id="contactModalPanel"
        class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-[24px] bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="contactModalTitle"
    >

        <!-- Header -->

        <div class="border-b border-[#eadfd5] bg-[#fffdf9] p-5 md:p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f4eadf] text-[#a98260]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"
                            />
                        </svg>

                    </div>

                    <h2
                        id="contactModalTitle"
                        class="mt-4 text-xl font-bold text-[#30221b]"
                    >
                        Contacter le propriétaire
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-[#8f8176]">
                        Votre message sera envoyé directement au propriétaire
                        de ce bien.
                    </p>

                </div>


                <button
                    id="closeContactButton"
                    type="button"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-[#8f8176] transition hover:bg-[#f4eadf] hover:text-[#30221b]"
                    aria-label="Fermer"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 6l12 12M18 6 6 18"
                        />
                    </svg>

                </button>

            </div>

        </div>


        <!-- Corps -->

        <div class="p-5 md:p-6">

            <!-- Propriétaire -->

            <div class="flex items-center gap-3 rounded-2xl bg-[#faf7f2] p-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#30221b] text-sm font-bold text-[#f7b500]">
                    <?= e($initiales) ?>
                </div>

                <div class="min-w-0">

                    <p class="text-xs text-[#9b8d82]">
                        Vous contactez
                    </p>

                    <p class="truncate text-sm font-bold text-[#30221b]">
                        <?= e($proprioNomComplet) ?>
                    </p>

                    <p class="mt-1 truncate text-xs text-[#8f8176]">
                        <?= e($titre) ?>
                    </p>

                </div>

            </div>


            <!-- Téléphone -->

            <?php if ($telephone !== ''): ?>

                <a
                    href="tel:<?= e($telephone) ?>"
                    class="mt-4 flex items-center justify-between rounded-xl border border-[#eadfd5] px-4 py-3 transition hover:bg-[#faf7f2]"
                >

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#edf8ef] text-[#5caa70]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"
                                />
                            </svg>

                        </div>

                        <div>

                            <p class="text-[10px] text-[#9b8d82]">
                                Téléphone
                            </p>

                            <p class="text-sm font-bold text-[#30221b]">
                                <?= e($telephone) ?>
                            </p>

                        </div>

                    </div>

                    <span class="text-xs font-bold text-[#725238]">
                        Appeler
                    </span>

                </a>

            <?php endif; ?>


            <!-- Formulaire -->

            <form
                action="/immo-sn/messages/envoyer"
                method="POST"
                class="mt-5 space-y-4"
            >

                <input
                    type="hidden"
                    name="bien_id"
                    value="<?= e($bienId) ?>"
                >

                <input
                    type="hidden"
                    name="destinataire_id"
                    value="<?= e($bien['user_id'] ?? '') ?>"
                >


                <div>

                    <label
                        for="messageContent"
                        class="mb-2 block text-sm font-bold text-[#30221b]"
                    >
                        Votre message
                    </label>

                    <textarea
                        id="messageContent"
                        name="contenu"
                        rows="6"
                        required
                        minlength="3"
                        placeholder="Bonjour, je suis intéressé(e) par ce bien. Est-il toujours disponible ?"
                        class="w-full resize-none rounded-xl border border-[#e7ddd3] bg-white px-4 py-3 text-sm leading-6 text-[#30221b] outline-none transition placeholder:text-[#aaa098] focus:border-[#a98260] focus:ring-2 focus:ring-[#f4eadf]"
                    ></textarea>

                    <p class="mt-1.5 text-[11px] text-[#9b8d82]">
                        Votre message sera enregistré dans la messagerie du propriétaire.
                    </p>

                </div>


                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#46342b]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m22 2-7 20-4-9-9-4 20-7Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M22 2 11 13"
                        />

                    </svg>

                    Envoyer le message

                </button>

            </form>

        </div>

    </div>

</div>


<!-- =============================================================
     POPUP SIGNALEMENT
============================================================== -->

<div
    id="reportModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#30221b]/60 px-4 py-6 backdrop-blur-sm"
    aria-hidden="true"
>

    <div
        id="reportModalPanel"
        class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-[24px] bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="reportModalTitle"
    >

        <!-- Header -->

        <div class="border-b border-[#eadfd5] bg-[#fffdf9] p-5 md:p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#fff0ed] text-[#a53c32]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 17h.01"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                            />
                        </svg>

                    </div>

                    <h2
                        id="reportModalTitle"
                        class="mt-4 text-xl font-bold text-[#30221b]"
                    >
                        Signaler ce bien
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-[#8f8176]">
                        Aidez-nous à maintenir des annonces fiables et de qualité.
                    </p>

                </div>


                <button
                    id="closeReportButton"
                    type="button"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-[#8f8176] transition hover:bg-[#f4eadf] hover:text-[#30221b]"
                    aria-label="Fermer"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 6l12 12M18 6 6 18"
                        />
                    </svg>

                </button>

            </div>

        </div>


        <!-- Corps -->

        <div class="p-5 md:p-6">

            <div class="rounded-2xl bg-[#faf7f2] p-4">

                <p class="text-xs text-[#9b8d82]">
                    Bien concerné
                </p>

                <p class="mt-1 font-bold text-[#30221b]">
                    <?= e($titre) ?>
                </p>

            </div>


            <form
                action="/immo-sn/signalements"
                method="POST"
                class="mt-5 space-y-5"
            >

                <input
                    type="hidden"
                    name="bien_id"
                    value="<?= e($bienId) ?>"
                >


                <div>

                    <label
                        for="reportReason"
                        class="mb-2 block text-sm font-bold text-[#30221b]"
                    >
                        Motif du signalement
                    </label>

                    <select
                        id="reportReason"
                        name="motif"
                        required
                        class="w-full rounded-xl border border-[#e7ddd3] bg-white px-4 py-3 text-sm text-[#30221b] outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#f4eadf]"
                    >

                        <option value="">
                            Sélectionner un motif
                        </option>

                        <option value="Annonce frauduleuse">
                            Annonce frauduleuse
                        </option>

                        <option value="Informations incorrectes">
                            Informations incorrectes
                        </option>

                        <option value="Bien déjà vendu ou loué">
                            Bien déjà vendu ou loué
                        </option>

                        <option value="Prix incorrect">
                            Prix incorrect
                        </option>

                        <option value="Contenu inapproprié">
                            Contenu inapproprié
                        </option>

                        <option value="Fausse localisation">
                            Fausse localisation
                        </option>

                        <option value="Autre">
                            Autre
                        </option>

                    </select>

                </div>


                <div class="rounded-xl border border-[#eadfd5] bg-[#fffdf9] p-4">

                    <div class="flex gap-3">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mt-0.5 h-5 w-5 shrink-0 text-[#a98260]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 17h.01"
                            />

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />

                        </svg>

                        <p class="text-xs leading-5 text-[#8f8176]">
                            Votre signalement sera transmis à l'administration
                            pour vérification. Le statut initial sera
                            <strong class="text-[#30221b]">en attente</strong>.
                        </p>

                    </div>

                </div>


                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#a53c32] px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#8f3028]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v4"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 17h.01"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                        />
                    </svg>

                    Signaler le bien

                </button>

            </form>

        </div>

    </div>

</div>


<!-- =============================================================
     JAVASCRIPT
============================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Photos
    |--------------------------------------------------------------------------
    */

    const mainImage =
        document.getElementById('mainPropertyImage');

    const thumbnails =
        document.querySelectorAll('.photoThumbnail');

    thumbnails.forEach(function (thumbnail) {

        thumbnail.addEventListener('click', function () {

            const image =
                thumbnail.getAttribute('data-image');

            if (!image || !mainImage) {
                return;
            }

            mainImage.src = image;

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Contact Modal
    |--------------------------------------------------------------------------
    */

    const contactModal =
        document.getElementById('contactModal');

    const openContactButton =
        document.getElementById('openContactButton');

    const closeContactButton =
        document.getElementById('closeContactButton');

    function openContactModal() {

        if (!contactModal) {
            return;
        }

        contactModal.classList.remove(
            'hidden'
        );

        contactModal.classList.add(
            'flex'
        );

        contactModal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );

        const textarea =
            document.getElementById(
                'messageContent'
            );

        if (textarea) {

            setTimeout(function () {

                textarea.focus();

            }, 100);
        }
    }


    function closeContactModal() {

        if (!contactModal) {
            return;
        }

        contactModal.classList.add(
            'hidden'
        );

        contactModal.classList.remove(
            'flex'
        );

        contactModal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );
    }


    if (openContactButton) {

        openContactButton.addEventListener(
            'click',
            openContactModal
        );

    }


    if (closeContactButton) {

        closeContactButton.addEventListener(
            'click',
            closeContactModal
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Report Modal
    |--------------------------------------------------------------------------
    */

    const reportModal =
        document.getElementById('reportModal');

    const openReportButton =
        document.getElementById('openReportButton');

    const closeReportButton =
        document.getElementById('closeReportButton');


    function openReportModal() {

        if (!reportModal) {
            return;
        }

        reportModal.classList.remove(
            'hidden'
        );

        reportModal.classList.add(
            'flex'
        );

        reportModal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );
    }


    function closeReportModal() {

        if (!reportModal) {
            return;
        }

        reportModal.classList.add(
            'hidden'
        );

        reportModal.classList.remove(
            'flex'
        );

        reportModal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );
    }


    if (openReportButton) {

        openReportButton.addEventListener(
            'click',
            openReportModal
        );

    }


    if (closeReportButton) {

        closeReportButton.addEventListener(
            'click',
            closeReportModal
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Fermeture en cliquant sur l'arrière-plan
    |--------------------------------------------------------------------------
    */

    if (contactModal) {

        contactModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === contactModal
                ) {

                    closeContactModal();

                }

            }
        );

    }


    if (reportModal) {

        reportModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === reportModal
                ) {

                    closeReportModal();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Touche ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key !== 'Escape') {
                return;
            }

            closeContactModal();
            closeReportModal();

        }
    );

});

</script>