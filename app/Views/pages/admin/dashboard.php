
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
        'couleur' => 'brown',
        'url' => null,
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
        'description' => 'Agences enregistrées ',
        'icone' => 'agences',
        'couleur' => 'violet',
        'url' => null,
    ],
    [
        'titre' => 'Signalements',
        'valeur' => $totalSignalements,
        'description' => 'Signalements reçus',
        'icone' => 'flag',
        'couleur' => 'red',
        'url' => null,
    ],
];


?>

<div class="min-h-screen bg-[#faf7f2] text-[#30221b]">

    <!-- EN-TÊTE -->
    <header class="sticky top-0 z-30 border-b border-[#e9dfd5] bg-[#faf7f2]/95 backdrop-blur-xl">
        <div class="mx-auto flex max-w-[1600px] items-center justify-between gap-4 px-5 py-4 sm:px-8">

            <a href="/immo-sn/admin" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#30221b] text-[#f7b500] shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10.5L12 3l9 7.5M5.25 9v10.75h13.5V9M9 21v-6h6v6"/>
                    </svg>
                </span>

                <span>
                    <span class="block text-lg font-black tracking-tight">IMMO-SN</span>
                    <span class="block text-xs text-[#8d7a6b]">Administration</span>
                </span>
            </a>

            <div class="flex items-center gap-3">
                <span class="hidden text-sm font-medium text-[#8d7a6b] sm:block">
                    <?= $e($dateDuJour) ?>
                </span>

                <a href="/immo-sn/admin/signalements"
                   class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-[#e8ded4] bg-white transition hover:bg-[#f1e7df]"
                   aria-label="Ouvrir les signalements"
                   title="Signalements">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12h4"/>
                    </svg>

                    <?php if ($signalementsEnAttente > 0): ?>
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#f7b500] px-1 text-[10px] font-black">
                            <?= $signalementsEnAttente > 99 ? '99+' : $signalementsEnAttente ?>
                        </span>
                    <?php endif; ?>
                </a>

                <div class="hidden items-center gap-2 rounded-xl border border-[#e8ded4] bg-white px-3 py-2 sm:flex">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#f1e7df] text-[#5a402d]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0"/>
                        </svg>
                    </span>
                    <span class="text-sm font-bold">Administrateur</span>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-[1600px] space-y-8 px-5 py-7 sm:px-8 lg:py-10">

        <!-- ACCUEIL PREMIUM -->
        <section class="relative overflow-hidden rounded-[28px] bg-[#30221b] shadow-[0_18px_50px_rgba(48,34,27,0.12)]">
            <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 right-1/3 h-64 w-64 rounded-full bg-[#a98260]/15 blur-3xl"></div>

            <div class="relative grid gap-8 p-6 sm:p-9 lg:grid-cols-[1fr_300px] lg:items-center lg:p-11">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-[#e6d8ca]">
                        <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>
                        CENTRE DE PILOTAGE IMMO-SN
                    </div>

                    <h1 class="mt-5 max-w-3xl text-3xl font-black leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                        Votre plateforme,
                        <span class="text-[#f7b500]">en un coup d’œil.</span>
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-7 text-[#d7cbc2] sm:text-base">
                        Suivez vos annonces immobilières, contrôlez leur publication
                        et gérez les activités de votre plateforme depuis un espace
                        centralisé.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="/immo-sn/admin/biens"
                           class="inline-flex items-center gap-2 rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-extrabold text-[#30221b] transition hover:-translate-y-0.5 hover:bg-[#ffc21a]">
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

                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-5 sm:p-6">
                    <p class="text-xs font-bold uppercase tracking-[0.15em] text-[#cbbcad]">
                        Votre priorité
                    </p>

                    <p class="mt-4 text-5xl font-black tracking-tight text-white">
                        <?= number_format($totalPriorites, 0, ',', ' ') ?>
                    </p>

                    <p class="mt-2 text-sm leading-6 text-[#d7cbc2]">
                        éléments en attente de traitement
                    </p>

                    <div class="mt-5 space-y-3">
                        <a href="/immo-sn/admin/biens?statut=en_attente"
                           class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-white/5 px-3 py-3 transition hover:bg-white/10">
                            <span class="text-sm text-white">Annonces à examiner</span>
                            <span class="font-black text-[#f7b500]">
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
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-[#a98260]">
                        Vue d'ensemble
                    </p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight sm:text-3xl">
                        Les chiffres essentiels
                    </h2>
                    <p class="mt-2 text-sm text-[#8d7a6b]">
                        Indicateurs issus des données enregistrées dans la plateforme.
                    </p>
                </div>

                <span class="w-fit rounded-full border border-[#e8ded4] bg-white px-3 py-2 text-xs font-semibold text-[#8d7a6b]">
                    Mise à jour au chargement
                </span>
            </div>

           <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <?php foreach ($cartes as $carte): ?>
                    <?php
                    $couleurs = [
                        'brown' => [
                            'fond' => 'bg-[#f1e7df]',
                            'texte' => 'text-[#5a402d]',
                            'barre' => 'bg-[#5a402d]',
                        ],
                        'green' => [
                            'fond' => 'bg-emerald-50',
                            'texte' => 'text-emerald-700',
                            'barre' => 'bg-emerald-600',
                        ],
                        'gold' => [
                            'fond' => 'bg-amber-50',
                            'texte' => 'text-amber-700',
                            'barre' => 'bg-amber-500',
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
                    ];

                    $couleur = $couleurs[$carte['couleur']] ?? $couleurs['brown'];
                    $conteneur = $carte['url'] ? 'a' : 'div';
                    ?>

                    <<?= $conteneur ?>
                        <?php if ($carte['url']): ?>
                            href="<?= $e($carte['url']) ?>"
                        <?php endif; ?>
                        class="group relative overflow-hidden rounded-[22px] border border-[#e8ded4] bg-white p-5 shadow-[0_5px_24px_rgba(48,34,27,0.035)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_14px_34px_rgba(48,34,27,0.08)] sm:p-6">

                        <div class="flex items-start justify-between gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl <?= $couleur['fond'] ?> <?= $couleur['texte'] ?>">
                                <?php if ($carte['icone'] === 'building-2'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V5l7-3v19m7 0V9l-7-4M9 8v.01M9 12v.01M9 16v.01M15 12v.01M15 16v.01"/>
                                    </svg>
                                <?php elseif ($carte['icone'] === 'check-circle'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                <?php elseif ($carte['icone'] === 'clock'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                    </svg>
                                <?php elseif ($carte['icone'] === 'ban'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12"/>
                                    </svg>
                                <?php elseif ($carte['icone'] === 'calendar-clock'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 10h18m-9 3v3l2 1"/>
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
                                <span class="text-lg text-[#c5b5a7] transition group-hover:translate-x-1 group-hover:text-[#5a402d]">↗</span>
                            <?php endif; ?>
                        </div>

                        <p class="mt-6 text-sm font-semibold text-[#8d7a6b]">
                            <?= $e($carte['titre']) ?>
                        </p>

                        <p class="mt-1 text-4xl font-black tracking-tight text-[#30221b]">
                            <?= number_format($carte['valeur'], 0, ',', ' ') ?>
                        </p>

                        <p class="mt-2 text-xs leading-5 text-[#9a887a]">
                            <?= $e($carte['description']) ?>
                        </p>

                        <div class="mt-5 h-1 overflow-hidden rounded-full bg-[#f1e9e2]">
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

            <div class="rounded-[24px] border border-[#e8ded4] bg-white p-5 shadow-[0_5px_24px_rgba(48,34,27,0.035)] sm:p-7">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-[#a98260]">
                            Modération
                        </p>
                        <h2 class="mt-1 text-xl font-black">État des annonces</h2>
                        <p class="mt-2 text-sm text-[#8d7a6b]">
                            Une vue rapide des annonces selon leur état.
                        </p>
                    </div>

                    <a href="/immo-sn/admin/biens"
                       class="shrink-0 text-sm font-bold text-[#5a402d] hover:underline">
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
                        'couleur' => 'bg-amber-500',
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
                                <span class="text-sm font-semibold text-[#5a402d]">
                                    <?= $e($ligne['nom']) ?>
                                </span>
                                <span class="text-sm font-black <?= $ligne['texte'] ?>">
                                    <?= number_format($ligne['nombre'], 0, ',', ' ') ?>
                                    <span class="font-medium text-[#9a887a]">
                                        (<?= $pourcentage($ligne['nombre'], $totalBiens) ?> %)
                                    </span>
                                </span>
                            </div>

                            <div class="h-2.5 overflow-hidden rounded-full bg-[#f1e9e2]">
                                <div class="h-full rounded-full <?= $ligne['couleur'] ?> transition-all duration-500 group-hover:opacity-80"
                                     style="width: <?= $pourcentage($ligne['nombre'], $totalBiens) ?>%">
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <p class="mt-6 text-xs leading-5 text-[#9a887a]">
                    Les pourcentages sont calculés sur le total des annonces.
                    Les catégories peuvent se recouper si une annonce expirée conserve
                    un statut de publication.
                </p>
            </div>

            <!-- CENTRE DE SUIVI -->
            <div class="rounded-[24px] border border-[#e8ded4] bg-[#f1e7df] p-5 sm:p-7">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-[#5a402d]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-xl font-black">Centre de suivi</h2>
                        <p class="text-sm text-[#8d7a6b]">Vos prochaines actions</p>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <a href="/immo-sn/admin/biens?statut=en_attente"
                       class="flex items-center gap-3 rounded-2xl border border-white bg-white/80 p-4 transition hover:bg-white">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold">Examiner les annonces</span>
                            <span class="mt-1 block text-xs text-[#8d7a6b]">Contrôler les nouvelles publications</span>
                        </span>
                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-sm font-black text-amber-800">
                            <?= number_format($biensEnAttente, 0, ',', ' ') ?>
                        </span>
                    </a>

                    <a href="/immo-sn/admin/biens?statut=rejete"
                       class="flex items-center gap-3 rounded-2xl border border-white bg-white/80 p-4 transition hover:bg-white">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 8l8 8M16 8l-8 8"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold">Consulter les biens rejetés</span>
                            <span class="mt-1 block text-xs text-[#8d7a6b]">Vérifier les annonces refusées</span>
                        </span>
                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-sm font-black text-red-800">
                            <?= number_format($biensRejetes, 0, ',', ' ') ?>
                        </span>
                    </a>

                    <a href="/immo-sn/admin/biens?statut=expire"
                       class="flex items-center gap-3 rounded-2xl border border-white bg-white/80 p-4 transition hover:bg-white">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 10h18"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold">Vérifier les expirations</span>
                            <span class="mt-1 block text-xs text-[#8d7a6b]">Suivre les annonces arrivées à échéance</span>
                        </span>
                        <span class="rounded-full bg-orange-100 px-2.5 py-1 text-sm font-black text-orange-800">
                            <?= number_format($biensExpires, 0, ',', ' ') ?>
                        </span>
                    </a>

                    <a href="/immo-sn/admin/signalements"
                       class="flex items-center gap-3 rounded-2xl border border-white bg-white/80 p-4 transition hover:bg-white">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#f7b500]/20 text-[#5a402d]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3m0 4h.01M5 20h14a2 2 0 001.7-3.05l-7-12a2 2 0 00-3.4 0l-7 12A2 2 0 005 20z"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold">Traiter les signalements</span>
                            <span class="mt-1 block text-xs text-[#8d7a6b]">Consulter les alertes des utilisateurs</span>
                        </span>
                        <span class="rounded-full bg-[#f7b500]/25 px-2.5 py-1 text-sm font-black text-[#5a402d]">
                            <?= number_format($signalementsEnAttente, 0, ',', ' ') ?>
                        </span>
                    </a>
                </div>
            </div>
        </section>

        <!-- ACTIVITÉS RÉCENTES ET OUTILS -->
        <section class="grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">

            <!-- ACTIVITÉS RÉCENTES -->
            <div class="overflow-hidden rounded-[24px] border border-[#e8ded4] bg-white shadow-[0_5px_24px_rgba(48,34,27,0.035)]">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#f0e8e1] px-5 py-5 sm:px-7">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-[#a98260]">
                            Journal de la plateforme
                        </p>
                        <h2 class="mt-1 text-xl font-black">Activités récentes</h2>
                        <p class="mt-1 text-sm text-[#8d7a6b]">
                            Les dernières opérations enregistrées.
                        </p>
                    </div>

                    <span class="rounded-full bg-[#f1e7df] px-3 py-1.5 text-xs font-bold text-[#5a402d]">
                        <?= count($activitesRecentes) ?> activité(s)
                    </span>
                </div>

                <?php if ($activitesRecentes): ?>
                    <div class="divide-y divide-[#f3ede7]">
                        <?php foreach ($activitesRecentes as $activite): ?>
                            <?php
                            $type = (string) ($activite['type'] ?? '');
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
                                    'titre' => 'Nouvelle inscription',
                                    'etiquette' => 'Utilisateur',
                                    'fond' => 'bg-sky-50',
                                    'texte' => 'text-sky-700',
                                ],
                                'signalement' => [
                                    'titre' => 'Signalement reçu',
                                    'etiquette' => 'Signalement',
                                    'fond' => 'bg-red-50',
                                    'texte' => 'text-red-700',
                                ],
                            ];

                            $config = $typeConfig[$type] ?? [
                                'titre' => 'Activité de la plateforme',
                                'etiquette' => 'Activité',
                                'fond' => 'bg-[#f1e7df]',
                                'texte' => 'text-[#5a402d]',
                            ];
                            ?>

                            <div class="flex items-start gap-4 px-5 py-5 transition hover:bg-[#fdfbf8] sm:px-7">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl <?= $config['fond'] ?> <?= $config['texte'] ?>">
                                    <?php if ($type === 'utilisateur'): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87m-1-11.13a4 4 0 010 7.75"/>
                                        </svg>
                                    <?php elseif ($type === 'signalement'): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M5 20h14a2 2 0 001.7-3.05l-7-12a2 2 0 00-3.4 0l-7 12A2 2 0 005 20z"/>
                                        </svg>
                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5M5.25 9v11.25h13.5V9M9 20.25v-6h6v6"/>
                                        </svg>
                                    <?php endif; ?>
                                </span>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-sm font-extrabold">
                                            <?= $e($config['titre']) ?>
                                        </h3>
                                        <span class="rounded-full bg-[#faf7f2] px-2.5 py-1 text-[10px] font-bold text-[#8d7a6b]">
                                            <?= $e($config['etiquette']) ?>
                                        </span>
                                    </div>

                                    <p class="mt-1 break-words text-sm leading-6 text-[#6f5d50]">
                                        <?= $e($description) ?>
                                    </p>

                                    <p class="mt-2 text-xs font-medium text-[#a18e7e]">
                                        <?= $e($tempsEcoule($activite['created_at'] ?? null)) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-14 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f1e7df] text-[#5a402d]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8m-8 5h8m-8 5h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            </svg>
                        </span>
                        <h3 class="mt-4 font-extrabold">Aucune activité récente</h3>
                        <p class="mt-2 text-sm text-[#8d7a6b]">
                            Les nouvelles annonces, inscriptions et alertes apparaîtront ici.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- OUTILS D'ADMINISTRATION -->
            <aside class="rounded-[24px] border border-[#e8ded4] bg-white p-5 shadow-[0_5px_24px_rgba(48,34,27,0.035)] sm:p-7">
                <p class="text-xs font-black uppercase tracking-[0.16em] text-[#a98260]">
                    Administration
                </p>
                <h2 class="mt-1 text-xl font-black">Outils de gestion</h2>
                <p class="mt-2 text-sm leading-6 text-[#8d7a6b]">
                    Accédez aux principaux espaces techniques de votre plateforme.
                </p>

                <div class="mt-5 space-y-3">
                    <a href="/immo-sn/admin/biens"
                       class="group flex items-center gap-3 rounded-2xl border border-[#eee5dc] p-3 transition hover:border-[#d9c5b3] hover:bg-[#fdfaf7]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#30221b] text-[#f7b500]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5M5.25 9v11.25h13.5V9M9 20.25v-6h6v6"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-extrabold">Gestion des biens</span>
                            <span class="mt-1 block text-xs text-[#8d7a6b]">Consulter et filtrer les annonces</span>
                        </span>
                        <span class="text-[#a98260] transition group-hover:translate-x-1">→</span>
                    </a>

                    <a href="/immo-sn/admin/signalements"
                       class="group flex items-center gap-3 rounded-2xl border border-[#eee5dc] p-3 transition hover:border-[#d9c5b3] hover:bg-[#fdfaf7]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M5 20h14a2 2 0 001.7-3.05l-7-12a2 2 0 00-3.4 0l-7 12A2 2 0 005 20z"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-extrabold">Signalements</span>
                            <span class="mt-1 block text-xs text-[#8d7a6b]">Suivre les alertes des utilisateurs</span>
                        </span>
                        <span class="text-[#a98260] transition group-hover:translate-x-1">→</span>
                    </a>

                    <a href="/immo-sn/admin/cron-monitor"
                       class="group flex items-center gap-3 rounded-2xl border border-[#eee5dc] p-3 transition hover:border-[#d9c5b3] hover:bg-[#fdfaf7]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#f1e7df] text-[#5a402d]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-extrabold">Cron Monitor</span>
                            <span class="mt-1 block text-xs text-[#8d7a6b]">Surveiller les tâches automatiques</span>
                        </span>
                        <span class="text-[#a98260] transition group-hover:translate-x-1">→</span>
                    </a>

                    <a href="/immo-sn/admin/mail-logs"
                       class="group flex items-center gap-3 rounded-2xl border border-[#eee5dc] p-3 transition hover:border-[#d9c5b3] hover:bg-[#fdfaf7]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-extrabold">Journaux des e-mails</span>
                            <span class="mt-1 block text-xs text-[#8d7a6b]">Consulter les journaux d'envoi</span>
                        </span>
                        <span class="text-[#a98260] transition group-hover:translate-x-1">→</span>
                    </a>
                </div>

                <div class="mt-6 rounded-2xl bg-[#30221b] p-4 text-white">
                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-[#f7b500]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                            </svg>
                        </span>
                        <span class="font-extrabold">Gestion centralisée</span>
                    </div>
                    <p class="mt-3 text-xs leading-6 text-[#d7cbc2]">
                        Vérifiez régulièrement les annonces et les signalements
                        afin de maintenir la qualité et la fiabilité des contenus
                        publiés sur IMMO-SN.
                    </p>
                </div>
            </aside>
        </section>
</main>
</div>
