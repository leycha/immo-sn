<?php

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

/*
|--------------------------------------------------------------------------
| Nettoyage du chemin de base
|--------------------------------------------------------------------------
*/

$currentPath = preg_replace(
    '#^/immo-sn/public#',
    '',
    $currentPath
);

$currentPath = preg_replace(
    '#^/immo-sn#',
    '',
    $currentPath
);

/*
|--------------------------------------------------------------------------
| Normalisation du chemin
|--------------------------------------------------------------------------
*/

$currentPath = rtrim($currentPath, '/') ?: '/';


/*
|--------------------------------------------------------------------------
| Détection des liens actifs
|--------------------------------------------------------------------------
*/

if (!function_exists('isMemberActive')) {
    function isMemberActive(string $path): bool
    {
        global $currentPath;

        $path = rtrim($path, '/') ?: '/';

        // Le Dashboard correspond aussi à /member
        if ($path === '/member/dashboard') {
            return $currentPath === '/member'
                || $currentPath === '/member/dashboard'
                || str_starts_with($currentPath, '/member/dashboard/');
        }

        return $currentPath === $path
            || str_starts_with($currentPath, $path . '/');
    }
}




?>
<aside
    id="memberSidebar"
    aria-label="Navigation membre"
    class="
        fixed
        inset-y-0
        left-0
        z-50
        w-[270px]
        h-screen
        min-h-screen
        shrink-0
        bg-[#071b36]
        text-white
        flex
        flex-col
        border-r
        border-[#0e2a4e]
        overflow-y-auto
        -translate-x-full
        transition-transform
        duration-300
        ease-in-out
        lg:translate-x-0
    "
>
    <button
        type="button"
        id="memberSidebarClose"
        aria-label="Fermer le menu"
        class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-lg text-white hover:bg-[#0e2a4e] lg:hidden"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

<!-- =========================================================
     LOGO
========================================================== -->
<div class="px-5 pt-6 pb-5">

    <a
        href="/immo-sn/member"
        class="flex items-center gap-3 group"
    >

        <div
            class="
                w-11
                h-11
                rounded-2xl
                bg-[#f5b800]
                flex
                items-center
                justify-center
                shadow-lg
                shadow-[#071b36]/20
                shrink-0
                group-hover:scale-105
                transition
            "
        >
            <span class="text-[#0b1528] font-extrabold text-sm">
                IS
            </span>
        </div>

        <div>

            <div class="flex items-baseline">

                <span class="text-xl font-bold tracking-tight text-white">
                    ImmoSn
                </span>

                <span class="text-[#f5b800] font-bold">
                    .com
                </span>

            </div>

            <p class="text-[10px] uppercase tracking-[0.18em] text-slate-400 mt-0.5">
                Espace membre
            </p>

        </div>

    </a>

</div>


<!-- =========================================================
     PROFIL RAPIDE
========================================================== -->
<div class="px-4 mb-6">

    <div
        class="
            rounded-2xl
            bg-[#0e2a4e]/70
            border
            border-[#163d6e]
            p-4
        "
    >

        <div class="flex items-center gap-3">

            <div
                class="
                    w-10
                    h-10
                    rounded-xl
                    bg-[#f5b800]
                    flex
                    items-center
                    justify-center
                    shrink-0
                "
            >
                <span class="text-sm font-bold text-[#0b1528]">
                    M
                </span>
            </div>

            <div class="min-w-0">

                <p class="text-sm font-semibold text-white truncate">
                    Espace membre
                </p>

                <p class="text-xs text-slate-400 truncate mt-0.5">
                    Gestion immobilière
                </p>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     NAVIGATION
========================================================== -->
<div class="px-4 flex-1 overflow-y-auto">

    <p
        class="
            px-3
            mb-3
            text-[10px]
            font-semibold
            uppercase
            tracking-[0.18em]
            text-slate-400
        "
    >
        Menu principal
    </p>


    <nav class="space-y-1">

        <!-- =================================================
             DASHBOARD
        ================================================== -->
        <a
            href="/immo-sn/member/dashboard"
            class="
                group
                relative
                flex
                items-center
                gap-3
                rounded-xl
                px-3
                py-3
                text-sm
                transition-all
                <?= isMemberActive('/member/dashboard')
                    ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                    : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    <?= isMemberActive('/member/dashboard')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-[18px] h-[18px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"
                    />
                </svg>

            </span>

            <span class="font-medium">
                Dashboard
            </span>

        </a>


        <!-- =================================================
             PUBLIER UN BIEN
        ================================================== -->
        <a
            href="/immo-sn/member/publier"
            class="
                group
                flex
                items-center
                gap-3
                rounded-xl
                px-3
                py-3
                text-sm
                transition-all
                <?= isMemberActive('/member/publier')
                    ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                    : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    <?= isMemberActive('/member/publier')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-[18px] h-[18px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 5v14M5 12h14"
                    />
                </svg>

            </span>

            <span class="font-medium">
                Publier un bien
            </span>

        </a>


        <!-- =================================================
             MES BIENS
        ================================================== -->
        <a
            href="/immo-sn/member/mes-biens"
            class="
                group
                flex
                items-center
                gap-3
                rounded-xl
                px-3
                py-3
                text-sm
                transition-all
                <?= isMemberActive('/member/mes-biens')
                    ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                    : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    <?= isMemberActive('/member/mes-biens')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-[18px] h-[18px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"
                    />
                </svg>

            </span>

            <span class="font-medium">
                Mes biens
            </span>

        </a>


        <!-- =================================================
             MES ALERTES
        ================================================== -->
        <a
            href="/immo-sn/member/alertes"
            class="
                group
                flex
                items-center
                gap-3
                rounded-xl
                px-3
                py-3
                text-sm
                transition-all
                <?= isMemberActive('/member/alertes')
                    ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                    : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    <?= isMemberActive('/member/alertes')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-[18px] h-[18px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"
                    />
                </svg>

            </span>

            <span class="font-medium">
                Mes alertes
            </span>

        </a>


        <!-- =================================================
             MESSAGERIE
        ================================================== -->
        <a
            href="/immo-sn/member/messages"
            class="
                group
                flex
                items-center
                gap-3
                rounded-xl
                px-3
                py-3
                text-sm
                transition-all
                <?= isMemberActive('/member/messages')
                    ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                    : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    <?= isMemberActive('/member/messages')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-[18px] h-[18px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <rect
                        x="3"
                        y="4"
                        width="18"
                        height="16"
                        rx="2"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 7l8.05 5.1a1.8 1.8 0 001.9 0L21 7"
                    />
                </svg>

            </span>

            <span class="font-medium">
                Messagerie
            </span>

        </a>


        <!-- =================================================
             FAVORIS
        ================================================== -->
        <a
            href="/immo-sn/member/favoris"
            class="
                group
                flex
                items-center
                gap-3
                rounded-xl
                px-3
                py-3
                text-sm
                transition-all
                <?= isMemberActive('/member/favoris')
                    ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                    : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    <?= isMemberActive('/member/favoris')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-[18px] h-[18px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25A5.25 5.25 0 017.5 3c1.387 0 2.64.576 3.5 1.501A5.25 5.25 0 0114.5 3a5.25 5.25 0 015.25 5.25c0 3.924-2.438 7.11-4.739 9.257a25.18 25.18 0 01-4.244 3.17 15.247 15.247 0 01-.383.218l-.022.012-.007.003-.003.001a.752.752 0 01-.707 0l-.003-.001Z"
                    />
                </svg>

            </span>

            <span class="font-medium">
                Favoris
            </span>

        </a>

    </nav>


    <!-- =====================================================
         MON COMPTE
    ====================================================== -->
    <p
        class="
            px-3
            mt-7
            mb-3
            text-[10px]
            font-semibold
            uppercase
            tracking-[0.18em]
            text-slate-400
        "
    >
        Mon compte
    </p>


    <nav class="space-y-1">

        <!-- =================================================
             PROFIL
        ================================================== -->
        <a
            href="/immo-sn/member/profil"
            class="
                group
                flex
                items-center
                gap-3
                rounded-xl
                px-3
                py-3
                text-sm
                transition-all
                <?= isMemberActive('/member/profil')
                    ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                    : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    <?= isMemberActive('/member/profil')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-[18px] h-[18px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <circle
                        cx="12"
                        cy="8"
                        r="3"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5.5 20a6.5 6.5 0 0113 0"
                    />
                </svg>

            </span>

            <span class="font-medium">
                Mon profil
            </span>

        </a>


        <!-- =================================================
             RETOUR AU SITE
        ================================================== -->
        <a
            href="/immo-sn/"
            class="
                group
                flex
                items-center
                gap-3
                rounded-xl
                px-3
                py-3
                text-sm
                text-slate-300
                hover:bg-[#0e2a4e]
                hover:text-white
                transition-all
            "
        >

            <span
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    bg-[#0e2a4e]
                    text-slate-200
                    group-hover:bg-[#163d6e]
                    group-hover:text-[#f5b800]
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-[18px] h-[18px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 18l-6-6 6-6"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h11"
                    />
                </svg>

            </span>

            <span class="font-medium">
                Retour au site
            </span>

        </a>

    </nav>

</div>


<!-- =========================================================
     BAS DE SIDEBAR
========================================================== -->
<div class="p-4 mt-4">

    <div class="border-t border-[#0e2a4e] pt-4">

        <div
            class="
                rounded-2xl
                bg-[#0e2a4e]/70
                border
                border-[#163d6e]
                p-4
            "
        >

            <div class="flex items-center gap-3">

                <div
                    class="
                        w-9
                        h-9
                        rounded-xl
                        bg-[#f5b800]/15
                        flex
                        items-center
                        justify-center
                    "
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-[#f5b800]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 11v5"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 8h.01"
                        />

                    </svg>

                </div>


                <div>

                    <p class="text-xs font-semibold text-white">
                        Immo-SN
                    </p>

                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Espace sécurisé
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


</aside>