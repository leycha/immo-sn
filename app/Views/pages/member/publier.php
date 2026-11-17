
<?php

/**
 * PAGE : PUBLICATION OU CORRECTION D'UN BIEN — IMMOSN
 */

$categories = $categories ?? [];
$villes = $villes ?? [];
$success = $success ?? null;
$error = $error ?? null;
$bien = is_array($bien ?? null) ? $bien : [];

$modeModification = !empty($modeModification);

$bienId = (string) ($bien['id'] ?? '');

$motifRejet = trim((string) ($bien['motif_rejet'] ?? ''));

$valeur = static function (string $champ, mixed $defaut = '') use ($bien): string {
    return htmlspecialchars(
        (string) ($bien[$champ] ?? $defaut),
        ENT_QUOTES,
        'UTF-8'
    );
};

$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

?>


<div class="min-h-screen bg-[#faf7f2]">

    <!-- EN-TÊTE — MÊME DESIGN QUE MES BIENS -->
    <header class="sticky top-0 z-20 border-b border-[#eadfd5] bg-[#fffdf9]/95 backdrop-blur">

       <div class="flex min-h-[76px] flex-col items-start justify-center gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 md:px-8">
            <div>
                <div class="mb-1 flex items-center gap-2 text-xs text-[#a98260]">
                    <span>Espace membre</span>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-3.5 w-3.5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m9 18 6-6-6-6"/>
                    </svg>

                   <span class="text-[#8f8176]">
    <?= $modeModification ? 'Corriger mon annonce' : 'Publier un bien' ?>
</span>
                </div>

              <h1 class="text-2xl font-bold tracking-tight text-[#30221b] md:text-3xl">
    <?= $modeModification ? 'Corriger mon annonce' : 'Publier un bien' ?>
</h1>

<p class="mt-1 text-sm text-[#8f8176]">
    <?= $modeModification
        ? 'Corrigez les informations signalées par le modérateur.'
        : 'Suivez les étapes pour créer votre annonce immobilière.' ?>
</p>
            </div>

            <a href="/immo-sn/member/mes-biens"
               class="inline-flex items-center w-full justify-center sm:w-auto gap-2 rounded-xl border border-[#eadfd5] bg-white px-4 py-3 text-sm font-bold text-[#30221b] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#f4eadf] sm:px-5">

                <span aria-hidden="true"></span>

                <span class="hidden sm:inline">Retour à mes biens</span>
                <span class="sm:hidden">Retour</span>
            </a>

        </div>

    </header>

    <!-- CONTENU -->
    <main class="px-5 py-6 md:px-8 md:py-8">

        <div class="mx-auto max-w-7xl">


        <!-- MESSAGES -->
        <?php if (!empty($success)): ?>

            <div
                class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800"
                role="status"
            >
                <p class="font-semibold">Publication réussie</p>
                <p class="mt-1 text-sm">
                    <?= $e($success) ?>
                </p>

                <a
                    href="/immo-sn/member/mes-biens"
                    class="mt-3 inline-block font-semibold underline"
                >
                    Voir mes biens
                </a>
            </div>

        <?php endif; ?>


        <?php if (!empty($error)): ?>
            <?php if ($modeModification): ?>
    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-900">
        <h2 class="font-bold">Votre annonce doit être corrigée</h2>

        <p class="mt-2 text-sm">
            <?php if ($motifRejet !== ''): ?>
                Motif indiqué par le modérateur :
                <?= $e($motifRejet) ?>
            <?php else: ?>
                Aucun motif détaillé n'est enregistré. Vérifiez les informations
                de votre annonce avant de la soumettre à nouveau.
            <?php endif; ?>
        </p>
    </div>
<?php endif; ?>

            <div
                class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800"
                role="alert"
            >
                <p class="font-semibold">
                    Une erreur est survenue
                </p>

                <p class="mt-1 text-sm">
                    <?= $e($error) ?>
                </p>
            </div>

        <?php endif; ?>


        <!-- CARTE PRINCIPALE -->
        <div class="overflow-hidden rounded-3xl border border-[#e8ded2] bg-white shadow-sm">

            <!-- BARRE DE PROGRESSION -->
            <div class="border-b border-gray-100 px-5 py-6 sm:px-8">

                <div class="mb-5 flex items-center justify-between gap-3">

                 <div class="min-w-0">
                        <p class="text-sm text-gray-500">
                            Étape <span id="numero-etape">1</span> sur 5
                        </p>

                        <h2
                            id="titre-etape"
                            class="mt-1 break-words text-xl font-bold text-[#30221b]"  >
                            Informations générales
                        </h2>
                    </div>

                    <span
                        id="pourcentage-etape"
                        class="rounded-full bg-[#f5ede5] px-3 py-2 text-sm font-bold text-[#725238] shrink-0 "
                    >
                        20 %
                    </span>

                </div>

                <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                    <div
                        id="barre-progression"
                        class="h-full rounded-full bg-[#a98260] transition-all duration-300"
                        style="width: 20%"
                    ></div>
                </div>

                <!-- INDICATEURS DES ÉTAPES -->
                <div class="mt-6 grid grid-cols-5 gap-2">

                    <?php
                    $etapes = [
                        ['numero' => 1, 'nom' => 'Infos'],
                        ['numero' => 2, 'nom' => 'Lieu'],
                        ['numero' => 3, 'nom' => 'Détails'],
                        ['numero' => 4, 'nom' => 'Photos'],
                        ['numero' => 5, 'nom' => 'Résumé'],
                    ];
                    ?>

                    <?php foreach ($etapes as $etape): ?>

                        <div
                            class="indicateur-etape text-center"
                            data-indicateur="<?= $etape['numero'] ?>"
                        >
                            <div
                                class="numero-indicateur mx-auto flex h-9 w-9 items-center justify-center rounded-full border-2 border-gray-200 bg-white text-sm font-bold text-gray-500"
                            >
                                <?= $etape['numero'] ?>
                            </div>

                            <p class="mt-2 text-xs font-medium text-gray-500 sm:text-sm">
                                <?= $e($etape['nom']) ?>
                            </p>
                        </div>

                    <?php endforeach; ?>

                </div>

            </div>


            <!-- FORMULAIRE UNIQUE -->
            <form
                id="formulaire-publication"
                action="<?= $modeModification
    ? '/immo-sn/member/biens/modifier/' . rawurlencode($bienId)
    : '/immo-sn/member/publier' ?>"
                method="POST"
                enctype="multipart/form-data"
                novalidate
                class="p-5 sm:p-8"
            >

                <!-- =========================================
                     ÉTAPE 1 : INFORMATIONS GÉNÉRALES
                ========================================== -->
                <section
                    class="etape-formulaire"
                    data-etape="1"
                >

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-[#30221b]">
                            Présentez votre bien
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Commencez par les informations principales de votre annonce.
                        </p>
                    </div>


                    <div class="space-y-5">

                        <!-- TITRE -->
                        <div>
                            <label
                                for="titre"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Titre de l'annonce
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="titre"
                                name="titre"
                                required
                                maxlength="200"
                                placeholder="Ex. : Appartement moderne de 3 chambres à Dakar"
                                class="champ-publication"
                                value="<?= $valeur('titre') ?>"
                            >

                            <p class="mt-1 text-xs text-gray-500">
                                Choisissez un titre clair qui décrit votre bien.
                            </p>
                        </div>


                        <!-- TYPE D'OFFRE -->
                        <div>
                            <label
                                for="type_offre"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Type d'offre
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="type_offre"
                                name="type_offre"
                                required
                                class="champ-publication"
                            >
                               <option value="vente" <?= strtolower((string)($bien['type_offre'] ?? 'vente')) === 'vente' ? 'selected' : '' ?>>
    Vente
</option>

<option value="location" <?= strtolower((string)($bien['type_offre'] ?? '')) === 'location' ? 'selected' : '' ?>>
    Location
</option>
                            </select>
                        </div>


                        <!-- CATÉGORIE -->
                        <div>
                            <label
                                for="category_id"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Catégorie du bien
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="category_id"
                                name="category_id"
                                required
                                class="champ-publication"
                            >
                                <option value="">
                                    Sélectionnez une catégorie
                                </option>

                                <?php foreach ($categories as $categorie): ?>
<option
    value="<?= $e($categorie['id'] ?? '') ?>"
    <?= (string)($categorie['id'] ?? '') === (string)($bien['category_id'] ?? '') ? 'selected' : '' ?>
>
    <?= $e($categorie['libelle'] ?? '') ?>
</option>
                                <?php endforeach; ?>

                            </select>
                        </div>


                        <!-- PRIX -->
                        <div>
                            <label
                                for="prix"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Prix en FCFA
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input
                                    type="number"
                                    id="prix"
                                    name="prix"
                                    required
                                    min="1"
                                    step="1"
                                    placeholder="Ex. : 25000000"
                                    class="champ-publication pr-20"
                                    value="<?= $valeur('prix') ?>"
                                >

                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-500">
                                    FCFA
                                </span>
                            </div>

                            <p class="mt-1 text-xs text-gray-500">
                                Indiquez le prix de vente ou le loyer demandé.
                            </p>
                        </div>

                    </div>

                </section>


                <!-- =========================================
                     ÉTAPE 2 : LOCALISATION
                ========================================== -->
                <section
                    class="etape-formulaire hidden"
                    data-etape="2"
                    hidden
                >

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-[#30221b]">
                            Où se trouve votre bien ?
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Renseignez sa localisation pour aider les visiteurs à le trouver.
                        </p>
                    </div>


                    <div class="space-y-5">

                       
<!-- VILLE -->
<div>
    <label
        for="ville_id"
        class="mb-2 block text-sm font-semibold text-[#30221b]"
    >
        Ville / Localité
        <span class="text-red-500">*</span>
    </label>

    <select
        id="ville_id"
        name="ville_id"
        required
        class="champ-publication"
    >
        <option value="">
            Sélectionnez une ville
        </option>

        <?php foreach ($villes as $ville): ?>

            <?php
            $libelleVille = trim(
                (string) ($ville['ville'] ?? '')
            );

            $quartierVille = trim(
                (string) ($ville['quartier'] ?? '')
            );

            $regionVille = trim(
                (string) ($ville['region'] ?? '')
            );

            $descriptionVille = $libelleVille;

            if ($quartierVille !== '') {
                $descriptionVille .= ' — ' . $quartierVille;
            }

            if ($regionVille !== '') {
                $descriptionVille .= ' (' . $regionVille . ')';
            }

            // Ville déjà enregistrée pour le bien en cours de modification
            $villeSelectionnee = (string) ($bien['ville_id'] ?? '');

            $villeOption = (string) ($ville['id'] ?? '');
            ?>

            <option
                value="<?= $e($villeOption) ?>"
                data-ville="<?= $e($libelleVille) ?>"
                data-quartier="<?= $e($quartierVille) ?>"
                <?= $villeOption === $villeSelectionnee ? 'selected' : '' ?>
            >
                <?= $e($descriptionVille) ?>
            </option>

        <?php endforeach; ?>

    </select>

    <p class="mt-1 text-xs text-gray-500">
        Les options proviennent des localités enregistrées dans ImmoSN.
    </p>
</div>



                        <!-- QUARTIER -->
                        <div>
                            <label
                                for="quartier"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Quartier
                            </label>

                            <input
                                type="text"
                                id="quartier"
                                name="quartier"
                                maxlength="150"
                                placeholder="Ex. : Liberté 6, Almadies..."
                                class="champ-publication"
                            >
                        </div>


                        <!-- ADRESSE -->
                        <div>
                            <label
                                for="adresse"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Adresse précise
                            </label>

                            <input
                                type="text"
                                id="adresse"
                                name="adresse"
                                maxlength="255"
                                placeholder="Rue, numéro ou indication utile"
                                class="champ-publication"
                            >
                        </div>


                        <!-- GPS -->
                        <div class="rounded-2xl border border-[#e8ded2] bg-[#faf7f2] p-5">

                            <div class="mb-4">
                                <h4 class="font-bold text-[#30221b]">
                                    Position GPS
                                </h4>

                                <p class="mt-1 text-sm text-gray-600">
                                    Autorisez la géolocalisation de votre navigateur pour renseigner les coordonnées.
                                </p>
                            </div>

                            <button
                                type="button"
                                id="detecter-position"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 py-3 font-semibold text-white transition hover:bg-[#5a402d] sm:w-auto"
                            >
                                <span aria-hidden="true">◎</span>
                                <span id="texte-bouton">
                                    Détecter ma position
                                </span>
                            </button>

                            <p
                                id="statut-gps"
                                class="mt-3 text-sm text-gray-600"
                                aria-live="polite"
                            >
                                Vous pouvez aussi saisir les coordonnées manuellement.
                            </p>


                            <div class="mt-5 grid gap-4 sm:grid-cols-2">

                                <div>
                                    <label
                                        for="latitude"
                                        class="mb-2 block text-sm font-semibold text-[#30221b]"
                                    >
                                        Latitude
                                    </label>

                                    <input
                                        type="number"
                                        id="latitude"
                                        name="latitude"
                                        min="-90"
                                        max="90"
                                        step="any"
                                        placeholder="Ex. : 14.7167"
                                        class="champ-publication"
                                        value="<?= $valeur('latitude') ?>"
                                    >
                                </div>


                                <div>
                                    <label
                                        for="longitude"
                                        class="mb-2 block text-sm font-semibold text-[#30221b]"
                                    >
                                        Longitude
                                    </label>

                                    <input
                                        type="number"
                                        id="longitude"
                                        name="longitude"
                                        min="-180"
                                        max="180"
                                        step="any"
                                        placeholder="Ex. : -17.4677"
                                        class="champ-publication"
                                        value="<?= $valeur('longitude') ?>"
                                    >
                                </div>

                            </div>

                            <p class="mt-3 text-xs text-gray-500">
                                Les coordonnées GPS sont facultatives, mais elles permettent de localiser le bien sur la carte.
                            </p>

                        </div>

                    </div>

                </section>


                <!-- =========================================
                     ÉTAPE 3 : CARACTÉRISTIQUES ET DESCRIPTION
                ========================================== -->
                <section
                    class="etape-formulaire hidden"
                    data-etape="3"
                    hidden
                >

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-[#30221b]">
                            Décrivez votre bien
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Précisez les caractéristiques et les avantages de votre propriété.
                        </p>
                    </div>


                    <div class="space-y-5">

                        <!-- SURFACE -->
                        <div>
                            <label
                                for="surface"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Surface en m²
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="number"
                                id="surface"
                                name="surface"
                                required
                                min="1"
                                step="any"
                                placeholder="Ex. : 120"
                                class="champ-publication"
                                value="<?= $valeur('superficie') ?>"
                            >
                        </div>


                        <!-- NOMBRE DE PIÈCES -->
                        <div>
                            <label
                                for="pieces"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Nombre total de pièces
                            </label>

                            <input
                                type="number"
                                id="pieces"
                                name="pieces"
                                min="0"
                                step="1"
                                placeholder="Ex. : 5"
                                class="champ-publication"
                                value="<?= $valeur('pieces') ?>"
                            >
                        </div>


                        <!-- CHAMBRES ET SALLES DE BAIN -->
                        <div class="grid gap-5 sm:grid-cols-2">

                            <div>
                                <label
                                    for="chambres"
                                    class="mb-2 block text-sm font-semibold text-[#30221b]"
                                >
                                    Nombre de chambres
                                </label>

                                <input
                                    type="number"
                                    id="chambres"
                                    name="chambres"
                                    min="0"
                                    step="1"
                                    placeholder="Ex. : 3"
                                    class="champ-publication"
                                   value="<?= $valeur('chambres') ?>"
                                >
                            </div>


                            <div>
                                <label
                                    for="salles_bain"
                                    class="mb-2 block text-sm font-semibold text-[#30221b]"
                                >
                                    Salles de bain
                                </label>

                                <input
                                    type="number"
                                    id="salles_bain"
                                    name="salles_bain"
                                    min="0"
                                    step="1"
                                    placeholder="Ex. : 2"
                                    class="champ-publication"
                                    value="<?= $valeur('salles_de_bain') ?>"
                                >
                            </div>

                        </div>


                        <!-- ÉTAGES -->
                        <div>
                            <label
                                for="etages"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Nombre d'étages
                            </label>

                            <input
                                type="number"
                                id="etages"
                                name="etages"
                                min="0"
                                step="1"
                                placeholder="Ex. : 2"
                                class="champ-publication"
                            >

                            <p class="mt-1 text-xs text-gray-500">
                                Champ informatif : son enregistrement en base nécessite une prise en charge par le contrôleur et le modèle.
                            </p>
                        </div>


                        <!-- DESCRIPTION -->
                        <div>
                            <label
                                for="description"
                                class="mb-2 block text-sm font-semibold text-[#30221b]"
                            >
                                Description du bien
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                required
                                minlength="10"
                                rows="7"
                                placeholder="Décrivez le bien, son état, ses équipements, son environnement et ses points forts..."
                                class="champ-publication resize-y"
                                <?= $valeur('description') ?>
                            ></textarea>

                            <div class="mt-2 flex justify-between gap-3 text-xs text-gray-500">
                                <span>
                                    Une description détaillée inspire confiance.
                                </span>

                                <span>
                                    <span id="compteur-description">0</span> caractères
                                </span>
                            </div>
                        </div>

                    </div>

                </section>


                <!-- =========================================
                     ÉTAPE 4 : PHOTOS
                ========================================== -->
                <section
                    class="etape-formulaire hidden"
                    data-etape="4"
                    hidden
                >

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-[#30221b]">
                            Ajoutez des photos
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Des photos de qualité permettent aux visiteurs de mieux découvrir votre bien.
                        </p>
                    </div>


                    <div class="rounded-2xl border-2 border-dashed border-[#d8c6b4] bg-[#faf7f2] p-6 text-center sm:p-10">

                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl shadow-sm">
                            <span aria-hidden="true">▧</span>
                        </div>

                        <label
                            for="photos"
                            class="block cursor-pointer text-lg font-bold text-[#30221b]"
                        >
                            Sélectionner les photos du bien
                        </label>

                        <p class="mt-2 text-sm text-gray-600">
                            Formats acceptés : JPG, JPEG et PNG.
                        </p>

                        <input
                            type="file"
                            id="photos"
                            name="photos[]"
                            multiple
                            accept="image/png,image/jpeg"
                            class="mt-5 block w-full cursor-pointer rounded-xl border border-gray-200 bg-white p-3 text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-[#30221b] file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-[#5a402d]"
                        >

                        <p
                            id="nombre-photos"
                            class="mt-4 text-sm font-semibold text-[#725238]"
                            aria-live="polite"
                        >
                            Aucune photo sélectionnée.
                        </p>

                    </div>


                    <!-- APERÇU DES PHOTOS -->
                    <div
                        id="apercu-photos"
                        class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3"
                    ></div>


                    <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                        <p class="font-semibold">
                            Conseil
                        </p>

                        <p class="mt-1">
                            Ajoutez des images nettes de la façade, des pièces principales et des autres espaces importants. Vérifiez également les éventuelles limites de taille imposées par votre système d'envoi de photos.
                        </p>
                    </div>

                </section>


                <!-- =========================================
                     ÉTAPE 5 : RÉCAPITULATIF
                ========================================== -->
                <section
                    class="etape-formulaire hidden"
                    data-etape="5"
                    hidden
                >

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-[#30221b]">
                            Vérifiez votre annonce
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Relisez les informations avant de publier votre bien.
                        </p>
                    </div>


                    <!-- RÉCAPITULATIF GÉNÉRAL -->
                    <div class="overflow-hidden rounded-2xl border border-[#e8ded2]">

                        <div class="bg-[#30221b] px-5 py-4 text-white">
                            <p class="text-sm text-white/70">
                                Votre annonce
                            </p>

                            <h4
                                id="recap-titre"
                                class="mt-1 break-words text-xl font-bold"
                            >
                                Titre de votre annonce
                            </h4>

                            <p
                                id="recap-type"
                                class="mt-2 text-sm text-white/80"
                            >
                                Vente
                            </p>
                        </div>


                        <div class="space-y-5 p-5">

                            <!-- INFORMATIONS -->
                            <div>
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <h5 class="font-bold text-[#30221b]">
                                        Informations générales
                                    </h5>

                                    <button
                                        type="button"
                                        class="text-sm font-semibold text-[#8a6344] underline"
                                        data-aller-etape="1"
                                    >
                                        Modifier
                                    </button>
                                </div>

                                <dl class="grid gap-4 sm:grid-cols-2">

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Catégorie
                                        </dt>
                                        <dd
                                            id="recap-categorie"
                                            class="mt-1 min-w-0 break-words font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Prix
                                        </dt>
                                        <dd
                                            id="recap-prix"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                </dl>
                            </div>


                            <div class="border-t border-gray-100"></div>


                            <!-- LOCALISATION -->
                            <div>
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <h5 class="font-bold text-[#30221b]">
                                        Localisation
                                    </h5>

                                    <button
                                        type="button"
                                        class="text-sm font-semibold text-[#8a6344] underline"
                                        data-aller-etape="2"
                                    >
                                        Modifier
                                    </button>
                                </div>

                                <dl class="grid gap-4 sm:grid-cols-2">

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Ville / Localité
                                        </dt>
                                        <dd
                                            id="recap-ville"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Quartier
                                        </dt>
                                        <dd
                                            id="recap-quartier"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <dt class="text-sm text-gray-500">
                                            Adresse
                                        </dt>
                                        <dd
                                            id="recap-adresse"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Latitude
                                        </dt>
                                        <dd
                                            id="recap-latitude"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Longitude
                                        </dt>
                                        <dd
                                            id="recap-longitude"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                </dl>
                            </div>


                            <div class="border-t border-gray-100"></div>


                            <!-- CARACTÉRISTIQUES -->
                            <div>
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <h5 class="font-bold text-[#30221b]">
                                        Caractéristiques
                                    </h5>

                                    <button
                                        type="button"
                                        class="text-sm font-semibold text-[#8a6344] underline"
                                        data-aller-etape="3"
                                    >
                                        Modifier
                                    </button>
                                </div>

                                <dl class="grid grid-cols-2 gap-4 sm:grid-cols-3">

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Surface
                                        </dt>
                                        <dd
                                            id="recap-surface"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Pièces
                                        </dt>
                                        <dd
                                            id="recap-pieces"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Chambres
                                        </dt>
                                        <dd
                                            id="recap-chambres"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Salles de bain
                                        </dt>
                                        <dd
                                            id="recap-salles-bain"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm text-gray-500">
                                            Étages
                                        </dt>
                                        <dd
                                            id="recap-etages"
                                            class="mt-1 font-semibold text-[#30221b]"
                                        >
                                            —
                                        </dd>
                                    </div>

                                </dl>

                                <div class="mt-5">
                                    <dt class="text-sm text-gray-500">
                                        Description
                                    </dt>

                                    <dd
                                        id="recap-description"
                                        class="mt-2 whitespace-pre-wrap break-words rounded-xl bg-[#faf7f2] p-4 text-sm leading-6 text-[#30221b]"
                                    >
                                        —
                                    </dd>
                                </div>
                            </div>


                            <div class="border-t border-gray-100"></div>


                            <!-- PHOTOS -->
                            <div>
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <h5 class="font-bold text-[#30221b]">
                                        Photos
                                    </h5>

                                    <button
                                        type="button"
                                        class="text-sm font-semibold text-[#8a6344] underline"
                                        data-aller-etape="4"
                                    >
                                        Modifier
                                    </button>
                                </div>

                                <p
                                    id="recap-photos"
                                    class="text-sm text-gray-600"
                                >
                                    Aucune photo sélectionnée.
                                </p>
                            </div>

                        </div>

                    </div>


                    <!-- AVERTISSEMENT -->
                    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                        Vérifiez que les informations sont correctes. En cliquant sur « Publier le bien », vous envoyez le formulaire pour enregistrer votre annonce.
                    </div>

                </section>


                <!-- =========================================
                     NAVIGATION ENTRE LES ÉTAPES
                ========================================== -->
                <div class="mt-8 flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">

                    <button
                        type="button"
                        id="bouton-precedent"
                       class="hidden w-full items-center justify-center gap-2 rounded-xl border border-[#d8c6b4] px-5 py-3 font-semibold text-[#30221b] transition hover:bg-[#faf7f2] sm:w-auto"    >
                        <span aria-hidden="true"></span>
                        Précédent
                    </button>


                    <div class="flex flex-col gap-3 sm:ml-auto sm:flex-row">

                        <a
                            href="/immo-sn/member/mes-biens"
                      class="inline-flex w-full items-center justify-center rounded-xl px-5 py-3 font-semibold text-gray-600 transition hover:bg-gray-100 sm:w-auto" >
                            Annuler
                        </a>


                        <button
                            type="button"
                            id="bouton-suivant"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-6 py-3 font-semibold text-white transition hover:bg-[#5a402d] sm:w-auto"
                        >
                            Étape suivante
                            <span aria-hidden="true"></span>
                        </button>


                        <button
                            type="submit"
                            id="bouton-publier"
                            class="hidden w-full items-center justify-center gap-2 rounded-xl bg-[#a98260] px-6 py-3 font-bold text-white transition hover:bg-[#8a6344] sm:w-auto"
                        >
                            <span aria-hidden="true">✓</span>
<?= $modeModification ? 'Soumettre les corrections' : 'Publier le bien' ?>
                        </button>

                    </div>

                </div>

            </form>

        </div>


        <p class="mt-5 text-center text-xs text-gray-500">
            Les champs marqués d'un astérisque (*) sont obligatoires.
        </p>

    




<!-- =========================================
     STYLES DU FORMULAIRE
========================================== -->
<style>
.champ-publication {
    display: block;
    width: 100%;
    border: 1px solid #eadfd5;
    border-radius: 0.75rem;
    background: #fffdf9;
    padding: 0.85rem 1rem;
    color: #30221b;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}

.champ-publication:focus {
    border-color: #a98260;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(169, 130, 96, 0.15);
}

.champ-publication:disabled {
    cursor: not-allowed;
    background: #f4eadf;
}

.champ-publication::placeholder {
    color: #a69a90;
}

.numero-indicateur.actif {
    border-color: #a98260;
    background: #30221b;
    color: #fff;
}

.numero-indicateur.termine {
    border-color: #a98260;
    background: #f4eadf;
    color: #725238;
}

.indicateur-etape.actif p {
    color: #30221b;
    font-weight: 700;
}

.apercu-photo {
    aspect-ratio: 4 / 3;
    width: 100%;
    overflow: hidden;
    border-radius: 0.75rem;
    border: 1px solid #eadfd5;
    object-fit: cover;
    background: #faf7f2;
}

</style>


<!-- =========================================
     JAVASCRIPT : ÉTAPES, GPS ET RÉCAPITULATIF
========================================== -->
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
     * =====================================================
     * AFFICHAGE D'UNE ÉTAPE
     * =====================================================
     */

    function afficherEtape(numero, faireDefiler = true) {

        etapeActuelle = numero;

        sections.forEach(function (section) {

            const numeroSection = Number(section.dataset.etape);
            const visible = numeroSection === numero;

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

            indicateur.classList.toggle(
                'actif',
                numeroIndicateur === numero
            );

            cercle.classList.toggle(
                'actif',
                numeroIndicateur === numero
            );

            cercle.classList.toggle(
                'termine',
                numeroIndicateur < numero
            );

            if (numeroIndicateur < numero) {
                cercle.textContent = '✓';
            } else {
                cercle.textContent = String(numeroIndicateur);
            }

        });


        boutonPrecedent.classList.toggle('hidden', numero === 1);
        boutonPrecedent.classList.toggle('sm:flex', numero !== 1);

        boutonSuivant.classList.toggle('hidden', numero === 5);
        boutonPublier.classList.toggle('hidden', numero !== 5);

        if (numero === 5) {
            actualiserRecapitulatif();
        }

        if (faireDefiler) {
            document.getElementById('titre-etape').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }


    /*
     * =====================================================
     * VALIDATION DES CHAMPS D'UNE ÉTAPE
     * =====================================================
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

        /*
         * Latitude et longitude sont facultatives.
         * Si elles sont toutes les deux renseignées,
         * elles doivent être cohérentes.
         */

        if (numero === 2) {

            const latitude = document.getElementById('latitude');
            const longitude = document.getElementById('longitude');

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

            latitude.setCustomValidity('');
            longitude.setCustomValidity('');
        }

        return true;
    }


    /*
     * Effacer les erreurs personnalisées pendant la saisie.
     */

    ['latitude', 'longitude'].forEach(function (id) {

        const champ = document.getElementById(id);

        champ.addEventListener('input', function () {
            champ.setCustomValidity('');
        });

    });


    /*
     * =====================================================
     * BOUTON SUIVANT
     * =====================================================
     */

    boutonSuivant.addEventListener('click', function () {

        if (!validerEtape(etapeActuelle)) {
            return;
        }

        if (etapeActuelle < 5) {
            afficherEtape(etapeActuelle + 1);
        }
    });


    /*
     * =====================================================
     * BOUTON PRÉCÉDENT
     * =====================================================
     */

    boutonPrecedent.addEventListener('click', function () {

        if (etapeActuelle > 1) {
            afficherEtape(etapeActuelle - 1);
        }
    });


    /*
     * =====================================================
     * BOUTONS MODIFIER DU RÉCAPITULATIF
     * =====================================================
     */

    document.querySelectorAll('[data-aller-etape]').forEach(function (bouton) {

        bouton.addEventListener('click', function () {

            const destination = Number(bouton.dataset.allerEtape);

            afficherEtape(destination);
        });

    });


    /*
     * =====================================================
     * MISE À JOUR DU RÉCAPITULATIF
     * =====================================================
     */

    function valeurChamp(id) {

        const champ = document.getElementById(id);

        if (!champ) {
            return '';
        }

        return champ.value.trim();
    }


    function texteSelect(id) {

        const select = document.getElementById(id);

        if (!select || select.selectedIndex < 0) {
            return '';
        }

        return select.options[select.selectedIndex].text.trim();
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
        afficherValeur('recap-quartier', valeurChamp('quartier'));
        afficherValeur('recap-adresse', valeurChamp('adresse'));

        afficherValeur('recap-latitude', valeurChamp('latitude'));
        afficherValeur('recap-longitude', valeurChamp('longitude'));

        afficherValeur('recap-surface', valeurChamp('surface'), ' m²');
        afficherValeur('recap-pieces', valeurChamp('pieces'));
        afficherValeur('recap-chambres', valeurChamp('chambres'));
        afficherValeur('recap-salles-bain', valeurChamp('salles_bain'));
        afficherValeur('recap-etages', valeurChamp('etages'));

        document.getElementById('recap-description').textContent =
            description || 'Aucune description renseignée.';


        const photos = document.getElementById('photos').files;
        const recapPhotos = document.getElementById('recap-photos');

        if (photos.length === 0) {

            recapPhotos.textContent = 'Aucune photo sélectionnée.';

        } else {

            recapPhotos.textContent =
                photos.length + (
                    photos.length > 1
                        ? ' photos sélectionnées.'
                        : ' photo sélectionnée.'
                );
        }
    }


    /*
     * Actualiser le récapitulatif dès qu'un champ change.
     */

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
     * =====================================================
     * COMPTEUR DE DESCRIPTION
     * =====================================================
     */

    const description = document.getElementById('description');
    const compteurDescription = document.getElementById('compteur-description');

    function actualiserCompteurDescription() {
        compteurDescription.textContent = String(description.value.length);
    }

    description.addEventListener('input', actualiserCompteurDescription);
    actualiserCompteurDescription();


    /*
     * =====================================================
     * SÉLECTION ET APERÇU DES PHOTOS
     * =====================================================
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

            nombrePhotos.textContent =
                'Certaines images ne sont pas au format JPG ou PNG.';

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
            nom.className = 'mt-2 break-words text-xs text-gray-600';
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
     * =====================================================
     * GÉOLOCALISATION GPS
     * =====================================================
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

                latitude.value =
                    position.coords.latitude.toFixed(7);

                longitude.value =
                    position.coords.longitude.toFixed(7);

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
                        'Votre position est actuellement indisponible. Réessayez ou saisissez les coordonnées manuellement.';

                } else if (erreur.code === 3) {

                    message =
                        'Le délai de géolocalisation est dépassé. Réessayez.';

                }

                statutGPS.textContent = message;

                texteBoutonGPS.textContent =
                    'Réessayer la détection';

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
     * =====================================================
     * VÉRIFICATION FINALE AVANT ENVOI
     * =====================================================
     */

    formulaire.addEventListener('submit', function (event) {

        /*
         * Vérifier les étapes dans l'ordre pour guider
         * l'utilisateur vers le premier champ invalide.
         */

        for (let numero = 1; numero <= 5; numero++) {

            if (!validerEtape(numero)) {

                event.preventDefault();

                afficherEtape(numero);

                return;
            }
        }

        /*
         * Toutes les validations du navigateur sont passées.
         * Le formulaire est envoyé au contrôleur PHP.
         */

        boutonPublier.disabled = true;
        boutonPublier.textContent = 'Enregistrement en cours...';

    });


    /*
     * =====================================================
     * INITIALISATION
     * =====================================================
     */

    afficherEtape(1, false);

});
</script>
        </div>
    </main>
</div>