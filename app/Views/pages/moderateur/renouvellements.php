
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
    } catch (Throwable $e) {
        return 'Date indisponible';
    }
};

?>

<div class="min-h-screen bg-[#faf7f2] px-3 py-6 sm:px-6 sm:py-8 lg:px-8">

    <div class="mx-auto max-w-7xl">

        <!-- TOPBAR -->
        <div class="mb-8 flex min-w-0 flex-col gap-4 rounded-2xl bg-white px-4 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:px-5">

            <div class="min-w-0">

                <div class="flex min-w-0 flex-wrap items-center gap-2 text-xs font-medium text-[#a98260]">
                    <span>IMMO-SN</span>
                    <span class="text-[#cdbba9]">/</span>
                    <span>Administration</span>
                    <span class="text-[#cdbba9]">/</span>
                    <span class="break-words text-[#30221b]">Renouvellements</span>
                </div>

                <p class="mt-1 break-words text-sm font-semibold text-[#30221b]">
                    Espace de modération
                </p>

            </div>

            <a
                href="/immo-sn/moderateur"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#5a402d] sm:w-auto sm:shrink-0"
            >
                <span aria-hidden="true">&larr;</span>
                Tableau de bord
            </a>

        </div>

        <!-- HERO -->
        <section class="relative isolate mb-8 overflow-hidden rounded-[28px] bg-[#30221b] px-4 py-7 shadow-xl shadow-[#30221b]/10 sm:px-9 sm:py-10">

            <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full border-[35px] border-white/5"></div>
            <div class="pointer-events-none absolute -bottom-24 right-36 h-48 w-48 rounded-full bg-[#f7b500]/10 blur-3xl"></div>

            <div class="relative z-10 flex min-w-0 flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

                <div class="min-w-0 max-w-2xl">

                    <div class="mb-5 inline-flex max-w-full flex-wrap items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-[#f7c95c]">
                        <span class="h-2 w-2 shrink-0 rounded-full bg-[#f7b500]"></span>
                        Gestion des renouvellements
                    </div>

                    <h1 class="break-words text-2xl font-bold leading-tight text-white sm:text-4xl">
                        Demandes de renouvellement.
                    </h1>

                    <p class="mt-4 max-w-xl break-words text-sm leading-7 text-white/70 sm:text-base">
                        Examinez les demandes de prolongation des annonces expirées.
                        Vérifiez les informations et décidez d'accepter ou de refuser
                        chaque renouvellement.
                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">

                        <a
                            href="#liste-demandes"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#f7b500] px-5 py-3 text-sm font-bold text-[#30221b] transition hover:bg-[#ffd04d] sm:w-auto"
                        >
                            Consulter les demandes
                            <span aria-hidden="true">&darr;</span>
                        </a>

                        <a
                            href="/immo-sn/moderateur"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20 sm:w-auto"
                        >
                            Retour au tableau de bord
                        </a>

                    </div>

                </div>

                <div class="hidden shrink-0 lg:flex lg:items-center lg:justify-center">
                    <div class="flex h-36 w-36 items-center justify-center rounded-[32px] bg-white/10 text-[#f7b500]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-20 w-20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 7h18"></path>
                            <path d="M5 3h14v18H5z"></path>
                            <path d="M9 11h6"></path>
                            <path d="M9 15h6"></path>
                            <path d="m9 19 2 2 4-4"></path>
                        </svg>

                    </div>
                </div>

            </div>

        </section>

        <!-- MESSAGES -->
        <?php if ($messageSucces): ?>

            <div class="mb-6 flex min-w-0 items-start gap-3 rounded-2xl bg-green-50 px-4 py-4 text-sm font-medium text-green-800 sm:px-5">
                <span class="shrink-0 text-lg" aria-hidden="true">&#10003;</span>
                <p class="min-w-0 break-words"><?= $e($messageSucces) ?></p>
            </div>

        <?php endif; ?>

        <?php if ($messageErreur): ?>

            <div class="mb-6 flex min-w-0 items-start gap-3 rounded-2xl bg-red-50 px-4 py-4 text-sm font-medium text-red-800 sm:px-5">
                <span class="shrink-0 text-lg" aria-hidden="true">!</span>
                <p class="min-w-0 break-words"><?= $e($messageErreur) ?></p>
            </div>

        <?php endif; ?>

        <!-- STATISTIQUES -->
        <section class="mb-10">

            <div class="mb-5">
                <p class="break-words text-xs font-bold uppercase tracking-wider text-[#a98260]">
                    Vue d'ensemble
                </p>

                <h2 class="mt-1 break-words text-xl font-bold text-[#30221b]">
                    Activité des renouvellements
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <!-- DEMANDES EN ATTENTE -->
                <div class="flex min-w-0 items-center justify-between gap-3 rounded-2xl border border-[#eee5dc] bg-white p-4 shadow-sm transition hover:shadow-md sm:p-6">

                    <div class="min-w-0">
                        <p class="break-words text-sm font-medium text-[#75665d]">
                            Demandes en attente
                        </p>

                        <p class="mt-3 text-3xl font-bold text-[#30221b] sm:text-4xl">
                            <?= number_format(count($demandes), 0, ',', ' ') ?>
                        </p>

                        <p class="mt-1 break-words text-xs text-[#a98260]">
                            Demandes à examiner
                        </p>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 sm:h-14 sm:w-14">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                    </div>

                </div>

                <!-- DURÉE -->
                <div class="flex min-w-0 items-center justify-between gap-3 rounded-2xl border border-[#eee5dc] bg-white p-4 shadow-sm transition hover:shadow-md sm:p-6">

                    <div class="min-w-0">
                        <p class="break-words text-sm font-medium text-[#75665d]">
                            Durée du renouvellement
                        </p>

                        <p class="mt-3 break-words text-3xl font-bold text-[#8b684d] sm:text-4xl">
                            30 jours
                        </p>

                        <p class="mt-1 break-words text-xs text-[#a98260]">
                            Durée prévue par renouvellement
                        </p>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#f5eee5] text-[#8b684d] sm:h-14 sm:w-14">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                    </div>

                </div>

            </div>

        </section>

        <!-- LISTE DES DEMANDES -->
        <section id="liste-demandes" class="min-w-0">

            <div class="mb-6 flex min-w-0 flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                <div class="min-w-0">
                    <p class="break-words text-xs font-bold uppercase tracking-wider text-[#a98260]">
                        Traitement des demandes
                    </p>

                    <h2 class="mt-1 break-words text-xl font-bold text-[#30221b] sm:text-2xl">
                        Renouvellements à examiner
                    </h2>
                </div>

                <p class="break-words text-sm text-[#75665d] sm:shrink-0">
                    <?= number_format(count($demandes), 0, ',', ' ') ?>
                    demande(s) en attente
                </p>

            </div>

            <!-- GRILLE : 1 COLONNE MOBILE, 2 TABLETTE, 3 ORDINATEUR -->
            <div class="grid min-w-0 grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                <?php if (empty($demandes)): ?>

                    <div class="col-span-full rounded-3xl border border-[#eee5dc] bg-white px-4 py-12 text-center shadow-sm sm:px-6 sm:py-16">

                        <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-2xl bg-[#f5eee5] text-[#a98260]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                            >
                                <path d="M8 6h13"></path>
                                <path d="M8 12h13"></path>
                                <path d="M8 18h13"></path>
                                <path d="M3 6h.01"></path>
                                <path d="M3 12h.01"></path>
                                <path d="M3 18h.01"></path>
                            </svg>

                        </div>

                        <h3 class="break-words text-xl font-bold text-[#30221b]">
                            Aucune demande en attente
                        </h3>

                        <p class="mx-auto mt-2 max-w-md break-words text-sm leading-6 text-[#75665d]">
                            Toutes les demandes de renouvellement ont été traitées.
                            Les nouvelles demandes apparaîtront ici.
                        </p>

                    </div>

                <?php else: ?>

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

                        $motifId = 'motif-' . $demandeId;

                        ?>

                        <!-- CARTE RENOUVELLEMENT -->
                        <article class="flex min-w-0 h-full flex-col overflow-hidden rounded-2xl border border-[#e9dfd4] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                            <!-- EN-TÊTE -->
                            <div class="min-w-0 bg-[#30221b] px-4 py-4">

                                <div class="mb-3 flex min-w-0 items-center justify-between gap-2">

                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-amber-400/15 px-2.5 py-1 text-[10px] font-bold text-[#ffd66e]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#f7b500]"></span>
                                        En attente
                                    </span>

                                    <span class="min-w-0 break-all text-right text-[10px] text-white/60">
                                        #<?= $e($demandeId) ?>
                                    </span>

                                </div>

                                <h3 class="min-w-0 break-words text-base font-bold leading-5 text-white">
                                    <?= $e($demande['titre_bien'] ?? 'Annonce sans titre') ?>
                                </h3>

                                <p class="mt-2 break-words text-[11px] text-white/60">
                                    Demande du <?= $e($formatDate($demande['date_demande'] ?? null)) ?>
                                </p>

                            </div>

                            <!-- CONTENU -->
                            <div class="flex min-w-0 flex-1 flex-col p-4">

                                <!-- PROPRIÉTAIRE -->
                                <div class="mb-4 flex min-w-0 items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#30221b] text-sm font-bold text-[#f7b500]">
                                        <?= $e(mb_strtoupper(mb_substr($nomComplet, 0, 1, 'UTF-8'), 'UTF-8')) ?>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] font-semibold uppercase tracking-wide text-[#a98260]">
                                            Propriétaire
                                        </p>

                                        <p class="break-words text-sm font-bold text-[#30221b]">
                                            <?= $e($nomComplet) ?>
                                        </p>

                                        <p class="break-all text-xs text-[#75665d]">
                                            <?= $e($demande['email_proprietaire'] ?? 'E-mail non renseigné') ?>
                                        </p>
                                    </div>

                                </div>

                                <!-- INFORMATIONS -->
                                <div class="mb-4 min-w-0 space-y-3 rounded-xl bg-[#faf7f2] p-3">

                                    <div class="flex min-w-0 items-start justify-between gap-3">
                                        <span class="shrink-0 text-xs text-[#8b7b70]">
                                            Statut actuel
                                        </span>

                                        <span class="min-w-0 break-words text-right text-xs font-bold text-[#30221b]">
                                            <?= $e($demande['statut_bien'] ?? 'Non renseigné') ?>
                                        </span>
                                    </div>

                                    <div class="flex min-w-0 items-start justify-between gap-3">
                                        <span class="shrink-0 text-xs text-[#8b7b70]">
                                            Expiration
                                        </span>

                                        <span class="min-w-0 break-words text-right text-xs font-bold text-[#30221b]">
                                            <?= $e($formatDate($demande['expire_at'] ?? null)) ?>
                                        </span>
                                    </div>

                                    <div class="flex min-w-0 items-start justify-between gap-3">
                                        <span class="shrink-0 text-xs text-[#8b7b70]">
                                            Prolongation
                                        </span>

                                        <span class="shrink-0 text-xs font-bold text-[#8b684d]">
                                            30 jours
                                        </span>
                                    </div>

                                </div>

                                <!-- VOIR LE BIEN -->
                                <a
                                    href="/immo-sn/biens/<?= rawurlencode($idBien) ?>"
                                    class="mb-4 flex w-full items-center justify-center gap-2 rounded-lg border border-[#e5d8ca] bg-white px-3 py-2.5 text-xs font-bold text-[#5a402d] transition hover:bg-[#f5eee5]"
                                >
                                    Consulter le bien
                                    <span aria-hidden="true">&rarr;</span>
                                </a>

                                <!-- ACTIONS -->
                                <div class="mt-auto min-w-0 space-y-3">

                                    <!-- APPROUVER -->
                                    <form
                                        method="POST"
                                        action="/immo-sn/moderateur/renouvellements/<?= rawurlencode($demandeId) ?>/approuver"
                                        onsubmit="return confirm('Confirmer le renouvellement de cette annonce pour 30 jours ?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="_csrf_token"
                                            value="<?= $e(Session::csrfToken()) ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-700 px-3 py-2.5 text-xs font-bold text-white transition hover:bg-green-800"
                                        >
                                            <span aria-hidden="true">&#10003;</span>
                                            Approuver
                                        </button>

                                    </form>

                                    <!-- REFUSER -->
                                    <form
                                        method="POST"
                                        action="/immo-sn/moderateur/renouvellements/<?= rawurlencode($demandeId) ?>/refuser"
                                        onsubmit="return confirm('Confirmer le refus de cette demande ?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="_csrf_token"
                                            value="<?= $e(Session::csrfToken()) ?>"
                                        >

                                        <label
                                            for="<?= $e($motifId) ?>"
                                            class="mb-1.5 block break-words text-xs font-semibold text-[#30221b]"
                                        >
                                            Motif du refus
                                        </label>

                                        <textarea
                                            id="<?= $e($motifId) ?>"
                                            name="motif_refus"
                                            rows="2"
                                            maxlength="2000"
                                            required
                                            placeholder="Expliquez le motif du refus..."
                                            class="w-full min-w-0 resize-y rounded-lg border border-[#ded5cb] bg-[#fdfcfb] px-3 py-2 text-xs text-[#30221b] outline-none transition placeholder:text-[#b4a69a] focus:border-red-400 focus:ring-2 focus:ring-red-100"
                                        ></textarea>

                                        <button
                                            type="submit"
                                            class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-xs font-bold text-red-700 transition hover:bg-red-100"
                                        >
                                            <span aria-hidden="true">&times;</span>
                                            Refuser
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </section>

        <!-- PIED DE PAGE -->
        <div class="mt-12 border-t border-[#e8ded3] py-6 text-center">

            <p class="break-words text-xs text-[#a98260]">
                IMMO-SN &mdash; Espace de modération
            </p>

        </div>

    </div>

</div>
