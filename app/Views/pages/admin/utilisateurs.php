
<?php
/**
 * Vue : Gestion des utilisateurs — Administration IMMO-SN
 * Emplacement : app/Views/pages/admin/utilisateurs.php
 *
 * Variables fournies par AdminController :
 * $utilisateurs, $recherche, $roleSelectionne,
 * $statutSelectionne, $erreurUtilisateurs
 */

$utilisateurs = is_array($utilisateurs ?? null) ? $utilisateurs : [];
$recherche = (string) ($recherche ?? '');
$roleSelectionne = (string) ($roleSelectionne ?? '');
$statutSelectionne = (string) ($statutSelectionne ?? '');
$erreurUtilisateurs = (string) ($erreurUtilisateurs ?? '');

$e = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

$normaliser = static function ($value): string {
    $value = mb_strtolower(trim((string) ($value ?? '')), 'UTF-8');
    return str_replace([' ', '-'], '_', $value);
};

$roleLabel = static function ($role) use ($normaliser): string {
    $role = $normaliser($role);

    return [
        'admin' => 'Administrateur',
        'moderateur' => 'Modérateur',
        'agence' => 'Agence',
        'particulier' => 'Particulier',
    ][$role] ?? ucfirst(str_replace('_', ' ', $role ?: 'Inconnu'));
};

$roleBadge = static function ($role) use ($normaliser): string {
    return match ($normaliser($role)) {
        'admin' => 'bg-[#071B36] text-white',
        'moderateur' => 'bg-blue-50 text-blue-700 border border-blue-100',
        'agence' => 'bg-amber-50 text-amber-800 border border-amber-100',
        'particulier' => 'bg-slate-100 text-slate-700 border border-slate-200',
        default => 'bg-slate-100 text-slate-600',
    };
};

$statutLabel = static function ($statut) use ($normaliser): string {
    $statut = $normaliser($statut);

    return match ($statut) {
        'actif', 'active', '1' => 'Actif',
        'suspendu', 'suspendue', 'suspended' => 'Suspendu',
        'inactif', 'inactive', '0' => 'Inactif',
        'bloque', 'bloquee', 'blocked' => 'Bloqué',
        default => ucfirst(str_replace('_', ' ', $statut ?: 'Inconnu')),
    };
};

$statutBadge = static function ($statut) use ($normaliser): string {
    return match ($normaliser($statut)) {
        'actif', 'active', '1' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
        'suspendu', 'suspendue', 'suspended',
        'bloque', 'bloquee', 'blocked' => 'bg-red-50 text-red-700 border border-red-100',
        'inactif', 'inactive', '0' => 'bg-slate-100 text-slate-600 border border-slate-200',
        default => 'bg-slate-100 text-slate-600 border border-slate-200',
    };
};

$initiales = static function (array $utilisateur): string {
    $prenom = trim((string) ($utilisateur['prenom'] ?? ''));
    $nom = trim((string) ($utilisateur['nom'] ?? ''));

    $lettres = mb_substr($prenom, 0, 1, 'UTF-8')
        . mb_substr($nom, 0, 1, 'UTF-8');

    return mb_strtoupper($lettres !== '' ? $lettres : '?', 'UTF-8');
};

$idConnecte = (string) (\Core\Session::userId() ?? '');

$page = max(1, (int) ($page ?? 1));
$limite = max(1, (int) ($limite ?? 6));

$totalUtilisateurs = max(
    0,
    (int) ($totalUtilisateurs ?? count($utilisateurs))
);

$totalPages = max(
    1,
    (int) ($totalPages ?? ceil($totalUtilisateurs / $limite))
);

$debutUtilisateurs = $totalUtilisateurs > 0
    ? (($page - 1) * $limite) + 1
    : 0;

$finUtilisateurs = min($page * $limite, $totalUtilisateurs);

$paramsPagination = [];

if ($recherche !== '') {
    $paramsPagination['q'] = $recherche;
}

if ($roleSelectionne !== '') {
    $paramsPagination['role'] = $roleSelectionne;
}

if ($statutSelectionne !== '') {
    $paramsPagination['statut'] = $statutSelectionne;
}

$urlPage = static function (int $numeroPage) use ($paramsPagination): string {
    return '/immo-sn/admin/utilisateurs?' . http_build_query(
        array_merge($paramsPagination, ['page' => $numeroPage])
    );
};

$totalAdmins = max(0, (int) ($totalAdmins ?? 0));
$totalAgences = max(0, (int) ($totalAgences ?? 0));
$totalParticuliers = max(0, (int) ($totalParticuliers ?? 0));
$totalModerateurs = max(0, (int) ($totalModerateurs ?? 0));
$totalSuspendus = max(0, (int) ($totalSuspendus ?? 0));

$messageAdmin = trim((string) ($_GET['message'] ?? ''));
$typeMessageAdmin = (string) ($_GET['type'] ?? 'succes');
?>

<div class="min-h-screen bg-[#F4F6F9] text-[#071B36]">

    <!-- HEADER -->
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
        <div class="px-5 py-4 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-[#A98200]">
                        <span>Administration</span>
                        <span class="text-slate-300">/</span>
                        <span>Utilisateurs</span>
                    </div>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-[#071B36] sm:text-3xl">
                        Gestion des utilisateurs
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Gérez les comptes, les rôles et les accès de la plateforme.
                    </p>
                </div>

                <a href="/immo-sn/admin"
                   class="inline-flex w-fit items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#0E2A4E]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10.5 12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"/>
                    </svg>
                    Tableau de bord
                </a>
            </div>
        </div>
    </header>

    <main class="px-5 py-7 sm:px-8 lg:px-10">
        <div class="mx-auto max-w-[1600px]">

            <!-- MESSAGES -->
            <?php if ($messageAdmin !== ''): ?>
                <div id="message-admin"
                     role="alert"
                     class="mb-5 rounded-2xl border px-5 py-4 text-sm font-semibold
                     <?= $typeMessageAdmin === 'erreur'
                         ? 'border-red-200 bg-red-50 text-red-800'
                         : 'border-emerald-200 bg-emerald-50 text-emerald-800' ?>">
                    <?= $e($messageAdmin) ?>
                </div>
            <?php endif; ?>

            <?php if ($erreurUtilisateurs !== ''): ?>
                <div role="alert"
                     class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                    <?= $e($erreurUtilisateurs) ?>
                </div>
            <?php endif; ?>

            <!-- BANNIÈRE PRINCIPALE -->
            <section class="relative overflow-hidden rounded-[28px] bg-[#071B36] shadow-sm">
                <div class="absolute -right-20 -top-28 h-72 w-72 rounded-full bg-[#F5B800]/10 blur-3xl"></div>
                <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-blue-400/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-8 px-6 py-8 sm:px-9 lg:flex-row lg:items-center lg:justify-between lg:py-10">
                    <div class="max-w-2xl">
                        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>
                            <span class="text-xs font-medium text-slate-300">Centre de gestion des comptes</span>
                        </div>

                        <h2 class="text-3xl font-bold leading-tight text-white sm:text-4xl">
                            Votre communauté,
                            <span class="text-[#F5B800]">sous contrôle.</span>
                        </h2>

                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">
                            Consultez les utilisateurs inscrits, identifiez leurs rôles
                            et gérez leurs accès depuis votre espace d'administration.
                        </p>
                    </div>

                    <div class="shrink-0 rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm lg:min-w-[270px]">
                        <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-400">
                            Total des comptes
                        </p>

                        <p class="mt-3 text-4xl font-bold tabular-nums text-white">
                            <?= number_format($totalUtilisateurs, 0, ',', ' ') ?>
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Comptes enregistrés sur IMMO-SN
                        </p>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full w-full rounded-full bg-[#F5B800]"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- STATISTIQUES -->
            <section class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

                <!-- Total -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm7-3a4 4 0 010 8m3 5v-2a4 4 0 00-3-3.87"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500">Total des comptes</p>
                            <p class="mt-1 text-2xl font-bold text-[#071B36]"><?= $totalUtilisateurs ?></p>
                        </div>
                    </div>
                </div>

                <!-- Administrateurs -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 3l2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500">Administrateurs</p>
                            <p class="mt-1 text-2xl font-bold text-[#071B36]"><?= $totalAdmins ?></p>
                        </div>
                    </div>
                </div>

                <!-- Modérateurs -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 3l2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500">Modérateurs</p>
                            <p class="mt-1 text-2xl font-bold text-[#071B36]"><?= $totalModerateurs ?></p>
                        </div>
                    </div>
                </div>

                <!-- Particuliers -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500">Particuliers</p>
                            <p class="mt-1 text-2xl font-bold text-[#071B36]"><?= $totalParticuliers ?></p>
                        </div>
                    </div>
                </div>

                <!-- Suspendus -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M4.93 4.93l14.14 14.14"/>
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500">Suspendus</p>
                            <p class="mt-1 text-2xl font-bold text-[#071B36]"><?= $totalSuspendus ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTRES -->
            <section id="filtres-utilisateurs"
                     class="mt-7 scroll-mt-28 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#071B36] text-[#F5B800]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 5h16M7 12h10m-7 7h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.15em] text-[#A98200]">Recherche avancée</p>
                        <h2 class="mt-1 text-lg font-bold text-[#071B36]">Filtrer les utilisateurs</h2>
                    </div>
                </div>

                <form method="GET"
                      action="/immo-sn/admin/utilisateurs"
                      class="grid gap-4 xl:grid-cols-[minmax(220px,1fr)_220px_220px_auto_auto]">

                    <div>
                        <label for="q" class="mb-2 block text-xs font-semibold text-[#071B36]">Recherche</label>
                        <input id="q"
                               name="q"
                               type="search"
                               value="<?= $e($recherche) ?>"
                               placeholder="Nom, prénom, e-mail, téléphone..."
                               class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-[#071B36] outline-none transition placeholder:text-slate-400 focus:border-[#F5B800] focus:bg-white focus:ring-4 focus:ring-[#F5B800]/10">
                    </div>

                    <div>
                        <label for="role" class="mb-2 block text-xs font-semibold text-[#071B36]">Rôle</label>
                        <select id="role" name="role"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                            <option value="">Tous les rôles</option>
                            <option value="admin" <?= $roleSelectionne === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                            <option value="moderateur" <?= $roleSelectionne === 'moderateur' ? 'selected' : '' ?>>Modérateur</option>
                            <option value="agence" <?= $roleSelectionne === 'agence' ? 'selected' : '' ?>>Agence</option>
                            <option value="particulier" <?= $roleSelectionne === 'particulier' ? 'selected' : '' ?>>Particulier</option>
                        </select>
                    </div>

                    <div>
                        <label for="statut" class="mb-2 block text-xs font-semibold text-[#071B36]">Statut</label>
                        <select id="statut" name="statut"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                            <option value="">Tous les statuts</option>
                            <option value="actif" <?= $statutSelectionne === 'actif' ? 'selected' : '' ?>>Actif</option>
                            <option value="suspendu" <?= $statutSelectionne === 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
                            <option value="inactif" <?= $statutSelectionne === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                            <option value="bloque" <?= $statutSelectionne === 'bloque' ? 'selected' : '' ?>>Bloqué</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="h-12 w-full rounded-xl bg-[#071B36] px-5 text-sm font-bold text-white transition hover:bg-[#0E2A4E] xl:w-auto">
                            Rechercher
                        </button>
                    </div>

                    <div class="flex items-end">
                        <a href="/immo-sn/admin/utilisateurs"
                           class="inline-flex h-12 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-[#071B36] transition hover:bg-slate-50 xl:w-auto">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </section>

            <!-- LISTE DES UTILISATEURS -->
            <section class="mt-7">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.15em] text-[#A98200]">Comptes enregistrés</p>
                        <h2 class="mt-1 text-xl font-bold text-[#071B36]">Liste des utilisateurs</h2>
                    </div>

                    <p class="text-sm text-slate-500">
                        <span class="font-bold text-[#071B36]"><?= $totalUtilisateurs ?></span>
                        utilisateur(s)
                    </p>
                </div>

                <?php if ($utilisateurs): ?>

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[850px] border-collapse text-left">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th scope="col" class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Utilisateur
                                        </th>
                                        <th scope="col" class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Rôle
                                        </th>
                                        <th scope="col" class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Statut
                                        </th>
                                        <th scope="col" class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="tableau-utilisateurs" class="divide-y divide-slate-100">
                                    <?php foreach ($utilisateurs as $u): ?>
                                        <?php
                                        $id = (string) ($u['id'] ?? '');
                                        $prenom = trim((string) ($u['prenom'] ?? ''));
                                        $nom = trim((string) ($u['nom'] ?? ''));
                                        $nomComplet = trim($prenom . ' ' . $nom);
                                        $email = trim((string) ($u['email'] ?? ''));
                                        $telephone = (string) ($u['telephone'] ?? '');
                                        $ville = (string) ($u['ville'] ?? '');
                                        $role = (string) ($u['role'] ?? 'particulier');
                                        $statut = (string) ($u['status'] ?? 'inconnu');
                                        $estConnecte = $id !== '' && $id === $idConnecte;
                                        $estAdmin = $normaliser($role) === 'admin';
                                        $estVerifie = filter_var($u['is_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);
                                        ?>

                                        <tr class="ligne-utilisateur transition hover:bg-slate-50"
                                            data-nom="<?= $e($nomComplet) ?>"
                                            data-email="<?= $e($email) ?>"
                                            data-role="<?= $e($normaliser($role)) ?>"
                                            data-statut="<?= $e($normaliser($statut)) ?>">

                                            <!-- Utilisateur -->
                                            <td class="px-5 py-4">
                                                <div class="flex min-w-0 items-center gap-3">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#071B36] text-sm font-bold text-[#F5B800]">
                                                        <?= $e($initiales($u)) ?>
                                                    </div>

                                                    <div class="min-w-0">
                                                        <p class="truncate text-sm font-bold text-[#071B36]">
                                                            <?= $e($nomComplet ?: 'Nom non renseigné') ?>
                                                            <?php if ($estConnecte): ?>
                                                                <span class="ml-1 inline-flex rounded-md bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-800">
                                                                    Vous
                                                                </span>
                                                            <?php endif; ?>
                                                        </p>

                                                        <p class="max-w-[260px] break-all text-sm text-slate-500">
                                                            <?= $e($email ?: 'E-mail non renseigné') ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Rôle -->
                                            <td class="px-5 py-4">
                                                <span class="inline-flex whitespace-nowrap rounded-full px-3 py-1.5 text-xs font-bold <?= $e($roleBadge($role)) ?>">
                                                    <?= $e($roleLabel($role)) ?>
                                                </span>
                                            </td>

                                            <!-- Statut -->
                                            <td class="px-5 py-4">
                                                <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-full px-3 py-1.5 text-xs font-bold <?= $e($statutBadge($statut)) ?>">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                                    <?= $e($statutLabel($statut)) ?>
                                                </span>
                                            </td>

                                            <!-- Actions -->
                                            <td class="px-5 py-4">
                                                <?php if (!$estAdmin && !$estConnecte && $id !== ''): ?>
                                                    <div class="flex flex-nowrap items-center gap-2">

                                                        <!-- Modifier -->
                                                        <button type="button"
                                                            class="bouton-modifier inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-[#071B36] transition hover:border-[#F5B800] hover:bg-amber-50"
                                                            data-id="<?= $e($id) ?>"
                                                            data-nom="<?= $e($nom) ?>"
                                                            data-prenom="<?= $e($prenom) ?>"
                                                            data-email="<?= $e($email) ?>"
                                                            data-telephone="<?= $e($telephone) ?>"
                                                            data-ville="<?= $e($ville) ?>"
                                                            data-role="<?= $e($role) ?>"
                                                            data-statut="<?= $e($statut) ?>"
                                                            data-verifie="<?= $estVerifie ? '1' : '0' ?>">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="h-3.5 w-3.5"
                                                                 fill="none" viewBox="0 0 24 24"
                                                                 stroke="currentColor" stroke-width="1.8">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L9 17l-4 1 1-4 10.5-10.5z"/>
                                                            </svg>
                                                            Modifier
                                                        </button>

                                                        <!-- Supprimer -->
                                                        <form method="POST"
                                                              action="/immo-sn/admin/utilisateurs/<?= rawurlencode($id) ?>/supprimer"
                                                              class="form-supprimer-utilisateur shrink-0"
                                                              data-nom="<?= $e($nomComplet ?: $email) ?>">

                                                            <button type="submit"
                                                                    class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     class="h-3.5 w-3.5"
                                                                     fill="none" viewBox="0 0 24 24"
                                                                     stroke="currentColor" stroke-width="1.8">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                          d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/>
                                                                </svg>
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </div>

                                                <?php elseif ($estAdmin): ?>
                                                    <span class="text-xs text-slate-400">
                                                        Compte administrateur protégé
                                                    </span>

                                                <?php elseif ($estConnecte): ?>
                                                    <span class="text-xs text-slate-400">
                                                        Votre compte est protégé
                                                    </span>

                                                <?php else: ?>
                                                    <span class="text-xs text-slate-400">
                                                        Identifiant indisponible
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- PAGINATION SERVEUR -->
                        <div class="flex flex-col gap-4 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                            <p class="text-sm text-slate-500" aria-live="polite">
                                Affichage de
                                <strong class="text-[#071B36]"><?= $debutUtilisateurs ?></strong>
                                à
                                <strong class="text-[#071B36]"><?= $finUtilisateurs ?></strong>
                                sur
                                <strong class="text-[#071B36]"><?= number_format($totalUtilisateurs, 0, ',', ' ') ?></strong>
                                utilisateur(s)
                            </p>

                            <?php if ($totalPages > 1): ?>
                                <nav aria-label="Pagination des utilisateurs"
                                     class="flex flex-wrap items-center justify-center gap-2">

                                    <?php if ($page > 1): ?>
                                        <a href="<?= $e($urlPage($page - 1)) ?>"
                                           class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-[#071B36] transition hover:bg-slate-100">
                                            Précédente
                                        </a>
                                    <?php else: ?>
                                        <span class="cursor-not-allowed rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-400">
                                            Précédente
                                        </span>
                                    <?php endif; ?>

                                    <?php
                                    $premierePage = max(1, $page - 2);
                                    $dernierePage = min($totalPages, $page + 2);
                                    ?>

                                    <?php if ($premierePage > 1): ?>
                                        <a href="<?= $e($urlPage(1)) ?>"
                                           class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-[#071B36] hover:bg-slate-100">
                                            1
                                        </a>

                                        <?php if ($premierePage > 2): ?>
                                            <span class="px-1 text-slate-400">…</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($numero = $premierePage; $numero <= $dernierePage; $numero++): ?>
                                        <?php if ($numero === $page): ?>
                                            <span aria-current="page"
                                                  class="rounded-lg border border-[#071B36] bg-[#071B36] px-3 py-2 text-sm font-bold text-white">
                                                <?= $numero ?>
                                            </span>
                                        <?php else: ?>
                                            <a href="<?= $e($urlPage($numero)) ?>"
                                               class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-[#071B36] transition hover:bg-slate-100">
                                                <?= $numero ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    <?php if ($dernierePage < $totalPages): ?>
                                        <?php if ($dernierePage < $totalPages - 1): ?>
                                            <span class="px-1 text-slate-400">…</span>
                                        <?php endif; ?>

                                        <a href="<?= $e($urlPage($totalPages)) ?>"
                                           class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-[#071B36] hover:bg-slate-100">
                                            <?= $totalPages ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($page < $totalPages): ?>
                                        <a href="<?= $e($urlPage($page + 1)) ?>"
                                           class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-[#071B36] transition hover:bg-slate-100">
                                            Suivante
                                        </a>
                                    <?php else: ?>
                                        <span class="cursor-not-allowed rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-400">
                                            Suivante
                                        </span>
                                    <?php endif; ?>
                                </nav>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>

                        <!-- AUCUN UTILISATEUR -->
                        <div class="rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm7-3a4 4 0 010 8m3 5v-2a4 4 0 00-3-3.87"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-lg font-bold text-[#071B36]">
                                Aucun utilisateur trouvé
                            </h3>

                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                Aucun compte ne correspond aux critères de recherche sélectionnés.
                                Essayez de modifier vos filtres ou de réinitialiser la recherche.
                            </p>

                            <a href="/immo-sn/admin/utilisateurs"
                               class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#071B36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0E2A4E]">
                                Réinitialiser les filtres
                            </a>
                        </div>

                    <?php endif; ?>
            </section>

        </div>
    </main>
</div>

<!-- MODAL DE MODIFICATION -->
<div id="modal-modifier-utilisateur"
     class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-[#071B36]/60 p-4 backdrop-blur-sm"
     aria-hidden="true">

    <div class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
         role="dialog"
         aria-modal="true"
         aria-labelledby="titre-modal-utilisateur">

        <!-- En-tête modal -->
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 bg-slate-50 px-6 py-5 sm:px-7">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.15em] text-[#A98200]">Administration</p>
                <h2 id="titre-modal-utilisateur" class="mt-1 text-xl font-bold text-[#071B36]">
                    Modifier l’utilisateur
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Modifiez les informations du compte sélectionné.
                </p>
            </div>

            <button type="button" id="fermer-modal-utilisateur"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100"
                    aria-label="Fermer la fenêtre">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>

        <!-- Formulaire modal -->
        <form id="form-modifier-utilisateur" method="POST">
            <div class="max-h-[65vh] space-y-5 overflow-y-auto px-6 py-6 sm:px-7">

                <input type="hidden" id="edit-id" name="id">

                <div id="erreur-modification-utilisateur"
                     class="hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                     role="alert"></div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="edit-prenom" class="mb-2 block text-xs font-semibold text-[#071B36]">Prénom</label>
                        <input id="edit-prenom" name="prenom" type="text" required maxlength="100"
                               class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                    </div>

                    <div>
                        <label for="edit-nom" class="mb-2 block text-xs font-semibold text-[#071B36]">Nom</label>
                        <input id="edit-nom" name="nom" type="text" required maxlength="100"
                               class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                    </div>
                </div>

                <div>
                    <label for="edit-email" class="mb-2 block text-xs font-semibold text-[#071B36]">Adresse e-mail</label>
                    <input id="edit-email" name="email" type="email" required maxlength="255"
                           class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="edit-telephone" class="mb-2 block text-xs font-semibold text-[#071B36]">Téléphone</label>
                        <input id="edit-telephone" name="telephone" type="tel" maxlength="30"
                               class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                    </div>

                    <div>
                        <label for="edit-ville" class="mb-2 block text-xs font-semibold text-[#071B36]">Ville</label>
                        <input id="edit-ville" name="ville" type="text" maxlength="150"
                               class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="edit-role" class="mb-2 block text-xs font-semibold text-[#071B36]">Rôle</label>
                        <select id="edit-role" name="role" required
                                class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                            <option value="particulier">Particulier</option>
                            <option value="agence">Agence</option>
                            <option value="moderateur">Modérateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit-statut" class="mb-2 block text-xs font-semibold text-[#071B36]">Statut</label>
                        <select id="edit-statut" name="status" required
                                class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-[#071B36] outline-none focus:border-[#F5B800] focus:ring-4 focus:ring-[#F5B800]/10">
                            <option value="actif">Actif</option>
                            <option value="suspendu">Suspendu</option>
                            <option value="inactif">Inactif</option>
                            <option value="bloque">Bloqué</option>
                        </select>
                    </div>
                </div>

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <input id="edit-verifie" name="is_verified" type="checkbox" value="1"
                           class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-[#071B36]">
                    <span>
                        <span class="block text-sm font-semibold text-[#071B36]">Adresse e-mail vérifiée</span>
                        <span class="mt-1 block text-xs leading-5 text-slate-500">
                            Indique si l’adresse e-mail de cet utilisateur est vérifiée.
                        </span>
                    </span>
                </label>

                <p class="text-xs leading-5 text-slate-400">
                    La modification du mot de passe n’est pas incluse dans ce formulaire.
                </p>
            </div>

            <!-- Actions modal -->
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-7">
                <button type="button" id="annuler-modal-utilisateur"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-[#071B36] transition hover:bg-slate-100">
                    Annuler
                </button>

                <button type="submit" id="enregistrer-utilisateur"
                        class="rounded-xl bg-[#071B36] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0E2A4E] disabled:cursor-wait disabled:opacity-60">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Message de confirmation
    const message = document.getElementById('message-admin');

    if (message) {
        setTimeout(function () {
            message.style.opacity = '0';

            setTimeout(function () {
                message.remove();

                const url = new URL(window.location.href);
                url.searchParams.delete('message');
                url.searchParams.delete('type');

                window.history.replaceState({}, document.title, url.pathname + url.search + url.hash);
            }, 500);
        }, 4000);
    }

    // Modal de modification
    const modal = document.getElementById('modal-modifier-utilisateur');
    const formulaire = document.getElementById('form-modifier-utilisateur');
    const erreur = document.getElementById('erreur-modification-utilisateur');
    const boutonEnregistrer = document.getElementById('enregistrer-utilisateur');

    function fermerModal() {
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
    }

    function ouvrirModal(bouton) {
        if (!modal || !formulaire) return;

        const id = bouton.dataset.id || '';

        formulaire.action = '/immo-sn/admin/utilisateurs/' + encodeURIComponent(id) + '/modifier';

        document.getElementById('edit-id').value = id;
        document.getElementById('edit-prenom').value = bouton.dataset.prenom || '';
        document.getElementById('edit-nom').value = bouton.dataset.nom || '';
        document.getElementById('edit-email').value = bouton.dataset.email || '';
        document.getElementById('edit-telephone').value = bouton.dataset.telephone || '';
        document.getElementById('edit-ville').value = bouton.dataset.ville || '';
        document.getElementById('edit-role').value = bouton.dataset.role || 'particulier';
        document.getElementById('edit-statut').value = bouton.dataset.statut || 'actif';
        document.getElementById('edit-verifie').checked = bouton.dataset.verifie === '1';

        erreur.textContent = '';
        erreur.classList.add('hidden');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');

        document.getElementById('edit-prenom').focus();
    }

    document.querySelectorAll('.bouton-modifier:not(:disabled)').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            ouvrirModal(bouton);
        });
    });

    document.getElementById('fermer-modal-utilisateur')?.addEventListener('click', fermerModal);
    document.getElementById('annuler-modal-utilisateur')?.addEventListener('click', fermerModal);

    modal?.addEventListener('click', function (event) {
        if (event.target === modal) fermerModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            fermerModal();
        }
    });

    // Envoi AJAX du formulaire de modification
    formulaire?.addEventListener('submit', async function (event) {
        event.preventDefault();

        erreur.textContent = '';
        erreur.classList.add('hidden');

        boutonEnregistrer.disabled = true;
        boutonEnregistrer.textContent = 'Enregistrement…';

        try {
            const response = await fetch(formulaire.action, {
                method: 'POST',
                body: new FormData(formulaire),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            const contentType = response.headers.get('content-type') || '';

            if (!contentType.includes('application/json')) {
                throw new Error(
                    'Le serveur ne renvoie pas encore de réponse JSON. Le contrôleur doit être adapté avant d’utiliser la modification.'
                );
            }

            const resultat = await response.json();

            if (!response.ok || resultat.success !== true) {
                throw new Error(resultat.message || 'La modification a échoué.');
            }

            window.location.reload();

        } catch (exception) {
            erreur.textContent = exception.message || 'Une erreur est survenue.';
            erreur.classList.remove('hidden');

        } finally {
            boutonEnregistrer.disabled = false;
            boutonEnregistrer.textContent = 'Enregistrer les modifications';
        }
    });

    // Confirmation avant suppression
    document.querySelectorAll('.form-supprimer-utilisateur').forEach(function (formulaireSuppression) {
        formulaireSuppression.addEventListener('submit', function (event) {
            const nom = formulaireSuppression.dataset.nom || 'cet utilisateur';

            const confirme = window.confirm(
                'ATTENTION : vous allez supprimer le compte de ' + nom + '.\n\n' +
                'Cette opération peut également supprimer des biens et d’autres données associées.\n\n' +
                'Cette action est irréversible. Voulez-vous vraiment continuer ?'
            );

            if (!confirme) {
                event.preventDefault();
            }
        });
    });
});
</script>
