
<?php

use Core\Session;

$nom = $responsable['nom'] ?? '';
$prenom = $responsable['prenom'] ?? '';
$nomComplet = trim($prenom . ' ' . $nom);

$initiale = strtoupper(substr($prenom ?: $nom ?: 'U', 0, 1));

$statutCompte = $responsable['status'] ?? 'inconnu';

$statuts = [
    'actif' => ['Compte actif', 'bg-green-100 text-green-800'],
    'inactif' => ['Compte inactif', 'bg-yellow-100 text-yellow-800'],
    'suspendu' => ['Compte suspendu', 'bg-red-100 text-red-800'],
];

[$statutLabel, $statutStyle] = $statuts[$statutCompte] ?? [
    ucfirst($statutCompte),
    'bg-gray-100 text-gray-700'
];

?>

<div class="min-h-screen bg-[#faf7f2]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- EN-TÊTE -->
      <header class="sticky top-0 z-30 flex items-center justify-between border-b border-[#eee5dc] bg-white/95 px-4 py-4 backdrop-blur sm:px-8 sm:py-5">            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-[#a98260]">
                    Espace professionnel
                </p>

                <h1 class="mt-1 text-xl font-bold text-[#30221b] sm:text-2xl">
                    Mon profil
                </h1>
            </div>

            <span class="rounded-full px-3 py-2 text-xs font-semibold <?= $statutStyle ?>">
                <?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8') ?>
            </span>

        </header>

        <!-- CONTENU PRINCIPAL -->
        <div class="mx-auto max-w-7xl space-y-7 px-5 py-8 sm:px-8">

          
<!-- IDENTITÉ DU RESPONSABLE -->
<section class="overflow-hidden rounded-2xl border border-[#eee5dc] bg-white shadow-sm">

    <div class="bg-[#30221b] px-6 py-8 text-white sm:px-8">

        <p class="text-xs font-semibold uppercase tracking-widest text-[#d6b58f]">
            Espace personnel
        </p>

        <div class="mt-6 flex flex-col items-center gap-5 sm:flex-row">

            <!-- PHOTO DE PROFIL -->
            <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-full border-4 border-white/20 bg-[#a98260] text-4xl font-bold text-white">

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

               <h2 class="break-words text-2xl font-bold">
                    <?= htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8') ?>
                </h2>

                <p class="mt-2 text-sm text-white/70">
                    Responsable d'agence
                </p>

                <div class="mt-4 flex flex-wrap justify-center gap-2 sm:justify-start">

                    <span class="rounded-full px-3 py-1 text-xs font-semibold <?= $statutStyle ?>">
                        <?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <?php if (!empty($responsable['is_verified'])): ?>
                        <span class="rounded-full bg-green-500/20 px-3 py-1 text-xs font-semibold text-green-200">
                            Compte vérifié
                        </span>
                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

    <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 sm:p-8">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Nom
            </p>
            <p class="mt-2 font-semibold text-[#30221b]">
                <?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Prénom
            </p>
            <p class="mt-2 font-semibold text-[#30221b]">
                <?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Adresse e-mail
            </p>
           <p class="mt-2 break-all font-semibold text-[#30221b]">
    <?= htmlspecialchars($responsable['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>
</p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Téléphone
            </p>
            <p class="mt-2 font-semibold text-[#30221b]">
                <?= htmlspecialchars($responsable['telephone'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>

    </div>

</section>


<!-- MODIFICATION DES INFORMATIONS PERSONNELLES -->
<section class="rounded-2xl border border-[#eee5dc] bg-white p-6 shadow-sm sm:p-8">

    <div class="mb-7">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#a98260]">
            Paramètres du compte
        </p>

        <h2 class="mt-2 text-xl font-bold text-[#30221b]">
            Informations personnelles
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Gérez les informations associées à votre compte.
        </p>
    </div>

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

            <div>
                <label for="nom" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Nom *
                </label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    required
                    value="<?= htmlspecialchars($responsable['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="w-full rounded-xl border border-[#e8ded3] px-4 py-3 text-sm outline-none focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
                >
            </div>

            <div>
                <label for="prenom" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Prénom *
                </label>
                <input
                    type="text"
                    id="prenom"
                    name="prenom"
                    required
                    value="<?= htmlspecialchars($responsable['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="w-full rounded-xl border border-[#e8ded3] px-4 py-3 text-sm outline-none focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
                >
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Adresse e-mail *
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    value="<?= htmlspecialchars($responsable['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="w-full rounded-xl border border-[#e8ded3] px-4 py-3 text-sm outline-none focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
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
                    value="<?= htmlspecialchars($responsable['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="w-full rounded-xl border border-[#e8ded3] px-4 py-3 text-sm outline-none focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
                >
            </div>

            <div>
                <label for="ville" class="mb-2 block text-sm font-semibold text-[#30221b]">
                    Ville
                </label>
                <input
                    type="text"
                    id="ville"
                    name="ville"
                    value="<?= htmlspecialchars($responsable['ville'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="w-full rounded-xl border border-[#e8ded3] px-4 py-3 text-sm outline-none focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
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
                    value="<?= htmlspecialchars($responsable['quartier'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="w-full rounded-xl border border-[#e8ded3] px-4 py-3 text-sm outline-none focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
                >
            </div>

        </div>

        <div>
            <label for="bio" class="mb-2 block text-sm font-semibold text-[#30221b]">
                Biographie
            </label>
            <textarea
                id="bio"
                name="bio"
                rows="4"
                class="w-full rounded-xl border border-[#e8ded3] px-4 py-3 text-sm outline-none focus:border-[#a98260] focus:ring-2 focus:ring-[#a98260]/20"
            ><?= htmlspecialchars($responsable['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="flex flex-col border-t border-[#eee5dc] pt-6 sm:flex-row sm:justify-end">
               <button
                type="submit"
           class="w-full rounded-xl bg-[#30221b] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#5a402d] sm:w-auto" >
                Enregistrer mon profil
            </button>
        </div>

    </form>

</section>

        </div>
         <!-- PIED DE PAGE -->
        <footer class="mt-auto border-t border-[#e9dfd4] bg-white">

            <div class="mx-auto flex max-w-[1450px] flex-col gap-4 px-5 py-6 sm:px-7 md:flex-row md:items-center md:justify-between lg:px-9">

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#30221b] text-lg font-extrabold text-[#f7b500]">
                        I
                    </div>

                    <div>
                        <p class="text-sm font-extrabold tracking-wide text-[#30221b]">
                            IMMO-SN
                        </p>
                        <p class="mt-0.5 text-[11px] text-gray-500">
                            Votre partenaire immobilier au Sénégal.
                        </p>
                    </div>
                </div>

                <div class="text-left md:text-right">
                    <p class="text-xs font-medium text-gray-500">
                        © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                    </p>
                    <p class="mt-1 text-[11px] text-[#a98260]">
                        Espace professionnel · Gestion des biens immobiliers
                    </p>
                </div>

            </div>

        </footer>


    </main>

</div>