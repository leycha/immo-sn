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
     1. HERO : PHOTO LUMINEUSE À DROITE + TEXTE À GAUCHE + COURBE
========================================================= -->
<section class="relative overflow-hidden bg-[#061830] pb-24 pt-12 sm:pb-28 sm:pt-16 lg:pb-32 lg:pt-20">

    <!-- Photo pleine luminosité -->
    <div class="absolute inset-0">
        <img
            src="<?= htmlspecialchars($imageHero, ENT_QUOTES, 'UTF-8') ?>"
            alt="Présentation de la plateforme immobilière IMMO-SN"
            class="h-full w-full object-cover object-right"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-[#061830] via-[#061830]/80 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#061830]/70 via-transparent to-[#061830]/40"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
        <div class="grid items-center gap-8 lg:grid-cols-12">

            <!-- Contenu Texte & Recherche -->
            <div class="lg:col-span-7">

                <div class="mb-4 flex items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-[0.22em] text-[#F5D061]">
                        La référence immobilière au Sénégal
                    </span>
                    <span class="h-[2px] w-10 bg-[#F5C542]"></span>
                </div>

                <h1 class="font-['Playfair_Display'] text-4xl font-bold leading-[1.08] tracking-tight text-white drop-shadow-sm sm:text-6xl">
                    Trouvez un lieu <br>
                    <span class="text-[#F5C542]">à votre image.</span>
                </h1>

                <p class="mt-4 max-w-lg text-sm leading-6 text-white/90 sm:text-base">
                    Maisons, appartements, villas, terrains et immeubles.
                    Découvrez des opportunités immobilières sélectionnées pour vous, partout au Sénégal.
                </p>

                <!-- Barre de recherche -->
                <div class="mt-7 max-w-xl rounded-2xl border border-white/25 bg-white/95 p-1.5 shadow-2xl backdrop-blur-md">
                    <form action="/biens" method="GET" class="flex flex-col gap-1.5 sm:flex-row">
                        <div class="flex flex-1 items-center rounded-xl bg-slate-100 px-3.5 py-2.5">
                            <span class="mr-2.5 text-[#B88700]">
                                <?= icon('search', 'w-4 h-4') ?>
                            </span>
                            <input
                                type="text"
                                name="q"
                                placeholder="Ville, quartier (ex: Almadies, Saly), type de bien..."
                                class="w-full bg-transparent text-sm font-medium text-[#061830] outline-none placeholder:text-slate-500"
                            >
                        </div>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5C542] px-6 py-3 text-sm font-extrabold text-[#061830] shadow transition hover:bg-[#e5b32e]"
                        >
                            Rechercher
                            <?= icon('arrow-right', 'w-4 h-4') ?>
                        </button>
                    </form>
                </div>

                <!-- Boutons pilules -->
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a
                        href="/biens"
                        class="inline-flex items-center gap-2 rounded-full bg-[#F5C542] px-5 py-2.5 text-xs font-extrabold text-[#061830] shadow-lg transition hover:bg-[#e5b32e]"
                    >
                        Explorer les biens
                        <?= icon('arrow-right', 'w-3.5 h-3.5') ?>
                    </a>

                    <a
                        href="/biens/carte"
                        class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-[#061830]/50 px-5 py-2.5 text-xs font-bold text-white backdrop-blur-md transition hover:border-[#F5C542] hover:bg-[#061830]/80"
                    >
                        <span class="flex h-4 w-4 items-center justify-center rounded-full bg-white text-[#061830]">
                            <?= icon('map-pin', 'w-2.5 h-2.5') ?>
                        </span>
                        Explorer la carte
                    </a>

                    <a
                        href="/scanner"
                        class="inline-flex items-center gap-2 rounded-full border border-[#F5C542]/50 bg-[#061830]/50 px-5 py-2.5 text-xs font-bold text-[#F5D061] backdrop-blur-md transition hover:bg-[#F5C542]/20"
                    >
                        <?= icon('qr-code', 'w-3.5 h-3.5') ?>
                        Scanner un QR
                    </a>
                </div>

                <!-- Mini stats intégrées -->
                <div class="mt-8 flex flex-wrap items-center gap-6 border-t border-white/15 pt-5 text-white">
                    <div>
                        <p class="text-xl font-extrabold text-[#F5C542]"><?= number_format($totalBiens, 0, ',', ' ') ?></p>
                        <p class="text-[11px] text-white/80">Biens référencés</p>
                    </div>
                    <div class="h-7 w-px bg-white/20"></div>
                    <div>
                        <p class="text-xl font-extrabold text-[#F5C542]"><?= number_format($totalQuartiers, 0, ',', ' ') ?></p>
                        <p class="text-[11px] text-white/80">Quartiers couverts</p>
                    </div>
                    <div class="h-7 w-px bg-white/20"></div>
                    <div>
                        <p class="text-xl font-extrabold text-[#F5C542]"><?= number_format($totalAgences, 0, ',', ' ') ?></p>
                        <p class="text-[11px] text-white/80">Agences partenaires</p>
                    </div>
                    <div class="h-7 w-px bg-white/20"></div>
                    <div>
                        <p class="text-xl font-extrabold text-[#F5C542]">24/7</p>
                        <p class="text-[11px] text-white/80">Accès plateforme</p>
                    </div>
                </div>

            </div>

            <!-- Badge Sceau Doré à droite -->
            <div class="hidden lg:col-span-5 lg:flex lg:justify-end lg:self-end">
                <div class="flex h-28 w-28 flex-col items-center justify-center rounded-full border-[3px] border-[#F5C542] bg-[#061830]/90 p-2 text-center shadow-2xl ring-4 ring-[#F5C542]/30 backdrop-blur-md">
                    <span class="text-[#F5C542]">
                        <?= icon('shield-check', 'w-5 h-5') ?>
                    </span>
                    <span class="mt-1 font-['Playfair_Display'] text-xs font-bold leading-tight text-[#F5C542]">
                        Qualité<br>Premium
                    </span>
                    <span class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-white/80">
                        IMMO-SN
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- Découpe Courbe vers la section Catégories (#E6EEF8) -->
    <div class="pointer-events-none absolute bottom-0 left-0 right-0 overflow-hidden leading-none">
        <svg class="relative block h-10 w-full sm:h-14" viewBox="0 0 1440 80" preserveAspectRatio="none">
            <path d="M0,35 C480,95 960,-10 1440,45 L1440,80 L0,80 Z" fill="#E6EEF8"></path>
        </svg>
    </div>
</section>


<!-- =========================================================
     2. QUEL BIEN RECHERCHEZ-VOUS ? (FOND BLEU CLAIR + CARDS BLEU NUIT)
========================================================= -->
<section class="relative overflow-hidden bg-[#E6EEF8] py-16">

    <!-- Filigrane architectural subtil en arrière-plan -->
    <svg class="pointer-events-none absolute -right-10 top-4 h-72 w-72 text-[#061830]/[0.05]" viewBox="0 0 200 200" fill="currentColor">
        <path d="M100 15L15 85h25v95h120V85h25L100 15zm0 30l45 37v78h-30v-45H85v45H55V82l45-37z"/>
    </svg>

    <div class="relative z-10 mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mx-auto mb-10 max-w-xl text-center">
            <div class="inline-flex items-center gap-2.5">
                <span class="h-[1.5px] w-6 bg-[#D4A017]"></span>
                <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#B88700]">
                    Explorer nos offres
                </span>
                <span class="h-[1.5px] w-6 bg-[#D4A017]"></span>
            </div>

            <h2 class="mt-1.5 font-['Playfair_Display'] text-2xl font-bold text-[#061830] sm:text-4xl">
                Quel bien recherchez-vous ?
            </h2>
            <p class="mt-1.5 text-xs text-slate-600 sm:text-sm">
                Parcourez les principales catégories immobilières disponibles sur notre plateforme.
            </p>
        </div>

        <?php if (empty($categoriesAccueil)): ?>
            <div class="rounded-2xl border border-[#061830]/10 bg-white/80 p-8 text-center">
                <p class="text-sm text-slate-600">Aucune catégorie disponible pour le moment.</p>
            </div>
        <?php else: ?>
            <!-- Cards en Bleu Nuit (#061830) -->
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                <?php foreach ($categoriesAccueil as $categorie): ?>
                    <?php
                    $nomCategorie = $categorie['libelle'] ?? 'Immobilier';
                    $slugCategorie = strtolower(trim($categorie['slug'] ?? $nomCategorie));
                    $totalCategorie = (int) ($categorie['total'] ?? 0);
                    $designCategorie = $categoriesDesign[$slugCategorie] ?? [
                        'icon' => 'building-2',
                        'description' => 'Biens immobiliers disponibles'
                    ];
                    ?>

                    <a
                        href="/biens?category_id=<?= urlencode((string) ($categorie['id'] ?? '')) ?>"
                        class="group relative flex flex-col items-center rounded-2xl border border-[#12335E] bg-[#061830] px-5 py-6 text-center shadow-lg transition duration-300 hover:-translate-y-1 hover:border-[#F5C542] hover:bg-[#0A2547] hover:shadow-xl"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#F5C542]/15 text-[#F5C542] ring-1 ring-[#F5C542]/30 transition group-hover:scale-110 group-hover:bg-[#F5C542] group-hover:text-[#061830]">
                            <?= icon($designCategorie['icon'], 'w-6 h-6') ?>
                        </div>

                        <h3 class="mt-3.5 text-sm font-extrabold text-white sm:text-base">
                            <?= htmlspecialchars($nomCategorie, ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p class="mt-1 line-clamp-1 text-xs text-slate-300">
                            <?= htmlspecialchars($designCategorie['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="mt-4 flex items-center gap-2">
                            <span class="rounded-full bg-white/10 px-3 py-0.5 text-[11px] font-bold text-[#F5C542]">
                                <?= number_format($totalCategorie, 0, ',', ' ') ?> bien<?= $totalCategorie > 1 ? 's' : '' ?>
                            </span>
                            <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/20 text-white transition group-hover:border-[#F5C542] group-hover:bg-[#F5C542] group-hover:text-[#061830]">
                                <?= icon('arrow-right', 'w-3 h-3') ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>


<!-- =========================================================
     3. LES BIENS À DÉCOUVRIR (FOND BLANC + 3 CARDS/LIGNE + BOUTON OR EN BAS)
========================================================= -->
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mb-10">
            <div class="flex items-center gap-2">
                <span class="h-[1.5px] w-6 bg-[#D4A017]"></span>
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#B88700]">
                    Sélection IMMO-SN
                </span>
            </div>
            <h2 class="mt-2 font-['Playfair_Display'] text-3xl font-bold text-[#061830] sm:text-4xl">
                Les biens à découvrir
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Découvrez les dernières opportunités disponibles.
            </p>
        </div>

        <?php if (empty($biensAccueil)): ?>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-[#B88700] shadow-sm">
                    <?= icon('house', 'w-7 h-7') ?>
                </div>
                <h3 class="mt-5 text-lg font-extrabold text-[#061830]">
                    Aucun bien disponible
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    Les nouvelles annonces apparaîtront ici prochainement.
                </p>
            </div>
        <?php else: ?>
            <!-- 3 cards par ligne (md:grid-cols-2 lg:grid-cols-3) -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
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

                    <article class="group overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-md transition duration-300 hover:-translate-y-1 hover:border-[#F5C542] hover:shadow-xl">

                        <!-- Photo avec bandeau sombre prix & lieu intégré en bas -->
                        <div class="relative h-[235px] overflow-hidden">
                            <img
                                src="<?= htmlspecialchars($photoUrl, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                onerror="this.src='<?= htmlspecialchars($imageFallback, ENT_QUOTES, 'UTF-8') ?>'"
                            >

                            <!-- Badges haut -->
                            <div class="absolute inset-x-0 top-0 flex items-center justify-between p-3.5">
                                <?php if ($typeOffre === 'location'): ?>
                                    <span class="rounded-full bg-[#1D74F5] px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-white shadow">
                                        Location
                                    </span>
                                <?php else: ?>
                                    <span class="rounded-full bg-[#16A34A] px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-white shadow">
                                        À Vendre
                                    </span>
                                <?php endif; ?>

                                <span class="rounded-full bg-[#061830]/85 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-white backdrop-blur-sm">
                                    <?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>

                            <!-- Bandeau sombre bas : Localisation à gauche, Prix à droite -->
                            <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-2 bg-gradient-to-t from-[#061830] via-[#061830]/90 to-transparent px-4 pb-3 pt-9 text-white">
                                <p class="flex min-w-0 items-center gap-1.5 text-xs font-medium text-slate-200">
                                    <span class="shrink-0 text-[#F5C542]">
                                        <?= icon('map-pin', 'w-3.5 h-3.5') ?>
                                    </span>
                                    <span class="truncate">
                                        <?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?><?= $quartier ? ' · ' . htmlspecialchars($quartier, ENT_QUOTES, 'UTF-8') : '' ?>
                                    </span>
                                </p>

                                <p class="shrink-0 text-sm font-extrabold text-white">
                                    <?= number_format($prix, 0, ',', ' ') ?> <span class="text-xs text-[#F5C542]">FCFA</span><?= $typeOffre === 'location' ? '<span class="text-[10px] font-normal text-slate-300">/mois</span>' : '' ?>
                                </p>
                            </div>
                        </div>

                        <!-- Bas de carte -->
                        <div class="p-5">
                            <h3 class="line-clamp-1 text-base font-extrabold text-[#061830] transition group-hover:text-[#0B3B68]">
                                <a href="/biens/<?= rawurlencode((string) $id) ?>">
                                    <?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </h3>

                            <div class="mt-3.5 flex items-center justify-between border-t border-slate-100 pt-3.5 text-xs text-slate-600">
                                <div class="flex items-center gap-4">
                                    <?php if (!empty($bien['chambres'])): ?>
                                        <span class="flex items-center gap-1.5 font-medium">
                                            <?= icon('bed-double', 'w-4 h-4 text-[#B88700]') ?>
                                            <?= (int) $bien['chambres'] ?> chambres
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($bien['surface'])): ?>
                                        <span class="flex items-center gap-1.5 font-medium">
                                            <?= icon('maximize', 'w-4 h-4 text-[#B88700]') ?>
                                            <?= (int) $bien['surface'] ?> m²
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <a
                                    href="/biens/<?= rawurlencode((string) $id) ?>"
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#FFF4CC] text-[#8A6500] transition group-hover:bg-[#F5C542] group-hover:text-[#061830]"
                                    aria-label="Voir le bien"
                                >
                                    <?= icon('arrow-up-right', 'w-4 h-4') ?>
                                </a>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Bouton Jaune Or en bas -->
            <div class="mt-10 flex justify-center sm:justify-end">
                <a
                    href="/biens"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5C542] px-7 py-3.5 text-sm font-extrabold text-[#061830] shadow-md transition hover:bg-[#e5b32e]"
                >
                    Explorer tous les biens
                    <?= icon('arrow-right', 'w-4 h-4') ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>


<!-- =========================================================
     4. EXPLORER LES BIENS SUR LA CARTE (CONSERVÉ EN BLEU NUIT)
========================================================= -->
<section class="bg-[#F2F5F9] py-12">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
        <div class="relative overflow-hidden rounded-2xl bg-[#061830] shadow-xl">

            <!-- Lignes dorées décoratives en courbe -->
            <svg class="pointer-events-none absolute right-0 top-0 h-full w-1/2 opacity-30" viewBox="0 0 400 180" fill="none">
                <path d="M90,-20 C170,70 40,120 150,200" stroke="#F5C542" stroke-width="2" />
                <path d="M150,-20 C230,70 100,120 210,200" stroke="#F5C542" stroke-width="1.5" />
            </svg>

            <div class="grid items-center lg:grid-cols-12">

                <div class="relative h-48 sm:h-52 lg:col-span-4 lg:h-full">
                    <img
                        src="https://images.unsplash.com/photo-1524666041070-9d87656c25f5?auto=format&fit=crop&w=900&q=80"
                        alt="Carte interactive IMMO-SN"
                        class="h-full w-full object-cover"
                    >
                    <div class="absolute inset-0 hidden bg-gradient-to-r from-transparent via-[#061830]/30 to-[#061830] lg:block"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#061830] to-transparent lg:hidden"></div>
                </div>

                <div class="px-6 py-7 sm:px-8 lg:col-span-5">
                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#F5C542]">
                        Localisez votre futur bien
                    </p>
                    <h2 class="mt-1 font-['Playfair_Display'] text-2xl font-bold text-white sm:text-3xl">
                        Explorez les biens sur la carte
                    </h2>
                    <p class="mt-1.5 text-xs text-slate-300 sm:text-sm">
                        Visualisez les biens disponibles par quartier et trouvez l'emplacement idéal.
                    </p>
                </div>

                <div class="px-6 pb-7 lg:col-span-3 lg:flex lg:justify-end lg:px-8 lg:py-0">
                    <a
                        href="/biens/carte"
                        class="inline-flex items-center gap-2 rounded-full bg-[#F5C542] px-6 py-3 text-xs font-extrabold text-[#061830] shadow-lg transition hover:bg-[#e5b32e] sm:text-sm"
                    >
                        <?= icon('map', 'w-4 h-4') ?>
                        Explorer la carte
                        <?= icon('arrow-right', 'w-4 h-4') ?>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>


<!-- =========================================================
     5. UNE EXPÉRIENCE IMMOBILIÈRE PLUS SIMPLE (FOND BLANC + CARDS BLEU NUIT)
========================================================= -->
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mx-auto max-w-xl text-center">
            <div class="inline-flex items-center gap-2.5">
                <span class="h-[1.5px] w-6 bg-[#D4A017]"></span>
                <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#B88700]">
                    Pourquoi IMMO-SN ?
                </span>
                <span class="h-[1.5px] w-6 bg-[#D4A017]"></span>
            </div>

            <h2 class="mt-1.5 font-['Playfair_Display'] text-2xl font-bold text-[#061830] sm:text-4xl">
                Une expérience immobilière plus simple
            </h2>
            <p class="mt-2 text-xs text-slate-500 sm:text-sm">
                Nous réunissons recherche, localisation et découverte des biens sur une seule plateforme.
            </p>
        </div>

        <!-- Petites cards en Bleu Nuit (#061830) -->
        <div class="mt-11 grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-[#12335E] bg-[#061830] p-7 text-white shadow-lg transition duration-300 hover:-translate-y-1 hover:border-[#F5C542]">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#F5C542] text-[#061830]">
                        <?= icon('search-check', 'w-6 h-6') ?>
                    </div>
                    <h3 class="text-base font-extrabold text-white">Recherche simplifiée</h3>
                </div>
                <p class="mt-3.5 text-xs leading-6 text-slate-300 sm:text-sm">
                    Trouvez rapidement un bien grâce aux filtres, aux catégories et à la recherche par localisation.
                </p>
            </div>

            <div class="rounded-2xl border border-[#12335E] bg-[#061830] p-7 text-white shadow-lg transition duration-300 hover:-translate-y-1 hover:border-[#F5C542]">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#F5C542] text-[#061830]">
                        <?= icon('map-pin', 'w-6 h-6') ?>
                    </div>
                    <h3 class="text-base font-extrabold text-white">Localisation précise</h3>
                </div>
                <p class="mt-3.5 text-xs leading-6 text-slate-300 sm:text-sm">
                    Consultez la position des biens sur une carte interactive pour mieux préparer votre visite.
                </p>
            </div>

            <div class="rounded-2xl border border-[#12335E] bg-[#061830] p-7 text-white shadow-lg transition duration-300 hover:-translate-y-1 hover:border-[#F5C542]">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#F5C542] text-[#061830]">
                        <?= icon('qr-code', 'w-6 h-6') ?>
                    </div>
                    <h3 class="text-base font-extrabold text-white">Technologie QR</h3>
                </div>
                <p class="mt-3.5 text-xs leading-6 text-slate-300 sm:text-sm">
                    Accédez rapidement aux informations d'un bien grâce au système de QR code intégré.
                </p>
            </div>
        </div>

    </div>
</section>


<!-- =========================================================
     6. AGENCES PARTENAIRES (FOND BLANC + CARDS BLEU & JAUNE OR)
========================================================= -->
<section class="border-t border-slate-100 bg-white py-16">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <div class="mb-9 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-[1.5px] w-6 bg-[#D4A017]"></span>
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#B88700]">
                        Réseau professionnel
                    </span>
                </div>
                <h2 class="mt-1.5 font-['Playfair_Display'] text-2xl font-bold text-[#061830] sm:text-4xl">
                    Nos Agences Partenaires
                </h2>
                <p class="mt-1.5 text-xs text-slate-500 sm:text-sm">
                    Découvrez les professionnels et agences immobilières présents sur IMMO-SN.
                </p>
            </div>

            <?php if (!empty($agencesAccueil)): ?>
                <a
                    href="/agences"
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#F5C542] px-5 py-2.5 text-xs font-extrabold text-[#061830] shadow-sm transition hover:bg-[#e5b32e]"
                >
                    Découvrir toutes les agences
                    <?= icon('arrow-right', 'w-3.5 h-3.5') ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($agencesAccueil)): ?>
            <div class="grid gap-5 md:grid-cols-3">
                <?php foreach (array_slice($agencesAccueil, 0, 3) as $agence): ?>
                    <?php
                    $nomAgence = $agence['nom_agence'] ?? 'Agence immobilière';
                    $initiale = strtoupper(substr(trim($nomAgence), 0, 1));
                    ?>

                    <!-- Card combinant Bleu Nuit & Jaune Or -->
                    <a
                        href="/agences/<?= rawurlencode((string) ($agence['id'] ?? '')) ?>"
                        class="group flex items-center gap-4 rounded-2xl border-2 border-[#F5C542]/70 bg-[#061830] p-5 text-white shadow-md transition duration-300 hover:-translate-y-1 hover:border-[#F5C542] hover:shadow-xl"
                    >
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-[#F5C542] bg-[#F5C542] text-lg font-extrabold text-[#061830]">
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

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="truncate text-sm font-extrabold text-white sm:text-base">
                                    <?= htmlspecialchars($nomAgence, ENT_QUOTES, 'UTF-8') ?>
                                </h3>
                                <span class="shrink-0 rounded-full bg-[#F5C542] px-2.5 py-0.5 text-[10px] font-extrabold text-[#061830]">
                                    <?= (int) ($agence['nombre_biens'] ?? 0) ?> biens
                                </span>
                            </div>

                            <p class="mt-1 flex items-center gap-1 truncate text-xs text-slate-300">
                                <span class="shrink-0 text-[#F5C542]"><?= icon('map-pin', 'w-3 h-3') ?></span>
                                <span class="truncate"><?= htmlspecialchars($agence['adresse'] ?? 'Dakar, Sénégal', ENT_QUOTES, 'UTF-8') ?></span>
                            </p>

                            <?php if (!empty($agence['badge_verifie'])): ?>
                                <span class="mt-1.5 inline-flex items-center gap-1 text-[11px] font-bold text-[#F5C542]">
                                    ✓ Agence vérifiée
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center">
                <p class="text-sm text-slate-500">Aucune agence partenaire disponible pour le moment.</p>
            </div>
        <?php endif; ?>

    </div>
</section>


<!-- =========================================================
     7. CTA FINAL AVANT LE FOOTER (SANS BLEU NUIT, FOND CLAIR SABLE/OR)
========================================================= -->
<section class="border-t border-[#E8DFC8] bg-[#F8F5EC] py-16 text-center">
    <div class="mx-auto max-w-4xl px-5 sm:px-8">

        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F5C542]/25 text-[#8A6500]">
            <?= icon('house', 'w-6 h-6') ?>
        </div>

        <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#B88700]">
            Votre prochain projet commence ici
        </p>

        <h2 class="mt-2 font-['Playfair_Display'] text-3xl font-bold leading-tight text-[#061830] sm:text-5xl">
            Prêt à trouver votre prochain bien immobilier ?
        </h2>

        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">
            Explorez les annonces, comparez les biens et trouvez l'opportunité qui correspond à votre projet.
        </p>

        <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
            <a
                href="/biens"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5C542] px-8 py-3.5 text-sm font-extrabold text-[#061830] shadow-md transition hover:bg-[#e5b32e]"
            >
                <?= icon('search', 'w-4 h-4') ?>
                Commencer ma recherche
            </a>

            <a
                href="/biens/carte"
                class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#061830] bg-white px-8 py-3.5 text-sm font-bold text-[#061830] transition hover:bg-[#061830] hover:text-white"
            >
                <?= icon('map', 'w-4 h-4') ?>
                Explorer la carte
            </a>
        </div>
    </div>
</section>