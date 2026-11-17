
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

<div class="min-h-screen bg-[#faf7f2]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- EN-TÊTE -->
        <header class="sticky top-0 z-30 flex flex-col gap-4 border-b border-[#eee5dc] bg-white/95 px-5 py-4 backdrop-blur sm:flex-row sm:items-center sm:justify-between sm:px-8">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#a98260]">
                    Espace professionnel
                </p>

                <h1 class="mt-1 text-xl font-extrabold text-[#30221b] sm:text-2xl">
                    Mes QR Codes
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Gérez les QR Codes associés à vos annonces immobilières.
                </p>
            </div>

            <a
                href="/immo-sn/agence/qr-codes/zip"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#4a3930] hover:shadow-md"
            >
                <span class="text-lg">↓</span>
                Télécharger tout
            </a>

        </header>

        <!-- CONTENU -->
        <div class="mx-auto max-w-7xl space-y-8 px-5 py-8 sm:px-8">

            <!-- BANNIÈRE -->
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#30221b] via-[#49372c] to-[#5a402d] p-7 text-white shadow-lg sm:p-9">

                <div class="pointer-events-none absolute -right-12 -top-16 h-64 w-64 rounded-full border-[35px] border-[#f7b500]/10"></div>

                <div class="pointer-events-none absolute -bottom-24 right-36 h-52 w-52 rounded-full bg-[#f7b500]/10 blur-2xl"></div>

                <div class="relative z-10 max-w-2xl">

                    <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-semibold text-[#f7d98a]">
                        <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>
                        Bibliothèque numérique
                    </div>

                   <h2 class="mt-5 break-words text-2xl font-extrabold leading-tight sm:text-3xl">
                       Vos biens, accessibles en un scan.
                    </h2>

                    <p class="mt-3 text-sm leading-7 text-[#e0d6ce] sm:text-base">
                        Retrouvez tous les QR Codes de vos annonces.
                        Facilitez l'accès à vos biens et suivez les consultations
                        enregistrées grâce aux scans.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3 text-xs font-medium text-[#e0d6ce]">
                        <span class="rounded-lg border border-white/15 bg-white/10 px-3 py-2">
                            Téléchargement individuel
                        </span>

                        <span class="rounded-lg border border-white/15 bg-white/10 px-3 py-2">
                            Archive ZIP
                        </span>

                        <span class="rounded-lg border border-white/15 bg-white/10 px-3 py-2">
                            Suivi des scans
                        </span>
                    </div>

                </div>

            </section>

            <!-- STATISTIQUES -->
            <section class="grid gap-5 sm:grid-cols-2">

                <!-- TOTAL QR -->
                <article class="group relative overflow-hidden rounded-2xl border border-[#eee5dc] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-semibold text-gray-500">
                                QR Codes disponibles
                            </p>

                            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#30221b]">
                                <?= $nombreQrCodes ?>
                            </p>

                            <p class="mt-2 text-xs text-gray-500">
                                QR Codes associés à vos biens
                            </p>
                        </div>

                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#faf2e7] text-2xl text-[#a98260]">
                            ▦
                        </div>

                    </div>

                    <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#a98260] to-[#f7b500]"></div>

                </article>

                <!-- TOTAL SCANS -->
                <article class="group relative overflow-hidden rounded-2xl border border-[#eee5dc] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-semibold text-gray-500">
                                Nombre total de scans
                            </p>

                            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#30221b]">
                                <?= $totalScans ?>
                            </p>

                            <p class="mt-2 text-xs text-gray-500">
                                Consultations enregistrées
                            </p>
                        </div>

                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f3eee8] text-2xl text-[#5a402d]">
                            ↗
                        </div>

                    </div>

                    <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#30221b] to-[#a98260]"></div>

                </article>

            </section>

            <!-- BIBLIOTHÈQUE -->
            <section class="space-y-6">

                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-[#a98260]">
                            Gestion des annonces
                        </p>

                        <h3 class="mt-1 text-xl font-extrabold text-[#30221b] sm:text-2xl">
                            Bibliothèque de mes QR Codes
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Consultez, suivez et téléchargez les QR Codes de vos biens.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center rounded-full border border-[#eadfce] bg-white px-4 py-2 text-sm font-bold text-[#5a402d]">
                        <?= $nombreQrCodes ?> QR Code(s)
                    </span>

                </div>

                <?php if (empty($qrCodes)): ?>

                    <!-- ÉTAT VIDE -->
                    <div class="rounded-3xl border border-[#eee5dc] bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[#faf2e7] text-4xl text-[#a98260]">
                            ▦
                        </div>

                        <h3 class="mt-6 text-xl font-extrabold text-[#30221b]">
                            Aucun QR Code disponible
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-gray-500">
                            Aucun QR Code n'est actuellement associé à vos biens.
                            Dès qu'un QR Code sera disponible, vous pourrez le retrouver ici.
                        </p>

                        <a
                            href="/immo-sn/agence/biens"
                            class="mt-7 inline-flex items-center justify-center gap-2 rounded-xl bg-[#30221b] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#4a3930]"
                        >
                            Consulter mes biens
                            <span>→</span>
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

                            <article class="group flex flex-col overflow-hidden rounded-3xl border border-[#eee5dc] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                                <!-- EN-TÊTE -->
                                <div class="flex items-start justify-between gap-3 p-5">

                                    <div class="min-w-0">

                                        <div class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>

                                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-[#a98260]">
                                                Bien immobilier
                                            </p>
                                        </div>

                                        <h4 class="mt-3 line-clamp-2 break-words text-base font-extrabold leading-6 text-[#30221b]">
                                            <?= $e($qr['titre'] ?? 'Bien sans titre') ?>
                                        </h4>

                                        <p class="mt-1 text-xs text-gray-400">
                                            Référence : <?= $e($qr['bien_id'] ?? '—') ?>
                                        </p>

                                    </div>

                                    <span class="shrink-0 rounded-full border px-3 py-1.5 text-[11px] font-bold <?= $style ?>">
                                        <?= $e($label) ?>
                                    </span>

                                </div>

                                <!-- IMAGE QR CODE -->
                                <div class="mx-5 flex flex-col items-center rounded-2xl border border-[#eee5dc] bg-[#faf7f2] px-4 py-6">

                                    <div class="flex h-44 w-44 items-center justify-center rounded-2xl border border-[#eee5dc] bg-white p-3 shadow-sm transition duration-300 group-hover:shadow-md">

                                        <img
                                            src="<?= $e($cheminImage) ?>"
                                            alt="QR Code de <?= $e($qr['titre'] ?? 'ce bien') ?>"
                                            class="h-full w-full object-contain"
                                            loading="lazy"
                                        >

                                    </div>

                                    <p class="mt-4 text-xs font-medium text-[#a98260]">
                                        Scannez pour accéder à l'annonce
                                    </p>

                                </div>

                                <!-- STATISTIQUES -->
                                <div class="mt-5 grid grid-cols-2 divide-x divide-[#eee5dc] border-y border-[#eee5dc]">

                                    <div class="px-4 py-4 text-center">

                                        <div class="flex items-center justify-center gap-2 text-gray-400">
                                            <span>↗</span>
                                            <p class="text-xs font-medium">
                                                Nombre de scans
                                            </p>
                                        </div>

                                        <p class="mt-2 text-xl font-extrabold text-[#30221b]">
                                            <?= (int) ($qr['nb_scans'] ?? 0) ?>
                                        </p>

                                    </div>

                                    <div class="px-3 py-4 text-center">

                                        <div class="flex items-center justify-center gap-2 text-gray-400">
                                            <span>◷</span>
                                            <p class="text-xs font-medium">
                                                Dernier scan
                                            </p>
                                        </div>

                                        <p class="mt-2 break-words text-sm font-bold text-[#30221b]">

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

                                <!-- ACTION -->
                                <div class="mt-auto p-5">

                                    <a
                                        href="<?= $e($cheminImage) ?>"
                                        download="<?= $e($nomFichier) ?>"
                                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-[#30221b] px-4 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#4a3930] hover:shadow-md"
                                    >
                                        <span class="text-lg">↓</span>
                                        Télécharger le QR Code
                                    </a>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                    <!-- PAGINATION -->
                    <?php if ($totalPages > 1): ?>

                        <div class="flex flex-col items-center justify-between gap-4 rounded-2xl border border-[#eee5dc] bg-white p-5 shadow-sm sm:flex-row">

                            <div class="text-center sm:text-left">

                                <p class="text-sm font-bold text-[#30221b]">
                                    Page <?= $pageActuelle ?> sur <?= $totalPages ?>
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    <?= $nombreQrCodes ?> QR Codes au total
                                </p>

                            </div>

                            <nav class="flex flex-wrap items-center justify-center gap-2">

                                <?php if ($pageActuelle > 1): ?>

                                    <a
                                        href="?page=<?= $pageActuelle - 1 ?>"
                                        class="inline-flex items-center gap-2 rounded-xl border border-[#eee5dc] px-4 py-2.5 text-sm font-semibold text-[#30221b] transition hover:bg-[#faf7f2]"
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
                                            ? 'bg-[#30221b] text-white shadow-sm'
                                            : 'border border-[#eee5dc] bg-white text-[#30221b] hover:bg-[#faf7f2]'
                                        ?>"
                                    >
                                        <?= $i ?>
                                    </a>

                                <?php endfor; ?>

                                <?php if ($pageActuelle < $totalPages): ?>

                                    <a
                                        href="?page=<?= $pageActuelle + 1 ?>"
                                        class="inline-flex items-center gap-2 rounded-xl border border-[#eee5dc] px-4 py-2.5 text-sm font-semibold text-[#30221b] transition hover:bg-[#faf7f2]"
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
        <footer class="mt-10 border-t border-[#e9dfd5] bg-white px-5 py-6 sm:px-8">

            <div class="mx-auto flex max-w-7xl flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <a href="/immo-sn/" class="inline-flex items-center gap-2">

                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#30221b] text-sm font-extrabold text-[#f7b500]">
                            IM
                        </span>

                        <span class="text-base font-extrabold tracking-wide text-[#30221b]">
                            IMMO<span class="text-[#a98260]">-SN</span>
                        </span>

                    </a>

                    <p class="mt-2 text-xs text-gray-500">
                        Votre partenaire immobilier au Sénégal.
                    </p>

                </div>

                <div class="flex flex-col gap-2 text-xs text-gray-500 sm:items-end">

                    <p>
                        © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                    </p>

                    <p class="font-medium text-[#a98260]">
                        Espace professionnel · Gestion des QR Codes
                    </p>

                </div>

            </div>

        </footer>

    </main>

</div>
