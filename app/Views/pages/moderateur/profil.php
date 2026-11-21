
<?php

use Core\Session;

/*
 * =========================================================
 * PROFIL MODÉRATEUR — IMMO-SN
 * Design : Bleu nuit / Or / Ivoire
 * =========================================================
 * Données transmises par ModerateurController.
 */

$profil = $profil ?? [];

$prenom = $profil['prenom'] ?? '';
$nom = $profil['nom'] ?? '';
$email = $profil['email'] ?? '';
$telephone = $profil['telephone'] ?? '';
$ville = $profil['ville'] ?? '';
$quartier = $profil['quartier'] ?? '';
$bio = $profil['bio'] ?? '';
$photoProfil = $profil['photo_profil'] ?? '';
$role = $profil['role'] ?? 'moderateur';
$status = $profil['status'] ?? 'inconnu';

$initiales = strtoupper(
    substr($prenom ?: 'M', 0, 1)
    . substr($nom ?: 'N', 0, 1)
);

$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$dateCreation = !empty($profil['created_at'])
    ? date('d/m/Y', strtotime($profil['created_at']))
    : 'Non disponible';

$derniereConnexion = !empty($profil['last_login'])
    ? date('d/m/Y à H:i', strtotime($profil['last_login']))
    : 'Aucune connexion enregistrée';

$statutActif = strtolower((string) $status) === 'actif';

?>

<div class="min-h-screen bg-[#F5F3ED] px-3 py-6 text-[#071B36] sm:px-6 sm:py-8 lg:px-8">

    <div class="mx-auto max-w-7xl space-y-8">

        <!-- =====================================================
             EN-TÊTE
        ====================================================== -->

        <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-[#71869A]">
                    <span>IMMO-SN</span>
                    <span>/</span>
                    <span>Modération</span>
                    <span>/</span>
                    <span class="font-bold text-[#A98200]">Mon profil</span>
                </div>

                <h1 class="mt-2 break-words text-2xl font-black tracking-tight text-[#071B36] sm:text-3xl">
                    Mon profil
                </h1>

                <p class="mt-2 break-words text-sm text-[#71869A]">
                    Consultez les informations de votre compte et gérez votre sécurité.
                </p>

            </div>

            <a
                href="/immo-sn/logout"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#E8E1D3] bg-white px-5 py-3 text-sm font-semibold text-[#071B36] shadow-sm transition duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-700 sm:w-auto"
            >

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 12H3m0 0 4-4m-4 4 4 4m5-12h6a2 2 0 012 2v12a2 2 0 01-2 2h-6"/>

                </svg>

                Déconnexion

            </a>

        </header>


        <!-- =====================================================
             CARTE IDENTITÉ
        ====================================================== -->

        <section class="relative isolate overflow-hidden rounded-[28px] bg-[#071B36] p-5 text-white shadow-[0_20px_55px_rgba(7,27,54,0.16)] sm:p-8">

            <!-- Décorations -->

            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#F5B800]/15 blur-3xl"></div>

            <div class="pointer-events-none absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-[#71869A]/15 blur-3xl"></div>

            <div class="pointer-events-none absolute bottom-0 left-0 h-1 w-40 bg-gradient-to-r from-[#F5B800] to-transparent"></div>

            <div class="pointer-events-none absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-white/[0.035] to-transparent"></div>


            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center">

                <!-- Avatar -->

                <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-full border-4 border-[#F5B800]/40 bg-[#F5B800] text-3xl font-black text-[#071B36] shadow-xl">

                    <?php if ($photoProfil !== ''): ?>

                        <img
                            src="<?= $e($photoProfil) ?>"
                            alt="Photo de profil"
                            class="h-full w-full object-cover"
                        >

                    <?php else: ?>

                        <?= $e($initiales) ?>

                    <?php endif; ?>

                </div>

                <!-- Identité -->

                <div class="min-w-0 flex-1">

                    <div class="flex flex-wrap items-center gap-3">

                        <h2 class="min-w-0 break-words text-xl font-black sm:text-3xl">
                            <?= $e(trim($prenom . ' ' . $nom)) ?>
                        </h2>

                        <?php if ($statutActif): ?>

                            <span class="rounded-full border border-emerald-300/30 bg-emerald-400/15 px-3 py-1 text-xs font-semibold text-emerald-200">

                                <span class="mr-1 inline-block h-1.5 w-1.5 rounded-full bg-emerald-400"></span>

                                Compte actif

                            </span>

                        <?php else: ?>

                            <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-white/80">
                                <?= $e(ucfirst($status)) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <p class="mt-2 break-all text-sm text-white/65">
                        <?= $e($email) ?>
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2">

                        <span class="rounded-lg border border-[#F5B800]/25 bg-[#F5B800]/10 px-3 py-2 text-xs font-semibold text-[#F8D76A]">
                            Modérateur
                        </span>

                        <span class="rounded-lg border border-white/10 bg-white/[0.07] px-3 py-2 text-xs text-white/75">
                            Membre depuis <?= $e($dateCreation) ?>
                        </span>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             MESSAGES DE NOTIFICATION
        ====================================================== -->

        <?php if (!empty($success)): ?>

            <div class="flex items-start gap-3 break-words rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800">

                <span class="mt-0.5 text-lg">✓</span>

                <p>
                    <?= $e($success) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (!empty($error)): ?>

            <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">

                <span class="mt-0.5 text-lg">!</span>

                <p>
                    <?= $e($error) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (!empty($passwordSuccess)): ?>

            <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800">

                <span class="mt-0.5 text-lg">✓</span>

                <p>
                    <?= $e($passwordSuccess) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (!empty($passwordError)): ?>

            <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">

                <span class="mt-0.5 text-lg">!</span>

                <p>
                    <?= $e($passwordError) ?>
                </p>

            </div>

        <?php endif; ?>


        <!-- =====================================================
             INFORMATIONS PERSONNELLES
        ====================================================== -->

        <section class="overflow-hidden rounded-[24px] border border-[#E8E1D3] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] sm:p-8">

            <!-- Titre -->

            <div class="mb-7">

                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF7DF] text-[#8A6500]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-11a4 4 0 110-8 4 4 0 010 8zM9 11a4 4 0 100-8 4 4 0 000 8z"/>

                    </svg>

                </div>

                <h3 class="break-words text-xl font-black text-[#071B36]">
                    Informations personnelles
                </h3>

                <p class="mt-1 break-words text-sm text-[#71869A]">
                    Modifiez vos coordonnées et les informations de votre profil.
                </p>

            </div>


            <form
                action="/immo-sn/moderateur/profil/modifier"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-7"
            >

                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= $e(Session::csrfToken()) ?>"
                >


                <!-- PHOTO DE PROFIL -->

                <div class="flex flex-col gap-5 border-b border-[#EEE9DE] pb-7 sm:flex-row sm:items-center">

                    <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-full border-4 border-[#FFF7DF] bg-[#071B36] text-2xl font-black text-[#F5B800]">

                        <?php if ($photoProfil !== ''): ?>

                            <img
                                src="<?= $e($photoProfil) ?>"
                                alt="Photo de profil"
                                class="h-full w-full object-cover"
                            >

                        <?php else: ?>

                            <?= $e($initiales) ?>

                        <?php endif; ?>

                    </div>


                    <div class="min-w-0 flex-1">

                        <label for="photo_profil" class="mb-2 block text-sm font-bold text-[#071B36]">
                            Photo de profil
                        </label>

                        <input
                            type="file"
                            id="photo_profil"
                            name="photo_profil"
                            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            class="block w-full cursor-pointer rounded-xl border border-[#E5DED0] bg-[#FAFAF8] text-sm text-[#071B36] file:mr-4 file:border-0 file:bg-[#071B36] file:px-4 file:py-3 file:text-sm file:font-semibold file:text-[#F5B800] hover:file:bg-[#263B5C]"
                        >

                        <p class="mt-2 text-xs text-[#71869A]">
                            Formats acceptés : JPG et PNG. Taille maximale : 5 Mo.
                        </p>

                    </div>

                </div>


                <!-- IDENTITÉ -->

                <div>

                    <h4 class="mb-4 text-sm font-bold text-[#071B36]">
                        Identité
                    </h4>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>

                            <label for="prenom" class="mb-2 block text-sm font-semibold text-[#071B36]">
                                Prénom <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                value="<?= $e($prenom) ?>"
                                maxlength="100"
                                required
                                class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                            >

                        </div>


                        <div>

                            <label for="nom" class="mb-2 block text-sm font-semibold text-[#071B36]">
                                Nom <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="<?= $e($nom) ?>"
                                maxlength="100"
                                required
                                class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                            >

                        </div>

                    </div>

                </div>


                <!-- CONTACT -->

                <div>

                    <h4 class="mb-4 text-sm font-bold text-[#071B36]">
                        Coordonnées
                    </h4>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>

                            <label for="email" class="mb-2 block text-sm font-semibold text-[#071B36]">
                                Adresse e-mail <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= $e($email) ?>"
                                maxlength="254"
                                required
                                class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                            >

                        </div>


                        <div>

                            <label for="telephone" class="mb-2 block text-sm font-semibold text-[#071B36]">
                                Téléphone
                            </label>

                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                value="<?= $e($telephone) ?>"
                                maxlength="30"
                                class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                                placeholder="Ex. : 77 000 00 00"
                            >

                        </div>

                    </div>

                </div>


                <!-- LOCALISATION -->

                <div>

                    <h4 class="mb-4 text-sm font-bold text-[#071B36]">
                        Localisation
                    </h4>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>

                            <label for="ville" class="mb-2 block text-sm font-semibold text-[#071B36]">
                                Ville
                            </label>

                            <input
                                type="text"
                                id="ville"
                                name="ville"
                                value="<?= $e($ville) ?>"
                                maxlength="100"
                                class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                                placeholder="Votre ville"
                            >

                        </div>


                        <div>

                            <label for="quartier" class="mb-2 block text-sm font-semibold text-[#071B36]">
                                Quartier
                            </label>

                            <input
                                type="text"
                                id="quartier"
                                name="quartier"
                                value="<?= $e($quartier) ?>"
                                maxlength="100"
                                class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                                placeholder="Votre quartier"
                            >

                        </div>

                    </div>

                </div>


                <!-- BIOGRAPHIE -->

                <div>

                    <label for="bio" class="mb-2 block text-sm font-semibold text-[#071B36]">
                        À propos de moi
                    </label>

                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        maxlength="1000"
                        class="w-full resize-y rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm leading-6 text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                        placeholder="Présentez-vous en quelques lignes..."
                    ><?= $e($bio) ?></textarea>

                    <p class="mt-2 text-xs text-[#71869A]">
                        1000 caractères maximum.
                    </p>

                </div>


                <!-- ACTIONS -->

                <div class="flex flex-col gap-3 border-t border-[#EEE9DE] pt-6 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-xs text-[#71869A]">
                        Les champs marqués d'un astérisque sont obligatoires.
                    </p>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-6 py-3 text-sm font-extrabold text-[#071B36] shadow-lg shadow-[#F5B800]/15 transition duration-200 hover:-translate-y-0.5 hover:bg-[#FFD044] sm:w-auto"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"/>

                        </svg>

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </section>


        <!-- =====================================================
             INFORMATIONS DU COMPTE
        ====================================================== -->

        <section class="rounded-[24px] border border-[#E8E1D3] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] sm:p-8">

            <div class="mb-7">

                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#EEF1F5] text-[#071B36]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>

                    </svg>

                </div>

                <h3 class="break-words text-xl font-black text-[#071B36]">
                    Informations du compte
                </h3>

                <p class="mt-1 break-words text-sm text-[#71869A]">
                    Détails administratifs de votre compte.
                </p>

            </div>


            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                <!-- Fonction -->

                <div class="rounded-xl border border-[#E8E1D3] bg-[#FAFAF8] p-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Fonction
                    </p>

                    <p class="mt-3 font-bold text-[#071B36]">
                        <?= $e(ucfirst($role)) ?>
                    </p>

                </div>


                <!-- Statut -->

                <div class="rounded-xl border border-[#E8E1D3] bg-[#FAFAF8] p-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Statut
                    </p>

                    <p class="mt-3">

                        <?php if ($statutActif): ?>

                            <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-sm font-bold text-green-700">

                                <span class="h-2 w-2 rounded-full bg-green-500"></span>

                                <?= $e(ucfirst($status)) ?>

                            </span>

                        <?php else: ?>

                            <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-sm font-bold text-gray-700">

                                <span class="h-2 w-2 rounded-full bg-gray-400"></span>

                                <?= $e(ucfirst($status)) ?>

                            </span>

                        <?php endif; ?>

                    </p>

                </div>


                <!-- Vérification -->

                <div class="rounded-xl border border-[#E8E1D3] bg-[#FAFAF8] p-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Adresse vérifiée
                    </p>

                    <p class="mt-3 font-bold text-[#071B36]">

                        <?= !empty($profil['is_verified']) ? 'Oui' : 'Non' ?>

                    </p>

                </div>


                <!-- Date création -->

                <div class="rounded-xl border border-[#E8E1D3] bg-[#FAFAF8] p-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Date de création
                    </p>

                    <p class="mt-3 font-bold text-[#071B36]">
                        <?= $e($dateCreation) ?>
                    </p>

                </div>


                <!-- Dernière connexion -->

                <div class="rounded-xl border border-[#E8E1D3] bg-[#FAFAF8] p-5 sm:col-span-2">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Dernière connexion
                    </p>

                    <p class="mt-3 font-bold text-[#071B36]">
                        <?= $e($derniereConnexion) ?>
                    </p>

                </div>

            </div>

        </section>


        <!-- =====================================================
             SÉCURITÉ DU COMPTE
        ====================================================== -->

        <section class="rounded-[24px] border border-[#E8E1D3] bg-white p-5 shadow-[0_5px_24px_rgba(7,27,54,0.035)] sm:p-8">

            <div class="mb-7">

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF7DF] text-[#8A6500]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>

                    </svg>

                </div>

                <h3 class="mt-4 break-words text-xl font-black text-[#071B36]">
                    Sécurité du compte
                </h3>

                <p class="mt-1 text-sm text-[#71869A]">
                    Modifiez régulièrement votre mot de passe pour protéger votre compte.
                </p>

            </div>


            <form
                action="/immo-sn/moderateur/profil/mot-de-passe"
                method="POST"
                class="space-y-5"
            >

                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= $e(Session::csrfToken()) ?>"
                >


                <!-- ANCIEN MOT DE PASSE -->

                <div>

                    <label for="ancien_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#071B36]">
                        Ancien mot de passe
                    </label>

                    <input
                        type="password"
                        id="ancien_mot_de_passe"
                        name="ancien_mot_de_passe"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                        placeholder="Saisissez votre ancien mot de passe"
                    >

                </div>


                <!-- NOUVEAU MOT DE PASSE -->

                <div class="grid gap-5 sm:grid-cols-2">

                    <div>

                        <label for="nouveau_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#071B36]">
                            Nouveau mot de passe
                        </label>

                        <input
                            type="password"
                            id="nouveau_mot_de_passe"
                            name="nouveau_mot_de_passe"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                            placeholder="8 caractères minimum"
                        >

                    </div>


                    <div>

                        <label for="confirmation_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#071B36]">
                            Confirmer le nouveau mot de passe
                        </label>

                        <input
                            type="password"
                            id="confirmation_mot_de_passe"
                            name="confirmation_mot_de_passe"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-[#E5DED0] bg-[#FAFAF8] px-4 py-3 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                            placeholder="Confirmez le mot de passe"
                        >

                    </div>

                </div>


                <!-- ACTIONS -->

                <div class="flex flex-col gap-3 border-t border-[#EEE9DE] pt-6 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-xs text-[#71869A]">
                        Utilisez au moins 8 caractères.
                    </p>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#071B36] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#071B36]/10 transition duration-200 hover:-translate-y-0.5 hover:bg-[#263B5C] sm:w-auto"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>

                        </svg>

                        Modifier mon mot de passe

                    </button>

                </div>

            </form>

        </section>


        <!-- =====================================================
             PIED DE PAGE
        ====================================================== -->

        <footer class="border-t border-[#E8E1D3] pt-5">

            <div class="flex flex-col items-center justify-between gap-2 text-center text-xs text-[#71869A] sm:flex-row sm:text-left">

                <p>
                    <span class="font-black tracking-wide text-[#071B36]">IMMO-SN</span>
                    <span class="mx-1">·</span>
                    Profil modérateur
                </p>

                <p>
                    Gestion du compte et sécurité
                </p>

            </div>

        </footer>

    </div>

</div>
