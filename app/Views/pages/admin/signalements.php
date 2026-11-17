
<?php
/**
 * =========================================================
 * IMMO-SN — ADMINISTRATION
 * Page : Gestion des signalements
 * Design harmonisé avec Cron Monitor
 * =========================================================
 */

$signalements = is_array($signalements ?? null) ? $signalements : [];
$filtres = is_array($filtres ?? null) ? $filtres : [];
$statsSignalements = is_array($statsSignalements ?? null)
    ? $statsSignalements
    : [];

$recherche = trim((string) ($filtres['q'] ?? $_GET['q'] ?? ''));
$filtreStatut = trim((string) ($filtres['statut'] ?? $_GET['statut'] ?? ''));

$page = max(1, (int) ($page ?? 1));
$parPage = max(1, (int) ($parPage ?? 6));
$totalSignalements = max(0, (int) ($totalSignalements ?? 0));
$totalPages = max(1, (int) ($totalPages ?? 1));

$messageAdmin = trim((string) ($_GET['message'] ?? ''));
$typeMessageAdmin = (string) ($_GET['type'] ?? 'succes');

$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
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
            'border-[#cce5d2] bg-[#edf5ef] text-[#497657]',
            'bg-[#5caa70]',
        ],
        'rejete' => [
            'Rejeté',
            'border-[#f1cfca] bg-[#fbe9e7] text-[#a43d35]',
            'bg-[#c75b50]',
        ],
        default => [
            'En attente',
            'border-[#f1dfaa] bg-[#fff4cf] text-[#9a7000]',
            'bg-[#e0ac16]',
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

/*
 * Statistiques globales, fournies par AdminController.
 * Le total filtré reste distinct du total global.
 */
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

<div class="min-h-screen bg-[#faf7f2]">

    <!-- =====================================================
         EN-TÊTE
    ====================================================== -->

    <header class="sticky top-0 z-20 border-b border-[#e8ded4] bg-[#faf7f2]/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-[#a98260]">
                        <span>Administration</span>
                        <span class="text-[#cbbcad]">/</span>
                        <span class="text-[#8d7a6b]">Modération</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#30221b] sm:text-3xl">
                        Gestion des signalements
                    </h1>
                </div>

                <a href="/immo-sn/admin"
                   class="inline-flex w-fit items-center gap-3 rounded-2xl border border-[#e8ded4] bg-white px-4 py-3 transition hover:bg-[#f3ece5]">

                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#30221b] text-[#f7b500]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 10.5 12 3l9 7.5M5.25 9v11.25h13.5V9M9 20.25v-7.5h6v7.5"/>
                        </svg>
                    </span>

                    <span>
                        <span class="block text-sm font-bold text-[#30221b]">
                            Administrateur
                        </span>
                        <span class="mt-0.5 block text-xs text-[#9a887a]">
                            Tableau de bord
                        </span>
                    </span>
                </a>

            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

            <!-- =====================================================
                 MESSAGE
            ====================================================== -->

            <?php if ($messageAdmin !== ''): ?>
                <div id="message-admin"
                     role="alert"
                     class="mb-6 flex items-start gap-3 rounded-2xl border px-5 py-4 text-sm font-semibold shadow-sm
                     <?= $typeMessageAdmin === 'erreur'
                        ? 'border-red-200 bg-red-50 text-red-800'
                        : 'border-green-200 bg-green-50 text-green-800' ?>">

                    <span class="text-lg">
                        <?= $typeMessageAdmin === 'erreur' ? '⚠' : '✓' ?>
                    </span>

                    <p class="flex-1"><?= $e($messageAdmin) ?></p>

                    <button type="button"
                            onclick="document.getElementById('message-admin')?.remove()"
                            class="text-lg opacity-60 transition hover:opacity-100"
                            aria-label="Fermer le message">
                        &times;
                    </button>
                </div>
            <?php endif; ?>

            <!-- =====================================================
                 BANDEAU PRINCIPAL
            ====================================================== -->

            <section class="relative overflow-hidden rounded-[26px] bg-[#30221b] shadow-[0_18px_50px_rgba(48,34,27,0.14)]">

                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#a98260]/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-7 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between">

                    <div class="max-w-2xl">

                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>
                            <span class="text-xs font-medium text-[#ded3ca]">
                                Centre de modération
                            </span>
                        </div>

                        <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                            Gestion des
                            <span class="text-[#f7b500]">signalements</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-[#cfc3ba] sm:text-base">
                            Examinez les motifs signalés, identifiez les utilisateurs
                            concernés et assurez la qualité des annonces publiées sur IMMO-SN.
                        </p>

                    </div>

                    <div class="shrink-0 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm lg:min-w-[270px]">

                        <div class="flex items-center justify-between gap-5">

                            <div>
                                <p class="text-xs uppercase tracking-[0.15em] text-[#bcaea3]">
                                    Total enregistré
                                </p>

                                <p class="mt-2 text-3xl font-bold tabular-nums text-white">
                                    <?= number_format($totalGlobal, 0, ',', ' ') ?>
                                </p>

                                <p class="mt-1 text-xs text-[#cfc3ba]">
                                    signalement(s) conservé(s)
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#f7b500]/10 text-[#f7b500]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 8v4l3 2"/>
                                    <circle cx="12" cy="12" r="9"/>
                                </svg>
                            </div>

                        </div>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full w-full rounded-full bg-[#f7b500]"></div>
                        </div>

                        <p class="mt-2 text-right text-[11px] text-[#bcaea3]">
                            <?= number_format($nombreFiltres, 0, ',', ' ') ?> résultat(s) selon les filtres
                        </p>

                    </div>
                </div>
            </section>

            <!-- =====================================================
                 NAVIGATION ADMINISTRATION
            ====================================================== -->

            <section class="mt-7">
                <div class="overflow-hidden rounded-[20px] border border-[#e8ded4] bg-white shadow-[0_8px_25px_rgba(48,34,27,0.04)]">

                    <nav class="flex overflow-x-auto px-2 pt-2"
                         aria-label="Navigation administration">

                        <a href="/immo-sn/admin/biens"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#8d7a6b] transition hover:bg-[#faf7f2] hover:text-[#30221b]">
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
                           class="relative flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-bold text-[#30221b]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 text-[#f7b500]"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                            </svg>
                            Signalements
                            <span class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full bg-[#f7b500]"></span>
                        </a>

                        <a href="/immo-sn/admin/cron-monitor"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#8d7a6b] transition hover:bg-[#faf7f2] hover:text-[#30221b]">
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
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#8d7a6b] transition hover:bg-[#faf7f2] hover:text-[#30221b]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 7l9 6 9-6"/>
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                            </svg>
                            Mail Logs
                        </a>

                    </nav>
                </div>
            </section>

            <!-- =====================================================
                 STATISTIQUES
            ====================================================== -->

            <section class="mt-7">

                <div class="mb-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">
                        Modération
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-[#30221b] sm:text-2xl">
                        Vue d'ensemble
                    </h2>

                    <p class="mt-1 text-sm text-[#8d7a6b]">
                        Suivez les signalements et leur état de traitement.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <!-- TOTAL -->
                    <article class="group relative overflow-hidden rounded-[22px] border border-[#e8ded4] bg-white p-6 shadow-[0_8px_30px_rgba(48,34,27,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(48,34,27,0.10)]">

                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#a98260]/10 transition-transform duration-500 group-hover:scale-150"></div>

                        <div class="relative flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-[#8d7a6b]">
                                    Total enregistré
                                </p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                                    <?= number_format($totalGlobal, 0, ',', ' ') ?>
                                </p>
                                <p class="mt-1 text-xs text-[#9a887a]">
                                    tous statuts confondus
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#f2e8e1] text-[#5a402d]">
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
                    <article class="group relative overflow-hidden rounded-[22px] border border-[#e8ded4] bg-white p-6 shadow-[0_8px_30px_rgba(48,34,27,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(48,34,27,0.10)]">

                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#f7b500]/10 transition-transform duration-500 group-hover:scale-150"></div>

                        <div class="relative flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-[#8d7a6b]">
                                    En attente
                                </p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                                    <?= number_format($enAttente, 0, ',', ' ') ?>
                                </p>
                                <p class="mt-1 text-xs text-[#9a887a]">
                                    à examiner
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#fff4cf] text-[#9a7000]">
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
                    <article class="group relative overflow-hidden rounded-[22px] border border-[#e8ded4] bg-white p-6 shadow-[0_8px_30px_rgba(48,34,27,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(48,34,27,0.10)]">

                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#5caa70]/10 transition-transform duration-500 group-hover:scale-150"></div>

                        <div class="relative flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-[#8d7a6b]">
                                    Traités
                                </p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                                    <?= number_format($traites, 0, ',', ' ') ?>
                                </p>
                                <p class="mt-1 text-xs text-[#497657]">
                                    traitement effectué
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#edf5ef] text-[#497657]">
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
                    <article class="group relative overflow-hidden rounded-[22px] border border-[#e8ded4] bg-white p-6 shadow-[0_8px_30px_rgba(48,34,27,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(48,34,27,0.10)]">

                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#c75b50]/10 transition-transform duration-500 group-hover:scale-150"></div>

                        <div class="relative flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-[#8d7a6b]">
                                    Rejetés
                                </p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                                    <?= number_format($rejetes, 0, ',', ' ') ?>
                                </p>
                                <p class="mt-1 text-xs text-[#a43d35]">
                                    signalements rejetés
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#fbe9e7] text-[#a43d35]">
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
                 RECHERCHE ET FILTRES
            ====================================================== -->

            <section class="mt-7 overflow-hidden rounded-[24px] border border-[#e8ded4] bg-white shadow-[0_8px_30px_rgba(48,34,27,0.05)]">

                <div class="border-b border-[#eee6de] px-6 py-5 sm:px-7">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">
                        Recherche avancée
                    </p>

                    <h2 class="mt-1 text-lg font-bold text-[#30221b]">
                        Filtrer les signalements
                    </h2>

                    <p class="mt-1 text-sm text-[#8d7a6b]">
                        Recherchez par annonce, motif, propriétaire ou signaleur.
                    </p>
                </div>

                <div class="p-6 sm:p-7">
                    <form method="GET"
                          action="/immo-sn/admin/signalements"
                          class="grid gap-4 xl:grid-cols-[minmax(220px,1fr)_240px_auto_auto]">

                        <input type="hidden" name="page" value="1">

                        <div>
                            <label for="q"
                                   class="mb-2 block text-xs font-semibold text-[#5a402d]">
                                Recherche
                            </label>

                            <input id="q"
                                   name="q"
                                   type="search"
                                   value="<?= $e($recherche) ?>"
                                   placeholder="Titre, motif, nom, e-mail..."
                                   class="h-12 w-full rounded-xl border border-[#ddd2c7] bg-[#fdfbf9] px-4 text-sm text-[#30221b] outline-none transition placeholder:text-[#aa9a8d] focus:border-[#a98260] focus:bg-white focus:ring-4 focus:ring-[#a98260]/10">
                        </div>

                        <div>
                            <label for="statut"
                                   class="mb-2 block text-xs font-semibold text-[#5a402d]">
                                Statut
                            </label>

                            <select id="statut"
                                    name="statut"
                                    class="h-12 w-full rounded-xl border border-[#ddd2c7] bg-[#fdfbf9] px-3 text-sm text-[#5a402d] outline-none transition focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10">

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
                                    class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 text-sm font-bold text-white transition hover:bg-[#5a402d] xl:w-auto">
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
                               class="inline-flex h-12 w-full items-center justify-center rounded-xl border border-[#ddcfc2] bg-white px-4 text-sm font-semibold text-[#5a402d] transition hover:bg-[#faf7f2] xl:w-auto">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </section>

            <!-- =====================================================
                 LISTE DES SIGNALEMENTS
            ====================================================== -->

            <section class="mt-7">

                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">
                            Modération
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-[#30221b] sm:text-2xl">
                            Liste des signalements
                        </h2>

                        <p class="mt-1 text-sm text-[#8d7a6b]">
                            Consultez les détails et choisissez l'action adaptée.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 rounded-full border border-[#e8ded4] bg-white px-3 py-2">
                        <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>
                        <span class="text-xs font-bold text-[#5a402d]">
                            <?= number_format($nombreFiltres, 0, ',', ' ') ?> résultat(s)
                        </span>
                    </div>
                </div>

                <?php if (empty($signalementsFiltres)): ?>

                    <div class="overflow-hidden rounded-[24px] border border-[#e8ded4] bg-white px-6 py-16 text-center shadow-[0_8px_30px_rgba(48,34,27,0.05)]">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f2e8e1] text-[#5a402d]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-8 w-8"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4 5h16v12H7l-3 3V5z"/>
                                <path stroke-linecap="round" d="M8 9h8M8 13h5"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#30221b]">
                            Aucun signalement trouvé
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#9a887a]">
                            Aucun signalement ne correspond à votre recherche.
                            Modifiez les filtres ou réinitialisez-les.
                        </p>

                        <?php if ($recherche !== '' || $filtreStatut !== ''): ?>
                            <a href="/immo-sn/admin/signalements"
                               class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#30221b] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#5a402d]">
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

                            $emailProprietaire = trim(
                                (string) ($signalement['proprietaire_email'] ?? '')
                            );

                            $nomSignaleur = trim(
                                (string) ($signalement['user_prenom'] ?? '') . ' ' .
                                (string) ($signalement['user_nom'] ?? '')
                            );

                            $emailSignaleur = trim(
                                (string) ($signalement['user_email'] ?? '')
                            );

                            if ($nomSignaleur === '') {
                                $nomSignaleur = $emailSignaleur !== ''
                                    ? $emailSignaleur
                                    : 'Utilisateur inconnu';
                            }

                            $motif = trim((string) ($signalement['motif'] ?? ''));

                            [$statutLabel, $statutClasse, $pointClasse] =
                                $signalementStatut($statut);

                            $initialesProprietaire = '';

                            if ($nomProprietaire !== 'Propriétaire non renseigné') {
                                $mots = preg_split('/\s+/', $nomProprietaire) ?: [];

                                foreach (array_slice($mots, 0, 2) as $mot) {
                                    $initialesProprietaire .= mb_substr(
                                        $mot,
                                        0,
                                        1,
                                        'UTF-8'
                                    );
                                }
                            }
                            ?>

                            <article class="group flex flex-col overflow-hidden rounded-[22px] border border-[#e8ded4] bg-white shadow-[0_8px_30px_rgba(48,34,27,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(48,34,27,0.10)]">

                                <div class="h-1 <?= $pointClasse ?>"></div>

                                <div class="flex flex-1 flex-col p-5">

                                    <!-- TITRE ET STATUT -->

                                    <div class="flex items-start justify-between gap-3">

                                        <div class="min-w-0 flex-1">
                                            <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-[#a98260]">
                                                Signalement #<?= $e(substr($id, 0, 8)) ?>
                                            </p>

                                            <h3 class="mt-2 line-clamp-2 text-base font-bold leading-snug text-[#30221b]">
                                                <?= $e($titreBien) ?>
                                            </h3>
                                        </div>

                                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1.5 text-[11px] font-bold <?= $e($statutClasse) ?>">
                                            <span class="h-1.5 w-1.5 rounded-full <?= $pointClasse ?>"></span>
                                            <?= $e($statutLabel) ?>
                                        </span>

                                    </div>

                                    <!-- MOTIF -->

                                    <div class="mt-4 rounded-xl border border-[#eee6de] bg-[#faf7f2] p-3.5">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#a98260]">
                                            Motif du signalement
                                        </p>

                                        <p class="mt-1.5 line-clamp-3 text-sm leading-6 text-[#5a402d]"
                                           title="<?= $e($motif) ?>">
                                            <?= $e($motif !== '' ? $motif : 'Aucun motif précisé') ?>
                                        </p>
                                    </div>

                                    <!-- INFORMATIONS UTILISATEURS -->

                                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                                        <div class="min-w-0">
                                            <p class="text-[10px] font-bold uppercase tracking-wide text-[#a98260]">
                                                Propriétaire
                                            </p>

                                            <div class="mt-2 flex min-w-0 items-center gap-2.5">
                                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#30221b] text-[10px] font-bold text-[#f7b500]">
                                                    <?= $e($initialesProprietaire !== '' ? $initialesProprietaire : '?') ?>
                                                </div>

                                                <div class="min-w-0">
                                                    <p class="truncate text-xs font-bold text-[#30221b]"
                                                       title="<?= $e($nomProprietaire) ?>">
                                                        <?= $e($nomProprietaire) ?>
                                                    </p>

                                                    <?php if ($emailProprietaire !== ''): ?>
                                                        <p class="mt-1 truncate text-[10px] text-[#9a887a]"
                                                           title="<?= $e($emailProprietaire) ?>">
                                                            <?= $e($emailProprietaire) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="min-w-0 border-t border-[#eee6de] pt-3 sm:border-l sm:border-t-0 sm:pl-4 sm:pt-0">
                                            <p class="text-[10px] font-bold uppercase tracking-wide text-[#a98260]">
                                                Signalé par
                                            </p>

                                            <p class="mt-2 truncate text-xs font-bold text-[#30221b]"
                                               title="<?= $e($nomSignaleur) ?>">
                                                <?= $e($nomSignaleur) ?>
                                            </p>

                                            <?php if ($emailSignaleur !== '' && $nomSignaleur !== $emailSignaleur): ?>
                                                <p class="mt-1 truncate text-[10px] text-[#9a887a]"
                                                   title="<?= $e($emailSignaleur) ?>">
                                                    <?= $e($emailSignaleur) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>

                                    </div>

                                    <!-- DATE -->

                                    <div class="mt-4 flex items-center gap-2 border-t border-[#eee6de] pt-3 text-xs text-[#8d7a6b]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4 shrink-0 text-[#a98260]"
                                             fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="1.7">
                                            <rect x="3" y="5" width="18" height="16" rx="2"/>
                                            <path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18"/>
                                        </svg>

                                        <span>
                                            <?= $e($formatDate($signalement['created_at'] ?? null)) ?>
                                        </span>
                                    </div>

                                    <!-- ACTIONS -->

                                    <div class="mt-auto flex flex-wrap items-center gap-2 border-t border-[#eee6de] pt-4">

                                        <?php if ($bienId !== ''): ?>
                                            <a href="/immo-sn/biens/<?= rawurlencode($bienId) ?>"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center gap-1.5 rounded-xl border border-[#ddcfc2] bg-white px-3 py-2.5 text-xs font-bold text-[#5a402d] transition hover:bg-[#faf7f2]">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/>
                                                    <circle cx="12" cy="12" r="2.5"/>
                                                </svg>

                                                Voir le bien
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($statut === 'en_attente' && $id !== ''): ?>

                                            <form method="POST"
                                                  action="/immo-sn/admin/signalements/<?= rawurlencode($id) ?>/traiter"
                                                  onsubmit="return confirm('Confirmer le traitement de ce signalement ?');">

                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-xl bg-[#edf5ef] px-3 py-2.5 text-xs font-bold text-[#497657] transition hover:bg-[#dcecdf]">
                                                    <span aria-hidden="true">✓</span>
                                                    Traiter
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="/immo-sn/admin/signalements/<?= rawurlencode($id) ?>/rejeter"
                                                  onsubmit="return confirm('Confirmer le rejet de ce signalement ?');">

                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-xl bg-[#fbe9e7] px-3 py-2.5 text-xs font-bold text-[#a43d35] transition hover:bg-[#f7d9d5]">
                                                    <span aria-hidden="true">×</span>
                                                    Rejeter
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                        <?php if (in_array($statut, ['traite', 'rejete'], true) && $id !== ''): ?>

                                            <form method="POST"
                                                  action="/immo-sn/admin/signalements/<?= rawurlencode($id) ?>/supprimer"
                                                  onsubmit="return confirm('Voulez-vous vraiment archiver ce signalement ? Son historique sera conservé en base de données.');">

                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-white px-3 py-2.5 text-xs font-bold text-red-700 transition hover:bg-red-50">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4"
                                                         fill="none" viewBox="0 0 24 24"
                                                         stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M3 6h18M8 6V4h8v2m-11 0 1 14h12l1-14M10 10v6m4-6v6"/>
                                                    </svg>

                                                    Archiver
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                    </div>

                                </div>
                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

            <!-- =====================================================
                 PAGINATION
            ====================================================== -->

            <?php if ($totalPages > 1): ?>

                <nav class="mt-6 flex flex-col gap-4 rounded-[22px] border border-[#e8ded4] bg-white p-5 shadow-[0_8px_25px_rgba(48,34,27,0.04)] sm:flex-row sm:items-center sm:justify-between"
                     aria-label="Pagination des signalements">

                    <p class="text-sm text-[#8d7a6b]">
                        Affichage de
                        <strong class="text-[#30221b]"><?= $premierResultat ?></strong>
                        à
                        <strong class="text-[#30221b]"><?= $dernierResultat ?></strong>
                        sur
                        <strong class="text-[#30221b]">
                            <?= number_format($totalSignalements, 0, ',', ' ') ?>
                        </strong>
                        résultat(s)
                    </p>

                    <div class="flex flex-wrap items-center gap-2">

                        <?php if ($page > 1): ?>
                            <a href="<?= $e($urlPage($page - 1)) ?>"
                               class="rounded-xl border border-[#ddcfc2] px-4 py-2.5 text-sm font-semibold text-[#5a402d] transition hover:bg-[#faf7f2]">
                                ← Précédent
                            </a>
                        <?php else: ?>
                            <span class="cursor-not-allowed rounded-xl border border-[#eee6de] px-4 py-2.5 text-sm text-[#b7a99e]">
                                ← Précédent
                            </span>
                        <?php endif; ?>

                        <?php
                        $debut = max(1, $page - 2);
                        $fin = min($totalPages, $page + 2);
                        ?>

                        <?php for ($numero = $debut; $numero <= $fin; $numero++): ?>

                            <?php if ($numero === $page): ?>
                                <span aria-current="page"
                                      class="rounded-xl bg-[#30221b] px-4 py-2.5 text-sm font-bold text-white">
                                    <?= $numero ?>
                                </span>
                            <?php else: ?>
                                <a href="<?= $e($urlPage($numero)) ?>"
                                   class="rounded-xl border border-[#ddcfc2] px-4 py-2.5 text-sm font-semibold text-[#5a402d] transition hover:bg-[#faf7f2]">
                                    <?= $numero ?>
                                </a>
                            <?php endif; ?>

                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="<?= $e($urlPage($page + 1)) ?>"
                               class="rounded-xl border border-[#ddcfc2] px-4 py-2.5 text-sm font-semibold text-[#5a402d] transition hover:bg-[#faf7f2]">
                                Suivant →
                            </a>
                        <?php else: ?>
                            <span class="cursor-not-allowed rounded-xl border border-[#eee6de] px-4 py-2.5 text-sm text-[#b7a99e]">
                                Suivant →
                            </span>
                        <?php endif; ?>

                    </div>
                </nav>

            <?php endif; ?>

           
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
