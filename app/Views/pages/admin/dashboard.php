<?php

/**
 * =========================================================
 * DASHBOARD ADMINISTRATION — IMMO-SN PREMIUM
 * Fichier : app/Views/pages/admin/dashboard.php
 * =========================================================
 */

$stats = $stats ?? [];
$activitesRecentes = is_array($activitesRecentes ?? null)
    ? array_slice($activitesRecentes, 0, 6)
    : [];

$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$totalBiens = (int) ($stats['total_biens'] ?? 0);
$biensActifs = (int) ($stats['biens_actifs'] ?? 0);
$biensEnAttente = (int) ($stats['biens_en_attente'] ?? 0);
$biensRejetes = (int) ($stats['biens_rejetes'] ?? 0);
$biensExpires = (int) ($stats['biens_expires'] ?? 0);
$totalUtilisateurs = (int) ($stats['total_utilisateurs'] ?? 0);
$totalSignalements = (int) ($stats['total_signalements'] ?? 0);
$signalementsEnAttente = (int) ($stats['signalements_en_attente'] ?? 0);
$totalAgences = (int) ($stats['total_agences'] ?? 0);

$totalPriorites = $biensEnAttente + $signalementsEnAttente;
$dateDuJour = date('d/m/Y');

$pourcentage = static function (int $nombre, int $total): float {
    return $total > 0
        ? min(100, round(($nombre / $total) * 100, 1))
        : 0;
};

$tempsEcoule = static function ($date): string {
    if (empty($date)) {
        return 'Date inconnue';
    }

    $timestamp = strtotime((string) $date);

    if ($timestamp === false) {
        return 'Date inconnue';
    }

    $difference = max(0, time() - $timestamp);

    if ($difference < 60) {
        return 'À l’instant';
    }

    if ($difference < 3600) {
        return 'Il y a ' . floor($difference / 60) . ' min';
    }

    if ($difference < 86400) {
        return 'Il y a ' . floor($difference / 3600) . ' h';
    }

    if ($difference < 604800) {
        return 'Il y a ' . floor($difference / 86400) . ' j';
    }

    return date('d/m/Y à H:i', $timestamp);
};

$cartes = [
    [
        'titre' => 'Biens immobiliers',
        'valeur' => $totalBiens,
        'description' => 'Toutes les annonces enregistrées',
        'icone' => 'building-2',
        'couleur' => 'navy',
        'url' => '/immo-sn/admin/biens',
    ],
    [
        'titre' => 'Utilisateurs',
        'valeur' => $totalUtilisateurs,
        'description' => 'Comptes enregistrés',
        'icone' => 'users',
        'couleur' => 'blue',
        'url' => null,
    ],
    [
        'titre' => 'Agences',
        'valeur' => $totalAgences,
        'description' => 'Agences enregistrées',
        'icone' => 'agences',
        'couleur' => 'indigo',
        'url' => '/immo-sn/admin/agences',
    ],
    [
        'titre' => 'Signalements',
        'valeur' => $totalSignalements,
        'description' => 'Signalements reçus',
        'icone' => 'flag',
        'couleur' => 'red',
        'url' => '/immo-sn/admin/signalements',
    ],
];
?>

<div class="min-h-screen bg-[#f8fafc] text-[#0b1528]">

    <!-- EN-TÊTE -->
    <header class="sticky top-0 z-30 border-b border-[#e2e8f0] bg-white/95 backdrop-blur-xl">
        <div class="mx-auto flex max-w-[1600px] items-center justify-between gap-4 px-5 py-4 sm:px-8">

            <a href="/immo-sn/admin" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#071b36] text-[#f5b800] shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10.5L12 3l9 7.5M5.25 9v10.75h13.5V9M9 21v-6h6v6"/>
                    </svg>
                </span>

                <span>
                    <span class="block text-lg font-black tracking-tight text-[#071b36]">IMMO-SN</span>
                    <span class="block text-xs text-[#64748b]">Administration</span>
                </span>
            </a>

            <div class="flex items-center gap-3">
                <span class="hidden text-sm font-medium text-[#64748b] sm:block">
                    <?= $e($dateDuJour) ?>
                </span>

                <a href="/immo-sn/admin/signalements"
                   class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-[#e2e8f0] bg-white text-[#071b36] transition hover:bg-[#f8fafc]"
                   aria-label="Ouvrir les signalements"
                   title="Signalements">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12h4"/>
                    </svg>

                    <?php if ($signalementsEnAttente > 0): ?>
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#f5b800] px-1 text-[10px] font-black text-[#0b1528]">
                            <?= $signalementsEnAttente > 99 ? '99+' : $signalementsEnAttente ?>
                        </span>
                    <?php endif; ?>
                </a>

                <div class="hidden items-center gap-2 rounded-xl border border-[#e2e8f0] bg-white px-3 py-2 sm:flex">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#f8fafc] text-[#0e2a4e]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0"/>
                        </svg>
                    </span>
                    <span class="text-sm font-bold text-[#0b1528]">Administrateur</span>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-[1600px] space-y-8 px-5 py-7 sm:px-8 lg:py-10">

        <!-- ACCUEIL PREMIUM -->
        <section class="relative overflow-hidden rounded-[28px] bg-[#071b36] shadow-[0_18px_50px_rgba(7,27,54,0.16)]">
            <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 right-1/3 h-64 w-64 rounded-full bg-[#0e2a4e]/60 blur-3xl"></div>

            <div class="relative grid gap-8 p-6 sm:p-9 lg:grid-cols-[1fr_300px] lg:items-center lg:p-11">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-200">
                        <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                        CENTRE DE PILOTAGE IMMO-SN
                    </div>

                    <h1 class="mt-5 max-w-3xl text-3xl font-black leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                        Votre plateforme,
                        <span class="text-[#f5b800]">en un coup d’œil.</span>
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">
                        Suivez vos annonces immobilières, contrôlez leur publication
                        et gérez les activités de votre plateforme depuis un espace
                        centralisé.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="/immo-sn/admin/biens"
                           class="inline-flex items-center gap-2 rounded-xl bg-[#f5b800] px-5 py-3 text-sm font-extrabold text-[#0b1528] transition hover:-translate-y-0.5 hover:bg-[#ffca28]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 5v14m-7-7h14"/>
                            </svg>
                            Gérer les annonces
                        </a>

                        <a href="/immo-sn/admin/signalements"
                           class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                            Examiner les signalements
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-[#0e2a4e]/70 p-5 backdrop-blur-sm sm:p-6">
                    <p class="text-xs font-bold uppercase tracking-[0.15em] text-slate-400">
                        Votre priorité
                    </p>

                    <p class="mt-4 text-5xl font-black tracking-tight text-white">
                        <?= number_format($totalPriorites, 0, ',', ' ') ?>
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-300">
                        éléments en attente de traitement
                    </p>

                    <div class="mt-5 space-y-3">
                        <a href="/immo-sn/admin/biens?statut=en_attente"
                           class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-white/5 px-3 py-3 transition hover:bg-white/10">
                            <span class="text-sm text-white">Annonces à examiner</span>
                            <span class="font-black text-[#f5b800]">
                                <?= number_format($biensEnAttente, 0, ',', ' ') ?>
                            </span>
                        </a>

                        <a href="/immo-sn/admin/signalements"
                           class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-white/5 px-3 py-3 transition hover:bg-white/10">
                            <span class="text-sm text-white">Signalements en attente</span>
                            <span class="font-black text-white">
                                <?= number_format($signalementsEnAttente, 0, ',', ' ') ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- INDICATEURS -->
        <section>
            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-[#0e2a4e]">
                        Vue d'ensemble
                    </p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-[#071b36] sm:text-3xl">
                        Les chiffres essentiels
                    </h2>
                    <p class="mt-2 text-sm text-[#64748b]">
                        Indicateurs issus des données enregistrées dans la plateforme.
                    </p>
                </div>

                <span class="w-fit rounded-full border border-[#e2e8f0] bg-white px-3 py-2 text-xs font-semibold text-[#64748b]">
                    Mise à jour au chargement
                </span>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <?php foreach ($cartes as $carte): ?>
                    <?php
                    $couleurs = [
                        'navy' => [
                            'fond' => 'bg-[#071b36]/10',
                            'texte' => 'text-[#071b36]',
                            'barre' => 'bg-[#071b36]',
                        ],
                        'green' => [
                            'fond' => 'bg-emerald-50',
                            'texte' => 'text-emerald-700',
                            'barre' => 'bg-emerald-600',
                        ],
                        'gold' => [
                            'fond' => 'bg-amber-50',
                            'texte' => 'text-amber-700',
                            'barre' => 'bg-[#f5b800]',
                        ],
                        'red' => [
                            'fond' => 'bg-red-50',
                            'texte' => 'text-red-700',
                            'barre' => 'bg-red-500',
                        ],
                        'orange' => [
                            'fond' => 'bg-orange-50',
                            'texte' => 'text-orange-700',
                            'barre' => 'bg-orange-500',
                        ],
                        'blue' => [
                            'fond' => 'bg-sky-50',
                            'texte' => 'text-sky-700',
                            'barre' => 'bg-sky-500',
                        ],
                        'indigo' => [
                            'fond' => 'bg-indigo-50',
                            'texte' => 'text-indigo-700',
                            'barre' => 'bg-[#0e2a4e]',
                        ],
                    ];

                    $couleur = $couleurs[$carte['couleur']] ?? $couleurs['navy'];
                    $conteneur = $carte['url'] ? 'a' : 'div';
                    ?>

                    <<?= $conteneur ?>
                        <?php if ($carte['url']): ?>
                            href="<?= $e($carte['url']) ?>"
                        <?php endif; ?>
                        class="group relative overflow-hidden rounded-[22px] border border-[#e2e8f0] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] transition duration-300 hover:-translate-y-1 hover:border-[#0e2a4e]/40 hover:shadow-[0_14px_34px_rgba(7,27,54,0.08)] sm:p-6">

                        <div class="flex items-start justify-between gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl <?= $couleur['fond'] ?> <?= $couleur['texte'] ?>">
                                <?php if ($carte['icone'] === 'building-2'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V5l7-3v19m7 0V9l-7-4M9 8v.01M9 12v.01M9 16v.01M15 12v.01M15 16v.01"/>
                                    </svg>
                                <?php elseif ($carte['icone'] === 'agences'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.7">
                                        <rect x="3" y="7" width="18" height="14" rx="2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M8 7V4h8v3M7 11h2m6 0h2M7 15h2m6 0h2M10 21v-4h4v4"/>
                                    </svg>
                                <?php elseif ($carte['icone'] === 'users'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87m-1-11.13a4 4 0 010 7.75"/>
                                    </svg>
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M5 20h14a2 2 0 001.7-3.05l-7-12a2 2 0 00-3.4 0l-7 12A2 2 0 005 20z"/>
                                    </svg>
                                <?php endif; ?>
                            </span>

                            <?php if ($carte['url']): ?>
                                <span class="text-lg text-slate-400 transition group-hover:translate-x-1 group-hover:text-[#071b36]">↗</span>
                            <?php endif; ?>
                        </div>

                        <p class="mt-6 text-sm font-semibold text-[#64748b]">
                            <?= $e($carte['titre']) ?>
                        </p>

                        <p class="mt-1 text-4xl font-black tracking-tight text-[#071b36]">
                            <?= number_format($carte['valeur'], 0, ',', ' ') ?>
                        </p>

                        <p class="mt-2 text-xs leading-5 text-[#64748b]">
                            <?= $e($carte['description']) ?>
                        </p>

                        <div class="mt-5 h-1 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full <?= $couleur['barre'] ?> transition-all duration-500"
                                 style="width: <?= $pourcentage($carte['valeur'], max(1, $totalBiens, $totalUtilisateurs, $totalSignalements)) ?>%">
                            </div>
                        </div>
                    </<?= $conteneur ?>>

                <?php endforeach; ?>

            </div>
        </section>

        <!-- SUIVI DES ANNONCES -->
        <section class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">

            <div class="rounded-[24px] border border-[#e2e8f0] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] sm:p-7">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-[#0e2a4e]">
                            Modération
                        </p>
                        <h2 class="mt-1 text-xl font-black text-[#071b36]">État des annonces</h2>
                        <p class="mt-2 text-sm text-[#64748b]">
                            Une vue rapide des annonces selon leur état.
                        </p>
                    </div>

                    <a href="/immo-sn/admin/biens"
                       class="shrink-0 text-sm font-bold text-[#0e2a4e] hover:underline">
                        Tout voir →
                    </a>
                </div>

                <?php
                $lignesStatuts = [
                    [
                        'nom' => 'Actives',
                        'nombre' => $biensActifs,
                        'couleur' => 'bg-emerald-600',
                        'texte' => 'text-emerald-700',
                        'url' => '/immo-sn/admin/biens?statut=actif',
                    ],
                    [
                        'nom' => 'En attente',
                        'nombre' => $biensEnAttente,
                        'couleur' => 'bg-[#f5b800]',
                        'texte' => 'text-amber-700',
                        'url' => '/immo-sn/admin/biens?statut=en_attente',
                    ],
                    [
                        'nom' => 'Rejetées',
                        'nombre' => $biensRejetes,
                        'couleur' => 'bg-red-500',
                        'texte' => 'text-red-700',
                        'url' => '/immo-sn/admin/biens?statut=rejete',
                    ],
                    [
                        'nom' => 'Expirées',
                        'nombre' => $biensExpires,
                        'couleur' => 'bg-orange-500',
                        'texte' => 'text-orange-700',
                        'url' => '/immo-sn/admin/biens?statut=expire',
                    ],
                ];
                ?>

                <div class="mt-7 space-y-6">
                    <?php foreach ($lignesStatuts as $ligne): ?>
                        <a href="<?= $e($ligne['url']) ?>" class="group block">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <span class="text-sm font-semibold text-[#0b1528]">
                                    <?= $e($ligne['nom']) ?>
                                </span>
                                <span class="text-sm font-black <?= $ligne['texte'] ?>">
                                    <?= number_format($ligne['nombre'], 0, ',', ' ') ?>
                                    <span class="font-medium text-[#64748b]">
                                        (<?= $pourcentage($ligne['nombre'], $totalBiens) ?> %)
                                    </span>
                                </span>
                            </div>

                            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full <?= $ligne['couleur'] ?> transition-all duration-500 group-hover:opacity-80"
                                     style="width: <?= $pourcentage($ligne['nombre'], $totalBiens) ?>%">
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <p class="mt-6 text-xs leading-5 text-[#64748b]">
                    Les pourcentages sont calculés sur le total des annonces.
                    Les catégories peuvent se recouper si une annonce expirée conserve
                    un statut de publication.
                </p>
            </div>

            <!-- CENTRE DE SUIVI -->
            <div class="rounded-[24px] border border-[#e2e8f0] bg-[#071b36] p-5 text-white shadow-[0_12px_35px_rgba(7,27,54,0.12)] sm:p-7">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#f5b800]/15 text-[#f5b800]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-xl font-black text-white">Centre de suivi</h2>
                        <p class="text-sm text-slate-300">Vos prochaines actions</p>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <a href="/immo-sn/admin/biens?statut=en_attente"
                       class="flex items-center gap-3 rounded-2xl border border-white/10 bg-[#0e2a4e]/75 p-4 transition hover:bg-[#0e2a4e]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#f5b800]/15 text-[#f5b800]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold text-white">Examiner les annonces</span>
                            <span class="mt-1 block text-xs text-slate-300">Contrôler les nouvelles publications</span>
                        </span>
                        <span class="rounded-full bg-[#f5b800] px-2.5 py-1 text-sm font-black text-[#0b1528]">
                            <?= number_format($biensEnAttente, 0, ',', ' ') ?>
                        </span>
                    </a>

                    <a href="/immo-sn/admin/biens?statut=rejete"
                       class="flex items-center gap-3 rounded-2xl border border-white/10 bg-[#0e2a4e]/75 p-4 transition hover:bg-[#0e2a4e]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-500/20 text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 8l8 8M16 8l-8 8"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold text-white">Consulter les biens rejetés</span>
                            <span class="mt-1 block text-xs text-slate-300">Vérifier les annonces refusées</span>
                        </span>
                        <span class="rounded-full bg-red-500/20 px-2.5 py-1 text-sm font-black text-red-200">
                            <?= number_format($biensRejetes, 0, ',', ' ') ?>
                        </span>
                    </a>

                    <a href="/immo-sn/admin/biens?statut=expire"
                       class="flex items-center gap-3 rounded-2xl border border-white/10 bg-[#0e2a4e]/75 p-4 transition hover:bg-[#0e2a4e]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500/20 text-orange-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 10h18"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold text-white">Vérifier les expirations</span>
                            <span class="mt-1 block text-xs text-slate-300">Suivre les annonces arrivées à échéance</span>
                        </span>
                        <span class="rounded-full bg-orange-500/20 px-2.5 py-1 text-sm font-black text-orange-200">
                            <?= number_format($biensExpires, 0, ',', ' ') ?>
                        </span>
                    </a>

                    <a href="/immo-sn/admin/signalements"
                       class="flex items-center gap-3 rounded-2xl border border-white/10 bg-[#0e2a4e]/75 p-4 transition hover:bg-[#0e2a4e]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#f5b800]/15 text-[#f5b800]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3m0 4h.01M5 20h14a2 2 0 001.7-3.05l-7-12a2 2 0 00-3.4 0l-7 12A2 2 0 005 20z"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold text-white">Traiter les signalements</span>
                            <span class="mt-1 block text-xs text-slate-300">Consulter les alertes des utilisateurs</span>
                        </span>
                        <span class="rounded-full bg-[#f5b800]/20 px-2.5 py-1 text-sm font-black text-[#f5b800]">
                            <?= number_format($signalementsEnAttente, 0, ',', ' ') ?>
                        </span>
                    </a>
                </div>
            </div>
        </section>

        <!-- ACTIVITÉS RÉCENTES ET OUTILS -->
        <section class="grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">

            <!-- ACTIVITÉS RÉCENTES -->
            <div class="overflow-hidden rounded-[24px] border border-[#e2e8f0] bg-white shadow-[0_5px_24px_rgba(7,27,54,0.035)]">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#e2e8f0] px-5 py-5 sm:px-7">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-[#0e2a4e]">
                            Journal de la plateforme
                        </p>
                        <h2 class="mt-1 text-xl font-black text-[#071b36]">Activités récentes</h2>
                        <p class="mt-1 text-sm text-[#64748b]">
                            Les dernières opérations enregistrées.
                        </p>
                    </div>

                    <span class="rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-3 py-1.5 text-xs font-bold text-[#071b36]">
                        <?= count($activitesRecentes) ?> activité(s)
                    </span>
                </div>

                <?php if ($activitesRecentes): ?>
                    <div class="divide-y divide-[#e2e8f0]">
                        <?php foreach ($activitesRecentes as $activite): ?>
                            <?php
                            $type = strtolower(trim((string) ($activite['type'] ?? '')));
                            $description = trim((string) ($activite['description'] ?? ''));
                            $description = $description !== ''
                                ? $description
                                : 'Activité sans description';

                            $typeConfig = [
                                'bien' => [
                                    'titre' => 'Annonce immobilière',
                                    'etiquette' => 'Bien',
                                    'fond' => 'bg-amber-50',
                                    'texte' => 'text-amber-700',
                                ],
                                'utilisateur' => [
                                    'titre' => 'Nouveau compte',
                                    'etiquette' => 'Utilisateur',
                                    'fond' => 'bg-sky-50',
                                    'texte' => 'text-sky-700',
                                ],
                                'agence' => [
                                    'titre' => 'Agence partenaire',
                                    'etiquette' => 'Agence',
                                    'fond' => 'bg-indigo-50',
                                    'texte' => 'text-indigo-700',
                                ],
                                'signalement' => [
                                    'titre' => 'Signalement',
                                    'etiquette' => 'Alerte',
                                    'fond' => 'bg-red-50',
                                    'texte' => 'text-red-700',
                                ],
                            ];

                            $config = $typeConfig[$type] ?? [
                                'titre' => 'Activité système',
                                'etiquette' => 'Journal',
                                'fond' => 'bg-[#071b36]/10',
                                'texte' => 'text-[#071b36]',
                            ];

                            $dateActivite = $activite['created_at'] ?? $activite['date'] ?? null;
                            ?>

                            <div class="flex items-start justify-between gap-4 px-5 py-4 transition hover:bg-[#f8fafc] sm:px-7">
                                <div class="flex min-w-0 items-start gap-3.5">
                                    <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl <?= $config['fond'] ?> <?= $config['texte'] ?> text-xs font-black">
                                        <?= $e(mb_substr($config['etiquette'], 0, 1, 'UTF-8')) ?>
                                    </span>

                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="text-sm font-bold text-[#071b36]">
                                                <?= $e($config['titre']) ?>
                                            </p>
                                            <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold <?= $config['fond'] ?> <?= $config['texte'] ?>">
                                                <?= $e($config['etiquette']) ?>
                                            </span>
                                        </div>

                                        <p class="mt-1 break-words text-xs leading-5 text-[#64748b]">
                                            <?= $e($description) ?>
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 text-xs font-medium text-[#64748b]">
                                    <?= $e($tempsEcoule($dateActivite)) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-14 text-center">
                        <p class="text-sm font-bold text-[#071b36]">Aucune activité récente</p>
                        <p class="mt-1 text-xs text-[#64748b]">
                            Les prochaines opérations effectuées sur la plateforme apparaîtront ici.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- OUTILS D'ADMINISTRATION -->
            <div class="rounded-[24px] border border-[#e2e8f0] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] sm:p-7">
                <p class="text-xs font-black uppercase tracking-[0.16em] text-[#0e2a4e]">
                    Raccourcis
                </p>
                <h2 class="mt-1 text-xl font-black text-[#071b36]">Outils d’administration</h2>
                <p class="mt-1 text-sm text-[#64748b]">
                    Accès direct aux modules de gestion et de supervision.
                </p>

                <div class="mt-6 space-y-3">
                    <a href="/immo-sn/admin/biens"
                       class="flex items-center justify-between gap-3 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4 transition hover:border-[#0e2a4e]/40 hover:bg-white">
                        <div>
                            <p class="text-sm font-bold text-[#071b36]">Gestion des biens</p>
                            <p class="text-xs text-[#64748b]">Valider, rejeter ou filtrer les annonces</p>
                        </div>
                        <span class="font-bold text-[#0e2a4e]">→</span>
                    </a>

                    <a href="/immo-sn/admin/agences"
                       class="flex items-center justify-between gap-3 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4 transition hover:border-[#0e2a4e]/40 hover:bg-white">
                        <div>
                            <p class="text-sm font-bold text-[#071b36]">Gestion des agences</p>
                            <p class="text-xs text-[#64748b]">Approuver ou suspendre les partenaires</p>
                        </div>
                        <span class="font-bold text-[#0e2a4e]">→</span>
                    </a>

                    <a href="/immo-sn/admin/signalements"
                       class="flex items-center justify-between gap-3 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4 transition hover:border-[#0e2a4e]/40 hover:bg-white">
                        <div>
                            <p class="text-sm font-bold text-[#071b36]">Signalements</p>
                            <p class="text-xs text-[#64748b]">Examiner les alertes signalées</p>
                        </div>
                        <span class="font-bold text-[#0e2a4e]">→</span>
                    </a>

                    <a href="/immo-sn/admin/cron-monitor"
                       class="flex items-center justify-between gap-3 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4 transition hover:border-[#0e2a4e]/40 hover:bg-white">
                        <div>
                            <p class="text-sm font-bold text-[#071b36]">Cron Monitor</p>
                            <p class="text-xs text-[#64748b]">Superviser les tâches planifiées</p>
                        </div>
                        <span class="font-bold text-[#0e2a4e]">→</span>
                    </a>

                    <a href="/immo-sn/admin/mail-logs"
                       class="flex items-center justify-between gap-3 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4 transition hover:border-[#0e2a4e]/40 hover:bg-white">
                        <div>
                            <p class="text-sm font-bold text-[#071b36]">Mail Logs</p>
                            <p class="text-xs text-[#64748b]">Vérifier l’historique des e-mails</p>
                        </div>
                        <span class="font-bold text-[#0e2a4e]">→</span>
                    </a>
                </div>
            </div>

        </section>

    </main>
</div>