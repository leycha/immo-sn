
<?php require BASE_PATH . '/app/Views/components/icon.php'; ?>

<div class="min-h-screen bg-[#F5F3ED] px-4 py-10 sm:px-6 sm:py-14">

    <div class="mx-auto max-w-xl">

        <!-- =====================================================
             EN-TÊTE
        ====================================================== -->
        <div class="mb-8 text-center">

            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-[#E8E2D4] bg-[#FFF4CC] text-[#8A6500] shadow-sm">
                <?= icon('scan-line', 'h-8 w-8') ?>
            </div>

            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#E8E2D4] bg-white px-3 py-1.5">
                <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#8A6500]">
                    Scanner immobilier
                </span>
            </div>

            <h1 class="text-2xl font-extrabold tracking-tight text-[#071B36] sm:text-3xl">
                Scanner un QR Code
            </h1>

            <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#71869A]">
                Scannez le QR Code d'un bien pour accéder rapidement à ses informations et découvrir tous ses détails.
            </p>

        </div>


        <!-- =====================================================
             ZONE CAMÉRA
        ====================================================== -->
        <div class="mb-6 overflow-hidden rounded-[28px] border border-[#E8E2D4] bg-white p-4 shadow-[0_12px_35px_rgba(7,27,54,0.06)] sm:p-5">

            <div class="mb-4 flex items-center justify-between gap-3">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FFF4CC] text-[#8A6500]">
                        <?= icon('camera', 'h-5 w-5') ?>
                    </div>

                    <div>
                        <h2 class="text-sm font-bold text-[#071B36]">
                            Scanner avec la caméra
                        </h2>

                        <p class="mt-0.5 text-xs text-[#71869A]">
                            Placez le QR Code dans le cadre
                        </p>
                    </div>

                </div>

                <span class="flex shrink-0 items-center gap-1.5 rounded-full border border-green-100 bg-green-50 px-2.5 py-1.5 text-[10px] font-semibold text-green-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                    Direct
                </span>

            </div>

            <!-- Zone de scan -->
            <div
                id="qr-reader"
                class="aspect-square overflow-hidden rounded-2xl border border-[#1C3552] bg-[#071B36]"
            ></div>

            <!-- Conseils caméra -->
            <div class="mt-4 flex items-start gap-3 rounded-xl border border-[#E8E2D4] bg-[#FAFAF8] p-3">

                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#FFF4CC] text-[#8A6500]">
                    <?= icon('info', 'h-4 w-4') ?>
                </div>

                <p class="pt-1 text-xs leading-5 text-[#71869A]">
                    Autorisez l'accès à votre caméra lorsque votre navigateur vous le demande. Pour un meilleur résultat, placez le QR Code dans un endroit bien éclairé.
                </p>

            </div>

        </div>


        <!-- =====================================================
             MESSAGE D'ERREUR
        ====================================================== -->
        <div
            id="scanner-error"
            class="mb-5 hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"
        >
            <div class="flex items-start gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100">
                    <?= icon('alert-circle', 'h-5 w-5 text-red-600') ?>
                </div>

                <div>
                    <p class="font-bold">
                        Accès à la caméra impossible
                    </p>

                    <p class="mt-1 leading-5 text-red-600">
                        Impossible d'accéder à la caméra.
                        Utilisez la saisie manuelle ci-dessous.
                    </p>
                </div>

            </div>
        </div>


        <!-- =====================================================
             SÉPARATEUR
        ====================================================== -->
        <div class="mb-6 flex items-center gap-4">

            <div class="h-px flex-1 bg-[#E8E2D4]"></div>

            <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#71869A]">
                Ou autrement
            </span>

            <div class="h-px flex-1 bg-[#E8E2D4]"></div>

        </div>


        <!-- =====================================================
             SAISIE MANUELLE
        ====================================================== -->
        <div class="rounded-[28px] border border-[#E8E2D4] bg-white p-5 shadow-[0_12px_35px_rgba(7,27,54,0.06)] sm:p-6">

            <div class="mb-5 flex items-center gap-3">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">
                    <?= icon('keyboard', 'h-5 w-5') ?>
                </div>

                <div>
                    <h2 class="text-sm font-bold text-[#071B36]">
                        Saisie manuelle
                    </h2>

                    <p class="mt-1 text-xs text-[#71869A]">
                        Retrouvez un bien avec son code unique
                    </p>
                </div>

            </div>


            <form
                method="GET"
                action="/scan"
                id="manualForm"
                class="flex flex-col gap-4"
            >

                <div>

                    <label
                        for="manualToken"
                        class="mb-2 block text-xs font-bold text-[#071B36]"
                    >
                        Code du bien immobilier
                    </label>

                    <div class="relative">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#8A6500]">
                            <?= icon('qr-code', 'h-5 w-5') ?>
                        </div>

                        <input
                            type="text"
                            id="manualToken"
                            name="code"
                            placeholder="Ex. : IMMO-XXXXXX"
                            required
                            class="w-full rounded-xl border border-[#E8E2D4] bg-[#FAFAF8] py-3.5 pl-12 pr-4 text-sm font-medium text-[#071B36] outline-none transition placeholder:text-[#A0ADBA] focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/15"
                        />

                    </div>

                    <p class="mt-2 text-[11px] leading-5 text-[#71869A]">
                        Saisissez le code figurant sur le QR Code ou communiqué par le propriétaire.
                    </p>

                </div>


                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-6 py-3.5 text-sm font-extrabold text-[#071B36] shadow-sm transition hover:bg-[#D7A200] hover:shadow-md active:scale-[0.98]"
                >
                    <?= icon('search', 'h-4 w-4') ?>
                    Rechercher le bien
                </button>

            </form>

        </div>


        <!-- =====================================================
             INFORMATIONS ET SÉCURITÉ
        ====================================================== -->
        <div class="mt-6 flex items-start gap-3 rounded-2xl border border-[#E8E2D4] bg-white/70 p-4">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FFF4CC] text-[#8A6500]">
                <?= icon('shield-check', 'h-5 w-5') ?>
            </div>

            <div>
                <p class="text-xs font-bold text-[#071B36]">
                    Accès rapide aux annonces IMMO-SN
                </p>

                <p class="mt-1 text-xs leading-5 text-[#71869A]">
                    Le QR Code vous permet de retrouver rapidement les détails d'un bien publié sur IMMO-SN.
                </p>
            </div>

        </div>


        <!-- =====================================================
             RETOUR
        ====================================================== -->
        <div class="mt-8 text-center">

            <a
                href="/biens"
                class="inline-flex items-center gap-2 text-sm font-semibold text-[#71869A] transition hover:text-[#8A6500]"
            >
                <?= icon('arrow-left', 'h-4 w-4') ?>
                Retour aux annonces immobilières
            </a>

        </div>

    </div>

</div>


<!-- Bibliothèque html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<!-- JavaScript du scanner -->
<script src="/assets/js/scanner.js"></script>
