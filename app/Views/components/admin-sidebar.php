
<?php

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

/*
 * Nettoyage du chemin du projet
 */
$currentPath = preg_replace(
    '#^/immo-sn/public#',
    '',
    $currentPath
);

if ($currentPath === '') {
    $currentPath = '/';
}


/*
 * Vérifie si une route est active
 */
function isAdminActive(string $path): bool
{
    global $currentPath;

    if ($path === '/admin') {
        return $currentPath === '/admin' || $currentPath === '/admin/';
    }

    return str_starts_with($currentPath, $path);
}

?>

<aside
    id="adminSidebar"
    aria-label="Navigation administration"
    class="
        fixed
        inset-y-0
        left-0
        z-50
        w-[270px]
        h-screen
        min-h-screen
        shrink-0
        bg-[#30221b]
        text-white
        flex
        flex-col
        border-r
        border-[#45362d]
        overflow-y-auto
        -translate-x-full
        transition-transform
        duration-300
        ease-in-out
        lg:static
        lg:translate-x-0
        lg:h-auto
    "
>
    <button
        type="button"
        id="adminSidebarClose"
        aria-label="Fermer le menu"
        class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-lg text-white hover:bg-[#4a3930] lg:hidden"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="22"
            height="22"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>

    <!-- =========================================
         LOGO
    ========================================== -->

    <div class="px-6 pt-7 pb-6">

        <a
            href="/immo-sn/public/admin"
            class="flex items-center gap-3 group"
        >

            <!-- Logo -->

            <div
                class="
                    w-11
                    h-11
                    rounded-xl
                    bg-[#f7b500]
                    flex
                    items-center
                    justify-center
                    text-[#30221b]
                    font-bold
                    text-sm
                    shadow-lg
                    shadow-black/10
                    group-hover:scale-105
                    transition-transform
                "
            >
                IS
            </div>


            <!-- Nom -->

            <div class="leading-tight">

                <div class="text-xl font-bold tracking-tight">

                    <span class="text-white">
                        Immo
                    </span>

                    <span class="text-[#f7b500]">
                        Sn
                    </span>

                </div>

                <p class="text-[11px] text-[#b8aaa0] mt-0.5">
                    Administration
                </p>

            </div>

        </a>

    </div>


    <!-- =========================================
         SEPARATEUR
    ========================================== -->

    <div class="mx-6 border-t border-[#45362d]"></div>


    <!-- =========================================
         NAVIGATION
    ========================================== -->

    <nav class="flex-1 px-4 py-6 overflow-y-auto">


        <!-- TITRE -->

        <p
            class="
                px-3
                mb-3
                text-[10px]
                font-semibold
                uppercase
                tracking-[0.18em]
                text-[#8f8178]
            "
        >
            Navigation
        </p>


        <!-- =====================================
             DASHBOARD
        ====================================== -->

        <a
            href="/immo-sn/public/admin"
            class="
                group
                flex
                items-center
                gap-3
                px-3
                py-3
                mb-1
                rounded-xl
                text-sm
                font-medium

                <?= isAdminActive('/admin')
                    ? 'bg-[#f7b500] text-[#30221b] shadow-lg shadow-[#f7b500]/10'
                    : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    w-9
                    h-9
                    rounded-lg
                    flex
                    items-center
                    justify-center

                    <?= isAdminActive('/admin')
                        ? 'bg-[#30221b]/10'
                        : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>

            </span>

            <span>
                Dashboard
            </span>

        </a>


        <!-- =====================================
             BIENS
        ====================================== -->

        <a
            href="/immo-sn/public/admin/biens"
            class="
                group
                flex
                items-center
                gap-3
                px-3
                py-3
                mb-1
                rounded-xl
                text-sm
                font-medium

                <?= isAdminActive('/admin/biens')
                    ? 'bg-[#f7b500] text-[#30221b] shadow-lg shadow-[#f7b500]/10'
                    : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    w-9
                    h-9
                    rounded-lg
                    flex
                    items-center
                    justify-center

                    <?= isAdminActive('/admin/biens')
                        ? 'bg-[#30221b]/10'
                        : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M3 11.5 12 4l9 7.5"/>
                    <path d="M5 10.5V20h14v-9.5"/>
                    <path d="M9 20v-5h6v5"/>
                </svg>

            </span>

            <span class="flex-1">
                Gestion des biens 
            </span>

        </a>
        
<!-- =====================================
     DEMANDES DE RENOUVELLEMENT
====================================== -->

<a
    href="/immo-sn/public/admin/renouvellements"
    class="
        group
        flex
        items-center
        gap-3
        px-3
        py-3
        mb-1
        rounded-xl
        text-sm
        font-medium

        <?= isAdminActive('/admin/renouvellements')
            ? 'bg-[#f7b500] text-[#30221b] shadow-lg shadow-[#f7b500]/10'
            : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
        ?>
    "
>
    <span
        class="
            w-9
            h-9
            rounded-lg
            flex
            items-center
            justify-center

            <?= isAdminActive('/admin/renouvellements')
                ? 'bg-[#30221b]/10'
                : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
            ?>
        "
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="19"
            height="19"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M21 12a9 9 0 1 1-2.64-6.36"/>
            <polyline points="21 3 21 9 15 9"/>
            <path d="M12 7v5l3 2"/>
        </svg>
    </span>

    <span class="flex-1">
        Demandes de renouvellement
    </span>
</a>

        <!-- =====================================
             GESTION DES AGENCES
        ====================================== -->

        <a
            href="/immo-sn/public/admin/agences"
            class="
                group
                flex
                items-center
                gap-3
                px-3
                py-3
                mb-1
                rounded-xl
                text-sm
                font-medium

                <?= isAdminActive('/admin/agences')
                    ? 'bg-[#f7b500] text-[#30221b] shadow-lg shadow-[#f7b500]/10'
                    : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    w-9
                    h-9
                    rounded-lg
                    flex
                    items-center
                    justify-center

                    <?= isAdminActive('/admin/agences')
                        ? 'bg-[#30221b]/10'
                        : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M3 21h18"/>
                    <path d="M5 21V7l8-4v18"/>
                    <path d="M19 21V11l-6-4"/>
                    <path d="M9 9v.01"/>
                    <path d="M9 12v.01"/>
                    <path d="M9 15v.01"/>
                    <path d="M9 18v.01"/>
                    <path d="M15 13v.01"/>
                    <path d="M15 16v.01"/>
                    <path d="M15 19v.01"/>
                </svg>

            </span>

            <span class="flex-1">
                Gestion des agences
            </span>

        </a>

<!-- =====================================
     GESTION DES UTILISATEURS
====================================== -->

<a
    href="/immo-sn/public/admin/utilisateurs"
    class="
        group
        flex
        items-center
        gap-3
        px-3
        py-3
        mb-1
        rounded-xl
        text-sm
        font-medium

        <?= isAdminActive('/admin/utilisateurs')
            ? 'bg-[#f7b500] text-[#30221b] shadow-lg shadow-[#f7b500]/10'
            : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
        ?>
    "
>
    <span
        class="
            w-9
            h-9
            rounded-lg
            flex
            items-center
            justify-center

            <?= isAdminActive('/admin/utilisateurs')
                ? 'bg-[#30221b]/10'
                : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
            ?>
        "
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="19"
            height="19"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="10" cy="7" r="4"/>
            <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
    </span>

    <span class="flex-1">
        Gestion des utilisateurs
    </span>
</a>
       

        <!-- =====================================
             CRON MONITOR
        ====================================== -->

        <a
            href="/immo-sn/public/admin/cron-monitor"
            class="
                group
                flex
                items-center
                gap-3
                px-3
                py-3
                mb-1
                rounded-xl
                text-sm
                font-medium

                <?= isAdminActive('/admin/cron-monitor')
                    ? 'bg-[#f7b500] text-[#30221b] shadow-lg shadow-[#f7b500]/10'
                    : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    w-9
                    h-9
                    rounded-lg
                    flex
                    items-center
                    justify-center

                    <?= isAdminActive('/admin/cron-monitor')
                        ? 'bg-[#30221b]/10'
                        : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="12" cy="12" r="9"/>
                    <polyline points="12 7 12 12 15 14"/>
                </svg>

            </span>

            <span>
                Cron Monitor
            </span>

        </a>


        <!-- =====================================
             MAIL LOGS
        ====================================== -->

        <a
            href="/immo-sn/public/admin/mail-logs"
            class="
                group
                flex
                items-center
                gap-3
                px-3
                py-3
                mb-1
                rounded-xl
                text-sm
                font-medium

                <?= isAdminActive('/admin/mail-logs')
                    ? 'bg-[#f7b500] text-[#30221b] shadow-lg shadow-[#f7b500]/10'
                    : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
                ?>
            "
        >

            <span
                class="
                    w-9
                    h-9
                    rounded-lg
                    flex
                    items-center
                    justify-center

                    <?= isAdminActive('/admin/mail-logs')
                        ? 'bg-[#30221b]/10'
                        : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
                    ?>
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>

            </span>

            <span>
                Mail Logs
            </span>

        </a>


        <!-- =====================================
             SIGNALEMENTS
        ====================================== -->

        <a href="/immo-sn/public/admin/signalements"
   
            class="
                group
                flex
                items-center
                gap-3
                px-3
                py-3
                mb-1
                rounded-xl
                text-sm
                font-medium
                text-[#c8beb7]
                hover:bg-[#3c2d25]
                hover:text-white
            "
        >

            <span
                class="
                    w-9
                    h-9
                    rounded-lg
                    bg-[#3c2d25]
                    group-hover:bg-[#4a3930]
                    flex
                    items-center
                    justify-center
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                    <line x1="4" y1="22" x2="4" y2="15"/>
                </svg>

            </span>

            <span>
                Signalements
            </span>

        </a>

    </nav>


    <!-- =========================================
         BAS DE SIDEBAR
    ========================================== -->

    <div class="px-4 pb-5">


        <!-- Ligne -->

        <div class="border-t border-[#45362d] mb-4"></div>


        <!-- PROFIL ADMIN -->

        <div
            class="
                flex
                items-center
                gap-3
                p-3
                rounded-xl
                bg-[#3a2b23]
                border
                border-[#4a3930]
            "
        >

            <!-- Avatar -->

            <div
                class="
                    w-10
                    h-10
                    shrink-0
                    rounded-full
                    bg-[#f7b500]
                    text-[#30221b]
                    flex
                    items-center
                    justify-center
                    font-bold
                    text-sm
                "
            >
                A
            </div>


            <!-- Informations -->

            <div class="min-w-0 flex-1">

                <p class="text-sm font-semibold text-white truncate">
                    Administrateur
                </p>

                <p class="text-[11px] text-[#a99b91] truncate">
                    Gestion ImmoSn
                </p>

            </div>


            <!-- Déconnexion -->

            <a
                href="/immo-sn/public/logout"
                title="Déconnexion"
                class="
                    w-8
                    h-8
                    rounded-lg
                    flex
                    items-center
                    justify-center
                    text-[#a99b91]
                    hover:text-[#f7b500]
                    hover:bg-[#4a3930]
                "
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>

            </a>

        </div>


        <!-- VERSION -->

        <p class="text-center text-[10px] text-[#766960] mt-4">
            ImmoSn · Administration
        </p>

    </div>

</aside>

