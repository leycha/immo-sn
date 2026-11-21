
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
?>

<div class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

    <!-- FIL D'ARIANE -->
    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">

        <nav class="flex flex-wrap items-center gap-2 text-sm text-[#71869A]"
             aria-label="Fil d’Ariane">

            <a href="/" class="transition hover:text-[#B88A00]">
                Accueil
            </a>

            <svg class="h-3.5 w-3.5 text-[#AAB4BF]" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
            </svg>

            <a href="/agences" class="transition hover:text-[#B88A00]">
                Agences
            </a>

            <svg class="h-3.5 w-3.5 text-[#AAB4BF]" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
            </svg>

            <span class="max-w-[220px] truncate font-semibold text-[#101D33]">
                <?= $e($nom) ?>
            </span>

        </nav>

    </div>

    <!-- =====================================================
         BANNIÈRE PRINCIPALE DE L'AGENCE
    ====================================================== -->
    <section class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">

        <div class="relative isolate overflow-hidden rounded-[30px] bg-gradient-to-br from-[#071B36] via-[#102943] to-[#1D3851] shadow-[0_24px_70px_rgba(7,27,54,0.18)]">

            <!-- Décoration -->
            <div class="pointer-events-none absolute -right-24 -top-32 h-96 w-96 rounded-full bg-[#F5B800]/10 blur-3xl"></div>

            <div class="pointer-events-none absolute -bottom-40 left-1/3 h-80 w-80 rounded-full bg-[#D7A200]/10 blur-3xl"></div>

            <div class="pointer-events-none absolute right-0 top-0 h-full w-1/2 opacity-[0.06]"
                 style="background-image: linear-gradient(135deg, transparent 25%, #fff 25%, #fff 26%, transparent 26%, transparent 50%, #fff 50%, #fff 51%, transparent 51%); background-size: 34px 34px;">
            </div>

            <div class="relative grid gap-8 p-6 sm:p-9 lg:grid-cols-[1fr_auto] lg:items-center lg:p-11">

                <div class="flex flex-col gap-6 sm:flex-row sm:items-center">

                    <!-- LOGO -->
                    <div class="flex h-28 w-28 shrink-0 items-center justify-center self-start rounded-[26px] border border-white/20 bg-white p-2.5 shadow-2xl sm:h-36 sm:w-36">

                        <?php if ($logo !== ''): ?>

                            <img
                                src="<?= $e($logo) ?>"
                                alt="Logo de <?= $e($nom) ?>"
                                class="h-full w-full rounded-[19px] object-contain"
                                loading="eager"
                            >

                        <?php else: ?>

                            <div class="flex h-full w-full items-center justify-center rounded-[19px] bg-[#F5F3ED] text-5xl font-black text-[#B88A00]">
                                <?= $e($initiale) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- IDENTITÉ -->
                    <div class="min-w-0 flex-1">

                        <div class="mb-4 flex flex-wrap items-center gap-2">

                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-2 text-xs font-bold text-[#E4EAF0]">

                                <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>

                                Agence immobilière

                            </span>

                            <?php if ($badge): ?>

                                <span class="inline-flex items-center gap-1.5 rounded-full border border-[#F5B800]/30 bg-[#F5B800] px-3.5 py-2 text-xs font-extrabold text-[#071B36]">

                                    <svg viewBox="0 0 20 20" fill="currentColor"
                                         class="h-4 w-4" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 1.5 12.3 3l2.8.1 1.1 2.5 2.1 1.8-.8 2.7.5 2.7-2.4 1.5-1.3 2.5-2.8-.3L10 18l-2.5-1.5-2.8.3-1.3-2.5L1 12.8l.5-2.7-.8-2.7 2.1-1.8 1.1-2.5 2.8-.1L10 1.5Zm3.2 6.2a.75.75 0 0 0-1.1-1l-3.1 3.4-1.2-1.2a.75.75 0 0 0-1.1 1l1.8 1.8a.75.75 0 0 0 1.1 0l3.6-4Z" clip-rule="evenodd"/>
                                    </svg>

                                    Agence vérifiée

                                </span>

                            <?php endif; ?>

                        </div>

                        <h1 class="break-words text-2xl font-black tracking-tight text-white sm:text-3xl lg:text-4xl">
                            <?= $e($nom) ?>
                        </h1>

                        <p class="mt-3 flex items-start gap-2.5 text-sm leading-7 text-[#C7D3DF] sm:text-base">

                            <svg class="mt-1 h-5 w-5 shrink-0 text-[#F5B800]"
                                 viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.2"/>
                            </svg>

                            <span class="min-w-0 break-words">
                                <?= $e($adresse ?: 'Sénégal') ?>
                            </span>

                        </p>

                        <div class="mt-5 flex flex-wrap gap-x-6 gap-y-3 text-xs text-[#B8C7D2] sm:text-sm">

                            <?php if ($ninea !== ''): ?>

                                <span class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 text-[#F5B800]" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.8"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10l4 4v14H3V3h4Zm3 0v5h4V3M7 13h10M7 17h7"/>
                                    </svg>
                                    NINEA : <?= $e($ninea) ?>
                                </span>

                            <?php endif; ?>

                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4 text-[#F5B800]" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.8"
                                     aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                </svg>
                                <?= $e($dateFormatee) ?>
                            </span>

                        </div>

                    </div>

                </div>

                <!-- ACTIONS -->
                <div class="flex flex-col gap-3 sm:flex-row lg:min-w-[205px] lg:flex-col">

                    <a
                        href="<?= $e($contactUrl) ?>"
                        class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-[#F5B800] px-5 py-4 text-sm font-extrabold text-[#071B36] shadow-lg shadow-black/10 transition hover:bg-[#FFD04A] hover:shadow-xl"
                    >

                        <svg class="h-5 w-5" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 0 1 5.5 3h2A1.5 1.5 0 0 1 9 4.2l1 3a1.5 1.5 0 0 1-.7 1.7l-1.5.9a14 14 0 0 0 6.4 6.4l.9-1.5a1.5 1.5 0 0 1 1.7-.7l3 1a1.5 1.5 0 0 1 1.2 1.5v2A2.5 2.5 0 0 1 18.5 21 15.5 15.5 0 0 1 3 5.5Z"/>
                        </svg>

                        Contacter l'agence

                    </a>

                    <a
                        href="/biens?agence=<?= urlencode((string) $idAgence) ?>"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/5 px-5 py-4 text-sm font-bold text-white transition hover:border-[#F5B800]/50 hover:bg-white/10"
                    >

                        Découvrir ses biens

                        <svg class="h-4 w-4" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2"
                             aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                        </svg>

                    </a>

                </div>

            </div>

            <!-- BANDEAU INFÉRIEUR -->
            <div class="relative flex flex-wrap items-center justify-between gap-3 border-t border-white/10 bg-black/10 px-6 py-4 sm:px-10">

                <p class="text-xs font-medium text-[#C7D3DF]">
                    Votre partenaire immobilier au Sénégal
                </p>

                <a href="#biens-agence"
                   class="inline-flex items-center gap-2 text-xs font-extrabold text-[#F5B800] transition hover:text-white">
                    Explorer le catalogue
                    <svg class="h-4 w-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7 7 7 7-7"/>
                    </svg>
                </a>

            </div>

        </div>

    </section>

    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->
    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="flex w-full flex-col gap-8">

            <!-- =================================================
                 À PROPOS
            ================================================== -->
            <section id="description-agence"
                     class="w-full rounded-[28px] border border-[#E8E5DC] bg-white p-6 shadow-[0_12px_40px_rgba(7,27,54,0.045)] sm:p-8">

                <div class="mb-6 flex items-start gap-4">

                    <div class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-[#FFF5D8] text-[#A77C00]">

                        <svg class="h-6 w-6" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.7"
                             aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V5.8A2.8 2.8 0 0 1 6.8 3H20v16.5H6.8A2.8 2.8 0 0 0 4 22V19.5Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8M8 15h5"/>
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#A77C00]">
                            Découvrez-nous
                        </p>

                        <h2 class="mt-1 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                            À propos de l'agence
                        </h2>

                    </div>

                </div>

                <div class="rounded-2xl bg-[#FAFAF8] p-5 sm:p-6">

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
                     class="w-full overflow-hidden rounded-[28px] border border-[#E9E3D3] bg-gradient-to-br from-[#FFF9E9] via-white to-[#F5F3ED] p-6 shadow-[0_12px_40px_rgba(7,27,54,0.045)] sm:p-8">

                <div class="mb-7">

                    <div class="flex items-center gap-3">

                        <div class="h-1 w-9 rounded-full bg-[#F5B800]"></div>

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#A77C00]">
                            Notre accompagnement
                        </p>

                    </div>

                    <h2 class="mt-3 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                        Les services immobiliers
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-[#71869A]">
                        Un accompagnement professionnel adapté à vos projets immobiliers.
                    </p>

                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <?php foreach ($services as $service): ?>

                        <article class="group rounded-2xl border border-[#EAE7DE] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#E7C65B] hover:shadow-[0_14px_35px_rgba(7,27,54,0.08)]">

                            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF5D8] text-[#A77C00] transition duration-300 group-hover:bg-[#101D33] group-hover:text-[#F5B800]">

                                <svg class="h-5 w-5" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.7"
                                     aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="<?= $e($service['icone']) ?>"/>
                                </svg>

                            </div>

                            <h3 class="font-extrabold text-[#101D33]">
                                <?= $e($service['titre']) ?>
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#71869A]">
                                <?= $e($service['texte']) ?>
                            </p>

                            <div class="mt-5 h-1 w-8 rounded-full bg-[#F5B800] transition-all duration-300 group-hover:w-14"></div>

                        </article>

                    <?php endforeach; ?>

                </div>

            </section>

            <!-- =================================================
                 INFORMATIONS GÉNÉRALES
            ================================================== -->
            <section id="informations-agence"
                     class="w-full rounded-[28px] border border-[#E8E5DC] bg-white p-6 shadow-[0_12px_40px_rgba(7,27,54,0.045)] sm:p-8">

                <div class="mb-7">

                    <div class="flex items-center gap-3">

                        <div class="h-1 w-9 rounded-full bg-[#F5B800]"></div>

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#A77C00]">
                            Coordonnées et accès
                        </p>

                    </div>

                    <h2 class="mt-3 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                        Informations générales
                    </h2>

                    <p class="mt-2 text-sm text-[#71869A]">
                        Retrouvez les informations utiles pour contacter et localiser cette agence.
                    </p>

                </div>

                <div class="grid w-full grid-cols-1 items-stretch gap-5 md:grid-cols-2 xl:grid-cols-3">

                    <!-- =========================================
                         COORDONNÉES
                    ========================================== -->
                    <section id="contact-agence"
                             class="rounded-[24px] border border-[#E8E5DC] bg-[#FAFAF8] p-6">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF5D8] text-[#A77C00]">

                            <svg class="h-6 w-6" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.7"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 0 1 5.5 3h2A1.5 1.5 0 0 1 9 4.2l1 3a1.5 1.5 0 0 1-.7 1.7l-1.5.9a14 14 0 0 0 6.4 6.4l.9-1.5a1.5 1.5 0 0 1 1.7-.7l3 1a1.5 1.5 0 0 1 1.2 1.5v2A2.5 2.5 0 0 1 18.5 21 15.5 15.5 0 0 1 3 5.5Z"/>
                            </svg>

                        </div>

                        <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.16em] text-[#A77C00]">
                            Parlons de votre projet
                        </p>

                        <h2 class="mt-2 text-xl font-black text-[#101D33]">
                            Contacter l'agence
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#71869A]">
                            Vous souhaitez visiter un bien ou obtenir des informations ?
                        </p>

                        <div class="mt-6 space-y-5">

                            <!-- TÉLÉPHONE -->
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#A77C00] shadow-sm">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.7"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 0 1 5.5 3h2A1.5 1.5 0 0 1 9 4.2l1 3a1.5 1.5 0 0 1-.7 1.7l-1.5.9a14 14 0 0 0 6.4 6.4l.9-1.5a1.5 1.5 0 0 1 1.7-.7l3 1a1.5 1.5 0 0 1 1.2 1.5v2A2.5 2.5 0 0 1 18.5 21 15.5 15.5 0 0 1 3 5.5Z"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-semibold text-[#71869A]">
                                        Téléphone
                                    </p>

                                    <?php if ($telephone !== ''): ?>

                                        <a
                                            href="tel:<?= $e(preg_replace('/[^0-9+]/', '', $telephone)) ?>"
                                            class="mt-1 block break-words text-sm font-bold text-[#101D33] transition hover:text-[#A77C00]"
                                        >
                                            <?= $e($telephone) ?>
                                        </a>

                                    <?php else: ?>

                                        <p class="mt-1 text-sm text-[#71869A]">
                                            Non renseigné
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>

                            <!-- EMAIL -->
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#A77C00] shadow-sm">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.7"
                                         aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-semibold text-[#71869A]">
                                        Adresse e-mail
                                    </p>

                                    <?php if ($email !== ''): ?>

                                        <a
                                            href="mailto:<?= $e($email) ?>"
                                            class="mt-1 block break-all text-sm font-bold text-[#101D33] transition hover:text-[#A77C00]"
                                        >
                                            <?= $e($email) ?>
                                        </a>

                                    <?php else: ?>

                                        <p class="mt-1 text-sm text-[#71869A]">
                                            Non renseignée
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>

                            <!-- ADRESSE -->
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#A77C00] shadow-sm">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.7"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                        <circle cx="12" cy="9" r="2.2"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-semibold text-[#71869A]">
                                        Adresse
                                    </p>

                                    <p class="mt-1 break-words text-sm font-bold leading-6 text-[#101D33]">
                                        <?= $e($adresse ?: 'Sénégal') ?>
                                    </p>

                                </div>

                            </div>

                        </div>

                        <?php if ($telephone !== '' || $email !== ''): ?>

                            <a
                                href="<?= $e($contactUrl) ?>"
                                class="mt-7 flex w-full items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-3.5 text-sm font-extrabold text-white transition hover:bg-[#1B3654]"
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
                    <section class="rounded-[24px] border border-[#E8E5DC] bg-[#FAFAF8] p-6">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF5D8] text-[#A77C00]">

                            <svg class="h-6 w-6" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.7"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.2"/>
                            </svg>

                        </div>

                        <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.16em] text-[#A77C00]">
                            Nous trouver
                        </p>

                        <h2 class="mt-2 text-xl font-black text-[#101D33]">
                            Localisation
                        </h2>

                        <p class="mt-2 text-sm text-[#71869A]">
                            Adresse de l'agence
                        </p>

                        <div class="relative mt-6 min-h-[150px] overflow-hidden rounded-2xl border border-[#E8E5DC] bg-gradient-to-br from-[#FFF7E1] to-[#F0EEE7] p-5">

                            <div class="absolute -right-5 -top-5 h-28 w-28 rounded-full bg-[#F5B800]/15"></div>

                            <div class="absolute -bottom-10 -left-8 h-32 w-32 rounded-full bg-[#D7A200]/10"></div>

                            <div class="relative">

                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#A77C00] shadow-sm">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.8"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                        <circle cx="12" cy="9" r="2.2"/>
                                    </svg>

                                </div>

                                <p class="break-words text-sm font-bold leading-6 text-[#101D33]">
                                    <?= $e($adresse ?: 'Adresse non renseignée') ?>
                                </p>

                                <p class="mt-2 text-xs font-medium text-[#71869A]">
                                    Sénégal
                                </p>

                            </div>

                        </div>

                        <?php if ($adresse !== ''): ?>

                            <a
                                href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode($adresse . ', Sénégal') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-[#A77C00] transition hover:text-[#071B36]"
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
                    <section class="relative overflow-hidden rounded-[24px] border border-[#E9DDAF] bg-gradient-to-br from-[#FFF8E4] via-[#FFFDF7] to-[#F6F0DF] p-6">

                        <div class="absolute -right-12 -top-12 h-36 w-36 rounded-full bg-[#F5B800]/10"></div>

                        <div class="relative">

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-[#A77C00] shadow-sm">

                                <svg class="h-6 w-6" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.7"
                                     aria-hidden="true">
                                    <rect x="3" y="3" width="6" height="6" rx="1"/>
                                    <rect x="15" y="3" width="6" height="6" rx="1"/>
                                    <rect x="3" y="15" width="6" height="6" rx="1"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 15h3v3h-3zM21 15v2m-3 4h3m0-6v6"/>
                                </svg>

                            </div>

                            <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.16em] text-[#A77C00]">
                                Accès rapide
                            </p>

                            <h2 class="mt-2 text-xl font-black text-[#101D33]">
                                QR Codes de l'agence
                            </h2>

                            <p class="mt-3 text-sm leading-6 text-[#647386]">
                                Retrouvez les QR Codes associés aux biens de cette agence et partagez facilement vos annonces.
                            </p>

                            <div class="mt-5 flex items-center gap-3 rounded-xl border border-[#E9DDAF] bg-white/80 p-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#FFF5D8] text-[#A77C00]">

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
                                class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#071B36] px-4 py-3.5 text-sm font-extrabold text-white transition hover:bg-[#1B3654]"
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
                     class="w-full rounded-[28px] border border-[#E8E5DC] bg-white p-6 shadow-[0_12px_40px_rgba(7,27,54,0.045)] sm:p-8">

                <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

                    <div>

                        <div class="flex items-center gap-3">

                            <div class="h-1 w-9 rounded-full bg-[#F5B800]"></div>

                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#A77C00]">
                                Catalogue immobilier
                            </p>

                        </div>

                        <h2 class="mt-3 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                            Les biens de l'agence
                        </h2>

                        <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#FFF5D8] px-3 py-1.5 text-xs font-bold text-[#8A6500]">

                            <span class="h-1.5 w-1.5 rounded-full bg-[#D7A200]"></span>

                            <?= $nombreBiens ?>
                            bien<?= $nombreBiens > 1 ? 's' : '' ?>
                            disponible<?= $nombreBiens > 1 ? 's' : '' ?>

                        </div>

                    </div>

                    <?php if ($nombreBiens > 3): ?>

                        <a
                            href="/biens?agence=<?= urlencode((string) $idAgence) ?>"
                            class="inline-flex items-center gap-2 text-sm font-extrabold text-[#A77C00] transition hover:text-[#071B36]"
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

                            <article class="group overflow-hidden rounded-[24px] border border-[#E8E5DC] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#E5C65C] hover:shadow-[0_18px_45px_rgba(7,27,54,0.11)]">

                                <!-- PHOTO -->
                                <div class="relative overflow-hidden bg-[#F0EEE7]">

                                    <?php if ($photo !== ''): ?>

                                        <img
                                            src="<?= $e($photo) ?>"
                                            alt="<?= $e($titreBien) ?>"
                                            class="h-56 w-full object-cover transition duration-700 group-hover:scale-105"
                                            loading="lazy"
                                        >

                                    <?php else: ?>

                                        <div class="flex h-56 items-center justify-center bg-gradient-to-br from-[#FFF8E4] to-[#EDEBE3] text-[#A77C00]">

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
                                    <span class="absolute left-4 top-4 rounded-lg px-3 py-2 text-xs font-extrabold shadow-sm <?= $isVente ? 'bg-[#F5B800] text-[#071B36]' : 'bg-[#071B36] text-white' ?>">
                                        <?= $e($typeOffre) ?>
                                    </span>

                                </div>

                                <!-- CONTENU -->
                                <div class="p-5">

                                    <p class="flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-[#A77C00]">

                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2"
                                             aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                            <circle cx="12" cy="9" r="2"/>
                                        </svg>

                                        <?= $e($ville) ?>

                                        <?php if ($quartier !== ''): ?>
                                            <span class="text-[#B8C1CA]">·</span>
                                            <?= $e($quartier) ?>
                                        <?php endif; ?>

                                    </p>

                                    <h3 class="mt-3 line-clamp-2 min-h-12 font-extrabold leading-6 text-[#101D33] transition group-hover:text-[#A77C00]">
                                        <?= $e($titreBien) ?>
                                    </h3>

                                    <div class="mt-5 flex items-end justify-between gap-3 border-t border-[#EAE7DE] pt-4">

                                        <div class="min-w-0">

                                            <p class="text-xs text-[#71869A]">
                                                Prix annoncé
                                            </p>

                                            <p class="mt-1 text-xl font-black text-[#101D33]">

                                                <?= number_format($prix, 0, ',', ' ') ?>

                                                <span class="text-xs font-bold text-[#A77C00]">
                                                    FCFA
                                                </span>

                                            </p>

                                        </div>

                                        <a
                                            href="/biens?agence=<?= urlencode((string) $idAgence) ?>"
                                            class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#FFF5D8] text-[#A77C00] transition hover:bg-[#071B36] hover:text-[#F5B800]"
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
                                href="/biens?agence=<?= urlencode((string) $idAgence) ?>"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#071B36] px-7 py-4 text-sm font-extrabold text-white shadow-lg shadow-[#071B36]/10 transition hover:bg-[#1B3654]"
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
                    <div class="rounded-[24px] border border-dashed border-[#E2D6B5] bg-[#FFFCF4] px-6 py-14 text-center">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[24px] bg-[#FFF5D8] text-[#A77C00]">

                            <svg class="h-9 w-9" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 11 12 3l9 8M5 9.5V21h14V9.5M9 21v-7h6v7"/>
                            </svg>

                        </div>

                        <h3 class="mt-6 text-lg font-black text-[#101D33]">
                            Aucun bien disponible pour le moment
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-[#71869A]">
                            Les annonces de cette agence apparaîtront ici lorsqu'elles seront disponibles.
                            N'hésitez pas à revenir consulter son catalogue.
                        </p>

                        <a
                            href="/biens"
                            class="mt-7 inline-flex items-center justify-center gap-2 rounded-xl bg-[#071B36] px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-[#1B3654]"
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
         PIED DE PAGE
    ====================================================== -->
    <footer class="mt-6 border-t border-[#E8E5DC] bg-white">

        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">

            <a href="/" class="text-xl font-black tracking-tight text-[#071B36]">
                IMMO<span class="text-[#D7A200]">-SN</span>
            </a>

            <p class="text-sm text-[#71869A]">
                Votre partenaire immobilier au Sénégal.
            </p>

            <a
                href="/agences"
                class="inline-flex items-center gap-2 text-sm font-extrabold text-[#A77C00] transition hover:text-[#071B36]"
            >

                Toutes les agences

                <svg class="h-4 w-4" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                </svg>

            </a>

        </div>

        <div class="border-t border-[#F0EEE7] py-4 text-center text-xs text-[#9AA5B1]">
            © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
        </div>

    </footer>

</div>
