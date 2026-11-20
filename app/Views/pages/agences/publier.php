
<?php

/**
 * PAGE : PUBLICATION D'UN BIEN — IMMO-SN
 */

$categories = is_array($categories ?? null) ? $categories : [];
$villes = is_array($villes ?? null) ? $villes : [];

$success = $success ?? null;
$error = $error ?? null;

$e = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

?>

<div class="min-h-screen bg-[#F5F3ED]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- EN-TÊTE -->
        <header class="sticky top-0 z-30 border-b border-gray-200/80 bg-white/95 backdrop-blur-xl">
            <div class="flex min-h-[76px] flex-col items-start justify-center gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-8">

                <div>
                    <div class="mb-1 flex items-center gap-2 text-xs">
                        <span class="font-semibold text-[#7E9833]">Espace agence</span>
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-500">Publier un bien</span>
                    </div>

                    <h1 class="text-xl font-bold tracking-tight text-[#101D33] sm:text-2xl md:text-3xl">
                        Publier un bien
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Créez votre annonce immobilière en quelques étapes.
                    </p>
                </div>

                <a href="/immo-sn/agence/biens"
                   class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-[#101D33] shadow-sm transition hover:border-[#7E9833] hover:bg-[#F5F3ED]">

                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>

                    <span class="hidden sm:inline">Retour à mes biens</span>
                    <span class="sm:hidden">Retour</span>
                </a>

            </div>
        </header>

        <!-- CONTENU -->
        <div class="px-4 py-6 md:px-8 md:py-8">

            <div class="mx-auto max-w-6xl">

                <!-- BANDEAU INTRODUCTIF -->
                <div class="relative mb-7 overflow-hidden rounded-3xl bg-[#101D33] p-6 text-white shadow-sm md:p-9">

                    <div class="relative z-10 max-w-2xl">

                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-xs font-semibold text-[#C3DE8B]">
                            <span class="h-2 w-2 rounded-full bg-[#A8C66A]"></span>
                            ESPACE PROFESSIONNEL
                        </div>

                        <h2 class="break-words text-2xl font-bold leading-tight tracking-tight md:text-3xl">
                            Donnez de la visibilité à vos biens immobiliers.
                        </h2>

                        <p class="mt-3 max-w-xl text-sm leading-6 text-white/65 md:text-base">
                            Complétez les informations de votre annonce, ajoutez vos photos et soumettez-la à notre équipe pour validation.
                        </p>

                    </div>

                    <div class="pointer-events-none absolute -right-10 -top-16 h-64 w-64 rounded-full border-[35px] border-white/5"></div>
                    <div class="pointer-events-none absolute -bottom-24 right-28 h-48 w-48 rounded-full bg-[#7E9833]/20 blur-2xl"></div>

                </div>

                <!-- MESSAGES -->
                <?php if (!empty($success)): ?>

                    <div role="status" class="mb-6 flex gap-3 rounded-2xl border border-[#7E9833]/25 bg-[#7E9833]/10 p-5 text-[#526C21]">

                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>

                        <div>
                            <p class="font-bold">Annonce envoyée avec succès</p>
                            <p class="mt-1 text-sm"><?= $e($success) ?></p>

                            <a href="/immo-sn/agence/biens" class="mt-3 inline-flex items-center gap-2 text-sm font-bold underline">
                                Consulter mes biens →
                            </a>
                        </div>

                    </div>

                <?php endif; ?>

                <?php if (!empty($error)): ?>

                    <div role="alert" class="mb-6 flex gap-3 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-800">

                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                        </svg>

                        <div>
                            <p class="font-bold">La publication n'a pas abouti</p>
                            <p class="mt-1 text-sm"><?= $e($error) ?></p>
                        </div>

                    </div>

                <?php endif; ?>


                <!-- CARTE DU FORMULAIRE -->
                <div class="overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm">

                    <!-- PROGRESSION -->
                    <div class="border-b border-gray-100 bg-[#FAFAF8] px-5 py-6 md:px-8">

                        <div class="flex flex-wrap items-center justify-between gap-4">

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-[#7E9833]">
                                    Création d'annonce
                                </p>

                                <h2 id="titre-etape" class="mt-1 text-xl font-bold text-[#101D33]">
                                    Informations générales
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Étape <span id="numero-etape">1</span> sur 5
                                </p>
                            </div>

                            <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 text-center">
                                <p id="pourcentage-etape" class="text-xl font-extrabold text-[#7E9833]">
                                    20 %
                                </p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Progression
                                </p>
                            </div>

                        </div>

                        <div class="mt-6 h-2 overflow-hidden rounded-full bg-gray-200">
                            <div id="barre-progression"
                                 class="h-full rounded-full bg-[#7E9833] transition-all duration-300"
                                 style="width:20%">
                            </div>
                        </div>

                        <!-- INDICATEURS -->
                        <div class="mt-7 grid grid-cols-5 gap-2">

                            <?php
                            $etapes = [
                                1 => 'Informations',
                                2 => 'Localisation',
                                3 => 'Détails',
                                4 => 'Photos',
                                5 => 'Résumé',
                            ];
                            ?>

                            <?php foreach ($etapes as $numero => $nom): ?>

                                <div class="indicateur-etape text-center"
                                     data-indicateur="<?= $numero ?>">

                                    <div class="numero-indicateur mx-auto flex h-9 w-9 items-center justify-center rounded-full border-2 border-gray-200 bg-white text-sm font-bold text-gray-400">
                                        <?= $numero ?>
                                    </div>

                                    <p class="mt-2 hidden text-[11px] font-medium text-gray-500 sm:block md:text-xs">
                                        <?= $e($nom) ?>
                                    </p>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>


                    <!-- FORMULAIRE -->
                    <form id="formulaire-publication"
                          action="/immo-sn/agence/publier"
                          method="POST"
                          enctype="multipart/form-data"
                          novalidate
                          class="p-5 md:p-8">

                        <input type="hidden"
                               name="_csrf_token"
                               value="<?= $e(\Core\Session::csrfToken()) ?>">


                        <!-- ÉTAPE 1 -->
                        <section class="etape-formulaire" data-etape="1">

                            <div class="mb-7">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#7E9833]">
                                    Étape 01
                                </span>

                                <h3 class="mt-1 text-xl font-bold text-[#101D33]">
                                    Présentez votre bien
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    Renseignez les informations principales qui permettront aux visiteurs de comprendre votre annonce.
                                </p>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">

                                <div class="md:col-span-2">
                                    <label for="titre" class="label-publication">
                                        Titre de l'annonce <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text"
                                           id="titre"
                                           name="titre"
                                           required
                                           maxlength="200"
                                           placeholder="Ex. : Appartement moderne de 3 chambres à Dakar"
                                           class="champ-publication">

                                    <p class="aide-publication">
                                        Choisissez un titre précis et facile à comprendre.
                                    </p>
                                </div>

                                <div>
                                    <label for="type_offre" class="label-publication">
                                        Type d'offre <span class="text-red-500">*</span>
                                    </label>

                                    <select id="type_offre" name="type_offre" required class="champ-publication">
                                        <option value="vente" selected>Vente</option>
                                        <option value="location">Location</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="category_id" class="label-publication">
                                        Catégorie du bien <span class="text-red-500">*</span>
                                    </label>

                                    <select id="category_id" name="category_id" required class="champ-publication">
                                        <option value="">Sélectionnez une catégorie</option>

                                        <?php foreach ($categories as $categorie): ?>
                                            <option value="<?= $e($categorie['id'] ?? '') ?>">
                                                <?= $e($categorie['libelle'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="prix" class="label-publication">
                                        Prix en FCFA <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">
                                        <input type="number"
                                               id="prix"
                                               name="prix"
                                               required
                                               min="1"
                                               step="1"
                                               placeholder="Ex. : 25000000"
                                               class="champ-publication pr-24">

                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-500">
                                            FCFA
                                        </span>
                                    </div>

                                    <p class="aide-publication">
                                        Pour une location, indiquez le montant du loyer demandé.
                                    </p>
                                </div>

                            </div>

                        </section>


                        <!-- ÉTAPE 2 -->
                        <section class="etape-formulaire hidden" data-etape="2" hidden>

                            <div class="mb-7">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#7E9833]">
                                    Étape 02
                                </span>

                                <h3 class="mt-1 text-xl font-bold text-[#101D33]">
                                    Localisation du bien
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    Indiquez la ville où se trouve votre bien et, si possible, ses coordonnées GPS.
                                </p>
                            </div>

                            <div class="space-y-6">

                                <div>
                                    <label for="ville_id" class="label-publication">
                                        Ville / Localité <span class="text-red-500">*</span>
                                    </label>

                                    <select id="ville_id" name="ville_id" required class="champ-publication">
                                        <option value="">Sélectionnez une ville</option>

                                        <?php foreach ($villes as $ville): ?>

                                            <?php
                                            $libelleVille = trim((string) ($ville['ville'] ?? ''));
                                            $quartierVille = trim((string) ($ville['quartier'] ?? ''));
                                            $regionVille = trim((string) ($ville['region'] ?? ''));

                                            $descriptionVille = $libelleVille;

                                            if ($quartierVille !== '') {
                                                $descriptionVille .= ' — ' . $quartierVille;
                                            }

                                            if ($regionVille !== '') {
                                                $descriptionVille .= ' (' . $regionVille . ')';
                                            }
                                            ?>

                                            <option value="<?= $e($ville['id'] ?? '') ?>">
                                                <?= $e($descriptionVille) ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <p class="aide-publication">
                                        Sélectionnez la localité enregistrée dans IMMO-SN.
                                    </p>
                                </div>


                                <!-- GPS -->
                                <div class="overflow-hidden rounded-2xl border border-gray-200">

                                    <div class="border-b border-gray-100 bg-[#FAFAF8] p-5">

                                        <div class="flex items-start gap-3">

                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#7E9833]/10 text-[#7E9833]">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s8-4.5 8-11a8 8 0 10-16 0c0 6.5 8 11 8 11z"/>
                                                    <circle cx="12" cy="10" r="2.5"/>
                                                </svg>
                                            </div>

                                            <div>
                                                <h4 class="font-bold text-[#101D33]">
                                                    Position GPS
                                                </h4>

                                                <p class="mt-1 text-sm leading-5 text-gray-500">
                                                    Ajoutez les coordonnées géographiques pour faciliter la localisation de votre bien.
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="p-5">

                                        <button type="button"
                                                id="detecter-position"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#101D33] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#1D2E4A] sm:w-auto">

                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3m0 14v3M2 12h3m14 0h3M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                                            </svg>

                                            <span id="texte-bouton">Détecter ma position</span>

                                        </button>

                                        <p id="statut-gps" class="mt-3 text-sm text-gray-500" aria-live="polite">
                                            Vous pouvez également saisir les coordonnées manuellement.
                                        </p>

                                        <div class="mt-5 grid gap-5 sm:grid-cols-2">

                                            <div>
                                                <label for="latitude" class="label-publication">Latitude</label>

                                                <input type="number"
                                                       id="latitude"
                                                       name="latitude"
                                                       min="-90"
                                                       max="90"
                                                       step="any"
                                                       placeholder="Ex. : 14.7167"
                                                       class="champ-publication">
                                            </div>

                                            <div>
                                                <label for="longitude" class="label-publication">Longitude</label>

                                                <input type="number"
                                                       id="longitude"
                                                       name="longitude"
                                                       min="-180"
                                                       max="180"
                                                       step="any"
                                                       placeholder="Ex. : -17.4677"
                                                       class="champ-publication">
                                            </div>

                                        </div>

                                        <p class="mt-4 text-xs leading-5 text-gray-400">
                                            Les coordonnées GPS sont facultatives.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </section>


                        <!-- ÉTAPE 3 -->
                        <section class="etape-formulaire hidden" data-etape="3" hidden>

                            <div class="mb-7">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#7E9833]">
                                    Étape 03
                                </span>

                                <h3 class="mt-1 text-xl font-bold text-[#101D33]">
                                    Caractéristiques et description
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    Donnez aux visiteurs les informations utiles pour mieux apprécier votre bien.
                                </p>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">

                                <div>
                                    <label for="surface" class="label-publication">
                                        Surface en m² <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">
                                        <input type="number"
                                               id="surface"
                                               name="surface"
                                               required
                                               min="1"
                                               step="any"
                                               placeholder="Ex. : 120"
                                               class="champ-publication pr-14">

                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-500">m²</span>
                                    </div>
                                </div>

                                <div>
                                    <label for="pieces" class="label-publication">Nombre total de pièces</label>
                                    <input type="number" id="pieces" name="pieces" min="0" step="1" placeholder="Ex. : 5" class="champ-publication">
                                </div>

                                <div>
                                    <label for="chambres" class="label-publication">Nombre de chambres</label>
                                    <input type="number" id="chambres" name="chambres" min="0" step="1" placeholder="Ex. : 3" class="champ-publication">
                                </div>

                                <div>
                                    <label for="salles_bain" class="label-publication">Salles de bain</label>
                                    <input type="number" id="salles_bain" name="salles_bain" min="0" step="1" placeholder="Ex. : 2" class="champ-publication">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="description" class="label-publication">
                                        Description du bien <span class="text-red-500">*</span>
                                    </label>

                                    <textarea id="description"
                                              name="description"
                                              required
                                              minlength="10"
                                              rows="7"
                                              placeholder="Décrivez le bien, son état, ses équipements, son environnement et ses points forts..."
                                              class="champ-publication resize-y"></textarea>

                                    <div class="mt-2 flex flex-wrap justify-between gap-2 text-xs text-gray-400">
                                        <span>Une description précise inspire confiance.</span>
                                        <span><span id="compteur-description">0</span> caractères</span>
                                    </div>
                                </div>

                            </div>

                        </section>


                        <!-- ÉTAPE 4 -->
                        <section class="etape-formulaire hidden" data-etape="4" hidden>

                            <div class="mb-7">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#7E9833]">
                                    Étape 04
                                </span>

                                <h3 class="mt-1 text-xl font-bold text-[#101D33]">
                                    Photos du bien
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    Présentez votre bien avec des photos nettes et représentatives.
                                </p>
                            </div>

                            <div class="rounded-2xl border-2 border-dashed border-[#B9C98E] bg-[#F7F8F2] p-6 text-center sm:p-10">

                                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-[#7E9833] shadow-sm">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
                                    </svg>
                                </div>

                                <h4 class="text-lg font-bold text-[#101D33]">
                                    Ajoutez les photos de votre annonce
                                </h4>

                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                                    Sélectionnez plusieurs images au format JPG, JPEG ou PNG.
                                </p>

                                <label for="photos" class="mt-6 inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#101D33] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#1D2E4A]">

                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L7 9m5-5l5 5M4 16v4h16v-4"/>
                                    </svg>

                                    Choisir mes photos
                                </label>

                                <input type="file"
                                       id="photos"
                                       name="photos[]"
                                       multiple
                                       accept="image/png,image/jpeg"
                                       class="sr-only">

                                <p id="nombre-photos" class="mt-5 text-sm font-semibold text-[#668025]" aria-live="polite">
                                    Aucune photo sélectionnée.
                                </p>

                            </div>

                            <div id="apercu-photos" class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3"></div>

                            <div class="mt-6 flex gap-3 rounded-2xl border border-gray-200 bg-[#FAFAF8] p-4">

                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#7E9833]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5m0-8h.01"/>
                                </svg>

                                <div>
                                    <p class="text-sm font-bold text-[#101D33]">Conseil pour vos photos</p>
                                    <p class="mt-1 text-sm leading-6 text-gray-500">
                                        Privilégiez des images lumineuses de la façade, du salon, des chambres et des espaces importants.
                                    </p>
                                </div>

                            </div>

                        </section>


                        <!-- ÉTAPE 5 -->
                        <section class="etape-formulaire hidden" data-etape="5" hidden>

                            <div class="mb-7">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#7E9833]">
                                    Étape 05
                                </span>

                                <h3 class="mt-1 text-xl font-bold text-[#101D33]">
                                    Vérifiez votre annonce
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-gray-500">
                                    Prenez quelques instants pour vérifier les informations avant de les envoyer.
                                </p>
                            </div>

                            <div class="overflow-hidden rounded-2xl border border-gray-200">

                                <div class="bg-[#101D33] px-5 py-6 text-white md:px-7">
                                    <p class="text-xs font-bold uppercase tracking-wider text-[#A8C66A]">
                                        Votre annonce immobilière
                                    </p>

                                    <h4 id="recap-titre" class="mt-2 break-words text-xl font-bold md:text-2xl">
                                        Titre de votre annonce
                                    </h4>

                                    <p id="recap-type" class="mt-3 inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/90">
                                        Vente
                                    </p>
                                </div>

                                <div class="divide-y divide-gray-100">

                                    <div class="p-5 md:p-7">
                                        <div class="mb-5 flex items-center justify-between gap-3">
                                            <h5 class="font-bold text-[#101D33]">Informations générales</h5>
                                            <button type="button" data-aller-etape="1" class="text-sm font-bold text-[#668025] hover:underline">Modifier</button>
                                        </div>

                                        <dl class="grid gap-5 sm:grid-cols-2">
                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Catégorie</dt>
                                                <dd id="recap-categorie" class="mt-1 break-words font-semibold text-[#101D33]">—</dd>
                                            </div>

                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Prix</dt>
                                                <dd id="recap-prix" class="mt-1 font-bold text-[#668025]">—</dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <div class="p-5 md:p-7">
                                        <div class="mb-5 flex items-center justify-between gap-3">
                                            <h5 class="font-bold text-[#101D33]">Localisation</h5>
                                            <button type="button" data-aller-etape="2" class="text-sm font-bold text-[#668025] hover:underline">Modifier</button>
                                        </div>

                                        <dl class="grid gap-5 sm:grid-cols-2">
                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Ville / Localité</dt>
                                                <dd id="recap-ville" class="mt-1 break-words font-semibold text-[#101D33]">—</dd>
                                            </div>

                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Latitude</dt>
                                                <dd id="recap-latitude" class="mt-1 font-semibold text-[#101D33]">—</dd>
                                            </div>

                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Longitude</dt>
                                                <dd id="recap-longitude" class="mt-1 font-semibold text-[#101D33]">—</dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <div class="p-5 md:p-7">
                                        <div class="mb-5 flex items-center justify-between gap-3">
                                            <h5 class="font-bold text-[#101D33]">Caractéristiques</h5>
                                            <button type="button" data-aller-etape="3" class="text-sm font-bold text-[#668025] hover:underline">Modifier</button>
                                        </div>

                                        <dl class="grid grid-cols-2 gap-5 sm:grid-cols-4">
                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Surface</dt>
                                                <dd id="recap-surface" class="mt-1 font-semibold text-[#101D33]">—</dd>
                                            </div>

                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Pièces</dt>
                                                <dd id="recap-pieces" class="mt-1 font-semibold text-[#101D33]">—</dd>
                                            </div>

                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Chambres</dt>
                                                <dd id="recap-chambres" class="mt-1 font-semibold text-[#101D33]">—</dd>
                                            </div>

                                            <div>
                                                <dt class="text-xs font-medium text-gray-400">Salles de bain</dt>
                                                <dd id="recap-salles-bain" class="mt-1 font-semibold text-[#101D33]">—</dd>
                                            </div>
                                        </dl>

                                        <div class="mt-6">
                                            <dt class="text-xs font-medium text-gray-400">Description</dt>
                                            <dd id="recap-description" class="mt-2 whitespace-pre-wrap break-words rounded-xl bg-[#F5F3ED] p-4 text-sm leading-6 text-gray-600">—</dd>
                                        </div>
                                    </div>

                                    <div class="p-5 md:p-7">
                                        <div class="mb-4 flex items-center justify-between gap-3">
                                            <h5 class="font-bold text-[#101D33]">Photos</h5>
                                            <button type="button" data-aller-etape="4" class="text-sm font-bold text-[#668025] hover:underline">Modifier</button>
                                        </div>

                                        <p id="recap-photos" class="text-sm text-gray-500">
                                            Aucune photo sélectionnée.
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-6 rounded-2xl border border-[#DCE6C6] bg-[#F7F8F2] p-5">

                                <div class="flex gap-3">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#7E9833]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5m0-8h.01"/>
                                    </svg>

                                    <div>
                                        <p class="font-bold text-[#101D33]">Dernière vérification</p>
                                        <p class="mt-1 text-sm leading-6 text-gray-600">
                                            En cliquant sur « Publier le bien », votre annonce sera enregistrée et envoyée à notre équipe pour modération. Elle sera visible publiquement après validation.
                                        </p>
                                    </div>
                                </div>

                            </div>

                        </section>


                        <!-- NAVIGATION -->
                        <div class="mt-9 flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">

                            <button type="button"
                                    id="bouton-precedent"
                                    class="hidden w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-bold text-[#101D33] transition hover:bg-[#F5F3ED] sm:w-auto">

                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/>
                                </svg>

                                Précédent
                            </button>

                            <div class="flex flex-col gap-3 sm:ml-auto sm:flex-row">

                                <a href="/immo-sn/agence/biens"
                                   class="inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold text-gray-500 transition hover:bg-[#F5F3ED] hover:text-[#101D33]">
                                    Annuler
                                </a>

                                <button type="button"
                                        id="bouton-suivant"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#101D33] px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#1D2E4A] sm:w-auto">

                                    Étape suivante

                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7l7 7-7 7"/>
                                    </svg>
                                </button>

                                <button type="submit"
                                        id="bouton-publier"
                                        class="hidden w-full items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#668025] disabled:cursor-not-allowed disabled:opacity-60 sm:w-auto">

                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>

                                    Publier le bien
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

                <p class="mt-5 text-center text-xs text-gray-400">
                    Les champs marqués d'un astérisque (*) sont obligatoires.
                </p>

            </div>
        </div>

        <!-- PIED DE PAGE -->
        <footer class="mt-auto border-t border-gray-200 bg-white">

            <div class="mx-auto flex max-w-[1450px] flex-col gap-4 px-5 py-6 sm:px-7 md:flex-row md:items-center md:justify-between lg:px-9">

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#101D33] text-lg font-extrabold text-[#A8C66A]">
                        I
                    </div>

                    <div>
                        <p class="text-sm font-extrabold tracking-wide text-[#101D33]">
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

                    <p class="mt-1 text-[11px] text-[#7E9833]">
                        Espace professionnel · Gestion des biens immobiliers
                    </p>
                </div>

            </div>

        </footer>

    </main>

</div>


<style>
.label-publication {
    display: block;
    margin-bottom: 0.55rem;
    color: #101D33;
    font-size: 0.875rem;
    font-weight: 700;
}

.champ-publication {
    display: block;
    width: 100%;
    min-height: 49px;
    border: 1px solid #E1E4E6;
    border-radius: 0.85rem;
    background: #FAFAF8;
    padding: 0.8rem 1rem;
    color: #101D33;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}

.champ-publication:focus {
    border-color: #7E9833;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(126, 152, 51, 0.13);
}

.champ-publication::placeholder {
    color: #A0A6AD;
}

.champ-publication:disabled {
    cursor: not-allowed;
    background: #F0F1ED;
}

.aide-publication {
    margin-top: 0.45rem;
    color: #858D96;
    font-size: 0.75rem;
    line-height: 1.5;
}

.numero-indicateur {
    transition: all 0.25s ease;
}

.numero-indicateur.actif {
    border-color: #7E9833;
    background: #101D33;
    color: white;
    box-shadow: 0 0 0 4px rgba(126, 152, 51, 0.13);
}

.numero-indicateur.termine {
    border-color: #7E9833;
    background: #EAF0DC;
    color: #668025;
}

.indicateur-etape.actif p {
    color: #101D33;
    font-weight: 700;
}

.apercu-photo {
    display: block;
    width: 100%;
    aspect-ratio: 4 / 3;
    overflow: hidden;
    border: 1px solid #E1E4E6;
    border-radius: 0.85rem;
    background: #F5F3ED;
    object-fit: cover;
}

@media (max-width: 640px) {
    .champ-publication {
        min-height: 47px;
    }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const formulaire = document.getElementById('formulaire-publication');

    if (!formulaire) {
        return;
    }

    const sections = Array.from(
        document.querySelectorAll('.etape-formulaire')
    );

    const numeroEtape = document.getElementById('numero-etape');
    const titreEtape = document.getElementById('titre-etape');
    const pourcentageEtape = document.getElementById('pourcentage-etape');
    const barreProgression = document.getElementById('barre-progression');

    const boutonPrecedent = document.getElementById('bouton-precedent');
    const boutonSuivant = document.getElementById('bouton-suivant');
    const boutonPublier = document.getElementById('bouton-publier');

    const titresEtapes = [
        'Informations générales',
        'Localisation du bien',
        'Caractéristiques et description',
        'Photos du bien',
        'Récapitulatif de votre annonce'
    ];

    let etapeActuelle = 1;

    /*
     * AFFICHAGE DES ÉTAPES
     */
    function afficherEtape(numero, faireDefiler = true) {

        etapeActuelle = numero;

        sections.forEach(function (section) {

            const visible = Number(section.dataset.etape) === numero;

            section.hidden = !visible;
            section.classList.toggle('hidden', !visible);

        });

        numeroEtape.textContent = String(numero);
        titreEtape.textContent = titresEtapes[numero - 1];

        const progression = numero * 20;

        pourcentageEtape.textContent = progression + ' %';
        barreProgression.style.width = progression + '%';

        document.querySelectorAll('[data-indicateur]').forEach(function (indicateur) {

            const numeroIndicateur = Number(indicateur.dataset.indicateur);
            const cercle = indicateur.querySelector('.numero-indicateur');

            indicateur.classList.toggle('actif', numeroIndicateur === numero);
            cercle.classList.toggle('actif', numeroIndicateur === numero);
            cercle.classList.toggle('termine', numeroIndicateur < numero);

            cercle.textContent = numeroIndicateur < numero
                ? '✓'
                : String(numeroIndicateur);

        });

        boutonPrecedent.classList.toggle('hidden', numero === 1);
        boutonPrecedent.classList.toggle('sm:flex', numero !== 1);

        boutonSuivant.classList.toggle('hidden', numero === 5);
        boutonPublier.classList.toggle('hidden', numero !== 5);

        if (numero === 5) {
            actualiserRecapitulatif();
        }

        if (faireDefiler) {
            titreEtape.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }


    /*
     * VALIDATION D'UNE ÉTAPE
     */
    function validerEtape(numero) {

        const section = sections.find(function (element) {
            return Number(element.dataset.etape) === numero;
        });

        if (!section) {
            return true;
        }

        const champs = Array.from(
            section.querySelectorAll('input, select, textarea')
        );

        for (const champ of champs) {

            if (champ.disabled) {
                continue;
            }

            if (!champ.checkValidity()) {

                afficherEtape(numero, false);

                champ.reportValidity();
                champ.focus();

                return false;
            }
        }

        if (numero === 2) {

            const latitude = document.getElementById('latitude');
            const longitude = document.getElementById('longitude');

            latitude.setCustomValidity('');
            longitude.setCustomValidity('');

            if (latitude.value.trim() !== '') {

                const valeurLatitude = Number(latitude.value);

                if (
                    !Number.isFinite(valeurLatitude) ||
                    valeurLatitude < -90 ||
                    valeurLatitude > 90
                ) {
                    latitude.setCustomValidity(
                        'La latitude doit être comprise entre -90 et 90.'
                    );

                    latitude.reportValidity();
                    latitude.focus();

                    return false;
                }
            }

            if (longitude.value.trim() !== '') {

                const valeurLongitude = Number(longitude.value);

                if (
                    !Number.isFinite(valeurLongitude) ||
                    valeurLongitude < -180 ||
                    valeurLongitude > 180
                ) {
                    longitude.setCustomValidity(
                        'La longitude doit être comprise entre -180 et 180.'
                    );

                    longitude.reportValidity();
                    longitude.focus();

                    return false;
                }
            }
        }

        return true;
    }


    /*
     * EFFACER LES ERREURS GPS À LA SAISIE
     */
    ['latitude', 'longitude'].forEach(function (id) {

        const champ = document.getElementById(id);

        champ.addEventListener('input', function () {
            champ.setCustomValidity('');
        });

    });


    /*
     * NAVIGATION
     */
    boutonSuivant.addEventListener('click', function () {

        if (!validerEtape(etapeActuelle)) {
            return;
        }

        if (etapeActuelle < 5) {
            afficherEtape(etapeActuelle + 1);
        }
    });

    boutonPrecedent.addEventListener('click', function () {

        if (etapeActuelle > 1) {
            afficherEtape(etapeActuelle - 1);
        }
    });

    document.querySelectorAll('[data-aller-etape]').forEach(function (bouton) {

        bouton.addEventListener('click', function () {

            const destination = Number(bouton.dataset.allerEtape);

            afficherEtape(destination);
        });

    });


    /*
     * RÉCAPITULATIF
     */
    function valeurChamp(id) {

        const champ = document.getElementById(id);

        return champ ? champ.value.trim() : '';
    }

    function texteSelect(id) {

        const select = document.getElementById(id);

        if (!select || select.selectedIndex < 0) {
            return '';
        }

        return select.value
            ? select.options[select.selectedIndex].text.trim()
            : '';
    }

    function afficherValeur(id, valeur, suffixe = '') {

        const element = document.getElementById(id);

        if (!element) {
            return;
        }

        element.textContent = valeur !== ''
            ? valeur + suffixe
            : 'Non renseigné';
    }

    function formaterPrix(valeur) {

        if (valeur === '') {
            return 'Non renseigné';
        }

        const nombre = Number(valeur);

        if (!Number.isFinite(nombre)) {
            return 'Non renseigné';
        }

        return new Intl.NumberFormat('fr-FR', {
            maximumFractionDigits: 0
        }).format(nombre) + ' FCFA';
    }

    function actualiserRecapitulatif() {

        const titre = valeurChamp('titre');
        const type = texteSelect('type_offre');
        const description = valeurChamp('description');

        document.getElementById('recap-titre').textContent =
            titre || 'Titre de votre annonce';

        document.getElementById('recap-type').textContent =
            type || 'Type d’offre non renseigné';

        afficherValeur('recap-categorie', texteSelect('category_id'));

        document.getElementById('recap-prix').textContent =
            formaterPrix(valeurChamp('prix'));

        afficherValeur('recap-ville', texteSelect('ville_id'));
        afficherValeur('recap-latitude', valeurChamp('latitude'));
        afficherValeur('recap-longitude', valeurChamp('longitude'));

        afficherValeur('recap-surface', valeurChamp('surface'), ' m²');
        afficherValeur('recap-pieces', valeurChamp('pieces'));
        afficherValeur('recap-chambres', valeurChamp('chambres'));
        afficherValeur('recap-salles-bain', valeurChamp('salles_bain'));

        document.getElementById('recap-description').textContent =
            description || 'Aucune description renseignée.';

        const photos = document.getElementById('photos').files;
        const recapPhotos = document.getElementById('recap-photos');

        recapPhotos.textContent = photos.length === 0
            ? 'Aucune photo sélectionnée.'
            : photos.length + (
                photos.length > 1
                    ? ' photos sélectionnées.'
                    : ' photo sélectionnée.'
            );
    }

    formulaire.addEventListener('input', function () {

        if (etapeActuelle === 5) {
            actualiserRecapitulatif();
        }

    });

    formulaire.addEventListener('change', function () {

        if (etapeActuelle === 5) {
            actualiserRecapitulatif();
        }

    });


    /*
     * COMPTEUR DE DESCRIPTION
     */
    const description = document.getElementById('description');
    const compteurDescription = document.getElementById('compteur-description');

    function actualiserCompteurDescription() {
        compteurDescription.textContent = String(description.value.length);
    }

    description.addEventListener('input', actualiserCompteurDescription);

    actualiserCompteurDescription();


    /*
     * APERÇU DES PHOTOS
     */
    const inputPhotos = document.getElementById('photos');
    const nombrePhotos = document.getElementById('nombre-photos');
    const apercuPhotos = document.getElementById('apercu-photos');

    inputPhotos.addEventListener('change', function () {

        apercuPhotos.replaceChildren();

        const fichiers = Array.from(inputPhotos.files);

        if (fichiers.length === 0) {

            nombrePhotos.textContent = 'Aucune photo sélectionnée.';
            actualiserRecapitulatif();

            return;
        }

        const fichiersInvalides = fichiers.filter(function (fichier) {

            return ![
                'image/jpeg',
                'image/png'
            ].includes(fichier.type);

        });

        if (fichiersInvalides.length > 0) {

            inputPhotos.value = '';

            nombrePhotos.textContent =
                'Sélection refusée : utilisez uniquement des images JPG ou PNG.';

            apercuPhotos.replaceChildren();

            actualiserRecapitulatif();

            return;
        }

        nombrePhotos.textContent =
            fichiers.length + (
                fichiers.length > 1
                    ? ' photos sélectionnées.'
                    : ' photo sélectionnée.'
            );

        fichiers.forEach(function (fichier) {

            const carte = document.createElement('div');
            carte.className = 'min-w-0';

            const image = document.createElement('img');
            image.className = 'apercu-photo';
            image.alt = 'Aperçu : ' + fichier.name;

            const nom = document.createElement('p');
            nom.className = 'mt-2 break-words text-xs font-medium text-gray-500';
            nom.textContent = fichier.name;

            const urlImage = URL.createObjectURL(fichier);

            image.src = urlImage;

            image.addEventListener('load', function () {
                URL.revokeObjectURL(urlImage);
            }, { once: true });

            carte.appendChild(image);
            carte.appendChild(nom);

            apercuPhotos.appendChild(carte);
        });

        actualiserRecapitulatif();
    });


    /*
     * GÉOLOCALISATION GPS
     */
    const boutonGPS = document.getElementById('detecter-position');
    const texteBoutonGPS = document.getElementById('texte-bouton');
    const statutGPS = document.getElementById('statut-gps');

    const latitude = document.getElementById('latitude');
    const longitude = document.getElementById('longitude');

    boutonGPS.addEventListener('click', function () {

        if (!navigator.geolocation) {

            statutGPS.textContent =
                'Votre navigateur ne prend pas en charge la géolocalisation.';

            return;
        }

        texteBoutonGPS.textContent = 'Localisation en cours...';
        boutonGPS.disabled = true;

        statutGPS.textContent =
            'Veuillez autoriser l’accès à votre position si le navigateur le demande.';

        navigator.geolocation.getCurrentPosition(

            function (position) {

                latitude.value = position.coords.latitude.toFixed(7);
                longitude.value = position.coords.longitude.toFixed(7);

                latitude.setCustomValidity('');
                longitude.setCustomValidity('');

                texteBoutonGPS.textContent = 'Position détectée';

                statutGPS.textContent =
                    'Votre position GPS a été renseignée avec succès.';

                boutonGPS.disabled = false;

                actualiserRecapitulatif();
            },

            function (erreur) {

                let message =
                    'Impossible de récupérer votre position.';

                if (erreur.code === 1) {
                    message =
                        'Accès à la position refusé. Autorisez la géolocalisation dans votre navigateur.';
                } else if (erreur.code === 2) {
                    message =
                        'Votre position est indisponible. Réessayez ou saisissez les coordonnées manuellement.';
                } else if (erreur.code === 3) {
                    message =
                        'Le délai de géolocalisation est dépassé. Réessayez.';
                }

                statutGPS.textContent = message;
                texteBoutonGPS.textContent = 'Réessayer la détection';
                boutonGPS.disabled = false;
            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }

        );

    });


    /*
     * VALIDATION FINALE
     */
    formulaire.addEventListener('submit', function (event) {

        for (let numero = 1; numero <= 4; numero++) {

            if (!validerEtape(numero)) {

                event.preventDefault();

                afficherEtape(numero);

                return;
            }
        }

        boutonPublier.disabled = true;
        boutonPublier.textContent = 'Enregistrement en cours...';

    });


    /*
     * INITIALISATION
     */
    afficherEtape(1, false);

});
</script>
