
<?php

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

/*
 * On enlève le chemin du projet pour obtenir
 * uniquement la route de l'application.
 *
 * Exemple :
 * /immo-sn/public/admin/biens
 * devient :
 * /admin/biens
 */
$currentPath = preg_replace(
    '#^/immo-sn(?:/public)?#',
    '',
    $currentPath
);

if ($currentPath === '') {
    $currentPath = '/';
}

$isMemberArea     = str_starts_with($currentPath, '/member');
$isAdminArea      = str_starts_with($currentPath, '/admin');
$isAgencyArea     = str_starts_with($currentPath, '/agence');
$isModeratorArea  = str_starts_with($currentPath, '/moderateur');
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="theme-color"
        content="#071B36"
    >

    <meta
        name="description"
        content="IMMO-SN, votre plateforme immobilière au Sénégal."
    >

    <title>
        <?= htmlspecialchars($title ?? 'IMMO-SN — Plateforme Immobilière', ENT_QUOTES, 'UTF-8') ?>
    </title>


    <!-- =====================================================
         GOOGLE FONTS
    ====================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         TAILWIND CSS
    ====================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>


    <!-- =====================================================
         CSS PRINCIPAL
    ====================================================== -->

    <link
        rel="stylesheet"
        href="/assets/css/app.css"
    >


    <!-- =====================================================
         IDENTITÉ VISUELLE IMMO-SN
         BLEU NUIT • OR • IVOIRE
    ====================================================== -->

    <style>

        /* -----------------------------------------------------
           VARIABLES GLOBALES
        ------------------------------------------------------ */

        :root {
            --immo-navy: #071B36;
            --immo-navy-soft: #101D33;
            --immo-navy-light: #1A2B45;

            --immo-gold: #F5B800;
            --immo-gold-dark: #D7A200;
            --immo-gold-deep: #B88700;
            --immo-gold-soft: #FFF4CC;
            --immo-gold-pale: #FFF9E6;

            --immo-ivory: #F5F3ED;
            --immo-white: #FFFFFF;

            --immo-steel: #71869A;
            --immo-border: #E8E2D4;

            --immo-success: #15803D;
            --immo-danger: #DC2626;
            --immo-warning: #B45309;
            --immo-info: #5279B8;
        }


        /* -----------------------------------------------------
           BASE
        ------------------------------------------------------ */

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        h1,
        h2,
        h3,
        h4 {
            letter-spacing: -0.02em;
        }


        /* -----------------------------------------------------
           SCROLLBAR
        ------------------------------------------------------ */

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #F5F3ED;
        }

        ::-webkit-scrollbar-thumb {
            background: #D7CDAF;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #D7A200;
        }


        /* -----------------------------------------------------
           SÉLECTION DE TEXTE
        ------------------------------------------------------ */

        ::selection {
            background: #FFF0BF;
            color: #071B36;
        }


        /* -----------------------------------------------------
           TRANSITIONS GÉNÉRALES
        ------------------------------------------------------ */

        a,
        button,
        input,
        textarea,
        select {
            transition:
                color 0.2s ease,
                background-color 0.2s ease,
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }


        /* -----------------------------------------------------
           FOND DES ESPACES ADMIN, MEMBRE ET MODÉRATEUR
        ------------------------------------------------------ */

        .immo-dashboard {
            background:
                radial-gradient(
                    circle at top right,
                    rgba(245, 184, 0, 0.09),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    #F5F3ED 0%,
                    #FAFAF8 50%,
                    #F1EEE5 100%
                );
        }


        /* -----------------------------------------------------
           ZONE PRINCIPALE
        ------------------------------------------------------ */

        .immo-main-content {
            min-height: 100vh;
        }


        /* -----------------------------------------------------
           ANIMATION D'APPARITION DES PAGES
        ------------------------------------------------------ */

        .page-transition {
            animation: pageFade 0.35s ease-out;
        }

        @keyframes pageFade {

            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        /* -----------------------------------------------------
           FOCUS ACCESSIBLE
        ------------------------------------------------------ */

        :focus-visible {
            outline: 3px solid rgba(245, 184, 0, 0.45);
            outline-offset: 2px;
        }


        /* -----------------------------------------------------
           LIENS
        ------------------------------------------------------ */

        a {
            -webkit-tap-highlight-color: transparent;
        }


        /* -----------------------------------------------------
           BOUTONS
        ------------------------------------------------------ */

        button {
            -webkit-tap-highlight-color: transparent;
        }


        /* -----------------------------------------------------
           IMAGES
        ------------------------------------------------------ */

        img {
            max-width: 100%;
        }


        /* -----------------------------------------------------
           MOBILE
        ------------------------------------------------------ */

        @media (max-width: 640px) {

            .page-transition {
                animation-duration: 0.25s;
            }

        }

    </style>

</head>


<body
    class="<?= $isMemberArea || $isAdminArea || $isAgencyArea || $isModeratorArea
        ? 'immo-dashboard text-[#071B36] min-h-screen'
        : 'bg-[#F5F3ED] text-[#071B36] min-h-screen flex flex-col'
    ?>"
>


<?php if ($isAdminArea): ?>

    <!-- =====================================================
         ESPACE ADMINISTRATION
    ====================================================== -->

    <div class="flex min-h-screen w-full">


        <!-- SIDEBAR ADMIN -->

        <?php
        require BASE_PATH . '/app/Views/components/admin-sidebar.php';
        ?>


        <!-- OVERLAY MOBILE -->

        <button
            type="button"
            id="adminSidebarOverlay"
            aria-label="Fermer le menu"
            class="fixed inset-0 z-40 hidden bg-[#071B36]/60 backdrop-blur-sm lg:hidden"
        ></button>


        <!-- =================================================
             CONTENU ADMIN
        ================================================== -->

        <main class="immo-main-content min-w-0 flex-1 page-transition flex flex-col lg:ml-[270px] lg:w-[calc(100%-270px)] lg:flex-none">


            <!-- HEADER MOBILE ADMIN -->

            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-[#E8E2D4] bg-white/95 px-4 py-3 backdrop-blur-md lg:hidden">

                <div>
                    <p class="text-sm font-extrabold tracking-wide text-[#071B36]">
                        IMMO-SN
                    </p>

                    <p class="text-xs text-[#71869A]">
                        Administration
                    </p>
                </div>


                <button
                    type="button"
                    id="adminSidebarOpen"
                    aria-label="Ouvrir le menu"
                    aria-controls="adminSidebar"
                    aria-expanded="false"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#E8E2D4] bg-white text-[#071B36] hover:border-[#D7A200] hover:bg-[#FFF9E6] hover:text-[#8A6500]"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    >
                        <line x1="4" y1="6" x2="20" y2="6"/>
                        <line x1="4" y1="12" x2="20" y2="12"/>
                        <line x1="4" y1="18" x2="20" y2="18"/>
                    </svg>
                </button>

            </header>


            <!-- CONTENU DE LA PAGE -->

            <div class="flex-1">
                <?= $content ?>
            </div>


            <!-- =================================================
                 FOOTER ADMIN
            ================================================== -->

            <footer class="mt-auto border-t border-[#E8E2D4] bg-white">

                <div class="mx-auto flex max-w-[1600px] flex-col gap-3 px-5 py-6 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10">


                    <!-- IDENTITÉ -->

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#071B36] text-lg font-extrabold text-[#F5B800] shadow-sm">
                            I
                        </div>

                        <div>
                            <p class="text-sm font-extrabold tracking-wide text-[#071B36]">
                                IMMO-SN
                            </p>

                            <p class="text-xs text-[#71869A]">
                                Votre plateforme immobilière au Sénégal
                            </p>
                        </div>

                    </div>


                    <!-- INFORMATIONS -->

                    <div class="text-xs leading-6 text-[#71869A] md:text-right">

                        <p>
                            Espace d’administration — Gestion des utilisateurs
                        </p>

                        <p>
                            &copy; <?= date('Y') ?> IMMO-SN.
                            Tous droits réservés.
                        </p>

                    </div>

                </div>

            </footer>

        </main>

    </div>


    <!-- =====================================================
         JAVASCRIPT SIDEBAR ADMIN
    ====================================================== -->

    <script>
    (function () {
        const sidebar = document.getElementById('adminSidebar');
        const openButton = document.getElementById('adminSidebarOpen');
        const closeButton = document.getElementById('adminSidebarClose');
        const overlay = document.getElementById('adminSidebarOverlay');

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


<?php elseif ($isModeratorArea): ?>


    <!-- =====================================================
         ESPACE MODÉRATEUR
    ====================================================== -->

    <div class="flex min-h-screen w-full">


        <!-- SIDEBAR MODÉRATEUR -->

        <?php
        require BASE_PATH . '/app/Views/components/moderateur-sidebar.php';
        ?>


        <!-- OVERLAY MOBILE -->

        <button
            type="button"
            id="moderatorSidebarOverlay"
            aria-label="Fermer le menu"
            class="fixed inset-0 z-40 hidden bg-[#071B36]/60 backdrop-blur-sm lg:hidden"
        ></button>


        <!-- =================================================
             CONTENU MODÉRATEUR
        ================================================== -->

        <main class="
            immo-main-content
            min-w-0
            w-full
            flex-1
            page-transition
            flex
            flex-col
            h-screen
            overflow-y-auto
            lg:ml-[270px]
            lg:w-[calc(100%-270px)]
            lg:flex-none
        ">


            <!-- HEADER MOBILE MODÉRATEUR -->

            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-[#E8E2D4] bg-white/95 px-4 py-3 backdrop-blur-md lg:hidden">

                <div>
                    <p class="text-sm font-extrabold tracking-wide text-[#071B36]">
                        IMMO-SN
                    </p>

                    <p class="text-xs text-[#71869A]">
                        Espace modérateur
                    </p>
                </div>


                <button
                    type="button"
                    id="moderatorSidebarOpen"
                    aria-label="Ouvrir le menu"
                    aria-controls="moderatorSidebar"
                    aria-expanded="false"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#E8E2D4] bg-white text-[#071B36] hover:border-[#D7A200] hover:bg-[#FFF9E6] hover:text-[#8A6500]"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

            </header>


            <!-- CONTENU -->

            <div class="flex-1">
                <?= $content ?>
            </div>


            <!-- =================================================
                 FOOTER MODÉRATEUR
            ================================================== -->

            <footer class="mt-auto border-t border-[#E8E2D4] bg-white">

                <div class="mx-auto flex max-w-[1600px] flex-col gap-3 px-5 py-6 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10">


                    <!-- IDENTITÉ -->

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#071B36] text-lg font-extrabold text-[#F5B800] shadow-sm">
                            I
                        </div>

                        <div>
                            <p class="text-sm font-extrabold tracking-wide text-[#071B36]">
                                IMMO-SN
                            </p>

                            <p class="text-xs text-[#71869A]">
                                Votre plateforme immobilière au Sénégal
                            </p>
                        </div>

                    </div>


                    <!-- INFORMATIONS -->

                    <div class="text-xs leading-6 text-[#71869A] md:text-right">

                        <p>
                            Espace modérateur — Sécurité et qualité des annonces
                        </p>

                        <p>
                            &copy; <?= date('Y') ?> IMMO-SN.
                            Tous droits réservés.
                        </p>

                    </div>

                </div>

            </footer>

        </main>

    </div>


    <!-- =====================================================
         JAVASCRIPT SIDEBAR MODÉRATEUR
    ====================================================== -->

    <script>
    (function () {
        const sidebar = document.getElementById('moderatorSidebar');
        const openButton = document.getElementById('moderatorSidebarOpen');
        const closeButton = document.getElementById('moderatorSidebarClose');
        const overlay = document.getElementById('moderatorSidebarOverlay');

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


<?php elseif ($isMemberArea): ?>


    <!-- =====================================================
         ESPACE MEMBRE
    ====================================================== -->

    <div class="flex min-h-screen w-full">


        <!-- SIDEBAR MEMBRE -->

        <?php
        require BASE_PATH . '/app/Views/components/member-sidebar.php';
        ?>


        <!-- OVERLAY MOBILE -->

        <button
            type="button"
            id="memberSidebarOverlay"
            aria-label="Fermer le menu"
            class="fixed inset-0 z-40 hidden bg-[#071B36]/60 backdrop-blur-sm lg:hidden"
        ></button>


        <!-- =================================================
             CONTENU MEMBRE
        ================================================== -->

        <main class="immo-main-content min-w-0 flex-1 page-transition flex flex-col lg:ml-[270px] lg:w-[calc(100%-270px)] lg:flex-none">


            <!-- HEADER MOBILE MEMBRE -->

            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-[#E8E2D4] bg-white/95 px-4 py-3 backdrop-blur-md lg:hidden">

                <div>
                    <p class="text-sm font-extrabold tracking-wide text-[#071B36]">
                        IMMO-SN
                    </p>

                    <p class="text-xs text-[#71869A]">
                        Espace membre
                    </p>
                </div>


                <button
                    type="button"
                    id="memberSidebarOpen"
                    aria-label="Ouvrir le menu"
                    aria-controls="memberSidebar"
                    aria-expanded="false"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#E8E2D4] bg-white text-[#071B36] hover:border-[#D7A200] hover:bg-[#FFF9E6] hover:text-[#8A6500]"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

            </header>


            <!-- CONTENU -->

            <div class="flex-1">
                <?= $content ?>
            </div>


            <!-- =================================================
                 FOOTER MEMBRE
            ================================================== -->

            <footer class="mt-auto border-t border-[#E8E2D4] bg-white">

                <div class="mx-auto flex max-w-[1600px] flex-col gap-3 px-5 py-6 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10">


                    <!-- IDENTITÉ -->

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#071B36] text-lg font-extrabold text-[#F5B800] shadow-sm">
                            I
                        </div>

                        <div>
                            <p class="text-sm font-extrabold tracking-wide text-[#071B36]">
                                IMMO-SN
                            </p>

                            <p class="text-xs text-[#71869A]">
                                Votre plateforme immobilière au Sénégal
                            </p>
                        </div>

                    </div>


                    <!-- INFORMATIONS -->

                    <div class="text-xs leading-6 text-[#71869A] md:text-right">

                        <p>
                            Espace membre — Votre gestion immobilière
                        </p>

                        <p>
                            &copy; <?= date('Y') ?> IMMO-SN.
                            Tous droits réservés.
                        </p>

                    </div>

                </div>

            </footer>

        </main>

    </div>


    <!-- =====================================================
         JAVASCRIPT SIDEBAR MEMBRE
    ====================================================== -->

    <script>
    (function () {
        const sidebar = document.getElementById('memberSidebar');
        const openButton = document.getElementById('memberSidebarOpen');
        const closeButton = document.getElementById('memberSidebarClose');
        const overlay = document.getElementById('memberSidebarOverlay');

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


<?php elseif ($isAgencyArea): ?>


    <!-- =====================================================
         ESPACE AGENCE
    ====================================================== -->

    <main class="min-h-screen w-full page-transition">
        <?= $content ?>
    </main>


<?php else: ?>


    <!-- =====================================================
         SITE PUBLIC
    ====================================================== -->


    <!-- NAVBAR -->

    <?php
    require BASE_PATH . '/app/Views/components/navbar.php';
    ?>


    <!-- CONTENU PRINCIPAL -->

    <main class="flex-1 page-transition">
        <?= $content ?>
    </main>


    <!-- FOOTER PUBLIC -->

    <?php
    require BASE_PATH . '/app/Views/components/footer.php';
    ?>


<?php endif; ?>


</body>

</html>
