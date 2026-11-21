
<?php
/**
 * PAGE : CARTE INTERACTIVE — IMMO-SN
 *
 * Variables attendues :
 * - $biens : tableau des biens immobiliers
 */

$biensCarte = is_array($biens ?? null) ? $biens : [];
$nombreBiens = count($biensCarte);

$imageParDefaut = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=85';

$e = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

$biensJson = json_encode(
    $biensCarte,
    JSON_UNESCAPED_UNICODE
    | JSON_UNESCAPED_SLASHES
    | JSON_HEX_TAG
    | JSON_HEX_APOS
    | JSON_HEX_QUOT
    | JSON_HEX_AMP
);

if ($biensJson === false) {
    $biensJson = '[]';
}
?>

<div class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

    <!-- =====================================================
         EN-TÊTE
    ====================================================== -->

    <section class="relative isolate mx-auto max-w-[1600px] overflow-hidden rounded-b-[28px] bg-gradient-to-br from-[#071B36] via-[#102844] to-[#1B354D] text-white shadow-[0_20px_55px_rgba(7,27,54,0.18)]">

        <div class="pointer-events-none absolute -right-20 -top-28 h-80 w-80 rounded-full bg-[#F5B800]/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 left-1/4 h-72 w-72 rounded-full bg-[#71869A]/20 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-5 py-9 sm:px-7 sm:py-11 lg:px-8 lg:py-14">

            <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-center">

                <div class="max-w-2xl">

                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#F5B800]/30 bg-white/[0.06] px-4 py-2 text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#F5D56C] sm:text-xs">

                        <span class="h-2 w-2 rounded-full bg-[#F5B800] shadow-[0_0_12px_rgba(245,184,0,0.7)]"></span>

                        Explorer IMMO-SN

                        <span class="text-white/30">/</span>

                        Carte interactive

                    </div>

                    <h1 class="text-3xl font-black leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">

                        Explorez l'immobilier

                        <span class="text-[#F5B800]">autrement.</span>

                    </h1>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-[#C6D2DF] sm:text-base">

                        Localisez les biens disponibles, découvrez leur environnement
                        et trouvez votre prochain logement au Sénégal grâce à notre
                        carte interactive.

                    </p>

                    <div class="mt-6 flex flex-wrap gap-3 text-xs font-semibold text-white/75">

                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.06] px-3 py-2">

                            <svg class="h-4 w-4 text-[#F5B800]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.2"/>
                            </svg>

                            Localisation GPS

                        </span>

                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.06] px-3 py-2">

                            <svg class="h-4 w-4 text-[#F5B800]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5M5 9v11h14V9M9 20v-6h6v6"/>
                            </svg>

                            Biens immobiliers

                        </span>

                    </div>

                </div>

                <!-- COMPTEUR -->

                <div class="flex shrink-0 items-center gap-4 rounded-[24px] border border-white/10 bg-white/[0.07] px-6 py-5 shadow-xl backdrop-blur-md">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F5B800]/15 text-[#F5B800]">

                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5M5 9v11h14V9M9 20v-6h6v6"/>
                        </svg>

                    </div>

                    <div>

                        <div class="text-3xl font-black leading-none text-[#F5B800]">
                            <?= $nombreBiens ?>
                        </div>

                        <p class="mt-2 text-xs font-medium text-[#C6D2DF]">
                            Bien<?= $nombreBiens > 1 ? 's' : '' ?> référencé<?= $nombreBiens > 1 ? 's' : '' ?>
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">

        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#A98200]">
                    Recherche géographique
                </p>

                <h2 class="mt-1 text-xl font-black tracking-tight text-[#101D33] sm:text-2xl">
                    Trouvez un bien près de vous
                </h2>

                <p class="mt-2 text-sm text-[#71869A]">
                    Sélectionnez une annonce pour afficher son emplacement sur la carte.
                </p>

            </div>

            <div class="inline-flex items-center gap-2 self-start rounded-full border border-[#E5DFD0] bg-white px-4 py-2 text-xs font-bold text-[#71869A] sm:self-auto">

                <span class="h-2 w-2 rounded-full bg-[#D7A200]"></span>

                Carte OpenStreetMap

            </div>

        </div>


        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[390px_minmax(0,1fr)]">

            <!-- =================================================
                 LISTE DES BIENS
            ================================================== -->

            <aside class="flex min-h-0 flex-col overflow-hidden rounded-[26px] border border-[#E6E2D9] bg-white shadow-[0_12px_40px_rgba(16,29,51,0.06)]">

                <!-- EN-TÊTE DE LA LISTE -->

                <div class="border-b border-[#ECE9E2] bg-gradient-to-br from-white to-[#F8F6F0] px-5 py-5">

                    <div class="flex items-center justify-between gap-3">

                        <div>

                            <h2 class="text-base font-black tracking-tight text-[#101D33]">
                                Biens à explorer
                            </h2>

                            <p class="mt-1 text-xs text-[#71869A]">
                                <?= $nombreBiens ?>
                                bien<?= $nombreBiens > 1 ? 's' : '' ?>
                                disponible<?= $nombreBiens > 1 ? 's' : '' ?>
                            </p>

                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#101D33] text-[#F5B800]">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5-2V6l5 2m0 12l6-2m-6 2V8m6 10l5 2V6l-5-2m0 12V8m0-4L9 6"/>
                            </svg>

                        </div>

                    </div>

                </div>


                <!-- LISTE -->

                <div class="custom-scrollbar min-h-[260px] max-h-[650px] overflow-y-auto xl:max-h-[calc(100vh-280px)]">

                    <?php if (empty($biensCarte)): ?>

                        <div class="px-6 py-14 text-center">

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#F5F3ED] text-[#A98200]">

                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5-2V6l5 2m0 12l6-2m-6 2V8m6 10l5 2V6l-5-2m0 12V8m0-4L9 6"/>
                                </svg>

                            </div>

                            <h3 class="mt-5 font-black text-[#101D33]">
                                Aucun bien disponible
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#71869A]">
                                Aucun bien avec des coordonnées GPS n'est actuellement disponible sur la carte.
                            </p>

                            <a
                                href="/immo-sn/biens"
                                class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#101D33] px-5 py-3 text-xs font-bold text-white transition hover:bg-[#243B58]"
                            >
                                Explorer les annonces
                            </a>

                        </div>

                    <?php else: ?>

                        <div class="space-y-3 p-3">

                            <?php foreach ($biensCarte as $bien): ?>

                                <?php

                                $id = $bien['id'] ?? '';

                                $titre = $bien['titre'] ?? 'Bien immobilier';

                                $categorie =
                                    $bien['categorie_nom']
                                    ?? $bien['categorie']
                                    ?? 'Immobilier';

                                $ville =
                                    $bien['ville_nom']
                                    ?? $bien['ville']
                                    ?? '';

                                $quartier =
                                    $bien['quartier_nom']
                                    ?? $bien['quartier']
                                    ?? '';

                                $prix = (float) ($bien['prix'] ?? 0);

                                $latitude = $bien['latitude'] ?? '';

                                $longitude = $bien['longitude'] ?? '';

                                $photo = $bien['photo'] ?? '';

                                if (!$photo) {
                                    $photo = $imageParDefaut;
                                } elseif (
                                    !preg_match('~^https?://~i', $photo)
                                ) {
                                    $photo = '/' . ltrim($photo, '/');
                                }

                                $localisation = trim(
                                    implode(', ', array_filter([
                                        $quartier,
                                        $ville
                                    ]))
                                );

                                $prixFormate = number_format(
                                    $prix,
                                    0,
                                    ',',
                                    ' '
                                );

                                ?>

                                <article
                                    class="bien-card group cursor-pointer overflow-hidden rounded-[20px] border border-[#EAE7DF] bg-white p-3 transition duration-300 hover:-translate-y-0.5 hover:border-[#D7A200]/50 hover:bg-[#FFFEFA] hover:shadow-[0_10px_25px_rgba(16,29,51,0.08)]"
                                    data-id="<?= $e($id) ?>"
                                    data-lat="<?= $e($latitude) ?>"
                                    data-lng="<?= $e($longitude) ?>"
                                    tabindex="0"
                                    role="button"
                                    aria-label="Afficher <?= $e($titre) ?> sur la carte"
                                >

                                    <div class="flex gap-3">

                                        <!-- PHOTO -->

                                        <div class="relative h-28 w-28 shrink-0 overflow-hidden rounded-xl bg-[#F5F3ED] sm:h-32 sm:w-32">

                                            <img
                                                src="<?= $e($photo) ?>"
                                                alt="<?= $e($titre) ?>"
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='<?= $e($imageParDefaut) ?>';"
                                            >

                                            <div class="absolute bottom-2 left-2 rounded-lg bg-[#101D33]/90 px-2 py-1 text-[9px] font-bold uppercase tracking-wide text-white">
                                                IMMO-SN
                                            </div>

                                        </div>


                                        <!-- INFORMATIONS -->

                                        <div class="min-w-0 flex-1 py-0.5">

                                            <div class="flex items-center justify-between gap-2">

                                                <span class="inline-flex max-w-full items-center rounded-lg bg-[#F5B800]/15 px-2 py-1 text-[9px] font-extrabold uppercase tracking-wide text-[#806000]">

                                                    <span class="truncate">
                                                        <?= $e($categorie) ?>
                                                    </span>

                                                </span>

                                                <span class="h-2 w-2 shrink-0 rounded-full bg-[#D7A200]"></span>

                                            </div>


                                            <h3 class="mt-2 line-clamp-2 text-sm font-extrabold leading-5 text-[#101D33]">
                                                <?= $e($titre) ?>
                                            </h3>


                                            <?php if ($localisation): ?>

                                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-[#71869A]">

                                                    <svg class="h-3.5 w-3.5 shrink-0 text-[#B58B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                                        <circle cx="12" cy="9" r="2.2"/>
                                                    </svg>

                                                    <span class="truncate">
                                                        <?= $e($localisation) ?>
                                                    </span>

                                                </p>

                                            <?php endif; ?>


                                            <div class="mt-3 flex items-center justify-between gap-2">

                                                <p class="min-w-0 truncate text-sm font-black text-[#A98200]">
                                                    <?= $prixFormate ?>
                                                    <span class="text-[10px] font-bold text-[#71869A]">FCFA</span>
                                                </p>

                                                <a
                                                    href="/immo-sn/biens/<?= urlencode((string) $id) ?>"
                                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#101D33] text-[#F5B800] transition hover:bg-[#F5B800] hover:text-[#101D33]"
                                                    title="Voir le bien"
                                                    aria-label="Voir le détail de <?= $e($titre) ?>"
                                                    onclick="event.stopPropagation();"
                                                >

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </article>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="border-t border-[#ECE9E2] bg-[#FAF9F6] px-5 py-3">

                    <p class="flex items-center gap-2 text-[11px] font-medium text-[#71869A]">

                        <svg class="h-4 w-4 text-[#A98200]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>

                        Cliquez sur un bien pour le localiser.

                    </p>

                </div>

            </aside>


            <!-- =================================================
                 CARTE INTERACTIVE
            ================================================== -->

            <section class="relative min-h-[580px] overflow-hidden rounded-[26px] border border-[#E6E2D9] bg-white shadow-[0_12px_40px_rgba(16,29,51,0.07)]">

                <!-- EN-TÊTE CARTE -->

                <div class="absolute left-4 top-4 z-[500] max-w-[calc(100%-80px)] rounded-xl border border-white/80 bg-white/95 px-4 py-3 shadow-lg backdrop-blur-md">

                    <div class="flex items-center gap-2">

                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#101D33] text-[#F5B800]">

                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
                                <circle cx="12" cy="9" r="2.2"/>
                            </svg>

                        </span>

                        <div class="min-w-0">

                            <p class="text-xs font-black text-[#101D33]">
                                Carte des biens
                            </p>

                            <p class="text-[10px] text-[#71869A]">
                                Sénégal
                            </p>

                        </div>

                    </div>

                </div>


                <!-- CARTE -->

                <div
                    id="map"
                    class="z-0 h-[650px] min-h-[580px] w-full"
                    aria-label="Carte interactive des biens immobiliers"
                ></div>


                <!-- BOUTON POSITION -->

                <button
                    type="button"
                    id="ma-position"
                    class="absolute right-4 top-4 z-[1000] inline-flex items-center gap-2 rounded-xl border border-[#E6E2D9] bg-white px-4 py-3 text-xs font-extrabold text-[#101D33] shadow-lg transition hover:border-[#D7A200] hover:bg-[#FFFEFA] disabled:cursor-wait disabled:opacity-60 sm:text-sm"
                >

                    <svg class="h-5 w-5 text-[#A98200]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48 2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48 2.83-2.83"/>
                    </svg>

                    <span>Ma position</span>

                </button>

            </section>

        </div>

    </main>

</div>


<!-- =========================================================
     LEAFLET CSS
========================================================= -->

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>


<!-- =========================================================
     LEAFLET JAVASCRIPT
========================================================= -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    'use strict';

    const biens = <?= $biensJson ?>;

    const imageParDefaut = <?= json_encode($imageParDefaut, JSON_UNESCAPED_SLASHES) ?>;

    const mapElement = document.getElementById('map');

    if (!mapElement) {
        return;
    }

    /*
     * ========================================================
     * VÉRIFICATION DE LEAFLET
     * ========================================================
     */

    if (typeof L === 'undefined') {

        mapElement.innerHTML = `
            <div class="flex h-full min-h-[580px] items-center justify-center bg-[#F5F3ED] p-6 text-center">
                <div class="max-w-sm">
                    <div class="text-4xl">⚠️</div>
                    <h3 class="mt-4 font-bold text-[#101D33]">
                        Carte indisponible
                    </h3>
                    <p class="mt-2 text-sm text-[#71869A]">
                        La bibliothèque cartographique n'a pas pu être chargée.
                        Vérifiez votre connexion Internet et actualisez la page.
                    </p>
                </div>
            </div>
        `;

        console.error('Leaflet n’a pas été chargé.');

        return;
    }


    /*
     * ========================================================
     * CRÉATION DE LA CARTE
     * ========================================================
     */

    const dakar = [14.7167, -17.4677];

    const map = L.map('map', {
        zoomControl: true,
        scrollWheelZoom: true
    }).setView(dakar, 12);


    /*
     * ========================================================
     * FOND OPENSTREETMAP
     * ========================================================
     */

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a>'
        }
    ).addTo(map);


    /*
     * ========================================================
     * ÉCHAPPEMENT HTML
     * ========================================================
     */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /*
     * ========================================================
     * NORMALISATION DES PHOTOS
     * ========================================================
     */

    function normaliserPhoto(photo) {

        if (!photo) {
            return imageParDefaut;
        }

        photo = String(photo);

        if (/^https?:\/\//i.test(photo)) {
            return photo;
        }

        return '/' + photo.replace(/^\/+/, '');

    }


    /*
     * ========================================================
     * VALIDATION DES COORDONNÉES
     * ========================================================
     */

    function coordonneesValides(latitude, longitude) {

        return Number.isFinite(latitude)
            && Number.isFinite(longitude)
            && latitude >= -90
            && latitude <= 90
            && longitude >= -180
            && longitude <= 180;

    }


    /*
     * ========================================================
     * ICÔNES DES MARQUEURS
     * ========================================================
     */

    function createIcon(titre) {

        const titreFormate = escapeHtml(
            titre || 'Bien immobilier'
        );

        return L.divIcon({

            className: 'custom-price-marker',

            html: `
                <div class="price-marker">
                    <span>${titreFormate}</span>
                </div>
            `,

            iconSize: [160, 42],

            iconAnchor: [80, 21],

            popupAnchor: [0, -24]

        });

    }


    /*
     * ========================================================
     * POPUP D'UN BIEN
     * ========================================================
     */

    function popupBien(bien) {

        const photo = normaliserPhoto(bien.photo);

        const titre = bien.titre || 'Bien immobilier';

        const categorie =
            bien.categorie_nom
            || bien.categorie
            || 'Immobilier';

        const ville =
            bien.ville_nom
            || bien.ville
            || '';

        const quartier =
            bien.quartier_nom
            || bien.quartier
            || '';

        const localisation = [
            quartier,
            ville
        ]
        .filter(Boolean)
        .join(', ');

        const prix = Number(bien.prix || 0);

        const prixFormate = new Intl.NumberFormat('fr-FR').format(
            Number.isFinite(prix) ? prix : 0
        );

        const lien =
            '/immo-sn/biens/' +
            encodeURIComponent(bien.id || '');

        return `

            <div class="gps-popup">

                <div class="gps-popup-image">

                    <img
                        src="${escapeHtml(photo)}"
                        alt="${escapeHtml(titre)}"
                        onerror="this.onerror=null;this.src='${escapeHtml(imageParDefaut)}';"
                    >

                </div>

                <div class="gps-popup-content">

                    <span class="gps-popup-category">
                        ${escapeHtml(categorie)}
                    </span>

                    <h3>
                        ${escapeHtml(titre)}
                    </h3>

                    ${
                        localisation
                        ? `
                            <div class="localisation">

                                <svg
                                    width="15"
                                    height="15"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 21l-4.243-4.343a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>

                                <span>${escapeHtml(localisation)}</span>

                            </div>
                        `
                        : ''
                    }

                    <div class="prix">
                        ${prixFormate} FCFA
                    </div>

                    <a href="${lien}">

                        <span>Voir le détail du bien</span>

                        <span aria-hidden="true">→</span>

                    </a>

                </div>

            </div>

        `;

    }


    /*
     * ========================================================
     * CRÉATION DES MARQUEURS
     * ========================================================
     */

    const markers = [];

    biens.forEach(function (bien) {

        const latitude = parseFloat(bien.latitude);

        const longitude = parseFloat(bien.longitude);

        if (!coordonneesValides(latitude, longitude)) {
            return;
        }

        const marker = L.marker(
            [latitude, longitude],
            {
                icon: createIcon(bien.titre)
            }
        );

        marker.bindPopup(
            popupBien(bien),
            {
                maxWidth: 300,
                minWidth: 250
            }
        );

        marker.addTo(map);

        markers.push({
            marker: marker,
            bien: bien,
            latitude: latitude,
            longitude: longitude
        });

    });


    /*
     * ========================================================
     * CENTRAGE AUTOMATIQUE
     * ========================================================
     */

    if (markers.length > 0) {

        const group = L.featureGroup(
            markers.map(function (item) {
                return item.marker;
            })
        );

        map.fitBounds(
            group.getBounds().pad(0.15),
            {
                maxZoom: 14
            }
        );

    }


    /*
     * ========================================================
     * SÉLECTION D'UN BIEN DEPUIS LA LISTE
     * ========================================================
     */

    document.querySelectorAll('.bien-card').forEach(function (card) {

        function selectionnerBien() {

            const id = String(card.dataset.id || '');

            const item = markers.find(function (element) {

                return String(element.bien.id) === id;

            });

            if (!item) {

                card.classList.add('bien-card-no-gps');

                return;

            }

            document.querySelectorAll('.bien-card').forEach(function (element) {

                element.classList.remove('bien-card-selected');

            });

            card.classList.add('bien-card-selected');

            map.flyTo(
                [item.latitude, item.longitude],
                16,
                {
                    duration: 0.8
                }
            );

            setTimeout(function () {

                item.marker.openPopup();

            }, 500);

        }

        card.addEventListener('click', selectionnerBien);

        card.addEventListener('keydown', function (event) {

            if (event.key === 'Enter' || event.key === ' ') {

                event.preventDefault();

                selectionnerBien();

            }

        });

    });


    /*
     * ========================================================
     * SURVOL DES BIENS
     * ========================================================
     */

    document.querySelectorAll('.bien-card').forEach(function (card) {

        card.addEventListener('mouseenter', function () {

            const id = String(card.dataset.id || '');

            const item = markers.find(function (element) {

                return String(element.bien.id) === id;

            });

            if (!item) {
                return;
            }

            card.classList.add('bien-card-hovered');

        });

        card.addEventListener('mouseleave', function () {

            card.classList.remove('bien-card-hovered');

        });

    });


    /*
     * ========================================================
     * MA POSITION
     * ========================================================
     */

    const boutonPosition = document.getElementById('ma-position');

    let positionMarker = null;

    if (boutonPosition) {

        boutonPosition.addEventListener('click', function () {

            if (!navigator.geolocation) {

                alert(
                    'La géolocalisation n’est pas disponible sur votre navigateur.'
                );

                return;

            }

            const texteOriginal = boutonPosition.innerHTML;

            boutonPosition.innerHTML = `

                <svg
                    class="h-5 w-5 animate-spin text-[#A98200]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-dasharray="30 20"
                    />
                </svg>

                <span>Localisation...</span>

            `;

            boutonPosition.disabled = true;


            navigator.geolocation.getCurrentPosition(

                function (position) {

                    const latitude = position.coords.latitude;

                    const longitude = position.coords.longitude;

                    map.flyTo(
                        [latitude, longitude],
                        16,
                        {
                            duration: 1
                        }
                    );

                    if (positionMarker) {

                        map.removeLayer(positionMarker);

                    }

                    positionMarker = L.circleMarker(
                        [latitude, longitude],
                        {
                            radius: 9,
                            color: '#FFFFFF',
                            weight: 3,
                            fillColor: '#D7A200',
                            fillOpacity: 1
                        }
                    ).addTo(map);

                    positionMarker.bindPopup(`

                        <div class="user-location-popup">

                            <span class="user-location-icon">📍</span>

                            <strong>Vous êtes ici</strong>

                            <p>Votre position actuelle</p>

                        </div>

                    `);

                    positionMarker.openPopup();

                    boutonPosition.innerHTML = texteOriginal;

                    boutonPosition.disabled = false;

                },

                function (error) {

                    let message = 'Impossible de récupérer votre position.';

                    switch (error.code) {

                        case error.PERMISSION_DENIED:

                            message = 'Vous avez refusé l’accès à votre position. Autorisez la géolocalisation dans votre navigateur pour utiliser cette fonctionnalité.';

                            break;

                        case error.POSITION_UNAVAILABLE:

                            message = 'Votre position est actuellement indisponible.';

                            break;

                        case error.TIMEOUT:

                            message = 'La récupération de votre position a pris trop de temps.';

                            break;

                    }

                    alert(message);

                    boutonPosition.innerHTML = texteOriginal;

                    boutonPosition.disabled = false;

                },

                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }

            );

        });

    }


    /*
     * ========================================================
     * REDIMENSIONNEMENT LEAFLET
     * ========================================================
     */

    setTimeout(function () {

        map.invalidateSize(true);

    }, 300);

    setTimeout(function () {

        map.invalidateSize(true);

    }, 1000);

    window.addEventListener('resize', function () {

        map.invalidateSize();

    });

});
</script>


<!-- =========================================================
     STYLE PERSONNALISÉ
========================================================= -->

<style>

/* =========================================================
   MARQUEURS PERSONNALISÉS
========================================================= */

.custom-price-marker {
    background: transparent !important;
    border: none !important;
}

.price-marker {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 160px;
    min-height: 36px;
    padding: 8px 12px;
    border: 2px solid #FFFFFF;
    border-radius: 999px;
    background: #101D33;
    color: #F5B800;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    box-shadow: 0 5px 15px rgba(16, 29, 51, 0.25);
    text-align: center;
}

.price-marker span {
    display: block;
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.price-marker::after {
    content: '';
    position: absolute;
    bottom: -7px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    border-top: 7px solid #101D33;
}


/* =========================================================
   POPUP LEAFLET
========================================================= */

.gps-popup {
    width: 280px;
    max-width: calc(100vw - 80px);
    box-sizing: border-box;
    overflow: hidden;
    border-radius: 16px;
    background: #FFFFFF;
}

.gps-popup-image {
    width: 100%;
    height: 150px;
    overflow: hidden;
    background: #F5F3ED;
}

.gps-popup-image img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gps-popup-content {
    padding: 14px;
}

.gps-popup-category {
    display: inline-block;
    margin-bottom: 8px;
    padding: 5px 9px;
    border-radius: 7px;
    background: rgba(245, 184, 0, 0.15);
    color: #806000;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.gps-popup h3 {
    margin: 0;
    color: #101D33;
    font-size: 15px;
    font-weight: 800;
    line-height: 1.4;
}

.gps-popup .localisation {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 8px;
    color: #71869A;
    font-size: 12px;
}

.gps-popup .localisation svg {
    flex-shrink: 0;
    color: #A98200;
}

.gps-popup .prix {
    margin-top: 12px;
    color: #A98200;
    font-size: 16px;
    font-weight: 900;
}

.gps-popup a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-top: 13px;
    padding: 11px 12px;
    border-radius: 10px;
    background: #101D33;
    color: #F5B800;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    transition: all 0.2s ease;
}

.gps-popup a:hover {
    background: #F5B800;
    color: #101D33;
}


/* =========================================================
   POPUP POSITION UTILISATEUR
========================================================= */

.user-location-popup {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 5px;
    color: #101D33;
    font-size: 13px;
}

.user-location-icon {
    font-size: 18px;
}

.user-location-popup p {
    margin: 0;
    color: #71869A;
    font-size: 11px;
}


/* =========================================================
   LISTE DES BIENS
========================================================= */

.bien-card-selected {
    border-color: #D7A200 !important;
    background: #FFFCF1 !important;
    box-shadow: 0 0 0 2px rgba(215, 162, 0, 0.12);
}

.bien-card-hovered {
    border-color: rgba(215, 162, 0, 0.5);
}


/* =========================================================
   BARRE DE DÉFILEMENT
========================================================= */

.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(16, 29, 51, 0.2) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    border-radius: 999px;
    background: rgba(16, 29, 51, 0.18);
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(16, 29, 51, 0.35);
}


/* =========================================================
   CONTRÔLES LEAFLET
========================================================= */

.leaflet-control-zoom {
    overflow: hidden;
    border: none !important;
    border-radius: 10px !important;
    box-shadow: 0 5px 15px rgba(16, 29, 51, 0.15) !important;
}

.leaflet-control-zoom a {
    background: #FFFFFF !important;
    color: #101D33 !important;
}

.leaflet-control-zoom a:hover {
    background: #F5F3ED !important;
    color: #A98200 !important;
}

.leaflet-popup-content-wrapper {
    overflow: hidden;
    padding: 0;
    border-radius: 16px;
}

.leaflet-popup-content {
    margin: 0;
}

.leaflet-popup-tip {
    background: #FFFFFF;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 640px) {

    .gps-popup {
        max-width: calc(100vw - 70px);
    }

    #map {
        height: 560px !important;
        min-height: 500px !important;
    }

}

</style>
