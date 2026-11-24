
<?php

$biens      = is_array($biens ?? null) ? $biens : [];
$total      = (int) ($total ?? 0);
$pages      = max(1, (int) ($pages ?? 1));
$page       = max(1, (int) ($page ?? 1));
$filtres    = is_array($filtres ?? null) ? $filtres : [];
$tri        = $tri ?? 'recent';
$categories = is_array($categories ?? null) ? $categories : [];
$villes     = is_array($villes ?? null) ? $villes : [];

$e = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

/*
|--------------------------------------------------------------------------
| Valeurs des filtres
|--------------------------------------------------------------------------
*/

$q          = trim((string) ($filtres['q'] ?? ''));
$typeOffre  = (string) ($filtres['type_offre'] ?? '');
$categoryId = (string) ($filtres['category_id'] ?? '');
$villeId    = (string) ($filtres['ville_id'] ?? '');
$chambres   = (string) ($filtres['chambres'] ?? '');
$prixMin    = (string) ($filtres['prix_min'] ?? '');
$prixMax    = (string) ($filtres['prix_max'] ?? '');

/*
|--------------------------------------------------------------------------
| Vérification des filtres actifs
|--------------------------------------------------------------------------
*/

$filtreActif =
    $q !== ''
    || $typeOffre !== ''
    || $categoryId !== ''
    || $villeId !== ''
    || $chambres !== ''
    || $prixMin !== ''
    || $prixMax !== '';

/*
|--------------------------------------------------------------------------
| Villes sans doublons
|--------------------------------------------------------------------------
*/

$villesUniques = [];

foreach ($villes as $ville) {

    $nomVille = trim((string) (
        $ville['ville']
        ?? $ville['ville_nom']
        ?? $ville['nom']
        ?? ''
    ));

    if ($nomVille === '') {
        continue;
    }

    $cle = function_exists('mb_strtolower')
        ? mb_strtolower($nomVille)
        : strtolower($nomVille);

    if (!isset($villesUniques[$cle])) {
        $villesUniques[$cle] = $ville;
    }
}

$villesUniques = array_values($villesUniques);

/*
|--------------------------------------------------------------------------
| Paramètres de pagination
|--------------------------------------------------------------------------
*/

$paginationQuery = $filtres;

if ($tri !== '') {
    $paginationQuery['tri'] = $tri;
}

$imageParDefaut = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=85';

?>

<section class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

   
<!-- =====================================================
     HERO — PRÉSENTATION IMMO-SN
====================================================== -->

<section class="overflow-hidden rounded-b-2xl bg-[#071B36] text-white">

    <div class="mx-auto grid max-w-[1450px] items-center gap-6 px-5 py-6 sm:px-8 sm:py-7 lg:grid-cols-2 lg:gap-8 lg:px-10">

        <!-- TEXTE -->

        <div>

            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#F5B800]/30 bg-white/5 px-3 py-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#F5B800]">
                    Votre espace immobilier
                </span>
            </div>

            <h1 class="max-w-xl text-2xl font-black leading-tight tracking-tight sm:text-3xl lg:text-4xl">
                Trouvez le bien qui
                <span class="block text-[#F5B800]">vous ressemble.</span>
            </h1>

            <p class="mt-3 max-w-lg text-sm leading-6 text-slate-300">
                Découvrez des logements et des opportunités immobilières au Sénégal.
                Trouvez le bien adapté à vos besoins et à votre budget.
            </p>

            <div class="mt-4 flex flex-wrap gap-2">

                <span class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-[11px] font-semibold text-slate-200">
                    <span class="text-[#F5B800]">⌂</span>
                    Annonces immobilières
                </span>

                <span class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-[11px] font-semibold text-slate-200">
                    <span class="text-[#F5B800]">⌕</span>
                    Recherche personnalisée
                </span>

            </div>

            <a
                href="/immo-sn/scanner"
                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#F5B800] px-4 py-3 text-xs font-extrabold text-[#071B36] transition hover:bg-[#FFD04A]"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/>
                    <path d="M14 14h2v2h-2zM18 14h2M18 18v2M14 18h2"/>
                </svg>
                Scanner un QR code
            </a>

        </div>

        <!-- IMAGE -->

        <div class="relative mx-auto w-full max-w-md lg:max-w-none">

            <div class="overflow-hidden rounded-2xl border border-white/10">

                <img
                    src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=85"
                    alt="Villa moderne présentée sur IMMO-SN"
                    loading="lazy"
                    class="h-[190px] w-full object-cover sm:h-[220px] lg:h-[245px]"
                    onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1000&q=85';"
                >

                <div class="border-t border-white/10 bg-[#0B2748] px-4 py-3">
                    <p class="text-sm font-extrabold text-white">
                        Votre prochain chez-vous.
                    </p>
                    <p class="mt-1 text-[11px] text-slate-300">
                        Votre projet, notre priorité · Vente · Location
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>


    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->

    <div class="mx-auto max-w-[1450px] px-4 py-9 sm:px-6 lg:px-10 lg:py-12">

        <div class="grid grid-cols-1 items-start gap-7 lg:grid-cols-[300px_minmax(0,1fr)] xl:grid-cols-[320px_minmax(0,1fr)]">


            <!-- =================================================
                 COLONNE DES FILTRES
            ================================================== -->

            <aside>

                <div class="rounded-[24px] border border-[#101D33]/[0.07] bg-white p-5 shadow-[0_5px_20px_rgba(16,29,51,0.035)] lg:sticky lg:top-24 lg:p-6">

                    <div class="mb-6 flex items-center justify-between gap-3">

                        <div>

                            <div class="flex items-center gap-2">

                                <span class="h-1.5 w-5 rounded-full bg-[#D7A200]"></span>

                                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#987100]">
                                    Recherche
                                </p>

                            </div>

                            <h2 class="mt-2 text-lg font-extrabold tracking-tight text-[#101D33]">
                                Filtrer les biens
                            </h2>

                            <p class="mt-1 text-xs text-[#71869A]">
                                Affinez votre recherche.
                            </p>

                        </div>

                        <?php if ($filtreActif): ?>

                            <a
                                href="/immo-sn/biens"
                                class="shrink-0 rounded-lg px-2 py-2 text-[11px] font-bold text-[#987100] transition hover:bg-[#F5F3ED] hover:text-[#101D33]"
                            >
                                Effacer
                            </a>

                        <?php endif; ?>

                    </div>


                    <form method="GET" action="/immo-sn/biens" class="space-y-5">

                        <!-- RECHERCHE -->

                        <div>

                            <label for="q" class="mb-2 block text-xs font-bold text-[#101D33]">
                                Rechercher un bien
                            </label>

                            <div class="relative">

                                <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#8A99A8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path d="m20 20-4-4"/>
                                </svg>

                                <input
                                    type="text"
                                    id="q"
                                    name="q"
                                    value="<?= $e($q) ?>"
                                    placeholder="Maison, appartement..."
                                    class="w-full rounded-xl border border-[#E2E5E5] bg-[#FAFAF8] py-3.5 pl-10 pr-3 text-sm text-[#101D33] placeholder:text-[#9AA6B2] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#D7A200]/15"
                                >

                            </div>

                        </div>


                        <!-- TYPE D'OFFRE -->

                        <div>

                            <label for="type_offre" class="mb-2 block text-xs font-bold text-[#101D33]">
                                Type d'offre
                            </label>

                            <select
                                id="type_offre"
                                name="type_offre"
                                class="w-full rounded-xl border border-[#E2E5E5] bg-[#FAFAF8] px-3.5 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#D7A200]/15"
                            >

                                <option value="">Toutes les offres</option>

                                <option value="vente" <?= $typeOffre === 'vente' ? 'selected' : '' ?>>
                                    Vente
                                </option>

                                <option value="location" <?= $typeOffre === 'location' ? 'selected' : '' ?>>
                                    Location
                                </option>

                            </select>

                        </div>


                        <!-- CATÉGORIE -->

                        <div>

                            <label for="category_id" class="mb-2 block text-xs font-bold text-[#101D33]">
                                Type de bien
                            </label>

                            <select
                                id="category_id"
                                name="category_id"
                                class="w-full rounded-xl border border-[#E2E5E5] bg-[#FAFAF8] px-3.5 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#D7A200]/15"
                            >

                                <option value="">Toutes les catégories</option>

                                <?php foreach ($categories as $categorie): ?>

                                    <?php

                                    $categorieId = $categorie['id'] ?? '';

                                    $categorieNom =
                                        $categorie['libelle']
                                        ?? $categorie['nom']
                                        ?? 'Catégorie';

                                    ?>

                                    <option
                                        value="<?= $e($categorieId) ?>"
                                        <?= (string) $categoryId === (string) $categorieId ? 'selected' : '' ?>
                                    >
                                        <?= $e($categorieNom) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- VILLE -->

                        <div>

                            <label for="ville_id" class="mb-2 block text-xs font-bold text-[#101D33]">
                                Ville
                            </label>

                            <select
                                id="ville_id"
                                name="ville_id"
                                class="w-full rounded-xl border border-[#E2E5E5] bg-[#FAFAF8] px-3.5 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#D7A200]/15"
                            >

                                <option value="">Toutes les villes</option>

                                <?php foreach ($villesUniques as $ville): ?>

                                    <?php

                                    $idVille =
                                        $ville['id']
                                        ?? $ville['ville_id']
                                        ?? '';

                                    $nomVille =
                                        $ville['ville']
                                        ?? $ville['ville_nom']
                                        ?? $ville['nom']
                                        ?? '';

                                    ?>

                                    <option
                                        value="<?= $e($idVille) ?>"
                                        <?= (string) $villeId === (string) $idVille ? 'selected' : '' ?>
                                    >
                                        <?= $e($nomVille) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- CHAMBRES -->

                        <div>

                            <label for="chambres" class="mb-2 block text-xs font-bold text-[#101D33]">
                                Chambres minimum
                            </label>

                            <select
                                id="chambres"
                                name="chambres"
                                class="w-full rounded-xl border border-[#E2E5E5] bg-[#FAFAF8] px-3.5 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#D7A200]/15"
                            >

                                <option value="">Toutes</option>

                                <?php for ($i = 1; $i <= 6; $i++): ?>

                                    <option value="<?= $i ?>" <?= (string) $chambres === (string) $i ? 'selected' : '' ?>>
                                        <?= $i ?>+ chambre<?= $i > 1 ? 's' : '' ?>
                                    </option>

                                <?php endfor; ?>

                            </select>

                        </div>


                        <!-- BUDGET -->

                        <div>

                            <label class="mb-2 block text-xs font-bold text-[#101D33]">
                                Budget (FCFA)
                            </label>

                            <div class="grid grid-cols-2 gap-2">

                                <input
                                    type="number"
                                    name="prix_min"
                                    value="<?= $e($prixMin) ?>"
                                    min="0"
                                    placeholder="Minimum"
                                    aria-label="Prix minimum"
                                    class="min-w-0 w-full rounded-xl border border-[#E2E5E5] bg-[#FAFAF8] px-3 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#D7A200]/15"
                                >

                                <input
                                    type="number"
                                    name="prix_max"
                                    value="<?= $e($prixMax) ?>"
                                    min="0"
                                    placeholder="Maximum"
                                    aria-label="Prix maximum"
                                    class="min-w-0 w-full rounded-xl border border-[#E2E5E5] bg-[#FAFAF8] px-3 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#D7A200]/15"
                                >

                            </div>

                        </div>


                        <!-- BOUTON -->

                        <button
                            type="submit"
                            class="group flex w-full items-center justify-center gap-2.5 rounded-xl bg-[#101D33] px-4 py-3.5 text-sm font-extrabold text-white shadow-md shadow-[#101D33]/10 transition duration-300 hover:-translate-y-0.5 hover:bg-[#1B304E] focus:outline-none focus:ring-4 focus:ring-[#D7A200]/20"
                        >

                            <svg class="h-4 w-4 text-[#F5C84B] transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"/>
                                <path d="m20 20-4-4"/>
                            </svg>

                            Rechercher les biens

                        </button>

                    </form>


                    <!-- Petit rappel -->

                    <div class="mt-6 rounded-xl border border-[#D7A200]/20 bg-[#F5F3ED] p-3.5">

                        <div class="flex items-start gap-3">

                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#987100]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 11v5m0-8h.01" stroke-linecap="round"/>
                            </svg>

                            <p class="text-[11px] leading-5 text-[#667589]">
                                Utilisez les filtres pour trouver plus rapidement un bien correspondant à votre projet.
                            </p>

                        </div>

                    </div>

                </div>

            </aside>


            <!-- =================================================
                 COLONNE DES RÉSULTATS
            ================================================== -->

            <main class="min-w-0">


                <!-- BARRE DE RÉSULTATS -->

                <div class="mb-6 flex flex-col gap-4 rounded-[20px] border border-[#101D33]/[0.07] bg-white px-5 py-5 shadow-[0_5px_20px_rgba(16,29,51,0.035)] sm:px-6 md:flex-row md:items-center md:justify-between">

                    <div>

                        <div class="flex flex-wrap items-center gap-2">

                            <span class="h-1.5 w-5 rounded-full bg-[#D7A200]"></span>

                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#987100]">
                                Catalogue immobilier
                            </p>

                        </div>

                        <h2 class="mt-2 text-xl font-extrabold tracking-tight text-[#101D33]">

                            <?= number_format($total, 0, ',', ' ') ?>

                            bien<?= $total > 1 ? 's' : '' ?>

                            trouvé<?= $total > 1 ? 's' : '' ?>

                        </h2>

                        <?php if ($filtreActif): ?>

                            <p class="mt-1.5 text-xs text-[#71869A]">
                                Résultats correspondant à vos critères de recherche.
                            </p>

                        <?php else: ?>

                            <p class="mt-1.5 text-xs text-[#71869A]">
                                Découvrez les opportunités immobilières disponibles.
                            </p>

                        <?php endif; ?>

                    </div>


                    <!-- TRI -->

                    <form method="GET" action="/immo-sn/biens" class="flex flex-wrap items-center gap-2">

                        <?php foreach ($filtres as $nom => $valeur): ?>

                            <?php if ($valeur !== '' && $nom !== 'tri'): ?>

                                <input
                                    type="hidden"
                                    name="<?= $e($nom) ?>"
                                    value="<?= $e($valeur) ?>"
                                >

                            <?php endif; ?>

                        <?php endforeach; ?>

                        <label for="tri" class="whitespace-nowrap text-xs font-bold text-[#71869A]">
                            Trier par :
                        </label>

                        <select
                            id="tri"
                            name="tri"
                            onchange="this.form.submit()"
                            class="rounded-xl border border-[#E2E5E5] bg-[#FAFAF8] px-3.5 py-3 text-xs font-bold text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#D7A200]/15"
                        >

                            <option value="recent" <?= $tri === 'recent' ? 'selected' : '' ?>>
                                Plus récents
                            </option>

                            <option value="ancien" <?= $tri === 'ancien' ? 'selected' : '' ?>>
                                Plus anciens
                            </option>

                            <option value="prix_asc" <?= $tri === 'prix_asc' ? 'selected' : '' ?>>
                                Prix croissant
                            </option>

                            <option value="prix_desc" <?= $tri === 'prix_desc' ? 'selected' : '' ?>>
                                Prix décroissant
                            </option>

                            <option value="surface_desc" <?= $tri === 'surface_desc' ? 'selected' : '' ?>>
                                Surface décroissante
                            </option>

                        </select>

                    </form>

                </div>


                <!-- =================================================
                     ÉTAT VIDE
                ================================================== -->

                <?php if (empty($biens)): ?>

                    <div class="overflow-hidden rounded-[24px] border border-[#101D33]/[0.07] bg-white px-6 py-14 text-center shadow-sm sm:py-20">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[24px] border border-[#D7A200]/20 bg-[#D7A200]/10">

                            <svg class="h-9 w-9 text-[#A47A00]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path d="M3 21h18"/>
                                <path d="M5 21V9l7-5 7 5v12"/>
                                <path d="M9 21v-5h6v5"/>
                                <path d="M9 11h.01M15 11h.01"/>
                            </svg>

                        </div>

                        <h2 class="mt-6 text-xl font-extrabold text-[#101D33] sm:text-2xl">
                            Aucun bien trouvé
                        </h2>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-[#71869A]">
                            Aucun bien ne correspond actuellement à vos critères de recherche.
                            Essayez de modifier vos filtres pour découvrir d'autres opportunités.
                        </p>

                        <?php if ($filtreActif): ?>

                            <a
                                href="/immo-sn/biens"
                                class="mt-7 inline-flex items-center gap-2 rounded-xl bg-[#101D33] px-5 py-3.5 text-xs font-extrabold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#D7A200] hover:text-[#101D33]"
                            >

                                Réinitialiser les filtres

                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M4 12a8 8 0 1 0 2.34-5.66L4 8.5"/>
                                    <path d="M4 4v4.5h4.5"/>
                                </svg>

                            </a>

                        <?php endif; ?>

                    </div>

                <?php else: ?>


                    <!-- =================================================
                         GRILLE DES BIENS
                    ================================================== -->

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($biens as $bien): ?>

                            <?php

                            $id = $bien['id'] ?? '';

                            $titre = $bien['titre'] ?? 'Bien immobilier';

                            $categorie = $bien['categorie_nom'] ?? 'Immobilier';

                            $ville = $bien['ville_nom'] ?? '';

                            $quartier = $bien['quartier_nom'] ?? '';

                            $photo = $bien['photo'] ?? '';

                            $prix = (float) ($bien['prix'] ?? 0);

                            $type = strtolower(trim((string) ($bien['type_offre'] ?? '')));

                            $chambresBien = $bien['chambres'] ?? null;

                            $surface = $bien['superficie'] ?? $bien['surface'] ?? null;


                            /*
                            |--------------------------------------------------------------------------
                            | Localisation
                            |--------------------------------------------------------------------------
                            */

                            $localisation = trim((string) $ville);

                            if ($quartier !== '') {

                                if ($localisation !== '') {
                                    $localisation .= ', ';
                                }

                                $localisation .= $quartier;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Image
                            |--------------------------------------------------------------------------
                            */

                            if (empty($photo)) {

                                $photo = $imageParDefaut;

                            } elseif (
                                !str_starts_with($photo, 'http://')
                                && !str_starts_with($photo, 'https://')
                            ) {

                                $photo = '/' . ltrim($photo, '/');

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Libellé de l'offre
                            |--------------------------------------------------------------------------
                            */

                            $offreLabel =
                                $type === 'location'
                                    ? 'À louer'
                                    : (
                                        $type === 'vente'
                                            ? 'À vendre'
                                            : ucfirst($type ?: 'Disponible')
                                    );

                            ?>

                            <article class="group flex flex-col overflow-hidden rounded-[22px] border border-[#101D33]/[0.07] bg-white shadow-[0_5px_20px_rgba(16,29,51,0.035)] transition-all duration-300 hover:-translate-y-1 hover:border-[#D7A200]/35 hover:shadow-[0_16px_35px_rgba(16,29,51,0.10)]">


                                <!-- IMAGE -->

                                <div class="relative h-[215px] shrink-0 overflow-hidden bg-[#E9E7E0]">

                                    <img
                                        src="<?= $e($photo) ?>"
                                        alt="<?= $e($titre) ?>"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='<?= $e($imageParDefaut) ?>';"
                                    >

                                    <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-[#101D33]/45 to-transparent"></div>

                                    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-[#101D33]/25 to-transparent"></div>


                                    <!-- TYPE D'OFFRE -->

                                    <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-[#101D33]/95 px-3.5 py-2 text-[10px] font-extrabold uppercase tracking-wide text-white shadow-lg backdrop-blur-sm">

                                        <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>

                                        <?= $e($offreLabel) ?>

                                    </span>


                                    <!-- CATÉGORIE -->

                                    <?php if ($categorie): ?>

                                        <span class="absolute right-4 top-4 max-w-[48%] truncate rounded-full border border-white/60 bg-white/95 px-3 py-2 text-[10px] font-bold text-[#101D33] shadow-sm backdrop-blur-sm">
                                            <?= $e($categorie) ?>
                                        </span>

                                    <?php endif; ?>

                                </div>


                                <!-- CONTENU -->

                                <div class="flex flex-1 flex-col p-5">

                                    <h2 class="line-clamp-1 text-[16px] font-extrabold tracking-tight text-[#101D33] transition group-hover:text-[#987100]">
                                        <?= $e($titre) ?>
                                    </h2>


                                    <!-- LOCALISATION -->

                                    <?php if ($localisation !== ''): ?>

                                        <p class="mt-2.5 flex items-center gap-2 text-xs text-[#71869A]">

                                            <svg class="h-4 w-4 shrink-0 text-[#B58A12]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                                <circle cx="12" cy="10" r="2.5"/>
                                            </svg>

                                            <span class="line-clamp-1">
                                                <?= $e($localisation) ?>
                                            </span>

                                        </p>

                                    <?php endif; ?>


                                    <!-- CARACTÉRISTIQUES -->

                                    <div class="mt-5 flex min-h-[35px] flex-wrap items-center gap-x-4 gap-y-2 border-t border-[#101D33]/[0.07] pt-4">

                                        <?php if ($chambresBien !== null && $chambresBien !== ''): ?>

                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#71869A]">

                                                <svg class="h-4 w-4 text-[#B58A12]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path d="M3 7v10"/>
                                                    <path d="M21 7v10"/>
                                                    <path d="M3 12h18"/>
                                                    <path d="M5 12V9a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3"/>
                                                    <path d="M12 12V9a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3"/>
                                                </svg>

                                                <?= $e($chambresBien) ?>

                                                chambre<?= (int) $chambresBien > 1 ? 's' : '' ?>

                                            </span>

                                        <?php endif; ?>


                                        <?php if ($surface !== null && $surface !== ''): ?>

                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#71869A]">

                                                <svg class="h-4 w-4 text-[#B58A12]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path d="M4 4h6"/>
                                                    <path d="M4 4v6"/>
                                                    <path d="M20 20h-6"/>
                                                    <path d="M20 20v-6"/>
                                                    <path d="M4 20v-6"/>
                                                    <path d="M4 20h6"/>
                                                    <path d="M20 4h-6"/>
                                                    <path d="M20 4v6"/>
                                                </svg>

                                                <?= $e($surface) ?> m²

                                            </span>

                                        <?php endif; ?>

                                    </div>


                                    <!-- PRIX ET ACTION -->

                                    <div class="mt-auto flex items-end justify-between gap-3 border-t border-[#101D33]/[0.07] pt-5">

                                        <div class="min-w-0">

                                            <p class="text-[17px] font-extrabold tracking-tight text-[#987100]">

                                                <?= number_format($prix, 0, ',', ' ') ?>

                                                <span class="text-xs font-bold">FCFA</span>

                                            </p>

                                            <?php if ($type === 'location'): ?>

                                                <span class="mt-1 block text-[10px] font-semibold text-[#71869A]">
                                                    Par mois
                                                </span>

                                            <?php endif; ?>

                                        </div>


                                        <a
                                            href="/immo-sn/biens/<?= urlencode((string) $id) ?>"
                                            class="group/link inline-flex shrink-0 items-center gap-2 rounded-xl bg-[#101D33] px-4 py-3 text-xs font-extrabold text-white transition duration-300 hover:bg-[#F5B800] hover:text-[#101D33] focus:outline-none focus:ring-4 focus:ring-[#D7A200]/20"
                                        >

                                            Voir le bien

                                            <svg class="h-4 w-4 transition-transform duration-300 group-hover/link:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path d="M5 12h14"/>
                                                <path d="m13 6 6 6-6 6"/>
                                            </svg>

                                        </a>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>


                    <!-- =================================================
                         PAGINATION
                    ================================================== -->

                    <?php if ($pages > 1): ?>

                        <div class="mt-9 flex flex-wrap items-center justify-center gap-2">

                            <!-- PRÉCÉDENT -->

                            <?php if ($page > 1): ?>

                                <?php

                                $queryPrec = $paginationQuery;
                                $queryPrec['page'] = $page - 1;

                                ?>

                                <a
                                    href="/immo-sn/biens?<?= $e(http_build_query($queryPrec)) ?>"
                                    class="inline-flex items-center gap-2 rounded-xl border border-[#101D33]/10 bg-white px-4 py-3 text-xs font-bold text-[#101D33] shadow-sm transition hover:border-[#D7A200] hover:bg-[#F5F3ED]"
                                >

                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="m15 18-6-6 6-6"/>
                                    </svg>

                                    Précédent

                                </a>

                            <?php endif; ?>


                            <!-- NUMÉROS -->

                            <?php

                            $debut = max(1, $page - 2);
                            $fin = min($pages, $page + 2);

                            ?>

                            <?php for ($i = $debut; $i <= $fin; $i++): ?>

                                <?php

                                $queryPage = $paginationQuery;
                                $queryPage['page'] = $i;

                                ?>

                                <a
                                    href="/immo-sn/biens?<?= $e(http_build_query($queryPage)) ?>"
                                    aria-current="<?= $i === $page ? 'page' : 'false' ?>"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl text-xs font-extrabold transition <?= $i === $page
                                        ? 'bg-[#101D33] text-[#F5C84B] shadow-md shadow-[#101D33]/15'
                                        : 'border border-[#101D33]/10 bg-white text-[#101D33] shadow-sm hover:border-[#D7A200] hover:bg-[#F5F3ED]'
                                    ?>"
                                >
                                    <?= $i ?>
                                </a>

                            <?php endfor; ?>


                            <!-- SUIVANT -->

                            <?php if ($page < $pages): ?>

                                <?php

                                $querySuiv = $paginationQuery;
                                $querySuiv['page'] = $page + 1;

                                ?>

                                <a
                                    href="/immo-sn/biens?<?= $e(http_build_query($querySuiv)) ?>"
                                    class="inline-flex items-center gap-2 rounded-xl border border-[#101D33]/10 bg-white px-4 py-3 text-xs font-bold text-[#101D33] shadow-sm transition hover:border-[#D7A200] hover:bg-[#F5F3ED]"
                                >

                                    Suivant

                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6"/>
                                    </svg>

                                </a>

                            <?php endif; ?>

                        </div>

                        <p class="mt-4 text-center text-[11px] font-medium text-[#71869A]">
                            Page <?= $page ?> sur <?= $pages ?>
                        </p>

                    <?php endif; ?>

                <?php endif; ?>

            </main>

        </div>

    </div>

</section>
