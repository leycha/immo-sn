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
        <?= htmlspecialchars($title ?? 'Connexion — ImmoSN', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

<div class="grid min-h-screen min-w-0 lg:grid-cols-2">

    <!-- =====================================================
         PARTIE GAUCHE : PRESENTATION
    ====================================================== -->

    <div class="relative hidden min-w-0 overflow-hidden bg-[#101D33] text-white lg:flex">

        <!-- DECORATION -->
        <div
            class="absolute inset-0"
            style="
                background:
                    radial-gradient(
                        circle at 15% 15%,
                        rgba(126, 152, 51, 0.35),
                        transparent 38%
                    ),
                    radial-gradient(
                        circle at 85% 85%,
                        rgba(113, 134, 154, 0.20),
                        transparent 40%
                    );
            "
        ></div>

        <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full border border-white/5"></div>
        <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full border border-white/5"></div>
        <div class="absolute -bottom-40 -left-40 h-[500px] w-[500px] rounded-full border border-white/5"></div>

        <!-- CONTENU -->
        <div class="relative z-10 flex w-full min-w-0 flex-col justify-between p-8 xl:p-14">

            <!-- LOGO -->
            <div>

                <a href="/immo-sn/" class="inline-flex items-center gap-3">

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl
                               bg-[#7E9833] text-xl font-black text-white shadow-lg"
                    >
                        I
                    </div>

                    <div>

                        <div class="text-2xl font-black tracking-tight">
                            Immo<span class="text-[#A9C65B]">SN</span>
                        </div>

                        <div class="text-xs tracking-wide text-white/50">
                            Immobilier au Sénégal
                        </div>

                    </div>

                </a>

            </div>


            <!-- TEXTE CENTRAL -->
            <div class="max-w-xl min-w-0 py-12">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#A9C65B]/20
                            bg-[#7E9833]/10 px-4 py-2">

                    <span class="h-2 w-2 rounded-full bg-[#A9C65B]"></span>

                    <span class="text-xs font-bold uppercase tracking-[0.18em] text-[#A9C65B]">
                        Bienvenue chez ImmoSN
                    </span>

                </div>

                <h1 class="mb-6 break-words text-4xl font-black leading-tight xl:text-6xl">

                    Votre projet immobilier,

                    <span class="text-[#A9C65B]">
                        notre priorité.
                    </span>

                </h1>

                <p class="max-w-lg text-base leading-8 text-white/65 xl:text-lg">

                    Retrouvez votre espace personnel et profitez d'une
                    expérience immobilière simple pour gérer vos annonces,
                    vos favoris, vos messages et vos projets.

                </p>


                <!-- SEPARATEUR -->
                <div class="my-10 h-px w-24 bg-[#7E9833]"></div>


                <!-- POINTS -->
                <div class="space-y-5">

                    <div class="flex items-start gap-4">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                   bg-white/5 text-[#A9C65B]"
                        >
                            <svg
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
                                <path d="m3 10 9-7 9 7"/>
                                <path d="M5 9v11h14V9"/>
                                <path d="M9 20v-6h6v6"/>
                            </svg>
                        </div>

                        <div>

                            <p class="font-bold text-white">
                                Votre espace immobilier
                            </p>

                            <p class="mt-1 text-sm text-white/45">
                                Retrouvez tous vos biens et vos activités.
                            </p>

                        </div>

                    </div>


                    <div class="flex items-start gap-4">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                   bg-white/5 text-[#A9C65B]"
                        >
                            <svg
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
                                <path d="M20 7v10"/>
                                <path d="M4 7v10"/>
                                <path d="M4 12h16"/>
                                <path d="M8 7a4 4 0 0 1 8 0"/>
                            </svg>
                        </div>

                        <div>

                            <p class="font-bold text-white">
                                Une expérience simplifiée
                            </p>

                            <p class="mt-1 text-sm text-white/45">
                                Gérez vos annonces en toute simplicité.
                            </p>

                        </div>

                    </div>


                    <div class="flex items-start gap-4">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                   bg-white/5 text-[#A9C65B]"
                        >
                            <svg
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
                                <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>

                        <div>

                            <p class="font-bold text-white">
                                Un espace sécurisé
                            </p>

                            <p class="mt-1 text-sm text-white/45">
                                Accédez à votre compte en toute confiance.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-6">

                <p class="text-xs text-white/35">
                    © <?= date('Y') ?> ImmoSN — Immobilier au Sénégal
                </p>

                <div class="flex items-center gap-2 text-xs text-white/40">

                    <span class="h-2 w-2 rounded-full bg-[#7E9833]"></span>

                    <span>Votre partenaire immobilier</span>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         PARTIE DROITE : FORMULAIRE DE CONNEXION
    ====================================================== -->

    <div class="flex min-w-0 items-center justify-center px-4 py-10 sm:px-8 sm:py-12">

        <div class="w-full min-w-0 max-w-md">


            <!-- LOGO MOBILE -->
            <div class="mb-10 text-center lg:hidden">

                <a href="/immo-sn/" class="inline-flex items-center gap-3">

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl
                               bg-[#7E9833] text-xl font-black text-white"
                    >
                        I
                    </div>

                    <div class="text-2xl font-black text-[#101D33]">
                        Immo<span class="text-[#7E9833]">SN</span>
                    </div>

                </a>

            </div>


            <!-- EN-TETE -->
            <div class="mb-8 min-w-0">

                <div class="mb-3 flex items-center gap-2">

                    <span class="h-1.5 w-8 rounded-full bg-[#7E9833]"></span>

                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#7E9833]">
                        Espace membre
                    </span>

                </div>

                <h2 class="break-words text-3xl font-black tracking-tight text-[#101D33] sm:text-4xl">
                    Heureux de vous revoir !
                </h2>

                <p class="mt-3 break-words leading-7 text-[#71869A]">
                    Connectez-vous à votre compte pour continuer.
                </p>

            </div>


            <!-- ERREUR -->
            <?php if (!empty($error)): ?>

                <div
                    class="mb-6 min-w-0 rounded-2xl border border-red-200
                           bg-red-50 px-4 py-4 text-sm text-red-700"
                >

                    <div class="flex min-w-0 items-start gap-3">

                        <div
                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg
                                   bg-red-100 font-bold text-red-600"
                        >
                            !
                        </div>

                        <div class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    </div>

                </div>

            <?php endif; ?>


            <!-- SUCCES -->
            <?php if (!empty($success)): ?>

                <div
                    class="mb-6 min-w-0 rounded-2xl border border-green-200
                           bg-green-50 px-4 py-4 text-sm text-green-700"
                >

                    <div class="flex min-w-0 items-start gap-3">

                        <div
                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg
                                   bg-green-100 font-bold text-green-600"
                        >
                            ✓
                        </div>

                        <div class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    </div>

                </div>

            <?php endif; ?>


            <!-- CARD FORMULAIRE -->
            <div class="rounded-[28px] border border-[#E6E8DF] bg-white p-5 shadow-sm sm:p-7">

                <form
                    method="POST"
                    action="/immo-sn/login"
                    class="min-w-0 space-y-6"
                >


                    <!-- EMAIL -->
                    <div class="min-w-0">

                        <label
                            for="email"
                            class="mb-2.5 block text-sm font-bold text-[#101D33]"
                        >
                            Adresse email
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
                                class="w-full min-w-0 rounded-2xl border border-[#E6E8DF]
                                       bg-[#F5F3ED]/50 py-3.5 pl-12 pr-4 text-sm text-[#101D33]
                                       outline-none transition duration-200
                                       placeholder:text-[#71869A]/60
                                       focus:border-[#7E9833] focus:bg-white
                                       focus:ring-4 focus:ring-[#7E9833]/10"
                            >

                        </div>

                    </div>


                    <!-- MOT DE PASSE -->
                    <div class="min-w-0">

                        <label
                            for="password"
                            class="mb-2.5 block text-sm font-bold text-[#101D33]"
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
                                class="w-full min-w-0 rounded-2xl border border-[#E6E8DF]
                                       bg-[#F5F3ED]/50 py-3.5 pl-12 pr-14 text-sm text-[#101D33]
                                       outline-none transition duration-200
                                       placeholder:text-[#71869A]/60
                                       focus:border-[#7E9833] focus:bg-white
                                       focus:ring-4 focus:ring-[#7E9833]/10"
                            >

                            <!-- BOUTON AFFICHER -->
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute right-2 top-1/2 flex h-10 w-10
                                       -translate-y-1/2 items-center justify-center rounded-xl
                                       text-[#71869A] transition
                                       hover:bg-[#EDF2E1] hover:text-[#6D852B]"
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


                    <!-- MOT DE PASSE OUBLIE -->
                    <div class="flex justify-end">

                        <a
                            href="/immo-sn/mot-de-passe-oublie"
                            class="text-sm font-semibold text-[#7E9833] transition
                                   hover:text-[#6D852B] hover:underline"
                        >
                            Mot de passe oublié ?
                        </a>

                    </div>


                    <!-- BOUTON CONNEXION -->
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-3
                               rounded-2xl bg-[#7E9833] px-5 py-4
                               font-bold text-white shadow-sm transition duration-200
                               hover:-translate-y-0.5 hover:bg-[#6D852B] hover:shadow-md
                               active:translate-y-0"
                    >

                        <span>Se connecter</span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
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


            <!-- INSCRIPTION -->
            <div class="mt-7 min-w-0 text-center">

                <p class="break-words text-sm text-[#71869A]">
                    Vous n'avez pas encore de compte ?
                </p>

                <a
                    href="/immo-sn/inscription"
                    class="mt-2 inline-flex max-w-full items-center justify-center gap-2
                           break-words font-bold text-[#7E9833] transition
                           hover:text-[#6D852B]"
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


            <!-- RETOUR ACCUEIL -->
            <div class="mt-8 text-center">

                <a
                    href="/immo-sn/"
                    class="inline-flex items-center gap-2 break-words text-sm font-semibold
                           text-[#71869A] transition hover:text-[#101D33]"
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


            <!-- FOOTER MOBILE -->
            <div class="mt-10 text-center lg:hidden">

                <p class="text-xs text-[#71869A]/70">
                    © <?= date('Y') ?> ImmoSN — Immobilier au Sénégal
                </p>

            </div>

        </div>

    </div>

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