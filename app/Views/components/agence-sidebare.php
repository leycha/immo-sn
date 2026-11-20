<?php

$nomAgence = $agence['nom_agence'] ?? 'Mon agence';
$initialeAgence = strtoupper(substr($nomAgence, 0, 1));

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$currentPath = preg_replace('#^/immo-sn/public#', '', $currentPath);
$currentPath = preg_replace('#^/immo-sn#', '', $currentPath);

if (!$currentPath) {
    $currentPath = '/';
}

if (!function_exists('isAgenceActive')) {
    function isAgenceActive(string $path): bool
    {
        global $currentPath;

        if ($path === '/agence/mon-agence') {
            return $currentPath === $path;
        }

        return $currentPath === $path
            || str_starts_with($currentPath, $path . '/');
    }
}

?>

<aside
    id="agencySidebar"
    aria-label="Navigation agence"
    class="fixed inset-y-0 left-0 z-50 flex h-screen min-h-screen w-[270px] shrink-0 flex-col overflow-y-auto border-r border-[#0e2a4e] bg-[#071b36] text-white -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0"
>
    <button
        type="button"
        id="agencySidebarClose"
        aria-label="Fermer le menu"
        class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-lg text-white hover:bg-[#0e2a4e] lg:hidden"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
        </svg>
    </button>

    <!-- LOGO -->
    <div class="px-5 pt-6 pb-5">

        <a href="/immo-sn/agence/mon-agence" class="flex items-center gap-3 group">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#f5b800] shadow-lg shadow-[#071b36]/20 transition group-hover:scale-105">
                <span class="text-sm font-extrabold text-[#0b1528]">IS</span>
            </div>

            <div>
                <div class="flex items-baseline">
                    <span class="text-xl font-bold tracking-tight">ImmoSn</span>
                    <span class="font-bold text-[#f5b800]">.com</span>
                </div>

                <p class="mt-0.5 text-[10px] uppercase tracking-[0.18em] text-slate-400">
                    Espace agence
                </p>
            </div>

        </a>

    </div>

    <!-- NAVIGATION -->
    <div class="flex-1 px-4">

        <nav class="space-y-1">

            <!-- TABLEAU DE BORD -->
            <a href="/immo-sn/agence/mon-agence"
               class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm transition-all
               <?= isAgenceActive('/agence/mon-agence')
                   ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                   : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white' ?>">

                <span class="flex h-9 w-9 items-center justify-center rounded-lg
                    <?= isAgenceActive('/agence/mon-agence')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]' ?>">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"/>
                    </svg>
                </span>

                <span class="font-medium">Tableau de bord</span>
            </a>

            <!-- MES BIENS -->
            <a href="/immo-sn/agence/biens"
               class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm transition-all
               <?= isAgenceActive('/agence/biens')
                   ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                   : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white' ?>">

                <span class="flex h-9 w-9 items-center justify-center rounded-lg
                    <?= isAgenceActive('/agence/biens')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]' ?>">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                        <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5"/>
                    </svg>
                </span>

                <span class="font-medium">Mes biens</span>
            </a>

            <!-- PUBLIER UN BIEN -->
            <a href="/immo-sn/agence/publier"
               class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm transition-all
               <?= isAgenceActive('/agence/publier')
                   ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                   : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white' ?>">

                <span class="flex h-9 w-9 items-center justify-center rounded-lg
                    <?= isAgenceActive('/agence/publier')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]' ?>">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
                    </svg>
                </span>

                <span class="font-medium">Publier un bien</span>
            </a>

            <!-- QR CODES -->
            <a href="/immo-sn/agence/qr-codes"
               class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm transition-all
               <?= isAgenceActive('/agence/qr-codes')
                   ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                   : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white' ?>">

                <span class="flex h-9 w-9 items-center justify-center rounded-lg
                    <?= isAgenceActive('/agence/qr-codes')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]' ?>">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                        <path stroke-linecap="round" d="M14 14h3v3h-3zM20 14v2M14 20h2M20 19v2"/>
                    </svg>
                </span>

                <span class="font-medium">Mes QR codes</span>
            </a>

        </nav>

        <nav class="mt-2 space-y-1">

            <a href="/immo-sn/agence/profil"
               class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm transition-all
               <?= isAgenceActive('/agence/profil')
                   ? 'bg-[#f5b800] text-[#0b1528] font-semibold shadow-md shadow-[#071b36]/20'
                   : 'text-slate-300 hover:bg-[#0e2a4e] hover:text-white' ?>">

                <span class="flex h-9 w-9 items-center justify-center rounded-lg
                    <?= isAgenceActive('/agence/profil')
                        ? 'bg-[#0b1528]/10 text-[#0b1528]'
                        : 'bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]' ?>">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="8" r="3"/>
                        <path stroke-linecap="round" d="M5.5 20a6.5 6.5 0 0113 0"/>
                    </svg>
                </span>

                <span class="font-medium">Mon profil</span>
            </a>

            <a href="/immo-sn/"
               class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-300 transition-all hover:bg-[#0e2a4e] hover:text-white">

                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0e2a4e] text-slate-200 group-hover:bg-[#163d6e] group-hover:text-[#f5b800]">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6M9 12h11"/>
                    </svg>
                </span>

                <span class="font-medium">Retour au site</span>
            </a>

        </nav>

    </div>

    <!-- BAS DE SIDEBAR -->
    <div class="mt-4 p-4">

        <div class="border-t border-[#0e2a4e] pt-4">

            <div class="rounded-2xl border border-[#163d6e] bg-[#0e2a4e]/70 p-4">

                <p class="text-xs font-semibold text-white">Immo-SN</p>

                <p class="mt-1 text-[11px] text-slate-400">
                    Espace professionnel sécurisé
                </p>

                <a href="/immo-sn/logout"
                   class="mt-3 inline-block text-xs font-medium text-[#f7b500] hover:underline">
                    Déconnexion
                </a>

            </div>

        </div>

    </div>

</aside>
<button
    type="button"
    id="agencySidebarOverlay"
    aria-label="Fermer le menu"
    class="fixed inset-0 z-40 hidden bg-[#071b36]/50 lg:hidden"
></button>

<header class="sticky top-0 z-30 flex items-center justify-between border-b border-[#e2e8f0] bg-white px-4 py-3 lg:hidden">
    <div>
        <p class="text-sm font-bold text-[#071b36]">IMMO-SN</p>
        <p class="text-xs text-[#64748b]">Espace agence</p>
    </div>

    <button
        type="button"
        id="agencySidebarOpen"
        aria-label="Ouvrir le menu"
        aria-controls="agencySidebar"
        aria-expanded="false"
        class="flex h-10 w-10 items-center justify-center rounded-lg border border-[#e2e8f0] text-[#071b36]"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</header>

<script>
(function () {
    const sidebar = document.getElementById('agencySidebar');
    const openButton = document.getElementById('agencySidebarOpen');
    const closeButton = document.getElementById('agencySidebarClose');
    const overlay = document.getElementById('agencySidebarOverlay');

    if (!sidebar || !openButton || !closeButton || !overlay) return;

    function ouvrirMenu() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        openButton.setAttribute('aria-expanded', 'true');
    }

    function fermerMenu() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
        openButton.setAttribute('aria-expanded', 'false');
    }

    openButton.addEventListener('click', ouvrirMenu);
    closeButton.addEventListener('click', fermerMenu);
    overlay.addEventListener('click', fermerMenu);

    sidebar.querySelectorAll('a').forEach(function (lien) {
        lien.addEventListener('click', fermerMenu);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') fermerMenu();
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) fermerMenu();
    });
})();
</script>