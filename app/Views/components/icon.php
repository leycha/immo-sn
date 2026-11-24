
<?php

if (!function_exists('icon')) {

    function icon(string $name, string $class = 'w-6 h-6'): string
    {
        $icons = [

            // =================================================
            // IMMOBILIER
            // =================================================

            'home' => '<path d="M3 10.5L12 3l9 7.5M5 9v12h14V9M9 21v-7h6v7"/>',

            'house' => '<path d="M3 10.5L12 3l9 7.5M5 9v12h14V9M9 21v-7h6v7"/>',

            'building' => '<path d="M4 21V5a2 2 0 012-2h12a2 2 0 012 2v16M2 21h20M9 7h2m-2 4h2m4-4h2m-2 4h2M9 21v-5h6v5"/>',

            'building-2' => '<path d="M3 21h18M5 21V5l7-3v19m0-14h7v14M8 7h1m-1 4h1m-1 4h1m7-3h1m-1 4h1"/>',

            'office' => '<path d="M4 21V5a2 2 0 012-2h12a2 2 0 012 2v16M2 21h20M9 7h2m-2 4h2m4-4h2m-2 4h2"/>',

            'briefcase-business' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2M3 12h18M10 12v2h4v-2"/>',

            'store' => '<path d="M3 10l2-7h14l2 7M4 10v11h16V10M3 10a3 3 0 006 0 3 3 0 006 0 3 3 0 006 0M9 21v-7h6v7"/>',

            'door-open' => '<path d="M5 21V4a2 2 0 012-2h10a2 2 0 012 2v17M5 21h14M13 12h.01"/>',

            'land' => '<path d="M3 19l4-12 6 3 8-5v14l-8 3-6-3-4 0zM7 7v12m6-9v12m8-17v14"/>',

            'map' => '<path d="M3 6l6-3 6 3 6-3v15l-6 3-6-3-6 3V6zM9 3v15m6-12v15"/>',

            // =================================================
            // NAVIGATION ET RECHERCHE
            // =================================================

            'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',

            'search-check' => '<circle cx="10.5" cy="10.5" r="7"/><path d="m16 16 5 5m-13-10 2 2 4-4"/>',

            'arrow-right' => '<path d="M5 12h14m-7-7 7 7-7 7"/>',

            'arrow-up-right' => '<path d="M7 17 17 7M7 7h10v10"/>',

            'map-pin' => '<path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1116 0z"/><circle cx="12" cy="10" r="2.5"/>',

            'pin' => '<path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1116 0z"/><circle cx="12" cy="10" r="2.5"/>',

            // =================================================
            // QR CODE
            // =================================================

            'qr-code' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zm4 0h3m-3 3v4m3-3h-3"/>',

            'qrcode' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zm4 0h3m-3 3v4m3-3h-3"/>',

            // =================================================
            // CARACTÉRISTIQUES DES BIENS
            // =================================================

            'bed' => '<path d="M3 18v-8a2 2 0 012-2h14a2 2 0 012 2v8M3 14h18M3 18v3m18-3v3M7 8V6a2 2 0 012-2h6a2 2 0 012 2v2"/>',

            'bed-double' => '<path d="M3 18v-8a2 2 0 012-2h14a2 2 0 012 2v8M3 14h18M3 18v3m18-3v3M7 8V6a2 2 0 012-2h6a2 2 0 012 2v2"/>',

            'bath' => '<path d="M3 11h18v4a4 4 0 01-4 4H7a4 4 0 01-4-4v-4zm2 8v2m14-2v2M7 11V6a3 3 0 016 0v1"/>',

            'maximize' => '<path d="M8 3H5a2 2 0 00-2 2v3m13-5h3a2 2 0 012 2v3M3 16v3a2 2 0 002 2h3m13-5v3a2 2 0 01-2 2h-3"/>',

            // =================================================
            // SÉCURITÉ ET BADGES
            // =================================================

            'check' => '<path d="m5 12 4 4L19 6"/>',

            'badge' => '<path d="m12 3 2.5 2 3.2-.2.8 3.1 2.5 2.1-1.2 3 1.2 3-2.5 2.1-.8 3.1-3.2-.2L12 23l-2.5-2-3.2.2-.8-3.1L3 16l1.2-3L3 10l2.5-2.1.8-3.1 3.2.2L12 3z"/><path d="m9 13 2 2 4-4"/>',

            'shield' => '<path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path d="m9 12 2 2 4-4"/>',

            'shield-check' => '<path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path d="m9 12 2 2 4-4"/>',

            'zap' => '<path d="m13 2-3 9h7L9 22l3-9H5l8-11z"/>',

        ];

        if (!isset($icons[$name])) {
            return '';
        }

        $safeClass = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');

        return '<svg xmlns="http://www.w3.org/2000/svg"'
            . ' class="' . $safeClass . '"'
            . ' fill="none"'
            . ' stroke="currentColor"'
            . ' stroke-width="2"'
            . ' stroke-linecap="round"'
            . ' stroke-linejoin="round"'
            . ' viewBox="0 0 24 24"'
            . ' aria-hidden="true">'
            . $icons[$name]
            . '</svg>';
    }
}
