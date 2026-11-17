
<?php
use Core\Session;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Mot de passe oublié — ImmoSN') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#faf7f2] flex items-center justify-center px-4">

    <main class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#30221b]">ImmoSN</h1>
            <h2 class="text-xl font-semibold text-[#5a402d] mt-5">
                Mot de passe oublié ?
            </h2>
            <p class="text-gray-500 mt-2 text-sm">
                Saisissez votre adresse e-mail pour recevoir un lien de réinitialisation.
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form action="/immo-sn/mot-de-passe-oublie" method="POST" class="space-y-5">

            <input
                type="hidden"
                name="_csrf_token"
                value="<?= htmlspecialchars(Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>"
            >

            <div>
                <label for="email" class="block text-sm font-medium text-[#30221b] mb-2">
                    Adresse e-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    placeholder="exemple@email.com"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#a98260]"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-[#30221b] hover:bg-[#5a402d] text-white font-semibold py-3 rounded-xl transition"
            >
                Envoyer le lien
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
