
<?php

$cronLogs = is_array($cronLogs ?? null) ? $cronLogs : [];

$statsCron = is_array($statsCron ?? null) ? $statsCron : [
    'total' => 0,
    'succes' => 0,
    'erreurs' => 0,
];

$erreurCron = $erreurCron ?? null;

/**
 * Valeurs des filtres et de la pagination transmises
 * par AdminController::cronMonitor().
 */
$recherche = trim((string) ($recherche ?? ''));
$statut = (string) ($statut ?? '');
$dateDebut = (string) ($dateDebut ?? '');
$dateFin = (string) ($dateFin ?? '');

$page = max(1, (int) ($page ?? 1));
$parPage = (int) ($parPage ?? 10);
$totalLogs = max(0, (int) ($totalLogs ?? 0));
$totalPages = max(1, (int) ($totalPages ?? 1));

$parPage = in_array($parPage, [10, 20, 50], true)
    ? $parPage
    : 10;


/**
 * Sécuriser les valeurs affichées dans le HTML.
 */
$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
};

/**
 * Normaliser le statut enregistré en base.
 */
$normaliserStatut = static function ($statut): string {
    $statut = mb_strtolower(
        trim((string) $statut),
        'UTF-8'
    );

    if (in_array($statut, ['success', 'succes', 'succès'], true)) {
        return 'success';
    }

    if (in_array($statut, ['error', 'erreur', 'failed', 'failure'], true)) {
        return 'error';
    }

    if (in_array($statut, ['running', 'en_cours', 'en cours'], true)) {
        return 'running';
    }

    return 'unknown';
};

/**
 * Préparer l'affichage d'un statut.
 */
$afficherStatut = static function ($statut) use ($normaliserStatut): array {
    $statutNormalise = $normaliserStatut($statut);

    switch ($statutNormalise) {
        case 'success':
            return [
                'label' => 'Succès',
                'classe' => 'bg-[#edf5ef] text-[#497657]',
                'point' => 'bg-[#5caa70]',
            ];

        case 'error':
            return [
                'label' => 'Erreur',
                'classe' => 'bg-red-50 text-red-700',
                'point' => 'bg-red-500',
            ];

        case 'running':
            return [
                'label' => 'En cours',
                'classe' => 'bg-[#fff4cf] text-[#9a7000]',
                'point' => 'bg-[#f7b500]',
            ];

        default:
            return [
                'label' => 'Inconnu',
                'classe' => 'bg-gray-100 text-gray-700',
                'point' => 'bg-gray-400',
            ];
    }
};

/**
 * Convertir la date en format lisible.
 */
$formaterDate = static function ($date): string {
    if (empty($date)) {
        return 'Date indisponible';
    }

    try {
        $dateTime = new DateTime((string) $date);

        return $dateTime->format('d/m/Y à H:i:s');
    } catch (\Throwable $e) {
        return 'Date invalide';
    }
};

/**
 * Description indicative des scripts connus.
 * Les scripts inconnus restent affichés normalement.
 */
$descriptions = [
    'expire-biens.php' => 'Expiration automatique des annonces immobilières',
    'daily-stats.php' => 'Mise à jour des statistiques',
    'send-alertes.php' => 'Envoi des alertes immobilières',
    'inactivite.php' => 'Gestion des comptes inactifs',
    'clean-uploads.php' => 'Nettoyage des fichiers temporaires',
    'archive-logs.php' => 'Archivage des journaux',
];

$totalExecutions = (int) ($statsCron['total'] ?? 0);
$totalSucces = (int) ($statsCron['succes'] ?? 0);
$totalErreurs = (int) ($statsCron['erreurs'] ?? 0);

$etatGlobal = $erreurCron !== null
    ? 'Indisponible'
    : ($totalErreurs > 0 ? 'À vérifier' : 'Surveillance active');

$classeEtatGlobal = $erreurCron !== null || $totalErreurs > 0
    ? 'text-[#fca5a5]'
    : 'text-white';

?>

<div class="min-h-screen bg-[#faf7f2]">

    <!-- HEADER -->
    <header class="sticky top-0 z-20 border-b border-[#e8ded4] bg-[#faf7f2]/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-[#a98260]">
                        <span>Administration</span>
                        <span class="text-[#cbbcad]">/</span>
                        <span class="text-[#8d7a6b]">Cron Monitor</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#30221b] sm:text-3xl">
                        Cron Monitor
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#30221b] text-sm font-bold text-[#f7b500] shadow-sm">
                        A
                    </div>

                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-[#30221b]">
                            Administrateur
                        </p>
                        <p class="text-xs text-[#9a887a]">
                            Espace administration
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

            <!-- HERO -->
            <section class="relative overflow-hidden rounded-[26px] bg-[#30221b] shadow-[0_18px_50px_rgba(48,34,27,0.14)]">

                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#a98260]/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-7 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between">

                    <div class="max-w-2xl">

                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full <?= $erreurCron !== null || $totalErreurs > 0 ? 'bg-red-400' : 'bg-[#6fd28b]' ?>"></span>

                            <span class="text-xs font-medium text-[#ded3ca]">
                                <?= $e($etatGlobal) ?>
                            </span>
                        </div>

                        <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                            Surveillance des tâches
                            <span class="text-[#f7b500]">automatiques</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-[#cfc3ba] sm:text-base">
                            Consultez les exécutions réellement enregistrées,
                            leurs résultats, leur durée et les éventuelles erreurs.
                        </p>

                    </div>

                    <div class="shrink-0 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm lg:min-w-[260px]">

                        <p class="text-xs uppercase tracking-[0.15em] text-[#bcaea3]">
                            Exécutions enregistrées
                        </p>

                        <p class="mt-2 text-3xl font-bold text-white">
                            <?= $totalExecutions ?>
                        </p>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/10">
                            <?php
                            $tauxSucces = $totalExecutions > 0
                                ? min(100, (int) round(($totalSucces / $totalExecutions) * 100))
                                : 0;
                            ?>

                            <div
                                class="h-full rounded-full bg-[#f7b500]"
                                style="width: <?= $tauxSucces ?>%;"
                            ></div>
                        </div>

                        <p class="mt-2 text-right text-[11px] text-[#bcaea3]">
                            <?= $tauxSucces ?> % de succès
                        </p>

                    </div>

                </div>
            </section>

            <!-- NAVIGATION -->
            <section class="mt-7">
                <div class="overflow-hidden rounded-[20px] border border-[#e8ded4] bg-white shadow-[0_8px_25px_rgba(48,34,27,0.04)]">

                    <nav class="flex overflow-x-auto px-2 pt-2">

            <!-- Biens : onglet actif -->
            <a href="/immo-sn/admin/biens"
               class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#8d7a6b] transition hover:bg-[#faf7f2] hover:text-[#30221b]">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                </svg>

                Biens

           </a>

            <!-- Signalements : onglet inactif -->
            <a href="/immo-sn/admin/signalements"
               class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#8d7a6b] transition hover:bg-[#faf7f2] hover:text-[#30221b]">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                </svg>

                Signalements
            </a>


                        <a
                            href="/immo-sn/admin/cron-monitor"
                            aria-current="page"
                            class="relative flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-bold text-[#30221b]"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-[#f7b500]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>

                            Cron Monitor

                            <span class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full bg-[#f7b500]"></span>
                        </a>

                         <!-- Mail Logs -->
            <a href="/immo-sn/admin/mail-logs"
               class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#8d7a6b] transition hover:bg-[#faf7f2] hover:text-[#30221b]">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 7l9 6 9-6"/>
                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                </svg>

                Mail Logs
            </a>

                    </nav>
                </div>
            </section>

            <!-- TITRE -->
            <section class="mt-7">
                <div class="mb-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">
                        Monitoring
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-[#30221b] sm:text-2xl">
                        Résultats des exécutions
                    </h2>

                    <p class="mt-1 text-sm text-[#8d7a6b]">
                        Statistiques calculées à partir des journaux stockés dans la base de données.
                    </p>
                </div>

                <!-- MESSAGE D'ERREUR -->
                <?php if ($erreurCron !== null): ?>
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                        <p class="font-semibold">Impossible de charger les journaux.</p>
                        <p class="mt-1"><?= $e($erreurCron) ?></p>
                        <p class="mt-2 text-xs">
                            Consultez les journaux PHP du serveur pour identifier la cause.
                        </p>
                    </div>
                <?php endif; ?>

                <!-- STATISTIQUES DYNAMIQUES -->
                <div class="grid gap-4 md:grid-cols-3">

                    <div class="rounded-[22px] border border-[#e8ded4] bg-white p-6 shadow-[0_8px_30px_rgba(48,34,27,0.05)]">
                        <p class="text-sm font-medium text-[#8d7a6b]">
                            Exécutions enregistrées
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                            <?= $totalExecutions ?>
                        </p>

                        <p class="mt-1 text-xs text-[#9a887a]">
                            Total présent dans les journaux
                        </p>
                    </div>

                    <div class="rounded-[22px] border border-[#e8ded4] bg-white p-6 shadow-[0_8px_30px_rgba(48,34,27,0.05)]">
                        <p class="text-sm font-medium text-[#8d7a6b]">
                            Exécutions réussies
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-[#497657]">
                            <?= $totalSucces ?>
                        </p>

                        <p class="mt-1 text-xs text-[#9a887a]">
                            Statut de réussite enregistré
                        </p>
                    </div>

                    <div class="rounded-[22px] border border-[#e8ded4] bg-white p-6 shadow-[0_8px_30px_rgba(48,34,27,0.05)]">
                        <p class="text-sm font-medium text-[#8d7a6b]">
                            Exécutions en erreur
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight <?= $totalErreurs > 0 ? 'text-red-700' : 'text-[#30221b]' ?>">
                            <?= $totalErreurs ?>
                        </p>

                        <p class="mt-1 text-xs <?= $totalErreurs > 0 ? 'text-red-700' : 'text-[#497657]' ?>">
                            <?= $totalErreurs > 0 ? 'Des incidents sont à examiner' : 'Aucune erreur enregistrée dans les statuts reconnus' ?>
                        </p>
                    </div>

                </div>
            </section>

            <!-- TABLE DES JOURNAUX -->
            <section class="mt-7 overflow-hidden rounded-[24px] border border-[#e8ded4] bg-white shadow-[0_8px_30px_rgba(48,34,27,0.05)]">

                <div class="flex flex-col gap-3 border-b border-[#eee6de] px-6 py-5 sm:px-7 md:flex-row md:items-center md:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">
                            Journal d'exécution
                        </p>

                        <h2 class="mt-1 text-lg font-bold text-[#30221b]">
                            Historique réel des tâches CRON
                        </h2>

                        <p class="mt-1 text-xs text-[#9a887a]">
                            Les 100 exécutions les plus récentes au maximum.
                        </p>
                    </div>

                    <a
                        href="/immo-sn/admin/cron-monitor"
                        class="inline-flex items-center justify-center gap-2 self-start rounded-xl border border-[#e8ded4] bg-[#faf7f2] px-4 py-2.5 text-sm font-semibold text-[#5a402d] transition hover:bg-[#f3ece5]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 9A7 7 0 0 1 18 6l2 3M4 15l2 3a7 7 0 0 0 12.5-3"/>
                        </svg>
                        Actualiser
                    </a>

                
                </div>

               
<!-- FORMULAIRE DE RECHERCHE ET DE FILTRAGE -->
<form
    method="GET"
    action="/immo-sn/admin/cron-monitor"
    class="border-b border-[#eee6de] bg-[#fdfbf9] px-6 py-5 sm:px-7"
>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <!-- Recherche par script -->
        <div>
            <label
                for="q"
                class="mb-2 block text-xs font-semibold text-[#5a402d]"
            >
                Nom du script
            </label>

            <input
                type="search"
                id="q"
                name="q"
                value="<?= $e($recherche) ?>"
                placeholder="Ex. daily-stats.php"
                class="w-full rounded-xl border border-[#e8ded4] bg-white px-4 py-3 text-sm text-[#30221b] outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
            >
        </div>

        <!-- Filtre par statut -->
        <div>
            <label
                for="statut"
                class="mb-2 block text-xs font-semibold text-[#5a402d]"
            >
                Statut d'exécution
            </label>

            <select
                id="statut"
                name="statut"
                class="w-full rounded-xl border border-[#e8ded4] bg-white px-4 py-3 text-sm text-[#30221b] outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
            >
                <option value="" <?= $statut === '' ? 'selected' : '' ?>>
                    Tous les statuts
                </option>

                <option value="success" <?= $statut === 'success' ? 'selected' : '' ?>>
                    Succès
                </option>

                <option value="error" <?= $statut === 'error' ? 'selected' : '' ?>>
                    Erreur
                </option>

                <option value="running" <?= $statut === 'running' ? 'selected' : '' ?>>
                    En cours
                </option>

                <option value="unknown" <?= $statut === 'unknown' ? 'selected' : '' ?>>
                    Inconnu
                </option>
            </select>
        </div>

    </div>

    <!-- Boutons -->
    <div class="mt-4 flex flex-col gap-3 sm:flex-row">

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-xl bg-[#30221b] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#5a402d]"
        >
            Rechercher
        </button>

        <a
            href="/immo-sn/admin/cron-monitor"
            class="inline-flex items-center justify-center rounded-xl border border-[#e8ded4] bg-white px-5 py-3 text-sm font-semibold text-[#5a402d] transition hover:bg-[#f3ece5]"
        >
            Réinitialiser
        </a>

    </div>
</form>


               <div class="overflow-x-auto">
    <table class="w-full min-w-[1000px] table-fixed">

                        <thead class="bg-[#faf7f2]">
                            <tr class="text-left">
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#8d7a6b]">
                                    Script
                                </th>

                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#8d7a6b]">
                                    Statut
                                </th>

                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#8d7a6b]">
                                    Éléments traités
                                </th>

                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#8d7a6b]">
                                    Durée
                                </th>

                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#8d7a6b]">
                                    Date d'exécution
                                </th>

                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#8d7a6b]">
                                    Message
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#eee6de]">

                            <?php if ($cronLogs === []): ?>

                                <tr>
                                    <td colspan="6" class="px-6 py-14 text-center">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f3ece5] text-[#8d7a6b]">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-7 w-7"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.6"
                                            >
                                                <circle cx="12" cy="12" r="9"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                            </svg>
                                        </div>

                                        <p class="mt-4 text-sm font-semibold text-[#30221b]">
                                            Aucune exécution enregistrée
                                        </p>

                                        <p class="mt-1 text-xs text-[#8d7a6b]">
                                            Les journaux apparaîtront ici après l'exécution des scripts CRON.
                                        </p>
                                    </td>
                                </tr>

                            <?php else: ?>

                                <?php foreach ($cronLogs as $log): ?>

                                    <?php
                                  $script = (string) ($log['script'] ?? 'Script inconnu');
$statut = $afficherStatut($log['status'] ?? '');
$description = $descriptions[$script] ?? 'Exécution enregistrée';
 $messageErreur = trim((string) ($log['error_message'] ?? ''));
                                    ?>

                                    <tr class="transition hover:bg-[#fdfbf9]">

                                        <td class="px-6 py-5">
                                            <p class="text-sm font-bold text-[#30221b]">
                                                <?= $e($script) ?>
                                            </p>

                                            <p class="mt-1 max-w-[240px] text-xs leading-5 text-[#8d7a6b]">
                                                <?= $e($description) ?>
                                            </p>
                                        </td>

                                       
<td class="px-6 py-5">
    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold <?= $e($statut['classe']) ?>">
        <span class="h-1.5 w-1.5 rounded-full <?= $e($statut['point']) ?>"></span>
        <?= $e($statut['label']) ?>
    </span>
</td>


                                        <td class="px-6 py-5">
                                            <span class="text-sm font-semibold text-[#30221b]">
                                                <?= (int) ($log['items_processed'] ?? 0) ?>
                                            </span>
                                        </td>

                                        <td class="px-6 py-5">
                                            <span class="text-sm font-medium text-[#5a402d]">
                                                <?= (int) ($log['duration_ms'] ?? 0) ?> ms
                                            </span>
                                        </td>

                                        <td class="px-6 py-5">
                                            <p class="text-sm font-medium text-[#30221b]">
                                                <?= $e($formaterDate($log['executed_at'] ?? null)) ?>
                                            </p>
                                        </td>

                                        <td class="px-6 py-5">
                                            <?php if ($messageErreur !== ''): ?>
                                                <p class="max-w-[260px] whitespace-normal text-xs leading-5 text-red-700">
                                                    <?= $e($messageErreur) ?>
                                                </p>
                                            <?php else: ?>
                                                <span class="text-xs text-[#9a887a]">
                                                    —
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

               
                <div class="flex flex-col gap-4 border-t border-[#eee6de] bg-[#fdfbf9] px-6 py-4 text-xs text-[#9a887a]">

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <span>
                            <?= count($cronLogs) ?> résultat(s) affiché(s)
                            sur <?= $totalLogs ?> résultat(s) correspondant aux filtres.
                        </span>

                        <span>
                            Page <?= $page ?> sur <?= $totalPages ?>
                        </span>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <?php
                      $parametresPagination = [
    'q' => $recherche,
    'statut' => $statut,
    'par_page' => $parPage,
];
                        $urlPage = static function (int $numeroPage) use (
                            $parametresPagination
                        ): string {
                            return '/immo-sn/admin/cron-monitor?'
                                . http_build_query(
                                    $parametresPagination + [
                                        'page' => $numeroPage,
                                    ]
                                );
                        };
                        ?>

                        <nav
                            class="flex flex-wrap items-center justify-between gap-3"
                            aria-label="Pagination des journaux CRON"
                        >
                            <a
                                href="<?= $e($urlPage(max(1, $page - 1))) ?>"
                                class="inline-flex items-center rounded-xl border border-[#e8ded4] px-4 py-2 font-semibold text-[#5a402d] transition hover:bg-[#f3ece5] <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>"
                                <?= $page <= 1 ? 'aria-disabled="true"' : '' ?>
                            >
                                &larr; Précédent
                            </a>

                            <span class="font-medium">
                                <?= $page ?> / <?= $totalPages ?>
                            </span>

                            <a
                                href="<?= $e($urlPage(min($totalPages, $page + 1))) ?>"
                                class="inline-flex items-center rounded-xl border border-[#e8ded4] px-4 py-2 font-semibold text-[#5a402d] transition hover:bg-[#f3ece5] <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>"
                                <?= $page >= $totalPages ? 'aria-disabled="true"' : '' ?>
                            >
                                Suivant &rarr;
                            </a>
                        </nav>
                    <?php endif; ?>

                </div>


            </section>

        </div>
    </main>
</div>
