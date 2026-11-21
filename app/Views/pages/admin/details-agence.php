
<?php
/**
 * Vue : Détails d'une agence partenaire — IMMO-SN PREMIUM
 * Emplacement : app/Views/pages/admin/details-agence.php
 */

$agence = is_array($agence ?? null) ? $agence : [];
$biens = is_array($biens ?? null) ? $biens : [];

$page = max(1, (int) ($page ?? 1));
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalBiens = max(0, (int) ($totalBiens ?? 0));

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
};

$nomAgence = $agence['nom_agence'] ?? 'Agence sans nom';
$statut = strtolower(trim((string) ($agence['statut'] ?? '')));

$statuts = [
    'en_attente' => 'En attente',
    'actif' => 'Active',
    'suspendue' => 'Suspendue',
    'refusee' => 'Refusée',
];

$statutsBiens = [
    'actif' => 'Actif',
    'en_attente' => 'En attente',
    'rejete' => 'Rejeté',
    'rejeté' => 'Rejeté',
    'refuse' => 'Refusé',
    'refusé' => 'Refusé',
    'expire' => 'Expiré',
    'expiré' => 'Expiré',
    'suspendu' => 'Suspendu',
    'suspendue' => 'Suspendu',
    'brouillon' => 'Brouillon',
];

$badgeVerifie = filter_var(
    $agence['badge_verifie'] ?? false,
    FILTER_VALIDATE_BOOLEAN
);

$dateCreation = $agence['created_at'] ?? null;
$logo = trim((string) ($agence['logo'] ?? ''));

$idAgence = (string) ($agence['id'] ?? '');

$urlDetails = '/immo-sn/admin/agences/'
    . rawurlencode($idAgence)
    . '/details';

$premierBien = $totalBiens > 0
    ? (($page - 1) * 3) + 1
    : 0;

$dernierBien = min($page * 3, $totalBiens);

$statutConfig = [
    'actif' => [
        'classe' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'point' => 'bg-emerald-500',
    ],
    'en_attente' => [
        'classe' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'point' => 'bg-amber-500',
    ],
    'suspendue' => [
        'classe' => 'bg-red-50 text-red-700 ring-red-200',
        'point' => 'bg-red-500',
    ],
    'refusee' => [
        'classe' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'point' => 'bg-rose-500',
    ],
];

$configStatut = $statutConfig[$statut] ?? [
    'classe' => 'bg-slate-100 text-slate-600 ring-slate-200',
    'point' => 'bg-slate-400',
];
?>

<div class="min-h-screen bg-[#f5f7fb] px-4 py-6 text-[#0b1528] sm:px-6 sm:py-8 lg:px-8">

    <div class="mx-auto max-w-[1500px]">

        <!-- RETOUR -->
        <a href="/immo-sn/admin/agences"
           class="group inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-slate-500 transition hover:bg-white hover:text-[#071b36]">
            <span class="transition group-hover:-translate-x-1">←</span>
            Retour à la liste des agences
        </a>

        <!-- =====================================================
             HEADER AGENCE
        ====================================================== -->
        <header class="relative mt-5 overflow-hidden rounded-[28px] bg-[#071b36] p-6 text-white shadow-[0_18px_50px_rgba(7,27,54,0.12)] sm:p-8 lg:p-10">

            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 left-1/3 h-64 w-64 rounded-full bg-[#0e2a4e]/80 blur-3xl"></div>

            <div class="relative flex flex-col justify-between gap-6 xl:flex-row xl:items-center">

                <div class="flex min-w-0 items-center gap-5">

                    <!-- LOGO -->
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-white/10 bg-white p-2 sm:h-24 sm:w-24">

                        <?php if ($logo !== ''): ?>
                            <img src="<?= $e($logo) ?>"
                                 alt="Logo de <?= $e($nomAgence) ?>"
                                 class="h-full w-full object-contain">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-10 w-10 text-[#071b36]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 10.5L12 3l9 7.5M5.25 9v10.75h13.5V9M9 21v-6h6v6"/>
                            </svg>
                        <?php endif; ?>

                    </div>

                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-black uppercase tracking-[0.18em] text-[#f5b800]">
                                Administration · Partenaires
                            </span>
                        </div>

                        <h1 class="mt-2 break-words text-2xl font-black tracking-tight sm:text-3xl lg:text-4xl">
                            <?= $e($nomAgence) ?>
                        </h1>

                        <p class="mt-2 text-sm text-slate-300">
                            Fiche complète de l'agence partenaire
                        </p>

                        <div class="mt-4 flex flex-wrap items-center gap-2">

                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold ring-1 <?= $configStatut['classe'] ?>">
                                <span class="h-2 w-2 rounded-full <?= $configStatut['point'] ?>"></span>
                                <?= $e($statuts[$statut] ?? 'Statut inconnu') ?>
                            </span>

                            <?php if ($badgeVerifie): ?>
                                <span class="inline-flex items-center gap-2 rounded-full bg-[#f5b800] px-3 py-1.5 text-xs font-bold text-[#071b36]">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-4 w-4"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 12l2 2 4-4m5 2a8 8 0 11-16 0 8 8 0 0116 0z"/>
                                    </svg>
                                    Agence vérifiée
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-xs font-semibold text-slate-300">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                    Non vérifiée
                                </span>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!-- RÉSUMÉ PORTFOLIO -->
                <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-[#0e2a4e]/70 p-5 sm:min-w-[240px]">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#f5b800]/10 text-[#f5b800]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 21h18M5 21V5l7-3v19m7 0V9l-7-4M9 8v.01M9 12v.01M9 16v.01M15 12v.01M15 16v.01"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400">
                            Portefeuille immobilier
                        </p>
                        <p class="mt-1 text-3xl font-black text-white">
                            <?= number_format($totalBiens, 0, ',', ' ') ?>
                        </p>
                        <p class="text-xs text-slate-400">
                            bien(s) enregistré(s)
                        </p>
                    </div>
                </div>

            </div>
        </header>

        <!-- =====================================================
             INFORMATIONS AGENCE + RESPONSABLE
        ====================================================== -->
        <div class="mt-6 grid grid-cols-1 gap-5 xl:grid-cols-3">

            <!-- INFORMATIONS PROFESSIONNELLES -->
            <section class="rounded-[24px] border border-slate-200/80 bg-white p-5 shadow-[0_4px_22px_rgba(7,27,54,0.035)] sm:p-7 xl:col-span-2">

                <div class="flex items-center gap-3 border-b border-slate-100 pb-5">

                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071b36]/5 text-[#071b36]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <rect x="3" y="7" width="18" height="14" rx="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8 7V4h8v3M7 11h2m6 0h2M7 15h2m6 0h2M10 21v-4h4v4"/>
                        </svg>
                    </span>

                    <div>
                        <h2 class="text-lg font-black text-[#071b36]">
                            Informations professionnelles
                        </h2>
                        <p class="text-xs text-slate-500">
                            Identité et coordonnées de l'agence
                        </p>
                    </div>

                </div>

                <!-- INFORMATIONS -->
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <!-- NOM -->
                    <div class="rounded-xl bg-[#f8fafc] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Nom de l'agence
                        </p>
                        <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                            <?= $e($nomAgence) ?>
                        </p>
                    </div>

                    <!-- NINEA -->
                    <div class="rounded-xl bg-[#f8fafc] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            NINEA
                        </p>
                        <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                            <?= $e($agence['ninea'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <!-- EMAIL -->
                    <div class="rounded-xl bg-[#f8fafc] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Email professionnel
                        </p>
                        <p class="mt-2 break-all text-sm font-bold text-[#071b36]">
                            <?= $e($agence['email_agence'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <!-- TÉLÉPHONE -->
                    <div class="rounded-xl bg-[#f8fafc] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Téléphone professionnel
                        </p>
                        <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                            <?= $e($agence['telephone_agence'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <!-- ADRESSE -->
                    <div class="rounded-xl bg-[#f8fafc] p-4 sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Adresse professionnelle
                        </p>
                        <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                            <?= $e($agence['adresse'] ?? 'Non renseignée') ?>
                        </p>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="rounded-xl border border-[#0e2a4e]/10 bg-[#0e2a4e]/[0.03] p-4 sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Description
                        </p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-7 text-slate-600">
                            <?= $e($agence['description'] ?? 'Aucune description renseignée.') ?>
                        </p>
                    </div>

                </div>
            </section>

            <!-- RESPONSABLE -->
            <section class="rounded-[24px] border border-slate-200/80 bg-white p-5 shadow-[0_4px_22px_rgba(7,27,54,0.035)] sm:p-7">

                <div class="flex items-center gap-3 border-b border-slate-100 pb-5">

                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f5b800]/15 text-[#071b36]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0"/>
                        </svg>
                    </span>

                    <div>
                        <h2 class="text-lg font-black text-[#071b36]">
                            Responsable
                        </h2>
                        <p class="text-xs text-slate-500">
                            Compte personnel associé
                        </p>
                    </div>

                </div>

                <div class="mt-6 space-y-4">

                    <!-- NOM -->
                    <div class="rounded-xl bg-[#f8fafc] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Nom complet
                        </p>
                        <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                            <?= $e(trim(
                                ($agence['responsable_prenom'] ?? '')
                                . ' '
                                . ($agence['responsable_nom'] ?? '')
                            ) ?: 'Non renseigné') ?>
                        </p>
                    </div>

                    <!-- EMAIL -->
                    <div class="rounded-xl bg-[#f8fafc] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Email personnel
                        </p>
                        <p class="mt-2 break-all text-sm font-bold text-[#071b36]">
                            <?= $e($agence['responsable_email'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <!-- TÉLÉPHONE -->
                    <div class="rounded-xl bg-[#f8fafc] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Téléphone personnel
                        </p>
                        <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                            <?= $e($agence['responsable_telephone'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <!-- VILLE -->
                    <div class="rounded-xl bg-[#f8fafc] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Ville
                        </p>
                        <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                            <?= $e($agence['responsable_ville'] ?? 'Non renseignée') ?>
                        </p>
                    </div>

                </div>
            </section>

        </div>

        <!-- =====================================================
             PORTFOLIO IMMOBILIER
        ====================================================== -->
        <section class="mt-8">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                <div>
                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[#0e2a4e]">
                        Portefeuille immobilier
                    </p>

                    <h2 class="mt-1 text-2xl font-black tracking-tight text-[#071b36] sm:text-3xl">
                        Biens de cette agence
                    </h2>

                    <p class="mt-2 text-sm text-slate-500">
                        Toutes les annonces associées à cette agence.
                    </p>
                </div>

                <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071b36]/5 text-[#071b36]">
                        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 21h18M5 21V5l7-3v19m7 0V9l-7-4M9 8v.01M9 12v.01M9 16v.01M15 12v.01M15 16v.01"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-400">
                            Total des biens
                        </p>
                        <p class="text-lg font-black text-[#071b36]">
                            <?= number_format($totalBiens, 0, ',', ' ') ?>
                        </p>
                    </div>
                </div>

            </div>

            <!-- AUCUN BIEN -->
            <?php if (empty($biens)): ?>

                <div class="mt-5 rounded-[24px] border border-dashed border-slate-300 bg-white px-6 py-14 text-center">

                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#071b36]/5 text-[#0e2a4e]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-8 w-8"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 10.5L12 3l9 7.5M5.25 9v10.75h13.5V9M9 21v-6h6v6"/>
                        </svg>
                    </span>

                    <h3 class="mt-5 text-lg font-black text-[#071b36]">
                        Aucun bien enregistré
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Cette agence ne possède actuellement aucune annonce.
                    </p>

                </div>

            <?php else: ?>

                <!-- GRILLE DES BIENS -->
                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                    <?php foreach ($biens as $bien): ?>

                        <?php
                        $statutBien = strtolower(trim(
                            (string) ($bien['statut'] ?? '')
                        ));

                        $libelleStatut = $statutsBiens[$statutBien]
                            ?? ucfirst($statutBien ?: 'Inconnu');

                        $photo = trim((string) ($bien['photo'] ?? ''));

                        $localisation = array_filter([
                            $bien['quartier_nom'] ?? '',
                            $bien['ville_nom'] ?? '',
                        ], static fn($valeur) => trim((string) $valeur) !== '');

                        $localisationTexte = implode(', ', $localisation);

                        $statutBienClasses = [
                            'actif' => 'bg-emerald-500 text-white',
                            'en_attente' => 'bg-amber-400 text-[#071b36]',
                            'rejete' => 'bg-red-500 text-white',
                            'rejeté' => 'bg-red-500 text-white',
                            'refuse' => 'bg-red-500 text-white',
                            'refusé' => 'bg-red-500 text-white',
                            'expire' => 'bg-orange-500 text-white',
                            'expiré' => 'bg-orange-500 text-white',
                            'suspendu' => 'bg-slate-600 text-white',
                            'suspendue' => 'bg-slate-600 text-white',
                            'brouillon' => 'bg-slate-500 text-white',
                        ];

                        $classeStatutBien = $statutBienClasses[$statutBien]
                            ?? 'bg-[#071b36] text-white';
                        ?>

                        <article class="group overflow-hidden rounded-[22px] border border-slate-200/80 bg-white shadow-[0_4px_22px_rgba(7,27,54,0.035)] transition duration-300 hover:-translate-y-1 hover:border-[#0e2a4e]/30 hover:shadow-[0_14px_32px_rgba(7,27,54,0.08)]">

                            <!-- IMAGE -->
                            <div class="relative h-52 overflow-hidden bg-[#0e2a4e]/5">

                                <?php if ($photo !== ''): ?>

                                    <img src="<?= $e($photo) ?>"
                                         alt="<?= $e($bien['titre'] ?? 'Photo du bien') ?>"
                                         class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                         loading="lazy">

                                <?php else: ?>

                                    <div class="flex h-full items-center justify-center text-[#0e2a4e]/30">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-14 w-14"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M3 10.5L12 3l9 7.5M5.25 9v10.75h13.5V9M9 21v-6h6v6"/>
                                        </svg>
                                    </div>

                                <?php endif; ?>

                                <!-- STATUS -->
                                <span class="absolute left-3 top-3 rounded-lg px-3 py-1.5 text-xs font-bold shadow-sm <?= $classeStatutBien ?>">
                                    <?= $e($libelleStatut) ?>
                                </span>

                                <!-- IMAGE GRADIENT -->
                                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#071b36]/35 to-transparent"></div>

                            </div>

                            <!-- CONTENU -->
                            <div class="p-5">

                                <p class="text-[11px] font-black uppercase tracking-[0.12em] text-[#0e2a4e]">
                                    <?= $e($bien['categorie_nom'] ?? 'Catégorie non renseignée') ?>
                                </p>

                                <h3 class="mt-2 line-clamp-2 min-h-[3.5rem] text-lg font-black leading-6 text-[#071b36]">
                                    <?= $e($bien['titre'] ?? 'Bien sans titre') ?>
                                </h3>

                                <!-- PRIX -->
                                <p class="mt-2 text-xl font-black text-[#0e2a4e]">
                                    <?php if (isset($bien['prix']) && $bien['prix'] !== ''): ?>
                                        <?= $e(number_format((float) $bien['prix'], 0, ',', ' ')) ?>
                                        <span class="text-sm font-bold text-slate-500">FCFA</span>
                                    <?php else: ?>
                                        <span class="text-sm font-semibold text-slate-400">
                                            Prix non renseigné
                                        </span>
                                    <?php endif; ?>
                                </p>

                                <!-- DÉTAILS -->
                                <div class="mt-4 space-y-3 border-t border-slate-100 pt-4 text-sm text-slate-500">

                                    <!-- LOCALISATION -->
                                    <div class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="mt-0.5 h-4 w-4 shrink-0 text-[#0e2a4e]"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M12 21s8-7.5 8-12a8 8 0 10-16 0c0 4.5 8 12 8 12z"/>
                                            <circle cx="12" cy="9" r="2.5"/>
                                        </svg>

                                        <span class="break-words">
                                            <?= $localisationTexte !== ''
                                                ? $e($localisationTexte)
                                                : 'Localisation non renseignée' ?>
                                        </span>
                                    </div>

                                    <!-- CARACTÉRISTIQUES -->
                                    <div class="flex flex-wrap gap-2">

                                        <?php if (isset($bien['surface']) && $bien['surface'] !== ''): ?>
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-50 px-2.5 py-1.5 text-xs font-semibold">
                                                <span class="text-[#0e2a4e]">▱</span>
                                                <?= $e($bien['surface']) ?> m²
                                            </span>
                                        <?php endif; ?>

                                        <?php if (isset($bien['pieces']) && $bien['pieces'] !== ''): ?>
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-50 px-2.5 py-1.5 text-xs font-semibold">
                                                <span class="text-[#0e2a4e]">⌂</span>
                                                <?= $e($bien['pieces']) ?> pièce(s)
                                            </span>
                                        <?php endif; ?>

                                        <?php if (isset($bien['chambres']) && $bien['chambres'] !== ''): ?>
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-50 px-2.5 py-1.5 text-xs font-semibold">
                                                <span class="text-[#0e2a4e]">♧</span>
                                                <?= $e($bien['chambres']) ?> chambre(s)
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <!-- TYPE OFFRE -->
                                    <?php if (!empty($bien['type_offre'])): ?>
                                        <div class="flex items-center gap-2">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#f5b800]"></span>
                                            Offre :
                                            <span class="font-semibold text-[#071b36]">
                                                <?= $e(ucfirst((string) $bien['type_offre'])) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <!-- FOOTER CARD -->
                                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">

                                    <span class="text-xs text-slate-400">
                                        <?php if (!empty($bien['created_at']) && strtotime((string) $bien['created_at']) !== false): ?>
                                            Publié le <?= $e(date('d/m/Y', strtotime((string) $bien['created_at']))) ?>
                                        <?php else: ?>
                                            Date inconnue
                                        <?php endif; ?>
                                    </span>

                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-[#071b36]/5 px-2.5 py-1.5 text-xs font-bold text-[#0e2a4e]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <?= (int) ($bien['nb_vues'] ?? 0) ?> vue(s)
                                    </span>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

                <!-- =================================================
                     PAGINATION
                ================================================== -->
                <?php if ($totalPages > 1): ?>

                    <div class="mt-7 flex flex-col items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row">

                        <p class="text-sm text-slate-500">
                            Affichage de
                            <strong class="text-[#071b36]"><?= $premierBien ?></strong>
                            à
                            <strong class="text-[#071b36]"><?= $dernierBien ?></strong>
                            sur
                            <strong class="text-[#071b36]"><?= $totalBiens ?></strong>
                            biens
                        </p>

                        <nav class="flex flex-wrap items-center justify-center gap-2"
                             aria-label="Pagination des biens">

                            <?php if ($page > 1): ?>
                                <a href="<?= $e($urlDetails . '?page=' . ($page - 1)) ?>"
                                   class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-[#071b36] transition hover:border-[#0e2a4e]/30 hover:bg-[#f8fafc]">
                                    ← Précédent
                                </a>
                            <?php endif; ?>

                            <?php for ($numero = 1; $numero <= $totalPages; $numero++): ?>

                                <a href="<?= $e($urlDetails . '?page=' . $numero) ?>"
                                   aria-current="<?= $numero === $page ? 'page' : 'false' ?>"
                                   class="flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-sm font-bold transition
                                   <?= $numero === $page
                                       ? 'bg-[#071b36] text-[#f5b800] shadow-sm'
                                       : 'border border-slate-200 bg-white text-[#071b36] hover:bg-[#f8fafc]' ?>">
                                    <?= $numero ?>
                                </a>

                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="<?= $e($urlDetails . '?page=' . ($page + 1)) ?>"
                                   class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-[#071b36] transition hover:border-[#0e2a4e]/30 hover:bg-[#f8fafc]">
                                    Suivant →
                                </a>
                            <?php endif; ?>

                        </nav>

                    </div>

                <?php endif; ?>

            <?php endif; ?>

        </section>

    </div>
</div>
