
<?php
/**
 * Vue : Mon profil — IMMO-SN
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
    <header class="sticky top-0 z-30 border-b border-[#E9E1D2] bg-white/95 px-4 py-5 shadow-sm backdrop-blur-xl sm:px-6 md:px-8">

        <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">

            <div class="min-w-0">

                <div class="mb-2 flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.18em]">

                    <span class="text-[#8A6500]">
                        Espace membre
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-3 w-3 text-[#B6AA91]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                    </svg>

                    <span class="text-[#8490A0]">
                        Profil
                    </span>

                </div>

                <h1 class="break-words text-xl font-extrabold tracking-tight text-[#071B36] sm:text-2xl">
                    Mon profil
                </h1>

                <p class="mt-1 text-xs text-[#718096] sm:text-sm">
                    Gérez vos informations personnelles et la sécurité de votre compte.
                </p>

            </div>


            <!-- IDENTITÉ DU MEMBRE -->
            <div class="hidden items-center gap-3 rounded-2xl border border-[#E9E1D2] bg-[#FAF8F3] px-3 py-2.5 sm:flex">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-xs font-extrabold text-[#F5B800] shadow-sm">
                    <?= htmlspecialchars($initiales, ENT_QUOTES, 'UTF-8') ?>
                </div>

                <div class="min-w-0 text-left">

                    <p class="max-w-[180px] truncate text-xs font-bold text-[#071B36]">
                        <?= htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <p class="mt-1 text-[10px] font-medium text-[#8490A0]">
                        Membre IMMO-SN
                    </p>

                </div>

            </div>

        </div>

    </header>


    <!-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== -->
    <main class="px-4 py-6 sm:px-6 md:px-8 md:py-8">

        <div class="mx-auto max-w-7xl">


            <!-- =================================================
                 CARTE PROFIL PRINCIPALE
            ================================================== -->
            <section class="overflow-hidden rounded-[28px] border border-[#E9E1D2] bg-white shadow-[0_15px_45px_rgba(7,27,54,0.07)]">

                <!-- BANDEAU DÉCORATIF -->
                <div class="relative h-36 overflow-hidden bg-[#071B36] md:h-44">

                    <!-- Motifs décoratifs -->
                    <div class="absolute -right-12 -top-24 h-64 w-64 rounded-full border border-[#F5B800]/20 bg-[#F5B800]/[0.06]"></div>

                    <div class="absolute -right-4 -top-12 h-44 w-44 rounded-full border border-[#F5B800]/15"></div>

                    <div class="absolute right-1/3 -bottom-28 h-56 w-56 rounded-full border border-white/10 bg-white/[0.03]"></div>

                    <div class="absolute left-1/3 top-8 h-28 w-28 rounded-full bg-[#F5B800]/10 blur-3xl"></div>

                    <div class="absolute bottom-0 left-0 h-px w-full bg-gradient-to-r from-transparent via-[#F5B800] to-transparent"></div>


                    <!-- IDENTITÉ DE MARQUE -->
                    <div class="absolute left-6 top-6 md:left-9 md:top-7">

                        <div class="text-lg font-extrabold tracking-wide text-white">
                            IMMO<span class="text-[#F5B800]">-SN</span>
                        </div>

                        <p class="mt-1 text-[9px] font-semibold uppercase tracking-[0.24em] text-white/50">
                            Immobilier & confiance
                        </p>

                    </div>


                    <!-- STATUT DU COMPTE -->
                    <div class="absolute right-5 top-5 hidden items-center gap-2 rounded-full border border-white/10 bg-white/[0.08] px-3.5 py-2 backdrop-blur-sm sm:flex">

                        <span class="h-2 w-2 rounded-full bg-[#69B781]"></span>

                        <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-white">
                            Compte actif
                        </span>

                    </div>

                </div>


                <!-- IDENTITÉ DU MEMBRE -->
                <div class="relative px-5 pb-7 sm:px-7 md:px-9">

                    <div class="-mt-16 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">

                            <!-- AVATAR -->
                            <div class="relative w-fit shrink-0">

                                <div class="flex h-32 w-32 items-center justify-center rounded-[28px] border-[6px] border-white bg-[#F5F0E4] text-3xl font-extrabold text-[#8A6500] shadow-[0_10px_30px_rgba(7,27,54,0.16)]">

                                    <?= htmlspecialchars($initiales, ENT_QUOTES, 'UTF-8') ?>

                                </div>

                                <span
                                    class="absolute bottom-2 right-2 flex h-7 w-7 items-center justify-center rounded-full border-[3px] border-white bg-[#5CAA70]"
                                    title="Compte actif"
                                >
                                    <span class="h-2 w-2 rounded-full bg-white"></span>
                                </span>

                            </div>


                            <!-- NOM ET RÔLE -->
                            <div class="min-w-0 pb-1">

                                <p class="mb-1.5 text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A18442]">
                                    Profil membre
                                </p>

                                <h2 class="break-words text-xl font-extrabold tracking-tight text-[#071B36] sm:text-2xl">
                                    <?= htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8') ?>
                                </h2>

                                <div class="mt-3 flex flex-wrap items-center gap-2">

                                    <span class="rounded-lg border border-[#E9DFC8] bg-[#FAF6EB] px-3 py-1.5 text-[10px] font-bold text-[#8A6500]">
                                        <?= htmlspecialchars($profil['role'] ?? 'Membre', ENT_QUOTES, 'UTF-8') ?>
                                    </span>

                                    <span class="text-[11px] text-[#8490A0]">
                                        Membre IMMO-SN
                                    </span>

                                </div>

                            </div>

                        </div>


                        <!-- INDICATEUR -->
                        <div class="hidden items-center gap-2 rounded-xl border border-[#E9E1D2] bg-[#FAF8F3] px-4 py-3 lg:flex">

                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#071B36] text-[#F5B800]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>

                            </span>

                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-wider text-[#8A6500]">
                                    Espace personnel
                                </p>
                                <p class="mt-0.5 text-xs font-bold text-[#071B36]">
                                    Votre compte IMMO-SN
                                </p>
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
                <section class="overflow-hidden rounded-[24px] border border-[#E9E1D2] bg-white shadow-[0_8px_30px_rgba(7,27,54,0.04)]">

                    <!-- EN-TÊTE -->
                    <div class="flex flex-col items-start justify-between gap-4 border-b border-[#EEE9DF] px-5 py-5 sm:flex-row sm:items-center sm:px-6">

                        <div class="flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                                </svg>

                            </div>

                            <div>
                                <h2 class="text-sm font-extrabold text-[#071B36]">
                                    Informations personnelles
                                </h2>

                                <p class="mt-1 text-[11px] text-[#8490A0]">
                                    Gérez vos coordonnées personnelles
                                </p>
                            </div>

                        </div>

                        <span class="rounded-lg border border-[#E9E1D2] bg-[#FAF8F3] px-3 py-1.5 text-[9px] font-bold uppercase tracking-wider text-[#8A6500]">
                            Mon profil
                        </span>

                    </div>


                    <!-- CONTENU -->
                    <div class="p-5 sm:p-6">

                        <!-- MESSAGE DE SUCCÈS -->
                        <?php if (!empty($success)): ?>

                            <div
                                data-profil-success
                                class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
                                role="status"
                            >

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mt-0.5 h-5 w-5 shrink-0"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                                </svg>

                                <span>
                                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                                </span>

                            </div>

                        <?php endif; ?>


                        <!-- MESSAGE D'ERREUR -->
                        <?php if (!empty($error)): ?>

                            <div
                                class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                                role="alert"
                            >

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mt-0.5 h-5 w-5 shrink-0"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3h.008v.008H12V15.75ZM10.29 3.86 2.82 17a2 2 0 0 0 1.73 3h14.9a2 2 0 0 0 1.73-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                                </svg>

                                <span>
                                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                                </span>

                            </div>

                        <?php endif; ?>


                        <!-- FORMULAIRE PROFIL -->
                        <form action="/immo-sn/member/profil" method="POST">

                            <div class="grid gap-5 sm:grid-cols-2">


                                <!-- PRÉNOM -->
                                <div>

                                    <label for="prenom" class="mb-2 block text-xs font-bold text-[#071B36]">
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
                                        class="h-12 w-full rounded-xl border border-[#E8E3D9] bg-[#FCFBF8] px-4 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5ADB6] focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                                    >

                                </div>


                                <!-- NOM -->
                                <div>

                                    <label for="nom" class="mb-2 block text-xs font-bold text-[#071B36]">
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
                                        class="h-12 w-full rounded-xl border border-[#E8E3D9] bg-[#FCFBF8] px-4 text-sm text-[#071B36] outline-none transition placeholder:text-[#A5ADB6] focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                                    >

                                </div>


                                <!-- EMAIL -->
                                <div class="sm:col-span-2">

                                    <label for="email" class="mb-2 block text-xs font-bold text-[#071B36]">
                                        Adresse email
                                    </label>

                                    <div class="relative">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#A18442]"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5H4.5a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.594a2.25 2.25 0 0 1-2.12 0L2.25 6.75"/>
                                        </svg>

                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            value="<?= htmlspecialchars($profil['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            maxlength="255"
                                            autocomplete="email"
                                            required
                                            class="h-12 w-full rounded-xl border border-[#E8E3D9] bg-[#FCFBF8] pl-11 pr-4 text-sm text-[#071B36] outline-none transition focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                                        >

                                    </div>

                                </div>


                                <!-- TÉLÉPHONE -->
                                <div>

                                    <label for="telephone" class="mb-2 block text-xs font-bold text-[#071B36]">
                                        Téléphone
                                    </label>

                                    <div class="relative">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#A18442]"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372a1.125 1.125 0 0 0-.852-1.093l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.035 12.035 0 0 1-7.186-7.186 1.125 1.125 0 0 1 .38-1.21l1.293-.97a1.125 1.125 0 0 0 .417-1.173L6.92 3.057a1.125 1.125 0 0 0-1.093-.852H4.5A2.25 2.25 0 0 0 2.25 4.455V6.75Z"/>
                                        </svg>

                                        <input
                                            id="telephone"
                                            name="telephone"
                                            type="tel"
                                            value="<?= htmlspecialchars($profil['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            maxlength="30"
                                            autocomplete="tel"
                                            class="h-12 w-full rounded-xl border border-[#E8E3D9] bg-[#FCFBF8] pl-11 pr-4 text-sm text-[#071B36] outline-none transition focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                                        >

                                    </div>

                                </div>


                                <!-- VILLE -->
                                <div>

                                    <label for="ville" class="mb-2 block text-xs font-bold text-[#071B36]">
                                        Ville
                                    </label>

                                    <div class="relative">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#A18442]"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7.5-4.5 7.5-11.25a7.5 7.5 0 1 0-15 0C4.5 16.5 12 21 12 21Z"/>
                                            <circle cx="12" cy="9.75" r="2.25"/>
                                        </svg>

                                        <input
                                            id="ville"
                                            name="ville"
                                            type="text"
                                            value="<?= htmlspecialchars($profil['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                            maxlength="150"
                                            autocomplete="address-level2"
                                            class="h-12 w-full rounded-xl border border-[#E8E3D9] bg-[#FCFBF8] pl-11 pr-4 text-sm text-[#071B36] outline-none transition focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                                        >

                                    </div>

                                </div>

                            </div>


                            <!-- ENREGISTREMENT -->
                            <div class="mt-7 flex flex-col gap-4 border-t border-[#EEE9DF] pt-5 sm:flex-row sm:items-center sm:justify-between">

                                <div class="flex items-start gap-2">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="mt-0.5 h-4 w-4 shrink-0 text-[#A18442]"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3h.008v.008H12V15.75ZM10.29 3.86 2.82 17a2 2 0 0 0 1.73 3h14.9a2 2 0 0 0 1.73-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                                    </svg>

                                    <p class="max-w-xs text-[10px] leading-5 text-[#8490A0]">
                                        Vérifiez vos informations avant d'enregistrer vos modifications.
                                    </p>

                                </div>

                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-3.5 text-xs font-extrabold text-white shadow-md shadow-[#071B36]/10 transition hover:-translate-y-0.5 hover:bg-[#102C50] hover:shadow-lg sm:w-auto"
                                >

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-4 w-4 text-[#F5B800]"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
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
                    <section class="overflow-hidden rounded-[24px] border border-[#E9E1D2] bg-white shadow-[0_8px_30px_rgba(7,27,54,0.04)]">

                        <div class="border-b border-[#EEE9DF] px-5 py-5 sm:px-6">

                            <div class="flex items-center justify-between gap-3">

                                <div>
                                    <h2 class="text-sm font-extrabold text-[#071B36]">
                                        Mon compte
                                    </h2>

                                    <p class="mt-1 text-[11px] text-[#8490A0]">
                                        Informations générales
                                    </p>
                                </div>

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 3.743-.704 4.5 4.5 0 0 0-7.486 0A9.38 9.38 0 0 0 15 19.128ZM15 19.128a9.38 9.38 0 0 1-6-2.128m6 2.128c.546 0 1.083-.046 1.607-.134M9 17a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z"/>
                                    </svg>

                                </div>

                            </div>

                        </div>


                        <div class="p-5 sm:p-6">

                            <!-- TYPE DE COMPTE -->
                            <div class="flex items-center gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-[#E9E1D2] bg-[#FAF8F3] text-[#8A6500]">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 3.743-.704 4.5 4.5 0 0 0-7.486 0A9.38 9.38 0 0 0 15 19.128ZM15 19.128a9.38 9.38 0 0 1-6-2.128m6 2.128c.546 0 1.083-.046 1.607-.134M9 17a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-[9px] font-bold uppercase tracking-[0.15em] text-[#929BA6]">
                                        Type de compte
                                    </p>

                                    <p class="mt-1 truncate text-sm font-extrabold text-[#071B36]">
                                        <?= htmlspecialchars($profil['role'] ?? 'Membre', ENT_QUOTES, 'UTF-8') ?>
                                    </p>

                                </div>

                            </div>


                            <div class="my-5 border-t border-[#EEE9DF]"></div>


                            <!-- STATUT -->
                            <div class="flex items-center gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-green-100 bg-green-50 text-green-700">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25L15 11.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-[9px] font-bold uppercase tracking-[0.15em] text-[#929BA6]">
                                        Statut du compte
                                    </p>

                                    <div class="mt-1.5 flex items-center gap-2">

                                        <span class="h-2 w-2 rounded-full bg-[#5CAA70]"></span>

                                        <span class="text-sm font-extrabold text-green-700">
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
                    <section class="overflow-hidden rounded-[24px] border border-[#E9E1D2] bg-white shadow-[0_8px_30px_rgba(7,27,54,0.04)]">

                        <div class="border-b border-[#EEE9DF] px-5 py-5 sm:px-6">

                            <div class="flex items-center gap-3">

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75a2.25 2.25 0 0 1-2.25-2.25v-6a2.25 2.25 0 0 1 2.25-2.25Z"/>
                                    </svg>

                                </div>

                                <div>
                                    <h2 class="text-sm font-extrabold text-[#071B36]">
                                        Sécurité
                                    </h2>

                                    <p class="mt-1 text-[11px] text-[#8490A0]">
                                        Protection de votre compte
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="p-5 sm:p-6">

                            <div class="rounded-xl border border-[#E9E1D2] bg-[#FAF8F3] p-4">

                                <div class="flex items-start gap-3">

                                    <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#071B36] text-[#F5B800]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75 5.25 6v5.25c0 4.5 2.875 7.625 6.75 9 3.875-1.375 6.75-4.5 6.75-9V6L12 3.75Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.5 12 1.75 1.75L14.75 10"/>
                                        </svg>

                                    </div>

                                    <p class="text-[11px] leading-5 text-[#718096]">
                                        Pour protéger votre compte, utilisez un mot de passe unique et difficile à deviner. Évitez de partager vos identifiants.
                                    </p>

                                </div>

                            </div>


                            <button
                                type="button"
                                id="ouvrir-modal-mot-de-passe"
                                class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-[#071B36] bg-white px-4 py-3 text-xs font-extrabold text-[#071B36] transition hover:bg-[#071B36] hover:text-white"
                            >

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75a2.25 2.25 0 0 1-2.25-2.25v-6a2.25 2.25 0 0 1 2.25-2.25Z"/>
                                </svg>

                                Modifier mon mot de passe

                            </button>

                        </div>

                    </section>


                    <!-- =================================================
                         DÉCONNEXION
                    ================================================== -->
                    <section class="rounded-[24px] border border-[#E9E1D2] bg-white p-5 shadow-[0_8px_30px_rgba(7,27,54,0.03)] sm:p-6">

                        <div class="flex items-start gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-[#F1DDD5] bg-[#FCF3EF] text-[#A45B36]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H9m0 0 3-3m-3 3 3 3"/>
                                </svg>

                            </div>


                            <div class="min-w-0">

                                <p class="text-sm font-extrabold text-[#071B36]">
                                    Vous souhaitez vous déconnecter ?
                                </p>

                                <p class="mt-1 text-[11px] leading-5 text-[#8490A0]">
                                    Votre session sera fermée sur cet appareil.
                                </p>

                                <a
                                    href="/immo-sn/logout"
                                    class="mt-3 inline-flex items-center gap-2 text-xs font-extrabold text-[#A45B36] transition hover:text-[#7F4228] hover:underline"
                                >
                                    Se déconnecter

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-3.5 w-3.5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/>
                                    </svg>

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
    class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-[#071B36]/70 px-3 py-4 backdrop-blur-sm sm:items-center sm:px-4 sm:py-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="titre-modal-mot-de-passe"
>

    <div class="my-auto w-full min-w-0 max-w-lg shrink-0 overflow-hidden rounded-2xl border border-[#E9E1D2] bg-white shadow-2xl">

        <!-- EN-TÊTE -->
        <div class="relative overflow-hidden bg-[#071B36] px-5 py-5 sm:px-6">

            <div class="absolute -right-8 -top-12 h-32 w-32 rounded-full border border-[#F5B800]/20 bg-[#F5B800]/[0.06]"></div>

            <div class="relative flex items-start justify-between gap-3">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#F5B800]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75a2.25 2.25 0 0 1-2.25-2.25v-6a2.25 2.25 0 0 1 2.25-2.25Z"/>
                        </svg>

                    </div>

                    <div>
                        <h2
                            id="titre-modal-mot-de-passe"
                            class="text-base font-extrabold text-white sm:text-lg"
                        >
                            Modifier mon mot de passe
                        </h2>

                        <p class="mt-1 text-[11px] text-white/60">
                            Sécurisez votre compte IMMO-SN
                        </p>
                    </div>

                </div>


                <button
                    type="button"
                    id="fermer-modal-mot-de-passe"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white/70 transition hover:bg-white/10 hover:text-white"
                    aria-label="Fermer"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/>
                    </svg>

                </button>

            </div>

        </div>


        <!-- FORMULAIRE -->
        <form
            action="/immo-sn/member/mot-de-passe"
            method="POST"
            id="formulaire-mot-de-passe"
            class="space-y-5 p-5 sm:p-6"
        >

            <!-- ERREUR -->
            <div
                id="erreur-mot-de-passe"
                class="<?= !empty($passwordError) ? '' : 'hidden' ?> rounded-xl border border-red-200 bg-red-50 p-3 text-xs leading-5 text-red-700"
                role="alert"
            >
                <?= htmlspecialchars($passwordError ?? '', ENT_QUOTES, 'UTF-8') ?>
            </div>


            <!-- ANCIEN MOT DE PASSE -->
            <div>

                <label
                    for="ancien_mot_de_passe"
                    class="mb-2 block text-xs font-bold text-[#071B36]"
                >
                    Ancien mot de passe
                </label>

                <input
                    type="password"
                    id="ancien_mot_de_passe"
                    name="ancien_mot_de_passe"
                    autocomplete="current-password"
                    required
                    class="h-12 w-full rounded-xl border border-[#E8E3D9] bg-[#FCFBF8] px-4 text-sm text-[#071B36] outline-none transition focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                >

            </div>


            <!-- NOUVEAU MOT DE PASSE -->
            <div>

                <label
                    for="nouveau_mot_de_passe"
                    class="mb-2 block text-xs font-bold text-[#071B36]"
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
                    class="h-12 w-full rounded-xl border border-[#E8E3D9] bg-[#FCFBF8] px-4 text-sm text-[#071B36] outline-none transition focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                >

                <p class="mt-1.5 text-[10px] text-[#8490A0]">
                    Au moins 8 caractères.
                </p>

            </div>


            <!-- CONFIRMATION -->
            <div>

                <label
                    for="confirmation_mot_de_passe"
                    class="mb-2 block text-xs font-bold text-[#071B36]"
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
                    class="h-12 w-full rounded-xl border border-[#E8E3D9] bg-[#FCFBF8] px-4 text-sm text-[#071B36] outline-none transition focus:border-[#D7A200] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10"
                >

            </div>


            <!-- BOUTONS -->
            <div class="flex flex-col-reverse gap-3 border-t border-[#EEE9DF] pt-5 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    id="annuler-modal-mot-de-passe"
                    class="rounded-xl border border-[#E8E3D9] px-5 py-3 text-xs font-bold text-[#071B36] transition hover:bg-[#F5F3ED]"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-3 text-xs font-extrabold text-white shadow-sm transition hover:bg-[#102C50]"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4 text-[#F5B800]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                    </svg>

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
