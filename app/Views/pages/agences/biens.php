
<?php
use Core\Session;

$nomAgence = $agence['nom_agence'] ?? 'Mon agence';
$biens = is_array($biens ?? null) ? $biens : [];
$renouvellements = is_array($renouvellements ?? null) ? $renouvellements : [];
$success = $success ?? null;
$error = $error ?? null;

$e = static fn($value): string => htmlspecialchars(
    (string)($value ?? ''),
    ENT_QUOTES,
    'UTF-8'
);

$statuts = [
    'actif' => ['Actif', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
    'en_attente' => ['En attente', 'bg-amber-50 text-amber-700 border-amber-200'],
    'pending' => ['En attente', 'bg-amber-50 text-amber-700 border-amber-200'],
    'rejete' => ['Rejeté', 'bg-red-50 text-red-700 border-red-200'],
    'refuse' => ['Refusé', 'bg-red-50 text-red-700 border-red-200'],
    'refusee' => ['Refusé', 'bg-red-50 text-red-700 border-red-200'],
    'expire' => ['Expiré', 'bg-slate-100 text-slate-700 border-slate-200'],
    'brouillon' => ['Brouillon', 'bg-blue-50 text-blue-700 border-blue-200'],
];

$nombreBiens = count($biens);
$nombreActifs = $nombreEnAttente = $nombreExpires = 0;

foreach ($biens as $bienStatistique) {
    $statut = strtolower(trim((string)($bienStatistique['statut'] ?? '')));
    $expiration = $bienStatistique['expire_at'] ?? null;
    $expirationTs = !empty($expiration) ? strtotime((string)$expiration) : false;

    $estExpire = $statut === 'expire'
        || ($expirationTs !== false && $expirationTs <= time());

    if ($estExpire) {
        $nombreExpires++;
    } elseif (in_array($statut, ['en_attente', 'pending'], true)) {
        $nombreEnAttente++;
    } elseif ($statut === 'actif') {
        $nombreActifs++;
    }
}

$formatDate = static function ($date): string {
    if (empty($date)) return 'Date inconnue';

    $timestamp = strtotime((string)$date);

    return $timestamp !== false
        ? date('d/m/Y', $timestamp)
        : 'Date inconnue';
};
?>

<div class="min-h-screen bg-[#F4F6F9] text-[#071B36]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen flex flex-col lg:ml-[270px]">

        <!-- =====================================================
             HEADER
        ====================================================== -->
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
            <div class="flex min-h-[82px] items-center justify-between gap-4 px-5 py-4 sm:px-8">

                <div>
                    <p class="text-[10px] font-black uppercase tracking-[.20em] text-[#B88A00]">
                        ESPACE PROFESSIONNEL
                    </p>

                    <h1 class="mt-1 text-xl font-black tracking-tight text-[#071B36] sm:text-2xl">
                        Mes biens immobiliers
                    </h1>

                    <p class="mt-1 text-xs text-slate-500">
                        <?= $e($nomAgence) ?>
                    </p>
                </div>

                <a href="/immo-sn/agence/publier"
                   class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-[#F5B800] px-4 py-3 text-sm font-extrabold text-[#071B36] shadow-sm transition hover:bg-[#e4aa00] hover:-translate-y-0.5">

                    <span class="text-xl leading-none">+</span>
                    <span class="hidden sm:inline">Publier un bien</span>
                    <span class="sm:hidden">Publier</span>
                </a>

            </div>
        </header>

        <div class="mx-auto w-full max-w-[1450px] flex-1 space-y-7 px-4 py-6 sm:px-7 sm:py-8 lg:px-9">

           <!-- =====================================================
     BANNIÈRE PRINCIPALE — HERO COMPACT
====================================================== -->

<section class="relative isolate overflow-hidden rounded-[26px] bg-[#071B36] px-5 py-5 text-white shadow-[0_18px_45px_rgba(7,27,54,.14)] sm:px-6 sm:py-6">

    <!-- DÉCORATION -->
    <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full border-[30px] border-[#F5B800]/10"></div>

    <div class="pointer-events-none absolute -bottom-28 right-1/3 h-60 w-60 rounded-full bg-[#365477]/30 blur-3xl"></div>

    <div class="pointer-events-none absolute right-8 top-8 h-2 w-2 rounded-full bg-[#F5B800]"></div>

    <!-- CONTENU -->
    <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <!-- PRÉSENTATION -->
        <div class="max-w-2xl">

            <!-- BADGE -->
            <span class="inline-flex items-center gap-2 rounded-full border border-[#F5B800]/20 bg-[#F5B800]/10 px-3 py-1.5 text-[10px] font-bold tracking-wide text-[#F5B800]">

                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>

                GESTION IMMOBILIÈRE

            </span>

            <!-- TITRE -->
            <h2 class="mt-3 text-2xl font-black leading-tight tracking-tight sm:text-3xl lg:text-[32px]">
                Gérez votre portefeuille immobilier
                <span class="text-[#F5B800]">en toute simplicité.</span>
            </h2>

            <!-- DESCRIPTION -->
            <p class="mt-2.5 max-w-xl text-sm leading-6 text-slate-300">
                Retrouvez toutes vos annonces, consultez leur état de publication
                et gérez facilement leur renouvellement depuis votre espace agence.
            </p>

            <!-- ACTIONS -->
            <div class="mt-4 flex flex-wrap gap-2.5">

                <a href="/immo-sn/agence/publier"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#F5B800] px-4 py-2.5 text-sm font-extrabold text-[#071B36] transition hover:bg-[#e4aa00] hover:-translate-y-0.5">

                    + Publier un bien

                </a>

                <a href="#liste-biens"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/10">

                    Voir mes annonces
                    <span class="text-[#F5B800]">→</span>

                </a>

            </div>

        </div>

        <!-- PORTFOLIO -->
        <div class="flex shrink-0 items-center gap-3 rounded-2xl border border-white/10 bg-white/[.06] p-4 backdrop-blur-sm">

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#F5B800]/10 text-xl text-[#F5B800]">
                ⌂
            </div>

            <div>

                <p class="text-xs font-medium text-slate-300">
                    Votre portefeuille
                </p>

                <p class="mt-1 text-2xl font-black text-white">
                    <?= number_format($nombreBiens, 0, ',', ' ') ?>

                    <span class="text-sm font-semibold text-slate-300">
                        bien<?= $nombreBiens > 1 ? 's' : '' ?>
                    </span>
                </p>

            </div>

        </div>

    </div>

</section>            <!-- =====================================================
                 STATISTIQUES
            ====================================================== -->
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <?php
                $statCards = [
                    [
                        'label' => 'Total des biens',
                        'value' => $nombreBiens,
                        'hint' => 'Toutes vos annonces',
                        'icon' => '⌂',
                        'iconBg' => 'bg-[#071B36]',
                        'iconFg' => 'text-[#F5B800]',
                        'num' => 'text-[#071B36]',
                    ],
                    [
                        'label' => 'Biens actifs',
                        'value' => $nombreActifs,
                        'hint' => 'Annonces actuellement actives',
                        'icon' => '✓',
                        'iconBg' => 'bg-emerald-50',
                        'iconFg' => 'text-emerald-600',
                        'num' => 'text-emerald-600',
                    ],
                    [
                        'label' => 'En attente',
                        'value' => $nombreEnAttente,
                        'hint' => 'En attente de validation',
                        'icon' => '◷',
                        'iconBg' => 'bg-amber-50',
                        'iconFg' => 'text-amber-600',
                        'num' => 'text-amber-600',
                    ],
                    [
                        'label' => 'Biens expirés',
                        'value' => $nombreExpires,
                        'hint' => 'Annonces à renouveler',
                        'icon' => '!',
                        'iconBg' => 'bg-red-50',
                        'iconFg' => 'text-red-600',
                        'num' => 'text-red-600',
                    ],
                ];
                ?>

                <?php foreach ($statCards as $card): ?>

                    <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                        <div class="flex items-center justify-between gap-3">

                            <p class="text-sm font-semibold text-slate-500">
                                <?= $e($card['label']) ?>
                            </p>

                            <span class="flex h-11 w-11 items-center justify-center rounded-xl <?= $card['iconBg'] ?> <?= $card['iconFg'] ?> text-xl font-bold">
                                <?= $e($card['icon']) ?>
                            </span>

                        </div>

                        <p class="mt-4 text-3xl font-black tracking-tight <?= $card['num'] ?>">
                            <?= number_format($card['value'], 0, ',', ' ') ?>
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            <?= $e($card['hint']) ?>
                        </p>

                    </div>

                <?php endforeach; ?>

            </section>

            <!-- =====================================================
                 LISTE DES BIENS
            ====================================================== -->
            <section id="liste-biens"
                     class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">

                <!-- En-tête de la liste -->
                <div class="flex flex-col justify-between gap-4 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:px-7">

                    <div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-1 rounded-full bg-[#F5B800]"></span>

                            <h3 class="text-lg font-black text-[#071B36]">
                                Liste de mes biens
                            </h3>
                        </div>

                        <p class="mt-2 text-xs leading-5 text-slate-500 sm:text-sm">
                            Consultez et gérez l'ensemble de vos annonces immobilières.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center gap-2 rounded-full border border-[#F5B800]/30 bg-[#F5B800]/10 px-4 py-2 text-xs font-bold text-[#8A6500]">
                        <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>

                        <?= number_format($nombreBiens, 0, ',', ' ') ?>
                        annonce<?= $nombreBiens > 1 ? 's' : '' ?>
                    </span>

                </div>

                <!-- Messages -->
                <?php if (!empty($success)): ?>
                    <div class="mx-5 mt-5 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 sm:mx-7">
                        <span class="font-bold">✓</span>
                        <p><?= $e($success) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="mx-5 mt-5 flex gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 sm:mx-7">
                        <span class="font-bold">!</span>
                        <p><?= $e($error) ?></p>
                    </div>
                <?php endif; ?>

                <!-- État vide -->
                <?php if (empty($biens)): ?>

                    <div class="px-5 py-16 text-center sm:py-20">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[24px] border border-slate-200 bg-[#F4F6F9] text-4xl text-slate-400">
                            ⌂
                        </div>

                        <h3 class="mt-6 text-xl font-black text-[#071B36]">
                            Votre portefeuille est encore vide
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-slate-500">
                            Vous n'avez pas encore publié de bien immobilier.
                            Ajoutez votre première annonce pour commencer à développer
                            votre visibilité sur IMMO-SN.
                        </p>

                        <a href="/immo-sn/agence/publier"
                           class="mt-7 inline-flex items-center gap-2 rounded-xl bg-[#F5B800] px-6 py-3.5 text-sm font-extrabold text-[#071B36] transition hover:bg-[#e4aa00]">
                            + Publier mon premier bien
                        </a>

                    </div>

                <?php else: ?>

                    <!-- Grille des annonces -->
                    <div class="grid grid-cols-1 gap-5 p-4 sm:grid-cols-2 sm:p-5 xl:grid-cols-3">

                        <?php foreach ($biens as $bien): ?>

                            <?php
                            $statutBien = strtolower(trim((string)($bien['statut'] ?? 'inconnu')));
                            $idBien = $bien['id'] ?? '';

                            $demandeRenouvellement = $renouvellements[$idBien] ?? null;
                            $statutRenouvellement = $demandeRenouvellement['statut'] ?? null;

                            $renouvellementEnAttente = $statutRenouvellement === 'en_attente';
                            $motifRefus = $demandeRenouvellement['motif_refus'] ?? null;

                            $dateExpiration = $bien['expire_at'] ?? null;
                            $timestampExpiration = !empty($dateExpiration)
                                ? strtotime((string)$dateExpiration)
                                : false;

                            $bienExpire = $statutBien === 'expire'
                                || ($timestampExpiration !== false && $timestampExpiration <= time());

                            if ($bienExpire) {
                                $labelBien = 'Expiré';
                                $styleBien = 'bg-red-50 text-red-700 border-red-200';
                            } else {
                                [$labelBien, $styleBien] = $statuts[$statutBien] ?? [
                                    ucfirst(str_replace('_', ' ', $statutBien)),
                                    'bg-slate-100 text-slate-700 border-slate-200'
                                ];
                            }

                            $titre = $bien['titre'] ?? 'Bien sans titre';
                            $ville = $bien['ville_nom'] ?? '';
                            $quartier = $bien['quartier_nom'] ?? '';

                            $localisation = trim(
                                $quartier . ($quartier && $ville ? ', ' : '') . $ville
                            );

                            $photo = $bien['photo'] ?? null;
                            ?>

                            <!-- Carte bien -->
                            <article class="group flex min-w-0 flex-col overflow-hidden rounded-[20px] border border-slate-200 bg-white transition duration-300 hover:-translate-y-1 hover:border-[#F5B800]/60 hover:shadow-xl">

                                <!-- Image -->
                                <div class="relative h-52 overflow-hidden bg-[#F4F6F9]">

                                    <?php if (!empty($photo)): ?>

                                        <img src="<?= $e($photo) ?>"
                                             alt="<?= $e($titre) ?>"
                                             loading="lazy"
                                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">

                                    <?php else: ?>

                                        <div class="flex h-full w-full flex-col items-center justify-center gap-2 text-slate-400">
                                            <span class="text-5xl">⌂</span>
                                            <span class="text-xs font-medium">Aucune photo disponible</span>
                                        </div>

                                    <?php endif; ?>

                                    <!-- Badge statut -->
                                    <div class="absolute left-3 top-3">

                                        <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-[11px] font-bold shadow-sm <?= $styleBien ?>">
                                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                            <?= $e($labelBien) ?>
                                        </span>

                                    </div>

                                    <?php if ($bienExpire): ?>

                                        <div class="absolute right-3 top-3">
                                            <span class="rounded-full bg-white/95 px-3 py-1.5 text-[10px] font-bold text-red-700 shadow-sm">
                                                Expiration dépassée
                                            </span>
                                        </div>

                                    <?php endif; ?>

                                </div>

                                <!-- Contenu -->
                                <div class="flex flex-1 flex-col p-5">

                                    <!-- Titre -->
                                    <h4 class="truncate text-base font-black text-[#071B36] sm:text-lg">
                                        <?= $e($titre) ?>
                                    </h4>

                                    <!-- Prix -->
                                    <?php if (!empty($bien['prix'])): ?>

                                        <p class="mt-2 text-lg font-black tracking-tight text-[#B88A00]">
                                            <?= $e($bien['prix']) ?>
                                        </p>

                                    <?php else: ?>

                                        <p class="mt-2 text-sm italic text-slate-400">
                                            Prix non renseigné
                                        </p>

                                    <?php endif; ?>

                                    <!-- Informations -->
                                    <div class="mt-5 space-y-3 border-t border-slate-100 pt-4">

                                        <!-- Catégorie -->
                                        <div class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#071B36]/5 text-[#071B36]">
                                                ⌂
                                            </span>

                                            <span class="pt-1 leading-5">
                                                <?= $e($bien['categorie_nom'] ?? 'Catégorie non renseignée') ?>
                                            </span>
                                        </div>

                                        <!-- Localisation -->
                                        <div class="flex items-start gap-3 text-sm text-slate-600">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#071B36]/5 text-[#071B36]">
                                                ⌖
                                            </span>

                                            <span class="min-w-0 break-words pt-1 leading-5">
                                                <?= $e($localisation !== '' ? $localisation : 'Localisation non renseignée') ?>
                                            </span>
                                        </div>

                                        <!-- Date de publication -->
                                        <div class="flex items-start gap-3 text-xs text-slate-500">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-500">
                                                ◷
                                            </span>

                                            <span class="pt-1 leading-5">
                                                Publié le :
                                                <?= $e($formatDate($bien['created_at'] ?? null)) ?>
                                            </span>
                                        </div>

                                        <!-- Date expiration -->
                                        <?php if (!empty($dateExpiration)): ?>

                                            <div class="flex items-start gap-3 text-xs <?= $bienExpire ? 'font-semibold text-red-600' : 'text-slate-500' ?>">

                                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-50">
                                                    ◴
                                                </span>

                                                <span class="pt-1 leading-5">
                                                    Expire le :
                                                    <?= $e($formatDate($dateExpiration)) ?>
                                                </span>

                                            </div>

                                        <?php endif; ?>

                                    </div>

                                    <!-- Renouvellement en attente -->
                                    <?php if ($bienExpire && $renouvellementEnAttente): ?>

                                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-3">

                                            <div class="flex items-center gap-2 text-sm font-bold text-amber-800">
                                                ◷ Renouvellement en attente
                                            </div>

                                            <p class="mt-1 text-xs leading-5 text-amber-700">
                                                Votre demande est en cours d'examen par notre équipe.
                                            </p>

                                        </div>

                                    <?php endif; ?>

                                    <!-- Renouvellement refusé -->
                                    <?php if ($bienExpire && !$renouvellementEnAttente && $statutRenouvellement === 'refusee'): ?>

                                        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-3">

                                            <p class="text-sm font-bold text-red-800">
                                                Demande précédente refusée
                                            </p>

                                            <?php if (!empty($motifRefus)): ?>

                                                <p class="mt-1 text-xs leading-5 text-red-700">
                                                    Motif : <?= $e($motifRefus) ?>
                                                </p>

                                            <?php endif; ?>

                                        </div>

                                    <?php endif; ?>

                                    <!-- Actions -->
                                    <div class="mt-auto flex flex-col gap-2.5 pt-5">

                                        <!-- Renouvellement -->
                                        <?php if ($bienExpire && !$renouvellementEnAttente): ?>

                                            <form method="POST"
                                                  action="/immo-sn/agence/biens/renouveler/<?= rawurlencode((string)$idBien) ?>"
                                                  onsubmit="return confirm('Confirmez-vous votre demande de renouvellement pour cette annonce ?');">

                                                <input type="hidden"
                                                       name="_csrf_token"
                                                       value="<?= $e(Session::csrfToken()) ?>">

                                                <button type="submit"
                                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-4 py-3 text-sm font-extrabold text-[#071B36] transition hover:bg-[#e4aa00]">
                                                    ↻ Demander le renouvellement
                                                </button>

                                            </form>

                                        <?php endif; ?>

                                        <!-- Voir -->
                                        <a href="/immo-sn/agence/biens/<?= rawurlencode((string)$idBien) ?>"
                                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#071B36] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#102B4D]">
                                            Voir le bien
                                            <span class="text-[#F5B800]">→</span>
                                        </a>

                                        <!-- Supprimer -->
                                        <form method="POST"
                                              action="/immo-sn/agence/biens/<?= rawurlencode((string)$idBien) ?>/supprimer"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette annonce ? Cette action est définitive.');">

                                            <input type="hidden"
                                                   name="_csrf_token"
                                                   value="<?= $e(Session::csrfToken()) ?>">

                                            <button type="submit"
                                                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700 transition hover:bg-red-100">
                                                🗑 Supprimer cette annonce
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

        </div>

        <!-- =====================================================
             FOOTER
        ====================================================== -->
        <footer class="mt-auto border-t border-slate-200 bg-white">

            <div class="mx-auto flex max-w-[1450px] flex-col gap-4 px-5 py-6 sm:px-7 md:flex-row md:items-center md:justify-between lg:px-9">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#071B36] text-lg font-black text-[#F5B800]">
                        I
                    </div>

                    <div>
                        <p class="text-sm font-black tracking-wide text-[#071B36]">
                            IMMO-SN
                        </p>

                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Votre partenaire immobilier au Sénégal.
                        </p>
                    </div>

                </div>

                <div class="text-left md:text-right">

                    <p class="text-xs font-medium text-slate-500">
                        © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                    </p>

                    <p class="mt-1 text-[11px] font-semibold text-[#B88A00]">
                        Espace professionnel · Gestion des biens immobiliers
                    </p>

                </div>

            </div>

        </footer>

    </main>

</div>
