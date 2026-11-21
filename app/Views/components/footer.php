
<?php
/**
 * Footer public d'IMMO-SN
 * Emplacement : app/Views/components/footer.php
 */
?>

<footer class="mt-0 overflow-hidden bg-[#071B36] text-white">

    <!-- Ligne décorative dorée -->
    <div class="h-1 w-full bg-gradient-to-r from-[#8A6500] via-[#F5B800] to-[#8A6500]"></div>

    <!-- Présentation des avantages -->
    <div class="border-b border-white/10">
        <div class="mx-auto max-w-7xl px-5 py-10 sm:px-8 lg:px-10">

            <!-- Introduction -->
            <div class="mb-9 text-center">

                <span class="inline-flex items-center gap-2 rounded-full border border-[#F5B800]/30 bg-[#F5B800]/10 px-4 py-2 text-xs font-bold tracking-wide text-[#FFD45C]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                    POURQUOI CHOISIR IMMO-SN ?
                </span>

                <h2 class="mt-4 text-xl font-bold tracking-tight text-white sm:text-2xl">
                    Votre immobilier, en toute simplicité.
                </h2>

                <p class="mx-auto mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                    Une plateforme pensée pour faciliter votre recherche
                    et la gestion de vos annonces immobilières au Sénégal.
                </p>

            </div>

            <!-- Avantages -->
            <div class="grid grid-cols-2 gap-x-5 gap-y-8 md:grid-cols-4">

                <!-- Annonces immobilières -->
                <div class="group flex flex-col items-center text-center">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#F5B800]/20 bg-[#102B4C] text-2xl shadow-lg shadow-black/10 transition duration-300 group-hover:-translate-y-1 group-hover:border-[#F5B800]/60 group-hover:bg-[#17395F]">
                        🏠
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-white">
                        Annonces immobilières
                    </h3>

                    <p class="mt-2 max-w-[220px] text-xs leading-5 text-slate-400">
                        Retrouvez des biens adaptés à vos recherches.
                    </p>

                </div>

                <!-- Suivi des annonces -->
                <div class="group flex flex-col items-center text-center">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#F5B800]/20 bg-[#102B4C] text-2xl shadow-lg shadow-black/10 transition duration-300 group-hover:-translate-y-1 group-hover:border-[#F5B800]/60 group-hover:bg-[#17395F]">
                        🛡️
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-white">
                        Suivi des annonces
                    </h3>

                    <p class="mt-2 max-w-[220px] text-xs leading-5 text-slate-400">
                        Consultez le statut de vos annonces depuis votre compte.
                    </p>

                </div>

                <!-- Espace personnel -->
                <div class="group flex flex-col items-center text-center">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#F5B800]/20 bg-[#102B4C] text-2xl shadow-lg shadow-black/10 transition duration-300 group-hover:-translate-y-1 group-hover:border-[#F5B800]/60 group-hover:bg-[#17395F]">
                        👤
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-white">
                        Espace personnel
                    </h3>

                    <p class="mt-2 max-w-[220px] text-xs leading-5 text-slate-400">
                        Retrouvez vos annonces et vos fonctionnalités au même endroit.
                    </p>

                </div>

                <!-- Navigation -->
                <div class="group flex flex-col items-center text-center">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#F5B800]/20 bg-[#102B4C] text-2xl shadow-lg shadow-black/10 transition duration-300 group-hover:-translate-y-1 group-hover:border-[#F5B800]/60 group-hover:bg-[#17395F]">
                        ⚡
                    </div>

                    <h3 class="mt-4 text-sm font-bold text-white">
                        Navigation simple
                    </h3>

                    <p class="mt-2 max-w-[220px] text-xs leading-5 text-slate-400">
                        Parcourez la plateforme sur ordinateur ou mobile.
                    </p>

                </div>

            </div>

        </div>
    </div>

    <!-- Barre inférieure -->
    <div class="mx-auto max-w-7xl px-5 py-5 sm:px-8 lg:px-10">

        <div class="flex flex-col items-center justify-between gap-3 text-center sm:flex-row sm:text-left">

            <p class="text-xs leading-5 text-slate-400">
                &copy; <?= date('Y') ?>
                <span class="font-bold text-white">IMMO-SN</span>.
                Tous droits réservés.
            </p>

            <p class="flex items-center gap-2 text-xs leading-5 text-slate-400">
                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                Votre plateforme immobilière au Sénégal.
            </p>

        </div>

    </div>

</footer>
