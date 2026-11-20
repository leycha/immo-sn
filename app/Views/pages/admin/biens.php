<?php
/**
 * Vue : Gestion des biens — Administration IMMOSN
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
    ];
    return $libelles[$statut] ?? ucfirst($statut ?: 'Inconnu');
};

$statutStyle = static function (string $statut): array {
    $statut = strtolower(trim($statut));
    if ($statut === 'actif') {
        return ['bg-emerald-100 text-emerald-700', 'bg-emerald-500'];
    }
    if (in_array($statut, ['en_attente', 'en-attente', 'pending'], true)) {
        return ['bg-amber-100 text-amber-800', 'bg-[#f5b800]'];
    }
    if (in_array($statut, ['rejete', 'rejeté', 'refuse', 'refusé'], true)) {
        return ['bg-red-100 text-red-700', 'bg-red-500'];
    }
    return ['bg-slate-100 text-slate-700', 'bg-slate-400'];
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
?>

<div class="min-h-screen bg-[#f8fafc]">
    <header class="sticky top-0 z-20 border-b border-[#e2e8f0] bg-white/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-[#64748b]">
                        <span>Administration</span><span>/</span><span>Biens immobiliers</span>
                    </div>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#071b36] sm:text-3xl">Gestion des biens</h1>
                    <p class="mt-1 text-sm text-[#64748b]">Consultez et recherchez les annonces publiées sur Immo-SN.</p>
                </div>
                <a href="/immo-sn/biens"
                   class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#071b36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0e2a4e]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#f5b800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z"/>
                        <circle cx="12" cy="12" r="2.5"/>
                    </svg>
                    Voir les biens publics
                </a>
            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">

<?php
$messageAdmin = trim((string) ($_GET['message'] ?? ''));
$typeMessageAdmin = (string) ($_GET['type'] ?? 'succes');
?>

<?php if ($messageAdmin !== ''): ?>
    <div
        id="message-admin"
        role="alert"
        class="mx-auto mb-5 max-w-[1600px] rounded-xl border px-5 py-4 text-sm font-semibold transition-opacity duration-500
        <?= $typeMessageAdmin === 'erreur'
            ? 'border-red-200 bg-red-50 text-red-800'
            : 'border-emerald-200 bg-emerald-50 text-emerald-800' ?>"
    >
        <?= $e($messageAdmin) ?>
    </div>
<?php endif; ?>

        <div class="mx-auto max-w-[1600px]">
            <section class="relative overflow-hidden rounded-[26px] bg-[#071b36] shadow-[0_18px_50px_rgba(7,27,54,0.16)]">

                <!-- Décoration du fond -->
                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#0e2a4e]/60 blur-3xl"></div>

                <div class="relative flex flex-col gap-7 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between">

                    <!-- Présentation -->
                    <div class="max-w-2xl">

                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                            <span class="text-xs font-medium text-slate-200">
                                Centre de gestion immobilière
                            </span>
                        </div>

                        <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                            Gestion des
                            <span class="text-[#f5b800]">biens immobiliers</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-xs leading-7 text-slate-300 sm:text-base">
                            Consultez les annonces immobilières, contrôlez leur statut,
                            validez les publications et veillez à la qualité des biens
                            proposés sur IMMO-SN.
                        </p>

                    </div>

                    <!-- Carte de synthèse -->
                    <div class="shrink-0 rounded-2xl border border-white/10 bg-[#0e2a4e]/70 p-5 backdrop-blur-sm lg:min-w-[270px]">

                        <div class="flex items-center justify-between gap-5">

                            <div>
                                <p class="text-xs uppercase tracking-[0.15em] text-slate-400">
                                    Total des annonces
                                </p>

                                <p class="mt-2 text-3xl font-bold tabular-nums text-white">
                                    <?= number_format((int) ($stats['total'] ?? $totalBiens), 0, ',', ' ') ?>
                                </p>

                                <p class="mt-1 text-xs text-slate-300">
                                    bien(s) enregistré(s)
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#f5b800]/15 text-[#f5b800]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                                </svg>
                            </div>

                        </div>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full w-full rounded-full bg-[#f5b800]"></div>
                        </div>

                        <p class="mt-2 text-right text-[11px] text-slate-400">
                            <?= number_format($totalBiens, 0, ',', ' ') ?>
                            résultat(s) correspondant aux filtres
                        </p>

                    </div>

                </div>
            </section>

            <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071b36] text-[#f5b800]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-[#64748b]">Total des biens</p><p class="mt-1 text-2xl font-bold text-[#0b1528]"><?= (int) ($stats['total'] ?? $totalBiens) ?></p></div>
                    </div>
                </div>
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-[#64748b]">Biens actifs</p><p class="mt-1 text-2xl font-bold text-[#0b1528]"><?= (int) ($stats['actifs'] ?? 0) ?></p></div>
                    </div>
                </div>
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-[#64748b]">En attente</p><p class="mt-1 text-2xl font-bold text-[#0b1528]"><?= (int) ($stats['attente'] ?? 0) ?></p></div>
                    </div>
                </div>

                <!-- Biens rejetés -->
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-100 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#64748b]">Biens rejetés</p>
                            <p class="mt-1 text-2xl font-bold text-[#0b1528]">
                                <?= (int) ($stats['rejetes'] ?? 0) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Biens expirés -->
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#64748b]">Biens expirés</p>
                            <p class="mt-1 text-2xl font-bold text-[#0b1528]">
                                <?= (int) ($stats['expires'] ?? 0) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Biens suspendus -->
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 5v14M16 5v14"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#64748b]">Biens suspendus</p>
                            <p class="mt-1 text-2xl font-bold text-[#0b1528]">
                                <?= (int) ($stats['suspendus'] ?? 0) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-7">
                <div class="overflow-hidden rounded-[20px] border border-[#e2e8f0] bg-white shadow-[0_8px_25px_rgba(7,27,54,0.04)]">
                    <nav class="flex overflow-x-auto px-2 pt-2" aria-label="Navigation administration">
                        <a href="/immo-sn/admin/biens"
                           aria-current="page"
                           class="relative flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-bold text-[#071b36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 text-[#f5b800]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                            </svg>
                            Biens
                            <span class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full bg-[#f5b800]"></span>
                        </a>

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

                        <a href="/immo-sn/admin/mail-logs"
                           class="flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-medium text-[#64748b] transition hover:bg-[#f8fafc] hover:text-[#071b36]">
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

            <section id="filtres-biens"
                     class="mt-6 scroll-mt-28 rounded-[24px] border border-[#e2e8f0] bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">Recherche avancée</p>
                </div>
                <form method="GET" action="/immo-sn/admin/biens" class="grid gap-4 xl:grid-cols-[minmax(220px,1fr)_220px_220px_auto_auto]">
                    <div>
                        <label for="q" class="mb-2 block text-xs font-semibold text-[#0b1528]">Recherche</label>
                        <input id="q" name="q" type="search" value="<?= $e($q) ?>" placeholder="Titre, propriétaire, ville..."
                               class="h-12 w-full rounded-xl border border-[#cbd5e1] bg-[#f8fafc] px-4 text-sm text-[#0b1528] outline-none transition placeholder:text-slate-400 focus:border-[#071b36] focus:bg-white focus:ring-4 focus:ring-[#071b36]/10">
                    </div>
                    <div>
                        <label for="statut" class="mb-2 block text-xs font-semibold text-[#0b1528]">Statut</label>
                        <select id="statut" name="statut" class="h-12 w-full rounded-xl border border-[#cbd5e1] bg-[#f8fafc] px-3 text-sm text-[#0b1528] outline-none focus:border-[#071b36] focus:ring-4 focus:ring-[#071b36]/10">
                            <option value="">Tous les statuts</option>
                            <option value="actif" <?= $statutFiltre === 'actif' ? 'selected' : '' ?>>Actif</option>
                            <option value="en_attente" <?= in_array($statutFiltre, ['en_attente', 'en-attente', 'pending'], true) ? 'selected' : '' ?>>En attente</option>
                            <option value="rejete" <?= in_array($statutFiltre, ['rejete', 'rejeté'], true) ? 'selected' : '' ?>>Rejeté</option>
                            <option value="expire" <?= in_array($statutFiltre, ['expire', 'expiré'], true) ? 'selected' : '' ?>>Expiré</option>
                        </select>
                    </div>
                    <div>
                        <label for="category_id" class="mb-2 block text-xs font-semibold text-[#0b1528]">Catégorie</label>
                        <select id="category_id" name="category_id" class="h-12 w-full rounded-xl border border-[#cbd5e1] bg-[#f8fafc] px-3 text-sm text-[#0b1528] outline-none focus:border-[#071b36] focus:ring-4 focus:ring-[#071b36]/10">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?= $e($categorie['id'] ?? '') ?>" <?= (string) ($categorie['id'] ?? '') === $categorieFiltre ? 'selected' : '' ?>>
                                    <?= $e($categorie['libelle'] ?? 'Catégorie') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="h-12 w-full rounded-xl bg-[#071b36] px-5 text-sm font-bold text-white transition hover:bg-[#0e2a4e] lg:w-auto">Rechercher</button>
                    </div>
                    <div class="flex items-end">
                        <a href="/immo-sn/admin/biens" class="inline-flex h-12 w-full items-center justify-center rounded-xl border border-[#e2e8f0] bg-white px-4 text-sm font-semibold text-[#0b1528] transition hover:bg-[#f8fafc] lg:w-auto">Réinitialiser</a>
                    </div>
                </form>
            </section>

            <section class="mt-6">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">Catalogue</p>
                    </div>
                    <p class="text-sm text-[#64748b]">
                        <?= number_format($debutResultats, 0, ',', ' ') ?>–<?= number_format($finResultats, 0, ',', ' ') ?>
                        sur <?= number_format($totalBiens, 0, ',', ' ') ?> bien(s)
                    </p>
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
                            <article class="group overflow-hidden rounded-[24px] border border-[#e2e8f0] bg-white shadow-[0_4px_18px_rgba(7,27,54,0.035)] transition-all duration-300 hover:-translate-y-1 hover:border-[#0e2a4e]/40 hover:shadow-[0_20px_45px_rgba(7,27,54,0.10)]">
                                <div class="relative h-56 overflow-hidden bg-slate-100 sm:h-60">
                                    <?php if ($photo !== ''): ?>
                                        <img src="<?= $e($photo) ?>" alt="<?= $e($bien['titre'] ?? 'Photo du bien') ?>"
                                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                                    <?php else: ?>
                                        <div class="flex h-full flex-col items-center justify-center gap-3 text-[#0e2a4e]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                                            </svg>
                                            <span class="text-xs font-medium">Aucune photo disponible</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="absolute left-4 top-4">
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold shadow-sm <?= $badgeClasse ?>">
                                            <span class="h-1.5 w-1.5 rounded-full <?= $pointClasse ?>"></span><?= $e($statutLibelle($statut)) ?>
                                        </span>
                                    </div>
                                    <div class="absolute bottom-4 left-4 rounded-xl border border-white/15 bg-[#071b36]/95 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg backdrop-blur-md">
                                        <?= $e($formatPrix($bien['prix'] ?? null)) ?>
                                    </div>
                                </div>

                                <div class="p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-[#0e2a4e]"><?= $e($bien['categorie_nom'] ?? 'Catégorie non définie') ?></p>
                                            <h3 class="mt-2 line-clamp-2 text-lg font-bold leading-snug text-[#0b1528]"><?= $e($bien['titre'] ?? 'Annonce sans titre') ?></h3>
                                        </div>
                                        <?php if ($bienId !== ''): ?>
                                            <span class="shrink-0 rounded-lg bg-[#f8fafc] px-2.5 py-1 text-xs font-semibold text-[#64748b]">#<?= $e(substr($bienId, 0, 8)) ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-4 flex items-start gap-2 text-sm text-[#0b1528]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-[#0e2a4e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-6.1 7-12a7 7 0 10-14 0c0 5.9 7 12 7 12z"/><circle cx="12" cy="9" r="2"/>
                                        </svg>
                                        <div class="min-w-0">
                                            <p class="font-semibold"><?= $e($ville ?: 'Ville non renseignée') ?></p>
                                            <?php if ($quartier !== ''): ?><p class="mt-0.5 text-xs text-[#64748b]"><?= $e($quartier) ?></p><?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mt-5 border-t border-[#e2e8f0] pt-4">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-[#0e2a4e]">Propriétaire</p>
                                        <div class="mt-2 flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#071b36] text-xs font-bold text-[#f5b800]"><?= $e($initiales ?: '?') ?></div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-[#0b1528]"><?= $e($proprietaire ?: 'Propriétaire inconnu') ?></p>
                                                <?php if (!empty($bien['proprio_email'])): ?>
                                                    <p class="truncate text-xs text-[#64748b]"><?= $e($bien['proprio_email']) ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (in_array($statut, ['rejete', 'rejeté', 'refuse', 'refusé'], true) && $motifRejet !== ''): ?>
                                        <div class="mt-4 rounded-xl border border-red-100 bg-red-50 p-4">
                                            <p class="text-xs font-bold uppercase tracking-wide text-red-800">
                                                Motif du rejet
                                            </p>
                                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-red-700">
                                                <?= $e($motifRejet) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-5 flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="text-xs text-[#64748b]">
                                            <?php if (!empty($bien['created_at'])): ?>
                                                Publié le <?= $e(date('d/m/Y', strtotime((string) $bien['created_at']))) ?>
                                            <?php else: ?>
                                                Date non disponible
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($bienId !== ''): ?>
                                            <a href="/immo-sn/biens/<?= rawurlencode($bienId) ?>"
                                               class="inline-flex items-center gap-2 rounded-xl bg-[#071b36] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#0e2a4e]">
                                                Voir le bien
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#f5b800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7"/>
                                                </svg>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-[#64748b]">ID indisponible</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (
                                        $bienId !== ''
                                        && in_array($statut, ['en_attente', 'en-attente', 'pending'], true)
                                    ): ?>
                                        <div class="mt-4 grid grid-cols-1 gap-3 border-t border-[#e2e8f0] pt-4 sm:grid-cols-2">

                                            <!-- Bouton Approuver -->
                                            <form
                                                method="POST"
                                                action="/immo-sn/admin/biens/approuver/<?= rawurlencode($bienId) ?>"
                                                onsubmit="return confirm('Confirmer l’approbation de ce bien ?');"
                                            >
                                                <button
                                                    type="submit"
                                                    class="w-full rounded-xl bg-emerald-100 px-4 py-3 text-sm font-bold text-emerald-700 transition hover:bg-emerald-200"
                                                >
                                                    ✓ Approuver
                                                </button>
                                            </form>

                                            <!-- Bouton pour afficher le formulaire de rejet -->
                                            <button
                                                type="button"
                                                onclick="document.getElementById('form-rejet-<?= $e($bienId) ?>').classList.toggle('hidden')"
                                                class="w-full rounded-xl bg-red-100 px-4 py-3 text-sm font-bold text-red-700 transition hover:bg-red-200"
                                            >
                                                ✕ Rejeter
                                            </button>

                                            <!-- Formulaire de rejet -->
                                            <form
                                                id="form-rejet-<?= $e($bienId) ?>"
                                                method="POST"
                                                action="/immo-sn/admin/biens/rejeter/<?= rawurlencode($bienId) ?>"
                                                class="hidden space-y-3 rounded-xl border border-red-200 bg-red-50/70 p-4 sm:col-span-2"
                                            >
                                                <label for="motif-rejet-<?= $e($bienId) ?>" class="block text-xs font-bold uppercase tracking-wide text-red-800">
                                                    Motif du rejet
                                                </label>
                                                <textarea
                                                    id="motif-rejet-<?= $e($bienId) ?>"
                                                    name="motif_rejet"
                                                    rows="3"
                                                    required
                                                    placeholder="Précisez la raison du rejet de cette annonce..."
                                                    class="w-full rounded-xl border border-red-200 bg-white px-3.5 py-2.5 text-sm text-[#0b1528] outline-none focus:border-red-400 focus:ring-2 focus:ring-red-200"
                                                ></textarea>
                                                <div class="flex items-center justify-end gap-2">
                                                    <button
                                                        type="button"
                                                        onclick="document.getElementById('form-rejet-<?= $e($bienId) ?>').classList.add('hidden')"
                                                        class="rounded-lg border border-[#e2e8f0] bg-white px-3.5 py-2 text-xs font-semibold text-[#64748b] hover:bg-[#f8fafc]"
                                                    >
                                                        Annuler
                                                    </button>
                                                    <button
                                                        type="submit"
                                                        class="rounded-lg bg-red-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-red-800"
                                                    >
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

                    <?php if ($totalPages > 1): ?>
                        <nav class="mt-8 flex flex-wrap items-center justify-center gap-2" aria-label="Pagination">
                            <?php if ($page > 1): ?>
                                <a href="<?= $e($buildPageUrl($page - 1)) ?>"
                                   class="rounded-xl border border-[#e2e8f0] bg-white px-4 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-[#f8fafc]">
                                    Précédent
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="<?= $e($buildPageUrl($i)) ?>"
                                   class="rounded-xl px-4 py-2.5 text-sm font-bold transition <?= $i === $page
                                       ? 'bg-[#f5b800] text-[#0b1528] shadow-sm'
                                       : 'border border-[#e2e8f0] bg-white text-[#071b36] hover:bg-[#f8fafc]' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="<?= $e($buildPageUrl($page + 1)) ?>"
                                   class="rounded-xl border border-[#e2e8f0] bg-white px-4 py-2.5 text-sm font-semibold text-[#071b36] transition hover:bg-[#f8fafc]">
                                    Suivant
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="rounded-[24px] border border-[#e2e8f0] bg-white px-6 py-16 text-center shadow-sm">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f8fafc] text-[#0e2a4e]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-[#071b36]">Aucun bien trouvé</h3>
                        <p class="mt-2 text-sm text-[#64748b]">Essayez de modifier vos filtres ou votre recherche.</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</div>

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