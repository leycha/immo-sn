document.addEventListener('DOMContentLoaded', function () {
    const qrReader = document.getElementById('qr-reader');
    const errorBox = document.getElementById('scanner-error');
    const manualForm = document.getElementById('manualForm');
    const manualToken = document.getElementById('manualToken');

    if (!qrReader) return;

    // Configuration du scanner
    const html5QrCode = new Html5Qrcode("qr-reader");

    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        rememberLastUsedCamera: true,
    };

    // Callback succès
    function onScanSuccess(decodedText) {
        // Arrête la caméra
        html5QrCode.stop().then(() => {
            // Extrait le token de l'URL scannée
            // Formats supportés :
            //   http://localhost:8000/scan/TOKEN
            //   https://immosn.com/scan/TOKEN
            //   TOKEN (si le QR encode juste le token)
            let token = decodedText.trim();

            // Si c'est une URL complète, on extrait la dernière partie
            if (token.includes('/')) {
                const parts = token.split('/');
                token = parts[parts.length - 1];
            }

            // Redirige
            window.location.href = '/scan/' + token;
        });
    }

    // Callback erreur (ignoré — c'est normal quand rien n'est scanné)
    function onScanError() { /* silent */ }

    // Démarre la caméra
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        onScanError
    ).catch(err => {
        console.error("Erreur caméra :", err);
        errorBox.classList.remove('hidden');
        qrReader.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full text-gray-500 text-sm p-6 text-center">
                <svg class="w-16 h-16 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Caméra désactivée dans cet aperçu — activation lors du test réel
            </div>
        `;
    });

    // Fallback : soumission manuelle
    manualForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const token = manualToken.value.trim();
        if (token) {
            window.location.href = '/scan/' + token;
        }
    });
});