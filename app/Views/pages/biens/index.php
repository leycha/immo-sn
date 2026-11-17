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

$typeOffre = (string) (
    $filtres['type_offre'] ?? ''
);

$categoryId = (string) (
    $filtres['category_id'] ?? ''
);

$villeId = (string) (
    $filtres['ville_id'] ?? ''
);

$chambres = (string) (
    $filtres['chambres'] ?? ''
);

$prixMin = (string) (
    $filtres['prix_min'] ?? ''
);

$prixMax = (string) (
    $filtres['prix_max'] ?? ''
);


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
| Fonction pour construire les URLs de pagination
|--------------------------------------------------------------------------
*/

$paginationQuery = $filtres;

if ($tri !== '') {
    $paginationQuery['tri'] = $tri;
}

?>
<section class="min-h-screen bg-[#f7f4ee] text-[#30221b]">

<!-- En-tête premium IMMO-SN : version compacte -->
<section class="relative isolate overflow-hidden rounded-[28px] bg-[#241811] text-white shadow-[0_16px_45px_rgba(48,34,27,0.16)] sm:rounded-[32px]">

    <!-- Lumières décoratives -->
    <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[#f7b500]/15 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 left-0 h-48 w-48 rounded-full bg-[#a98260]/15 blur-3xl"></div>

    <div class="relative grid items-center gap-6 p-5 sm:p-7 lg:grid-cols-2 lg:gap-8 lg:p-9">

        <!-- Partie gauche -->
        <div class="relative z-10">

            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#f7b500]/25 bg-white/[0.04] px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-[#e8c99f]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#f7b500]"></span>
                Votre projet immobilier
            </div>

            <h1 class="max-w-xl text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl lg:text-4xl">
                Trouvez le bien qui
                <span class="block text-[#e8bd78]">vous ressemble.</span>
            </h1>

            <p class="mt-3 max-w-lg text-xs leading-6 text-white/65 sm:text-sm">
                Découvrez des logements et des opportunités immobilières au Sénégal.
                Trouvez le bien adapté à vos besoins et à votre budget.
            </p>

            <!-- Les trois éléments sur la même ligne -->
            <div class="mt-5 flex flex-wrap items-center gap-2">

                <!-- Annonces -->
                <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-3 py-2.5">
                    <svg class="h-4 w-4 shrink-0 text-[#f7b500]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-[11px] font-medium text-white/85">Annonces immobilières</span>
                </div>

                <!-- Recherche -->
                <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-3 py-2.5">
                    <svg class="h-4 w-4 shrink-0 text-[#f7b500]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m16 16 4 4" stroke-linecap="round"/>
                    </svg>
                    <span class="text-[11px] font-medium text-white/85">Recherche personnalisée</span>
                </div>

                <!-- Bouton QR code intégré à la ligne -->
                <a
                    href="/immo-sn/scanner"
                    class="group inline-flex items-center justify-center gap-2 rounded-xl bg-[#f7b500] px-3.5 py-2.5 text-[11px] font-extrabold text-[#30221b] shadow-md shadow-black/10 transition duration-300 hover:-translate-y-0.5 hover:bg-[#ffd04a] focus:outline-none focus:ring-4 focus:ring-[#f7b500]/30"
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

            <div class="pointer-events-none absolute inset-5 rounded-[28px] bg-[#f7b500]/15 blur-2xl"></div>

            <div class="relative overflow-hidden rounded-[24px] border border-[#e8bd78]/25 bg-[#49372c] shadow-[0_15px_35px_rgba(0,0,0,0.25)] sm:rounded-[30px]">

                <img
                    src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=85"
                    alt="Villa moderne présentée sur IMMO-SN"
                    loading="lazy"
                    class="h-[210px] w-full object-cover transition duration-700 hover:scale-105 sm:h-[260px] lg:h-[300px]"
                    onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1000&q=85';"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-[#241811]/80 via-transparent to-[#241811]/5"></div>

                <!-- Légende -->
                <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-5">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-black/20 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-white backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#f7b500]"></span>
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

            <!-- Petit bandeau sous l'image -->
            <div class="mt-3 flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#f7b500]/10 text-[#f7b500]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M3 10.5 12 3l9 7.5M5 9v11h14V9M9 20v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-bold text-white">Votre projet, notre priorité</p>
                    <p class="mt-0.5 text-[11px] text-white/60">Vente · Location · Immobilier</p>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ========================================================
     CONTENU
========================================================= -->

<div class="max-w-[1450px] mx-auto px-6 lg:px-10 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-[310px_1fr] gap-7">


        <!-- =================================================
             FILTRES
        ================================================== -->

        <aside>

            <div
                class="
                    bg-white
                    rounded-[22px]
                    border
                    border-[#eadfd5]
                    shadow-sm
                    p-5
                    lg:sticky
                    lg:top-24
                "
            >

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-3
                        mb-6
                    "
                >

                    <div>

                        <p
                            class="
                                text-[10px]
                                uppercase
                                tracking-[0.18em]
                                font-extrabold
                                text-[#a98260]
                            "
                        >
                            Recherche
                        </p>

                        <h2
                            class="
                                text-lg
                                font-extrabold
                                text-[#30221b]
                                mt-1
                            "
                        >
                            Filtrer les biens
                        </h2>

                    </div>


                    <?php if ($filtreActif): ?>

                        <a
                            href="/immo-sn/biens"
                            class="
                                text-[11px]
                                font-bold
                                text-[#a98260]
                                hover:text-[#30221b]
                            "
                        >
                            Réinitialiser
                        </a>

                    <?php endif; ?>

                </div>


                <form
                    method="GET"
                    action="/immo-sn/biens"
                    class="space-y-5"
                >

                    <!-- RECHERCHE -->

                    <div>

                        <label
                            for="q"
                            class="
                                block
                                text-xs
                                font-bold
                                text-[#5a402d]
                                mb-2
                            "
                        >
                            Rechercher
                        </label>

                        <div class="relative">

                            <svg
                                class="
                                    absolute
                                    left-3
                                    top-1/2
                                    -translate-y-1/2
                                    w-4
                                    h-4
                                    text-[#a98260]
                                "
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle
                                    cx="11"
                                    cy="11"
                                    r="7"
                                />

                                <path d="m20 20-4-4" />
                            </svg>


                            <input
                                type="text"
                                id="q"
                                name="q"
                                value="<?= htmlspecialchars($q) ?>"
                                placeholder="Maison, appartement..."
                                class="
                                    w-full
                                    rounded-xl
                                    border
                                    border-[#e6dcd1]
                                    bg-[#fffdf9]
                                    pl-10
                                    pr-3
                                    py-3
                                    text-sm
                                    text-[#30221b]
                                    placeholder:text-[#a99b91]
                                    focus:border-[#dca94b]
                                    focus:ring-2
                                    focus:ring-[#f7b500]/20
                                    outline-none
                                "
                            >

                        </div>

                    </div>


                    <!-- TYPE D'OFFRE -->

                    <div>

                        <label
                            for="type_offre"
                            class="
                                block
                                text-xs
                                font-bold
                                text-[#5a402d]
                                mb-2
                            "
                        >
                            Type d'offre
                        </label>

                        <select
                            id="type_offre"
                            name="type_offre"
                            class="
                                w-full
                                rounded-xl
                                border
                                border-[#e6dcd1]
                                bg-[#fffdf9]
                                px-3
                                py-3
                                text-sm
                                text-[#30221b]
                                outline-none
                                focus:border-[#dca94b]
                                focus:ring-2
                                focus:ring-[#f7b500]/20
                            "
                        >

                            <option value="">
                                Toutes les offres
                            </option>

                            <option
                                value="vente"
                                <?= $typeOffre === 'vente' ? 'selected' : '' ?>
                            >
                                Vente
                            </option>

                            <option
                                value="location"
                                <?= $typeOffre === 'location' ? 'selected' : '' ?>
                            >
                                Location
                            </option>

                        </select>

                    </div>


                    <!-- CATÉGORIE -->

                    <div>

                        <label
                            for="category_id"
                            class="
                                block
                                text-xs
                                font-bold
                                text-[#5a402d]
                                mb-2
                            "
                        >
                            Type de bien
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="
                                w-full
                                rounded-xl
                                border
                                border-[#e6dcd1]
                                bg-[#fffdf9]
                                px-3
                                py-3
                                text-sm
                                text-[#30221b]
                                outline-none
                                focus:border-[#dca94b]
                                focus:ring-2
                                focus:ring-[#f7b500]/20
                            "
                        >

                            <option value="">
                                Toutes les catégories
                            </option>

                            <?php foreach ($categories as $categorie): ?>

                                <?php

                                $categorieId =
                                    $categorie['id'] ?? '';

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

                        <label
                            for="ville_id"
                            class="
                                block
                                text-xs
                                font-bold
                                text-[#5a402d]
                                mb-2
                            "
                        >
                            Ville
                        </label>

                        <select
                            id="ville_id"
                            name="ville_id"
                            class="
                                w-full
                                rounded-xl
                                border
                                border-[#e6dcd1]
                                bg-[#fffdf9]
                                px-3
                                py-3
                                text-sm
                                text-[#30221b]
                                outline-none
                                focus:border-[#dca94b]
                                focus:ring-2
                                focus:ring-[#f7b500]/20
                            "
                        >

                            <option value="">
                                Toutes les villes
                            </option>

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

                        <label
                            for="chambres"
                            class="
                                block
                                text-xs
                                font-bold
                                text-[#5a402d]
                                mb-2
                            "
                        >
                            Chambres minimum
                        </label>

                        <select
                            id="chambres"
                            name="chambres"
                            class="
                                w-full
                                rounded-xl
                                border
                                border-[#e6dcd1]
                                bg-[#fffdf9]
                                px-3
                                py-3
                                text-sm
                                text-[#30221b]
                                outline-none
                                focus:border-[#dca94b]
                                focus:ring-2
                                focus:ring-[#f7b500]/20
                            "
                        >

                            <option value="">
                                Toutes
                            </option>

                            <?php for ($i = 1; $i <= 6; $i++): ?>

                                <option
                                    value="<?= $i ?>"
                                    <?= (string) $chambres === (string) $i ? 'selected' : '' ?>
                                >
                                    <?= $i ?>+
                                    chambre<?= $i > 1 ? 's' : '' ?>
                                </option>

                            <?php endfor; ?>

                        </select>

                    </div>


                    <!-- PRIX -->

                    <div>

                        <label
                            class="
                                block
                                text-xs
                                font-bold
                                text-[#5a402d]
                                mb-2
                            "
                        >
                            Budget
                        </label>

                        <div class="grid grid-cols-2 gap-2">

                            <input
                                type="number"
                                name="prix_min"
                                value="<?= htmlspecialchars($prixMin) ?>"
                                min="0"
                                placeholder="Min"
                                class="
                                    w-full
                                    rounded-xl
                                    border
                                    border-[#e6dcd1]
                                    bg-[#fffdf9]
                                    px-3
                                    py-3
                                    text-sm
                                    text-[#30221b]
                                    outline-none
                                    focus:border-[#dca94b]
                                "
                            >

                            <input
                                type="number"
                                name="prix_max"
                                value="<?= htmlspecialchars($prixMax) ?>"
                                min="0"
                                placeholder="Max"
                                class="
                                    w-full
                                    rounded-xl
                                    border
                                    border-[#e6dcd1]
                                    bg-[#fffdf9]
                                    px-3
                                    py-3
                                    text-sm
                                    text-[#30221b]
                                    outline-none
                                    focus:border-[#dca94b]
                                "
                            >

                        </div>

                    </div>


                    <!-- BOUTON -->

                    <button
                        type="submit"
                        class="
                            w-full
                            rounded-xl
                            bg-[#30221b]
                            px-4
                            py-3
                            text-sm
                            font-extrabold
                            text-white
                            hover:bg-[#5a402d]
                            hover:-translate-y-0.5
                            shadow-sm
                            transition
                        "
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


            <!-- BARRE RÉSULTATS -->

            <div
                class="
                    bg-white
                    rounded-[18px]
                    border
                    border-[#eadfd5]
                    px-5
                    py-4
                    mb-5
                    flex
                    flex-col
                    md:flex-row
                    md:items-center
                    md:justify-between
                    gap-4
                "
            >

                <div>

                    <p
                        class="
                            text-sm
                            font-bold
                            text-[#30221b]
                        "
                    >
                        <?= number_format($total, 0, ',', ' ') ?>

                        bien<?= $total > 1 ? 's' : '' ?>

                        trouvé<?= $total > 1 ? 's' : '' ?>
                    </p>

                    <?php if ($filtreActif): ?>

                        <p
                            class="
                                text-xs
                                text-[#8a7b70]
                                mt-1
                            "
                        >
                            Résultats correspondant à vos critères
                        </p>

                    <?php endif; ?>

                </div>


                <!-- TRI -->

                <form
                    method="GET"
                    action="/immo-sn/biens"
                    class="flex items-center gap-2"
                >

                    <?php foreach ($filtres as $nom => $valeur): ?>

                        <?php if ($valeur !== '' && $nom !== 'tri'): ?>

                            <input
                                type="hidden"
                                name="<?= htmlspecialchars($nom) ?>"
                                value="<?= htmlspecialchars((string) $valeur) ?>"
                            >

                        <?php endif; ?>

                    <?php endforeach; ?>


                    <label
                        for="tri"
                        class="
                            text-xs
                            font-semibold
                            text-[#806f63]
                            whitespace-nowrap
                        "
                    >
                        Trier :
                    </label>


                    <select
                        id="tri"
                        name="tri"
                        onchange="this.form.submit()"
                        class="
                            rounded-xl
                            border
                            border-[#e6dcd1]
                            bg-[#fffdf9]
                            px-3
                            py-2.5
                            text-xs
                            font-semibold
                            text-[#30221b]
                            outline-none
                        "
                    >

                        <option
                            value="recent"
                            <?= $tri === 'recent' ? 'selected' : '' ?>
                        >
                            Plus récents
                        </option>

                        <option
                            value="ancien"
                            <?= $tri === 'ancien' ? 'selected' : '' ?>
                        >
                            Plus anciens
                        </option>

                        <option
                            value="prix_asc"
                            <?= $tri === 'prix_asc' ? 'selected' : '' ?>
                        >
                            Prix croissant
                        </option>

                        <option
                            value="prix_desc"
                            <?= $tri === 'prix_desc' ? 'selected' : '' ?>
                        >
                            Prix décroissant
                        </option>

                        <option
                            value="surface_desc"
                            <?= $tri === 'surface_desc' ? 'selected' : '' ?>
                        >
                            Surface décroissante
                        </option>

                    </select>

                </form>

            </div>


            <!-- =================================================
                 LISTE
            ================================================== -->

            <?php if (empty($biens)): ?>

                <div
                    class="
                        bg-white
                        border
                        border-[#eadfd5]
                        rounded-[22px]
                        p-10
                        md:p-16
                        text-center
                    "
                >

                    <div
                        class="
                            w-16
                            h-16
                            mx-auto
                            rounded-2xl
                            bg-[#f2eadf]
                            flex
                            items-center
                            justify-center
                            mb-5
                        "
                    >

                        <svg
                            class="w-8 h-8 text-[#a98260]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        >
                            <path d="M3 21h18" />
                            <path d="M5 21V9l7-5 7 5v12" />
                            <path d="M9 21v-5h6v5" />
                        </svg>

                    </div>


                    <h2
                        class="
                            text-xl
                            font-extrabold
                            text-[#30221b]
                        "
                    >
                        Aucun bien trouvé
                    </h2>


                    <p
                        class="
                            text-sm
                            text-[#806f63]
                            mt-2
                            max-w-md
                            mx-auto
                        "
                    >
                        Aucun bien ne correspond actuellement
                        à vos critères de recherche.
                    </p>


                    <?php if ($filtreActif): ?>

                        <a
                            href="/immo-sn/biens"
                            class="
                                inline-flex
                                mt-6
                                rounded-xl
                                bg-[#30221b]
                                px-5
                                py-3
                                text-sm
                                font-bold
                                text-white
                                hover:bg-[#5a402d]
                            "
                        >
                            Réinitialiser les filtres
                        </a>

                    <?php endif; ?>

                </div>


            <?php else: ?>


                <div
                    class="
                        grid
                        grid-cols-1
                        md:grid-cols-2
                        xl:grid-cols-3
                        gap-5
                    "
                >

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
                            (float) (
                                $bien['prix'] ?? 0
                            );

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

                            $photo =
                                '/' .
                                ltrim($photo, '/');
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Libellé offre
                        |--------------------------------------------------------------------------
                        */

                        $offreLabel =
                            $type === 'location'
                                ? 'À louer'
                                : (
                                    $type === 'vente'
                                        ? 'À vendre'
                                        : ucfirst(
                                            $type ?: 'Disponible'
                                        )
                                );

                        ?>


                        <article
                            class="
                                group
                                bg-white
                                rounded-[20px]
                                border
                                border-[#eadfd5]
                                overflow-hidden
                                shadow-sm
                                hover:shadow-xl
                                hover:-translate-y-1
                                transition-all
                                duration-300
                            "
                        >

                            <!-- IMAGE -->

                            <div
                                class="
                                    relative
                                    h-[215px]
                                    overflow-hidden
                                "
                            >

                                <img
                                    src="<?= htmlspecialchars($photo) ?>"
                                    alt="<?= htmlspecialchars($titre) ?>"
                                    class="
                                        w-full
                                        h-full
                                        object-cover
                                        group-hover:scale-105
                                        transition
                                        duration-500
                                    "
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='<?= htmlspecialchars($imageParDefaut, ENT_QUOTES) ?>';"
                                >


                                <div
                                    class="
                                        absolute
                                        inset-x-0
                                        top-0
                                        h-24
                                        bg-gradient-to-b
                                        from-black/35
                                        to-transparent
                                        pointer-events-none
                                    "
                                ></div>


                                <!-- TYPE OFFRE -->

                                <span
                                    class="
                                        absolute
                                        top-4
                                        left-4
                                        rounded-full
                                        bg-[#30221b]
                                        px-3
                                        py-1.5
                                        text-[10px]
                                        font-extrabold
                                        uppercase
                                        tracking-wide
                                        text-white
                                        shadow
                                    "
                                >
                                    <?= htmlspecialchars($offreLabel) ?>
                                </span>


                                <!-- CATÉGORIE -->

                                <?php if ($categorie): ?>

                                    <span
                                        class="
                                            absolute
                                            top-4
                                            right-4
                                            rounded-full
                                            bg-white/95
                                            px-3
                                            py-1.5
                                            text-[10px]
                                            font-bold
                                            text-[#5a402d]
                                            shadow-sm
                                        "
                                    >
                                        <?= htmlspecialchars($categorie) ?>
                                    </span>

                                <?php endif; ?>

                            </div>


                            <!-- CONTENU -->

                            <div class="p-5">

                                <h2
                                    class="
                                        text-[17px]
                                        font-extrabold
                                        text-[#30221b]
                                        line-clamp-1
                                    "
                                >
                                    <?= htmlspecialchars($titre) ?>
                                </h2>


                                <!-- LOCALISATION -->

                                <?php if ($localisation): ?>

                                    <p
                                        class="
                                            flex
                                            items-center
                                            gap-1.5
                                            text-xs
                                            text-[#806f63]
                                            mt-2
                                            line-clamp-1
                                        "
                                    >

                                        <svg
                                            class="w-4 h-4 shrink-0 text-[#a98260]"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"
                                            />

                                            <circle
                                                cx="12"
                                                cy="10"
                                                r="2.5"
                                            />
                                        </svg>

                                        <?= htmlspecialchars($localisation) ?>

                                    </p>

                                <?php endif; ?>


                                <!-- CARACTÉRISTIQUES -->

                                <div
                                    class="
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-4
                                        mt-5
                                        pt-4
                                        border-t
                                        border-[#eee5db]
                                    "
                                >

                                    <?php if (
                                        $chambresBien !== null
                                        && $chambresBien !== ''
                                    ): ?>

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-1.5
                                                text-xs
                                                font-semibold
                                                text-[#6f625a]
                                            "
                                        >

                                            <svg
                                                class="w-4 h-4 text-[#a98260]"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path d="M3 7v10" />
                                                <path d="M21 7v10" />
                                                <path d="M3 12h18" />
                                                <path d="M5 12V9a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3" />
                                                <path d="M12 12V9a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3" />
                                            </svg>

                                            <?= htmlspecialchars((string) $chambresBien) ?>

                                            chambre<?= (int) $chambresBien > 1 ? 's' : '' ?>

                                        </span>

                                    <?php endif; ?>


                                    <?php if (
                                        $surface !== null
                                        && $surface !== ''
                                    ): ?>

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-1.5
                                                text-xs
                                                font-semibold
                                                text-[#6f625a]
                                            "
                                        >

                                            <svg
                                                class="w-4 h-4 text-[#a98260]"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path d="M4 4h6" />
                                                <path d="M4 4v6" />
                                                <path d="M20 20h-6" />
                                                <path d="M20 20v-6" />
                                                <path d="M4 20v-6" />
                                                <path d="M4 20h6" />
                                                <path d="M20 4h-6" />
                                                <path d="M20 4v6" />
                                            </svg>

                                            <?= htmlspecialchars((string) $surface) ?>
                                            m²

                                        </span>

                                    <?php endif; ?>

                                </div>


                                <!-- PRIX + ACTION -->

                                <div
                                    class="
                                        flex
                                        items-end
                                        justify-between
                                        gap-3
                                        mt-5
                                    "
                                >

                                    <div>

                                        <p
                                            class="
                                                text-[18px]
                                                font-extrabold
                                                text-[#a87800]
                                            "
                                        >

                                            <?= number_format(
                                                $prix,
                                                0,
                                                ',',
                                                ' '
                                            ) ?>

                                            FCFA

                                        </p>


                                        <?php if ($type === 'location'): ?>

                                            <span
                                                class="
                                                    text-[10px]
                                                    font-semibold
                                                    text-[#918178]
                                                "
                                            >
                                                par mois
                                            </span>

                                        <?php endif; ?>

                                    </div>


                                    <a
                                        href="/immo-sn/biens/<?= urlencode((string) $id) ?>"
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-xl
                                            bg-[#f2eadf]
                                            px-4
                                            py-2.5
                                            text-xs
                                            font-extrabold
                                            text-[#30221b]
                                            hover:bg-[#30221b]
                                            hover:text-white
                                            transition
                                        "
                                    >

                                        Voir

                                        <svg
                                            class="w-4 h-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path d="M5 12h14" />
                                            <path d="m13 6 6 6-6 6" />
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

                    <div
                        class="
                            flex
                            flex-wrap
                            justify-center
                            items-center
                            gap-2
                            mt-8
                        "
                    >

                        <?php if ($page > 1): ?>

                            <?php

                            $queryPrec =
                                $paginationQuery;

                            $queryPrec['page'] =
                                $page - 1;

                            ?>

                            <a
                                href="/immo-sn/biens?<?= htmlspecialchars(http_build_query($queryPrec)) ?>"
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-xl
                                    border
                                    border-[#e3d8cc]
                                    bg-white
                                    px-4
                                    py-2.5
                                    text-xs
                                    font-bold
                                    text-[#5a402d]
                                    hover:bg-[#f2eadf]
                                "
                            >
                                ← Précédent
                            </a>

                        <?php endif; ?>


                        <?php

                        $debut = max(
                            1,
                            $page - 2
                        );

                        $fin = min(
                            $pages,
                            $page + 2
                        );

                        ?>


                        <?php for (
                            $i = $debut;
                            $i <= $fin;
                            $i++
                        ): ?>

                            <?php

                            $queryPage =
                                $paginationQuery;

                            $queryPage['page'] =
                                $i;

                            ?>

                            <a
                                href="/immo-sn/biens?<?= htmlspecialchars(http_build_query($queryPage)) ?>"
                                class="
                                    w-10
                                    h-10
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-xl
                                    text-xs
                                    font-extrabold
                                    <?= $i === $page
                                        ? 'bg-[#30221b] text-white'
                                        : 'bg-white border border-[#e3d8cc] text-[#5a402d] hover:bg-[#f2eadf]'
                                    ?>
                                "
                            >
                                <?= $i ?>
                            </a>

                        <?php endfor; ?>


                        <?php if ($page < $pages): ?>

                            <?php

                            $querySuiv =
                                $paginationQuery;

                            $querySuiv['page'] =
                                $page + 1;

                            ?>

                            <a
                                href="/immo-sn/biens?<?= htmlspecialchars(http_build_query($querySuiv)) ?>"
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-xl
                                    border
                                    border-[#e3d8cc]
                                    bg-white
                                    px-4
                                    py-2.5
                                    text-xs
                                    font-bold
                                    text-[#5a402d]
                                    hover:bg-[#f2eadf]
                                "
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
