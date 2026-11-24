<?php
/**
 * Vue : Détail d'une agence — IMMO-SN
 *
 * Variables attendues :
 * - $agence : tableau contenant les données de l'agence
 * - $biens  : tableau contenant les biens de l'agence
 */

$agence = is_array($agence ?? null) ? $agence : [];

$nom          = $agence['nom_agence'] ?? 'Agence immobilière';
$ninea        = $agence['ninea'] ?? '';
$description  = $agence['description'] ?? '';
$adresse      = $agence['adresse'] ?? '';
$logo         = $agence['logo'] ?? '';
$telephone    = $agence['telephone'] ?? '';
$email        = $agence['email'] ?? '';
$badge        = !empty($agence['badge_verifie']);
$dateCreation = $agence['created_at'] ?? null;
$idAgence     = $agence['id'] ?? '';

$biens = is_array($biens ?? null) ? $biens : [];
$nombreBiens = count($biens);

$e = static function ($valeur): string {
    return htmlspecialchars((string) ($valeur ?? ''), ENT_QUOTES, 'UTF-8');
};

$dateFormatee = $dateCreation
    ? 'Depuis ' . date('F Y', strtotime($dateCreation))
    : 'Agence immobilière';

$initiale = function_exists('mb_substr')
    ? mb_strtoupper(mb_substr($nom, 0, 1, 'UTF-8'), 'UTF-8')
    : strtoupper(substr($nom, 0, 1));

$contactUrl = '#contact-agence';

if ($telephone !== '') {
    $contactUrl = 'tel:' . preg_replace('/[^0-9+]/', '', $telephone);
} elseif ($email !== '') {
    $contactUrl = 'mailto:' . $email;
}

$services = [
    [
        'titre' => 'Vente immobilière',
        'texte' => 'Découvrez les biens proposés à la vente et trouvez votre prochain investissement.',
        'icone' => 'M3 10.5 12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6'
    ],
    [
        'titre' => 'Location',
        'texte' => 'Trouvez un logement adapté à vos besoins et à votre budget.',
        'icone' => 'M4 21V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16M2 21h20M8 7h2m4 0h2M8 11h2m4 0h2M10 21v-5h4v5'
    ],
    [
        'titre' => 'Conseil immobilier',
        'texte' => 'Bénéficiez d’un accompagnement dans toutes les étapes de votre projet.',
        'icone' => 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 5v4l3 2'
    ],
    [
        'titre' => 'Gestion de biens',
        'texte' => 'Confiez la gestion et le suivi de vos biens à des professionnels.',
        'icone' => 'M4 7h16M4 12h16M4 17h10M3 4h18v16H3z'
    ]
];

$urlBiensAgence = '/biens?agence=' . urlencode((string) $idAgence);
?>

<div class="min-h-screen bg-[#F4F6F9] text-[#061830]">

    <!-- =====================================================
         FIL D'ARIANE
    ====================================================== -->
    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">

        <nav class="flex flex-wrap items-center gap-2 text-sm text-[#8290A0]"
             aria-label="Fil d’Ariane">

            <a href="/" class="transition hover:text-[#D4A900]">
                Accueil
            </a>

            <svg class="h-3.5 w-3.5 text-[#B8C2CE]" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
            </svg>

            <a href="/agences" class="transition hover:text-[#D4A900]">
                Agences
            </a>

            <svg class="h-3.5 w-3.5 text-[#B8C2CE]" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
            </svg>

            <span class="max-w-[220px] truncate font-bold text-[#061830]">
                <?= $e($nom) ?>
            </span>

        </nav>

    </div>


   
<!-- =====================================================
     BANNIÈRE PRINCIPALE DE L'AGENCE
====================================================== -->
<section class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">

    <div class="overflow-hidden rounded-2xl bg-[#071B36] shadow-sm">

        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[1fr_auto] lg:items-center">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                <!-- LOGO -->
                <div class="flex h-20 w-20 shrink-0 items-center justify-center self-start rounded-xl border border-white/15 bg-white p-2 sm:h-24 sm:w-24">

                    <?php if ($logo !== ''): ?>

                        <img
                            src="<?= $e($logo) ?>"
                            alt="Logo de <?= $e($nom) ?>"
                            class="h-full w-full rounded-lg object-contain"
                            loading="eager"
                        >

                    <?php else: ?>

                        <div class="flex h-full w-full items-center justify-center rounded-lg bg-[#F3F6FA] text-3xl font-black text-[#A98200]">
                            <?= $e($initiale) ?>
                        </div>

                    <?php endif; ?>

                </div>

                <!-- IDENTITÉ DE L'AGENCE -->
                <div class="min-w-0 flex-1">

                    <div class="mb-3 flex flex-wrap items-center gap-2">

                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-bold text-slate-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                            Agence immobilière
                        </span>

                        <?php if ($badge): ?>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#F5B800] px-3 py-1 text-[11px] font-extrabold text-[#071B36]">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 1.5 12.3 3l2.8.1 1.1 2.5 2.1 1.8-.8 2.7.5 2.7-2.4 1.5-1.3 2.5-2.8-.3L10 18l-2.5-1.5-2.8.3-1.3-2.5L1 12.8l.5-2.7-.8-2.7 2.1-1.8 1.1-2.5 2.8-.1L10 1.5Zm3.2 6.2a.75.75 0 0 0-1.1-1l-3.1 3.4-1.2-1.2a.75.75 0 0 0-1.1 1l1.8 1.8a.75.75 0 0 0 1.1 0l3.6-4Z" clip-rule="evenodd"/>
                                </svg>
                                Agence vérifiée
                            </span>

                        <?php endif; ?>

                    </div>

                    <h1 class="break-words text-xl font-black tracking-tight text-white sm:text-2xl lg:text-3xl">
                        <?= $e($nom) ?>
                    </h1>

                    <p class="mt-2 flex items-start gap-2 text-xs leading-6 text-slate-300 sm:text-sm">
                        <svg class="mt-1 h-4 w-4 shrink-0 text-[#F5B800]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                            <circle cx="12" cy="9" r="2.2"/>
                        </svg>

                        <span class="break-words">
                            <?= $e($adresse ?: 'Sénégal') ?>
                        </span>
                    </p>

                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-[11px] text-slate-300 sm:text-xs">

                        <?php if ($ninea !== ''): ?>

                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-[#F5B800]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10l4 4v14H3V3h4Zm3 0v5h4V3M7 13h10M7 17h7"/>
                                </svg>
                                NINEA : <?= $e($ninea) ?>
                            </span>

                        <?php endif; ?>

                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-[#F5B800]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                            <?= $e($dateFormatee) ?>
                        </span>

                    </div>

                </div>

            </div>

            <!-- ACTIONS -->
            <div class="flex flex-col gap-2 sm:flex-row lg:min-w-[190px] lg:flex-col">

                <a
                    href="<?= $e($contactUrl) ?>"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-4 py-3 text-xs font-extrabold text-[#071B36] transition hover:bg-[#DCA500] sm:text-sm"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 0 1 5.5 3h2A1.5 1.5 0 0 1 9 4.2l1 3a1.5 1.5 0 0 1-.7 1.7l-1.5.9a14 14 0 0 0 6.4 6.4l.9-1.5a1.5 1.5 0 0 1 1.7-.7l3 1a1.5 1.5 0 0 1 1.2 1.5v2A2.5 2.5 0 0 1 18.5 21 15.5 15.5 0 0 1 3 5.5Z"/>
                    </svg>
                    Contacter l'agence
                </a>

                <a
                    href="<?= $e($urlBiensAgence) ?>"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/5 px-4 py-3 text-xs font-bold text-white transition hover:border-[#F5B800] hover:text-[#F5B800] sm:text-sm"
                >
                    Découvrir ses biens

                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

            </div>

        </div>

        <!-- BANDEAU INFÉRIEUR -->
        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-white/10 bg-[#0B2748] px-5 py-3 sm:px-6">

            <p class="text-[11px] font-medium text-slate-300 sm:text-xs">
                Votre partenaire immobilier au Sénégal
            </p>

            <a href="#biens-agence" class="inline-flex items-center gap-2 text-[11px] font-bold text-[#F5B800] transition hover:text-white sm:text-xs">
                Explorer le catalogue

                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7 7 7 7-7"/>
                </svg>
            </a>

        </div>

    </div>

</section>



    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->
    <main class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">

        <div class="flex w-full flex-col gap-8">


            <!-- =================================================
                 À PROPOS
            ================================================== -->
            <section id="description-agence"
                     class="w-full rounded-[28px] border border-[#E3E9F0] bg-white p-6 shadow-[0_12px_40px_rgba(6,24,48,0.045)] sm:p-8">

                <div class="mb-6 flex items-start gap-4">

                    <div class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-[#EAF0F8] text-[#D4A900]">

                        <svg class="h-6 w-6" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.7"
                             aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V5.8A2.8 2.8 0 0 1 6.8 3H20v16.5H6.8A2.8 2.8 0 0 0 4 22V19.5Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8M8 15h5"/>
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#B18B12]">
                            Découvrez-nous
                        </p>

                        <h2 class="mt-1 text-xl font-black tracking-tight text-[#061830] sm:text-2xl">
                            À propos de l'agence
                        </h2>

                    </div>

                </div>

                <div class="rounded-2xl bg-[#F4F6F9] p-5 sm:p-6">

                    <p class="whitespace-pre-line text-sm leading-8 text-[#5F6F80] sm:text-base">
                        <?= $e(
                            $description ?: 'Cette agence accompagne ses clients dans leurs projets immobiliers au Sénégal.'
                        ) ?>
                    </p>

                </div>

            </section>


            <!-- =================================================
                 SERVICES
            ================================================== -->
            <section id="services-agence"
                     class="w-full overflow-hidden rounded-[28px] border border-[#E3E9F0] bg-white p-6 shadow-[0_12px_40px_rgba(6,24,48,0.045)] sm:p-8">

                <div class="mb-7">

                    <div class="flex items-center gap-3">

                        <div class="h-1 w-9 rounded-full bg-[#F5C542]"></div>

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#B18B12]">
                            Notre accompagnement
                        </p>

                    </div>

                    <h2 class="mt-3 text-xl font-black tracking-tight text-[#061830] sm:text-2xl">
                        Les services immobiliers
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-[#8290A0]">
                        Un accompagnement professionnel adapté à vos projets immobiliers.
                    </p>

                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <?php foreach ($services as $service): ?>

                        <article class="group rounded-2xl border border-[#E3E9F0] bg-[#F8FAFC] p-5 transition duration-300 hover:-translate-y-1 hover:border-[#F5C542] hover:bg-white hover:shadow-[0_14px_35px_rgba(6,24,48,0.08)]">

                            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-[#EAF0F8] text-[#D4A900] transition duration-300 group-hover:bg-[#061830] group-hover:text-[#F5C542]">

                                <svg class="h-5 w-5" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.7"
                                     aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="<?= $e($service['icone']) ?>"/>
                                </svg>

                            </div>

                            <h3 class="font-extrabold text-[#061830]">
                                <?= $e($service['titre']) ?>
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#8290A0]">
                                <?= $e($service['texte']) ?>
                            </p>

                            <div class="mt-5 h-1 w-8 rounded-full bg-[#F5C542] transition-all duration-300 group-hover:w-14"></div>

                        </article>

                    <?php endforeach; ?>

                </div>

            </section>


            <!-- =================================================
                 INFORMATIONS GÉNÉRALES
            ================================================== -->
            <section id="informations-agence"
                     class="w-full rounded-[28px] border border-[#E3E9F0] bg-white p-6 shadow-[0_12px_40px_rgba(6,24,48,0.045)] sm:p-8">

                <div class="mb-7">

                    <div class="flex items-center gap-3">

                        <div class="h-1 w-9 rounded-full bg-[#F5C542]"></div>

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#B18B12]">
                            Coordonnées et accès
                        </p>

                    </div>

                    <h2 class="mt-3 text-xl font-black tracking-tight text-[#061830] sm:text-2xl">
                        Informations générales
                    </h2>

                    <p class="mt-2 text-sm text-[#8290A0]">
                        Retrouvez les informations utiles pour contacter et localiser cette agence.
                    </p>

                </div>

                <div class="grid w-full grid-cols-1 items-stretch gap-5 md:grid-cols-2 xl:grid-cols-3">


                    <!-- =========================================
                         COORDONNÉES
                    ========================================== -->
                    <section id="contact-agence"
                             class="rounded-[24px] border border-[#E3E9F0] bg-[#F8FAFC] p-6">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#EAF0F8] text-[#D4A900]">

                            <svg class="h-6 w-6" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.7"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 0 1 5.5 3h2A1.5 1.5 0 0 1 9 4.2l1 3a1.5 1.5 0 0 1-.7 1.7l-1.5.9a14 14 0 0 0 6.4 6.4l.9-1.5a1.5 1.5 0 0 1 1.7-.7l3 1a1.5 1.5 0 0 1 1.2 1.5v2A2.5 2.5 0 0 1 18.5 21 15.5 15.5 0 0 1 3 5.5Z"/>
                            </svg>

                        </div>

                        <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.16em] text-[#B18B12]">
                            Parlons de votre projet
                        </p>

                        <h2 class="mt-2 text-xl font-black text-[#061830]">
                            Contacter l'agence
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#8290A0]">
                            Vous souhaitez visiter un bien ou obtenir des informations ?
                        </p>

                        <div class="mt-6 space-y-5">


                            <!-- TÉLÉPHONE -->
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#D4A900] shadow-sm">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.7"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 0 1 5.5 3h2A1.5 1.5 0 0 1 9 4.2l1 3a1.5 1.5 0 0 1-.7 1.7l-1.5.9a14 14 0 0 0 6.4 6.4l.9-1.5a1.5 1.5 0 0 1 1.7-.7l3 1a1.5 1.5 0 0 1 1.2 1.5v2A2.5 2.5 0 0 1 18.5 21 15.5 15.5 0 0 1 3 5.5Z"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-semibold text-[#8290A0]">
                                        Téléphone
                                    </p>

                                    <?php if ($telephone !== ''): ?>

                                        <a
                                            href="tel:<?= $e(preg_replace('/[^0-9+]/', '', $telephone)) ?>"
                                            class="mt-1 block break-words text-sm font-bold text-[#061830] transition hover:text-[#B18B12]"
                                        >
                                            <?= $e($telephone) ?>
                                        </a>

                                    <?php else: ?>

                                        <p class="mt-1 text-sm text-[#8290A0]">
                                            Non renseigné
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- EMAIL -->
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#D4A900] shadow-sm">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.7"
                                         aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-semibold text-[#8290A0]">
                                        Adresse e-mail
                                    </p>

                                    <?php if ($email !== ''): ?>

                                        <a
                                            href="mailto:<?= $e($email) ?>"
                                            class="mt-1 block break-all text-sm font-bold text-[#061830] transition hover:text-[#B18B12]"
                                        >
                                            <?= $e($email) ?>
                                        </a>

                                    <?php else: ?>

                                        <p class="mt-1 text-sm text-[#8290A0]">
                                            Non renseignée
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- ADRESSE -->
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#D4A900] shadow-sm">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.7"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                        <circle cx="12" cy="9" r="2.2"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-semibold text-[#8290A0]">
                                        Adresse
                                    </p>

                                    <p class="mt-1 break-words text-sm font-bold leading-6 text-[#061830]">
                                        <?= $e($adresse ?: 'Sénégal') ?>
                                    </p>

                                </div>

                            </div>

                        </div>


                        <?php if ($telephone !== '' || $email !== ''): ?>

                            <a
                                href="<?= $e($contactUrl) ?>"
                                class="mt-7 flex w-full items-center justify-center gap-2 rounded-xl bg-[#061830] px-5 py-3.5 text-sm font-extrabold text-white transition hover:bg-[#173B60]"
                            >

                                Prendre contact

                                <svg class="h-4 w-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2"
                                     aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                                </svg>

                            </a>

                        <?php endif; ?>

                    </section>


                    <!-- =========================================
                         LOCALISATION
                    ========================================== -->
                    <section class="rounded-[24px] border border-[#E3E9F0] bg-[#F8FAFC] p-6">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#EAF0F8] text-[#D4A900]">

                            <svg class="h-6 w-6" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.7"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.2"/>
                            </svg>

                        </div>

                        <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.16em] text-[#B18B12]">
                            Nous trouver
                        </p>

                        <h2 class="mt-2 text-xl font-black text-[#061830]">
                            Localisation
                        </h2>

                        <p class="mt-2 text-sm text-[#8290A0]">
                            Adresse de l'agence
                        </p>


                        <div class="relative mt-6 min-h-[150px] overflow-hidden rounded-2xl border border-[#DCE5EF] bg-gradient-to-br from-[#EAF0F8] to-[#DCE7F3] p-5">

                            <div class="absolute -right-5 -top-5 h-28 w-28 rounded-full bg-[#F5C542]/20"></div>

                            <div class="absolute -bottom-10 -left-8 h-32 w-32 rounded-full bg-[#061830]/5"></div>

                            <div class="relative">

                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#D4A900] shadow-sm">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.8"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                        <circle cx="12" cy="9" r="2.2"/>
                                    </svg>

                                </div>

                                <p class="break-words text-sm font-bold leading-6 text-[#061830]">
                                    <?= $e($adresse ?: 'Adresse non renseignée') ?>
                                </p>

                                <p class="mt-2 text-xs font-medium text-[#71839A]">
                                    Sénégal
                                </p>

                            </div>

                        </div>


                        <?php if ($adresse !== ''): ?>

                            <a
                                href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode($adresse . ', Sénégal') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-[#B18B12] transition hover:text-[#061830]"
                            >

                                Ouvrir dans Google Maps

                                <svg class="h-4 w-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.8"
                                     aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 4h6v6m-11 4L20 4M18 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6"/>
                                </svg>

                            </a>

                        <?php endif; ?>

                    </section>


                    <!-- =========================================
                         QR CODES
                    ========================================== -->
                    <section class="relative overflow-hidden rounded-[24px] border border-[#DCE5EF] bg-gradient-to-br from-[#EAF0F8] via-white to-[#F4F6F9] p-6">

                        <div class="absolute -right-12 -top-12 h-36 w-36 rounded-full bg-[#F5C542]/15"></div>

                        <div class="relative">

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-[#D4A900] shadow-sm">

                                <svg class="h-6 w-6" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.7"
                                     aria-hidden="true">
                                    <rect x="3" y="3" width="6" height="6" rx="1"/>
                                    <rect x="15" y="3" width="6" height="6" rx="1"/>
                                    <rect x="3" y="15" width="6" height="6" rx="1"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 15h3v3h-3zM21 15v2m-3 4h3m0-6v6"/>
                                </svg>

                            </div>

                            <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.16em] text-[#B18B12]">
                                Accès rapide
                            </p>

                            <h2 class="mt-2 text-xl font-black text-[#061830]">
                                QR Codes de l'agence
                            </h2>

                            <p class="mt-3 text-sm leading-6 text-[#647386]">
                                Retrouvez les QR Codes associés aux biens de cette agence et partagez facilement vos annonces.
                            </p>

                            <div class="mt-5 flex items-center gap-3 rounded-xl border border-[#DCE5EF] bg-white/90 p-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#EAF0F8] text-[#D4A900]">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.8"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/>
                                    </svg>

                                </div>

                                <p class="text-xs font-semibold leading-5 text-[#526174]">
                                    Téléchargement groupé des QR Codes au format ZIP.
                                </p>

                            </div>

                            <a
                                href="/agences/<?= urlencode((string) $idAgence) ?>/qr-zip"
                                class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#061830] px-4 py-3.5 text-sm font-extrabold text-white transition hover:bg-[#173B60]"
                            >

                                <svg class="h-4 w-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.8"
                                     aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/>
                                </svg>

                                Télécharger le ZIP

                            </a>

                        </div>

                    </section>

                </div>

            </section>


            <!-- =================================================
                 BIENS DE L'AGENCE
            ================================================== -->
            <section id="biens-agence"
                     class="w-full rounded-[28px] border border-[#E3E9F0] bg-white p-6 shadow-[0_12px_40px_rgba(6,24,48,0.045)] sm:p-8">

                <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

                    <div>

                        <div class="flex items-center gap-3">

                            <div class="h-1 w-9 rounded-full bg-[#F5C542]"></div>

                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#B18B12]">
                                Catalogue immobilier
                            </p>

                        </div>

                        <h2 class="mt-3 text-xl font-black tracking-tight text-[#061830] sm:text-2xl">
                            Les biens de l'agence
                        </h2>

                        <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#EAF0F8] px-3 py-1.5 text-xs font-bold text-[#173B60]">

                            <span class="h-1.5 w-1.5 rounded-full bg-[#D4A900]"></span>

                            <?= $nombreBiens ?>
                            bien<?= $nombreBiens > 1 ? 's' : '' ?>
                            disponible<?= $nombreBiens > 1 ? 's' : '' ?>

                        </div>

                    </div>


                    <?php if ($nombreBiens > 3): ?>

                        <a
                            href="<?= $e($urlBiensAgence) ?>"
                            class="inline-flex items-center gap-2 text-sm font-extrabold text-[#B18B12] transition hover:text-[#061830]"
                        >

                            Voir tous les biens

                            <svg class="h-4 w-4" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                            </svg>

                        </a>

                    <?php endif; ?>

                </div>


                <?php if (!empty($biens)): ?>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

                        <?php foreach (array_slice($biens, 0, 3) as $bien): ?>

                            <?php
                            $typeOffre = $bien['type_offre'] ?? 'Location';
                            $isVente = stripos($typeOffre, 'vente') !== false;
                            $titreBien = $bien['titre'] ?? 'Bien immobilier';
                            $ville = $bien['ville_nom'] ?? 'Sénégal';
                            $quartier = $bien['quartier_nom'] ?? '';
                            $prix = (float) ($bien['prix'] ?? 0);
                            $photo = $bien['photo'] ?? '';
                            ?>

                            <article class="group overflow-hidden rounded-[24px] border border-[#E3E9F0] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#F5C542] hover:shadow-[0_18px_45px_rgba(6,24,48,0.11)]">


                                <!-- PHOTO -->
                                <div class="relative overflow-hidden bg-[#EAF0F8]">

                                    <?php if ($photo !== ''): ?>

                                        <img
                                            src="<?= $e($photo) ?>"
                                            alt="<?= $e($titreBien) ?>"
                                            class="h-56 w-full object-cover transition duration-700 group-hover:scale-105"
                                            loading="lazy"
                                        >

                                    <?php else: ?>

                                        <div class="flex h-56 items-center justify-center bg-gradient-to-br from-[#EAF0F8] to-[#DCE7F3] text-[#D4A900]">

                                            <div class="text-center">

                                                <svg class="mx-auto h-12 w-12"
                                                     viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="1.3"
                                                     aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 11 9-8 9 8M5.5 9.5V21h13V9.5M9 21v-7h6v7"/>
                                                </svg>

                                                <p class="mt-3 text-xs font-semibold">
                                                    Photo non disponible
                                                </p>

                                            </div>

                                        </div>

                                    <?php endif; ?>


                                    <!-- BADGE OFFRE -->
                                    <span class="absolute left-4 top-4 rounded-lg px-3 py-2 text-xs font-extrabold shadow-sm <?= $isVente ? 'bg-[#F5C542] text-[#061830]' : 'bg-[#061830] text-white' ?>">
                                        <?= $e($typeOffre) ?>
                                    </span>

                                </div>


                                <!-- CONTENU -->
                                <div class="p-5">

                                    <p class="flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-[#B18B12]">

                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2"
                                             aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                            <circle cx="12" cy="9" r="2"/>
                                        </svg>

                                        <?= $e($ville) ?>

                                        <?php if ($quartier !== ''): ?>
                                            <span class="text-[#B8C2CE]">·</span>
                                            <?= $e($quartier) ?>
                                        <?php endif; ?>

                                    </p>

                                    <h3 class="mt-3 line-clamp-2 min-h-12 font-extrabold leading-6 text-[#061830] transition group-hover:text-[#B18B12]">
                                        <?= $e($titreBien) ?>
                                    </h3>


                                    <div class="mt-5 flex items-end justify-between gap-3 border-t border-[#E8EDF3] pt-4">

                                        <div class="min-w-0">

                                            <p class="text-xs text-[#8290A0]">
                                                Prix annoncé
                                            </p>

                                            <p class="mt-1 text-xl font-black text-[#061830]">

                                                <?= number_format($prix, 0, ',', ' ') ?>

                                                <span class="text-xs font-bold text-[#B18B12]">
                                                    FCFA
                                                </span>

                                            </p>

                                        </div>

                                        <a
                                            href="<?= $e($urlBiensAgence) ?>"
                                            class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#EAF0F8] text-[#061830] transition hover:bg-[#061830] hover:text-[#F5C542]"
                                            aria-label="Découvrir les biens de l'agence"
                                        >

                                            <svg class="h-5 w-5" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                                 aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                                            </svg>

                                        </a>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>


                    <?php if ($nombreBiens > 3): ?>

                        <div class="mt-8 flex justify-center">

                            <a
                                href="<?= $e($urlBiensAgence) ?>"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#061830] px-7 py-4 text-sm font-extrabold text-white shadow-lg shadow-[#061830]/10 transition hover:bg-[#173B60]"
                            >

                                Voir les <?= $nombreBiens ?> biens

                                <svg class="h-4 w-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2"
                                     aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                                </svg>

                            </a>

                        </div>

                    <?php endif; ?>


                <?php else: ?>


                    <!-- AUCUN BIEN -->
                    <div class="rounded-[24px] border border-dashed border-[#C9D6E5] bg-[#F8FAFC] px-6 py-14 text-center">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[24px] bg-[#EAF0F8] text-[#D4A900]">

                            <svg class="h-9 w-9" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 11 12 3l9 8M5 9.5V21h14V9.5M9 21v-7h6v7"/>
                            </svg>

                        </div>

                        <h3 class="mt-6 text-lg font-black text-[#061830]">
                            Aucun bien disponible pour le moment
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-[#8290A0]">
                            Les annonces de cette agence apparaîtront ici lorsqu'elles seront disponibles.
                            N'hésitez pas à revenir consulter son catalogue.
                        </p>

                        <a
                            href="/biens"
                            class="mt-7 inline-flex items-center justify-center gap-2 rounded-xl bg-[#061830] px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-[#173B60]"
                        >

                            Explorer les biens

                            <svg class="h-4 w-4" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                            </svg>

                        </a>

                    </div>

                <?php endif; ?>

            </section>

        </div>

    </main>


    <!-- =====================================================
         PIED DE PAGE BLEU MARINE
    ====================================================== -->
    <footer class="relative mt-8 overflow-hidden bg-[#061830] text-white">

        <!-- Décorations -->
        <div class="pointer-events-none absolute -right-24 -top-28 h-72 w-72 rounded-full bg-[#F5C542]/10 blur-3xl"></div>

        <div class="pointer-events-none absolute -bottom-32 left-1/4 h-64 w-64 rounded-full bg-[#173B60]/50 blur-3xl"></div>


        <!-- CONTENU DU FOOTER -->
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

            <div class="grid gap-10 md:grid-cols-3 md:items-center">


                <!-- LOGO ET IDENTITÉ -->
                <div>

                    <a href="/" class="inline-flex items-center text-2xl font-black tracking-tight text-white">

                        IMMO<span class="text-[#F5C542]">-SN</span>

                    </a>

                    <p class="mt-4 max-w-sm text-sm leading-7 text-[#B8C7D8]">
                        Votre partenaire immobilier au Sénégal. Trouvez le bien qui correspond à vos projets et découvrez les agences immobilières de confiance.
                    </p>

                    <div class="mt-5 flex items-center gap-2">

                        <span class="h-1 w-8 rounded-full bg-[#F5C542]"></span>

                        <span class="text-xs font-bold uppercase tracking-[0.18em] text-[#F5C542]">
                            L'immobilier en toute confiance
                        </span>

                    </div>

                </div>


                <!-- NAVIGATION -->
                <div class="md:justify-self-center">

                    <h3 class="text-sm font-extrabold uppercase tracking-[0.16em] text-white">
                        Navigation
                    </h3>

                    <div class="mt-5 flex flex-col gap-3">

                        <a href="/"
                           class="text-sm text-[#B8C7D8] transition hover:text-[#F5C542]">
                            Accueil
                        </a>

                        <a href="/biens"
                           class="text-sm text-[#B8C7D8] transition hover:text-[#F5C542]">
                            Nos biens immobiliers
                        </a>

                        <a href="/agences"
                           class="text-sm text-[#B8C7D8] transition hover:text-[#F5C542]">
                            Nos agences
                        </a>

                    </div>

                </div>


                <!-- CONTACT ET RETOUR -->
                <div class="md:justify-self-end">

                    <h3 class="text-sm font-extrabold uppercase tracking-[0.16em] text-white">
                        Vous recherchez un bien ?
                    </h3>

                    <p class="mt-4 max-w-xs text-sm leading-7 text-[#B8C7D8]">
                        Parcourez notre catalogue et trouvez votre prochain logement ou investissement immobilier.
                    </p>

                    <a
                        href="/biens"
                        class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5C542] px-5 py-3.5 text-sm font-extrabold text-[#061830] transition hover:bg-[#FFDA68]"
                    >

                        Explorer les biens

                        <svg class="h-4 w-4" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2"
                             aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                        </svg>

                    </a>

                </div>

            </div>


            <!-- SÉPARATION -->
            <div class="my-9 h-px bg-white/10"></div>


            <!-- COPYRIGHT -->
            <div class="flex flex-col items-center justify-between gap-4 text-center sm:flex-row sm:text-left">

                <p class="text-xs text-[#AFC0D2]">
                    © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                </p>

                <a
                    href="/agences"
                    class="inline-flex items-center gap-2 text-xs font-extrabold text-[#F5C542] transition hover:text-white"
                >

                    Toutes les agences

                    <svg class="h-4 w-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                    </svg>

                </a>

            </div>

        </div>

    </footer>

</div>
