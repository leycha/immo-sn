
<?php

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$currentPath = preg_replace(
    '#^/immo-sn(?:/public)?#',
    '',
    $currentPath
);

if ($currentPath === '') {
    $currentPath = '/';
}

if (!function_exists('isModeratorActive')) {
    function isModeratorActive(string $path): bool
    {
        global $currentPath;

        if ($path === '/moderateur') {
            return $currentPath === '/moderateur'
                || $currentPath === '/moderateur/';
        }

        return str_starts_with($currentPath, $path);
    }
}

?>
<aside
    id="moderatorSidebar"
    aria-label="Navigation modérateur"
   
class="
    fixed
    inset-y-0
    left-0
    z-50
    w-[270px]
    min-w-[270px]
    max-w-[270px]
    h-screen
    box-border
    shrink-0
    bg-[#30221b]
    text-white
    flex
    flex-col
    border-r
    border-[#45362d]
    overflow-hidden
    -translate-x-full
    transition-transform
    duration-300
    ease-in-out
    lg:translate-x-0
"

>
    <button
        type="button"
        id="moderatorSidebarClose"
        aria-label="Fermer le menu"
        class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-lg text-white hover:bg-[#4a3930] lg:hidden"
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
      <!-- LOGO -->

    <div class="px-6 pt-7 pb-6">

        <a href="/immo-sn/moderateur" class="flex items-center gap-3 group">

            <div class="w-11 h-11 rounded-xl bg-[#f7b500] flex items-center justify-center text-[#30221b] font-bold text-sm shadow-lg group-hover:scale-105 transition-transform">
                IS
            </div>

            <div class="leading-tight">
                <div class="text-xl font-bold tracking-tight">
                    <span class="text-white">Immo</span>
                    <span class="text-[#f7b500]">Sn</span>
                </div>

                <p class="text-[11px] text-[#b8aaa0] mt-0.5">
                    Modération
                </p>
            </div>

        </a>

    </div>

    <div class="mx-6 border-t border-[#45362d]"></div>

    <!-- NAVIGATION -->

    <nav class="flex-1 min-h-0 px-4 py-6 overflow-y-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
        <p class="px-3 mb-3 text-[10px] font-semibold uppercase tracking-[0.18em] text-[#8f8178]">
            Espace modérateur
        </p>

        <!-- TABLEAU DE BORD -->

        <a href="/immo-sn/moderateur"
           class="group flex items-center gap-3 px-3 py-3 mb-1 rounded-xl text-sm font-medium
           <?= isModeratorActive('/moderateur')
                ? 'bg-[#f7b500] text-[#30221b]'
                : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
           ?>">

            <span class="w-9 h-9 rounded-lg flex items-center justify-center
                <?= isModeratorActive('/moderateur')
                    ? 'bg-[#30221b]/10'
                    : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
                ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                </svg>
            </span>

            <span>Tableau de bord</span>
        </a>

        <!-- ANNONCES -->

        <a href="/immo-sn/moderateur/annonces"
           class="group flex items-center gap-3 px-3 py-3 mb-1 rounded-xl text-sm font-medium
           <?= isModeratorActive('/moderateur/annonces')
                ? 'bg-[#f7b500] text-[#30221b]'
                : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
           ?>">

            <span class="w-9 h-9 rounded-lg bg-[#3c2d25] group-hover:bg-[#4a3930] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 11.5 12 4l9 7.5"/>
                    <path d="M5 10.5V20h14v-9.5"/>
                    <path d="M9 20v-5h6v5"/>
                </svg>
            </span>

            <span class="flex-1">Annonces à vérifier</span>
        </a>

        <!-- DEMANDES DE RENOUVELLEMENT -->

        <a href="/immo-sn/moderateur/renouvellements"
           class="group flex items-center gap-3 px-3 py-3 mb-1 rounded-xl text-sm font-medium
           <?= isModeratorActive('/moderateur/renouvellements')
                ? 'bg-[#f7b500] text-[#30221b]'
                : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
           ?>">

            <span class="w-9 h-9 rounded-lg flex items-center justify-center
                <?= isModeratorActive('/moderateur/renouvellements')
                    ? 'bg-[#30221b]/10'
                    : 'bg-[#3c2d25] group-hover:bg-[#4a3930]'
                ?>">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="19" height="19"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M21 12a9 9 0 1 1-2.64-6.36"/>
                    <polyline points="21 3 21 9 15 9"/>
                    <path d="M12 7v5l3 2"/>
                </svg>

            </span>

            <span class="flex-1">Demandes de renouvellement</span>
        </a>


        <!-- SIGNALEMENTS -->

        <a href="/immo-sn/moderateur/signalements"
           class="group flex items-center gap-3 px-3 py-3 mb-1 rounded-xl text-sm font-medium
           <?= isModeratorActive('/moderateur/signalements')
                ? 'bg-[#f7b500] text-[#30221b]'
                : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
           ?>">

            <span class="w-9 h-9 rounded-lg bg-[#3c2d25] group-hover:bg-[#4a3930] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                    <line x1="4" y1="22" x2="4" y2="15"/>
                </svg>
            </span>

            <span class="flex-1">Signalements</span>
        </a>

        <!-- HISTORIQUE -->

        <a href="/immo-sn/moderateur/historique"
           class="group flex items-center gap-3 px-3 py-3 mb-1 rounded-xl text-sm font-medium
           <?= isModeratorActive('/moderateur/historique')
                ? 'bg-[#f7b500] text-[#30221b]'
                : 'text-[#c8beb7] hover:bg-[#3c2d25] hover:text-white'
           ?>">

            <span class="w-9 h-9 rounded-lg bg-[#3c2d25] group-hover:bg-[#4a3930] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <polyline points="12 7 12 12 15 14"/>
                </svg>
            </span>

            <span class="flex-1">Historique des décisions</span>
        </a>

    </nav>

    <!-- PROFIL ET DÉCONNEXION -->

    <div class="px-4 pb-5">

        <div class="border-t border-[#45362d] mb-4"></div>

        <a href="/immo-sn/moderateur/profil"
           class="flex items-center gap-3 p-3 mb-2 rounded-xl
           <?= isModeratorActive('/moderateur/profil')
                ? 'bg-[#f7b500] text-[#30221b]'
                : 'bg-[#3a2b23] border border-[#4a3930] text-white hover:bg-[#45362d]'
           ?>">

            <div class="w-10 h-10 shrink-0 rounded-full bg-[#f7b500] text-[#30221b] flex items-center justify-center font-bold text-sm">
                M
            </div>

            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold truncate">Modérateur</p>
                <p class="text-[11px] opacity-70 truncate">Mon profil</p>
            </div>

        </a>

        <a href="/immo-sn/public/logout"
           title="Déconnexion"
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm text-[#c8beb7] hover:bg-[#3c2d25] hover:text-[#f7b500]">

            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>

            <span>Déconnexion</span>
        </a>

        <p class="text-center text-[10px] text-[#766960] mt-4">
            ImmoSn · Modération
        </p>

    </div>

</aside>
