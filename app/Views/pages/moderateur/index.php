
<?php

/**
 * =========================================================
 * DASHBOARD MODÉRATEUR — IMMO-SN PREMIUM
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

<div class="min-h-screen bg-[#faf7f2] text-[#30221b]">

   <main class="mx-auto max-w-[1600px] space-y-8 px-4 py-6 sm:px-8 sm:py-7 lg:py-10">

   
        <!-- =====================================================
             BARRE SUPÉRIEURE
        ====================================================== -->

        <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

           <div class="min-w-0">
                <p class="text-xs font-semibold text-[#a98260]">
                    IMMO-SN / Administration / Modération
                </p>

                <h2 class="mt-1 text-xl font-black tracking-tight text-[#30221b]">
                    Espace de modération
                </h2>

                <p class="mt-1 text-sm text-[#8d7a6b]">
                    Gérez les annonces et les signalements de la plateforme.
                </p>
            </div>

           <div class="flex w-full flex-wrap items-center justify-between gap-3 sm:w-auto sm:justify-end">

           

                <span class="hidden text-sm font-medium text-[#8d7a6b] sm:block">
                    <?= $e($dateDuJour) ?>
                </span>

                <a href="/immo-sn/moderateur/signalements"
                   class="relative flex h-11 w-11 items-center justify-center rounded-xl bg-white text-[#5a402d] shadow-sm transition hover:bg-[#f1e7df]"
                   aria-label="Consulter les signalements"
                   title="Signalements">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12h4"/>
                    </svg>

                    <?php if ($signalementsEnAttente > 0): ?>
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#f7b500] px-1 text-[10px] font-black text-[#30221b]">
                            <?= $signalementsEnAttente > 99 ? '99+' : $signalementsEnAttente ?>
                        </span>
                    <?php endif; ?>

                </a>

                <div class="flex items-center gap-2 rounded-xl bg-white px-3 py-2 shadow-sm">

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#30221b] text-sm font-black text-[#f7b500]">
                        M
                    </span>

                    <span class="hidden sm:block">
                        <span class="block text-sm font-bold text-[#30221b]">
                            Modérateur
                        </span>
                        <span class="block text-xs text-[#8d7a6b]">
                            Espace de modération
                        </span>
                    </span>

                </div>

            </div>

        </header>


        <!-- =====================================================
             ACCUEIL PREMIUM
        ====================================================== -->

        <section class="relative isolate overflow-hidden rounded-[28px] bg-[#30221b] shadow-[0_18px_50px_rgba(48,34,27,0.12)]">

            <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 right-1/3 h-64 w-64 rounded-full bg-[#a98260]/15 blur-3xl"></div>

      <div class="relative grid min-w-0 gap-6 p-4 sm:gap-8 sm:p-9 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-center lg:p-11">

      
               <div class="min-w-0">

                    <div class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-2 text-xs font-bold text-[#e6d8ca]">

                        <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>

                        CENTRE DE MODÉRATION IMMO-SN

                    </div>

                   <h1 class="mt-5 max-w-3xl break-words text-2xl font-black leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">

                   
                        Votre espace,
                        <span class="text-[#f7b500]">en un coup d’œil.</span>

                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-7 text-[#d7cbc2] sm:text-base">

                        Suivez les publications immobilières, examinez les signalements
                        et veillez à la qualité des contenus diffusés sur la plateforme
                        IMMO-SN.

                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">

                    
                        <a href="/immo-sn/moderateur/annonces"
                          class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-extrabold text-[#30221b] transition hover:-translate-y-0.5 hover:bg-[#ffc21a] sm:w-auto"
                           > Examiner les annonces

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 12h14m-6-6 6 6-6 6"/>
                            </svg>

                        </a>

                        <a href="/immo-sn/moderateur/signalements"
                           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10 sm:w-auto"
                           > Consulter les signalements

                            <span aria-hidden="true">→</span>

                        </a>

                    </div>

                </div>


                <!-- PRIORITÉS -->

                <div class="rounded-2xl bg-white/[0.06] p-5 sm:p-6">

                    <p class="text-xs font-bold uppercase tracking-[0.15em] text-[#cbbcad]">
                        Vos priorités
                    </p>

                    <p class="mt-4 text-5xl font-black tracking-tight text-white tabular-nums">
                        <?= number_format($totalPriorites, 0, ',', ' ') ?>
                    </p>

                    <p class="mt-2 text-sm leading-6 text-[#d7cbc2]">
                        éléments en attente de traitement
                    </p>

                    <div class="mt-5 space-y-3">

                        <a href="/immo-sn/moderateur/annonces"
                           class="flex items-center justify-between gap-3 rounded-xl bg-white/5 px-3 py-3 transition hover:bg-white/10">

                            <span class="text-sm text-white">
                                Annonces à examiner
                            </span>

                            <span class="font-black text-[#f7b500]">
                                <?= number_format($annoncesEnAttente, 0, ',', ' ') ?>
                            </span>

                        </a>

                        <a href="/immo-sn/moderateur/signalements"
                           class="flex items-center justify-between gap-3 rounded-xl bg-white/5 px-3 py-3 transition hover:bg-white/10">

                            <span class="text-sm text-white">
                                Signalements à vérifier
                            </span>

                            <span class="font-black text-white">
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

            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="text-xs font-black uppercase tracking-[0.18em] text-[#a98260]">
                        Vue d'ensemble
                    </p>

                    <h2 class="mt-1 text-2xl font-black tracking-tight text-[#30221b] sm:text-3xl">
                        Les chiffres essentiels
                    </h2>

                    <p class="mt-2 text-sm text-[#8d7a6b]">
                        Suivi de votre activité de modération.
                    </p>

                </div>

                <span class="w-fit rounded-full bg-white px-3 py-2 text-xs font-semibold text-[#8d7a6b] shadow-sm">
                    Mise à jour au chargement
                </span>

            </div>


            <!-- CARTES STATISTIQUES -->

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <?php foreach ($cartes as $carte): ?>

                    <?php
                    $couleurs = [
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

                    <article class="group relative overflow-hidden rounded-[22px] bg-white p-5 shadow-[0_5px_24px_rgba(48,34,27,0.035)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_14px_34px_rgba(48,34,27,0.08)] sm:p-6">

                        <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full <?= $style['fond'] ?> opacity-70 transition-transform duration-300 group-hover:scale-125"></div>

                        <div class="relative">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <p class="break-words text-sm font-semibold text-[#8d7a6b]">

                                      <?= $e($carte['titre']) ?>
                                    </p>

                                    <p class="mt-5 text-4xl font-black tracking-tight text-[#30221b] tabular-nums">
                                        <?= number_format($carte['valeur'], 0, ',', ' ') ?>
                                    </p>

                                </div>

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl <?= $style['fond'] ?> <?= $style['texte'] ?>">

                                    <?php if ($carte['icone'] === 'home'): ?>

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M3 10.5 12 3l9 7.5M5 9v12h14V9M9 21v-7h6v7"/>
                                        </svg>

                                    <?php elseif ($carte['icone'] === 'flag'): ?>

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M4 22V4m0 0c4-3 8 3 16 0v12c-8 3-12-3-16 0"/>
                                        </svg>

                                    <?php elseif ($carte['icone'] === 'check'): ?>

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round"
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
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m9 12 2 2 4-4"/>
                                        </svg>

                                    <?php endif; ?>

                                </div>

                            </div>

                           <div class="mt-5 flex flex-col items-start justify-between gap-3 border-t border-[#f0eae4] pt-4 sm:flex-row sm:items-center">

                           
                                <p class="text-xs text-[#95877d]">
                                    <?= $e($carte['description']) ?>
                                </p>

                                <a href="<?= $e($carte['lien']) ?>"
                                   class="inline-flex shrink-0 items-center gap-1 text-xs font-bold text-[#8b684d] transition hover:text-[#30221b]">

                                    <?= $e($carte['action']) ?>

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-3.5 w-3.5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M5 12h14m-5-5 5 5-5 5"/>
                                    </svg>

                                </a>

                            </div>

                        </div>

                        <div class="absolute bottom-0 left-0 h-1 w-full <?= $style['barre'] ?> opacity-0 transition group-hover:opacity-100"></div>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>


        <!-- =====================================================
             ACCÈS RAPIDES
        ====================================================== -->

        <section>

            <div class="mb-5">

                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#a98260]">
                    Navigation rapide
                </p>

                <h2 class="mt-1 text-2xl font-black tracking-tight text-[#30221b]">
                    Vos espaces de travail
                </h2>

                <p class="mt-2 text-sm text-[#8d7a6b]">
                    Accédez directement aux différents outils de modération.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <!-- ANNONCES -->

                <a href="/immo-sn/moderateur/annonces"
                   class="group flex items-center gap-4 rounded-[22px] bg-white p-5 shadow-[0_5px_24px_rgba(48,34,27,0.035)] transition hover:-translate-y-1 hover:shadow-lg">

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#f7b500]/15 text-[#8b684d] transition group-hover:bg-[#f7b500] group-hover:text-[#30221b]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 10.5 12 3l9 7.5M5 9v12h14V9M9 21v-7h6v7"/>
                        </svg>

                    </span>

                    <span class="min-w-0 flex-1">

                        <span class="block font-extrabold text-[#30221b]">
                            Annonces
                        </span>

                        <span class="mt-1 block text-xs text-[#8d7a6b]">
                            Vérifier les publications
                        </span>

                    </span>

                    <span class="text-lg text-[#b6a99e] transition group-hover:translate-x-1 group-hover:text-[#8b684d]">
                        →
                    </span>

                </a>


                <!-- SIGNALEMENTS -->

                <a href="/immo-sn/moderateur/signalements"
                   class="group flex items-center gap-4 rounded-[22px] bg-white p-5 shadow-[0_5px_24px_rgba(48,34,27,0.035)] transition hover:-translate-y-1 hover:shadow-lg">

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700 transition group-hover:bg-red-600 group-hover:text-white">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 21V4m0 0c4-3 8 3 16 0v12c-8 3-12-3-16 0"/>
                        </svg>

                    </span>

                    <span class="min-w-0 flex-1">

                        <span class="block font-extrabold text-[#30221b]">
                            Signalements
                        </span>

                        <span class="mt-1 block text-xs text-[#8d7a6b]">
                            Examiner les alertes
                        </span>

                    </span>

                    <span class="text-lg text-[#b6a99e] transition group-hover:translate-x-1 group-hover:text-[#8b684d]">
                        →
                    </span>

                </a>


                <!-- RENOUVELLEMENTS -->

                <a href="/immo-sn/moderateur/renouvellements"
                   class="group flex items-center gap-4 rounded-[22px] bg-white p-5 shadow-[0_5px_24px_rgba(48,34,27,0.035)] transition hover:-translate-y-1 hover:shadow-lg">

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#f1e7df] text-[#5a402d] transition group-hover:bg-[#30221b] group-hover:text-[#f7b500]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 7v5l3 2M21 3v6h-6"/>
                        </svg>

                    </span>

                    <span class="min-w-0 flex-1">

                        <span class="block font-extrabold text-[#30221b]">
                            Renouvellements
                        </span>

                        <span class="mt-1 block text-xs text-[#8d7a6b]">
                            Traiter les demandes
                        </span>

                    </span>

                    <span class="text-lg text-[#b6a99e] transition group-hover:translate-x-1 group-hover:text-[#8b684d]">
                        →
                    </span>

                </a>


                <!-- HISTORIQUE -->

                <a href="/immo-sn/moderateur/historique"
                   class="group flex items-center gap-4 rounded-[22px] bg-white p-5 shadow-[0_5px_24px_rgba(48,34,27,0.035)] transition hover:-translate-y-1 hover:shadow-lg">

                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 transition group-hover:bg-emerald-600 group-hover:text-white">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 7v5l3 2"/>
                        </svg>

                    </span>

                    <span class="min-w-0 flex-1">

                        <span class="block font-extrabold text-[#30221b]">
                            Historique
                        </span>

                        <span class="mt-1 block text-xs text-[#8d7a6b]">
                            Consulter les décisions
                        </span>

                    </span>

                    <span class="text-lg text-[#b6a99e] transition group-hover:translate-x-1 group-hover:text-[#8b684d]">
                        →
                    </span>

                </a>

            </div>

        </section>


       
    </main>

</div>
