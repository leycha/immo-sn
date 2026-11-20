<?php
/**
 * Vue : Gestion des agences — Administration IMMO-SN
 * Emplacement : app/Views/admin/agences.php
 */

$agences = is_array($agences ?? null) ? $agences : [];

$messageSucces = $messageSucces ?? null;
$messageErreur = $messageErreur ?? null;

$statuts = [
    'en_attente' => 'En attente',
    'actif'      => 'Active',
    'suspendue'  => 'Suspendue',
    'refusee'    => 'Refusée',
];

$stylesStatuts = [
    'en_attente' => 'bg-amber-100 text-amber-800',
    'actif'      => 'bg-emerald-100 text-emerald-700',
    'suspendue'  => 'bg-orange-100 text-orange-800',
    'refusee'    => 'bg-red-100 text-red-700',
];

$compteurs = [
    'en_attente' => 0,
    'actif'      => 0,
    'suspendue'  => 0,
    'refusee'    => 0,
];

foreach ($agences as $agence) {
    $statut = strtolower(trim((string) ($agence['statut'] ?? '')));

    if (isset($compteurs[$statut])) {
        $compteurs[$statut]++;
    }
}

$totalAgences = count($agences);

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};
?>

<div class="min-h-screen bg-[#f8fafc]">

    <!-- EN-TÊTE -->
    <header class="sticky top-0 z-20 border-b border-[#e2e8f0] bg-white/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-[#64748b]">
                        <span>Administration</span>
                        <span>/</span>
                        <span>Partenaires</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#071b36] sm:text-3xl">
                        Gestion des agences
                    </h1>

                    <p class="mt-1 text-sm text-[#64748b]">
                        Gérez les inscriptions et les agences partenaires d'IMMO-SN.
                    </p>
                </div>

                <a href="/immo-sn/"
                   class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#071b36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0e2a4e]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-[#f5b800]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                    </svg>
                    Accueil
                </a>

            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

            <!-- MESSAGES FLASH -->
            <?php if ($messageSucces): ?>
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100">✓</span>
                    <span><?= $e($messageSucces) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($messageErreur): ?>
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100">!</span>
                    <span><?= $e($messageErreur) ?></span>
                </div>
            <?php endif; ?>

            <!-- GRAND BLOC DE PRÉSENTATION -->
            <section class="relative overflow-hidden rounded-[26px] bg-[#071b36] shadow-[0_18px_50px_rgba(7,27,54,0.16)]">

                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#0e2a4e]/60 blur-3xl"></div>

                <div class="relative flex flex-col gap-7 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between">

                    <div class="max-w-2xl">

                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                            <span class="text-xs font-medium text-slate-200">
                                Centre de gestion immobilière
                            </span>
                        </div>

                        <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                            Gestion des
                            <span class="text-[#f5b800]">agences partenaires</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300">
                            Consultez les demandes d'inscription, vérifiez les informations
                            des agences et contrôlez leurs autorisations sur la plateforme IMMO-SN.
                        </p>

                    </div>

                    <!-- CARTE TOTAL -->
                    <div class="shrink-0 rounded-2xl border border-white/10 bg-[#0e2a4e]/70 p-5 backdrop-blur-sm lg:min-w-[270px]">

                        <div class="flex items-center justify-between gap-5">

                            <div>
                                <p class="text-xs uppercase tracking-[0.15em] text-slate-400">
                                    Total des agences
                                </p>

                                <p class="mt-2 text-3xl font-bold tabular-nums text-white">
                                    <?= number_format($totalAgences, 0, ',', ' ') ?>
                                </p>

                                <p class="mt-1 text-xs text-slate-300">
                                    agence(s) enregistrée(s)
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#f5b800]/15 text-[#f5b800]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                                </svg>
                            </div>

                        </div>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full w-full rounded-full bg-[#f5b800]"></div>
                        </div>

                        <p class="mt-2 text-right text-[11px] text-slate-400">
                            Réseau des partenaires IMMO-SN
                        </p>

                    </div>
                </div>
            </section>

            <!-- CARTES STATISTIQUES -->
            <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <!-- EN ATTENTE -->
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#64748b]">En attente</p>
                            <p class="mt-1 text-2xl font-bold text-[#0b1528]"><?= $compteurs['en_attente'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- ACTIVES -->
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#64748b]">Agences actives</p>
                            <p class="mt-1 text-2xl font-bold text-[#0b1528]"><?= $compteurs['actif'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- SUSPENDUES -->
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 5v14M16 5v14"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#64748b]">Agences suspendues</p>
                            <p class="mt-1 text-2xl font-bold text-[#0b1528]"><?= $compteurs['suspendue'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- REFUSÉES -->
                <div class="rounded-[20px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#64748b]">Agences refusées</p>
                            <p class="mt-1 text-2xl font-bold text-[#0b1528]"><?= $compteurs['refusee'] ?></p>
                        </div>
                    </div>
                </div>

            </section>

            <!-- GRILLE DES AGENCES -->
            <section class="mt-8">

                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                            Partenaires
                        </p>
                        <h2 class="mt-1 text-xl font-bold text-[#071b36]">
                            Nos agences partenaires
                        </h2>
                        <p class="mt-1 text-sm text-[#64748b]">
                            Consultez et gérez chaque agence individuellement.
                        </p>
                    </div>

                    <span class="w-fit rounded-full border border-[#e2e8f0] bg-white px-4 py-2 text-xs font-bold text-[#071b36]">
                        <?= number_format($totalAgences, 0, ',', ' ') ?> agence(s)
                    </span>
                </div>

                <?php if (empty($agences)): ?>

                    <div class="rounded-[24px] border border-[#e2e8f0] bg-white px-6 py-16 text-center shadow-sm">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f8fafc] text-[#0e2a4e]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-8 w-8"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#071b36]">
                            Aucune agence trouvée
                        </h3>

                        <p class="mt-2 text-sm text-[#64748b]">
                            Les inscriptions des agences partenaires apparaîtront ici.
                        </p>
                    </div>

                <?php else: ?>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($agences as $agence): ?>

                            <?php
                            $statut = strtolower(trim((string) ($agence['statut'] ?? '')));

                            $libelleStatut = $statuts[$statut] ?? $statut;
                            $styleStatut = $stylesStatuts[$statut] ?? 'bg-slate-100 text-slate-700';

                            $nomAgence = $agence['nom_agence'] ?? 'Agence sans nom';
                            $email = $agence['email'] ?? '';
                            $telephone = $agence['telephone'] ?? '';
                            $adresse = $agence['adresse'] ?? '';
                            $ninea = $agence['ninea'] ?? '';
                            $dateCreation = $agence['created_at'] ?? null;
                            $idAgence = (string) ($agence['id'] ?? '');

                            $badgeVerifie = filter_var(
                                $agence['badge_verifie'] ?? false,
                                FILTER_VALIDATE_BOOLEAN
                            );

                            $nombreBiens = (int) ($agence['nombre_biens'] ?? 0);
                            ?>

                            <article class="group relative flex flex-col overflow-visible rounded-[24px] border border-[#e2e8f0] bg-white shadow-[0_8px_25px_rgba(7,27,54,0.04)] transition duration-300 hover:-translate-y-1 hover:border-[#0e2a4e]/40 hover:shadow-[0_18px_40px_rgba(7,27,54,0.10)]">

                                <!-- BANDEAU SUPÉRIEUR -->
                                <div class="relative h-2 rounded-t-[24px] bg-gradient-to-r from-[#071b36] via-[#0e2a4e] to-[#f5b800]"></div>

                                <div class="flex flex-1 flex-col p-5 sm:p-6">

                                    <!-- IDENTITÉ ET STATUT -->
                                    <div class="flex items-start justify-between gap-3">

                                        <div class="flex min-w-0 items-center gap-3">

                                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#f8fafc] text-[#0e2a4e] ring-1 ring-[#e2e8f0]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-7 w-7"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>
                                                </svg>
                                            </div>

                                            <div class="min-w-0">
                                                <h3 class="break-words text-base font-bold leading-6 text-[#0b1528]">
                                                    <?= $e($nomAgence) ?>
                                                </h3>

                                                <p class="mt-1 text-[10px] text-[#64748b]">
                                                    Référence : <?= $e(substr($idAgence, 0, 8)) ?>
                                                </p>
                                            </div>

                                        </div>

                                        <span class="inline-flex shrink-0 rounded-full px-3 py-1.5 text-[10px] font-bold <?= $styleStatut ?>">
                                            <?= $e($libelleStatut) ?>
                                        </span>

                                    </div>

                                    <!-- BADGE ET BIENS -->
                                    <div class="mt-5 grid grid-cols-2 gap-3">

                                        <div class="rounded-2xl bg-[#f8fafc] p-3">
                                            <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748b]">
                                                Biens actifs
                                            </p>
                                            <p class="mt-1 text-2xl font-bold text-[#0b1528]">
                                                <?= $nombreBiens ?>
                                            </p>
                                        </div>

                                        <div class="rounded-2xl bg-[#f8fafc] p-3">
                                            <p class="text-[10px] font-semibold uppercase tracking-wider text-[#64748b]">
                                                Vérification
                                            </p>

                                            <p class="mt-2 text-xs font-bold <?= $badgeVerifie ? 'text-emerald-700' : 'text-[#64748b]' ?>">
                                                <?= $badgeVerifie ? '✓ Badge vérifié' : 'Non vérifiée' ?>
                                            </p>
                                        </div>

                                    </div>

                                    <!-- COORDONNÉES -->
                                    <div class="mt-5 space-y-3">

                                        <div class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#0e2a4e]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                                </svg>
                                            </span>
                                            <p class="min-w-0 break-all text-xs text-[#0b1528]">
                                                <?= $e($email ?: 'E-mail non renseigné') ?>
                                            </p>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#0e2a4e]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M3 5a2 2 0 012-2h3l2 5-2 1a15 15 0 007 7l1-2 5 2v3a2 2 0 01-2 2C10 21 3 14 3 5z"/>
                                                </svg>
                                            </span>
                                            <p class="text-xs text-[#0b1528]">
                                                <?= $e($telephone ?: 'Téléphone non renseigné') ?>
                                            </p>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#0e2a4e]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M12 21s7-5.5 7-12a7 7 0 10-14 0c0 6.5 7 12 7 12z"/>
                                                    <circle cx="12" cy="9" r="2"/>
                                                </svg>
                                            </span>
                                            <p class="break-words text-xs leading-5 text-[#0b1528]">
                                                <?= $e($adresse ?: 'Adresse non renseignée') ?>
                                            </p>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#0e2a4e]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.7">
                                                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                                                    <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>
                                                </svg>
                                            </span>
                                            <p class="text-xs text-[#0b1528]">
                                                Inscription :
                                                <?php if ($dateCreation && strtotime((string) $dateCreation) !== false): ?>
                                                    <?= $e(date('d/m/Y', strtotime((string) $dateCreation))) ?>
                                                <?php else: ?>
                                                    Inconnue
                                                <?php endif; ?>
                                            </p>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#0e2a4e] text-xs font-bold">
                                                ID
                                            </span>
                                            <p class="break-all text-xs text-[#0b1528]">
                                                NINEA : <?= $e($ninea ?: 'Non renseigné') ?>
                                            </p>
                                        </div>

                                    </div>

                                    <!-- ACTIONS -->
                                    <div class="mt-6 flex items-center gap-2 border-t border-[#e2e8f0] pt-5">

                                        <?php if ($idAgence !== ''): ?>

                                            <a href="/immo-sn/admin/agences/<?= rawurlencode($idAgence) ?>/details"
                                               class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#071b36] px-3 py-3 text-xs font-bold text-white transition hover:bg-[#0e2a4e]">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4 text-[#f5b800]"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                Détails
                                            </a>

                                            <details class="relative">

                                                <summary title="Autres actions"
                                                         class="flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-xl border border-[#e2e8f0] bg-white text-xl font-bold text-[#071b36] transition hover:bg-[#f8fafc] [&::-webkit-details-marker]:hidden">
                                                    ⋮
                                                </summary>

                                                <div class="absolute bottom-13 right-0 z-40 w-56 rounded-xl border border-[#e2e8f0] bg-white p-2 shadow-[0_12px_35px_rgba(7,27,54,0.15)]">

                                                    <?php if ($statut === 'en_attente'): ?>

                                                        <form method="POST"
                                                              action="/immo-sn/admin/agences/<?= rawurlencode($idAgence) ?>/approuver"
                                                              onsubmit="return confirm('Confirmer l’approbation de cette agence ?');">
                                                            <button type="submit" class="w-full rounded-lg px-3 py-2.5 text-left text-xs font-semibold text-emerald-700 hover:bg-emerald-50">
                                                                ✓ Approuver
                                                            </button>
                                                        </form>

                                                        <form method="POST"
                                                              action="/immo-sn/admin/agences/<?= rawurlencode($idAgence) ?>/rejeter"
                                                              onsubmit="return confirm('Confirmer le rejet de cette agence ?');">
                                                            <button type="submit" class="w-full rounded-lg px-3 py-2.5 text-left text-xs font-semibold text-red-700 hover:bg-red-50">
                                                                ✕ Rejeter
                                                            </button>
                                                        </form>

                                                    <?php elseif ($statut === 'actif'): ?>

                                                        <form method="POST"
                                                              action="/immo-sn/admin/agences/<?= rawurlencode($idAgence) ?>/suspendre"
                                                              onsubmit="return confirm('Confirmer la suspension de cette agence ?');">
                                                            <button type="submit" class="w-full rounded-lg px-3 py-2.5 text-left text-xs font-semibold text-orange-700 hover:bg-orange-50">
                                                                Suspendre l'agence
                                                            </button>
                                                        </form>

                                                    <?php elseif ($statut === 'suspendue'): ?>

                                                        <form method="POST"
                                                              action="/immo-sn/admin/agences/<?= rawurlencode($idAgence) ?>/reactiver"
                                                              onsubmit="return confirm('Confirmer la réactivation de cette agence ?');">
                                                            <button type="submit" class="w-full rounded-lg px-3 py-2.5 text-left text-xs font-semibold text-emerald-700 hover:bg-emerald-50">
                                                                ✓ Réactiver l'agence
                                                            </button>
                                                        </form>

                                                    <?php elseif ($statut === 'refusee'): ?>

                                                        <form method="POST"
                                                              action="/immo-sn/admin/agences/<?= rawurlencode($idAgence) ?>/supprimer"
                                                              onsubmit="return confirm('Attention : supprimer définitivement cette agence ? Le compte utilisateur associé sera conservé.');">
                                                            <button type="submit" class="w-full rounded-lg px-3 py-2.5 text-left text-xs font-semibold text-red-700 hover:bg-red-50">
                                                                Supprimer l'agence
                                                            </button>
                                                        </form>

                                                    <?php endif; ?>

                                                </div>

                                            </details>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

        </div>
    </main>

</div>