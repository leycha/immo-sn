
<?php

/**
 * =========================================================
 * DASHBOARD MODÉRATEUR — IMMO-SN
 * Design : Bleu nuit / Or / Ivoire
 * =========================================================
 */

$stats = $stats ?? [
    'annonces_en_attente' => 0,
    'signalements_en_attente' => 0,
    'annonces_traitees' => 0,
    'signalements_traites' => 0,
];

$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$annoncesEnAttente = (int) ($stats['annonces_en_attente'] ?? 0);
$signalementsEnAttente = (int) ($stats['signalements_en_attente'] ?? 0);
$annoncesTraitees = (int) ($stats['annonces_traitees'] ?? 0);
$signalementsTraites = (int) ($stats['signalements_traites'] ?? 0);

$totalPriorites = $annoncesEnAttente + $signalementsEnAttente;
$dateDuJour = date('d/m/Y');

$cartes = [
    [
        'titre' => 'Annonces en attente',
        'valeur' => $annoncesEnAttente,
        'description' => 'Annonces à examiner',
        'couleur' => 'gold',
        'icone' => 'home',
        'lien' => '/immo-sn/moderateur/annonces',
        'action' => 'Examiner',
    ],
    [
        'titre' => 'Signalements en attente',
        'valeur' => $signalementsEnAttente,
        'description' => 'Signalements à vérifier',
        'couleur' => 'red',
        'icone' => 'flag',
        'lien' => '/immo-sn/moderateur/signalements',
        'action' => 'Consulter',
    ],
    [
        'titre' => 'Annonces traitées',
        'valeur' => $annoncesTraitees,
        'description' => 'Annonces déjà examinées',
        'couleur' => 'green',
        'icone' => 'check',
        'lien' => '/immo-sn/moderateur/historique',
        'action' => 'Historique',
    ],
    [
        'titre' => 'Signalements traités',
        'valeur' => $signalementsTraites,
        'description' => 'Signalements déjà examinés',
        'couleur' => 'blue',
        'icone' => 'shield',
        'lien' => '/immo-sn/moderateur/historique',
        'action' => 'Historique',
    ],
];

?>

<div class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

    <main class="mx-auto max-w-[1600px] space-y-9 px-4 py-6 sm:px-8 sm:py-8 lg:px-10 lg:py-10">

        <!-- =====================================================
             BARRE SUPÉRIEURE
        ====================================================== -->

        <header class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-[#71869A]">
                    <span>IMMO-SN</span>
                    <span>/</span>
                    <span>Administration</span>
                    <span>/</span>
                    <span class="font-bold text-[#A98200]">Modération</span>
                </div>

                <h2 class="mt-2 text-xl font-black tracking-tight text-[#071B36] sm:text-2xl">
                    Espace de modération
                </h2>

                <p class="mt-1 text-sm text-[#71869A]">
                    Gérez les annonces et les signalements de la plateforme.
                </p>

            </div>

            <div class="flex w-full flex-wrap items-center justify-between gap-3 sm:w-auto sm:justify-end">

                <span class="hidden text-sm font-medium text-[#71869A] sm:block">
                    <?= $e($dateDuJour) ?>
                </span>

                <!-- Notifications -->

                <a href="/immo-sn/moderateur/signalements"
                   class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-[#E8E1D3] bg-white text-[#071B36] shadow-sm transition duration-200 hover:border-[#D7A200] hover:bg-[#FFFAEB] hover:text-[#A98200]"
                   aria-label="Consulter les signalements"
                   title="Signalements">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12h4"/>

                    </svg>

                    <?php if ($signalementsEnAttente > 0): ?>

                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-black text-white shadow-sm">
                            <?= $signalementsEnAttente > 99 ? '99+' : $signalementsEnAttente ?>
                        </span>

                    <?php endif; ?>

                </a>

                <!-- Profil modérateur -->

                <div class="flex items-center gap-3 rounded-xl border border-[#E8E1D3] bg-white px-3 py-2 shadow-sm">

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#071B36] text-sm font-black text-[#F5B800]">
                        M
                    </span>

                    <span class="hidden sm:block">

                        <span class="block text-sm font-bold text-[#071B36]">
                            Modérateur
                        </span>

                        <span class="block text-xs text-[#71869A]">
                            Espace de modération
                        </span>

                    </span>

                </div>

            </div>

        </header>


      <!-- =====================================================
     BANNIÈRE D'ACCUEIL — VERSION COMPACTE
====================================================== -->

<section class="relative overflow-hidden rounded-2xl bg-[#071B36] shadow-sm">

    <div class="relative grid min-w-0 gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_270px] lg:items-center">

        <!-- Présentation -->

        <div class="min-w-0">

            <div class="inline-flex items-center gap-2 rounded-full border border-[#F5B800]/20 bg-[#F5B800]/10 px-3 py-1.5 text-[10px] font-bold tracking-wide text-[#F8D76A]">

                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>

                CENTRE DE MODÉRATION IMMO-SN

            </div>

            <h1 class="mt-3 max-w-3xl break-words text-xl font-black leading-tight tracking-tight text-white sm:text-2xl lg:text-3xl">

                Votre espace,
                <span class="text-[#F5B800]">en un coup d’œil.</span>

            </h1>

            <p class="mt-2 max-w-2xl text-xs leading-6 text-[#C3CED9] sm:text-sm">

                Suivez les publications immobilières, examinez les signalements
                et veillez à la qualité des contenus diffusés sur la plateforme
                IMMO-SN.

            </p>

            <!-- Boutons -->

            <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:flex-wrap">

                <a href="/immo-sn/moderateur/annonces"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#F5B800] px-4 py-2.5 text-xs font-extrabold text-[#071B36] transition hover:bg-[#FFD044] sm:w-auto">

                    Examiner les annonces

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 12h14m-6-6 6 6-6 6"/>

                    </svg>

                </a>

                <a href="/immo-sn/moderateur/signalements"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-white/15 bg-white/[0.06] px-4 py-2.5 text-xs font-bold text-white transition hover:border-[#F5B800]/40 hover:bg-white/10 sm:w-auto">

                    Consulter les signalements

                    <span aria-hidden="true">→</span>

                </a>

            </div>

        </div>


        <!-- PRIORITÉS -->

        <div class="rounded-xl border border-white/10 bg-white/[0.06] p-4">

            <div class="flex items-center justify-between gap-3">

                <p class="text-[10px] font-bold uppercase tracking-wider text-[#C3CED9]">
                    Vos priorités
                </p>

                <span class="rounded-md border border-[#F5B800]/20 bg-[#F5B800]/10 px-2 py-1 text-[9px] font-bold uppercase text-[#F8D76A]">
                    À traiter
                </span>

            </div>

            <p class="mt-2 text-3xl font-black tracking-tight text-white tabular-nums">
                <?= number_format($totalPriorites, 0, ',', ' ') ?>
            </p>

            <p class="mt-1 text-xs text-[#C3CED9]">
                éléments en attente de traitement
            </p>

            <div class="mt-3 space-y-2">

                <a href="/immo-sn/moderateur/annonces"
                   class="flex items-center justify-between gap-2 rounded-lg border border-white/[0.07] bg-white/[0.05] px-3 py-2.5 transition hover:bg-white/10">

                    <span class="flex items-center gap-2 text-xs text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                        Annonces à examiner
                    </span>

                    <span class="text-sm font-black text-[#F5B800]">
                        <?= number_format($annoncesEnAttente, 0, ',', ' ') ?>
                    </span>

                </a>

                <a href="/immo-sn/moderateur/signalements"
                   class="flex items-center justify-between gap-2 rounded-lg border border-white/[0.07] bg-white/[0.05] px-3 py-2.5 transition hover:bg-white/10">

                    <span class="flex items-center gap-2 text-xs text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                        Signalements à vérifier
                    </span>

                    <span class="text-sm font-black text-[#FCA5A5]">
                        <?= number_format($signalementsEnAttente, 0, ',', ' ') ?>
                    </span>

                </a>

            </div>

        </div>

    </div>

</section>

        <!-- =====================================================
             INDICATEURS
        ====================================================== -->

        <section>

            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="text-xs font-black uppercase tracking-[0.18em] text-[#A98200]">
                        Vue d'ensemble
                    </p>

                    <h2 class="mt-1 text-2xl font-black tracking-tight text-[#071B36] sm:text-3xl">
                        Les chiffres essentiels
                    </h2>

                    <p class="mt-2 text-sm text-[#71869A]">
                        Suivi de votre activité de modération.
                    </p>

                </div>

                <span class="w-fit rounded-full border border-[#E8E1D3] bg-white px-3 py-2 text-xs font-semibold text-[#71869A] shadow-sm">

                    <span class="mr-1.5 inline-block h-1.5 w-1.5 rounded-full bg-[#D7A200]"></span>

                    Mise à jour au chargement

                </span>

            </div>


            <!-- CARTES STATISTIQUES -->

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <?php foreach ($cartes as $carte): ?>

                    <?php

                    $couleurs = [
                        'gold' => [
                            'fond' => 'bg-[#FFF7DF]',
                            'texte' => 'text-[#8A6500]',
                            'barre' => 'bg-[#F5B800]',
                        ],
                        'red' => [
                            'fond' => 'bg-red-50',
                            'texte' => 'text-red-700',
                            'barre' => 'bg-red-500',
                        ],
                        'green' => [
                            'fond' => 'bg-emerald-50',
                            'texte' => 'text-emerald-700',
                            'barre' => 'bg-emerald-600',
                        ],
                        'blue' => [
                            'fond' => 'bg-sky-50',
                            'texte' => 'text-sky-700',
                            'barre' => 'bg-sky-500',
                        ],
                    ];

                    $style = $couleurs[$carte['couleur']] ?? $couleurs['gold'];

                    ?>

                    <article class="group relative overflow-hidden rounded-[22px] border border-[#E8E1D3] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] transition duration-300 hover:-translate-y-1 hover:border-[#D7A200]/40 hover:shadow-[0_16px_38px_rgba(7,27,54,0.09)] sm:p-6">

                        <!-- Décoration -->

                        <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full <?= $style['fond'] ?> opacity-70 transition-transform duration-300 group-hover:scale-125"></div>

                        <div class="relative">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <p class="break-words text-sm font-semibold text-[#71869A]">
                                        <?= $e($carte['titre']) ?>
                                    </p>

                                    <p class="mt-5 text-4xl font-black tracking-tight text-[#071B36] tabular-nums">
                                        <?= number_format($carte['valeur'], 0, ',', ' ') ?>
                                    </p>

                                </div>

                                <!-- Icône -->

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl <?= $style['fond'] ?> <?= $style['texte'] ?>">

                                    <?php if ($carte['icone'] === 'home'): ?>

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M3 10.5 12 3l9 7.5M5 9v12h14V9M9 21v-7h6v7"/>

                                        </svg>

                                    <?php elseif ($carte['icone'] === 'flag'): ?>

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M4 22V4m0 0c4-3 8 3 16 0v12c-8 3-12-3-16 0"/>

                                        </svg>

                                    <?php elseif ($carte['icone'] === 'check'): ?>

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="m5 12 4 4L19 6"/>

                                            <circle cx="12" cy="12" r="10"/>

                                        </svg>

                                    <?php else: ?>

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="m9 12 2 2 4-4"/>

                                        </svg>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- Description et action -->

                            <div class="mt-5 flex flex-col items-start justify-between gap-3 border-t border-[#EEE9DE] pt-4 sm:flex-row sm:items-center">

                                <p class="text-xs text-[#71869A]">
                                    <?= $e($carte['description']) ?>
                                </p>

                                <a href="<?= $e($carte['lien']) ?>"
                                   class="inline-flex shrink-0 items-center gap-1 text-xs font-bold text-[#A98200] transition hover:text-[#071B36]">

                                    <?= $e($carte['action']) ?>

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-3.5 w-3.5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M5 12h14m-5-5 5 5-5 5"/>

                                    </svg>

                                </a>

                            </div>

                        </div>

                        <!-- Barre inférieure -->

                        <div class="absolute bottom-0 left-0 h-1 w-full <?= $style['barre'] ?> opacity-0 transition duration-300 group-hover:opacity-100"></div>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>


        <!-- =====================================================
             ACCÈS RAPIDES
        ====================================================== -->

        <section>

            <div class="mb-5">

                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#A98200]">
                    Navigation rapide
                </p>

                <h2 class="mt-1 text-2xl font-black tracking-tight text-[#071B36]">
                    Vos espaces de travail
                </h2>

                <p class="mt-2 text-sm text-[#71869A]">
                    Accédez directement aux différents outils de modération.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <!-- ANNONCES -->

                <a href="/immo-sn/moderateur/annonces"
                   class="group flex items-center gap-4 rounded-[22px] border border-[#E8E1D3] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] transition duration-300 hover:-translate-y-1 hover:border-[#D7A200]/50 hover:shadow-[0_14px_30px_rgba(7,27,54,0.08)]">

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#FFF7DF] text-[#8A6500] transition duration-200 group-hover:bg-[#F5B800] group-hover:text-[#071B36]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 10.5 12 3l9 7.5M5 9v12h14V9M9 21v-7h6v7"/>

                        </svg>

                    </span>

                    <span class="min-w-0 flex-1">

                        <span class="block font-extrabold text-[#071B36]">
                            Annonces
                        </span>

                        <span class="mt-1 block text-xs text-[#71869A]">
                            Vérifier les publications
                        </span>

                    </span>

                    <span class="text-lg text-[#A5B0B8] transition group-hover:translate-x-1 group-hover:text-[#A98200]">
                        →
                    </span>

                </a>


                <!-- SIGNALEMENTS -->

                <a href="/immo-sn/moderateur/signalements"
                   class="group flex items-center gap-4 rounded-[22px] border border-[#E8E1D3] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] transition duration-300 hover:-translate-y-1 hover:border-red-200 hover:shadow-[0_14px_30px_rgba(7,27,54,0.08)]">

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700 transition duration-200 group-hover:bg-red-600 group-hover:text-white">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 21V4m0 0c4-3 8 3 16 0v12c-8 3-12-3-16 0"/>

                        </svg>

                    </span>

                    <span class="min-w-0 flex-1">

                        <span class="block font-extrabold text-[#071B36]">
                            Signalements
                        </span>

                        <span class="mt-1 block text-xs text-[#71869A]">
                            Examiner les alertes
                        </span>

                    </span>

                    <span class="text-lg text-[#A5B0B8] transition group-hover:translate-x-1 group-hover:text-red-600">
                        →
                    </span>

                </a>


                <!-- RENOUVELLEMENTS -->

                <a href="/immo-sn/moderateur/renouvellements"
                   class="group flex items-center gap-4 rounded-[22px] border border-[#E8E1D3] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] transition duration-300 hover:-translate-y-1 hover:border-[#D7A200]/50 hover:shadow-[0_14px_30px_rgba(7,27,54,0.08)]">

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#EEF1F5] text-[#71869A] transition duration-200 group-hover:bg-[#071B36] group-hover:text-[#F5B800]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <circle cx="12" cy="12" r="9"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 7v5l3 2M21 3v6h-6"/>

                        </svg>

                    </span>

                    <span class="min-w-0 flex-1">

                        <span class="block font-extrabold text-[#071B36]">
                            Renouvellements
                        </span>

                        <span class="mt-1 block text-xs text-[#71869A]">
                            Traiter les demandes
                        </span>

                    </span>

                    <span class="text-lg text-[#A5B0B8] transition group-hover:translate-x-1 group-hover:text-[#A98200]">
                        →
                    </span>

                </a>


                <!-- HISTORIQUE -->

                <a href="/immo-sn/moderateur/historique"
                   class="group flex items-center gap-4 rounded-[22px] border border-[#E8E1D3] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] transition duration-300 hover:-translate-y-1 hover:border-[#D7A200]/50 hover:shadow-[0_14px_30px_rgba(7,27,54,0.08)]">

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#FFF7DF] text-[#8A6500] transition duration-200 group-hover:bg-[#F5B800] group-hover:text-[#071B36]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <circle cx="12" cy="12" r="9"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 7v5l3 2"/>

                        </svg>

                    </span>

                    <span class="min-w-0 flex-1">

                        <span class="block font-extrabold text-[#071B36]">
                            Historique
                        </span>

                        <span class="mt-1 block text-xs text-[#71869A]">
                            Consulter les décisions
                        </span>

                    </span>

                    <span class="text-lg text-[#A5B0B8] transition group-hover:translate-x-1 group-hover:text-[#A98200]">
                        →
                    </span>

                </a>

            </div>

        </section>


        <!-- =====================================================
             PIED DE PAGE
        ====================================================== -->

        <footer class="border-t border-[#E8E1D3] pt-5">

            <div class="flex flex-col items-center justify-between gap-2 text-center text-xs text-[#71869A] sm:flex-row sm:text-left">

                <p>
                    <span class="font-black tracking-wide text-[#071B36]">IMMO-SN</span>
                    <span class="mx-1">·</span>
                    Espace de modération
                </p>

                <p>
                    Administration et contrôle des contenus immobiliers
                </p>

            </div>

        </footer>

    </main>

</div>
