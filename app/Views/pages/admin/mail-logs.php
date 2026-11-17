
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

<div class="min-h-screen bg-[#faf7f2]">

    <!-- EN-TÊTE -->
    <header class="sticky top-0 z-30 border-b border-[#e8dfd5] bg-white/95 backdrop-blur">
        <div class="flex min-h-[82px] items-center justify-between gap-4 px-5 py-4 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="hidden h-11 w-11 items-center justify-center rounded-2xl bg-[#30221b] shadow-sm sm:flex">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-[#f7b500]"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <div>
                    <div class="mb-1 flex items-center gap-2 text-xs text-[#9a897b]">
                        <span>Administration</span>
                        <span>/</span>
                        <span class="font-medium text-[#725238]">Mail Logs</span>
                    </div>
                    <h1 class="text-xl font-bold text-[#30221b] md:text-2xl">
                        Journaux des emails
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden text-right md:block">
                    <p class="text-sm font-semibold text-[#30221b]">Administrateur</p>
                    <p class="text-xs text-[#9a897b]">Espace administration</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#30221b] shadow-sm">
                    <span class="text-sm font-bold text-[#f7b500]">A</span>
                </div>
            </div>
        </div>
    </header>

    <main class="space-y-8 p-5 md:p-6 lg:p-8">

       <!-- HERO : HISTORIQUE DES EMAILS -->
<section class="relative overflow-hidden rounded-[26px] bg-[#30221b] shadow-[0_18px_50px_rgba(48,34,27,0.14)]">

    <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
    <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#a98260]/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-7 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between">

        <!-- CONTENU PRINCIPAL -->
        <div class="max-w-2xl">

            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">

                <span class="h-2 w-2 rounded-full <?= (int) $echecs > 0 ? 'bg-red-400' : 'bg-[#6fd28b]' ?>"></span>

                <span class="text-xs font-medium text-[#ded3ca]">
                    Suivi des communications
                </span>

            </div>

            <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                Historique des emails
                <span class="text-[#f7b500]">envoyés</span>
            </h2>

            <p class="mt-4 max-w-xl text-sm leading-7 text-[#cfc3ba] sm:text-base">
                Consultez les emails enregistrés par IMMO-SN,
                recherchez un destinataire et identifiez rapidement
                les éventuels échecs d'envoi.
            </p>

        </div>

        <!-- CARTE STATISTIQUE -->
        <div class="shrink-0 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm lg:min-w-[260px]">

            <p class="text-xs uppercase tracking-[0.15em] text-[#bcaea3]">
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
                    class="h-full rounded-full bg-[#f7b500]"
                    style="width: <?= $tauxSuccesEmails ?>%;"
                ></div>
            </div>

            <div class="mt-3 flex items-center justify-between gap-3 text-[11px]">
                <span class="text-[#bcaea3]">
                    <?= $tauxSuccesEmails ?> % de succès
                </span>

                <span class="text-[#d98b8b]">
                    <?= (int) $echecs ?> échec(s)
                </span>
            </div>

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

            <!-- Cron Monitor -->
            <a href="/immo-sn/admin/cron-monitor"
               class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#8d7a6b] transition hover:bg-[#faf7f2] hover:text-[#30221b]">

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


                        <a
                            href="/immo-sn/admin/mail-logs"
                             
                            aria-current="page"
                            class="relative flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-bold text-[#30221b]"
                        
                       >
                        <svg xmlns="http://www.w3.org/2000/svg"
                      class="h-4 w-4 text-[#f7b500]"
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
                       <span class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full bg-[#f7b500]"></span>
                       
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
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#a98260]">
                    Vue d'ensemble
                </p>
                <h2 class="mt-1 text-xl font-bold text-[#30221b]">
                    Activité des emails
                </h2>
                <p class="mt-1 text-sm text-[#8b7b6f]">
                    Statistiques globales du journal, indépendantes des filtres.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#8b7b6f]">Total des emails</p>
                            <p class="mt-3 text-3xl font-bold text-[#30221b]"><?= $e($total) ?></p>
                            <p class="mt-2 text-xs text-[#a98260]">Depuis le début</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f2eadf] text-[#725238]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <article class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#8b7b6f]">Envoyés</p>
                            <p class="mt-3 text-3xl font-bold text-[#30221b]"><?= $e($envoyes) ?></p>
                            <p class="mt-2 text-xs text-[#497657]">Acceptés par le serveur</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#edf5ef] text-[#497657]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <article class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#8b7b6f]">Échecs</p>
                            <p class="mt-3 text-3xl font-bold text-[#30221b]"><?= $e($echecs) ?></p>
                            <p class="mt-2 text-xs text-[#a45d5d]">À vérifier si nécessaire</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f7eeee] text-[#a45d5d]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9L1.9 18.4A1.8 1.8 0 003.5 21h17a1.8 1.8 0 001.6-2.6L13.7 3.9a2 2 0 00-3.4 0z"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <article class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-[#8b7b6f]">Aujourd'hui</p>
                            <p class="mt-3 text-3xl font-bold text-[#30221b]"><?= $e($aujourdHui) ?></p>
                            <p class="mt-2 text-xs text-[#a98260]">Activité du jour</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#fff7df] text-[#a98260]">
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
        <section class="rounded-[24px] border border-[#e8ded4] bg-white p-5 shadow-sm sm:p-7">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                        Recherche
                    </p>
                    <h2 class="mt-1 text-lg font-bold text-[#30221b]">
                        Filtrer les journaux
                    </h2>
                </div>

                <a href="/immo-sn/admin/mail-logs"
                   class="inline-flex items-center justify-center gap-2 self-start rounded-xl border border-[#ded2c7] px-4 py-2.5 text-sm font-semibold text-[#725238] transition hover:bg-[#faf7f2] sm:self-auto">
                    Réinitialiser
                </a>
            </div>

            <form method="GET" action="/immo-sn/admin/mail-logs"
                  class="grid grid-cols-1 items-end gap-4 lg:grid-cols-[minmax(0,1fr)_240px_auto]">

                <div>
                    <label for="q" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#725238]">
                        Rechercher
                    </label>
                    <input
                        id="q"
                        name="q"
                        type="text"
                        value="<?= $e($recherche) ?>"
                        placeholder="Email, sujet ou modèle..."
                        class="w-full rounded-xl border border-[#ded2c7] bg-[#fffdfb] px-4 py-3.5 text-sm text-[#30221b] placeholder:text-[#b3a69c] focus:border-[#a98260] focus:outline-none focus:ring-2 focus:ring-[#f7b500]/20"
                    >
                </div>

                <div>
                    <label for="statut" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#725238]">
                        Statut
                    </label>
                   <select
    id="statut"
    name="statut"
    class="w-full rounded-xl border border-[#ded2c7] bg-[#fffdfb] px-4 py-3.5 text-sm text-[#30221b] focus:border-[#a98260] focus:outline-none focus:ring-2 focus:ring-[#f7b500]/20"
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
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#46352b] lg:w-auto">
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
        <section class="overflow-hidden rounded-[24px] border border-[#e8ded4] bg-white shadow-sm">

            <div class="border-b border-[#eee6de] px-5 py-5 md:px-7">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[#30221b]">Historique des emails</h2>
                        <p class="mt-1 text-sm text-[#9a897b]">
                            Consultez le détail de chaque envoi enregistré.
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-2 self-start rounded-xl border border-[#eee6de] bg-[#faf7f2] px-3 py-2 text-xs font-medium text-[#725238] sm:self-auto">
                        <span class="h-2 w-2 rounded-full bg-[#a98260]"></span>
                        <?= $e($debutResultats) ?>–<?= $e($finResultats) ?> sur <?= $e($totalLogs) ?> résultat(s)
                    </span>
                </div>
            </div>

           <div class="w-full overflow-x-auto">
    <table class="w-full min-w-[1000px] table-fixed">
                    <thead class="bg-[#faf7f2]">
                        <tr class="text-left text-[11px] uppercase tracking-[0.14em] text-[#8b7b6f]">
                            <th class="px-6 py-4 font-semibold">Destinataire</th>
                            <th class="px-6 py-4 font-semibold">Sujet</th>
                            <th class="px-6 py-4 font-semibold">Type</th>
                            <th class="px-6 py-4 font-semibold">Date</th>
                            <th class="px-6 py-4 font-semibold">Statut</th>
                            <th class="px-6 py-4 font-semibold">Détail</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#eee6de]">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f2eadf]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#725238]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-[#30221b]">Aucun email trouvé</h3>
                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#9a897b]">
                                    Aucun journal ne correspond aux critères sélectionnés.
                                    Modifiez la recherche ou réinitialisez les filtres.
                                </p>
                                <a href="/immo-sn/admin/mail-logs"
                                   class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#30221b] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#46352b]">
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
                                ? 'border-[#d5e8d9] bg-[#edf5ef] text-[#497657]'
                                : ($estEchec
                                    ? 'border-[#efdada] bg-[#f7eeee] text-[#a45d5d]'
                                    : 'border-[#eee0c6] bg-[#fff7df] text-[#8c6a24]');

                            $pointClasses = $estEnvoye
                                ? 'bg-[#5caa70]'
                                : ($estEchec ? 'bg-[#b86b6b]' : 'bg-[#c69a39]');

                            $erreurLog = trim((string) ($log['error'] ?? ''));
                            ?>
                            <tr class="transition hover:bg-[#fffdfb]">
                                <td class="px-6 py-5">
                                   <p class="max-w-[230px] break-words text-xs font-semibold text-[#30221b]">
                                    <?= $e($log['email_to'] ?? 'Destinataire inconnu') ?>
                                    </p>
                                </td>

                                <td class="px-6 py-5">
                                    <p class="max-w-xs truncate text-sm text-[#30221b]"
                                       title="<?= $e($log['subject'] ?? '') ?>">
                                        <?= $e(($log['subject'] ?? '') !== '' ? $log['subject'] : 'Sans sujet') ?>
                                    </p>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex max-w-[180px] break-words rounded-lg bg-[#f2eadf] px-2.5 py-1 text-xs font-medium text-[#725238]">
                                        <?= $e(($log['template'] ?? '') !== '' ? $log['template'] : 'Non précisé') ?>
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="text-sm text-[#725238]">
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
                                            <summary class="cursor-pointer text-xs font-semibold text-[#a45d5d] hover:underline">
                                                Voir l'erreur
                                            </summary>
                                            <p class="mt-2 whitespace-normal break-words rounded-lg bg-[#f7eeee] p-3 text-xs leading-5 text-[#725238]">
                                                <?= $e($erreurLog) ?>
                                            </p>
                                        </details>
                                    <?php elseif ($estEnvoye): ?>
                                        <span class="text-xs text-[#497657]">Aucune erreur</span>
                                    <?php else: ?>
                                        <span class="text-xs text-[#9a897b]">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="border-t border-[#eee6de] bg-[#fffdfb] px-5 py-5 md:px-7">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-[#30221b]">
                            Page <?= $e($page) ?> sur <?= $e($totalPages) ?>
                        </p>
                        <p class="mt-1 text-xs text-[#9a897b]">
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
                                   class="inline-flex items-center justify-center rounded-xl border border-[#ded2c7] bg-white px-3 py-2.5 text-sm font-semibold text-[#725238] transition hover:bg-[#f2eadf]"
                                   aria-label="Première page">
                                    <span class="hidden sm:inline">Première</span>
                                    <span class="sm:hidden">«</span>
                                </a>

                                <a href="<?= $e($urlPage($page - 1)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-[#ded2c7] bg-white px-3 py-2.5 text-sm font-semibold text-[#725238] transition hover:bg-[#f2eadf]"
                                   aria-label="Page précédente">
                                    <span class="hidden sm:inline">Précédente</span>
                                    <span class="sm:hidden">‹</span>
                                </a>
                            <?php else: ?>
                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-[#eee6de] bg-[#faf7f2] px-3 py-2.5 text-sm text-[#b3a69c]">
                                    <span class="hidden sm:inline">Première</span>
                                    <span class="sm:hidden">«</span>
                                </span>
                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-[#eee6de] bg-[#faf7f2] px-3 py-2.5 text-sm text-[#b3a69c]">
                                    <span class="hidden sm:inline">Précédente</span>
                                    <span class="sm:hidden">‹</span>
                                </span>
                            <?php endif; ?>

                            <!-- NUMÉROS DE PAGES -->
                            <?php if ($premierePageVisible > 1): ?>
                                <span class="px-1 text-sm text-[#9a897b]">…</span>
                            <?php endif; ?>

                            <?php for ($numero = $premierePageVisible; $numero <= $dernierePageVisible; $numero++): ?>
                                <?php if ($numero === $page): ?>
                                    <span aria-current="page"
                                          class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#30221b] bg-[#30221b] px-3 text-sm font-bold text-white">
                                        <?= $e($numero) ?>
                                    </span>
                                <?php else: ?>
                                    <a href="<?= $e($urlPage($numero)) ?>"
                                       class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#ded2c7] bg-white px-3 text-sm font-semibold text-[#725238] transition hover:border-[#a98260] hover:bg-[#f2eadf]">
                                        <?= $e($numero) ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($dernierePageVisible < $totalPages): ?>
                                <span class="px-1 text-sm text-[#9a897b]">…</span>
                            <?php endif; ?>

                            <!-- PAGE SUIVANTE -->
                            <?php if ($page < $totalPages): ?>
                                <a href="<?= $e($urlPage($page + 1)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-[#ded2c7] bg-white px-3 py-2.5 text-sm font-semibold text-[#725238] transition hover:bg-[#f2eadf]"
                                   aria-label="Page suivante">
                                    <span class="hidden sm:inline">Suivante</span>
                                    <span class="sm:hidden">›</span>
                                </a>

                                <a href="<?= $e($urlPage($totalPages)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-[#ded2c7] bg-white px-3 py-2.5 text-sm font-semibold text-[#725238] transition hover:bg-[#f2eadf]"
                                   aria-label="Dernière page">
                                    <span class="hidden sm:inline">Dernière</span>
                                    <span class="sm:hidden">»</span>
                                </a>
                            <?php else: ?>
                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-[#eee6de] bg-[#faf7f2] px-3 py-2.5 text-sm text-[#b3a69c]">
                                    <span class="hidden sm:inline">Suivante</span>
                                    <span class="sm:hidden">›</span>
                                </span>
                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-[#eee6de] bg-[#faf7f2] px-3 py-2.5 text-sm text-[#b3a69c]">
                                    <span class="hidden sm:inline">Dernière</span>
                                    <span class="sm:hidden">»</span>
                                </span>
                            <?php endif; ?>
                        </nav>
                    <?php else: ?>
                        <p class="text-xs text-[#9a897b]">
                            Tous les résultats tiennent sur une seule page.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

     

    </main>
</div>
