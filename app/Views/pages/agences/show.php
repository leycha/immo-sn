
<?php
/**
 * Détail d'une agence — IMMO-SN
 *
 * Variables attendues :
 * - $agence : tableau contenant les données de l'agence
 * - $biens  : tableau contenant les biens de l'agence
 */

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

$biens        = $biens ?? [];
$nombreBiens  = count($biens);

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
?>

<div class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

    <!-- FIL D'ARIANE -->
    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">

        <nav class="flex flex-wrap items-center gap-2 text-sm text-[#71869A]" aria-label="Fil d’Ariane">

            <a href="/" class="transition hover:text-[#7E9833]">
                Accueil
            </a>

            <span>/</span>

            <a href="/agences" class="transition hover:text-[#7E9833]">
                Agences
            </a>

            <span>/</span>

            <span class="max-w-[220px] truncate font-semibold text-[#101D33]">
                <?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>
            </span>

        </nav>

    </div>

    <!-- BANNIÈRE DE L'AGENCE -->
    <section class="mx-auto max-w-7xl px-4 pt-5 sm:px-6 lg:px-8">

        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-br from-[#101D33] via-[#1A3048] to-[#263F50] shadow-[0_20px_60px_rgba(16,29,51,0.16)]">

            <div class="pointer-events-none absolute -right-20 -top-32 h-80 w-80 rounded-full bg-[#7E9833]/20 blur-3xl"></div>

            <div class="pointer-events-none absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#8FAF35]/10 blur-3xl"></div>

            <div class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-[1fr_auto] lg:items-center lg:p-10">

                <div class="flex flex-col gap-6 sm:flex-row sm:items-center">

                    <!-- LOGO -->
                    <div class="flex h-28 w-28 shrink-0 items-center justify-center self-start rounded-[25px] border border-white/20 bg-white p-2 shadow-xl sm:h-32 sm:w-32">

                        <?php if ($logo !== ''): ?>

                            <img
                                src="<?= htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') ?>"
                                alt="Logo de <?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>"
                                class="h-full w-full rounded-[18px] object-contain"
                                loading="eager"
                            >

                        <?php else: ?>

                            <div class="flex h-full w-full items-center justify-center rounded-[18px] bg-[#F5F3ED] text-4xl font-black text-[#7E9833]">
                                <?= htmlspecialchars($initiale, ENT_QUOTES, 'UTF-8') ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- IDENTITÉ -->
                    <div class="min-w-0 flex-1">

                        <div class="mb-3 flex flex-wrap items-center gap-2">

                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-semibold text-[#E2E9D2]">

                                <span class="h-2 w-2 rounded-full bg-[#8FAF35]"></span>

                                Agence immobilière

                            </span>

                            <?php if ($badge): ?>

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#8FAF35] px-3 py-1.5 text-xs font-extrabold text-[#101D33]">

                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 1.5 12.3 3l2.8.1 1.1 2.5 2.1 1.8-.8 2.7.5 2.7-2.4 1.5-1.3 2.5-2.8-.3L10 18l-2.5-1.5-2.8.3-1.3-2.5L1 12.8l.5-2.7-.8-2.7 2.1-1.8 1.1-2.5 2.8-.1L10 1.5Zm3.2 6.2a.75.75 0 0 0-1.1-1l-3.1 3.4-1.2-1.2a.75.75 0 0 0-1.1 1l1.8 1.8a.75.75 0 0 0 1.1 0l3.6-4Z" clip-rule="evenodd"/>
                                    </svg>

                                    Agence vérifiée

                                </span>

                            <?php endif; ?>

                        </div>

                        <h1 class="break-words text-2xl font-black tracking-tight text-white sm:text-3xl lg:text-4xl">
                            <?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>
                        </h1>

                        <p class="mt-3 flex items-start gap-2 text-sm leading-6 text-[#D5DFE7] sm:text-base">

                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#A8C76A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.2"/>
                            </svg>

                            <span class="min-w-0 break-words">
                                <?= htmlspecialchars($adresse ?: 'Sénégal', ENT_QUOTES, 'UTF-8') ?>
                            </span>

                        </p>

                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-[#B8C7D2] sm:text-sm">

                            <?php if ($ninea !== ''): ?>

                                <span>
                                    NINEA : <?= htmlspecialchars($ninea, ENT_QUOTES, 'UTF-8') ?>
                                </span>

                            <?php endif; ?>

                            <span>
                                <?= htmlspecialchars($dateFormatee, ENT_QUOTES, 'UTF-8') ?>
                            </span>

                        </div>

                    </div>

                </div>

                <!-- ACTIONS -->
                <div class="flex flex-col gap-3 sm:flex-row lg:min-w-[190px] lg:flex-col">

                    <a
                        href="<?= htmlspecialchars($contactUrl, ENT_QUOTES, 'UTF-8') ?>"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#8FAF35] px-5 py-3.5 text-sm font-extrabold text-[#101D33] shadow-lg shadow-black/10 transition hover:bg-[#A6C65B]"
                    >

                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 0 1 5.5 3h2A1.5 1.5 0 0 1 9 4.2l1 3a1.5 1.5 0 0 1-.7 1.7l-1.5.9a14 14 0 0 0 6.4 6.4l.9-1.5a1.5 1.5 0 0 1 1.7-.7l3 1a1.5 1.5 0 0 1 1.2 1.5v2A2.5 2.5 0 0 1 18.5 21 15.5 15.5 0 0 1 3 5.5Z"/>
                        </svg>

                        Contacter l'agence

                    </a>

                    <a
                        href="/biens?agence=<?= urlencode($idAgence) ?>"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/5 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-white/10"
                    >

                        Découvrir ses biens

                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                        </svg>

                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- CONTENU PRINCIPAL -->
    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="flex w-full flex-col gap-8">

            <!-- À PROPOS -->
            <section id="description-agence" class="w-full rounded-[28px] border border-[#E3E5DC] bg-white p-6 shadow-[0_16px_45px_rgba(16,29,51,0.05)] sm:p-8">

                <div class="mb-6 flex items-start gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#EEF2E4] text-[#7E9833]">

                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V5.8A2.8 2.8 0 0 1 6.8 3H20v16.5H6.8A2.8 2.8 0 0 0 4 22V19.5Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8M8 15h5"/>
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#7E9833]">
                            Découvrez-nous
                        </p>

                        <h2 class="mt-1 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                            À propos de l'agence
                        </h2>

                    </div>

                </div>

                <p class="whitespace-pre-line text-sm leading-8 text-[#647386] sm:text-base">
                    <?= htmlspecialchars(
                        $description ?: 'Cette agence accompagne ses clients dans leurs projets immobiliers au Sénégal.',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

            </section>

            <!-- SERVICES -->
            <section id="services-agence" class="w-full rounded-[28px] border border-[#E0E5D5] bg-gradient-to-br from-[#F0F3E9] via-white to-[#F5F3ED] p-6 shadow-[0_16px_45px_rgba(16,29,51,0.05)] sm:p-8">

                <div class="mb-5">

                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#7E9833]">
                        Notre accompagnement
                    </p>

                    <h2 class="mt-1 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                        Les services immobiliers
                    </h2>

                    <p class="mt-2 text-sm text-[#71869A]">
                        Un accompagnement adapté à vos projets.
                    </p>

                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <?php
                    $services = [
                        [
                            'titre' => 'Vente immobilière',
                            'texte' => 'Découvrez les biens proposés à la vente.',
                            'icone' => 'M3 10.5 12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6'
                        ],
                        [
                            'titre' => 'Location',
                            'texte' => 'Trouvez un logement adapté à vos besoins.',
                            'icone' => 'M4 21V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16M2 21h20M8 7h2m4 0h2M8 11h2m4 0h2M10 21v-5h4v5'
                        ],
                        [
                            'titre' => 'Conseil immobilier',
                            'texte' => 'Faites-vous accompagner dans votre projet.',
                            'icone' => 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 5v4l3 2'
                        ],
                        [
                            'titre' => 'Gestion de biens',
                            'texte' => 'Un accompagnement pour vos biens immobiliers.',
                            'icone' => 'M4 7h16M4 12h16M4 17h10M3 4h18v16H3z'
                        ]
                    ];
                    ?>

                    <?php foreach ($services as $service): ?>

                        <article class="group rounded-2xl border border-[#E3E7DC] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#B8C98D] hover:shadow-lg">

                            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#EEF2E4] text-[#7E9833] transition group-hover:bg-[#101D33] group-hover:text-[#A8C76A]">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="<?= htmlspecialchars($service['icone'], ENT_QUOTES, 'UTF-8') ?>"/>
                                </svg>

                            </div>

                            <h3 class="font-extrabold text-[#101D33]">
                                <?= htmlspecialchars($service['titre'], ENT_QUOTES, 'UTF-8') ?>
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#71869A]">
                                <?= htmlspecialchars($service['texte'], ENT_QUOTES, 'UTF-8') ?>
                            </p>

                        </article>

                    <?php endforeach; ?>

                </div>

            </section>

            <!-- INFORMATIONS GÉNÉRALES -->
            <section id="informations-agence" class="w-full rounded-[28px] border border-[#E3E5DC] bg-white p-6 shadow-[0_16px_45px_rgba(16,29,51,0.05)] sm:p-8">

                <div class="mb-6">

                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#7E9833]">
                        Coordonnées et accès
                    </p>

                    <h2 class="mt-1 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                        Informations générales
                    </h2>

                    <p class="mt-2 text-sm text-[#71869A]">
                        Retrouvez les informations utiles pour contacter et localiser cette agence.
                    </p>

                </div>

                <!-- CONTACT, LOCALISATION ET QR CODES -->
                <div class="grid w-full grid-cols-1 items-stretch gap-6 md:grid-cols-2 xl:grid-cols-3">

                    <!-- COORDONNÉES -->
                    <section id="contact-agence" class="rounded-[24px] border border-[#E3E5DC] bg-[#FAFBF8] p-6">

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#7E9833]">
                            Parlons de votre projet
                        </p>

                        <h2 class="mt-2 text-xl font-black text-[#101D33]">
                            Contacter l'agence
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#71869A]">
                            Vous souhaitez visiter un bien ou obtenir des informations ?
                        </p>

                        <div class="mt-6 space-y-4">

                            <!-- TÉLÉPHONE -->
                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EEF2E4] text-[#7E9833]">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 0 1 5.5 3h2A1.5 1.5 0 0 1 9 4.2l1 3a1.5 1.5 0 0 1-.7 1.7l-1.5.9a14 14 0 0 0 6.4 6.4l.9-1.5a1.5 1.5 0 0 1 1.7-.7l3 1a1.5 1.5 0 0 1 1.2 1.5v2A2.5 2.5 0 0 1 18.5 21 15.5 15.5 0 0 1 3 5.5Z"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-semibold text-[#71869A]">
                                        Téléphone
                                    </p>

                                    <?php if ($telephone !== ''): ?>

                                        <a
                                            href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $telephone), ENT_QUOTES, 'UTF-8') ?>"
                                            class="mt-1 block break-words text-sm font-bold text-[#101D33] hover:text-[#7E9833]"
                                        >
                                            <?= htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8') ?>
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

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EEF2E4] text-[#7E9833]">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
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
                                            href="mailto:<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                                            class="mt-1 block break-all text-sm font-bold text-[#101D33] hover:text-[#7E9833]"
                                        >
                                            <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>
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

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EEF2E4] text-[#7E9833]">

                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                        <circle cx="12" cy="9" r="2.2"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-semibold text-[#71869A]">
                                        Adresse
                                    </p>

                                    <p class="mt-1 break-words text-sm font-bold leading-6 text-[#101D33]">
                                        <?= htmlspecialchars($adresse ?: 'Sénégal', ENT_QUOTES, 'UTF-8') ?>
                                    </p>

                                </div>

                            </div>

                        </div>

                        <?php if ($telephone !== '' || $email !== ''): ?>

                            <a
                                href="<?= htmlspecialchars($contactUrl, ENT_QUOTES, 'UTF-8') ?>"
                                class="mt-6 flex w-full items-center justify-center rounded-xl bg-[#101D33] px-5 py-3.5 text-sm font-extrabold text-white transition hover:bg-[#243B58]"
                            >
                                Prendre contact
                            </a>

                        <?php endif; ?>

                    </section>

                    <!-- LOCALISATION -->
                    <section class="rounded-[24px] border border-[#E3E5DC] bg-[#FAFBF8] p-6">

                        <div class="mb-4 flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#EEF2E4] text-[#7E9833]">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                    <circle cx="12" cy="9" r="2.2"/>
                                </svg>

                            </div>

                            <div>

                                <h2 class="font-black text-[#101D33]">
                                    Localisation
                                </h2>

                                <p class="mt-1 text-xs text-[#71869A]">
                                    Adresse de l'agence
                                </p>

                            </div>

                        </div>

                        <div class="relative overflow-hidden rounded-2xl border border-[#E3E5DC] bg-[#EEF2E4] p-5">

                            <div class="absolute -right-5 -top-5 h-24 w-24 rounded-full bg-[#7E9833]/10"></div>

                            <div class="relative">

                                <p class="break-words text-sm font-bold leading-6 text-[#101D33]">
                                    <?= htmlspecialchars($adresse ?: 'Adresse non renseignée', ENT_QUOTES, 'UTF-8') ?>
                                </p>

                                <p class="mt-2 text-xs leading-5 text-[#71869A]">
                                    Sénégal
                                </p>

                            </div>

                        </div>

                        <?php if ($adresse !== ''): ?>

                            <a
                                href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode($adresse . ', Sénégal') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-4 inline-flex items-center gap-2 text-sm font-extrabold text-[#6D872D] transition hover:text-[#101D33]"
                            >

                                Ouvrir dans Google Maps

                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 4h6v6m-11 4L20 4M18 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6"/>
                                </svg>

                            </a>

                        <?php endif; ?>

                    </section>

                    <!-- QR CODES -->
                    <section class="rounded-[24px] border border-[#DCE4CC] bg-[#EEF2E4] p-6">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-[#7E9833]">

                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <rect x="3" y="3" width="6" height="6" rx="1"/>
                                <rect x="15" y="3" width="6" height="6" rx="1"/>
                                <rect x="3" y="15" width="6" height="6" rx="1"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 15h3v3h-3zM21 15v2m-3 4h3m0-6v6"/>
                            </svg>

                        </div>

                        <h2 class="mt-4 font-black text-[#101D33]">
                            QR Codes de l'agence
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#647386]">
                            Accédez aux QR Codes associés aux biens de cette agence.
                        </p>

                        <a
                            href="/agences/<?= urlencode($idAgence) ?>/qr-zip"
                            class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-[#B8C98F] bg-white px-4 py-3 text-sm font-extrabold text-[#526A25] transition hover:bg-[#101D33] hover:text-white"
                        >
                            Télécharger le ZIP
                        </a>

                    </section>

                </div>

            </section>

            <!-- BIENS DE L'AGENCE -->
            <section id="biens-agence" class="w-full rounded-[28px] border border-[#E3E5DC] bg-white p-6 shadow-[0_16px_45px_rgba(16,29,51,0.05)] sm:p-8">

                <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

                    <div>

                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#7E9833]">
                            Catalogue immobilier
                        </p>

                        <h2 class="mt-1 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                            Les biens de l'agence
                        </h2>

                        <p class="mt-2 text-sm text-[#71869A]">
                            <?= $nombreBiens ?>
                            bien<?= $nombreBiens > 1 ? 's' : '' ?> disponible<?= $nombreBiens > 1 ? 's' : '' ?>
                        </p>

                    </div>

                    <?php if ($nombreBiens > 3): ?>

                        <a
                            href="/biens?agence=<?= urlencode($idAgence) ?>"
                            class="inline-flex items-center gap-2 text-sm font-extrabold text-[#6D872D] transition hover:text-[#101D33]"
                        >

                            Voir tous les biens

                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
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

                            <article class="group overflow-hidden rounded-[22px] border border-[#E3E5DC] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                                <div class="relative overflow-hidden bg-[#EEF2E4]">

                                    <?php if ($photo !== ''): ?>

                                        <img
                                            src="<?= htmlspecialchars($photo, ENT_QUOTES, 'UTF-8') ?>"
                                            alt="<?= htmlspecialchars($titreBien, ENT_QUOTES, 'UTF-8') ?>"
                                            class="h-56 w-full object-cover transition duration-500 group-hover:scale-105"
                                            loading="lazy"
                                        >

                                    <?php else: ?>

                                        <div class="flex h-56 items-center justify-center bg-[#EEF2E4] text-[#7E9833]">

                                            <div class="text-center">

                                                <svg class="mx-auto h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 11 9-8 9 8M5 9.5V21h14V9.5M9 21v-7h6v7"/>
                                                </svg>

                                                <p class="mt-2 text-xs font-semibold">
                                                    Photo non disponible
                                                </p>

                                            </div>

                                        </div>

                                    <?php endif; ?>

                                    <span class="absolute left-3 top-3 rounded-lg px-3 py-1.5 text-xs font-extrabold <?= $isVente ? 'bg-[#8FAF35] text-[#101D33]' : 'bg-[#101D33] text-white' ?>">
                                        <?= htmlspecialchars($typeOffre, ENT_QUOTES, 'UTF-8') ?>
                                    </span>

                                </div>

                                <div class="p-5">

                                    <p class="text-xs font-semibold uppercase tracking-wider text-[#7E9833]">

                                        <?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>

                                        <?php if ($quartier !== ''): ?>
                                            · <?= htmlspecialchars($quartier, ENT_QUOTES, 'UTF-8') ?>
                                        <?php endif; ?>

                                    </p>

                                    <h3 class="mt-2 line-clamp-2 min-h-12 font-extrabold leading-6 text-[#101D33]">
                                        <?= htmlspecialchars($titreBien, ENT_QUOTES, 'UTF-8') ?>
                                    </h3>

                                    <div class="mt-4 flex items-end justify-between gap-3 border-t border-[#E3E5DC] pt-4">

                                        <div>

                                            <p class="text-xs text-[#71869A]">
                                                Prix annoncé
                                            </p>

                                            <p class="mt-1 text-lg font-black text-[#526A25]">

                                                <?= number_format($prix, 0, ',', ' ') ?>

                                                <span class="text-xs font-bold">
                                                    FCFA
                                                </span>

                                            </p>

                                        </div>

                                        <a
                                            href="/biens?agence=<?= urlencode($idAgence) ?>"
                                            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EEF2E4] text-[#526A25] transition hover:bg-[#101D33] hover:text-white"
                                            aria-label="Découvrir les biens de l'agence"
                                        >

                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                                            </svg>

                                        </a>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                    <?php if ($nombreBiens > 3): ?>

                        <div class="mt-7 text-center">

                            <a
                                href="/biens?agence=<?= urlencode($idAgence) ?>"
                                class="inline-flex items-center justify-center rounded-xl bg-[#101D33] px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-[#243B58]"
                            >
                                Voir les <?= $nombreBiens ?> biens
                            </a>

                        </div>

                    <?php endif; ?>

                <?php else: ?>

                    <div class="rounded-[24px] border border-dashed border-[#C9D4B2] bg-[#FAFBF8] px-6 py-12 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EEF2E4] text-[#7E9833]">

                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 11 12 3l9 8M5 9.5V21h14V9.5M9 21v-7h6v7"/>
                            </svg>

                        </div>

                        <h3 class="mt-5 text-lg font-black text-[#101D33]">
                            Aucun bien disponible pour le moment
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#71869A]">
                            Les annonces de cette agence apparaîtront ici lorsqu'elles seront disponibles.
                        </p>

                        <a
                            href="/biens"
                            class="mt-6 inline-flex items-center justify-center rounded-xl bg-[#101D33] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#243B58]"
                        >
                            Explorer les biens
                        </a>

                    </div>

                <?php endif; ?>

            </section>

        </div>

    </main>

    <!-- PIED DE PAGE -->
    <footer class="border-t border-[#E3E5DC] bg-white">

        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-7 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">

            <a href="/" class="text-lg font-black tracking-tight text-[#101D33]">
                IMMO<span class="text-[#7E9833]">-SN</span>
            </a>

            <p class="text-[#71869A]">
                Votre partenaire immobilier au Sénégal.
            </p>

            <a
                href="/agences"
                class="font-bold text-[#6D872D] transition hover:text-[#101D33]"
            >
                Toutes les agences
            </a>

        </div>

    </footer>

</div>
