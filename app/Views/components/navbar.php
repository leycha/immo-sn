<?php
/**
- Navbar public ImmoSn
 *
- Menus :
- - Accueil
- - Recherche & Filtre
- - Carte interactive
- - Scanner QR
- - Se connecter
 */

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

/**
- Détermine si un lien est actif.
 */
function navActive(string $path, string $currentPath): string
{
    // Accueil
    if ($path === '/') {
        return $currentPath === '/'
            || $currentPath === '/immo-sn/public/'
            ? 'bg-white shadow-sm text-[#071b36]'
            : 'text-[#64748b]';
    }

    // Carte : doit être testée avant /biens
    if ($path === '/biens/carte') {
        return $currentPath === '/biens/carte'
            || $currentPath === '/immo-sn/public/biens/carte'
            ? 'bg-white shadow-sm text-[#071b36]'
            : 'text-[#64748b]';
    }

    // Autres pages
    $fullPath = '/immo-sn/public' . $path;

    return $currentPath === $path
        || str_starts_with($currentPath, $fullPath)
        ? 'bg-white shadow-sm text-[#071b36]'
        : 'text-[#64748b]';
}
?>

<header
    class="
        sticky
        top-0
        z-50
        border-b
        border-[#e2e8f0]
        bg-white/95
        backdrop-blur-xl
    "
>

    <nav class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">

        <div class="flex h-[76px] items-center justify-between gap-6">


            <!-- =====================================================
                 LOGO
            ====================================================== -->

            <a
                href="/immo-sn/public/"
                class="group flex items-center gap-3 shrink-0"
            >

                <!-- Logo carré -->
                <div
                    class="
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-2xl
                        bg-[#071b36]
                        shadow-lg
                        shadow-[#071b36]/10
                        transition
                        duration-200
                        group-hover:-translate-y-0.5
                    "
                >
                    <span
                        class="
                            text-sm
                            font-extrabold
                            tracking-tight
                            text-[#f5b800]
                        "
                    >
                        IS
                    </span>
                </div>


                <!-- Nom -->
                <div class="hidden sm:block">

                    <div class="flex items-baseline leading-none">

                        <span
                            class="
                                text-xl
                                font-extrabold
                                tracking-tight
                                text-[#071b36]
                            "
                        >
                            ImmoSn
                        </span>

                        <span
                            class="
                                ml-0.5
                                text-xl
                                font-extrabold
                                tracking-tight
                                text-[#f5b800]
                            "
                        >
                            .com
                        </span>

                    </div>

                    <p
                        class="
                            mt-1
                            text-[9px]
                            font-semibold
                            uppercase
                            tracking-[0.2em]
                            text-[#64748b]
                        "
                    >
                        Immobilier au Sénégal
                    </p>

                </div>

            </a>


            <!-- =====================================================
                 NAVIGATION DESKTOP
            ====================================================== -->

            <ul
                class="
                    hidden
                    md:flex
                    items-center
                    gap-1
                    rounded-2xl
                    border
                    border-[#e2e8f0]
                    bg-[#f8fafc]
                    p-1.5
                "
            >


                <!-- =================================================
1. ACCUEIL
                ================================================== -->

                <li>

                    <a
                        href="/immo-sn/"
                        class="
                            group
                            flex
                            items-center
                            gap-2
                            rounded-xl
                            px-4
                            py-2.5
                            text-sm
                            font-semibold
                            transition-all
                            hover:bg-white
                            hover:text-[#071b36]
                            hover:shadow-sm
                            <?= navActive('/', $currentPath) ?>
                        "
                    >

                        <span
                            class="
                                flex
                                h-7
                                w-7
                                items-center
                                justify-center
                                rounded-lg
                                bg-[#e2e8f0]/70
                                text-[#0e2a4e]
                                transition
                                group-hover:bg-[#f5b800]
                                group-hover:text-[#0b1528]
                            "
                        >

                            <!-- Maison -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
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

                        <span>
                            Accueil
                        </span>

                    </a>

                </li>


                <!-- =================================================
2. RECHERCHE & FILTRE
                ================================================== -->

                <li>

                    <a
                        href="/immo-sn/biens"
                        class="
                            group
                            flex
                            items-center
                            gap-2
                            rounded-xl
                            px-4
                            py-2.5
                            text-sm
                            font-semibold
                            transition-all
                            hover:bg-white
                            hover:text-[#071b36]
                            hover:shadow-sm
                            <?= navActive('/biens', $currentPath) ?>
                        "
                    >

                        <span
                            class="
                                flex
                                h-7
                                w-7
                                items-center
                                justify-center
                                rounded-lg
                                bg-[#e2e8f0]/70
                                text-[#0e2a4e]
                                transition
                                group-hover:bg-[#f5b800]
                                group-hover:text-[#0b1528]
                            "
                        >

                            <!-- Recherche -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle
                                    cx="11"
                                    cy="11"
                                    r="7"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M20 20l-4-4"
                                />

                            </svg>

                        </span>

                        <span>
                            Recherche &amp; Filtre
                        </span>

                    </a>

                </li>


                <!-- =================================================
3. CARTE INTERACTIVE
                ================================================== -->

                <li>

                    <a
                        href="/immo-sn/biens/carte"
                        class="
                            group
                            flex
                            items-center
                            gap-2
                            rounded-xl
                            px-4
                            py-2.5
                            text-sm
                            font-semibold
                            transition-all
                            hover:bg-white
                            hover:text-[#071b36]
                            hover:shadow-sm
                            <?= navActive('/biens/carte', $currentPath) ?>
                        "
                    >

                        <span
                            class="
                                flex
                                h-7
                                w-7
                                items-center
                                justify-center
                                rounded-lg
                                bg-[#e2e8f0]/70
                                text-[#0e2a4e]
                                transition
                                group-hover:bg-[#f5b800]
                                group-hover:text-[#0b1528]
                            "
                        >

                            <!-- Carte -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 18l-6 3V6l6-3 6 3 6-3v15l-6 3-6-3z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 3v15"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 6v15"
                                />

                            </svg>

                        </span>

                        <span>
                            Carte interactive
                        </span>

                    </a>

                </li>


                <!-- =================================================
4. SCANNER QR
                ================================================== -->

                <li>

                    <a
                        href="/immo-sn/scanner"
                        class="
                            group
                            flex
                            items-center
                            gap-2
                            rounded-xl
                            px-4
                            py-2.5
                            text-sm
                            font-semibold
                            transition-all
                            hover:bg-white
                            hover:text-[#071b36]
                            hover:shadow-sm
                            <?= navActive('/scanner', $currentPath) ?>
                        "
                    >

                        <span
                            class="
                                flex
                                h-7
                                w-7
                                items-center
                                justify-center
                                rounded-lg
                                bg-[#e2e8f0]/70
                                text-[#0e2a4e]
                                transition
                                group-hover:bg-[#f5b800]
                                group-hover:text-[#0b1528]
                            "
                        >

                            <!-- QR Code -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 4h6v6H4z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14 4h6v6h-6z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 14h6v6H4z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14 14h3v3h-3z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M20 14v6h-3"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14 20h3"
                                />

                            </svg>

                        </span>

                        <span>
                            Scanner QR
                        </span>

                    </a>

                </li>

            </ul>


            <!-- =====================================================
                 ACTIONS
            ====================================================== -->

           <div class="flex items-center gap-2 sm:gap-3">

    <!-- MENU MOBILE -->
    <details class="relative md:hidden">

        <summary
            class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-[#e2e8f0] bg-white text-[#071b36] hover:bg-[#f8fafc]"
            aria-label="Ouvrir le menu de navigation"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </summary>

        <div class="absolute right-0 top-14 z-[60] w-[min(88vw,340px)] rounded-2xl border border-[#e2e8f0] bg-white p-3 shadow-2xl">

            <nav class="flex flex-col gap-1" aria-label="Navigation mobile">

                <a href="/immo-sn/"
                   class="rounded-xl px-4 py-3 text-sm font-semibold text-[#0b1528] hover:bg-[#f8fafc] hover:text-[#071b36]">
                    Accueil
                </a>

                <a href="/immo-sn/biens"
                   class="rounded-xl px-4 py-3 text-sm font-semibold text-[#0b1528] hover:bg-[#f8fafc] hover:text-[#071b36]">
                    Recherche &amp; Filtre
                </a>

                <a href="/immo-sn/biens/carte"
                   class="rounded-xl px-4 py-3 text-sm font-semibold text-[#0b1528] hover:bg-[#f8fafc] hover:text-[#071b36]">
                    Carte interactive
                </a>

                <a href="/immo-sn/scanner"
                   class="rounded-xl px-4 py-3 text-sm font-semibold text-[#0b1528] hover:bg-[#f8fafc] hover:text-[#071b36]">
                    Scanner QR
                </a>

                <a href="/immo-sn/member/favoris"
                   class="rounded-xl px-4 py-3 text-sm font-semibold text-[#0b1528] hover:bg-[#f8fafc] hover:text-[#071b36]">
                    Mes favoris
                </a>

                <div class="my-2 border-t border-[#e2e8f0]"></div>

                <a href="/immo-sn/login"
                   class="rounded-xl bg-[#071b36] px-4 py-3 text-center text-sm font-semibold text-white hover:bg-[#0e2a4e]">
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
                    class="
                        hidden
                        sm:flex
                        h-10
                        w-10
                        items-center
                        justify-center
                        rounded-xl
                        border
                        border-[#e2e8f0]
                        bg-white
                        text-[#0e2a4e]
                        transition-all
                        hover:border-[#071b36]/30
                        hover:bg-[#f8fafc]
                        hover:text-[#071b36]
                    "
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-[18px] w-[18px]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78Z"
                        />

                    </svg>

                </a>


                <!-- =================================================
                     SE CONNECTER
                ================================================== -->

                <a
                    href="/immo-sn/login"
                    class="
                       hidden
                        sm:inline-flex
                        items-center
                        justify-center
                        gap-2
                        rounded-xl
                        bg-[#071b36]
                        px-5
                        py-2.5
                        text-sm
                        font-semibold
                        text-white
                        shadow-lg
                        shadow-[#071b36]/10
                        transition-all
                        hover:-translate-y-0.5
                        hover:bg-[#0e2a4e]
                        hover:shadow-xl
                    "
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-[#f5b800]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10 17l5-5-5-5"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12H3"
                        />

                    </svg>

                    <span>
                        Se connecter
                    </span>

                </a>

            </div>

        </div>

    </nav>

</header>