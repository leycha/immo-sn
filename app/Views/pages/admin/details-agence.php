
<?php
/**
 * Vue : Détails d'une agence partenaire
 * Emplacement : app/Views/pages/admin/details-agence.php
 */

$agence = is_array($agence ?? null) ? $agence : [];
$biens = is_array($biens ?? null) ? $biens : [];

$page = max(1, (int) ($page ?? 1));
$totalPages = max(1, (int) ($totalPages ?? 1));
$totalBiens = max(0, (int) ($totalBiens ?? 0));

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$nomAgence = $agence['nom_agence'] ?? 'Agence sans nom';
$statut = strtolower(trim((string) ($agence['statut'] ?? '')));

$statuts = [
    'en_attente' => 'En attente',
    'actif' => 'Active',
    'suspendue' => 'Suspendue',
    'refusee' => 'Refusée',
];

$statutsBiens = [
    'actif' => 'Actif',
    'en_attente' => 'En attente',
    'rejete' => 'Rejeté',
    'rejeté' => 'Rejeté',
    'refuse' => 'Refusé',
    'refusé' => 'Refusé',
    'expire' => 'Expiré',
    'expiré' => 'Expiré',
    'suspendu' => 'Suspendu',
    'suspendue' => 'Suspendu',
    'brouillon' => 'Brouillon',
];

$badgeVerifie = filter_var(
    $agence['badge_verifie'] ?? false,
    FILTER_VALIDATE_BOOLEAN
);

$dateCreation = $agence['created_at'] ?? null;
$logo = trim((string) ($agence['logo'] ?? ''));

$idAgence = (string) ($agence['id'] ?? '');

$urlDetails = '/immo-sn/admin/agences/'
    . rawurlencode($idAgence)
    . '/details';

$premierBien = $totalBiens > 0
    ? (($page - 1) * 3) + 1
    : 0;

$dernierBien = min($page * 3, $totalBiens);
?>

<div class="min-h-screen bg-[#faf7f2] px-5 py-8 sm:px-8 lg:px-10">
    <div class="mx-auto max-w-6xl">

        <a href="/immo-sn/admin/agences"
           class="inline-flex items-center gap-2 text-sm font-semibold text-[#8d7058] transition hover:text-[#30221b]">
            <span aria-hidden="true">←</span>
            Retour à la liste des agences
        </a>

        <!-- EN-TÊTE -->
        <header class="mt-6 flex flex-col justify-between gap-5 rounded-[26px] bg-[#30221b] p-7 text-white shadow-lg sm:flex-row sm:items-center sm:p-9">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#f7b500]">
                    Administration · Partenaires
                </p>

               <h1 class="mt-3 break-words text-2xl font-bold sm:text-3xl">
                    <?= $e($nomAgence) ?>
                </h1>

                <p class="mt-2 text-sm text-[#d5c8bd]">
                    Fiche complète de l'agence partenaire
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-bold">
                    <?= $e($statuts[$statut] ?? 'Statut inconnu') ?>
                </span>

                <?php if ($badgeVerifie): ?>
                    <span class="rounded-full bg-[#f7b500] px-4 py-2 text-xs font-bold text-[#30221b]">
                        ✓ Agence vérifiée
                    </span>
                <?php else: ?>
                    <span class="rounded-full border border-white/20 px-4 py-2 text-xs font-semibold text-white">
                        Non vérifiée
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <!-- INFORMATIONS DE L'AGENCE -->
        <div class="mt-7 grid grid-cols-1 gap-6 lg:grid-cols-3">

            <section class="rounded-2xl border border-[#e8ded4] bg-white p-6 shadow-sm lg:col-span-2">
                <div class="flex items-center gap-3 border-b border-[#f1ece5] pb-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f5eee6] text-xl text-[#a98260]">
                        ◈
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-[#30221b]">
                            Informations professionnelles
                        </h2>

                        <p class="text-xs text-[#9a887a]">
                            Identité et coordonnées de l'agence
                        </p>
                    </div>
                </div>

                <?php if ($logo !== ''): ?>
                    <div class="my-5">
                        <img src="<?= $e($logo) ?>"
                             alt="Logo de <?= $e($nomAgence) ?>"
                             class="h-24 w-24 rounded-xl border border-[#e8ded4] bg-white object-contain p-2">
                    </div>
                <?php endif; ?>

                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <p class="text-xs text-[#9a887a]">Nom de l'agence</p>
                        <p class="mt-1 font-semibold text-[#30221b]">
                            <?= $e($nomAgence) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-[#9a887a]">NINEA</p>
                        <p class="mt-1 font-semibold text-[#30221b]">
                            <?= $e($agence['ninea'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-[#9a887a]">Email professionnel</p>
                        <p class="mt-1 break-all font-semibold text-[#30221b]">
                            <?= $e($agence['email_agence'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-[#9a887a]">Téléphone professionnel</p>
                        <p class="mt-1 font-semibold text-[#30221b]">
                            <?= $e($agence['telephone_agence'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-xs text-[#9a887a]">Adresse professionnelle</p>
                        <p class="mt-1 font-semibold text-[#30221b]">
                            <?= $e($agence['adresse'] ?? 'Non renseignée') ?>
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-xs text-[#9a887a]">Description</p>
                        <p class="mt-2 whitespace-pre-line leading-7 text-[#5a402d]">
                            <?= $e($agence['description'] ?? 'Aucune description renseignée.') ?>
                        </p>
                    </div>

                </div>
            </section>

            <!-- RESPONSABLE -->
            <section class="rounded-2xl border border-[#e8ded4] bg-white p-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#f1ece5] pb-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#f5eee6] text-xl text-[#a98260]">
                        ♙
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-[#30221b]">
                            Responsable
                        </h2>

                        <p class="text-xs text-[#9a887a]">
                            Compte personnel associé
                        </p>
                    </div>
                </div>

                <div class="mt-5 space-y-5">

                    <div>
                        <p class="text-xs text-[#9a887a]">Nom complet</p>
                        <p class="mt-1 font-semibold text-[#30221b]">
                            <?= $e(trim(
                                ($agence['responsable_prenom'] ?? '')
                                . ' '
                                . ($agence['responsable_nom'] ?? '')
                            ) ?: 'Non renseigné') ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-[#9a887a]">Email personnel</p>
                        <p class="mt-1 break-all font-semibold text-[#30221b]">
                            <?= $e($agence['responsable_email'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-[#9a887a]">Téléphone personnel</p>
                        <p class="mt-1 font-semibold text-[#30221b]">
                            <?= $e($agence['responsable_telephone'] ?? 'Non renseigné') ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-[#9a887a]">Ville</p>
                        <p class="mt-1 font-semibold text-[#30221b]">
                            <?= $e($agence['responsable_ville'] ?? 'Non renseignée') ?>
                        </p>
                    </div>

                </div>
            </section>

        </div>


        <!-- BIENS DE L'AGENCE -->
        <section class="mt-8">

            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">
                        Portefeuille immobilier
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#30221b]">
                        Biens de cette agence
                    </h2>

                    <p class="mt-1 text-sm text-[#8d7b6c]">
                        Toutes les annonces associées à cette agence.
                    </p>
                </div>

                <div class="rounded-xl border border-[#e8ded4] bg-white px-4 py-3">
                    <span class="text-sm text-[#8d7b6c]">
                        Total des biens :
                    </span>

                    <strong class="ml-1 text-lg text-[#30221b]">
                        <?= $totalBiens ?>
                    </strong>
                </div>

            </div>

            <?php if (empty($biens)): ?>

                <div class="mt-5 rounded-2xl border border-dashed border-[#d9cbbd] bg-white px-6 py-14 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f5eee6] text-3xl text-[#a98260]">
                        ⌂
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-[#30221b]">
                        Aucun bien enregistré
                    </h3>

                    <p class="mt-2 text-sm text-[#9a887a]">
                        Cette agence ne possède actuellement aucune annonce.
                    </p>

                </div>

            <?php else: ?>

                <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                    <?php foreach ($biens as $bien): ?>

                        <?php
                        $statutBien = strtolower(trim(
                            (string) ($bien['statut'] ?? '')
                        ));

                        $libelleStatut = $statutsBiens[$statutBien]
                            ?? ucfirst($statutBien ?: 'Inconnu');

                        $photo = trim((string) ($bien['photo'] ?? ''));

                        $localisation = array_filter([
                            $bien['quartier_nom'] ?? '',
                            $bien['ville_nom'] ?? '',
                        ], static fn($valeur) => trim((string) $valeur) !== '');

                        $localisationTexte = implode(', ', $localisation);
                        ?>

                        <article class="overflow-hidden rounded-2xl border border-[#e8ded4] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                            <!-- PHOTO -->
                            <div class="relative h-48 bg-[#f1ece5]">

                                <?php if ($photo !== ''): ?>

                                    <img src="<?= $e($photo) ?>"
                                         alt="<?= $e($bien['titre'] ?? 'Photo du bien') ?>"
                                         class="h-full w-full object-cover"
                                         loading="lazy">

                                <?php else: ?>

                                    <div class="flex h-full items-center justify-center text-4xl text-[#b9a895]">
                                        ⌂
                                    </div>

                                <?php endif; ?>

                                <span class="absolute left-3 top-3 rounded-full bg-white px-3 py-1.5 text-xs font-bold text-[#30221b] shadow">
                                    <?= $e($libelleStatut) ?>
                                </span>

                            </div>

                            <!-- INFORMATIONS -->
                            <div class="p-3">

                                <p class="text-xs font-semibold uppercase tracking-wide text-[#a98260]">
                                    <?= $e($bien['categorie_nom'] ?? 'Catégorie non renseignée') ?>
                                </p>

                                <h3 class="mt-1 line-clamp-2 text-lg font-bold text-[#30221b]">
                                    <?= $e($bien['titre'] ?? 'Bien sans titre') ?>
                                </h3>

                                <p class="mt-1 text-xl font-bold text-[#8b674a]">
                                    <?php if (isset($bien['prix']) && $bien['prix'] !== ''): ?>
                                        <?= $e(number_format((float) $bien['prix'], 0, ',', ' ')) ?> FCFA
                                    <?php else: ?>
                                        Prix non renseigné
                                    <?php endif; ?>
                                </p>

                                <div class="mt-2 space-y-1 border-t border-[#f1ece5] pt-4 text-sm text-[#78685b]">

                                    <?php if ($localisationTexte !== ''): ?>
                                        <p class="break-words">
    <span class="mr-2">⌖</span>
    <?= $e($localisationTexte) ?>
</p>
                                    <?php else: ?>
                                        <p>Localisation non renseignée</p>
                                    <?php endif; ?>

                                    <div class="flex flex-wrap gap-x-4 gap-y-2">

                                        <?php if (isset($bien['surface']) && $bien['surface'] !== ''): ?>
                                            <span>
                                                <?= $e($bien['surface']) ?> m²
                                            </span>
                                        <?php endif; ?>

                                        <?php if (isset($bien['pieces']) && $bien['pieces'] !== ''): ?>
                                            <span>
                                                <?= $e($bien['pieces']) ?> pièce(s)
                                            </span>
                                        <?php endif; ?>

                                        <?php if (isset($bien['chambres']) && $bien['chambres'] !== ''): ?>
                                            <span>
                                                <?= $e($bien['chambres']) ?> chambre(s)
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <?php if (!empty($bien['type_offre'])): ?>
                                        <p>
                                            Offre :
                                            <?= $e(ucfirst((string) $bien['type_offre'])) ?>
                                        </p>
                                    <?php endif; ?>

                                </div>

                                <div class="mt-2 flex items-center justify-between border-t border-[#f1ece5] pt-4">

                                    <span class="text-xs text-[#9a887a]">
                                        <?php if (!empty($bien['created_at']) && strtotime((string) $bien['created_at']) !== false): ?>
                                            Publié le <?= $e(date('d/m/Y', strtotime((string) $bien['created_at']))) ?>
                                        <?php else: ?>
                                            Date inconnue
                                        <?php endif; ?>
                                    </span>

                                    <span class="text-xs font-semibold text-[#a98260]">
                                        <?= (int) ($bien['nb_vues'] ?? 0) ?> vue(s)
                                    </span>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

                <!-- PAGINATION -->
                <?php if ($totalPages > 1): ?>

                    <div class="mt-7 flex flex-col items-center justify-between gap-4 rounded-2xl border border-[#e8ded4] bg-white p-3 sm:flex-row">

                        <p class="text-sm text-[#8d7b6c]">
                            Affichage de
                            <strong class="text-[#30221b]"><?= $premierBien ?></strong>
                            à
                            <strong class="text-[#30221b]"><?= $dernierBien ?></strong>
                            sur
                            <strong class="text-[#30221b]"><?= $totalBiens ?></strong>
                            biens
                        </p>

                        <nav class="flex flex-wrap items-center justify-center gap-2"
                             aria-label="Pagination des biens">

                            <?php if ($page > 1): ?>
                                <a href="<?= $e($urlDetails . '?page=' . ($page - 1)) ?>"
                                   class="rounded-xl border border-[#e8ded4] px-4 py-2 text-sm font-semibold text-[#5a402d] transition hover:bg-[#f5eee6]">
                                    ← Précédent
                                </a>
                            <?php endif; ?>

                            <?php for ($numero = 1; $numero <= $totalPages; $numero++): ?>

                                <a href="<?= $e($urlDetails . '?page=' . $numero) ?>"
                                   aria-current="<?= $numero === $page ? 'page' : 'false' ?>"
                                   class="flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-sm font-bold transition
                                   <?= $numero === $page
                                       ? 'bg-[#30221b] text-white'
                                       : 'border border-[#e8ded4] text-[#5a402d] hover:bg-[#f5eee6]' ?>">
                                    <?= $numero ?>
                                </a>

                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="<?= $e($urlDetails . '?page=' . ($page + 1)) ?>"
                                   class="rounded-xl border border-[#e8ded4] px-4 py-2 text-sm font-semibold text-[#5a402d] transition hover:bg-[#f5eee6]">
                                    Suivant →
                                </a>
                            <?php endif; ?>

                        </nav>

                    </div>

                <?php endif; ?>

            <?php endif; ?>

        </section>

    </div>
</div>
