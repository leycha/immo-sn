<?php

use Core\Session;

$decisions = is_array($decisions ?? null) ? $decisions : [];
$filtres = is_array($filtres ?? null) ? $filtres : [];

$total = (int) ($total ?? 0);
$page = max(1, (int) ($page ?? 1));
$parPage = max(1, (int) ($parPage ?? 10));
$totalPages = max(1, (int) ($totalPages ?? 1));

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$libellesActions = [
    'annonce_approuvee' => 'Annonce approuvée',
    'annonce_rejetee' => 'Annonce rejetée',
    'signalement_traite' => 'Signalement traité',
    'signalement_rejete' => 'Signalement rejeté',
    'signalement_archive' => 'Signalement archivé',
];

$classesActions = [
    'annonce_approuvee' => 'border-green-200 bg-green-50 text-green-700',
    'annonce_rejetee' => 'border-red-200 bg-red-50 text-red-700',
    'signalement_traite' => 'border-blue-200 bg-blue-50 text-blue-700',
    'signalement_rejete' => 'border-red-200 bg-red-50 text-red-700',
    'signalement_archive' => 'border-gray-200 bg-gray-50 text-gray-700',
];

$decoderDetails = static function ($details): array {
    if (is_array($details)) {
        return $details;
    }

    if (!is_string($details) || trim($details) === '') {
        return [];
    }

    $resultat = json_decode($details, true);

    return is_array($resultat) ? $resultat : [];
};

$formaterDetails = static function (array $details) use ($e): string {
    $elements = [];

    foreach ($details as $cle => $valeur) {
        if (is_array($valeur) || is_object($valeur)) {
            $valeur = json_encode(
                $valeur,
                JSON_UNESCAPED_UNICODE
            );
        }

        $libelle = match ((string) $cle) {
            'type' => 'Type',
            'cible_id' => 'Identifiant',
            'motif' => 'Motif',
            default => ucfirst(str_replace('_', ' ', (string) $cle)),
        };

        $elements[] = '<span class="font-semibold text-[#101D33]">'
            . $e($libelle)
            . ' :</span> '
            . $e($valeur);
    }

    return implode('<br>', $elements);
};

$debut = $total > 0 ? (($page - 1) * $parPage) + 1 : 0;
$fin = min($page * $parPage, $total);

$parametres = [
    'recherche' => $filtres['recherche'] ?? '',
    'action' => $filtres['action'] ?? '',
    'date_debut' => $filtres['date_debut'] ?? '',
    'date_fin' => $filtres['date_fin'] ?? '',
];

?>

<div class="min-h-screen bg-[#F5F3ED] px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-7xl space-y-7">

        <!-- =====================================================
             EN-TÊTE
        ====================================================== -->
        <header class="flex flex-col gap-4 rounded-2xl border border-[#E5E7DE] bg-white px-5 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-[#71869A]">
                    <span class="text-[#7E9833]">IMMO-SN</span>
                    <span>/</span>
                    <span>Administration</span>
                    <span>/</span>
                    <span class="text-[#101D33]">Historique des décisions</span>
                </div>

                <p class="mt-1 text-sm text-[#71869A]">
                    Espace de modération
                </p>
            </div>

            <a
                href="/immo-sn/moderateur"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#101D33] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1B2D4A] sm:w-auto"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"/>
                    <path d="M9 12h12"/>
                </svg>
                Tableau de bord
            </a>

        </header>


        <!-- =====================================================
             BANNIÈRE PRINCIPALE
        ====================================================== -->
        <section class="relative isolate overflow-hidden rounded-[28px] bg-[#101D33] px-5 py-8 text-white shadow-xl shadow-[#101D33]/10 sm:px-9 sm:py-10 lg:px-12 lg:py-12">

            <!-- Décorations -->
            <div class="pointer-events-none absolute -right-16 -top-20 h-72 w-72 rounded-full border-[35px] border-white/5"></div>

            <div class="pointer-events-none absolute -bottom-28 right-40 h-64 w-64 rounded-full bg-[#7E9833]/20 blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center justify-between gap-8 lg:flex-row">

                <div class="w-full min-w-0 max-w-2xl">

                    <span class="inline-flex items-center gap-2 rounded-full border border-[#7E9833]/30 bg-[#7E9833]/15 px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#B8D47B]">

                        <span class="h-2 w-2 rounded-full bg-[#8FAF35]"></span>

                        Suivi de la modération

                    </span>

                    <h1 class="mt-6 break-words text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl lg:text-5xl">

                        Historique des décisions<span class="text-[#A9C76A]">.</span>

                    </h1>

                    <p class="mt-5 max-w-xl text-sm leading-7 text-white/70 sm:text-base">
                        Retrouvez les décisions prises par les modérateurs,
                        consultez les détails des interventions et suivez
                        les différentes actions effectuées sur la plateforme.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">

                        <a
                            href="#journal-decisions"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#8FAF35] sm:w-auto"
                        >
                            Consulter l'historique

                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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


                <!-- Illustration -->
                <div class="hidden h-56 w-56 shrink-0 items-center justify-center rounded-[32px] border border-white/10 bg-white/5 lg:flex">

                    <svg class="h-32 w-32 text-[#A9C76A]" viewBox="0 0 120 120" fill="none">
                        <rect x="24" y="17" width="72" height="88" rx="8" stroke="currentColor" stroke-width="4"/>
                        <path d="M42 39h36M42 54h36M42 69h20" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="78" cy="83" r="17" fill="#101D33" stroke="currentColor" stroke-width="4"/>
                        <path d="m70 83 5 5 11-11" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                </div>

            </div>

        </section>


        <!-- =====================================================
             STATISTIQUES
        ====================================================== -->
        <section>

            <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#7E9833]">
                        Vue d'ensemble
                    </p>

                    <h2 class="mt-1 text-xl font-extrabold text-[#101D33]">
                        Activité de modération
                    </h2>
                </div>

                <p class="text-sm text-[#71869A]">
                    Synthèse des résultats de votre recherche
                </p>

            </div>


            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <!-- TOTAL -->
                <div class="rounded-2xl border border-[#E5E7DE] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#EEF3E3] text-[#607A27]">

                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M8 6h13M8 12h13M8 18h13"/>
                                <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>

                        </div>

                        <span class="rounded-full bg-[#EEF3E3] px-3 py-1 text-xs font-semibold text-[#607A27]">
                            Global
                        </span>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-[#101D33]">
                        <?= number_format($total, 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#101D33]">
                        Total des décisions
                    </p>

                    <p class="mt-1 text-xs text-[#71869A]">
                        Résultats correspondant aux filtres
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-[#7E9833]"></div>

                </div>


                <!-- AFFICHÉES -->
                <div class="rounded-2xl border border-[#E5E7DE] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>

                        </div>

                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            Cette page
                        </span>

                    </div>

                    <p class="mt-5 text-3xl font-extrabold text-[#101D33]">
                        <?= number_format(count($decisions), 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#101D33]">
                        Décisions affichées
                    </p>

                    <p class="mt-1 text-xs text-[#71869A]">
                        Nombre de décisions sur cette page
                    </p>

                    <div class="mt-5 h-1.5 rounded-full bg-blue-500"></div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             FILTRES
        ====================================================== -->
        <section class="rounded-[24px] border border-[#E5E7DE] bg-white p-5 shadow-sm sm:p-7">

            <div class="mb-6">

                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#7E9833]">
                    Recherche avancée
                </p>

                <h2 class="mt-1 text-xl font-extrabold text-[#101D33]">
                    Filtrer les décisions
                </h2>

                <p class="mt-1 text-sm text-[#71869A]">
                    Retrouvez une intervention selon son type, son contenu ou sa date.
                </p>

            </div>


            <form
                method="GET"
                action="/immo-sn/moderateur/historique"
                class="grid grid-cols-1 items-end gap-4 md:grid-cols-2 xl:grid-cols-4"
            >

                <!-- Recherche -->
                <div>

                    <label for="recherche" class="mb-2 block text-sm font-semibold text-[#101D33]">
                        Recherche
                    </label>

                    <div class="relative">

                        <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#71869A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>

                        <input
                            type="search"
                            id="recherche"
                            name="recherche"
                            value="<?= $e($filtres['recherche'] ?? '') ?>"
                            placeholder="Modérateur, e-mail, détails..."
                            class="w-full rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] py-3.5 pl-12 pr-4 text-sm text-[#101D33] outline-none transition placeholder:text-[#8A98A6] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                        >

                    </div>

                </div>


                <!-- Type de décision -->
                <div>

                    <label for="action" class="mb-2 block text-sm font-semibold text-[#101D33]">
                        Type de décision
                    </label>

                    <select
                        id="action"
                        name="action"
                        class="w-full rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] px-4 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                    >

                        <option value="">Toutes les décisions</option>

                        <?php foreach ($libellesActions as $code => $libelle): ?>

                            <option
                                value="<?= $e($code) ?>"
                                <?= ($filtres['action'] ?? '') === $code ? 'selected' : '' ?>
                            >
                                <?= $e($libelle) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- Date début -->
                <div>

                    <label for="date_debut" class="mb-2 block text-sm font-semibold text-[#101D33]">
                        Date de début
                    </label>

                    <input
                        type="date"
                        id="date_debut"
                        name="date_debut"
                        value="<?= $e($filtres['date_debut'] ?? '') ?>"
                        class="w-full rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] px-4 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                    >

                </div>


                <!-- Date fin -->
                <div>

                    <label for="date_fin" class="mb-2 block text-sm font-semibold text-[#101D33]">
                        Date de fin
                    </label>

                    <input
                        type="date"
                        id="date_fin"
                        name="date_fin"
                        value="<?= $e($filtres['date_fin'] ?? '') ?>"
                        class="w-full rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] px-4 py-3.5 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                    >

                </div>


                <!-- Boutons -->
                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap md:col-span-2 xl:col-span-4">

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#101D33] px-5 py-3.5 text-sm font-bold text-white transition hover:bg-[#1B2D4A] sm:w-auto"
                    >

                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>

                        Rechercher

                    </button>


                    <a
                        href="/immo-sn/moderateur/historique"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#E5E7DE] bg-[#F5F3ED] px-5 py-3.5 text-sm font-semibold text-[#101D33] transition hover:bg-[#EEF0E9] sm:w-auto"
                    >

                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>

                        Réinitialiser

                    </a>

                </div>

            </form>

        </section>


        <!-- =====================================================
             JOURNAL DES DÉCISIONS
        ====================================================== -->
        <section id="journal-decisions" class="space-y-5">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#7E9833]">
                        Traçabilité
                    </p>

                    <h2 class="mt-1 text-xl font-extrabold text-[#101D33]">
                        Journal des décisions
                    </h2>

                    <p class="mt-1 text-sm text-[#71869A]">
                        Consultez les opérations effectuées dans l'espace de modération.
                    </p>

                </div>


                <div class="inline-flex w-fit items-center gap-2 rounded-xl border border-[#E5E7DE] bg-white px-4 py-2.5 text-sm font-semibold text-[#607A27] shadow-sm">

                    <span class="h-2 w-2 rounded-full bg-[#7E9833]"></span>

                    <?= number_format($total, 0, ',', ' ') ?>

                    décision(s)

                </div>

            </div>


            <!-- Conteneur du tableau -->
            <div class="overflow-hidden rounded-[24px] border border-[#E5E7DE] bg-white shadow-sm">

                <?php if (empty($decisions)): ?>

                    <!-- Aucun résultat -->
                    <div class="px-6 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EEF3E3] text-[#7E9833]">

                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path d="M8 6h13M8 12h13M8 18h13"/>
                                <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>

                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#101D33]">
                            Aucun historique disponible
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#71869A]">
                            Aucune décision ne correspond actuellement à vos critères.
                            Les prochaines décisions enregistrées apparaîtront ici.
                        </p>

                        <a
                            href="/immo-sn/moderateur/historique"
                            class="mt-6 inline-flex items-center justify-center rounded-xl bg-[#101D33] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1B2D4A]"
                        >
                            Réinitialiser les filtres
                        </a>

                    </div>

                <?php else: ?>

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1000px] border-collapse text-left">

                            <thead class="bg-[#F5F3ED]">

                                <tr class="border-b border-[#E5E7DE]">

                                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-[#71869A]">
                                        Date
                                    </th>

                                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-[#71869A]">
                                        Modérateur
                                    </th>

                                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-[#71869A]">
                                        Décision
                                    </th>

                                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-[#71869A]">
                                        Détails
                                    </th>

                                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-[#71869A]">
                                        Adresse IP
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-[#EEF0E9]">

                                <?php foreach ($decisions as $decision): ?>

                                    <?php

                                    $action = (string) ($decision['action'] ?? '');

                                    $details = $decoderDetails(
                                        $decision['details'] ?? null
                                    );

                                    $moderateur = trim(
                                        (string) ($decision['moderateur_prenom'] ?? '')
                                        . ' '
                                        . (string) ($decision['moderateur_nom'] ?? '')
                                    );

                                    $date = $decision['created_at'] ?? null;

                                    $dateFormatee = 'Date inconnue';

                                    if (!empty($date)) {
                                        try {
                                            $dateFormatee = (new DateTimeImmutable(
                                                (string) $date
                                            ))->format('d/m/Y à H:i');
                                        } catch (Throwable $exception) {
                                            $dateFormatee = (string) $date;
                                        }
                                    }

                                    $libelleAction = $libellesActions[$action]
                                        ?? ucfirst(str_replace('_', ' ', $action));

                                    $classeAction = $classesActions[$action]
                                        ?? 'border-gray-200 bg-gray-50 text-gray-700';

                                    ?>

                                    <tr class="align-top transition hover:bg-[#FAFBF8]">

                                        <!-- DATE -->
                                        <td class="whitespace-nowrap px-5 py-5">

                                            <p class="text-sm font-bold text-[#101D33]">
                                                <?= $e($dateFormatee) ?>
                                            </p>

                                        </td>


                                        <!-- MODÉRATEUR -->
                                        <td class="px-5 py-5">

                                            <p class="text-sm font-semibold text-[#101D33]">
                                                <?= $e($moderateur !== '' ? $moderateur : 'Modérateur inconnu') ?>
                                            </p>

                                            <p class="mt-1 max-w-[190px] break-all text-xs text-[#71869A]">
                                                <?= $e($decision['moderateur_email'] ?? '') ?>
                                            </p>

                                            <span class="mt-2 inline-flex rounded-full border border-[#E5E7DE] bg-[#F5F3ED] px-2.5 py-1 text-[11px] font-semibold text-[#607A27]">
                                                <?= $e($decision['moderateur_role'] ?? 'moderateur') ?>
                                            </span>

                                        </td>


                                        <!-- DÉCISION -->
                                        <td class="px-5 py-5">

                                            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-bold <?= $classeAction ?>">

                                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>

                                                <?= $e($libelleAction) ?>

                                            </span>

                                            <p class="mt-2 text-xs text-[#8A98A6]">
                                                <?= $e($action) ?>
                                            </p>

                                        </td>


                                        <!-- DÉTAILS -->
                                        <td class="max-w-sm px-5 py-5">

                                            <?php if (!empty($details)): ?>

                                                <div class="space-y-1 break-words text-xs leading-6 text-[#71869A]">
                                                    <?= $formaterDetails($details) ?>
                                                </div>

                                            <?php else: ?>

                                                <span class="text-xs italic text-[#8A98A6]">
                                                    Aucun détail renseigné
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <!-- ADRESSE IP -->
                                        <td class="whitespace-nowrap px-5 py-5">

                                            <span class="inline-flex rounded-lg border border-[#E5E7DE] bg-[#F5F3ED] px-3 py-2 font-mono text-xs text-[#71869A]">
                                                <?= $e($decision['ip_address'] ?? 'Non disponible') ?>
                                            </span>

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

                <div class="flex flex-col gap-3 rounded-2xl border border-[#E5E7DE] bg-white px-5 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-sm text-[#71869A]">

                        Affichage de

                        <strong class="text-[#101D33]"><?= $debut ?></strong>

                        à

                        <strong class="text-[#101D33]"><?= $fin ?></strong>

                        sur

                        <strong class="text-[#101D33]"><?= $total ?></strong>

                        décision(s).

                    </p>


                    <div class="flex flex-wrap items-center gap-2">

                        <?php if ($page > 1): ?>

                            <a
                                href="?<?= $e(http_build_query($parametres + ['page' => $page - 1])) ?>"
                                class="inline-flex items-center gap-2 rounded-xl border border-[#E5E7DE] bg-white px-4 py-2.5 text-sm font-semibold text-[#101D33] transition hover:bg-[#EEF3E3]"
                            >
                                &larr; Précédent
                            </a>

                        <?php else: ?>

                            <span class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-300">
                                &larr; Précédent
                            </span>

                        <?php endif; ?>


                        <span class="rounded-xl bg-[#101D33] px-4 py-2.5 text-sm font-bold text-white">
                            <?= $page ?> / <?= $totalPages ?>
                        </span>


                        <?php if ($page < $totalPages): ?>

                            <a
                                href="?<?= $e(http_build_query($parametres + ['page' => $page + 1])) ?>"
                                class="inline-flex items-center gap-2 rounded-xl bg-[#101D33] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1B2D4A]"
                            >
                                Suivant &rarr;
                            </a>

                        <?php else: ?>

                            <span class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-300">
                                Suivant &rarr;
                            </span>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endif; ?>

        </section>


        <!-- =====================================================
             PIED DE PAGE
        ====================================================== -->
        <footer class="border-t border-[#E5E7DE] py-6 text-center">

            <p class="text-xs font-medium text-[#71869A]">
                IMMO-SN — Espace de modération
            </p>

        </footer>

    </div>

</div>