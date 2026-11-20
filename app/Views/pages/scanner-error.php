<?php require BASE_PATH . '/app/Views/components/icon.php'; ?>

<div class="flex min-h-screen items-center justify-center bg-[#F5F3ED] px-5 py-16">

    <div class="mx-auto w-full max-w-lg">

        <!-- =====================================================
             CARTE ERREUR
        ====================================================== -->
        <div class="rounded-[30px] border border-[#E6E8DF] bg-white px-7 py-12 text-center shadow-sm sm:px-12">

            <!-- Icône -->
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-[28px] bg-red-50 text-red-500">
                <?= icon('qrcode', 'h-12 w-12') ?>
            </div>

            <!-- Badge -->
            <div class="mt-6 inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2">
                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-red-600">
                    QR Code indisponible
        񟄀       </span>
            </div>

            <!-- Titre -->
            <h1 class="mt-5 text-2xl font-extrabold leading-tight text-[#101D33] sm:text-3xl">
                <?= htmlspecialchars($title ?? 'QR Code non reconnu', ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <!-- Message -->
            <p class="mx-auto mt-4 max-w-sm text-sm leading-7 text-[#71869A]">
                <?= htmlspecialchars($message ?? 'Ce QR code n\'existe pas.', ENT_QUOTES, 'UTF-8') ?>
            </p>

            <!-- Séparateur -->
            <div class="my-8 border-t border-[#E6E8DF]"></div>

            <!-- Boutons -->
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">

                <a
                    href="/scanner"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#6D852B] sm:w-auto"
                >
                    <?= icon('scan-line', 'h-4 w-4') ?>
                    Réessayer
                </a>

                <a
                    href="/biens"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#DCE2D2] bg-white px-6 py-3.5 text-sm font-bold text-[#101D33] transition hover:border-[#7E9833] hover:bg-[#F5F3ED] sm:w-auto"
                >
                    <?= icon('house', 'h-4 w-4') ?>
                    Voir les biens
                </a>

            </div>

            <!-- Message sécurité -->
            <div class="mt-9 flex items-center justify-center gap-2 text-xs text-[#71869A]">
                <?= icon('shield-check', 'h-4 w-4 text-[#7E9833]') ?>
                <span>Votre expérience immobilière avec IMMO-SN</span>
            </div>

        </div>

        <!-- Pied de page -->
        <p class="mt-6 text-center text-xs text-[#71869A]">
            © <?= date('Y') ?> IMMO-SN — Tous droits réservés.
        </p>

    </div>

</div>