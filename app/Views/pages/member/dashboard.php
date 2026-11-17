<?php
/**
 * Dashboard membre ImmoSn
 *
 * Variables disponibles :
 * $stats
 * $user
 * $derniersBiens
 * $dernieresAlertes
 */


/*
 * =========================================================
 * UTILISATEUR CONNECTÉ
 * =========================================================
 */

$user = $user ?? [];

$prenom = trim($user['prenom'] ?? '');
$nom = trim($user['nom'] ?? '');

$nomComplet = trim($prenom . ' ' . $nom);

if ($nomComplet === '') {
    $nomComplet = 'Membre';
}

$prenomAffichage = $prenom !== ''
    ? $prenom
    : 'Membre';

$initiale = strtoupper(
    mb_substr($prenomAffichage, 0, 1)
);

if ($initiale === '') {
    $initiale = 'M';
}

$role = trim($user['role'] ?? '');

if ($role === '') {
    $role = 'Membre';
}


/*
 * =========================================================
 * STATISTIQUES
 * =========================================================
 */

$nombreBiens = (int) ($stats['biens'] ?? 0);

$nombreAlertes = (int) ($stats['alertes'] ?? 0);

$nombreVues = (int) ($stats['vues'] ?? 0);

$nombreFavoris = (int) ($stats['favoris'] ?? 0);

$nombreMessagesNonLus = (int) (
    $stats['messages_non_lus'] ?? 0
);


/*
 * =========================================================
 * DERNIERS BIENS
 * =========================================================
 */

$derniersBiens = is_array($derniersBiens ?? null)
    ? $derniersBiens
    : [];
    /*
 * =========================================================
 * ACTIVITÉ DES 30 DERNIERS JOURS
 * =========================================================
 */

$activite30Jours = is_array($activite30Jours ?? null)
    ? $activite30Jours
    : [];

$maxVues = 0;

foreach ($activite30Jours as $jour) {

    $total = (int) ($jour['total'] ?? 0);

    if ($total > $maxVues) {
        $maxVues = $total;
    }
}

/*
 * Évite une division par zéro
 * lorsqu'il n'y a encore aucune vue.
 */
if ($maxVues <= 0) {
    $maxVues = 1;
}


/*
 * =========================================================
 * DERNIÈRES ALERTES
 * =========================================================
 */

$dernieresAlertes = is_array($dernieresAlertes ?? null)
    ? $dernieresAlertes
    : [];

$derniereAlerte = $dernieresAlertes[0] ?? null;


/*
 * =========================================================
 * FONCTION DATE
 * =========================================================
 */

$formatDate = static function ($date): string {

    if (empty($date)) {
        return '';
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return '';
    }

    return date('d/m/Y', $timestamp);
};


/*
 * =========================================================
 * LIBELLÉ DU STATUT
 * =========================================================
 */

$libelleStatut = static function ($statut): string {

    $statut = strtolower(
        trim((string) $statut)
    );

    return match ($statut) {

        'actif',
        'active',
        'valide',
        'validé',
        'approved'
            => 'Validé',

        'en-attente',
        'en_attente',
        'attente',
        'pending'
            => 'En attente',

        'rejete',
        'rejeté',
        'rejected'
            => 'Rejeté',

        'expire',
        'expiré',
        'expired'
            => 'Expiré',

        'inactif',
        'inactive'
            => 'Inactif',

        default
            => ucfirst(
                str_replace(
                    ['-', '_'],
                    ' ',
                    $statut !== ''
                        ? $statut
                        : 'Inconnu'
                )
            ),
    };
};


/*
 * =========================================================
 * CLASSES DU STATUT
 * =========================================================
 */

$classesStatut = static function ($statut): array {

    $statut = strtolower(
        trim((string) $statut)
    );

    return match ($statut) {

        'actif',
        'active',
        'valide',
        'validé',
        'approved'
            => [
                'container' =>
                    'bg-[#edf5ef] text-[#497657]',
                'dot' =>
                    'bg-[#5caa70]',
            ],

        'en-attente',
        'en_attente',
        'attente',
        'pending'
            => [
                'container' =>
                    'bg-[#fff4d9] text-[#a8750d]',
                'dot' =>
                    'bg-[#dca94b]',
            ],

        'rejete',
        'rejeté',
        'rejected'
            => [
                'container' =>
                    'bg-[#fbecec] text-[#9b4e4e]',
                'dot' =>
                    'bg-[#c96c6c]',
            ],

        default
            => [
                'container' =>
                    'bg-[#f2eadf] text-[#725238]',
                'dot' =>
                    'bg-[#a98260]',
            ],
    };
};

?>

<div class="min-h-screen bg-[#faf7f2]">


    <!-- =========================================================
         HEADER
    ========================================================== -->
    <header class="sticky top-0 z-20 border-b border-[#e8dfd5] bg-white/95 backdrop-blur">

        <div class="flex min-h-[82px] flex-col items-start justify-center gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div>

                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                    Espace membre
                </p>

                <h1 class="mt-1 break-words text-xl font-bold text-[#30221b] md:text-2xl">

                  Dashboard
                </h1>

            </div>


            <div class="flex items-center gap-4">


                <!-- Notification -->
                <a
                    href="/immo-sn/member/alertes"
                    class="
                        relative
                        flex
                        h-10
                        w-10
                        items-center
                        justify-center
                        rounded-xl
                        border
                        border-[#e8dfd5]
                        bg-white
                        text-[#725238]
                        hover:bg-[#faf7f2]
                    "
                    title="Mes alertes"
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
                            d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10 21h4"
                        />

                    </svg>

                    <?php if ($nombreAlertes > 0): ?>

                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#f7b500] ring-2 ring-white"></span>

                    <?php endif; ?>

                </a>


                <!-- Profil -->
                <div class="hidden sm:flex items-center gap-3">

                    <div class="text-right">

                        <p class="text-sm font-semibold text-[#30221b]">
                            <?= htmlspecialchars($nomComplet) ?>
                        </p>

                        <p class="text-xs text-[#9a897b] mt-0.5">
                            <?= htmlspecialchars($role) ?>
                        </p>

                    </div>


                    <a
                        href="/immo-sn/member/profil"
                        class="
                            flex
                            h-11
                            w-11
                            items-center
                            justify-center
                            rounded-2xl
                            bg-[#30221b]
                            shadow-sm
                        "
                        title="Mon profil"
                    >

                        <span class="text-sm font-bold text-[#f7b500]">
                            <?= htmlspecialchars($initiale) ?>
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </header>


    <!-- =========================================================
         CONTENU
    ========================================================== -->
    <main class="p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">


            <!-- =================================================
                 HERO / WELCOME
            ================================================== -->
            <section
                class="
                    relative
                    overflow-hidden
                    rounded-[28px]
                    bg-[#30221b]
                    shadow-xl
                    mb-8
                "
            >

                <!-- Décorations -->
                <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-[#f7b500]/10"></div>

                <div class="absolute right-28 -bottom-32 h-72 w-72 rounded-full border border-[#f7b500]/10"></div>

                <div class="relative px-6 py-7 md:px-8 md:py-9">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">


                        <!-- Texte -->
                        <div class="max-w-2xl">

                            <div
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-full
                                    border
                                    border-white/10
                                    bg-white/10
                                    px-3
                                    py-1.5
                                    mb-5
                                "
                            >

                                <span class="h-2 w-2 rounded-full bg-[#f7b500]"></span>

                                <span class="text-xs font-semibold text-[#f7ead8]">
                                    Bienvenue dans votre espace
                                </span>

                            </div>


                            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white">
                                Bonjour <?= htmlspecialchars($prenomAffichage) ?> 👋
                            </h2>

                            <p class="mt-3 max-w-xl text-sm md:text-base leading-7 text-[#d7cbc1]">
                                Retrouvez ici vos annonces, vos favoris, vos alertes
                                et les principales activités de votre compte immobilier.
                            </p>


                            <!-- Boutons -->
                            <div class="mt-6 flex flex-col sm:flex-row gap-3">

                                <a
                                    href="/immo-sn/member/publier"
                                    class="
                                        inline-flex
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-xl
                                        bg-[#f7b500]
                                        px-5
                                        py-3
                                        text-sm
                                        font-bold
                                        text-[#30221b]
                                        hover:bg-[#ffc52e]
                                        shadow-sm
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 5v14M5 12h14"
                                        />
                                    </svg>

                                    Publier un bien

                                </a>


                                <a
                                    href="/immo-sn/member/mes-biens"
                                    class="
                                        inline-flex
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-xl
                                        border
                                        border-white/15
                                        bg-white/5
                                        px-5
                                        py-3
                                        text-sm
                                        font-semibold
                                        text-white
                                        hover:bg-white/10
                                    "
                                >

                                    Mes biens

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 12h14M13 6l6 6-6 6"
                                        />
                                    </svg>

                                </a>

                            </div>

                        </div>


                        <!-- Illustration -->
                        <div class="hidden lg:flex w-56 h-44 items-center justify-center">

                            <div class="relative">

                                <!-- Cercle -->
                                <div
                                    class="
                                        flex
                                        h-40
                                        w-40
                                        items-center
                                        justify-center
                                        rounded-full
                                        border
                                        border-[#f7b500]/20
                                        bg-[#f7b500]/10
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-20 w-20 text-[#f7b500]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.1"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"
                                        />
                                    </svg>

                                </div>


                                <!-- Petit badge -->
                                <div
                                    class="
                                        absolute
                                        -right-2
                                        bottom-2
                                        flex
                                        h-12
                                        w-12
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        bg-white
                                        shadow-lg
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-[#30221b]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25A5.25 5.25 0 017.5 3c1.387 0 2.64.576 3.5 1.501A5.25 5.25 0 0114.5 3a5.25 5.25 0 015.25 5.25c0 3.924-2.438 7.11-4.739 9.257a25.18 25.18 0 01-4.244 3.17 15.247 15.247 0 01-.383.218l-.022.012-.007.003-.003.001a.752.752 0 01-.707 0l-.003-.001Z"
                                        />
                                    </svg>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            <!-- =================================================
                 STATISTIQUES
            ================================================== -->
            <section class="mb-8">

                <div class="flex items-end justify-between mb-5">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                            Vue d'ensemble
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-[#30221b]">
                            Vos statistiques
                        </h2>

                    </div>

                    <span class="hidden sm:block text-xs text-[#9a897b]">
                        Activité de votre compte
                    </span>

                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">


                    <!-- Biens -->
                    <a
                        href="/immo-sn/member/mes-biens"
                        class="
                            group
                            rounded-[22px]
                            border
                            border-[#e8dfd5]
                            bg-white
                            p-5
                            shadow-sm
                            hover:-translate-y-0.5
                            hover:shadow-md
                            transition
                        "
                    >

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#8b7b6f]">
                                    Mes biens
                                </p>

                                <p class="mt-3 text-3xl font-bold text-[#30221b]">
                                    <?= $nombreBiens ?>
                                </p>

                                <p class="mt-2 text-xs text-[#a98260]">
                                    Annonces immobilières
                                </p>

                            </div>


                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-[#f2eadf]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-[#725238]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 10.5L12 3l9 7.5"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 9.5V21h14V9.5"
                                    />

                                </svg>

                            </div>

                        </div>

                    </a>


                    <!-- Alertes -->
                    <a
                        href="/immo-sn/member/alertes"
                        class="
                            group
                            rounded-[22px]
                            border
                            border-[#e8dfd5]
                            bg-white
                            p-5
                            shadow-sm
                            hover:-translate-y-0.5
                            hover:shadow-md
                            transition
                        "
                    >

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#8b7b6f]">
                                    Mes alertes
                                </p>

                                <p class="mt-3 text-3xl font-bold text-[#30221b]">
                                    <?= $nombreAlertes ?>
                                </p>

                                <p class="mt-2 text-xs text-[#a98260]">
                                    Alertes immobilières
                                </p>

                            </div>


                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-[#fff7df]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-[#b98516]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10 21h4"
                                    />

                                </svg>

                            </div>

                        </div>

                    </a>


                    <!-- Vues -->
                    <div
                        class="
                            group
                            rounded-[22px]
                            border
                            border-[#e8dfd5]
                            bg-white
                            p-5
                            shadow-sm
                            hover:-translate-y-0.5
                            hover:shadow-md
                            transition
                        "
                    >

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#8b7b6f]">
                                    Vues
                                </p>

                                <p class="mt-3 text-3xl font-bold text-[#30221b]">
                                    <?= number_format($nombreVues, 0, ',', ' ') ?>
                                </p>

                                <p class="mt-2 text-xs text-[#a98260]">
                                    Vues sur vos annonces
                                </p>

                            </div>


                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-[#f2eadf]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-[#725238]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="2.5"
                                    />

                                </svg>

                            </div>

                        </div>

                    </div>


                    <!-- Favoris -->
                    <a
                        href="/immo-sn/member/favoris"
                        class="
                            group
                            rounded-[22px]
                            border
                            border-[#e8dfd5]
                            bg-white
                            p-5
                            shadow-sm
                            hover:-translate-y-0.5
                            hover:shadow-md
                            transition
                        "
                    >

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#8b7b6f]">
                                    Mes favoris
                                </p>

                                <p class="mt-3 text-3xl font-bold text-[#30221b]">
                                    <?= $nombreFavoris ?>
                                </p>

                                <p class="mt-2 text-xs text-[#a98260]">
                                    Biens sauvegardés
                                </p>

                            </div>


                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    bg-[#f8eeee]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-[#9b6666]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25A5.25 5.25 0 017.5 3c1.387 0 2.64.576 3.5 1.501A5.25 5.25 0 0114.5 3a5.25 5.25 0 015.25 5.25c0 3.924-2.438 7.11-4.739 9.257a25.18 25.18 0 01-4.244 3.17 15.247 15.247 0 01-.383.218l-.022.012-.007.003-.003.001a.752.752 0 01-.707 0l-.003-.001Z"
                                    />
                                </svg>

                            </div>

                        </div>

                    </a>

                </div>

            </section>


            <!-- =================================================
                 ACTIVITÉ + ALERTE
            ================================================== -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">


                <!-- Statistiques 30 jours -->
                <div
                    class="
                        lg:col-span-2
                        rounded-[24px]
                        border
                        border-[#e8dfd5]
                        bg-white
                        p-6
                        shadow-sm
                    "
                >

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                                Activité
                            </p>

                            <h3 class="mt-1 text-lg font-bold text-[#30221b]">
                                Statistiques des 30 derniers jours
                            </h3>

                        </div>

                        <span
                            class="
                                inline-flex
                                items-center
                                gap-1.5
                                rounded-full
                                bg-[#fff7df]
                                px-3
                                py-1.5
                                text-xs
                                font-semibold
                                text-[#a8750d]
                            "
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-[#f7b500]"></span>
                            Activité
                        </span>

                    </div>


             <!-- Graphique dynamique des 30 derniers jours -->

<div class="flex h-40 items-end gap-1.5 sm:gap-2">

    <?php if (!empty($activite30Jours)): ?>

        <?php foreach ($activite30Jours as $jour): ?>

            <?php
            $total = (int) ($jour['total'] ?? 0);

            /*
             * Hauteur de la barre proportionnelle
             * au nombre de vues du jour.
             */
            if ($total > 0) {

                $hauteur = (int) round(
                    ($total / $maxVues) * 100
                );

                /*
                 * Une petite hauteur minimale permet
                 * de voir les jours qui ont au moins 1 vue.
                 */
                $hauteur = max(8, $hauteur);

            } else {

                /*
                 * Jour sans aucune vue.
                 */
                $hauteur = 3;
            }


            /*
             * Date du jour
             */
            $date = $jour['jour'] ?? '';

            $dateFormatee = '';

            if ($date !== '') {

                $timestamp = strtotime($date);

                if ($timestamp !== false) {

                    $dateFormatee = date(
                        'd/m/Y',
                        $timestamp
                    );
                }
            }


            /*
             * Texte du tooltip
             */
            $texteVue =
                $total . ' vue' .
                ($total > 1 ? 's' : '');

            $tooltip =
                $dateFormatee !== ''
                    ? $dateFormatee . ' : ' . $texteVue
                    : $texteVue;
            ?>


            <div class="group relative flex h-full flex-1 items-end">

                <div
                    class="
                        w-full
                        rounded-t-lg
                        bg-[#a98260]
                        transition-all
                        duration-200
                        hover:bg-[#725238]
                    "
                    style="height: <?= $hauteur ?>%;"
                    title="<?= htmlspecialchars(
                        $tooltip,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                ></div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <!-- Aucun historique disponible -->

        <div class="flex h-full w-full items-end gap-2">

            <?php for ($i = 0; $i < 30; $i++): ?>

                <div
                    class="
                        h-[3%]
                        flex-1
                        rounded-t-lg
                        bg-[#eee6de]
                    "
                    title="Aucune vue"
                ></div>

            <?php endfor; ?>

        </div>

    <?php endif; ?>

</div>


                    <div class="mt-3 flex justify-between text-[10px] text-[#a9998c]">

                        <span>30 jours</span>
                        <span>Aujourd'hui</span>

                    </div>

                </div>


                <!-- Dernière alerte -->
                <div
                    class="
                        rounded-[24px]
                        border
                        border-[#e8dfd5]
                        bg-white
                        p-6
                        shadow-sm
                    "
                >

                    <div class="flex items-center justify-between mb-5">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                                Notification
                            </p>

                            <h3 class="mt-1 text-lg font-bold text-[#30221b]">
                                Dernière alerte
                            </h3>

                        </div>


                        <div
                            class="
                                flex
                                h-10
                                w-10
                                items-center
                                justify-center
                                rounded-xl
                                bg-[#fff7df]
                            "
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-[#b98516]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M10 21h4"
                                />

                            </svg>

                        </div>

                    </div>


                    <?php if ($derniereAlerte): ?>

                        <?php
                        $alerteTitre =
                            trim(
                                $derniereAlerte['categorie_nom'] ?? ''
                            ) !== ''
                                ? 'Alerte ' .
                                    $derniereAlerte['categorie_nom']
                                : 'Alerte immobilière';

                        $alerteDescription =
                            trim(
                                $derniereAlerte['ville_nom'] ?? ''
                            ) !== ''
                                ? 'Recherche enregistrée pour ' .
                                    $derniereAlerte['ville_nom']
                                : 'Votre alerte immobilière est active.';

                        $alerteDate =
                            $formatDate(
                                $derniereAlerte['created_at'] ?? ''
                            );
                        ?>

                        <div class="rounded-2xl bg-[#faf7f2] border border-[#eee6de] p-4">

                            <div class="flex items-start gap-3">

                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-[#30221b]
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-[#f7b500]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M10 21h4"
                                        />

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-sm font-bold text-[#30221b]">
                                        <?= htmlspecialchars($alerteTitre) ?>
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-[#8b7b6f]">
                                        <?= htmlspecialchars($alerteDescription) ?>
                                    </p>

                                    <?php if ($alerteDate !== ''): ?>

                                        <p class="mt-3 text-[10px] font-medium text-[#a98260]">
                                            <?= htmlspecialchars($alerteDate) ?>
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    <?php else: ?>

                        <div class="rounded-2xl bg-[#faf7f2] border border-[#eee6de] p-4">

                            <div class="flex items-center gap-3">

                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-[#f2eadf]
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-[#725238]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 8v4l2.5 2.5"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="8"
                                        />

                                    </svg>

                                </div>

                                <p class="text-xs leading-5 text-[#8b7b6f]">
                                    Vous n'avez encore aucune alerte immobilière.
                                </p>

                            </div>

                        </div>

                    <?php endif; ?>


                    <a
                        href="/immo-sn/member/alertes"
                        class="
                            mt-4
                            inline-flex
                            items-center
                            gap-2
                            text-xs
                            font-semibold
                            text-[#725238]
                            hover:text-[#30221b]
                        "
                    >

                        Voir mes alertes

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-3.5 h-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14M13 6l6 6-6 6"
                            />
                        </svg>

                    </a>

                </div>

            </section>


            <!-- =================================================
                 DERNIERS BIENS + ACTIONS RAPIDES
            ================================================== -->
            <section class="grid grid-cols-1 xl:grid-cols-3 gap-5">


                <!-- =============================================
                     DERNIERS BIENS
                ============================================== -->
                <div
                    class="
                        xl:col-span-2
                        rounded-[24px]
                        border
                        border-[#e8dfd5]
                        bg-white
                        shadow-sm
                        overflow-hidden
                    "
                >

                    <!-- En-tête -->
      <div class="flex flex-col items-start justify-between gap-4 px-4 py-5 sm:flex-row sm:items-center sm:px-6 border-b border-[#eee6de]">

      
                        <div>

                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                                Mes annonces
                            </p>

                            <h2 class="mt-1 text-lg font-bold text-[#30221b]">
                                Derniers biens
                            </h2>

                            <p class="mt-1 text-sm text-[#9a897b]">
                                Vos dernières annonces immobilières
                            </p>

                        </div>


                        <a
                            href="/immo-sn/member/mes-biens"
                            class="
                                inline-flex
                                items-center
                                gap-1.5
                                rounded-xl
                                border
                                border-[#e8dfd5]
                                bg-[#faf7f2]
                                px-3.5
                                py-2
                                text-xs
                                font-semibold
                                text-[#725238]
                                hover:bg-[#f2eadf]
                            "
                        >

                            Voir tout

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-3.5 h-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 12h14M13 6l6 6-6 6"
                                />
                            </svg>

                        </a>

                    </div>


                    <!-- Tableau -->
                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[680px] text-left">

                            <thead class="bg-[#faf7f2]">

                                <tr class="text-[10px] uppercase tracking-[0.12em] text-[#8b7b6f]">

                                    <th class="px-6 py-4 font-semibold">
                                        Bien
                                    </th>

                                    <th class="px-6 py-4 font-semibold">
                                        Localisation
                                    </th>

                                    <th class="px-6 py-4 font-semibold">
                                        Publication
                                    </th>

                                    <th class="px-6 py-4 text-right font-semibold">
                                        État
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-[#eee6de]">

                                <?php if (!empty($derniersBiens)): ?>

                                    <?php foreach ($derniersBiens as $bien): ?>

                                        <?php
                                        $bienId = $bien['id'] ?? '';

                                        $titre =
                                            trim(
                                                $bien['titre'] ?? ''
                                            );

                                        if ($titre === '') {
                                            $titre = 'Bien immobilier';
                                        }

                                        $categorie =
                                            trim(
                                                $bien['categorie_nom'] ?? ''
                                            );

                                        if ($categorie === '') {
                                            $categorie = 'Immobilier';
                                        }

                                        $ville =
                                            trim(
                                                $bien['ville_nom'] ?? ''
                                            );

                                        $quartier =
                                            trim(
                                                $bien['quartier_nom'] ?? ''
                                            );

                                        $localisation =
                                            trim(
                                                $quartier .
                                                (
                                                    $quartier !== ''
                                                    && $ville !== ''
                                                        ? ' — '
                                                        : ''
                                                ) .
                                                $ville
                                            );

                                        if ($localisation === '') {
                                            $localisation = 'Non renseignée';
                                        }

                                        $datePublication =
                                            $formatDate(
                                                $bien['created_at'] ?? ''
                                            );

                                        $statut =
                                            $bien['statut'] ?? '';

                                        $statutTexte =
                                            $libelleStatut(
                                                $statut
                                            );

                                        $statutClasses =
                                            $classesStatut(
                                                $statut
                                            );
                                        ?>

                                        <tr class="hover:bg-[#fffdfb] transition">

                                            <td class="px-6 py-4">

                                                <?php if ($bienId !== ''): ?>

                                                    <a
                                                        href="/immo-sn/biens/<?= urlencode((string) $bienId) ?>"
                                                        class="flex items-center gap-3"
                                                    >

                                                <?php else: ?>

                                                    <div class="flex items-center gap-3">

                                                <?php endif; ?>


                                                    <div
                                                        class="
                                                            flex
                                                            h-10
                                                            w-10
                                                            shrink-0
                                                            items-center
                                                            justify-center
                                                            rounded-xl
                                                            bg-[#f2eadf]
                                                        "
                                                    >

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-5 w-5 text-[#725238]"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="1.7"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"
                                                            />
                                                        </svg>

                                                    </div>


                                                    <div>

                                                        <p class="text-sm font-semibold text-[#30221b]">
                                                            <?= htmlspecialchars($titre) ?>
                                                        </p>

                                                        <p class="text-xs text-[#9a897b] mt-0.5">
                                                            <?= htmlspecialchars($categorie) ?>
                                                        </p>

                                                    </div>


                                                <?php if ($bienId !== ''): ?>

                                                    </a>

                                                <?php else: ?>

                                                    </div>

                                                <?php endif; ?>

                                            </td>


                                            <td class="px-6 py-4 text-sm text-[#725238]">
                                                <?= htmlspecialchars($localisation) ?>
                                            </td>


                                            <td class="px-6 py-4 text-sm text-[#725238]">
                                                <?= htmlspecialchars($datePublication ?: '—') ?>
                                            </td>


                                            <td class="px-6 py-4 text-right">

                                                <span
                                                    class="
                                                        inline-flex
                                                        items-center
                                                        gap-1.5
                                                        rounded-full
                                                        px-3
                                                        py-1.5
                                                        text-[10px]
                                                        font-semibold
                                                        <?= $statutClasses['container'] ?>
                                                    "
                                                >

                                                    <span
                                                        class="
                                                            h-1.5
                                                            w-1.5
                                                            rounded-full
                                                            <?= $statutClasses['dot'] ?>
                                                        "
                                                    ></span>

                                                    <?= htmlspecialchars($statutTexte) ?>

                                                </span>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="px-6 py-10 text-center"
                                        >

                                            <div class="flex flex-col items-center">

                                                <div
                                                    class="
                                                        flex
                                                        h-12
                                                        w-12
                                                        items-center
                                                        justify-center
                                                        rounded-2xl
                                                        bg-[#f2eadf]
                                                    "
                                                >

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-6 w-6 text-[#725238]"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="1.7"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9"
                                                        />

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M9 20v-6h6v6"
                                                        />

                                                    </svg>

                                                </div>

                                                <p class="mt-3 text-sm font-semibold text-[#30221b]">
                                                    Aucun bien publié
                                                </p>

                                                <p class="mt-1 text-xs text-[#9a897b]">
                                                    Vous n'avez pas encore publié de bien.
                                                </p>

                                                <a
                                                    href="/immo-sn/member/publier"
                                                    class="
                                                        mt-4
                                                        inline-flex
                                                        items-center
                                                        gap-2
                                                        rounded-xl
                                                        bg-[#30221b]
                                                        px-4
                                                        py-2.5
                                                        text-xs
                                                        font-semibold
                                                        text-white
                                                        hover:bg-[#4a3529]
                                                    "
                                                >

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M12 5v14M5 12h14"
                                                        />
                                                    </svg>

                                                    Publier un bien

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>


                <!-- =============================================
                     ACTIONS RAPIDES
                ============================================== -->
                <div
                    class="
                        rounded-[24px]
                        border
                        border-[#e8dfd5]
                        bg-white
                        p-6
                        shadow-sm
                    "
                >

                    <div class="mb-5">

                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#a98260]">
                            Raccourcis
                        </p>

                        <h2 class="mt-1 text-lg font-bold text-[#30221b]">
                            Actions rapides
                        </h2>

                        <p class="mt-1 text-sm text-[#9a897b]">
                            Accédez rapidement aux fonctions principales.
                        </p>

                    </div>


                    <div class="space-y-3">


                        <!-- Publier -->
                        <a
                            href="/immo-sn/member/publier"
                            class="
                                group
                                flex
                                items-center
                                gap-4
                                rounded-2xl
                                border
                                border-[#eadfd5]
                                p-4
                                hover:bg-[#faf7f2]
                                hover:border-[#d8c6b6]
                                transition
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-11
                                    w-11
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-[#fff7df]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-[#b98516]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 5v14M5 12h14"
                                    />
                                </svg>

                            </div>


                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-[#30221b]">
                                    Publier un bien
                                </p>

                                <p class="mt-1 text-xs text-[#9a897b]">
                                    Ajouter une annonce
                                </p>

                            </div>


                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="ml-auto h-4 w-4 text-[#b9aa9d] group-hover:text-[#725238]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </a>


                        <!-- Mes biens -->
                        <a
                            href="/immo-sn/member/mes-biens"
                            class="
                                group
                                flex
                                items-center
                                gap-4
                                rounded-2xl
                                border
                                border-[#eadfd5]
                                p-4
                                hover:bg-[#faf7f2]
                                hover:border-[#d8c6b6]
                                transition
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-11
                                    w-11
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-[#f2eadf]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-[#725238]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 20v-6h6v6"
                                    />

                                </svg>

                            </div>


                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-[#30221b]">
                                    Mes biens
                                </p>

                                <p class="mt-1 text-xs text-[#9a897b]">
                                    Gérer mes annonces
                                </p>

                            </div>


                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="ml-auto h-4 w-4 text-[#b9aa9d] group-hover:text-[#725238]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </a>


                        <!-- Favoris -->
                        <a
                            href="/immo-sn/member/favoris"
                            class="
                                group
                                flex
                                items-center
                                gap-4
                                rounded-2xl
                                border
                                border-[#eadfd5]
                                p-4
                                hover:bg-[#faf7f2]
                                hover:border-[#d8c6b6]
                                transition
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-11
                                    w-11
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-[#f8eeee]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-[#9b6666]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25A5.25 5.25 0 017.5 3c1.387 0 2.64.576 3.5 1.501A5.25 5.25 0 0114.5 3a5.25 5.25 0 015.25 5.25c0 3.924-2.438 7.11-4.739 9.257a25.18 25.18 0 01-4.244 3.17 15.247 15.247 0 01-.383.218l-.022.012-.007.003-.003.001a.752.752 0 01-.707 0l-.003-.001Z"
                                    />
                                </svg>

                            </div>


                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-[#30221b]">
                                    Mes favoris
                                </p>

                                <p class="mt-1 text-xs text-[#9a897b]">
                                    Biens sauvegardés
                                </p>

                            </div>


                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="ml-auto h-4 w-4 text-[#b9aa9d] group-hover:text-[#725238]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </a>


                        <!-- Messages -->
                        <a
                            href="/immo-sn/member/messages"
                            class="
                                group
                                flex
                                items-center
                                gap-4
                                rounded-2xl
                                border
                                border-[#eadfd5]
                                p-4
                                hover:bg-[#faf7f2]
                                hover:border-[#d8c6b6]
                                transition
                            "
                        >

                            <div
                                class="
                                    relative
                                    flex
                                    h-11
                                    w-11
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-[#f2eadf]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-[#725238]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 5h16v11H8l-4 4V5Z"
                                    />

                                </svg>

                                <?php if ($nombreMessagesNonLus > 0): ?>

                                    <span
                                        class="
                                            absolute
                                            -right-1
                                            -top-1
                                            flex
                                            h-5
                                            min-w-5
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-[#f7b500]
                                            px-1
                                            text-[9px]
                                            font-bold
                                            text-[#30221b]
                                        "
                                    >
                                        <?= $nombreMessagesNonLus ?>
                                    </span>

                                <?php endif; ?>

                            </div>


                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-[#30221b]">
                                    Mes messages
                                </p>

                                <p class="mt-1 text-xs text-[#9a897b]">
                                    Consulter ma messagerie
                                </p>

                            </div>


                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="ml-auto h-4 w-4 text-[#b9aa9d] group-hover:text-[#725238]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </a>


                        <!-- Profil -->
                        <a
                            href="/immo-sn/member/profil"
                            class="
                                group
                                flex
                                items-center
                                gap-4
                                rounded-2xl
                                border
                                border-[#eadfd5]
                                p-4
                                hover:bg-[#faf7f2]
                                hover:border-[#d8c6b6]
                                transition
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-11
                                    w-11
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-[#f2eadf]
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-[#725238]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <circle
                                        cx="12"
                                        cy="8"
                                        r="3"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5.5 20a6.5 6.5 0 0113 0"
                                    />

                                </svg>

                            </div>


                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-[#30221b]">
                                    Mon profil
                                </p>

                                <p class="mt-1 text-xs text-[#9a897b]">
                                    Gérer mon compte
                                </p>

                            </div>


                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="ml-auto h-4 w-4 text-[#b9aa9d] group-hover:text-[#725238]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </a>

                    </div>

                </div>

            </section>


        </div>

    </main>

</div>