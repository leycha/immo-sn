
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

$tauxSuccesEmails = $total > 0
    ? min(100, (int) round(($envoyes / $total) * 100))
    : 0;

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
        'envoye', 'envoyé' => 'envoyé',
        'echec', 'failed', 'failure', 'échec' => 'échec',
        default => $value,
    };
};

/**
 * Pagination avec conservation des filtres.
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

$premierePageVisible = max(1, $page - 2);
$dernierePageVisible = min($totalPages, $page + 2);

?>

<div class="min-h-screen bg-[#f4f6f9] text-[#0b1528]">

    <!-- =========================================================
         HEADER
    ========================================================== -->
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
        <div class="flex min-h-[76px] items-center justify-between gap-4 px-5 py-4 lg:px-8">

            <div class="flex items-center gap-3 sm:gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#071b36] shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-[#f5b800]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 7l9 6 9-6"/>
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                    </svg>
                </div>

                <div>
                    <div class="mb-1 flex items-center gap-2 text-xs text-slate-500">
                        <span>Administration</span>
                        <span>/</span>
                        <span class="font-medium text-[#0e2a4e]">Mail Logs</span>
                    </div>

                    <h1 class="text-xl font-bold tracking-tight text-[#071b36] sm:text-2xl">
                        Journaux des emails
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden text-right md:block">
                    <p class="text-sm font-semibold text-[#071b36]">Administrateur</p>
                    <p class="text-xs text-slate-500">Espace administration</p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071b36]">
                    <span class="text-sm font-bold text-[#f5b800]">A</span>
                </div>
            </div>

        </div>
    </header>

    <main class="space-y-6 p-4 sm:p-5 lg:p-8">

        <!-- =====================================================
     HERO — MAIL LOGS COMPACT
====================================================== -->
<section class="relative overflow-hidden rounded-[24px] bg-[#071b36] shadow-[0_12px_35px_rgba(7,27,54,0.12)]">

    <!-- DÉCORATIONS -->
    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#0e2a4e]/60 blur-3xl"></div>

    <div class="relative flex flex-col gap-5 p-5 sm:p-6 lg:flex-row lg:items-center lg:justify-between">

        <!-- PRÉSENTATION -->
        <div class="max-w-2xl">

            <!-- BADGE -->
            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                <span class="h-2 w-2 rounded-full <?= $echecs > 0 ? 'bg-red-400' : 'bg-emerald-400' ?>"></span>
                <span class="text-xs font-medium text-slate-300">
                    Monitoring des communications
                </span>
            </div>

            <!-- TITRE -->
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-white sm:text-3xl lg:text-4xl">
                Gestion des emails
                <span class="text-[#f5b800]">système</span>
            </h2>

            <!-- DESCRIPTION -->
            <p class="mt-2.5 max-w-xl text-sm leading-6 text-slate-300">
                Surveillez les communications automatiques d'IMMO-SN,
                consultez les historiques d'envoi et identifiez
                rapidement les erreurs de transmission.
            </p>

            <!-- INDICATEURS -->
            <div class="mt-4 flex flex-wrap gap-2.5">

                <span class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-slate-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    <?= $e($envoyes) ?> email(s) envoyé(s)
                </span>

                <span class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-slate-300">
                    <span class="h-2 w-2 rounded-full bg-red-400"></span>
                    <?= $e($echecs) ?> échec(s)
                </span>

            </div>

        </div>

        <!-- INDICATEUR DE RÉUSSITE -->
        <div class="w-full shrink-0 rounded-2xl border border-white/10 bg-[#0e2a4e]/80 p-4 backdrop-blur-sm sm:p-5 lg:w-[260px]">

            <div class="flex items-center justify-between gap-3">

                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                    Taux de réussite
                </p>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#f5b800]/15">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4 text-[#f5b800]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

            </div>

            <!-- POURCENTAGE -->
            <div class="mt-2 flex items-end gap-2">
                <span class="text-3xl font-bold tabular-nums text-white">
                    <?= $tauxSuccesEmails ?>%
                </span>

                <span class="mb-1 text-xs text-slate-400">
                    emails acceptés
                </span>
            </div>

            <!-- BARRE DE PROGRESSION -->
            <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-white/10">
                <div class="h-full rounded-full bg-[#f5b800] transition-all duration-500"
                     style="width: <?= $tauxSuccesEmails ?>%;"></div>
            </div>

            <!-- DÉTAIL -->
            <div class="mt-2 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">
                    <?= $e($envoyes) ?> réussis
                </span>

                <span class="text-red-300">
                    <?= $e($echecs) ?> erreurs
                </span>
            </div>

        </div>

    </div>
</section>
        <!-- =====================================================
             NAVIGATION ADMIN
        ====================================================== -->
        <section>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <nav class="flex overflow-x-auto" aria-label="Navigation administration">

                    <!-- Biens -->
                    <a href="/immo-sn/admin/biens"
                       class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-slate-500 transition hover:bg-slate-50 hover:text-[#071b36]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                        </svg>
                        Biens
                    </a>

                    <!-- Signalements -->
                    <a href="/immo-sn/admin/signalements"
                       class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-slate-500 transition hover:bg-slate-50 hover:text-[#071b36]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v4m0 4h.01M10.3 3.9L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                        </svg>
                        Signalements
                    </a>

                    <!-- Cron Monitor -->
                    <a href="/immo-sn/admin/cron-monitor"
                       class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-slate-500 transition hover:bg-slate-50 hover:text-[#071b36]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 7v5l3 2"/>
                        </svg>
                        Cron Monitor
                    </a>

                    <!-- Mail Logs -->
                    <a href="/immo-sn/admin/mail-logs"
                       aria-current="page"
                       class="relative flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-bold text-[#071b36]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 text-[#f5b800]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 7l9 6 9-6"/>
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                        </svg>

                        Mail Logs

                        <span class="absolute bottom-0 left-4 right-4 h-[3px] rounded-full bg-[#f5b800]"></span>
                    </a>

                </nav>
            </div>
        </section>

        <!-- =====================================================
             ERREUR
        ====================================================== -->
        <?php if (!empty($erreurMailLogs)): ?>
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                <div class="flex items-start gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v4m0 4h.01M10.3 3.9L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="font-bold text-red-900">Chargement impossible</h2>
                        <p class="mt-1 text-sm leading-6 text-red-800">
                            <?= $e($erreurMailLogs) ?>
                        </p>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <!-- =====================================================
             STATISTIQUES
        ====================================================== -->
        <section>

            <div class="mb-5">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                    Vue d'ensemble
                </p>

                <h2 class="mt-1 text-xl font-bold tracking-tight text-[#071b36]">
                    Activité des emails
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Statistiques globales du système, indépendantes des filtres.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <!-- TOTAL -->
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total des emails</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-[#071b36]">
                                <?= $e($total) ?>
                            </p>
                            <p class="mt-2 text-xs text-slate-400">Depuis le début</p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071b36]/[0.06] text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4 6h16M4 12h16M4 18h10"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <!-- ENVOYES -->
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Envoyés</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-emerald-700">
                                <?= $e($envoyes) ?>
                            </p>
                            <p class="mt-2 text-xs text-emerald-600">Envois réussis</p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <!-- ECHECS -->
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Échecs</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight <?= $echecs > 0 ? 'text-red-700' : 'text-[#071b36]' ?>">
                                <?= $e($echecs) ?>
                            </p>
                            <p class="mt-2 text-xs text-red-600">À surveiller</p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v4m0 4h.01M10.3 3.9L1.9 18.4A1.8 1.8 0 003.5 21h17a1.8 1.8 0 001.6-2.6L13.7 3.9a2 2 0 00-3.4 0z"/>
                            </svg>
                        </div>
                    </div>
                </article>

                <!-- AUJOURD'HUI -->
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Aujourd'hui</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-[#071b36]">
                                <?= $e($aujourdHui) ?>
                            </p>
                            <p class="mt-2 text-xs text-[#0e2a4e]">Activité du jour</p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f5b800]/15 text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 3v4M8 3v4M3 11h18"/>
                            </svg>
                        </div>
                    </div>
                </article>

            </div>
        </section>

        <!-- =====================================================
             FILTRES
        ====================================================== -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                        Recherche
                    </p>
                    <h2 class="mt-1 text-lg font-bold text-[#071b36]">
                        Filtrer les journaux
                    </h2>
                </div>

                <a href="/immo-sn/admin/mail-logs"
                   class="inline-flex items-center justify-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-slate-50 sm:self-auto">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 4v6h6M4 10a8 8 0 0114-4M20 20v-6h-6M20 14a8 8 0 01-14 4"/>
                    </svg>
                    Réinitialiser
                </a>
            </div>

            <form method="GET"
                  action="/immo-sn/admin/mail-logs"
                  class="grid grid-cols-1 items-end gap-4 lg:grid-cols-[minmax(0,1fr)_240px_auto]">

                <!-- RECHERCHE -->
                <div>
                    <label for="q" class="mb-2 block text-xs font-semibold text-[#071b36]">
                        Email, sujet ou modèle
                    </label>

                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m16 16 4 4"/>
                        </svg>

                        <input
                            id="q"
                            name="q"
                            type="text"
                            value="<?= $e($recherche) ?>"
                            placeholder="Rechercher dans les emails..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-10 pr-4 text-sm text-[#071b36] placeholder:text-slate-400 outline-none transition focus:border-[#0e2a4e] focus:bg-white focus:ring-4 focus:ring-[#071b36]/[0.06]"
                        >
                    </div>
                </div>

                <!-- STATUT -->
                <div>
                    <label for="statut" class="mb-2 block text-xs font-semibold text-[#071b36]">
                        Statut d'envoi
                    </label>

                    <select
                        id="statut"
                        name="statut"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-[#071b36] outline-none transition focus:border-[#0e2a4e] focus:bg-white focus:ring-4 focus:ring-[#071b36]/[0.06]"
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

                <!-- BOUTON -->
                <div>
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#071b36] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0e2a4e] lg:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m16 16 4 4"/>
                        </svg>
                        Filtrer
                    </button>
                </div>

            </form>
        </section>

        <!-- =====================================================
             HISTORIQUE DES EMAILS
        ====================================================== -->
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <!-- HEADER -->
            <div class="border-b border-slate-200 px-5 py-5 sm:px-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#071b36]/[0.06]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4 text-[#071b36]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 7l9 6 9-6"/>
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                </svg>
                            </div>

                            <h2 class="text-lg font-bold text-[#071b36]">
                                Historique des emails
                            </h2>
                        </div>

                        <p class="mt-2 text-sm text-slate-500">
                            Consultez le détail de chaque envoi enregistré.
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-2 self-start rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-[#0e2a4e] sm:self-auto">
                        <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                        <?= $e($debutResultats) ?>–<?= $e($finResultats) ?>
                        sur <?= $e($totalLogs) ?> résultat(s)
                    </span>

                </div>
            </div>

            <!-- TABLE -->
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[1000px] table-fixed">

                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr class="text-left text-[11px] uppercase tracking-[0.12em] text-slate-500">
                            <th class="px-6 py-4 font-semibold">Destinataire</th>
                            <th class="px-6 py-4 font-semibold">Sujet</th>
                            <th class="px-6 py-4 font-semibold">Type</th>
                            <th class="px-6 py-4 font-semibold">Date</th>
                            <th class="px-6 py-4 font-semibold">Statut</th>
                            <th class="px-6 py-4 font-semibold">Détail</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                    <?php if (empty($logs)): ?>

                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">

                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-7 w-7 text-[#0e2a4e]"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                    </svg>
                                </div>

                                <h3 class="text-lg font-bold text-[#071b36]">
                                    Aucun email trouvé
                                </h3>

                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                    Aucun journal ne correspond aux critères sélectionnés.
                                    Modifiez votre recherche ou réinitialisez les filtres.
                                </p>

                                <a href="/immo-sn/admin/mail-logs"
                                   class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[#071b36] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#0e2a4e]">
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

                            <tr class="transition hover:bg-slate-50/80">

                                <!-- DESTINATAIRE -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#071b36]/[0.05]">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4 text-[#0e2a4e]"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M3 7l9 6 9-6"/>
                                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                            </svg>
                                        </div>

                                        <p class="max-w-[230px] break-words text-xs font-semibold text-[#071b36]">
                                            <?= $e($log['email_to'] ?? 'Destinataire inconnu') ?>
                                        </p>

                                    </div>
                                </td>

                                <!-- SUJET -->
                                <td class="px-6 py-5">
                                    <p class="max-w-xs truncate text-sm font-medium text-[#071b36]"
                                       title="<?= $e($log['subject'] ?? '') ?>">
                                        <?= $e(($log['subject'] ?? '') !== '' ? $log['subject'] : 'Sans sujet') ?>
                                    </p>
                                </td>

                                <!-- TYPE -->
                                <td class="px-6 py-5">
                                    <span class="inline-flex max-w-[180px] break-words rounded-lg bg-[#071b36]/[0.05] px-2.5 py-1.5 text-xs font-medium text-[#071b36]">
                                        <?= $e(($log['template'] ?? '') !== '' ? $log['template'] : 'Non précisé') ?>
                                    </span>
                                </td>

                                <!-- DATE -->
                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="text-sm text-slate-600">
                                        <?= $e($formatDate($log['sent_at'] ?? null)) ?>
                                    </span>
                                </td>

                                <!-- STATUT -->
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border px-2.5 py-1.5 text-xs font-semibold <?= $badgeClasses ?>">
                                        <span class="h-1.5 w-1.5 rounded-full <?= $pointClasses ?>"></span>
                                        <?= $e($statutLog !== '' ? $statutLog : 'Inconnu') ?>
                                    </span>
                                </td>

                                <!-- DETAIL -->
                                <td class="px-6 py-5">

                                    <?php if ($erreurLog !== ''): ?>

                                        <details class="max-w-xs">
                                            <summary class="cursor-pointer text-xs font-semibold text-red-700 transition hover:text-red-900">
                                                Voir l'erreur
                                            </summary>

                                            <p class="mt-2 whitespace-normal break-words rounded-xl border border-red-100 bg-red-50 p-3 text-xs leading-5 text-red-800">
                                                <?= $e($erreurLog) ?>
                                            </p>
                                        </details>

                                    <?php elseif ($estEnvoye): ?>

                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-3.5 w-3.5"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Aucune erreur
                                        </span>

                                    <?php else: ?>

                                        <span class="text-xs text-slate-400">—</span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <!-- =================================================
                 PAGINATION
            ================================================== -->
            <div class="border-t border-slate-200 bg-slate-50 px-5 py-5 sm:px-6">

                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    <div>
                        <p class="text-sm font-semibold text-[#071b36]">
                            Page <?= $e($page) ?> sur <?= $e($totalPages) ?>
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            <?= $e($debutResultats) ?> à <?= $e($finResultats) ?>
                            sur <?= $e($totalLogs) ?> journal(aux)
                        </p>
                    </div>

                    <?php if ($totalPages > 1): ?>

                        <nav aria-label="Pagination des journaux des emails"
                             class="flex flex-wrap items-center gap-2">

                            <!-- PREMIERE / PRECEDENTE -->
                            <?php if ($page > 1): ?>

                                <a href="<?= $e($urlPage(1)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-[#071b36] transition hover:border-[#0e2a4e] hover:bg-slate-100"
                                   aria-label="Première page">
                                    <span class="hidden sm:inline">Première</span>
                                    <span class="sm:hidden">«</span>
                                </a>

                                <a href="<?= $e($urlPage($page - 1)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-[#071b36] transition hover:border-[#0e2a4e] hover:bg-slate-100"
                                   aria-label="Page précédente">
                                    <span class="hidden sm:inline">Précédente</span>
                                    <span class="sm:hidden">‹</span>
                                </a>

                            <?php else: ?>

                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-400">
                                    <span class="hidden sm:inline">Première</span>
                                    <span class="sm:hidden">«</span>
                                </span>

                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-400">
                                    <span class="hidden sm:inline">Précédente</span>
                                    <span class="sm:hidden">‹</span>
                                </span>

                            <?php endif; ?>

                            <!-- ELLIPSIS -->
                            <?php if ($premierePageVisible > 1): ?>
                                <span class="px-1 text-sm text-slate-400">…</span>
                            <?php endif; ?>

                            <!-- NUMEROS -->
                            <?php for ($numero = $premierePageVisible; $numero <= $dernierePageVisible; $numero++): ?>

                                <?php if ($numero === $page): ?>

                                    <span aria-current="page"
                                          class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-[#071b36] bg-[#071b36] px-3 text-sm font-bold text-[#f5b800]">
                                        <?= $e($numero) ?>
                                    </span>

                                <?php else: ?>

                                    <a href="<?= $e($urlPage($numero)) ?>"
                                       class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-[#071b36] transition hover:border-[#0e2a4e] hover:bg-slate-100">
                                        <?= $e($numero) ?>
                                    </a>

                                <?php endif; ?>

                            <?php endfor; ?>

                            <?php if ($dernierePageVisible < $totalPages): ?>
                                <span class="px-1 text-sm text-slate-400">…</span>
                            <?php endif; ?>

                            <!-- SUIVANTE / DERNIERE -->
                            <?php if ($page < $totalPages): ?>

                                <a href="<?= $e($urlPage($page + 1)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-[#071b36] transition hover:border-[#0e2a4e] hover:bg-slate-100"
                                   aria-label="Page suivante">
                                    <span class="hidden sm:inline">Suivante</span>
                                    <span class="sm:hidden">›</span>
                                </a>

                                <a href="<?= $e($urlPage($totalPages)) ?>"
                                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-[#071b36] transition hover:border-[#0e2a4e] hover:bg-slate-100"
                                   aria-label="Dernière page">
                                    <span class="hidden sm:inline">Dernière</span>
                                    <span class="sm:hidden">»</span>
                                </a>

                            <?php else: ?>

                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-400">
                                    <span class="hidden sm:inline">Suivante</span>
                                    <span class="sm:hidden">›</span>
                                </span>

                                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-400">
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
