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
        return ['bg-[#edf5ef] text-[#497657]', 'bg-[#5caa70]'];
    }
    if (in_array($statut, ['en_attente', 'en-attente', 'pending'], true)) {
        return ['bg-[#fff4cf] text-[#9a7000]', 'bg-[#f7b500]'];
    }
    if (in_array($statut, ['rejete', 'rejeté', 'refuse', 'refusé'], true)) {
        return ['bg-[#fbe9e7] text-[#a43d35]', 'bg-[#d85d52]'];
    }
    return ['bg-[#f2e8e1] text-[#725238]', 'bg-[#a98260]'];
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

<div class="min-h-screen bg-[#faf7f2]">
    <header class="sticky top-0 z-20 border-b border-[#e8ded4] bg-[#faf7f2]/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-[#a98260]">
                        <span>Administration</span><span>/</span><span>Biens immobiliers</span>
                    </div>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#30221b] sm:text-3xl">Gestion des biens</h1>
                    <p class="mt-1 text-sm text-[#8d7a6b]">Consultez et recherchez les annonces publiées sur Immo-SN.</p>
                </div>
                <a href="/immo-sn/biens"
                   class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#5a402d]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
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
            : 'border-green-200 bg-green-50 text-green-800' ?>"
    >
        <?= $e($messageAdmin) ?>
    </div>
<?php endif; ?>


        <div class="mx-auto max-w-[1600px]">
<section class="relative overflow-hidden rounded-[26px] bg-[#30221b] shadow-[0_18px_50px_rgba(48,34,27,0.14)]">

    <!-- Décoration du fond -->
    <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
    <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#a98260]/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-7 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between">

        <!-- Présentation -->
        <div class="max-w-2xl">

            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>
                <span class="text-xs font-medium text-[#ded3ca]">
                    Centre de gestion immobilière
                </span>
            </div>

            <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                Gestion des
                <span class="text-[#f7b500]">biens immobiliers</span>
            </h2>

            <p class="mt-4 max-w-xl text-xs leading-7 text-[#cfc3ba] sm:text-base">
                Consultez les annonces immobilières, contrôlez leur statut,
                validez les publications et veillez à la qualité des biens
                proposés sur IMMO-SN.
            </p>

        </div>

        <!-- Carte de synthèse -->
        <div class="shrink-0 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm lg:min-w-[270px]">

            <div class="flex items-center justify-between gap-5">

                <div>
                    <p class="text-xs uppercase tracking-[0.15em] text-[#bcaea3]">
                        Total des annonces
                    </p>

                    <p class="mt-2 text-3xl font-bold tabular-nums text-white">
                        <?= number_format((int) ($stats['total'] ?? $totalBiens), 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 text-xs text-[#cfc3ba]">
                        bien(s) enregistré(s)
                    </p>
                </div>

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#f7b500]/10 text-[#f7b500]">
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
                <div class="h-full w-full rounded-full bg-[#f7b500]"></div>
            </div>

            <p class="mt-2 text-right text-[11px] text-[#bcaea3]">
                <?= number_format($totalBiens, 0, ',', ' ') ?>
                résultat(s) correspondant aux filtres
            </p>

        </div>

    </div>
</section>



            <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#30221b] text-[#f7b500]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-[#9a887a]">Total des biens</p><p class="mt-1 text-2xl font-bold text-[#30221b]"><?= (int) ($stats['total'] ?? $totalBiens) ?></p></div>
                    </div>
                </div>
                <div class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#edf5ef] text-[#497657]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-[#9a887a]">Biens actifs</p><p class="mt-1 text-2xl font-bold text-[#30221b]"><?= (int) ($stats['actifs'] ?? 0) ?></p></div>
                    </div>
                </div>
                <div class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#fff4cf] text-[#9a7000]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                        </div>
                        <div><p class="text-xs font-medium text-[#9a887a]">En attente</p><p class="mt-1 text-2xl font-bold text-[#30221b]"><?= (int) ($stats['attente'] ?? 0) ?></p></div>
                    </div>
                </div>
                
<!-- Biens rejetés -->
<div class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#fbe9e7] text-[#a43d35]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-medium text-[#9a887a]">Biens rejetés</p>
            <p class="mt-1 text-2xl font-bold text-[#30221b]">
                <?= (int) ($stats['rejetes'] ?? 0) ?>
            </p>
        </div>
    </div>
</div>

<!-- Biens expirés -->
<div class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f2e8e1] text-[#725238]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="9"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-medium text-[#9a887a]">Biens expirés</p>
            <p class="mt-1 text-2xl font-bold text-[#30221b]">
                <?= (int) ($stats['expires'] ?? 0) ?>
            </p>
        </div>
    </div>
</div>

<!-- Biens suspendus -->
<div class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#eee8f8] text-[#7152a3]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 5v14M16 5v14"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-medium text-[#9a887a]">Biens suspendus</p>
            <p class="mt-1 text-2xl font-bold text-[#30221b]">
                <?= (int) ($stats['suspendus'] ?? 0) ?>
            </p>
        </div>
    </div>
</div>

            </section>
<section class="mt-7">
    <div class="overflow-hidden rounded-[20px] border border-[#e8ded4] bg-white shadow-[0_8px_25px_rgba(48,34,27,0.04)]">

        <nav class="flex overflow-x-auto px-2 pt-2"
             aria-label="Navigation administration">

            <!-- Biens : onglet actif -->
            <a href="/immo-sn/admin/biens"
               aria-current="page"
               class="relative flex shrink-0 items-center gap-2 px-5 py-4 text-sm font-bold text-[#30221b]">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4 text-[#f7b500]"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                </svg>

                Biens

                <span class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full bg-[#f7b500]"></span>
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

             <section id="filtres-biens"
               class="mt-6 scroll-mt-28 rounded-[24px] border border-[#e8ded4] bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">Recherche avancée</p>
                </div>
                <form method="GET" action="/immo-sn/admin/biens" class="grid gap-4 xl:grid-cols-[minmax(220px,1fr)_220px_220px_auto_auto]">
                    <div>
                        <label for="q" class="mb-2 block text-xs font-semibold text-[#5a402d]">Recherche</label>
                        <input id="q" name="q" type="search" value="<?= $e($q) ?>" placeholder="Titre, propriétaire, ville..."
                               class="h-12 w-full rounded-xl border border-[#ddd2c7] bg-[#fdfbf9] px-4 text-sm text-[#30221b] outline-none transition placeholder:text-[#aa9a8d] focus:border-[#a98260] focus:bg-white focus:ring-4 focus:ring-[#a98260]/10">
                    </div>
                    <div>
                        <label for="statut" class="mb-2 block text-xs font-semibold text-[#5a402d]">Statut</label>
                        <select id="statut" name="statut" class="h-12 w-full rounded-xl border border-[#ddd2c7] bg-[#fdfbf9] px-3 text-sm text-[#5a402d] outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10">
                            <option value="">Tous les statuts</option>
                            <option value="actif" <?= $statutFiltre === 'actif' ? 'selected' : '' ?>>Actif</option>
                            <option value="en_attente" <?= in_array($statutFiltre, ['en_attente', 'en-attente', 'pending'], true) ? 'selected' : '' ?>>En attente</option>
                            <option value="rejete" <?= in_array($statutFiltre, ['rejete', 'rejeté'], true) ? 'selected' : '' ?>>Rejeté</option>
                            <option value="expire" <?= in_array($statutFiltre, ['expire', 'expiré'], true) ? 'selected' : '' ?>>Expiré</option>
                        </select>
                    </div>
                    <div>
                        <label for="category_id" class="mb-2 block text-xs font-semibold text-[#5a402d]">Catégorie</label>
                        <select id="category_id" name="category_id" class="h-12 w-full rounded-xl border border-[#ddd2c7] bg-[#fdfbf9] px-3 text-sm text-[#5a402d] outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?= $e($categorie['id'] ?? '') ?>" <?= (string) ($categorie['id'] ?? '') === $categorieFiltre ? 'selected' : '' ?>>
                                    <?= $e($categorie['libelle'] ?? 'Catégorie') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="h-12 w-full rounded-xl bg-[#30221b] px-5 text-sm font-bold text-white transition hover:bg-[#5a402d] lg:w-auto">Rechercher</button>
                    </div>
                    <div class="flex items-end">
                        <a href="/immo-sn/admin/biens" class="inline-flex h-12 w-full items-center justify-center rounded-xl border border-[#ddcfc2] bg-white px-4 text-sm font-semibold text-[#5a402d] transition hover:bg-[#faf7f2] lg:w-auto">Réinitialiser</a>
                    </div>
                </form>
            </section>

            <section class="mt-6">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">Catalogue</p>
                       
                    </div>
                    <p class="text-sm text-[#8d7a6b]">
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
                            <article class="group overflow-hidden rounded-[24px] border border-[#e8ded4] bg-white shadow-[0_4px_18px_rgba(48,34,27,0.035)] transition-all duration-300 hover:-translate-y-1 hover:border-[#d2bda9] hover:shadow-[0_20px_45px_rgba(48,34,27,0.10)]">
                                <div class="relative h-56 overflow-hidden bg-[#f2e8e1] sm:h-60">
                                    <?php if ($photo !== ''): ?>
                                        <img src="<?= $e($photo) ?>" alt="<?= $e($bien['titre'] ?? 'Photo du bien') ?>"
                                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                                    <?php else: ?>
                                        <div class="flex h-full flex-col items-center justify-center gap-3 text-[#a98260]">
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
                                   <div class="absolute bottom-4 left-4 rounded-xl border border-white/15 bg-[#30221b]/95 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg backdrop-blur-md">
                                        <?= $e($formatPrix($bien['prix'] ?? null)) ?>
                                    </div>
                                </div>

                                <div class="p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-[#a98260]"><?= $e($bien['categorie_nom'] ?? 'Catégorie non définie') ?></p>
                                            <h3 class="mt-2 line-clamp-2 text-lg font-bold leading-snug text-[#30221b]"><?= $e($bien['titre'] ?? 'Annonce sans titre') ?></h3>
                                        </div>
                                        <?php if ($bienId !== ''): ?>
                                            <span class="shrink-0 rounded-lg bg-[#faf7f2] px-2.5 py-1 text-xs font-semibold text-[#8d7a6b]">#<?= $e(substr($bienId, 0, 8)) ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-4 flex items-start gap-2 text-sm text-[#725f50]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-[#a98260]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-6.1 7-12a7 7 0 10-14 0c0 5.9 7 12 7 12z"/><circle cx="12" cy="9" r="2"/>
                                        </svg>
                                        <div class="min-w-0">
                                            <p class="font-semibold"><?= $e($ville ?: 'Ville non renseignée') ?></p>
                                            <?php if ($quartier !== ''): ?><p class="mt-0.5 text-xs text-[#9a887a]"><?= $e($quartier) ?></p><?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mt-5 border-t border-[#eee6de] pt-4">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-[#a98260]">Propriétaire</p>
                                        <div class="mt-2 flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#30221b] text-xs font-bold text-[#f7b500]"><?= $e($initiales ?: '?') ?></div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-[#30221b]"><?= $e($proprietaire ?: 'Propriétaire inconnu') ?></p>
                                                <?php if (!empty($bien['proprio_email'])): ?>
                                                    <p class="truncate text-xs text-[#9a887a]"><?= $e($bien['proprio_email']) ?></p>
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
                                        <div class="text-xs text-[#9a887a]">
                                            <?php if (!empty($bien['created_at'])): ?>
                                                Publié le <?= $e(date('d/m/Y', strtotime((string) $bien['created_at']))) ?>
                                            <?php else: ?>
                                                Date non disponible
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($bienId !== ''): ?>
                                            <a href="/immo-sn/biens/<?= rawurlencode($bienId) ?>"
                                               class="inline-flex items-center gap-2 rounded-xl bg-[#30221b] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#5a402d]">
                                                Voir le bien
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7"/>
                                                </svg>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-[#9a887a]">ID indisponible</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (
                                        $bienId !== ''
                                        && in_array($statut, ['en_attente', 'en-attente', 'pending'], true)
                                    ): ?>
                                        <div class="mt-4 grid grid-cols-1 gap-3 border-t border-[#eee6de] pt-4 sm:grid-cols-2">

                                            <!-- Bouton Approuver -->
                                            <form
                                                method="POST"
                                                action="/immo-sn/admin/biens/approuver/<?= rawurlencode($bienId) ?>"
                                                onsubmit="return confirm('Confirmer l’approbation de ce bien ?');"
                                            >
                                                <button
                                                    type="submit"
                                                    class="w-full rounded-xl bg-[#edf5ef] px-4 py-3 text-sm font-bold text-[#497657] transition hover:bg-[#dcecdf]"
                                                >
                                                    ✓ Approuver
                                                </button>
                                            </form>

                                            <!-- Bouton pour afficher le formulaire de rejet -->
                                            <button
                                                type="button"
                                                onclick="document.getElementById('form-rejet-<?= $e($bienId) ?>').classList.toggle('hidden')"
                                                class="w-full rounded-xl bg-[#fbe9e7] px-4 py-3 text-sm font-bold text-[#a43d35] transition hover:bg-[#f7d9d5]"
                                            >
                                                ✕ Rejeter
                                            </button>

                                            <!-- Formulaire de rejet -->
                                            <form
                                                id="form-rejet-<?= $e($bienId) ?>"
                                                method="POST"
                                                action="/immo-sn/admin/biens/rejeter/<?= rawurlencode($bienId) ?>"
                                                class="hidden sm:col-span-2"
                                                onsubmit="return confirm('Confirmer le rejet de ce bien ?');"
                                            >
                                                <label
                                                    for="motif-rejet-<?= $e($bienId) ?>"
                                                    class="mb-2 block text-sm font-semibold text-[#5a402d]"
                                                >
                                                    Motif du rejet <span class="text-red-600">*</span>
                                                </label>

                                                <textarea
                                                    id="motif-rejet-<?= $e($bienId) ?>"
                                                    name="motif_rejet"
                                                    rows="3"
                                                    required
                                                    minlength="5"
                                                    maxlength="2000"
                                                    placeholder="Expliquez les corrections à apporter au propriétaire..."
                                                    class="w-full rounded-xl border border-[#ddd2c7] bg-[#fdfbf9] p-3 text-sm text-[#30221b] outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                                                ></textarea>

                                                <button
                                                    type="submit"
                                                    class="mt-3 w-full rounded-xl bg-[#a43d35] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#842f29]"
                                                >
                                                    Confirmer le rejet
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="rounded-[24px] border border-[#e8ded4] bg-white px-6 py-16 text-center shadow-sm">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f2e8e1] text-[#a98260]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V9l7-5 7 5v12"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#30221b]">
                            Aucun bien trouvé
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#9a887a]">
                            Aucune annonce ne correspond à cette recherche. Modifiez les filtres ou réinitialisez-les.
                        </p>

                        <a href="/immo-sn/admin/biens"
                           class="mt-5 inline-flex rounded-xl bg-[#30221b] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#5a402d]">
                            Afficher tous les biens
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($totalPages > 1): ?>
                    <nav class="mt-7 flex flex-col gap-4 rounded-[20px] border border-[#e8ded4] bg-white p-4 sm:flex-row sm:items-center sm:justify-between"
                         aria-label="Pagination des biens">

                        <p class="text-sm text-[#8d7a6b]">
                            Page <strong class="text-[#30221b]"><?= $page ?></strong>
                            sur <strong class="text-[#30221b]"><?= $totalPages ?></strong>
                        </p>

                        <div class="flex flex-wrap items-center gap-2">
                            <?php if ($page > 1): ?>
                                <a href="<?= $e($buildPageUrl(1)) ?>"
                                   class="rounded-lg border border-[#ddcfc2] px-3 py-2 text-sm font-semibold text-[#5a402d] hover:bg-[#faf7f2]">
                                    Première
                                </a>

                                <a href="<?= $e($buildPageUrl($page - 1)) ?>"
                                   class="rounded-lg border border-[#ddcfc2] px-3 py-2 text-sm font-semibold text-[#5a402d] hover:bg-[#faf7f2]">
                                    Précédente
                                </a>
                            <?php endif; ?>

                            <?php
                            $premierePage = max(1, $page - 2);
                            $dernierePage = min($totalPages, $page + 2);

                            for ($numero = $premierePage; $numero <= $dernierePage; $numero++):
                            ?>
                                <a href="<?= $e($buildPageUrl($numero)) ?>"
                                   class="rounded-lg px-3.5 py-2 text-sm font-bold <?= $numero === $page ? 'bg-[#30221b] text-white' : 'border border-[#ddcfc2] text-[#5a402d] hover:bg-[#faf7f2]' ?>"
                                   <?= $numero === $page ? 'aria-current="page"' : '' ?>>
                                    <?= $numero ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="<?= $e($buildPageUrl($page + 1)) ?>"
                                   class="rounded-lg border border-[#ddcfc2] px-3 py-2 text-sm font-semibold text-[#5a402d] hover:bg-[#faf7f2]">
                                    Suivante
                                </a>

                                <a href="<?= $e($buildPageUrl($totalPages)) ?>"
                                   class="rounded-lg border border-[#ddcfc2] px-3 py-2 text-sm font-semibold text-[#5a402d] hover:bg-[#faf7f2]">
                                    Dernière
                                </a>
                            <?php endif; ?>
                        </div>
                    </nav>
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

            // Nettoyer l'URL pour éviter de réafficher
            // le même message lors d'un rafraîchissement.
            const url = new URL(window.location.href);
            url.searchParams.delete('message');
            url.searchParams.delete('type');

            window.history.replaceState({}, document.title, url.pathname + url.search + url.hash);
        }, 500);
    }, 4000);
});
</script>
