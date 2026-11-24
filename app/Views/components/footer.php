<?php
/**
- Footer public d'IMMO-SN
- Emplacement : app/Views/components/footer.php
 */
?>

<footer class="mt-0 overflow-hidden bg-[#061830] text-white">

    <!-- Ligne décorative dorée -->
    <div class="h-1 w-full bg-gradient-to-r from-[#8A6500] via-[#F5C542] to-[#8A6500]"></div>

    <!-- Présentation compacte des avantages -->
    <div class="border-b border-white/10">
        <div class="mx-auto max-w-7xl px-5 py-6 sm:px-8 lg:px-10">

            <!-- En-tête condensé -->
            <div class="mb-5 flex flex-col items-center justify-between gap-2 text-center sm:flex-row sm:text-left">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-[#F5C542]/30 bg-[#F5C542]/10 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-[#FFD45C]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#F5C542]"></span>
                        Pourquoi IMMO-SN ?
                    </span>
                    <h2 class="text-sm font-bold text-white sm:text-base">
                        Votre immobilier, en toute simplicité.
                    </h2>
                </div>
                <p class="text-xs text-slate-400">
                    Recherche et gestion de vos annonces au Sénégal.
                </p>
            </div>

            <!-- 4 Avantages en ligne horizontale compacte -->
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Annonces immobilières -->
                <div class="group flex items-center gap-3 rounded-xl border border-white/10 bg-[#0B2242]/70 px-3.5 py-2.5 transition hover:border-[#F5C542]/50">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F5C542]/15 text-base">
                        🏠
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-xs font-bold text-white">
                            Annonces immobilières
                        </h3>
                        <p class="truncate text-[11px] text-slate-400">
                            Des biens adaptés à vos recherches.
                        </p>
                    </div>
                </div>

                <!-- Suivi des annonces -->
                <div class="group flex items-center gap-3 rounded-xl border border-white/10 bg-[#0B2242]/70 px-3.5 py-2.5 transition hover:border-[#F5C542]/50">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F5C542]/15 text-base">
                        🛡️
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-xs font-bold text-white">
                            Suivi des annonces
                        </h3>
                        <p class="truncate text-[11px] text-slate-400">
                            Statut en temps réel sur votre compte.
                        </p>
                    </div>
                </div>

                <!-- Espace personnel -->
                <div class="group flex items-center gap-3 rounded-xl border border-white/10 bg-[#0B2242]/70 px-3.5 py-2.5 transition hover:border-[#F5C542]/50">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F5C542]/15 text-base">
                        👤
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-xs font-bold text-white">
                            Espace personnel
                        </h3>
                        <p class="truncate text-[11px] text-slate-400">
                            Vos annonces et outils au même endroit.
                        </p>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="group flex items-center gap-3 rounded-xl border border-white/10 bg-[#0B2242]/70 px-3.5 py-2.5 transition hover:border-[#F5C542]/50">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F5C542]/15 text-base">
                        ⚡
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-xs font-bold text-white">
                            Navigation simple
                        </h3>
                        <p class="truncate text-[11px] text-slate-400">
                            Fluide sur ordinateur et mobile.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Barre inférieure ultra-compacte -->
    <div class="mx-auto max-w-7xl px-5 py-3.5 sm:px-8 lg:px-10">
        <div class="flex flex-col items-center justify-between gap-2 text-center sm:flex-row sm:text-left">
            <p class="text-[11px] text-slate-400">
                &copy; <?= date('Y') ?>
                <span class="font-bold text-white">IMMO-SN</span>.
                Tous droits réservés.
            </p>

            <p class="flex items-center gap-2 text-[11px] text-slate-400">
                <span class="h-1.5 w-1.5 rounded-full bg-[#F5C542]"></span>
                Votre plateforme immobilière au Sénégal.
            </p>
        </div>
    </div>

</footer>