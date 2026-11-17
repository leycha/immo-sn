
<?php

use Core\Session;

$demandes = is_array($demandes ?? null) ? $demandes : [];

$e = static function ($valeur): string {
    return htmlspecialchars(
        (string) ($valeur ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$messageSucces = Session::getFlash('success');
$messageErreur = Session::getFlash('error');

$formatDate = static function ($date): string {
    if (empty($date)) {
        return 'Non renseignée';
    }

    try {
        return (new DateTime((string) $date))->format('d/m/Y à H:i');
    } catch (Throwable $exception) {
        return 'Date indisponible';
    }
};

$totalDemandes = count($demandes);

?>

<div class="min-h-screen bg-[#faf7f2]">

    <!-- EN-TÊTE -->
    <header class="sticky top-0 z-20 border-b border-[#e8ded4] bg-[#faf7f2]/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-medium text-[#a98260]">
                        <span>IMMO-SN</span>
                        <span>/</span>
                        <span>Administration</span>
                        <span>/</span>
                        <span class="text-[#30221b]">Renouvellements</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#30221b] sm:text-3xl">
                        Demandes de renouvellement
                    </h1>

                    <p class="mt-1 text-sm text-[#8d7a6b]">
                        Gestion et prolongation des annonces expirées.
                    </p>
                </div>

                <a
                    href="/immo-sn/admin"
                    class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#5a402d]"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6M9 12h12"/>
                    </svg>
                    Tableau de bord
                </a>

            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

            <!-- MESSAGES FLASH -->
            <?php if ($messageSucces): ?>
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                        ✓
                    </span>
                    <span><?= $e($messageSucces) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($messageErreur): ?>
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100">
                        !
                    </span>
                    <span><?= $e($messageErreur) ?></span>
                </div>
            <?php endif; ?>

            <!-- BANNIÈRE PRINCIPALE -->
            <section class="relative overflow-hidden rounded-[26px] bg-[#30221b] shadow-[0_18px_50px_rgba(48,34,27,0.14)]">

                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f7b500]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#a98260]/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-7 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between lg:px-10">

                    <div class="max-w-2xl">

                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>
                            <span class="text-xs font-medium text-[#ded3ca]">
                                Centre de gestion immobilière
                            </span>
                        </div>

                        <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                            Gestion des
                            <span class="text-[#f7b500]">renouvellements</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-[#cfc3ba]">
                            Examinez les demandes de prolongation des annonces.
                            Consultez les informations des propriétaires et les
                            détails des biens avant de prendre votre décision.
                        </p>

                        <div class="mt-7 flex flex-wrap gap-3">

                            <a
                                href="#liste-renouvellements"
                                class="inline-flex items-center gap-2 rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-bold text-[#30221b] transition hover:bg-[#ffca38]"
                            >
                                Consulter les demandes
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M7 17 17 7M7 7h10v10"/>
                                </svg>
                            </a>

                            <a
                                href="/immo-sn/admin"
                                class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                            >
                                Tableau de bord
                            </a>

                        </div>

                    </div>

                    <!-- ILLUSTRATION -->
                    <div class="hidden h-48 w-48 shrink-0 items-center justify-center rounded-[30px] border border-white/10 bg-white/5 lg:flex">

                        <svg class="h-28 w-28 text-[#f7b500]" viewBox="0 0 120 120" fill="none">
                            <rect x="22" y="25" width="76" height="72" rx="9" stroke="currentColor" stroke-width="3.5"/>
                            <path d="M22 45h76" stroke="currentColor" stroke-width="3.5"/>
                            <path d="M42 17v16M78 17v16" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/>
                            <path d="M39 62h17M39 76h10" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/>
                            <circle cx="78" cy="75" r="17" fill="#30221b" stroke="currentColor" stroke-width="3.5"/>
                            <path d="M78 66v10l6 4" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        </svg>

                    </div>

                </div>
            </section>

            <!-- STATISTIQUES -->
            <section class="mt-6">

                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">
                            Vue d'ensemble
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-[#30221b]">
                            Suivi des renouvellements
                        </h2>

                        <p class="mt-1 text-sm text-[#8d7a6b]">
                            État actuel des demandes reçues.
                        </p>
                    </div>

                    <span class="w-fit rounded-full border border-[#e8ded4] bg-white px-4 py-2 text-xs font-bold text-[#5a402d]">
                        <?= number_format($totalDemandes, 0, ',', ' ') ?> demande(s)
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                    <!-- DEMANDES EN ATTENTE -->
                    <div class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#fff4cf] text-[#9a7000]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                </svg>
                            </div>

                            <span class="rounded-full bg-[#fff4cf] px-3 py-1.5 text-xs font-bold text-[#9a7000]">
                                À examiner
                            </span>

                        </div>

                        <p class="mt-5 text-3xl font-bold tabular-nums text-[#30221b]">
                            <?= number_format($totalDemandes, 0, ',', ' ') ?>
                        </p>

                        <p class="mt-1 text-sm font-semibold text-[#5a402d]">
                            Demandes en attente
                        </p>

                        <p class="mt-1 text-xs text-[#9a887a]">
                            Prolongations à examiner par l'administration.
                        </p>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-[#f5eddd]">
                            <div class="h-full w-full rounded-full bg-[#f7b500]"></div>
                        </div>

                    </div>

                    <!-- DURÉE DU RENOUVELLEMENT -->
                    <div class="rounded-[20px] border border-[#e8ded4] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#edf5ef] text-[#497657]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                                    <path d="M16 3v4M8 3v4M3 10h18"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 15 2 2 4-4"/>
                                </svg>
                            </div>

                            <span class="rounded-full bg-[#edf5ef] px-3 py-1.5 text-xs font-bold text-[#497657]">
                                Durée fixe
                            </span>

                        </div>

                        <p class="mt-5 text-3xl font-bold tabular-nums text-[#30221b]">
                            30 jours
                        </p>

                        <p class="mt-1 text-sm font-semibold text-[#5a402d]">
                            Durée du renouvellement
                        </p>

                        <p class="mt-1 text-xs text-[#9a887a]">
                            Prolongation prévue après approbation.
                        </p>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-[#edf5ef]">
                            <div class="h-full w-full rounded-full bg-[#497657]"></div>
                        </div>

                    </div>

                </div>
            </section>

            <!-- LISTE DES DEMANDES -->
            <section id="liste-renouvellements" class="mt-8">

                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a98260]">
                            Gestion des dossiers
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-[#30221b]">
                            Liste des demandes
                        </h2>

                        <p class="mt-1 text-sm text-[#8d7a6b]">
                            Consultez les annonces et gérez les prolongations.
                        </p>
                    </div>

                    <span class="w-fit rounded-full border border-[#e8ded4] bg-white px-4 py-2 text-xs font-bold text-[#5a402d]">
                        <?= number_format($totalDemandes, 0, ',', ' ') ?> demande(s)
                    </span>

                </div>

                <?php if (empty($demandes)): ?>

                    <!-- AUCUNE DEMANDE -->
                    <div class="rounded-[24px] border border-[#e8ded4] bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#f2e8e1] text-[#a98260]">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#30221b]">
                            Aucune demande en attente
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#9a887a]">
                            Toutes les demandes de renouvellement ont été traitées.
                            Les nouvelles demandes apparaîtront ici.
                        </p>

                    </div>

                <?php else: ?>

                    <!-- CARTES DES DEMANDES -->
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                        <?php foreach ($demandes as $demande): ?>

                            <?php
                            $demandeId = (string) ($demande['demande_id'] ?? '');
                            $idBien = (string) ($demande['bien_id'] ?? '');

                            $nomComplet = trim(
                                (string) ($demande['prenom_proprietaire'] ?? '')
                                . ' '
                                . (string) ($demande['nom_proprietaire'] ?? '')
                            );

                            $nomComplet = $nomComplet !== ''
                                ? $nomComplet
                                : 'Propriétaire non renseigné';

                            $titreBien = $demande['titre_bien'] ?? 'Annonce sans titre';
                            $statutBien = $demande['statut_bien'] ?? 'Non renseigné';
                            $email = $demande['email_proprietaire'] ?? 'Non renseignée';
                            ?>

                            <article class="group relative flex h-full flex-col overflow-hidden rounded-[24px] border border-[#e8ded4] bg-white shadow-[0_8px_25px_rgba(48,34,27,0.04)] transition duration-300 hover:-translate-y-1 hover:border-[#c9ad91] hover:shadow-[0_18px_40px_rgba(48,34,27,0.10)]">

                                <!-- BANDEAU SUPÉRIEUR -->
                                <div class="h-2 bg-gradient-to-r from-[#30221b] via-[#a98260] to-[#f7b500]"></div>

                                <div class="flex flex-1 flex-col p-5 sm:p-6">

                                    <!-- TITRE ET STATUT -->
                                    <div class="flex items-start justify-between gap-3">

                                        <div class="flex min-w-0 items-center gap-3">

                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#faf7f2] text-[#a98260] ring-1 ring-[#e8ded4]">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01"/>
                                                </svg>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#a98260]">
                                                    Annonce immobilière
                                                </p>
<h3 class="mt-1 min-w-0 break-words text-base font-bold leading-6 text-[#30221b]">
                                                   <?= $e($titreBien) ?>
                                                </h3>
                                            </div>

                                        </div>

                                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-[#fff4cf] px-3 py-1.5 text-[10px] font-bold text-[#9a7000]">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#d9a400]"></span>
                                            En attente
                                        </span>

                                    </div>

                                    <!-- DATE DE DEMANDE -->
                                    <div class="mt-5 flex items-center gap-2 rounded-xl bg-[#faf7f2] px-4 py-3">
                                        <svg class="h-4 w-4 shrink-0 text-[#a98260]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                                            <path d="M16 2v4M8 2v4M3 10h18"/>
                                        </svg>

                                        <div class="min-w-0">
                                            <p class="text-[10px] font-semibold uppercase tracking-wider text-[#9a887a]">
                                                Date de la demande
                                            </p>

                                            <p class="mt-0.5 text-xs font-bold text-[#30221b]">
                                                <?= $e($formatDate($demande['date_demande'] ?? null)) ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- INFORMATIONS -->
                                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                        <!-- PROPRIÉTAIRE -->
                                        <div class="rounded-2xl border border-[#eee7de] bg-white p-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#a98260]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <circle cx="12" cy="8" r="4"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-2a8 8 0 0116 0v2"/>
                                                </svg>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#9a887a]">
                                                    Propriétaire
                                                </p>
                                            </div>

                                            <p class="mt-2 break-words text-sm font-bold text-[#30221b]">
                                                <?= $e($nomComplet) ?>
                                            </p>
                                        </div>

                                        <!-- STATUT DU BIEN -->
                                        <div class="rounded-2xl border border-[#eee7de] bg-white p-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#a98260]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                                </svg>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#9a887a]">
                                                    Statut de l'annonce
                                                </p>
                                            </div>

                                            <p class="mt-2 break-words text-sm font-bold text-[#30221b]">
                                                <?= $e($statutBien) ?>
                                            </p>
                                        </div>

                                        <!-- EMAIL -->
                                        <div class="rounded-2xl border border-[#eee7de] bg-white p-4 sm:col-span-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#a98260]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                                </svg>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#9a887a]">
                                                    Adresse e-mail
                                                </p>
                                            </div>

                                            <p class="mt-2 break-all text-sm font-medium text-[#5a402d]">
                                                <?= $e($email) ?>
                                            </p>
                                        </div>

                                        <!-- EXPIRATION -->
                                        <div class="rounded-2xl border border-[#eee7de] bg-white p-4 sm:col-span-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#a98260]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                                </svg>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#9a887a]">
                                                    Date d'expiration
                                                </p>
                                            </div>

                                            <p class="mt-2 text-sm font-bold text-[#30221b]">
                                                <?= $e($formatDate($demande['expire_at'] ?? null)) ?>
                                            </p>
                                        </div>

                                    </div>

                                    <!-- ACTIONS -->
                                    <div class="mt-auto border-t border-[#f1ece5] pt-5">

                                        <!-- CONSULTER LE BIEN -->
                                        <a
                                            href="/immo-sn/biens/<?= rawurlencode($idBien) ?>"
                                            class="group flex w-full items-center justify-between rounded-xl border border-[#e8ded4] bg-[#faf7f2] px-4 py-3.5 text-sm font-bold text-[#30221b] transition hover:border-[#a98260] hover:bg-[#f2e8e1]"
                                        >
                                            <span class="inline-flex items-center gap-2">
                                                <svg class="h-5 w-5 text-[#a98260]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                Consulter l'annonce
                                            </span>

                                            <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M5 12h14m-6-6 6 6-6 6"/>
                                            </svg>
                                        </a>

                                        <!-- APPROUVER -->
                                        <form
                                            method="POST"
                                            action="/immo-sn/admin/renouvellements/<?= rawurlencode($demandeId) ?>/approuver"
                                            class="mt-3"
                                            onsubmit="return confirm('Confirmer le renouvellement de cette annonce pour 30 jours ?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="_csrf_token"
                                                value="<?= $e(Session::csrfToken()) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-4 py-3.5 text-sm font-bold text-white transition hover:bg-[#5a402d]"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                                                </svg>
                                                Approuver le renouvellement
                                            </button>

                                        </form>

                                        <!-- REFUSER -->
                                        <form
                                            method="POST"
                                            action="/immo-sn/admin/renouvellements/<?= rawurlencode($demandeId) ?>/refuser"
                                            class="mt-4 rounded-xl border border-[#f1d9d5] bg-[#fff9f8] p-4"
                                            onsubmit="return confirm('Confirmer le refus de cette demande ?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="_csrf_token"
                                                value="<?= $e(Session::csrfToken()) ?>"
                                            >

                                            <label
                                                for="motif-<?= $e($demandeId) ?>"
                                                class="mb-2 flex items-center gap-2 text-xs font-bold text-[#30221b]"
                                            >
                                                <svg class="h-4 w-4 text-[#a43d35]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.3 3.8 2.6 17.2A2 2 0 004.3 20h15.4a2 2 0 001.7-2.8L13.7 3.8a2 2 0 00-3.4 0z"/>
                                                </svg>
                                                Motif du refus
                                            </label>

                                            <textarea
                                                id="motif-<?= $e($demandeId) ?>"
                                                name="motif_refus"
                                                rows="3"
                                                maxlength="2000"
                                                required
                                                placeholder="Expliquez pourquoi cette demande est refusée..."
                                                class="w-full resize-y rounded-xl border border-[#ead7d3] bg-white px-4 py-3 text-sm text-[#30221b] outline-none transition placeholder:text-[#b5a79d] focus:border-[#a43d35] focus:ring-2 focus:ring-red-100"
                                            ></textarea>

                                            <button
                                                type="submit"
                                                class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border border-[#e9c7c2] bg-white px-4 py-3 text-sm font-bold text-[#a43d35] transition hover:bg-[#fbe9e7]"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/>
                                                </svg>
                                                Refuser la demande
                                            </button>

                                        </form>

                                    </div>

                                </div>
                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

            <!-- PIED DE PAGE -->
            <footer class="mt-10 border-t border-[#e8ded4] py-6 text-center">
                <p class="text-xs font-medium text-[#a99484]">
                    IMMO-SN — Administration
                </p>
            </footer>

        </div>
    </main>
</div>
