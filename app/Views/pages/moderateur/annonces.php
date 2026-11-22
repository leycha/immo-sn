
<?php

use Core\Session;

$biens = $biens ?? [];
$filtres = $filtres ?? [];
$categories = $categories ?? [];

$page = max(1, (int) ($page ?? 1));
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalBiens = (int) ($totalBiens ?? 0);

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$message = trim((string) ($_GET['message'] ?? ''));
$typeMessage = (string) ($_GET['type'] ?? 'succes');

?>

<div class="min-h-screen bg-[#F5F3ED] px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-7xl">

        <!-- =====================================================
             TOPBAR
        ====================================================== -->
        <div class="mb-8 flex flex-col gap-4 rounded-2xl border border-[#E8E1D5] bg-white px-5 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-[#71869A]">
                    <span class="font-bold text-[#A98232]">IMMO-SN</span>
                    <span>/</span>
                    <span>Administration</span>
                    <span>/</span>
                    <span class="text-[#101D33]">Validation des annonces</span>
                </div>

                <p class="mt-1 text-sm font-semibold text-[#101D33]">
                    Espace de modération
                </p>
            </div>

            <a
                href="/immo-sn/moderateur"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#101D33] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1B2D4A]"
            >
                <span aria-hidden="true">&larr;</span>
                Tableau de bord
            </a>

        </div>


        <!-- =====================================================
             EN-TÊTE PRINCIPAL
        ====================================================== -->
        <section class="relative isolate mb-8 overflow-hidden rounded-[28px] bg-[#071B36] px-5 py-8 shadow-xl shadow-[#071B36]/15 sm:px-9 sm:py-10">

            <!-- Décorations -->
            <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full border-[35px] border-white/5"></div>

            <div class="pointer-events-none absolute -bottom-24 right-36 h-48 w-48 rounded-full bg-[#F5B800]/15 blur-3xl"></div>

            <div class="pointer-events-none absolute -left-20 bottom-0 h-52 w-52 rounded-full bg-[#D7A200]/10 blur-3xl"></div>

            <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

                <div class="max-w-2xl">

                    <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-[#F5B800]/30 bg-[#F5B800]/10 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-[#F5D36B]">

                        <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>

                        Espace modérateur

                    </div>

                    <h1 class="break-words text-2xl font-bold leading-tight text-white sm:text-4xl">
                        Validation des annonces.
                    </h1>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-white/70 sm:text-base">
                        Examinez les annonces immobilières, vérifiez leur conformité
                        et prenez les décisions nécessaires avant leur publication.
                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">

                        <a
                            href="#liste-annonces"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-5 py-3 text-sm font-bold text-[#071B36] shadow-lg shadow-[#F5B800]/10 transition hover:-translate-y-0.5 hover:bg-[#FFD04A] sm:w-auto"
                        >
                            Consulter les annonces
                            <span aria-hidden="true">&darr;</span>
                        </a>

                        <a
                            href="/immo-sn/moderateur"
                            class="inline-flex w-full items-center justify-center rounded-xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20 sm:w-auto"
                        >
                            Retour au tableau de bord
                        </a>

                    </div>

                </div>

                <!-- Illustration -->
                <div class="hidden shrink-0 lg:flex lg:items-center lg:justify-center">

                    <div class="flex h-36 w-36 items-center justify-center rounded-[32px] border border-[#F5B800]/20 bg-white/10 text-[#F5C94F] shadow-2xl">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-20 w-20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="m3 10 9-7 9 7"></path>
                            <path d="M5 9v11h14V9"></path>
                            <path d="M9 20v-6h6v6"></path>
                            <path d="m16 5 3 2.3"></path>
                        </svg>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             MESSAGE DE RETOUR
        ====================================================== -->
        <?php if ($message !== ''): ?>

            <div class="mb-6 flex items-start gap-3 rounded-2xl border px-5 py-4 text-sm font-medium
                <?= $typeMessage === 'erreur'
                    ? 'border-red-200 bg-red-50 text-red-700'
                    : 'border-green-200 bg-green-50 text-green-700' ?>">

                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                    <?= $typeMessage === 'erreur'
                        ? 'bg-red-100 text-red-700'
                        : 'bg-green-100 text-green-700' ?>"
                      aria-hidden="true">
                    <?= $typeMessage === 'erreur' ? '!' : '&#10003;' ?>
                </span>

                <p class="pt-1"><?= $e($message) ?></p>

            </div>

        <?php endif; ?>


        <!-- =====================================================
             STATISTIQUES
        ====================================================== -->
        <section class="mb-8">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <!-- Nombre d'annonces -->
                <div class="group rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                    <div class="flex items-center justify-between">

                        <p class="text-sm font-medium text-[#71869A]">
                            Annonces à examiner
                        </p>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-amber-100 bg-amber-50 text-amber-700">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m3 10 9-7 9 7"></path>
                                <path d="M5 9v11h14V9"></path>
                            </svg>

                        </div>

                    </div>

                    <p class="mt-4 text-3xl font-bold tracking-tight text-[#071B36]">
                        <?= number_format($totalBiens, 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 text-xs text-[#71869A]">
                        Annonces en attente de validation
                    </p>

                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-[#F0EDE6]">
                        <div class="h-full w-2/3 rounded-full bg-[#F5B800]"></div>
                    </div>

                </div>


                <!-- Pagination -->
                <div class="rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <p class="text-sm font-medium text-[#71869A]">
                            Page actuelle
                        </p>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-[#F5DFA0] bg-[#FFF8E5] text-[#8A6500]">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M8 6h13"></path>
                                <path d="M8 12h13"></path>
                                <path d="M8 18h13"></path>
                                <path d="M3 6h.01"></path>
                                <path d="M3 12h.01"></path>
                                <path d="M3 18h.01"></path>
                            </svg>

                        </div>

                    </div>

                    <p class="mt-4 text-3xl font-bold tracking-tight text-[#071B36]">

                        <?= (int) $page ?>

                        <span class="text-lg font-medium text-[#71869A]">
                            / <?= (int) $totalPages ?>
                        </span>

                    </p>

                    <p class="mt-1 text-xs text-[#71869A]">
                        Pagination des résultats
                    </p>

                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-[#F0EDE6]">

                        <div
                            class="h-full rounded-full bg-[#F5B800]"
                            style="width: <?= min(100, ($page / $totalPages) * 100) ?>%"
                        ></div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             RECHERCHE ET FILTRES
        ====================================================== -->
        <section class="mb-8 rounded-[24px] border border-[#E8E1D5] bg-white p-5 shadow-sm sm:p-7">

            <div class="mb-6">

                <p class="text-xs font-bold uppercase tracking-wider text-[#A98232]">
                    Outils de recherche
                </p>

                <h2 class="mt-1 text-xl font-bold text-[#071B36]">
                    Filtrer les annonces
                </h2>

                <p class="mt-1 text-sm text-[#71869A]">
                    Retrouvez rapidement une annonce à examiner.
                </p>

            </div>

            <form
                method="GET"
                action="/immo-sn/moderateur/annonces"
            >

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    <!-- Recherche -->
                    <div class="md:col-span-2">

                        <label
                            for="q"
                            class="mb-2 block text-sm font-semibold text-[#101D33]"
                        >
                            Rechercher une annonce
                        </label>

                        <div class="relative">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#71869A]"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>

                            <input
                                type="search"
                                id="q"
                                name="q"
                                value="<?= $e($filtres['q'] ?? '') ?>"
                                placeholder="Titre, description, propriétaire, ville..."
                                class="w-full rounded-xl border border-[#E8E1D5] bg-[#FAFAF8] py-3.5 pl-12 pr-4 text-sm text-[#101D33] outline-none transition placeholder:text-[#8A98A6] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/15"
                            >

                        </div>

                    </div>


                    <!-- Catégorie -->
                    <div>

                        <label
                            for="category_id"
                            class="mb-2 block text-sm font-semibold text-[#101D33]"
                        >
                            Catégorie
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="w-full rounded-xl border border-[#E8E1D5] bg-[#FAFAF8] px-4 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/15"
                        >

                            <option value="">Toutes les catégories</option>

                            <?php foreach ($categories as $categorie): ?>

                                <option
                                    value="<?= $e($categorie['id'] ?? '') ?>"
                                    <?= (string) ($filtres['category_id'] ?? '') === (string) ($categorie['id'] ?? '')
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= $e($categorie['libelle'] ?? '') ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>


                <!-- Boutons -->
                <div class="mt-6 flex flex-wrap gap-3">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#071B36] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#142D4C]"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>

                        Rechercher

                    </button>

                    <a
                        href="/immo-sn/moderateur/annonces"
                        class="inline-flex items-center justify-center rounded-xl border border-[#E8E1D5] bg-[#FAFAF8] px-6 py-3 text-sm font-semibold text-[#101D33] transition hover:border-[#D7A200] hover:bg-[#FFF8E5]"
                    >
                        Réinitialiser
                    </a>

                </div>

            </form>

        </section>


        <!-- =====================================================
             LISTE DES ANNONCES
        ====================================================== -->
        <section id="liste-annonces">

            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="text-xs font-bold uppercase tracking-wider text-[#A98232]">
                        Modération immobilière
                    </p>

                    <h2 class="mt-1 text-2xl font-bold text-[#071B36]">
                        Annonces à valider
                    </h2>

                </div>

                <p class="text-sm text-[#71869A]">
                    <?= number_format($totalBiens, 0, ',', ' ') ?>
                    résultat(s)
                </p>

            </div>


            <!-- Grille des annonces -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

                <?php if (empty($biens)): ?>

                    <!-- Aucune annonce -->
                    <div class="col-span-full rounded-[24px] border border-[#E8E1D5] bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-2xl border border-[#F5DFA0] bg-[#FFF8E5] text-[#A98232]">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="m3 10 9-7 9 7"></path>
                                <path d="M5 9v11h14V9"></path>
                                <path d="M9 20v-6h6v6"></path>
                            </svg>

                        </div>

                        <h3 class="text-xl font-bold text-[#071B36]">
                            Aucune annonce en attente
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#71869A]">
                            Aucune annonce ne correspond actuellement à vos critères.
                            Vous pourrez retrouver ici les prochaines annonces à examiner.
                        </p>

                    </div>

                <?php else: ?>

                    <?php foreach ($biens as $bien): ?>

                        <?php
                        $bienId = (string) ($bien['id'] ?? '');
                        $formRejetId = 'form-rejet-' . $bienId;
                        ?>

                        <article class="group flex h-full flex-col overflow-hidden rounded-[22px] border border-[#E8E1D5] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D7A200]/40 hover:shadow-xl">

                               <!-- PHOTO -->
                            <div class="relative h-48 shrink-0 overflow-hidden bg-[#F0EDE6]">

                                <?php
                                $photo = trim((string) ($bien['photo'] ?? ''));

                                $photoUrl = $photo !== ''
                                    ? (
                                        preg_match('#^https?://#i', $photo)
                                            ? $photo
                                            : '/' . ltrim($photo, '/')
                                    )
                                    : '';
                                ?>

                                <?php if ($photoUrl !== ''): ?>

                                    <img
                                        src="<?= $e($photoUrl) ?>"
                                        alt="<?= $e($bien['titre'] ?? 'Photo du bien') ?>"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >

                                <?php else: ?>

                                    <div class="flex h-full items-center justify-center text-[#A98232]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                            <path d="m3 10 9-7 9 7"></path>
                                            <path d="M5 9v11h14V9"></path>
                                            <path d="M9 20v-6h6v6"></path>
                                        </svg>
                                    </div>

                                <?php endif; ?>

                                <!-- Statut -->
                                <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-bold text-amber-800 shadow-sm">
                                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                    En attente
                                </span>

                            </div>
                            <!-- INFORMATIONS -->
                            <div class="flex min-w-0 flex-1 flex-col p-5">

                                <div class="flex-1">

                                    <h3 class="min-w-0 break-words line-clamp-2 text-lg font-bold leading-snug text-[#071B36] transition group-hover:text-[#A98232]">

                                        <?= $e($bien['titre'] ?? 'Annonce sans titre') ?>

                                    </h3>


                                    <!-- Catégorie et ville -->
                                    <div class="mt-3 flex min-w-0 flex-wrap items-center gap-x-2 gap-y-1 text-xs text-[#71869A]">

                                        <span>
                                            <?= $e($bien['categorie_nom'] ?? 'Catégorie non précisée') ?>
                                        </span>

                                        <span class="text-[#D4CBB9]">&bull;</span>

                                        <span>
                                            <?= $e($bien['ville_nom'] ?? 'Ville non précisée') ?>
                                        </span>

                                    </div>


                                    <!-- Prix -->
                                    <p class="mt-4 text-xl font-bold text-[#8A6500]">

                                        <?php if (isset($bien['prix']) && is_numeric($bien['prix'])): ?>

                                            <?= number_format((float) $bien['prix'], 0, ',', ' ') ?> FCFA

                                        <?php else: ?>

                                            <span class="text-sm font-medium text-[#8A98A6]">
                                                Prix non précisé
                                            </span>

                                        <?php endif; ?>

                                    </p>


                                    <!-- Détails -->
                                    <a
                                        href="/immo-sn/biens/<?= rawurlencode($bienId) ?>"
                                        class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#8A6500] transition hover:text-[#071B36]"
                                    >

                                        Voir les détails

                                        <span class="text-lg" aria-hidden="true">&rarr;</span>

                                    </a>

                                </div>


                                <!-- Séparateur -->
                                <div class="my-5 h-px bg-[#E8E1D5]"></div>


                                <!-- MOTIF DE REJET -->
                                <label
                                    for="<?= $e($formRejetId) ?>-motif"
                                    class="mb-2 block text-xs font-semibold text-[#101D33]"
                                >
                                    Motif du rejet
                                </label>

                                <input
                                    type="text"
                                    id="<?= $e($formRejetId) ?>-motif"
                                    name="motif_rejet"
                                    form="<?= $e($formRejetId) ?>"
                                    required
                                    maxlength="1000"
                                    placeholder="Préciser le motif..."
                                    class="mb-4 w-full rounded-xl border border-[#E8E1D5] bg-[#FAFAF8] px-3 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#8A98A6] focus:border-red-400 focus:ring-2 focus:ring-red-100"
                                >


                                <!-- BOUTONS D'ACTION -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                    <!-- APPROUVER -->
                                    <form
                                        method="POST"
                                        action="/immo-sn/moderateur/annonces/<?= rawurlencode($bienId) ?>/approuver"
                                        onsubmit="return confirm('Confirmer l’approbation de cette annonce ?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="_csrf_token"
                                            value="<?= $e(Session::csrfToken()) ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-green-700 px-2 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-800"
                                        >

                                            <span aria-hidden="true">&#10003;</span>

                                            Approuver

                                        </button>

                                    </form>


                                    <!-- REJETER -->
                                    <form
                                        id="<?= $e($formRejetId) ?>"
                                        method="POST"
                                        action="/immo-sn/moderateur/annonces/<?= rawurlencode($bienId) ?>/rejeter"
                                        onsubmit="return confirm('Confirmer le rejet de cette annonce ?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="_csrf_token"
                                            value="<?= $e(Session::csrfToken()) ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-700 px-2 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-red-800"
                                        >

                                            <span aria-hidden="true">&times;</span>

                                            Rejeter

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </section>


        <!-- =====================================================
             PAGINATION
        ====================================================== -->
        <?php if ($totalPages > 1): ?>

            <?php
            $parametres = [
                'q' => $filtres['q'] ?? '',
                'category_id' => $filtres['category_id'] ?? '',
            ];
            ?>

            <div class="mt-9 flex flex-wrap items-center justify-center gap-3">

                <?php if ($page > 1): ?>

                    <a
                        href="?<?= $e(http_build_query($parametres + ['page' => $page - 1])) ?>"
                        class="rounded-xl border border-[#E8E1D5] bg-white px-5 py-3 text-sm font-semibold text-[#101D33] shadow-sm transition hover:border-[#D7A200] hover:bg-[#FFF8E5]"
                    >
                        &larr; Précédent
                    </a>

                <?php endif; ?>


                <span class="rounded-xl bg-[#071B36] px-5 py-3 text-sm font-semibold text-white shadow-sm">
                    Page <?= (int) $page ?> / <?= (int) $totalPages ?>
                </span>


                <?php if ($page < $totalPages): ?>

                    <a
                        href="?<?= $e(http_build_query($parametres + ['page' => $page + 1])) ?>"
                        class="rounded-xl border border-[#E8E1D5] bg-white px-5 py-3 text-sm font-semibold text-[#101D33] shadow-sm transition hover:border-[#D7A200] hover:bg-[#FFF8E5]"
                    >
                        Suivant &rarr;
                    </a>

                <?php endif; ?>

            </div>

        <?php endif; ?>


        <!-- =====================================================
             PIED DE PAGE
        ====================================================== -->
        <div class="mt-12 border-t border-[#E8E1D5] py-6 text-center">

            <p class="text-xs text-[#71869A]">
                IMMO-SN &mdash; Espace de modération
            </p>

        </div>

    </div>

</div>
