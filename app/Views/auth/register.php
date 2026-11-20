<?php

$error = $error ?? null;
$success = $success ?? null;

$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$ville = $_POST['ville'] ?? '';

$role = $_POST['role'] ?? 'particulier';
$nomAgence = $_POST['nom_agence'] ?? '';
$adresseAgence = $_POST['adresse_agence'] ?? '';
$ninea = $_POST['ninea'] ?? '';
$emailAgence = $_POST['email_agence'] ?? '';
$telephoneAgence = $_POST['telephone_agence'] ?? '';
$descriptionAgence = $_POST['description_agence'] ?? '';
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
        <?= htmlspecialchars($title ?? 'Inscription — ImmoSN', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        .field {
            width: 100%;
            min-width: 0;
            min-height: 48px;
            border: 1px solid #E6E8DF;
            border-radius: 14px;
            padding: 12px 15px;
            background: #F5F3ED;
            color: #101D33;
            font-size: 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .field::placeholder {
            color: #9AA5B1;
        }

        .field:focus {
            border-color: #7E9833;
            background: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(126, 152, 51, .10);
        }

        textarea.field {
            min-height: 95px;
            padding-top: 13px;
            padding-bottom: 13px;
            resize: vertical;
        }

        .role-choice input:checked + div {
            border-color: #7E9833;
            background: #EDF2E1;
        }

        .role-choice input:checked + div .role-dot {
            background: #7E9833;
            border-color: #7E9833;
            box-shadow: inset 0 0 0 3px white;
        }

        .role-choice input:focus-visible + div {
            outline: 3px solid rgba(126, 152, 51, .20);
            outline-offset: 2px;
        }

        .agency-fields {
            transition: opacity .2s ease;
        }

        input[type="file"] {
            max-width: 100%;
        }
    </style>

</head>

<body class="min-h-screen overflow-x-hidden bg-[#F5F3ED] text-[#101D33]">

<div class="grid min-h-screen min-w-0 lg:grid-cols-[42%_58%]">


    <!-- =====================================================
         PARTIE GAUCHE : IDENTITE VISUELLE
    ====================================================== -->

    <aside
        class="relative hidden min-h-screen min-w-0 overflow-hidden
               bg-[#101D33] p-8 text-white
               lg:sticky lg:top-0 lg:flex lg:h-screen
               lg:flex-col lg:justify-between xl:p-12"
    >

        <!-- DECORATIONS -->

        <div
            class="absolute inset-0"
            style="
                background:
                    radial-gradient(
                        circle at 15% 20%,
                        rgba(126, 152, 51, .28),
                        transparent 38%
                    ),
                    radial-gradient(
                        circle at 90% 85%,
                        rgba(113, 134, 154, .18),
                        transparent 40%
                    );
            "
        ></div>

        <div class="absolute -bottom-32 -right-32 h-[480px] w-[480px] rounded-full border border-[#A9C65B]/10"></div>

        <div class="absolute -bottom-20 -right-20 h-[360px] w-[360px] rounded-full border border-[#A9C65B]/10"></div>

        <div class="absolute left-1/2 top-1/2 h-80 w-80 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#7E9833]/5 blur-3xl"></div>


        <!-- LOGO -->

        <a
            href="/immo-sn/"
            class="relative z-10 flex min-w-0 items-center gap-3"
        >

            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center
                       rounded-2xl bg-[#7E9833] text-xl font-black text-white shadow-lg"
            >
                I
            </div>

            <div class="min-w-0">

                <div class="text-2xl font-black tracking-tight">
                    Immo<span class="text-[#A9C65B]">SN</span>
                </div>

                <p class="text-xs text-white/45">
                    Immobilier au Sénégal
                </p>

            </div>

        </a>


        <!-- TEXTE DE PRESENTATION -->

        <div class="relative z-10 min-w-0 max-w-lg py-12">

            <div class="mb-6 inline-flex items-center gap-2 rounded-full
                        border border-[#A9C65B]/20 bg-[#7E9833]/10 px-4 py-2">

                <span class="h-2 w-2 rounded-full bg-[#A9C65B]"></span>

                <span class="text-xs font-bold uppercase tracking-[.18em] text-[#A9C65B]">
                    Rejoignez ImmoSN
                </span>

            </div>


            <h1 class="break-words text-4xl font-black leading-tight xl:text-6xl">

                L'immobilier

                <span class="text-[#A9C65B]">
                    prend une nouvelle dimension.
                </span>

            </h1>


            <p class="mt-7 max-w-sm break-words text-sm leading-7 text-white/60 xl:text-base">

                Créez votre compte et découvrez un espace conçu
                pour faciliter vos projets immobiliers au Sénégal.

            </p>


            <!-- SEPARATEUR -->

            <div class="my-9 h-px w-20 bg-[#7E9833]"></div>


            <!-- AVANTAGES -->

            <div class="space-y-5">


                <!-- AVANTAGE 1 -->

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center
                               rounded-xl bg-white/5 text-[#A9C65B]"
                    >

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
                            Un espace dédié
                        </p>

                        <p class="mt-1 text-sm text-white/45">
                            Gérez facilement votre activité immobilière.
                        </p>

                    </div>

                </div>


                <!-- AVANTAGE 2 -->

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center
                               rounded-xl bg-white/5 text-[#A9C65B]"
                    >

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
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>

                    </div>

                    <div>

                        <p class="font-bold text-white">
                            Particuliers & agences
                        </p>

                        <p class="mt-1 text-sm text-white/45">
                            Un espace adapté à chaque profil.
                        </p>

                    </div>

                </div>


                <!-- AVANTAGE 3 -->

                <div class="flex items-start gap-4">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center
                               rounded-xl bg-white/5 text-[#A9C65B]"
                    >

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
                            Une plateforme sécurisée
                        </p>

                        <p class="mt-1 text-sm text-white/45">
                            Vos informations sont au cœur de nos priorités.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- FOOTER -->

        <div class="relative z-10 flex flex-wrap items-center justify-between
                    gap-3 border-t border-white/10 pt-6 text-xs text-white/35">

            <span>IMMO-SN</span>

            <span>© <?= date('Y') ?> — Immobilier au Sénégal</span>

        </div>

    </aside>


    <!-- =====================================================
         PARTIE DROITE : FORMULAIRE D'INSCRIPTION
    ====================================================== -->

    <main
        class="flex min-h-screen min-w-0 items-start justify-center
               px-4 py-7
               sm:px-8 sm:py-10
               lg:h-screen lg:overflow-y-auto lg:px-10
               xl:px-14"
    >

        <div class="w-full min-w-0 max-w-[620px]">


            <!-- LOGO MOBILE -->

            <a
                href="/immo-sn/"
                class="mb-8 flex min-w-0 items-center gap-3 lg:hidden"
            >

                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center
                           rounded-xl bg-[#101D33] font-black text-[#A9C65B]"
                >
                    I
                </div>

                <div class="text-xl font-black tracking-tight">
                    Immo<span class="text-[#7E9833]">SN</span>
                </div>

            </a>


            <!-- TITRE -->

            <div class="mb-7 min-w-0">

                <div class="mb-3 flex items-center gap-2">

                    <span class="h-1.5 w-8 rounded-full bg-[#7E9833]"></span>

                    <p class="text-xs font-bold uppercase tracking-[.18em] text-[#7E9833]">
                        Espace client
                    </p>

                </div>


                <div class="flex min-w-0 flex-wrap items-end justify-between gap-3">

                    <div class="min-w-0">

                        <h2 class="break-words text-3xl font-black tracking-tight text-[#101D33] sm:text-4xl">
                            Créer un compte
                        </h2>

                        <p class="mt-3 break-words text-sm leading-6 text-[#71869A] sm:text-base">
                            Quelques informations pour rejoindre ImmoSN.
                        </p>

                    </div>

                    <span class="hidden shrink-0 rounded-lg bg-[#EDF2E1] px-3 py-2
                                 text-xs font-bold text-[#6D852B] sm:block">
                        01 / 01
                    </span>

                </div>

            </div>


            <!-- MESSAGE ERREUR -->

            <?php if (!empty($error)): ?>

                <div
                    class="mb-5 min-w-0 rounded-2xl border border-red-200
                           bg-red-50 px-4 py-4 text-sm text-red-700"
                >

                    <div class="flex min-w-0 items-start gap-3">

                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 font-bold">
                            !
                        </div>

                        <p class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                    </div>

                </div>

            <?php endif; ?>


            <!-- MESSAGE SUCCÈS -->

            <?php if (!empty($success)): ?>

                <div
                    class="mb-5 min-w-0 rounded-2xl border border-green-200
                           bg-green-50 px-4 py-4 text-sm text-green-700"
                >

                    <div class="flex min-w-0 items-start gap-3">

                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-100 font-bold">
                            ✓
                        </div>

                        <p class="min-w-0 break-words [overflow-wrap:anywhere]">
                            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                    </div>

                </div>

            <?php endif; ?>


            <!-- CARTE FORMULAIRE -->

            <div class="rounded-[26px] border border-[#E6E8DF] bg-white p-4 shadow-sm sm:p-6">


                <form
                    method="POST"
                    action="/immo-sn/inscription"
                    enctype="multipart/form-data"
                    class="min-w-0 space-y-6"
                >


                    <!-- =================================================
                         CHOIX DU PROFIL
                    ================================================== -->

                    <div class="min-w-0">

                        <label class="mb-3 block text-sm font-bold text-[#101D33]">
                            Quel est votre profil ?
                        </label>

                        <div class="grid grid-cols-2 gap-3">


                            <!-- PARTICULIER -->

                            <label class="role-choice min-w-0 cursor-pointer">

                                <input
                                    type="radio"
                                    name="role"
                                    value="particulier"
                                    <?= $role === 'particulier' ? 'checked' : '' ?>
                                    class="sr-only"
                                >

                                <div
                                    class="flex min-h-[62px] min-w-0 items-center justify-between
                                           gap-2 rounded-2xl border border-[#E6E8DF]
                                           bg-[#F5F3ED]/50 px-3 transition sm:px-4"
                                >

                                    <div class="flex min-w-0 items-center gap-2.5">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#71869A]">

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
                                                <circle cx="12" cy="8" r="4"/>
                                                <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                            </svg>

                                        </div>

                                        <span class="break-words text-xs font-bold text-[#101D33] sm:text-sm">
                                            Particulier
                                        </span>

                                    </div>

                                    <span class="role-dot h-4 w-4 shrink-0 rounded-full border border-[#C9D0C2]"></span>

                                </div>

                            </label>


                            <!-- AGENCE -->

                            <label class="role-choice min-w-0 cursor-pointer">

                                <input
                                    type="radio"
                                    name="role"
                                    value="agence"
                                    <?= $role === 'agence' ? 'checked' : '' ?>
                                    class="sr-only"
                                >

                                <div
                                    class="flex min-h-[62px] min-w-0 items-center justify-between
                                           gap-2 rounded-2xl border border-[#E6E8DF]
                                           bg-[#F5F3ED]/50 px-3 transition sm:px-4"
                                >

                                    <div class="flex min-w-0 items-center gap-2.5">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#71869A]">

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
                                                <path d="M3 21h18"/>
                                                <path d="M5 21V5l7-3 7 3v16"/>
                                                <path d="M9 9h1"/>
                                                <path d="M14 9h1"/>
                                                <path d="M9 13h1"/>
                                                <path d="M14 13h1"/>
                                                <path d="M10 21v-4h4v4"/>
                                            </svg>

                                        </div>

                                        <span class="break-words text-xs font-bold text-[#101D33] sm:text-sm">
                                            Agence
                                        </span>

                                    </div>

                                    <span class="role-dot h-4 w-4 shrink-0 rounded-full border border-[#C9D0C2]"></span>

                                </div>

                            </label>

                        </div>

                    </div>


                    <!-- =================================================
                         INFORMATIONS PERSONNELLES
                    ================================================== -->

                    <div class="min-w-0">


                        <!-- TITRE SECTION -->

                        <div class="mb-4 flex min-w-0 items-center gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#EDF2E1] text-[#6D852B]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <h3 class="text-sm font-bold text-[#101D33]">
                                    Informations personnelles
                                </h3>

                                <p class="text-xs text-[#71869A]">
                                    Vos coordonnées
                                </p>

                            </div>

                        </div>


                        <!-- NOM & PRENOM -->

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">


                            <!-- NOM -->

                            <div class="min-w-0">

                                <label
                                    for="nom"
                                    class="mb-2 block text-sm font-semibold text-[#101D33]"
                                >
                                    Nom <span class="text-red-500">*</span>
                                </label>

                                <input
                                    class="field"
                                    type="text"
                                    id="nom"
                                    name="nom"
                                    value="<?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Votre nom"
                                    autocomplete="family-name"
                                    required
                                >

                            </div>


                            <!-- PRENOM -->

                            <div class="min-w-0">

                                <label
                                    for="prenom"
                                    class="mb-2 block text-sm font-semibold text-[#101D33]"
                                >
                                    Prénom <span class="text-red-500">*</span>
                                </label>

                                <input
                                    class="field"
                                    type="text"
                                    id="prenom"
                                    name="prenom"
                                    value="<?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Votre prénom"
                                    autocomplete="given-name"
                                    required
                                >

                            </div>

                        </div>


                        <!-- EMAIL -->

                        <div class="mt-4 min-w-0">

                            <label
                                for="email"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                Adresse e-mail <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <div class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#71869A]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
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
                                    class="field pl-11"
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="nom@exemple.com"
                                    autocomplete="email"
                                    required
                                >

                            </div>

                        </div>


                        <!-- TELEPHONE & VILLE -->

                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">


                            <!-- TELEPHONE -->

                            <div class="min-w-0">

                                <label
                                    for="telephone"
                                    class="mb-2 block text-sm font-semibold text-[#101D33]"
                                >
                                    Téléphone
                                </label>

                                <div class="relative">

                                    <div class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#71869A]">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="18"
                                            height="18"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                            19.79 19.79 0 0 1-8.63-3.07
                                            19.5 19.5 0 0 1-6-6
                                            19.79 19.79 0 0 1-3.07-8.67
                                            A2 2 0 0 1 2.12 3h3a2 2 0 0 1
                                            2 1.72c.12.96.36 1.9.72 2.8a2 2 0
                                            0 1-.45 2.11L6.09 10.9a16 16 0 0 0
                                            6 6 0 0 0 2.29 2.29l1.27-1.27a2 2
                                            0 0 1 2.11-.45c.9.36 1.84.6
                                            2.8.72A2 2 0 0 1 22 16.92Z"/>
                                        </svg>

                                    </div>

                                    <input
                                        class="field pl-11"
                                        type="tel"
                                        id="telephone"
                                        name="telephone"
                                        value="<?= htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8') ?>"
                                        placeholder="77 000 00 00"
                                        autocomplete="tel"
                                    >

                                </div>

                            </div>


                            <!-- VILLE -->

                            <div class="min-w-0">

                                <label
                                    for="ville"
                                    class="mb-2 block text-sm font-semibold text-[#101D33]"
                                >
                                    Ville
                                </label>

                                <div class="relative">

                                    <div class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#71869A]">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="18"
                                            height="18"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                            <circle cx="12" cy="10" r="2.5"/>
                                        </svg>

                                    </div>

                                    <input
                                        class="field pl-11"
                                        type="text"
                                        id="ville"
                                        name="ville"
                                        value="<?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>"
                                        placeholder="Dakar"
                                        autocomplete="address-level2"
                                    >

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         INFORMATIONS AGENCE
                    ================================================== -->

                    <div
                        id="agencyFields"
                        class="<?= $role === 'agence' ? '' : 'hidden' ?>
                               agency-fields min-w-0 space-y-5
                               rounded-2xl border border-[#E6E8DF]
                               bg-[#F5F3ED]/60 p-4 sm:p-5"
                    >


                        <!-- EN-TETE AGENCE -->

                        <div class="flex min-w-0 items-start gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EDF2E1] text-[#6D852B]">

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
                                    <path d="M3 21h18"/>
                                    <path d="M5 21V5l7-3 7 3v16"/>
                                    <path d="M9 9h1"/>
                                    <path d="M14 9h1"/>
                                    <path d="M9 13h1"/>
                                    <path d="M14 13h1"/>
                                    <path d="M10 21v-4h4v4"/>
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <h3 class="text-sm font-bold text-[#101D33]">
                                    Informations professionnelles
                                </h3>

                                <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                    Renseignez les informations de votre agence.
                                </p>

                            </div>

                        </div>


                        <!-- NOM AGENCE -->

                        <div class="min-w-0">

                            <label
                                for="nom_agence"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                Nom de l'agence <span class="text-red-500">*</span>
                            </label>

                            <input
                                class="field"
                                type="text"
                                id="nom_agence"
                                name="nom_agence"
                                value="<?= htmlspecialchars($nomAgence, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Nom de votre agence"
                            >

                        </div>


                        <!-- ADRESSE AGENCE -->

                        <div class="min-w-0">

                            <label
                                for="adresse_agence"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                Adresse de l'agence <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                class="field"
                                id="adresse_agence"
                                name="adresse_agence"
                                rows="2"
                                placeholder="Adresse complète de l'agence"
                            ><?= htmlspecialchars($adresseAgence, ENT_QUOTES, 'UTF-8') ?></textarea>

                        </div>


                        <!-- NINEA -->

                        <div class="min-w-0">

                            <label
                                for="ninea"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                NINEA <span class="text-red-500">*</span>
                            </label>

                            <input
                                class="field"
                                type="text"
                                id="ninea"
                                name="ninea"
                                value="<?= htmlspecialchars($ninea, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Numéro NINEA"
                            >

                        </div>


                        <!-- EMAIL AGENCE -->

                        <div class="min-w-0">

                            <label
                                for="email_agence"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                E-mail professionnel <span class="text-red-500">*</span>
                            </label>

                            <input
                                class="field"
                                type="email"
                                id="email_agence"
                                name="email_agence"
                                value="<?= htmlspecialchars($emailAgence, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="contact@agence.com"
                            >

                        </div>


                        <!-- TELEPHONE AGENCE -->

                        <div class="min-w-0">

                            <label
                                for="telephone_agence"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                Téléphone professionnel <span class="text-red-500">*</span>
                            </label>

                            <input
                                class="field"
                                type="tel"
                                id="telephone_agence"
                                name="telephone_agence"
                                value="<?= htmlspecialchars($telephoneAgence, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="33 800 00 00"
                            >

                        </div>


                        <!-- LOGO AGENCE -->

                        <div class="min-w-0">

                            <label
                                for="logo_agence"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                Logo de l'agence
                            </label>

                            <input
                                class="block w-full min-w-0 rounded-2xl border border-[#E6E8DF]
                                       bg-white p-3 text-sm text-[#71869A]
                                       file:mr-3 file:max-w-full file:rounded-lg
                                       file:border-0 file:bg-[#EDF2E1]
                                       file:px-3 file:py-2.5 file:text-xs
                                       file:font-bold file:text-[#6D852B]
                                       hover:file:bg-[#E1EACB]"
                                type="file"
                                id="logo_agence"
                                name="logo_agence"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >

                            <p class="mt-2 break-words text-xs leading-5 text-[#71869A]">
                                Formats acceptés : JPG, PNG ou WebP. Taille maximale : 2 Mo.
                            </p>

                        </div>


                        <!-- DESCRIPTION AGENCE -->

                        <div class="min-w-0">

                            <label
                                for="description_agence"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                Description de l'agence
                            </label>

                            <textarea
                                class="field"
                                id="description_agence"
                                name="description_agence"
                                rows="3"
                                placeholder="Présentez brièvement votre agence, vos services et votre expérience..."
                            ><?= htmlspecialchars($descriptionAgence, ENT_QUOTES, 'UTF-8') ?></textarea>

                        </div>


                        <!-- INFO VALIDATION -->

                        <div class="flex min-w-0 items-start gap-3 rounded-xl border border-[#E6E8DF] bg-white p-3">

                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#EDF2E1] text-[#6D852B]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="17"
                                    height="17"
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

                            <p class="min-w-0 break-words text-xs leading-5 text-[#71869A]">
                                Les informations de votre agence seront soumises à une validation administrative avant sa visibilité sur la plateforme.
                            </p>

                        </div>

                    </div>


                    <!-- =================================================
                         SECURITE
                    ================================================== -->

                    <div class="min-w-0">


                        <!-- TITRE SECTION -->

                        <div class="mb-4 flex min-w-0 items-center gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#EDF2E1] text-[#6D852B]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
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

                            <div class="min-w-0">

                                <h3 class="text-sm font-bold text-[#101D33]">
                                    Sécurité de votre compte
                                </h3>

                                <p class="text-xs text-[#71869A]">
                                    Choisissez un mot de passe sécurisé.
                                </p>

                            </div>

                        </div>


                        <!-- MOT DE PASSE -->

                        <div class="min-w-0">

                            <label
                                for="password"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                Mot de passe <span class="text-red-500">*</span>
                            </label>

                            <div class="relative min-w-0">

                                <input
                                    class="field pr-16"
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="8 caractères minimum"
                                    minlength="8"
                                    autocomplete="new-password"
                                    required
                                >

                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute right-3 top-1/2
                                           -translate-y-1/2 rounded-lg px-2 py-1
                                           text-xs font-bold text-[#7E9833]
                                           transition hover:bg-[#EDF2E1]"
                                    aria-label="Afficher le mot de passe"
                                    aria-pressed="false"
                                >
                                    Voir
                                </button>

                            </div>

                            <p class="mt-2 text-xs text-[#71869A]">
                                Utilisez au moins 8 caractères.
                            </p>

                        </div>


                        <!-- CONFIRMATION -->

                        <div class="mt-4 min-w-0">

                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-semibold text-[#101D33]"
                            >
                                Confirmation du mot de passe <span class="text-red-500">*</span>
                            </label>

                            <input
                                class="field"
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Retapez votre mot de passe"
                                minlength="8"
                                autocomplete="new-password"
                                required
                            >

                        </div>

                    </div>


                    <!-- =================================================
                         BOUTON INSCRIPTION
                    ================================================== -->

                    <button
                        type="submit"
                        class="group flex min-h-[52px] w-full items-center justify-center
                               gap-3 rounded-2xl bg-[#7E9833] px-5 py-4
                               text-sm font-bold text-white shadow-sm transition duration-200
                               hover:-translate-y-0.5 hover:bg-[#6D852B] hover:shadow-md
                               active:translate-y-0"
                    >

                        <span>Créer mon compte</span>

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


            <!-- =================================================
                 LIENS BAS DE PAGE
            ================================================== -->

            <div class="mt-7 flex min-w-0 flex-col justify-between gap-4 sm:flex-row sm:flex-wrap sm:items-center">


                <!-- CONNEXION -->

                <p class="break-words text-sm text-[#71869A]">

                    Déjà inscrit ?

                    <a
                        href="/immo-sn/login"
                        class="font-bold text-[#7E9833] transition hover:text-[#6D852B] hover:underline"
                    >
                        Se connecter
                    </a>

                </p>


                <!-- RETOUR ACCUEIL -->

                <a
                    href="/immo-sn/"
                    class="inline-flex items-center gap-2 break-words text-xs font-semibold text-[#71869A]
                           transition hover:text-[#101D33]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="15"
                        height="15"
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

            <div class="mt-8 border-t border-[#E6E8DF] pt-5 text-center lg:hidden">

                <p class="text-xs text-[#71869A]">
                    © <?= date('Y') ?> ImmoSN — Immobilier au Sénégal
                </p>

            </div>

        </div>

    </main>

</div>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script>

    // =====================================================
    // AFFICHER / MASQUER LE MOT DE PASSE
    // =====================================================

    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    if (passwordInput && togglePassword) {

        togglePassword.addEventListener('click', function () {

            const visible = passwordInput.type === 'password';

            passwordInput.type = visible ? 'text' : 'password';

            this.textContent = visible ? 'Masquer' : 'Voir';

            this.setAttribute(
                'aria-label',
                visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
            );

            this.setAttribute(
                'aria-pressed',
                visible ? 'true' : 'false'
            );

        });

    }


    // =====================================================
    // AFFICHAGE CONDITIONNEL DES CHAMPS AGENCE
    // =====================================================

    const roleInputs = document.querySelectorAll('input[name="role"]');

    const agencyFields = document.getElementById('agencyFields');

    const nomAgenceInput = document.getElementById('nom_agence');
    const adresseAgenceInput = document.getElementById('adresse_agence');
    const nineaInput = document.getElementById('ninea');
    const emailAgenceInput = document.getElementById('email_agence');
    const telephoneAgenceInput = document.getElementById('telephone_agence');


    function toggleAgencyFields() {

        const selectedRole = document.querySelector(
            'input[name="role"]:checked'
        )?.value;

        const isAgency = selectedRole === 'agence';

        agencyFields.classList.toggle('hidden', !isAgency);

        nomAgenceInput.required = isAgency;
        adresseAgenceInput.required = isAgency;
        nineaInput.required = isAgency;
        emailAgenceInput.required = isAgency;
        telephoneAgenceInput.required = isAgency;

    }


    roleInputs.forEach(input => {

        input.addEventListener('change', toggleAgencyFields);

    });


    // Initialisation au chargement

    toggleAgencyFields();

</script>

</body>

</html>