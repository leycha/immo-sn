
<?php
/**
 * Vue : Mes alertes
 * Emplacement :
 * app/Views/pages/member/alertes.php
 *
 * Variables disponibles :
 * $alertes
 * $categories
 * $villes
 */

$alertes = is_array($alertes ?? null) ? $alertes : [];
$categories = is_array($categories ?? null) ? $categories : [];
$villes = is_array($villes ?? null) ? $villes : [];

$nombreTotal = count($alertes);
$nombreActives = 0;

foreach ($alertes as $alerte) {
    if (!empty($alerte['is_active'])) {
        $nombreActives++;
    }
}

$nombreInactives = $nombreTotal - $nombreActives;
$success = $_GET['success'] ?? null;

if (!function_exists('eAlerte')) {
    function eAlerte($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
?>

<div class="min-h-screen bg-[#F5F3ED]">

    <!-- =========================================================
         HEADER
    ========================================================== -->
    <header class="sticky top-0 z-30 border-b border-[#E7E0D5] bg-white/95 shadow-sm backdrop-blur-xl">

        <div class="mx-auto flex min-h-[82px] max-w-[1600px] flex-col justify-center gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-8 lg:px-10">

            <div>
                <div class="mb-1.5 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#A98235]">
                    <span>Espace membre</span>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-3.5 w-3.5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                    </svg>

                    <span class="text-[#8793A0]">Mes alertes</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-[#071B36] sm:text-3xl">
                    Mes alertes
                </h1>

                <p class="mt-1 text-sm text-[#718096]">
                    Retrouvez les biens correspondant à vos critères de recherche.
                </p>
            </div>

            <button
                type="button"
                onclick="ouvrirModalCreation()"
                class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-[#071B36] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#071B36]/10 transition duration-300 hover:-translate-y-0.5 hover:bg-[#102B4D]"
            >
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-[#F5B800] text-[#071B36]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>

                <span>Créer une alerte</span>
            </button>

        </div>
    </header>


    <!-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== -->
    <main class="px-5 py-7 md:px-8 md:py-9">

        <div class="mx-auto max-w-[1450px]">

            <!-- =================================================
                 MESSAGE DE SUCCÈS
            ================================================== -->
            <?php if ($success): ?>

                <div
                    id="messageSucces"
                    class="mb-7 flex items-start gap-3 rounded-2xl border border-[#D8E8DB] bg-[#F0F7F1] px-5 py-4 text-sm text-[#376B49]"
                >

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#3F8054] shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                        </svg>
                    </div>

                    <div class="pt-0.5">

                        <?php if ($success === 'created'): ?>

                            <p class="font-bold">Alerte créée avec succès.</p>
                            <p class="mt-1 text-xs text-[#62816B]">
                                Votre recherche immobilière est maintenant enregistrée.
                            </p>

                        <?php elseif ($success === 'updated'): ?>

                            <p class="font-bold">Alerte modifiée avec succès.</p>
                            <p class="mt-1 text-xs text-[#62816B]">
                                Les critères de votre alerte ont été mis à jour.
                            </p>

                        <?php elseif ($success === 'deleted'): ?>

                            <p class="font-bold">Alerte supprimée avec succès.</p>
                            <p class="mt-1 text-xs text-[#62816B]">
                                Cette recherche ne sera plus conservée dans votre espace.
                            </p>

                        <?php else: ?>

                            <p class="font-bold">Opération effectuée avec succès.</p>

                        <?php endif; ?>

                    </div>

                    <button
                        type="button"
                        onclick="fermerMessageSucces()"
                        class="ml-auto rounded-lg p-2 text-[#62816B] transition hover:bg-[#DCECDF]"
                        aria-label="Fermer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                        </svg>
                    </button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 STATISTIQUES
            ================================================== -->
            <div class="mb-9 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                <!-- TOTAL -->
                <div class="group relative overflow-hidden rounded-2xl border border-[#E8E1D6] bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#071B36]/5">

                    <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-[#F5B800]/10 transition duration-300 group-hover:scale-125"></div>

                    <div class="relative flex items-center justify-between gap-4">

                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#84909C]">
                                Total des alertes
                            </p>

                            <h2 class="mt-3 text-4xl font-bold tracking-tight text-[#071B36]">
                                <?= $nombreTotal ?>
                            </h2>

                            <p class="mt-2 text-xs font-medium text-[#A98235]">
                                Recherches enregistrées
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#FFF5D8] text-[#A98235] transition group-hover:bg-[#F5B800] group-hover:text-[#071B36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>

                    </div>
                </div>


                <!-- ACTIVES -->
                <div class="group relative overflow-hidden rounded-2xl border border-[#E8E1D6] bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#071B36]/5">

                    <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-[#E8F2E9]/70 transition duration-300 group-hover:scale-125"></div>

                    <div class="relative flex items-center justify-between gap-4">

                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#84909C]">
                                Alertes actives
                            </p>

                            <h2 class="mt-3 text-4xl font-bold tracking-tight text-[#071B36]">
                                <?= $nombreActives ?>
                            </h2>

                            <p class="mt-2 text-xs font-medium text-[#4D805B]">
                                Recherches actuellement actives
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#EAF4EC] text-[#4D805B] transition group-hover:bg-[#D5E9D9]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>

                    </div>
                </div>


                <!-- INACTIVES -->
                <div class="group relative overflow-hidden rounded-2xl border border-[#E8E1D6] bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#071B36]/5 sm:col-span-2 xl:col-span-1">

                    <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-[#E9EDF1]/70 transition duration-300 group-hover:scale-125"></div>

                    <div class="relative flex items-center justify-between gap-4">

                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#84909C]">
                                Alertes inactives
                            </p>

                            <h2 class="mt-3 text-4xl font-bold tracking-tight text-[#071B36]">
                                <?= $nombreInactives ?>
                            </h2>

                            <p class="mt-2 text-xs font-medium text-[#718096]">
                                Recherches actuellement désactivées
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#EDF1F5] text-[#718096] transition group-hover:bg-[#E0E7EF]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0Z"/>
                            </svg>
                        </div>

                    </div>
                </div>

            </div>


            <!-- =================================================
                 SECTION ALERTES
            ================================================== -->
            <section class="overflow-hidden rounded-2xl border border-[#E8E1D6] bg-white shadow-sm">

                <!-- EN-TÊTE -->
                <div class="border-b border-[#EAE5DC] px-5 py-6 md:px-7">

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-3">

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#071B36] text-[#F5B800] shadow-lg shadow-[#071B36]/10">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-lg font-bold text-[#071B36]">
                                    Mes recherches
                                </h2>

                                <p class="mt-1 text-xs text-[#84909C]">
                                    Consultez et gérez vos alertes immobilières.
                                </p>
                            </div>

                        </div>

                        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-[#E8E1D6] bg-[#FAF8F3] px-4 py-2 text-xs font-bold text-[#071B36]">
                            <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>
                            <?= $nombreTotal ?>
                            <span class="font-medium text-[#718096]">alerte(s)</span>
                        </div>

                    </div>


                    <!-- FILTRES -->
                    <div class="mt-6 flex flex-wrap gap-2">

                        <button
                            type="button"
                            data-filtre="toutes"
                            class="filtre-alerte rounded-xl bg-[#071B36] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition"
                        >
                            Toutes
                            <span class="ml-1 text-[#F5B800]"><?= $nombreTotal ?></span>
                        </button>

                        <button
                            type="button"
                            data-filtre="actives"
                            class="filtre-alerte rounded-xl border border-transparent px-4 py-2.5 text-xs font-bold text-[#718096] transition hover:border-[#E8E1D6] hover:bg-[#FAF8F3]"
                        >
                            Actives
                            <span class="ml-1 text-[#4D805B]"><?= $nombreActives ?></span>
                        </button>

                        <button
                            type="button"
                            data-filtre="inactives"
                            class="filtre-alerte rounded-xl border border-transparent px-4 py-2.5 text-xs font-bold text-[#718096] transition hover:border-[#E8E1D6] hover:bg-[#FAF8F3]"
                        >
                            Inactives
                            <span class="ml-1 text-[#A98235]"><?= $nombreInactives ?></span>
                        </button>

                    </div>
                </div>


                <?php if (empty($alertes)): ?>

                    <!-- ÉTAT VIDE -->
                    <div class="px-6 py-20 text-center">

                        <div class="relative mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-[#FFF5D8]">

                            <div class="absolute inset-2 rounded-2xl border border-[#E8D9A8]"></div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="relative h-10 w-10 text-[#A98235]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>

                        </div>

                        <h3 class="mt-7 text-2xl font-bold text-[#071B36]">
                            Vous n'avez pas encore d'alerte
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-[#718096]">
                            Créez une alerte pour enregistrer vos critères de recherche
                            et retrouver plus facilement les biens qui vous intéressent.
                        </p>

                        <button
                            type="button"
                            onclick="ouvrirModalCreation()"
                            class="mt-7 inline-flex items-center gap-2 rounded-xl bg-[#071B36] px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#071B36]/10 transition hover:-translate-y-0.5 hover:bg-[#102B4D]"
                        >
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-[#F5B800] text-[#071B36]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </span>
                            Créer ma première alerte
                        </button>

                    </div>

                <?php else: ?>

                    <!-- LISTE DES ALERTES -->
                    <div id="listeAlertes" class="divide-y divide-[#EEE9E0]">

                        <?php foreach ($alertes as $alerte): ?>

                            <?php
                            $active = !empty($alerte['is_active']);

                            $statutLabel = $active ? 'Active' : 'Inactive';

                            $statutClass = $active
                                ? 'border-[#D8E8DB] bg-[#F0F7F1] text-[#376B49]'
                                : 'border-[#E3E6EA] bg-[#F1F3F5] text-[#718096]';

                            $statutDot = $active
                                ? 'bg-[#4D9B64]'
                                : 'bg-[#9AA5B1]';

                            $categorieNom = $alerte['categorie_nom'] ?? 'Tous les types';
                            $ville = $alerte['ville_nom'] ?? '';
                            $quartier = $alerte['quartier_nom'] ?? '';
                            $region = $alerte['region_nom'] ?? '';

                            $budget = isset($alerte['budget_max'])
                                ? number_format((float) $alerte['budget_max'], 0, ',', ' ')
                                : '—';

                            $dateCreation = '';

                            if (!empty($alerte['created_at'])) {
                                $timestamp = strtotime($alerte['created_at']);

                                if ($timestamp !== false) {
                                    $dateCreation = date('d/m/Y', $timestamp);
                                }
                            }

                            $jsonAlerte = htmlspecialchars(
                                json_encode(
                                    $alerte,
                                    JSON_HEX_TAG |
                                    JSON_HEX_APOS |
                                    JSON_HEX_QUOT |
                                    JSON_HEX_AMP
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                            <article
                                class="alerte-item group px-5 py-6 transition duration-300 hover:bg-[#FCFBF8] md:px-7"
                                data-statut="<?= $active ? 'active' : 'inactive' ?>"
                            >

                                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                                    <!-- INFORMATIONS -->
                                    <div class="min-w-0 flex-1">

                                        <div class="flex items-start gap-4">

                                            <!-- ICÔNE -->
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl <?= $active ? 'bg-[#FFF5D8] text-[#A98235]' : 'bg-[#EDF1F5] text-[#718096]' ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-6 w-6"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9h14v-9M9 19v-5h6v5"/>
                                                </svg>
                                            </div>

                                            <div class="min-w-0 flex-1">

                                                <!-- TITRE + STATUT -->
                                                <div class="flex flex-wrap items-center gap-2.5">

                                                    <h3 class="text-base font-bold text-[#071B36]">
                                                        <?= eAlerte($categorieNom) ?>
                                                    </h3>

                                                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[10px] font-bold <?= $statutClass ?>">
                                                        <span class="h-1.5 w-1.5 rounded-full <?= $statutDot ?>"></span>
                                                        <?= $statutLabel ?>
                                                    </span>

                                                </div>


                                                <!-- LOCALISATION ET DATE -->
                                                <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2.5">

                                                    <?php if ($ville || $quartier): ?>

                                                        <div class="flex items-center gap-2 text-xs text-[#526477]">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="h-4 w-4 shrink-0 text-[#A98235]"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24"
                                                                 stroke="currentColor"
                                                                 stroke-width="1.7">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                                            </svg>

                                                            <span>
                                                                <?php if ($ville): ?>
                                                                    <?= eAlerte($ville) ?>
                                                                <?php endif; ?>

                                                                <?php if ($quartier): ?>
                                                                    <?php if ($ville): ?>
                                                                        <span class="mx-1 text-[#D4D9DE]">·</span>
                                                                    <?php endif; ?>
                                                                    <?= eAlerte($quartier) ?>
                                                                <?php endif; ?>
                                                            </span>

                                                        </div>

                                                    <?php endif; ?>


                                                    <?php if ($region): ?>

                                                        <div class="flex items-center gap-2 text-xs text-[#718096]">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="h-4 w-4 text-[#A98235]"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24"
                                                                 stroke="currentColor"
                                                                 stroke-width="1.7">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s8-4.5 8-11a8 8 0 1 0-16 0c0 6.5 8 11 8 11Z"/>
                                                                <circle cx="12" cy="10" r="2.5"/>
                                                            </svg>

                                                            <?= eAlerte($region) ?>

                                                        </div>

                                                    <?php endif; ?>


                                                    <div class="flex items-center gap-2 text-xs text-[#718096]">

                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="h-4 w-4 text-[#A98235]"
                                                             fill="none"
                                                             viewBox="0 0 24 24"
                                                             stroke="currentColor"
                                                             stroke-width="1.7">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2"/>
                                                            <circle cx="12" cy="12" r="9"/>
                                                        </svg>

                                                        Créée le <?= eAlerte($dateCreation ?: '—') ?>

                                                    </div>

                                                </div>


                                                <!-- BUDGET -->
                                                <div class="mt-4 flex flex-wrap items-baseline gap-2">

                                                    <span class="text-xl font-bold tracking-tight text-[#071B36]">
                                                        <?= $budget ?>
                                                    </span>

                                                    <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#A98235]">
                                                        FCFA max.
                                                    </span>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <!-- ACTIONS -->
                                    <div class="flex flex-wrap items-center gap-2 border-t border-[#EEE9E0] pt-4 lg:justify-end lg:border-0 lg:pt-0">

                                        <!-- MODIFIER -->
                                        <button
                                            type="button"
                                            onclick="ouvrirModalModification(<?= $jsonAlerte ?>)"
                                            class="inline-flex items-center gap-2 rounded-xl border border-[#E7E2D9] px-3.5 py-2.5 text-xs font-bold text-[#526477] transition hover:border-[#D9C58F] hover:bg-[#FFF9E9] hover:text-[#071B36]"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4Z"/>
                                            </svg>
                                            Modifier
                                        </button>


                                        <!-- ACTIVER / DÉSACTIVER -->
                                        <form
                                            method="POST"
                                            action="/immo-sn/member/alertes/basculer/<?= urlencode((string) ($alerte['id'] ?? '')) ?>"
                                            class="inline"
                                        >
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-2 rounded-xl border border-transparent px-3.5 py-2.5 text-xs font-bold transition <?= $active ? 'text-[#A98235] hover:border-[#E8D9A8] hover:bg-[#FFF8E6]' : 'text-[#43815A] hover:border-[#D8E8DB] hover:bg-[#F0F7F1]' ?>"
                                            >

                                                <?php if ($active): ?>

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                                    </svg>

                                                    Désactiver

                                                <?php else: ?>

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                                                    </svg>

                                                    Activer

                                                <?php endif; ?>

                                            </button>
                                        </form>


                                        <!-- SUPPRIMER -->
                                        <button
                                            type="button"
                                            onclick="ouvrirModalSuppression('<?= eAlerte($alerte['id'] ?? '') ?>', '<?= eAlerte($categorieNom) ?>')"
                                            class="inline-flex items-center gap-2 rounded-xl border border-transparent px-3.5 py-2.5 text-xs font-bold text-[#A65D50] transition hover:border-[#F0DAD5] hover:bg-[#FCF2EF]"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2m-9 0 1 14h8l1-14M10 11v5m4-5v5"/>
                                            </svg>
                                            Supprimer
                                        </button>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>


                        <!-- MESSAGE FILTRE VIDE -->
                        <div id="aucunResultatFiltre" class="hidden px-6 py-16 text-center">

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#FFF5D8]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-7 w-7 text-[#A98235]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0Z"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-lg font-bold text-[#071B36]">
                                Aucune alerte trouvée
                            </h3>

                            <p class="mt-2 text-sm text-[#718096]">
                                Aucune alerte ne correspond au filtre sélectionné.
                            </p>

                        </div>

                    </div>

                <?php endif; ?>

            </section>


            <!-- =================================================
                 ACCÈS RAPIDES
            ================================================== -->
            <section class="mt-10">

                <div class="mb-5">
                    <div class="mb-2 h-1 w-10 rounded-full bg-[#F5B800]"></div>

                    <h2 class="text-xl font-bold text-[#071B36]">
                        Accès rapides
                    </h2>

                    <p class="mt-1 text-sm text-[#718096]">
                        Retrouvez rapidement les principales fonctionnalités de votre espace membre.
                    </p>
                </div>


                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                    <!-- PUBLIER UN BIEN -->
                    <a
                        href="/immo-sn/member/publier"
                        class="group rounded-2xl border border-[#E8E1D6] bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D9C58F] hover:shadow-xl hover:shadow-[#071B36]/5"
                    >
                        <div class="flex items-start justify-between gap-4">

                            <div>
                                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF5D8] text-[#A98235] transition group-hover:bg-[#F5B800] group-hover:text-[#071B36]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>

                                <h3 class="font-bold text-[#071B36]">
                                    Publier un bien
                                </h3>

                                <p class="mt-2 text-xs leading-6 text-[#718096]">
                                    Ajoutez une nouvelle annonce immobilière à votre espace.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-1 h-5 w-5 shrink-0 text-[#C4CBD2] transition group-hover:translate-x-1 group-hover:text-[#A98235]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                            </svg>

                        </div>
                    </a>


                    <!-- MES BIENS -->
                    <a
                        href="/immo-sn/member/mes-biens"
                        class="group rounded-2xl border border-[#E8E1D6] bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D9C58F] hover:shadow-xl hover:shadow-[#071B36]/5"
                    >
                        <div class="flex items-start justify-between gap-4">

                            <div>
                                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-[#EDF1F5] text-[#071B36] transition group-hover:bg-[#071B36] group-hover:text-[#F5B800]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 21v-7h6v7"/>
                                    </svg>
                                </div>

                                <h3 class="font-bold text-[#071B36]">
                                    Mes biens
                                </h3>

                                <p class="mt-2 text-xs leading-6 text-[#718096]">
                                    Gérez vos annonces et consultez leur statut.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-1 h-5 w-5 shrink-0 text-[#C4CBD2] transition group-hover:translate-x-1 group-hover:text-[#A98235]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                            </svg>

                        </div>
                    </a>


                    <!-- MES ALERTES -->
                    <a
                        href="/immo-sn/member/alertes"
                        class="group rounded-2xl border border-[#D9C58F] bg-[#FFF9E9] p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#071B36]/5"
                    >
                        <div class="flex items-start justify-between gap-4">

                            <div>
                                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 0 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 21h4"/>
                                    </svg>
                                </div>

                                <h3 class="font-bold text-[#071B36]">
                                    Mes alertes
                                </h3>

                                <p class="mt-2 text-xs leading-6 text-[#718096]">
                                    Recevez des annonces correspondant à vos critères.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-1 h-5 w-5 shrink-0 text-[#A98235] transition group-hover:translate-x-1"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                            </svg>

                        </div>
                    </a>

                </div>
            </section>

        </div>
    </main>
</div>


<!-- =============================================================
     MODALE : CRÉER UNE ALERTE
============================================================== -->
<div
    id="modalCreer"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-[#071B36]/70 px-4 py-6 backdrop-blur-sm"
    onclick="fermerModalSiFond(event, 'modalCreer')"
>

    <div
        class="max-h-[90vh] w-full min-w-0 max-w-lg overflow-x-hidden overflow-y-auto rounded-3xl border border-[#E8E1D6] bg-white shadow-2xl"
        onclick="event.stopPropagation()"
    >

        <!-- HEADER -->
        <div class="flex items-center justify-between border-b border-[#EAE5DC] px-6 py-5">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-[#071B36]">
                        Créer une alerte
                    </h2>

                    <p class="mt-1 text-xs text-[#718096]">
                        Enregistrez vos critères de recherche.
                    </p>
                </div>

            </div>

            <button
                type="button"
                onclick="fermerModal('modalCreer')"
                class="rounded-xl p-2 text-[#718096] transition hover:bg-[#F5F3ED] hover:text-[#071B36]"
                aria-label="Fermer"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>

        </div>


        <!-- FORMULAIRE -->
        <form method="POST"
              action="/immo-sn/member/alertes/creer"
              class="px-6 py-6">

            <!-- CATÉGORIE -->
            <div class="mb-5">

                <label for="create_category_id"
                       class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#526477]">
                    Type de bien
                </label>

                <select
                    id="create_category_id"
                    name="category_id"
                    class="w-full rounded-xl border border-[#D9DDE1] bg-[#FCFBF8] px-4 py-3.5 text-sm text-[#071B36] outline-none transition focus:border-[#C49B3B] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                >
                    <option value="">Tous les types</option>

                    <?php foreach ($categories as $categorie): ?>

                        <?php
                        $categorieId = $categorie['id']
                            ?? $categorie['category_id']
                            ?? '';

                        $categorieNom = $categorie['libelle']
                            ?? $categorie['nom']
                            ?? $categorie['name']
                            ?? 'Catégorie';
                        ?>

                        <option value="<?= eAlerte($categorieId) ?>">
                            <?= eAlerte($categorieNom) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>


            <!-- VILLE -->
            <div class="mb-5">

                <label for="create_ville_id"
                       class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#526477]">
                    Localisation
                </label>

                <select
                    id="create_ville_id"
                    name="ville_id"
                    class="w-full rounded-xl border border-[#D9DDE1] bg-[#FCFBF8] px-4 py-3.5 text-sm text-[#071B36] outline-none transition focus:border-[#C49B3B] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                >
                    <option value="">Toutes les localisations</option>

                    <?php foreach ($villes as $villeItem): ?>

                        <?php
                        $villeId = $villeItem['id']
                            ?? $villeItem['ville_id']
                            ?? '';

                        $villeNom = $villeItem['ville']
                            ?? $villeItem['nom']
                            ?? '';

                        $quartierNom = $villeItem['quartier']
                            ?? '';

                        $labelVille = $villeNom;

                        if ($quartierNom) {
                            $labelVille .= ' — ' . $quartierNom;
                        }
                        ?>

                        <option value="<?= eAlerte($villeId) ?>">
                            <?= eAlerte($labelVille ?: 'Localisation') ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>


            <!-- BUDGET -->
            <div class="mb-6">

                <label for="create_budget_max"
                       class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#526477]">
                    Budget maximum
                </label>

                <div class="relative">

                    <input
                        type="number"
                        id="create_budget_max"
                        name="budget_max"
                        min="1"
                        step="1"
                        required
                        placeholder="Ex. 250000"
                        class="w-full rounded-xl border border-[#D9DDE1] bg-[#FCFBF8] px-4 py-3.5 pr-20 text-sm text-[#071B36] outline-none transition placeholder:text-[#AAB5BE] focus:border-[#C49B3B] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                    >

                    <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-bold text-[#A98235]">
                        FCFA
                    </span>

                </div>

                <p class="mt-2 text-xs text-[#84909C]">
                    Indiquez le montant maximum que vous souhaitez consacrer.
                </p>

            </div>


            <!-- ACTIONS -->
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="fermerModal('modalCreer')"
                    class="rounded-xl border border-[#D9DDE1] px-5 py-3 text-sm font-bold text-[#526477] transition hover:bg-[#F5F3ED]"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#071B36] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#071B36]/10 transition hover:bg-[#102B4D]"
                >
                    <span class="inline-flex items-center gap-2">
                        <span class="text-[#F5B800]">+</span>
                        Créer l'alerte
                    </span>
                </button>

            </div>

        </form>

    </div>
</div>


<!-- =============================================================
     MODALE : MODIFIER UNE ALERTE
============================================================== -->
<div
    id="modalModifier"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-[#071B36]/70 px-4 py-6 backdrop-blur-sm"
    onclick="fermerModalSiFond(event, 'modalModifier')"
>

    <div
        class="max-h-[90vh] w-full min-w-0 max-w-lg overflow-x-hidden overflow-y-auto rounded-3xl border border-[#E8E1D6] bg-white shadow-2xl"
        onclick="event.stopPropagation()"
    >

        <!-- HEADER -->
        <div class="flex items-center justify-between border-b border-[#EAE5DC] px-6 py-5">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4Z"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-[#071B36]">
                        Modifier l'alerte
                    </h2>

                    <p class="mt-1 text-xs text-[#718096]">
                        Modifiez les critères de votre recherche.
                    </p>
                </div>

            </div>

            <button
                type="button"
                onclick="fermerModal('modalModifier')"
                class="rounded-xl p-2 text-[#718096] transition hover:bg-[#F5F3ED] hover:text-[#071B36]"
                aria-label="Fermer"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>

        </div>


        <!-- FORMULAIRE -->
        <form id="formModifier"
              method="POST"
              action=""
              class="px-6 py-6">

            <!-- CATÉGORIE -->
            <div class="mb-5">

                <label for="modifier_category_id"
                       class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#526477]">
                    Type de bien
                </label>

                <select
                    id="modifier_category_id"
                    name="category_id"
                    class="w-full rounded-xl border border-[#D9DDE1] bg-[#FCFBF8] px-4 py-3.5 text-sm text-[#071B36] outline-none transition focus:border-[#C49B3B] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                >
                    <option value="">Tous les types</option>

                    <?php foreach ($categories as $categorie): ?>

                        <?php
                        $categorieId = $categorie['id']
                            ?? $categorie['category_id']
                            ?? '';

                        $categorieNom = $categorie['libelle']
                            ?? $categorie['nom']
                            ?? $categorie['name']
                            ?? 'Catégorie';
                        ?>

                        <option value="<?= eAlerte($categorieId) ?>">
                            <?= eAlerte($categorieNom) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>


            <!-- VILLE -->
            <div class="mb-5">

                <label for="modifier_ville_id"
                       class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#526477]">
                    Localisation
                </label>

                <select
                    id="modifier_ville_id"
                    name="ville_id"
                    class="w-full rounded-xl border border-[#D9DDE1] bg-[#FCFBF8] px-4 py-3.5 text-sm text-[#071B36] outline-none transition focus:border-[#C49B3B] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                >
                    <option value="">Toutes les localisations</option>

                    <?php foreach ($villes as $villeItem): ?>

                        <?php
                        $villeId = $villeItem['id']
                            ?? $villeItem['ville_id']
                            ?? '';

                        $villeNom = $villeItem['ville']
                            ?? $villeItem['nom']
                            ?? '';

                        $quartierNom = $villeItem['quartier']
                            ?? '';

                        $labelVille = $villeNom;

                        if ($quartierNom) {
                            $labelVille .= ' — ' . $quartierNom;
                        }
                        ?>

                        <option value="<?= eAlerte($villeId) ?>">
                            <?= eAlerte($labelVille ?: 'Localisation') ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>


            <!-- BUDGET -->
            <div class="mb-6">

                <label for="modifier_budget_max"
                       class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#526477]">
                    Budget maximum
                </label>

                <div class="relative">

                    <input
                        type="number"
                        id="modifier_budget_max"
                        name="budget_max"
                        min="1"
                        step="1"
                        required
                        class="w-full rounded-xl border border-[#D9DDE1] bg-[#FCFBF8] px-4 py-3.5 pr-20 text-sm text-[#071B36] outline-none transition placeholder:text-[#AAB5BE] focus:border-[#C49B3B] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                    >

                    <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-bold text-[#A98235]">
                        FCFA
                    </span>

                </div>

            </div>


            <!-- ACTIONS -->
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="fermerModal('modalModifier')"
                    class="rounded-xl border border-[#D9DDE1] px-5 py-3 text-sm font-bold text-[#526477] transition hover:bg-[#F5F3ED]"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#071B36] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#071B36]/10 transition hover:bg-[#102B4D]"
                >
                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>
</div>


<!-- =============================================================
     MODALE : SUPPRESSION
============================================================== -->
<div
    id="modalSuppression"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-[#071B36]/70 px-4 py-6 backdrop-blur-sm"
    onclick="fermerModalSiFond(event, 'modalSuppression')"
>

    <div
        class="max-h-[90vh] w-full min-w-0 max-w-md overflow-x-hidden overflow-y-auto rounded-3xl border border-[#E8E1D6] bg-white shadow-2xl"
        onclick="event.stopPropagation()"
    >

        <div class="px-6 py-8 text-center">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#FCF0ED]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-7 w-7 text-[#A65D50]"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2m-9 0 1 14h8l1-14M10 11v5m4-5v5"/>
                </svg>
            </div>

            <h2 class="mt-5 text-xl font-bold text-[#071B36]">
                Supprimer cette alerte ?
            </h2>

            <p class="mt-3 text-sm leading-6 text-[#718096]">
                Vous êtes sur le point de supprimer
                <strong id="nomAlerteSuppression" class="font-bold text-[#071B36]">
                    cette alerte
                </strong>.
                Cette action est définitive.
            </p>

            <form
                id="formSuppression"
                method="POST"
                action=""
                class="mt-7 flex flex-col-reverse gap-2 sm:flex-row sm:justify-center"
            >

                <button
                    type="button"
                    onclick="fermerModal('modalSuppression')"
                    class="rounded-xl border border-[#D9DDE1] px-5 py-3 text-sm font-bold text-[#526477] transition hover:bg-[#F5F3ED]"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#A65D50] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#914D41]"
                >
                    Supprimer définitivement
                </button>

            </form>

        </div>
    </div>
</div>


<!-- =============================================================
     JAVASCRIPT
============================================================== -->
<script>
(function () {

    const modalCreer = document.getElementById('modalCreer');
    const modalModifier = document.getElementById('modalModifier');
    const modalSuppression = document.getElementById('modalSuppression');

    const formModifier = document.getElementById('formModifier');
    const formSuppression = document.getElementById('formSuppression');

    const modifierCategorie = document.getElementById('modifier_category_id');
    const modifierVille = document.getElementById('modifier_ville_id');
    const modifierBudget = document.getElementById('modifier_budget_max');

    const nomAlerteSuppression = document.getElementById('nomAlerteSuppression');


    /* =========================================================
       OUVRIR / FERMER LES MODALES
    ========================================================= */

    window.ouvrirModalCreation = function () {

        if (!modalCreer) {
            return;
        }

        modalCreer.classList.remove('hidden');
        modalCreer.classList.add('flex');

        document.body.classList.add('overflow-hidden');

        setTimeout(function () {

            const champBudget = document.getElementById('create_budget_max');

            if (champBudget) {
                champBudget.focus();
            }

        }, 100);

    };


    window.ouvrirModalModification = function (alerte) {

        if (!modalModifier || !alerte || !formModifier) {
            return;
        }

        formModifier.action =
            '/immo-sn/member/alertes/modifier/' +
            encodeURIComponent(alerte.id);

        modifierCategorie.value =
            alerte.category_id ?? '';

        modifierVille.value =
            alerte.ville_id ?? '';

        modifierBudget.value =
            alerte.budget_max ?? '';

        modalModifier.classList.remove('hidden');
        modalModifier.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    };


    window.ouvrirModalSuppression = function (id, nom) {

        if (!modalSuppression || !formSuppression) {
            return;
        }

        formSuppression.action =
            '/immo-sn/member/alertes/supprimer/' +
            encodeURIComponent(id);

        nomAlerteSuppression.textContent =
            nom || 'cette alerte';

        modalSuppression.classList.remove('hidden');
        modalSuppression.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    };


    window.fermerModal = function (id) {

        const modal = document.getElementById(id);

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        if (
            modalCreer?.classList.contains('flex') ||
            modalModifier?.classList.contains('flex') ||
            modalSuppression?.classList.contains('flex')
        ) {
            return;
        }

        document.body.classList.remove('overflow-hidden');

    };


    window.fermerModalSiFond = function (event, id) {

        if (event.target === event.currentTarget) {
            window.fermerModal(id);
        }

    };


    /* =========================================================
       FERMER AVEC ESC
    ========================================================= */

    document.addEventListener('keydown', function (event) {

        if (event.key !== 'Escape') {
            return;
        }

        window.fermerModal('modalCreer');
        window.fermerModal('modalModifier');
        window.fermerModal('modalSuppression');

    });


    /* =========================================================
       FILTRES
    ========================================================= */

    const boutonsFiltres =
        document.querySelectorAll('.filtre-alerte');

    const itemsAlertes =
        document.querySelectorAll('.alerte-item');

    const aucunResultat =
        document.getElementById('aucunResultatFiltre');


    boutonsFiltres.forEach(function (bouton) {

        bouton.addEventListener('click', function () {

            const filtre = bouton.dataset.filtre;

            /* Style du bouton actif */
            boutonsFiltres.forEach(function (btn) {

                btn.classList.remove(
                    'bg-[#071B36]',
                    'text-white'
                );

                btn.classList.add(
                    'text-[#718096]'
                );

            });


            bouton.classList.remove(
                'text-[#718096]'
            );

            bouton.classList.add(
                'bg-[#071B36]',
                'text-white'
            );


            let visibles = 0;


            itemsAlertes.forEach(function (item) {

                const statut = item.dataset.statut;

                let afficher = true;

                if (filtre === 'actives') {

                    afficher = statut === 'active';

                } else if (filtre === 'inactives') {

                    afficher = statut === 'inactive';

                }


                if (afficher) {

                    item.classList.remove('hidden');
                    visibles++;

                } else {

                    item.classList.add('hidden');

                }

            });


            if (aucunResultat) {

                if (visibles === 0) {
                    aucunResultat.classList.remove('hidden');
                } else {
                    aucunResultat.classList.add('hidden');
                }

            }

        });

    });


    /* =========================================================
       FERMETURE DU MESSAGE DE SUCCÈS
    ========================================================= */

    window.fermerMessageSucces = function () {

        const message =
            document.getElementById('messageSucces');

        if (message) {
            message.remove();
        }

    };


    /* =========================================================
       AUTO-DISPARITION DU MESSAGE
    ========================================================= */

    const messageSucces =
        document.getElementById('messageSucces');

    if (messageSucces) {

        setTimeout(function () {

            messageSucces.style.transition =
                'opacity 0.3s ease, transform 0.3s ease';

            messageSucces.style.opacity = '0';

            messageSucces.style.transform =
                'translateY(-5px)';

            setTimeout(function () {
                messageSucces.remove();
            }, 300);

        }, 5000);

    }

})();
</script>
