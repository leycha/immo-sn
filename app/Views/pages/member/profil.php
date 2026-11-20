
<?php
/**
 * Vue : Mon profil — ImmoSn
 */

// Sécurisation des données du profil
$profil = is_array($profil ?? null) ? $profil : [];

$prenom = $profil['prenom'] ?? '';
$nom = $profil['nom'] ?? '';

$initiales = strtoupper(
    substr($prenom ?: 'M', 0, 1) .
    substr($nom ?: 'N', 0, 1)
);

$nomComplet = trim($prenom . ' ' . $nom);
?>

<div class="min-h-screen bg-[#F5F3ED]">

    <!-- =========================================================
         HEADER
    ========================================================== -->
    <header class="sticky top-0 z-20 border-b border-[#E5E7DE] bg-white/95 px-5 py-5 backdrop-blur md:px-8">

        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">

            <div class="min-w-0">
                <div class="mb-1 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#7E9833]">
                    <span>Espace membre</span>
                    <span class="text-[#AAB3A0]">/</span>
                    <span>Profil</span>
                </div>

                <h1 class="break-words text-xl font-bold tracking-tight text-[#101D33] sm:text-2xl">
                    Mon profil
                </h1>

                <p class="mt-1 text-sm text-[#71869A]">
                    Gérez vos informations personnelles et la sécurité de votre compte.
                </p>
            </div>

            <!-- Identité -->
            <div class="hidden items-center gap-3 rounded-2xl border border-[#E5E7DE] bg-white px-3 py-2.5 shadow-sm sm:flex">

                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#101D33] text-xs font-bold text-white">
                    <?= htmlspecialchars($initiales, ENT_QUOTES, 'UTF-8') ?>
                </div>

                <div class="text-left">
                    <p class="text-xs font-semibold text-[#101D33]">
                        <?= htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <p class="mt-0.5 text-[11px] text-[#71869A]">
                        Membre ImmoSn
                    </p>
                </div>

            </div>

        </div>

    </header>


    <!-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== -->
    <main class="px-4 py-6 sm:px-6 md:px-8 md:py-8">

        <div class="mx-auto max-w-6xl">

            <!-- =================================================
                 CARTE PROFIL PRINCIPALE
            ================================================== -->
            <section class="overflow-hidden rounded-[28px] border border-[#E5E7DE] bg-white shadow-[0_12px_40px_rgba(16,29,51,0.06)]">

                <!-- Bandeau -->
                <div class="relative h-36 overflow-hidden bg-[#101D33] md:h-40">

                    <!-- Décorations -->
                    <div class="absolute -right-12 -top-20 h-56 w-56 rounded-full border border-[#8FAF35]/20 bg-[#8FAF35]/10"></div>

                    <div class="absolute right-28 -bottom-24 h-52 w-52 rounded-full border border-white/10 bg-white/5"></div>

                    <div class="absolute left-1/3 top-10 h-24 w-24 rounded-full bg-[#8FAF35]/15 blur-2xl"></div>

                    <div class="absolute bottom-0 left-0 h-px w-full bg-gradient-to-r from-transparent via-[#8FAF35]/70 to-transparent"></div>

                    <!-- Logo -->
                    <div class="absolute left-6 top-6 md:left-8">
                        <div class="text-base font-bold tracking-wide text-white">
                            Immo<span class="text-[#8FAF35]">Sn</span>
                            <span class="font-medium text-white/60">.com</span>
                        </div>

                        <p class="mt-1 text-[10px] uppercase tracking-[0.2em] text-white/50">
                            Immobilier & confiance
                        </p>
                    </div>

                    <!-- Statut -->
                    <div class="absolute right-5 top-5 hidden items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 backdrop-blur-sm sm:flex">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#8FAF35]"></span>

                        <span class="text-[10px] font-semibold uppercase tracking-wider text-white/90">
                            Compte actif
                        </span>
                    </div>

                </div>


                <!-- Identité du membre -->
                <div class="relative px-5 pb-7 sm:px-7 md:px-9">

                    <div class="-mt-16 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">

                            <!-- Avatar -->
                            <div class="relative w-fit">

                                <div class="flex h-32 w-32 items-center justify-center rounded-full border-[6px] border-white bg-[#EEF3E3] text-3xl font-bold text-[#607A27] shadow-[0_10px_30px_rgba(16,29,51,0.15)]">

                                    <?= htmlspecialchars($initiales, ENT_QUOTES, 'UTF-8') ?>

                                </div>

                                <!-- Statut -->
                                <span
                                    class="absolute bottom-3 right-3 flex h-6 w-6 items-center justify-center rounded-full border-[3px] border-white bg-[#5CAA70]"
                                    title="Compte actif"
                                >
                                    <span class="h-2 w-2 rounded-full bg-white"></span>
                                </span>

                            </div>


                            <!-- Nom -->
                            <div class="pb-1">

                                <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.16em] text-[#7E9833]">
                                    Profil membre
                                </p>

                                <h2 class="break-words text-xl font-bold tracking-tight text-[#101D33] sm:text-2xl">
                                    <?= htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8') ?>
                                </h2>

                                <div class="mt-2 flex flex-wrap items-center gap-2">

                                    <span class="rounded-full border border-[#DCE6C7] bg-[#EEF3E3] px-3 py-1 text-[11px] font-semibold text-[#607A27]">
                                        <?= htmlspecialchars($profil['role'] ?? 'Membre', ENT_QUOTES, 'UTF-8') ?>
                                    </span>

                                    <span class="text-xs text-[#71869A]">
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
                <section class="overflow-hidden rounded-[24px] border border-[#E5E7DE] bg-white shadow-[0_8px_30px_rgba(16,29,51,0.04)]">

                    <!-- En-tête -->
                    <div class="flex flex-col items-start justify-between gap-4 border-b border-[#E9EBE3] px-5 py-5 sm:flex-row sm:items-center sm:px-6">

                        <div class="flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#EEF3E3] text-[#607A27]">

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
                                <h2 class="text-sm font-bold text-[#101D33]">
                                    Informations personnelles
                                </h2>

                                <p class="mt-0.5 text-xs text-[#71869A]">
                                    Vos coordonnées personnelles
                                </p>
                            </div>

                        </div>

                        <span class="hidden rounded-full bg-[#F5F3ED] px-3 py-1 text-[10px] font-semibold text-[#7E9833] sm:block">
                            Profil
                        </span>

                    </div>


                    <!-- Contenu -->
                    <div class="p-5 sm:p-6">

                        <!-- Message de succès -->
                        <?php if (!empty($success)): ?>
                            <div
                                data-profil-success
                                class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
                                role="status"
                            >
                                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>


                        <!-- Message d'erreur -->
                        <?php if (!empty($error)): ?>
                            <div
                                class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                                role="alert"
                            >
                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>


                        <!-- Formulaire -->
                        <form action="/immo-sn/member/profil" method="POST">

                            <div class="grid gap-5 sm:grid-cols-2">

                                <!-- Prénom -->
                                <div>
                                    <label for="prenom" class="mb-2 block text-xs font-semibold text-[#101D33]">
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
                                        class="h-12 w-full rounded-xl border border-[#E1E5DC] bg-[#FCFCFA] px-4 text-sm text-[#101D33] shadow-sm outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                                    >
                                </div>


                                <!-- Nom -->
                                <div>
                                    <label for="nom" class="mb-2 block text-xs font-semibold text-[#101D33]">
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
                                        class="h-12 w-full rounded-xl border border-[#E1E5DC] bg-[#FCFCFA] px-4 text-sm text-[#101D33] shadow-sm outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                                    >
                                </div>


                                <!-- Email -->
                                <div class="sm:col-span-2">

                                    <label for="email" class="mb-2 block text-xs font-semibold text-[#101D33]">
                                        Adresse email
                                    </label>

                                    <div class="relative">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#7E9833]"
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
                                            class="h-12 w-full rounded-xl border border-[#E1E5DC] bg-[#FCFCFA] pl-11 pr-4 text-sm text-[#101D33] shadow-sm outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                                        >

                                    </div>

                                </div>


                                <!-- Téléphone -->
                                <div>
                                    <label for="telephone" class="mb-2 block text-xs font-semibold text-[#101D33]">
                                        Téléphone
                                    </label>

                                    <input
                                        id="telephone"
                                        name="telephone"
                                        type="tel"
                                        value="<?= htmlspecialchars($profil['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                        maxlength="30"
                                        autocomplete="tel"
                                        class="h-12 w-full rounded-xl border border-[#E1E5DC] bg-[#FCFCFA] px-4 text-sm text-[#101D33] shadow-sm outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                                    >
                                </div>


                                <!-- Ville -->
                                <div>
                                    <label for="ville" class="mb-2 block text-xs font-semibold text-[#101D33]">
                                        Ville
                                    </label>

                                    <input
                                        id="ville"
                                        name="ville"
                                        type="text"
                                        value="<?= htmlspecialchars($profil['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                        maxlength="150"
                                        autocomplete="address-level2"
                                        class="h-12 w-full rounded-xl border border-[#E1E5DC] bg-[#FCFCFA] px-4 text-sm text-[#101D33] shadow-sm outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                                    >
                                </div>

                            </div>


                            <!-- Bouton d'enregistrement -->
                            <div class="mt-7 flex flex-col gap-3 border-t border-[#E9EBE3] pt-5 sm:flex-row sm:items-center sm:justify-between">

                                <p class="text-[11px] leading-5 text-[#71869A]">
                                    Vérifiez vos informations avant d'enregistrer vos modifications.
                                </p>

                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#6B812B] hover:shadow-md sm:w-auto"
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
                    <section class="overflow-hidden rounded-[24px] border border-[#E5E7DE] bg-white shadow-[0_8px_30px_rgba(16,29,51,0.04)]">

                        <div class="border-b border-[#E9EBE3] px-5 py-5 sm:px-6">

                            <div class="flex items-center justify-between">

                                <div>
                                    <h2 class="text-sm font-bold text-[#101D33]">
                                        Mon compte
                                    </h2>

                                    <p class="mt-0.5 text-xs text-[#71869A]">
                                        Informations du compte
                                    </p>
                                </div>

                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#101D33] text-[#8FAF35]">

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

                            <!-- Rôle -->
                            <div class="flex items-center gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#EEF3E3] text-[#607A27]">

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

                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#71869A]">
                                        Type de compte
                                    </p>

                                    <p class="mt-1 truncate text-sm font-bold text-[#101D33]">
                                        <?= htmlspecialchars($profil['role'] ?? 'Membre', ENT_QUOTES, 'UTF-8') ?>
                                    </p>

                                </div>

                            </div>


                            <div class="my-5 border-t border-[#E9EBE3]"></div>


                            <!-- Statut -->
                            <div class="flex items-center gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#EDF5EF] text-[#497657]">

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

                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#71869A]">
                                        Statut du compte
                                    </p>

                                    <div class="mt-1.5 flex items-center gap-2">

                                        <span class="h-2 w-2 rounded-full bg-[#5CAA70]"></span>

                                        <span class="text-sm font-bold text-[#497657]">
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
                    <section class="overflow-hidden rounded-[24px] border border-[#E5E7DE] bg-white shadow-[0_8px_30px_rgba(16,29,51,0.04)]">

                        <div class="border-b border-[#E9EBE3] px-5 py-5 sm:px-6">

                            <div class="flex items-center gap-3">

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#EEF3E3] text-[#607A27]">

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
                                    <h2 class="text-sm font-bold text-[#101D33]">
                                        Sécurité
                                    </h2>

                                    <p class="mt-0.5 text-xs text-[#71869A]">
                                        Protection de votre compte
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="p-5 sm:p-6">

                            <div class="rounded-xl border border-[#E5E7DE] bg-[#F7F8F3] p-4">

                                <div class="flex gap-3">

                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-[#7E9833] shadow-sm">

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

                                    <p class="text-xs leading-5 text-[#71869A]">
                                        Pour protéger votre compte, utilisez un mot de passe unique et difficile à deviner.
                                    </p>

                                </div>

                            </div>


                            <button
                                type="button"
                                id="ouvrir-modal-mot-de-passe"
                                class="mt-4 w-full rounded-xl border border-[#101D33] bg-white px-4 py-3 text-sm font-semibold text-[#101D33] transition hover:bg-[#101D33] hover:text-white"
                            >
                                Modifier mon mot de passe
                            </button>

                        </div>

                    </section>


                    <!-- =================================================
                         DÉCONNEXION
                    ================================================== -->
                    <section class="rounded-[24px] border border-[#E5E7DE] bg-white p-5 shadow-[0_8px_30px_rgba(16,29,51,0.03)]">

                        <div class="flex gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FCEFEB] text-[#A45B36]">

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

                                <p class="text-sm font-bold text-[#101D33]">
                                    Vous souhaitez vous déconnecter ?
                                </p>

                                <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                    Votre session sera fermée sur cet appareil.
                                </p>

                                <a
                                    href="/immo-sn/logout"
                                    class="mt-3 inline-block text-xs font-bold text-[#A45B36] transition hover:text-[#7F4228] hover:underline"
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


<!-- =============================================================
     MODAL : MODIFIER LE MOT DE PASSE
============================================================= -->
<div
    id="modal-mot-de-passe"
    class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-[#101D33]/60 px-3 py-4 backdrop-blur-sm sm:items-center sm:px-4 sm:py-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="titre-modal-mot-de-passe"
>

    <div class="my-auto w-full min-w-0 max-w-lg shrink-0 overflow-hidden rounded-2xl border border-[#E5E7DE] bg-white shadow-2xl">

        <!-- En-tête -->
        <div class="flex items-start justify-between gap-3 bg-[#101D33] px-5 py-5 sm:px-6">

            <div>
                <h2
                    id="titre-modal-mot-de-passe"
                    class="text-lg font-bold text-white"
                >
                    Modifier mon mot de passe
                </h2>

                <p class="mt-1 text-xs text-white/65">
                    Sécurisez votre compte ImmoSn
                </p>
            </div>

            <button
                type="button"
                id="fermer-modal-mot-de-passe"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xl text-white transition hover:bg-white/10"
                aria-label="Fermer"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 6l12 12M18 6 6 18"/>
                </svg>
            </button>

        </div>


        <!-- Formulaire -->
        <form
            action="/immo-sn/member/mot-de-passe"
            method="POST"
            id="formulaire-mot-de-passe"
            class="space-y-5 p-5 sm:p-6"
        >

            <!-- Erreur -->
            <div
                id="erreur-mot-de-passe"
                class="<?= !empty($passwordError) ? '' : 'hidden' ?> rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700"
                role="alert"
            >
                <?= htmlspecialchars($passwordError ?? '', ENT_QUOTES, 'UTF-8') ?>
            </div>


            <!-- Ancien mot de passe -->
            <div>

                <label
                    for="ancien_mot_de_passe"
                    class="mb-2 block text-sm font-semibold text-[#101D33]"
                >
                    Ancien mot de passe
                </label>

                <input
                    type="password"
                    id="ancien_mot_de_passe"
                    name="ancien_mot_de_passe"
                    autocomplete="current-password"
                    required
                    class="h-12 w-full rounded-xl border border-[#E1E5DC] bg-[#FCFCFA] px-4 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                >

            </div>


            <!-- Nouveau mot de passe -->
            <div>

                <label
                    for="nouveau_mot_de_passe"
                    class="mb-2 block text-sm font-semibold text-[#101D33]"
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
                    class="h-12 w-full rounded-xl border border-[#E1E5DC] bg-[#FCFCFA] px-4 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                >

                <p class="mt-1 text-xs text-[#71869A]">
                    Au moins 8 caractères.
                </p>

            </div>


            <!-- Confirmation -->
            <div>

                <label
                    for="confirmation_mot_de_passe"
                    class="mb-2 block text-sm font-semibold text-[#101D33]"
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
                    class="h-12 w-full rounded-xl border border-[#E1E5DC] bg-[#FCFCFA] px-4 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:ring-4 focus:ring-[#7E9833]/10"
                >

            </div>


            <!-- Boutons -->
            <div class="flex flex-col-reverse gap-3 border-t border-[#E9EBE3] pt-5 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    id="annuler-modal-mot-de-passe"
                    class="rounded-xl border border-[#E1E5DC] px-5 py-3 text-sm font-semibold text-[#101D33] transition hover:bg-[#F5F3ED]"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#7E9833] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#6B812B]"
                >
                    Enregistrer
                </button>

            </div>

        </form>

    </div>

</div>


<!-- =============================================================
     JAVASCRIPT
============================================================= -->
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


    // Vérifier la présence des éléments
    if (
        !modal ||
        !ouvrir ||
        !formulaire ||
        !erreur ||
        !ancien ||
        !nouveau ||
        !confirmation
    ) {
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


    // Fermer le modal
    function fermerModal() {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');
    }


    // Bouton d'ouverture
    ouvrir.addEventListener('click', function () {

        erreur.textContent = '';
        erreur.classList.add('hidden');

        ouvrirModal();
    });


    // Bouton de fermeture
    if (fermer) {
        fermer.addEventListener('click', fermerModal);
    }


    // Bouton Annuler
    if (annuler) {
        annuler.addEventListener('click', fermerModal);
    }


    // Fermer en cliquant à l'extérieur
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


    // Vérification avant envoi
    formulaire.addEventListener('submit', function (event) {

        erreur.textContent = '';
        erreur.classList.add('hidden');


        // Vérifier les champs obligatoires
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


        // Vérifier la longueur du mot de passe
        if (nouveau.value.length < 8) {

            event.preventDefault();

            erreur.textContent =
                'Le nouveau mot de passe doit contenir au moins 8 caractères.';

            erreur.classList.remove('hidden');

            nouveau.focus();

            return;
        }


        // Vérifier la confirmation
        if (nouveau.value !== confirmation.value) {

            event.preventDefault();

            erreur.textContent =
                'Les deux nouveaux mots de passe ne correspondent pas.';

            erreur.classList.remove('hidden');

            confirmation.focus();

            return;
        }

        // Si les vérifications réussissent,
        // le formulaire est envoyé au contrôleur PHP.

    });


    // Réouvrir le modal si le serveur a renvoyé une erreur
    if (erreur.textContent.trim() !== '') {
        ouvrirModal();
    }

});


// Faire disparaître le message de succès
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
