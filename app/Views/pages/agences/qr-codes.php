
<?php

$nomAgence = $agence['nom_agence'] ?? 'Mon agence';

$qrCodes = is_array($qrCodes ?? null) ? $qrCodes : [];

$statuts = [
    'actif' => ['Actif', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
    'en_attente' => ['En attente', 'bg-amber-50 text-amber-700 border-amber-200'],
    'pending' => ['En attente', 'bg-amber-50 text-amber-700 border-amber-200'],
    'rejete' => ['Rejeté', 'bg-red-50 text-red-700 border-red-200'],
    'refuse' => ['Refusé', 'bg-red-50 text-red-700 border-red-200'],
    'refusee' => ['Refusé', 'bg-red-50 text-red-700 border-red-200'],
    'expire' => ['Expiré', 'bg-gray-100 text-gray-700 border-gray-200'],
    'brouillon' => ['Brouillon', 'bg-blue-50 text-blue-700 border-blue-200'],
];

$nombreQrCodes = count($qrCodes);

$totalScans = array_sum(array_map(
    static fn($qr) => (int) ($qr['nb_scans'] ?? 0),
    $qrCodes
));

$elementsParPage = 6;

$pageActuelle = max(1, (int) ($_GET['page'] ?? 1));

$totalPages = max(1, (int) ceil($nombreQrCodes / $elementsParPage));

$pageActuelle = min($pageActuelle, $totalPages);

$debut = ($pageActuelle - 1) * $elementsParPage;

$qrCodesPage = array_slice($qrCodes, $debut, $elementsParPage);

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

?>

<div class="min-h-screen bg-[#F5F3ED]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- EN-TÊTE -->
        <header class="sticky top-0 z-30 border-b border-[#E5E5DD] bg-white/95 backdrop-blur-xl">

            <div class="flex min-h-[82px] flex-col justify-center gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                <div>
                    <div class="mb-1 flex items-center gap-2 text-xs">
                        <span class="font-bold text-[#C89A00]">Espace agence</span>
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-500">Mes QR Codes</span>
                    </div>

                    <h1 class="text-2xl font-extrabold tracking-tight text-[#071B36] sm:text-3xl">
                        Mes QR Codes
                    </h1>

                    <p class="mt-1 text-sm text-[#718096]">
                        Gérez les QR Codes associés à vos annonces immobilières.
                    </p>
                </div>

                <a
                    href="/immo-sn/agence/qr-codes/zip"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#0E2A4E] hover:shadow-md"
                >
                    <svg class="h-5 w-5 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v3h16v-3"/>
                    </svg>
                    Télécharger tout
                </a>

            </div>

        </header>


        <!-- CONTENU PRINCIPAL -->
        <div class="mx-auto max-w-7xl space-y-8 px-5 py-8 sm:px-8">


            <!-- BANNIÈRE INTRODUCTIVE -->
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#071B36] via-[#102C4C] to-[#1D3854] p-7 text-white shadow-lg sm:p-10">

                <div class="pointer-events-none absolute -right-16 -top-20 h-72 w-72 rounded-full border-[38px] border-[#F5B800]/10"></div>

                <div class="pointer-events-none absolute -bottom-28 right-36 h-60 w-60 rounded-full bg-[#F5B800]/10 blur-3xl"></div>

                <div class="pointer-events-none absolute right-12 top-12 hidden opacity-[0.08] md:block">
                    <svg class="h-40 w-40" viewBox="0 0 100 100" fill="none">
                        <path d="M10 10H90V90H10V10Z" stroke="white" stroke-width="2"/>
                        <path d="M25 25H75V75H25V25Z" stroke="white" stroke-width="2"/>
                        <path d="M38 38H62V62H38V38Z" stroke="white" stroke-width="2"/>
                    </svg>
                </div>

                <div class="relative z-10 max-w-2xl">

                    <div class="inline-flex items-center gap-2 rounded-full border border-[#F5B800]/25 bg-[#F5B800]/10 px-4 py-2 text-xs font-bold tracking-wide text-[#F5D35B]">
                        <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>
                        BIBLIOTHÈQUE NUMÉRIQUE
                    </div>

                    <h2 class="mt-5 break-words text-2xl font-extrabold leading-tight sm:text-3xl md:text-4xl">
                        Vos biens, accessibles en un scan.
                    </h2>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-[#D8E0E8] sm:text-base">
                        Retrouvez tous les QR Codes de vos annonces.
                        Facilitez l'accès à vos biens et suivez les consultations
                        enregistrées grâce aux scans.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">

                        <span class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-3 py-2.5 text-xs font-semibold text-white/90">
                            <svg class="h-4 w-4 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v3h16v-3"/>
                            </svg>
                            Téléchargement individuel
                        </span>

                        <span class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-3 py-2.5 text-xs font-semibold text-white/90">
                            <svg class="h-4 w-4 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <path stroke-linecap="round" d="M14 14h3v3h-3zM20 14v2m-3 5h4v-4"/>
                            </svg>
                            Archive ZIP
                        </span>

                        <span class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-3 py-2.5 text-xs font-semibold text-white/90">
                            <svg class="h-4 w-4 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 14l4-4 4 4 6-7"/>
                            </svg>
                            Suivi des scans
                        </span>

                    </div>

                </div>

            </section>


            <!-- STATISTIQUES -->
            <section class="grid gap-5 sm:grid-cols-2">

                <!-- TOTAL QR CODES -->
                <article class="group relative overflow-hidden rounded-2xl border border-[#E5E5DD] bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <p class="text-sm font-semibold text-[#718096]">
                                QR Codes disponibles
                            </p>

                            <p class="mt-3 text-4xl font-extrabold tracking-tight text-[#071B36]">
                                <?= $nombreQrCodes ?>
                            </p>

                            <p class="mt-2 text-xs text-gray-500">
                                QR Codes associés à vos biens
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#F5B800]/10 text-[#C89A00] transition group-hover:bg-[#F5B800]/20">

                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 14h3v3h-3zM20 14v2m-3 5h4v-4"/>
                            </svg>

                        </div>

                    </div>

                    <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#F5B800] to-[#E6D18B]"></div>

                </article>


                <!-- TOTAL SCANS -->
                <article class="group relative overflow-hidden rounded-2xl border border-[#E5E5DD] bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <p class="text-sm font-semibold text-[#718096]">
                                Nombre total de scans
                            </p>

                            <p class="mt-3 text-4xl font-extrabold tracking-tight text-[#071B36]">
                                <?= $totalScans ?>
                            </p>

                            <p class="mt-2 text-xs text-gray-500">
                                Consultations enregistrées
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#EDF1F5] text-[#71869A] transition group-hover:bg-[#E1E9F0]">

                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 6h6v6"/>
                            </svg>

                        </div>

                    </div>

                    <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#071B36] to-[#71869A]"></div>

                </article>

            </section>


            <!-- BIBLIOTHÈQUE DES QR CODES -->
            <section class="space-y-6">

                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#C89A00]">
                            Gestion des annonces
                        </p>

                        <h3 class="mt-2 text-xl font-extrabold text-[#071B36] sm:text-2xl">
                            Bibliothèque de mes QR Codes
                        </h3>

                        <p class="mt-2 text-sm text-[#718096]">
                            Consultez, suivez et téléchargez les QR Codes de vos biens.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center gap-2 rounded-xl border border-[#F5B800]/30 bg-[#F5B800]/10 px-4 py-2.5 text-sm font-bold text-[#806000]">

                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                        </svg>

                        <?= $nombreQrCodes ?> QR Code(s)

                    </span>

                </div>


                <?php if (empty($qrCodes)): ?>

                    <!-- ÉTAT VIDE -->
                    <div class="rounded-3xl border border-[#E5E5DD] bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[#F5B800]/10 text-[#C89A00]">

                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 14h3v3h-3zM20 14v2m-3 5h4v-4"/>
                            </svg>

                        </div>

                        <h3 class="mt-6 text-xl font-extrabold text-[#071B36]">
                            Aucun QR Code disponible
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-[#718096]">
                            Aucun QR Code n'est actuellement associé à vos biens.
                            Dès qu'un QR Code sera disponible, vous pourrez le retrouver ici.
                        </p>

                        <a
                            href="/immo-sn/agence/biens"
                            class="mt-7 inline-flex items-center justify-center gap-2 rounded-xl bg-[#071B36] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#0E2A4E]"
                        >
                            Consulter mes biens

                            <svg class="h-4 w-4 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7l7 7-7 7"/>
                            </svg>
                        </a>

                    </div>


                <?php else: ?>

                    <!-- GRILLE DES QR CODES -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($qrCodesPage as $qr): ?>

                            <?php

                            $statut = strtolower(trim((string) ($qr['statut'] ?? 'inconnu')));

                            [$label, $style] = $statuts[$statut] ?? [
                                ucfirst(str_replace('_', ' ', $statut)),
                                'bg-gray-100 text-gray-700 border-gray-200'
                            ];

                            $cheminQr = str_replace(
                                '\\',
                                '/',
                                (string) ($qr['file_path'] ?? '')
                            );

                            $cheminImage = '/immo-sn/' . ltrim($cheminQr, '/');

                            $dernierScan = $qr['last_scan_at'] ?? null;

                            $nomFichier = 'QR_' . preg_replace(
                                '/[^a-zA-Z0-9_-]/',
                                '_',
                                (string) ($qr['bien_id'] ?? 'bien')
                            ) . '.png';

                            ?>

                            <article class="group flex flex-col overflow-hidden rounded-3xl border border-[#E5E5DD] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">


                                <!-- EN-TÊTE DE LA CARTE -->
                                <div class="flex items-start justify-between gap-3 p-5">

                                    <div class="min-w-0">

                                        <div class="flex items-center gap-2">

                                            <span class="h-2 w-2 shrink-0 rounded-full bg-[#F5B800]"></span>

                                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-[#B58B00]">
                                                Bien immobilier
                                            </p>

                                        </div>

                                        <h4 class="mt-3 line-clamp-2 break-words text-base font-extrabold leading-6 text-[#071B36]">
                                            <?= $e($qr['titre'] ?? 'Bien sans titre') ?>
                                        </h4>

                                        <p class="mt-2 text-xs text-[#8A96A3]">
                                            Référence : <?= $e($qr['bien_id'] ?? '—') ?>
                                        </p>

                                    </div>

                                    <span class="shrink-0 rounded-full border px-3 py-1.5 text-[11px] font-bold <?= $style ?>">
                                        <?= $e($label) ?>
                                    </span>

                                </div>


                                <!-- IMAGE QR CODE -->
                                <div class="mx-5 flex flex-col items-center rounded-2xl border border-[#E5E5DD] bg-[#F8F7F2] px-4 py-6">

                                    <div class="flex h-44 w-44 items-center justify-center rounded-2xl border border-[#E5E5DD] bg-white p-3 shadow-sm transition duration-300 group-hover:border-[#F5B800]/50 group-hover:shadow-md">

                                        <img
                                            src="<?= $e($cheminImage) ?>"
                                            alt="QR Code de <?= $e($qr['titre'] ?? 'ce bien') ?>"
                                            class="h-full w-full object-contain"
                                            loading="lazy"
                                        >

                                    </div>

                                    <p class="mt-4 flex items-center gap-2 text-xs font-medium text-[#718096]">

                                        <svg class="h-4 w-4 text-[#C89A00]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h3v3h-3zM20 14v2m-3 5h4v-4"/>
                                        </svg>

                                        Scannez pour accéder à l'annonce

                                    </p>

                                </div>


                                <!-- STATISTIQUES DE LA CARTE -->
                                <div class="mt-5 grid grid-cols-2 divide-x divide-[#E5E5DD] border-y border-[#E5E5DD]">

                                    <div class="px-4 py-4 text-center">

                                        <div class="flex items-center justify-center gap-2 text-[#8A96A3]">

                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-9"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 6h6v6"/>
                                            </svg>

                                            <p class="text-xs font-medium">
                                                Nombre de scans
                                            </p>

                                        </div>

                                        <p class="mt-2 text-2xl font-extrabold text-[#071B36]">
                                            <?= (int) ($qr['nb_scans'] ?? 0) ?>
                                        </p>

                                    </div>


                                    <div class="px-3 py-4 text-center">

                                        <div class="flex items-center justify-center gap-2 text-[#8A96A3]">

                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                                <circle cx="12" cy="12" r="9"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                            </svg>

                                            <p class="text-xs font-medium">
                                                Dernier scan
                                            </p>

                                        </div>

                                        <p class="mt-2 break-words text-sm font-bold text-[#071B36]">

                                            <?php if ($dernierScan): ?>

                                                <?php
                                                $timestampScan = strtotime((string) $dernierScan);
                                                ?>

                                                <?= $timestampScan !== false
                                                    ? $e(date('d/m/Y H:i', $timestampScan))
                                                    : 'Date indisponible'
                                                ?>

                                            <?php else: ?>

                                                <span class="text-xs font-medium text-gray-400">
                                                    Jamais scanné
                                                </span>

                                            <?php endif; ?>

                                        </p>

                                    </div>

                                </div>


                                <!-- ACTION DE TÉLÉCHARGEMENT -->
                                <div class="mt-auto p-5">

                                    <a
                                        href="<?= $e($cheminImage) ?>"
                                        download="<?= $e($nomFichier) ?>"
                                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-[#071B36] px-4 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#0E2A4E] hover:shadow-md"
                                    >

                                        <svg class="h-5 w-5 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v3h16v-3"/>
                                        </svg>

                                        Télécharger le QR Code

                                    </a>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>


                    <!-- PAGINATION -->
                    <?php if ($totalPages > 1): ?>

                        <div class="flex flex-col items-center justify-between gap-5 rounded-2xl border border-[#E5E5DD] bg-white p-5 shadow-sm sm:flex-row">

                            <div class="text-center sm:text-left">

                                <p class="text-sm font-bold text-[#071B36]">
                                    Page <?= $pageActuelle ?> sur <?= $totalPages ?>
                                </p>

                                <p class="mt-1 text-xs text-[#718096]">
                                    <?= $nombreQrCodes ?> QR Codes au total
                                </p>

                            </div>

                            <nav class="flex flex-wrap items-center justify-center gap-2" aria-label="Pagination des QR Codes">

                                <?php if ($pageActuelle > 1): ?>

                                    <a
                                        href="?page=<?= $pageActuelle - 1 ?>"
                                        class="inline-flex items-center gap-2 rounded-xl border border-[#E5E5DD] px-4 py-2.5 text-sm font-semibold text-[#071B36] transition hover:border-[#F5B800] hover:bg-[#F5F3ED]"
                                    >
                                        ← Précédent
                                    </a>

                                <?php endif; ?>


                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                                    <a
                                        href="?page=<?= $i ?>"
                                        aria-current="<?= $i === $pageActuelle ? 'page' : 'false' ?>"
                                        class="flex h-10 w-10 items-center justify-center rounded-xl text-sm font-bold transition
                                        <?= $i === $pageActuelle
                                            ? 'bg-[#071B36] text-white shadow-sm'
                                            : 'border border-[#E5E5DD] bg-white text-[#071B36] hover:border-[#F5B800] hover:bg-[#F5F3ED]'
                                        ?>"
                                    >
                                        <?= $i ?>
                                    </a>

                                <?php endfor; ?>


                                <?php if ($pageActuelle < $totalPages): ?>

                                    <a
                                        href="?page=<?= $pageActuelle + 1 ?>"
                                        class="inline-flex items-center gap-2 rounded-xl border border-[#E5E5DD] px-4 py-2.5 text-sm font-semibold text-[#071B36] transition hover:border-[#F5B800] hover:bg-[#F5F3ED]"
                                    >
                                        Suivant →
                                    </a>

                                <?php endif; ?>

                            </nav>

                        </div>

                    <?php endif; ?>

                <?php endif; ?>

            </section>

        </div>


        <!-- PIED DE PAGE -->
        <footer class="mt-10 border-t border-[#E5E5DD] bg-white px-5 py-7 sm:px-8">

            <div class="mx-auto flex max-w-7xl flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <a href="/immo-sn/" class="inline-flex items-center gap-3">

                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071B36] text-sm font-extrabold text-[#F5B800]">
                            IM
                        </span>

                        <span class="text-lg font-extrabold tracking-wide text-[#071B36]">
                            IMMO<span class="text-[#C89A00]">-SN</span>
                        </span>

                    </a>

                    <p class="mt-2 text-xs text-[#718096]">
                        Votre partenaire immobilier au Sénégal.
                    </p>

                </div>

                <div class="flex flex-col gap-2 text-xs text-[#718096] sm:items-end">

                    <p>
                        © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                    </p>

                    <p class="font-semibold text-[#B58B00]">
                        Espace professionnel · Gestion des QR Codes
                    </p>

                </div>

            </div>

        </footer>

    </main>

</div>
