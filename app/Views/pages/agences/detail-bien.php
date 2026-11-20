<?php

use Core\Session;

$nomAgence = $agence['nom_agence'] ?? 'Mon agence';
$bien = $bien ?? [];
$photos = $photos ?? [];
$qrCode = $qrCode ?? null;

$statuts = [
    'actif' => ['Actif', 'bg-green-50 text-green-700 border-green-200'],
    'en_attente' => ['En attente', 'bg-amber-50 text-amber-700 border-amber-200'],
    'pending' => ['En attente', 'bg-amber-50 text-amber-700 border-amber-200'],
    'rejete' => ['Rejeté', 'bg-red-50 text-red-700 border-red-200'],
    'refuse' => ['Refusé', 'bg-red-50 text-red-700 border-red-200'],
    'refusee' => ['Refusé', 'bg-red-50 text-red-700 border-red-200'],
    'expire' => ['Expiré', 'bg-gray-100 text-gray-700 border-gray-200'],
    'brouillon' => ['Brouillon', 'bg-blue-50 text-blue-700 border-blue-200'],
];

$statutBien = strtolower(trim((string)($bien['statut'] ?? 'inconnu')));

[$labelStatut, $styleStatut] = $statuts[$statutBien] ?? [
    ucfirst(str_replace('_', ' ', $statutBien)),
    'bg-gray-100 text-gray-700 border-gray-200'
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

<div class="min-h-screen bg-[#F5F3ED]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- HEADER -->
        <header class="sticky top-0 z-30 border-b border-[#e5e9e2] bg-white/95 backdrop-blur-xl">

            <div class="flex flex-col justify-between gap-4 px-5 py-5 sm:flex-row sm:items-center sm:px-8">

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#7E9833]">
                        Urban Signature · Espace agence
                    </p>

                    <h1 class="mt-1 text-xl font-extrabold tracking-tight text-[#101D33] sm:text-2xl">
                        Détails du bien
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-3">

                    <a
                        href="/immo-sn/agence/biens"
                        class="inline-flex items-center gap-2 rounded-xl border border-[#dce2d8] bg-white px-4 py-3 text-sm font-semibold text-[#101D33] transition hover:bg-[#F5F3ED]"
                    >
                        <span>←</span>
                        Retour aux biens
                    </a>

                    <button
                        type="button"
                        onclick="ouvrirModalModification()"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#7E9833] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#687f27]"
                    >
                        <span>✎</span>
                        Modifier ce bien
                    </button>

                </div>

            </div>

        </header>

        <!-- CONTENU -->
        <div class="mx-auto max-w-[1500px] space-y-7 px-4 py-6 sm:px-7 sm:py-8 lg:px-9">

            <!-- HERO -->
            <section class="relative isolate overflow-hidden rounded-[26px] bg-[#101D33] px-6 py-8 text-white shadow-xl sm:px-9 sm:py-10">

                <div class="absolute -right-20 -top-24 -z-10 h-80 w-80 rounded-full border-[40px] border-[#7E9833]/10"></div>

                <div class="absolute -bottom-24 right-36 -z-10 h-64 w-64 rounded-full bg-[#71869A]/10 blur-3xl"></div>

                <div class="relative">

                    <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-[#b7c2ce]">
                        <span class="h-2 w-2 rounded-full bg-[#7E9833]"></span>
                        Fiche immobilière
                    </div>

                    <h2 class="mt-5 max-w-4xl break-words text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl lg:text-4xl">
                        <?= $e($titre) ?>
                    </h2>

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-[#c2cbd6]">

                        <span class="inline-flex items-center gap-2">
                            <span class="text-[#7E9833]">⌖</span>
                            <?= $e($localisation !== '' ? $localisation : 'Localisation non renseignée') ?>
                        </span>

                        <span class="hidden h-1 w-1 rounded-full bg-white/30 sm:block"></span>

                        <span class="inline-flex items-center gap-2">
                            <span class="text-[#7E9833]">▤</span>
                            <?= $e($bien['categorie_nom'] ?? 'Catégorie non renseignée') ?>
                        </span>

                    </div>

                    <div class="mt-7 flex flex-wrap items-center gap-3">

                        <span class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-bold <?= $styleStatut ?>">
                            <span class="h-2 w-2 rounded-full bg-current"></span>
                            <?= $e($labelStatut) ?>
                        </span>

                        <?php if (!empty($bien['prix'])): ?>
                            <span class="rounded-full border border-[#7E9833]/30 bg-[#7E9833]/10 px-4 py-2 text-sm font-bold text-[#b5ce76]">
                                <?= $e($bien['prix']) ?> FCFA
                            </span>
                        <?php endif; ?>

                    </div>

                </div>

            </section>

            <!-- INFORMATIONS PRINCIPALES -->
            <section class="grid gap-5 lg:grid-cols-3">

                <!-- FICHE DU BIEN -->
                <div class="rounded-[22px] border border-[#e3e7df] bg-white p-5 shadow-sm sm:p-7 lg:col-span-2">

                    <div class="flex items-center gap-3 border-b border-[#edf0ea] pb-5">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#7E9833]/10 text-xl text-[#7E9833]">
                            ▤
                        </div>

                        <div>
                            <h3 class="font-extrabold text-[#101D33]">
                                Informations du bien
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Caractéristiques principales de votre annonce
                            </p>
                        </div>

                    </div>

                    <!-- CARACTÉRISTIQUES -->
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">

                        <div class="rounded-xl bg-[#F5F3ED] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                Prix
                            </p>
                            <p class="mt-2 text-lg font-extrabold text-[#101D33]">
                                <?= !empty($bien['prix'])
                                    ? $e($bien['prix']) . ' FCFA'
                                    : 'Non renseigné'
                                ?>
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F5F3ED] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                Catégorie
                            </p>
                            <p class="mt-2 font-bold text-[#101D33]">
                                <?= $e($bien['categorie_nom'] ?? 'Non renseignée') ?>
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F5F3ED] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                Type d'offre
                            </p>
                            <p class="mt-2 font-bold text-[#101D33]">
                                <?= $e($bien['type_offre'] ?? 'Non renseigné') ?>
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F5F3ED] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                Superficie
                            </p>
                            <p class="mt-2 font-bold text-[#101D33]">
                                <?= !empty($bien['superficie'])
                                    ? $e($bien['superficie']) . ' m²'
                                    : 'Non renseignée'
                                ?>
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F5F3ED] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                Nombre de pièces
                            </p>
                            <p class="mt-2 font-bold text-[#101D33]">
                                <?= $e($bien['pieces'] ?? 'Non renseigné') ?>
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F5F3ED] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                Chambres
                            </p>
                            <p class="mt-2 font-bold text-[#101D33]">
                                <?= $e($bien['chambres'] ?? 'Non renseigné') ?>
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F5F3ED] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                Salles de bain
                            </p>
                            <p class="mt-2 font-bold text-[#101D33]">
                                <?= $e($bien['salles_de_bain'] ?? 'Non renseigné') ?>
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F5F3ED] p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                Date de publication
                            </p>
            <p class="mt-2 font-bold text-[#101D33]">
                <?= !empty($bien['created_at'])
                    ? $e(date('d/m/Y', strtotime($bien['created_at'])))
                    : 'Non renseignée'
                ?>
            </p>
                        </div>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mt-7 border-t border-[#edf0ea] pt-6">

                        <div class="flex items-center gap-3">
                            <span class="h-8 w-1 rounded-full bg-[#7E9833]"></span>
                            <h4 class="font-extrabold text-[#101D33]">
                                Description du bien
                            </h4>
                        </div>

                        <p class="mt-4 whitespace-pre-line text-sm leading-8 text-[#59677a]">
                            <?= $e($description !== '' ? $description : 'Aucune description disponible.') ?>
                        </p>

                    </div>

                </div>

                <!-- LOCALISATION -->
                <div class="rounded-[22px] border border-[#e3e7df] bg-white p-5 shadow-sm sm:p-7">

                    <div class="flex items-center gap-3 border-b border-[#edf0ea] pb-5">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#101D33] text-xl text-white">
                            ⌖
                        </div>

                        <div>
                            <h3 class="font-extrabold text-[#101D33]">
                                Localisation
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Situation géographique
                            </p>
                        </div>

                    </div>

                    <div class="mt-6 space-y-5">

                        <div class="flex gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#7E9833]/10 text-[#7E9833]">
                                ◉
                            </span>
                            <div>
                                <p class="text-xs text-[#71869A]">Région</p>
                                <p class="mt-1 font-bold text-[#101D33]">
                                    <?= $e($bien['region_nom'] ?? 'Non renseignée') ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#7E9833]/10 text-[#7E9833]">
                                ⌖
                            </span>
                            <div>
                                <p class="text-xs text-[#71869A]">Ville</p>
                                <p class="mt-1 font-bold text-[#101D33]">
                                    <?= $e($ville !== '' ? $ville : 'Non renseignée') ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#7E9833]/10 text-[#7E9833]">
                                ▣
                            </span>
                            <div>
                                <p class="text-xs text-[#71869A]">Quartier</p>
                                <p class="mt-1 font-bold text-[#101D33]">
                                    <?= $e($quartier !== '' ? $quartier : 'Non renseigné') ?>
                                </p>
                            </div>
                        </div>

                    </div>

                    <?php if (!empty($bien['latitude']) && !empty($bien['longitude'])): ?>

                        <div class="mt-6 rounded-xl bg-[#101D33] p-4 text-white">
                            <p class="text-xs font-bold text-[#b5ce76]">
                                Coordonnées GPS
                            </p>

                            <p class="mt-2 break-all text-xs text-[#d0d8e1]">
                                <?= $e($bien['latitude']) ?>,
                                <?= $e($bien['longitude']) ?>
                            </p>
                        </div>

                    <?php endif; ?>

                </div>

            </section>

            <!-- PHOTOS -->
            <section class="overflow-hidden rounded-[22px] border border-[#e3e7df] bg-white shadow-sm">

                <div class="flex flex-col gap-3 border-b border-[#edf0ea] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">

                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#7E9833]/10 text-xl text-[#7E9833]">
                            ▧
                        </div>

                        <div>
                            <h3 class="font-extrabold text-[#101D33]">
                                Galerie du bien
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Photos de votre annonce immobilière
                            </p>
                        </div>
                    </div>

                    <span class="w-fit rounded-full bg-[#F5F3ED] px-4 py-2 text-xs font-bold text-[#101D33]">
                        <?= count($photos) ?> photo<?= count($photos) > 1 ? 's' : '' ?>
                    </span>

                </div>

                <div class="p-5 sm:p-7">

                    <?php if (empty($photos)): ?>

                        <div class="rounded-2xl border border-dashed border-[#dce2d8] bg-[#F5F3ED] px-5 py-12 text-center">

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl text-[#71869A]">
                                ▧
                            </div>

                            <p class="mt-4 font-bold text-[#101D33]">
                                Aucune photo disponible
                            </p>

                            <p class="mt-2 text-sm text-gray-500">
                                Les images de ce bien n'ont pas encore été ajoutées.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            <?php foreach ($photos as $photo): ?>

                                <div class="group relative overflow-hidden rounded-2xl border border-[#e3e7df] bg-[#F5F3ED]">

                                    <img
                                        src="<?= $e($photo['file_path'] ?? '') ?>"
                                        alt="Photo du bien"
                                        class="h-56 w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >

                                    <?php if (!empty($photo['is_principal'])): ?>

                                        <span class="absolute left-3 top-3 rounded-full bg-[#7E9833] px-3 py-1.5 text-[10px] font-bold text-white shadow-sm">
                                            ★ Photo principale
                                        </span>

                                    <?php endif; ?>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </section>

            <!-- QR CODE -->
            <section class="overflow-hidden rounded-[22px] border border-[#e3e7df] bg-white shadow-sm">

                <div class="border-b border-[#edf0ea] px-5 py-5 sm:px-7">

                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#101D33] text-xl text-white">
                            ▦
                        </div>

                        <div>
                            <h3 class="font-extrabold text-[#101D33]">
                                QR code du bien
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Accès rapide à votre annonce
                            </p>
                        </div>

                    </div>

                </div>

                <div class="p-5 sm:p-7">

                    <?php if (!empty($qrCode['file_path'])): ?>

                        <?php
                        $qrFileName = basename($qrCode['file_path']);
                        $qrUrl = '/immo-sn/stockage/qrcodes/' . rawurlencode($qrFileName);
                        ?>

                        <div class="flex flex-col gap-6 rounded-2xl bg-[#F5F3ED] p-5 sm:flex-row sm:items-center sm:p-7">

                            <div class="flex shrink-0 items-center justify-center rounded-2xl border border-[#e3e7df] bg-white p-4 shadow-sm">
                                <img
                                    src="<?= $e($qrUrl) ?>"
                                    alt="QR code du bien"
                                    class="h-44 w-44 object-contain"
                                >
                            </div>

                            <div class="max-w-xl">

                                <span class="inline-flex rounded-full bg-[#7E9833]/10 px-3 py-1.5 text-xs font-bold text-[#687f27]">
                                    QR code disponible
                                </span>

                                <h4 class="mt-4 text-lg font-extrabold text-[#101D33]">
                                    Partagez votre annonce facilement
                                </h4>

                                <p class="mt-2 text-sm leading-7 text-[#71869A]">
                                    Ce QR code permet aux personnes intéressées
                                    d'accéder rapidement à la fiche de votre bien.
                                    Vous pouvez le télécharger et le partager.
                                </p>

                                <a
                                    href="/immo-sn/biens/<?= rawurlencode((string)($bien['id'] ?? '')) ?>/qr"
                                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#101D33] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#1c304e]"
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

                        <div class="rounded-2xl bg-[#F5F3ED] p-6 text-center">

                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-white text-2xl text-[#71869A]">
                                ▦
                            </div>

                            <p class="mt-4 font-bold text-[#101D33]">
                                Aucun QR code disponible
                            </p>

                            <p class="mt-2 text-sm text-gray-500">
                                Le QR code de ce bien n'est pas encore généré.
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </section>

        </div>

    </main>

    <!-- MODAL MODIFICATION -->
    <div
        id="modalModification"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#101D33]/70 p-3 backdrop-blur-sm sm:p-5"
        onclick="fermerModalModification(event)"
    >

        <div
            class="max-h-[92vh] w-full min-w-0 max-w-4xl overflow-x-hidden overflow-y-auto rounded-[24px] border border-white/20 bg-[#F5F3ED] shadow-2xl"
            onclick="event.stopPropagation()"
        >

            <!-- EN-TÊTE MODAL -->
            <div class="sticky top-0 z-10 flex items-center justify-between bg-[#101D33] px-5 py-5 text-white sm:px-7">

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#b5ce76]">
                        Urban Signature
                    </p>

                    <h2 class="mt-1 text-xl font-extrabold">
                        Modifier mon bien
                    </h2>

                    <p class="mt-1 text-xs text-[#b8c4d1]">
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

                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= $e(Session::csrfToken()) ?>"
                >

                <!-- 1. INFORMATIONS GÉNÉRALES -->
                <section class="rounded-2xl border border-[#e3e7df] bg-white p-5 sm:p-6">

                    <div class="mb-5 flex items-center gap-3 border-b border-[#edf0ea] pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#7E9833]/10 font-extrabold text-[#7E9833]">
                            01
                        </span>
                        <h3 class="font-extrabold text-[#101D33]">
                            Informations générales
                        </h3>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Titre du bien *
                            </label>
                            <input
                                type="text"
                                name="titre"
                                required
                                maxlength="255"
                                value="<?= $e($bien['titre'] ?? '') ?>"
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Catégorie *
                            </label>
                            <select
                                name="category_id"
                                required
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                                <option value="">Choisir une catégorie</option>
                                <?php foreach (($categories ?? []) as $categorie): ?>
                                    <option
                                        value="<?= $e($categorie['id']) ?>"
                                        <?= (string)($bien['category_id'] ?? '') === (string)$categorie['id'] ? 'selected' : '' ?>
                                    >
                                        <?= $e($categorie['libelle']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Type d'offre *
                            </label>
                            <select
                                name="type_offre"
                                required
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                                <option value="">Choisir</option>
                                <option value="vente" <?= ($bien['type_offre'] ?? '') === 'vente' ? 'selected' : '' ?>>Vente</option>
                                <option value="location" <?= ($bien['type_offre'] ?? '') === 'location' ? 'selected' : '' ?>>Location</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Prix (FCFA) *
                            </label>
                            <input
                                type="number"
                                name="prix"
                                min="1"
                                step="1"
                                required
                                value="<?= $e($bien['prix'] ?? '') ?>"
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Superficie (m²) *
                            </label>
                            <input
                                type="number"
                                name="surface"
                                min="1"
                                step="0.01"
                                required
                                value="<?= $e($bien['superficie'] ?? $bien['surface'] ?? '') ?>"
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Nombre de pièces
                            </label>
                            <input
                                type="number"
                                name="pieces"
                                min="0"
                                value="<?= $e($bien['pieces'] ?? '') ?>"
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Nombre de chambres
                            </label>
                            <input
                                type="number"
                                name="chambres"
                                min="0"
                                value="<?= $e($bien['chambres'] ?? '') ?>"
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Salles de bain
                            </label>
                            <input
                                type="number"
                                name="salles_de_bain"
                                min="0"
                                value="<?= $e($bien['salles_de_bain'] ?? '') ?>"
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                    </div>

                </section>

                <!-- 2. LOCALISATION -->
                <section class="rounded-2xl border border-[#e3e7df] bg-white p-5 sm:p-6">

                    <div class="mb-5 flex items-center gap-3 border-b border-[#edf0ea] pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#7E9833]/10 font-extrabold text-[#7E9833]">
                            02
                        </span>
                        <h3 class="font-extrabold text-[#101D33]">
                            Localisation
                        </h3>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Ville *
                            </label>
                            <select
                                name="ville_id"
                                required
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                                <option value="">Choisir une ville</option>

                                <?php foreach (($villes ?? []) as $villeOption): ?>
                                    <option
                                        value="<?= $e($villeOption['id']) ?>"
                                        <?= (string)($bien['ville_id'] ?? '') === (string)$villeOption['id'] ? 'selected' : '' ?>
                                    >
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
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Latitude
                            </label>
                            <input
                                type="number"
                                name="latitude"
                                step="any"
                                value="<?= $e($bien['latitude'] ?? '') ?>"
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Longitude
                            </label>
                            <input
                                type="number"
                                name="longitude"
                                step="any"
                                value="<?= $e($bien['longitude'] ?? '') ?>"
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                    </div>

                </section>

                <!-- 3. DESCRIPTION ET VIDEO -->
                <section class="rounded-2xl border border-[#e3e7df] bg-white p-5 sm:p-6">

                    <div class="mb-5 flex items-center gap-3 border-b border-[#edf0ea] pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#7E9833]/10 font-extrabold text-[#7E9833]">
                            03
                        </span>
                        <h3 class="font-extrabold text-[#101D33]">
                            Description et vidéo
                        </h3>
                    </div>

                    <div class="space-y-5">

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                Description *
                            </label>
                            <textarea
                                name="description"
                                rows="6"
                                required
                                class="w-full resize-y rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 leading-7 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            ><?= $e($bien['description'] ?? '') ?></textarea>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#101D33]">
                                URL de la vidéo
                            </label>
                            <input
                                type="url"
                                name="video_url"
                                value="<?= $e($bien['video_url'] ?? '') ?>"
                                placeholder="https://..."
                                class="w-full rounded-xl border border-[#dce2d8] bg-[#FAFAF8] px-4 py-3 outline-none focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                    </div>

                </section>

                <!-- 4. PHOTOS EXISTANTES -->
                <section class="rounded-2xl border border-[#e3e7df] bg-white p-5 sm:p-6">

                    <div class="mb-5 flex items-center gap-3 border-b border-[#edf0ea] pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#7E9833]/10 font-extrabold text-[#7E9833]">
                            04
                        </span>
                        <h3 class="font-extrabold text-[#101D33]">
                            Photos du bien
                        </h3>
                    </div>

                    <?php if (!empty($photos)): ?>

                        <p class="mb-4 text-sm leading-6 text-[#71869A]">
                            Sélectionnez les photos à supprimer ou choisissez une nouvelle photo principale.
                        </p>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            <?php foreach ($photos as $photo): ?>

                                <label class="group block cursor-pointer overflow-hidden rounded-2xl border border-[#e3e7df] bg-[#F5F3ED]">

                                    <div class="relative">

                                        <img
                                            src="<?= $e($photo['file_path'] ?? '') ?>"
                                            alt="Photo existante"
                                            class="h-40 w-full object-cover transition duration-300 group-hover:brightness-95"
                                        >

                                        <?php if (!empty($photo['is_principal'])): ?>
                                            <span class="absolute left-3 top-3 rounded-full bg-[#7E9833] px-3 py-1.5 text-[10px] font-bold text-white">
                                                ★ Principale
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <div class="space-y-3 p-4">

                                        <span class="block text-xs font-bold text-[#101D33]">
                                            <?= !empty($photo['is_principal']) ? 'Photo principale actuelle' : 'Photo du bien' ?>
                                        </span>

                                        <span class="flex items-center gap-2 text-xs text-red-600">
                                            <input
                                                type="checkbox"
                                                name="photos_a_supprimer[]"
                                                value="<?= $e($photo['id']) ?>"
                                                class="rounded border-gray-300 accent-red-600"
                                            >
                                            Supprimer cette photo
                                        </span>

                                        <span class="flex items-center gap-2 text-xs font-medium text-[#687f27]">
                                            <input
                                                type="radio"
                                                name="photo_principale"
                                                value="<?= $e($photo['id']) ?>"
                                                <?= !empty($photo['is_principal']) ? 'checked' : '' ?>
                                                class="accent-[#7E9833]"
                                            >
                                            Définir comme principale
                                        </span>

                                    </div>

                                </label>

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <div class="rounded-xl bg-[#F5F3ED] p-4 text-sm text-[#71869A]">
                            Aucune photo existante.
                        </div>

                    <?php endif; ?>

                    <!-- AJOUT PHOTOS -->
                    <div class="mt-6 rounded-xl border border-dashed border-[#cbd5c1] bg-[#F5F3ED] p-5">

                        <label class="mb-2 block text-sm font-bold text-[#101D33]">
                            Ajouter de nouvelles photos
                        </label>

                        <input
                            type="file"
                            name="photos[]"
                            accept="image/jpeg,image/png"
                            multiple
                            class="w-full rounded-xl border border-[#dce2d8] bg-white px-4 py-3 text-sm"
                        >

                        <p class="mt-2 text-xs leading-5 text-[#71869A]">
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
                <div class="flex flex-col-reverse gap-3 border-t border-[#dce2d8] pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="fermerModalModification()"
                        class="rounded-xl border border-[#dce2d8] bg-white px-6 py-3 text-sm font-bold text-[#101D33] transition hover:bg-[#F5F3ED]"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-7 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#687f27]"
                    >
                        <span>✓</span>
                        Enregistrer les modifications
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

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