
<?php

$biens      = $biens ?? [];
$total      = (int) ($total ?? 0);
$pages      = (int) ($pages ?? 1);
$page       = (int) ($page ?? 1);
$filtres    = $filtres ?? [];
$tri        = $tri ?? 'recent';
$categories = $categories ?? [];
$villes     = $villes ?? [];

/*
|--------------------------------------------------------------------------
| Valeurs des filtres
|--------------------------------------------------------------------------
*/

$q = trim((string) ($filtres['q'] ?? ''));

$typeOffre = (string) ($filtres['type_offre'] ?? '');
$categoryId = (string) ($filtres['category_id'] ?? '');
$villeId = (string) ($filtres['ville_id'] ?? '');
$chambres = (string) ($filtres['chambres'] ?? '');
$prixMin = (string) ($filtres['prix_min'] ?? '');
$prixMax = (string) ($filtres['prix_max'] ?? '');

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

    $nomVille = trim(
        (string) (
            $ville['ville']
            ?? $ville['ville_nom']
            ?? $ville['nom']
            ?? ''
        )
    );

    if ($nomVille === '') {
        continue;
    }

    $cle = mb_strtolower($nomVille);

    if (!isset($villesUniques[$cle])) {
        $villesUniques[$cle] = $ville;
    }
}

$villesUniques = array_values($villesUniques);

/*
|--------------------------------------------------------------------------
| URLs de pagination
|--------------------------------------------------------------------------
*/

$paginationQuery = $filtres;

if ($tri !== '') {
    $paginationQuery['tri'] = $tri;
}

?>

<section class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

    <!-- =====================================================
         EN-TÊTE PREMIUM IMMO-SN
    ====================================================== -->

    <section class="relative isolate overflow-hidden rounded-[28px] bg-[#101D33] text-white shadow-[0_16px_45px_rgba(16,29,51,0.15)] sm:rounded-[32px]">

        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[#7E9833]/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-0 h-48 w-48 rounded-full bg-[#71869A]/20 blur-3xl"></div>

        <div class="relative grid items-center gap-6 p-5 sm:p-7 lg:grid-cols-2 lg:gap-8 lg:p-9">

            <!-- Partie gauche -->

            <div class="relative z-10">

                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#7E9833]/40 bg-white/[0.04] px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-[#C5D59A]">

                    <span class="h-1.5 w-1.5 rounded-full bg-[#8FAF35]"></span>

                    Votre projet immobilier

                </div>

                <h1 class="max-w-xl text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl lg:text-4xl">

                    Trouvez le bien qui

                    <span class="block text-[#A8C46A]">vous ressemble.</span>

                </h1>

                <p class="mt-3 max-w-lg text-xs leading-6 text-white/65 sm:text-sm">

                    Découvrez des logements et des opportunités immobilières au Sénégal.
                    Trouvez le bien adapté à vos besoins et à votre budget.

                </p>

                <!-- Informations et bouton QR -->

                <div class="mt-5 flex flex-wrap items-center gap-2">

                    <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-3 py-2.5">

                        <svg class="h-4 w-4 shrink-0 text-[#A8C46A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span class="text-[11px] font-medium text-white/85">Annonces immobilières</span>

                    </div>

                    <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-3 py-2.5">

                        <svg class="h-4 w-4 shrink-0 text-[#A8C46A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m16 16 4 4" stroke-linecap="round"/>
                        </svg>

                        <span class="text-[11px] font-medium text-white/85">Recherche personnalisée</span>

                    </div>

                    <a
                        href="/immo-sn/scanner"
                        class="group inline-flex items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-3.5 py-2.5 text-[11px] font-extrabold text-white shadow-md shadow-black/10 transition duration-300 hover:-translate-y-0.5 hover:bg-[#8FAF35] focus:outline-none focus:ring-4 focus:ring-[#7E9833]/30"
                    >

                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/>
                            <path d="M14 14h2v2h-2zM18 14h2M18 18v2M14 18h2"/>
                        </svg>

                        <span>Scanner un QR code</span>

                        <svg class="h-4 w-4 shrink-0 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                    </a>

                </div>

            </div>


            <!-- Partie droite : image immobilière -->

            <div class="relative mx-auto w-full max-w-xl lg:max-w-none">

                <div class="pointer-events-none absolute inset-5 rounded-[28px] bg-[#7E9833]/20 blur-2xl"></div>

                <div class="relative overflow-hidden rounded-[24px] border border-[#A8C46A]/25 bg-[#263751] shadow-[0_15px_35px_rgba(0,0,0,0.25)] sm:rounded-[30px]">

                    <img
                        src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=85"
                        alt="Villa moderne présentée sur IMMO-SN"
                        loading="lazy"
                        class="h-[210px] w-full object-cover transition duration-700 hover:scale-105 sm:h-[260px] lg:h-[300px]"
                        onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1000&q=85';"
                    >

                    <div class="absolute inset-0 bg-gradient-to-t from-[#101D33]/85 via-transparent to-[#101D33]/5"></div>

                    <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-5">

                        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-black/20 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-white backdrop-blur-md">

                            <span class="h-1.5 w-1.5 rounded-full bg-[#A8C46A]"></span>

                            L'immobilier autrement

                        </span>

                        <h2 class="mt-2 text-lg font-bold text-white sm:text-xl">
                            Votre prochain chez-vous.
                        </h2>

                        <p class="mt-1 text-xs text-white/70">
                            Explorez les possibilités avec IMMO-SN.
                        </p>

                    </div>

                </div>


                <!-- Bandeau sous l'image -->

                <div class="mt-3 flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#7E9833]/20 text-[#A8C46A]">

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path d="M3 10.5 12 3l9 7.5M5 9v11h14V9M9 20v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-bold text-white">
                            Votre projet, notre priorité
                        </p>

                        <p class="mt-0.5 text-[11px] text-white/60">
                            Vente · Location · Immobilier
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->

    <div class="mx-auto max-w-[1450px] px-4 py-8 sm:px-6 lg:px-10">

        <div class="grid grid-cols-1 gap-7 lg:grid-cols-[310px_1fr]">


            <!-- =================================================
                 FILTRES
            ================================================== -->

            <aside>

                <div class="rounded-[22px] border border-[#101D33]/[0.07] bg-white p-5 shadow-sm lg:sticky lg:top-24">

                    <div class="mb-6 flex items-center justify-between gap-3">

                        <div>

                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#7E9833]">
                                Recherche
                            </p>

                            <h2 class="mt-1 text-lg font-extrabold text-[#101D33]">
                                Filtrer les biens
                            </h2>

                        </div>

                        <?php if ($filtreActif): ?>

                            <a
                                href="/immo-sn/biens"
                                class="text-[11px] font-bold text-[#7E9833] hover:text-[#101D33]"
                            >
                                Réinitialiser
                            </a>

                        <?php endif; ?>

                    </div>


                    <form method="GET" action="/immo-sn/biens" class="space-y-5">

                        <!-- RECHERCHE -->

                        <div>

                            <label for="q" class="mb-2 block text-xs font-bold text-[#101D33]">
                                Rechercher
                            </label>

                            <div class="relative">

                                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#71869A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path d="m20 20-4-4"/>
                                </svg>

                                <input
                                    type="text"
                                    id="q"
                                    name="q"
                                    value="<?= htmlspecialchars($q) ?>"
                                    placeholder="Maison, appartement..."
                                    class="w-full rounded-xl border border-[#DCE1E3] bg-[#F9FAF8] py-3 pl-10 pr-3 text-sm text-[#101D33] placeholder:text-[#71869A]/70 outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
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
                                class="w-full rounded-xl border border-[#DCE1E3] bg-[#F9FAF8] px-3 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
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
                                class="w-full rounded-xl border border-[#DCE1E3] bg-[#F9FAF8] px-3 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
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
                                        value="<?= htmlspecialchars((string) $categorieId) ?>"
                                        <?= (string) $categoryId === (string) $categorieId ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($categorieNom) ?>
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
                                class="w-full rounded-xl border border-[#DCE1E3] bg-[#F9FAF8] px-3 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
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
                                        value="<?= htmlspecialchars((string) $idVille) ?>"
                                        <?= (string) $villeId === (string) $idVille ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($nomVille) ?>
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
                                class="w-full rounded-xl border border-[#DCE1E3] bg-[#F9FAF8] px-3 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
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
                                Budget
                            </label>

                            <div class="grid grid-cols-2 gap-2">

                                <input
                                    type="number"
                                    name="prix_min"
                                    value="<?= htmlspecialchars($prixMin) ?>"
                                    min="0"
                                    placeholder="Min"
                                    class="w-full rounded-xl border border-[#DCE1E3] bg-[#F9FAF8] px-3 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                                >

                                <input
                                    type="number"
                                    name="prix_max"
                                    value="<?= htmlspecialchars($prixMax) ?>"
                                    min="0"
                                    placeholder="Max"
                                    class="w-full rounded-xl border border-[#DCE1E3] bg-[#F9FAF8] px-3 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                                >

                            </div>

                        </div>


                        <!-- BOUTON RECHERCHE -->

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-[#101D33] px-4 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1B304E] focus:outline-none focus:ring-4 focus:ring-[#101D33]/15"
                        >
                            Rechercher les biens
                        </button>

                    </form>

                </div>

            </aside>


            <!-- =================================================
                 RÉSULTATS
            ================================================== -->

            <main class="min-w-0">


                <!-- BARRE DE RÉSULTATS -->

                <div class="mb-5 flex flex-col gap-4 rounded-[18px] border border-[#101D33]/[0.07] bg-white px-5 py-4 shadow-sm md:flex-row md:items-center md:justify-between">

                    <div>

                        <p class="text-sm font-bold text-[#101D33]">

                            <?= number_format($total, 0, ',', ' ') ?>

                            bien<?= $total > 1 ? 's' : '' ?>

                            trouvé<?= $total > 1 ? 's' : '' ?>

                        </p>

                        <?php if ($filtreActif): ?>

                            <p class="mt-1 text-xs text-[#71869A]">
                                Résultats correspondant à vos critères
                            </p>

                        <?php endif; ?>

                    </div>


                    <!-- TRI -->

                    <form method="GET" action="/immo-sn/biens" class="flex items-center gap-2">

                        <?php foreach ($filtres as $nom => $valeur): ?>

                            <?php if ($valeur !== '' && $nom !== 'tri'): ?>

                                <input
                                    type="hidden"
                                    name="<?= htmlspecialchars($nom) ?>"
                                    value="<?= htmlspecialchars((string) $valeur) ?>"
                                >

                            <?php endif; ?>

                        <?php endforeach; ?>

                        <label for="tri" class="whitespace-nowrap text-xs font-semibold text-[#71869A]">
                            Trier :
                        </label>

                        <select
                            id="tri"
                            name="tri"
                            onchange="this.form.submit()"
                            class="rounded-xl border border-[#DCE1E3] bg-[#F9FAF8] px-3 py-2.5 text-xs font-semibold text-[#101D33] outline-none focus:border-[#7E9833]"
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
                     LISTE DES BIENS
                ================================================== -->

                <?php if (empty($biens)): ?>

                    <div class="rounded-[22px] border border-[#101D33]/[0.07] bg-white p-10 text-center shadow-sm md:p-16">

                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#7E9833]/10">

                            <svg class="h-8 w-8 text-[#7E9833]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path d="M3 21h18"/>
                                <path d="M5 21V9l7-5 7 5v12"/>
                                <path d="M9 21v-5h6v5"/>
                            </svg>

                        </div>

                        <h2 class="text-xl font-extrabold text-[#101D33]">
                            Aucun bien trouvé
                        </h2>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#71869A]">
                            Aucun bien ne correspond actuellement à vos critères de recherche.
                        </p>

                        <?php if ($filtreActif): ?>

                            <a
                                href="/immo-sn/biens"
                                class="mt-6 inline-flex rounded-xl bg-[#101D33] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#7E9833]"
                            >
                                Réinitialiser les filtres
                            </a>

                        <?php endif; ?>

                    </div>

                <?php else: ?>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($biens as $bien): ?>

                            <?php

                            $id = $bien['id'] ?? '';

                            $titre =
                                $bien['titre']
                                ?? 'Bien immobilier';

                            $categorie =
                                $bien['categorie_nom']
                                ?? 'Immobilier';

                            $ville =
                                $bien['ville_nom']
                                ?? '';

                            $quartier =
                                $bien['quartier_nom']
                                ?? '';

                            $photo =
                                $bien['photo']
                                ?? '';

                            $prix =
                                (float) ($bien['prix'] ?? 0);

                            $type =
                                strtolower(
                                    trim(
                                        (string) (
                                            $bien['type_offre']
                                            ?? ''
                                        )
                                    )
                                );

                            $chambresBien =
                                $bien['chambres']
                                ?? null;

                            $surface =
                                $bien['superficie']
                                ?? $bien['surface']
                                ?? null;


                            /*
                            |--------------------------------------------------------------------------
                            | Localisation
                            |--------------------------------------------------------------------------
                            */

                            $localisation = $ville;

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

                            $imageParDefaut =
                                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=85';

                            if (!$photo) {

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


                            <article class="group overflow-hidden rounded-[20px] border border-[#101D33]/[0.07] bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">


                                <!-- IMAGE -->

                                <div class="relative h-[215px] overflow-hidden">

                                    <img
                                        src="<?= htmlspecialchars($photo) ?>"
                                        alt="<?= htmlspecialchars($titre) ?>"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='<?= htmlspecialchars($imageParDefaut, ENT_QUOTES) ?>';"
                                    >

                                    <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-[#101D33]/40 to-transparent"></div>


                                    <!-- TYPE D'OFFRE -->

                                    <span class="absolute left-4 top-4 rounded-full bg-[#101D33] px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide text-white shadow">
                                        <?= htmlspecialchars($offreLabel) ?>
                                    </span>


                                    <!-- CATÉGORIE -->

                                    <?php if ($categorie): ?>

                                        <span class="absolute right-4 top-4 rounded-full border border-white/70 bg-white/95 px-3 py-1.5 text-[10px] font-bold text-[#101D33] shadow-sm">
                                            <?= htmlspecialchars($categorie) ?>
                                        </span>

                                    <?php endif; ?>

                                </div>


                                <!-- CONTENU -->

                                <div class="p-5">

                                    <h2 class="line-clamp-1 text-[17px] font-extrabold text-[#101D33]">
                                        <?= htmlspecialchars($titre) ?>
                                    </h2>


                                    <!-- LOCALISATION -->

                                    <?php if ($localisation): ?>

                                        <p class="mt-2 flex items-center gap-1.5 text-xs text-[#71869A]">

                                            <svg class="h-4 w-4 shrink-0 text-[#7E9833]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                                <circle cx="12" cy="10" r="2.5"/>
                                            </svg>

                                            <?= htmlspecialchars($localisation) ?>

                                        </p>

                                    <?php endif; ?>


                                    <!-- CARACTÉRISTIQUES -->

                                    <div class="mt-5 flex flex-wrap items-center gap-4 border-t border-[#101D33]/[0.07] pt-4">

                                        <?php if (
                                            $chambresBien !== null
                                            && $chambresBien !== ''
                                        ): ?>

                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#71869A]">

                                                <svg class="h-4 w-4 text-[#7E9833]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path d="M3 7v10"/>
                                                    <path d="M21 7v10"/>
                                                    <path d="M3 12h18"/>
                                                    <path d="M5 12V9a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3"/>
                                                    <path d="M12 12V9a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3"/>
                                                </svg>

                                                <?= htmlspecialchars((string) $chambresBien) ?>

                                                chambre<?= (int) $chambresBien > 1 ? 's' : '' ?>

                                            </span>

                                        <?php endif; ?>


                                        <?php if (
                                            $surface !== null
                                            && $surface !== ''
                                        ): ?>

                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#71869A]">

                                                <svg class="h-4 w-4 text-[#7E9833]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path d="M4 4h6"/>
                                                    <path d="M4 4v6"/>
                                                    <path d="M20 20h-6"/>
                                                    <path d="M20 20v-6"/>
                                                    <path d="M4 20v-6"/>
                                                    <path d="M4 20h6"/>
                                                    <path d="M20 4h-6"/>
                                                    <path d="M20 4v6"/>
                                                </svg>

                                                <?= htmlspecialchars((string) $surface) ?> m²

                                            </span>

                                        <?php endif; ?>

                                    </div>


                                    <!-- PRIX ET ACTION -->

                                    <div class="mt-5 flex items-end justify-between gap-3">

                                        <div>

                                            <p class="text-[18px] font-extrabold text-[#7E9833]">

                                                <?= number_format($prix, 0, ',', ' ') ?>

                                                FCFA

                                            </p>

                                            <?php if ($type === 'location'): ?>

                                                <span class="text-[10px] font-semibold text-[#71869A]">
                                                    par mois
                                                </span>

                                            <?php endif; ?>

                                        </div>


                                        <a
                                            href="/immo-sn/biens/<?= urlencode((string) $id) ?>"
                                            class="inline-flex items-center gap-2 rounded-xl bg-[#101D33] px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-[#7E9833]"
                                        >

                                            Voir

                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
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

                        <div class="mt-8 flex flex-wrap items-center justify-center gap-2">

                            <?php if ($page > 1): ?>

                                <?php

                                $queryPrec = $paginationQuery;
                                $queryPrec['page'] = $page - 1;

                                ?>

                                <a
                                    href="/immo-sn/biens?<?= htmlspecialchars(http_build_query($queryPrec)) ?>"
                                    class="inline-flex items-center gap-2 rounded-xl border border-[#101D33]/10 bg-white px-4 py-2.5 text-xs font-bold text-[#101D33] transition hover:border-[#7E9833] hover:bg-[#F5F3ED]"
                                >
                                    ← Précédent
                                </a>

                            <?php endif; ?>


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
                                    href="/immo-sn/biens?<?= htmlspecialchars(http_build_query($queryPage)) ?>"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-xs font-extrabold transition <?= $i === $page
                                        ? 'bg-[#101D33] text-white shadow-md'
                                        : 'border border-[#101D33]/10 bg-white text-[#101D33] hover:border-[#7E9833] hover:bg-[#F5F3ED]'
                                    ?>"
                                >
                                    <?= $i ?>
                                </a>

                            <?php endfor; ?>


                            <?php if ($page < $pages): ?>

                                <?php

                                $querySuiv = $paginationQuery;
                                $querySuiv['page'] = $page + 1;

                                ?>

                                <a
                                    href="/immo-sn/biens?<?= htmlspecialchars(http_build_query($querySuiv)) ?>"
                                    class="inline-flex items-center gap-2 rounded-xl border border-[#101D33]/10 bg-white px-4 py-2.5 text-xs font-bold text-[#101D33] transition hover:border-[#7E9833] hover:bg-[#F5F3ED]"
                                >
                                    Suivant →
                                </a>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                <?php endif; ?>

            </main>

        </div>

    </div>

</section>
