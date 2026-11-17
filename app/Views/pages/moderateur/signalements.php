
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

<div class="min-h-screen bg-[#faf7f2] px-3 py-6 sm:px-6 sm:py-8 lg:px-8">

    <div class="mx-auto max-w-7xl space-y-7">

        <!-- EN-TÊTE -->
        <header class="flex min-w-0 flex-col gap-4 rounded-2xl border border-[#eee7de] bg-white px-4 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:px-5">

            <div class="min-w-0">

                <div class="flex min-w-0 flex-wrap items-center gap-2 text-xs font-semibold text-[#a98260]">
                    <span>IMMO-SN</span>
                    <span class="text-gray-300">/</span>
                    <span>Administration</span>
                    <span class="text-gray-300">/</span>
                    <span class="break-words text-[#30221b]">Gestion des signalements</span>
                </div>

                <p class="mt-1 break-words text-sm text-gray-500">
                    Espace de modération
                </p>

            </div>

            <a
                href="/immo-sn/moderateur"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#493629] sm:w-auto sm:shrink-0"
            >
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"/>
                    <path d="M9 12h12"/>
                </svg>
                Tableau de bord
            </a>

        </header>

        <!-- BANNIÈRE PRINCIPALE -->
        <section class="relative isolate overflow-hidden rounded-[28px] bg-[#30221b] px-4 py-7 text-white shadow-xl sm:px-9 sm:py-10 lg:px-12 lg:py-12">

            <div class="pointer-events-none absolute -right-16 -top-20 h-72 w-72 rounded-full border-[35px] border-white/5"></div>
            <div class="pointer-events-none absolute -bottom-28 right-40 h-64 w-64 rounded-full bg-[#f7b500]/10"></div>

            <div class="relative z-10 flex min-w-0 flex-col justify-between gap-8 lg:flex-row lg:items-center">

                <div class="w-full min-w-0 max-w-2xl">

                    <span class="inline-flex max-w-full flex-wrap items-center gap-2 rounded-full bg-[#f7b500] px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#30221b]">
                        <span class="h-2 w-2 shrink-0 rounded-full bg-[#30221b]"></span>
                        Modération IMMO-SN
                    </span>

                    <h1 class="mt-6 break-words text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl lg:text-5xl">
                        Gestion des signalements<span class="text-[#f7b500]">.</span>
                    </h1>

                    <p class="mt-5 max-w-xl break-words text-sm leading-7 text-white/70 sm:text-base">
                        Consultez les signalements envoyés par les utilisateurs,
                        examinez les annonces concernées et prenez les mesures
                        nécessaires pour garantir la fiabilité de la plateforme.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">

                        <a
                            href="#liste-signalements"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-bold text-[#30221b] transition hover:bg-[#ffca38] sm:w-auto"
                        >
                            Consulter les signalements
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 17 17 7"/>
                                <path d="M7 7h10v10"/>
                            </svg>
                        </a>

                        <a
                            href="/immo-sn/moderateur"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20 sm:w-auto"
                        >
                            Tableau de bord
                        </a>

                    </div>

                </div>

                <div class="hidden h-56 w-56 shrink-0 items-center justify-center rounded-[32px] border border-white/10 bg-white/5 lg:flex">
                    <svg class="h-32 w-32 text-[#f7b500]" viewBox="0 0 120 120" fill="none">
                        <path d="M35 17h36l19 19v64a5 5 0 0 1-5 5H35a5 5 0 0 1-5-5V22a5 5 0 0 1 5-5Z" stroke="currentColor" stroke-width="4"/>
                        <path d="M71 17v20h19" stroke="currentColor" stroke-width="4" stroke-linejoin="round"/>
                        <path d="M43 52h34M43 64h34" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="76" cy="83" r="20" fill="#30221b" stroke="currentColor" stroke-width="4"/>
                        <path d="M76 72v13M76 91v1" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </div>

            </div>
        </section>

        <!-- MESSAGE -->
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

        <!-- STATISTIQUES -->
        <section class="min-w-0">

            <div class="mb-4 flex min-w-0 flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">

                <div class="min-w-0">
                    <p class="break-words text-xs font-bold uppercase tracking-[0.18em] text-[#a98260]">
                        Vue d'ensemble
                    </p>

                    <h2 class="mt-1 break-words text-xl font-extrabold text-[#30221b]">
                        Statistiques des signalements
                    </h2>
                </div>

                <p class="break-words text-sm text-gray-500 sm:shrink-0">
                    Suivi global de la modération
                </p>

            </div>

            <div class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <!-- TOTAL -->
                <div class="min-w-0 rounded-2xl border border-[#eee7de] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

                    <div class="flex min-w-0 items-center justify-between gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#f7b500]/15 text-[#a98260]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M8 6h13M8 12h13M8 18h13"/>
                                <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                        </div>

                        <span class="shrink-0 rounded-full bg-[#faf7f2] px-3 py-1 text-xs font-semibold text-[#a98260]">
                            Global
                        </span>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-[#30221b]">
                        <?= number_format((int) $statsSignalements['total'], 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-gray-700">
                        Total des signalements
                    </p>

                    <p class="mt-1 break-words text-xs text-gray-400">
                        Tous les signalements reçus
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-[#f7b500]"></div>

                </div>

                <!-- EN ATTENTE -->
                <div class="min-w-0 rounded-2xl border border-[#eee7de] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

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

                    <p class="mt-5 text-3xl font-extrabold text-[#30221b]">
                        <?= number_format((int) $statsSignalements['en_attente'], 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-gray-700">
                        En attente
                    </p>

                    <p class="mt-1 break-words text-xs text-gray-400">
                        Signalements à examiner
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-amber-400"></div>

                </div>

                <!-- TRAITÉS -->
                <div class="min-w-0 rounded-2xl border border-[#eee7de] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

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

                    <p class="mt-5 text-3xl font-extrabold text-[#30221b]">
                        <?= number_format((int) $statsSignalements['traites'], 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-gray-700">
                        Traités
                    </p>

                    <p class="mt-1 break-words text-xs text-gray-400">
                        Signalements pris en charge
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-green-500"></div>

                </div>

                <!-- REJETÉS -->
                <div class="min-w-0 rounded-2xl border border-[#eee7de] bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-5">

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

                    <p class="mt-5 text-3xl font-extrabold text-[#30221b]">
                        <?= number_format((int) $statsSignalements['rejetes'], 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 break-words text-sm font-semibold text-gray-700">
                        Rejetés
                    </p>

                    <p class="mt-1 break-words text-xs text-gray-400">
                        Signalements non retenus
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-red-500"></div>

                </div>

            </div>
        </section>

        <!-- RECHERCHE ET FILTRES -->
        <section class="min-w-0 rounded-[24px] border border-[#eee7de] bg-white p-4 shadow-sm sm:p-7">

            <div class="mb-6 min-w-0">

                <p class="break-words text-xs font-bold uppercase tracking-[0.18em] text-[#a98260]">
                    Recherche avancée
                </p>

                <h2 class="mt-1 break-words text-xl font-extrabold text-[#30221b]">
                    Retrouver un signalement
                </h2>

                <p class="mt-1 break-words text-sm text-gray-500">
                    Recherchez par annonce ou filtrez les signalements selon leur statut.
                </p>

            </div>

            <form
                method="GET"
                action="/immo-sn/moderateur/signalements"
                class="grid min-w-0 grid-cols-1 items-end gap-4 xl:grid-cols-[minmax(220px,1fr)_240px_auto_auto]"
            >

                <div class="min-w-0">
                    <label for="q" class="mb-2 block text-sm font-semibold text-[#30221b]">
                        Recherche
                    </label>

                    <div class="relative min-w-0">

                        <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#a98260]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>

                        <input
                            id="q"
                            type="text"
                            name="q"
                            value="<?= $e($recherche) ?>"
                            placeholder="Titre d'annonce, motif..."
                            class="w-full min-w-0 rounded-xl border border-[#e9e1d8] bg-[#faf7f2]/60 py-3.5 pl-12 pr-4 text-sm text-[#30221b] outline-none transition placeholder:text-gray-400 focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                        >

                    </div>
                </div>

                <div class="min-w-0">
                    <label for="statut" class="mb-2 block text-sm font-semibold text-[#30221b]">
                        Statut
                    </label>

                    <select
                        id="statut"
                        name="statut"
                        class="w-full min-w-0 rounded-xl border border-[#e9e1d8] bg-[#faf7f2]/60 px-4 py-3.5 text-sm text-[#30221b] outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
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

                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 py-3.5 text-sm font-bold text-white transition hover:bg-[#493629] xl:w-auto"
                >
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>
                    Rechercher
                </button>

                <a
                    href="/immo-sn/moderateur/signalements"
                    class="inline-flex w-full items-center justify-center rounded-xl border border-[#e9e1d8] bg-[#faf7f2] px-4 py-3.5 text-sm font-semibold text-[#6b5748] transition hover:bg-[#f0e9df] xl:w-auto"
                    title="Réinitialiser les filtres"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                    <span class="ml-2 xl:hidden">Réinitialiser</span>
                </a>

            </form>
        </section>

        <!-- LISTE DES SIGNALEMENTS -->
        <section id="liste-signalements" class="min-w-0 space-y-5">

            <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                <div class="min-w-0">

                    <p class="break-words text-xs font-bold uppercase tracking-[0.18em] text-[#a98260]">
                        Gestion des dossiers
                    </p>

                    <h2 class="mt-1 break-words text-xl font-extrabold text-[#30221b]">
                        Liste des signalements
                    </h2>

                    <p class="mt-1 break-words text-sm text-gray-500">
                        Consultez les informations et gérez les actions de modération.
                    </p>

                </div>

                <div class="inline-flex w-fit max-w-full shrink-0 items-center gap-2 rounded-xl border border-[#eee7de] bg-white px-4 py-2.5 text-sm font-semibold text-[#6b5748] shadow-sm">
                    <span class="h-2 w-2 shrink-0 rounded-full bg-[#f7b500]"></span>
                    <?= number_format($totalSignalements, 0, ',', ' ') ?>
                    résultat(s)
                </div>

            </div>

            <div class="min-w-0 overflow-hidden rounded-[24px] border border-[#eee7de] bg-white shadow-sm">

                <?php if (empty($signalements)): ?>

                    <div class="px-4 py-12 text-center sm:px-6 sm:py-16">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#faf7f2] text-[#a98260]">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path d="M8 6h13M8 12h13M8 18h13"/>
                                <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 break-words text-lg font-bold text-[#30221b]">
                            Aucun signalement trouvé
                        </h3>

                        <p class="mx-auto mt-2 max-w-md break-words text-sm leading-6 text-gray-500">
                            Aucun signalement ne correspond à votre recherche.
                            Essayez de modifier vos filtres ou revenez plus tard.
                        </p>

                        <a
                            href="/immo-sn/moderateur/signalements"
                            class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-[#30221b] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#493629] sm:w-auto"
                        >
                            Réinitialiser les filtres
                        </a>

                    </div>

                <?php else: ?>

                    <div class="w-full overflow-x-auto">

                        <table class="w-full min-w-[950px] border-collapse text-left">

                            <thead class="bg-[#faf7f2]">

                                <tr class="border-b border-[#eee7de]">

                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#806c5c]">
                                        Annonce
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#806c5c]">
                                        Déclarant
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#806c5c]">
                                        Motif
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#806c5c]">
                                        Statut
                                    </th>

                                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-[#806c5c]">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-[#f0ebe5]">

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

                                    <tr class="transition hover:bg-[#faf7f2]/70">

                                        <!-- ANNONCE -->
                                        <td class="px-6 py-5 align-top">

                                            <p class="max-w-[220px] break-words text-sm font-bold leading-5 text-[#30221b]">
                                                <?= $e($signalement['bien_titre'] ?? 'Annonce indisponible') ?>
                                            </p>

                                            <p class="mt-1 max-w-[220px] break-words text-xs text-gray-500">
                                                Propriétaire :
                                                <?= $e($proprietaire !== '' ? $proprietaire : 'Non renseigné') ?>
                                            </p>

                                            <?php if ($bienId !== ''): ?>

                                                <a
                                                    href="/immo-sn/biens/<?= rawurlencode($bienId) ?>"
                                                    class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-[#a98260] transition hover:text-[#30221b]"
                                                >
                                                    Voir l'annonce
                                                    <span>→</span>
                                                </a>

                                            <?php endif; ?>

                                        </td>

                                        <!-- DECLARANT -->
                                        <td class="px-6 py-5 align-top">

                                            <p class="max-w-[190px] break-words text-sm font-semibold text-[#30221b]">
                                                <?= $e($declarant !== '' ? $declarant : 'Non renseigné') ?>
                                            </p>

                                            <p class="mt-1 max-w-[190px] break-all text-xs text-gray-500">
                                                <?= $e($signalement['declarant_email'] ?? '') ?>
                                            </p>

                                        </td>

                                        <!-- MOTIF -->
                                        <td class="px-6 py-5 align-top">

                                            <p class="max-w-[260px] break-words text-sm leading-6 text-gray-600" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
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
                                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-green-700"
                                                        >
                                                            <span>✓</span>
                                                            Traiter
                                                        </button>

                                                    </form>

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
                                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50"
                                                        >
                                                            <span>✕</span>
                                                            Rejeter
                                                        </button>

                                                    </form>

                                                <?php else: ?>

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
                                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-[#e9e1d8] bg-[#faf7f2] px-3 py-2 text-xs font-bold text-[#6b5748] transition hover:bg-[#f0e9df]"
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

            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>

                <div class="flex min-w-0 flex-col gap-3 rounded-2xl border border-[#eee7de] bg-white px-4 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:px-5">

                    <p class="break-words text-sm text-gray-500">
                        Page
                        <span class="font-bold text-[#30221b]"><?= $page ?></span>
                        sur
                        <span class="font-bold text-[#30221b]"><?= $totalPages ?></span>
                    </p>

                    <div class="flex min-w-0 flex-wrap items-center gap-2">

                        <?php if ($page > 1): ?>

                            <a
                                href="?<?= $e(http_build_query($parametres + ['page' => $page - 1])) ?>"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-[#e9e1d8] bg-white px-4 py-2.5 text-sm font-semibold text-[#6b5748] transition hover:bg-[#faf7f2] sm:flex-none"
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
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#30221b] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#493629] sm:flex-none"
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

        <!-- PIED DE PAGE -->
        <footer class="border-t border-[#e9e1d8] py-6 text-center">

            <p class="break-words text-xs font-medium text-gray-400">
                IMMO-SN — Espace de modération
            </p>

        </footer>

    </div>
</div>
