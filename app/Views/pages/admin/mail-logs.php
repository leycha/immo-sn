<?php

/**
 * Vue : Mail Logs — IMMO-SN
 * Emplacement : app/Views/pages/admin/mail-logs.php
 */

$stats = $stats ?? [];
$logs = $logs ?? [];
$recherche = (string) ($recherche ?? '');
$statut = (string) ($statut ?? '');
$page = max(1, (int) ($page ?? 1));
$parPage = max(1, (int) ($parPage ?? 10));
$totalLogs = max(0, (int) ($totalLogs ?? count($logs)));
$totalPages = max(1, (int) ($totalPages ?? ceil($totalLogs / $parPage)));
$page = min($page, $totalPages);
$erreurMailLogs = $erreurMailLogs ?? null;

$e = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

$total = (int) ($stats['total'] ?? 0);
$envoyes = (int) ($stats['envoyes'] ?? 0);
$echecs = (int) ($stats['echecs'] ?? 0);
$aujourdHui = (int) ($stats['aujourd_hui'] ?? 0);

$debutResultats = $totalLogs > 0 ? (($page - 1) * $parPage) + 1 : 0;
$finResultats = min($page * $parPage, $totalLogs);

$formatDate = static function ($date): string {
    if (empty($date)) {
        return 'Date inconnue';
    }

    try {
        return (new DateTime((string) $date))->format('d/m/Y à H:i');
    } catch (Throwable $exception) {
        return (string) $date;
    }
};

$normaliserStatut = static function ($value): string {
    $value = mb_strtolower(trim((string) $value), 'UTF-8');

    return match ($value) {
        'envoye' => 'envoyé',
        'echec', 'failed', 'failure' => 'échec',
        default => $value,
    };
};

/**
 * Conserver les filtres dans les liens de pagination.
 */
$urlPage = static function (int $numero) use ($recherche, $statut): string {
    $parametres = [];

    if ($recherche !== '') {
        $parametres['q'] = $recherche;
    }

    if ($statut !== '') {
        $parametres['statut'] = $statut;
    }

    $parametres['page'] = $numero;

    return '/immo-sn/admin/mail-logs?' . http_build_query($parametres);
};

/**
 * Afficher une petite série de numéros de pages autour de la page courante.
 */
$premierePageVisible = max(1, $page - 2);
$dernierePageVisible = min($totalPages, $page + 2);

?>

<div class="min-h-screen bg-[#f8fafc] text-[#0b1528]">

    <!-- EN-TÊTE -->
    <header class="sticky top-0 z-30 border-b border-[#e2e8f0] bg-white/95 backdrop-blur-xl">
        <div class="flex min-h-[82px] items-center justify-between gap-4 px-5 py-4 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="hidden h-11 w-11 items-center justify-center rounded-2xl bg-[#071b36] shadow-sm sm:flex">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-[#f5b800]"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <div>
                    <div class="mb-1 flex items-center gap-2 text-xs text-[#64748b]">
                        <span>Administration</span>
                        <span>/</span>
                        <span class="font-medium text-[#0e2a4e]">Mail Logs</span>
                    </div>
                    <h1 class="text-xl font-bold text-[#071b36] md:text-2xl">
                        Journaux des emails
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden text-right md:block">
                    <p class="text-sm font-semibold text-[#0b1528]">Administrateur</p>
                    <p class="text-xs text-[#64748b]">Espace administration</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#071b36] shadow-sm">
                    <span class="text-sm font-bold text-[#f5b800]">A</span>
                </div>
            </div>
        </div>
    </header>

    <main class="space-y-8 p-5 md:p-6 lg:p-8">

        <!-- HERO : HISTORIQUE DES EMAILS -->
        <section class="relative overflow-hidden rounded-[26px] bg-[#071b36] shadow-[0_18px_50px_rgba(7,27,54,0.16)]">

            <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#0e2a4e]/60 blur-3xl"></div>

            <div class="relative flex flex-col gap-7 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between">

                <!-- CONTENU PRINCIPAL -->
                <div class="max-w-2xl">

                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">

                        <span class="h-2 w-2 rounded-full <?= (int) $echecs > 0 ? 'bg-red-400' : 'bg-emerald-400' ?>"></span>

                        <span class="text-xs font-medium text-slate-200">
                            Suivi des communications
                        </span>

                    </div>

                    <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                        Historique des emails
                        <span class="text-[#f5b800]">envoyés</span>
                    </h2>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">
                        Consultez les emails enregistrés par IMMO-SN,
                        recherchez un destinataire et identifiez rapidement
                        les éventuels échecs d'envoi.
                    </p>

                </div>

                <!-- CARTE STATISTIQUE -->
                <div class="shrink-0 rounded-2xl border border-white/10 bg-[#0e2a4e]/70 p-5 backdrop-blur-sm lg:min-w-[260px]">

                    <p class="text-xs uppercase tracking-[0.15em] text-slate-400">
                        Emails enregistrés
                    </p>

                    <p class="mt-2 text-3xl font-bold text-white">
                        <?= (int) $total ?>
                    </p>

                    <?php
                    $tauxSuccesEmails = (int) $total > 0
                        ? min(100, (int) round(((int) $envoyes / (int) $total) * 100))
                        : 0;
                    ?>

                    <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/10">
                        <div
                            class="h-full rounded-full bg-[#f5b800]"
                            style="width: <?= $tauxSuccesEmails ?>%;"
                        ></div>
                    </div>

                    <div class="mt-3 flex items-center justify-between gap-3 text-[11px]">
                        <span class="text-slate-300">
                            <?= $tauxSuccesEmails ?> % de succès
                        </span>

                        <span class="text-red-300">
                            <?= (int) $echecs ?> échec(s)
                        </span>
                    </div>

                </div>

            </div>
        </section>

        <!-- NAVIGATION -->
        <section class="mt-7">
            <div class="overflow-hidden rounded-[20px] border border-[#e2e8f0] bg-white shadow-[0_8px_25px_rgba(7,27,54,0.04)]">

                <nav class="flex overflow-x-auto px-2 pt-2" aria-label="Navigation administration">

                    <!-- Biens -->
                    <a href="/immo-sn/admin/biens"
                       class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#64748b] transition hover:bg-[#f8fafc] hover:text-[#071b36]">

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

                    <!-- Signalements -->
                    <a href="/immo-sn/admin/signalements"
                       class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#64748b] transition hover:bg-[#f8fafc] hover:text-[#071b36]">

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

                    <!-- Cron Monitor -->
                    <a href="/immo-sn/admin/cron-monitor"
                       class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#64748b] transition hover:bg-[#f8fafc] hover:text-[#071b36]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 7v5l3 2"/>
                        </svg>

                        Cron Monitor
                    </a>

                    <!-- Mail Logs : onglet actif -->
                    <a
                        href="/immo-sn/admin/mail-logs"
                        aria-current="page"
                        class="relative flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-bold text-[#071b36]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 text-[#f5b800]"
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
                        <span class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full bg-[#f5b800]"></span>
                    </a>

                </nav>
            </div>
        </section>

        <!-- MESSAGE D'ERREUR -->
        <?php if (!empty($erreurMailLogs)): ?>
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        !
                    </span>
                    <div>
                        <h2 class="font-bold text-red-900">Chargement impossible</h2>
                        <p class="mt-1 text-sm leading-6 text-red-800">
                            <?= $e($erreurMailLogs) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- STATISTIQUES -->
        <section>
            <div class="mb-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0e2a4e]">
                    Vue d'ensemble
                </p>
                <h2 class="mt-1 text-xl font-bold text-[#071b36]">
                    Activité des emails
                </h2>
                <p class="mt-1 text-sm text-[#64748b]">
                    Statistiques globales du journal, indépendantes des filtres.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)]">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#64748b]">Total des emails</p>
                            <p class="mt-3 text-3xl font-bold text-[#071b36]"><?= $e($total) ?></p>
                            <p class="mt-2 text-xs text-[#0e2a4e]">Depuis le début</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071b36]/10 text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <article class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)]">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#64748b]">Envoyés</p>
                            <p class="mt-3 text-3xl font-bold text-emerald-700"><?= $e($envoyes) ?></p>
                            <p class="mt-2 text-xs text-emerald-700">Acceptés par le serveur</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <article class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)]">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#64748b]">Échecs</p>
                            <p class="mt-3 text-3xl font-bold <?= $echecs > 0 ? 'text-red-700' : 'text-[#071b36]' ?>"><?= $e($echecs) ?></p>
                            <p class="mt-2 text-xs text-red-700">À vérifier si nécessaire</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9L1.9 18.4A1.8 1.8 0 003.5 21h17a1.8 1.8 0 001.6-2.6L13.7 3.9a2 2 0 00-3.4 0z"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <article class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)]">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#64748b]">Aujourd'hui</p>
                            <p class="mt-3 text-3xl font-bold text-[#071b36]"><?= $e($aujourdHui) ?></p>
                            <p class="mt-2 text-xs text-[#0e2a4e]">Activité du jour</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f5b800]/15 text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 11h18"/>
                            </svg>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- FILTRES -->
        <section class="rounded-[24px] border border-[#e2e8f0] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] sm:p-7">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#0e2a4e]">
                        Recherche
                    </p>
                    <h2 class="mt-1 text-lg font-bold text-[#071b36]">
                        Filtrer les journaux
                    </h2>
                </div>

                <a href="/immo-sn/admin/mail-logs"
                   class="inline-flex items-center justify-center gap-2 self-start rounded-xl border border-[#e2e8f0] bg-white px-4 py-2.5 text-sm font-semibold text-[#0b1528] transition hover:bg-[#f8fafc] sm:self-auto">
                    Réinitialiser
                </a>
            </div>

            <form method="GET" action="/immo-sn/admin/mail-logs"
                  class="grid grid-cols-1 items-end gap-4 lg:grid-cols-[minmax(0,1fr)_240px_auto]">

                <div>
                    <label for="q" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#0b1528]">
                        Rechercher
                    </label>
                    <input
                        id="q"
                        name="q"
                        type="text"
                        value="<?= $e($recherche) ?>"
                        placeholder="Email, sujet ou modèle..."
                        class="w-full rounded-xl border border-[#cbd5e1] bg-white px-4 py-3.5 text-sm text-[#0b1528] placeholder:text-slate-400 focus:border-[#071b36] focus:outline-none focus:ring-2 focus:ring-[#071b36]/15"
                    >
                </div>

                <div>
                    <label for="statut" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#0b1528]">
                        Statut
                    </label>
                    <select
                        id="statut"
                        name="statut"
                        class="w-full rounded-xl border border-[#cbd5e1] bg-white px-4 py-3.5 text-sm text-[#0b1528] focus:border-[#071b36] focus:outline-none focus:ring-2 focus:ring-[#071b36]/15"
                    >
                        <option value="" <?= $statut === '' ? 'selected' : '' ?>>
                            Tous les statuts
                        </option>

                        <option value="envoyé" <?= mb_strtolower(trim($statut), 'UTF-8') === 'envoyé' ? 'selected' : '' ?>>
                            Envoyé
                        </option>

                        <option value="échec" <?= mb_strtolower(trim($statut), 'UTF-8') === 'échec' ? 'selected' : '' ?>>
                            Échec
                        </option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#071b36] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0e2a4e] lg:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4"/>
                        </svg>
                        Filtrer
                    </button>
                </div>
            </form>
        </section>

        <!-- HISTORIQUE -->
        <section class="overflow-hidden rounded-[24px] border border-[#e2e8f0] bg-white shadow-[0_5px_24px_rgba(7,27,54,0.035)]">

            <div class="border-b border-[#e2e8f0] px-5 py-5 md:px-7">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[#071b36]">Historique des emails</h2>
                        <p class="mt-1 text-sm text-[#64748b]">
                            Consultez le détail de chaque envoi enregistré.
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-2 self-start rounded-xl border border-[#e2e8f0] bg-[#f8fafc] px-3 py-2 text-xs font-medium text-[#0e2a4e] sm:self-auto">
                        <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                        <?= $e($debutResultats) ?>–<?= $e($finResultats) ?> sur <?= $e($totalLogs) ?> résultat(s)
                    </span>
                </div>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[1000px] table-fixed">
                    <thead class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                        <tr class="text-left text-[11px] uppercase tracking-[0.14em] text-[#64748b]">
                            <th class="px-6 py-4 font-semibold">Destinataire</th>
                            <th class="px-6 py-4 font-semibold">Sujet</th>
                            <th class="px-6 py-4 font-semibold">Type</th>
                            <th class="px-6 py-4 font-semibold">Date</th>
                            <th class="px-6 py-4 font-semibold">Statut</th>
                            <th class="px-6 py-4 font-semibold">Détail</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#e2e8f0]">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f8fafc] border border-[#e2e8f0]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#0e2a4e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-[#071b36]">Aucun email trouvé</h3>
                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#64748b]">
                                    Aucun journal ne correspond aux critères sélectionnés.
                                    Modifiez la recherche ou réinitialisez les filtres.
                                </p>
                                <a href="/immo-sn/admin/mail-logs"
                                   class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#071b36] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#0e2a4e]">
                                    Afficher tous les emails
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <?php
                            $statutLog = trim((string) ($log['status'] ?? ''));
                            $statutNormalise = $normaliserStatut($statutLog);
                            $estEnvoye = $statutNormalise === 'envoyé';
                            $estEchec = $statutNormalise === 'échec';

                            $badgeClasses = $estEnvoye
                                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                : ($estEchec
                                    ? 'border-red-200 bg-red-50 text-red-700'
                                    : 'border-amber-200 bg-amber-50 text-amber-800');

                            $pointClasses = $estEnvoye
                                ? 'bg-emerald-500'
                                : ($estEchec ? 'bg-red-500' : 'bg-[#f5b800]');

                            $erreurLog = trim((string) ($log['error'] ?? ''));
                            ?>
                            <tr class="transition hover:bg-[#f8fafc]">
                                <td class="px-6 py-5">
                                    <p class="max-w-[230px] break-words text-xs font-semibold text-[#0b1528]">
                                        <?= $e($log['email_to'] ?? 'Destinataire inconnu') ?>
                                    </p>
                                </td>

                                <td class="px-6 py-5">
                                    <p class="max-w-xs truncate text-sm text-[#0b1528]"
                                       title="<?= $e($log['subject'] ?? '') ?>">
                                        <?= $e(($log['subject'] ?? '') !== '' ? $log['subject'] : 'Sans sujet') ?>
                                    </p>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex max-w-[180px] break-words rounded-lg bg-[#071b36]/10 px-2.5 py-1 text-xs font-medium text-[#071b36]">
                                        <?= $e(($log['template'] ?? '') !== '' ? $log['template'] : 'Non précisé') ?>
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="text-sm text-[#0e2a4e]">
                                        <?= $e($formatDate($log['sent_at'] ?? null)) ?>
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border px-2.5 py-1.5 text-xs font-semibold <?= $badgeClasses ?>">
                                        <span class="h-1.5 w-1.5 rounded-full <?= $pointClasses ?>"></span>
                                        <?= $e($statutLog !== '' ? $statutLog : 'Inconnu') ?>
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    <?php if ($erreurLog !== ''): ?>
                                        <details class="max-w-xs">
                                            <summary class="cursor-pointer text-xs font-semibold text-red-700 hover:underline">
                                                Voir l'erreur
                                            </summary>
                                            <p class="mt-2 whitespace-normal break-words rounded-lg bg-red-50 p-3 text-xs leading-5 text-red-800">
                                                <?= $e($erreurLog) ?>
                                            </p>
                                        </details>
                                    <?php elseif ($estEnvoye): ?>
                                        <span class="text-xs text-emerald-700">Aucune erreur</span>
                                    <?php else: ?>
                                        <span class="text-xs text-[#64748b]">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="border-t border-[#e2e8f0] bg-[#f8fafc] px-5 py-5 md:px-7">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-[#071b36]">
                            Page <?= $e($page) ?> sur <?= $e($totalPages) ?>
                        </p>
                        <p class="mt-1 text-xs text-[#64748b]">
                            <?= $e($debutResultats) ?> à <?= $e($finResultats) ?>
                            sur <?= $e($totalLogs) ?> journal(aux)
                        </p>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Pagination des journaux des emails"
                             class="flex flex-wrap items-center gap-2">

                            <!-- PREMIÈRE PAGE -->
                            <?php if ($page > 1): ?>
                                <a href="<?= $e($urlPage(1)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-[#e2e8f0] bg-white px-3 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-slate-100"
                                   aria-label="Première page">
                                    <span class="hidden sm:inline">Première</span>
                                    <span class="sm:hidden">«</span>
                                </a>

                                <a href="<?= $e($urlPage($page - 1)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-[#e2e8f0] bg-white px-3 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-slate-100"
                                   aria-label="Page précédente">
                                    <span class="hidden sm:inline">Précédente</span>
                                    <span class="sm:hidden">‹</span>
                                </a>
                            <?php else: ?>
                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-[#e2e8f0] bg-slate-100 px-3 py-2.5 text-sm text-slate-400">
                                    <span class="hidden sm:inline">Première</span>
                                    <span class="sm:hidden">«</span>
                                </span>
                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-[#e2e8f0] bg-slate-100 px-3 py-2.5 text-sm text-slate-400">
                                    <span class="hidden sm:inline">Précédente</span>
                                    <span class="sm:hidden">‹</span>
                                </span>
                            <?php endif; ?>

                            <!-- NUMÉROS DE PAGES -->
                            <?php if ($premierePageVisible > 1): ?>
                                <span class="px-1 text-sm text-[#64748b]">…</span>
                            <?php endif; ?>

                            <?php for ($numero = $premierePageVisible; $numero <= $dernierePageVisible; $numero++): ?>
                                <?php if ($numero === $page): ?>
                                    <span aria-current="page"
                                          class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#071b36] bg-[#071b36] px-3 text-sm font-bold text-[#f5b800]">
                                        <?= $e($numero) ?>
                                    </span>
                                <?php else: ?>
                                    <a href="<?= $e($urlPage($numero)) ?>"
                                       class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#e2e8f0] bg-white px-3 text-sm font-semibold text-[#071b36] transition hover:border-[#0e2a4e] hover:bg-slate-100">
                                        <?= $e($numero) ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($dernierePageVisible < $totalPages): ?>
                                <span class="px-1 text-sm text-[#64748b]">…</span>
                            <?php endif; ?>

                            <!-- PAGE SUIVANTE -->
                            <?php if ($page < $totalPages): ?>
                                <a href="<?= $e($urlPage($page + 1)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-[#e2e8f0] bg-white px-3 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-slate-100"
                                   aria-label="Page suivante">
                                    <span class="hidden sm:inline">Suivante</span>
                                    <span class="sm:hidden">›</span>
                                </a>

                                <a href="<?= $e($urlPage($totalPages)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-[#e2e8f0] bg-white px-3 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-slate-100"
                                   aria-label="Dernière page">
                                    <span class="hidden sm:inline">Dernière</span>
                                    <span class="sm:hidden">»</span>
                                </a>
                            <?php else: ?>
                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-[#e2e8f0] bg-slate-100 px-3 py-2.5 text-sm text-slate-400">
                                    <span class="hidden sm:inline">Suivante</span>
                                    <span class="sm:hidden">›</span>
                                </span>
                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-[#e2e8f0] bg-slate-100 px-3 py-2.5 text-sm text-slate-400">
                                    <span class="hidden sm:inline">Dernière</span>
                                    <span class="sm:hidden">»</span>
                                </span>
                            <?php endif; ?>

                        </nav>
                    <?php endif; ?>
                </div>
            </div>

        </section>

    </main>
</div>