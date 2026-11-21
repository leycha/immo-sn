
<?php
/**
 * =========================================================
 * IMMO-SN — ADMINISTRATION
 * Page : Gestion des signalements
 * Design : Bleu nuit / Or
 * Fichier : app/Views/pages/admin/signalements.php
 * =========================================================
 */

$signalements = is_array($signalements ?? null) ? $signalements : [];
$filtres = is_array($filtres ?? null) ? $filtres : [];
$statsSignalements = is_array($statsSignalements ?? null) ? $statsSignalements : [];

$recherche = trim((string) ($filtres['q'] ?? $_GET['q'] ?? ''));
$filtreStatut = trim((string) ($filtres['statut'] ?? $_GET['statut'] ?? ''));

$page = max(1, (int) ($page ?? 1));
$parPage = max(1, (int) ($parPage ?? 6));
$totalSignalements = max(0, (int) ($totalSignalements ?? 0));
$totalPages = max(1, (int) ($totalPages ?? 1));

$messageAdmin = trim((string) ($_GET['message'] ?? ''));
$typeMessageAdmin = (string) ($_GET['type'] ?? 'succes');

$e = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

$normaliserStatut = static function (string $statut): string {
    $statut = mb_strtolower(trim($statut), 'UTF-8');
    $statut = str_replace(['-', ' '], '_', $statut);

    return match ($statut) {
        'traité', 'resolu', 'résolu', 'resolved' => 'traite',
        'rejeté', 'refusé', 'refuse', 'rejected' => 'rejete',
        'pending', 'nouveau' => 'en_attente',
        default => $statut !== '' ? $statut : 'en_attente',
    };
};

$filtreStatutNormalise = $normaliserStatut($filtreStatut);

$signalementStatut = static function (string $statut) use ($normaliserStatut): array {
    return match ($normaliserStatut($statut)) {
        'traite' => [
            'Traité',
            'border-emerald-200 bg-emerald-50 text-emerald-700',
            'bg-emerald-500',
        ],
        'rejete' => [
            'Rejeté',
            'border-red-200 bg-red-50 text-red-700',
            'bg-red-500',
        ],
        default => [
            'En attente',
            'border-amber-200 bg-amber-50 text-amber-800',
            'bg-[#f5b800]',
        ],
    };
};

$formatDate = static function ($date): string {
    if (empty($date)) {
        return 'Date non disponible';
    }

    try {
        return (new DateTimeImmutable((string) $date))->format('d/m/Y à H:i');
    } catch (Throwable $exception) {
        return 'Date non disponible';
    }
};

$totalGlobal = max(
    0,
    (int) ($statsSignalements['total'] ?? $totalSignalements)
);

$enAttente = max(0, (int) ($statsSignalements['en_attente'] ?? 0));
$traites = max(0, (int) ($statsSignalements['traites'] ?? 0));
$rejetes = max(0, (int) ($statsSignalements['rejetes'] ?? 0));

$nombreFiltres = $totalSignalements;
$signalementsFiltres = $signalements;

$urlPage = static function (int $numero) use ($recherche, $filtreStatut): string {
    $parametres = ['page' => $numero];

    if ($recherche !== '') {
        $parametres['q'] = $recherche;
    }

    if ($filtreStatut !== '') {
        $parametres['statut'] = $filtreStatut;
    }

    return '/immo-sn/admin/signalements?' . http_build_query($parametres);
};

$premierResultat = $totalSignalements > 0
    ? (($page - 1) * $parPage) + 1
    : 0;

$dernierResultat = min($page * $parPage, $totalSignalements);
?>

<div class="min-h-screen bg-[#f3f6fb] text-[#0b1528]">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                        <span>Administration</span>
                        <span>/</span>
                        <span class="text-[#0e2a4e]">Modération</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#071b36] sm:text-3xl">
                        Gestion des signalements
                    </h1>
                </div>

                <a href="/immo-sn/admin"
                   class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 transition hover:border-[#f5b800] hover:bg-slate-50">

                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071b36] text-[#f5b800]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 10.5 12 3l9 7.5M5.25 9v11.25h13.5V9M9 20.25v-7.5h6v7.5"/>
                        </svg>
                    </span>

                    <span>
                        <span class="block text-sm font-bold text-[#071b36]">Administrateur</span>
                        <span class="mt-0.5 block text-xs text-slate-500">Tableau de bord</span>
                    </span>
                </a>
            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

            <!-- =====================================================
                 MESSAGE FLASH
            ====================================================== -->

            <?php if ($messageAdmin !== ''): ?>
                <div id="message-admin"
                     role="alert"
                     class="mb-6 flex items-start gap-3 rounded-2xl border px-5 py-4 text-sm font-semibold shadow-sm
                     <?= $typeMessageAdmin === 'erreur'
                        ? 'border-red-200 bg-red-50 text-red-800'
                        : 'border-emerald-200 bg-emerald-50 text-emerald-800' ?>">

                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl
                        <?= $typeMessageAdmin === 'erreur' ? 'bg-red-100' : 'bg-emerald-100' ?>">
                        <?= $typeMessageAdmin === 'erreur' ? '⚠' : '✓' ?>
                    </span>

                    <p class="flex-1 pt-1"><?= $e($messageAdmin) ?></p>

                    <button type="button"
                            onclick="document.getElementById('message-admin')?.remove()"
                            class="text-lg opacity-60 transition hover:opacity-100"
                            aria-label="Fermer le message">
                        &times;
                    </button>
                </div>
            <?php endif; ?>

            <!-- =====================================================
                 HERO
            ====================================================== -->

            <section class="relative overflow-hidden rounded-[28px] bg-[#071b36] shadow-xl shadow-[#071b36]/10">

                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#0e2a4e]/70 blur-3xl"></div>

                <div class="relative flex flex-col gap-8 px-6 py-8 sm:px-9 lg:flex-row lg:items-center lg:justify-between lg:py-10">

                    <div class="max-w-2xl">

                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                            <span class="text-xs font-medium text-slate-300">Centre de modération</span>
                        </div>

                        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl">
                            Gardez la plateforme
                            <span class="text-[#f5b800]">fiable et sécurisée</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">
                            Examinez les motifs signalés, identifiez les utilisateurs concernés
                            et assurez la qualité des annonces publiées sur IMMO-SN.
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs text-slate-300">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                <?= number_format($enAttente, 0, ',', ' ') ?> à examiner
                            </div>

                            <div class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs text-slate-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                <?= number_format($traites, 0, ',', ' ') ?> traités
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0 rounded-2xl border border-white/10 bg-[#0e2a4e]/80 p-5 backdrop-blur-sm lg:min-w-[280px]">

                        <div class="flex items-center justify-between gap-5">
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-400">
                                    Total enregistré
                                </p>

                                <p class="mt-2 text-4xl font-bold tabular-nums text-white">
                                    <?= number_format($totalGlobal, 0, ',', ' ') ?>
                                </p>

                                <p class="mt-1 text-xs text-slate-300">
                                    signalement(s) conservé(s)
                                </p>
                            </div>

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#f5b800]/15 text-[#f5b800]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-7 w-7"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 8v4l3 2"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full w-full rounded-full bg-[#f5b800]"></div>
                        </div>

                        <p class="mt-2 text-right text-[11px] text-slate-400">
                            <?= number_format($nombreFiltres, 0, ',', ' ') ?> résultat(s) filtré(s)
                        </p>
                    </div>
                </div>
            </section>

            <!-- =====================================================
                 NAVIGATION ADMIN
            ====================================================== -->

            <section class="mt-7">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <nav class="flex overflow-x-auto"
                         aria-label="Navigation administration">

                        <a href="/immo-sn/admin/biens"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                            </svg>
                            Biens
                        </a>

                        <a href="/immo-sn/admin/signalements"
                           aria-current="page"
                           class="relative flex shrink-0 items-center gap-2 bg-[#071b36]/[0.03] px-5 py-4 text-sm font-bold text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 text-[#f5b800]"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                            </svg>
                            Signalements
                            <span class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full bg-[#f5b800]"></span>
                        </a>

                        <a href="/immo-sn/admin/cron-monitor"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 7v5l3 2"/>
                            </svg>
                            Cron Monitor
                        </a>

                        <a href="/immo-sn/admin/mail-logs"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m3 7 9 6 9-6"/>
                            </svg>
                            Mail Logs
                        </a>
                    </nav>
                </div>
            </section>

            <!-- =====================================================
                 STATISTIQUES
            ====================================================== -->

            <section class="mt-8">

                <div class="mb-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                        Indicateurs de modération
                    </p>

                    <h2 class="mt-1 text-xl font-bold tracking-tight text-[#071b36] sm:text-2xl">
                        Vue d'ensemble
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Suivez les signalements et leur état de traitement.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <!-- TOTAL -->

                    <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#071b36]/5 transition-transform duration-500 group-hover:scale-150"></div>

                        <div class="relative flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Total enregistré</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-[#071b36]">
                                    <?= number_format($totalGlobal, 0, ',', ' ') ?>
                                </p>
                                <p class="mt-1 text-xs text-slate-500">Tous statuts confondus</p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#071b36]/10 text-[#071b36]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.8">
                                    <rect x="4" y="3" width="16" height="18" rx="2"/>
                                    <path stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/>
                                </svg>
                            </div>
                        </div>
                    </article>

                    <!-- EN ATTENTE -->

                    <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#f5b800]/10 transition-transform duration-500 group-hover:scale-150"></div>

                        <div class="relative flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-500">En attente</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-[#071b36]">
                                    <?= number_format($enAttente, 0, ',', ' ') ?>
                                </p>
                                <p class="mt-1 text-xs text-amber-700">À examiner</p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 7v5l3 2"/>
                                </svg>
                            </div>
                        </div>
                    </article>

                    <!-- TRAITES -->

                    <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-emerald-500/10 transition-transform duration-500 group-hover:scale-150"></div>

                        <div class="relative flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Traités</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-[#071b36]">
                                    <?= number_format($traites, 0, ',', ' ') ?>
                                </p>
                                <p class="mt-1 text-xs text-emerald-700">Traitement effectué</p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m5 12 4 4L19 6"/>
                                </svg>
                            </div>
                        </div>
                    </article>

                    <!-- REJETES -->

                    <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-red-500/10 transition-transform duration-500 group-hover:scale-150"></div>

                        <div class="relative flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Rejetés</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-[#071b36]">
                                    <?= number_format($rejetes, 0, ',', ' ') ?>
                                </p>
                                <p class="mt-1 text-xs text-red-700">Signalements rejetés</p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m6 6 12 12M18 6 6 18"/>
                                </svg>
                            </div>
                        </div>
                    </article>

                </div>
            </section>

            <!-- =====================================================
                 FILTRES
            ====================================================== -->

            <section class="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-5 sm:px-7">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071b36] text-[#f5b800]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M4 5h16M7 12h10M10 19h4"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                                Recherche avancée
                            </p>
                            <h2 class="mt-1 text-lg font-bold text-[#071b36]">
                                Filtrer les signalements
                            </h2>
                        </div>
                    </div>

                    <p class="mt-2 text-sm text-slate-500">
                        Recherchez par annonce, motif, propriétaire ou signaleur.
                    </p>
                </div>

                <div class="p-6 sm:p-7">
                    <form method="GET"
                          action="/immo-sn/admin/signalements"
                          class="grid gap-4 xl:grid-cols-[minmax(220px,1fr)_240px_auto_auto]">

                        <input type="hidden" name="page" value="1">

                        <div>
                            <label for="q" class="mb-2 block text-xs font-semibold text-[#071b36]">
                                Recherche
                            </label>

                            <input id="q"
                                   name="q"
                                   type="search"
                                   value="<?= $e($recherche) ?>"
                                   placeholder="Titre, motif, nom, e-mail..."
                                   class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-[#071b36] outline-none transition placeholder:text-slate-400 focus:border-[#071b36] focus:bg-white focus:ring-4 focus:ring-[#071b36]/10">
                        </div>

                        <div>
                            <label for="statut" class="mb-2 block text-xs font-semibold text-[#071b36]">
                                Statut
                            </label>

                            <select id="statut"
                                    name="statut"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071b36] outline-none transition focus:border-[#071b36] focus:bg-white focus:ring-4 focus:ring-[#071b36]/10">

                                <option value="" <?= $filtreStatut === '' ? 'selected' : '' ?>>
                                    Tous les statuts
                                </option>

                                <option value="en_attente" <?= $filtreStatutNormalise === 'en_attente' && $filtreStatut !== '' ? 'selected' : '' ?>>
                                    En attente
                                </option>

                                <option value="traite" <?= $filtreStatutNormalise === 'traite' ? 'selected' : '' ?>>
                                    Traités
                                </option>

                                <option value="rejete" <?= $filtreStatutNormalise === 'rejete' ? 'selected' : '' ?>>
                                    Rejetés
                                </option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#071b36] px-5 text-sm font-bold text-white transition hover:bg-[#0e2a4e] xl:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.8">
                                    <circle cx="10.5" cy="10.5" r="6.5"/>
                                    <path stroke-linecap="round" d="m16 16 5 5"/>
                                </svg>
                                Rechercher
                            </button>
                        </div>

                        <div class="flex items-end">
                            <a href="/immo-sn/admin/signalements"
                               class="inline-flex h-12 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-[#071b36] transition hover:bg-slate-50 xl:w-auto">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </section>

            <!-- =====================================================
                 LISTE
            ====================================================== -->

            <section class="mt-8">

                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                            Modération
                        </p>

                        <h2 class="mt-1 text-xl font-bold tracking-tight text-[#071b36] sm:text-2xl">
                            Liste des signalements
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Consultez les détails et choisissez l'action adaptée.
                        </p>
                    </div>

                    <div class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2">
                        <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                        <span class="text-xs font-bold text-[#071b36]">
                            <?= number_format($nombreFiltres, 0, ',', ' ') ?> résultat(s)
                        </span>
                    </div>
                </div>

                <?php if (empty($signalementsFiltres)): ?>

                    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#071b36]/5 text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-8 w-8"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M4 5h16v12H7l-3 3V5z"/>
                                <path stroke-linecap="round" d="M8 9h8M8 13h5"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#071b36]">
                            Aucun signalement trouvé
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            Aucun signalement ne correspond à votre recherche.
                            Modifiez les filtres ou réinitialisez-les.
                        </p>

                        <?php if ($recherche !== '' || $filtreStatut !== ''): ?>
                            <a href="/immo-sn/admin/signalements"
                               class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#071b36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0e2a4e]">
                                Afficher tous les signalements
                            </a>
                        <?php endif; ?>
                    </div>

                <?php else: ?>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($signalementsFiltres as $signalement): ?>

                            <?php
                            $id = (string) ($signalement['id'] ?? '');
                            $bienId = (string) ($signalement['bien_id'] ?? '');

                            $statut = $normaliserStatut(
                                (string) ($signalement['statut'] ?? 'en_attente')
                            );

                            $titreBien = trim((string) ($signalement['bien_titre'] ?? ''));

                            if ($titreBien === '') {
                                $titreBien = 'Bien immobilier #' . substr($bienId, 0, 8);
                            }

                            $nomProprietaire = trim(
                                (string) ($signalement['proprietaire_prenom'] ?? '') . ' ' .
                                (string) ($signalement['proprietaire_nom'] ?? '')
                            );

                            if ($nomProprietaire === '') {
                                $nomProprietaire = 'Propriétaire non renseigné';
                            }

                            $nomSignaleur = trim(
                                (string) ($signalement['signaleur_prenom'] ?? '') . ' ' .
                                (string) ($signalement['signaleur_nom'] ?? '')
                            );

                            if ($nomSignaleur === '') {
                                $nomSignaleur = 'Utilisateur non renseigné';
                            }

                            $motif = trim((string) ($signalement['motif'] ?? ''));
                            $description = trim((string) ($signalement['description'] ?? ''));
                            $dateCreation = $formatDate($signalement['created_at'] ?? null);

                            [$libelleStatut, $classeBadge, $classePoint] = $signalementStatut($statut);
                            ?>

                            <article class="flex h-full flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                                <div>
                                    <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-4">
                                        <div class="min-w-0">
                                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#0e2a4e]">
                                                Signalement #<?= $e(substr($id, 0, 8)) ?>
                                            </p>

                                            <h3 class="mt-1 break-words text-base font-bold text-[#071b36]">
                                                <?= $e($titreBien) ?>
                                            </h3>
                                        </div>

                                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-bold <?= $e($classeBadge) ?>">
                                            <span class="h-1.5 w-1.5 rounded-full <?= $e($classePoint) ?>"></span>
                                            <?= $e($libelleStatut) ?>
                                        </span>
                                    </div>

                                    <div class="mt-4 space-y-3">

                                        <div>
                                            <p class="text-xs font-semibold text-slate-500">Motif du signalement</p>
                                            <p class="mt-1 text-sm font-bold text-[#071b36]">
                                                <?= $e($motif !== '' ? $motif : 'Motif non précisé') ?>
                                            </p>
                                        </div>

                                        <?php if ($description !== ''): ?>
                                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                                <p class="text-xs leading-6 text-slate-600">
                                                    <?= $e($description) ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <dl class="mt-4 space-y-3 border-t border-slate-100 pt-4 text-xs">

                                        <div class="flex items-start justify-between gap-3">
                                            <dt class="shrink-0 text-slate-500">Propriétaire</dt>
                                            <dd class="text-right font-semibold text-[#071b36]">
                                                <?= $e($nomProprietaire) ?>
                                            </dd>
                                        </div>

                                        <div class="flex items-start justify-between gap-3">
                                            <dt class="shrink-0 text-slate-500">Signalé par</dt>
                                            <dd class="text-right font-semibold text-[#071b36]">
                                                <?= $e($nomSignaleur) ?>
                                            </dd>
                                        </div>

                                        <div class="flex items-start justify-between gap-3">
                                            <dt class="shrink-0 text-slate-500">Date</dt>
                                            <dd class="text-right font-medium text-[#0e2a4e]">
                                                <?= $e($dateCreation) ?>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div class="mt-5 space-y-2 border-t border-slate-100 pt-4">

                                    <?php if ($bienId !== ''): ?>
                                        <a href="/immo-sn/biens/<?= rawurlencode($bienId) ?>"
                                           class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-[#071b36] transition hover:bg-slate-50">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M15 3h6v6M21 3l-9 9M5 7H3v14h14v-2"/>
                                            </svg>
                                            Consulter le bien
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($statut === 'en_attente' && $id !== ''): ?>
                                        <div class="grid grid-cols-2 gap-2">

                                            <form method="POST"
                                                  action="/immo-sn/admin/signalements/<?= rawurlencode($id) ?>/traiter">
                                                <button type="submit"
                                                        class="w-full rounded-xl bg-[#071b36] px-3 py-2.5 text-xs font-bold text-white transition hover:bg-[#0e2a4e]">
                                                    Traiter
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="/immo-sn/admin/signalements/<?= rawurlencode($id) ?>/rejeter">
                                                <button type="submit"
                                                        class="w-full rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-bold text-red-700 transition hover:bg-red-100">
                                                    Rejeter
                                                </button>
                                            </form>

                                        </div>
                                    <?php endif; ?>

                                </div>
                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

                <!-- =====================================================
                     PAGINATION
                ====================================================== -->

                <?php if ($totalSignalements > 0): ?>
                    <div class="mt-7 flex flex-col items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm sm:flex-row">

                        <p class="text-xs text-slate-500">
                            Affichage de
                            <strong class="text-[#071b36]"><?= $premierResultat ?></strong>
                            à
                            <strong class="text-[#071b36]"><?= $dernierResultat ?></strong>
                            sur
                            <strong class="text-[#071b36]"><?= number_format($totalSignalements, 0, ',', ' ') ?></strong>
                            signalement(s)
                        </p>

                        <?php if ($totalPages > 1): ?>
                            <nav class="flex flex-wrap items-center gap-2"
                                 aria-label="Pagination des signalements">

                                <a href="<?= $e($urlPage(max(1, $page - 1))) ?>"
                                   class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-[#071b36] transition hover:bg-slate-50 <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>"
                                   <?= $page <= 1 ? 'aria-disabled="true"' : '' ?>>
                                    ← Précédent
                                </a>

                                <span class="rounded-xl bg-[#071b36]/5 px-3 py-2 text-xs font-bold text-[#071b36]">
                                    Page <?= $page ?> / <?= $totalPages ?>
                                </span>

                                <a href="<?= $e($urlPage(min($totalPages, $page + 1))) ?>"
                                   class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-[#071b36] transition hover:bg-slate-50 <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>"
                                   <?= $page >= $totalPages ? 'aria-disabled="true"' : '' ?>>
                                    Suivant →
                                </a>
                            </nav>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

            </section>

        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const message = document.getElementById('message-admin');

    if (!message) {
        return;
    }

    setTimeout(function () {
        message.style.opacity = '0';

        setTimeout(function () {
            message.remove();

            const url = new URL(window.location.href);
            url.searchParams.delete('message');
            url.searchParams.delete('type');

            window.history.replaceState(
                {},
                document.title,
                url.pathname + url.search + url.hash
            );
        }, 500);
    }, 4000);
});
</script>
