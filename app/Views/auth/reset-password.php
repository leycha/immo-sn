<?php
use Core\Session;
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
        <?= htmlspecialchars($title ?? 'Nouveau mot de passe — ImmoSN', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

<div class="grid min-h-screen lg:grid-cols-2">


    <!-- =====================================================
         PARTIE GAUCHE : IDENTITE VISUELLE
    ====================================================== -->

    <aside
        class="relative hidden overflow-hidden bg-[#101D33] p-10 text-white
               lg:flex lg:flex-col lg:justify-between xl:p-14"
    >

        <!-- Décoration -->

        <div
            class="absolute inset-0"
            style="
                background:
                    radial-gradient(
                        circle at 15% 15%,
                        rgba(126, 152, 51, .28),
                        transparent 38%
                    ),
                    radial-gradient(
                        circle at 85% 85%,
                        rgba(113, 134, 154, .18),
                        transparent 40%
                    );
            "
        ></div>

        <div class="absolute -bottom-32 -right-32 h-[480px] w-[480px] rounded-full border border-[#A9C65B]/10"></div>

        <div class="absolute -bottom-20 -right-20 h-[360px] w-[360px] rounded-full border border-[#A9C65B]/10"></div>


        <!-- LOGO -->

        <a href="/immo-sn/" class="relative z-10 inline-flex items-center gap-3">

            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl
                       bg-[#7E9833] text-xl font-black text-white"
            >
                I
            </div>

            <div>

                <div class="text-2xl font-black">
                    Immo<span class="text-[#A9C65B]">SN</span>
                </div>

                <p class="text-xs text-white/45">
                    Immobilier au Sénégal
                </p>

            </div>

        </a>


        <!-- TEXTE -->

        <div class="relative z-10 max-w-xl py-12">

            <div class="mb-6 inline-flex items-center gap-2 rounded-full
                        border border-[#A9C65B]/20 bg-[#7E9833]/10 px-4 py-2">

                <span class="h-2 w-2 rounded-full bg-[#A9C65B]"></span>

                <span class="text-xs font-bold uppercase tracking-[.18em] text-[#A9C65B]">
                    Sécurité de votre compte
                </span>

            </div>

            <h1 class="break-words text-4xl font-black leading-tight xl:text-6xl">

                Un nouveau départ,

                <span class="text-[#A9C65B]">
                    en toute sécurité.
                </span>

            </h1>

            <p class="mt-7 max-w-md text-sm leading-7 text-white/60 xl:text-base">
                Votre compte vous appartient. Choisissez un nouveau mot de passe
                pour retrouver votre espace personnel et poursuivre vos projets immobiliers.
            </p>


            <!-- Séparateur -->

            <div class="my-9 h-px w-20 bg-[#7E9833]"></div>


            <!-- Info sécurité -->

            <div class="flex items-start gap-4">

                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center
                           rounded-xl bg-white/5 text-[#A9C65B]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="22"
                        height="22"
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

                    <p class="font-bold">
                        Protégez votre compte
                    </p>

                    <p class="mt-1 text-sm leading-6 text-white/45">
                        Utilisez un mot de passe personnel et difficile à deviner.
                    </p>

                </div>

            </div>

        </div>


        <!-- FOOTER -->

        <div class="relative z-10 border-t border-white/10 pt-6 text-xs text-white/35">

            © <?= date('Y') ?> ImmoSN — Immobilier au Sénégal

        </div>

    </aside>


    <!-- =====================================================
         PARTIE DROITE : FORMULAIRE
    ====================================================== -->

    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-8">

        <div class="w-full max-w-md">


            <!-- LOGO MOBILE -->

            <div class="mb-9 text-center lg:hidden">

                <a href="/immo-sn/" class="inline-flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl
                               bg-[#101D33] font-black text-[#A9C65B]"
                    >
                        I
                    </div>

                    <div class="text-2xl font-black text-[#101D33]">
                        Immo<span class="text-[#7E9833]">SN</span>
                    </div>

                </a>

            </div>


            <!-- TITRE -->

            <div class="mb-8">

                <div class="mb-3 flex items-center gap-2">

                    <span class="h-1.5 w-8 rounded-full bg-[#7E9833]"></span>

                    <p class="text-xs font-bold uppercase tracking-[.18em] text-[#7E9833]">
                        Récupération du compte
                    </p>

                </div>

                <h2 class="text-3xl font-black tracking-tight text-[#101D33] sm:text-4xl">
                    Nouveau mot de passe
                </h2>

                <p class="mt-3 text-sm leading-6 text-[#71869A] sm:text-base">
                    Définissez un nouveau mot de passe pour sécuriser votre compte.
                </p>

            </div>


            <!-- ERREUR -->

            <?php if (!empty($error)): ?>

                <div
                    class="mb-6 rounded-2xl border border-red-200
                           bg-red-50 px-4 py-4 text-sm text-red-700"
                >

                    <div class="flex items-start gap-3">

                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 font-bold">
                            !
                        </div>

                        <p class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                    </div>

                </div>

            <?php endif; ?>


            <!-- CARTE FORMULAIRE -->

            <div class="rounded-[26px] border border-[#E6E8DF] bg-white p-5 shadow-sm sm:p-7">

                <form
                    action="/immo-sn/reinitialiser-mot-de-passe"
                    method="POST"
                    class="space-y-6"
                >

                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= htmlspecialchars(Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <!-- TOKEN -->

                    <input
                        type="hidden"
                        name="token"
                        value="<?= htmlspecialchars($token ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >


                    <!-- NOUVEAU MOT DE PASSE -->

                    <div>

                        <label
                            for="password"
                            class="mb-2 block text-sm font-bold text-[#101D33]"
                        >
                            Nouveau mot de passe
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
                                    <rect x="3" y="11" width="18" height="10" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>

                            </div>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                minlength="8"
                                required
                                autocomplete="new-password"
                                placeholder="8 caractères minimum"
                                class="w-full rounded-2xl border border-[#E6E8DF]
                                       bg-[#F5F3ED]/50 py-3.5 pl-12 pr-14
                                       text-sm text-[#101D33] outline-none transition
                                       placeholder:text-[#71869A]/60
                                       focus:border-[#7E9833] focus:bg-white
                                       focus:ring-4 focus:ring-[#7E9833]/10"
                            >

                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute right-2 top-1/2 flex h-10 w-10
                                       -translate-y-1/2 items-center justify-center
                                       rounded-xl text-[#71869A] transition
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

                        <p class="mt-2 text-xs text-[#71869A]">
                            8 caractères minimum.
                        </p>

                    </div>


                    <!-- CONFIRMATION -->

                    <div>

                        <label
                            for="password_confirmation"
                            class="mb-2 block text-sm font-bold text-[#101D33]"
                        >
                            Confirmer le mot de passe
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
                                    <rect x="3" y="11" width="18" height="10" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>

                            </div>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                minlength="8"
                                required
                                autocomplete="new-password"
                                placeholder="Retapez votre mot de passe"
                                class="w-full rounded-2xl border border-[#E6E8DF]
                                       bg-[#F5F3ED]/50 py-3.5 pl-12 pr-4
                                       text-sm text-[#101D33] outline-none transition
                                       placeholder:text-[#71869A]/60
                                       focus:border-[#7E9833] focus:bg-white
                                       focus:ring-4 focus:ring-[#7E9833]/10"
                            >

                        </div>

                    </div>


                    <!-- BOUTON -->

                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-3
                               rounded-2xl bg-[#7E9833] px-5 py-4
                               text-sm font-bold text-white shadow-sm transition
                               hover:-translate-y-0.5 hover:bg-[#6D852B] hover:shadow-md
                               active:translate-y-0"
                    >

                        <span>Enregistrer le nouveau mot de passe</span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
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


            <!-- RETOUR CONNEXION -->

            <div class="mt-7 text-center">

                <a
                    href="/immo-sn/login"
                    class="inline-flex items-center justify-center gap-2
                           text-sm font-semibold text-[#7E9833] transition
                           hover:text-[#6D852B] hover:underline"
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

                    Retour à la connexion

                </a>

            </div>


            <!-- FOOTER MOBILE -->

            <div class="mt-9 border-t border-[#E6E8DF] pt-5 text-center lg:hidden">

                <p class="text-xs text-[#71869A]">
                    © <?= date('Y') ?> ImmoSN — Immobilier au Sénégal
                </p>

            </div>

        </div>

    </main>

</div>


<!-- =====================================================
     JAVASCRIPT : AFFICHER / MASQUER MOT DE PASSE
====================================================== -->

<script>

const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const eyeIcon = document.getElementById('eyeIcon');

if (passwordInput && togglePassword) {

    togglePassword.addEventListener('click', function () {

        const visible = passwordInput.type === 'password';

        passwordInput.type = visible ? 'text' : 'password';

        this.setAttribute(
            'aria-label',
            visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
        );

        this.setAttribute(
            'aria-pressed',
            visible ? 'true' : 'false'
        );

        if (eyeIcon) {

            eyeIcon.innerHTML = visible
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