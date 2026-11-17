
<?php

use Core\Session;

/*
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

<div class="min-h-screen bg-[#faf7f2] px-3 py-6 sm:px-6 sm:py-8 lg:px-8">
    <div class="mx-auto max-w-7xl space-y-8">

        <!-- EN-TÊTE -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

           <div class="min-w-0">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#a98260]">
                    Espace modérateur
                </p>

               <h1 class="mt-2 break-words text-2xl font-bold tracking-tight text-[#30221b] sm:text-3xl">

                 Mon profil
                </h1>

                <p class="mt-2 break-words text-sm text-[#78685d]">

                  Consultez les informations de votre compte et gérez votre sécurité.
                </p>
            </div>

            <a
                href="/immo-sn/logout"
          class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#eadfd3] bg-white px-5 py-3 text-sm font-semibold text-[#5a402d] transition hover:border-red-200 hover:bg-red-50 hover:text-red-700 sm:w-auto"
           >
                <span>↪</span>
                Déconnexion
            </a>

        </div>


        <!-- CARTE IDENTITÉ -->
       <section class="relative overflow-hidden rounded-[28px] bg-[#30221b] p-4 text-white shadow-[0_18px_50px_rgba(48,34,27,0.14)] sm:p-8">

       
            <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f7b500]/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center">

                <div class="flex h-28 w-28 shrink-0 items-center justify-center rounded-full border-4 border-white/20 bg-[#f3e5d5] text-3xl font-bold text-[#30221b] shadow-xl">
                    <?= $e($initiales) ?>
                </div>

                <div class="min-w-0 flex-1">

                    <div class="flex flex-wrap items-center gap-3">

                        <h2 class="min-w-0 break-words text-xl font-bold sm:text-3xl">

                           <?= $e(trim($prenom . ' ' . $nom)) ?>
                        </h2>

                        <?php if ($statutActif): ?>
                            <span class="rounded-full border border-green-300/30 bg-green-400/15 px-3 py-1 text-xs font-semibold text-green-200">
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

                        <span class="rounded-lg bg-[#f7b500]/15 px-3 py-2 text-xs font-semibold text-[#f7b500]">
                            Modérateur
                        </span>

                        <span class="rounded-lg bg-white/10 px-3 py-2 text-xs text-white/75">
                            Membre depuis <?= $e($dateCreation) ?>
                        </span>

                    </div>

                </div>

            </div>

        </section>


        <!-- MESSAGES -->
        <?php if (!empty($success)): ?>
           <div class="break-words rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800">

            <?= $e($success) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">
                <?= $e($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($passwordSuccess)): ?>
            <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800">
                <?= $e($passwordSuccess) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($passwordError)): ?>
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">
                <?= $e($passwordError) ?>
            </div>
        <?php endif; ?>


     
<!-- INFORMATIONS PERSONNELLES -->
<section class="rounded-[24px] border border-[#eee5db] bg-white p-4 shadow-sm sm:p-8">


    <div class="mb-7">
        <h3 class="break-words text-xl font-bold text-[#30221b]">

          Informations personnelles
        </h3>

       <p class="mt-1 break-words text-sm text-[#8b7b70]">

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
        <div class="flex flex-col gap-5 border-b border-[#eee5db] pb-7 sm:flex-row sm:items-center">

            <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-full border-4 border-[#f3e5d5] bg-[#30221b] text-2xl font-bold text-white">

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
                <label for="photo_profil" class="mb-2 block text-sm font-bold text-[#30221b]">
                    Photo de profil
                </label>

                <input
                    type="file"
                    id="photo_profil"
                    name="photo_profil"
                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                    class="block w-full cursor-pointer rounded-xl border border-[#e8ded3] bg-[#faf7f2] text-sm text-[#5a402d] file:mr-4 file:border-0 file:bg-[#30221b] file:px-4 file:py-3 file:text-sm file:font-semibold file:text-white hover:file:bg-[#5a402d]"
                >

                <p class="mt-2 text-xs text-[#8b7b70]">
                    Formats acceptés : JPG et PNG. Taille maximale : 5 Mo.
                </p>

            </div>

        </div>


        <!-- IDENTITÉ -->
        <div class="grid gap-5 sm:grid-cols-2">

            <div>
                <label for="prenom" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Prénom <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="prenom"
                    name="prenom"
                    value="<?= $e($prenom) ?>"
                    maxlength="100"
                    required
                    class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                >
            </div>

            <div>
                <label for="nom" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Nom <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="nom"
                    name="nom"
                    value="<?= $e($nom) ?>"
                    maxlength="100"
                    required
                    class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                >
            </div>

        </div>


        <!-- CONTACT -->
        <div class="grid gap-5 sm:grid-cols-2">

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Adresse e-mail <span class="text-red-500">*</span>
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= $e($email) ?>"
                    maxlength="254"
                    required
                    class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                >
            </div>

            <div>
                <label for="telephone" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Téléphone
                </label>

                <input
                    type="tel"
                    id="telephone"
                    name="telephone"
                    value="<?= $e($telephone) ?>"
                    maxlength="30"
                    class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                    placeholder="Ex. : 77 000 00 00"
                >
            </div>

        </div>


        <!-- LOCALISATION -->
        <div class="grid gap-5 sm:grid-cols-2">

            <div>
                <label for="ville" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Ville
                </label>

                <input
                    type="text"
                    id="ville"
                    name="ville"
                    value="<?= $e($ville) ?>"
                    maxlength="100"
                    class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                    placeholder="Votre ville"
                >
            </div>

            <div>
                <label for="quartier" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Quartier
                </label>

                <input
                    type="text"
                    id="quartier"
                    name="quartier"
                    value="<?= $e($quartier) ?>"
                    maxlength="100"
                    class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                    placeholder="Votre quartier"
                >
            </div>

        </div>


        <!-- BIOGRAPHIE -->
        <div>
            <label for="bio" class="mb-2 block text-sm font-semibold text-[#30221b]">
                À propos de moi
            </label>

            <textarea
                id="bio"
                name="bio"
                rows="4"
                maxlength="1000"
                class="w-full resize-y rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm leading-6 outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                placeholder="Présentez-vous en quelques lignes..."
            ><?= $e($bio) ?></textarea>

            <p class="mt-2 text-xs text-[#8b7b70]">
                1000 caractères maximum.
            </p>
        </div>


        <!-- ACTIONS -->
        <div class="flex flex-col gap-3 border-t border-[#eee5db] pt-6 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-xs text-[#8b7b70]">
                Les champs marqués d'un astérisque sont obligatoires.
            </p>

            <button
                type="submit"
           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#30221b]/10 transition hover:bg-[#5a402d] sm:w-auto"
           >
                <span>✓</span>
                Enregistrer les modifications
            </button>

        </div>

    </form>

</section>

        <!-- INFORMATIONS DU COMPTE -->
       <section class="rounded-[24px] border border-[#eee5db] bg-white p-4 shadow-sm sm:p-8">

       
            <div class="mb-7">
              <h3 class="break-words text-xl font-bold text-[#30221b]">

                Informations du compte
                </h3>

                <p class="mt-1 break-words text-sm text-[#8b7b70]">

                  Détails administratifs de votre compte.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <div class="rounded-xl border border-[#eee5db] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#a98260]">
                        Fonction
                    </p>

                    <p class="mt-2 font-semibold text-[#30221b]">
                        <?= $e(ucfirst($role)) ?>
                    </p>
                </div>

                <div class="rounded-xl border border-[#eee5db] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#a98260]">
                        Statut
                    </p>

                    <p class="mt-2 font-semibold text-[#30221b]">
                        <?= $e(ucfirst($status)) ?>
                    </p>
                </div>

                <div class="rounded-xl border border-[#eee5db] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#a98260]">
                        Adresse vérifiée
                    </p>

                    <p class="mt-2 font-semibold text-[#30221b]">
                        <?= !empty($profil['is_verified']) ? 'Oui' : 'Non' ?>
                    </p>
                </div>

                <div class="rounded-xl border border-[#eee5db] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#a98260]">
                        Date de création
                    </p>

                    <p class="mt-2 font-semibold text-[#30221b]">
                        <?= $e($dateCreation) ?>
                    </p>
                </div>

                <div class="rounded-xl border border-[#eee5db] p-4 sm:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#a98260]">
                        Dernière connexion
                    </p>

                    <p class="mt-2 font-semibold text-[#30221b]">
                        <?= $e($derniereConnexion) ?>
                    </p>
                </div>

            </div>

        </section>


        <!-- SÉCURITÉ -->
       <section class="rounded-[24px] border border-[#eee5db] bg-white p-4 shadow-sm sm:p-8">
      
            <div class="mb-7">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#f7b500]/15 text-xl text-[#5a402d]">
                    🔐
                </div>

              <h3 class="mt-4 break-words text-xl font-bold text-[#30221b]">

                Sécurité du compte
                </h3>

                <p class="mt-1 text-sm text-[#8b7b70]">
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

                <div>
                    <label for="ancien_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#30221b]">
                        Ancien mot de passe
                    </label>

                    <input
                        type="password"
                        id="ancien_mot_de_passe"
                        name="ancien_mot_de_passe"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                        placeholder="Saisissez votre ancien mot de passe"
                    >
                </div>

                <div class="grid gap-5 sm:grid-cols-2">

                    <div>
                        <label for="nouveau_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Nouveau mot de passe
                        </label>

                        <input
                            type="password"
                            id="nouveau_mot_de_passe"
                            name="nouveau_mot_de_passe"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                            placeholder="8 caractères minimum"
                        >
                    </div>

                    <div>
                        <label for="confirmation_mot_de_passe" class="mb-2 block text-sm font-semibold text-[#30221b]">
                            Confirmer le nouveau mot de passe
                        </label>

                        <input
                            type="password"
                            id="confirmation_mot_de_passe"
                            name="confirmation_mot_de_passe"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-[#e8ded3] bg-[#faf7f2] px-4 py-3 text-sm outline-none transition focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/15"
                            placeholder="Confirmez le mot de passe"
                        >
                    </div>

                </div>

                <div class="flex flex-col gap-3 border-t border-[#eee5db] pt-6 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-xs text-[#8b7b70]">
                        Utilisez au moins 8 caractères.
                    </p>

                    <button
                        type="submit"
                  class="inline-flex w-full items-center justify-center rounded-xl bg-[#30221b] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#30221b]/10 transition hover:bg-[#5a402d] sm:w-auto"
                   >
                        Modifier mon mot de passe
                    </button>

                </div>

            </form>

        </section>

    </div>

</div>
