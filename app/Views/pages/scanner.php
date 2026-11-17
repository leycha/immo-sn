
<?php require BASE_PATH . '/app/Views/components/icon.php'; ?>

<div class="bg-cream min-h-screen py-10">
    <div class="max-w-xl mx-auto px-6">

        <h1 class="text-2xl font-bold text-center text-gray-900 mb-2">
            Scanner un QR Code
        </h1>

        <p class="text-center text-gray-500 text-sm mb-8">
            Pointez votre caméra vers le QR Code du bien
        </p>

        <!-- ZONE CAMÉRA -->
        <div class="bg-white rounded-3xl shadow-lg p-4 mb-6">
            <div
                id="qr-reader"
                class="rounded-2xl overflow-hidden bg-gray-900 aspect-square"
            ></div>
        </div>

        <!-- MESSAGE D'ERREUR -->
        <div
            id="scanner-error"
            class="hidden bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm text-center mb-4"
        >
            Impossible d'accéder à la caméra.
            Utilisez la saisie manuelle ci-dessous.
        </div>

        <!-- SAISIE MANUELLE -->
        <div class="bg-white rounded-2xl shadow-sm p-5">

            <p class="text-center text-gray-500 text-sm mb-4">
                Ou saisissez le code du bien manuellement
            </p>

            <form
                method="GET"
                action="/scan"
                id="manualForm"
                class="flex flex-col gap-3 sm:flex-row"
            >

                <input
                    type="text"
                    id="manualToken"
                    name="code"
                    placeholder="Code du bien"
                    required
                    class="flex-1 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm"
                />

                <button
                    type="submit"
                    class="btn-primary w-full text-sm px-6 sm:w-auto"
                >
                    Valider
                </button>

            </form>

        </div>

    </div>
</div>

<!-- Bibliothèque html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<!-- JavaScript du scanner -->
<script src="/assets/js/scanner.js"></script>
