
<?php require BASE_PATH . '/app/Views/components/icon.php'; ?>

<div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#F5F3ED] px-5 py-16">

    <!-- Décoration d'arrière-plan -->
    <div class="pointer-events-none absolute -left-32 -top-32 h-80 w-80 rounded-full bg-[#F5B800]/10 blur-3xl"></div>

    <div class="pointer-events-none absolute -bottom-32 -right-32 h-80 w-80 rounded-full bg-[#071B36]/5 blur-3xl"></div>

    <div class="relative mx-auto w-full max-w-lg">

        <!-- =====================================================
             CARTE ERREUR
        ====================================================== -->

        <div class="overflow-hidden rounded-[30px] border border-[#E8E2D4] bg-white px-7 py-12 text-center shadow-xl shadow-[#071B36]/5 sm:px-12">

            <!-- Bande décorative -->
            <div class="absolute left-1/2 top-0 h-1 w-32 -translate-x-1/2 rounded-b-full bg-[#F5B800]"></div>

            <!-- Icône -->
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-[28px] border border-[#F5B800]/20 bg-[#FFF4CC] text-[#8A6500] shadow-sm">

                <?= icon('qrcode', 'h-12 w-12') ?>

            </div>

            <!-- Badge -->
            <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-red-100 bg-red-50 px-4 py-2">

                <span class="h-2 w-2 rounded-full bg-red-500"></span>

                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-red-600">
                    QR Code indisponible
                </span>

            </div>

            <!-- Titre -->
            <h1 class="mt-5 text-2xl font-extrabold leading-tight text-[#071B36] sm:text-3xl">

                <?= htmlspecialchars(
                    $title ?? 'QR Code non reconnu',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </h1>

            <!-- Message -->
            <p class="mx-auto mt-4 max-w-sm text-sm leading-7 text-[#71869A]">

                <?= htmlspecialchars(
                    $message ?? 'Ce QR code n\'existe pas.',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </p>

            <!-- Séparateur -->
            <div class="my-8 border-t border-[#E8E2D4]"></div>

            <!-- Boutons -->
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">

                <a
                    href="/scanner"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-6 py-3.5 text-sm font-extrabold text-[#071B36] shadow-sm transition duration-200 hover:bg-[#D7A200] hover:shadow-md sm:w-auto"
                >

                    <?= icon('scan-line', 'h-4 w-4') ?>

                    Réessayer

                </a>

                <a
                    href="/biens"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#E8E2D4] bg-white px-6 py-3.5 text-sm font-bold text-[#071B36] transition duration-200 hover:border-[#D7A200] hover:bg-[#FFFCF3] sm:w-auto"
                >

                    <?= icon('house', 'h-4 w-4') ?>

                    Voir les biens

                </a>

            </div>

            <!-- Message sécurité -->
            <div class="mt-9 flex items-center justify-center gap-2 text-xs text-[#71869A]">

                <?= icon('shield-check', 'h-4 w-4 text-[#B88700]') ?>

                <span>Votre expérience immobilière avec IMMO-SN</span>

            </div>

        </div>

        <!-- Pied de page -->
        <p class="mt-6 text-center text-xs text-[#71869A]">

            © <?= date('Y') ?> IMMO-SN — Tous droits réservés.

        </p>

    </div>

</div>
