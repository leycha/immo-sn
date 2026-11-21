
<?php
/**
 * Vue : Gestion des biens — Administration IMMO-SN
 * Emplacement : app/Views/pages/admin/biens.php
 *
 * Variables attendues :
 * $biens, $filtres, $categories, $stats,
 * $page, $totalPages, $totalBiens, $parPage
 */

$biens = is_array($biens ?? null) ? $biens : [];
$filtres = is_array($filtres ?? null) ? $filtres : [];
$categories = is_array($categories ?? null) ? $categories : [];
$stats = is_array($stats ?? null) ? $stats : [];

$e = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

$q = (string) ($filtres['q'] ?? '');
$statutFiltre = (string) ($filtres['statut'] ?? '');
$categorieFiltre = (string) ($filtres['category_id'] ?? '');

$page = max(1, (int) ($page ?? 1));
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalBiens = max(0, (int) ($totalBiens ?? count($biens)));
$parPage = max(1, (int) ($parPage ?? 3));

$statutLibelle = static function (string $statut): string {
    $statut = strtolower(trim($statut));

    $libelles = [
        'actif' => 'Actif',
        'en_attente' => 'En attente',
        'en-attente' => 'En attente',
        'pending' => 'En attente',
        'rejete' => 'Rejeté',
        'rejeté' => 'Rejeté',
        'refuse' => 'Refusé',
        'refusé' => 'Refusé',
        'brouillon' => 'Brouillon',
        'expire' => 'Expiré',
        'expiré' => 'Expiré',
        'suspendu' => 'Suspendu',
        'suspendue' => 'Suspendu',
    ];

    return $libelles[$statut] ?? ucfirst($statut ?: 'Inconnu');
};

$statutStyle = static function (string $statut): array {
    $statut = strtolower(trim($statut));

    if ($statut === 'actif') {
        return ['bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500'];
    }

    if (in_array($statut, ['en_attente', 'en-attente', 'pending'], true)) {
        return ['bg-amber-50 text-amber-800 border-amber-200', 'bg-[#f5b800]'];
    }

    if (in_array($statut, ['rejete', 'rejeté', 'refuse', 'refusé'], true)) {
        return ['bg-red-50 text-red-700 border-red-200', 'bg-red-500'];
    }

    if (in_array($statut, ['expire', 'expiré'], true)) {
        return ['bg-slate-100 text-slate-600 border-slate-200', 'bg-slate-400'];
    }

    if (in_array($statut, ['suspendu', 'suspendue'], true)) {
        return ['bg-indigo-50 text-indigo-700 border-indigo-200', 'bg-indigo-500'];
    }

    return ['bg-slate-100 text-slate-700 border-slate-200', 'bg-slate-400'];
};

$formatPrix = static function ($prix): string {
    if ($prix === null || $prix === '') {
        return 'Prix non renseigné';
    }

    return number_format((float) $prix, 0, ',', ' ') . ' FCFA';
};

$buildPageUrl = static function (int $numero) use ($filtres): string {
    $params = array_filter([
        'q' => trim((string) ($filtres['q'] ?? '')),
        'statut' => trim((string) ($filtres['statut'] ?? '')),
        'category_id' => trim((string) ($filtres['category_id'] ?? '')),
        'page' => $numero,
    ], static fn ($value) => $value !== '' && $value !== null);

    return '/immo-sn/admin/biens?' . http_build_query($params);
};

$debutResultats = $totalBiens > 0 ? (($page - 1) * $parPage) + 1 : 0;
$finResultats = min($page * $parPage, $totalBiens);

$messageAdmin = trim((string) ($_GET['message'] ?? ''));
$typeMessageAdmin = (string) ($_GET['type'] ?? 'succes');
?>

<div class="min-h-screen bg-[#f5f3ed]">

    <!-- =====================================================
         EN-TÊTE
    ====================================================== -->
    <header class="sticky top-0 z-20 border-b border-[#e6e2d8] bg-white/95 backdrop-blur-xl">
        <div class="px-5 py-5 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-[#64748b]">
                        <span>Administration</span>
                        <span class="text-[#f5b800]">/</span>
                        <span>Gestion immobilière</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#071b36] sm:text-3xl">
                        Gestion des biens
                    </h1>

                    <p class="mt-1 text-sm text-[#718096]">
                        Consultez, contrôlez et gérez toutes les annonces immobilières.
                    </p>
                </div>

                <a href="/immo-sn/biens"
                   class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#071b36] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#0e2a4e]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-[#f5b800]"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z"/>
                        <circle cx="12" cy="12" r="2.5"/>
                    </svg>
                    Voir les biens publics
                </a>

            </div>
        </div>
    </header>

    <main class="px-4 py-6 sm:px-6 lg:px-10 lg:py-8">

        <div class="mx-auto max-w-[1600px]">

            <!-- =================================================
                 MESSAGE ADMIN
            ================================================== -->
            <?php if ($messageAdmin !== ''): ?>
                <div id="message-admin"
                     role="alert"
                     class="mb-6 rounded-2xl border px-5 py-4 text-sm font-semibold shadow-sm
                     <?= $typeMessageAdmin === 'erreur'
                         ? 'border-red-200 bg-red-50 text-red-800'
                         : 'border-emerald-200 bg-emerald-50 text-emerald-800' ?>">
                    <?= $e($messageAdmin) ?>
                </div>
            <?php endif; ?>

            <!-- =================================================
                 BANNIÈRE PRINCIPALE
            ================================================== -->
            <section class="relative overflow-hidden rounded-[28px] bg-[#071b36] shadow-[0_20px_55px_rgba(7,27,54,0.15)]">

                <div class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-36 left-1/3 h-80 w-80 rounded-full bg-[#0e2a4e] blur-3xl"></div>

                <div class="relative flex flex-col gap-8 p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between lg:p-10">

                    <div class="max-w-2xl">

                        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-[#f5b800]/20 bg-[#f5b800]/10 px-3.5 py-2">
                            <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                            <span class="text-xs font-semibold tracking-wide text-[#f5d978]">
                                Centre de gestion immobilière
                            </span>
                        </div>

                        <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl lg:text-[42px]">
                            Gestion des
                            <span class="text-[#f5b800]">biens immobiliers</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">
                            Contrôlez les annonces, examinez les publications en attente,
                            approuvez les biens et assurez la qualité des annonces
                            présentes sur la plateforme IMMO-SN.
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="#filtres-biens"
                               class="inline-flex items-center gap-2 rounded-xl bg-[#f5b800] px-4 py-3 text-sm font-bold text-[#071b36] transition hover:bg-[#ffd34b]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path stroke-linecap="round" d="m20 20-4-4"/>
                                </svg>
                                Rechercher une annonce
                            </a>

                            <a href="/immo-sn/admin/signalements"
                               class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                Consulter les signalements
                            </a>
                        </div>

                    </div>

                    <!-- Total -->
                    <div class="w-full shrink-0 rounded-2xl border border-white/10 bg-[#0e2a4e]/80 p-6 backdrop-blur-lg sm:p-7 lg:w-[290px]">

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                    Total des annonces
                                </p>

                                <p class="mt-3 text-4xl font-bold tabular-nums text-white">
                                    <?= number_format((int) ($stats['total'] ?? $totalBiens), 0, ',', ' ') ?>
                                </p>

                                <p class="mt-2 text-sm text-slate-300">
                                    biens enregistrés
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f5b800]/15 text-[#f5b800]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 h-1.5 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full w-full rounded-full bg-[#f5b800]"></div>
                        </div>

                        <p class="mt-3 text-right text-xs text-slate-400">
                            <?= number_format($totalBiens, 0, ',', ' ') ?> résultat(s) filtré(s)
                        </p>

                    </div>

                </div>
            </section>

            <!-- =================================================
                 STATISTIQUES
            ================================================== -->
            <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

                <!-- Total -->
                <article class="group rounded-2xl border border-[#e6e2d8] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#071b36] text-[#f5b800]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#718096]">Total des biens</p>
                            <p class="mt-1 text-2xl font-bold tabular-nums text-[#071b36]">
                                <?= number_format((int) ($stats['total'] ?? $totalBiens), 0, ',', ' ') ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 h-1 rounded-full bg-[#f5b800]"></div>
                </article>

                <!-- Actifs -->
                <article class="group rounded-2xl border border-[#e6e2d8] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#718096]">Biens actifs</p>
                            <p class="mt-1 text-2xl font-bold tabular-nums text-[#071b36]">
                                <?= number_format((int) ($stats['actifs'] ?? 0), 0, ',', ' ') ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 h-1 rounded-full bg-emerald-500"></div>
                </article>

                <!-- En attente -->
                <article class="group rounded-2xl border border-[#e6e2d8] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#718096]">En attente</p>
                            <p class="mt-1 text-2xl font-bold tabular-nums text-[#071b36]">
                                <?= number_format((int) ($stats['attente'] ?? 0), 0, ',', ' ') ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 h-1 rounded-full bg-[#f5b800]"></div>
                </article>

                <!-- Rejetés -->
                <article class="group rounded-2xl border border-[#e6e2d8] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#718096]">Biens rejetés</p>
                            <p class="mt-1 text-2xl font-bold tabular-nums text-[#071b36]">
                                <?= number_format((int) ($stats['rejetes'] ?? 0), 0, ',', ' ') ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 h-1 rounded-full bg-red-500"></div>
                </article>

                <!-- Expirés -->
                <article class="group rounded-2xl border border-[#e6e2d8] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#718096]">Biens expirés</p>
                            <p class="mt-1 text-2xl font-bold tabular-nums text-[#071b36]">
                                <?= number_format((int) ($stats['expires'] ?? 0), 0, ',', ' ') ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 h-1 rounded-full bg-slate-400"></div>
                </article>

                <!-- Suspendus -->
                <article class="group rounded-2xl border border-[#e6e2d8] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 5v14M16 5v14"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#718096]">Biens suspendus</p>
                            <p class="mt-1 text-2xl font-bold tabular-nums text-[#071b36]">
                                <?= number_format((int) ($stats['suspendus'] ?? 0), 0, ',', ' ') ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 h-1 rounded-full bg-indigo-500"></div>
                </article>

            </section>

            <!-- =================================================
                 NAVIGATION ADMIN
            ================================================== -->
            <section class="mt-7">
                <div class="overflow-hidden rounded-2xl border border-[#e6e2d8] bg-white shadow-sm">

                    <nav class="flex overflow-x-auto" aria-label="Navigation administration">

                        <a href="/immo-sn/admin/biens"
                           aria-current="page"
                           class="relative inline-flex shrink-0 items-center gap-2.5 border-b-2 border-[#f5b800] bg-[#071b36] px-5 py-4 text-sm font-bold text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#f5b800]"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                            </svg>
                            Biens
                        </a>

                        <a href="/immo-sn/admin/signalements"
                           class="inline-flex shrink-0 items-center gap-2.5 border-b-2 border-transparent px-5 py-4 text-sm font-semibold text-[#718096] transition hover:bg-[#f5f3ed] hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
                            </svg>
                            Signalements
                        </a>

                        <a href="/immo-sn/admin/cron-monitor"
                           class="inline-flex shrink-0 items-center gap-2.5 border-b-2 border-transparent px-5 py-4 text-sm font-semibold text-[#718096] transition hover:bg-[#f5f3ed] hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                            Cron Monitor
                        </a>

                        <a href="/immo-sn/admin/mail-logs"
                           class="inline-flex shrink-0 items-center gap-2.5 border-b-2 border-transparent px-5 py-4 text-sm font-semibold text-[#718096] transition hover:bg-[#f5f3ed] hover:text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6"/>
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                            </svg>
                            Mail Logs
                        </a>

                    </nav>
                </div>
            </section>

            <!-- =================================================
                 FILTRES
            ================================================== -->
            <section id="filtres-biens"
                     class="mt-6 scroll-mt-28 rounded-[24px] border border-[#e6e2d8] bg-white p-5 shadow-sm sm:p-7">

                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071b36] text-[#f5b800]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="m20 20-4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-[#071b36]">Recherche avancée</h2>
                        <p class="mt-0.5 text-xs text-[#718096]">Affinez les résultats selon vos critères</p>
                    </div>
                </div>

                <form method="GET"
                      action="/immo-sn/admin/biens"
                      class="grid gap-4 xl:grid-cols-[minmax(220px,1fr)_220px_220px_auto_auto]">

                    <div>
                        <label for="q" class="mb-2 block text-xs font-bold text-[#071b36]">
                            Recherche
                        </label>
                        <input id="q"
                               name="q"
                               type="search"
                               value="<?= $e($q) ?>"
                               placeholder="Titre, propriétaire, ville..."
                               class="h-12 w-full rounded-xl border border-[#e1e5eb] bg-[#f8fafc] px-4 text-sm text-[#071b36] outline-none transition placeholder:text-slate-400 focus:border-[#071b36] focus:bg-white focus:ring-4 focus:ring-[#071b36]/10">
                    </div>

                    <div>
                        <label for="statut" class="mb-2 block text-xs font-bold text-[#071b36]">
                            Statut
                        </label>
                        <select id="statut"
                                name="statut"
                                class="h-12 w-full rounded-xl border border-[#e1e5eb] bg-[#f8fafc] px-3 text-sm text-[#071b36] outline-none transition focus:border-[#071b36] focus:bg-white focus:ring-4 focus:ring-[#071b36]/10">
                            <option value="">Tous les statuts</option>
                            <option value="actif" <?= $statutFiltre === 'actif' ? 'selected' : '' ?>>Actif</option>
                            <option value="en_attente" <?= in_array($statutFiltre, ['en_attente', 'en-attente', 'pending'], true) ? 'selected' : '' ?>>En attente</option>
                            <option value="rejete" <?= in_array($statutFiltre, ['rejete', 'rejeté'], true) ? 'selected' : '' ?>>Rejeté</option>
                            <option value="expire" <?= in_array($statutFiltre, ['expire', 'expiré'], true) ? 'selected' : '' ?>>Expiré</option>
                        </select>
                    </div>

                    <div>
                        <label for="category_id" class="mb-2 block text-xs font-bold text-[#071b36]">
                            Catégorie
                        </label>
                        <select id="category_id"
                                name="category_id"
                                class="h-12 w-full rounded-xl border border-[#e1e5eb] bg-[#f8fafc] px-3 text-sm text-[#071b36] outline-none transition focus:border-[#071b36] focus:bg-white focus:ring-4 focus:ring-[#071b36]/10">
                            <option value="">Toutes les catégories</option>

                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?= $e($categorie['id'] ?? '') ?>"
                                    <?= (string) ($categorie['id'] ?? '') === $categorieFiltre ? 'selected' : '' ?>>
                                    <?= $e($categorie['libelle'] ?? 'Catégorie') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="h-12 w-full rounded-xl bg-[#071b36] px-5 text-sm font-bold text-white transition hover:bg-[#0e2a4e] hover:shadow-md lg:w-auto">
                            Rechercher
                        </button>
                    </div>

                    <div class="flex items-end">
                        <a href="/immo-sn/admin/biens"
                           class="inline-flex h-12 w-full items-center justify-center rounded-xl border border-[#e6e2d8] bg-white px-4 text-sm font-semibold text-[#071b36] transition hover:bg-[#f5f3ed] lg:w-auto">
                            Réinitialiser
                        </a>
                    </div>

                </form>
            </section>

            <!-- =================================================
                 CATALOGUE
            ================================================== -->
            <section class="mt-7">

                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                                Catalogue immobilier
                            </p>
                        </div>
                        <h2 class="mt-1 text-xl font-bold text-[#071b36] sm:text-2xl">
                            Annonces immobilières
                        </h2>
                    </div>

                    <div class="inline-flex w-fit items-center rounded-xl border border-[#e6e2d8] bg-white px-4 py-2.5 text-sm text-[#64748b]">
                        <span class="font-semibold text-[#071b36]">
                            <?= number_format($debutResultats, 0, ',', ' ') ?>–<?= number_format($finResultats, 0, ',', ' ') ?>
                        </span>
                        <span class="mx-2">sur</span>
                        <span class="font-semibold text-[#071b36]">
                            <?= number_format($totalBiens, 0, ',', ' ') ?>
                        </span>
                        <span class="ml-1">bien(s)</span>
                    </div>
                </div>

                <?php if ($biens): ?>

                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($biens as $bien): ?>

                            <?php
                            $statut = strtolower(trim((string) ($bien['statut'] ?? '')));
                            [$badgeClasse, $pointClasse] = $statutStyle($statut);

                            $prenom = trim((string) ($bien['proprio_prenom'] ?? ''));
                            $nom = trim((string) ($bien['proprio_nom'] ?? ''));
                            $proprietaire = trim($prenom . ' ' . $nom);

                            $initiales = strtoupper(
                                ($prenom !== '' ? mb_substr($prenom, 0, 1, 'UTF-8') : '') .
                                ($nom !== '' ? mb_substr($nom, 0, 1, 'UTF-8') : '')
                            );

                            $ville = trim((string) ($bien['ville_nom'] ?? ''));
                            $quartier = trim((string) ($bien['quartier_nom'] ?? ''));
                            $photo = trim((string) ($bien['photo'] ?? ''));
                            $bienId = (string) ($bien['id'] ?? '');
                            $motifRejet = trim((string) ($bien['motif_rejet'] ?? ''));
                            ?>

                            <article class="group flex flex-col overflow-hidden rounded-[24px] border border-[#e6e2d8] bg-white shadow-[0_4px_18px_rgba(7,27,54,0.04)] transition-all duration-300 hover:-translate-y-1 hover:border-[#0e2a4e]/30 hover:shadow-[0_20px_45px_rgba(7,27,54,0.10)]">

                                <!-- PHOTO -->
                                <div class="relative h-56 overflow-hidden bg-[#f5f3ed] sm:h-60">

                                    <?php if ($photo !== ''): ?>
                                        <img src="<?= $e($photo) ?>"
                                             alt="<?= $e($bien['titre'] ?? 'Photo du bien') ?>"
                                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                             loading="lazy">
                                    <?php else: ?>
                                        <div class="flex h-full flex-col items-center justify-center gap-3 text-[#0e2a4e]">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-12 w-12"
                                                 fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="1.3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                                            </svg>
                                            <span class="text-xs font-medium">Aucune photo disponible</span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- STATUT -->
                                    <div class="absolute left-4 top-4">
                                        <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-bold shadow-sm <?= $badgeClasse ?>">
                                            <span class="h-1.5 w-1.5 rounded-full <?= $pointClasse ?>"></span>
                                            <?= $e($statutLibelle($statut)) ?>
                                        </span>
                                    </div>

                                    <!-- PRIX -->
                                    <div class="absolute bottom-4 left-4 rounded-xl border border-white/10 bg-[#071b36]/95 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg backdrop-blur-md">
                                        <?= $e($formatPrix($bien['prix'] ?? null)) ?>
                                    </div>

                                    <!-- ID -->
                                    <?php if ($bienId !== ''): ?>
                                        <div class="absolute bottom-4 right-4 rounded-lg bg-white/95 px-2.5 py-1.5 text-xs font-bold text-[#071b36] shadow-sm">
                                            #<?= $e(substr($bienId, 0, 8)) ?>
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <!-- CONTENU -->
                                <div class="flex flex-1 flex-col p-5">

                                    <!-- TITRE -->
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">
                                            <?= $e($bien['categorie_nom'] ?? 'Catégorie non définie') ?>
                                        </p>

                                        <h3 class="mt-2 line-clamp-2 text-lg font-bold leading-snug text-[#071b36]">
                                            <?= $e($bien['titre'] ?? 'Annonce sans titre') ?>
                                        </h3>
                                    </div>

                                    <!-- LOCALISATION -->
                                    <div class="mt-4 flex items-start gap-2.5 text-sm text-[#071b36]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="mt-0.5 h-4 w-4 shrink-0 text-[#0e2a4e]"
                                             fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 21s7-6.1 7-12a7 7 0 10-14 0c0 5.9 7 12 7 12z"/>
                                            <circle cx="12" cy="9" r="2"/>
                                        </svg>

                                        <div class="min-w-0">
                                            <p class="font-semibold">
                                                <?= $e($ville ?: 'Ville non renseignée') ?>
                                            </p>

                                            <?php if ($quartier !== ''): ?>
                                                <p class="mt-0.5 text-xs text-[#718096]">
                                                    <?= $e($quartier) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- PROPRIÉTAIRE -->
                                    <div class="mt-5 border-t border-[#eeeae2] pt-4">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">
                                            Propriétaire
                                        </p>

                                        <div class="mt-2 flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#071b36] text-xs font-bold text-[#f5b800]">
                                                <?= $e($initiales ?: '?') ?>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-[#071b36]">
                                                    <?= $e($proprietaire ?: 'Propriétaire inconnu') ?>
                                                </p>

                                                <?php if (!empty($bien['proprio_email'])): ?>
                                                    <p class="truncate text-xs text-[#718096]">
                                                        <?= $e($bien['proprio_email']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- MOTIF DU REJET -->
                                    <?php if (
                                        in_array($statut, ['rejete', 'rejeté', 'refuse', 'refusé'], true)
                                        && $motifRejet !== ''
                                    ): ?>
                                        <div class="mt-4 rounded-xl border border-red-100 bg-red-50 p-4">
                                            <p class="text-xs font-bold uppercase tracking-wide text-red-800">
                                                Motif du rejet
                                            </p>
                                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-red-700">
                                                <?= $e($motifRejet) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- DATE ET BOUTON -->
                                    <div class="mt-5 flex flex-col gap-3 border-t border-[#eeeae2] pt-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="text-xs text-[#718096]">
                                            <?php if (!empty($bien['created_at'])): ?>
                                                Publié le <?= $e(date('d/m/Y', strtotime((string) $bien['created_at']))) ?>
                                            <?php else: ?>
                                                Date non disponible
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($bienId !== ''): ?>
                                            <a href="/immo-sn/biens/<?= rawurlencode($bienId) ?>"
                                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#071b36] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#0e2a4e]">
                                                Voir le bien
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4 text-[#f5b800]"
                                                     fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7"/>
                                                </svg>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-[#718096]">ID indisponible</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- =================================================
                                         ACTIONS : APPROUVER / REJETER
                                    ================================================== -->
                                    <?php if (
                                        $bienId !== ''
                                        && in_array($statut, ['en_attente', 'en-attente', 'pending'], true)
                                    ): ?>

                                        <div class="mt-4 grid grid-cols-1 gap-3 border-t border-[#eeeae2] pt-4 sm:grid-cols-2">

                                            <!-- APPROUVER -->
                                            <form method="POST"
                                                  action="/immo-sn/admin/biens/approuver/<?= rawurlencode($bienId) ?>"
                                                  onsubmit="return confirm('Confirmer l’approbation de ce bien ?');">

                                                <button type="submit"
                                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Approuver
                                                </button>
                                            </form>

                                            <!-- OUVRIR REJET -->
                                            <button type="button"
                                                    onclick="document.getElementById('form-rejet-<?= $e($bienId) ?>').classList.toggle('hidden')"
                                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-50 px-4 py-3 text-sm font-bold text-red-700 transition hover:bg-red-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                                                </svg>
                                                Rejeter
                                            </button>

                                            <!-- FORMULAIRE REJET -->
                                            <form id="form-rejet-<?= $e($bienId) ?>"
                                                  method="POST"
                                                  action="/immo-sn/admin/biens/rejeter/<?= rawurlencode($bienId) ?>"
                                                  class="hidden space-y-3 rounded-xl border border-red-200 bg-red-50/70 p-4 sm:col-span-2">

                                                <label for="motif-rejet-<?= $e($bienId) ?>"
                                                       class="block text-xs font-bold uppercase tracking-wide text-red-800">
                                                    Motif du rejet
                                                </label>

                                                <textarea id="motif-rejet-<?= $e($bienId) ?>"
                                                          name="motif_rejet"
                                                          rows="3"
                                                          required
                                                          placeholder="Précisez la raison du rejet de cette annonce..."
                                                          class="w-full rounded-xl border border-red-200 bg-white px-3.5 py-2.5 text-sm text-[#071b36] outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-200"></textarea>

                                                <div class="flex flex-wrap items-center justify-end gap-2">

                                                    <button type="button"
                                                            onclick="document.getElementById('form-rejet-<?= $e($bienId) ?>').classList.add('hidden')"
                                                            class="rounded-lg border border-[#e6e2d8] bg-white px-4 py-2.5 text-xs font-semibold text-[#64748b] transition hover:bg-[#f5f3ed]">
                                                        Annuler
                                                    </button>

                                                    <button type="submit"
                                                            class="rounded-lg bg-red-700 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-red-800">
                                                        Confirmer le rejet
                                                    </button>
                                                </div>

                                            </form>

                                        </div>

                                    <?php endif; ?>

                                </div>
                            </article>

                        <?php endforeach; ?>

                    </div>

                    <!-- =================================================
                         PAGINATION
                    ================================================== -->
                    <?php if ($totalPages > 1): ?>
                        <nav class="mt-8 flex flex-wrap items-center justify-center gap-2"
                             aria-label="Pagination">

                            <?php if ($page > 1): ?>
                                <a href="<?= $e($buildPageUrl($page - 1)) ?>"
                                   class="inline-flex items-center gap-2 rounded-xl border border-[#e6e2d8] bg-white px-4 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-[#f5f3ed]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Précédent
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="<?= $e($buildPageUrl($i)) ?>"
                                   class="inline-flex h-11 min-w-11 items-center justify-center rounded-xl px-4 text-sm font-bold transition
                                   <?= $i === $page
                                       ? 'bg-[#f5b800] text-[#071b36] shadow-sm'
                                       : 'border border-[#e6e2d8] bg-white text-[#071b36] hover:bg-[#f5f3ed]' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="<?= $e($buildPageUrl($page + 1)) ?>"
                                   class="inline-flex items-center gap-2 rounded-xl border border-[#e6e2d8] bg-white px-4 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-[#f5f3ed]">
                                    Suivant
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            <?php endif; ?>

                        </nav>
                    <?php endif; ?>

                <?php else: ?>

                    <!-- =================================================
                         AUCUN RÉSULTAT
                    ================================================== -->
                    <div class="rounded-[24px] border border-[#e6e2d8] bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#071b36] text-[#f5b800]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#071b36]">
                            Aucun bien trouvé
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-[#718096]">
                            Aucune annonce ne correspond à votre recherche actuelle.
                            Essayez de modifier vos filtres.
                        </p>

                        <a href="/immo-sn/admin/biens"
                           class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-[#071b36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0e2a4e]">
                            Réinitialiser les filtres
                        </a>

                    </div>

                <?php endif; ?>

            </section>

        </div>
    </main>
</div>

<!-- =========================================================
     DISPARITION AUTOMATIQUE DU MESSAGE
========================================================== -->
<script>
(function () {
    const msg = document.getElementById('message-admin');

    if (!msg) return;

    setTimeout(function () {
        msg.classList.add('opacity-0');

        setTimeout(function () {
            msg.remove();
        }, 500);

    }, 5000);
})();
</script>
