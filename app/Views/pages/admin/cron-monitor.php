
<?php

$cronLogs = is_array($cronLogs ?? null) ? $cronLogs : [];

$statsCron = is_array($statsCron ?? null) ? $statsCron : [
    'total' => 0,
    'succes' => 0,
    'erreurs' => 0,
];

$erreurCron = $erreurCron ?? null;

$recherche = trim((string) ($recherche ?? ''));
$statut = (string) ($statut ?? '');
$dateDebut = (string) ($dateDebut ?? '');
$dateFin = (string) ($dateFin ?? '');

$page = max(1, (int) ($page ?? 1));
$parPage = (int) ($parPage ?? 10);
$totalLogs = max(0, (int) ($totalLogs ?? 0));
$totalPages = max(1, (int) ($totalPages ?? 1));

$parPage = in_array($parPage, [10, 20, 50], true) ? $parPage : 10;

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
};

$normaliserStatut = static function ($statut): string {
    $statut = mb_strtolower(trim((string) $statut), 'UTF-8');

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

$afficherStatut = static function ($statut) use ($normaliserStatut): array {
    $statutNormalise = $normaliserStatut($statut);

    switch ($statutNormalise) {
        case 'success':
            return [
                'label' => 'Succès',
                'classe' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'point' => 'bg-emerald-500',
            ];

        case 'error':
            return [
                'label' => 'Erreur',
                'classe' => 'bg-red-50 text-red-700 border-red-200',
                'point' => 'bg-red-500',
            ];

        case 'running':
            return [
                'label' => 'En cours',
                'classe' => 'bg-amber-50 text-amber-800 border-amber-200',
                'point' => 'bg-[#f5b800]',
            ];

        default:
            return [
                'label' => 'Inconnu',
                'classe' => 'bg-slate-100 text-slate-700 border-slate-200',
                'point' => 'bg-slate-400',
            ];
    }
};

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

$tauxSucces = $totalExecutions > 0
    ? min(100, (int) round(($totalSucces / $totalExecutions) * 100))
    : 0;

?>

<div class="min-h-screen bg-[#f5f3ed]">

    <!-- =====================================================
         HEADER
    ====================================================== -->
    <header class="sticky top-0 z-20 border-b border-[#e5e7eb] bg-white/95 backdrop-blur-xl">
        <div class="px-5 py-5 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                        <span>Administration</span>
                        <span>/</span>
                        <span class="text-[#0e2a4e]">Cron Monitor</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#071b36] sm:text-3xl">
                        Cron Monitor
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Surveillance et suivi des tâches automatiques
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#071b36] text-[#f5b800] shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-[#071b36]">Administrateur</p>
                        <p class="text-xs text-slate-500">Espace administration</p>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

            <!-- =================================================
                 HERO
            ================================================== -->
            <section class="relative overflow-hidden rounded-[28px] bg-[#071b36] shadow-[0_20px_55px_rgba(7,27,54,0.15)]">

                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#0e2a4e]/60 blur-3xl"></div>

                <div class="relative flex flex-col gap-8 px-6 py-8 sm:px-9 lg:flex-row lg:items-center lg:justify-between lg:py-10">

                    <div class="max-w-2xl">

                        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2">
                            <span class="h-2 w-2 rounded-full <?= $erreurCron !== null || $totalErreurs > 0 ? 'bg-red-400' : 'bg-emerald-400' ?>"></span>

                            <span class="text-xs font-semibold text-slate-200">
                                <?= $e($etatGlobal) ?>
                            </span>
                        </div>

                        <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl lg:text-5xl">
                            Surveillance des tâches
                            <span class="text-[#f5b800]">automatiques</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">
                            Contrôlez les exécutions des scripts CRON, leurs résultats,
                            leurs durées et les éventuelles erreurs enregistrées.
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-xs text-slate-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Suivi automatique
                            </div>

                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-xs text-slate-300">
                                <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                                Historique des tâches
                            </div>
                        </div>

                    </div>

                    <!-- Carte synthèse -->
                    <div class="shrink-0 rounded-[22px] border border-white/10 bg-[#0e2a4e]/75 p-6 backdrop-blur-md lg:min-w-[280px]">

                        <div class="flex items-center justify-between gap-5">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.15em] text-slate-400">
                                    Exécutions enregistrées
                                </p>

                                <p class="mt-3 text-4xl font-bold tabular-nums text-white">
                                    <?= number_format($totalExecutions, 0, ',', ' ') ?>
                                </p>

                                <p class="mt-1 text-xs text-slate-300">
                                    opérations journalisées
                                </p>
                            </div>

                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f5b800]/15 text-[#f5b800]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 h-2 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-[#f5b800]" style="width: <?= $tauxSucces ?>%;"></div>
                        </div>

                        <p class="mt-3 text-right text-xs text-slate-400">
                            <?= $tauxSucces ?> % de réussite
                        </p>

                    </div>

                </div>
            </section>

            <!-- =================================================
                 NAVIGATION ADMIN
            ================================================== -->
            <section class="mt-7">
                <div class="overflow-hidden rounded-[20px] border border-[#e5e7eb] bg-white shadow-sm">

                    <nav class="flex overflow-x-auto px-2 pt-2" aria-label="Navigation administration">

                        <a href="/immo-sn/admin/biens"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-slate-500 transition hover:bg-[#f5f3ed] hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                            </svg>
                            Biens
                        </a>

                        <a href="/immo-sn/admin/signalements"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-slate-500 transition hover:bg-[#f5f3ed] hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                            </svg>
                            Signalements
                        </a>

                        <a href="/immo-sn/admin/cron-monitor"
                           aria-current="page"
                           class="relative flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-bold text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#f5b800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                            Cron Monitor
                            <span class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full bg-[#f5b800]"></span>
                        </a>

                        <a href="/immo-sn/admin/mail-logs"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-slate-500 transition hover:bg-[#f5f3ed] hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6"/>
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                            </svg>
                            Mail Logs
                        </a>

                    </nav>
                </div>
            </section>

            <!-- =================================================
                 STATISTIQUES
            ================================================== -->
            <section class="mt-8">

                <div class="mb-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                        Monitoring
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-[#071b36] sm:text-2xl">
                        Résultats des exécutions
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Statistiques calculées à partir des journaux stockés en base de données.
                    </p>
                </div>

                <!-- Erreur -->
                <?php if ($erreurCron !== null): ?>
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-800">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                                </svg>
                            </div>

                            <div>
                                <p class="font-bold">Impossible de charger les journaux.</p>
                                <p class="mt-1"><?= $e($erreurCron) ?></p>
                                <p class="mt-2 text-xs text-red-600">
                                    Consultez les journaux PHP du serveur pour identifier la cause.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Cartes statistiques -->
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

                    <!-- Total -->
                    <div class="group rounded-[22px] border border-[#e5e7eb] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#071b36] text-[#f5b800]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                </svg>
                            </div>
                            <span class="rounded-full bg-[#f5f3ed] px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-[#0e2a4e]">
                                Total
                            </span>
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Exécutions enregistrées
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-[#071b36]">
                            <?= number_format($totalExecutions, 0, ',', ' ') ?>
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Total présent dans les journaux
                        </p>
                    </div>

                    <!-- Succès -->
                    <div class="group rounded-[22px] border border-[#e5e7eb] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                                OK
                            </span>
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Exécutions réussies
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-emerald-700">
                            <?= number_format($totalSucces, 0, ',', ' ') ?>
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Statut de réussite enregistré
                        </p>
                    </div>

                    <!-- Erreurs -->
                    <div class="group rounded-[22px] border border-[#e5e7eb] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl <?= $totalErreurs > 0 ? 'bg-red-50 text-red-700' : 'bg-[#f5f3ed] text-[#071b36]' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                                </svg>
                            </div>
                            <span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider <?= $totalErreurs > 0 ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700' ?>">
                                <?= $totalErreurs > 0 ? 'Attention' : 'Stable' ?>
                            </span>
                        </div>

                        <p class="mt-5 text-sm font-medium text-slate-500">
                            Exécutions en erreur
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight <?= $totalErreurs > 0 ? 'text-red-700' : 'text-[#071b36]' ?>">
                            <?= number_format($totalErreurs, 0, ',', ' ') ?>
                        </p>

                        <p class="mt-2 text-xs <?= $totalErreurs > 0 ? 'text-red-600' : 'text-emerald-600' ?>">
                            <?= $totalErreurs > 0 ? 'Des incidents sont à examiner' : 'Aucune erreur reconnue enregistrée' ?>
                        </p>
                    </div>

                </div>
            </section>

            <!-- =================================================
                 HISTORIQUE CRON
            ================================================== -->
            <section class="mt-8 overflow-hidden rounded-[26px] border border-[#e5e7eb] bg-white shadow-[0_10px_35px_rgba(7,27,54,0.05)]">

                <!-- En-tête -->
                <div class="flex flex-col gap-4 border-b border-[#e5e7eb] px-6 py-6 sm:px-8 md:flex-row md:items-center md:justify-between">

                    <div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071b36] text-[#f5b800]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 9A7 7 0 0 1 18 6l2 3M4 15l2 3a7 7 0 0 0 12.5-3"/>
                                </svg>
                            </div>

                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                                Journal d'exécution
                            </p>
                        </div>

                        <h2 class="mt-3 text-xl font-bold text-[#071b36] sm:text-2xl">
                            Historique des tâches CRON
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Consultez les exécutions réellement enregistrées sur le serveur.
                        </p>
                    </div>

                    <a href="/immo-sn/admin/cron-monitor"
                       class="inline-flex items-center justify-center gap-2 self-start rounded-xl bg-[#071b36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0e2a4e]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#f5b800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 9A7 7 0 0 1 18 6l2 3M4 15l2 3a7 7 0 0 0 12.5-3"/>
                        </svg>
                        Actualiser
                    </a>

                </div>

                <!-- Filtres -->
                <form method="GET"
                      action="/immo-sn/admin/cron-monitor"
                      class="border-b border-[#e5e7eb] bg-[#faf9f6] px-6 py-6 sm:px-8">

                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_260px]">

                        <div>
                            <label for="q" class="mb-2 block text-xs font-bold text-[#071b36]">
                                Nom du script
                            </label>

                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-4-4"/>
                                </svg>

                                <input type="search"
                                       id="q"
                                       name="q"
                                       value="<?= $e($recherche) ?>"
                                       placeholder="Ex. daily-stats.php"
                                       class="h-12 w-full rounded-xl border border-[#d9dce3] bg-white pl-12 pr-4 text-sm text-[#071b36] outline-none transition placeholder:text-slate-400 focus:border-[#0e2a4e] focus:ring-4 focus:ring-[#071b36]/10">
                            </div>
                        </div>

                        <div>
                            <label for="statut" class="mb-2 block text-xs font-bold text-[#071b36]">
                                Statut d'exécution
                            </label>

                            <select id="statut"
                                    name="statut"
                                    class="h-12 w-full rounded-xl border border-[#d9dce3] bg-white px-4 text-sm text-[#071b36] outline-none transition focus:border-[#0e2a4e] focus:ring-4 focus:ring-[#071b36]/10">
                                <option value="" <?= $statut === '' ? 'selected' : '' ?>>Tous les statuts</option>
                                <option value="success" <?= $statut === 'success' ? 'selected' : '' ?>>Succès</option>
                                <option value="error" <?= $statut === 'error' ? 'selected' : '' ?>>Erreur</option>
                                <option value="running" <?= $statut === 'running' ? 'selected' : '' ?>>En cours</option>
                                <option value="unknown" <?= $statut === 'unknown' ? 'selected' : '' ?>>Inconnu</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">

                        <button type="submit"
                                class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-[#071b36] px-6 text-sm font-bold text-white transition hover:bg-[#0e2a4e]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#f5b800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-4-4"/>
                            </svg>
                            Rechercher
                        </button>

                        <a href="/immo-sn/admin/cron-monitor"
                           class="inline-flex h-12 items-center justify-center rounded-xl border border-[#e5e7eb] bg-white px-6 text-sm font-bold text-[#071b36] transition hover:bg-[#f5f3ed]">
                            Réinitialiser
                        </a>

                    </div>
                </form>

                <!-- TABLEAU -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] table-fixed">

                        <thead class="border-b border-[#e5e7eb] bg-[#f5f3ed]">
                            <tr class="text-left">
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">Script</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">Statut</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">Éléments traités</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">Durée</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">Date d'exécution</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">Message</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#edf0f3]">

                            <?php if ($cronLogs === []): ?>

                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f5f3ed] text-[#0e2a4e]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                                <circle cx="12" cy="12" r="9"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 text-base font-bold text-[#071b36]">
                                            Aucune exécution enregistrée
                                        </h3>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Les journaux apparaîtront ici après l'exécution des scripts CRON.
                                        </p>
                                    </td>
                                </tr>

                            <?php else: ?>

                                <?php foreach ($cronLogs as $log): ?>

                                    <?php
                                    $script = (string) ($log['script'] ?? 'Script inconnu');
                                    $statutLog = $afficherStatut($log['status'] ?? '');
                                    $description = $descriptions[$script] ?? 'Exécution enregistrée';
                                    $messageErreur = trim((string) ($log['error_message'] ?? ''));
                                    ?>

                                    <tr class="transition hover:bg-[#faf9f6]">

                                        <td class="px-6 py-5">
                                            <div class="flex items-start gap-3">
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#071b36] text-[#f5b800]">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 12h8M8 17h5"/>
                                                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                                                    </svg>
                                                </div>

                                                <div class="min-w-0">
                                                    <p class="break-all text-sm font-bold text-[#071b36]">
                                                        <?= $e($script) ?>
                                                    </p>
                                                    <p class="mt-1 max-w-[240px] text-xs leading-5 text-slate-500">
                                                        <?= $e($description) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-5">
                                            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-bold <?= $e($statutLog['classe']) ?>">
                                                <span class="h-1.5 w-1.5 rounded-full <?= $e($statutLog['point']) ?>"></span>
                                                <?= $e($statutLog['label']) ?>
                                            </span>
                                        </td>

                                        <td class="px-6 py-5">
                                            <span class="inline-flex items-center rounded-lg bg-[#f5f3ed] px-3 py-1.5 text-sm font-bold text-[#071b36]">
                                                <?= number_format((int) ($log['items_processed'] ?? 0), 0, ',', ' ') ?>
                                            </span>
                                        </td>

                                        <td class="px-6 py-5">
                                            <span class="text-sm font-semibold text-[#0e2a4e]">
                                                <?= number_format((int) ($log['duration_ms'] ?? 0), 0, ',', ' ') ?>
                                                <span class="text-xs font-medium text-slate-400">ms</span>
                                            </span>
                                        </td>

                                        <td class="px-6 py-5">
                                            <p class="text-sm font-semibold text-[#071b36]">
                                                <?= $e($formaterDate($log['executed_at'] ?? null)) ?>
                                            </p>
                                        </td>

                                        <td class="px-6 py-5">
                                            <?php if ($messageErreur !== ''): ?>
                                                <div class="flex items-start gap-2 rounded-lg bg-red-50 p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                                                    </svg>

                                                    <p class="max-w-[260px] whitespace-normal text-xs leading-5 text-red-700">
                                                        <?= $e($messageErreur) ?>
                                                    </p>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-sm text-slate-400">—</span>
                                            <?php endif; ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <!-- FOOTER TABLEAU + PAGINATION -->
                <div class="border-t border-[#e5e7eb] bg-[#faf9f6] px-6 py-5 sm:px-8">

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-slate-500">
                            <span class="font-bold text-[#071b36]"><?= count($cronLogs) ?></span>
                            résultat(s) affiché(s) sur
                            <span class="font-bold text-[#071b36]"><?= $totalLogs ?></span>
                            résultat(s) correspondant aux filtres.
                        </p>

                        <p class="text-xs font-semibold text-[#0e2a4e]">
                            Page <?= $page ?> sur <?= $totalPages ?>
                        </p>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <?php
                        $parametresPagination = [
                            'q' => $recherche,
                            'statut' => $statut,
                            'par_page' => $parPage,
                        ];

                        $urlPage = static function (int $numeroPage) use ($parametresPagination): string {
                            return '/immo-sn/admin/cron-monitor?'
                                . http_build_query(
                                    $parametresPagination + ['page' => $numeroPage]
                                );
                        };
                        ?>

                        <nav class="mt-5 flex flex-wrap items-center justify-between gap-3"
                             aria-label="Pagination des journaux CRON">

                            <a href="<?= $e($urlPage(max(1, $page - 1))) ?>"
                               class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#e5e7eb] bg-white px-4 py-3 text-sm font-bold text-[#071b36] transition hover:bg-[#f5f3ed] <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>"
                               <?= $page <= 1 ? 'aria-disabled="true"' : '' ?>>
                                &larr; Précédent
                            </a>

                            <div class="flex items-center gap-2">
                                <span class="rounded-xl bg-[#071b36] px-4 py-2.5 text-sm font-bold text-[#f5b800]">
                                    <?= $page ?>
                                </span>

                                <span class="text-xs text-slate-400">sur</span>

                                <span class="text-sm font-semibold text-[#071b36]">
                                    <?= $totalPages ?>
                                </span>
                            </div>

                            <a href="<?= $e($urlPage(min($totalPages, $page + 1))) ?>"
                               class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#e5e7eb] bg-white px-4 py-3 text-sm font-bold text-[#071b36] transition hover:bg-[#f5f3ed] <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>"
                               <?= $page >= $totalPages ? 'aria-disabled="true"' : '' ?>>
                                Suivant &rarr;
                            </a>
                        </nav>
                    <?php endif; ?>

                </div>

            </section>

        </div>
    </main>
</div>
