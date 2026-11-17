
<?php

use Core\Session;

$error = $error ?? null;
$success = $success ?? null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($title ?? 'Connexion — ImmoSn', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="min-h-screen bg-[#faf7f2] text-[#30221b]">

<div class="grid min-h-screen min-w-0 lg:grid-cols-2">

    <!-- =====================================================
         PARTIE GAUCHE
    ====================================================== -->

    <div class="relative hidden min-w-0 overflow-hidden bg-[#30221b] text-white lg:flex">

        <div
            class="absolute inset-0 opacity-20"
            style="
                background:
                    radial-gradient(
                        circle at 20% 20%,
                        #f7b500 0,
                        transparent 35%
                    ),
                    radial-gradient(
                        circle at 80% 80%,
                        #a98260 0,
                        transparent 35%
                    );
            "
        ></div>

        <div class="relative z-10 flex w-full min-w-0 flex-col justify-between p-8 xl:p-12">

            <div>

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl
                               bg-[#f7b500] text-xl font-black text-[#30221b]"
                    >
                        I
                    </div>

                    <div class="min-w-0">

                        <div class="text-2xl font-black">
                            Immo<span class="text-[#f7b500]">SN</span>
                        </div>

                        <div class="text-xs text-white/60">
                            Immobilier au Sénégal
                        </div>

                    </div>

                </div>

            </div>

            <div class="max-w-xl min-w-0">

                <p class="mb-5 text-sm font-bold uppercase tracking-[0.25em] text-[#f7b500]">
                    Bienvenue
                </p>

                <h1 class="mb-6 break-words text-4xl font-black leading-tight xl:text-5xl">
                    Trouvez votre prochain bien immobilier.
                </h1>

                <p class="text-base leading-relaxed text-white/70 xl:text-lg">
                    Connectez-vous à votre espace ImmoSN pour gérer
                    vos annonces, vos favoris, vos messages et vos
                    biens immobiliers.
                </p>

            </div>

            <div class="text-sm text-white/40">
                © <?= date('Y') ?> ImmoSN
            </div>

        </div>

    </div>


    <!-- =====================================================
         PARTIE DROITE
    ====================================================== -->

    <div class="flex min-w-0 items-center justify-center px-4 py-8 sm:px-10 sm:py-10">

        <div class="w-full min-w-0 max-w-md">

            <!-- Logo mobile -->

            <div class="mb-8 text-center sm:mb-10 lg:hidden">

                <div class="inline-flex max-w-full items-center gap-3">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl
                               bg-[#30221b] text-xl font-black text-[#f7b500]"
                    >
                        I
                    </div>

                    <div class="text-2xl font-black text-[#30221b]">
                        Immo<span class="text-[#f7b500]">SN</span>
                    </div>

                </div>

            </div>


            <!-- Titre -->

            <div class="mb-8 min-w-0">

                <p class="mb-2 text-sm font-bold text-[#a98260]">
                    ESPACE MEMBRE
                </p>

                <h2 class="break-words text-3xl font-black text-[#30221b] sm:text-4xl">
                    Connexion
                </h2>

                <p class="mt-3 break-words text-[#5a402d]/70">
                    Connectez-vous pour accéder à votre espace.
                </p>

            </div>


            <!-- Erreur -->

            <?php if (!empty($error)): ?>

                <div
                    class="mb-6 min-w-0 rounded-2xl border border-red-200
                           bg-red-50 px-4 py-4 text-sm text-red-700"
                >

                    <div class="flex min-w-0 items-start gap-3">

                        <span class="shrink-0 font-bold">
                            !
                        </span>

                        <span class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </span>

                    </div>

                </div>

            <?php endif; ?>


            <!-- Message de succès -->

            <?php if (!empty($success)): ?>

                <div
                    class="mb-6 min-w-0 rounded-2xl border border-green-200
                           bg-green-50 px-4 py-4 text-sm text-green-700"
                >

                    <div class="flex min-w-0 items-start gap-3">

                        <span class="shrink-0 font-bold">
                            ✓
                        </span>

                        <span class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                        </span>

                    </div>

                </div>

            <?php endif; ?>


            <!-- Formulaire -->

            <form
                method="POST"
                action="/immo-sn/login"
                class="min-w-0 space-y-5"
            >

                <!-- Email -->

                <div class="min-w-0">

                    <label
                        for="email"
                        class="mb-2 block text-sm font-bold text-[#30221b]"
                    >
                        Adresse email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="exemple@email.com"
                        autocomplete="email"
                        required
                        class="w-full min-w-0 rounded-2xl border border-[#eadfd5]
                               bg-white px-4 py-3.5 outline-none transition
                               focus:border-[#f7b500] focus:ring-4
                               focus:ring-[#f7b500]/10"
                    >

                </div>


                <!-- Mot de passe -->

                <div class="min-w-0">

                    <div class="mb-2 flex items-center justify-between">

                        <label
                            for="password"
                            class="text-sm font-bold text-[#30221b]"
                        >
                            Mot de passe
                        </label>

                    </div>

                    <div class="relative min-w-0">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Votre mot de passe"
                            autocomplete="current-password"
                            required
                            class="w-full min-w-0 rounded-2xl border border-[#eadfd5]
                                   bg-white px-4 py-3.5 pr-14 outline-none
                                   transition focus:border-[#f7b500]
                                   focus:ring-4 focus:ring-[#f7b500]/10"
                        >

                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-2 top-1/2 flex h-11 w-11
                                   -translate-y-1/2 items-center justify-center
                                   rounded-xl text-[#a98260]
                                   transition hover:text-[#30221b]"
                            aria-label="Afficher le mot de passe"
                            aria-pressed="false"
                        >
                            👁
                        </button>

                    </div>

                </div>


                <!-- Bouton -->

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-[#30221b] px-5 py-4
                           font-bold text-white transition
                           hover:bg-[#5a402d] hover:-translate-y-0.5
                           active:translate-y-0"
                >
                    Se connecter
                </button>

            </form>


            <!-- Inscription -->

            <div class="mt-6 min-w-0 text-center">

                <p class="break-words text-sm text-[#5a402d]/80">
                    Vous n'avez pas encore de compte ?
                </p>

                <a
                    href="/immo-sn/inscription"
                    class="mt-2 inline-block max-w-full break-words font-bold
                           text-[#a98260] transition
                           hover:text-[#30221b] hover:underline"
                >
                    Créer un compte →
                </a>

            </div>


            <!-- Mot de passe oublié -->

            <div class="mt-4 text-center">

                <a
                    href="/immo-sn/mot-de-passe-oublie"
                    class="break-words text-sm font-semibold text-[#a98260]
                           transition hover:text-[#30221b] hover:underline"
                >
                    Mot de passe oublié ?
                </a>

            </div>


            <!-- Retour -->

            <div class="mt-8 text-center">

                <a
                    href="/immo-sn/"
                    class="break-words text-sm font-semibold text-[#a98260]
                           hover:text-[#30221b]"
                >
                    ← Retour à l'accueil
                </a>

            </div>

        </div>

    </div>

</div>


<script>

const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

if (passwordInput && togglePassword) {

    togglePassword.addEventListener('click', function () {

        const isPassword =
            passwordInput.type === 'password';

        passwordInput.type =
            isPassword ? 'text' : 'password';

        this.textContent =
            isPassword ? '🙈' : '👁';

        this.setAttribute(
            'aria-label',
            isPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
        );

        this.setAttribute(
            'aria-pressed',
            isPassword ? 'true' : 'false'
        );

    });

}

</script>

</body>

</html>
