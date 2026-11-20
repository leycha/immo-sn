
<?php

use Core\Session;

$nom = $responsable['nom'] ?? '';
$prenom = $responsable['prenom'] ?? '';
$nomComplet = trim($prenom . ' ' . $nom);

$initiale = strtoupper(substr($prenom ?: $nom ?: 'U', 0, 1));

$statutCompte = $responsable['status'] ?? 'inconnu';

$statuts = [
    'actif' => ['Compte actif', 'bg-[#7E9833]/10 text-[#668025] border-[#7E9833]/20'],
    'inactif' => ['Compte inactif', 'bg-amber-50 text-amber-700 border-amber-200'],
    'suspendu' => ['Compte suspendu', 'bg-red-50 text-red-700 border-red-200'],
];

[$statutLabel, $statutStyle] = $statuts[$statutCompte] ?? [
    ucfirst($statutCompte),
    'bg-gray-100 text-gray-700 border-gray-200'
];

?>

<div class="min-h-screen bg-[#F5F3ED]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- EN-TÊTE -->
        <header class="sticky top-0 z-30 flex items-center justify-between border-b border-gray-200/80 bg-white/95 px-4 py-4 backdrop-blur-xl sm:px-8 sm:py-5">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#7E9833]">
                    Espace professionnel
                </p>

                <h1 class="mt-1 text-xl font-bold tracking-tight text-[#101D33] sm:text-2xl">
                    Mon profil
                </h1>
            </div>

            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-semibold <?= $statutStyle ?>">
                <span class="h-2 w-2 rounded-full bg-current"></span>
                <?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8') ?>
            </span>

        </header>

        <!-- CONTENU PRINCIPAL -->
        <div class="mx-auto max-w-7xl space-y-7 px-5 py-8 sm:px-8">

            <!-- IDENTITÉ DU RESPONSABLE -->
            <section class="overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-sm">

                <!-- BANNIÈRE PROFIL -->
                <div class="relative overflow-hidden bg-[#101D33] px-6 py-8 text-white sm:px-9 sm:py-10">

                    <div class="absolute -right-16 -top-24 h-64 w-64 rounded-full border border-white/5"></div>
                    <div class="absolute -right-5 -top-14 h-48 w-48 rounded-full border border-white/5"></div>

                    <div class="relative">

                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#A8C66A]">
                            Espace personnel
                        </p>

                        <div class="mt-6 flex flex-col items-center gap-6 sm:flex-row">

                            <!-- PHOTO DE PROFIL -->
                            <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-white/20 bg-[#7E9833] text-4xl font-bold text-white shadow-xl">

                                <?php if (!empty($responsable['photo_profil'])): ?>

                                    <img
                                        src="<?= htmlspecialchars($responsable['photo_profil'], ENT_QUOTES, 'UTF-8') ?>"
                                        alt="Photo de profil"
                                        class="h-full w-full object-cover"
                                    >

                                <?php else: ?>

                                    <?= htmlspecialchars($initiale, ENT_QUOTES, 'UTF-8') ?>

                                <?php endif; ?>

                            </div>

                            <!-- INFORMATIONS DU RESPONSABLE -->
                            <div class="text-center sm:text-left">

                                <h2 class="break-words text-2xl font-bold tracking-tight sm:text-3xl">
                                    <?= htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8') ?>
                                </h2>

                                <p class="mt-2 text-sm text-white/60">
                                    Responsable d'agence immobilière
                                </p>

                                <div class="mt-4 flex flex-wrap justify-center gap-2 sm:justify-start">

                                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white">
                                        <span class="h-2 w-2 rounded-full bg-[#A8C66A]"></span>
                                        <?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8') ?>
                                    </span>

                                    <?php if (!empty($responsable['is_verified'])): ?>

                                        <span class="inline-flex items-center gap-2 rounded-full bg-[#7E9833]/20 px-3 py-1.5 text-xs font-semibold text-[#C3DE8B]">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a8 8 0 11-16 0 8 8 0 0116 0z"/>
                                            </svg>
                                            Compte vérifié
                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- INFORMATIONS DU PROFIL -->
                <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 sm:p-8">

                    <!-- NOM -->
                    <div class="rounded-2xl border border-gray-100 bg-[#F5F3ED]/60 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">
                            Nom
                        </p>
                        <p class="mt-2 font-semibold text-[#101D33]">
                            <?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>

                    <!-- PRÉNOM -->
                    <div class="rounded-2xl border border-gray-100 bg-[#F5F3ED]/60 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">
                            Prénom
                        </p>
                        <p class="mt-2 font-semibold text-[#101D33]">
                            <?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>

                    <!-- EMAIL -->
                    <div class="rounded-2xl border border-gray-100 bg-[#F5F3ED]/60 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">
                            Adresse e-mail
                        </p>
                        <p class="mt-2 break-all font-semibold text-[#101D33]">
                            <?= htmlspecialchars($responsable['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>

                    <!-- TÉLÉPHONE -->
                    <div class="rounded-2xl border border-gray-100 bg-[#F5F3ED]/60 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">
                            Téléphone
                        </p>
                        <p class="mt-2 font-semibold text-[#101D33]">
                            <?= htmlspecialchars($responsable['telephone'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>

                </div>

            </section>

            <!-- MODIFICATION DES INFORMATIONS PERSONNELLES -->
            <section class="rounded-3xl border border-gray-200/80 bg-white p-6 shadow-sm sm:p-8">

                <!-- TITRE SECTION -->
                <div class="mb-8">

                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#7E9833]/10 text-[#7E9833]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 4h4v4M20 4l-9 9M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#7E9833]">
                                Paramètres du compte
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-[#101D33]">
                                Informations personnelles
                            </h2>
                        </div>

                    </div>

                    <p class="mt-4 text-sm leading-6 text-gray-500">
                        Actualisez vos informations personnelles afin de maintenir votre profil professionnel à jour.
                    </p>

                </div>

                <!-- FORMULAIRE -->
                <form
                    method="POST"
                    action="/immo-sn/agence/profil/modifier"
                    class="space-y-6"
                >

                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= htmlspecialchars(Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <!-- NOM -->
                        <div>
                            <label for="nom" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Nom <span class="text-[#7E9833]">*</span>
                            </label>

                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                required
                                value="<?= htmlspecialchars($responsable['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Votre nom"
                                class="w-full rounded-xl border border-gray-200 bg-[#FAFAF8] px-4 py-3 text-sm text-[#101D33] transition placeholder:text-gray-400 focus:border-[#7E9833] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <!-- PRÉNOM -->
                        <div>
                            <label for="prenom" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Prénom <span class="text-[#7E9833]">*</span>
                            </label>

                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                required
                                value="<?= htmlspecialchars($responsable['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Votre prénom"
                                class="w-full rounded-xl border border-gray-200 bg-[#FAFAF8] px-4 py-3 text-sm text-[#101D33] transition placeholder:text-gray-400 focus:border-[#7E9833] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <!-- EMAIL -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Adresse e-mail <span class="text-[#7E9833]">*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                value="<?= htmlspecialchars($responsable['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="exemple@email.com"
                                class="w-full rounded-xl border border-gray-200 bg-[#FAFAF8] px-4 py-3 text-sm text-[#101D33] transition placeholder:text-gray-400 focus:border-[#7E9833] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <!-- TÉLÉPHONE -->
                        <div>
                            <label for="telephone" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Téléphone
                            </label>

                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                value="<?= htmlspecialchars($responsable['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="+221 77 000 00 00"
                                class="w-full rounded-xl border border-gray-200 bg-[#FAFAF8] px-4 py-3 text-sm text-[#101D33] transition placeholder:text-gray-400 focus:border-[#7E9833] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <!-- VILLE -->
                        <div>
                            <label for="ville" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Ville
                            </label>

                            <input
                                type="text"
                                id="ville"
                                name="ville"
                                value="<?= htmlspecialchars($responsable['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Votre ville"
                                class="w-full rounded-xl border border-gray-200 bg-[#FAFAF8] px-4 py-3 text-sm text-[#101D33] transition placeholder:text-gray-400 focus:border-[#7E9833] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                        <!-- QUARTIER -->
                        <div>
                            <label for="quartier" class="mb-2 block text-sm font-semibold text-[#101D33]">
                                Quartier
                            </label>

                            <input
                                type="text"
                                id="quartier"
                                name="quartier"
                                value="<?= htmlspecialchars($responsable['quartier'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Votre quartier"
                                class="w-full rounded-xl border border-gray-200 bg-[#FAFAF8] px-4 py-3 text-sm text-[#101D33] transition placeholder:text-gray-400 focus:border-[#7E9833] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#7E9833]/10"
                            >
                        </div>

                    </div>

                    <!-- BIOGRAPHIE -->
                    <div>

                        <label for="bio" class="mb-2 block text-sm font-semibold text-[#101D33]">
                            Biographie
                        </label>

                        <textarea
                            id="bio"
                            name="bio"
                            rows="4"
                            placeholder="Présentez-vous en quelques mots..."
                            class="w-full resize-y rounded-xl border border-gray-200 bg-[#FAFAF8] px-4 py-3 text-sm text-[#101D33] transition placeholder:text-gray-400 focus:border-[#7E9833] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#7E9833]/10"
                        ><?= htmlspecialchars($responsable['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

                        <p class="mt-2 text-xs text-gray-400">
                            Quelques mots sur vous et votre activité professionnelle.
                        </p>

                    </div>

                    <!-- BOUTON ENREGISTRER -->
                    <div class="flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#101D33] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1D2E4A] hover:shadow-md sm:w-auto"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>

                            Enregistrer mon profil
                        </button>

                    </div>

                </form>

            </section>

        </div>

        <!-- PIED DE PAGE -->
        <footer class="mt-auto border-t border-gray-200 bg-white">

            <div class="mx-auto flex max-w-[1450px] flex-col gap-4 px-5 py-6 sm:px-7 md:flex-row md:items-center md:justify-between lg:px-9">

                <!-- IDENTITÉ IMMO-SN -->
                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#101D33] text-lg font-extrabold text-[#A8C66A]">
                        I
                    </div>

                    <div>
                        <p class="text-sm font-extrabold tracking-wide text-[#101D33]">
                            IMMO-SN
                        </p>

                        <p class="mt-0.5 text-[11px] text-gray-500">
                            Votre partenaire immobilier au Sénégal.
                        </p>
                    </div>

                </div>

                <!-- COPYRIGHT -->
                <div class="text-left md:text-right">

                    <p class="text-xs font-medium text-gray-500">
                        © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                    </p>

                    <p class="mt-1 text-[11px] text-[#7E9833]">
                        Espace professionnel · Gestion de votre compte
                    </p>

                </div>

            </div>

        </footer>

    </main>

</div>
