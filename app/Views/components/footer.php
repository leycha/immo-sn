
<?php
/**
 * Footer public d'IMMO-SN
 * Emplacement : app/Views/components/footer.php
 */
?>

<footer class="mt-0 bg-[#242424] text-white">

    <!-- Présentation des avantages -->
    <div class="border-b border-white/10">
        <div class="mx-auto max-w-7xl px-5 py-6 sm:px-8 lg:px-10">

            <div class="mb-5 text-center">
                <span class="inline-flex items-center justify-center rounded-full bg-[#f7b500] px-5 py-2 text-sm font-bold text-[#30221b]">
                    Pourquoi choisir IMMO-SN ?
                </span>

                <p class="mx-auto mt-2 max-w-2xl text-xs leading-5 text-gray-400">
                    Une plateforme pensée pour faciliter votre recherche
                    et la gestion de vos annonces immobilières au Sénégal.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-x-5 gap-y-5 text-center md:grid-cols-4">

                <!-- Annonces immobilières -->
                <div class="flex flex-col items-center gap-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-2xl">
                        🏠
                    </div>

                    <h3 class="text-xs font-semibold sm:text-sm">
                        Annonces immobilières
                    </h3>

                    <p class="max-w-[220px] text-xs leading-4 text-gray-400">
                        Retrouvez des biens adaptés à vos recherches.
                    </p>
                </div>

                <!-- Suivi des annonces -->
                <div class="flex flex-col items-center gap-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-2xl">
                        🛡️
                    </div>

                    <h3 class="text-xs font-semibold sm:text-sm">
                        Suivi des annonces
                    </h3>

                    <p class="max-w-[220px] text-xs leading-4 text-gray-400">
                        Consultez le statut de vos annonces depuis votre compte.
                    </p>
                </div>

                <!-- Espace personnel -->
                <div class="flex flex-col items-center gap-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-2xl">
                        👤
                    </div>

                    <h3 class="text-xs font-semibold sm:text-sm">
                        Espace personnel
                    </h3>

                    <p class="max-w-[220px] text-xs leading-4 text-gray-400">
                        Retrouvez vos annonces et vos fonctionnalités au même endroit.
                    </p>
                </div>

                <!-- Navigation -->
                <div class="flex flex-col items-center gap-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-2xl">
                        ⚡
                    </div>

                    <h3 class="text-xs font-semibold sm:text-sm">
                        Navigation simple
                    </h3>

                    <p class="max-w-[220px] text-xs leading-4 text-gray-400">
                        Parcourez la plateforme sur ordinateur ou mobile.
                    </p>
                </div>

            </div>

        </div>
    </div>

    <!-- Barre inférieure -->
    <div class="mx-auto max-w-7xl px-5 py-3 sm:px-8 lg:px-10">

        <div class="flex flex-col items-center justify-between gap-1 text-center sm:flex-row sm:text-left">

            <p class="text-[11px] leading-4 text-gray-400">
                &copy; <?= date('Y') ?> IMMO-SN. Tous droits réservés.
            </p>

            <p class="text-[11px] leading-4 text-gray-500">
                Votre plateforme immobilière au Sénégal.
            </p>

        </div>

    </div>

</footer>
