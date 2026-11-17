
<?php

use Core\Session;

$nomAgence = $agence['nom_agence'] ?? 'Mon agence';
$bien = $bien ?? [];
$photos = $photos ?? [];
$qrCode = $qrCode ?? null;

$statuts = [
    'actif' => ['Actif', 'bg-green-100 text-green-800'],
    'en_attente' => ['En attente', 'bg-yellow-100 text-yellow-800'],
    'pending' => ['En attente', 'bg-yellow-100 text-yellow-800'],
    'rejete' => ['Rejeté', 'bg-red-100 text-red-800'],
    'refuse' => ['Refusé', 'bg-red-100 text-red-800'],
    'refusee' => ['Refusé', 'bg-red-100 text-red-800'],
    'expire' => ['Expiré', 'bg-gray-100 text-gray-700'],
    'brouillon' => ['Brouillon', 'bg-blue-100 text-blue-800'],
];

$statutBien = strtolower(trim((string)($bien['statut'] ?? 'inconnu')));

[$labelStatut, $styleStatut] = $statuts[$statutBien] ?? [
    ucfirst(str_replace('_', ' ', $statutBien)),
    'bg-gray-100 text-gray-700'
];

$titre = $bien['titre'] ?? 'Bien sans titre';

$ville = $bien['ville_nom'] ?? '';
$quartier = $bien['quartier_nom'] ?? '';
$localisation = trim($quartier . ($quartier && $ville ? ', ' : '') . $ville);

$description = $bien['description'] ?? '';
?>

<div class="min-h-screen bg-[#faf7f2]">

   <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <!-- CONTENU PRINCIPAL -->
    <main class="min-h-screen lg:ml-[270px]">

        <!-- EN-TÊTE -->
        <header class="sticky top-0 z-30 flex flex-col gap-4 border-b border-[#eee5dc] bg-white/95 px-4 py-4 backdrop-blur sm:flex-row sm:items-center sm:justify-between sm:px-8 sm:py-5">

            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-[#a98260]">
                    Espace professionnel
                </p>

                <h1 class="mt-1 text-xl font-bold text-[#30221b] sm:text-2xl">
                    Détails du bien
                </h1>
            </div>

          <div class="flex flex-wrap items-center gap-3">

    <a href="/immo-sn/agence/biens"
       class="rounded-xl border border-[#eee5dc] px-4 py-3 text-sm font-semibold text-[#30221b] hover:bg-[#faf7f2]">
        ← Retour aux biens
    </a>

    <button
    type="button"
    onclick="ouvrirModalModification()"
    class="rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-bold text-[#30221b] hover:bg-[#e5a900]"
>
    ✎ Modifier ce bien
</button>

</div>

        </header>

        <div class="mx-auto max-w-7xl space-y-7 px-5 py-8 sm:px-8">

            <!-- INTRODUCTION -->
            <section class="rounded-2xl bg-[#30221b] p-7 text-white shadow-lg sm:p-9">
                <p class="text-sm text-[#c8beb7]">
                    Consultation de votre annonce immobilière
                </p>

                <h2 class="mt-2 break-words text-2xl font-bold sm:text-3xl">
                    <?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>
                </h2>

                <p class="mt-2 break-words text-sm text-[#c8beb7]">
                    <?= htmlspecialchars(
                        $localisation !== '' ? $localisation : 'Localisation non renseignée',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>
            </section>

            <!-- INFORMATIONS PRINCIPALES -->
            <section class="grid gap-5 lg:grid-cols-3">

                <div class="rounded-2xl border border-[#eee5dc] bg-white p-6 shadow-sm lg:col-span-2">

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-lg font-bold text-[#30221b]">
                            Informations du bien
                        </h3>

                        <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold <?= $styleStatut ?>">
                            <?= htmlspecialchars($labelStatut, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Prix</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= !empty($bien['prix'])
                                    ? htmlspecialchars((string)$bien['prix'], ENT_QUOTES, 'UTF-8') . ' FCFA'
                                    : 'Non renseigné'
                                ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Catégorie</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= htmlspecialchars($bien['categorie_nom'] ?? 'Non renseignée', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Type d'offre</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= htmlspecialchars($bien['type_offre'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Superficie</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= !empty($bien['superficie'])
                                    ? htmlspecialchars((string)$bien['superficie'], ENT_QUOTES, 'UTF-8') . ' m²'
                                    : 'Non renseignée'
                                ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Nombre de pièces</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= htmlspecialchars((string)($bien['pieces'] ?? 'Non renseigné'), ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Chambres</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= htmlspecialchars((string)($bien['chambres'] ?? 'Non renseigné'), ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Salles de bain</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= htmlspecialchars((string)($bien['salles_de_bain'] ?? 'Non renseigné'), ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Date de publication</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= !empty($bien['created_at'])
                                    ? htmlspecialchars(date('d/m/Y', strtotime($bien['created_at'])), ENT_QUOTES, 'UTF-8')
                                    : 'Non renseignée'
                                ?>
                            </p>
                        </div>

                    </div>

                    <div class="mt-7 border-t border-[#eee5dc] pt-6">
                        <h4 class="font-bold text-[#30221b]">Description</h4>

                        <p class="mt-3 whitespace-pre-line text-sm leading-7 text-gray-600">
                            <?= htmlspecialchars(
                                $description !== '' ? $description : 'Aucune description disponible.',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>
                    </div>

                </div>

                <!-- LOCALISATION -->
                <div class="rounded-2xl border border-[#eee5dc] bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-[#30221b]">
                        Localisation
                    </h3>

                    <div class="mt-5 space-y-4 text-sm">

                        <div>
                            <p class="text-xs text-gray-400">Région</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= htmlspecialchars($bien['region_nom'] ?? 'Non renseignée', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Ville</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= htmlspecialchars($ville !== '' ? $ville : 'Non renseignée', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Quartier</p>
                            <p class="mt-1 font-semibold text-[#30221b]">
                                <?= htmlspecialchars($quartier !== '' ? $quartier : 'Non renseigné', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                    </div>
                </div>

            </section>

            <!-- PHOTOS -->
            <section class="rounded-2xl border border-[#eee5dc] bg-white p-6 shadow-sm">

                <h3 class="text-lg font-bold text-[#30221b]">
                    Photos du bien
                </h3>

                <?php if (empty($photos)): ?>

                    <p class="mt-5 rounded-xl bg-[#faf7f2] p-6 text-center text-sm text-gray-500">
                        Aucune photo disponible pour ce bien.
                    </p>

                <?php else: ?>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        <?php foreach ($photos as $photo): ?>

                            <div class="overflow-hidden rounded-xl border border-[#eee5dc]">
                                <img
                                    src="<?= htmlspecialchars($photo['file_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Photo du bien"
                                    class="h-56 w-full object-cover"
                                    loading="lazy"
                                >

                                <?php if (!empty($photo['is_principal'])): ?>
                                    <p class="bg-[#faf7f2] px-3 py-2 text-xs font-semibold text-[#a98260]">
                                        Photo principale
                                    </p>
                                <?php endif; ?>
                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

            <!-- QR CODE -->
            <section class="rounded-2xl border border-[#eee5dc] bg-white p-6 shadow-sm">

                <h3 class="text-lg font-bold text-[#30221b]">
                    QR code du bien
                </h3>

               <?php if (!empty($qrCode['file_path'])): ?>

    <?php
    $qrFileName = basename($qrCode['file_path']);
    $qrUrl = '/immo-sn/stockage/qrcodes/' . rawurlencode($qrFileName);
    ?>

    <div class="mt-5 flex flex-col items-start gap-4 sm:flex-row sm:items-center">

        <div class="rounded-xl border border-[#eee5dc] bg-white p-3">
            <img
                src="<?= htmlspecialchars($qrUrl, ENT_QUOTES, 'UTF-8') ?>"
                alt="QR code du bien"
                class="h-40 w-40 object-contain"
            >
        </div>

        <div>
            <p class="text-sm text-gray-600">
                Ce QR code permet d'accéder rapidement à votre annonce.
            </p>

           <a
    href="/immo-sn/biens/<?= rawurlencode((string)($bien['id'] ?? '')) ?>/qr"
    class="mt-4 inline-flex items-center gap-2 rounded-xl bg-[#30221b] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#4a3930]"
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

    <p class="text-sm text-gray-500">
        Aucun QR code disponible pour ce bien.
    </p>

<?php endif; ?>

            </section>

        </div>

    </main>
    <!-- MODAL DE MODIFICATION DU BIEN -->
<div
    id="modalModification"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 p-4"
    onclick="fermerModalModification(event)"
>
    <div
    class="max-h-[90vh] w-full min-w-0 max-w-4xl overflow-x-hidden overflow-y-auto rounded-2xl bg-[#faf7f2] shadow-2xl"
        onclick="event.stopPropagation()"
    >

        <!-- EN-TÊTE DU MODAL -->
        <div class="sticky top-0 z-10 flex items-center justify-between bg-[#30221b] px-6 py-5 text-white">
            <div>
                <p class="text-xs uppercase tracking-widest text-[#c8beb7]">
                    Gestion immobilière
                </p>
                <h2 class="mt-1 text-xl font-bold">
                    Modifier mon bien
                </h2>
            </div>

            <button
                type="button"
                onclick="fermerModalModification()"
                class="rounded-lg px-3 py-2 text-2xl text-white hover:bg-white/10"
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
            class="space-y-7 p-6 sm:p-8"
        >

            <input
                type="hidden"
                name="_csrf_token"
                value="<?= htmlspecialchars(Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>"
            >

            <!-- INFORMATIONS GÉNÉRALES -->
            <section>
                <h3 class="mb-4 border-b border-[#e8ded3] pb-3 text-lg font-bold text-[#30221b]">
                    1. Informations générales
                </h3>

                <div class="grid gap-5 sm:grid-cols-2">

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Titre du bien *
                        </label>
                        <input
                            type="text"
                            name="titre"
                            required
                            maxlength="255"
                            value="<?= htmlspecialchars((string)($bien['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Catégorie *
                        </label>
                        <select
                            name="category_id"
                            required
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                            <option value="">Choisir une catégorie</option>

                            <?php foreach (($categories ?? []) as $categorie): ?>
                                <option
                                    value="<?= htmlspecialchars((string)$categorie['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?= (string)($bien['category_id'] ?? '') === (string)$categorie['id'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars((string)$categorie['libelle'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Type d'offre *
                        </label>
                        <select
                            name="type_offre"
                            required
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                            <option value="">Choisir</option>
                            <option value="vente" <?= ($bien['type_offre'] ?? '') === 'vente' ? 'selected' : '' ?>>
                                Vente
                            </option>
                            <option value="location" <?= ($bien['type_offre'] ?? '') === 'location' ? 'selected' : '' ?>>
                                Location
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Prix (FCFA) *
                        </label>
                        <input
                            type="number"
                            name="prix"
                            min="1"
                            step="1"
                            required
                            value="<?= htmlspecialchars((string)($bien['prix'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Superficie (m²) *
                        </label>
                        <input
                            type="number"
                            name="surface"
                            min="1"
                            step="0.01"
                            required
                            value="<?= htmlspecialchars((string)($bien['superficie'] ?? $bien['surface'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Nombre de pièces
                        </label>
                        <input
                            type="number"
                            name="pieces"
                            min="0"
                            value="<?= htmlspecialchars((string)($bien['pieces'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Nombre de chambres
                        </label>
                        <input
                            type="number"
                            name="chambres"
                            min="0"
                            value="<?= htmlspecialchars((string)($bien['chambres'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Salles de bain
                        </label>
                        <input
                            type="number"
                            name="salles_de_bain"
                            min="0"
                            value="<?= htmlspecialchars((string)($bien['salles_de_bain'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                </div>
            </section>

            <!-- LOCALISATION -->
            <section>
                <h3 class="mb-4 border-b border-[#e8ded3] pb-3 text-lg font-bold text-[#30221b]">
                    2. Localisation
                </h3>

                <div class="grid gap-5 sm:grid-cols-2">

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Ville *
                        </label>
                        <select
                            name="ville_id"
                            required
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                            <option value="">Choisir une ville</option>

                            <?php foreach (($villes ?? []) as $villeOption): ?>
                                <option
                                    value="<?= htmlspecialchars((string)$villeOption['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?= (string)($bien['ville_id'] ?? '') === (string)$villeOption['id'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars(
                                        trim(
                                            (string)($villeOption['ville'] ?? '') .
                                            (!empty($villeOption['quartier']) ? ' — ' . $villeOption['quartier'] : '') .
                                            (!empty($villeOption['region']) ? ' (' . $villeOption['region'] . ')' : '')
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Latitude
                        </label>
                        <input
                            type="number"
                            name="latitude"
                            step="any"
                            value="<?= htmlspecialchars((string)($bien['latitude'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Longitude
                        </label>
                        <input
                            type="number"
                            name="longitude"
                            step="any"
                            value="<?= htmlspecialchars((string)($bien['longitude'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                </div>
            </section>

            <!-- DESCRIPTION ET VIDÉO -->
            <section>
                <h3 class="mb-4 border-b border-[#e8ded3] pb-3 text-lg font-bold text-[#30221b]">
                    3. Description et vidéo
                </h3>

                <div class="space-y-5">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Description *
                        </label>
                        <textarea
                            name="description"
                            rows="6"
                            required
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        ><?= htmlspecialchars((string)($bien['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                            URL de la vidéo
                        </label>
                        <input
                            type="url"
                            name="video_url"
                            value="<?= htmlspecialchars((string)($bien['video_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="https://..."
                            class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 outline-none focus:border-[#a98260]"
                        >
                    </div>

                </div>
            </section>

            <!-- PHOTOS EXISTANTES -->
            <section>
                <h3 class="mb-4 border-b border-[#e8ded3] pb-3 text-lg font-bold text-[#30221b]">
                    4. Photos du bien
                </h3>

                <?php if (!empty($photos)): ?>

                    <p class="mb-3 text-sm text-gray-600">
                        Coche les photos que tu souhaites supprimer.
                    </p>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        <?php foreach ($photos as $photo): ?>

                            <label class="relative block cursor-pointer overflow-hidden rounded-xl border border-[#e5d9cc] bg-white">

                                <img
                                    src="<?= htmlspecialchars($photo['file_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Photo existante"
                                    class="h-40 w-full object-cover"
                                >

                                <div class="space-y-2 p-3">

                                    <span class="block text-xs font-semibold text-[#30221b]">
                                        <?= !empty($photo['is_principal']) ? 'Photo principale actuelle' : 'Photo du bien' ?>
                                    </span>

                                    <span class="flex items-center gap-2 text-xs text-red-600">
                                        <input
                                            type="checkbox"
                                            name="photos_a_supprimer[]"
                                            value="<?= htmlspecialchars((string)$photo['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            class="rounded border-gray-300"
                                        >
                                        Supprimer cette photo
                                    </span>

                                    <span class="flex items-center gap-2 text-xs text-[#a98260]">
                                        <input
                                            type="radio"
                                            name="photo_principale"
                                            value="<?= htmlspecialchars((string)$photo['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            <?= !empty($photo['is_principal']) ? 'checked' : '' ?>
                                        >
                                        Définir comme principale
                                    </span>

                                </div>

                            </label>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <p class="rounded-xl bg-white p-4 text-sm text-gray-500">
                        Aucune photo existante.
                    </p>

                <?php endif; ?>

                <div class="mt-5">
                    <label class="mb-2 block text-sm font-semibold text-[#30221b]">
                        Ajouter de nouvelles photos
                    </label>

                    <input
                        type="file"
                        name="photos[]"
                        accept="image/jpeg,image/png"
                        multiple
                        class="w-full rounded-xl border border-[#e5d9cc] bg-white px-4 py-3 text-sm"
                    >

                    <p class="mt-2 text-xs text-gray-500">
                        Formats acceptés : JPG et PNG. Taille maximale : 5 Mo par photo.
                    </p>
                </div>

            </section>

            <!-- AVERTISSEMENT -->
            <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm leading-6 text-yellow-900">
                Après modification, ton annonce sera remise en attente de validation par l'administration.
            </div>

            <!-- BOUTONS -->
            <div class="flex flex-col-reverse gap-3 border-t border-[#e8ded3] pt-5 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="fermerModalModification()"
                    class="rounded-xl border border-[#e5d9cc] bg-white px-6 py-3 text-sm font-semibold text-[#30221b] hover:bg-gray-50"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#f7b500] px-7 py-3 text-sm font-bold text-[#30221b] hover:bg-[#e5a900]"
                >
                    Enregistrer les modifications
                </button>

            </div>

        </form>

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

</div>
