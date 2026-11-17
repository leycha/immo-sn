
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
            height: 46px;
            border: 1px solid #e8e2da;
            border-radius: 8px;
            padding: 0 13px;
            background: #fff;
            color: #30221b;
            font-size: 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .field:focus {
            border-color: #b58a45;
            box-shadow: 0 0 0 3px rgba(181,138,69,.10);
        }

        textarea.field {
            height: auto;
            min-height: 76px;
            padding-top: 12px;
            padding-bottom: 12px;
            resize: vertical;
        }

        .role-choice input:checked + div {
            border-color: #a98260;
            background: #f8f4ee;
        }

        .role-choice input:checked + div .role-dot {
            background: #a98260;
            border-color: #a98260;
            box-shadow: inset 0 0 0 3px white;
        }
    </style>

</head>

<body class="min-h-screen overflow-x-hidden bg-[#f7f6f3] text-[#30221b]">

<div class="grid min-h-screen min-w-0 lg:grid-cols-[42%_58%]">

    <!-- =====================================================
         IDENTITE VISUELLE
    ====================================================== -->

    <aside
        class="relative hidden min-h-screen min-w-0 overflow-hidden
               bg-[#292019] p-8 text-white
               lg:sticky lg:top-0 lg:flex lg:h-screen
               lg:flex-col lg:justify-between xl:p-12"
    >

        <div class="absolute -bottom-24 -right-32 h-[480px] w-[480px] rounded-full border border-[#c5a36a]/20"></div>

        <div class="absolute -bottom-12 -right-20 h-[360px] w-[360px] rounded-full border border-[#c5a36a]/20"></div>


        <!-- Logo -->

        <a href="/immo-sn/" class="relative z-10 flex min-w-0 items-center gap-3">

            <div
                class="flex h-11 w-11 shrink-0 items-center justify-center
                       bg-[#c5a36a] text-xl font-black text-[#292019]"
            >
                I
            </div>

            <span class="break-words text-2xl font-bold tracking-tight">
                IMMO<span class="text-[#c5a36a]">SN</span>
            </span>

        </a>


        <!-- Présentation -->

        <div class="relative z-10 min-w-0 max-w-lg">

            <div class="mb-6 h-px w-14 bg-[#c5a36a]"></div>

            <p class="mb-5 text-xs font-semibold uppercase tracking-[.3em] text-[#c5a36a]">
                Immobilier • Sénégal
            </p>

            <h1 class="break-words text-4xl font-semibold leading-tight tracking-tight xl:text-6xl">
                L'immobilier
                <br>
                prend une
                <br>
                <span class="font-light italic text-[#c5a36a]">
                    nouvelle dimension.
                </span>
            </h1>

            <p class="mt-7 max-w-sm break-words text-sm leading-7 text-white/55">
                Une plateforme pensée pour connecter les projets immobiliers.
            </p>

        </div>


        <!-- Pied de page -->

        <div class="relative z-10 flex flex-wrap justify-between gap-3 border-t border-white/10 pt-5 text-xs text-white/40">

            <span>IMMO-SN</span>

            <span>© <?= date('Y') ?></span>

        </div>

    </aside>


    <!-- =====================================================
         FORMULAIRE
    ====================================================== -->

    <main
        class="flex min-h-screen min-w-0 items-start justify-center
               px-4 py-7
               sm:px-8 sm:py-10
               lg:h-screen lg:overflow-y-auto lg:px-10
               xl:px-14"
    >

        <div class="w-full min-w-0 max-w-[590px]">


            <!-- LOGO MOBILE -->

            <a
                href="/immo-sn/"
                class="mb-8 flex min-w-0 items-center gap-2 lg:hidden"
            >

                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center
                           bg-[#292019] font-black text-[#c5a36a]"
                >
                    I
                </div>

                <span class="break-words text-xl font-bold">
                    IMMO<span class="text-[#a98260]">SN</span>
                </span>

            </a>


            <!-- TITRE -->

            <div class="mb-6 flex min-w-0 items-end justify-between gap-3">

                <div class="min-w-0">

                    <p class="mb-2 text-[11px] font-bold uppercase tracking-[.22em] text-[#a98260]">
                        Espace client
                    </p>

                    <h2 class="break-words text-2xl font-semibold tracking-tight sm:text-4xl">
                        Créer un compte
                    </h2>

                </div>

                <span class="hidden shrink-0 text-xs text-[#a49a90] sm:block">
                    ÉTAPE 01 / 01
                </span>

            </div>


            <!-- MESSAGE ERREUR -->

            <?php if (!empty($error)): ?>

                <div
                    class="mb-4 min-w-0 rounded-lg border border-red-200
                           bg-red-50 px-4 py-3 text-sm text-red-700"
                >

                    <p class="break-words [overflow-wrap:anywhere]">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>

                </div>

            <?php endif; ?>


            <!-- MESSAGE SUCCÈS -->

            <?php if (!empty($success)): ?>

                <div
                    class="mb-4 min-w-0 rounded-lg border border-green-200
                           bg-green-50 px-4 py-3 text-sm text-green-700"
                >

                    <p class="break-words [overflow-wrap:anywhere]">
                        <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                    </p>

                </div>

            <?php endif; ?>


            <!-- FORMULAIRE -->

            <form
                method="POST"
                action="/immo-sn/inscription"
                enctype="multipart/form-data"
                class="min-w-0 space-y-5"
            >


                <!-- PROFIL -->

                <div class="min-w-0">

                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-[#75665c]">
                        Vous êtes
                    </label>

                    <div class="grid grid-cols-2 gap-2 sm:gap-3">


                        <!-- PARTICULIER -->

                        <label class="role-choice min-w-0 cursor-pointer">

                            <input
                                type="radio"
                                name="role"
                                value="particulier"
                                <?= $role === 'particulier' ? 'checked' : '' ?>
                                class="sr-only"
                            >

                            <div class="flex h-12 min-w-0 items-center justify-between gap-2 rounded-lg border border-[#e8e2da] bg-white px-2 transition sm:px-4">

                                <span class="break-words text-xs font-semibold sm:text-sm">
                                    Particulier
                                </span>

                                <span class="role-dot h-4 w-4 shrink-0 rounded-full border border-[#d5ccc1]"></span>

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

                            <div class="flex h-12 min-w-0 items-center justify-between gap-2 rounded-lg border border-[#e8e2da] bg-white px-2 transition sm:px-4">

                                <span class="break-words text-xs font-semibold sm:text-sm">
                                    Agence
                                </span>

                                <span class="role-dot h-4 w-4 shrink-0 rounded-full border border-[#d5ccc1]"></span>

                            </div>

                        </label>

                    </div>

                </div>


                <!-- INFORMATIONS PERSONNELLES -->

                <div class="min-w-0">

                    <div class="mb-3 flex min-w-0 items-center gap-3">

                        <span class="shrink-0 text-[10px] font-bold uppercase tracking-wider text-[#a98260] sm:text-[11px]">
                            Informations personnelles
                        </span>

                        <div class="h-px min-w-0 flex-1 bg-[#e9e2d9]"></div>

                    </div>


                    <!-- Nom et prénom -->

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                        <div class="min-w-0">

                            <label for="nom" class="mb-1.5 block text-xs font-semibold">
                                Nom *
                            </label>

                            <input
                                class="field"
                                type="text"
                                id="nom"
                                name="nom"
                                value="<?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Nom"
                                autocomplete="family-name"
                                required
                            >

                        </div>


                        <div class="min-w-0">

                            <label for="prenom" class="mb-1.5 block text-xs font-semibold">
                                Prénom *
                            </label>

                            <input
                                class="field"
                                type="text"
                                id="prenom"
                                name="prenom"
                                value="<?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Prénom"
                                autocomplete="given-name"
                                required
                            >

                        </div>

                    </div>


                    <!-- Email -->

                    <div class="mt-3 min-w-0">

                        <label for="email" class="mb-1.5 block text-xs font-semibold">
                            Adresse e-mail *
                        </label>

                        <input
                            class="field"
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="nom@exemple.com"
                            autocomplete="email"
                            required
                        >

                    </div>


                    <!-- Téléphone et ville -->

                    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">

                        <div class="min-w-0">

                            <label for="telephone" class="mb-1.5 block text-xs font-semibold">
                                Téléphone
                            </label>

                            <input
                                class="field"
                                type="tel"
                                id="telephone"
                                name="telephone"
                                value="<?= htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="77 000 00 00"
                                autocomplete="tel"
                            >

                        </div>


                        <div class="min-w-0">

                            <label for="ville" class="mb-1.5 block text-xs font-semibold">
                                Ville
                            </label>

                            <input
                                class="field"
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


                <!-- AGENCE -->

                <div
                    id="agencyFields"
                    class="<?= $role === 'agence' ? '' : 'hidden' ?>
                           min-w-0 space-y-3 rounded-lg border
                           border-[#e8e2da] bg-[#fbfaf8] p-3 sm:p-4"
                >

                    <p class="break-words text-xs font-bold uppercase tracking-wider text-[#a98260]">
                        Informations professionnelles
                    </p>


                    <!-- Nom agence -->

                    <div class="min-w-0">

                        <label for="nom_agence" class="mb-1.5 block text-xs font-semibold">
                            Nom de l'agence *
                        </label>

                        <input
                            class="field"
                            type="text"
                            id="nom_agence"
                            name="nom_agence"
                            value="<?= htmlspecialchars($nomAgence, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Nom de l'agence"
                        >

                    </div>


                    <!-- Adresse agence -->

                    <div class="min-w-0">

                        <label for="adresse_agence" class="mb-1.5 block text-xs font-semibold">
                            Adresse *
                        </label>

                        <textarea
                            class="field"
                            id="adresse_agence"
                            name="adresse_agence"
                            rows="2"
                            placeholder="Adresse de l'agence"
                        ><?= htmlspecialchars($adresseAgence, ENT_QUOTES, 'UTF-8') ?></textarea>

                    </div>


                    <!-- NINEA -->

                    <div class="min-w-0">

                        <label for="ninea" class="mb-1.5 block text-xs font-semibold">
                            NINEA *
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


                    <!-- Email professionnel -->

                    <div class="min-w-0">

                        <label for="email_agence" class="mb-1.5 block text-xs font-semibold">
                            E-mail professionnel *
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


                    <!-- Téléphone professionnel -->

                    <div class="min-w-0">

                        <label for="telephone_agence" class="mb-1.5 block text-xs font-semibold">
                            Téléphone professionnel *
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


                    <!-- Logo -->

                    <div class="min-w-0">

                        <label for="logo_agence" class="mb-1.5 block text-xs font-semibold">
                            Logo de l'agence
                        </label>

                        <input
                            class="block w-full min-w-0 rounded-lg border border-[#e8e2da]
                                   bg-white p-2.5 text-sm text-[#75665c]
                                   file:mr-2 file:max-w-full file:rounded-md
                                   file:border-0 file:bg-[#f8f4ee]
                                   file:px-2 file:py-2 file:text-xs
                                   file:font-semibold file:text-[#30221b]
                                   sm:file:mr-3 sm:file:px-3"
                            type="file"
                            id="logo_agence"
                            name="logo_agence"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <p class="mt-1 break-words text-xs text-[#8a7761]">
                            Formats acceptés : JPG, PNG ou WebP. Taille maximale : 2 Mo.
                        </p>

                    </div>


                    <!-- Description -->

                    <div class="min-w-0">

                        <label for="description_agence" class="mb-1.5 block text-xs font-semibold">
                            Description de l'agence
                        </label>

                        <textarea
                            class="field"
                            id="description_agence"
                            name="description_agence"
                            rows="3"
                            placeholder="Présentez brièvement votre agence..."
                        ><?= htmlspecialchars($descriptionAgence, ENT_QUOTES, 'UTF-8') ?></textarea>

                    </div>


                    <p class="break-words text-xs text-[#8a7761]">
                        L'agence sera visible après validation administrative.
                    </p>

                </div>


                <!-- MOT DE PASSE -->

                <div class="min-w-0">

                    <div class="mb-3 flex min-w-0 items-center gap-3">

                        <span class="shrink-0 text-[11px] font-bold uppercase tracking-wider text-[#a98260]">
                            Sécurité
                        </span>

                        <div class="h-px min-w-0 flex-1 bg-[#e9e2d9]"></div>

                    </div>


                    <!-- Mot de passe -->

                    <div class="min-w-0">

                        <label for="password" class="mb-1.5 block text-xs font-semibold">
                            Mot de passe *
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
                                       -translate-y-1/2 text-xs
                                       font-bold text-[#a98260]"
                            >
                                Voir
                            </button>

                        </div>

                    </div>


                    <!-- Confirmation -->

                    <div class="mt-3 min-w-0">

                        <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold">
                            Confirmation du mot de passe *
                        </label>

                        <input
                            class="field"
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirmer le mot de passe"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                </div>


                <!-- BOUTON INSCRIPTION -->

                <button
                    type="submit"
                    class="flex min-h-12 w-full items-center justify-center
                           gap-2 rounded-lg bg-[#292019] px-4 py-3
                           text-sm font-bold text-white transition
                           hover:bg-[#493326]"
                >
                    Créer mon compte

                    <span class="shrink-0 text-[#c5a36a]">
                        →
                    </span>

                </button>

            </form>


            <!-- LIENS -->

            <div class="mt-5 flex flex-col items-start justify-between gap-3 text-sm sm:flex-row sm:flex-wrap sm:items-center">

                <p class="break-words text-[#8b8178]">

                    Déjà inscrit ?

                    <a
                        href="/immo-sn/login"
                        class="font-bold text-[#8b6841] hover:text-[#30221b]"
                    >
                        Se connecter
                    </a>

                </p>

                <a
                    href="/immo-sn/"
                    class="break-words text-xs font-semibold text-[#a98260]
                           hover:text-[#30221b]"
                >
                    Retour à l'accueil
                </a>

            </div>

        </div>

    </main>

</div>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script>

    // Affichage du mot de passe

    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    if (passwordInput && togglePassword) {

        togglePassword.addEventListener('click', function () {

            const visible = passwordInput.type === 'password';

            passwordInput.type = visible ? 'text' : 'password';

            this.textContent = visible ? 'Masquer' : 'Voir';

        });

    }


    // Affichage conditionnel des champs agence

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

    toggleAgencyFields();

</script>

</body>

</html>
