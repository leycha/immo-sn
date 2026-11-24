
<?php

require BASE_PATH . '/app/Views/components/icon.php';

$biensAccueil = is_array($biens ?? null) ? $biens : [];
$agencesAccueil = is_array($agences ?? null) ? $agences : [];
$resumeAccueil = is_array($resume ?? null) ? $resume : [];
$categoriesAccueil = is_array($categories ?? null) ? $categories : [];

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

<section class="relative overflow-hidden bg-[#071B36]">

    <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-[#F5B800]/15 blur-3xl"></div>

    <div class="absolute -bottom-40 left-1/3 h-96 w-96 rounded-full bg-[#71869A]/15 blur-3xl"></div>

    <div class="relative mx-auto max-w-[1500px] px-4 py-5 sm:px-8 lg:px-12">

        <div class="relative min-h-[620px] overflow-hidden rounded-[30px] sm:min-h-[650px]">

            <img
                src="<?= htmlspecialchars($imageHero, ENT_QUOTES, 'UTF-8') ?>"
                alt="Présentation de la plateforme immobilière IMMO-SN"
                class="absolute inset-0 h-full w-full object-cover"
            >

            <div class="absolute inset-0 bg-gradient-to-r from-[#071B36]/95 via-[#071B36]/75 to-[#071B36]/25"></div>

            <div class="absolute inset-0 bg-gradient-to-t from-[#071B36]/50 via-transparent to-transparent"></div>

            <div class="relative z-10 flex min-h-[620px] items-center sm:min-h-[650px]">

                <div class="max-w-3xl px-6 py-16 sm:px-12 lg:px-16">

                    <div class="mb-7 inline-flex items-center gap-3 rounded-full border border-[#F5B800]/30 bg-[#071B36]/45 px-4 py-2.5 backdrop-blur-md">

                        <span class="h-2.5 w-2.5 rounded-full bg-[#F5B800]"></span>

                        <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#F5D66B] sm:text-xs">
                            La référence immobilière au Sénégal
                        </span>

                    </div>

                    <h1 class="font-['Playfair_Display'] text-4xl font-bold leading-[1.08] tracking-tight text-white sm:text-6xl lg:text-7xl">

                        Trouvez un lieu

                        <span class="text-[#F5B800]">
                            à votre image.
                        </span>

                    </h1>

                    <p class="mt-7 max-w-2xl text-sm leading-7 text-white/80 sm:text-lg">
                        Maisons, appartements, villas, terrains et immeubles.
                        Découvrez des opportunités immobilières sélectionnées
                        pour vous, partout au Sénégal.
                    </p>

                    <!-- Recherche -->

                    <div class="mt-10 max-w-3xl rounded-[22px] border border-white/30 bg-white p-2 shadow-2xl">

                        <form
                            action="/biens"
                            method="GET"
                            class="flex flex-col gap-2 md:flex-row"
                        >

                            <div class="flex flex-1 items-center rounded-[16px] bg-[#F5F3ED] px-4 py-3.5">

                                <span class="mr-3 text-[#8A6500]">
                                    <?= icon('search', 'w-5 h-5') ?>
                                </span>

                                <input
                                    type="text"
                                    name="q"
                                    placeholder="Ville, quartier, type de bien..."
                                    class="w-full bg-transparent text-sm font-medium text-[#071B36] outline-none placeholder:text-[#71869A]"
                                >

                            </div>

                            <button
                                type="submit"
                                class="flex items-center justify-center gap-2 rounded-[16px] bg-[#F5B800] px-7 py-4 text-sm font-extrabold text-[#071B36] transition hover:bg-[#D7A200]"
                            >

                                <?= icon('search', 'w-4 h-4') ?>

                                Rechercher

                            </button>

                        </form>

                    </div>

                    <!-- Raccourcis -->

                    <div class="mt-5 flex flex-wrap gap-3">

                        <a
                            href="/biens"
                            class="rounded-full border border-white/25 bg-white/10 px-4 py-2.5 text-xs font-semibold text-white backdrop-blur-sm transition hover:border-[#F5B800]/50 hover:bg-white/20"
                        >
                            Voir tous les biens
                        </a>

                        <a
                            href="/biens/carte"
                            class="rounded-full border border-white/25 bg-white/10 px-4 py-2.5 text-xs font-semibold text-white backdrop-blur-sm transition hover:border-[#F5B800]/50 hover:bg-white/20"
                        >
                            Explorer la carte
                        </a>

                        <a
                            href="/scanner"
                            class="rounded-full border border-[#F5B800]/50 bg-[#F5B800]/10 px-4 py-2.5 text-xs font-semibold text-[#F5D66B] transition hover:bg-[#F5B800]/20"
                        >
                            Scanner un QR
                        </a>

                    </div>

                </div>

            </div>

            <!-- Carte de présentation -->

            <div class="absolute bottom-7 right-7 hidden w-[270px] rounded-[22px] border border-white/30 bg-white/95 p-4 shadow-2xl backdrop-blur-md lg:block">

                <div class="flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">

                        <?= icon('shield-check', 'w-5 h-5') ?>

                    </div>

                    <div>

                        <p class="text-sm font-extrabold text-[#071B36]">
                            Une plateforme pensée
                        </p>

                        <p class="mt-1 text-xs text-[#71869A]">
                            Pour votre projet immobilier
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     STATISTIQUES
========================================================= -->

<section class="relative z-20 -mt-8 px-4 sm:px-8">

    <div class="mx-auto max-w-6xl">

        <div class="grid grid-cols-2 overflow-hidden rounded-[24px] border border-[#E8E2D4] bg-white shadow-xl md:grid-cols-4">

            <div class="border-b border-[#E8E2D4] p-5 text-center md:border-b-0 md:border-r md:p-6">

                <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-[#FFF4CC] text-[#8A6500]">
                    <?= icon('house', 'w-5 h-5') ?>
                </div>

                <p class="text-2xl font-extrabold text-[#071B36]">
                    <?= number_format($totalBiens, 0, ',', ' ') ?>
                </p>

                <p class="mt-1 text-xs font-medium text-[#71869A]">
                    Biens référencés
                </p>

            </div>


            <div class="border-b border-[#E8E2D4] p-5 text-center md:border-b-0 md:border-r md:p-6">

                <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-[#FFF4CC] text-[#8A6500]">
                    <?= icon('map-pin', 'w-5 h-5') ?>
                </div>

                <p class="text-2xl font-extrabold text-[#071B36]">
                    <?= number_format($totalQuartiers, 0, ',', ' ') ?>
                </p>

                <p class="mt-1 text-xs font-medium text-[#71869A]">
                    Quartiers couverts
                </p>

            </div>


            <div class="p-5 text-center md:border-r md:border-[#E8E2D4] md:p-6">

                <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-[#FFF4CC] text-[#8A6500]">
                    <?= icon('building-2', 'w-5 h-5') ?>
                </div>

                <p class="text-2xl font-extrabold text-[#071B36]">
                    <?= number_format($totalAgences, 0, ',', ' ') ?>
                </p>

                <p class="mt-1 text-xs font-medium text-[#71869A]">
                    Agences partenaires
                </p>

            </div>


            <div class="p-5 text-center md:p-6">

                <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-[#FFF4CC] text-[#8A6500]">
                    <?= icon('clock', 'w-5 h-5') ?>
                </div>

                <p class="text-2xl font-extrabold text-[#071B36]">
                    24/7
                </p>

                <p class="mt-1 text-xs font-medium text-[#71869A]">
                    Accès à la plateforme
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CATÉGORIES
========================================================= -->

<section class="home-categories bg-[#F5F3ED] py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">

            <div>

                <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-[#8A6500]">
                    Explorer
                </p>

                <h2 class="font-['Playfair_Display'] text-3xl font-bold text-[#071B36] sm:text-4xl">
                    Quel bien recherchez-vous ?
                </h2>

                <p class="mt-3 max-w-xl text-sm leading-6 text-[#71869A]">
                    Parcourez les principales catégories immobilières
                    disponibles sur notre plateforme.
                </p>

            </div>

            <a
                href="/biens"
                class="inline-flex items-center gap-2 text-sm font-bold text-[#8A6500] transition hover:text-[#071B36]"
            >
                Voir toutes les annonces
                <?= icon('arrow-right', 'w-4 h-4') ?>
            </a>

        </div>


        <?php if (empty($categoriesAccueil)): ?>

            <div class="rounded-[24px] border border-[#E8E2D4] bg-white p-10 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FFF4CC] text-[#8A6500]">
                    <?= icon('layers', 'w-6 h-6') ?>
                </div>

                <p class="mt-4 text-sm text-[#71869A]">
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
                            $categorie['slug'] ?? $nomCategorie
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
                        class="group relative overflow-hidden rounded-[24px] border border-[#E8E2D4] bg-white p-5 transition duration-300 hover:-translate-y-1 hover:border-[#D7A200] hover:shadow-xl sm:p-6"
                    >

                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FFF4CC] text-[#8A6500] transition group-hover:bg-[#F5B800] group-hover:text-[#071B36]">

                            <?= icon($designCategorie['icon'], 'w-6 h-6') ?>

                        </div>

                        <h3 class="mt-6 text-base font-extrabold text-[#071B36] sm:text-lg">

                            <?= htmlspecialchars(
                                $nomCategorie,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </h3>

                        <p class="mt-2 text-xs leading-5 text-[#71869A]">

                            <?= htmlspecialchars(
                                $designCategorie['description'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </p>

                        <div class="mt-5 flex items-center justify-between gap-2">

                            <span class="text-xs font-bold text-[#8A6500]">

                                <?= number_format(
                                    $totalCategorie,
                                    0,
                                    ',',
                                    ' '
                                ) ?>

                                bien<?= $totalCategorie > 1 ? 's' : '' ?>

                            </span>

                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#F5F3ED] text-[#071B36] transition group-hover:translate-x-1 group-hover:bg-[#F5B800] group-hover:text-[#071B36]">

                                <?= icon('arrow-right', 'w-4 h-4') ?>

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

<section class="home-properties bg-white py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">

            <div>

                <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-[#8A6500]">
                    Sélection IMMO-SN
                </p>

                <h2 class="font-['Playfair_Display'] text-3xl font-bold text-[#071B36] sm:text-4xl">
                    Les biens à découvrir
                </h2>

                <p class="mt-3 text-sm text-[#71869A]">
                    Découvrez les dernières opportunités disponibles.
                </p>

            </div>

        </div>


        <?php if (empty($biensAccueil)): ?>

            <div class="rounded-[28px] border border-[#E8E2D4] bg-[#F5F3ED] p-10 text-center sm:p-14">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-[#8A6500] shadow-sm">
                    <?= icon('house', 'w-7 h-7') ?>
                </div>

                <h3 class="mt-5 text-lg font-extrabold text-[#071B36]">
                    Aucun bien disponible
                </h3>

                <p class="mt-2 text-sm text-[#71869A]">
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

                    <article class="group overflow-hidden rounded-[26px] border border-[#E8E2D4] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D7A200] hover:shadow-xl">

                        <!-- Photo -->

                        <div class="relative h-[230px] overflow-hidden sm:h-[245px]">

                            <img
                                src="<?= htmlspecialchars($photoUrl, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                onerror="this.src='<?= htmlspecialchars($imageFallback, ENT_QUOTES, 'UTF-8') ?>'"
                            >

                            <div class="absolute inset-x-0 top-0 flex items-center justify-between gap-2 p-4">

                                <span class="rounded-full bg-white/95 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide text-[#071B36] shadow-sm">

                                    <?= htmlspecialchars(
                                        $categorie,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                                <?php if ($typeOffre === 'location'): ?>

                                    <span class="rounded-full bg-[#F5B800] px-3 py-1.5 text-[10px] font-extrabold text-[#071B36] shadow-sm">
                                        LOCATION
                                    </span>

                                <?php else: ?>

                                    <span class="rounded-full bg-[#071B36] px-3 py-1.5 text-[10px] font-extrabold text-white shadow-sm">
                                        VENTE
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>


                        <!-- Informations du bien -->

                        <div class="p-5 sm:p-6">

                            <p class="flex items-center gap-1.5 text-xs font-medium text-[#71869A]">

                                <?= icon('map-pin', 'w-3.5 h-3.5 text-[#B88700]') ?>

                                <?= htmlspecialchars(
                                    $ville,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                                <?php if ($quartier): ?>

                                    <span class="text-[#A8B2BC]">·</span>

                                    <?= htmlspecialchars(
                                        $quartier,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php endif; ?>

                            </p>

                            <h3 class="mt-3 line-clamp-1 text-lg font-extrabold text-[#071B36]">

                                <?= htmlspecialchars(
                                    $titre,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </h3>


                            <!-- Caractéristiques -->

                            <div class="mt-5 flex min-h-8 items-center gap-4 border-b border-[#E8E2D4] pb-5 text-xs text-[#71869A]">

                                <?php if (!empty($bien['chambres'])): ?>

                                    <span class="flex items-center gap-1.5">

                                        <?= icon('bed-double', 'w-4 h-4 text-[#B88700]') ?>

                                        <?= (int) $bien['chambres'] ?>
                                        chambres

                                    </span>

                                <?php endif; ?>


                                <?php if (!empty($bien['surface'])): ?>

                                    <span class="flex items-center gap-1.5">

                                        <?= icon('maximize', 'w-4 h-4 text-[#B88700]') ?>

                                        <?= (int) $bien['surface'] ?>
                                        m²

                                    </span>

                                <?php endif; ?>

                            </div>


                            <!-- Prix et accès -->

                            <div class="mt-5 flex items-end justify-between gap-3">

                                <div>

                                    <p class="text-xl font-extrabold text-[#071B36]">

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

                                        <p class="mt-1 text-xs text-[#71869A]">
                                            Par mois
                                        </p>

                                    <?php endif; ?>

                                </div>


                                <a
                                    href="/biens/<?= rawurlencode((string) $id) ?>"
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#FFF4CC] text-[#8A6500] transition group-hover:bg-[#F5B800] group-hover:text-[#071B36]"
                                    aria-label="Voir le bien"
                                >

                                    <?= icon('arrow-up-right', 'w-5 h-5') ?>

                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <?php if (!empty($biensAccueil)): ?>

            <div class="mt-9 flex justify-center sm:justify-end">

                <a
                    href="/biens"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-6 py-3.5 text-sm font-extrabold text-[#071B36] transition hover:bg-[#D7A200]"
                >
                    Explorer tous les biens
                    <?= icon('arrow-right', 'w-4 h-4') ?>
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     CARTE / EXPLORATION
========================================================= -->

<section class="bg-[#F5F3ED] py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="grid overflow-hidden rounded-[30px] bg-[#071B36] lg:grid-cols-2">

            <div class="flex flex-col justify-center p-8 sm:p-12 lg:p-16">

                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#F5B800]">
                    Localisez votre futur bien
                </p>

                <h2 class="mt-4 font-['Playfair_Display'] text-3xl font-bold leading-tight text-white sm:text-4xl">
                    Explorez les biens
                    directement sur la carte.
                </h2>

                <p class="mt-5 max-w-xl text-sm leading-7 text-white/70">
                    Visualisez les biens disponibles, repérez leur localisation
                    et trouvez plus facilement le quartier qui correspond
                    à votre projet.
                </p>

                <a
                    href="/biens/carte"
                    class="mt-8 inline-flex w-fit items-center gap-2 rounded-xl bg-[#F5B800] px-6 py-3.5 text-sm font-extrabold text-[#071B36] transition hover:bg-[#D7A200]"
                >

                    <?= icon('map', 'w-4 h-4') ?>

                    Explorer la carte

                    <?= icon('arrow-right', 'w-4 h-4') ?>

                </a>

            </div>


            <div class="relative min-h-[350px] overflow-hidden sm:min-h-[420px]">

                <img
                    src="https://images.unsplash.com/photo-1524666041070-9d87656c25f5?auto=format&fit=crop&w=1200&q=80"
                    alt="Carte et localisation des biens immobiliers"
                    class="absolute inset-0 h-full w-full object-cover"
                >

                <div class="absolute inset-0 bg-[#071B36]/35"></div>

                <div class="absolute left-[25%] top-[32%] flex h-11 w-11 items-center justify-center rounded-full border-4 border-white bg-[#F5B800] text-[#071B36] shadow-xl">
                    <?= icon('map-pin', 'w-4 h-4') ?>
                </div>

                <div class="absolute right-[28%] top-[48%] flex h-11 w-11 items-center justify-center rounded-full border-4 border-white bg-[#071B36] text-[#F5B800] shadow-xl">
                    <?= icon('map-pin', 'w-4 h-4') ?>
                </div>

                <div class="absolute bottom-[20%] left-[48%] flex h-11 w-11 items-center justify-center rounded-full border-4 border-white bg-[#F5B800] text-[#071B36] shadow-xl">
                    <?= icon('map-pin', 'w-4 h-4') ?>
                </div>

                <div class="absolute bottom-6 left-6 rounded-xl border border-white/30 bg-white/95 px-4 py-3 shadow-lg">

                    <p class="flex items-center gap-2 text-xs font-bold text-[#071B36]">

                        <span class="h-2.5 w-2.5 rounded-full bg-[#F5B800]"></span>

                        Découvrez les biens autour de vous

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     POURQUOI IMMO-SN
========================================================= -->

<section class="bg-white py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mx-auto max-w-2xl text-center">

            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#8A6500]">
                Pourquoi IMMO-SN ?
            </p>

            <h2 class="mt-3 font-['Playfair_Display'] text-3xl font-bold text-[#071B36] sm:text-4xl">
                Une expérience immobilière plus simple.
            </h2>

            <p class="mt-4 text-sm leading-6 text-[#71869A]">
                Nous réunissons recherche, localisation et découverte
                des biens sur une seule plateforme.
            </p>

        </div>


        <div class="mt-12 grid gap-6 md:grid-cols-3">

            <!-- Avantage 1 -->

            <div class="rounded-[26px] border border-[#E8E2D4] bg-[#F5F3ED] p-7 transition duration-300 hover:-translate-y-1 hover:border-[#D7A200] hover:shadow-lg">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#071B36] text-[#F5B800]">
                    <?= icon('search-check', 'w-6 h-6') ?>
                </div>

                <h3 class="mt-6 text-lg font-extrabold text-[#071B36]">
                    Recherche simplifiée
                </h3>

                <p class="mt-3 text-sm leading-6 text-[#71869A]">
                    Trouvez rapidement un bien grâce aux filtres,
                    aux catégories et à la recherche par localisation.
                </p>

            </div>


            <!-- Avantage 2 -->

            <div class="rounded-[26px] border border-[#E8E2D4] bg-[#F5F3ED] p-7 transition duration-300 hover:-translate-y-1 hover:border-[#D7A200] hover:shadow-lg">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#071B36] text-[#F5B800]">
                    <?= icon('map-pin', 'w-6 h-6') ?>
                </div>

                <h3 class="mt-6 text-lg font-extrabold text-[#071B36]">
                    Localisation précise
                </h3>

                <p class="mt-3 text-sm leading-6 text-[#71869A]">
                    Consultez la position des biens sur une carte
                    interactive pour mieux préparer votre visite.
                </p>

            </div>


            <!-- Avantage 3 -->

            <div class="rounded-[26px] border border-[#E8E2D4] bg-[#F5F3ED] p-7 transition duration-300 hover:-translate-y-1 hover:border-[#D7A200] hover:shadow-lg">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#071B36] text-[#F5B800]">
                    <?= icon('qr-code', 'w-6 h-6') ?>
                </div>

                <h3 class="mt-6 text-lg font-extrabold text-[#071B36]">
                    Technologie QR
                </h3>

                <p class="mt-3 text-sm leading-6 text-[#71869A]">
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

<section class="home-agencies bg-[#F5F3ED] py-20">

    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mb-10">

            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#8A6500]">
                Réseau professionnel
            </p>

            <h2 class="mt-3 font-['Playfair_Display'] text-3xl font-bold text-[#071B36] sm:text-4xl">
                Nos agences partenaires
            </h2>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-[#71869A]">
                Découvrez les professionnels et agences immobilières
                présents sur IMMO-SN.
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
                        href="/agences/<?= rawurlencode((string) ($agence['id'] ?? '')) ?>"
                        class="group rounded-[24px] border border-[#E8E2D4] bg-white p-5 transition duration-300 hover:-translate-y-1 hover:border-[#D7A200] hover:shadow-xl"
                    >

                        <div class="flex items-center gap-4">

                            <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#071B36] text-xl font-extrabold text-[#F5B800]">

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

                                <h3 class="truncate font-extrabold text-[#071B36]">
                                    <?= htmlspecialchars(
                                        $nomAgence,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </h3>

                                <?php if (!empty($agence['badge_verifie'])): ?>

                                    <p class="mt-1 flex items-center gap-1.5 text-xs font-bold text-[#287A4B]">

                                        <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#E8F5EC] text-[10px] text-[#287A4B]">
                                            ✓
                                        </span>

                                        Agence vérifiée

                                    </p>

                                <?php endif; ?>

                            </div>


                            <span class="text-lg text-[#B88700] transition group-hover:translate-x-1">
                                →
                            </span>

                        </div>


                        <div class="mt-5 flex items-center justify-between gap-3 border-t border-[#E8E2D4] pt-4">

                            <p class="flex min-w-0 items-center gap-2 text-xs text-[#71869A]">

                                <?= icon('map-pin', 'w-3.5 h-3.5 shrink-0 text-[#B88700]') ?>

                                <span class="truncate">
                                    <?= htmlspecialchars(
                                        $agence['adresse'] ?? 'Dakar, Sénégal',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>

                            </p>

                            <span class="shrink-0 text-xs font-bold text-[#071B36]">

                                <?= (int) ($agence['nombre_biens'] ?? 0) ?>

                                biens

                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>


            <div class="mt-8 flex justify-center sm:justify-end">

                <a
                    href="/agences"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-6 py-3.5 text-sm font-extrabold text-[#071B36] transition hover:bg-[#D7A200]"
                >
                    Découvrir toutes les agences
                    <?= icon('arrow-right', 'w-4 h-4') ?>
                </a>

            </div>

        <?php else: ?>

            <div class="rounded-[24px] border border-[#E8E2D4] bg-white p-10 text-center sm:p-12">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FFF4CC] text-[#8A6500]">
                    <?= icon('building-2', 'w-6 h-6') ?>
                </div>

                <p class="mt-4 text-sm text-[#71869A]">
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

        <div class="relative overflow-hidden rounded-[30px] bg-[#071B36] px-7 py-14 text-center sm:px-12 lg:px-20">

            <div class="absolute -left-24 -top-24 h-64 w-64 rounded-full bg-[#F5B800]/15 blur-3xl"></div>

            <div class="absolute -bottom-24 -right-24 h-64 w-64 rounded-full bg-[#71869A]/20 blur-3xl"></div>

            <div class="relative">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-[#F5B800]/30 bg-white/10 text-[#F5B800]">
                    <?= icon('house', 'w-6 h-6') ?>
                </div>

                <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-[#F5B800]">
                    Votre prochain projet commence ici
                </p>

                <h2 class="mx-auto mt-4 max-w-3xl font-['Playfair_Display'] text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">
                    Prêt à trouver votre prochain bien immobilier ?
                </h2>

                <p class="mx-auto mt-5 max-w-2xl text-sm leading-6 text-white/70">
                    Explorez les annonces, comparez les biens et
                    trouvez l'opportunité qui correspond à votre projet.
                </p>

                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">

                    <a
                        href="/biens"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-7 py-4 text-sm font-extrabold text-[#071B36] transition hover:bg-[#D7A200]"
                    >

                        <?= icon('search', 'w-4 h-4') ?>

                        Commencer ma recherche

                    </a>

                    <a
                        href="/biens/carte"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/25 bg-white/10 px-7 py-4 text-sm font-bold text-white transition hover:border-[#F5B800]/50 hover:bg-white/20"
                    >

                        <?= icon('map', 'w-4 h-4') ?>

                        Explorer la carte

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =========================================================
     DESIGN PREMIUM IMMO-SN — BLEU NUIT & OR
     ========================================================= -->
<style>
/* Sections profondes pour réduire l'effet "page blanche" */
.home-categories,
.home-properties,
.home-agencies {
    position: relative;
    isolation: isolate;
    overflow: hidden;
}
.home-categories,
.home-agencies {
    background:
        radial-gradient(ellipse at 8% 0%, rgba(212,175,55,.12), transparent 38%),
        radial-gradient(ellipse at 92% 100%, rgba(48,83,125,.28), transparent 42%),
        #08172b !important;
}
.home-properties {
    background:
        radial-gradient(ellipse at 100% 5%, rgba(212,175,55,.08), transparent 38%),
        #0b1b30 !important;
}
.home-categories::before,
.home-properties::before,
.home-agencies::before {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: .22;
    background-image: radial-gradient(rgba(255,255,255,.22) .65px, transparent .65px);
    background-size: 22px 22px;
    mask-image: linear-gradient(to bottom, black, transparent 75%);
}
.home-categories > div,
.home-properties > div,
.home-agencies > div {
    position: relative;
    z-index: 1;
}

/* Titres et textes sur les sections bleu nuit */
.home-categories h2,
.home-properties h2,
.home-agencies h2 {
    color: #fff !important;
}
.home-categories p,
.home-properties p,
.home-agencies p {
    color: rgba(226,233,243,.72);
}
.home-categories .text-\[\#8A6500\],
.home-properties .text-\[\#8A6500\],
.home-agencies .text-\[\#8A6500\] {
    color: #e3bd56 !important;
}
.home-categories a.inline-flex,
.home-properties a.inline-flex,
.home-agencies a.inline-flex {
    color: #e3bd56 !important;
}

/* Catégories : cartes vitrées, halo or, interaction élégante */
.home-categories .grid > a {
    min-height: 210px;
    border: 1px solid rgba(212,175,55,.25) !important;
    background: linear-gradient(145deg, rgba(25,43,67,.96), rgba(11,24,41,.98)) !important;
    box-shadow: 0 14px 36px rgba(0,0,0,.18);
    transition: transform .28s ease, border-color .28s ease, box-shadow .28s ease;
}
.home-categories .grid > a:hover {
    transform: translateY(-7px);
    border-color: rgba(226,190,82,.8) !important;
    box-shadow: 0 20px 44px rgba(0,0,0,.32), 0 0 26px rgba(212,175,55,.09);
}
.home-categories .grid > a h3 {
    color: #f8fafc !important;
}
.home-categories .grid > a p {
    color: #aebdd0 !important;
}
.home-categories .grid > a .rounded-2xl {
    background: linear-gradient(135deg, #d4af37, #f0d77a) !important;
    color: #0b1b30 !important;
    box-shadow: 0 8px 22px rgba(212,175,55,.15);
}
.home-categories .grid > a .text-xs.font-bold {
    color: #e3bd56 !important;
}
.home-categories .grid > a .rounded-full {
    background: rgba(212,175,55,.12) !important;
    border: 1px solid rgba(212,175,55,.18);
    color: #e3bd56 !important;
}

/* Biens : cartes premium foncées, photo en vedette, prix or */
.home-properties article {
    border: 1px solid rgba(212,175,55,.2) !important;
    background: linear-gradient(145deg, #182a42, #0e1c2e) !important;
    box-shadow: 0 15px 35px rgba(0,0,0,.22);
    transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
}
.home-properties article:hover {
    transform: translateY(-6px);
    border-color: rgba(212,175,55,.7) !important;
    box-shadow: 0 24px 50px rgba(0,0,0,.34), 0 0 24px rgba(212,175,55,.06);
}
.home-properties article > div:first-child {
    position: relative;
}
.home-properties article > div:first-child::after {
    content: "";
    position: absolute;
    inset: 40% 0 0;
    pointer-events: none;
    background: linear-gradient(to top, rgba(8,23,42,.58), transparent);
}
.home-properties article > div:nth-child(2) {
    background: transparent !important;
}
.home-properties article h3 {
    color: #f8fafc !important;
}
.home-properties article .border-b {
    border-color: rgba(255,255,255,.12) !important;
}
.home-properties article .text-xl.font-extrabold {
    color: #e3bd56 !important;
}
.home-properties article .text-xs,
.home-properties article .text-sm {
    color: #b7c5d7 !important;
}
.home-properties article .text-\[\#71869A\] {
    color: #b7c5d7 !important;
}
.home-properties article .bg-\[\#FFF4CC\] {
    background: rgba(212,175,55,.15) !important;
    color: #e3bd56 !important;
}
.home-properties article .group-hover\:bg-\[\#F5B800\] {
    background: #d4af37 !important;
}
.home-properties article a[aria-label="Voir le bien"] {
    background: linear-gradient(135deg, #d4af37, #f0d77a) !important;
    color: #0b1b30 !important;
    box-shadow: 0 8px 20px rgba(212,175,55,.16);
}

/* Agences : panneaux bleu nuit, bordures et éléments or */
.home-agencies .grid > a {
    border: 1px solid rgba(212,175,55,.24) !important;
    background: linear-gradient(145deg, rgba(25,43,67,.98), rgba(10,22,37,.98)) !important;
    box-shadow: 0 14px 35px rgba(0,0,0,.2);
    transition: transform .28s ease, border-color .28s ease, box-shadow .28s ease;
}
.home-agencies .grid > a:hover {
    transform: translateY(-6px);
    border-color: rgba(212,175,55,.8) !important;
    box-shadow: 0 22px 44px rgba(0,0,0,.3), 0 0 24px rgba(212,175,55,.08);
}
.home-agencies .grid > a h3 {
    color: #fff !important;
}
.home-agencies .grid > a .bg-\[\#071B36\] {
    background: linear-gradient(135deg, #d4af37, #f0d77a) !important;
    color: #0b1b30 !important;
}
.home-agencies .grid > a .border-t {
    border-color: rgba(255,255,255,.12) !important;
}
.home-agencies .grid > a .text-\[\#071B36\] {
    color: #e3bd56 !important;
}
.home-agencies .grid > a .text-\[\#B88700\] {
    color: #e3bd56 !important;
}

/* Accessibilité : les effets de mouvement peuvent être désactivés */
@media (prefers-reduced-motion: reduce) {
    .home-categories .grid > a,
    .home-properties article,
    .home-agencies .grid > a {
        transition: none !important;
    }
}
</style>
