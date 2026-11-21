
<?php
/**
 * Navbar publique IMMO-SN
 *
 * Menus :
 * - Accueil
 * - Recherche & Filtre
 * - Carte interactive
 * - Scanner QR
 * - Mes favoris
 * - Se connecter
 */

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

/**
 * Détermine si un lien est actif.
 */
function navActive(string $path, string $currentPath): string
{
    // Accueil
    if ($path === '/') {
        return (
            $currentPath === '/'
            || $currentPath === '/immo-sn/'
            || $currentPath === '/immo-sn/public/'
        )
            ? 'bg-[#FFF4CC] text-[#071B36] shadow-sm'
            : 'text-[#71869A]';
    }

    // Carte interactive : à tester avant /biens
    if ($path === '/biens/carte') {
        return (
            $currentPath === '/biens/carte'
            || $currentPath === '/immo-sn/biens/carte'
            || $currentPath === '/immo-sn/public/biens/carte'
        )
            ? 'bg-[#FFF4CC] text-[#071B36] shadow-sm'
            : 'text-[#71869A]';
    }

    // Autres pages
    $fullPath = '/immo-sn/public' . $path;
    $projectPath = '/immo-sn' . $path;

    return (
        $currentPath === $path
        || str_starts_with($currentPath, $fullPath)
        || $currentPath === $projectPath
        || str_starts_with($currentPath, $projectPath . '/')
    )
        ? 'bg-[#FFF4CC] text-[#071B36] shadow-sm'
        : 'text-[#71869A]';
}
?>

<header class="sticky top-0 z-50 border-b border-[#E8E2D4] bg-white/95 backdrop-blur-xl">

    <nav class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">

        <div class="flex h-[76px] items-center justify-between gap-4 lg:gap-6">

            <!-- =====================================================
                 LOGO
            ====================================================== -->

            <a
                href="/immo-sn/"
                class="group flex shrink-0 items-center gap-3"
                aria-label="IMMO-SN - Accueil"
            >

                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#071B36] shadow-lg shadow-[#071B36]/15 transition duration-200 group-hover:-translate-y-0.5 group-hover:shadow-xl">

                    <span class="text-sm font-extrabold tracking-tight text-[#F5B800]">
                        IS
                    </span>

                </div>

                <div class="hidden sm:block">

                    <div class="flex items-baseline leading-none">

                        <span class="text-xl font-extrabold tracking-tight text-[#071B36]">
                            ImmoSn
                        </span>

                        <span class="ml-0.5 text-xl font-extrabold tracking-tight text-[#B88700]">
                            .com
                        </span>

                    </div>

                    <p class="mt-1 text-[9px] font-semibold uppercase tracking-[0.2em] text-[#71869A]">
                        Immobilier au Sénégal
                    </p>

                </div>

            </a>


            <!-- =====================================================
                 NAVIGATION DESKTOP
            ====================================================== -->

            <ul class="hidden items-center gap-1 rounded-2xl border border-[#E8E2D4] bg-[#F5F3ED] p-1.5 md:flex">

                <!-- ACCUEIL -->
                <li>
                    <a
                        href="/immo-sn/"
                        class="group flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 hover:bg-white hover:text-[#071B36] hover:shadow-sm <?= navActive('/', $currentPath) ?>"
                    >

                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#E8E2D4]/70 text-[#071B36] transition group-hover:bg-[#F5B800]">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"/>
                            </svg>

                        </span>

                        <span>Accueil</span>

                    </a>
                </li>


                <!-- RECHERCHE -->
                <li>
                    <a
                        href="/immo-sn/biens"
                        class="group flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 hover:bg-white hover:text-[#071B36] hover:shadow-sm <?= navActive('/biens', $currentPath) ?>"
                    >

                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#E8E2D4]/70 text-[#071B36] transition group-hover:bg-[#F5B800]">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 20l-4-4"/>
                            </svg>

                        </span>

                        <span>Recherche &amp; Filtre</span>

                    </a>
                </li>


                <!-- CARTE INTERACTIVE -->
                <li>
                    <a
                        href="/immo-sn/biens/carte"
                        class="group flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 hover:bg-white hover:text-[#071B36] hover:shadow-sm <?= navActive('/biens/carte', $currentPath) ?>"
                    >

                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#E8E2D4]/70 text-[#071B36] transition group-hover:bg-[#F5B800]">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l-6 3V6l6-3 6 3 6-3v15l-6 3-6-3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v15"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 6v15"/>
                            </svg>

                        </span>

                        <span>Carte interactive</span>

                    </a>
                </li>


                <!-- SCANNER QR -->
                <li>
                    <a
                        href="/immo-sn/scanner"
                        class="group flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 hover:bg-white hover:text-[#071B36] hover:shadow-sm <?= navActive('/scanner', $currentPath) ?>"
                    >

                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#E8E2D4]/70 text-[#071B36] transition group-hover:bg-[#F5B800]">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 4h6v6h-6z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 14h6v6H4z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 14h3v3h-3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 14v6h-3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 20h3"/>
                            </svg>

                        </span>

                        <span>Scanner QR</span>

                    </a>
                </li>

            </ul>


            <!-- =====================================================
                 ACTIONS
            ====================================================== -->

            <div class="flex shrink-0 items-center gap-2 sm:gap-3">

                <!-- =================================================
                     MENU MOBILE
                ================================================== -->

                <details class="group relative md:hidden">

                    <summary
                        class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-[#E8E2D4] bg-white text-[#071B36] transition hover:border-[#F5B800] hover:bg-[#FFF9E6] [&::-webkit-details-marker]:hidden"
                        aria-label="Ouvrir le menu de navigation"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-open:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-6 w-6 group-open:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>

                    </summary>


                    <!-- PANNEAU MOBILE -->
                    <div class="absolute right-0 top-14 z-[60] w-[min(88vw,340px)] rounded-2xl border border-[#E8E2D4] bg-white p-3 shadow-2xl shadow-[#071B36]/15">

                        <div class="mb-3 border-b border-[#E8E2D4] px-3 pb-3">

                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#71869A]">
                                Navigation
                            </p>

                            <p class="mt-1 text-sm font-bold text-[#071B36]">
                                Explorez IMMO-SN
                            </p>

                        </div>

                        <nav class="flex flex-col gap-1" aria-label="Navigation mobile">

                            <a
                                href="/immo-sn/"
                                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-[#FFF4CC] hover:text-[#071B36] <?= navActive('/', $currentPath) ?>"
                            >
                                <span class="text-[#B88700]">⌂</span>
                                Accueil
                            </a>

                            <a
                                href="/immo-sn/biens"
                                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#34445A] transition hover:bg-[#FFF4CC] hover:text-[#071B36] <?= navActive('/biens', $currentPath) ?>"
                            >
                                <span class="text-[#B88700]">⌕</span>
                                Recherche &amp; Filtre
                            </a>

                            <a
                                href="/immo-sn/biens/carte"
                                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#34445A] transition hover:bg-[#FFF4CC] hover:text-[#071B36] <?= navActive('/biens/carte', $currentPath) ?>"
                            >
                                <span class="text-[#B88700]">⌖</span>
                                Carte interactive
                            </a>

                            <a
                                href="/immo-sn/scanner"
                                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#34445A] transition hover:bg-[#FFF4CC] hover:text-[#071B36] <?= navActive('/scanner', $currentPath) ?>"
                            >
                                <span class="text-[#B88700]">▦</span>
                                Scanner QR
                            </a>

                            <a
                                href="/immo-sn/member/favoris"
                                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#34445A] transition hover:bg-[#FFF4CC] hover:text-[#071B36] <?= navActive('/member/favoris', $currentPath) ?>"
                            >
                                <span class="text-[#B88700]">♡</span>
                                Mes favoris
                            </a>

                            <div class="my-2 border-t border-[#E8E2D4]"></div>

                            <a
                                href="/immo-sn/login"
                                class="flex items-center justify-center gap-2 rounded-xl bg-[#071B36] px-4 py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#101D33]"
                            >

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3"/>
                                </svg>

                                Se connecter

                            </a>

                        </nav>

                    </div>

                </details>


                <!-- =================================================
                     FAVORIS
                ================================================== -->

                <a
                    href="/immo-sn/member/favoris"
                    title="Mes favoris"
                    aria-label="Mes favoris"
                    class="hidden h-10 w-10 items-center justify-center rounded-xl border border-[#E8E2D4] bg-white text-[#071B36] transition-all duration-200 hover:border-[#F5B800] hover:bg-[#FFF9E6] hover:text-[#B88700] sm:flex"
                >

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78Z"/>
                    </svg>

                </a>


                <!-- =================================================
                     SE CONNECTER
                ================================================== -->

                <a
                    href="/immo-sn/login"
                    class="hidden items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#071B36]/10 transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#101D33] hover:shadow-xl sm:inline-flex"
                >

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3"/>
                    </svg>

                    <span>Se connecter</span>

                </a>

            </div>

        </div>

    </nav>

</header>
