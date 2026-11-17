<?php

$biensCarte = $biens ?? [];

$nombreBiens = count($biensCarte);

$imageParDefaut = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=85';

?>

<!-- =========================================================
     PAGE : CARTE INTERACTIVE
========================================================= -->

<div class="min-h-screen bg-[#faf7f2]">

<!-- EN-TÊTE PREMIUM -->
<section class="relative isolate overflow-hidden rounded-[28px] bg-[#30221b] text-white shadow-[0_16px_45px_rgba(48,34,27,0.15)] sm:rounded-[32px]">

    <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 left-1/4 h-52 w-52 rounded-full bg-[#a98260]/15 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-5 py-7 sm:px-7 sm:py-9 lg:px-9">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div class="max-w-2xl">

                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#f7b500]/25 bg-white/5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-[#e8c99f] sm:text-xs">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#f7b500]"></span>
                    Explorer IMMO-SN
                    <span class="text-white/30">/</span>
                    Carte interactive
                </div>

                <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl lg:text-4xl">
                    Explorez l'immobilier
                    <span class="text-[#e8bd78]">autrement.</span>
                </h1>

                <p class="mt-3 max-w-xl text-xs leading-6 text-white/65 sm:text-sm">
                    Localisez les biens disponibles, découvrez leur environnement
                    et trouvez votre prochain logement au Sénégal.
                </p>

            </div>

            <!-- Compteur de biens -->
            <div class="flex shrink-0 items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.06] px-5 py-4 backdrop-blur-sm">

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#f7b500]/10 text-[#f7b500]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M3 10.5 12 3l9 7.5M5 9v11h14V9M9 20v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div>
                    <div class="text-2xl font-extrabold leading-none text-[#f7b500]">
                        <?= $nombreBiens ?>
                    </div>
                    <div class="mt-1.5 text-xs text-white/60">
                        Bien<?= $nombreBiens > 1 ? 's' : '' ?> sur la carte
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

       <div class="grid grid-cols-1 lg:grid-cols-[440px_minmax(0,1fr)] gap-5">

            <!-- =================================================
                 LISTE DES BIENS
            ================================================== -->

            <aside class="overflow-hidden rounded-[26px] border border-[#30221b]/[0.08] bg-white shadow-[0_12px_35px_rgba(48,34,27,0.06)]">
                <div class="border-b border-[#30221b]/[0.08] bg-gradient-to-br from-white to-[#faf7f2] px-5 py-5">

    <div class="flex items-center justify-between gap-3">

        <div>
            <h2 class="text-base font-extrabold tracking-tight text-[#30221b]">
                Biens à explorer
            </h2>

            <p class="mt-1 text-xs text-[#30221b]/55">
                <?= $nombreBiens ?>
                bien<?= $nombreBiens > 1 ? 's' : '' ?>
                disponible<?= $nombreBiens > 1 ? 's' : '' ?>
            </p>
        </div>

        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#30221b] text-[#f7b500] shadow-sm">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M9 20l-5-2V6l5 2m0 12l6-2m-6 2V8m6 10l5 2V6l-5-2m0 12V8m0-4L9 6"/>
            </svg>
        </div>

    </div>
</div>

                <!-- =================================================
                     LISTE
                ================================================== -->

             <div class="max-h-[520px] overflow-y-auto custom-scrollbar lg:max-h-[calc(100vh-260px)]">
                 <?php if (empty($biensCarte)): ?>

                        <div class="p-8 text-center">

                            <div class="w-16 h-16 mx-auto rounded-full bg-[#faf7f2] flex items-center justify-center mb-4">

                                <svg
                                    class="w-8 h-8 text-[#a98260]"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 20l-5-2V6l5 2m0 12l6-2m-6 2V8m6 10l5 2V6l-5-2m0 12V8m0-4L9 6"
                                    />

                                </svg>

                            </div>

                            <h3 class="font-semibold text-[#30221b]">
                                Aucun bien disponible
                            </h3>

                            <p class="text-sm text-[#30221b]/50 mt-2">
                                Aucun bien avec des coordonnées GPS n'est actuellement disponible.
                            </p>

                        </div>

                    <?php else: ?>

                       <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3">
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

                                /*
                                 * Gestion de la photo
                                 */

                                if (!$photo) {

                                    $photo = $imageParDefaut;

                                } elseif (

                                    !str_starts_with($photo, 'http://')
                                    && !str_starts_with($photo, 'https://')

                                ) {

                                    $photo = '/' . ltrim($photo, '/');

                                }

                                /*
                                 * Localisation
                                 */

                                $localisation = trim(

                                    implode(
                                        ', ',
                                        array_filter([
                                            $quartier,
                                            $ville
                                        ])
                                    )

                                );

                                ?>

                                <div
                                 
    class="bien-card min-w-0 overflow-hidden rounded-2xl border border-[#30221b]/[0.08] 
    bg-white p-2.5 transition-all duration-200 hover:border-[#a98260]/40 hover:bg-[#faf7f2]
     hover:shadow-md cursor-pointer"
                                    data-id="<?= htmlspecialchars((string) $id) ?>"
                                    data-lat="<?= htmlspecialchars((string) $latitude) ?>"
                                    data-lng="<?= htmlspecialchars((string) $longitude) ?>"
                                >

                                   <div class="flex flex-col gap-2">

                                        <!-- Image -->
<div class="h-28 w-full overflow-hidden rounded-xl bg-[#faf7f2]">
                                            <img
                                                src="<?= htmlspecialchars($photo) ?>"
                                                alt="<?= htmlspecialchars($titre) ?>"
                                                class="w-full h-full object-cover"
                                                onerror="this.src='<?= htmlspecialchars($imageParDefaut) ?>'"
                                            >

                                        </div>


                                        <!-- Informations -->

                                        <div class="min-w-0 flex-1">

                                            <div class="flex items-start justify-between gap-2">

                                                <span class="inline-flex items-center px-2 py-1 rounded-lg bg-[#f7b500]/10 text-[#8a6500] text-[10px] font-semibold uppercase tracking-wide">

                                                    <?= htmlspecialchars($categorie) ?>

                                                </span>

                                            </div>


                                            <h3 class="font-semibold text-[#30221b] text-sm mt-2 line-clamp-2">

                                                <?= htmlspecialchars($titre) ?>

                                            </h3>


                                            <?php if ($localisation): ?>

                                                <p class="text-xs text-[#30221b]/50 mt-1 flex items-center gap-1">

                                                    <svg
                                                        class="w-3.5 h-3.5 flex-shrink-0"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 21l-4.243-4.343a8 8 0 1111.314 0z"
                                                        />

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />

                                                    </svg>

                                                    <span class="truncate">
                                                        <?= htmlspecialchars($localisation) ?>
                                                    </span>

                                                </p>

                                            <?php endif; ?>


                                            <div class="flex items-center justify-between gap-2 mt-2">

                                                <span class="font-bold text-[#30221b] text-sm">

                                                    <?= number_format($prix, 0, ',', ' ') ?> FCFA

                                                </span>


                                                <a
                                                    href="/immo-sn/biens/<?= urlencode((string) $id) ?>"
                                                    class="text-[#f7b500] hover:text-[#30221b] transition"
                                                    title="Voir le bien"
                                                    onclick="event.stopPropagation();"
                                                >

                                                    <svg
                                                        class="w-5 h-5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5l7 7-7 7"
                                                        />

                                                    </svg>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </aside>


            <!-- =================================================
                 CARTE
            ================================================== -->

            <div class="relative">

                <div
                    id="map"
                    class="w-full h-[650px] lg:h-[calc(100vh-205px)] min-h-[580px] rounded-3xl overflow-hidden shadow-sm border border-[#30221b]/10 z-0"
                ></div>


                <!-- Bouton position -->

                <button
                    type="button"
                    id="ma-position"
                    class="absolute top-4 right-4 z-[1000] bg-white hover:bg-[#faf7f2] text-[#30221b] shadow-lg rounded-xl px-4 py-3 flex items-center gap-2 font-semibold text-sm transition-all"
                >

                    <svg
                        class="w-5 h-5 text-[#f7b500]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"
                        />

                    </svg>

                    <span>
                        Ma position
                    </span>

                </button>

            </div>

        </div>

    </section>

</div>


<!-- =========================================================
     LEAFLET CSS
========================================================= -->

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>


<!-- =========================================================
     LEAFLET JS
========================================================= -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
     * ========================================================
     * DONNÉES DES BIENS
     * ========================================================
     */

    const biens = <?= json_encode(
        $biensCarte,
        JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES
        | JSON_HEX_TAG
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
        | JSON_HEX_AMP
    ) ?>;


    /*
     * ========================================================
     * VÉRIFICATION DE LEAFLET
     * ========================================================
     */

    if (typeof L === 'undefined') {

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
        zoomControl: true
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
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);


  function createIcon(titre) {

    const titreFormate = escapeHtml(
        titre || 'Bien immobilier'
    );

    return L.divIcon({

        className: 'custom-price-marker',

        html: `
            <div class="price-marker">
                ${titreFormate}
            </div>
        `,

        iconSize: [140, 36],

        iconAnchor: [70, 18],

        popupAnchor: [0, -20]

    });
}

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
     * POPUP D'UN BIEN
     * ========================================================
     */

    function popupBien(bien) {

        const imageParDefaut =
            <?= json_encode($imageParDefaut) ?>;

        let photo = bien.photo || '';


        /*
         * Gestion de la photo
         */

        if (!photo) {

            photo = imageParDefaut;

        } else if (

            !photo.startsWith('http://')
            && !photo.startsWith('https://')

        ) {

            photo = '/' + photo.replace(/^\/+/, '');

        }


        /*
         * Informations du bien
         */

        const titre =
            bien.titre || 'Bien immobilier';

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


        /*
         * Localisation
         */

        const localisation = [

            quartier,
            ville

        ]
        .filter(Boolean)
        .join(', ');


        /*
         * Prix
         */

        const prix =
            Number(bien.prix || 0);

        const prixFormate =
            new Intl.NumberFormat('fr-FR').format(prix);


        /*
         * Lien vers le détail
         */

        const lien =
            '/immo-sn/biens/' +
            encodeURIComponent(bien.id || '');


        /*
         * HTML du popup
         */

        return `

            <div class="gps-popup">

                <img
                    src="${escapeHtml(photo)}"
                    alt="${escapeHtml(titre)}"
                    onerror="this.src='${escapeHtml(imageParDefaut)}'"
                >

                <div class="gps-popup-content">

                    <div class="gps-popup-category">
                        ${escapeHtml(categorie)}
                    </div>

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

                                    ${escapeHtml(localisation)}

                                </div>

                            `
                            : ''
                    }


                    <div class="prix">
                        ${prixFormate} FCFA
                    </div>


                    <a href="${lien}">

                        Voir le détail

                        <span>→</span>

                    </a>

                </div>

            </div>

        `;
    }


    /*
     * ========================================================
     * MARQUEURS
     * ========================================================
     */

    const markers = [];


    biens.forEach(function (bien) {

        const latitude =
            parseFloat(bien.latitude);

        const longitude =
            parseFloat(bien.longitude);


        /*
         * Coordonnées absentes
         */

        if (
            Number.isNaN(latitude)
            ||
            Number.isNaN(longitude)
        ) {

            return;
        }


        /*
         * Coordonnées invalides
         */

        if (
            latitude < -90
            ||
            latitude > 90
            ||
            longitude < -180
            ||
            longitude > 180
        ) {

            return;
        }


        const marker = L.marker(
    [latitude, longitude],
    {
        icon: createIcon(bien.titre)
    }
);


        /*
         * Popup
         */

        marker.bindPopup(

            popupBien(bien),

            {
                maxWidth: 280
            }

        );


        /*
         * Ajout à la carte
         */

        marker.addTo(map);


        /*
         * Stockage
         */

        markers.push({

            marker: marker,

            bien: bien

        });

    });


    /*
     * ========================================================
     * CENTRAGE AUTOMATIQUE
     * ========================================================
     */

    if (markers.length > 0) {

        const group =
            L.featureGroup(

                markers.map(function (item) {

                    return item.marker;

                })

            );


        map.fitBounds(

            group.getBounds().pad(0.15)

        );

    }


    /*
     * ========================================================
     * SURVOL DES BIENS
     * ========================================================
     */

    document
        .querySelectorAll('.bien-card')
        .forEach(function (card) {

            card.addEventListener(
                'mouseenter',
                function () {

                    const id =
                        String(card.dataset.id || '');


                    const item =
                        markers.find(function (element) {

                            return String(
                                element.bien.id
                            ) === id;

                        });


                    if (!item) {

                        return;
                    }


                    const latitude =
                        parseFloat(
                            item.bien.latitude
                        );

                    const longitude =
                        parseFloat(
                            item.bien.longitude
                        );


                    if (
                        Number.isNaN(latitude)
                        ||
                        Number.isNaN(longitude)
                    ) {

                        return;
                    }


                    map.flyTo(

                        [latitude, longitude],

                        16,

                        {
                            duration: 0.7
                        }

                    );


                    setTimeout(function () {

                        item.marker.openPopup();

                    }, 500);

                }
            );

        });


    /*
     * ========================================================
     * MA POSITION
     * ========================================================
     */

    const boutonPosition =
        document.getElementById('ma-position');

    let positionMarker = null;


    if (boutonPosition) {

        boutonPosition.addEventListener(

            'click',

            function () {

                /*
                 * Vérification de la géolocalisation
                 */

                if (!navigator.geolocation) {

                    alert(
                        'La géolocalisation n’est pas disponible sur votre navigateur.'
                    );

                    return;
                }


                /*
                 * Texte original
                 */

                const texteOriginal =
                    boutonPosition.innerHTML;


                /*
                 * État de chargement
                 */

                boutonPosition.innerHTML = `

                    <svg
                        class="w-5 h-5 animate-spin text-[#f7b500]"
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

                    <span>
                        Localisation...
                    </span>

                `;


                boutonPosition.disabled = true;


                /*
                 * Géolocalisation
                 */

                navigator.geolocation.getCurrentPosition(

                    function (position) {

                        const latitude =
                            position.coords.latitude;

                        const longitude =
                            position.coords.longitude;


                        /*
                         * Déplacement de la carte
                         */

                        map.flyTo(

                            [latitude, longitude],

                            16,

                            {
                                duration: 1
                            }

                        );


                        /*
                         * Supprimer ancien marqueur
                         */

                        if (positionMarker) {

                            map.removeLayer(
                                positionMarker
                            );

                        }


                        /*
                         * Nouveau marqueur
                         */

                        positionMarker =
                            L.circleMarker(

                                [latitude, longitude],

                                {
                                    radius: 8,
                                    color: '#ffffff',
                                    weight: 3,
                                    fillColor: '#f7b500',
                                    fillOpacity: 1
                                }

                            ).addTo(map);


                        /*
                         * Popup position
                         */

                        positionMarker.bindPopup(`

                            <div class="user-location-popup">

                                <strong>
                                    📍 Vous êtes ici
                                </strong>

                            </div>

                        `);


                        positionMarker.openPopup();


                        /*
                         * Restaurer le bouton
                         */

                        boutonPosition.innerHTML =
                            texteOriginal;

                        boutonPosition.disabled =
                            false;

                    },


                    function (error) {

                        let message =
                            'Impossible de récupérer votre position.';


                        switch (error.code) {

                            case error.PERMISSION_DENIED:

                                message =
                                    'Vous avez refusé l’accès à votre position.';

                                break;


                            case error.POSITION_UNAVAILABLE:

                                message =
                                    'Votre position est actuellement indisponible.';

                                break;


                            case error.TIMEOUT:

                                message =
                                    'La récupération de votre position a pris trop de temps.';

                                break;

                        }


                        alert(message);


                        boutonPosition.innerHTML =
                            texteOriginal;

                        boutonPosition.disabled =
                            false;

                    },


                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }

                );

            }

        );

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


});

</script>


<!-- =========================================================
     STYLE
========================================================= -->

<style>

.custom-price-marker {

    background: transparent !important;

    border: none !important;

}


.price-marker {

    background: #30221b;

    color: white;

    padding: 8px 12px;

    border-radius: 999px;

    font-size: 11px;

    font-weight: 700;

    white-space: nowrap;

    box-shadow:
        0 4px 12px rgba(48, 34, 27, 0.25);

    border: 2px solid white;

    position: relative;

    text-align: center;

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

    border-top: 7px solid #30221b;

}


.gps-popup {

    width: 280px;

    max-width: calc(100vw - 80px);

    box-sizing: border-box;

    overflow: hidden;

    border-radius: 16px;

}


.gps-popup img {

    width: 100%;

    height: 150px;

    object-fit: cover;

    display: block;

}


.gps-popup-content {

    padding: 14px;

}


.gps-popup-category {

    display: inline-block;

    background: rgba(247, 181, 0, 0.12);

    color: #8a6500;

    padding: 5px 8px;

    border-radius: 7px;

    font-size: 10px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: 0.04em;

    margin-bottom: 8px;

}


.gps-popup h3 {

    margin: 0;

    color: #30221b;

    font-size: 16px;

    font-weight: 700;

    line-height: 1.35;

}


.gps-popup .localisation {

    display: flex;

    align-items: center;

    gap: 5px;

    margin-top: 7px;

    color: rgba(48, 34, 27, 0.55);

    font-size: 12px;

}


.gps-popup .prix {

    margin-top: 10px;

    color: #30221b;

    font-size: 15px;

    font-weight: 800;

}


.gps-popup a {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 12px;

    padding: 9px 11px;

    background: #30221b;

    color: white;

    border-radius: 9px;

    text-decoration: none;

    font-size: 12px;

    font-weight: 600;

    transition: all 0.2s ease;

}


.gps-popup a:hover {

    background: #f7b500;

    color: #30221b;

}


.user-location-popup {

    padding: 3px 5px;

    color: #30221b;

    font-size: 13px;

}


.custom-scrollbar::-webkit-scrollbar {

    width: 5px;

}


.custom-scrollbar::-webkit-scrollbar-track {

    background: transparent;

}


.custom-scrollbar::-webkit-scrollbar-thumb {

    background: rgba(48, 34, 27, 0.15);

    border-radius: 999px;

}


.custom-scrollbar::-webkit-scrollbar-thumb:hover {

    background: rgba(48, 34, 27, 0.25);

}


.leaflet-control-zoom {

    border: none !important;

    box-shadow:
        0 4px 12px rgba(48, 34, 27, 0.15) !important;

}


.leaflet-control-zoom a {

    color: #30221b !important;

    background: white !important;

}


.leaflet-control-zoom a:hover {

    background: #faf7f2 !important;

}


.leaflet-popup-content-wrapper {

    border-radius: 16px;

    padding: 0;

    overflow: hidden;

}


.leaflet-popup-content {

    margin: 0;

}


.leaflet-popup-tip {

    background: white;

}

</style>