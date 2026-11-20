
<?php
/**
 * =========================================================
 * PAGE : MES FAVORIS — ESPACE MEMBRE IMMO-SN
 * =========================================================
 *
 * Variable attendue :
 * $favoris
 *
 * Chaque favori peut contenir :
 * id, titre, prix, photo, categorie_nom,
 * ville_nom, quartier_nom
 */

// Sécurisation des données affichées
$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

// Vérification des données
$favoris = is_array($favoris ?? null) ? $favoris : [];
$totalFavoris = count($favoris);
?>

<div class="min-h-screen bg-[#F5F3ED]">

    <!-- =====================================================
         HEADER
    ====================================================== -->
    <header class="sticky top-0 z-30 border-b border-[#E5E7E0] bg-white/95 backdrop-blur-xl">

        <div class="flex min-h-[82px] items-center justify-between gap-4 px-5 py-4 md:px-8">

            <div class="min-w-0">

                <div class="mb-1 flex items-center gap-2 text-xs font-medium text-[#71869A]">

                    <span>Espace membre</span>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-3.5 w-3.5 shrink-0"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m9 18 6-6-6-6"/>
                    </svg>

                    <span class="truncate text-[#7E9833]">
                        Mes favoris
                    </span>

                </div>

                <h1 class="text-2xl font-bold tracking-tight text-[#101D33] md:text-3xl">
                    Mes favoris
                </h1>

                <p class="mt-1 text-sm text-[#71869A]">
                    Retrouvez les biens que vous souhaitez garder à portée de main.
                </p>

            </div>

            <!-- Compteur -->
            <div class="hidden shrink-0 items-center gap-3 rounded-2xl border border-[#E5E7E0] bg-[#FAFBF7] px-4 py-3 sm:flex">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EDF2DF] text-[#7E9833]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/>
                    </svg>

                </div>

                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-[#71869A]">
                        Biens enregistrés
                    </p>

                    <p class="mt-0.5 text-lg font-bold text-[#101D33]">
                        <?= $totalFavoris ?>
                    </p>
                </div>

            </div>

        </div>

    </header>


    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->
    <main class="px-5 py-6 md:px-8 md:py-8">

        <div class="mx-auto max-w-7xl">

            <?php if (!empty($favoris)): ?>

                <!-- =================================================
                     INTRODUCTION
                ================================================== -->
                <section class="mb-6 flex flex-col gap-4 rounded-2xl border border-[#E5E7E0] bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-4">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#EDF2DF] text-[#7E9833]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.7">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/>
                            </svg>

                        </div>

                        <div class="min-w-0">

                            <h2 class="text-sm font-bold text-[#101D33]">
                                Vos biens favoris
                            </h2>

                            <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                Comparez vos annonces préférées et consultez rapidement leurs détails.
                            </p>

                        </div>

                    </div>

                    <a href="/immo-sn/biens"
                       class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-[#7E9833] px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#6B812B] sm:w-auto">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 4v16m8-8H4"/>
                        </svg>

                        Découvrir d'autres biens

                    </a>

                </section>


                <!-- =================================================
                     STATISTIQUES
                ================================================== -->
                <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">

                    <!-- Total favoris -->
                    <div class="rounded-2xl border border-[#E5E7E0] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                        <div class="flex items-center justify-between gap-4">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-[#71869A]">
                                    Total favoris
                                </p>

                                <p class="mt-2 text-3xl font-bold text-[#101D33]">
                                    <?= $totalFavoris ?>
                                </p>

                                <p class="mt-1 text-xs text-[#71869A]">
                                    Biens enregistrés
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#EDF2DF] text-[#7E9833]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/>
                                </svg>

                            </div>

                        </div>

                    </div>


                    <!-- À consulter -->
                    <div class="rounded-2xl border border-[#E5E7E0] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                        <div class="flex items-center justify-between gap-4">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-[#71869A]">
                                    À consulter
                                </p>

                                <p class="mt-2 text-3xl font-bold text-[#101D33]">
                                    <?= $totalFavoris ?>
                                </p>

                                <p class="mt-1 text-xs text-[#71869A]">
                                    Annonces à découvrir
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#EAF0F5] text-[#71869A]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                                    <circle cx="12" cy="12" r="2.75"/>
                                </svg>

                            </div>

                        </div>

                    </div>


                    <!-- Recherche -->
                    <a href="/immo-sn/biens"
                       class="group rounded-2xl border border-[#E5E7E0] bg-[#101D33] p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">

                        <div class="flex items-center justify-between gap-4">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-[#C5D0DC]">
                                    Recherche
                                </p>

                                <p class="mt-2 text-base font-bold text-white">
                                    Trouver un nouveau bien
                                </p>

                                <p class="mt-1 text-xs text-[#C5D0DC]">
                                    Explorer les annonces
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#A8C66C] transition group-hover:bg-[#7E9833] group-hover:text-white">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M5 12h14m-7-7 7 7-7 7"/>
                                </svg>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- =================================================
                     TITRE DE LA LISTE
                ================================================== -->
                <div class="mb-5 flex items-center justify-between gap-4">

                    <div>
                        <h2 class="text-lg font-bold text-[#101D33]">
                            Vos annonces enregistrées
                        </h2>

                        <p class="mt-1 text-xs text-[#71869A]">
                            Les biens que vous avez ajoutés à vos favoris.
                        </p>
                    </div>

                    <span class="shrink-0 rounded-full bg-[#EDF2DF] px-3 py-1.5 text-xs font-bold text-[#627A27]">
                        <?= $totalFavoris ?> bien<?= $totalFavoris > 1 ? 's' : '' ?>
                    </span>

                </div>


                <!-- =================================================
                     GRILLE DES FAVORIS
                ================================================== -->
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">

                    <?php foreach ($favoris as $bien): ?>

                        <?php
                        $bienId = $bien['id'] ?? '';
                        $titre = $bien['titre'] ?? 'Bien immobilier';
                        $categorie = $bien['categorie_nom'] ?? 'Bien immobilier';
                        $ville = $bien['ville_nom'] ?? '';
                        $quartier = $bien['quartier_nom'] ?? '';
                        $photo = $bien['photo'] ?? '';
                        $prix = (float) ($bien['prix'] ?? 0);

                        $localisation = $quartier !== ''
                            ? $quartier
                            : $ville;
                        ?>

                        <article class="group overflow-hidden rounded-2xl border border-[#E5E7E0] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                            <!-- IMAGE -->
                            <div class="relative h-56 overflow-hidden bg-[#E9ECE5]">

                                <?php if (!empty($photo)): ?>

                                    <img src="<?= $e($photo) ?>"
                                         alt="<?= $e($titre) ?>"
                                         loading="lazy"
                                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105">

                                <?php else: ?>

                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#EDF2DF] to-[#E4E9DC]">

                                        <div class="text-center">

                                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white/80 text-[#7E9833]">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-7 w-7"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.4">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l2.409 2.409m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l3.409 3.409M3.75 19.5h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                                                </svg>

                                            </div>

                                            <p class="mt-2 text-[11px] font-medium text-[#71869A]">
                                                Aucune photo
                                            </p>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <!-- Dégradé inférieur -->
                                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#101D33]/60 to-transparent"></div>


                                <!-- Catégorie -->
                                <div class="absolute left-4 top-4 max-w-[calc(100%-5rem)]">

                                    <span class="inline-flex max-w-full truncate rounded-full bg-white/95 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide text-[#101D33] shadow-sm backdrop-blur">
                                        <?= $e($categorie) ?>
                                    </span>

                                </div>


                                <!-- Icône favori -->
                                <div title="Bien enregistré dans vos favoris"
                                     class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-[#101D33]/90 text-[#A8C66C] shadow-lg backdrop-blur">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="currentColor"
                                         viewBox="0 0 24 24">
                                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25A5.25 5.25 0 0 1 7.5 3c1.387 0 2.64.576 3.5 1.501A5.25 5.25 0 0 1 14.5 3a5.25 5.25 0 0 1 5.25 5.25c0 3.924-2.438 7.11-4.739 9.257a25.18 25.18 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.218l-.022.012-.007.003-.003.001a.752.752 0 0 1-.707 0l-.003-.001Z"/>
                                    </svg>

                                </div>


                                <!-- Localisation -->
                                <?php if (!empty($quartier) || !empty($ville)): ?>

                                    <div class="absolute bottom-4 left-4 flex max-w-[80%] items-center gap-1.5 text-white">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4 shrink-0"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                        </svg>

                                        <span class="truncate text-xs font-semibold">
                                            <?= $e($localisation) ?>
                                        </span>

                                    </div>

                                <?php endif; ?>

                            </div>


                            <!-- INFORMATIONS DU BIEN -->
                            <div class="p-5">

                                <!-- Titre et localisation -->
                                <div class="mb-4">

                                    <h3 class="truncate text-lg font-bold text-[#101D33]"
                                        title="<?= $e($titre) ?>">
                                        <?= $e($titre) ?>
                                    </h3>

                                    <p class="mt-1 truncate text-xs text-[#71869A]">

                                        <?php if ($categorie !== ''): ?>
                                            <?= $e($categorie) ?>
                                        <?php endif; ?>

                                        <?php if ($categorie !== '' && ($ville !== '' || $quartier !== '')): ?>
                                            <span class="mx-1 text-[#B7C1CA]">•</span>
                                        <?php endif; ?>

                                        <?= $e($localisation) ?>

                                    </p>

                                </div>


                                <!-- Séparateur -->
                                <div class="mb-4 h-px bg-[#E9ECE5]"></div>


                                <!-- PRIX -->
                                <div class="mb-4">

                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-[#71869A]">
                                        Prix
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-[#101D33]">
                                        <?= number_format($prix, 0, ',', ' ') ?>

                                        <span class="text-xs font-bold text-[#7E9833]">
                                            FCFA
                                        </span>
                                    </p>

                                </div>


                                <!-- BOUTONS D'ACTION -->
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">

                                    <!-- Voir le bien -->
                                    <a href="/immo-sn/biens/<?= rawurlencode((string) $bienId) ?>"
                                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#101D33] px-3 py-3 text-xs font-bold text-white transition hover:bg-[#1D3454]">

                                        Voir le bien

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4 shrink-0"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M5 12h14m-6-6 6 6-6 6"/>
                                        </svg>

                                    </a>


                                    <!-- Retirer des favoris -->
                                    <form action="/immo-sn/member/favoris/retirer"
                                          method="POST"
                                          onsubmit="return confirm('Voulez-vous vraiment retirer ce bien de vos favoris ?');">

                                        <input type="hidden"
                                               name="bien_id"
                                               value="<?= $e($bienId) ?>">

                                        <button type="submit"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#E5E7E0] bg-white px-3 py-3 text-xs font-bold text-[#71869A] transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4 shrink-0"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M6 7h12m-10 0 .7 12h6.6L16 7M9 7V4h6v3"/>
                                            </svg>

                                            Retirer

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>


                <!-- =================================================
                     ACCÈS RAPIDES
                ================================================== -->
                <section class="mt-10">

                    <div class="mb-5">

                        <h2 class="text-lg font-bold text-[#101D33]">
                            Accès rapides
                        </h2>

                        <p class="mt-1 text-xs text-[#71869A]">
                            Retrouvez rapidement les principales fonctionnalités de votre espace membre.
                        </p>

                    </div>


                    <div class="grid gap-4 md:grid-cols-3">

                        <!-- Publier un bien -->
                        <a href="/immo-sn/member/publier"
                           class="group rounded-2xl border border-[#E5E7E0] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#EDF2DF] text-[#7E9833]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M12 4v16m8-8H4"/>
                                        </svg>

                                    </div>

                                    <h3 class="font-bold text-[#101D33]">
                                        Publier un bien
                                    </h3>

                                    <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                        Ajoutez une nouvelle annonce à votre espace.
                                    </p>

                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mt-1 h-5 w-5 text-[#AAB6C0] transition group-hover:translate-x-1 group-hover:text-[#7E9833]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="m9 18 6-6-6-6"/>
                                </svg>

                            </div>

                        </a>


                        <!-- Mes biens -->
                        <a href="/immo-sn/member/mes-biens"
                           class="group rounded-2xl border border-[#E5E7E0] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#EAF0F5] text-[#71869A]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10Z"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 21v-7h6v7"/>
                                        </svg>

                                    </div>

                                    <h3 class="font-bold text-[#101D33]">
                                        Mes biens
                                    </h3>

                                    <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                        Gérez vos annonces et consultez leur statut.
                                    </p>

                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mt-1 h-5 w-5 text-[#AAB6C0] transition group-hover:translate-x-1 group-hover:text-[#7E9833]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="m9 18 6-6-6-6"/>
                                </svg>

                            </div>

                        </a>


                        <!-- Alertes -->
                        <a href="/immo-sn/member/alertes"
                           class="group rounded-2xl border border-[#E5E7E0] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#EDF2DF] text-[#7E9833]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 0 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M10 21h4"/>
                                        </svg>

                                    </div>

                                    <h3 class="font-bold text-[#101D33]">
                                        Mes alertes
                                    </h3>

                                    <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                        Recevez des annonces correspondant à vos critères.
                                    </p>

                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mt-1 h-5 w-5 text-[#AAB6C0] transition group-hover:translate-x-1 group-hover:text-[#7E9833]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="m9 18 6-6-6-6"/>
                                </svg>

                            </div>

                        </a>

                    </div>

                </section>


            <?php else: ?>


                <!-- =================================================
                     AUCUN FAVORI
                ================================================== -->
                <section class="flex min-h-[500px] items-center justify-center rounded-3xl border border-[#E5E7E0] bg-white p-6 shadow-sm sm:p-10">

                    <div class="max-w-md text-center">

                        <!-- Illustration -->
                        <div class="relative mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-[#EDF2DF]">

                            <div class="absolute inset-2 rounded-2xl border border-[#D7E2BE]"></div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="relative h-10 w-10 text-[#7E9833]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.5">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/>
                            </svg>

                        </div>


                        <h2 class="mt-7 text-2xl font-bold text-[#101D33]">
                            Votre liste est vide
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-[#71869A]">
                            Vous n'avez pas encore enregistré de bien dans vos favoris.
                            Parcourez les annonces disponibles et ajoutez celles qui vous intéressent.
                        </p>


                        <a href="/immo-sn/biens"
                           class="mt-7 inline-flex items-center gap-2 rounded-xl bg-[#7E9833] px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#6B812B]">

                            Découvrir les biens

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M5 12h14"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m13 6 6 6-6 6"/>
                            </svg>

                        </a>

                    </div>

                </section>


                <!-- =================================================
                     ACCÈS RAPIDES — LISTE VIDE
                ================================================== -->
                <section class="mt-8">

                    <div class="mb-5">

                        <h2 class="text-lg font-bold text-[#101D33]">
                            Commencer sur Immo-SN
                        </h2>

                        <p class="mt-1 text-xs text-[#71869A]">
                            Explorez les annonces et utilisez les fonctionnalités de votre espace membre.
                        </p>

                    </div>


                    <div class="grid gap-4 md:grid-cols-3">


                        <!-- Mes biens -->
                        <a href="/immo-sn/member/mes-biens"
                           class="group rounded-2xl border border-[#E5E7E0] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#EAF0F5] text-[#71869A]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10Z"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 21v-7h6v7"/>
                                        </svg>

                                    </div>

                                    <h3 class="font-bold text-[#101D33]">
                                        Mes biens
                                    </h3>

                                    <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                        Gérez vos annonces et consultez leur statut.
                                    </p>

                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mt-1 h-5 w-5 text-[#AAB6C0] transition group-hover:translate-x-1 group-hover:text-[#7E9833]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="m9 18 6-6-6-6"/>
                                </svg>

                            </div>

                        </a>


                        <!-- Créer une alerte -->
                        <a href="/immo-sn/member/alertes"
                           class="group rounded-2xl border border-[#E5E7E0] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#EDF2DF] text-[#7E9833]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 0 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M10 21h4"/>
                                        </svg>

                                    </div>

                                    <h3 class="font-bold text-[#101D33]">
                                        Créer une alerte
                                    </h3>

                                    <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                        Soyez informé lorsque de nouveaux biens correspondent à vos critères.
                                    </p>

                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mt-1 h-5 w-5 text-[#AAB6C0] transition group-hover:translate-x-1 group-hover:text-[#7E9833]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="m9 18 6-6-6-6"/>
                                </svg>

                            </div>

                        </a>


                        <!-- Publier un bien -->
                        <a href="/immo-sn/member/publier"
                           class="group rounded-2xl bg-[#101D33] p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#7E9833] text-white">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M12 4v16m8-8H4"/>
                                        </svg>

                                    </div>

                                    <h3 class="font-bold text-white">
                                        Publier un bien
                                    </h3>

                                    <p class="mt-1 text-xs leading-5 text-[#C5D0DC]">
                                        Publiez votre propre annonce immobilière.
                                    </p>

                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mt-1 h-5 w-5 text-[#A8C66C] transition group-hover:translate-x-1"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="m9 18 6-6-6-6"/>
                                </svg>

                            </div>

                        </a>

                    </div>

                </section>

            <?php endif; ?>

        </div>

    </main>

</div>
