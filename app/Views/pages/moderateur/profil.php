<?php

use Core\Session;

/*
 * =========================================================
 * PROFIL MODÉRATEUR — IMMO-SN
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

<div class="min-h-screen bg-[#F5F3ED] px-3 py-6 text-[#101D33] sm:px-6 sm:py-8 lg:px-8">

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
                    <span class="text-[#7E9833]">Mon profil</span>
                </div>

                <h1 class="mt-2 break-words text-2xl font-black tracking-tight text-[#101D33] sm:text-3xl">
                    Mon profil
                </h1>

                <p class="mt-2 break-words text-sm text-[#71869A]">
                    Consultez les informations de votre compte et gérez votre sécurité.
                </p>

            </div>

            <a
                href="/immo-sn/logout"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#E5E7DE] bg-white px-5 py-3 text-sm font-semibold text-[#101D33] shadow-sm transition hover:border-red-200 hover:bg-red-50 hover:text-red-700 sm:w-auto"
            >
                <span>↪</span>
                Déconnexion
            </a>

        </header>


        <!-- =====================================================
             CARTE IDENTITÉ
        ====================================================== -->

        <section class="relative overflow-hidden rounded-[28px] bg-[#101D33] p-5 text-white shadow-[0_18px_50px_rgba(16,29,51,0.12)] sm:p-8">

            <!-- Décoration -->

            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#7E9833]/20 blur-3xl"></div>

            <div class="pointer-events-none absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-[#71869A]/15 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center">

                <!-- Avatar -->

                <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-full border-4 border-white/20 bg-[#7E9833] text-3xl font-black text-white shadow-xl">

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

                        <span class="rounded-lg border border-[#7E9833]/30 bg-[#7E9833]/20 px-3 py-2 text-xs font-semibold text-[#C4DB8A]">
                            Modérateur
                        </span>

                        <span class="rounded-lg bg-white/10 px-3 py-2 text-xs text-white/75">
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

        <section class="overflow-hidden rounded-[24px] border border-[#E5E7DE] bg-white p-5 shadow-[0_5px_24px_rgba(16,29,51,0.035)] sm:p-8">

            <!-- Titre -->

            <div class="mb-7">

                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#F1F6E8] text-[#7E9833]">

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

                <h3 class="break-words text-xl font-black text-[#101D33]">
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

                <div class="flex flex-col gap-5 border-b border-[#E5E7DE] pb-7 sm:flex-row sm:items-center">

                    <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-full border-4 border-[#F1F6E8] bg-[#101D33] text-2xl font-black text-white">

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

                        <label for="photo_profil" class="mb-2 block text-sm font-bold text-[#101D33]">
                            Photo de profil
                        </label>

                        <input
                            type="file"
                            id="photo_profil"
                            name="photo_profil"
                            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            class="block w-full cursor-pointer rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] text-sm text-[#101D33] file:mr-4 file:border-0 file:bg-[#101D33] file:px-4 file:py-3 file:text-sm file:font-semibold file:text-white hover:file:bg-[#263B5C]"
                        >

                        <p class="mt-2 text-xs text-[#71869A]">
                            Formats acceptés : JPG et PNG. Taille maximale : 5 Mo.
                        </p>

                    </div>

                </div>


                <!-- IDENTITÉ -->

                <div>

                    <h4 class="mb-4 text-sm font-bold text-[#101D33]">
                        Identité
                    </h4>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>

                            <label for="prenom" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Prénom <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                value="<?= $e($prenom) ?>"
                                maxlength="100"
                                required
                                class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                            >

                        </div>


                        <div>

                            <label for="nom" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Nom <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="<?= $e($nom) ?>"
                                maxlength="100"
                                required
                                class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                            >

                        </div>

                    </div>

                </div>


                <!-- CONTACT -->

                <div>

                    <h4 class="mb-4 text-sm font-bold text-[#101D33]">
                        Coordonnées
                    </h4>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>

                            <label for="email" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Adresse e-mail <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= $e($email) ?>"
                                maxlength="254"
                                required
                                class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                            >

                        </div>


                        <div>

                            <label for="telephone" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Téléphone
                            </label>

                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                value="<?= $e($telephone) ?>"
                                maxlength="30"
                                class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                                placeholder="Ex. : 77 000 00 00"
                            >

                        </div>

                    </div>

                </div>


                <!-- LOCALISATION -->

                <div>

                    <h4 class="mb-4 text-sm font-bold text-[#101D33]">
                        Localisation
                    </h4>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>

                            <label for="ville" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Ville
                            </label>

                            <input
                                type="text"
                                id="ville"
                                name="ville"
                                value="<?= $e($ville) ?>"
                                maxlength="100"
                                class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                                placeholder="Votre ville"
                            >

                        </div>


                        <div>

                            <label for="quartier" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Quartier
                            </label>

                            <input
                                type="text"
                                id="quartier"
                                name="quartier"
                                value="<?= $e($quartier) ?>"
                                maxlength="100"
                                class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                                placeholder="Votre quartier"
                            >

                        </div>

                    </div>

                </div>


                <!-- BIOGRAPHIE -->

                <div>

                    <label for="bio" class="mb-2 block text-sm font-semibold text-[#101D33]">
                        À propos de moi
                    </label>

                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        maxlength="1000"
                        class="w-full resize-y rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm leading-6 text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                        placeholder="Présentez-vous en quelques lignes..."
                    ><?= $e($bio) ?></textarea>

                    <p class="mt-2 text-xs text-[#71869A]">
                        1000 caractères maximum.
                    </p>

                </div>


                <!-- ACTIONS -->

                <div class="flex flex-col gap-3 border-t border-[#E5E7DE] pt-6 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-xs text-[#71869A]">
                        Les champs marqués d'un astérisque sont obligatoires.
                    </p>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#7E9833]/15 transition hover:-translate-y-0.5 hover:bg-[#6B822B] sm:w-auto"
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

        <section class="rounded-[24px] border border-[#E5E7DE] bg-white p-5 shadow-[0_5px_24px_rgba(16,29,51,0.035)] sm:p-8">

            <div class="mb-7">

                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#EEF1F5] text-[#71869A]">

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

                <h3 class="break-words text-xl font-black text-[#101D33]">
                    Informations du compte
                </h3>

                <p class="mt-1 break-words text-sm text-[#71869A]">
                    Détails administratifs de votre compte.
                </p>

            </div>


            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                <!-- Fonction -->

                <div class="rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] p-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Fonction
                    </p>

                    <p class="mt-3 font-bold text-[#101D33]">
                        <?= $e(ucfirst($role)) ?>
                    </p>

                </div>


                <!-- Statut -->

                <div class="rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] p-5">

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

                <div class="rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] p-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Adresse vérifiée
                    </p>

                    <p class="mt-3 font-bold text-[#101D33]">

                        <?= !empty($profil['is_verified']) ? 'Oui' : 'Non' ?>

                    </p>

                </div>


                <!-- Date création -->

                <div class="rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] p-5">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Date de création
                    </p>

                    <p class="mt-3 font-bold text-[#101D33]">
                        <?= $e($dateCreation) ?>
                    </p>

                </div>


                <!-- Dernière connexion -->

                <div class="rounded-xl border border-[#E5E7DE] bg-[#FAFBF8] p-5 sm:col-span-2">

                    <p class="text-xs font-bold uppercase tracking-wider text-[#71869A]">
                        Dernière connexion
                    </p>

                    <p class="mt-3 font-bold text-[#101D33]">
                        <?= $e($derniereConnexion) ?>
                    </p>

                </div>

            </div>

        </section>


        <!-- =====================================================
             SÉCURITÉ DU COMPTE
        ====================================================== -->

        <section class="rounded-[24px] border border-[#E5E7DE] bg-white p-5 shadow-[0_5px_24px_rgba(16,29,51,0.035)] sm:p-8">

            <div class="mb-7">

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#F1F6E8] text-[#7E9833]">

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

                <h3 class="mt-4 break-words text-xl font-black text-[#101D33]">
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

                    <label for="ancien_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#101D33]">
                        Ancien mot de passe
                    </label>

                    <input
                        type="password"
                        id="ancien_mot_de_passe"
                        name="ancien_mot_de_passe"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                        placeholder="Saisissez votre ancien mot de passe"
                    >

                </div>


                <!-- NOUVEAU MOT DE PASSE -->

                <div class="grid gap-5 sm:grid-cols-2">

                    <div>

                        <label for="nouveau_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#101D33]">
                            Nouveau mot de passe
                        </label>

                        <input
                            type="password"
                            id="nouveau_mot_de_passe"
                            name="nouveau_mot_de_passe"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                            placeholder="8 caractères minimum"
                        >

                    </div>


                    <div>

                        <label for="confirmation_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#101D33]">
                            Confirmer le nouveau mot de passe
                        </label>

                        <input
                            type="password"
                            id="confirmation_mot_de_passe"
                            name="confirmation_mot_de_passe"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-[#DDE3D7] bg-[#F8F8F3] px-4 py-3 text-sm text-[#101D33] outline-none transition placeholder:text-[#A5B0B8] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                            placeholder="Confirmez le mot de passe"
                        >

                    </div>

                </div>


                <!-- ACTIONS -->

                <div class="flex flex-col gap-3 border-t border-[#E5E7DE] pt-6 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-xs text-[#71869A]">
                        Utilisez au moins 8 caractères.
                    </p>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#101D33] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#101D33]/10 transition hover:-translate-y-0.5 hover:bg-[#263B5C] sm:w-auto"
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

    </div>

</div>