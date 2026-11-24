
<?php
/**
 * Vue : Liste des agences immobilières
 * Design : IMMO-SN — Urban Signature (Navy & Gold)
 */

$agences = is_array($agences ?? null) ? $agences : [];
$totalAgences = count($agences);

if (!function_exists('eAgence')) {
    function eAgence($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
?>

<div class="min-h-screen bg-[#F5F3ED] text-[#071B36]">

    <!-- HEADER -->
    <header class="sticky top-0 z-20 border-b border-[#E4E8ED] bg-white/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="mx-auto flex max-w-[1600px] flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-[#71869A]">
                        <span class="font-bold text-[#F5B800]">IMMO-SN</span>
                        <span>/</span>
                        <span>Réseau immobilier</span>
                        <span>/</span>
                        <span class="text-[#071B36]">Agences</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#071B36] sm:text-3xl">
                        Nos agences partenaires
                    </h1>

                    <p class="mt-1 text-sm text-[#71869A]">
                        Les professionnels qui donnent vie à vos projets immobiliers.
                    </p>
                </div>

                <a href="/"
                   class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0E2A4E]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9"/>
                    </svg>
                    Accueil
                </a>

            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

           <!-- HERO — VERSION COMPACTE -->
<section class="relative overflow-hidden rounded-[24px] bg-[#071B36] shadow-[0_16px_40px_rgba(7,27,54,0.14)]">

    <!-- DÉCORATION -->
    <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-[#F5B800]/15 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-28 left-1/3 h-64 w-64 rounded-full bg-[#53708F]/20 blur-3xl"></div>

    <div class="relative flex flex-col gap-5 px-5 py-5 sm:px-7 sm:py-6 lg:flex-row lg:items-center lg:justify-between lg:px-9">

        <!-- PRÉSENTATION -->
        <div class="max-w-3xl">

            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#F5B800]/20 bg-[#F5B800]/10 px-3 py-1">
                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>
                <span class="text-[11px] font-medium text-[#E6EDF5]">
                    Le réseau immobilier IMMO-SN
                </span>
            </div>

            <h2 class="font-['Playfair_Display'] text-2xl font-semibold leading-tight text-white sm:text-3xl lg:text-4xl">
                Des partenaires de confiance,
                <span class="text-[#F5B800]">près de vos projets.</span>
            </h2>

            <p class="mt-2.5 max-w-2xl text-sm leading-6 text-[#C3CEDB]">
                Découvrez notre sélection d'agences immobilières au Sénégal.
                Des professionnels à votre écoute pour vous accompagner
                dans l'achat, la vente et la location de vos biens.
            </p>

            <!-- ACTIONS -->
            <div class="mt-4 flex flex-wrap gap-2.5">

                <a href="/biens"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#F5B800] px-4 py-2.5 text-sm font-bold text-[#071B36] transition hover:bg-[#FFD033]">
                    Découvrir les biens
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14m-6-6 6 6-6 6"/>
                    </svg>
                </a>

                <a href="/"
                   class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">
                    Retour à l'accueil
                </a>

            </div>
        </div>

        <!-- STATISTIQUE -->
        <div class="shrink-0 rounded-2xl border border-white/10 bg-white/[0.06] p-4 backdrop-blur-md sm:min-w-[230px]">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-[11px] uppercase tracking-[0.16em] text-[#A9B9C9]">
                        Notre réseau
                    </p>

                    <p class="mt-1 text-3xl font-bold tabular-nums text-white">
                        <?= number_format($totalAgences, 0, ',', ' ') ?>
                    </p>

                    <p class="mt-1 text-xs text-[#C3CEDB]">
                        agence<?= $totalAgences > 1 ? 's' : '' ?>
                        référencée<?= $totalAgences > 1 ? 's' : '' ?>
                    </p>
                </div>

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-[#F5B800]/20 bg-[#F5B800]/10 text-[#F5B800]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5"/>
                        <path stroke-linecap="round" d="M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>
                    </svg>
                </div>

            </div>

            <div class="mt-3 h-1 overflow-hidden rounded-full bg-white/10">
                <div class="h-full w-full rounded-full bg-[#F5B800]"></div>
            </div>

            <p class="mt-2 text-right text-[10px] text-[#A9B9C9]">
                Le réseau des partenaires IMMO-SN
            </p>

        </div>

    </div>
</section>
            <!-- INTRODUCTION -->
            <section class="mt-8">

                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#B58A00]">
                            Trouvez votre partenaire
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-[#071B36]">
                            Toutes nos agences
                        </h2>

                        <p class="mt-1 text-sm text-[#71869A]">
                            Consultez les profils et choisissez votre agence immobilière.
                        </p>
                    </div>

                    <!-- COMPTEUR -->
                    <div class="inline-flex w-fit items-center gap-3 rounded-xl border border-[#E2E5DA] bg-white px-4 py-3 shadow-sm">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F5B800]/10 text-[#B58A00]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <circle cx="10.5" cy="10.5" r="6.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 5 5"/>
                            </svg>
                        </div>

                        <div>
                            <p id="agencyCount" class="text-lg font-bold text-[#071B36]">
                                <?= $totalAgences ?>
                            </p>
                            <p class="text-xs text-[#71869A]">
                                agence(s) affichée(s)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- RECHERCHE -->
                <div class="rounded-[22px] border border-[#E2E5DA] bg-white p-5 shadow-sm">

                    <label for="agencySearch" class="mb-3 block text-sm font-bold text-[#071B36]">
                        Rechercher une agence
                    </label>

                    <div class="flex flex-col gap-3 sm:flex-row">

                        <div class="relative flex-1">

                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#B58A00]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="10.5" cy="10.5" r="6.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 5 5"/>
                            </svg>

                            <input
                                type="search"
                                id="agencySearch"
                                placeholder="Nom de l'agence, adresse..."
                                autocomplete="off"
                                class="w-full rounded-xl border border-[#E2E5DA] bg-[#F5F3ED] py-3.5 pl-12 pr-4 text-sm text-[#071B36] outline-none transition placeholder:text-[#91A0AE] focus:border-[#F5B800] focus:ring-2 focus:ring-[#F5B800]/20"
                            >
                        </div>

                        <button
                            type="button"
                            id="clearAgencySearch"
                            class="hidden items-center justify-center gap-2 rounded-xl border border-[#E2E5DA] px-5 py-3 text-sm font-semibold text-[#071B36] transition hover:bg-[#F5F3ED] sm:inline-flex"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/>
                            </svg>
                            Effacer
                        </button>
                    </div>

                    <p class="mt-3 text-xs text-[#71869A]">
                        La liste se met à jour automatiquement pendant votre recherche.
                    </p>
                </div>
            </section>

            <!-- LISTE -->
            <section class="mt-8">

                <div class="mb-5">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#B58A00]">
                        Nos partenaires
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-[#071B36]">
                        Découvrez les agences
                    </h2>
                </div>

                <?php if (!empty($agences)): ?>

                    <div id="agencyGrid" class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                        <?php foreach ($agences as $agence): ?>

                            <?php
                            $nom = trim((string) ($agence['nom_agence'] ?? 'Agence immobilière'));
                            $adresse = trim((string) ($agence['adresse'] ?? 'Adresse non renseignée'));
                            $description = trim((string) ($agence['description'] ?? ''));
                            $logo = trim((string) ($agence['logo'] ?? ''));
                            $id = (string) ($agence['id'] ?? '');
                            $nombreBiens = (int) ($agence['nombre_biens'] ?? 0);

                            $estVerifiee = filter_var(
                                $agence['badge_verifie'] ?? false,
                                FILTER_VALIDATE_BOOLEAN
                            );

                            $logoUrl = $logo !== '' ? '/' . ltrim($logo, '/') : '';

                            $initiale = mb_strtoupper(
                                mb_substr($nom, 0, 1, 'UTF-8'),
                                'UTF-8'
                            );

                            $texteRecherche = mb_strtolower(
                                $nom . ' ' . $adresse . ' ' . $description,
                                'UTF-8'
                            );
                            ?>

                            <article
                                class="agency-card group flex h-full flex-col overflow-hidden rounded-[24px] border border-[#E2E5DA] bg-white shadow-[0_8px_25px_rgba(7,27,54,0.04)] transition duration-300 hover:-translate-y-1 hover:border-[#F5B800]/60 hover:shadow-[0_18px_40px_rgba(7,27,54,0.10)]"
                                data-name="<?= eAgence($texteRecherche) ?>"
                            >

                                <!-- BANDE ACCENT -->
                                <div class="h-1.5 bg-gradient-to-r from-[#071B36] via-[#F5B800] to-[#D8AA00]"></div>

                                <div class="flex flex-1 flex-col p-5 sm:p-6">

                                    <!-- IDENTITÉ -->
                                    <div class="flex items-start justify-between gap-3">

                                        <div class="flex min-w-0 items-center gap-3">

                                            <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#071B36] ring-1 ring-[#E2E5DA]">

                                                <?php if ($logoUrl !== ''): ?>

                                                    <img
                                                        src="<?= eAgence($logoUrl) ?>"
                                                        alt="Logo de <?= eAgence($nom) ?>"
                                                        class="h-full w-full object-contain p-2"
                                                        loading="lazy"
                                                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                                    >

                                                    <span class="hidden font-['Playfair_Display'] text-3xl font-bold text-[#F5B800]" aria-hidden="true">
                                                        <?= eAgence($initiale) ?>
                                                    </span>

                                                <?php else: ?>

                                                    <span class="font-['Playfair_Display'] text-3xl font-bold text-[#F5B800]" aria-hidden="true">
                                                        <?= eAgence($initiale) ?>
                                                    </span>

                                                <?php endif; ?>

                                            </div>

                                            <div class="min-w-0">
                                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#B58A00]">
                                                    Agence partenaire
                                                </p>

                                                <h3 class="mt-1 break-words text-base font-bold leading-6 text-[#071B36] transition group-hover:text-[#B58A00]">
                                                    <?= eAgence($nom) ?>
                                                </h3>
                                            </div>
                                        </div>

                                        <?php if ($estVerifiee): ?>

                                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-[#F5B800]/20 bg-[#FFF8DF] px-2.5 py-1.5 text-[10px] font-bold text-[#806000]">
                                                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#F5B800] text-[10px] text-[#071B36]">
                                                    ✓
                                                </span>
                                                Vérifiée
                                            </span>

                                        <?php endif; ?>
                                    </div>

                                    <!-- LOCALISATION -->
                                    <div class="mt-5 flex items-start gap-3 rounded-xl bg-[#F5F3ED] p-3.5">

                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#B58A00]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.5 7-12a7 7 0 10-14 0c0 6.5 7 12 7 12z"/>
                                            <circle cx="12" cy="9" r="2"/>
                                        </svg>

                                        <div class="min-w-0">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                                Localisation
                                            </p>

                                            <p class="mt-1 break-words text-xs leading-5 text-[#33445A]">
                                                <?= eAgence($adresse) ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- DESCRIPTION -->
                                    <div class="mt-5 flex-1">

                                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#B58A00]">
                                            À propos de l'agence
                                        </p>

                                        <?php if ($description !== ''): ?>

                                            <p class="mt-2 text-sm leading-6 text-[#46576B]">
                                                <?= eAgence(mb_strimwidth($description, 0, 150, '…', 'UTF-8')) ?>
                                            </p>

                                        <?php else: ?>

                                            <p class="mt-2 text-sm leading-6 text-[#71869A]">
                                                Découvrez cette agence et consultez les biens immobiliers qu'elle propose.
                                            </p>

                                        <?php endif; ?>
                                    </div>

                                    <!-- BIENS -->
                                    <div class="mt-5 flex items-center justify-between rounded-xl border border-[#E7E9E1] bg-white p-4">

                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#71869A]">
                                                Biens immobiliers
                                            </p>

                                            <p class="mt-1 text-2xl font-bold tabular-nums text-[#071B36]">
                                                <?= number_format($nombreBiens, 0, ',', ' ') ?>
                                            </p>
                                        </div>

                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-[#F5B800]/20 bg-[#F5B800]/10 text-[#B58A00]">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- ACTION -->
                                    <div class="mt-5 border-t border-[#E9ECE4] pt-5">

                                        <a
                                            href="/agences/<?= rawurlencode($id) ?>"
                                            class="group/link flex w-full items-center justify-between rounded-xl bg-[#071B36] px-5 py-3.5 text-sm font-bold text-white transition hover:bg-[#0E2A4E]"
                                            aria-label="Découvrir l'agence <?= eAgence($nom) ?>"
                                        >
                                            <span class="inline-flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#F5B800]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                Découvrir l'agence
                                            </span>

                                            <svg class="h-4 w-4 text-[#F5B800] transition group-hover/link:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M5 12h14m-6-6 6 6-6 6"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>

                        <?php endforeach; ?>
                    </div>

                    <!-- AUCUN RÉSULTAT DE RECHERCHE -->
                    <div
                        id="searchEmpty"
                        class="mt-6 hidden rounded-[24px] border border-dashed border-[#D6C78F] bg-white px-6 py-14 text-center"
                        role="status"
                    >

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#F5B800]/10 text-[#B58A00]">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <circle cx="10.5" cy="10.5" r="6.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 5 5"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#071B36]">
                            Aucune agence trouvée
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#71869A]">
                            Aucune agence ne correspond à votre recherche.
                            Essayez un autre nom ou une autre adresse.
                        </p>

                        <button
                            type="button"
                            id="resetAgencySearch"
                            class="mt-6 rounded-xl bg-[#071B36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0E2A4E]"
                        >
                            Afficher toutes les agences
                        </button>
                    </div>

                <?php else: ?>

                    <!-- AUCUNE AGENCE -->
                    <div class="rounded-[24px] border border-[#E2E5DA] bg-white px-6 py-16 text-center shadow-sm">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-[#F5B800]/20 bg-[#F5B800]/10 text-[#B58A00]">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-5h6v5"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-[#071B36]">
                            Aucune agence disponible
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#71869A]">
                            Notre réseau immobilier ne compte actuellement aucune agence référencée.
                            Revenez prochainement pour découvrir nos partenaires.
                        </p>

                        <a
                            href="/"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#071B36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0E2A4E]"
                        >
                            Retour à l'accueil
                        </a>
                    </div>

                <?php endif; ?>

            </section>

          <!-- FOOTER — BLEU MARINE -->
<footer class="mt-10 overflow-hidden rounded-t-[22px] bg-[#071B36] px-5 py-6 text-center shadow-[0_-8px_25px_rgba(7,27,54,0.06)]">

    <div class="mx-auto mb-3 h-1 w-12 rounded-full bg-[#F5B800]"></div>

    <p class="text-sm font-bold tracking-wide text-white">
        IMMO-SN
    </p>

    <p class="mt-1 text-xs text-[#C3CEDB]">
        Votre partenaire immobilier au Sénégal
    </p>

    <p class="mt-3 text-[10px] text-[#8FA3B9]">
        © <?= date('Y') ?> IMMO-SN — Tous droits réservés.
    </p>

</footer>

        </div>
    </main>
</div>

<!-- RECHERCHE DYNAMIQUE -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('agencySearch');
    const agencyCards = Array.from(document.querySelectorAll('.agency-card'));
    const agencyCount = document.getElementById('agencyCount');
    const searchEmpty = document.getElementById('searchEmpty');
    const clearButton = document.getElementById('clearAgencySearch');
    const resetButton = document.getElementById('resetAgencySearch');
    const agencyGrid = document.getElementById('agencyGrid');

    if (!searchInput) {
        return;
    }

    function normaliserTexte(texte) {
        return texte
            .toLocaleLowerCase('fr')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function filtrerAgences() {
        const recherche = normaliserTexte(searchInput.value);
        let nombreVisible = 0;

        agencyCards.forEach(function (carte) {
            const nom = normaliserTexte(carte.dataset.name || '');
            const correspond = nom.includes(recherche);

            carte.classList.toggle('hidden', !correspond);

            if (correspond) {
                nombreVisible++;
            }
        });

        if (agencyCount) {
            agencyCount.textContent = nombreVisible;
        }

        if (searchEmpty) {
            searchEmpty.classList.toggle(
                'hidden',
                nombreVisible !== 0 || agencyCards.length === 0
            );
        }

        if (agencyGrid) {
            agencyGrid.classList.toggle(
                'hidden',
                nombreVisible === 0 && agencyCards.length > 0
            );
        }

        if (clearButton) {
            clearButton.classList.toggle(
                'hidden',
                searchInput.value.length === 0
            );
        }
    }

    function reinitialiserRecherche() {
        searchInput.value = '';
        filtrerAgences();
        searchInput.focus();
    }

    searchInput.addEventListener('input', filtrerAgences);

    if (clearButton) {
        clearButton.addEventListener('click', reinitialiserRecherche);
    }

    if (resetButton) {
        resetButton.addEventListener('click', reinitialiserRecherche);
    }

    filtrerAgences();
});
</script>
