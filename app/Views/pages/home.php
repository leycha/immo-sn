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
| Icônes, couleurs pastel (style maquette) et descriptions des catégories
|--------------------------------------------------------------------------
*/
$categoriesDesign = [
    'maison' => [
        'icon' => 'house',
        'bg' => 'bg-sky-100 text-sky-600',
        'description' => 'Maisons familiales et résidentielles'
    ],
    'maisons' => [
        'icon' => 'house',
        'bg' => 'bg-sky-100 text-sky-600',
        'description' => 'Maisons familiales et résidentielles'
    ],
    'appartement' => [
        'icon' => 'building-2',
        'bg' => 'bg-amber-100 text-amber-600',
        'description' => 'Appartements modernes et confortables'
    ],
    'appartements' => [
        'icon' => 'building-2',
        'bg' => 'bg-amber-100 text-amber-600',
        'description' => 'Appartements modernes et confortables'
    ],
    'immeuble' => [
        'icon' => 'building',
        'bg' => 'bg-indigo-100 text-indigo-600',
        'description' => 'Immeubles et opportunités professionnelles'
    ],
    'immeubles' => [
        'icon' => 'building',
        'bg' => 'bg-indigo-100 text-indigo-600',
        'description' => 'Immeubles et opportunités professionnelles'
    ],
    'bureau' => [
        'icon' => 'briefcase-business',
        'bg' => 'bg-purple-100 text-purple-600',
        'description' => 'Bureaux et espaces professionnels'
    ],
    'local-commercial' => [
        'icon' => 'store',
        'bg' => 'bg-emerald-100 text-emerald-600',
        'description' => 'Locaux adaptés aux activités commerciales'
    ],
    'local_commercial' => [
        'icon' => 'store',
        'bg' => 'bg-emerald-100 text-emerald-600',
        'description' => 'Locaux adaptés aux activités commerciales'
    ],
    'studio' => [
        'icon' => 'building-2',
        'bg' => 'bg-cyan-100 text-cyan-600',
        'description' => 'Studios modernes et fonctionnels'
    ],
    'terrain' => [
        'icon' => 'map',
        'bg' => 'bg-teal-100 text-teal-600',
        'description' => 'Terrains pour vos futurs projets'
    ],
    'terrains' => [
        'icon' => 'map',
        'bg' => 'bg-teal-100 text-teal-600',
        'description' => 'Terrains pour vos futurs projets'
    ],
    'villa' => [
        'icon' => 'house',
        'bg' => 'bg-amber-100 text-amber-600',
        'description' => 'Villas et résidences haut de gamme'
    ],
];
?>

<!-- =========================================================
     HERO PLEINE LARGEUR + DÉCOUPE COURBE (STYLE MAQUETTE)
========================================================= -->
<section class="relative overflow-hidden bg-[#061830] pb-24 pt-12 sm:pb-32 sm:pt-16 lg:pb-36 lg:pt-20">

    <!-- Image de fond avec superposition dégradée riche -->
    <div class="absolute inset-0">
        <img
            src="<?= htmlspecialchars($imageHero, ENT_QUOTES, 'UTF-8') ?>"
            alt="Présentation de la plateforme immobilière IMMO-SN"
            class="h-full w-full object-cover object-center opacity-75"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-[#061830] via-[#061830]/85 to-[#061830]/25"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#061830] via-transparent to-[#061830]/60"></div>
    </div>

    <!-- Halo doré subtil -->
    <div class="pointer-events-none absolute -left-24 top-1/4 h-96 w-96 rounded-full bg-[#F5B800]/15 blur-3xl"></div>

    <div class="relative z-10 mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
        <div class="grid items-center gap-12 lg:grid-cols-12">

            <!-- Colonne Principale -->
            <div class="lg:col-span-8">

                <!-- Sur-titre avec ligne dorée (style maquette) -->
                <div class="mb-5 flex items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-[0.22em] text-[#F5D061]">
                        La référence immobilière au Sénégal
                    </span>
                    <span class="h-[2px] w-10 bg-[#F5B800]"></span>
                </div>

                <!-- Titre Principal -->
                <h1 class="font-['Playfair_Display'] text-4xl font-bold leading-[1.1] tracking-tight text-white sm:text-6xl lg:text-[64px]">
                    Trouvez un lieu <br class="hidden sm:inline">
                    <span class="text-[#F5C542]">à votre image.</span>
                </h1>

                <p class="mt-5 max-w-xl text-sm leading-7 text-slate-200 sm:text-base">
                    Maisons, appartements, villas, terrains et immeubles.
                    Découvrez des opportunités immobilières sélectionnées pour vous, partout au Sénégal.
                </p>

                <!-- Barre de recherche intégrée -->
                <div class="mt-8 max-w-2xl rounded-2xl border border-white/20 bg-white/95 p-2 shadow-2xl backdrop-blur-md">
                    <form action="/biens" method="GET" class="flex flex-col gap-2 sm:flex-row">
                        <div class="flex flex-1 items-center rounded-xl bg-slate-100/80 px-4 py-3">
                            <span class="mr-3 text-[#B88700]">
                                <?= icon('search', 'w-5 h-5') ?>
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
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5C542] px-7 py-3.5 text-sm font-extrabold text-[#061830] shadow-md transition hover:bg-[#e5b32e]"
                        >
                            Rechercher
                            <?= icon('arrow-right', 'w-4 h-4') ?>
                        </button>
                    </form>
                </div>

                <!-- Boutons d'action secondaires (pilules comme la maquette) -->
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a
                        href="/biens"
                        class="inline-flex items-center gap-2 rounded-full bg-[#F5C542] px-5 py-2.5 text-xs font-extrabold text-[#061830] shadow-lg shadow-[#F5C542]/20 transition hover:bg-[#e5b32e]"
                    >
                        Explorer les biens
                        <?= icon('arrow-right', 'w-3.5 h-3.5') ?>
                    </a>

                    <a
                        href="/biens/carte"
                        class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-5 py-2.5 text-xs font-bold text-white backdrop-blur-md transition hover:border-[#F5C542] hover:bg-white/20"
                    >
                        <span class="flex h-4 w-4 items-center justify-center rounded-full bg-white text-[#061830]">
                            <?= icon('map-pin', 'w-2.5 h-2.5') ?>
                        </span>
                        Explorer la carte
                    </a>

                    <a
                        href="/scanner"
                        class="inline-flex items-center gap-2 rounded-full border border-[#F5C542]/40 bg-[#061830]/60 px-5 py-2.5 text-xs font-bold text-[#F5D061] backdrop-blur-md transition hover:bg-[#F5C542]/20"
                    >
                        <?= icon('qr-code', 'w-3.5 h-3.5') ?>
                        Scanner un QR
                    </a>
                </div>

                <!-- Bloc Confiance & Clients (inspiré du bas-gauche de la maquette) -->
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex -space-x-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-[#061830] bg-amber-200 text-xs font-bold text-[#061830]">SN</div>
                        <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-[#061830] bg-sky-200 text-xs font-bold text-[#061830]">DK</div>
                        <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-[#061830] bg-emerald-200 text-xs font-bold text-[#061830]">IM</div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-white">
                            Plateforme immobilière de confiance
                        </p>
                        <div class="mt-0.5 flex items-center gap-1 text-xs text-[#F5C542]">
                            <span>★★★★★</span>
                            <span class="ml-1 text-[11px] text-slate-300">Annonces & Agences vérifiées</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Badge Sceau Doré "Qualité Premium" (inspiré du bas-droite de la maquette) -->
            <div class="hidden lg:col-span-4 lg:flex lg:justify-end lg:self-end">
                <div class="relative flex h-32 w-32 flex-col items-center justify-center rounded-full border-4 border-[#F5C542] bg-[#061830]/95 p-3 text-center shadow-2xl ring-4 ring-[#F5C542]/25 backdrop-blur-md">
                    <span class="text-[#F5C542]">
                        <?= icon('shield-check', 'w-6 h-6') ?>
                    </span>
                    <span class="mt-1 font-['Playfair_Display'] text-sm font-bold leading-tight text-[#F5C542]">
                        Qualité<br>Premium
                    </span>
                    <span class="mt-1 text-[9px] font-semibold uppercase tracking-wider text-white/75">
                        IMMO-SN
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- Découpe Courbe (Wave SVG) en bas du Hero -->
    <div class="pointer-events-none absolute bottom-0 left-0 right-0 overflow-hidden leading-none">
        <svg class="relative block h-12 w-full sm:h-16 lg:h-20" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path d="M0,45 C480,100 960,0 1440,55 L1440,90 L0,90 Z" fill="#FFFFFF"></path>
        </svg>
    </div>
</section>


<!-- =========================================================
     STATISTIQUES (BANDEAU FLOTTANT ÉLÉGANT)
========================================================= -->
<section class="relative z-20 -mt-6 px-4 sm:-mt-10 sm:px-8">
    <div class="mx-auto max-w-6xl">
        <div class="grid grid-cols-2 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[0_15px_40px_-15px_rgba(6,24,48,0.12)] md:grid-cols-4">

            <div class="border-b border-slate-100 p-5 text-center md:border-b-0 md:border-r md:p-6">
                <div class="mx-auto mb-2.5 flex h-11 w-11 items-center justify-center rounded-full bg-sky-50 text-sky-600">
                    <?= icon('house', 'w-5 h-5') ?>
                </div>
                <p class="text-2xl font-extrabold text-[#061830]">
                    <?= number_format($totalBiens, 0, ',', ' ') ?>
                </p>
                <p class="mt-0.5 text-xs font-medium text-slate-500">
                    Biens référencés
                </p>
            </div>

            <div class="border-b border-slate-100 p-5 text-center md:border-b-0 md:border-r md:p-6">
                <div class="mx-auto mb-2.5 flex h-11 w-11 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                    <?= icon('map-pin', 'w-5 h-5') ?>
                </div>
                <p class="text-2xl font-extrabold text-[#061830]">
                    <?= number_format($totalQuartiers, 0, ',', ' ') ?>
                </p>
                <p class="mt-0.5 text-xs font-medium text-slate-500">
                    Quartiers couverts
                </p>
            </div>

            <div class="p-5 text-center md:border-r md:border-slate-100 md:p-6">
                <div class="mx-auto mb-2.5 flex h-11 w-11 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                    <?= icon('building-2', 'w-5 h-5') ?>
                </div>
                <p class="text-2xl font-extrabold text-[#061830]">
                    <?= number_format($totalAgences, 0, ',', ' ') ?>
                </p>
                <p class="mt-0.5 text-xs font-medium text-slate-500">
                    Agences partenaires
                </p>
            </div>

            <div class="p-5 text-center md:p-6">
                <div class="mx-auto mb-2.5 flex h-11 w-11 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <?= icon('clock', 'w-5 h-5') ?>
                </div>
                <p class="text-2xl font-extrabold text-[#061830]">
                    24/7
                </p>
                <p class="mt-0.5 text-xs font-medium text-slate-500">
                    Accès à la plateforme
                </p>
            </div>

        </div>
    </div>
</section>


<!-- =========================================================
     CATÉGORIES ("WHAT WE OFFER" STYLE MAQUETTE)
========================================================= -->
<section class="relative bg-white py-20">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <!-- En-tête centré avec lignes dorées (exactement comme la maquette) -->
        <div class="mx-auto mb-12 max-w-2xl text-center">
            <div class="inline-flex items-center gap-3">
                <span class="h-[1.5px] w-7 bg-[#D4A017]"></span>
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#B88700]">
                    Explorer nos offres
                </span>
                <span class="h-[1.5px] w-7 bg-[#D4A017]"></span>
            </div>

            <h2 class="mt-2 font-['Playfair_Display'] text-3xl font-bold text-[#0B3B68] sm:text-4xl">
                Quel bien recherchez-vous ?
            </h2>

            <p class="mt-3 text-sm text-slate-500">
                Des solutions immobilières complètes adaptées à tous vos projets au Sénégal.
            </p>
        </div>

        <?php if (empty($categoriesAccueil)): ?>
            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-10 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <?= icon('layers', 'w-6 h-6') ?>
                </div>
                <p class="mt-4 text-sm text-slate-500">
                    Aucune catégorie disponible pour le moment.
                </p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <?php foreach ($categoriesAccueil as $categorie): ?>
                    <?php
                    $nomCategorie = $categorie['libelle'] ?? 'Immobilier';
                    $slugCategorie = strtolower(trim($categorie['slug'] ?? $nomCategorie));
                    $totalCategorie = (int) ($categorie['total'] ?? 0);
                    $designCategorie = $categoriesDesign[$slugCategorie] ?? [
                        'icon' => 'building-2',
                        'bg' => 'bg-sky-100 text-sky-600',
                        'description' => 'Biens immobiliers disponibles'
                    ];
                    ?>

                    <a
                        href="/biens?category_id=<?= urlencode((string) ($categorie['id'] ?? '')) ?>"
                        class="group relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white p-7 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition duration-300 hover:-translate-y-1.5 hover:border-[#F5C542] hover:shadow-xl"
                    >
                        <!-- Icône circulaire pastel (style maquette) -->
                        <div class="flex h-14 w-14 items-center justify-center rounded-full <?= $designCategorie['bg'] ?> transition duration-300 group-hover:scale-110">
                            <?= icon($designCategorie['icon'], 'w-6 h-6') ?>
                        </div>

                        <h3 class="mt-5 text-base font-extrabold text-[#061830]">
                            <?= htmlspecialchars($nomCategorie, ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p class="mt-1.5 text-xs leading-5 text-slate-500">
                            <?= htmlspecialchars($designCategorie['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="mt-5 flex w-full items-center justify-between border-t border-slate-100 pt-4">
                            <span class="text-xs font-bold text-[#0B3B68]">
                                <?= number_format($totalCategorie, 0, ',', ' ') ?> bien<?= $totalCategorie > 1 ? 's' : '' ?>
                            </span>

                            <span class="flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition group-hover:border-[#061830] group-hover:bg-[#061830] group-hover:text-white">
                                <?= icon('arrow-right', 'w-3.5 h-3.5') ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>


<!-- =========================================================
     BIENS À LA UNE ("OUR FEATURED PROPERTIES" STYLE MAQUETTE)
========================================================= -->
<section class="bg-[#F6F9FC] py-20">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <!-- En-tête aligné gauche + bouton pilule à droite (comme la maquette) -->
        <div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="h-[1.5px] w-6 bg-[#D4A017]"></span>
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#B88700]">
                        Sélection IMMO-SN
                    </span>
                </div>

                <h2 class="mt-2 font-['Playfair_Display'] text-3xl font-bold text-[#0B3B68] sm:text-4xl">
                    Nos Biens à la Une
                </h2>
            </div>

            <a
                href="/biens"
                class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-2.5 text-xs font-bold text-[#061830] shadow-sm transition hover:border-[#061830] hover:bg-[#061830] hover:text-white"
            >
                Voir tous les biens
                <?= icon('arrow-right', 'w-3.5 h-3.5') ?>
            </a>
        </div>

        <?php if (empty($biensAccueil)): ?>
            <div class="rounded-2xl border border-slate-200/70 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-amber-600">
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

                    <article class="group overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-[0_8px_25px_rgba(6,24,48,0.05)] transition duration-300 hover:-translate-y-1.5 hover:shadow-xl">

                        <!-- Image + Badges + Bandeau sombre prix/lieu (exactement comme la maquette) -->
                        <div class="relative h-[230px] overflow-hidden">
                            <img
                                src="<?= htmlspecialchars($photoUrl, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                onerror="this.src='<?= htmlspecialchars($imageFallback, ENT_QUOTES, 'UTF-8') ?>'"
                            >

                            <!-- Badges supérieurs -->
                            <div class="absolute inset-x-0 top-0 flex items-center justify-between p-3.5">
                                <?php if ($typeOffre === 'location'): ?>
                                    <span class="rounded-full bg-[#1D74F5] px-3 py-1 text-[11px] font-bold text-white shadow">
                                        Location
                                    </span>
                                <?php else: ?>
                                    <span class="rounded-full bg-[#16A34A] px-3 py-1 text-[11px] font-bold text-white shadow">
                                        À Vendre
                                    </span>
                                <?php endif; ?>

                                <span class="rounded-full bg-[#061830]/75 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-white backdrop-blur-md">
                                    <?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>

                            <!-- Bandeau inférieur sombre avec Localisation & Prix (style maquette) -->
                            <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-2 bg-gradient-to-t from-[#061830] via-[#061830]/90 to-transparent px-4 pb-3 pt-8 text-white">
                                <p class="flex min-w-0 items-center gap-1.5 text-xs font-medium text-slate-200">
                                    <span class="shrink-0 text-[#F5C542]">
                                        <?= icon('map-pin', 'w-3.5 h-3.5') ?>
                                    </span>
                                    <span class="truncate">
                                        <?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?><?= $quartier ? ', ' . htmlspecialchars($quartier, ENT_QUOTES, 'UTF-8') : '' ?>
                                    </span>
                                </p>

                                <p class="shrink-0 text-sm font-extrabold text-white">
                                    <?= number_format($prix, 0, ',', ' ') ?> <span class="text-[11px] font-semibold text-[#F5C542]">FCFA</span><?= $typeOffre === 'location' ? '<span class="text-[10px] font-normal text-slate-300">/mois</span>' : '' ?>
                                </p>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="line-clamp-1 text-base font-extrabold text-[#061830] transition group-hover:text-[#0B3B68]">
                                    <a href="/biens/<?= rawurlencode((string) $id) ?>">
                                        <?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                </h3>
                            </div>

                            <!-- Caractéristiques & Lien -->
                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3.5 text-xs text-slate-500">
                                <div class="flex items-center gap-4">
                                    <?php if (!empty($bien['chambres'])): ?>
                                        <span class="flex items-center gap-1.5 font-medium">
                                            <?= icon('bed-double', 'w-4 h-4 text-slate-400') ?>
                                            <?= (int) $bien['chambres'] ?> Ch.
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($bien['surface'])): ?>
                                        <span class="flex items-center gap-1.5 font-medium">
                                            <?= icon('maximize', 'w-4 h-4 text-slate-400') ?>
                                            <?= (int) $bien['surface'] ?> m²
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <a
                                    href="/biens/<?= rawurlencode((string) $id) ?>"
                                    class="inline-flex items-center gap-1 text-xs font-bold text-[#0B3B68] transition group-hover:text-[#B88700]"
                                >
                                    Détails
                                    <?= icon('arrow-up-right', 'w-3.5 h-3.5') ?>
                                </a>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>


<!-- =========================================================
     BANNIÈRE CTA CARTE ("READY TO MOVE?" STYLE MAQUETTE)
========================================================= -->
<section class="bg-white py-14">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
        <div class="relative overflow-hidden rounded-3xl bg-[#061830] shadow-2xl">

            <!-- Lignes dorées décoratives en courbe (comme sur la bannière de la maquette) -->
            <svg class="pointer-events-none absolute right-0 top-0 h-full w-1/2 opacity-25" viewBox="0 0 400 200" fill="none">
                <path d="M100,-20 C180,80 50,140 160,220" stroke="#F5C542" stroke-width="2" />
                <path d="M160,-20 C240,80 110,140 220,220" stroke="#F5C542" stroke-width="1.5" />
            </svg>

            <div class="grid items-center lg:grid-cols-12">

                <!-- Visuel gauche -->
                <div class="relative h-56 sm:h-64 lg:col-span-4 lg:h-full">
                    <img
                        src="https://images.unsplash.com/photo-1524666041070-9d87656c25f5?auto=format&fit=crop&w=900&q=80"
                        alt="Carte interactive IMMO-SN"
                        class="h-full w-full object-cover"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#061830]/40 to-[#061830] hidden lg:block"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#061830] to-transparent lg:hidden"></div>

                    <!-- Pin flottant -->
                    <div class="absolute left-6 top-6 flex items-center gap-2 rounded-full bg-white/95 px-3.5 py-1.5 text-xs font-bold text-[#061830] shadow-lg">
                        <span class="h-2 w-2 rounded-full bg-[#16A34A]"></span>
                        Carte interactive
                    </div>
                </div>

                <!-- Texte central -->
                <div class="p-7 sm:p-10 lg:col-span-5">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#F5C542]">
                        Localisez votre futur bien
                    </p>
                    <h2 class="mt-2 font-['Playfair_Display'] text-2xl font-bold text-white sm:text-3xl">
                        Explorez les biens sur la carte
                    </h2>
                    <p class="mt-2.5 text-xs leading-6 text-slate-300 sm:text-sm">
                        Visualisez les biens disponibles par quartier et trouvez plus facilement l'emplacement idéal pour votre projet.
                    </p>
                </div>

                <!-- Bouton droit -->
                <div class="px-7 pb-8 lg:col-span-3 lg:flex lg:justify-end lg:px-10 lg:py-0">
                    <a
                        href="/biens/carte"
                        class="inline-flex items-center gap-2 rounded-full bg-[#F5C542] px-6 py-3.5 text-xs font-extrabold text-[#061830] shadow-lg transition hover:bg-[#e5b32e] sm:text-sm"
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
     POURQUOI IMMO-SN & AGENCES PARTENAIRES
========================================================= -->
<section class="bg-[#F6F9FC] py-20">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">

        <!-- Avantages IMMO-SN -->
        <div class="mx-auto max-w-2xl text-center">
            <div class="inline-flex items-center gap-3">
                <span class="h-[1.5px] w-7 bg-[#D4A017]"></span>
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#B88700]">
                    Pourquoi IMMO-SN ?
                </span>
                <span class="h-[1.5px] w-7 bg-[#D4A017]"></span>
            </div>

            <h2 class="mt-2 font-['Playfair_Display'] text-3xl font-bold text-[#0B3B68] sm:text-4xl">
                Une expérience immobilière plus simple
            </h2>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-100 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#061830] text-[#F5C542]">
                    <?= icon('search-check', 'w-6 h-6') ?>
                </div>
                <h3 class="mt-5 text-base font-extrabold text-[#061830]">
                    Recherche simplifiée
                </h3>
                <p class="mt-2 text-xs leading-6 text-slate-500 sm:text-sm">
                    Trouvez rapidement un bien grâce aux filtres, aux catégories et à la recherche par localisation.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#061830] text-[#F5C542]">
                    <?= icon('map-pin', 'w-6 h-6') ?>
                </div>
                <h3 class="mt-5 text-base font-extrabold text-[#061830]">
                    Localisation précise
                </h3>
                <p class="mt-2 text-xs leading-6 text-slate-500 sm:text-sm">
                    Consultez la position des biens sur une carte interactive pour mieux préparer votre visite.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#061830] text-[#F5C542]">
                    <?= icon('qr-code', 'w-6 h-6') ?>
                </div>
                <h3 class="mt-5 text-base font-extrabold text-[#061830]">
                    Technologie QR intégrée
                </h3>
                <p class="mt-2 text-xs leading-6 text-slate-500 sm:text-sm">
                    Scannez un panneau immobilier sur place pour accéder instantanément à sa fiche complète.
                </p>
            </div>
        </div>

        <!-- Agences Partenaires -->
        <div class="mt-20">
            <div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="h-[1.5px] w-6 bg-[#D4A017]"></span>
                        <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#B88700]">
                            Réseau professionnel
                        </span>
                    </div>
                    <h2 class="mt-2 font-['Playfair_Display'] text-3xl font-bold text-[#0B3B68] sm:text-4xl">
                        Nos Agences Partenaires
                    </h2>
                </div>

                <?php if (!empty($agencesAccueil)): ?>
                    <a
                        href="/agences"
                        class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-2.5 text-xs font-bold text-[#061830] shadow-sm transition hover:border-[#061830] hover:bg-[#061830] hover:text-white"
                    >
                        Toutes les agences
                        <?= icon('arrow-right', 'w-3.5 h-3.5') ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($agencesAccueil)): ?>
                <div class="grid gap-6 md:grid-cols-3">
                    <?php foreach (array_slice($agencesAccueil, 0, 3) as $agence): ?>
                        <?php
                        $nomAgence = $agence['nom_agence'] ?? 'Agence immobilière';
                        $initiale = strtoupper(substr(trim($nomAgence), 0, 1));
                        ?>

                        <a
                            href="/agences/<?= rawurlencode((string) ($agence['id'] ?? '')) ?>"
                            class="group rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#F5C542] hover:shadow-lg"
                        >
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-[#F5C542] bg-[#061830] text-lg font-extrabold text-[#F5C542]">
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
                                    <h3 class="truncate text-base font-extrabold text-[#061830]">
                                        <?= htmlspecialchars($nomAgence, ENT_QUOTES, 'UTF-8') ?>
                                    </h3>

                                    <?php if (!empty($agence['badge_verifie'])): ?>
                                        <p class="mt-1 inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700">
                                            ✓ Agence vérifiée
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
                                <span class="flex min-w-0 items-center gap-1.5">
                                    <span class="shrink-0 text-[#B88700]"><?= icon('map-pin', 'w-3.5 h-3.5') ?></span>
                                    <span class="truncate"><?= htmlspecialchars($agence['adresse'] ?? 'Dakar, Sénégal', ENT_QUOTES, 'UTF-8') ?></span>
                                </span>

                                <span class="shrink-0 font-extrabold text-[#0B3B68]">
                                    <?= (int) ($agence['nombre_biens'] ?? 0) ?> biens
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="rounded-2xl border border-slate-200/70 bg-white p-10 text-center">
                    <p class="text-sm text-slate-500">Aucune agence partenaire disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>


<!-- =========================================================
     CTA FINAL
========================================================= -->
<section class="bg-white px-5 py-20 sm:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="relative overflow-hidden rounded-3xl bg-[#061830] px-7 py-14 text-center shadow-2xl sm:px-14 lg:py-16">

            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-[#F5C542]/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-sky-500/15 blur-3xl"></div>

            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#F5C542]">
                    Votre prochain projet commence ici
                </p>

                <h2 class="mx-auto mt-3 max-w-2xl font-['Playfair_Display'] text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">
                    Prêt à trouver votre prochain bien immobilier ?
                </h2>

                <p class="mx-auto mt-4 max-w-xl text-sm leading-6 text-slate-300">
                    Explorez les annonces, comparez les biens et trouvez l'opportunité qui correspond à vos objectifs.
                </p>

                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a
                        href="/biens"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-[#F5C542] px-8 py-4 text-sm font-extrabold text-[#061830] shadow-lg transition hover:bg-[#e5b32e]"
                    >
                        <?= icon('search', 'w-4 h-4') ?>
                        Commencer ma recherche
                    </a>

                    <a
                        href="/biens/carte"
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-white/30 bg-white/10 px-8 py-4 text-sm font-bold text-white backdrop-blur-sm transition hover:border-[#F5C542] hover:bg-white/20"
                    >
                        <?= icon('map', 'w-4 h-4') ?>
                        Explorer la carte
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>