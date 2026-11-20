
<?php
use Core\Session;

$statut = $agence['statut'] ?? 'en_attente';

$statuts = [
    'active' => ['Validée', 'bg-[#7E9833]/10 text-[#5E7826] border-[#C9D8A7'],
    'en_attente' => ['En attente de validation', 'bg-amber-50 text-amber-700 border-amber-200'],
    'suspendue' => ['Suspendue', 'bg-red-50 text-red-700 border-red-200'],
    'refusee' => ['Refusée', 'bg-red-50 text-red-700 border-red-200'],
];

[$statutLabel, $statutStyle] = $statuts[$statut] ?? [
    ucfirst($statut),
    'bg-gray-100 text-gray-700 border-gray-200'
];

$nomAgence = $agence['nom_agence'] ?? 'Mon agence';
$initiale = strtoupper(substr($nomAgence, 0, 1));

$e = static function ($valeur): string {
    return htmlspecialchars((string) ($valeur ?? ''), ENT_QUOTES, 'UTF-8');
};

$cartes = [
    [
        'titre' => 'Total des biens',
        'valeur' => (int) ($statistiques['total'] ?? 0),
        'icone' => '🏘️',
        'description' => 'Toutes vos annonces',
        'fond' => 'bg-[#71869A]/10',
        'couleur' => 'text-[#71869A]',
    ],
    [
        'titre' => 'Biens actifs',
        'valeur' => (int) ($statistiques['actifs'] ?? 0),
        'icone' => '🏠',
        'description' => 'Annonces en ligne',
        'fond' => 'bg-[#7E9833]/10',
        'couleur' => 'text-[#7E9833]',
    ],
    [
        'titre' => 'En attente',
        'valeur' => (int) ($statistiques['en_attente'] ?? 0),
        'icone' => '⏳',
        'description' => 'En cours de validation',
        'fond' => 'bg-amber-50',
        'couleur' => 'text-amber-600',
    ],
    [
        'titre' => 'Biens refusés',
        'valeur' => (int) ($statistiques['refuses'] ?? 0),
        'icone' => '📋',
        'description' => 'Annonces non validées',
        'fond' => 'bg-red-50',
        'couleur' => 'text-red-600',
    ],
];

$statutsAffichage = [
    'actif' => ['Actif', 'bg-[#7E9833]/10 text-[#5E7826]'],
    'en_attente' => ['En attente', 'bg-amber-50 text-amber-700'],
    'rejete' => ['Refusé', 'bg-red-50 text-red-700'],
    'refuse' => ['Refusé', 'bg-red-50 text-red-700'],
    'refusee' => ['Refusé', 'bg-red-50 text-red-700'],
    'brouillon' => ['Brouillon', 'bg-gray-100 text-gray-700'],
    'expire' => ['Expiré', 'bg-orange-50 text-orange-700'],
];

$derniersBiens = array_slice($biens ?? [], 0, 3);
?>

<div class="min-h-screen bg-[#F5F3ED] text-[#101D33]">

    <?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

    <main class="min-h-screen lg:ml-[270px]">

        <!-- HEADER -->
        <header class="sticky top-0 z-30 border-b border-[#E2E5DA] bg-[#F5F3ED]/95 px-4 py-4 backdrop-blur-xl sm:px-8 sm:py-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#7E9833]">
                        Espace professionnel
                    </p>

                    <h1 class="mt-1 text-xl font-bold text-[#101D33] sm:text-2xl">
                        Tableau de bord
                    </h1>

                    <p class="mt-1 text-sm text-[#71869A]">
                        Suivez et gérez les activités de votre agence.
                    </p>
                </div>

                <span class="inline-flex w-fit items-center gap-2 rounded-full border px-4 py-2 text-xs font-bold <?= $statutStyle ?>">
                    <span class="h-2 w-2 rounded-full bg-current"></span>
                    <?= $e($statutLabel) ?>
                </span>

            </div>
        </header>

        <div class="mx-auto max-w-7xl space-y-8 px-5 py-8 sm:px-8">

            <!-- BANNIÈRE DE BIENVENUE -->
            <section class="relative overflow-hidden rounded-[28px] bg-[#101D33] p-6 text-white shadow-[0_18px_50px_rgba(16,29,51,0.15)] sm:p-9">

                <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full border-[35px] border-[#7E9833]/10"></div>
                <div class="pointer-events-none absolute -bottom-24 right-1/4 h-52 w-52 rounded-full bg-[#71869A]/15 blur-2xl"></div>
                <div class="pointer-events-none absolute -left-20 top-1/2 h-48 w-48 rounded-full bg-[#7E9833]/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center">

                    <?php if (!empty($agence['logo'])): ?>

                        <img
                            src="<?= $e($agence['logo']) ?>"
                            alt="Logo de l'agence"
                            class="h-20 w-20 shrink-0 rounded-2xl border border-white/20 bg-white object-contain p-2 shadow-lg"
                        >

                    <?php else: ?>

                        <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-[#7E9833] text-3xl font-black text-white shadow-lg">
                            <?= $e($initiale) ?>
                        </div>

                    <?php endif; ?>

                    <div class="min-w-0">

                        <p class="text-sm font-medium text-[#B8C7D6]">
                            Bienvenue dans votre espace professionnel
                        </p>

                        <h2 class="mt-2 break-words font-['Playfair_Display'] text-2xl font-bold sm:text-3xl">
                            <?= $e($nomAgence) ?>
                        </h2>

                        <p class="mt-2 max-w-xl text-sm leading-6 text-[#C3CEDB]">
                            Retrouvez en un seul endroit les performances, les annonces et les informations de votre agence.
                        </p>

                        <div class="mt-5 flex flex-wrap items-center gap-3">

                            <span class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-white">
                                Espace agence
                            </span>

                            <span class="rounded-lg bg-[#7E9833]/15 px-3 py-2 text-xs font-semibold text-[#A8C65D]">
                                IMMO-SN
                            </span>

                        </div>
                    </div>
                </div>
            </section>

            <!-- STATISTIQUES -->
            <section class="space-y-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#7E9833]">
                        Vue d'ensemble
                    </p>

                    <h2 class="mt-1 text-xl font-extrabold text-[#101D33]">
                        Activité de votre agence
                    </h2>

                    <p class="mt-1 text-sm text-[#71869A]">
                        Un aperçu rapide de vos annonces immobilières.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <?php foreach ($cartes as $carte): ?>

                        <article class="group rounded-2xl border border-[#E2E5DA] bg-white p-5 shadow-[0_4px_18px_rgba(16,29,51,0.03)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgba(16,29,51,0.08)]">

                            <div class="flex items-center justify-between gap-3">

                                <div>
                                    <p class="text-sm font-semibold text-[#71869A]">
                                        <?= $e($carte['titre']) ?>
                                    </p>

                                    <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#101D33]">
                                        <?= $carte['valeur'] ?>
                                    </p>
                                </div>

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-2xl <?= $carte['fond'] ?>">
                                    <?= $carte['icone'] ?>
                                </div>
                            </div>

                            <div class="mt-4 border-t border-[#EDF0E8] pt-3">
                                <p class="text-xs font-medium text-[#91A0AE]">
                                    <?= $e($carte['description']) ?>
                                </p>
                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>
            </section>

            <!-- DERNIERS BIENS -->
            <section class="space-y-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#7E9833]">
                            Votre portefeuille
                        </p>

                        <h2 class="mt-1 text-xl font-extrabold text-[#101D33]">
                            Derniers biens publiés
                        </h2>

                        <p class="mt-1 text-sm text-[#71869A]">
                            Retrouvez les dernières annonces de votre agence.
                        </p>
                    </div>

                    <a
                        href="/immo-sn/agence/biens"
                        class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#101D33] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#1C304E]"
                    >
                        Voir tous les biens
                        <span aria-hidden="true">→</span>
                    </a>

                </div>

                <?php if (empty($derniersBiens)): ?>

                    <div class="rounded-2xl border border-dashed border-[#CAD3BA] bg-white px-6 py-14 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#7E9833]/10 text-3xl">
                            🏘️
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-[#101D33]">
                            Aucun bien publié pour le moment
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#71869A]">
                            Vos annonces apparaîtront ici dès leur publication.
                        </p>

                    </div>

                <?php else: ?>

                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($derniersBiens as $bien): ?>

                            <?php
                            $statutBien = strtolower(trim($bien['statut'] ?? ''));

                            [$libelleStatut, $couleurStatut] =
                                $statutsAffichage[$statutBien] ?? [
                                    'Non défini',
                                    'bg-gray-100 text-gray-700'
                                ];
                            ?>

                            <article class="group overflow-hidden rounded-2xl border border-[#E2E5DA] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_14px_35px_rgba(16,29,51,0.10)]">

                                <!-- IMAGE -->
                                <div class="relative h-48 overflow-hidden bg-[#E9ECE4]">

                                    <?php if (!empty($bien['photo'])): ?>

                                        <img
                                            src="<?= $e($bien['photo']) ?>"
                                            alt="<?= $e($bien['titre'] ?? 'Bien immobilier') ?>"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        >

                                    <?php else: ?>

                                        <div class="flex h-full items-center justify-center text-5xl">
                                            🏠
                                        </div>

                                    <?php endif; ?>

                                    <span class="absolute right-3 top-3 rounded-full px-3 py-1.5 text-xs font-bold shadow-sm <?= $couleurStatut ?>">
                                        <?= $e($libelleStatut) ?>
                                    </span>

                                </div>

                                <div class="space-y-4 p-5">

                                    <div>
                                        <h3 class="line-clamp-2 break-words text-base font-extrabold text-[#101D33]">
                                            <?= $e($bien['titre'] ?? 'Sans titre') ?>
                                        </h3>

                                        <p class="mt-2 text-lg font-extrabold text-[#7E9833]">
                                            <?= number_format((float) ($bien['prix'] ?? 0), 0, ',', ' ') ?>
                                            <span class="text-xs font-bold">FCFA</span>
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap gap-x-4 gap-y-2 border-t border-[#EDF0E8] pt-4 text-xs font-medium text-[#71869A]">

                                        <?php if (!empty($bien['ville_nom'])): ?>
                                            <span class="min-w-0 break-words">📍 <?= $e($bien['ville_nom']) ?></span>
                                        <?php endif; ?>

                                        <?php if (!empty($bien['superficie'])): ?>
                                            <span>📐 <?= $e($bien['superficie']) ?> m²</span>
                                        <?php endif; ?>

                                        <?php if (!empty($bien['type_offre'])): ?>
                                            <span>🏷️ <?= $e($bien['type_offre']) ?></span>
                                        <?php endif; ?>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

            <!-- ÉTAT DE L'AGENCE -->
            <section class="overflow-hidden rounded-2xl border border-[#E2E5DA] bg-white shadow-sm">

                <div class="border-b border-[#E2E5DA] px-6 py-5">

                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#7E9833]">
                        Suivi administratif
                    </p>

                    <h2 class="mt-1 text-lg font-extrabold text-[#101D33]">
                        État de votre agence
                    </h2>

                </div>

                <div class="flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-start gap-4">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#7E9833]/10 text-xl">
                            🏢
                        </div>

                        <div>
                            <h3 class="font-bold text-[#101D33]">
                                <?= $e($statutLabel) ?>
                            </h3>

                            <p class="mt-2 max-w-2xl text-sm leading-6 text-[#71869A]">

                                <?php if ($statut === 'en_attente'): ?>

                                    Votre dossier est en attente de validation par l'administration.

                                <?php elseif ($statut === 'active'): ?>

                                    Votre agence est validée. Vous pouvez poursuivre la gestion de vos activités.

                                <?php elseif ($statut === 'suspendue'): ?>

                                    Votre agence est suspendue. Veuillez contacter l'administration.

                                <?php elseif ($statut === 'refusee'): ?>

                                    Votre demande a été refusée. Veuillez contacter l'administration.

                                <?php else: ?>

                                    Consultez les informations relatives à votre dossier auprès de l'administration.

                                <?php endif; ?>

                            </p>
                        </div>

                    </div>

                    <span class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-xs font-bold <?= $statutStyle ?>">
                        <span class="h-2 w-2 rounded-full bg-current"></span>
                        <?= $e($statutLabel) ?>
                    </span>

                </div>

            </section>

            <!-- INFORMATIONS AGENCE -->
            <section class="overflow-hidden rounded-2xl border border-[#E2E5DA] bg-white shadow-sm">

                <div class="flex flex-col gap-4 border-b border-[#E2E5DA] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#7E9833]">
                            Profil professionnel
                        </p>

                        <h2 class="mt-1 text-lg font-extrabold text-[#101D33]">
                            Informations de mon agence
                        </h2>

                        <p class="mt-1 text-sm text-[#71869A]">
                            Consultez et mettez à jour les informations enregistrées.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="ouvrirPopupAgence()"
                        class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#91AD42]"
                    >
                        <span>✎</span>
                        Modifier mes informations
                    </button>

                </div>

                <div class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">

                    <?php
                    $informations = [
                        'Nom de l’agence' => $agence['nom_agence'] ?? '',
                        'Adresse' => $agence['adresse'] ?? '',
                        'Téléphone' => $agence['telephone'] ?? '',
                        'Adresse e-mail' => $agence['email'] ?? '',
                        'NINEA' => $agence['ninea'] ?? '',
                        'Statut' => $statutLabel,
                    ];
                    ?>

                    <?php foreach ($informations as $label => $valeur): ?>

                        <div class="rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] p-4 transition hover:border-[#C1D19B]">

                            <p class="text-[11px] font-bold uppercase tracking-wider text-[#71869A]">
                                <?= $e($label) ?>
                            </p>

                            <p class="mt-2 break-words text-sm font-bold text-[#101D33]">
                                <?= $e($valeur !== '' ? $valeur : 'Non renseigné') ?>
                            </p>

                        </div>

                    <?php endforeach; ?>

                    <div class="rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] p-4 sm:col-span-2 lg:col-span-3">

                        <p class="text-[11px] font-bold uppercase tracking-wider text-[#71869A]">
                            Description
                        </p>

                        <p class="mt-2 break-words whitespace-pre-line text-sm leading-7 text-[#33445A]">
                            <?= $e($agence['description'] ?? 'Aucune description renseignée.') ?>
                        </p>

                    </div>

                </div>

            </section>

        </div>

        <!-- FOOTER -->
        <footer class="mt-10 border-t border-[#E2E5DA] bg-white px-5 py-6 sm:px-8">

            <div class="mx-auto flex max-w-7xl flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <a href="/immo-sn/" class="inline-flex items-center gap-2">

                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#101D33] text-sm font-extrabold text-[#9DBA4C]">
                            IM
                        </span>

                        <span class="text-base font-extrabold tracking-wide text-[#101D33]">
                            IMMO<span class="text-[#7E9833]">-SN</span>
                        </span>

                    </a>

                    <p class="mt-2 text-xs text-[#71869A]">
                        Votre partenaire immobilier au Sénégal.
                    </p>

                </div>

                <div class="flex flex-col gap-2 text-xs text-[#71869A] sm:items-end">

                    <p>
                        © <?= date('Y') ?> IMMO-SN. Tous droits réservés.
                    </p>

                    <p class="font-medium text-[#7E9833]">
                        Espace professionnel · Tableau de bord agence
                    </p>

                </div>

            </div>

        </footer>

    </main>
</div>

<!-- POPUP MODIFICATION AGENCE -->
<div
    id="popupAgence"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#101D33]/75 px-4 py-6 backdrop-blur-sm"
    onclick="fermerPopupAgence(event)"
>

    <div class="flex max-h-[90vh] w-full min-w-0 max-w-2xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">

        <!-- HEADER POPUP -->
        <div class="relative overflow-hidden border-b border-[#E2E5DA] bg-[#101D33] px-6 py-5 text-white">

            <div class="pointer-events-none absolute -right-10 -top-16 h-40 w-40 rounded-full bg-[#7E9833]/20 blur-2xl"></div>

            <div class="relative flex items-center justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-[#9DBA4C]">
                        Profil professionnel
                    </p>

                    <h2 class="mt-1 text-xl font-extrabold">
                        Modifier mon agence
                    </h2>

                    <p class="mt-1 text-sm text-[#C3CEDB]">
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

                <label class="mb-2 block text-sm font-bold text-[#101D33]">
                    Nom de l'agence
                </label>

                <input
                    type="text"
                    name="nom_agence"
                    value="<?= $e($agence['nom_agence'] ?? '') ?>"
                    required
                    class="w-full rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] px-4 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:bg-white focus:ring-2 focus:ring-[#7E9833]/15"
                >

            </div>

            <div>

                <label class="mb-2 block text-sm font-bold text-[#101D33]">
                    Adresse
                </label>

                <input
                    type="text"
                    name="adresse"
                    value="<?= $e($agence['adresse'] ?? '') ?>"
                    required
                    class="w-full rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] px-4 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:bg-white focus:ring-2 focus:ring-[#7E9833]/15"
                >

            </div>

            <div class="grid gap-5 sm:grid-cols-2">

                <div>

                    <label class="mb-2 block text-sm font-bold text-[#101D33]">
                        Téléphone
                    </label>

                    <input
                        type="text"
                        name="telephone"
                        value="<?= $e($agence['telephone'] ?? '') ?>"
                        class="w-full rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] px-4 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:bg-white focus:ring-2 focus:ring-[#7E9833]/15"
                    >

                </div>

                <div>

                    <label class="mb-2 block text-sm font-bold text-[#101D33]">
                        Adresse e-mail
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="<?= $e($agence['email'] ?? '') ?>"
                        class="w-full rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] px-4 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:bg-white focus:ring-2 focus:ring-[#7E9833]/15"
                    >

                </div>

            </div>

            <div>

                <label class="mb-2 block text-sm font-bold text-[#101D33]">
                    NINEA
                </label>

                <input
                    type="text"
                    name="ninea"
                    value="<?= $e($agence['ninea'] ?? '') ?>"
                    required
                    class="w-full rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] px-4 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:bg-white focus:ring-2 focus:ring-[#7E9833]/15"
                >

            </div>

            <div>

                <label class="mb-2 block text-sm font-bold text-[#101D33]">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full resize-y rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] px-4 py-3 text-sm text-[#101D33] outline-none transition focus:border-[#7E9833] focus:bg-white focus:ring-2 focus:ring-[#7E9833]/15"
                ><?= $e($agence['description'] ?? '') ?></textarea>

            </div>

            <!-- BOUTONS -->
            <div class="flex flex-col-reverse gap-3 border-t border-[#E2E5DA] pt-5 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="fermerPopupAgence()"
                    class="rounded-xl border border-[#E2E5DA] px-5 py-3 text-sm font-bold text-[#71869A] transition hover:bg-[#F5F3ED]"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#7E9833] px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#91AD42]"
                >
                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>

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
