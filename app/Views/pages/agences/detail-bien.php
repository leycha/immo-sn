
<?php

use Core\Session;

$nomAgence = $agence['nom_agence'] ?? 'Mon agence';
$bien = $bien ?? [];
$photos = $photos ?? [];
$qrCode = $qrCode ?? null;

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

$statutBien = strtolower(trim((string)($bien['statut'] ?? 'inconnu')));

[$labelStatut, $styleStatut] = $statuts[$statutBien] ?? [
    ucfirst(str_replace('_', ' ', $statutBien)),
    'bg-slate-100 text-slate-700 border-slate-200'
];

$titre = $bien['titre'] ?? 'Bien sans titre';

$ville = $bien['ville_nom'] ?? '';
$quartier = $bien['quartier_nom'] ?? '';

$localisation = trim(
    $quartier . ($quartier && $ville ? ', ' : '') . $ville
);

$description = $bien['description'] ?? '';

$e = static function ($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
};
?>

<div class="min-h-screen bg-[#F4F6F9] text-[#071B36]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- =====================================================
             HEADER
        ====================================================== -->
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">

            <div class="flex flex-col justify-between gap-4 px-5 py-5 sm:flex-row sm:items-center sm:px-8">

                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.22em] text-[#B88A00]">
                        <?= $e($nomAgence) ?> · ESPACE AGENCE
                    </p>

                    <h1 class="mt-1 text-xl font-black tracking-tight text-[#071B36] sm:text-2xl">
                        Détails du bien
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-3">

                    <a href="/immo-sn/agence/biens"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-[#071B36] transition hover:bg-[#F4F6F9]">
                        <span>←</span>
                        Retour aux biens
                    </a>

                    <button type="button"
                            onclick="ouvrirModalModification()"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#F5B800] px-5 py-3 text-sm font-extrabold text-[#071B36] shadow-sm transition hover:bg-[#E4AA00] hover:-translate-y-0.5">
                        <span>✎</span>
                        Modifier ce bien
                    </button>

                </div>

            </div>

        </header>

        <!-- =====================================================
             CONTENU PRINCIPAL
        ====================================================== -->
        <div class="mx-auto max-w-[1500px] space-y-7 px-4 py-6 sm:px-7 sm:py-8 lg:px-9">
<!-- =================================================
     HERO — FICHE IMMOBILIÈRE COMPACT
================================================== -->

<section class="relative isolate overflow-hidden rounded-[26px] bg-[#071B36] px-5 py-5 text-white shadow-[0_18px_45px_rgba(7,27,54,.14)] sm:px-6 sm:py-6">

    <!-- DÉCORATION -->
    <div class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 rounded-full border-[35px] border-[#F5B800]/10"></div>

    <div class="pointer-events-none absolute -bottom-24 right-36 h-64 w-64 rounded-full bg-[#365477]/30 blur-3xl"></div>

    <div class="pointer-events-none absolute right-8 top-8 h-2 w-2 rounded-full bg-[#F5B800]"></div>

    <!-- CONTENU -->
    <div class="relative">

        <!-- BADGE -->
        <div class="inline-flex items-center gap-2 rounded-full border border-[#F5B800]/20 bg-[#F5B800]/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-[#F5B800]">
            <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
            Fiche immobilière
        </div>

        <!-- TITRE -->
        <h2 class="mt-3 max-w-4xl break-words text-2xl font-black leading-tight tracking-tight sm:text-3xl lg:text-4xl">
            <?= $e($titre) ?>
        </h2>

        <!-- LOCALISATION ET CATÉGORIE -->
        <div class="mt-3 flex flex-wrap items-center gap-2.5 text-xs text-slate-300 sm:text-sm">

            <span class="inline-flex items-center gap-2">
                <span class="text-[#F5B800]">⌖</span>
                <?= $e($localisation !== '' ? $localisation : 'Localisation non renseignée') ?>
            </span>

            <span class="hidden h-1 w-1 rounded-full bg-white/30 sm:block"></span>

            <span class="inline-flex items-center gap-2">
                <span class="text-[#F5B800]">▤</span>
                <?= $e($bien['categorie_nom'] ?? 'Catégorie non renseignée') ?>
            </span>

        </div>

        <!-- STATUT ET PRIX -->
        <div class="mt-4 flex flex-wrap items-center gap-2.5">

            <span class="inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-extrabold <?= $styleStatut ?>">
                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                <?= $e($labelStatut) ?>
            </span>

            <?php if (!empty($bien['prix'])): ?>

                <span class="rounded-full border border-[#F5B800]/20 bg-[#F5B800]/10 px-3.5 py-1.5 text-sm font-extrabold text-[#F5B800]">
                    <?= $e($bien['prix']) ?> FCFA
                </span>

            <?php endif; ?>

        </div>

    </div>

</section>           <!-- =================================================
                 INFORMATIONS PRINCIPALES
            ================================================== -->
            <section class="grid gap-5 lg:grid-cols-3">

                <!-- FICHE DU BIEN -->
                <div class="rounded-[22px] border border-slate-200 bg-white p-5 shadow-sm sm:p-7 lg:col-span-2">

                    <div class="flex items-center gap-3 border-b border-slate-100 pb-5">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-xl text-[#F5B800]">
                            ▤
                        </div>

                        <div>
                            <h3 class="font-black text-[#071B36]">
                                Informations du bien
                            </h3>
                            <p class="mt-1 text-xs text-slate-500">
                                Caractéristiques principales de votre annonce
                            </p>
                        </div>

                    </div>

                    <!-- CARACTÉRISTIQUES -->
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">

                        <div class="rounded-xl border border-slate-100 bg-[#F4F6F9] p-4 transition hover:border-[#F5B800]/40">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Prix</p>
                            <p class="mt-2 text-lg font-black text-[#071B36]">
                                <?= !empty($bien['prix']) ? $e($bien['prix']) . ' FCFA' : 'Non renseigné' ?>
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-[#F4F6F9] p-4 transition hover:border-[#F5B800]/40">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Catégorie</p>
                            <p class="mt-2 font-bold text-[#071B36]">
                                <?= $e($bien['categorie_nom'] ?? 'Non renseignée') ?>
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-[#F4F6F9] p-4 transition hover:border-[#F5B800]/40">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Type d'offre</p>
                            <p class="mt-2 font-bold text-[#071B36]">
                                <?= $e($bien['type_offre'] ?? 'Non renseigné') ?>
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-[#F4F6F9] p-4 transition hover:border-[#F5B800]/40">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Superficie</p>
                            <p class="mt-2 font-bold text-[#071B36]">
                                <?= !empty($bien['superficie']) ? $e($bien['superficie']) . ' m²' : 'Non renseignée' ?>
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-[#F4F6F9] p-4 transition hover:border-[#F5B800]/40">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Nombre de pièces</p>
                            <p class="mt-2 font-bold text-[#071B36]">
                                <?= $e($bien['pieces'] ?? 'Non renseigné') ?>
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-[#F4F6F9] p-4 transition hover:border-[#F5B800]/40">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Chambres</p>
                            <p class="mt-2 font-bold text-[#071B36]">
                                <?= $e($bien['chambres'] ?? 'Non renseigné') ?>
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-[#F4F6F9] p-4 transition hover:border-[#F5B800]/40">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Salles de bain</p>
                            <p class="mt-2 font-bold text-[#071B36]">
                                <?= $e($bien['salles_de_bain'] ?? 'Non renseigné') ?>
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-[#F4F6F9] p-4 transition hover:border-[#F5B800]/40">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Date de publication</p>
                            <p class="mt-2 font-bold text-[#071B36]">
                                <?= !empty($bien['created_at']) ? $e(date('d/m/Y', strtotime($bien['created_at']))) : 'Non renseignée' ?>
                            </p>
                        </div>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mt-7 border-t border-slate-100 pt-6">

                        <div class="flex items-center gap-3">
                            <span class="h-8 w-1 rounded-full bg-[#F5B800]"></span>
                            <h4 class="font-black text-[#071B36]">Description du bien</h4>
                        </div>

                        <p class="mt-4 whitespace-pre-line text-sm leading-8 text-slate-600">
                            <?= $e($description !== '' ? $description : 'Aucune description disponible.') ?>
                        </p>

                    </div>

                </div>

                <!-- LOCALISATION -->
                <div class="rounded-[22px] border border-slate-200 bg-white p-5 shadow-sm sm:p-7">

                    <div class="flex items-center gap-3 border-b border-slate-100 pb-5">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-xl text-[#F5B800]">
                            ⌖
                        </div>

                        <div>
                            <h3 class="font-black text-[#071B36]">Localisation</h3>
                            <p class="mt-1 text-xs text-slate-500">Situation géographique</p>
                        </div>

                    </div>

                    <div class="mt-6 space-y-5">

                        <div class="flex gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F5B800]/10 text-[#B88A00]">◉</span>
                            <div>
                                <p class="text-xs text-slate-500">Région</p>
                                <p class="mt-1 font-bold text-[#071B36]"><?= $e($bien['region_nom'] ?? 'Non renseignée') ?></p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F5B800]/10 text-[#B88A00]">⌖</span>
                            <div>
                                <p class="text-xs text-slate-500">Ville</p>
                                <p class="mt-1 font-bold text-[#071B36]"><?= $e($ville !== '' ? $ville : 'Non renseignée') ?></p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F5B800]/10 text-[#B88A00]">▣</span>
                            <div>
                                <p class="text-xs text-slate-500">Quartier</p>
                                <p class="mt-1 font-bold text-[#071B36]"><?= $e($quartier !== '' ? $quartier : 'Non renseigné') ?></p>
                            </div>
                        </div>

                    </div>

                    <?php if (!empty($bien['latitude']) && !empty($bien['longitude'])): ?>

                        <div class="mt-6 rounded-xl bg-[#071B36] p-4 text-white">
                            <p class="text-xs font-extrabold text-[#F5B800]">Coordonnées GPS</p>
                            <p class="mt-2 break-all text-xs text-slate-300">
                                <?= $e($bien['latitude']) ?>, <?= $e($bien['longitude']) ?>
                            </p>
                        </div>

                    <?php endif; ?>

                </div>

            </section>

            <!-- =================================================
                 GALERIE PHOTOS
            ================================================== -->
            <section class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm">

                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">

                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-xl text-[#F5B800]">▧</div>

                        <div>
                            <h3 class="font-black text-[#071B36]">Galerie du bien</h3>
                            <p class="mt-1 text-xs text-slate-500">Photos de votre annonce immobilière</p>
                        </div>
                    </div>

                    <span class="w-fit rounded-full border border-[#F5B800]/30 bg-[#F5B800]/10 px-4 py-2 text-xs font-extrabold text-[#8A6500]">
                        <?= count($photos) ?> photo<?= count($photos) > 1 ? 's' : '' ?>
                    </span>

                </div>

                <div class="p-5 sm:p-7">

                    <?php if (empty($photos)): ?>

                        <div class="rounded-2xl border border-dashed border-slate-300 bg-[#F4F6F9] px-5 py-12 text-center">

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl text-slate-400">▧</div>

                            <p class="mt-4 font-bold text-[#071B36]">Aucune photo disponible</p>

                            <p class="mt-2 text-sm text-slate-500">
                                Les images de ce bien n'ont pas encore été ajoutées.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            <?php foreach ($photos as $photo): ?>

                                <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-[#F4F6F9]">

                                    <img
                                        src="<?= $e($photo['file_path'] ?? '') ?>"
                                        alt="Photo du bien"
                                        class="h-56 w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >

                                    <?php if (!empty($photo['is_principal'])): ?>
                                        <span class="absolute left-3 top-3 rounded-full bg-[#F5B800] px-3 py-1.5 text-[10px] font-extrabold text-[#071B36] shadow-sm">
                                            ★ Photo principale
                                        </span>
                                    <?php endif; ?>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </section>

            <!-- =================================================
                 QR CODE
            ================================================== -->
            <section class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-5 py-5 sm:px-7">

                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-xl text-[#F5B800]">▦</div>

                        <div>
                            <h3 class="font-black text-[#071B36]">QR code du bien</h3>
                            <p class="mt-1 text-xs text-slate-500">Accès rapide à votre annonce</p>
                        </div>

                    </div>

                </div>

                <div class="p-5 sm:p-7">

                    <?php if (!empty($qrCode['file_path'])): ?>

                        <?php
                        $qrFileName = basename($qrCode['file_path']);
                        $qrUrl = '/immo-sn/stockage/qrcodes/' . rawurlencode($qrFileName);
                        ?>

                        <div class="flex flex-col gap-6 rounded-2xl border border-slate-100 bg-[#F4F6F9] p-5 sm:flex-row sm:items-center sm:p-7">

                            <div class="flex shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <img
                                    src="<?= $e($qrUrl) ?>"
                                    alt="QR code du bien"
                                    class="h-44 w-44 object-contain"
                                >
                            </div>

                            <div class="max-w-xl">

                                <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                    QR code disponible
                                </span>

                                <h4 class="mt-4 text-lg font-black text-[#071B36]">
                                    Partagez votre annonce facilement
                                </h4>

                                <p class="mt-2 text-sm leading-7 text-slate-500">
                                    Ce QR code permet aux personnes intéressées
                                    d'accéder rapidement à la fiche de votre bien.
                                    Vous pouvez le télécharger et le partager.
                                </p>

                                <a
                                    href="/immo-sn/biens/<?= rawurlencode((string)($bien['id'] ?? '')) ?>/qr"
                                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#071B36] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#102B4D]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V3"/>
                                    </svg>
                                    Télécharger le QR code
                                </a>

                            </div>

                        </div>

                    <?php else: ?>

                        <div class="rounded-2xl border border-dashed border-slate-300 bg-[#F4F6F9] p-6 text-center">

                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-white text-2xl text-slate-400">▦</div>

                            <p class="mt-4 font-bold text-[#071B36]">Aucun QR code disponible</p>

                            <p class="mt-2 text-sm text-slate-500">
                                Le QR code de ce bien n'est pas encore généré.
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </section>

        </div>

    </main>

    <!-- =========================================================
         MODAL MODIFICATION
    ========================================================== -->
    <div
        id="modalModification"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#071B36]/75 p-3 backdrop-blur-sm sm:p-5"
        onclick="fermerModalModification(event)"
    >

        <div
            class="max-h-[92vh] w-full min-w-0 max-w-4xl overflow-x-hidden overflow-y-auto rounded-[24px] border border-white/20 bg-[#F4F6F9] shadow-2xl"
            onclick="event.stopPropagation()"
        >

            <!-- EN-TÊTE MODAL -->
            <div class="sticky top-0 z-10 flex items-center justify-between bg-[#071B36] px-5 py-5 text-white sm:px-7">

                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#F5B800]">
                        IMMO-SN · ESPACE AGENCE
                    </p>

                    <h2 class="mt-1 text-xl font-black">
                        Modifier mon bien
                    </h2>

                    <p class="mt-1 text-xs text-slate-300">
                        Mise à jour de votre annonce immobilière
                    </p>
                </div>

                <button
                    type="button"
                    onclick="fermerModalModification()"
                    class="flex h-10 w-10 items-center justify-center rounded-xl text-2xl text-white transition hover:bg-white/10"
                    aria-label="Fermer"
                >
                    &times;
                </button>

            </div>

            <!-- FORMULAIRE -->
            <form
                action="/immo-sn/agence/biens/<?= rawurlencode((string)($bien['id'] ?? '')) ?>/modifier"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-7 p-5 sm:p-7"
            >

                <input type="hidden" name="_csrf_token" value="<?= $e(Session::csrfToken()) ?>">

                <!-- 01. INFORMATIONS GÉNÉRALES -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">

                    <div class="mb-5 flex items-center gap-3 border-b border-slate-100 pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F5B800]/15 font-black text-[#B88A00]">01</span>
                        <h3 class="font-black text-[#071B36]">Informations générales</h3>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Titre du bien *</label>
                            <input type="text" name="titre" required maxlength="255"
                                   value="<?= $e($bien['titre'] ?? '') ?>"
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none transition focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Catégorie *</label>
                            <select name="category_id" required
                                    class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                                <option value="">Choisir une catégorie</option>
                                <?php foreach (($categories ?? []) as $categorie): ?>
                                    <option value="<?= $e($categorie['id']) ?>"
                                        <?= (string)($bien['category_id'] ?? '') === (string)$categorie['id'] ? 'selected' : '' ?>>
                                        <?= $e($categorie['libelle']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Type d'offre *</label>
                            <select name="type_offre" required
                                    class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                                <option value="">Choisir</option>
                                <option value="vente" <?= ($bien['type_offre'] ?? '') === 'vente' ? 'selected' : '' ?>>Vente</option>
                                <option value="location" <?= ($bien['type_offre'] ?? '') === 'location' ? 'selected' : '' ?>>Location</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Prix (FCFA) *</label>
                            <input type="number" name="prix" min="1" step="1" required
                                   value="<?= $e($bien['prix'] ?? '') ?>"
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Superficie (m²) *</label>
                            <input type="number" name="surface" min="1" step="0.01" required
                                   value="<?= $e($bien['superficie'] ?? $bien['surface'] ?? '') ?>"
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Nombre de pièces</label>
                            <input type="number" name="pieces" min="0"
                                   value="<?= $e($bien['pieces'] ?? '') ?>"
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Nombre de chambres</label>
                            <input type="number" name="chambres" min="0"
                                   value="<?= $e($bien['chambres'] ?? '') ?>"
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Salles de bain</label>
                            <input type="number" name="salles_de_bain" min="0"
                                   value="<?= $e($bien['salles_de_bain'] ?? '') ?>"
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                    </div>

                </section>

                <!-- 02. LOCALISATION -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">

                    <div class="mb-5 flex items-center gap-3 border-b border-slate-100 pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F5B800]/15 font-black text-[#B88A00]">02</span>
                        <h3 class="font-black text-[#071B36]">Localisation</h3>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Ville *</label>
                            <select name="ville_id" required
                                    class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                                <option value="">Choisir une ville</option>

                                <?php foreach (($villes ?? []) as $villeOption): ?>
                                    <option value="<?= $e($villeOption['id']) ?>"
                                        <?= (string)($bien['ville_id'] ?? '') === (string)$villeOption['id'] ? 'selected' : '' ?>>
                                        <?= $e(
                                            trim(
                                                (string)($villeOption['ville'] ?? '') .
                                                (!empty($villeOption['quartier']) ? ' — ' . $villeOption['quartier'] : '') .
                                                (!empty($villeOption['region']) ? ' (' . $villeOption['region'] . ')' : '')
                                            )
                                        ) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Latitude</label>
                            <input type="number" name="latitude" step="any"
                                   value="<?= $e($bien['latitude'] ?? '') ?>"
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Longitude</label>
                            <input type="number" name="longitude" step="any"
                                   value="<?= $e($bien['longitude'] ?? '') ?>"
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                    </div>

                </section>

                <!-- 03. DESCRIPTION ET VIDÉO -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">

                    <div class="mb-5 flex items-center gap-3 border-b border-slate-100 pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F5B800]/15 font-black text-[#B88A00]">03</span>
                        <h3 class="font-black text-[#071B36]">Description et vidéo</h3>
                    </div>

                    <div class="space-y-5">

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">Description *</label>
                            <textarea name="description" rows="6" required
                                      class="w-full resize-y rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 leading-7 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10"><?= $e($bien['description'] ?? '') ?></textarea>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#071B36]">URL de la vidéo</label>
                            <input type="url" name="video_url"
                                   value="<?= $e($bien['video_url'] ?? '') ?>"
                                   placeholder="https://..."
                                   class="w-full rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                        </div>

                    </div>

                </section>

                <!-- 04. PHOTOS EXISTANTES -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">

                    <div class="mb-5 flex items-center gap-3 border-b border-slate-100 pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F5B800]/15 font-black text-[#B88A00]">04</span>
                        <h3 class="font-black text-[#071B36]">Photos du bien</h3>
                    </div>

                    <?php if (!empty($photos)): ?>

                        <p class="mb-4 text-sm leading-6 text-slate-500">
                            Sélectionnez les photos à supprimer ou choisissez une nouvelle photo principale.
                        </p>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            <?php foreach ($photos as $photo): ?>

                                <label class="group block cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-[#F4F6F9] transition hover:border-[#F5B800]/50">

                                    <div class="relative">

                                        <img
                                            src="<?= $e($photo['file_path'] ?? '') ?>"
                                            alt="Photo existante"
                                            class="h-40 w-full object-cover transition duration-300 group-hover:brightness-95"
                                        >

                                        <?php if (!empty($photo['is_principal'])): ?>
                                            <span class="absolute left-3 top-3 rounded-full bg-[#F5B800] px-3 py-1.5 text-[10px] font-extrabold text-[#071B36]">
                                                ★ Principale
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <div class="space-y-3 p-4">

                                        <span class="block text-xs font-bold text-[#071B36]">
                                            <?= !empty($photo['is_principal']) ? 'Photo principale actuelle' : 'Photo du bien' ?>
                                        </span>

                                        <span class="flex items-center gap-2 text-xs font-bold text-red-600">
                                            <input type="checkbox"
                                                   name="photos_a_supprimer[]"
                                                   value="<?= $e($photo['id']) ?>"
                                                   class="rounded border-slate-300 accent-red-600">
                                            Supprimer cette photo
                                        </span>

                                        <span class="flex items-center gap-2 text-xs font-bold text-[#B88A00]">
                                            <input type="radio"
                                                   name="photo_principale"
                                                   value="<?= $e($photo['id']) ?>"
                                                   <?= !empty($photo['is_principal']) ? 'checked' : '' ?>
                                                   class="accent-[#F5B800]">
                                            Définir comme principale
                                        </span>

                                    </div>

                                </label>

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <div class="rounded-xl bg-[#F4F6F9] p-4 text-sm text-slate-500">
                            Aucune photo existante.
                        </div>

                    <?php endif; ?>

                    <!-- AJOUT PHOTOS -->
                    <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-[#F4F6F9] p-5">

                        <label class="mb-2 block text-sm font-black text-[#071B36]">
                            Ajouter de nouvelles photos
                        </label>

                        <input type="file"
                               name="photos[]"
                               accept="image/jpeg,image/png"
                               multiple
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm">

                        <p class="mt-2 text-xs leading-5 text-slate-500">
                            Formats acceptés : JPG et PNG. Taille maximale : 5 Mo par photo.
                        </p>

                    </div>

                </section>

                <!-- AVERTISSEMENT -->
                <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                    <span class="mt-0.5 text-lg">⚠</span>
                    <p>
                        Après modification, votre annonce sera remise en attente de validation par l'administration.
                    </p>
                </div>

                <!-- ACTIONS -->
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">

                    <button type="button"
                            onclick="fermerModalModification()"
                            class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-[#071B36] transition hover:bg-slate-50">
                        Annuler
                    </button>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-7 py-3 text-sm font-extrabold text-[#071B36] shadow-sm transition hover:bg-[#E4AA00]">
                        <span>✓</span>
                        Enregistrer les modifications
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- =========================================================
     SCRIPT MODAL
========================================================== -->
<script>
    function ouvrirModalModification() {
        const modal = document.getElementById('modalModification');

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }

    function fermerModalModification(event) {
        if (event && event.target !== event.currentTarget) {
            return;
        }

        const modal = document.getElementById('modalModification');

        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            fermerModalModification();
        }
    });
</script>
