
<?php

require BASE_PATH . '/app/Views/components/icon.php';

/*
|--------------------------------------------------------------------------
| DONNÉES DE LA PAGE D'ACCUEIL
|--------------------------------------------------------------------------
*/

$biensAccueil = is_array($biens ?? null) ? $biens : [];
$agencesAccueil = is_array($agences ?? null) ? $agences : [];
$resumeAccueil = is_array($resume ?? null) ? $resume : [];
$categoriesAccueil = is_array($categories ?? null) ? $categories : [];

$imageHero = 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1800&q=85';

$imageFallback = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80';

/*
|--------------------------------------------------------------------------
| STATISTIQUES RÉELLES
|--------------------------------------------------------------------------
*/

$totalBiens = (int) ($resumeAccueil['biens'] ?? 0);
$totalQuartiers = (int) ($resumeAccueil['quartiers'] ?? 0);
$totalAgences = (int) ($resumeAccueil['agences'] ?? 0);

/*
|--------------------------------------------------------------------------
| CONFIGURATION DES CATÉGORIES
|--------------------------------------------------------------------------
*/

$categoriesDesign = [

    'maison' => [
        'icon' => 'house',
        'description' => 'Maisons familiales et résidentielles'
    ],
    'maisons' => [
        'icon' => 'house',
        'description' => 'Maisons familiales et résidentielles'
    ],

    'appartement' => [
        'icon' => 'building-2',
        'description' => 'Appartements modernes et confortables'
    ],
    'appartements' => [
        'icon' => 'building-2',
        'description' => 'Appartements modernes et confortables'
    ],

    'immeuble' => [
        'icon' => 'building',
        'description' => 'Immeubles et opportunités professionnelles'
    ],
    'immeubles' => [
        'icon' => 'building',
        'description' => 'Immeubles et opportunités professionnelles'
    ],

    'bureau' => [
        'icon' => 'briefcase-business',
        'description' => 'Bureaux et espaces professionnels'
    ],
    'bureaux' => [
        'icon' => 'briefcase-business',
        'description' => 'Bureaux et espaces professionnels'
    ],

    'local-commercial' => [
        'icon' => 'store',
        'description' => 'Locaux adaptés aux activités commerciales'
    ],
    'local_commercial' => [
        'icon' => 'store',
        'description' => 'Locaux adaptés aux activités commerciales'
    ],
    'local commercial' => [
        'icon' => 'store',
        'description' => 'Locaux adaptés aux activités commerciales'
    ],
    'locaux-commerciaux' => [
        'icon' => 'store',
        'description' => 'Locaux adaptés aux activités commerciales'
    ],

    'studio' => [
        'icon' => 'door-open',
        'description' => 'Studios modernes et fonctionnels'
    ],
    'studios' => [
        'icon' => 'door-open',
        'description' => 'Studios modernes et fonctionnels'
    ],

    'terrain' => [
        'icon' => 'map',
        'description' => 'Terrains pour vos futurs projets'
    ],
    'terrains' => [
        'icon' => 'map',
        'description' => 'Terrains pour vos futurs projets'
    ],

    'villa' => [
        'icon' => 'house',
        'description' => 'Villas et résidences haut de gamme'
    ],
    'villas' => [
        'icon' => 'house',
        'description' => 'Villas et résidences haut de gamme'
    ],
];

/*
|--------------------------------------------------------------------------
| NORMALISATION DES SLUGS
|--------------------------------------------------------------------------
*/

$normaliserSlugCategorie = static function ($slug) {

    $slug = trim((string) $slug);

    if (function_exists('mb_strtolower')) {
        $slug = mb_strtolower($slug, 'UTF-8');
    } else {
        $slug = strtolower($slug);
    }

    $slug = strtr($slug, [
        'é' => 'e',
        'è' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'à' => 'a',
        'â' => 'a',
        'ä' => 'a',
        'î' => 'i',
        'ï' => 'i',
        'ô' => 'o',
        'ö' => 'o',
        'ù' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ç' => 'c',
    ]);

    $slug = preg_replace('/[\s_]+/', '-', $slug);

    return trim($slug, '-');
};

?>

<!-- =========================================================
     1. HERO — PRÉSENTATION INSTITUTIONNELLE
========================================================= -->

<section class="relative isolate overflow-hidden bg-[#061830]">

    <!-- Image de fond -->
    <div class="absolute inset-0 -z-20">
        <img
            src="<?= htmlspecialchars($imageHero, ENT_QUOTES, 'UTF-8') ?>"
            alt="Présentation immobilière IMMO-SN"
            class="h-full w-full object-cover object-center"
        >
    </div>

    <!-- Voile bleu marine -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#061830] via-[#061830]/95 to-[#061830]/65"></div>

    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-[#061830]/80 via-transparent to-[#061830]/30"></div>

    <!-- Contenu -->
    <div class="mx-auto max-w-7xl px-5 pb-20 pt-16 sm:px-8 sm:pb-24 sm:pt-20 lg:px-10 lg:pb-28 lg:pt-24">

        <div class="grid items-center gap-12 lg:grid-cols-12">

            <!-- Colonne gauche -->
            <div class="lg:col-span-7">

                <div class="mb-6 inline-flex items-center gap-3 border-l-2 border-[#F5C542] pl-4">

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-[#F5C542]">
                            Plateforme immobilière
                        </p>

                        <p class="mt-1 text-xs font-medium text-white/75">
                            La référence immobilière au Sénégal
                        </p>
                    </div>

                </div>

                <h1 class="max-w-2xl text-4xl font-extrabold leading-[1.12] tracking-tight text-white sm:text-5xl lg:text-6xl">

                    Trouvez le bien immobilier

                    <span class="mt-1 block text-[#F5C542]">
                        qui vous correspond.
                    </span>

                </h1>

                <p class="mt-6 max-w-xl text-sm leading-7 text-slate-200 sm:text-base">
                    Découvrez une sélection de maisons, appartements, villas,
                    terrains et espaces professionnels partout au Sénégal.
                    IMMO-SN vous accompagne dans votre recherche immobilière.
                </p>

                <!-- Barre de recherche -->
                <div class="mt-9 max-w-2xl rounded-xl border border-white/20 bg-white p-2 shadow-2xl">

                    <form action="/biens" method="GET" class="flex flex-col gap-2 sm:flex-row">

                        <div class="flex min-h-[52px] flex-1 items-center gap-3 rounded-lg bg-[#F2F5F9] px-4">

                            <span class="text-[#B88700]">
                                <?= icon('search', 'h-5 w-5') ?>
                            </span>

                            <input
                                type="text"
                                name="q"
                                placeholder="Ville, quartier, type de bien..."
                                class="w-full bg-transparent text-sm font-medium text-[#061830] outline-none placeholder:text-slate-500"
                            >

                        </div>

                        <button
                            type="submit"
                            class="inline-flex min-h-[52px] items-center justify-center gap-2 rounded-lg bg-[#F5C542] px-7 text-sm font-extrabold text-[#061830] transition hover:bg-[#e5b32e]"
                        >
                            Rechercher
                            <?= icon('arrow-right', 'h-4 w-4') ?>
                        </button>

                    </form>

                </div>

                <!-- Liens rapides -->
                <div class="mt-5 flex flex-wrap gap-3">

                    <a
                        href="/biens"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#F5C542] px-5 py-3 text-xs font-extrabold text-[#061830] transition hover:bg-[#e5b32e]"
                    >
                        Explorer les biens
                        <?= icon('arrow-right', 'h-4 w-4') ?>
                    </a>

                    <a
                        href="/biens/carte"
                        class="inline-flex items-center gap-2 rounded-lg border border-white/40 bg-white/10 px-5 py-3 text-xs font-bold text-white transition hover:border-[#F5C542] hover:bg-white/20"
                    >
                        <?= icon('map-pin', 'h-4 w-4 text-[#F5C542]') ?>
                        Explorer la carte
                    </a>

                    <a
                        href="/scanner"
                        class="inline-flex items-center gap-2 rounded-lg border border-white/40 bg-white/10 px-5 py-3 text-xs font-bold text-white transition hover:border-[#F5C542] hover:bg-white/20"
                    >
                        <?= icon('qr-code', 'h-4 w-4 text-[#F5C542]') ?>
                        Scanner un QR
                    </a>

                </div>

            </div>

            <!-- Colonne droite : encadré institutionnel -->
            <div class="hidden lg:col-span-5 lg:block">

                <div class="ml-auto max-w-sm border border-white/20 bg-[#061830]/75 p-7 shadow-2xl backdrop-blur-md">

                    <div class="flex items-center gap-3 border-b border-white/15 pb-5">

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#F5C542] text-[#061830]">
                            <?= icon('shield-check', 'h-6 w-6') ?>
                        </div>

                        <div>
                            <p class="text-sm font-extrabold text-white">
                                IMMO-SN
                            </p>
                            <p class="mt-1 text-[10px] font-semibold uppercase tracking-widest text-[#F5C542]">
                                Votre espace immobilier
                            </p>
                        </div>

                    </div>

                    <h2 class="mt-6 text-xl font-bold leading-snug text-white">
                        Une plateforme pensée pour simplifier vos projets immobiliers.
                    </h2>

                    <p class="mt-3 text-xs leading-6 text-slate-300">
                        Recherche, consultation des annonces, localisation
                        et accès rapide aux informations des biens.
                    </p>

                    <div class="mt-6 flex items-center gap-3 border-t border-white/15 pt-5">

                        <div class="flex h-10 w-10 items-center justify-center rounded-full border border-[#F5C542]/50 text-[#F5C542]">
                            <?= icon('check', 'h-5 w-5') ?>
                        </div>

                        <p class="text-xs font-semibold text-white">
                            Une expérience immobilière centralisée.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Bandeau statistiques -->
    <div class="border-t border-white/15 bg-[#061830]/90">

        <div class="mx-auto grid max-w-7xl grid-cols-2 divide-x divide-y divide-white/10 px-5 sm:px-8 md:grid-cols-4 md:divide-y-0 lg:px-10">

            <div class="px-4 py-5 text-center sm:py-6">
                <p class="text-2xl font-extrabold text-[#F5C542]">
                    <?= number_format($totalBiens, 0, ',', ' ') ?>
                </p>
                <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-300 sm:text-xs">
                    Biens référencés
                </p>
            </div>

            <div class="px-4 py-5 text-center sm:py-6">
                <p class="text-2xl font-extrabold text-[#F5C542]">
                    <?= number_format($totalQuartiers, 0, ',', ' ') ?>
                </p>
                <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-300 sm:text-xs">
                    Quartiers couverts
                </p>
            </div>

            <div class="px-4 py-5 text-center sm:py-6">
                <p class="text-2xl font-extrabold text-[#F5C542]">
                    <?= number_format($totalAgences, 0, ',', ' ') ?>
                </p>
                <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-300 sm:text-xs">
                    Agences partenaires
                </p>
            </div>

            <div class="px-4 py-5 text-center sm:py-6">
                <p class="text-2xl font-extrabold text-[#F5C542]">
                    24/7
                </p>
                <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-300 sm:text-xs">
                    Accès plateforme
                </p>
            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     2. CATÉGORIES IMMOBILIÈRES
========================================================= -->

<section class="relative overflow-hidden bg-[#E6EEF8] py-16 sm:py-20">

    <div class="pointer-events-none absolute -right-20 -top-20 h-80 w-80 rounded-full border-[35px] border-[#061830]/[0.035]"></div>

    <div class="relative mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <!-- En-tête -->
        <div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

            <div>

                <div class="mb-3 flex items-center gap-3">
                    <span class="h-[2px] w-8 bg-[#D4A017]"></span>

                    <span class="text-[10px] font-extrabold uppercase tracking-[0.22em] text-[#9A7100]">
                        Nos catégories
                    </span>
                </div>

                <h2 class="text-2xl font-extrabold tracking-tight text-[#061830] sm:text-3xl">
                    Quel bien recherchez-vous ?
                </h2>

                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-600">
                    Identifiez rapidement le type de bien correspondant
                    à votre projet immobilier.
                </p>

            </div>

            <a
                href="/biens"
                class="inline-flex w-fit items-center gap-2 text-xs font-extrabold text-[#061830] transition hover:text-[#9A7100]"
            >
                Voir toutes les annonces
                <?= icon('arrow-right', 'h-4 w-4') ?>
            </a>

        </div>

        <?php if (empty($categoriesAccueil)): ?>

            <div class="border border-slate-200 bg-white p-10 text-center shadow-sm">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-[#E6EEF8] text-[#061830]">
                    <?= icon('house', 'h-7 w-7') ?>
                </div>

                <p class="mt-4 text-sm font-semibold text-slate-600">
                    Aucune catégorie disponible pour le moment.
                </p>

            </div>

        <?php else: ?>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <?php foreach ($categoriesAccueil as $categorie): ?>

                    <?php

                    $nomCategorie = $categorie['libelle'] ?? 'Immobilier';

                    $slugBrut = $categorie['slug'] ?? $nomCategorie;

                    $slugCategorie = $normaliserSlugCategorie($slugBrut);

                    $totalCategorie = (int) ($categorie['total'] ?? 0);

                    $designCategorie = $categoriesDesign[$slugCategorie] ?? [
                        'icon' => 'house',
                        'description' => 'Découvrez nos biens immobiliers'
                    ];

                    ?>

                    <a
                        href="/biens?category_id=<?= urlencode((string) ($categorie['id'] ?? '')) ?>"
                        class="group flex min-h-[220px] flex-col border border-slate-200/80 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D4A017] hover:shadow-xl sm:p-6"
                    >

                        <div class="flex items-start justify-between">

                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#061830] text-[#F5C542] transition group-hover:bg-[#F5C542] group-hover:text-[#061830]">
                                <?= icon($designCategorie['icon'], 'h-6 w-6') ?>
                            </div>

                            <span class="text-slate-300 transition group-hover:text-[#D4A017]">
                                <?= icon('arrow-up-right', 'h-5 w-5') ?>
                            </span>

                        </div>

                        <h3 class="mt-5 text-sm font-extrabold text-[#061830] sm:text-base">
                            <?= htmlspecialchars($nomCategorie, ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p class="mt-2 min-h-[40px] text-xs leading-5 text-slate-500">
                            <?= htmlspecialchars($designCategorie['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="mt-auto border-t border-slate-100 pt-4">

                            <span class="inline-flex items-center gap-2 text-[11px] font-bold text-[#061830]">

                                <span class="h-1.5 w-1.5 rounded-full bg-[#D4A017]"></span>

                                <?= number_format($totalCategorie, 0, ',', ' ') ?>
                                bien<?= $totalCategorie > 1 ? 's' : '' ?>

                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     3. LES BIENS À DÉCOUVRIR
========================================================= -->

<section class="bg-white py-16 sm:py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <!-- En-tête -->
        <div class="mb-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">

            <div>

                <div class="mb-3 flex items-center gap-3">
                    <span class="h-[2px] w-8 bg-[#D4A017]"></span>

                    <span class="text-[10px] font-extrabold uppercase tracking-[0.22em] text-[#9A7100]">
                        Sélection immobilière
                    </span>
                </div>

                <h2 class="text-2xl font-extrabold tracking-tight text-[#061830] sm:text-3xl">
                    Les biens à découvrir
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Retrouvez les opportunités immobilières disponibles sur IMMO-SN.
                </p>

            </div>

            <a
                href="/biens"
                class="hidden items-center gap-2 text-xs font-extrabold text-[#061830] transition hover:text-[#9A7100] sm:inline-flex"
            >
                Consulter toutes les annonces
                <?= icon('arrow-right', 'h-4 w-4') ?>
            </a>

        </div>

        <?php if (empty($biensAccueil)): ?>

            <div class="border border-slate-200 bg-[#F7F9FC] px-5 py-14 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-xl bg-white text-[#B88700] shadow-sm">
                    <?= icon('house', 'h-8 w-8') ?>
                </div>

                <h3 class="mt-5 text-lg font-extrabold text-[#061830]">
                    Aucun bien disponible
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Les nouvelles annonces apparaîtront ici prochainement.
                </p>

            </div>

        <?php else: ?>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <?php foreach (array_slice($biensAccueil, 0, 6) as $bien): ?>

                    <?php

                    $id = $bien['id'] ?? '';

                    $photo = $bien['photo'] ?? null;

                    $photoUrl = $photo
                        ? (str_starts_with($photo, 'http') ? $photo : '/' . ltrim($photo, '/'))
                        : $imageFallback;

                    $titre = $bien['titre'] ?? 'Bien immobilier';

                    $ville = $bien['ville_nom'] ?? 'Sénégal';

                    $quartier = $bien['quartier_nom'] ?? '';

                    $prix = (float) ($bien['prix'] ?? 0);

                    $categorie = $bien['categorie_nom'] ?? 'Immobilier';

                    $typeOffre = strtolower($bien['type_offre'] ?? '');

                    ?>

                    <article class="group overflow-hidden border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D4A017] hover:shadow-xl">

                        <!-- Image -->
                        <div class="relative h-[230px] overflow-hidden bg-[#E6EEF8]">

                            <img
                                src="<?= htmlspecialchars($photoUrl, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                onerror="this.src='<?= htmlspecialchars($imageFallback, ENT_QUOTES, 'UTF-8') ?>'"
                            >

                            <!-- Badges -->
                            <div class="absolute left-4 top-4 flex flex-wrap gap-2">

                                <?php if ($typeOffre === 'location'): ?>

                                    <span class="bg-[#0B3B68] px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-white shadow">
                                        Location
                                    </span>

                                <?php else: ?>

                                    <span class="bg-[#D4A017] px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-[#061830] shadow">
                                        À vendre
                                    </span>

                                <?php endif; ?>

                            </div>

                            <div class="absolute right-4 top-4">
                                <span class="bg-[#061830]/90 px-3 py-1.5 text-[10px] font-bold text-white backdrop-blur-sm">
                                    <?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>

                            <!-- Bandeau inférieur -->
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#061830] via-[#061830]/80 to-transparent px-4 pb-4 pt-12">

                                <div class="flex items-end justify-between gap-3">

                                    <p class="flex min-w-0 items-center gap-1.5 text-xs text-white/90">

                                        <span class="shrink-0 text-[#F5C542]">
                                            <?= icon('map-pin', 'h-4 w-4') ?>
                                        </span>

                                        <span class="truncate">
                                            <?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>

                                            <?php if ($quartier): ?>
                                                · <?= htmlspecialchars($quartier, ENT_QUOTES, 'UTF-8') ?>
                                            <?php endif; ?>
                                        </span>

                                    </p>

                                    <p class="shrink-0 text-sm font-extrabold text-white">

                                        <?= number_format($prix, 0, ',', ' ') ?>

                                        <span class="text-[10px] font-bold text-[#F5C542]">
                                            FCFA
                                        </span>

                                        <?php if ($typeOffre === 'location'): ?>
                                            <span class="text-[10px] font-normal text-white/70">
                                                /mois
                                            </span>
                                        <?php endif; ?>

                                    </p>

                                </div>

                            </div>

                        </div>

                        <!-- Informations -->
                        <div class="p-5">

                            <div class="flex items-start justify-between gap-3">

                                <h3 class="line-clamp-1 text-base font-extrabold text-[#061830] transition group-hover:text-[#0B3B68]">
                                    <a href="/biens/<?= rawurlencode((string) $id) ?>">
                                        <?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                </h3>

                                <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-[#D4A017]"></span>

                            </div>

                            <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-slate-100 pt-4 text-xs text-slate-600">

                                <?php if (!empty($bien['chambres'])): ?>

                                    <span class="inline-flex items-center gap-1.5">
                                        <?= icon('bed-double', 'h-4 w-4 text-[#B88700]') ?>
                                        <?= (int) $bien['chambres'] ?> chambres
                                    </span>

                                <?php endif; ?>

                                <?php if (!empty($bien['surface'])): ?>

                                    <span class="inline-flex items-center gap-1.5">
                                        <?= icon('maximize', 'h-4 w-4 text-[#B88700]') ?>
                                        <?= (int) $bien['surface'] ?> m²
                                    </span>

                                <?php endif; ?>

                                <a
                                    href="/biens/<?= rawurlencode((string) $id) ?>"
                                    class="ml-auto inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[#E6EEF8] text-[#061830] transition hover:bg-[#F5C542]"
                                    aria-label="Voir le bien"
                                >
                                    <?= icon('arrow-up-right', 'h-4 w-4') ?>
                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

            <!-- Bouton mobile et desktop -->
            <div class="mt-10 flex justify-center">

                <a
                    href="/biens"
                    class="inline-flex items-center justify-center gap-3 bg-[#061830] px-7 py-3.5 text-xs font-extrabold text-white transition hover:bg-[#0B3B68]"
                >
                    Voir toutes les annonces
                    <?= icon('arrow-right', 'h-4 w-4 text-[#F5C542]') ?>
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     4. EXPLORER LES BIENS SUR LA CARTE
========================================================= -->

<section class="bg-[#E6EEF8] py-16 sm:py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="relative overflow-hidden bg-[#061830] shadow-xl">

            <!-- Motif décoratif -->
            <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border-[35px] border-white/[0.04]"></div>

            <div class="grid lg:grid-cols-12">

                <!-- Image -->
                <div class="relative min-h-[220px] lg:col-span-5">

                    <img
                        src="https://images.unsplash.com/photo-1524666041070-9d87656c25f5?auto=format&fit=crop&w=900&q=80"
                        alt="Localisation géographique des biens immobiliers"
                        class="absolute inset-0 h-full w-full object-cover"
                    >

                    <div class="absolute inset-0 bg-gradient-to-r from-transparent to-[#061830]/70"></div>

                    <div class="absolute bottom-5 left-5 flex items-center gap-3 bg-[#061830]/90 px-4 py-3 backdrop-blur-sm">

                        <span class="flex h-9 w-9 items-center justify-center bg-[#F5C542] text-[#061830]">
                            <?= icon('map-pin', 'h-5 w-5') ?>
                        </span>

                        <div>
                            <p class="text-xs font-bold text-white">
                                Exploration géographique
                            </p>
                            <p class="mt-1 text-[10px] text-slate-300">
                                Sénégal
                            </p>
                        </div>

                    </div>

                </div>

                <!-- Texte -->
                <div class="flex flex-col justify-center px-6 py-9 sm:px-10 lg:col-span-7 lg:px-12">

                    <div class="mb-4 flex items-center gap-3">
                        <span class="h-[2px] w-8 bg-[#F5C542]"></span>

                        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#F5C542]">
                            Localisation des annonces
                        </span>
                    </div>

                    <h2 class="max-w-xl text-2xl font-extrabold leading-tight text-white sm:text-3xl">
                        Explorez les biens immobiliers sur la carte.
                    </h2>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300">
                        Visualisez les biens disponibles par quartier,
                        repérez leur emplacement et identifiez plus facilement
                        les opportunités correspondant à votre recherche.
                    </p>

                    <div class="mt-7">

                        <a
                            href="/biens/carte"
                            class="inline-flex items-center gap-3 bg-[#F5C542] px-6 py-3.5 text-xs font-extrabold text-[#061830] transition hover:bg-[#e5b32e]"
                        >
                            <?= icon('map', 'h-4 w-4') ?>
                            Accéder à la carte interactive
                            <?= icon('arrow-right', 'h-4 w-4') ?>
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     5. LES AVANTAGES DE LA PLATEFORME
========================================================= -->

<section class="bg-white py-16 sm:py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <!-- En-tête -->
        <div class="mx-auto max-w-2xl text-center">

            <div class="mb-3 flex items-center justify-center gap-3">
                <span class="h-[2px] w-8 bg-[#D4A017]"></span>

                <span class="text-[10px] font-extrabold uppercase tracking-[0.22em] text-[#9A7100]">
                    Notre engagement
                </span>

                <span class="h-[2px] w-8 bg-[#D4A017]"></span>
            </div>

            <h2 class="text-2xl font-extrabold tracking-tight text-[#061830] sm:text-3xl">
                Une expérience immobilière plus simple
            </h2>

            <p class="mt-3 text-sm leading-6 text-slate-500">
                Des outils pratiques pour faciliter la recherche,
                la consultation et la découverte des biens immobiliers.
            </p>

        </div>

        <!-- Avantages -->
        <div class="mt-12 grid gap-6 md:grid-cols-3">

            <!-- Carte 1 -->
            <article class="group border border-slate-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D4A017] hover:shadow-lg">

                <div class="flex h-14 w-14 items-center justify-center bg-[#061830] text-[#F5C542] transition group-hover:bg-[#F5C542] group-hover:text-[#061830]">
                    <?= icon('search-check', 'h-7 w-7') ?>
                </div>

                <p class="mt-6 text-[10px] font-bold uppercase tracking-widest text-[#9A7100]">
                    01 — Recherche
                </p>

                <h3 class="mt-2 text-lg font-extrabold text-[#061830]">
                    Recherche simplifiée
                </h3>

                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Trouvez rapidement un bien grâce aux catégories,
                    aux filtres et à la recherche par localisation.
                </p>

                <div class="mt-6 border-t border-slate-100 pt-4">
                    <span class="text-xs font-bold text-[#061830]">
                        Trouvez plus facilement
                    </span>
                </div>

            </article>

            <!-- Carte 2 -->
            <article class="group border border-slate-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D4A017] hover:shadow-lg">

                <div class="flex h-14 w-14 items-center justify-center bg-[#061830] text-[#F5C542] transition group-hover:bg-[#F5C542] group-hover:text-[#061830]">
                    <?= icon('map-pin', 'h-7 w-7') ?>
                </div>

                <p class="mt-6 text-[10px] font-bold uppercase tracking-widest text-[#9A7100]">
                    02 — Localisation
                </p>

                <h3 class="mt-2 text-lg font-extrabold text-[#061830]">
                    Localisation précise
                </h3>

                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Consultez la position des biens sur une carte
                    interactive pour mieux préparer vos visites.
                </p>

                <div class="mt-6 border-t border-slate-100 pt-4">
                    <span class="text-xs font-bold text-[#061830]">
                        Repérez les opportunités
                    </span>
                </div>

            </article>

            <!-- Carte 3 -->
            <article class="group border border-slate-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D4A017] hover:shadow-lg">

                <div class="flex h-14 w-14 items-center justify-center bg-[#061830] text-[#F5C542] transition group-hover:bg-[#F5C542] group-hover:text-[#061830]">
                    <?= icon('qr-code', 'h-7 w-7') ?>
                </div>

                <p class="mt-6 text-[10px] font-bold uppercase tracking-widest text-[#9A7100]">
                    03 — Technologie
                </p>

                <h3 class="mt-2 text-lg font-extrabold text-[#061830]">
                    Technologie QR
                </h3>

                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Accédez rapidement aux informations d'un bien
                    grâce au système de QR code intégré.
                </p>

                <div class="mt-6 border-t border-slate-100 pt-4">
                    <span class="text-xs font-bold text-[#061830]">
                        Accès rapide aux informations
                    </span>
                </div>

            </article>

        </div>

    </div>

</section>


<!-- =========================================================
     6. AGENCES PARTENAIRES
========================================================= -->

<section class="border-y border-slate-200 bg-[#F2F5F9] py-16 sm:py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <!-- En-tête -->
        <div class="mb-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">

            <div>

                <div class="mb-3 flex items-center gap-3">
                    <span class="h-[2px] w-8 bg-[#D4A017]"></span>

                    <span class="text-[10px] font-extrabold uppercase tracking-[0.22em] text-[#9A7100]">
                        Réseau professionnel
                    </span>
                </div>

                <h2 class="text-2xl font-extrabold tracking-tight text-[#061830] sm:text-3xl">
                    Nos agences partenaires
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Découvrez les professionnels et agences immobilières présents sur IMMO-SN.
                </p>

            </div>

            <?php if (!empty($agencesAccueil)): ?>

                <a
                    href="/agences"
                    class="inline-flex w-fit items-center gap-2 bg-[#061830] px-5 py-3 text-xs font-extrabold text-white transition hover:bg-[#0B3B68]"
                >
                    Toutes les agences
                    <?= icon('arrow-right', 'h-4 w-4 text-[#F5C542]') ?>
                </a>

            <?php endif; ?>

        </div>

        <?php if (!empty($agencesAccueil)): ?>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

                <?php foreach (array_slice($agencesAccueil, 0, 3) as $agence): ?>

                    <?php

                    $nomAgence = $agence['nom_agence'] ?? 'Agence immobilière';

                    $initiale = strtoupper(substr(trim($nomAgence), 0, 1));

                    ?>

                    <a
                        href="/agences/<?= rawurlencode((string) ($agence['id'] ?? '')) ?>"
                        class="group border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D4A017] hover:shadow-lg"
                    >

                        <div class="flex items-center gap-4">

                            <!-- Logo -->
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden border border-[#D4A017]/40 bg-[#061830] text-xl font-extrabold text-[#F5C542]">

                                <?php if (!empty($agence['logo'])): ?>

                                    <img
                                        src="<?= htmlspecialchars($agence['logo'], ENT_QUOTES, 'UTF-8') ?>"
                                        alt="<?= htmlspecialchars($nomAgence, ENT_QUOTES, 'UTF-8') ?>"
                                        class="h-full w-full object-cover"
                                    >

                                <?php else: ?>

                                    <?= htmlspecialchars($initiale, ENT_QUOTES, 'UTF-8') ?>

                                <?php endif; ?>

                            </div>

                            <!-- Informations -->
                            <div class="min-w-0 flex-1">

                                <div class="flex items-start justify-between gap-2">

                                    <h3 class="truncate text-sm font-extrabold text-[#061830] transition group-hover:text-[#9A7100]">
                                        <?= htmlspecialchars($nomAgence, ENT_QUOTES, 'UTF-8') ?>
                                    </h3>

                                    <span class="shrink-0 bg-[#E6EEF8] px-2 py-1 text-[10px] font-bold text-[#061830]">
                                        <?= (int) ($agence['nombre_biens'] ?? 0) ?> biens
                                    </span>

                                </div>

                                <p class="mt-2 flex items-center gap-1.5 truncate text-xs text-slate-500">

                                    <span class="text-[#B88700]">
                                        <?= icon('map-pin', 'h-3.5 w-3.5') ?>
                                    </span>

                                    <?= htmlspecialchars($agence['adresse'] ?? 'Dakar, Sénégal', ENT_QUOTES, 'UTF-8') ?>

                                </p>

                                <?php if (!empty($agence['badge_verifie'])): ?>

                                    <span class="mt-2 inline-flex items-center gap-1 text-[10px] font-bold text-[#997000]">

                                        <?= icon('shield-check', 'h-3.5 w-3.5') ?>

                                        Agence vérifiée

                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">

                            <span class="text-[11px] font-semibold text-slate-500">
                                Consulter l'agence
                            </span>

                            <span class="flex h-8 w-8 items-center justify-center bg-[#E6EEF8] text-[#061830] transition group-hover:bg-[#F5C542]">
                                <?= icon('arrow-up-right', 'h-4 w-4') ?>
                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="border border-slate-200 bg-white p-10 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center bg-[#E6EEF8] text-[#061830]">
                    <?= icon('building', 'h-7 w-7') ?>
                </div>

                <h3 class="mt-4 text-sm font-bold text-[#061830]">
                    Aucune agence partenaire disponible
                </h3>

                <p class="mt-2 text-xs text-slate-500">
                    Les agences partenaires apparaîtront ici prochainement.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     7. APPEL À L'ACTION FINAL
========================================================= -->

<section class="relative overflow-hidden bg-[#061830] py-16 sm:py-20">

    <!-- Décoration -->
    <div class="pointer-events-none absolute -right-20 -top-32 h-96 w-96 rounded-full border-[45px] border-white/[0.035]"></div>

    <div class="pointer-events-none absolute -bottom-32 -left-20 h-80 w-80 rounded-full border-[35px] border-[#F5C542]/[0.08]"></div>

    <div class="relative mx-auto max-w-5xl px-5 text-center sm:px-8">

        <div class="mx-auto flex h-16 w-16 items-center justify-center border border-[#F5C542]/40 bg-[#F5C542]/10 text-[#F5C542]">
            <?= icon('house', 'h-8 w-8') ?>
        </div>

        <p class="mt-6 text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#F5C542]">
            Votre projet immobilier commence ici
        </p>

        <h2 class="mx-auto mt-4 max-w-3xl text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl">
            Donnez vie à votre prochain projet immobilier.
        </h2>

        <p class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-slate-300">
            Explorez les annonces, comparez les biens et découvrez
            les opportunités qui correspondent à vos besoins partout au Sénégal.
        </p>

        <div class="mt-9 flex flex-col justify-center gap-3 sm:flex-row">

            <a
                href="/biens"
                class="inline-flex items-center justify-center gap-3 bg-[#F5C542] px-7 py-4 text-xs font-extrabold text-[#061830] transition hover:bg-[#e5b32e]"
            >
                <?= icon('search', 'h-4 w-4') ?>
                Commencer ma recherche
                <?= icon('arrow-right', 'h-4 w-4') ?>
            </a>

            <a
                href="/biens/carte"
                class="inline-flex items-center justify-center gap-3 border border-white/40 bg-white/5 px-7 py-4 text-xs font-bold text-white transition hover:border-[#F5C542] hover:bg-white/10"
            >
                <?= icon('map', 'h-4 w-4 text-[#F5C542]') ?>
                Explorer la carte
            </a>

        </div>

        <div class="mx-auto mt-10 flex max-w-xl items-center justify-center gap-3 border-t border-white/15 pt-6">

            <span class="text-[#F5C542]">
                <?= icon('shield-check', 'h-5 w-5') ?>
            </span>

            <p class="text-[11px] font-medium text-slate-300">
                IMMO-SN — Votre espace de découverte immobilière au Sénégal.
            </p>

        </div>

    </div>

</section>
