
<?php

use Core\Session;

$nomAgence = $agence['nom_agence'] ?? 'Mon agence';

$biens = is_array($biens ?? null) ? $biens : [];
$renouvellements = is_array($renouvellements ?? null) ? $renouvellements : [];

$success = $success ?? null;
$error = $error ?? null;

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

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

$nombreBiens = count($biens);

$nombreActifs = 0;
$nombreEnAttente = 0;
$nombreExpires = 0;

foreach ($biens as $bienStatistique) {
    $statut = strtolower(trim((string) ($bienStatistique['statut'] ?? '')));

    $expiration = $bienStatistique['expire_at'] ?? null;

    $estExpire = (
        $statut === 'expire'
        || (
            !empty($expiration)
            && strtotime((string) $expiration) !== false
            && strtotime((string) $expiration) <= time()
        )
    );

    if ($estExpire) {
        $nombreExpires++;
    } elseif (in_array($statut, ['en_attente', 'pending'], true)) {
        $nombreEnAttente++;
    } elseif ($statut === 'actif') {
        $nombreActifs++;
    }
}

$formatDate = static function ($date): string {
    if (empty($date)) {
        return 'Date inconnue';
    }

    $timestamp = strtotime((string) $date);

    return $timestamp !== false
        ? date('d/m/Y', $timestamp)
        : 'Date inconnue';
};

?>

<div class="min-h-screen bg-[#faf7f2]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px] flex flex-col">

        <!-- EN-TÊTE -->
        <header class="sticky top-0 z-30 border-b border-[#eee5dc] bg-white/95 backdrop-blur">
            <div class="flex min-h-[82px] items-center justify-between gap-4 px-5 py-4 sm:px-8">

                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#a98260]">
                        Espace professionnel
                    </p>

                    <h1 class="mt-1 text-xl font-extrabold tracking-tight text-[#30221b] sm:text-2xl">
                        Mes biens immobiliers
                    </h1>
                </div>

                <a
                    href="/immo-sn/agence/publier"
                    class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-[#30221b] px-3.5 py-3 text-xs font-bold text-white shadow-sm transition hover:bg-[#4a3930] sm:px-5 sm:text-sm"
                >
                    <span class="text-lg leading-none text-[#f7b500]">+</span>
                    <span class="hidden sm:inline">Publier un bien</span>
                    <span class="sm:hidden">Publier</span>
                </a>

            </div>
        </header>

        <!-- CONTENU -->
        <div class="mx-auto w-full max-w-[1450px] flex-1 space-y-7 px-4 py-6 sm:px-7 sm:py-8 lg:px-9">

            <!-- BANNIÈRE D'INTRODUCTION -->
            <section class="relative isolate overflow-hidden rounded-[24px] bg-[#30221b] px-6 py-8 text-white shadow-lg sm:px-9 sm:py-10">

                <div class="absolute -right-16 -top-24 -z-10 h-72 w-72 rounded-full border-[35px] border-[#f7b500]/10"></div>
                <div class="absolute -bottom-28 right-32 -z-10 h-60 w-60 rounded-full bg-[#a98260]/10 blur-2xl"></div>

                <div class="relative flex flex-col justify-between gap-7 lg:flex-row lg:items-center">

                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-semibold tracking-wide text-[#e9d9c8]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#f7b500]"></span>
                            GESTION IMMOBILIÈRE
                        </div>

                        <h2 class="mt-5 text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl lg:text-[34px]">
                            Gérez votre portefeuille immobilier
                            <span class="text-[#f7b500]">en toute simplicité.</span>
                        </h2>

                        <p class="mt-3 max-w-xl text-sm leading-7 text-[#d1c6bd]">
                            Retrouvez toutes vos annonces, consultez leur état de publication
                            et gérez facilement leur renouvellement depuis votre espace agence.
                        </p>
                    </div>

                    <div class="flex shrink-0 items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.07] p-4 sm:p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#f7b500]/15 text-2xl text-[#f7b500]">
                            ⌂
                        </div>

                        <div>
                            <p class="text-xs text-[#c8beb7]">
                                Votre portefeuille
                            </p>
                            <p class="mt-1 text-2xl font-extrabold text-white">
                                <?= $nombreBiens ?>
                                <span class="text-sm font-medium text-[#c8beb7]">
                                    bien<?= $nombreBiens > 1 ? 's' : '' ?>
                                </span>
                            </p>
                        </div>
                    </div>

                </div>
            </section>

            <!-- STATISTIQUES -->
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-2xl border border-[#eee5dc] bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500">
                            Total des biens
                        </p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#faf3e9] text-xl text-[#a98260]">
                            ▤
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold tracking-tight text-[#30221b]">
                        <?= $nombreBiens ?>
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        Toutes vos annonces
                    </p>
                </div>

                <div class="rounded-2xl border border-[#eee5dc] bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500">
                            Biens actifs
                        </p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-xl text-emerald-600">
                            ✓
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-700">
                        <?= $nombreActifs ?>
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        Annonces actuellement actives
                    </p>
                </div>

                <div class="rounded-2xl border border-[#eee5dc] bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500">
                            En attente
                        </p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-600">
                            ◷
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold tracking-tight text-amber-600">
                        <?= $nombreEnAttente ?>
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        En attente de validation
                    </p>
                </div>

                <div class="rounded-2xl border border-[#eee5dc] bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500">
                            Biens expirés
                        </p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-xl text-red-600">
                            !
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold tracking-tight text-red-600">
                        <?= $nombreExpires ?>
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        Annonces à renouveler
                    </p>
                </div>

            </section>

            <!-- LISTE DES BIENS -->
            <section class="overflow-hidden rounded-[22px] border border-[#eee5dc] bg-white shadow-sm">

                <!-- TITRE DE LA LISTE -->
                <div class="flex flex-col justify-between gap-4 border-b border-[#eee5dc] px-5 py-5 sm:flex-row sm:items-center sm:px-7">

                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-5 w-1 rounded-full bg-[#f7b500]"></span>
                            <h3 class="text-lg font-extrabold text-[#30221b]">
                                Liste de mes biens
                            </h3>
                        </div>

                        <p class="mt-2 text-xs leading-5 text-gray-500 sm:text-sm">
                            Consultez et gérez l'ensemble de vos annonces immobilières.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center gap-2 rounded-full border border-[#eee5dc] bg-[#faf7f2] px-4 py-2 text-xs font-bold text-[#806044]">
                        <span class="h-2 w-2 rounded-full bg-[#a98260]"></span>
                        <?= $nombreBiens ?> annonce<?= $nombreBiens > 1 ? 's' : '' ?>
                    </span>

                </div>

                <!-- MESSAGES -->
                <?php if (!empty($success)): ?>
                    <div class="mx-5 mt-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 sm:mx-7">
                        <span class="font-bold">✓</span>
                        <p><?= $e($success) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="mx-5 mt-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 sm:mx-7">
                        <span class="font-bold">!</span>
                        <p><?= $e($error) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (empty($biens)): ?>

                    <!-- AUCUN BIEN -->
                    <div class="px-5 py-16 text-center sm:py-20">

                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[24px] border border-[#eee5dc] bg-[#faf7f2] text-4xl text-[#a98260]">
                            ▤
                        </div>

                        <h3 class="mt-6 text-xl font-extrabold text-[#30221b]">
                            Votre portefeuille est encore vide
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-gray-500">
                            Vous n'avez pas encore publié de bien immobilier.
                            Ajoutez votre première annonce pour commencer à développer
                            votre visibilité sur IMMO-SN.
                        </p>

                        <a
                            href="/immo-sn/agence/publier"
                            class="mt-7 inline-flex items-center gap-2 rounded-xl bg-[#30221b] px-6 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#4a3930]"
                        >
                            <span class="text-lg text-[#f7b500]">+</span>
                            Publier mon premier bien
                        </a>

                    </div>

                <?php else: ?>

                    <!-- GRILLE DES ANNONCES -->
                    <div class="grid grid-cols-1 gap-5 p-4 sm:grid-cols-2 sm:p-5 xl:grid-cols-3">

                        <?php foreach ($biens as $bien): ?>

                            <?php
                            $statutBien = strtolower(
                                trim((string) ($bien['statut'] ?? 'inconnu'))
                            );

                            $idBien = $bien['id'] ?? '';

                            $demandeRenouvellement = $renouvellements[$idBien] ?? null;

                            $statutRenouvellement =
                                $demandeRenouvellement['statut'] ?? null;

                            $renouvellementEnAttente =
                                $statutRenouvellement === 'en_attente';

                            $motifRefus =
                                $demandeRenouvellement['motif_refus'] ?? null;

                            $dateExpiration = $bien['expire_at'] ?? null;

                            $timestampExpiration = !empty($dateExpiration)
                                ? strtotime((string) $dateExpiration)
                                : false;

                            $bienExpire = (
                                $statutBien === 'expire'
                                || (
                                    $timestampExpiration !== false
                                    && $timestampExpiration <= time()
                                )
                            );

                            if ($bienExpire) {
                                $labelBien = 'Expiré';
                                $styleBien = 'bg-red-50 text-red-700 border-red-200';
                            } else {
                                [$labelBien, $styleBien] = $statuts[$statutBien] ?? [
                                    ucfirst(str_replace('_', ' ', $statutBien)),
                                    'bg-gray-100 text-gray-700 border-gray-200'
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

                            <article class="group flex min-w-0 flex-col overflow-hidden rounded-[19px] border border-[#eee5dc] bg-white transition duration-300 hover:-translate-y-1 hover:border-[#c5a17e] hover:shadow-lg">

                                <!-- PHOTO -->
                                <div class="relative h-52 overflow-hidden bg-[#f4eee7]">

                                    <?php if (!empty($photo)): ?>

                                        <img
                                            src="<?= $e($photo) ?>"
                                            alt="<?= $e($titre) ?>"
                                            loading="lazy"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        >

                                    <?php else: ?>

                                        <div class="flex h-full w-full flex-col items-center justify-center gap-2 text-[#a98260]">
                                            <span class="text-5xl">⌂</span>
                                            <span class="text-xs font-medium">Aucune photo disponible</span>
                                        </div>

                                    <?php endif; ?>

                                    <!-- STATUT -->
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

                                <!-- INFORMATIONS -->
                                <div class="flex flex-1 flex-col p-5">

                                    <!-- TITRE ET PRIX -->
                                    <div class="min-w-0">
                                        <h4 class="min-w-0 truncate text-base font-extrabold text-[#30221b] sm:text-lg">
                                            <?= $e($titre) ?>
                                        </h4>

                                        <?php if (!empty($bien['prix'])): ?>
                                            <p class="mt-2 text-lg font-extrabold tracking-tight text-[#a98260]">
                                                <?= $e($bien['prix']) ?>
                                            </p>
                                        <?php else: ?>
                                            <p class="mt-2 text-sm italic text-gray-400">
                                                Prix non renseigné
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- DÉTAILS -->
                                    <div class="mt-5 space-y-3 border-t border-[#f0eae3] pt-4">

                                        <div class="flex items-start gap-3 text-sm text-gray-600">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#faf3e9] text-sm text-[#a98260]">
                                                ⌂
                                            </span>
                                            <span class="pt-1 leading-5">
                                                <?= $e($bien['categorie_nom'] ?? 'Catégorie non renseignée') ?>
                                            </span>
                                        </div>

                                        <div class="flex items-start gap-3 text-sm text-gray-600">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#faf3e9] text-sm text-[#a98260]">
                                                ⌖
                                            </span>
                                           <span class="min-w-0 break-words pt-1 leading-5">
    <?= $e($localisation !== '' ? $localisation : 'Localisation non renseignée') ?>
</span>
                                        </div>

                                        <div class="flex items-start gap-3 text-xs text-gray-500">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-gray-50 text-sm text-[#a98260]">
                                                ◷
                                            </span>
                                            <span class="pt-1 leading-5">
                                                Publié le :
                                                <?= $e($formatDate($bien['created_at'] ?? null)) ?>
                                            </span>
                                        </div>

                                        <?php if (!empty($dateExpiration)): ?>
                                            <div class="flex items-start gap-3 text-xs <?= $bienExpire ? 'font-semibold text-red-600' : 'text-gray-500' ?>">
                                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-gray-50 text-sm text-[#a98260]">
                                                    ◴
                                                </span>
                                                <span class="pt-1 leading-5">
                                                    Expire le :
                                                    <?= $e($formatDate($dateExpiration)) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                    </div>

                                    <!-- RENOUVELLEMENT EN ATTENTE -->
                                    <?php if ($bienExpire && $renouvellementEnAttente): ?>

                                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-3">
                                            <div class="flex items-center gap-2 text-sm font-bold text-amber-800">
                                                <span>◷</span>
                                                Renouvellement en attente
                                            </div>
                                            <p class="mt-1 text-xs leading-5 text-amber-700">
                                                Votre demande est en cours d'examen par notre équipe.
                                            </p>
                                        </div>

                                    <?php endif; ?>

                                    <!-- DEMANDE REFUSÉE -->
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

                                    <!-- ACTIONS -->
                                    <div class="mt-auto flex flex-col gap-2.5 pt-5">

                                        <?php if ($bienExpire && !$renouvellementEnAttente): ?>

                                            <form
                                                method="POST"
                                                action="/immo-sn/agence/biens/renouveler/<?= rawurlencode((string) $idBien) ?>"
                                                onsubmit="return confirm('Confirmez-vous votre demande de renouvellement pour cette annonce ?');"
                                            >
                                                <input
                                                    type="hidden"
                                                    name="_csrf_token"
                                                    value="<?= $e(Session::csrfToken()) ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#a98260] bg-[#a98260] px-4 py-3 text-xs font-bold text-white transition hover:border-[#8f6c4c] hover:bg-[#8f6c4c] sm:text-sm"
                                                >
                                                    <span>↻</span>
                                                    Demander le renouvellement
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                        <a
                                            href="/immo-sn/agence/biens/<?= rawurlencode((string) $idBien) ?>"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-4 py-3 text-xs font-bold text-white transition hover:bg-[#4a3930] sm:text-sm"
                                        >
                                            Voir le bien
                                            <span class="text-[#f7b500]">→</span>
                                        </a>

                                        <form
                                            method="POST"
                                            action="/immo-sn/agence/biens/<?= rawurlencode((string) $idBien) ?>/supprimer"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette annonce ? Cette action est définitive.');"
                                        >
                                            <input
                                                type="hidden"
                                                name="_csrf_token"
                                                value="<?= $e(Session::csrfToken()) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-bold text-red-700 transition hover:bg-red-100 sm:text-sm"
                                            >
                                                <span>🗑</span>
                                                Supprimer cette annonce
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

        <!-- PIED DE PAGE -->
        <footer class="mt-auto border-t border-[#e9dfd4] bg-white">

            <div class="mx-auto flex max-w-[1450px] flex-col gap-4 px-5 py-6 sm:px-7 md:flex-row md:items-center md:justify-between lg:px-9">

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#30221b] text-lg font-extrabold text-[#f7b500]">
                        I
                    </div>

                    <div>
                        <p class="text-sm font-extrabold tracking-wide text-[#30221b]">
                            IMMO-SN
                        </p>
                        <p class="mt-0.5 text-[11px] text-gray-500">
                            Votre partenaire immobilier au Sénégal.
                        </p>
                    </div>
                </div>

                <div class="text-left md:text-right">
                    <p class="text-xs font-medium text-gray-500">
                        © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                    </p>
                    <p class="mt-1 text-[11px] text-[#a98260]">
                        Espace professionnel · Gestion des biens immobiliers
                    </p>
                </div>

            </div>

        </footer>

    </main>

</div>
