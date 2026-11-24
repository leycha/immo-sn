
<?php
/**
 * Dashboard membre IMMO-SN
 *
 * Variables disponibles :
 * $stats
 * $user
 * $derniersBiens
 * $dernieresAlertes
 * $activite30Jours
 */

/* =========================================================
   FONCTION D'ÉCHAPPEMENT HTML
========================================================= */

$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

/* =========================================================
   UTILISATEUR CONNECTÉ
========================================================= */

$user = $user ?? [];

$prenom = trim($user['prenom'] ?? '');
$nom = trim($user['nom'] ?? '');

$nomComplet = trim($prenom . ' ' . $nom);

if ($nomComplet === '') {
    $nomComplet = 'Membre';
}

$prenomAffichage = $prenom !== '' ? $prenom : 'Membre';

$initiale = strtoupper(mb_substr($prenomAffichage, 0, 1));

if ($initiale === '') {
    $initiale = 'M';
}

$role = trim($user['role'] ?? '');

if ($role === '') {
    $role = 'Membre';
}

/* =========================================================
   STATISTIQUES
========================================================= */

$nombreBiens = (int) ($stats['biens'] ?? 0);
$nombreAlertes = (int) ($stats['alertes'] ?? 0);
$nombreVues = (int) ($stats['vues'] ?? 0);
$nombreFavoris = (int) ($stats['favoris'] ?? 0);
$nombreMessagesNonLus = (int) ($stats['messages_non_lus'] ?? 0);

/* =========================================================
   DERNIERS BIENS
========================================================= */

$derniersBiens = is_array($derniersBiens ?? null)
    ? $derniersBiens
    : [];

/* =========================================================
   ACTIVITÉ DES 30 DERNIERS JOURS
========================================================= */

$activite30Jours = is_array($activite30Jours ?? null)
    ? $activite30Jours
    : [];

$maxVues = 0;

foreach ($activite30Jours as $jour) {
    $total = (int) ($jour['total'] ?? 0);
    $maxVues = max($maxVues, $total);
}

if ($maxVues <= 0) {
    $maxVues = 1;
}

/* =========================================================
   DERNIÈRES ALERTES
========================================================= */

$dernieresAlertes = is_array($dernieresAlertes ?? null)
    ? $dernieresAlertes
    : [];

$derniereAlerte = $dernieresAlertes[0] ?? null;

/* =========================================================
   FONCTION DATE
========================================================= */

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

/* =========================================================
   LIBELLÉ DU STATUT
========================================================= */

$libelleStatut = static function ($statut): string {
    $statut = strtolower(trim((string) $statut));

    return match ($statut) {
        'actif', 'active', 'valide', 'validé', 'approved' => 'Validé',
        'en-attente', 'en_attente', 'attente', 'pending' => 'En attente',
        'rejete', 'rejeté', 'rejected' => 'Rejeté',
        'expire', 'expiré', 'expired' => 'Expiré',
        'inactif', 'inactive' => 'Inactif',

        default => ucfirst(
            str_replace(
                ['-', '_'],
                ' ',
                $statut !== '' ? $statut : 'Inconnu'
            )
        ),
    };
};

/* =========================================================
   CLASSES DU STATUT
========================================================= */

$classesStatut = static function ($statut): array {
    $statut = strtolower(trim((string) $statut));

    return match ($statut) {
        'actif', 'active', 'valide', 'validé', 'approved' => [
            'container' => 'bg-[#EAF3ED] text-[#39734C]',
            'dot' => 'bg-[#4D9A68]',
        ],

        'en-attente', 'en_attente', 'attente', 'pending' => [
            'container' => 'bg-[#FFF4D9] text-[#946B0D]',
            'dot' => 'bg-[#D6A32E]',
        ],

        'rejete', 'rejeté', 'rejected' => [
            'container' => 'bg-[#FBECEC] text-[#9B4E4E]',
            'dot' => 'bg-[#C96C6C]',
        ],

        'expire', 'expiré', 'expired' => [
            'container' => 'bg-[#EEF0F3] text-[#64748B]',
            'dot' => 'bg-[#94A3B8]',
        ],

        default => [
            'container' => 'bg-[#EEF0F3] text-[#64748B]',
            'dot' => 'bg-[#94A3B8]',
        ],
    };
};

?>

<div class="min-h-screen bg-[#F5F3ED]">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="sticky top-0 z-30 border-b border-[#E8E1D5] bg-[#FFFDF9]/95 backdrop-blur-xl">

        <div class="mx-auto flex min-h-[82px] max-w-[1600px] flex-col items-start justify-center gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">

            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[0.22em] text-[#A98237]">
                    IMMO-SN · Espace personnel
                </p>

                <h1 class="mt-1 text-xl font-extrabold tracking-tight text-[#071B36] md:text-2xl">
                    Tableau de bord
                </h1>
            </div>

            <div class="flex w-full items-center justify-between gap-4 sm:w-auto">

                <!-- Notification -->

                <a
                    href="/immo-sn/member/alertes"
                    class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-[#E8E1D5] bg-white text-[#536477] transition hover:border-[#D7B45E] hover:bg-[#FBF5E6] hover:text-[#98701F]"
                    title="Mes alertes"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10 21h4"/>
                    </svg>

                    <?php if ($nombreAlertes > 0): ?>
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#D7A200] ring-2 ring-white"></span>
                    <?php endif; ?>
                </a>

                <!-- Profil -->

                <div class="flex items-center gap-3">

                    <div class="text-right">
                        <p class="text-sm font-bold text-[#071B36]">
                            <?= $e($nomComplet) ?>
                        </p>

                        <p class="mt-0.5 text-xs text-[#8190A0]">
                            <?= $e($role) ?>
                        </p>
                    </div>

                    <a
                        href="/immo-sn/member/profil"
                        class="flex h-11 w-11 items-center justify-center rounded-xl border border-[#D7A200]/30 bg-[#071B36] shadow-sm transition hover:bg-[#142D4B]"
                        title="Mon profil"
                    >
                        <span class="text-sm font-extrabold text-[#F5C84C]">
                            <?= $e($initiale) ?>
                        </span>
                    </a>

                </div>

            </div>

        </div>

    </header>


    <!-- =====================================================
         CONTENU PRINCIPAL
    ====================================================== -->

    <main class="px-4 py-7 sm:px-6 lg:px-8 lg:py-9">

        <div class="mx-auto max-w-[1500px]">

<!-- =================================================
     HERO / BIENVENUE — VERSION COMPACTE
================================================== -->

<section class="relative mb-7 overflow-hidden rounded-2xl bg-[#071B36] shadow-sm">

    <div class="relative flex flex-col gap-5 px-5 py-5 sm:px-7 sm:py-6 lg:flex-row lg:items-center lg:justify-between">

        <!-- Texte -->

        <div class="max-w-2xl">

            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-[#F5B800]/25 bg-[#F5B800]/10 px-3 py-1.5">

                <span class="h-1.5 w-1.5 rounded-full bg-[#F5B800]"></span>

                <span class="text-[9px] font-bold uppercase tracking-wider text-[#F5D985]">
                    Votre espace immobilier
                </span>

            </div>

            <h2 class="text-xl font-extrabold leading-tight tracking-tight text-white sm:text-2xl lg:text-3xl">
                Bonjour <?= $e($prenomAffichage) ?>
                <span class="text-[#F5B800]">👋</span>
            </h2>

            <p class="mt-2 max-w-xl text-xs leading-6 text-[#CBD4E0] sm:text-sm">
                Retrouvez en un seul endroit vos annonces, vos favoris,
                vos alertes et les principales activités de votre compte immobilier.
            </p>

            <!-- Boutons -->

            <div class="mt-4 flex flex-col gap-2.5 sm:flex-row">

                <a
                    href="/immo-sn/member/publier"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#F5B800] px-4 py-2.5 text-xs font-extrabold text-[#071B36] transition hover:bg-[#FFDA70]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 5v14M5 12h14"/>
                    </svg>

                    Publier un bien
                </a>

                <a
                    href="/immo-sn/member/mes-biens"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/[0.06] px-4 py-2.5 text-xs font-bold text-white transition hover:border-[#F5B800]/50 hover:bg-white/10"
                >
                    Mes biens

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>

            </div>

        </div>

        <!-- Illustration compacte -->

        <div class="hidden shrink-0 items-center justify-center lg:flex">

            <div class="flex h-28 w-28 items-center justify-center rounded-full border border-[#F5B800]/25 bg-[#F5B800]/[0.06]">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-14 w-14 text-[#F5B800]"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"/>
                </svg>

            </div>

        </div>

    </div>

</section>
            <!-- =================================================
                 STATISTIQUES
            ================================================== -->

            <section class="mb-9">

                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A98237]">
                            Vue d'ensemble
                        </p>

                        <h2 class="mt-1.5 text-xl font-extrabold tracking-tight text-[#071B36]">
                            Vos statistiques
                        </h2>
                    </div>

                    <span class="text-xs text-[#8190A0]">
                        Activité de votre compte
                    </span>

                </div>


                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">


                    <!-- Biens -->

                    <a
                        href="/immo-sn/member/mes-biens"
                        class="group rounded-[22px] border border-[#E9E3D9] bg-white p-5 shadow-[0_4px_18px_rgba(7,27,54,0.035)] transition duration-200 hover:-translate-y-1 hover:border-[#D7B45E] hover:shadow-[0_12px_30px_rgba(7,27,54,0.08)]"
                    >

                        <div class="flex items-start justify-between">

                            <div>
                                <p class="text-sm font-semibold text-[#8190A0]">
                                    Mes biens
                                </p>

                                <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#071B36]">
                                    <?= $nombreBiens ?>
                                </p>

                                <p class="mt-2 text-xs font-semibold text-[#A98237]">
                                    Annonces immobilières
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FBF3DE] transition group-hover:bg-[#F8E9BD]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6 text-[#A98237]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 10.5L12 3l9 7.5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M5 9.5V21h14V9.5"/>
                                </svg>
                            </div>

                        </div>

                    </a>


                    <!-- Alertes -->

                    <a
                        href="/immo-sn/member/alertes"
                        class="group rounded-[22px] border border-[#E9E3D9] bg-white p-5 shadow-[0_4px_18px_rgba(7,27,54,0.035)] transition duration-200 hover:-translate-y-1 hover:border-[#D7B45E] hover:shadow-[0_12px_30px_rgba(7,27,54,0.08)]"
                    >

                        <div class="flex items-start justify-between">

                            <div>
                                <p class="text-sm font-semibold text-[#8190A0]">
                                    Mes alertes
                                </p>

                                <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#071B36]">
                                    <?= $nombreAlertes ?>
                                </p>

                                <p class="mt-2 text-xs font-semibold text-[#A98237]">
                                    Alertes immobilières
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FBF3DE] transition group-hover:bg-[#F8E9BD]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6 text-[#A98237]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M10 21h4"/>
                                </svg>
                            </div>

                        </div>

                    </a>


                    <!-- Vues -->

                    <div class="group rounded-[22px] border border-[#E9E3D9] bg-white p-5 shadow-[0_4px_18px_rgba(7,27,54,0.035)] transition duration-200 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgba(7,27,54,0.08)]">

                        <div class="flex items-start justify-between">

                            <div>
                                <p class="text-sm font-semibold text-[#8190A0]">
                                    Vues
                                </p>

                                <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#071B36]">
                                    <?= number_format($nombreVues, 0, ',', ' ') ?>
                                </p>

                                <p class="mt-2 text-xs font-semibold text-[#A98237]">
                                    Vues sur vos annonces
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FBF3DE] transition group-hover:bg-[#F8E9BD]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6 text-[#A98237]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                                    <circle cx="12" cy="12" r="2.5"/>
                                </svg>
                            </div>

                        </div>

                    </div>


                    <!-- Favoris -->

                    <a
                        href="/immo-sn/member/favoris"
                        class="group rounded-[22px] border border-[#E9E3D9] bg-white p-5 shadow-[0_4px_18px_rgba(7,27,54,0.035)] transition duration-200 hover:-translate-y-1 hover:border-[#D7B45E] hover:shadow-[0_12px_30px_rgba(7,27,54,0.08)]"
                    >

                        <div class="flex items-start justify-between">

                            <div>
                                <p class="text-sm font-semibold text-[#8190A0]">
                                    Mes favoris
                                </p>

                                <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#071B36]">
                                    <?= $nombreFavoris ?>
                                </p>

                                <p class="mt-2 text-xs font-semibold text-[#A98237]">
                                    Biens sauvegardés
                                </p>
                            </div>

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F1F2F4] transition group-hover:bg-[#E8EBEF]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6 text-[#71869A]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25A5.25 5.25 0 017.5 3c1.387 0 2.64.576 3.5 1.501A5.25 5.25 0 0114.5 3a5.25 5.25 0 015.25 5.25c0 3.924-2.438 7.11-4.739 9.257a25.18 25.18 0 01-4.244 3.17 15.247 15.247 0 01-.383.218l-.022.012-.007.003-.003.001a.752.752 0 01-.707 0l-.003-.001Z"/>
                                </svg>
                            </div>

                        </div>

                    </a>

                </div>

            </section>


            <!-- =================================================
                 ACTIVITÉ + DERNIÈRE ALERTE
            ================================================== -->

            <section class="mb-9 grid grid-cols-1 gap-5 lg:grid-cols-3">


                <!-- Statistiques 30 jours -->

                <div class="rounded-[24px] border border-[#E9E3D9] bg-white p-5 shadow-[0_4px_18px_rgba(7,27,54,0.035)] sm:p-6 lg:col-span-2">

                    <div class="mb-7 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A98237]">
                                Activité
                            </p>

                            <h3 class="mt-1.5 text-lg font-extrabold text-[#071B36]">
                                Statistiques des 30 derniers jours
                            </h3>
                        </div>

                        <span class="inline-flex w-fit items-center gap-2 rounded-full border border-[#EADCB9] bg-[#FBF5E6] px-3 py-1.5 text-[10px] font-bold text-[#94701F]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#D7A200]"></span>
                            Activité
                        </span>

                    </div>


                  <!-- Graphique dynamique -->

<?php
$graphique = array_values($activite30Jours);

$largeurGraphique = 900;
$hauteurGraphique = 250;

$margeGauche = 45;
$margeDroite = 20;
$margeHaut = 20;
$margeBas = 35;

$zoneLargeur = $largeurGraphique - $margeGauche - $margeDroite;
$zoneHauteur = $hauteurGraphique - $margeHaut - $margeBas;

$nombrePoints = count($graphique);

$pointsCourbe = [];
$pointsSvg = [];
$cheminZone = '';

$totalVues30Jours = 0;
$maxVuesJour = 0;

foreach ($graphique as $jour) {
    $total = (int) ($jour['total'] ?? 0);

    $totalVues30Jours += $total;
    $maxVuesJour = max($maxVuesJour, $total);
}

$echelleMax = max(1, $maxVuesJour);

if ($nombrePoints > 0) {

    foreach ($graphique as $index => $jour) {

        $total = (int) ($jour['total'] ?? 0);

        $x = $nombrePoints === 1
            ? $margeGauche + ($zoneLargeur / 2)
            : $margeGauche + (
                ($index / ($nombrePoints - 1)) * $zoneLargeur
            );

        $y = $margeHaut + (
            $zoneHauteur - (($total / $echelleMax) * $zoneHauteur)
        );

        $pointsCourbe[] = [
            'x' => $x,
            'y' => $y,
            'total' => $total,
            'jour' => $jour['jour'] ?? '',
        ];

        $pointsSvg[] = round($x, 2) . ',' . round($y, 2);
    }

    $ligneSvg = implode(' ', $pointsSvg);

    $premierPoint = $pointsCourbe[0];
    $dernierPoint = $pointsCourbe[count($pointsCourbe) - 1];

    $cheminZone =
        'M ' . round($premierPoint['x'], 2) . ' ' . ($margeHaut + $zoneHauteur) .
        ' L ' . $ligneSvg .
        ' L ' . round($dernierPoint['x'], 2) . ' ' . ($margeHaut + $zoneHauteur) .
        ' Z';
}
?>

<div class="mt-2">

    <!-- Résumé des vues -->

    <div class="mb-5 flex flex-wrap items-end justify-between gap-4">

        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-[#8190A0]">
                Total des consultations
            </p>

            <p class="mt-1 text-3xl font-extrabold tracking-tight text-[#071B36]">
                <?= number_format($totalVues30Jours, 0, ',', ' ') ?>

                <span class="text-sm font-semibold text-[#A98237]">
                    vues
                </span>
            </p>
        </div>

        <div class="rounded-xl border border-[#EADCB9] bg-[#FBF5E6] px-3 py-2">

            <p class="text-[10px] font-bold text-[#94701F]">
                Record journalier
            </p>

            <p class="mt-0.5 text-lg font-extrabold text-[#071B36]">
                <?= number_format($maxVuesJour, 0, ',', ' ') ?>
                <span class="text-xs font-semibold">vues</span>
            </p>

        </div>

    </div>


    <!-- SVG Évolution -->

    <?php if (!empty($graphique)): ?>

        <div class="relative w-full overflow-hidden rounded-2xl border border-[#E9E3D9] bg-[#FCFBF8] p-2 sm:p-4">

            <svg
                viewBox="0 0 <?= $largeurGraphique ?> <?= $hauteurGraphique ?>"
                class="block w-full"
                role="img"
                aria-label="Évolution des vues de vos annonces sur les 30 derniers jours"
            >

                <defs>

                    <!-- Dégradé de la zone -->

                    <linearGradient
                        id="immoVuesGradient"
                        x1="0"
                        y1="0"
                        x2="0"
                        y2="1"
                    >
                        <stop
                            offset="0%"
                            stop-color="#D7A200"
                            stop-opacity="0.30"
                        />

                        <stop
                            offset="100%"
                            stop-color="#D7A200"
                            stop-opacity="0.02"
                        />
                    </linearGradient>

                </defs>


                <!-- Grille horizontale -->

                <?php for ($niveau = 0; $niveau <= 4; $niveau++): ?>

                    <?php
                    $ratio = $niveau / 4;

                    $yGrille = $margeHaut + ($zoneHauteur * $ratio);

                    $valeurGrille = (int) round(
                        $echelleMax * (1 - $ratio)
                    );
                    ?>

                    <line
                        x1="<?= $margeGauche ?>"
                        y1="<?= round($yGrille, 2) ?>"
                        x2="<?= $largeurGraphique - $margeDroite ?>"
                        y2="<?= round($yGrille, 2) ?>"
                        stroke="#E8E5DD"
                        stroke-width="1"
                        stroke-dasharray="4 5"
                    />

                    <text
                        x="<?= $margeGauche - 8 ?>"
                        y="<?= round($yGrille + 4, 2) ?>"
                        text-anchor="end"
                        font-size="11"
                        fill="#8190A0"
                    >
                        <?= $valeurGrille ?>
                    </text>

                <?php endfor; ?>


                <!-- Zone sous la courbe -->

                <path
                    d="<?= $e($cheminZone) ?>"
                    fill="url(#immoVuesGradient)"
                />


                <!-- Courbe principale -->

                <polyline
                    points="<?= $e($ligneSvg) ?>"
                    fill="none"
                    stroke="#C39216"
                    stroke-width="3.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />


                <!-- Points interactifs -->

                <?php foreach ($pointsCourbe as $point): ?>

                    <?php
                    $datePoint = '';

                    if (!empty($point['jour'])) {
                        $timestampPoint = strtotime($point['jour']);

                        if ($timestampPoint !== false) {
                            $datePoint = date('d/m/Y', $timestampPoint);
                        }
                    }

                    $tooltipPoint = ($datePoint !== '' ? $datePoint : 'Date inconnue')
                        . ' — '
                        . $point['total']
                        . ' vue'
                        . ($point['total'] > 1 ? 's' : '');
                    ?>

                    <circle
                        cx="<?= round($point['x'], 2) ?>"
                        cy="<?= round($point['y'], 2) ?>"
                        r="5"
                        fill="#FFFFFF"
                        stroke="#C39216"
                        stroke-width="3"
                    >
                        <title><?= $e($tooltipPoint) ?></title>
                    </circle>

                <?php endforeach; ?>


                <!-- Axe inférieur -->

                <line
                    x1="<?= $margeGauche ?>"
                    y1="<?= $margeHaut + $zoneHauteur ?>"
                    x2="<?= $largeurGraphique - $margeDroite ?>"
                    y2="<?= $margeHaut + $zoneHauteur ?>"
                    stroke="#DCD8CE"
                    stroke-width="1.2"
                />

            </svg>

        </div>


        <!-- Dates -->

        <div class="mt-3 flex items-center justify-between px-1">

            <?php
            $dateDebutGraphique = '';
            $dateFinGraphique = '';

            if (!empty($graphique)) {

                $premiereDate = $graphique[0]['jour'] ?? '';
                $derniereDate = $graphique[count($graphique) - 1]['jour'] ?? '';

                if ($premiereDate !== '') {
                    $dateDebutGraphique = $formatDate($premiereDate);
                }

                if ($derniereDate !== '') {
                    $dateFinGraphique = $formatDate($derniereDate);
                }
            }
            ?>

            <span class="text-[10px] font-semibold text-[#8190A0]">
                <?= $e($dateDebutGraphique ?: '30 derniers jours') ?>
            </span>

            <span class="rounded-full bg-[#FBF3DE] px-3 py-1 text-[10px] font-extrabold text-[#94701F]">
                Évolution quotidienne
            </span>

            <span class="text-[10px] font-semibold text-[#8190A0]">
                <?= $e($dateFinGraphique ?: "Aujourd'hui") ?>
            </span>

        </div>


    <?php else: ?>

        <!-- Aucune donnée -->

        <div class="flex min-h-[220px] flex-col items-center justify-center rounded-2xl border border-dashed border-[#E5DED1] bg-[#FCFBF8] px-6 text-center">

            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FBF3DE]">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-7 w-7 text-[#A98237]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 17l6-6 4 4 8-9"
                    />
                </svg>

            </div>

            <p class="mt-4 text-sm font-extrabold text-[#071B36]">
                Vos statistiques apparaîtront ici
            </p>

            <p class="mt-1 max-w-sm text-xs leading-5 text-[#8190A0]">
                Dès que vos annonces recevront des visites, vous pourrez suivre leur évolution au quotidien.
            </p>

        </div>

    <?php endif; ?>

</div>


                </div>


                <!-- Dernière alerte -->

                <div class="rounded-[24px] border border-[#E9E3D9] bg-white p-5 shadow-[0_4px_18px_rgba(7,27,54,0.035)] sm:p-6">

                    <div class="mb-5 flex items-center justify-between">

                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A98237]">
                                Notification
                            </p>

                            <h3 class="mt-1.5 text-lg font-extrabold text-[#071B36]">
                                Dernière alerte
                            </h3>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#FBF3DE]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 text-[#A98237]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M10 21h4"/>
                            </svg>

                        </div>

                    </div>


                    <?php if ($derniereAlerte): ?>

                        <?php
                        $alerteTitre =
                            trim($derniereAlerte['categorie_nom'] ?? '') !== ''
                                ? 'Alerte ' . $derniereAlerte['categorie_nom']
                                : 'Alerte immobilière';

                        $alerteDescription =
                            trim($derniereAlerte['ville_nom'] ?? '') !== ''
                                ? 'Recherche enregistrée pour ' . $derniereAlerte['ville_nom']
                                : 'Votre alerte immobilière est active.';

                        $alerteDate = $formatDate(
                            $derniereAlerte['created_at'] ?? ''
                        );
                        ?>

                        <div class="rounded-2xl border border-[#EADCB9] bg-[#FCF8ED] p-4">

                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#071B36]">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5 text-[#F5C84C]"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10 21h4"/>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-sm font-extrabold text-[#071B36]">
                                        <?= $e($alerteTitre) ?>
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-[#64748B]">
                                        <?= $e($alerteDescription) ?>
                                    </p>

                                    <?php if ($alerteDate !== ''): ?>

                                        <p class="mt-3 text-[10px] font-bold text-[#A98237]">
                                            <?= $e($alerteDate) ?>
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    <?php else: ?>

                        <div class="rounded-2xl border border-[#E9E3D9] bg-[#F8F7F3] p-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FBF3DE]">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5 text-[#A98237]"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 8v4l2.5 2.5"/>
                                        <circle cx="12" cy="12" r="8"/>
                                    </svg>

                                </div>

                                <p class="text-xs leading-5 text-[#8190A0]">
                                    Vous n'avez encore aucune alerte immobilière.
                                </p>

                            </div>

                        </div>

                    <?php endif; ?>


                    <a
                        href="/immo-sn/member/alertes"
                        class="mt-4 inline-flex items-center gap-2 text-xs font-extrabold text-[#98701F] transition hover:text-[#071B36]"
                    >
                        Voir mes alertes

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-3.5 w-3.5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>

                </div>

            </section>


            <!-- =================================================
                 DERNIERS BIENS + ACTIONS RAPIDES
            ================================================== -->

            <section class="grid grid-cols-1 gap-5 xl:grid-cols-3">


                <!-- =============================================
                     DERNIERS BIENS
                ============================================== -->

                <div class="overflow-hidden rounded-[24px] border border-[#E9E3D9] bg-white shadow-[0_4px_18px_rgba(7,27,54,0.035)] xl:col-span-2">

                    <!-- En-tête -->

                    <div class="flex flex-col items-start justify-between gap-4 border-b border-[#E9E3D9] px-5 py-5 sm:flex-row sm:items-center sm:px-6">

                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A98237]">
                                Mes annonces
                            </p>

                            <h2 class="mt-1.5 text-lg font-extrabold text-[#071B36]">
                                Derniers biens
                            </h2>

                            <p class="mt-1 text-xs text-[#8190A0]">
                                Vos dernières annonces immobilières
                            </p>
                        </div>

                        <a
                            href="/immo-sn/member/mes-biens"
                            class="inline-flex items-center gap-2 rounded-xl border border-[#EADCB9] bg-[#FBF5E6] px-4 py-2.5 text-xs font-extrabold text-[#94701F] transition hover:bg-[#F6EBCB]"
                        >
                            Voir tout

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-3.5 w-3.5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>

                    </div>


                    <!-- Tableau -->

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[680px] text-left">

                            <thead class="bg-[#F8F6F0]">

                                <tr class="text-[10px] uppercase tracking-[0.13em] text-[#8190A0]">

                                    <th class="px-6 py-4 font-extrabold">
                                        Bien
                                    </th>

                                    <th class="px-6 py-4 font-extrabold">
                                        Localisation
                                    </th>

                                    <th class="px-6 py-4 font-extrabold">
                                        Publication
                                    </th>

                                    <th class="px-6 py-4 text-right font-extrabold">
                                        État
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-[#EEEAE2]">

                                <?php if (!empty($derniersBiens)): ?>

                                    <?php foreach ($derniersBiens as $bien): ?>

                                        <?php
                                        $bienId = $bien['id'] ?? '';

                                        $titre = trim($bien['titre'] ?? '');

                                        if ($titre === '') {
                                            $titre = 'Bien immobilier';
                                        }

                                        $categorie = trim($bien['categorie_nom'] ?? '');

                                        if ($categorie === '') {
                                            $categorie = 'Immobilier';
                                        }

                                        $ville = trim($bien['ville_nom'] ?? '');
                                        $quartier = trim($bien['quartier_nom'] ?? '');

                                        $localisation = trim(
                                            $quartier .
                                            (
                                                $quartier !== '' && $ville !== ''
                                                    ? ' — '
                                                    : ''
                                            ) .
                                            $ville
                                        );

                                        if ($localisation === '') {
                                            $localisation = 'Non renseignée';
                                        }

                                        $datePublication = $formatDate(
                                            $bien['created_at'] ?? ''
                                        );

                                        $statut = $bien['statut'] ?? '';

                                        $statutTexte = $libelleStatut($statut);
                                        $statutClasses = $classesStatut($statut);
                                        ?>

                                        <tr class="transition hover:bg-[#FCFAF5]">

                                            <td class="px-6 py-4">

                                                <?php if ($bienId !== ''): ?>

                                                    <a
                                                        href="/immo-sn/biens/<?= urlencode((string) $bienId) ?>"
                                                        class="flex items-center gap-3"
                                                    >

                                                <?php else: ?>

                                                    <div class="flex items-center gap-3">

                                                <?php endif; ?>


                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FBF3DE]">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-5 w-5 text-[#A98237]"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="1.7">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"/>
                                                    </svg>

                                                </div>


                                                <div class="min-w-0">

                                                    <p class="text-sm font-extrabold text-[#071B36]">
                                                        <?= $e($titre) ?>
                                                    </p>

                                                    <p class="mt-0.5 text-xs text-[#8190A0]">
                                                        <?= $e($categorie) ?>
                                                    </p>

                                                </div>


                                                <?php if ($bienId !== ''): ?>

                                                    </a>

                                                <?php else: ?>

                                                    </div>

                                                <?php endif; ?>

                                            </td>


                                            <td class="px-6 py-4 text-sm text-[#536477]">
                                                <?= $e($localisation) ?>
                                            </td>


                                            <td class="px-6 py-4 text-sm text-[#536477]">
                                                <?= $e($datePublication ?: '—') ?>
                                            </td>


                                            <td class="px-6 py-4 text-right">

                                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[10px] font-extrabold <?= $statutClasses['container'] ?>">

                                                    <span class="h-1.5 w-1.5 rounded-full <?= $statutClasses['dot'] ?>"></span>

                                                    <?= $e($statutTexte) ?>

                                                </span>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td colspan="4" class="px-6 py-12 text-center">

                                            <div class="flex flex-col items-center">

                                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FBF3DE]">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-7 w-7 text-[#A98237]"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="1.7">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M9 20v-6h6v6"/>
                                                    </svg>

                                                </div>

                                                <p class="mt-4 text-sm font-extrabold text-[#071B36]">
                                                    Aucun bien publié
                                                </p>

                                                <p class="mt-1 text-xs text-[#8190A0]">
                                                    Vous n'avez pas encore publié de bien.
                                                </p>

                                                <a
                                                    href="/immo-sn/member/publier"
                                                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#071B36] px-4 py-3 text-xs font-extrabold text-white transition hover:bg-[#142D4B]"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M12 5v14M5 12h14"/>
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

                <div class="rounded-[24px] border border-[#E9E3D9] bg-white p-5 shadow-[0_4px_18px_rgba(7,27,54,0.035)] sm:p-6">

                    <div class="mb-6">

                        <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#A98237]">
                            Raccourcis
                        </p>

                        <h2 class="mt-1.5 text-lg font-extrabold text-[#071B36]">
                            Actions rapides
                        </h2>

                        <p class="mt-1 text-xs leading-5 text-[#8190A0]">
                            Accédez rapidement aux fonctions principales.
                        </p>

                    </div>


                    <div class="space-y-3">


                        <!-- Publier -->

                        <a
                            href="/immo-sn/member/publier"
                            class="group flex items-center gap-4 rounded-2xl border border-[#E9E3D9] p-3.5 transition hover:border-[#D7B45E] hover:bg-[#FCF9F0]"
                        >

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#FBF3DE] transition group-hover:bg-[#F5E8C6]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-[#A98237]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 5v14M5 12h14"/>
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <p class="text-sm font-extrabold text-[#071B36]">
                                    Publier un bien
                                </p>

                                <p class="mt-1 text-xs text-[#8190A0]">
                                    Ajouter une annonce
                                </p>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="ml-auto h-4 w-4 shrink-0 text-[#A0ACB8] transition group-hover:translate-x-1 group-hover:text-[#A98237]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>


                        <!-- Mes biens -->

                        <a
                            href="/immo-sn/member/mes-biens"
                            class="group flex items-center gap-4 rounded-2xl border border-[#E9E3D9] p-3.5 transition hover:border-[#D7B45E] hover:bg-[#FCF9F0]"
                        >

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#FBF3DE] transition group-hover:bg-[#F5E8C6]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-[#A98237]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 10.5L12 3l9 7.5M5.5 9v11h13V9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 20v-6h6v6"/>
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <p class="text-sm font-extrabold text-[#071B36]">
                                    Mes biens
                                </p>

                                <p class="mt-1 text-xs text-[#8190A0]">
                                    Gérer mes annonces
                                </p>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="ml-auto h-4 w-4 shrink-0 text-[#A0ACB8] transition group-hover:translate-x-1 group-hover:text-[#A98237]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>


                        <!-- Favoris -->

                        <a
                            href="/immo-sn/member/favoris"
                            class="group flex items-center gap-4 rounded-2xl border border-[#E9E3D9] p-3.5 transition hover:border-[#D7B45E] hover:bg-[#FCF9F0]"
                        >

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#F1F2F4] transition group-hover:bg-[#E8EBEF]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-[#71869A]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25A5.25 5.25 0 017.5 3c1.387 0 2.64.576 3.5 1.501A5.25 5.25 0 0114.5 3a5.25 5.25 0 015.25 5.25c0 3.924-2.438 7.11-4.739 9.257a25.18 25.18 0 01-4.244 3.17 15.247 15.247 0 01-.383.218l-.022.012-.007.003-.003.001a.752.752 0 01-.707 0l-.003-.001Z"/>
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <p class="text-sm font-extrabold text-[#071B36]">
                                    Mes favoris
                                </p>

                                <p class="mt-1 text-xs text-[#8190A0]">
                                    Biens sauvegardés
                                </p>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="ml-auto h-4 w-4 shrink-0 text-[#A0ACB8] transition group-hover:translate-x-1 group-hover:text-[#A98237]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>


                        <!-- Messages -->

                        <a
                            href="/immo-sn/member/messages"
                            class="group flex items-center gap-4 rounded-2xl border border-[#E9E3D9] p-3.5 transition hover:border-[#D7B45E] hover:bg-[#FCF9F0]"
                        >

                            <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#FBF3DE] transition group-hover:bg-[#F5E8C6]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-[#A98237]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M4 5h16v11H8l-4 4V5Z"/>
                                </svg>

                                <?php if ($nombreMessagesNonLus > 0): ?>

                                    <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#D7A200] px-1 text-[9px] font-extrabold text-[#071B36]">
                                        <?= $nombreMessagesNonLus ?>
                                    </span>

                                <?php endif; ?>

                            </div>

                            <div class="min-w-0">

                                <p class="text-sm font-extrabold text-[#071B36]">
                                    Mes messages
                                </p>

                                <p class="mt-1 text-xs text-[#8190A0]">
                                    Consulter ma messagerie
                                </p>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="ml-auto h-4 w-4 shrink-0 text-[#A0ACB8] transition group-hover:translate-x-1 group-hover:text-[#A98237]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>


                        <!-- Profil -->

                        <a
                            href="/immo-sn/member/profil"
                            class="group flex items-center gap-4 rounded-2xl border border-[#E9E3D9] p-3.5 transition hover:border-[#D7B45E] hover:bg-[#FCF9F0]"
                        >

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#FBF3DE] transition group-hover:bg-[#F5E8C6]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-[#A98237]"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <circle cx="12" cy="8" r="3"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M5.5 20a6.5 6.5 0 0113 0"/>
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <p class="text-sm font-extrabold text-[#071B36]">
                                    Mon profil
                                </p>

                                <p class="mt-1 text-xs text-[#8190A0]">
                                    Gérer mon compte
                                </p>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="ml-auto h-4 w-4 shrink-0 text-[#A0ACB8] transition group-hover:translate-x-1 group-hover:text-[#A98237]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>

                    </div>

                </div>

            </section>

        </div>

    </main>

</div>
