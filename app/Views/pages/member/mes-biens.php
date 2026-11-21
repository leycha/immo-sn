
<?php
/**
 * Vue : Mes biens
 * Emplacement : app/Views/pages/member/mes-biens.php
 * Design : IMMO-SN — Premium Navy & Gold
 */

$biens = is_array($biens ?? null) ? $biens : [];
$renouvellements = is_array($renouvellements ?? null) ? $renouvellements : [];

$success = $success ?? null;
$error = $error ?? null;

$e = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

$normaliserStatut = static function ($statut): string {
    return strtolower(str_replace(
        ['-', ' '],
        '_',
        trim((string) ($statut ?? ''))
    ));
};

$totalBiens = count($biens);
$valides = 0;
$rejetes = 0;
$attente = 0;

foreach ($biens as $bien) {
    $statutBien = $normaliserStatut($bien['statut'] ?? '');

    if ($statutBien === 'actif') {
        $valides++;
    }

    if (in_array($statutBien, ['rejete', 'rejeté'], true)) {
        $rejetes++;
    }

    if ($statutBien === 'en_attente') {
        $attente++;
    }
}
?>

<div class="min-h-screen bg-[#F5F3ED]">

    <!-- =====================================================
         HEADER
    ====================================================== -->
    <header class="sticky top-0 z-20 border-b border-[#E8E1D5] bg-white/95 backdrop-blur-xl">
        <div class="flex min-h-[82px] flex-col justify-center gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">

            <div class="min-w-0">
                <div class="mb-2 flex items-center gap-2 text-xs font-semibold">
                    <span class="text-[#A98236]">Espace membre</span>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-3.5 w-3.5 text-[#A8A29A]"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m9 18 6-6-6-6"/>
                    </svg>

                    <span class="text-[#718096]">Mes biens</span>
                </div>

                <h1 class="break-words text-2xl font-extrabold tracking-tight text-[#071B36] sm:text-3xl">
                    Mes biens
                </h1>

                <p class="mt-1 text-sm text-[#718096]">
                    Gérez vos annonces immobilières depuis votre espace membre.
                </p>
            </div>

            <!-- ACTIONS -->
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:flex-wrap sm:items-center">

                <?php if (!empty($biens)): ?>
                    <a href="/immo-sn/member/qr-codes/zip"
                       title="Télécharger tous mes QR codes"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#DED5C5] bg-white px-4 py-3 text-sm font-bold text-[#071B36] shadow-sm transition duration-200 hover:border-[#C69A3A] hover:bg-[#FBF8F1] sm:w-auto">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 shrink-0 text-[#A98236]"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 16.5v2A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5v-2M12 3v12m0 0 4-4m-4 4-4-4"/>
                        </svg>

                        <span class="hidden sm:inline">Télécharger mes QR codes</span>
                        <span class="sm:hidden">QR codes ZIP</span>
                    </a>
                <?php endif; ?>

                <a href="/immo-sn/member/publier"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#D7A72E] px-5 py-3 text-sm font-extrabold text-[#071B36] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-[#E5B943] hover:shadow-md sm:w-auto">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                    <span class="hidden sm:inline">Publier un bien</span>
                    <span class="sm:hidden">Publier</span>
                </a>
            </div>
        </div>
    </header>

    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->
    <main class="px-4 py-6 sm:px-6 md:px-8 md:py-8">

        <!-- MESSAGES -->
        <?php if (!empty($success)): ?>
            <div class="mx-auto mb-6 max-w-7xl">
                <div class="flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800 shadow-sm">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-100">
                        <svg class="h-5 w-5"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <div class="min-w-0 pt-0.5">
                        <p class="font-bold">Opération réussie</p>
                        <p class="mt-1 break-words text-sm"><?= $e($success) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="mx-auto mb-6 max-w-7xl">
                <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 shadow-sm">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100">
                        <svg class="h-5 w-5"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v4m0 4h.01M10.3 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.86a2 2 0 0 0-3.4 0Z"/>
                        </svg>
                    </div>

                    <div class="min-w-0 pt-0.5">
                        <p class="font-bold">Une erreur est survenue</p>
                        <p class="mt-1 break-words text-sm"><?= $e($error) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mx-auto max-w-7xl">

            <!-- =================================================
                 STATISTIQUES
            ================================================== -->
            <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <!-- TOTAL -->
                <div class="group relative overflow-hidden rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-[#D7A72E]/10 transition group-hover:bg-[#D7A72E]/20"></div>

                    <div class="relative flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-[#718096]">
                                Total des biens
                            </p>

                            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-[#071B36]">
                                <?= $totalBiens ?>
                            </h2>

                            <p class="mt-1 text-xs text-[#8993A2]">
                                Annonces enregistrées
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#FBF3DE] text-[#A98236] transition group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 12l9-8 9 8M5 10v9h14v-9M9 19v-5h6v5"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- VALIDÉS -->
                <div class="group relative overflow-hidden rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-green-500/5"></div>

                    <div class="relative flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-[#718096]">
                                Biens validés
                            </p>

                            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-[#071B36]">
                                <?= $valides ?>
                            </h2>

                            <p class="mt-1 text-xs text-green-700">
                                Annonces actuellement actives
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-green-50 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- REJETÉS -->
                <div class="group relative overflow-hidden rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-red-500/5"></div>

                    <div class="relative flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-[#718096]">
                                Biens rejetés
                            </p>

                            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-[#071B36]">
                                <?= $rejetes ?>
                            </h2>

                            <p class="mt-1 text-xs text-red-600">
                                Annonces refusées lors de la validation
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- EN ATTENTE -->
                <div class="group relative overflow-hidden rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-[#D7A72E]/10"></div>

                    <div class="relative flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-[#718096]">
                                En attente
                            </p>

                            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-[#071B36]">
                                <?= $attente ?>
                            </h2>

                            <p class="mt-1 text-xs text-[#A98236]">
                                Annonces en cours de validation
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#FBF3DE] text-[#A98236]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- =================================================
                 SECTION MES ANNONCES
            ================================================== -->
            <section class="overflow-hidden rounded-2xl border border-[#E8E1D5] bg-white shadow-sm">

                <!-- EN-TÊTE -->
                <div class="flex flex-col gap-4 border-b border-[#EEE9DF] bg-white px-5 py-5 sm:flex-row sm:items-center sm:justify-between md:px-7">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#071B36] text-[#E2B94F] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 12l9-8 9 8M5 10v9h14v-9M9 19v-5h6v5"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <h2 class="break-words text-lg font-extrabold text-[#071B36]">
                                Mes annonces
                            </h2>

                            <p class="mt-1 text-xs text-[#718096]">
                                Consultez et gérez vos biens immobiliers.
                            </p>
                        </div>
                    </div>

                    <div class="inline-flex w-fit items-center gap-2 rounded-full border border-[#E9D9B5] bg-[#FBF6E9] px-4 py-2 text-xs font-bold text-[#8A6824]">
                        <span class="h-2 w-2 rounded-full bg-[#D7A72E]"></span>
                        <?= $totalBiens ?>
                        <span>bien(s)</span>
                    </div>
                </div>

                <?php if (empty($biens)): ?>

                    <!-- ÉTAT VIDE -->
                    <div class="px-6 py-20 text-center">
                        <div class="relative mx-auto flex h-28 w-28 items-center justify-center rounded-[2rem] bg-[#F8F2E4]">
                            <div class="absolute inset-2 rounded-[1.5rem] border border-[#E8D8B5]"></div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="relative h-12 w-12 text-[#A98236]"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"/>
                            </svg>
                        </div>

                        <h3 class="mt-7 break-words text-xl font-extrabold text-[#071B36] sm:text-2xl">
                            Vous n'avez pas encore de bien
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#718096]">
                            Commencez à publier votre premier bien immobilier
                            pour le proposer aux visiteurs de ImmoSn.
                        </p>

                        <a href="/immo-sn/member/publier"
                           class="mt-7 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#D7A72E] px-6 py-3.5 text-sm font-extrabold text-[#071B36] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#E5B943] hover:shadow-md sm:w-auto">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 4v16m8-8H4"/>
                            </svg>

                            Publier mon premier bien
                        </a>
                    </div>

                <?php else: ?>

                    <!-- CARTES DES BIENS -->
                    <div class="p-5 md:p-7">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

                            <?php foreach ($biens as $bien): ?>

                                <?php
                                $statut = $normaliserStatut($bien['statut'] ?? '');

                                if ($statut === 'actif') {
                                    $statutLabel = 'Validé';
                                    $statutClass = 'bg-green-50 text-green-700 ring-green-200';
                                    $statutDot = 'bg-green-500';
                                } elseif ($statut === 'en_attente') {
                                    $statutLabel = 'En attente';
                                    $statutClass = 'bg-[#FBF5E8] text-[#967027] ring-[#EAD9B4]';
                                    $statutDot = 'bg-[#D7A72E]';
                                } elseif (in_array($statut, ['rejete', 'rejeté'], true)) {
                                    $statutLabel = 'Rejeté';
                                    $statutClass = 'bg-red-50 text-red-700 ring-red-200';
                                    $statutDot = 'bg-red-500';
                                } elseif ($statut === 'expire') {
                                    $statutLabel = 'Expiré';
                                    $statutClass = 'bg-slate-100 text-slate-700 ring-slate-200';
                                    $statutDot = 'bg-slate-500';
                                } else {
                                    $statutLabel = ucfirst(str_replace('_', ' ', $statut ?: 'Inconnu'));
                                    $statutClass = 'bg-slate-100 text-slate-600 ring-slate-200';
                                    $statutDot = 'bg-slate-400';
                                }

                                $titre = $bien['titre'] ?? 'Bien sans titre';
                                $categorie = $bien['categorie_nom'] ?? 'Bien immobilier';
                                $ville = $bien['ville_nom'] ?? 'Localisation non renseignée';
                                $quartier = $bien['quartier_nom'] ?? '';
                                $photo = $bien['photo'] ?? '';
                                $idBien = $bien['id'] ?? '';

                                $demandeRenouvellement = $renouvellements[$idBien] ?? null;
                                $statutRenouvellement = $demandeRenouvellement['statut'] ?? null;
                                $renouvellementEnAttente = $statutRenouvellement === 'en_attente';
                                $motifRefus = $demandeRenouvellement['motif_refus'] ?? null;

                                $dateExpiration = $bien['expire_at'] ?? null;

                                $bienExpire = (
                                    $statut === 'expire'
                                    || (
                                        !empty($dateExpiration)
                                        && strtotime((string) $dateExpiration) <= time()
                                    )
                                );
                                ?>

                                <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-[#E8E1D5] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D7BC7C] hover:shadow-xl">

                                    <!-- PHOTO -->
                                    <div class="relative h-52 overflow-hidden bg-[#F7F2E7]">

                                        <?php if (!empty($photo)): ?>
                                            <img src="<?= $e($photo) ?>"
                                                 alt="<?= $e($titre) ?>"
                                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                                 loading="lazy">
                                        <?php else: ?>
                                            <div class="flex h-full w-full flex-col items-center justify-center text-[#A98236]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="mb-2 h-14 w-14"
                                                     fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="1.3">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"/>
                                                </svg>

                                                <span class="text-xs font-semibold">
                                                    Aucune photo disponible
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <!-- VOILE DISCRET -->
                                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#071B36]/20 via-transparent to-transparent"></div>

                                        <!-- STATUT -->
                                        <div class="absolute left-3 top-3">
                                            <span class="inline-flex items-center gap-2 rounded-full bg-white/95 px-3 py-1.5 text-xs font-extrabold shadow-md ring-1 <?= $statutClass ?>">
                                                <span class="h-2 w-2 rounded-full <?= $statutDot ?>"></span>
                                                <?= $e($statutLabel) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- INFORMATIONS -->
                                    <div class="flex min-w-0 flex-1 flex-col p-5">

                                        <p class="mb-2 break-words text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#A98236]">
                                            <?= $e($categorie) ?>
                                        </p>

                                        <h3 class="min-w-0 break-words text-lg font-extrabold leading-snug text-[#071B36] line-clamp-2">
                                            <?= $e($titre) ?>
                                        </h3>

                                        <!-- LOCALISATION -->
                                        <div class="mt-3 flex items-start gap-2 text-sm text-[#718096]">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="mt-0.5 h-4 w-4 shrink-0 text-[#B28A3E]"
                                                 fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="1.7">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                            </svg>

                                            <div class="min-w-0">
                                                <p class="break-words font-semibold text-[#26364B]">
                                                    <?= $e($ville) ?>
                                                </p>

                                                <?php if (!empty($quartier)): ?>
                                                    <p class="mt-0.5 break-words text-xs text-[#8993A2]">
                                                        <?= $e($quartier) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- PRIX -->
                                        <div class="mt-5 border-t border-[#EEE9DF] pt-4">
                                            <?php if (isset($bien['prix']) && $bien['prix'] !== ''): ?>
                                                <p class="text-xl font-extrabold text-[#071B36]">
                                                    <?= number_format((float) $bien['prix'], 0, ',', ' ') ?>
                                                    <span class="text-xs font-extrabold text-[#A98236]">FCFA</span>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-sm font-medium text-[#8993A2]">
                                                    Prix non renseigné
                                                </p>
                                            <?php endif; ?>
                                        </div>

                                        <!-- ACTIONS -->
                                        <div class="mt-auto pt-5">

                                            <?php if ($bienExpire): ?>

                                                <?php if ($renouvellementEnAttente): ?>

                                                    <div class="mb-3 rounded-xl border border-[#EAD9B4] bg-[#FBF6E9] p-3 text-sm text-[#8A6824]">
                                                        <div class="flex items-start gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="mt-0.5 h-5 w-5 shrink-0"
                                                                 fill="none" viewBox="0 0 24 24"
                                                                 stroke="currentColor" stroke-width="1.8">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>

                                                            <div>
                                                                <p class="font-extrabold">
                                                                    Demande de renouvellement en attente
                                                                </p>

                                                                <p class="mt-1 text-xs leading-5">
                                                                    Votre demande est en cours d'examen par notre équipe.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php else: ?>

                                                    <?php if ($statutRenouvellement === 'refusee'): ?>
                                                        <div class="mb-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                                                            <p class="font-extrabold">
                                                                Votre précédente demande a été refusée.
                                                            </p>

                                                            <?php if (!empty($motifRefus)): ?>
                                                                <p class="mt-1 text-xs leading-5">
                                                                    Motif : <?= $e($motifRefus) ?>
                                                                </p>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <form method="POST"
                                                          action="/immo-sn/member/biens/renouveler/<?= urlencode((string) $idBien) ?>"
                                                          class="mb-3"
                                                          onsubmit="return confirm('Confirmez-vous votre demande de renouvellement pour cette annonce ?');">

                                                        <input type="hidden"
                                                               name="_csrf_token"
                                                               value="<?= $e(\Core\Session::csrfToken()) ?>">

                                                        <button type="submit"
                                                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#D7B45E] bg-[#FBF5E6] px-4 py-3 text-sm font-extrabold text-[#80601F] transition hover:border-[#C69A3A] hover:bg-[#F5EBD2]">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="h-4 w-4"
                                                                 fill="none" viewBox="0 0 24 24"
                                                                 stroke="currentColor" stroke-width="1.8">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="M4 4v6h6M20 20v-6h-6M5.5 15a7 7 0 0 0 12.2 2M18.5 9A7 7 0 0 0 6.3 7"/>
                                                            </svg>

                                                            Demander le renouvellement
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                            <?php endif; ?>

                                            <a href="/immo-sn/member/biens/modifier/<?= urlencode((string) $idBien) ?>"
                                               class="mb-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#DED5C5] bg-white px-4 py-3 text-sm font-bold text-[#071B36] transition hover:border-[#C69A3A] hover:bg-[#FBF8F1]">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4 text-[#A98236]"
                                                     fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M16.862 4.487l2.651 2.651M8 16l8.5-8.5a2.121 2.121 0 013 3L11 19H8v-3z"/>
                                                </svg>

                                                Modifier mon annonce
                                            </a>

                                            <a href="/immo-sn/biens/<?= urlencode((string) $idBien) ?>"
                                               class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#071B36] px-4 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-[#142D4D] hover:shadow-md">

                                                Voir le bien

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4 text-[#E2B94F] transition group-hover:translate-x-1"
                                                     fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M5 12h14m-6-6 6 6-6 6"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </article>

                            <?php endforeach; ?>

                        </div>
                    </div>

                <?php endif; ?>

            </section>

            <!-- =================================================
                 ACCÈS RAPIDES
            ================================================== -->
            <section class="mt-9">

                <div class="mb-5">
                    <div class="flex items-center gap-2">
                        <span class="h-6 w-1 rounded-full bg-[#D7A72E]"></span>

                        <h2 class="text-xl font-extrabold text-[#071B36]">
                            Accès rapides
                        </h2>
                    </div>

                    <p class="mt-2 text-sm text-[#718096]">
                        Retrouvez rapidement les principales fonctionnalités de votre espace membre.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-3">

                    <!-- PUBLIER -->
                    <a href="/immo-sn/member/publier"
                       class="group rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D7BC7C] hover:shadow-lg">

                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#071B36] text-[#E2B94F] transition group-hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6"
                                         fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>

                                <h3 class="break-words font-extrabold text-[#071B36]">
                                    Publier un bien
                                </h3>

                                <p class="mt-1 break-words text-xs leading-5 text-[#718096]">
                                    Ajoutez une nouvelle annonce à votre espace.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-1 h-5 w-5 shrink-0 text-[#B5A98F] transition group-hover:translate-x-1 group-hover:text-[#A98236]"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m9 18 6-6-6-6"/>
                            </svg>
                        </div>
                    </a>

                    <!-- MES BIENS -->
                    <a href="/immo-sn/member/mes-biens"
                       class="group rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D7BC7C] hover:shadow-lg">

                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FBF3DE] text-[#A98236] transition group-hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6"
                                         fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9 21v-7h6v7"/>
                                    </svg>
                                </div>

                                <h3 class="break-words font-extrabold text-[#071B36]">
                                    Mes biens
                                </h3>

                                <p class="mt-1 break-words text-xs leading-5 text-[#718096]">
                                    Gérez vos annonces et consultez leur statut.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-1 h-5 w-5 shrink-0 text-[#B5A98F] transition group-hover:translate-x-1 group-hover:text-[#A98236]"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m9 18 6-6-6-6"/>
                            </svg>
                        </div>
                    </a>

                    <!-- ALERTES -->
                    <a href="/immo-sn/member/alertes"
                       class="group rounded-2xl border border-[#E8E1D5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D7BC7C] hover:shadow-lg">

                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FBF3DE] text-[#A98236] transition group-hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6"
                                         fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 0 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10 21h4"/>
                                    </svg>
                                </div>

                                <h3 class="break-words font-extrabold text-[#071B36]">
                                    Mes alertes
                                </h3>

                                <p class="mt-1 break-words text-xs leading-5 text-[#718096]">
                                    Recevez des annonces correspondant à vos critères.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-1 h-5 w-5 shrink-0 text-[#B5A98F] transition group-hover:translate-x-1 group-hover:text-[#A98236]"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m9 18 6-6-6-6"/>
                            </svg>
                        </div>
                    </a>

                </div>
            </section>

            <!-- SIGNATURE -->
            <div class="mt-9 flex flex-col items-center justify-center gap-2 border-t border-[#E6DFD3] pt-6 text-center sm:flex-row sm:justify-between">
                <p class="text-xs text-[#8993A2]">
                    Votre espace immobilier, simplement.
                </p>

                <p class="text-xs font-extrabold tracking-wider text-[#A98236]">
                    IMMO-SN
                    <span class="mx-1 text-[#D7A72E]">•</span>
                    L'immobilier en toute confiance
                </p>
            </div>

        </div>
    </main>
</div>
