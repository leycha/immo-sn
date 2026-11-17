<?php
/**
 * Mon profil — ImmoSn
 */
?>

<div class="min-h-screen bg-[#faf7f2]">

    <!-- =========================================================
         HEADER
    ========================================================== -->
    <header class="sticky top-0 z-20 border-b border-[#eadfd5] bg-[#fffdf9]/95 px-5 py-5 backdrop-blur md:px-8">

       <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">

             <div class="min-w-0">
                <div class="mb-1 flex items-center gap-2 text-[11px] font-medium uppercase tracking-[0.16em] text-[#a98260]">
                    <span>Espace membre</span>
                    <span class="text-[#d6c7ba]">/</span>
                    <span>Profil</span>
                </div>

                <h1 class="break-words text-xl font-semibold tracking-tight text-[#30221b] sm:text-2xl">
                    Mon profil
                </h1>

                <p class="mt-1 break-words text-sm text-[#8f8176]">
                    Gérez vos informations personnelles et la sécurité de votre compte.
                </p>
            </div>

            <div class="hidden items-center gap-3 rounded-2xl border border-[#eadfd5] bg-white px-3 py-2.5 shadow-sm sm:flex">

                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#30221b] text-xs font-bold text-[#f7b500]">
                    <?= strtoupper(
                        substr($profil['prenom'] ?? 'M', 0, 1)
                        . substr($profil['nom'] ?? 'N', 0, 1)
                    ) ?>
                </div>

                <div class="text-left">

                    <p class="text-xs font-semibold text-[#30221b]">
                        <?= htmlspecialchars(
                            ($profil['prenom'] ?? '') . ' ' . ($profil['nom'] ?? '')
                        ) ?>
                    </p>

                    <p class="mt-0.5 text-[11px] text-[#9b8d82]">
                        Membre ImmoSn
                    </p>

                </div>

            </div>

        </div>

    </header>


    <!-- =========================================================
         CONTENU
    ========================================================== -->
    <main class="px-4 py-6 sm:px-6 md:px-8 md:py-8">

        <div class="mx-auto max-w-6xl">

            <!-- =================================================
                 CARTE PROFIL PRINCIPALE
            ================================================== -->
            <section class="overflow-hidden rounded-[28px] border border-[#e8ddd3] bg-white shadow-[0_12px_40px_rgba(48,34,27,0.06)]">

                <!-- Bandeau premium -->
                <div class="relative h-36 overflow-hidden bg-[#30221b] md:h-40">

                    <!-- décorations -->
                    <div class="absolute -right-12 -top-20 h-56 w-56 rounded-full border border-[#f7b500]/10 bg-[#f7b500]/10"></div>

                    <div class="absolute right-28 -bottom-24 h-52 w-52 rounded-full border border-white/5 bg-white/5"></div>

                    <div class="absolute left-1/3 top-10 h-24 w-24 rounded-full bg-[#a98260]/10 blur-2xl"></div>

                    <!-- lignes décoratives -->
                    <div class="absolute bottom-0 left-0 h-px w-full bg-gradient-to-r from-transparent via-[#f7b500]/40 to-transparent"></div>

                    <!-- logo -->
                    <div class="absolute left-6 top-6 md:left-8">
                        <div class="text-base font-bold tracking-wide text-white">
                            Immo<span class="text-[#f7b500]">Sn</span>
                            <span class="font-medium text-[#d7c4b2]">.com</span>
                        </div>

                        <p class="mt-1 text-[10px] uppercase tracking-[0.2em] text-white/45">
                            Immobilier & confiance
                        </p>
                    </div>

                    <!-- badge -->
                    <div class="absolute right-5 top-5 hidden items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 backdrop-blur-sm sm:flex">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#f7b500]"></span>
                        <span class="text-[10px] font-medium uppercase tracking-wider text-white/80">
                            Compte actif
                        </span>
                    </div>

                </div>


                <!-- Profil -->
                <div class="relative px-5 pb-7 sm:px-7 md:px-9">

                    <div class="-mt-16 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                        <!-- identité -->
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">

                            <!-- Avatar -->
                            <div class="relative w-fit">

                                <div class="flex h-32 w-32 items-center justify-center rounded-full border-[6px] border-white bg-[#f3e5d5] text-3xl font-bold text-[#30221b] shadow-[0_10px_30px_rgba(48,34,27,0.16)]">

                                    <?= strtoupper(
                                        substr($profil['prenom'] ?? 'M', 0, 1)
                                        . substr($profil['nom'] ?? 'N', 0, 1)
                                    ) ?>

                                </div>

                                <!-- statut -->
                                <span
                                    class="absolute bottom-3 right-3 flex h-6 w-6 items-center justify-center rounded-full border-[3px] border-white bg-[#5caa70]"
                                    title="Compte actif"
                                >
                                    <span class="h-2 w-2 rounded-full bg-white"></span>
                                </span>

                            </div>


                            <!-- Nom -->
                            <div class="pb-1">

                                <p class="mb-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                                    Profil membre
                                </p>

                              <h2 class="break-words text-xl font-semibold tracking-tight text-[#30221b] sm:text-2xl">
                                  <?= htmlspecialchars(
                                        ($profil['prenom'] ?? '') . ' ' . ($profil['nom'] ?? '')
                                    ) ?>
                                </h2>

                                <div class="mt-2 flex flex-wrap items-center gap-2">

                                    <span class="rounded-full bg-[#f3e5d5] px-3 py-1 text-[11px] font-medium text-[#725238]">
                                        <?= htmlspecialchars($profil['role'] ?? 'Membre') ?>
                                    </span>

                                    <span class="text-xs text-[#9b8d82]">
                                        Membre ImmoSn
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            <!-- =================================================
                 GRILLE PRINCIPALE
            ================================================== -->
            <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1.55fr)_minmax(300px,0.85fr)]">


<!-- =================================================
     INFORMATIONS PERSONNELLES
================================================== -->
<section class="overflow-hidden rounded-[24px] border border-[#e8ddd3] bg-white shadow-[0_8px_30px_rgba(48,34,27,0.04)]">

    <!-- Header -->
    <div class="flex flex-col items-start justify-between gap-4 border-b border-[#eee5dc] px-5 py-5 sm:flex-row sm:items-center sm:px-6">

    
      <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f3e5d5] text-[#725238]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.7">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                </svg>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-[#30221b]">
                    Informations personnelles
                </h2>
                <p class="mt-0.5 text-xs text-[#9b8d82]">
                    Vos coordonnées personnelles
                </p>
            </div>
        </div>

        <span class="hidden rounded-full bg-[#faf7f2] px-3 py-1 text-[10px] font-medium text-[#a98260] sm:block">
            Profil
        </span>
    </div>

    <!-- Contenu -->
    <div class="p-5 sm:p-6">

      
<?php if (!empty($success)): ?>
    <div
        data-profil-success
        class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
    >
        <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

        <!-- Message d'erreur -->
        <?php if (!empty($error)): ?>
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de modification -->
        <form action="/immo-sn/member/profil" method="POST">

            <div class="grid gap-5 sm:grid-cols-2">

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="mb-2 block text-xs font-semibold text-[#725238]">
                        Prénom
                    </label>

                    <input
                        id="prenom"
                        name="prenom"
                        type="text"
                        value="<?= htmlspecialchars($profil['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        maxlength="100"
                        autocomplete="given-name"
                        required
                        class="h-12 w-full rounded-xl border border-[#e4d9d0] bg-[#fffdfb] px-4 text-sm text-[#30221b] shadow-sm outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                    >
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="mb-2 block text-xs font-semibold text-[#725238]">
                        Nom
                    </label>

                    <input
                        id="nom"
                        name="nom"
                        type="text"
                        value="<?= htmlspecialchars($profil['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        maxlength="100"
                        autocomplete="family-name"
                        required
                        class="h-12 w-full rounded-xl border border-[#e4d9d0] bg-[#fffdfb] px-4 text-sm text-[#30221b] shadow-sm outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                    >
                </div>

                <!-- Email -->
                <div class="sm:col-span-2">
                    <label for="email" class="mb-2 block text-xs font-semibold text-[#725238]">
                        Adresse email
                    </label>

                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#a98260]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5H4.5a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.594a2.25 2.25 0 0 1-2.12 0L2.25 6.75"/>
                        </svg>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="<?= htmlspecialchars($profil['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            maxlength="255"
                            autocomplete="email"
                            required
                            class="h-12 w-full rounded-xl border border-[#e4d9d0] bg-[#fffdfb] pl-11 pr-4 text-sm text-[#30221b] shadow-sm outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                        >
                    </div>
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="mb-2 block text-xs font-semibold text-[#725238]">
                        Téléphone
                    </label>

                    <input
                        id="telephone"
                        name="telephone"
                        type="tel"
                        value="<?= htmlspecialchars($profil['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        maxlength="30"
                        autocomplete="tel"
                        class="h-12 w-full rounded-xl border border-[#e4d9d0] bg-[#fffdfb] px-4 text-sm text-[#30221b] shadow-sm outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                    >
                </div>

                <!-- Ville -->
                <div>
                    <label for="ville" class="mb-2 block text-xs font-semibold text-[#725238]">
                        Ville
                    </label>

                    <input
                        id="ville"
                        name="ville"
                        type="text"
                        value="<?= htmlspecialchars($profil['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        maxlength="150"
                        autocomplete="address-level2"
                        class="h-12 w-full rounded-xl border border-[#e4d9d0] bg-[#fffdfb] px-4 text-sm text-[#30221b] shadow-sm outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                    >
                </div>


            </div>

            <!-- Bouton d'enregistrement -->
            <div class="mt-7 flex flex-col gap-3 border-t border-[#eee5dc] pt-5 sm:flex-row sm:items-center sm:justify-between">

        
                <p class="text-[11px] leading-5 text-[#9b8d82]">
                    Vérifiez vos informations avant d'enregistrer vos modifications.
                </p>

                <button
                    type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-bold text-[#30221b] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#e6a900] hover:shadow-md sm:w-auto"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 12.75 9.25 17 19 7.25"/>
                    </svg>

                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>
</section>



                <!-- =================================================
                     COLONNE DROITE
                ================================================== -->
                <div class="space-y-6">


                    <!-- =================================================
                         MON COMPTE
                    ================================================== -->
                    <section class="overflow-hidden rounded-[24px] border border-[#e8ddd3] bg-white shadow-[0_8px_30px_rgba(48,34,27,0.04)]">

                        <div class="border-b border-[#eee5dc] px-5 py-5 sm:px-6">

                            <div class="flex items-center justify-between">

                                <div>

                                    <h2 class="text-sm font-semibold text-[#30221b]">
                                        Mon compte
                                    </h2>

                                    <p class="mt-0.5 text-xs text-[#9b8d82]">
                                        Informations du compte
                                    </p>

                                </div>

                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#30221b] text-[#f7b500]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 0 0 3.743-.704 4.5 4.5 0 0 0-7.486 0A9.38 9.38 0 0 0 15 19.128ZM15 19.128a9.38 9.38 0 0 1-6-2.128m6 2.128c.546 0 1.083-.046 1.607-.134M9 17a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z"
                                        />
                                    </svg>

                                </div>

                            </div>

                        </div>


                        <div class="p-5 sm:p-6">

                            <!-- rôle -->
                            <div class="flex items-center gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#f3e5d5] text-[#a98260]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 0 0 3.743-.704 4.5 4.5 0 0 0-7.486 0A9.38 9.38 0 0 0 15 19.128ZM15 19.128a9.38 9.38 0 0 1-6-2.128m6 2.128c.546 0 1.083-.046 1.607-.134M9 17a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z"
                                        />
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-[11px] uppercase tracking-wider text-[#a98260]">
                                        Type de compte
                                    </p>

                                    <p class="mt-1 truncate text-sm font-semibold text-[#30221b]">
                                        <?= htmlspecialchars($profil['role'] ?? 'Membre') ?>
                                    </p>

                                </div>

                            </div>


                            <!-- séparateur -->
                            <div class="my-5 border-t border-[#eee5dc]"></div>


                            <!-- statut -->
                            <div class="flex items-center gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#edf5ef] text-[#497657]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m9 12.75 2.25 2.25L15 11.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-[11px] uppercase tracking-wider text-[#a98260]">
                                        Statut du compte
                                    </p>

                                    <div class="mt-1.5 flex items-center gap-2">

                                        <span class="h-2 w-2 rounded-full bg-[#5caa70]"></span>

                                        <span class="text-sm font-semibold text-[#497657]">
                                            Actif
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </section>


                    <!-- =================================================
                         SÉCURITÉ
                    ================================================== -->
                    <section class="overflow-hidden rounded-[24px] border border-[#e8ddd3] bg-white shadow-[0_8px_30px_rgba(48,34,27,0.04)]">

                        <div class="border-b border-[#eee5dc] px-5 py-5 sm:px-6">

                            <div class="flex items-center gap-3">

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f5e9df] text-[#a45b36]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75a2.25 2.25 0 0 1-2.25-2.25v-6a2.25 2.25 0 0 1 2.25-2.25Z"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <h2 class="text-sm font-semibold text-[#30221b]">
                                        Sécurité
                                    </h2>

                                    <p class="mt-0.5 text-xs text-[#9b8d82]">
                                        Protection de votre compte
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="p-5 sm:p-6">

                            <div class="rounded-xl bg-[#faf7f2] p-4">

                                <div class="flex gap-3">

                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-[#a98260] shadow-sm">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 3.75 5.25 6v5.25c0 4.5 2.875 7.625 6.75 9 3.875-1.375 6.75-4.5 6.75-9V6L12 3.75Z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m9.5 12 1.75 1.75L14.75 10"
                                            />
                                        </svg>

                                    </div>

                                    <p class="text-xs leading-5 text-[#8f8176]">
                                        Pour protéger votre compte, utilisez un mot
                                        de passe unique et difficile à deviner.
                                    </p>

                                </div>

                            </div>

<button
    type="button"
    id="ouvrir-modal-mot-de-passe"
    class="mt-4 w-full rounded-xl border border-[#30221b] bg-white px-4 py-3 text-sm font-semibold text-[#30221b] transition hover:bg-[#30221b] hover:text-white"
>
    Modifier mon mot de passe
</button>
                        </div>

                    </section>


                    <!-- =================================================
                         DÉCONNEXION
                    ================================================== -->
                    <section class="rounded-[24px] border border-[#eadfd5] bg-[#fffaf5] p-5 shadow-[0_8px_30px_rgba(48,34,27,0.03)]">

                        <div class="flex gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#f5e9df] text-[#a45b36]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M18 12H9m0 0 3-3m-3 3 3 3"
                                    />
                                </svg>

                            </div>


                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-[#30221b]">
                                    Vous souhaitez vous déconnecter ?
                                </p>

                                <p class="mt-1 text-xs leading-5 text-[#9b8d82]">
                                    Votre session sera fermée sur cet appareil.
                                </p>

                                
<a
    href="/immo-sn/logout"
    class="mt-3 inline-block text-xs font-bold text-[#a45b36] hover:text-[#7f4228] hover:underline"
>
    Se déconnecter
</a>

                            </div>

                        </div>

                    </section>

                </div>

            </div>

        </div>

    </main>

</div>

<!-- MODAL : MODIFIER LE MOT DE PASSE -->
<div
    id="modal-mot-de-passe"
   class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-black/50 px-3 py-4 backdrop-blur-sm sm:items-center sm:px-4 sm:py-6"
   role="dialog"
    aria-modal="true"
    aria-labelledby="titre-modal-mot-de-passe"
>
<?php if (!empty($passwordSuccess)): ?>
    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="status">
        <?= htmlspecialchars($passwordSuccess, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
   <div class="my-auto w-full min-w-0 max-w-lg shrink-0 overflow-hidden rounded-2xl border border-[#e8ddd3] bg-white shadow-2xl">
        <!-- En-tête -->
        <div class="flex items-start justify-between gap-3 bg-[#30221b] px-4 py-5 sm:px-6">

          <div>
                <h2
                    id="titre-modal-mot-de-passe"
                    class="text-lg font-semibold text-white"
                >
                    Modifier mon mot de passe
                </h2>
                <p class="mt-1 text-xs text-white/70">
                    Sécurisez votre compte ImmoSn
                </p>
            </div>

            <button
                type="button"
                id="fermer-modal-mot-de-passe"
                class="flex h-9 w-9 items-center justify-center rounded-full text-xl text-white hover:bg-white/10"
                aria-label="Fermer"
            >
                
            </button>
        </div>

        <!-- Formulaire -->
       <form action="/immo-sn/member/mot-de-passe" method="POST" id="formulaire-mot-de-passe">
            <div
    id="erreur-mot-de-passe"
    class="<?= !empty($passwordError) ? '' : 'hidden' ?> mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700"
    role="alert"
>
    <?= htmlspecialchars($passwordError ?? '', ENT_QUOTES, 'UTF-8') ?>
</div>

            <div>
                <label
                    for="ancien_mot_de_passe"
                    class="mb-2 block text-sm font-semibold text-[#725238]"
                >
                    Ancien mot de passe
                </label>
                <input
                    type="password"
                    id="ancien_mot_de_passe"
                    name="ancien_mot_de_passe"
                    autocomplete="current-password"
                    required
                    class="h-12 w-full rounded-xl border border-[#e4d9d0] px-4 text-sm outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                >
            </div>

            <div>
                <label
                    for="nouveau_mot_de_passe"
                    class="mb-2 block text-sm font-semibold text-[#725238]"
                >
                    Nouveau mot de passe
                </label>
                <input
                    type="password"
                    id="nouveau_mot_de_passe"
                    name="nouveau_mot_de_passe"
                    minlength="8"
                    autocomplete="new-password"
                    required
                    class="h-12 w-full rounded-xl border border-[#e4d9d0] px-4 text-sm outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                >
                <p class="mt-1 text-xs text-[#9b8d82]">
                    Au moins 8 caractères.
                </p>
            </div>

            <div>
                <label
                    for="confirmation_mot_de_passe"
                    class="mb-2 block text-sm font-semibold text-[#725238]"
                >
                    Confirmer le nouveau mot de passe
                </label>
                <input
                    type="password"
                    id="confirmation_mot_de_passe"
                    name="confirmation_mot_de_passe"
                    minlength="8"
                    autocomplete="new-password"
                    required
                    class="h-12 w-full rounded-xl border border-[#e4d9d0] px-4 text-sm outline-none focus:border-[#a98260] focus:ring-4 focus:ring-[#a98260]/10"
                >
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-[#eee5dc] pt-5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    id="annuler-modal-mot-de-passe"
                    class="rounded-xl border border-[#e4d9d0] px-5 py-3 text-sm font-semibold text-[#725238] hover:bg-[#faf7f2]"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-bold text-[#30221b] hover:bg-[#e6a900]"
                >
                    Enregistrer
                </button>
            </div>
        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal-mot-de-passe');
    const ouvrir = document.getElementById('ouvrir-modal-mot-de-passe');
    const fermer = document.getElementById('fermer-modal-mot-de-passe');
    const annuler = document.getElementById('annuler-modal-mot-de-passe');
    const formulaire = document.getElementById('formulaire-mot-de-passe');

    const ancien = document.getElementById('ancien_mot_de_passe');
    const nouveau = document.getElementById('nouveau_mot_de_passe');
    const confirmation = document.getElementById('confirmation_mot_de_passe');
    const erreur = document.getElementById('erreur-mot-de-passe');

    // Vérifier que les éléments nécessaires existent
    if (!modal || !ouvrir || !formulaire || !erreur ||
        !ancien || !nouveau || !confirmation) {
        console.error(
            'Erreur : un élément nécessaire au modal de mot de passe est introuvable.'
        );
        return;
    }

    // Ouvrir le modal
    function ouvrirModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        ancien.focus();
    }

    // Fermer le modal sans effacer les messages serveur
    function fermerModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Bouton pour ouvrir
    ouvrir.addEventListener('click', function () {
        erreur.textContent = '';
        erreur.classList.add('hidden');
        ouvrirModal();
    });

    // Bouton pour fermer
    if (fermer) {
        fermer.addEventListener('click', fermerModal);
    }

    // Bouton Annuler
    if (annuler) {
        annuler.addEventListener('click', fermerModal);
    }

    // Fermer en cliquant à l'extérieur du modal
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            fermerModal();
        }
    });

    // Fermer avec la touche Échap
    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            !modal.classList.contains('hidden')
        ) {
            fermerModal();
        }
    });

    // Vérifier les champs avant l'envoi
    formulaire.addEventListener('submit', function (event) {
        erreur.textContent = '';
        erreur.classList.add('hidden');

        if (
            ancien.value.trim() === '' ||
            nouveau.value === '' ||
            confirmation.value === ''
        ) {
            event.preventDefault();

            erreur.textContent =
                'Veuillez remplir tous les champs du formulaire.';

            erreur.classList.remove('hidden');
            return;
        }

        if (nouveau.value.length < 8) {
            event.preventDefault();

            erreur.textContent =
                'Le nouveau mot de passe doit contenir au moins 8 caractères.';

            erreur.classList.remove('hidden');
            nouveau.focus();
            return;
        }

        if (nouveau.value !== confirmation.value) {
            event.preventDefault();

            erreur.textContent =
                'Les deux nouveaux mots de passe ne correspondent pas.';

            erreur.classList.remove('hidden');
            confirmation.focus();
            return;
        }

        // Si les vérifications réussissent, le formulaire est envoyé
        // à /immo-sn/member/mot-de-passe par la méthode POST.
    });

    // Réouvrir le modal si le serveur a affiché une erreur
    if (erreur.textContent.trim() !== '') {
        ouvrirModal();
    }
});

// Faire disparaître progressivement les messages de succès
document.addEventListener('DOMContentLoaded', function () {
    const successMessage = document.querySelector('[data-profil-success]');

    if (successMessage) {
        setTimeout(function () {
            successMessage.style.transition = 'opacity 0.5s ease';
            successMessage.style.opacity = '0';

            setTimeout(function () {
                successMessage.remove();
            }, 500);
        }, 4000);
    }
});
</script>