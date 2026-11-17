
<?php
use Core\Session;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Nouveau mot de passe — ImmoSN') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#faf7f2] flex items-center justify-center px-4">

    <main class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#30221b]">ImmoSN</h1>
            <h2 class="text-xl font-semibold text-[#5a402d] mt-5">
                Créer un nouveau mot de passe
            </h2>
            <p class="text-gray-500 mt-2 text-sm">
                Choisissez un nouveau mot de passe pour accéder à votre compte.
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/immo-sn/reinitialiser-mot-de-passe" method="POST" class="space-y-5">

            <input
                type="hidden"
                name="_csrf_token"
                value="<?= htmlspecialchars(Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>"
            >

            <input
                type="hidden"
                name="token"
                value="<?= htmlspecialchars($token ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >

            <div>
                <label for="password" class="block text-sm font-medium text-[#30221b] mb-2">
                    Nouveau mot de passe
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required
                    autocomplete="new-password"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#a98260]"
                >
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#30221b] mb-2">
                    Confirmer le mot de passe
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    minlength="8"
                    required
                    autocomplete="new-password"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#a98260]"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-[#30221b] hover:bg-[#5a402d] text-white font-semibold py-3 rounded-xl transition"
            >
                Enregistrer le nouveau mot de passe
            </button>

        </form>

        <div class="text-center mt-6">
            <a href="/immo-sn/login" class="text-sm text-[#a98260] hover:underline">
                Retour à la connexion
            </a>
        </div>

    </main>

</body>
</html>
