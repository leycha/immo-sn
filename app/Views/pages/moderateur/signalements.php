
<?php

use Core\Session;

$signalements = is_array($signalements ?? null) ? $signalements : [];
$filtres = is_array($filtres ?? null) ? $filtres : [];

$page = max(1, (int) ($page ?? 1));
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalSignalements = (int) ($totalSignalements ?? 0);

$statsSignalements = $statsSignalements ?? [
    'total' => 0,
    'en_attente' => 0,
    'traites' => 0,
    'rejetes' => 0,
];

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$message = trim((string) ($_GET['message'] ?? ''));
$typeMessage = (string) ($_GET['type'] ?? 'succes');

$statutFiltre = (string) ($filtres['statut'] ?? '');
$recherche = (string) ($filtres['q'] ?? '');

$parametres = [
    'q' => $recherche,
    'statut' => $statutFiltre,
];

?>

<div class="min-h-screen bg-[#F5F3ED] px-3 py-6 sm:px-6 sm:py-8 lg:px-8">

    <div class="mx-auto max-w-7xl space-y-7">

        <!-- =====================================================
             EN-TÊTE
        ====================================================== -->
        <header class="flex min-w-0 flex-col gap-4 rounded-2xl border border-[#E5E8DF] bg-white px-4 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:px-5">

            <div class="min-w-0">

                <div class="flex min-w-0 flex-wrap items-center gap-2 text-xs font-semibold text-[#71869A]">

                    <span>IMMO-SN</span>
                    <span class="text-[#B8C2C8]">/</span>
                    <span>Administration</span>
                    <span class="text-[#B8C2C8]">/</span>
                    <span class="break-words font-semibold text-[#A98200]">
                        Gestion des signalements
                    </span>

                </div>

                <p class="mt-1 break-words text-sm text-[#71869A]">
                    Espace de modération
                </p>

            </div>

            <a
                href="/immo-sn/moderateur"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#101D33] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1C3455] sm:w-auto sm:shrink-0"
            >

                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"/>
                    <path d="M9 12h12"/>
                </svg>

                Tableau de bord

            </a>

        </header>


  
<!-- =====================================================
     BANNIÈRE PRINCIPALE — VERSION COMPACTE
====================================================== -->
<section class="relative overflow-hidden rounded-2xl border border-[#18385D] bg-[#071B36] px-5 py-5 text-white shadow-sm sm:px-7 sm:py-6">

    <div class="relative z-10 flex min-w-0 items-center justify-between gap-5">

        <div class="min-w-0 flex-1">

            <span class="inline-flex items-center gap-2 rounded-full border border-[#F5B800]/30 bg-[#F5B800]/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-[#F5D66F] sm:text-xs">

                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>

                Modération IMMO-SN

            </span>

            <h1 class="mt-3 break-words text-xl font-extrabold leading-tight tracking-tight sm:text-2xl lg:text-3xl">

                Gestion des signalements<span class="text-[#F5B800]">.</span>

            </h1>

            <p class="mt-2 max-w-2xl break-words text-xs leading-5 text-[#D0DCEC] sm:text-sm sm:leading-6">

                Consultez les signalements envoyés par les utilisateurs,
                examinez les annonces concernées et prenez les mesures
                nécessaires pour garantir la fiabilité de la plateforme.

            </p>

            <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:flex-wrap">

                <a
                    href="#liste-signalements"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#F5B800] px-4 py-2.5 text-xs font-bold text-[#071B36] transition hover:bg-[#D7A200] sm:w-auto sm:text-sm"
                >

                    Consulter les signalements

                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M7 17 17 7"/>
                        <path d="M7 7h10v10"/>
                    </svg>

                </a>

                <a
                    href="/immo-sn/moderateur"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-white/20 bg-white/5 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-white/10 sm:w-auto sm:text-sm"
                >
                    Tableau de bord
                </a>

            </div>

        </div>

        <div class="hidden h-24 w-24 shrink-0 items-center justify-center rounded-2xl border border-[#F5B800]/20 bg-white/5 lg:flex">

            <svg class="h-12 w-12 text-[#F5B800]" viewBox="0 0 120 120" fill="none">

                <path d="M35 17h36l19 19v64a5 5 0 0 1-5 5H35a5 5 0 0 1-5-5V22a5 5 0 0 1 5-5Z" stroke="currentColor" stroke-width="4"/>

                <path d="M71 17v20h19" stroke="currentColor" stroke-width="4" stroke-linejoin="round"/>

                <path d="M43 52h34M43 64h34" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>

                <circle cx="76" cy="83" r="20" fill="#071B36" stroke="currentColor" stroke-width="4"/>

                <path d="M76 72v13M76 91v1" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>

            </svg>

        </div>

    </div>

</section>


        <!-- =====================================================
             MESSAGE
        ====================================================== -->
        <?php if ($message !== ''): ?>

            <div class="min-w-0 rounded-2xl border px-4 py-4 text-sm font-medium sm:px-5
                <?= $typeMessage === 'erreur'
                    ? 'border-red-200 bg-red-50 text-red-700'
                    : 'border-green-200 bg-green-50 text-green-700' ?>">

                <div class="flex min-w-0 items-start gap-3">

                    <?php if ($typeMessage === 'erreur'): ?>

                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="m15 9-6 6M9 9l6 6"/>
                        </svg>

                    <?php else: ?>

                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m5 12 4 4L19 6"/>
                        </svg>

                    <?php endif; ?>

                    <p class="min-w-0 break-words">
                        <?= $e($message) ?>
                    </p>

                </div>

            </div>

        <?php endif; ?>


        <!-- =====================================================
             STATISTIQUES
        ====================================================== -->
        <section class="min-w-0">

            <div class="mb-4 flex min-w-0 flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">

                <div class="min-w-0">

                    <p class="break-words text-xs font-bold uppercase tracking-[0.18em] text-[#A98200]">
                        Vue d'ensemble
                    </p>

                    <h2 class="mt-1 break-words text-xl font-extrabold text-[#101D33]">
                        Statistiques des signalements
                    </h2>

                </div>

                <p class="break-words text-sm text-[#71869A] sm:shrink-0">
                    Suivi global de la modération
                </p>

            </div>


            <div class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


                <!-- TOTAL -->
                <div class="min-w-0 rounded-2xl border border-[#E5E8DF] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

                    <div class="flex min-w-0 items-center justify-between gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-[#F5E8BF] bg-[#FFF4CC] text-[#8A6500]">

                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M8 6h13M8 12h13M8 18h13"/>
                                <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>

                        </div>

                        <span class="shrink-0 rounded-full border border-[#F5E8BF] bg-[#FFF7DF] px-3 py-1 text-xs font-semibold text-[#8A6500]">
                            Global
                        </span>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-[#101D33]">
                        <?= number_format((int) ($statsSignalements['total'] ?? 0), 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-[#34445A]">
                        Total des signalements
                    </p>

                    <p class="mt-1 break-words text-xs text-[#71869A]">
                        Tous les signalements reçus
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-[#F5B800]"></div>

                </div>


                <!-- EN ATTENTE -->
                <div class="min-w-0 rounded-2xl border border-[#E5E8DF] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

                    <div class="flex min-w-0 items-center justify-between gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">

                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2"/>
                            </svg>

                        </div>

                        <span class="shrink-0 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                            À traiter
                        </span>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-[#101D33]">
                        <?= number_format((int) ($statsSignalements['en_attente'] ?? 0), 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-[#34445A]">
                        En attente
                    </p>

                    <p class="mt-1 break-words text-xs text-[#71869A]">
                        Signalements à examiner
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-amber-400"></div>

                </div>


                <!-- TRAITÉS -->
                <div class="min-w-0 rounded-2xl border border-[#E5E8DF] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

                    <div class="flex min-w-0 items-center justify-between gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-700">

                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m5 12 4 4L19 6"/>
                                <circle cx="12" cy="12" r="10"/>
                            </svg>

                        </div>

                        <span class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            Terminé
                        </span>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-[#101D33]">
                        <?= number_format((int) ($statsSignalements['traites'] ?? 0), 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-[#34445A]">
                        Traités
                    </p>

                    <p class="mt-1 break-words text-xs text-[#71869A]">
                        Signalements pris en charge
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-green-500"></div>

                </div>


                <!-- REJETÉS -->
                <div class="min-w-0 rounded-2xl border border-[#E5E8DF] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

                    <div class="flex min-w-0 items-center justify-between gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">

                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="m9 9 6 6M15 9l-6 6"/>
                            </svg>

                        </div>

                        <span class="shrink-0 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                            Rejetés
                        </span>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-[#101D33]">
                        <?= number_format((int) ($statsSignalements['rejetes'] ?? 0), 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-[#34445A]">
                        Rejetés
                    </p>

                    <p class="mt-1 break-words text-xs text-[#71869A]">
                        Signalements non retenus
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-red-500"></div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             RECHERCHE ET FILTRES
        ====================================================== -->
        <section class="min-w-0 rounded-[24px] border border-[#E5E8DF] bg-white p-4 shadow-sm sm:p-7">

            <div class="mb-6 min-w-0">

                <p class="break-words text-xs font-bold uppercase tracking-[0.18em] text-[#A98200]">
                    Recherche avancée
                </p>

                <h2 class="mt-1 break-words text-xl font-extrabold text-[#101D33]">
                    Retrouver un signalement
                </h2>

                <p class="mt-1 break-words text-sm text-[#71869A]">
                    Recherchez par annonce ou filtrez les signalements selon leur statut.
                </p>

            </div>


            <form
                method="GET"
                action="/immo-sn/moderateur/signalements"
                class="grid min-w-0 grid-cols-1 items-end gap-4 xl:grid-cols-[minmax(220px,1fr)_240px_auto_auto]"
            >

                <!-- RECHERCHE -->
                <div class="min-w-0">

                    <label for="q" class="mb-2 block text-sm font-semibold text-[#101D33]">
                        Recherche
                    </label>

                    <div class="relative min-w-0">

                        <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#71869A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>

                        <input
                            id="q"
                            type="text"
                            name="q"
                            value="<?= $e($recherche) ?>"
                            placeholder="Titre d'annonce, motif..."
                            class="w-full min-w-0 rounded-xl border border-[#DCE1E4] bg-[#F8F9F6] py-3.5 pl-12 pr-4 text-sm text-[#101D33] outline-none transition placeholder:text-[#9AA8B1] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                        >

                    </div>

                </div>


                <!-- STATUT -->
                <div class="min-w-0">

                    <label for="statut" class="mb-2 block text-sm font-semibold text-[#101D33]">
                        Statut
                    </label>

                    <select
                        id="statut"
                        name="statut"
                        class="w-full min-w-0 rounded-xl border border-[#DCE1E4] bg-[#F8F9F6] px-4 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                    >

                        <option value="">Tous les statuts</option>

                        <option value="en_attente" <?= $statutFiltre === 'en_attente' ? 'selected' : '' ?>>
                            En attente
                        </option>

                        <option value="traite" <?= $statutFiltre === 'traite' ? 'selected' : '' ?>>
                            Traité
                        </option>

                        <option value="rejete" <?= $statutFiltre === 'rejete' ? 'selected' : '' ?>>
                            Rejeté
                        </option>

                    </select>

                </div>


                <!-- RECHERCHER -->
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-5 py-3.5 text-sm font-bold text-[#071B36] shadow-sm transition hover:bg-[#D7A200] focus:outline-none focus:ring-2 focus:ring-[#F5B800]/40 xl:w-auto"
                >

                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>

                    Rechercher

                </button>


                <!-- RÉINITIALISER -->
                <a
                    href="/immo-sn/moderateur/signalements"
                    class="inline-flex w-full items-center justify-center rounded-xl border border-[#DCE1E4] bg-[#F8F9F6] px-4 py-3.5 text-sm font-semibold text-[#526477] transition hover:border-[#D7A200] hover:bg-[#FFF7DF] xl:w-auto"
                    title="Réinitialiser les filtres"
                >

                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>

                    <span class="ml-2 xl:hidden">
                        Réinitialiser
                    </span>

                </a>

            </form>

        </section>


        <!-- =====================================================
             LISTE DES SIGNALEMENTS
        ====================================================== -->
        <section id="liste-signalements" class="min-w-0 space-y-5">

            <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                <div class="min-w-0">

                    <p class="break-words text-xs font-bold uppercase tracking-[0.18em] text-[#A98200]">
                        Gestion des dossiers
                    </p>

                    <h2 class="mt-1 break-words text-xl font-extrabold text-[#101D33]">
                        Liste des signalements
                    </h2>

                    <p class="mt-1 break-words text-sm text-[#71869A]">
                        Consultez les informations et gérez les actions de modération.
                    </p>

                </div>


                <div class="inline-flex w-fit max-w-full shrink-0 items-center gap-2 rounded-xl border border-[#E5D8B1] bg-[#FFFBEE] px-4 py-2.5 text-sm font-semibold text-[#7A5A00] shadow-sm">

                    <span class="h-2 w-2 shrink-0 rounded-full bg-[#D7A200]"></span>

                    <?= number_format($totalSignalements, 0, ',', ' ') ?>

                    résultat(s)

                </div>

            </div>


            <div class="min-w-0 overflow-hidden rounded-[24px] border border-[#E5E8DF] bg-white shadow-sm">


                <?php if (empty($signalements)): ?>

                    <!-- AUCUN SIGNALEMENT -->
                    <div class="px-4 py-12 text-center sm:px-6 sm:py-16">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-[#F5E8BF] bg-[#FFF4CC] text-[#8A6500]">

                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path d="M8 6h13M8 12h13M8 18h13"/>
                                <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>

                        </div>

                        <h3 class="mt-5 break-words text-lg font-bold text-[#101D33]">
                            Aucun signalement trouvé
                        </h3>

                        <p class="mx-auto mt-2 max-w-md break-words text-sm leading-6 text-[#71869A]">
                            Aucun signalement ne correspond à votre recherche.
                            Essayez de modifier vos filtres ou revenez plus tard.
                        </p>

                        <a
                            href="/immo-sn/moderateur/signalements"
                            class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-[#101D33] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1C3455] sm:w-auto"
                        >
                            Réinitialiser les filtres
                        </a>

                    </div>


                <?php else: ?>


                    <!-- TABLEAU -->
                    <div class="w-full overflow-x-auto">

                        <table class="w-full min-w-[950px] border-collapse text-left">

                            <thead class="bg-[#F3F5EF]">

                                <tr class="border-b border-[#E5E8DF]">

                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#526477]">
                                        Annonce
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#526477]">
                                        Déclarant
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#526477]">
                                        Motif
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#526477]">
                                        Statut
                                    </th>

                                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-[#526477]">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-[#EDF0E9]">

                                <?php foreach ($signalements as $signalement): ?>

                                    <?php

                                    $id = (string) ($signalement['id'] ?? '');
                                    $statut = (string) ($signalement['statut'] ?? '');
                                    $bienId = (string) ($signalement['bien_id'] ?? '');

                                    $declarant = trim(
                                        (string) ($signalement['declarant_prenom'] ?? '') . ' ' .
                                        (string) ($signalement['declarant_nom'] ?? '')
                                    );

                                    $proprietaire = trim(
                                        (string) ($signalement['proprietaire_prenom'] ?? '') . ' ' .
                                        (string) ($signalement['proprietaire_nom'] ?? '')
                                    );

                                    $statutLabel = match ($statut) {
                                        'en_attente' => 'En attente',
                                        'traite' => 'Traité',
                                        'rejete' => 'Rejeté',
                                        default => 'Inconnu',
                                    };

                                    $statutClass = match ($statut) {
                                        'en_attente' => 'border-amber-200 bg-amber-50 text-amber-700',
                                        'traite' => 'border-green-200 bg-green-50 text-green-700',
                                        'rejete' => 'border-red-200 bg-red-50 text-red-700',
                                        default => 'border-gray-200 bg-gray-50 text-gray-600',
                                    };

                                    ?>


                                    <tr class="transition hover:bg-[#FFFCF2]">


                                        <!-- ANNONCE -->
                                        <td class="px-6 py-5 align-top">

                                            <p class="max-w-[220px] break-words text-sm font-bold leading-5 text-[#101D33]">
                                                <?= $e($signalement['bien_titre'] ?? 'Annonce indisponible') ?>
                                            </p>

                                            <p class="mt-1 max-w-[220px] break-words text-xs text-[#71869A]">
                                                Propriétaire :
                                                <?= $e($proprietaire !== '' ? $proprietaire : 'Non renseigné') ?>
                                            </p>

                                            <?php if ($bienId !== ''): ?>

                                                <a
                                                    href="/immo-sn/biens/<?= rawurlencode($bienId) ?>"
                                                    class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-[#8A6500] transition hover:text-[#101D33]"
                                                >

                                                    Voir l'annonce

                                                    <span>→</span>

                                                </a>

                                            <?php endif; ?>

                                        </td>


                                        <!-- DÉCLARANT -->
                                        <td class="px-6 py-5 align-top">

                                            <p class="max-w-[190px] break-words text-sm font-semibold text-[#101D33]">
                                                <?= $e($declarant !== '' ? $declarant : 'Non renseigné') ?>
                                            </p>

                                            <p class="mt-1 max-w-[190px] break-all text-xs text-[#71869A]">
                                                <?= $e($signalement['declarant_email'] ?? '') ?>
                                            </p>

                                        </td>


                                        <!-- MOTIF -->
                                        <td class="px-6 py-5 align-top">

                                            <p
                                                class="max-w-[260px] break-words text-sm leading-6 text-[#526477]"
                                                style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"
                                            >
                                                <?= $e($signalement['motif'] ?? 'Aucun motif renseigné') ?>
                                            </p>

                                        </td>


                                        <!-- STATUT -->
                                        <td class="px-6 py-5 align-top">

                                            <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-full border px-3 py-1.5 text-xs font-bold <?= $statutClass ?>">

                                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>

                                                <?= $e($statutLabel) ?>

                                            </span>

                                        </td>


                                        <!-- ACTIONS -->
                                        <td class="px-6 py-5 align-top">

                                            <div class="flex min-w-[112px] flex-col items-end gap-2">


                                                <?php if ($statut === 'en_attente'): ?>


                                                    <!-- TRAITER -->
                                                    <form
                                                        method="POST"
                                                        action="/immo-sn/moderateur/signalements/<?= rawurlencode($id) ?>/traiter"
                                                        onsubmit="return confirm('Confirmer le traitement de ce signalement ?');"
                                                        class="w-full"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="_csrf_token"
                                                            value="<?= $e(Session::csrfToken()) ?>"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#F5B800] px-3 py-2 text-xs font-bold text-[#071B36] transition hover:bg-[#D7A200] focus:outline-none focus:ring-2 focus:ring-[#F5B800]/30"
                                                        >

                                                            <span>✓</span>

                                                            Traiter

                                                        </button>

                                                    </form>


                                                    <!-- REJETER -->
                                                    <form
                                                        method="POST"
                                                        action="/immo-sn/moderateur/signalements/<?= rawurlencode($id) ?>/rejeter"
                                                        onsubmit="return confirm('Confirmer le rejet de ce signalement ?');"
                                                        class="w-full"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="_csrf_token"
                                                            value="<?= $e(Session::csrfToken()) ?>"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-200"
                                                        >

                                                            <span>✕</span>

                                                            Rejeter

                                                        </button>

                                                    </form>


                                                <?php else: ?>


                                                    <!-- ARCHIVER -->
                                                    <form
                                                        method="POST"
                                                        action="/immo-sn/moderateur/signalements/<?= rawurlencode($id) ?>/supprimer"
                                                        onsubmit="return confirm('Confirmer l’archivage de ce signalement ?');"
                                                        class="w-full"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="_csrf_token"
                                                            value="<?= $e(Session::csrfToken()) ?>"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-[#E5D8B1] bg-[#FFFBEE] px-3 py-2 text-xs font-bold text-[#7A5A00] transition hover:border-[#D7A200] hover:bg-[#FFF4CC] focus:outline-none focus:ring-2 focus:ring-[#F5B800]/30"
                                                        >

                                                            <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M3 6h18"/>
                                                                <path d="M8 6V4h8v2"/>
                                                                <path d="m19 6-1 14H6L5 6"/>
                                                            </svg>

                                                            Archiver

                                                        </button>

                                                    </form>


                                                <?php endif; ?>


                                            </div>

                                        </td>

                                    </tr>


                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>


                <?php endif; ?>


            </div>


            <!-- =================================================
                 PAGINATION
            ================================================== -->
            <?php if ($totalPages > 1): ?>

                <div class="flex min-w-0 flex-col gap-3 rounded-2xl border border-[#E5E8DF] bg-white px-4 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:px-5">

                    <p class="break-words text-sm text-[#71869A]">

                        Page

                        <span class="font-bold text-[#101D33]">
                            <?= $page ?>
                        </span>

                        sur

                        <span class="font-bold text-[#101D33]">
                            <?= $totalPages ?>
                        </span>

                    </p>


                    <div class="flex min-w-0 flex-wrap items-center gap-2">


                        <?php if ($page > 1): ?>

                            <a
                                href="?<?= $e(http_build_query($parametres + ['page' => $page - 1])) ?>"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-[#DCE1E4] bg-white px-4 py-2.5 text-sm font-semibold text-[#526477] transition hover:border-[#D7A200] hover:bg-[#FFFBEE] sm:flex-none"
                            >

                                <span>←</span>

                                Précédent

                            </a>

                        <?php else: ?>

                            <span class="inline-flex flex-1 cursor-not-allowed items-center justify-center gap-2 rounded-xl border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-300 sm:flex-none">

                                <span>←</span>

                                Précédent

                            </span>

                        <?php endif; ?>


                        <?php if ($page < $totalPages): ?>

                            <a
                                href="?<?= $e(http_build_query($parametres + ['page' => $page + 1])) ?>"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#101D33] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1C3455] sm:flex-none"
                            >

                                Suivant

                                <span>→</span>

                            </a>

                        <?php else: ?>

                            <span class="inline-flex flex-1 cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-300 sm:flex-none">

                                Suivant

                                <span>→</span>

                            </span>

                        <?php endif; ?>


                    </div>

                </div>

            <?php endif; ?>


        </section>


        <!-- =====================================================
             PIED DE PAGE
        ====================================================== -->
        <footer class="border-t border-[#DFE4DA] py-6 text-center">

            <p class="break-words text-xs font-medium text-[#71869A]">
                IMMO-SN — Espace de modération
            </p>

        </footer>


    </div>

</div>
