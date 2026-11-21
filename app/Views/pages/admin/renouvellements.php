
<?php

use Core\Session;

/**
 * Vue : Demandes de renouvellement — IMMO-SN
 * Emplacement : app/Views/pages/admin/renouvellements.php
 */

$demandes = is_array($demandes ?? null) ? $demandes : [];

$e = static function ($valeur): string {
    return htmlspecialchars((string) ($valeur ?? ''), ENT_QUOTES, 'UTF-8');
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

<div class="min-h-screen bg-[#f5f7fb] text-[#0b1528]">

    <!-- =========================================================
         EN-TÊTE
    ========================================================== -->
    <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500">
                        <span>IMMO-SN</span>
                        <span>/</span>
                        <span>Administration</span>
                        <span>/</span>
                        <span class="text-[#0e2a4e]">Renouvellements</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#071b36] sm:text-3xl">
                        Demandes de renouvellement
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Gestion et prolongation des annonces immobilières.
                    </p>
                </div>

                <a
                    href="/immo-sn/admin"
                    class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#071b36] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#0e2a4e]"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m15 18-6-6 6-6M9 12h12"/>
                    </svg>
                    Tableau de bord
                </a>

            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

            <!-- =================================================
                 MESSAGES FLASH
            ================================================== -->
            <?php if ($messageSucces): ?>
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m5 12 4 4L19 6"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-emerald-900">
                            Opération réussie
                        </p>
                        <p class="mt-1 text-sm text-emerald-800">
                            <?= $e($messageSucces) ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($messageErreur): ?>
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v4m0 4h.01M10.3 3.8 2.6 17.2A2 2 0 004.3 20h15.4a2 2 0 001.7-2.8L13.7 3.8a2 2 0 00-3.4 0z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-red-900">
                            Une erreur est survenue
                        </p>
                        <p class="mt-1 text-sm text-red-800">
                            <?= $e($messageErreur) ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- =================================================
                 BANNIÈRE PRINCIPALE
            ================================================== -->
            <section class="relative overflow-hidden rounded-[28px] bg-[#071b36] shadow-[0_18px_50px_rgba(7,27,54,0.16)]">

                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#f5b800]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#0e2a4e]/60 blur-3xl"></div>

                <div class="relative flex flex-col gap-8 px-6 py-8 sm:px-8 lg:flex-row lg:items-center lg:justify-between lg:px-10 lg:py-10">

                    <div class="max-w-2xl">

                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                            <span class="text-xs font-medium text-slate-200">
                                Centre de gestion immobilière
                            </span>
                        </div>

                        <h2 class="font-['Playfair_Display'] text-3xl font-semibold leading-tight text-white sm:text-4xl">
                            Gestion des
                            <span class="text-[#f5b800]">renouvellements</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">
                            Examinez les demandes de prolongation des annonces.
                            Vérifiez les informations des propriétaires et les
                            détails des biens avant de prendre votre décision.
                        </p>

                        <div class="mt-7 flex flex-wrap gap-3">

                            <a
                                href="#liste-renouvellements"
                                class="inline-flex items-center gap-2 rounded-xl bg-[#f5b800] px-5 py-3 text-sm font-bold text-[#071b36] shadow-sm transition hover:bg-[#ffca28]"
                            >
                                Consulter les demandes

                                <svg class="h-4 w-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7 17 17 7M7 7h10v10"/>
                                </svg>
                            </a>

                            <a
                                href="/immo-sn/admin"
                                class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m3 10 9-7 9 7M5 9v11h14V9M9 20v-6h6v6"/>
                                </svg>
                                Tableau de bord
                            </a>

                        </div>

                    </div>

                    <!-- ILLUSTRATION -->
                    <div class="hidden h-48 w-48 shrink-0 items-center justify-center rounded-[30px] border border-white/10 bg-[#0e2a4e]/70 backdrop-blur-sm lg:flex">

                        <svg class="h-28 w-28 text-[#f5b800]" viewBox="0 0 120 120" fill="none">
                            <rect x="22" y="25" width="76" height="72" rx="9"
                                  stroke="currentColor" stroke-width="3.5"/>
                            <path d="M22 45h76" stroke="currentColor" stroke-width="3.5"/>
                            <path d="M42 17v16M78 17v16" stroke="currentColor"
                                  stroke-width="3.5" stroke-linecap="round"/>
                            <path d="M39 62h17M39 76h10" stroke="currentColor"
                                  stroke-width="3.5" stroke-linecap="round"/>
                            <circle cx="78" cy="75" r="17" fill="#071b36"
                                  stroke="currentColor" stroke-width="3.5"/>
                            <path d="M78 66v10l6 4" stroke="currentColor"
                                  stroke-width="3" stroke-linecap="round"/>
                        </svg>

                    </div>

                </div>
            </section>

            <!-- =================================================
                 STATISTIQUES
            ================================================== -->
            <section class="mt-8">

                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                            Vue d'ensemble
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-[#071b36]">
                            Suivi des renouvellements
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            État actuel des demandes reçues.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-[#071b36]">
                        <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                        <?= number_format($totalDemandes, 0, ',', ' ') ?> demande(s)
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                    <!-- DEMANDES EN ATTENTE -->
                    <article class="group rounded-[22px] border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        <div class="flex items-start justify-between gap-4">

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 7v5l3 2"/>
                                </svg>
                            </div>

                            <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                À examiner
                            </span>

                        </div>

                        <p class="mt-6 text-4xl font-bold tabular-nums text-[#071b36]">
                            <?= number_format($totalDemandes, 0, ',', ' ') ?>
                        </p>

                        <p class="mt-1 text-sm font-semibold text-[#0b1528]">
                            Demandes en attente
                        </p>

                        <p class="mt-2 text-xs leading-5 text-slate-500">
                            Prolongations à examiner par l'administration.
                        </p>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full w-full rounded-full bg-[#f5b800]"></div>
                        </div>

                    </article>

                    <!-- DURÉE DU RENOUVELLEMENT -->
                    <article class="group rounded-[22px] border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        <div class="flex items-start justify-between gap-4">

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                                    <path d="M16 3v4M8 3v4M3 10h18"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m9 15 2 2 4-4"/>
                                </svg>
                            </div>

                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Durée fixe
                            </span>

                        </div>

                        <p class="mt-6 text-4xl font-bold text-[#071b36]">
                            30 <span class="text-lg font-semibold text-slate-400">jours</span>
                        </p>

                        <p class="mt-1 text-sm font-semibold text-[#0b1528]">
                            Durée du renouvellement
                        </p>

                        <p class="mt-2 text-xs leading-5 text-slate-500">
                            Prolongation prévue après approbation.
                        </p>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full w-full rounded-full bg-emerald-600"></div>
                        </div>

                    </article>

                </div>
            </section>

            <!-- =================================================
                 LISTE DES DEMANDES
            ================================================== -->
            <section id="liste-renouvellements" class="mt-9">

                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0e2a4e]">
                            Gestion des dossiers
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-[#071b36]">
                            Liste des demandes
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Consultez les annonces et gérez les prolongations.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-[#071b36]">
                        <span class="h-2 w-2 rounded-full bg-[#f5b800]"></span>
                        <?= number_format($totalDemandes, 0, ',', ' ') ?> demande(s)
                    </span>

                </div>

                <?php if (empty($demandes)): ?>

                    <!-- AUCUNE DEMANDE -->
                    <div class="rounded-[24px] border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-[#0e2a4e]">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8 6h13M8 12h13M8 18h13"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#071b36]">
                            Aucune demande en attente
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            Toutes les demandes de renouvellement ont été traitées.
                            Les nouvelles demandes apparaîtront ici.
                        </p>

                        <a
                            href="/immo-sn/admin"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#071b36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0e2a4e]"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m15 18-6-6 6-6M9 12h12"/>
                            </svg>
                            Retour au tableau de bord
                        </a>

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

                            <article class="group relative flex h-full flex-col overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#0e2a4e]/30 hover:shadow-xl">

                                <!-- BANDEAU -->
                                <div class="h-1.5 bg-gradient-to-r from-[#071b36] via-[#0e2a4e] to-[#f5b800]"></div>

                                <div class="flex flex-1 flex-col p-5 sm:p-6">

                                    <!-- TITRE -->
                                    <div class="flex items-start justify-between gap-3">

                                        <div class="flex min-w-0 items-center gap-3">

                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#071b36]/5 text-[#0e2a4e] ring-1 ring-slate-200">
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="1.6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5M9 9h.01M15 9h.01"/>
                                                </svg>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#0e2a4e]">
                                                    Annonce immobilière
                                                </p>

                                                <h3 class="mt-1 break-words text-base font-bold leading-6 text-[#071b36]">
                                                    <?= $e($titreBien) ?>
                                                </h3>
                                            </div>

                                        </div>

                                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-[10px] font-bold text-amber-800">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            En attente
                                        </span>

                                    </div>

                                    <!-- DATE DE DEMANDE -->
                                    <div class="mt-5 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#0e2a4e] shadow-sm">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.7">
                                                <rect x="3" y="4" width="18" height="17" rx="2"/>
                                                <path d="M16 2v4M8 2v4M3 10h18"/>
                                            </svg>
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                                Date de la demande
                                            </p>
                                            <p class="mt-0.5 text-xs font-bold text-[#071b36]">
                                                <?= $e($formatDate($demande['date_demande'] ?? null)) ?>
                                            </p>
                                        </div>

                                    </div>

                                    <!-- INFORMATIONS -->
                                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                        <!-- PROPRIÉTAIRE -->
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:bg-slate-50">

                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#0e2a4e]" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <circle cx="12" cy="8" r="4"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M4 21v-2a8 8 0 0116 0v2"/>
                                                </svg>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Propriétaire
                                                </p>
                                            </div>

                                            <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                                                <?= $e($nomComplet) ?>
                                            </p>

                                        </div>

                                        <!-- STATUT DU BIEN -->
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:bg-slate-50">

                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#0e2a4e]" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M12 7v5l3 2"/>
                                                </svg>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Statut de l'annonce
                                                </p>
                                            </div>

                                            <p class="mt-2 break-words text-sm font-bold text-[#071b36]">
                                                <?= $e($statutBien) ?>
                                            </p>

                                        </div>

                                        <!-- EMAIL -->
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:bg-slate-50 sm:col-span-2">

                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#0e2a4e]" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                                </svg>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Adresse e-mail
                                                </p>
                                            </div>

                                            <p class="mt-2 break-all text-sm font-medium text-[#0e2a4e]">
                                                <?= $e($email) ?>
                                            </p>

                                        </div>

                                        <!-- EXPIRATION -->
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:bg-slate-50 sm:col-span-2">

                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#0e2a4e]" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="1.7">
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M12 7v5l3 2"/>
                                                </svg>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Date d'expiration
                                                </p>
                                            </div>

                                            <p class="mt-2 text-sm font-bold text-[#071b36]">
                                                <?= $e($formatDate($demande['expire_at'] ?? null)) ?>
                                            </p>

                                        </div>

                                    </div>

                                    <!-- =================================================
                                         ACTIONS
                                    ================================================== -->
                                    <div class="mt-auto border-t border-slate-200 pt-5">

                                        <!-- CONSULTER -->
                                        <a
                                            href="/immo-sn/biens/<?= rawurlencode($idBien) ?>"
                                            class="group flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-bold text-[#071b36] transition hover:border-[#0e2a4e]/30 hover:bg-slate-100"
                                        >
                                            <span class="inline-flex items-center gap-2">
                                                <svg class="h-5 w-5 text-[#0e2a4e]" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>

                                                Consulter l'annonce
                                            </span>

                                            <svg class="h-4 w-4 transition group-hover:translate-x-1"
                                                 viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2">
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
                                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#071b36] px-4 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#0e2a4e]"
                                            >
                                                <svg class="h-4 w-4 text-[#f5b800]" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="m5 12 4 4L19 6"/>
                                                </svg>
                                                Approuver le renouvellement
                                            </button>

                                        </form>

                                        <!-- REFUSER -->
                                        <form
                                            method="POST"
                                            action="/immo-sn/admin/renouvellements/<?= rawurlencode($demandeId) ?>/refuser"
                                            class="mt-4 rounded-xl border border-red-200 bg-red-50/50 p-4"
                                            onsubmit="return confirm('Confirmer le refus de cette demande ?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="_csrf_token"
                                                value="<?= $e(Session::csrfToken()) ?>"
                                            >

                                            <label
                                                for="motif-<?= $e($demandeId) ?>"
                                                class="mb-2 flex items-center gap-2 text-xs font-bold text-[#071b36]"
                                            >
                                                <svg class="h-4 w-4 text-red-700" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M12 9v4m0 4h.01"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M10.3 3.8 2.6 17.2A2 2 0 004.3 20h15.4a2 2 0 001.7-2.8L13.7 3.8a2 2 0 00-3.4 0z"/>
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
                                                class="w-full resize-y rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-[#0b1528] outline-none transition placeholder:text-slate-400 focus:border-red-600 focus:ring-2 focus:ring-red-100"
                                            ></textarea>

                                            <button
                                                type="submit"
                                                class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-3 text-sm font-bold text-red-700 transition hover:bg-red-50"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M6 6l12 12M18 6 6 18"/>
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

            <!-- =================================================
                 PIED DE PAGE
            ================================================== -->
            <footer class="mt-10 border-t border-slate-200 py-6 text-center">
                <p class="text-xs font-medium text-slate-500">
                    IMMO-SN — Administration
                </p>
            </footer>

        </div>
    </main>
</div>
