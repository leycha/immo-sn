
<?php
use Core\Session;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#071B36">

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

        .card-shadow {
            box-shadow: 0 25px 70px rgba(7, 27, 54, 0.10);
        }
    </style>

</head>

<body class="flex min-h-screen items-center justify-center bg-[#F5F3ED] px-4 py-10">

    <!-- Décoration arrière-plan -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -left-32 -top-32 h-80 w-80 rounded-full bg-[#F5B800]/10 blur-3xl"></div>
        <div class="absolute -bottom-40 -right-32 h-96 w-96 rounded-full bg-[#071B36]/5 blur-3xl"></div>
    </div>

    <!-- Conteneur principal -->
    <main class="relative z-10 w-full max-w-md">

        <!-- Carte principale -->
        <div class="overflow-hidden rounded-[28px] border border-[#E8E2D4] bg-white card-shadow">

            <!-- Bandeau identité -->
            <div class="relative overflow-hidden bg-[#071B36] px-8 py-9 text-center">

                <!-- Décoration -->
                <div class="pointer-events-none absolute -right-12 -top-16 h-48 w-48 rounded-full border border-white/5"></div>
                <div class="pointer-events-none absolute -right-4 -top-8 h-32 w-32 rounded-full border border-[#F5B800]/10"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-12 h-44 w-44 rounded-full bg-white/[0.03]"></div>

                <!-- Logo -->
                <div class="relative mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-[#F5B800]/30 bg-white/10 shadow-lg shadow-black/10">

                    <span class="text-3xl font-extrabold text-[#F5B800]">
                        I
                    </span>

                    <span class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full border-[3px] border-[#071B36] bg-[#F5B800]"></span>

                </div>

                <h1 class="relative mt-4 text-2xl font-extrabold tracking-wide text-white">
                    IMMO-SN
                </h1>

                <p class="relative mt-1 text-xs tracking-wide text-slate-300">
                    Votre plateforme immobilière au Sénégal
                </p>

                <div class="relative mx-auto mt-5 h-1 w-12 rounded-full gold-gradient"></div>

            </div>

            <!-- Contenu formulaire -->
            <div class="px-6 py-8 sm:px-8">

                <!-- Introduction -->
                <div class="mb-7 text-center">

                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-[#F5B800]/25 bg-[#FFF9E6] text-[#B88700]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path d="m3 7 9 6 9-6"/>
                            <path d="M16 16h.01"/>
                        </svg>

                    </div>

                    <h2 class="text-xl font-extrabold tracking-tight text-[#071B36]">
                        Mot de passe oublié ?
                    </h2>

                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-[#71869A]">
                        Pas d'inquiétude ! Saisissez votre adresse e-mail
                        pour recevoir un lien de réinitialisation.
                    </p>

                </div>

                <!-- Message erreur -->
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

                <!-- Message succès -->
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

                <!-- Formulaire -->
                <form
                    action="/immo-sn/mot-de-passe-oublie"
                    method="POST"
                    class="space-y-5"
                >

                    <!-- Token CSRF -->
                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= htmlspecialchars(Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <!-- Adresse e-mail -->
                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-sm font-bold text-[#071B36]"
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
                                class="w-full rounded-xl border border-[#E8E2D4] bg-[#FAFAF8] py-3.5 pl-12 pr-4 text-sm text-[#071B36] outline-none transition duration-200 placeholder:text-[#A0ADBA] hover:border-[#D7A200]/50 focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                        </div>

                        <p class="mt-2 text-xs text-[#71869A]">
                            Utilisez l'adresse e-mail associée à votre compte.
                        </p>

                    </div>

                    <!-- Bouton -->
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-4 text-sm font-bold text-white shadow-lg shadow-[#071B36]/15 transition duration-200 hover:bg-[#102B4C] active:scale-[0.98]"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-[#F5B800] transition-transform duration-200 group-hover:translate-x-1"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M22 2 11 13"/>
                            <path d="M22 2 15 22l-4-9-9-4Z"/>
                        </svg>

                        Envoyer le lien de réinitialisation

                    </button>

                </form>

                <!-- Retour connexion -->
                <div class="mt-7 border-t border-[#E8E2D4] pt-6 text-center">

                    <a
                        href="/immo-sn/login"
                        class="group inline-flex items-center justify-center gap-2 text-sm font-semibold text-[#71869A] transition hover:text-[#B88700]"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-1"
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

        <!-- Footer -->
        <div class="mt-6 text-center">

            <div class="inline-flex items-center justify-center gap-2 rounded-full border border-[#E8E2D4] bg-white/70 px-4 py-2 text-xs font-medium text-[#71869A]">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-[#B88700]"
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

                Réinitialisation sécurisée de votre compte

            </div>

            <p class="mt-4 text-[11px] text-[#71869A]">
                © <?= date('Y') ?> IMMO-SN — Tous droits réservés.
            </p>

        </div>

    </main>

</body>
</html>
