
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

    <meta name="theme-color" content="#071B36">

    <title>
        <?= htmlspecialchars($title ?? 'Connexion — IMMO-SN', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        ::selection {
            background: #F5B800;
            color: #071B36;
        }

        :focus-visible {
            outline: 3px solid rgba(245, 184, 0, 0.35);
            outline-offset: 3px;
        }

        .gold-gradient {
            background: linear-gradient(135deg, #F5B800 0%, #D7A200 100%);
        }

        .login-shadow {
            box-shadow: 0 25px 80px rgba(7, 27, 54, 0.10);
        }

        .hero-pattern {
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 45px 45px;
        }
    </style>

</head>

<body class="min-h-screen bg-[#F5F3ED] text-[#071B36]">

<div class="grid min-h-screen min-w-0 lg:grid-cols-2">

    <!-- =====================================================
         PARTIE GAUCHE : PRÉSENTATION
    ====================================================== -->

    <section class="relative hidden min-w-0 overflow-hidden bg-[#071B36] text-white lg:flex">

        <!-- Décoration -->
        <div class="absolute inset-0 hero-pattern"></div>

        <div
            class="absolute inset-0"
            style="
                background:
                    radial-gradient(
                        circle at 12% 15%,
                        rgba(245, 184, 0, 0.16),
                        transparent 38%
                    ),
                    radial-gradient(
                        circle at 90% 85%,
                        rgba(113, 134, 154, 0.17),
                        transparent 42%
                    );
            "
        ></div>

        <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full border border-[#F5B800]/10"></div>
        <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full border border-white/5"></div>
        <div class="absolute -bottom-40 -left-40 h-[500px] w-[500px] rounded-full border border-white/5"></div>

        <div class="absolute right-20 top-1/3 h-2 w-2 rounded-full bg-[#F5B800]/50"></div>
        <div class="absolute right-36 top-1/2 h-1.5 w-1.5 rounded-full bg-white/20"></div>

        <!-- Contenu -->
        <div class="relative z-10 flex w-full min-w-0 flex-col justify-between p-8 xl:p-14">

            <!-- Logo -->
            <div>

                <a href="/immo-sn/" class="inline-flex items-center gap-3">

                    <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-[#F5B800]/30 bg-white/10 text-xl font-black text-[#F5B800] shadow-lg">

                        I

                        <span class="absolute -bottom-1 -right-1 h-3.5 w-3.5 rounded-full border-[3px] border-[#071B36] bg-[#F5B800]"></span>

                    </div>

                    <div>

                        <div class="text-2xl font-black tracking-tight">
                            Immo<span class="text-[#F5B800]">SN</span>
                        </div>

                        <div class="text-xs tracking-wide text-white/50">
                            Immobilier au Sénégal
                        </div>

                    </div>

                </a>

            </div>

            <!-- Texte central -->
            <div class="max-w-xl min-w-0 py-12">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#F5B800]/20 bg-[#F5B800]/10 px-4 py-2">

                    <span class="h-2 w-2 rounded-full bg-[#F5B800] shadow-[0_0_10px_rgba(245,184,0,0.5)]"></span>

                    <span class="text-xs font-bold uppercase tracking-[0.18em] text-[#FFD45C]">
                        Bienvenue chez ImmoSN
                    </span>

                </div>

                <h1 class="mb-6 break-words text-4xl font-black leading-tight xl:text-6xl">

                    Votre projet immobilier,

                    <span class="text-[#F5B800]">
                        notre priorité.
                    </span>

                </h1>

                <p class="max-w-lg text-base leading-8 text-white/65 xl:text-lg">

                    Retrouvez votre espace personnel et profitez d'une
                    expérience immobilière simple pour gérer vos annonces,
                    vos favoris, vos messages et vos projets.

                </p>

                <!-- Séparateur -->
                <div class="my-10 h-1 w-20 rounded-full gold-gradient"></div>

                <!-- Avantages -->
                <div class="space-y-6">

                    <!-- Espace immobilier -->
                    <div class="flex items-start gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-[#F5B800]/15 bg-white/5 text-[#F5B800]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="21"
                                height="21"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="m3 10 9-7 9 7"/>
                                <path d="M5 9v11h14V9"/>
                                <path d="M9 20v-6h6v6"/>
                            </svg>

                        </div>

                        <div>

                            <p class="font-bold text-white">
                                Votre espace immobilier
                            </p>

                            <p class="mt-1 text-sm leading-5 text-white/45">
                                Retrouvez tous vos biens et vos activités.
                            </p>

                        </div>

                    </div>

                    <!-- Expérience simplifiée -->
                    <div class="flex items-start gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-[#F5B800]/15 bg-white/5 text-[#F5B800]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="21"
                                height="21"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <path d="M7 8h10"/>
                                <path d="M7 12h6"/>
                                <path d="M7 16h4"/>
                            </svg>

                        </div>

                        <div>

                            <p class="font-bold text-white">
                                Une expérience simplifiée
                            </p>

                            <p class="mt-1 text-sm leading-5 text-white/45">
                                Gérez vos annonces en toute simplicité.
                            </p>

                        </div>

                    </div>

                    <!-- Sécurité -->
                    <div class="flex items-start gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-[#F5B800]/15 bg-white/5 text-[#F5B800]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="21"
                                height="21"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>

                        </div>

                        <div>

                            <p class="font-bold text-white">
                                Un espace sécurisé
                            </p>

                            <p class="mt-1 text-sm leading-5 text-white/45">
                                Accédez à votre compte en toute confiance.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-6">

                <p class="text-xs text-white/35">
                    © <?= date('Y') ?>
                    <span class="font-bold text-white/60">ImmoSN</span>
                    — Immobilier au Sénégal
                </p>

                <div class="flex items-center gap-2 text-xs text-white/45">

                    <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>

                    <span>Votre partenaire immobilier</span>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         PARTIE DROITE : FORMULAIRE DE CONNEXION
    ====================================================== -->

    <section class="flex min-w-0 items-center justify-center px-4 py-10 sm:px-8 sm:py-12">

        <div class="w-full min-w-0 max-w-md">

            <!-- Logo mobile -->
            <div class="mb-10 text-center lg:hidden">

                <a href="/immo-sn/" class="inline-flex items-center gap-3">

                    <div class="relative flex h-12 w-12 items-center justify-center rounded-2xl border border-[#F5B800]/30 bg-[#071B36] text-xl font-black text-[#F5B800] shadow-lg">

                        I

                        <span class="absolute -bottom-1 -right-1 h-3.5 w-3.5 rounded-full border-[3px] border-[#F5F3ED] bg-[#F5B800]"></span>

                    </div>

                    <div class="text-2xl font-black text-[#071B36]">
                        Immo<span class="text-[#B88700]">SN</span>
                    </div>

                </a>

                <p class="mt-2 text-xs text-[#71869A]">
                    Immobilier au Sénégal
                </p>

            </div>

            <!-- En-tête -->
            <div class="mb-8 min-w-0">

                <div class="mb-4 flex items-center gap-2">

                    <span class="h-1.5 w-8 rounded-full gold-gradient"></span>

                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#B88700]">
                        Espace membre
                    </span>

                </div>

                <h2 class="break-words text-3xl font-black tracking-tight text-[#071B36] sm:text-4xl">
                    Heureux de vous revoir !
                </h2>

                <p class="mt-3 break-words leading-7 text-[#71869A]">
                    Connectez-vous à votre compte pour continuer.
                </p>

            </div>

            <!-- Erreur -->
            <?php if (!empty($error)): ?>

                <div class="mb-6 min-w-0 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">

                    <div class="flex min-w-0 items-start gap-3">

                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-red-100 font-bold text-red-600">
                            !
                        </div>

                        <div class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    </div>

                </div>

            <?php endif; ?>

            <!-- Succès -->
            <?php if (!empty($success)): ?>

                <div class="mb-6 min-w-0 rounded-2xl border border-green-200 bg-green-50 px-4 py-4 text-sm text-green-700">

                    <div class="flex min-w-0 items-start gap-3">

                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-green-100 font-bold text-green-600">
                            ✓
                        </div>

                        <div class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    </div>

                </div>

            <?php endif; ?>

            <!-- Carte formulaire -->
            <div class="overflow-hidden rounded-[28px] border border-[#E8E2D4] bg-white p-5 login-shadow sm:p-7">

                <!-- Petit accent -->
                <div class="mb-6 flex items-center gap-2">

                    <span class="h-1 w-8 rounded-full gold-gradient"></span>

                    <span class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Accéder à mon compte
                    </span>

                </div>

                <form
                    method="POST"
                    action="/immo-sn/login"
                    class="min-w-0 space-y-6"
                >

                    <!-- Email -->
                    <div class="min-w-0">

                        <label
                            for="email"
                            class="mb-2.5 block text-sm font-bold text-[#071B36]"
                        >
                            Adresse e-mail
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#71869A]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="19"
                                    height="19"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="m3 7 9 6 9-6"/>
                                </svg>

                            </div>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="exemple@email.com"
                                autocomplete="email"
                                required
                                class="w-full min-w-0 rounded-2xl border border-[#E8E2D4] bg-[#FAFAF8] py-3.5 pl-12 pr-4 text-sm text-[#071B36] outline-none transition duration-200 placeholder:text-[#A0ADBA] hover:border-[#D7A200]/50 focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                        </div>

                    </div>

                    <!-- Mot de passe -->
                    <div class="min-w-0">

                        <label
                            for="password"
                            class="mb-2.5 block text-sm font-bold text-[#071B36]"
                        >
                            Mot de passe
                        </label>

                        <div class="relative min-w-0">

                            <div class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#71869A]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="19"
                                    height="19"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect x="3" y="11" width="18" height="10" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>

                            </div>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Votre mot de passe"
                                autocomplete="current-password"
                                required
                                class="w-full min-w-0 rounded-2xl border border-[#E8E2D4] bg-[#FAFAF8] py-3.5 pl-12 pr-14 text-sm text-[#071B36] outline-none transition duration-200 placeholder:text-[#A0ADBA] hover:border-[#D7A200]/50 focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                            <!-- Bouton afficher / masquer -->
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute right-2 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-xl text-[#71869A] transition hover:bg-[#FFF9E6] hover:text-[#B88700]"
                                aria-label="Afficher le mot de passe"
                                aria-pressed="false"
                            >

                                <svg
                                    id="eyeIcon"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                            </button>

                        </div>

                    </div>

                    <!-- Mot de passe oublié -->
                    <div class="flex justify-end">

                        <a
                            href="/immo-sn/mot-de-passe-oublie"
                            class="text-sm font-semibold text-[#B88700] transition hover:text-[#8A6500] hover:underline"
                        >
                            Mot de passe oublié ?
                        </a>

                    </div>

                    <!-- Bouton connexion -->
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-[#071B36] px-5 py-4 font-bold text-white shadow-lg shadow-[#071B36]/15 transition duration-200 hover:-translate-y-0.5 hover:bg-[#102B4C] hover:shadow-xl active:translate-y-0"
                    >

                        <span>Se connecter</span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#F5B800"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="transition-transform group-hover:translate-x-1"
                        >
                            <path d="M5 12h14"/>
                            <path d="m13 6 6 6-6 6"/>
                        </svg>

                    </button>

                </form>

            </div>

            <!-- Inscription -->
            <div class="mt-7 min-w-0 text-center">

                <p class="break-words text-sm text-[#71869A]">
                    Vous n'avez pas encore de compte ?
                </p>

                <a
                    href="/immo-sn/inscription"
                    class="mt-2 inline-flex max-w-full items-center justify-center gap-2 break-words font-bold text-[#B88700] transition hover:text-[#8A6500]"
                >

                    Créer un compte

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M5 12h14"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>

                </a>

            </div>

            <!-- Retour accueil -->
            <div class="mt-8 text-center">

                <a
                    href="/immo-sn/"
                    class="inline-flex items-center gap-2 break-words text-sm font-semibold text-[#71869A] transition hover:text-[#071B36]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="m15 18-6-6 6-6"/>
                        <path d="M9 12h12"/>
                    </svg>

                    Retour à l'accueil

                </a>

            </div>

            <!-- Footer mobile -->
            <div class="mt-10 text-center lg:hidden">

                <div class="mx-auto mb-3 h-px w-16 bg-[#E8E2D4]"></div>

                <p class="text-xs text-[#71869A]/70">
                    © <?= date('Y') ?> ImmoSN — Immobilier au Sénégal
                </p>

            </div>

        </div>

    </section>

</div>

<!-- =====================================================
     SCRIPT : AFFICHER / MASQUER LE MOT DE PASSE
====================================================== -->

<script>
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const eyeIcon = document.getElementById('eyeIcon');

if (passwordInput && togglePassword) {

    togglePassword.addEventListener('click', function () {

        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';

        this.setAttribute(
            'aria-label',
            isPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
        );

        this.setAttribute(
            'aria-pressed',
            isPassword ? 'true' : 'false'
        );

        if (eyeIcon) {

            eyeIcon.innerHTML = isPassword
                ? `
                    <path d="M3 3l18 18"/>
                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                    <path d="M9.9 5.2A9.8 9.8 0 0 1 12 5c6.4 0 10 7 10 7a16.5 16.5 0 0 1-4.1 4.9"/>
                    <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.6 7 10 7c1.4 0 2.7-.4 3.8-1"/>
                  `
                : `
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                  `;

        }

    });

}
</script>

</body>
</html>
