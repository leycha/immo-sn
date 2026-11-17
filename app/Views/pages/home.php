<?php

require BASE_PATH . '/app/Views/components/icon.php';

$biensAccueil = $biens ?? [];
$agencesAccueil = $agences ?? [];

$resumeAccueil = $resume ?? [];
$categoriesAccueil = $categories ?? [];

$imageHero = 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1800&q=85';

$imageFallback = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80';


/*
|--------------------------------------------------------------------------
| Statistiques réelles
|--------------------------------------------------------------------------
*/

$totalBiens = (int) ($resumeAccueil['biens'] ?? 0);

$totalQuartiers = (int) ($resumeAccueil['quartiers'] ?? 0);

$totalAgences = (int) ($resumeAccueil['agences'] ?? 0);


/*
|--------------------------------------------------------------------------
| Icônes et descriptions des catégories
|--------------------------------------------------------------------------
|
| Les noms et nombres viennent de la base de données.
| Cette partie sert uniquement à conserver le design.
|
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

    'local-commercial' => [
        'icon' => 'store',
        'description' => 'Locaux adaptés aux activités commerciales'
    ],

    'local_commercial' => [
        'icon' => 'store',
        'description' => 'Locaux adaptés aux activités commerciales'
    ],

    'studio' => [
        'icon' => 'building-2',
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

];

?>

<!-- =========================================================
     HERO
========================================================= -->

<section class="relative overflow-hidden bg-[#30221b]">

    <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
    <div class="absolute -bottom-40 left-1/3 h-96 w-96 rounded-full bg-white/5 blur-3xl"></div>

    <div class="relative mx-auto max-w-[1500px] px-5 py-6 sm:px-8 lg:px-12">

        <div class="relative min-h-[650px] overflow-hidden rounded-[32px]">

            <img
                src="<?= htmlspecialchars($imageHero, ENT_QUOTES, 'UTF-8') ?>"
                alt="Immobilier ImmoSn"
                class="absolute inset-0 h-full w-full object-cover"
            >

            <div class="absolute inset-0 bg-gradient-to-r from-[#30221b]/95 via-[#30221b]/65 to-[#30221b]/20"></div>

            <div class="relative z-10 flex min-h-[650px] items-center">

                <div class="max-w-3xl px-7 py-16 sm:px-12 lg:px-16">

                    <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-md">

                        <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>

                        <span class="text-xs font-bold uppercase tracking-[0.18em] text-white">
                            La référence immobilière au Sénégal
                        </span>

                    </div>

                    <h1 class="font-['Playfair_Display'] text-5xl font-bold leading-[1.05] tracking-tight text-white sm:text-6xl lg:text-7xl">

                        Trouvez un lieu

                        <span class="text-[#f7b500]">
                            à votre image.
                        </span>

                    </h1>

                    <p class="mt-7 max-w-2xl text-base leading-7 text-white/75 sm:text-lg">
                        Maisons, appartements, villas, terrains et immeubles.
                        Découvrez des opportunités immobilières sélectionnées
                        pour vous, partout au Sénégal.
                    </p>

                    <div class="mt-10 max-w-3xl rounded-[24px] bg-white p-2 shadow-2xl">

                        <form
                            action="/biens"
                            method="GET"
                            class="flex flex-col gap-2 md:flex-row"
                        >

                            <div class="flex flex-1 items-center rounded-[18px] bg-[#faf7f2] px-4 py-3">

                                <span class="mr-3 text-[#a98260]">
                                    <?= icon('search', 'w-5 h-5') ?>
                                </span>

                                <input
                                    type="text"
                                    name="q"
                                    placeholder="Ville, quartier, type de bien..."
                                    class="w-full bg-transparent text-sm font-medium text-[#30221b] outline-none placeholder:text-[#9b8d81]"
                                >

                            </div>

                            <button
                                type="submit"
                                class="flex items-center justify-center gap-2 rounded-[18px] bg-[#30221b] px-7 py-4 text-sm font-bold text-white hover:bg-[#5a402d]"
                            >

                                <?= icon('search', 'w-4 h-4') ?>

                                Rechercher

                            </button>

                        </form>

                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">

                        <a
                            href="/biens"
                            class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white backdrop-blur-sm hover:bg-white/20"
                        >
                            Voir tous les biens
                        </a>

                        <a
                            href="/biens/carte"
                            class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white backdrop-blur-sm hover:bg-white/20"
                        >
                            Explorer la carte
                        </a>

                        <a
                            href="/scanner"
                            class="rounded-full border border-[#f7b500]/50 bg-[#f7b500]/10 px-4 py-2 text-xs font-semibold text-[#f7b500] hover:bg-[#f7b500]/20"
                        >
                            Scanner un QR
                        </a>

                    </div>

                </div>

            </div>

            <div class="absolute bottom-7 right-7 hidden w-[260px] rounded-[22px] border border-white/20 bg-white/95 p-4 shadow-2xl backdrop-blur-md lg:block">

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#30221b] text-[#f7b500]">
                        <?= icon('shield-check', 'w-5 h-5') ?>
                    </div>

                    <div>

                        <p class="text-sm font-extrabold text-[#30221b]">
                            Une plateforme pensée
                        </p>

                        <p class="mt-1 text-xs text-[#85786d]">
                            pour votre projet immobilier
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     STATISTIQUES RÉELLES
========================================================= -->

<section class="relative z-20 -mt-8 px-5 sm:px-8">

    <div class="mx-auto max-w-6xl">

        <div class="grid grid-cols-2 overflow-hidden rounded-[24px] border border-[#eadfd5] bg-white shadow-xl md:grid-cols-4">

            <div class="border-b border-[#eee5db] p-6 text-center md:border-b-0 md:border-r">

                <p class="text-2xl font-extrabold text-[#30221b]">
                    <?= number_format($totalBiens, 0, ',', ' ') ?>
                </p>

                <p class="mt-1 text-xs font-medium text-[#85786d]">
                    Biens référencés
                </p>

            </div>


            <div class="border-b border-[#eee5db] p-6 text-center md:border-b-0 md:border-r">

                <p class="text-2xl font-extrabold text-[#30221b]">
                    <?= number_format($totalQuartiers, 0, ',', ' ') ?>
                </p>

                <p class="mt-1 text-xs font-medium text-[#85786d]">
                    Quartiers couverts
                </p>

            </div>


            <div class="p-6 text-center md:border-r md:border-[#eee5db]">

                <p class="text-2xl font-extrabold text-[#30221b]">
                    <?= number_format($totalAgences, 0, ',', ' ') ?>
                </p>

                <p class="mt-1 text-xs font-medium text-[#85786d]">
                    Agences partenaires
                </p>

            </div>


            <div class="p-6 text-center">

                <p class="text-2xl font-extrabold text-[#30221b]">
                    24/7
                </p>

                <p class="mt-1 text-xs font-medium text-[#85786d]">
                    Accès à la plateforme
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CATEGORIES RÉELLES
========================================================= -->

<section class="bg-[#faf7f2] py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">

            <div>

                <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-[#a98260]">
                    Explorer
                </p>

                <h2 class="font-['Playfair_Display'] text-3xl font-bold text-[#30221b] sm:text-4xl">
                    Quel bien recherchez-vous ?
                </h2>

                <p class="mt-3 max-w-xl text-sm leading-6 text-[#85786d]">
                    Parcourez les principales catégories immobilières
                    disponibles sur notre plateforme.
                </p>

            </div>

            <a
                href="/biens"
                class="text-sm font-bold text-[#725238] hover:text-[#30221b]"
            >
                Voir toutes les annonces →
            </a>

        </div>


        <?php if (empty($categoriesAccueil)): ?>

            <div class="rounded-[24px] border border-[#eadfd5] bg-white p-10 text-center">

                <p class="text-sm text-[#85786d]">
                    Aucune catégorie disponible pour le moment.
                </p>

            </div>

        <?php else: ?>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

                <?php foreach ($categoriesAccueil as $categorie): ?>

                    <?php

                    $nomCategorie = $categorie['libelle'] ?? 'Immobilier';

                    $slugCategorie = strtolower(
                        trim(
                            $categorie['slug']
                            ?? $nomCategorie
                        )
                    );

                    $totalCategorie = (int) ($categorie['total'] ?? 0);

                    $designCategorie = $categoriesDesign[$slugCategorie]
                        ?? [
                            'icon' => 'building-2',
                            'description' => 'Biens immobiliers disponibles'
                        ];

                    ?>

                    <a
                        href="/biens?category_id=<?= urlencode((string) ($categorie['id'] ?? '')) ?>"
                        class="group relative overflow-hidden rounded-[24px] border border-[#eadfd5] bg-white p-6 transition duration-300 hover:-translate-y-1 hover:border-[#dca94b] hover:shadow-xl"
                    >

                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f5efe8] text-[#30221b] transition group-hover:bg-[#30221b] group-hover:text-[#f7b500]">

                            <?= icon($designCategorie['icon'], 'w-6 h-6') ?>

                        </div>

                        <h3 class="mt-6 text-lg font-extrabold text-[#30221b]">

                            <?= htmlspecialchars(
                                $nomCategorie,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </h3>

                        <p class="mt-2 text-xs leading-5 text-[#85786d]">

                            <?= htmlspecialchars(
                                $designCategorie['description'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </p>

                        <div class="mt-5 flex items-center justify-between">

                            <span class="text-xs font-bold text-[#a98260]">

                                <?= number_format(
                                    $totalCategorie,
                                    0,
                                    ',',
                                    ' '
                                ) ?>

                                bien<?= $totalCategorie > 1 ? 's' : '' ?>

                            </span>

                            <span class="text-[#30221b] transition group-hover:translate-x-1">
                                →
                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     BIENS À LA UNE
========================================================= -->

<section class="bg-white py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">

            <div>

                <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-[#a98260]">
                    Sélection ImmoSn
                </p>

                <h2 class="font-['Playfair_Display'] text-3xl font-bold text-[#30221b] sm:text-4xl">
                    Les biens à découvrir
                </h2>

                <p class="mt-3 text-sm text-[#85786d]">
                    Découvrez les dernières opportunités disponibles.
                </p>

            </div>

          

        </div>


        <?php if (empty($biensAccueil)): ?>

            <div class="rounded-[28px] border border-[#eadfd5] bg-[#faf7f2] p-14 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-[#a98260] shadow-sm">
                    <?= icon('house', 'w-7 h-7') ?>
                </div>

                <h3 class="mt-5 text-lg font-extrabold text-[#30221b]">
                    Aucun bien disponible
                </h3>

                <p class="mt-2 text-sm text-[#85786d]">
                    Les nouvelles annonces apparaîtront ici prochainement.
                </p>

            </div>

        <?php else: ?>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                <?php foreach (array_slice($biensAccueil, 0, 6) as $bien): ?>

                    <?php

                    $id = $bien['id'] ?? '';

                    $photo = $bien['photo'] ?? null;

                    $photoUrl = $photo
                        ? (
                            str_starts_with($photo, 'http')
                                ? $photo
                                : '/' . ltrim($photo, '/')
                        )
                        : $imageFallback;

                    $titre = $bien['titre'] ?? 'Bien immobilier';

                    $ville = $bien['ville_nom'] ?? 'Sénégal';

                    $quartier = $bien['quartier_nom'] ?? '';

                    $prix = (float) ($bien['prix'] ?? 0);

                    $categorie = $bien['categorie_nom'] ?? 'Immobilier';

                    $typeOffre = strtolower(
                        $bien['type_offre'] ?? ''
                    );

                    ?>

                    <article class="group overflow-hidden rounded-[28px] border border-[#eadfd5] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                        <div class="relative h-[245px] overflow-hidden">

                            <img
                                src="<?= htmlspecialchars(
                                    $photoUrl,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                alt="<?= htmlspecialchars(
                                    $titre,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                onerror="this.src='<?= htmlspecialchars(
                                    $imageFallback,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>'"
                            >

                            <div class="absolute inset-x-0 top-0 flex items-center justify-between p-4">

                                <span class="rounded-full bg-white/95 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide text-[#30221b] shadow-sm">

                                    <?= htmlspecialchars(
                                        $categorie,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                                <?php if ($typeOffre === 'location'): ?>

                                    <span class="rounded-full bg-[#f7b500] px-3 py-1.5 text-[10px] font-extrabold text-[#30221b]">
                                        LOCATION
                                    </span>

                                <?php else: ?>

                                    <span class="rounded-full bg-[#30221b] px-3 py-1.5 text-[10px] font-extrabold text-white">
                                        VENTE
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>


                        <div class="p-6">

                            <p class="flex items-center gap-1.5 text-xs font-medium text-[#a98260]">

                                <?= icon('map-pin', 'w-3.5 h-3.5') ?>

                                <?= htmlspecialchars(
                                    $ville,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                                <?php if ($quartier): ?>

                                    · <?= htmlspecialchars(
                                        $quartier,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php endif; ?>

                            </p>

                            <h3 class="mt-3 line-clamp-1 text-lg font-extrabold text-[#30221b]">

                                <?= htmlspecialchars(
                                    $titre,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </h3>


                            <div class="mt-5 flex items-center gap-4 border-b border-[#eee5db] pb-5 text-xs text-[#85786d]">

                                <?php if (!empty($bien['chambres'])): ?>

                                    <span class="flex items-center gap-1.5">

                                        <?= icon(
                                            'bed-double',
                                            'w-4 h-4'
                                        ) ?>

                                        <?= (int) $bien['chambres'] ?>
                                        chambres

                                    </span>

                                <?php endif; ?>


                                <?php if (!empty($bien['surface'])): ?>

                                    <span class="flex items-center gap-1.5">

                                        <?= icon(
                                            'maximize',
                                            'w-4 h-4'
                                        ) ?>

                                        <?= (int) $bien['surface'] ?>
                                        m²

                                    </span>

                                <?php endif; ?>

                            </div>


                            <div class="mt-5 flex items-end justify-between gap-3">

                                <div>

                                    <p class="text-xl font-extrabold text-[#30221b]">

                                        <?= number_format(
                                            $prix,
                                            0,
                                            ',',
                                            ' '
                                        ) ?>

                                        <span class="text-sm font-semibold">
                                            FCFA
                                        </span>

                                    </p>

                                    <?php if ($typeOffre === 'location'): ?>

                                        <p class="mt-1 text-xs text-[#85786d]">
                                            par mois
                                        </p>

                                    <?php endif; ?>

                                </div>


                                <a
                                    href="/biens/<?= rawurlencode(
                                        (string) $id
                                    ) ?>"
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f5efe8] text-[#30221b] transition group-hover:bg-[#30221b] group-hover:text-[#f7b500]"
                                    aria-label="Voir le bien"
                                >

                                    <?= icon(
                                        'arrow-up-right',
                                        'w-5 h-5'
                                    ) ?>

                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

        <?php if (!empty($biensAccueil)): ?>
            <div class="mt-8 flex justify-end">
                <a
                    href="/biens"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#30221b] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#5a402d]"
                >
                    Explorer tous les biens
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     CARTE / EXPLORATION
========================================================= -->

<section class="bg-[#faf7f2] py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="grid overflow-hidden rounded-[32px] bg-[#30221b] lg:grid-cols-2">

            <div class="flex flex-col justify-center p-8 sm:p-12 lg:p-16">

                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#f7b500]">
                    Localisez votre futur bien
                </p>

                <h2 class="mt-4 font-['Playfair_Display'] text-3xl font-bold leading-tight text-white sm:text-4xl">
                    Explorez les biens
                    directement sur la carte.
                </h2>

                <p class="mt-5 max-w-xl text-sm leading-7 text-white/65">
                    Visualisez les biens disponibles, repérez leur localisation
                    et trouvez plus facilement le quartier qui correspond
                    à votre projet.
                </p>

                <a
                    href="/biens/carte"
                    class="mt-8 inline-flex w-fit items-center gap-2 rounded-xl bg-[#f7b500] px-6 py-3.5 text-sm font-extrabold text-[#30221b] hover:bg-[#ffd45a]"
                >

                    <?= icon('map', 'w-4 h-4') ?>

                    Explorer la carte

                </a>

            </div>


            <div class="relative min-h-[380px] overflow-hidden">

                <img
                    src="https://images.unsplash.com/photo-1524666041070-9d87656c25f5?auto=format&fit=crop&w=1200&q=80"
                    alt="Carte et localisation"
                    class="absolute inset-0 h-full w-full object-cover"
                >

                <div class="absolute inset-0 bg-[#30221b]/25"></div>

                <div class="absolute left-[25%] top-[32%] flex h-10 w-10 items-center justify-center rounded-full border-4 border-white bg-[#30221b] text-[#f7b500] shadow-xl">
                    <?= icon('map-pin', 'w-4 h-4') ?>
                </div>

                <div class="absolute right-[28%] top-[48%] flex h-10 w-10 items-center justify-center rounded-full border-4 border-white bg-[#f7b500] text-[#30221b] shadow-xl">
                    <?= icon('map-pin', 'w-4 h-4') ?>
                </div>

                <div class="absolute bottom-[20%] left-[48%] flex h-10 w-10 items-center justify-center rounded-full border-4 border-white bg-[#30221b] text-[#f7b500] shadow-xl">
                    <?= icon('map-pin', 'w-4 h-4') ?>
                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     POURQUOI IMMOSN
========================================================= -->

<section class="bg-white py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mx-auto max-w-2xl text-center">

            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#a98260]">
                Pourquoi ImmoSn ?
            </p>

            <h2 class="mt-3 font-['Playfair_Display'] text-3xl font-bold text-[#30221b] sm:text-4xl">
                Une expérience immobilière plus simple.
            </h2>

            <p class="mt-4 text-sm leading-6 text-[#85786d]">
                Nous réunissons recherche, localisation et découverte
                des biens sur une seule plateforme.
            </p>

        </div>


        <div class="mt-12 grid gap-6 md:grid-cols-3">

            <div class="rounded-[26px] border border-[#eadfd5] bg-[#faf7f2] p-7">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#30221b] text-[#f7b500]">
                    <?= icon('search-check', 'w-6 h-6') ?>
                </div>

                <h3 class="mt-6 text-lg font-extrabold text-[#30221b]">
                    Recherche simplifiée
                </h3>

                <p class="mt-3 text-sm leading-6 text-[#85786d]">
                    Trouvez rapidement un bien grâce aux filtres,
                    aux catégories et à la recherche par localisation.
                </p>

            </div>


            <div class="rounded-[26px] border border-[#eadfd5] bg-[#faf7f2] p-7">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#30221b] text-[#f7b500]">
                    <?= icon('map-pin', 'w-6 h-6') ?>
                </div>

                <h3 class="mt-6 text-lg font-extrabold text-[#30221b]">
                    Localisation précise
                </h3>

                <p class="mt-3 text-sm leading-6 text-[#85786d]">
                    Consultez la position des biens sur une carte
                    interactive pour mieux préparer votre visite.
                </p>

            </div>


            <div class="rounded-[26px] border border-[#eadfd5] bg-[#faf7f2] p-7">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#30221b] text-[#f7b500]">
                    <?= icon('qr-code', 'w-6 h-6') ?>
                </div>

                <h3 class="mt-6 text-lg font-extrabold text-[#30221b]">
                    Technologie QR
                </h3>

                <p class="mt-3 text-sm leading-6 text-[#85786d]">
                    Accédez rapidement aux informations d'un bien
                    grâce au système de QR code intégré.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     AGENCES PARTENAIRES
========================================================= -->

<section class="bg-[#faf7f2] py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mb-10">

            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#a98260]">
                Réseau professionnel
            </p>

            <h2 class="mt-3 font-['Playfair_Display'] text-3xl font-bold text-[#30221b] sm:text-4xl">
                Nos agences partenaires
            </h2>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-[#85786d]">
                Découvrez les professionnels et agences immobilières
                présents sur ImmoSn.
            </p>

        </div>


        <?php if (!empty($agencesAccueil)): ?>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

               <?php foreach (array_slice($agencesAccueil, 0, 3) as $agence): ?>
                    <?php

                    $nomAgence = $agence['nom_agence']
                        ?? 'Agence immobilière';

                    $initiale = strtoupper(
                        substr(
                            trim($nomAgence),
                            0,
                            1
                        )
                    );

                    ?>

                    <a
                        href="/agences/<?= rawurlencode(
                            (string) ($agence['id'] ?? '')
                        ) ?>"
                        class="group rounded-[24px] border border-[#eadfd5] bg-white p-5 transition duration-300 hover:-translate-y-1 hover:border-[#dca94b] hover:shadow-xl"
                    >

                        <div class="flex items-center gap-4">

                            <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#30221b] text-xl font-extrabold text-[#f7b500]">

                                <?php if (!empty($agence['logo'])): ?>

                                    <img
                                        src="<?= htmlspecialchars(
                                            $agence['logo'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        alt="<?= htmlspecialchars(
                                            $nomAgence,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        class="h-full w-full object-cover"
                                    >

                                <?php else: ?>

                                    <?= htmlspecialchars(
                                        $initiale,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php endif; ?>

                            </div>


                            <div class="min-w-0 flex-1">

                                <h3 class="truncate font-extrabold text-[#30221b]">
                                    <?= htmlspecialchars(
                                        $nomAgence,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </h3>

                                <?php if (!empty($agence['badge_verifie'])): ?>

                                    <p class="mt-1 flex items-center gap-1 text-xs font-bold text-[#a98260]">

                                        <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#f7b500] text-[9px] text-[#30221b]">
                                            ✓
                                        </span>

                                        Agence vérifiée

                                    </p>

                                <?php endif; ?>

                            </div>


                            <span class="text-lg text-[#a98260] transition group-hover:translate-x-1 group-hover:text-[#30221b]">
                                →
                            </span>

                        </div>


                        <div class="mt-5 flex items-center justify-between border-t border-[#eee5db] pt-4">

                            <p class="flex items-center gap-2 text-xs text-[#85786d]">

                                <?= icon('map-pin', 'w-3.5 h-3.5') ?>

                                <?= htmlspecialchars(
                                    $agence['adresse']
                                    ?? 'Dakar, Sénégal',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </p>

                            <span class="text-xs font-bold text-[#30221b]">

                                <?= (int) (
                                    $agence['nombre_biens'] ?? 0
                                ) ?>

                                biens

                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

<div class="mt-8 flex justify-end">
    <a
        href="/agences"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#30221b] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#5a402d]"
    >
        Découvrir toutes les agences
        <span aria-hidden="true">→</span>
    </a>
</div>

        <?php else: ?>

            <div class="rounded-[24px] border border-[#eadfd5] bg-white p-12 text-center">

                <p class="text-sm text-[#85786d]">
                    Aucune agence partenaire disponible pour le moment.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     CTA FINAL
========================================================= -->

<section class="bg-white px-5 py-20 sm:px-8">

    <div class="mx-auto max-w-7xl">

        <div class="relative overflow-hidden rounded-[32px] bg-[#30221b] px-7 py-14 text-center sm:px-12 lg:px-20">

            <div class="absolute -left-24 -top-24 h-64 w-64 rounded-full bg-[#f7b500]/10 blur-3xl"></div>

            <div class="absolute -bottom-24 -right-24 h-64 w-64 rounded-full bg-[#f7b500]/10 blur-3xl"></div>

            <div class="relative">

                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#f7b500]">
                    Votre prochain projet commence ici
                </p>

                <h2 class="mx-auto mt-4 max-w-3xl font-['Playfair_Display'] text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">
                    Prêt à trouver votre prochain bien immobilier ?
                </h2>

                <p class="mx-auto mt-5 max-w-2xl text-sm leading-6 text-white/65">
                    Explorez les annonces, comparez les biens et
                    trouvez l'opportunité qui correspond à votre projet.
                </p>

                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">

                    <a
                        href="/biens"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#f7b500] px-7 py-4 text-sm font-extrabold text-[#30221b] hover:bg-[#ffd45a]"
                    >

                        <?= icon('search', 'w-4 h-4') ?>

                        Commencer ma recherche

                    </a>

                    <a
                        href="/biens/carte"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-7 py-4 text-sm font-bold text-white hover:bg-white/20"
                    >

                        <?= icon('map', 'w-4 h-4') ?>

                        Explorer la carte

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

