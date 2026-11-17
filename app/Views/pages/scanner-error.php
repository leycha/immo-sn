<?php require BASE_PATH . '/app/Views/components/icon.php'; ?>

<div class="bg-cream min-h-screen flex items-center justify-center py-20">
  <div class="max-w-md mx-auto text-center px-6">
    <div class="text-red-400 mb-4 flex justify-center">
      <?= icon('qrcode', 'w-20 h-20') ?>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-3">
      <?= htmlspecialchars($title ?? 'QR Code non reconnu') ?>
    </h1>
    <p class="text-gray-500 mb-8">
      <?= htmlspecialchars($message ?? 'Ce QR code n\'existe pas.') ?>
    </p>
    <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:justify-center">
      <a href="/scanner" class="btn-primary w-full sm:w-auto">Réessayer</a>
      <a href="/biens" class="btn-outline w-full sm:w-auto">Voir les biens</a>
    </div>
  </div>
</div>