<?php
use Core\Session;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#101D33">

    <title>
        <?= htmlspecialchars($title ?? 'Mot de passe oublié — IMMO-SN', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        ::selection {
            background: #DCE8C6;
            color: #101D33;
        }

        :focus-visible {
            outline: 3px solid rgba(126, 152, 51, 0.35);
            outline-offset: 2px;
        }
    </style>

</head>


<body class="flex min-h-screen items-center justify-center bg-[#F5F3ED] px-4 py-10">


    <!-- =====================================================
         CONTENEUR PRINCIPAL
    ====================================================== -->

    <main class="w-full max-w-md">


        <!-- =================================================
             CARTE
        ================================================== -->

        <div class="overflow-hidden rounded-[30px] border border-[#E6E8DF] bg-white shadow-sm">


            <!-- BANDEAU IDENTITÉ -->

            <div class="bg-[#101D33] px-8 py-8 text-center">

                <!-- Logo -->
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-2xl font-extrabold text-[#A9C65B]">
                    I
                </div>

                <h1 class="mt-4 text-2xl font-extrabold tracking-wide text-white">
                    IMMO-SN
                </h1>

                <p class="mt-1 text-xs text-white/60">
                    Votre plateforme immobilière au Sénégal
                </p>

            </div>


            <!-- =================================================
                 CONTENU FORMULAIRE
            ================================================== -->

            <div class="px-6 py-8 sm:px-8">


                <!-- TITRE -->

                <div class="mb-7 text-center">

                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#EDF2E1] text-[#7E9833]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 7.5 12 13l9-5.5"/>
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                        </svg>

                    </div>

                    <h2 class="text-xl font-extrabold text-[#101D33]">
                        Mot de passe oublié ?
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-[#71869A]">
                        Saisissez votre adresse e-mail pour recevoir un lien de réinitialisation.
                    </p>

                </div>


                <!-- =================================================
                     MESSAGE ERREUR
                ================================================== -->

                <?php if (!empty($error)): ?>

                    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mt-0.5 h-5 w-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>
                        </svg>

                        <p class="leading-5">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                    </div>

                <?php endif; ?>


                <!-- =================================================
                     MESSAGE SUCCÈS
                ================================================== -->

                <?php if (!empty($success)): ?>

                    <div class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mt-0.5 h-5 w-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                            <circle cx="12" cy="12" r="10"/>
                        </svg>

                        <p class="leading-5">
                            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                    </div>

                <?php endif; ?>


                <!-- =================================================
                     FORMULAIRE
                ================================================== -->

                <form
                    action="/immo-sn/mot-de-passe-oublie"
                    method="POST"
                    class="space-y-5"
                >

                    <!-- TOKEN CSRF -->

                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= htmlspecialchars(Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>"
                    >


                    <!-- EMAIL -->

                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-sm font-bold text-[#101D33]"
                        >
                            Adresse e-mail
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#71869A]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
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
                                required
                                autocomplete="email"
                                placeholder="exemple@email.com"
                                class="w-full rounded-xl border border-[#E1E5DA] bg-[#FAFBF8] py-3.5 pl-12 pr-4 text-sm text-[#101D33] outline-none transition placeholder:text-[#A0ADBA] focus:border-[#7E9833] focus:bg-white focus:ring-4 focus:ring-[#7E9833]/10"
                            >

                        </div>

                    </div>


                    <!-- BOUTON -->

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#6D852B] active:scale-[0.98]"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M22 2 11 13"/>
                            <path d="M22 2 15 22l-4-9-9-4Z"/>
                        </svg>

                        Envoyer le lien de réinitialisation

                    </button>

                </form>


                <!-- =================================================
                     RETOUR CONNEXION
                ================================================== -->

                <div class="mt-7 border-t border-[#E6E8DF] pt-6 text-center">

                    <a
                        href="/immo-sn/login"
                        class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-[#71869A] transition hover:text-[#7E9833]"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
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

            </div>

        </div>


        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <div class="mt-6 text-center">

            <div class="flex items-center justify-center gap-2 text-xs text-[#71869A]">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-[#7E9833]"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>

                <span>
                    Réinitialisation sécurisée de votre compte
                </span>

            </div>

            <p class="mt-3 text-[11px] text-[#71869A]">
                © <?= date('Y') ?> IMMO-SN — Tous droits réservés.
            </p>

        </div>

    </main>

</body>
</html>