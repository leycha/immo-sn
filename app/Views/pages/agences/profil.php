
<?php

use Core\Session;

$nom = $responsable['nom'] ?? '';
$prenom = $responsable['prenom'] ?? '';
$nomComplet = trim($prenom . ' ' . $nom);

$initiale = mb_strtoupper(
    mb_substr($prenom ?: $nom ?: 'U', 0, 1, 'UTF-8'),
    'UTF-8'
);

$statutCompte = $responsable['status'] ?? 'inconnu';

$statuts = [
    'actif' => ['Compte actif', 'bg-[#F5B800]/15 text-[#806000] border-[#F5B800]/40'],
    'inactif' => ['Compte inactif', 'bg-amber-50 text-amber-700 border-amber-200'],
    'suspendu' => ['Compte suspendu', 'bg-red-50 text-red-700 border-red-200'],
];

[$statutLabel, $statutStyle] = $statuts[$statutCompte] ?? [
    ucfirst($statutCompte),
    'bg-gray-100 text-gray-700 border-gray-200'
];

$e = static function ($valeur): string {
    return htmlspecialchars((string) ($valeur ?? ''), ENT_QUOTES, 'UTF-8');
};

?>

<div class="min-h-screen bg-[#F4F6F9] text-[#071B36]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- =====================================================
             EN-TÊTE
        ====================================================== -->
        <header class="sticky top-0 z-30 flex flex-col gap-3 border-b border-slate-200 bg-white/95 px-4 py-4 backdrop-blur-xl sm:flex-row sm:items-center sm:justify-between sm:px-8 sm:py-5">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#A17B00]">
                    Espace professionnel
                </p>

                <h1 class="mt-1 text-xl font-bold tracking-tight text-[#071B36] sm:text-2xl">
                    Mon profil
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Gérez vos informations personnelles et votre identité professionnelle.
                </p>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full border px-3 py-2 text-xs font-bold <?= $statutStyle ?>">
                <span class="h-2 w-2 rounded-full bg-current"></span>
                <?= $e($statutLabel) ?>
            </span>

        </header>

        <!-- =====================================================
             CONTENU PRINCIPAL
        ====================================================== -->
        <div class="mx-auto max-w-7xl space-y-7 px-5 py-8 sm:px-8">

            <!-- =================================================
                 IDENTITÉ DU RESPONSABLE
            ================================================== -->
            <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">

                <!-- BANNIÈRE PROFIL -->
                <div class="relative overflow-hidden bg-[#071B36] px-6 py-8 text-white sm:px-9 sm:py-10">

                    <!-- Décoration -->
                    <div class="pointer-events-none absolute -right-16 -top-24 h-64 w-64 rounded-full border-[30px] border-[#F5B800]/10"></div>
                    <div class="pointer-events-none absolute -right-5 -top-14 h-48 w-48 rounded-full border border-white/5"></div>
                    <div class="pointer-events-none absolute -bottom-20 left-1/3 h-48 w-48 rounded-full bg-[#F5B800]/10 blur-3xl"></div>

                    <div class="relative">

                        <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>

                            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-300">
                                Espace personnel
                            </p>
                        </div>

                        <div class="mt-6 flex flex-col items-center gap-6 sm:flex-row sm:items-center">

                            <!-- PHOTO DE PROFIL -->
                            <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-[#F5B800]/30 bg-[#F5B800] text-4xl font-black text-[#071B36] shadow-xl ring-4 ring-white/5">

                                <?php if (!empty($responsable['photo_profil'])): ?>

                                    <img
                                        src="<?= $e($responsable['photo_profil']) ?>"
                                        alt="Photo de profil"
                                        class="h-full w-full object-cover"
                                    >

                                <?php else: ?>

                                    <?= $e($initiale) ?>

                                <?php endif; ?>

                            </div>

                            <!-- INFORMATIONS DU RESPONSABLE -->
                            <div class="min-w-0 text-center sm:text-left">

                                <p class="text-sm font-medium text-slate-300">
                                    Responsable d'agence immobilière
                                </p>

                                <h2 class="mt-2 break-words font-['Playfair_Display'] text-2xl font-bold tracking-tight text-white sm:text-3xl">
                                    <?= $e($nomComplet) ?>
                                </h2>

                                <p class="mt-2 text-sm text-slate-400">
                                    Votre identité au sein du réseau IMMO-SN
                                </p>

                                <div class="mt-4 flex flex-wrap justify-center gap-2 sm:justify-start">

                                    <!-- STATUT -->
                                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white">
                                        <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>
                                        <?= $e($statutLabel) ?>
                                    </span>

                                    <!-- VÉRIFICATION -->
                                    <?php if (!empty($responsable['is_verified'])): ?>

                                        <span class="inline-flex items-center gap-2 rounded-full bg-[#F5B800]/15 px-3 py-1.5 text-xs font-semibold text-[#F5B800]">

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
                <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 sm:p-8">

                    <!-- NOM -->
                    <div class="group rounded-2xl border border-slate-200 bg-[#F4F6F9] p-5 transition hover:border-[#F5B800]/60 hover:bg-white">

                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071B36] text-sm font-bold text-[#F5B800]">
                                N
                            </div>

                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-400">
                                Nom
                            </p>
                        </div>

                        <p class="mt-4 break-words text-base font-bold text-[#071B36]">
                            <?= $e($nom !== '' ? $nom : 'Non renseigné') ?>
                        </p>

                    </div>

                    <!-- PRÉNOM -->
                    <div class="group rounded-2xl border border-slate-200 bg-[#F4F6F9] p-5 transition hover:border-[#F5B800]/60 hover:bg-white">

                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071B36] text-sm font-bold text-[#F5B800]">
                                P
                            </div>

                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-400">
                                Prénom
                            </p>
                        </div>

                        <p class="mt-4 break-words text-base font-bold text-[#071B36]">
                            <?= $e($prenom !== '' ? $prenom : 'Non renseigné') ?>
                        </p>

                    </div>

                    <!-- EMAIL -->
                    <div class="group rounded-2xl border border-slate-200 bg-[#F4F6F9] p-5 transition hover:border-[#F5B800]/60 hover:bg-white">

                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">

                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6"/>
                                </svg>

                            </div>

                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-400">
                                Adresse e-mail
                            </p>
                        </div>

                        <p class="mt-4 break-all text-base font-bold text-[#071B36]">
                            <?= $e($responsable['email'] ?? 'Non renseigné') ?>
                        </p>

                    </div>

                    <!-- TÉLÉPHONE -->
                    <div class="group rounded-2xl border border-slate-200 bg-[#F4F6F9] p-5 transition hover:border-[#F5B800]/60 hover:bg-white">

                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">

                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3h4l2 5-3 2a15 15 0 006 6l2-3 5 2v4a2 2 0 01-2 2A18 18 0 013 5a2 2 0 012-2z"/>
                                </svg>

                            </div>

                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-400">
                                Téléphone
                            </p>
                        </div>

                        <p class="mt-4 break-words text-base font-bold text-[#071B36]">
                            <?= $e($responsable['telephone'] ?? 'Non renseigné') ?>
                        </p>

                    </div>

                </div>

            </section>

            <!-- =================================================
                 MODIFICATION DES INFORMATIONS PERSONNELLES
            ================================================== -->
            <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <!-- TITRE SECTION -->
                <div class="mb-8">

                    <div class="flex items-center gap-3">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#F5B800]/15 text-[#A17B00]">

                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 4h4v4M20 4l-9 9M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#A17B00]">
                                Paramètres du compte
                            </p>

                            <h2 class="mt-1 text-xl font-extrabold text-[#071B36]">
                                Informations personnelles
                            </h2>
                        </div>

                    </div>

                    <p class="mt-4 text-sm leading-6 text-slate-500">
                        Actualisez vos informations personnelles afin de maintenir votre profil professionnel à jour.
                    </p>

                </div>

                <!-- FORMULAIRE -->
                <form
                    method="POST"
                    action="/immo-sn/agence/profil/modifier"
                    class="space-y-6"
                >

                    <!-- TOKEN CSRF -->
                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= $e(Session::csrfToken()) ?>"
                    >

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <!-- NOM -->
                        <div>

                            <label for="nom" class="mb-2 block text-sm font-bold text-[#071B36]">
                                Nom <span class="text-[#D6A000]">*</span>
                            </label>

                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                required
                                value="<?= $e($responsable['nom'] ?? '') ?>"
                                placeholder="Votre nom"
                                class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] transition placeholder:text-slate-400 focus:border-[#F5B800] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                        </div>

                        <!-- PRÉNOM -->
                        <div>

                            <label for="prenom" class="mb-2 block text-sm font-bold text-[#071B36]">
                                Prénom <span class="text-[#D6A000]">*</span>
                            </label>

                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                required
                                value="<?= $e($responsable['prenom'] ?? '') ?>"
                                placeholder="Votre prénom"
                                class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] transition placeholder:text-slate-400 focus:border-[#F5B800] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                        </div>

                        <!-- EMAIL -->
                        <div>

                            <label for="email" class="mb-2 block text-sm font-bold text-[#071B36]">
                                Adresse e-mail <span class="text-[#D6A000]">*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                value="<?= $e($responsable['email'] ?? '') ?>"
                                placeholder="exemple@email.com"
                                class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] transition placeholder:text-slate-400 focus:border-[#F5B800] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                        </div>

                        <!-- TÉLÉPHONE -->
                        <div>

                            <label for="telephone" class="mb-2 block text-sm font-bold text-[#071B36]">
                                Téléphone
                            </label>

                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                value="<?= $e($responsable['telephone'] ?? '') ?>"
                                placeholder="+221 77 000 00 00"
                                class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] transition placeholder:text-slate-400 focus:border-[#F5B800] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                        </div>

                        <!-- VILLE -->
                        <div>

                            <label for="ville" class="mb-2 block text-sm font-bold text-[#071B36]">
                                Ville
                            </label>

                            <input
                                type="text"
                                id="ville"
                                name="ville"
                                value="<?= $e($responsable['ville'] ?? '') ?>"
                                placeholder="Votre ville"
                                class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] transition placeholder:text-slate-400 focus:border-[#F5B800] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                        </div>

                        <!-- QUARTIER -->
                        <div>

                            <label for="quartier" class="mb-2 block text-sm font-bold text-[#071B36]">
                                Quartier
                            </label>

                            <input
                                type="text"
                                id="quartier"
                                name="quartier"
                                value="<?= $e($responsable['quartier'] ?? '') ?>"
                                placeholder="Votre quartier"
                                class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] transition placeholder:text-slate-400 focus:border-[#F5B800] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#F5B800]/10"
                            >

                        </div>

                    </div>

                    <!-- BIOGRAPHIE -->
                    <div>

                        <label for="bio" class="mb-2 block text-sm font-bold text-[#071B36]">
                            Biographie
                        </label>

                        <textarea
                            id="bio"
                            name="bio"
                            rows="4"
                            placeholder="Présentez-vous en quelques mots..."
                            class="w-full resize-y rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] transition placeholder:text-slate-400 focus:border-[#F5B800] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#F5B800]/10"
                        ><?= $e($responsable['bio'] ?? '') ?></textarea>

                        <p class="mt-2 text-xs text-slate-400">
                            Quelques mots sur vous et votre activité professionnelle.
                        </p>

                    </div>

                    <!-- BOUTON ENREGISTRER -->
                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#071B36] px-6 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#102C4D] hover:shadow-md sm:w-auto"
                        >

                            <svg class="h-4 w-4 text-[#F5B800]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>

                            Enregistrer mon profil
                        </button>
                        
                    <button
                        type="button"
                        onclick="ouvrirPopupAgence()"
                        class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#F5B800] px-5 py-3 text-sm font-bold text-[#071B36] shadow-sm transition hover:bg-[#DCA500]"
                    >
                        <span>✎</span>
                        Modifier mes informations
                    </button>


                    </div>

                </form>

            </section>
            <!-- POPUP MODIFICATION AGENCE -->
<div
    id="popupAgence"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#071B36]/75 px-4 py-6 backdrop-blur-sm"
    onclick="fermerPopupAgence(event)"
>

    <div class="flex max-h-[90vh] w-full min-w-0 max-w-2xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">

        <!-- HEADER POPUP -->
        <div class="relative overflow-hidden border-b border-slate-200 bg-[#071B36] px-6 py-5 text-white">

            <div class="pointer-events-none absolute -right-10 -top-16 h-40 w-40 rounded-full bg-[#F5B800]/15 blur-2xl"></div>

            <div class="relative flex items-center justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-[#F5B800]">
                        Profil professionnel
                    </p>

                    <h2 class="mt-1 text-xl font-extrabold">
                        Modifier mon agence
                    </h2>

                    <p class="mt-1 text-sm text-slate-300">
                        Mettez à jour les informations de votre agence.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="fermerPopupAgence()"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-2xl text-white transition hover:bg-white/10"
                    aria-label="Fermer"
                >
                    &times;
                </button>

            </div>

        </div>

        <!-- FORMULAIRE -->
        <form
            action="/immo-sn/agence/mon-agence/modifier"
            method="POST"
            class="space-y-5 overflow-y-auto p-6"
        >

            <input
                type="hidden"
                name="_csrf_token"
                value="<?= $e(Session::csrfToken()) ?>"
            >

            <div>

                <label class="mb-2 block text-sm font-bold text-[#071B36]">
                    Nom de l'agence
                </label>

                <input
                    type="text"
                    name="nom_agence"
                    value="<?= $e($agence['nom_agence'] ?? '') ?>"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] outline-none transition focus:border-[#F5B800] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                >

            </div>

            <div>

                <label class="mb-2 block text-sm font-bold text-[#071B36]">
                    Adresse
                </label>

                <input
                    type="text"
                    name="adresse"
                    value="<?= $e($agence['adresse'] ?? '') ?>"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] outline-none transition focus:border-[#F5B800] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                >

            </div>

            <div class="grid gap-5 sm:grid-cols-2">

                <div>

                    <label class="mb-2 block text-sm font-bold text-[#071B36]">
                        Téléphone
                    </label>

                    <input
                        type="text"
                        name="telephone"
                        value="<?= $e($agence['telephone'] ?? '') ?>"
                        class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] outline-none transition focus:border-[#F5B800] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                    >

                </div>

                <div>

                    <label class="mb-2 block text-sm font-bold text-[#071B36]">
                        Adresse e-mail
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="<?= $e($agence['email'] ?? '') ?>"
                        class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] outline-none transition focus:border-[#F5B800] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                    >

                </div>

            </div>

            <div>

                <label class="mb-2 block text-sm font-bold text-[#071B36]">
                    NINEA
                </label>

                <input
                    type="text"
                    name="ninea"
                    value="<?= $e($agence['ninea'] ?? '') ?>"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] outline-none transition focus:border-[#F5B800] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                >

            </div>

            <div>

                <label class="mb-2 block text-sm font-bold text-[#071B36]">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full resize-y rounded-xl border border-slate-200 bg-[#F4F6F9] px-4 py-3 text-sm text-[#071B36] outline-none transition focus:border-[#F5B800] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                ><?= $e($agence['description'] ?? '') ?></textarea>

            </div>

            <!-- BOUTONS -->
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="fermerPopupAgence()"
                    class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-500 transition hover:bg-[#F4F6F9]"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#F5B800] px-6 py-3 text-sm font-bold text-[#071B36] shadow-sm transition hover:bg-[#DCA500]"
                >
                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>

</div>

        </div>

        <!-- =====================================================
             PIED DE PAGE
        ====================================================== -->
        <footer class="mt-10 border-t border-slate-200 bg-white">

            <div class="mx-auto flex max-w-[1450px] flex-col gap-4 px-5 py-6 sm:px-7 md:flex-row md:items-center md:justify-between lg:px-9">

                <!-- IDENTITÉ IMMO-SN -->
                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#071B36] text-lg font-extrabold text-[#F5B800]">
                        I
                    </div>

                    <div>
                        <p class="text-sm font-extrabold tracking-wide text-[#071B36]">
                            IMMO-SN
                        </p>

                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Votre partenaire immobilier au Sénégal.
                        </p>
                    </div>

                </div>

                <!-- COPYRIGHT -->
                <div class="text-left md:text-right">

                    <p class="text-xs font-medium text-slate-500">
                        © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                    </p>

                    <p class="mt-1 text-[11px] font-semibold text-[#A17B00]">
                        Espace professionnel · Gestion de votre compte
                    </p>

                </div>

            </div>

        </footer>

    </main>

</div>

<!-- GESTION DU POPUP -->
<script>
    function ouvrirPopupAgence() {
        const popup = document.getElementById('popupAgence');

        if (!popup) return;

        popup.classList.remove('hidden');
        popup.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function fermerPopupAgence(event) {
        const popup = document.getElementById('popupAgence');

        if (!popup) return;

        if (!event || event.target === popup) {
            popup.classList.add('hidden');
            popup.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            fermerPopupAgence();
        }
    });
</script>
