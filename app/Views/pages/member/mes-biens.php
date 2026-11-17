<?php
/**
 * Vue : Mes biens
 * Emplacement :
 * app/Views/pages/member/mes-biens.php
 */
$biens = is_array($biens ?? null) ? $biens : [];
?>

<div class="min-h-screen bg-[#faf7f2]">

    <!-- =========================================================
         HEADER
    ========================================================== -->
    <header class="sticky top-0 z-20 border-b border-[#eadfd5] bg-[#fffdf9]/95 backdrop-blur">

       <div class="flex min-h-[76px] flex-col items-start justify-center gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 md:px-8">

       
            <div class="min-w-0">

                <div class="mb-1 flex items-center gap-2 text-xs text-[#a98260]">

                    <span>
                        Espace membre
                    </span>

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3.5 w-3.5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m9 18 6-6-6-6"
                        />
                    </svg>

                    <span class="text-[#8f8176]">
                        Mes biens
                    </span>

                </div>

               <h1 class="break-words text-xl font-bold tracking-tight text-[#30221b] sm:text-2xl md:text-3xl">

                 Mes biens
                </h1>

                <p class="mt-1 break-words text-sm text-[#8f8176]">
                    Gérez vos annonces immobilières depuis votre espace membre.
                </p>

            </div>

<!-- Actions de l'espace membre -->
<div class="flex w-full flex-col items-stretch gap-2 sm:w-auto sm:flex-row sm:flex-wrap sm:items-center sm:justify-end">


    <?php if (!empty($biens)): ?>
        <!-- Télécharger tous les QR codes -->
        <a
            href="/immo-sn/member/qr-codes/zip"
          class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#d8c4ae] bg-white px-3 py-3 text-sm font-bold text-[#5a402d] shadow-sm transition hover:border-[#a98260] hover:bg-[#faf7f2] sm:w-auto sm:px-4"
           title="Télécharger tous mes QR codes"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4 16.5v2A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5v-2M12 3v12m0 0 4-4m-4 4-4-4"
                />
            </svg>

            <span class="hidden sm:inline">
                Télécharger mes QR codes
            </span>

            <span class="sm:hidden">
                QR codes ZIP
            </span>
        </a>
    <?php endif; ?>

    <!-- Publier un bien -->
    <a
        href="/immo-sn/member/publier"
   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#46342b] sm:w-auto sm:px-5"
   >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 4v16m8-8H4"
            />
        </svg>

        <span class="hidden sm:inline">
            Publier un bien
        </span>

        <span class="sm:hidden">
            Publier
        </span>
    </a>

</div>
        </div>

    </header>


    <!-- =========================================================
         CONTENU
    ========================================================== -->
   <main class="px-4 py-6 sm:px-5 md:px-8 md:py-8">
 
<?php if (!empty($success)): ?>
    <div class="mx-auto mb-6 max-w-7xl">
        <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7"
                />
            </svg>

            <div class="min-w-0">
                <p class="font-semibold">
                    Opération réussie
                </p>

                <p class="mt-1 break-words text-sm">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="mx-auto mb-6 max-w-7xl">
        <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v4m0 4h.01M10.3 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.86a2 2 0 0 0-3.4 0Z"
                />
            </svg>

           <div class="min-w-0">
                <p class="font-semibold">
                    Une erreur est survenue
                </p>

               <p class="mt-1 break-words text-sm">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>


        <div class="mx-auto max-w-7xl">


            <!-- =================================================
                 STATISTIQUES
            ================================================== -->
          <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <!-- TOTAL -->
                <div
                    class="group rounded-2xl border border-[#eadfd5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-md"
                >

                    <div class="flex items-center justify-between gap-4">

                        <div class="min-w-0">

                            <p class="text-xs font-semibold uppercase tracking-wider text-[#9b8d82]">
                                Total des biens
                            </p>

                            <h2 class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                                <?= count($biens ?? []) ?>
                            </h2>

                            <p class="mt-1 text-xs text-[#a98260]">
                                Annonces enregistrées
                            </p>

                        </div>


                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#f4eadf] transition group-hover:bg-[#eee0d2]"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-[#8d6548]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 12l9-8 9 8M5 10v9h14v-9M9 19v-5h6v5"
                                />
                            </svg>

                        </div>

                    </div>

                </div>


                <!-- VALIDÉS -->
                <div
                    class="group rounded-2xl border border-[#eadfd5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-md"
                >

                    <?php
                    $valides = 0;

                    foreach ($biens ?? [] as $bien) {

                        if (strtolower(str_replace(['-', ' '], '_', trim((string) ($bien['statut'] ?? '')))) === 'actif') {
                            $valides++;
                        }

                    }
                    ?>

                    <div class="flex items-center justify-between gap-4">

                        <div class="min-w-0">

                            <p class="text-xs font-semibold uppercase tracking-wider text-[#9b8d82]">
                                Biens validés
                            </p>

                            <h2 class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                                <?= $valides ?>
                            </h2>

                            <p class="mt-1 text-xs text-[#5f8069]">
                                Annonces actuellement actives
                            </p>

                        </div>


                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#edf5ef]"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-[#497657]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                        </div>

                    </div>

                </div>


<!-- BIENS REJETÉS -->
<div
    class="group rounded-2xl border border-[#eadfd5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-md"
>

    <?php
    $rejetes = 0;

    foreach ($biens as $bien) {
        $statutBien = strtolower(
            str_replace(
                ['-', ' '],
                '_',
                trim((string) ($bien['statut'] ?? ''))
            )
        );

        if (in_array($statutBien, ['rejete', 'rejeté'], true)) {
            $rejetes++;
        }
    }
    ?>

    <div class="flex items-center justify-between gap-4">

        <div class="min-w-0">

            <p class="text-xs font-semibold uppercase tracking-wider text-[#9b8d82]">
                Biens rejetés
            </p>

            <h2 class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                <?= $rejetes ?>
            </h2>

            <p class="mt-1 text-xs text-red-600">
                Annonces refusées lors de la validation
            </p>

        </div>

        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-50">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-red-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.7"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18 18 6M6 6l12 12"
                />
            </svg>

        </div>

    </div>

</div>

                <!-- EN ATTENTE -->
                <div
                    class="group rounded-2xl border border-[#eadfd5] bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-md"
                >

                    <?php
                    $attente = 0;

                    foreach ($biens ?? [] as $bien) {

                        if (in_array(($bien['statut'] ?? ''), ['en-attente', 'en_attente', 'en attente'], true)) {
                            $attente++;
                        }

                    }
                    ?>

                    <div class="flex items-center justify-between gap-4">

                        <div class="min-w-0">

                            <p class="text-xs font-semibold uppercase tracking-wider text-[#9b8d82]">
                                En attente
                            </p>

                            <h2 class="mt-2 text-3xl font-bold tracking-tight text-[#30221b]">
                                <?= $attente ?>
                            </h2>

                            <p class="mt-1 text-xs text-[#a98260]">
                                Annonces en cours de validation
                            </p>

                        </div>


                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#fff4d6]"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-[#c28b22]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 SECTION MES ANNONCES
            ================================================== -->
            <section
                class="overflow-hidden rounded-2xl border border-[#eadfd5] bg-white shadow-sm"
            >


                <!-- En-tête -->
                <div
                    class="flex flex-col gap-4 border-b border-[#eee5dc] px-5 py-5 sm:flex-row sm:items-center sm:justify-between md:px-6"
                >

                    <div class="min-w-0">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4eadf]"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-[#8d6548]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 12l9-8 9 8M5 10v9h14v-9M9 19v-5h6v5"
                                    />

                                </svg>

                            </div>

                            <div class="min-w-0">

                               <h2 class="break-words text-lg font-bold text-[#30221b]">

                                Mes annonces
                                </h2>

                                <p class="mt-1 break-words text-xs text-[#8f8176]">

                               Consultez et gérez vos biens immobiliers.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div
                        class="inline-flex w-fit items-center rounded-full bg-[#f7f1eb] px-3 py-1.5 text-xs font-semibold text-[#725238]"
                    >

                        <?= count($biens ?? []) ?>

                        <span class="ml-1">
                            bien(s)
                        </span>

                    </div>

                </div>


                <?php if (empty($biens)): ?>


                    <!-- =================================================
                         ÉTAT VIDE
                    ================================================== -->
                    <div class="px-6 py-20 text-center">


                        <div
                            class="relative mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-[#f4eadf]"
                        >

                            <div
                                class="absolute inset-2 rounded-2xl border border-[#dfcdbb]"
                            ></div>

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="relative h-10 w-10 text-[#a98260]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"
                                />
                            </svg>

                        </div>


                        <h3 class="mt-7 break-words text-xl font-bold text-[#30221b] sm:text-2xl">

                          Vous n'avez pas encore de bien
                        </h3>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[#8f8176]">
                            Commencez à publier votre premier bien immobilier
                            pour le proposer aux visiteurs de ImmoSn.
                        </p>


                        <a
                            href="/immo-sn/member/publier"
                       class="mt-7 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-6 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#46342b] sm:w-auto"
                       >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>

                            Publier mon premier bien

                        </a>

                    </div>


                <?php else: ?>


                    
<!-- =====================================================
     AFFICHAGE DES BIENS EN CARTES
====================================================== -->

<div class="p-5 md:p-6">

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

        <?php foreach ($biens as $bien): ?>

            <?php
            $statut = strtolower(str_replace(['-', ' '], '_', trim((string) ($bien['statut'] ?? ''))));

            if ($statut === 'actif') {
                $statutLabel = 'Validé';
                $statutClass = 'bg-green-50 text-green-700 ring-green-200';
                $statutDot = 'bg-green-500';
            } elseif (in_array($statut, ['en_attente', 'en-attente', 'en attente'], true)) {
                $statutLabel = 'En attente';
                $statutClass = 'bg-amber-50 text-amber-700 ring-amber-200';
                $statutDot = 'bg-amber-500';
            } elseif ($statut === 'rejete' || $statut === 'rejeté') {
                $statutLabel = 'Rejeté';
                $statutClass = 'bg-red-50 text-red-700 ring-red-200';
                $statutDot = 'bg-red-500';
            } else {
                $statutLabel = ucfirst(
                    str_replace(['_', '-'], ' ', $statut ?: 'Inconnu')
                );
                $statutClass = 'bg-stone-100 text-stone-600 ring-stone-200';
                $statutDot = 'bg-stone-400';
            }

            $titre = $bien['titre'] ?? 'Bien sans titre';
            $categorie = $bien['categorie_nom'] ?? 'Bien immobilier';
            $ville = $bien['ville_nom'] ?? 'Localisation non renseignée';
            $quartier = $bien['quartier_nom'] ?? '';
            $photo = $bien['photo'] ?? '';
           
$idBien = $bien['id'] ?? '';

// Récupérer la dernière demande de renouvellement du bien.
$demandeRenouvellement = $renouvellements[$idBien] ?? null;

// Statut de la dernière demande.
$statutRenouvellement = $demandeRenouvellement['statut'] ?? null;

// Vérifier si une demande est déjà en attente.
$renouvellementEnAttente = $statutRenouvellement === 'en_attente';

// Récupérer le motif d'un éventuel refus.
$motifRefus = $demandeRenouvellement['motif_refus'] ?? null;

// Vérifier si l'annonce est expirée.
$dateExpiration = $bien['expire_at'] ?? null;

$bienExpire = (
    $statut === 'expire'
    || (
        !empty($dateExpiration)
        && strtotime((string) $dateExpiration) <= time()
    )
);

            ?>

            <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-[#eadfd5] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                <!-- PHOTO DU BIEN -->
                <div class="relative h-52 overflow-hidden bg-[#f4eadf]">

                    <?php if (!empty($photo)): ?>

                        <img
                            src="<?= htmlspecialchars($photo, ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            loading="lazy"
                        >

                    <?php else: ?>

                        <div class="flex h-full w-full flex-col items-center justify-center text-[#a98260]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mb-2 h-14 w-14"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.3"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"
                                />
                            </svg>

                            <span class="text-xs font-medium">
                                Aucune photo disponible
                            </span>

                        </div>

                    <?php endif; ?>

                    <!-- STATUT SUR LA PHOTO -->
                    <div class="absolute left-3 top-3">

                        <span class="inline-flex items-center gap-2 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold shadow-sm ring-1 <?= $statutClass ?>">

                            <span class="h-2 w-2 rounded-full <?= $statutDot ?>"></span>

                            <?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8') ?>

                        </span>

                    </div>

                </div>

                <!-- INFORMATIONS DU BIEN -->
                <div class="flex min-w-0 flex-1 flex-col p-5">
                    <p class="mb-2 break-words text-xs font-semibold uppercase tracking-wider text-[#a98260]">

                      <?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <h3 class="min-w-0 break-words line-clamp-2 text-lg font-bold leading-snug text-[#30221b]">

                       <?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>
                    </h3>

                    <!-- LOCALISATION -->
                    <div class="mt-3 flex items-start gap-2 text-sm text-[#8f8176]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mt-0.5 h-4 w-4 shrink-0 text-[#a98260]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"
                            />
                        </svg>

                        <div class="min-w-0">
    <p class="break-words font-medium text-[#463226]">

     <?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <?php if (!empty($quartier)): ?>
                                <p class="mt-0.5 break-words text-xs text-[#9b8d82]">

                                  <?= htmlspecialchars($quartier, ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>
                        </div>

                    </div>

                    <!-- PRIX -->
                    <div class="mt-5 border-t border-[#eee5dc] pt-4">

                        <?php if (isset($bien['prix']) && $bien['prix'] !== ''): ?>

                            <p class="text-xl font-extrabold text-[#30221b]">
                                <?= number_format(
                                    (float) $bien['prix'],
                                    0,
                                    ',',
                                    ' '
                                ) ?>
                                <span class="text-xs font-bold text-[#a98260]">
                                    FCFA
                                </span>
                            </p>

                        <?php else: ?>

                            <p class="text-sm text-[#9b8d82]">
                                Prix non renseigné
                            </p>

                        <?php endif; ?>

                    </div>

                    <!-- ACTION -->
                    <div class="mt-auto pt-5">

<?php if ($bienExpire): ?>

    <?php if ($renouvellementEnAttente): ?>

        <div class="mb-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
            <p class="font-bold">
                Demande de renouvellement en attente
            </p>
            <p class="mt-1">
                Votre demande est en cours d'examen par notre équipe.
            </p>
        </div>

    <?php else: ?>

        <?php if ($statutRenouvellement === 'refusee'): ?>

            <div class="mb-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                <p class="font-bold">
                    Votre précédente demande a été refusée.
                </p>

                <?php if (!empty($motifRefus)): ?>
                    <p class="mt-1">
                        Motif :
                        <?= htmlspecialchars($motifRefus, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>
            </div>

        <?php endif; ?>

        <form
            method="POST"
            action="/immo-sn/member/biens/renouveler/<?= urlencode((string) $idBien) ?>"
            class="mb-3"
            onsubmit="return confirm('Confirmez-vous votre demande de renouvellement pour cette annonce ?');"
        >
            <input
                type="hidden"
                name="_csrf_token"
                value="<?= htmlspecialchars(\Core\Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>"
            >

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#a98260] bg-[#faf7f2] px-4 py-3 text-sm font-bold text-[#6f4e37] transition hover:bg-[#f0e5d8]"
            >
                Demander le renouvellement
            </button>
        </form>

    <?php endif; ?>

<?php endif; ?>

<a
    href="/immo-sn/member/biens/modifier/<?= urlencode((string) $idBien) ?>"
    class="mb-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#a98260] bg-[#faf7f2] px-4 py-3 text-sm font-bold text-[#6f4e37] transition hover:bg-[#f0e5d8]"
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-4 w-4"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="1.8"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M16.862 4.487l2.651 2.651M8 16l8.5-8.5a2.121 2.121 0 013 3L11 19H8v-3z"
        />
    </svg>

    Modifier mon annonce
</a>

                        <a
                            href="/immo-sn/biens/<?= urlencode((string) $idBien) ?>"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#30221b] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#46342b]"
                        >
                            Voir le bien

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 transition group-hover:translate-x-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 12h14m-6-6 6 6-6 6"
                                />
                            </svg>
                        </a>

                    </div>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

</div>

                <?php endif; ?>

            </section>
             <!-- =================================================
                     ACCÈS RAPIDES
                ================================================== -->
                <section class="mt-8">

                    <div class="mb-4">

                        <h2 class="text-lg font-bold text-[#30221b]">
                            Accès rapides
                        </h2>

                        <p class="mt-1 text-xs text-[#8f8176]">
                            Retrouvez rapidement les principales fonctionnalités de votre espace membre.
                        </p>

                    </div>


                    <div class="grid gap-4 md:grid-cols-3">
 

                        <!-- Publier un bien -->
                        <a
                             href="/immo-sn/member/publier"
                            class="group rounded-2xl border border-[#eadfd5] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                        >

                            <div class="flex items-start justify-between gap-4">

    <div class="min-w-0">

                                    <div
                                        class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#f4eadf] text-[#8d6548]"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 4v16m8-8H4"
                                            />
                                        </svg>

                                    </div>

                                    <h3 class="break-words font-bold text-[#30221b]">

                                      Publier un bien
                                    </h3>

                                    <p class="mt-1 break-words text-xs leading-5 text-[#8f8176]">

                                     Ajoutez une nouvelle annonce à votre espace.
                                    </p>

                                </div>

                                 <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mt-1 h-5 w-5 text-[#d6c6b9] transition group-hover:translate-x-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m9 18 6-6-6-6"
                                    />
                                </svg>

                            </div>

                        </a>


                        <!-- Mes biens -->
                        <a
                            href="/immo-sn/member/mes-biens"
                            class="group rounded-2xl border border-[#eadfd5] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                        >

                           <div class="flex items-start justify-between gap-4">

    <div class="min-w-0">

                                    <div
                                        class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#f4eadf] text-[#8d6548]"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10Z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 21v-7h6v7"
                                            />
                                        </svg>

                                    </div>

                                    <h3 class="break-words font-bold text-[#30221b]">

                                       Mes biens
                                    </h3>

                                    <p class="mt-1 break-words text-xs leading-5 text-[#8f8176]">

                                     Gérez vos annonces et consultez leur statut.
                                    </p>

                                </div>

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mt-1 h-5 w-5 text-[#b6a69a] transition group-hover:translate-x-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m9 18 6-6-6-6"
                                    />
                                </svg>

                            </div>

                        </a>


                        <!-- Alertes -->
                        <a
                            href="/immo-sn/member/alertes"
                            class="group rounded-2xl border border-[#eadfd5] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                        >

                            <div class="flex items-start justify-between gap-4">

    <div class="min-w-0">

                                    <div
                                        class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#fff4d6] text-[#c28b22]"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 0 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M10 21h4"
                                            />

                                        </svg>

                                    </div>

                                   <h3 class="break-words font-bold text-[#30221b]">

                                     Mes alertes
                                    </h3>

                                    <p class="mt-1 break-words text-xs leading-5 text-[#8f8176]">

                                     Recevez des annonces correspondant à vos critères.
                                    </p>

                                </div>

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mt-1 h-5 w-5 text-[#b6a69a] transition group-hover:translate-x-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m9 18 6-6-6-6"
                                    />
                                </svg>

                            </div>

                        </a>

                    </div>

                </section>


        </div>

    </main>
    </div>
    
