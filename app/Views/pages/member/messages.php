
<?php

/**
 * ============================================================
 * VUE : MESSAGERIE MEMBRE — IMMO-SN
 * ============================================================
 *
 * Variables transmises par MemberController :
 * $messages
 * $conversation
 * $selectedId
 *
 */

$messages = is_array($messages ?? null) ? $messages : [];

$conversation = is_array($conversation ?? null)
    ? $conversation
    : null;

$selectedId = (string) ($selectedId ?? '');

$historique = is_array($conversation['historique'] ?? null)
    ? $conversation['historique']
    : [];

$userId = \Core\Session::userId();

$success = \Core\Session::getFlash('message_success');
$error = \Core\Session::getFlash('message_error');

/**
 * Protection et échappement HTML.
 */
$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

/**
 * Formatage des dates.
 */
if (!function_exists('formatDateMessage')) {
    function formatDateMessage(?string $date): string
    {
        if (empty($date)) {
            return '';
        }

        try {
            return (new DateTime($date))->format('d/m/Y à H:i');
        } catch (Throwable $e) {
            return $date;
        }
    }
}

/**
 * Initiales d'un utilisateur.
 */
if (!function_exists('initialesUtilisateur')) {
    function initialesUtilisateur(
        ?string $prenom,
        ?string $nom
    ): string {
        $prenom = trim((string) $prenom);
        $nom = trim((string) $nom);

        $initiales = '';

        if ($prenom !== '') {
            $initiales .= mb_substr($prenom, 0, 1);
        }

        if ($nom !== '') {
            $initiales .= mb_substr($nom, 0, 1);
        }

        return mb_strtoupper($initiales !== '' ? $initiales : 'U');
    }
}

/**
 * Informations de l'interlocuteur.
 */
$prenomActive = trim(
    (string) ($conversation['interlocuteur_prenom'] ?? '')
);

$nomActive = trim(
    (string) ($conversation['interlocuteur_nom'] ?? '')
);

$nomActiveComplet = trim($prenomActive . ' ' . $nomActive);

if ($nomActiveComplet === '') {
    $nomActiveComplet = 'Utilisateur';
}

$initialesActive = initialesUtilisateur(
    $prenomActive,
    $nomActive
);

/**
 * Identification du message servant à répondre.
 */
$messageId = (string) ($conversation['id'] ?? $selectedId);

?>

<div class="min-h-screen bg-[#F5F3ED]">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <header class="sticky top-0 z-30 border-b border-[#E7E0D2] bg-white/95 shadow-sm backdrop-blur-xl">

        <div class="flex min-h-[82px] flex-col justify-center px-4 py-4 sm:px-6 lg:px-8">

            <div class="mx-auto w-full max-w-7xl">

                <div class="mb-1.5 flex items-center gap-2 text-xs font-semibold">

                    <span class="text-[#8A6500]">
                        Espace membre
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-3.5 w-3.5 text-[#A5A5A0]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m9 18 6-6-6-6"/>

                    </svg>

                    <span class="text-[#718096]">
                        Messagerie
                    </span>

                </div>

                <div class="flex flex-wrap items-center justify-between gap-3">

                    <div>

                        <h1 class="text-xl font-extrabold tracking-tight text-[#071B36] sm:text-2xl">
                            Messagerie
                        </h1>

                        <p class="mt-1 text-xs text-[#718096] sm:text-sm">
                            Retrouvez et gérez tous vos échanges immobiliers.
                        </p>

                    </div>

                    <div class="hidden items-center gap-2 rounded-xl border border-[#E9E1D2] bg-[#FAF8F3] px-3 py-2 sm:flex">

                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#071B36] text-[#F5B800]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"/>

                            </svg>

                        </span>

                        <div>

                            <p class="text-[10px] font-semibold uppercase tracking-wider text-[#8A6500]">
                                IMMO-SN
                            </p>

                            <p class="text-xs font-bold text-[#071B36]">
                                Centre de messages
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </header>


    <!-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== -->

    <main class="px-3 py-5 sm:px-5 md:px-6 md:py-7">

        <div class="mx-auto max-w-7xl">

            <!-- NOTIFICATIONS -->

            <?php if (!empty($success)): ?>

                <div class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mt-0.5 h-5 w-5 shrink-0"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>

                    </svg>

                    <p><?= $e($success) ?></p>

                </div>

            <?php endif; ?>


            <?php if (!empty($error)): ?>

                <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mt-0.5 h-5 w-5 shrink-0"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4m0 4h.01M10.29 3.86 2.82 17a2 2 0 0 0 1.73 3h14.9a2 2 0 0 0 1.73-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>

                    </svg>

                    <p><?= $e($error) ?></p>

                </div>

            <?php endif; ?>


            <div class="flex min-h-[680px] min-w-0 flex-col overflow-hidden rounded-2xl border border-[#E7E0D2] bg-white shadow-[0_12px_45px_rgba(7,27,54,0.06)] lg:flex-row">


                <!-- =================================================
                     COLONNE GAUCHE : CONVERSATIONS
                ================================================== -->

                <aside class="w-full min-w-0 shrink-0 border-b border-[#E7E0D2] bg-white lg:w-[350px] lg:border-b-0 lg:border-r">

                    <div class="border-b border-[#EEE9DF] px-5 py-5">

                        <div class="flex items-center justify-between gap-3">

                            <div>

                                <div class="flex items-center gap-2">

                                    <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>

                                    <h2 class="text-sm font-extrabold text-[#071B36]">
                                        Mes conversations
                                    </h2>

                                </div>

                                <p class="mt-1.5 text-xs text-[#8490A0]">
                                    Consultez vos échanges récents
                                </p>

                            </div>

                            <?php if (!empty($messages)): ?>

                                <span class="flex h-8 min-w-8 items-center justify-center rounded-xl bg-[#071B36] px-2.5 text-xs font-extrabold text-[#F5B800] shadow-sm">

                                    <?= count($messages) ?>

                                </span>

                            <?php endif; ?>

                        </div>

                        <!-- RECHERCHE -->

                        <div class="relative mt-5">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#8A6500]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z"/>

                            </svg>

                            <input
                                type="text"
                                id="rechercheMessages"
                                placeholder="Rechercher une conversation..."
                                class="h-11 w-full rounded-xl border border-[#E8E3D9] bg-[#FAF9F6] pl-10 pr-3 text-xs text-[#071B36] outline-none transition placeholder:text-[#9AA3AD] focus:border-[#D7A200] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                            >

                        </div>

                    </div>


                    <!-- LISTE -->

                    <div id="listeConversations"
                         class="max-h-[600px] divide-y divide-[#F0ECE4] overflow-y-auto">

                        <?php if (!empty($messages)): ?>

                            <?php foreach ($messages as $message): ?>

                                <?php

                                $prenom = trim(
                                    (string) ($message['interlocuteur_prenom'] ?? '')
                                );

                                $nom = trim(
                                    (string) ($message['interlocuteur_nom'] ?? '')
                                );

                                $nomComplet = trim($prenom . ' ' . $nom);

                                if ($nomComplet === '') {
                                    $nomComplet = 'Utilisateur';
                                }

                                $initiales = initialesUtilisateur(
                                    $prenom,
                                    $nom
                                );

                                $messageItemId = (string) ($message['id'] ?? '');

                                $active = $messageItemId === $selectedId;

                                $nonLus = (int) ($message['non_lus_total'] ?? 0);

                                $dateMessage = formatDateMessage(
                                    $message['created_at'] ?? null
                                );

                                $urlConversation = '/immo-sn/member/messages?id=' .
                                    rawurlencode($messageItemId);

                                ?>

                                <a
                                    href="<?= $e($urlConversation) ?>"
                                    data-conversation-search="<?= $e(
                                        $nomComplet . ' ' .
                                        ($message['sujet'] ?? '') . ' ' .
                                        ($message['bien_titre'] ?? '') . ' ' .
                                        ($message['contenu'] ?? '')
                                    ) ?>"
                                    class="group relative flex w-full items-center gap-3.5 px-4 py-4 text-left transition duration-200
                                    <?= $active
                                        ? 'border-l-[3px] border-l-[#F5B800] bg-[#F8F6EF]'
                                        : 'border-l-[3px] border-l-transparent bg-white hover:bg-[#FAF9F6]' ?>"
                                >

                                    <!-- AVATAR -->

                                    <div class="relative shrink-0">

                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl text-xs font-extrabold shadow-sm
                                            <?= $active
                                                ? 'bg-[#071B36] text-[#F5B800]'
                                                : 'border border-[#E9E1D2] bg-[#F5F1E8] text-[#8A6500]' ?>">

                                            <?= $e($initiales) ?>

                                        </div>

                                        <?php if ($nonLus > 0): ?>

                                            <span class="absolute -bottom-1 -right-1 h-3.5 w-3.5 rounded-full border-[2.5px] border-white bg-[#D7A200]"
                                                  title="Message non lu"></span>

                                        <?php endif; ?>

                                    </div>


                                    <!-- INFORMATIONS -->

                                    <div class="min-w-0 flex-1">

                                        <div class="flex items-center justify-between gap-2">

                                            <p class="truncate text-[13px] <?= $nonLus > 0 ? 'font-extrabold' : 'font-semibold' ?> text-[#071B36]">

                                                <?= $e($nomComplet) ?>

                                            </p>

                                            <span class="shrink-0 text-[9px] font-medium text-[#929BA6]">

                                                <?= $e($dateMessage) ?>

                                            </span>

                                        </div>

                                        <p class="mt-1.5 truncate text-xs font-bold text-[#8A6500]">

                                            <?= $e($message['sujet'] ?? 'Message') ?>

                                        </p>

                                        <p class="mt-1 truncate text-xs leading-5 text-[#8290A0]">

                                            <?= $e($message['contenu'] ?? '') ?>

                                        </p>

                                        <?php if (!empty($message['bien_titre'])): ?>

                                            <p class="mt-1.5 truncate text-[10px] font-medium text-[#718096]">

                                                <span class="font-bold text-[#8A6500]">
                                                    Bien :
                                                </span>

                                                <?= $e($message['bien_titre']) ?>

                                            </p>

                                        <?php endif; ?>

                                    </div>


                                    <?php if ($nonLus > 0): ?>

                                        <span class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-[#D7A200] px-1 text-[9px] font-extrabold text-[#071B36]">

                                            <?= $nonLus > 99 ? '99+' : $nonLus ?>

                                        </span>

                                    <?php endif; ?>

                                </a>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <!-- AUCUN MESSAGE -->

                            <div class="px-5 py-16 text-center">

                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-[#E9E1D2] bg-[#F8F6EF]">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-7 w-7 text-[#8A6500]"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.6">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"/>

                                    </svg>

                                </div>

                                <p class="mt-4 text-sm font-extrabold text-[#071B36]">
                                    Aucun message
                                </p>

                                <p class="mx-auto mt-2 max-w-[220px] text-xs leading-5 text-[#8490A0]">
                                    Les messages concernant vos biens apparaîtront ici.
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>

                </aside>


                <!-- =================================================
                     COLONNE DROITE : CONVERSATION
                ================================================== -->

                <section class="flex min-w-0 flex-1 flex-col bg-[#F7F6F2]">

                    <?php if ($conversation): ?>

                        <!-- EN-TÊTE -->

                        <div class="flex items-center justify-between border-b border-[#E9E3D8] bg-white px-5 py-4 md:px-7">

                            <div class="flex min-w-0 items-center gap-3.5">

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#071B36] text-xs font-extrabold text-[#F5B800] shadow-sm">

                                    <?= $e($initialesActive) ?>

                                </div>

                                <div class="min-w-0">

                                    <h2 class="break-words text-sm font-extrabold text-[#071B36]">

                                        <?= $e($nomActiveComplet) ?>

                                    </h2>

                                    <?php if (!empty($conversation['bien_titre'])): ?>

                                        <p class="mt-1 break-words text-[11px] text-[#8290A0]">

                                            <span class="font-semibold text-[#8A6500]">
                                                Concernant :
                                            </span>

                                            <?= $e($conversation['bien_titre']) ?>

                                        </p>

                                    <?php else: ?>

                                        <p class="mt-1 text-[11px] text-[#8290A0]">
                                            Conversation privée
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>

                            <div class="hidden items-center gap-2 rounded-xl border border-[#E9E1D2] bg-[#FAF8F3] px-3 py-2 sm:flex">

                                <span class="h-2 w-2 rounded-full bg-[#D7A200]"></span>

                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#8A6500]">
                                    Conversation
                                </span>

                            </div>

                        </div>


                        <!-- HISTORIQUE COMPLET -->

                        <div class="flex-1 overflow-y-auto px-4 py-8 sm:px-6 md:px-8">

                            <div class="mx-auto max-w-3xl">

                                <!-- OBJET -->

                                <?php if (!empty($conversation['sujet'])): ?>

                                    <div class="mb-8 text-center">

                                        <div class="mb-3 flex items-center justify-center gap-3">

                                            <span class="h-px w-8 bg-[#D9D0BD]"></span>

                                            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-[#9A8C70]">
                                                Objet de la conversation
                                            </span>

                                            <span class="h-px w-8 bg-[#D9D0BD]"></span>

                                        </div>

                                        <span class="inline-flex max-w-full items-center rounded-xl border border-[#E9E1D2] bg-white px-4 py-2.5 text-xs font-bold text-[#8A6500] shadow-sm">

                                            <?= $e($conversation['sujet']) ?>

                                        </span>

                                    </div>

                                <?php endif; ?>


                                <!-- MESSAGES -->

                                <div class="space-y-6">

                                    <?php foreach ($historique as $historiqueMessage): ?>

                                        <?php

                                        $expediteurId = (string) (
                                            $historiqueMessage['expediteur_id'] ?? ''
                                        );

                                        $estMoi = $userId !== null
                                            && $expediteurId === (string) $userId;

                                        $prenomExpediteur = trim(
                                            (string) (
                                                $historiqueMessage['expediteur_prenom'] ?? ''
                                            )
                                        );

                                        $nomExpediteur = trim(
                                            (string) (
                                                $historiqueMessage['expediteur_nom'] ?? ''
                                            )
                                        );

                                        $nomExpediteurComplet = trim(
                                            $prenomExpediteur . ' ' . $nomExpediteur
                                        );

                                        if ($estMoi) {
                                            $nomExpediteurComplet = 'Vous';
                                        } elseif ($nomExpediteurComplet === '') {
                                            $nomExpediteurComplet = $nomActiveComplet;
                                        }

                                        $initialesExpediteur = $estMoi
                                            ? 'MO'
                                            : initialesUtilisateur(
                                                $prenomExpediteur,
                                                $nomExpediteur
                                            );

                                        ?>

                                        <div class="flex items-start gap-3.5 <?= $estMoi ? 'flex-row-reverse' : '' ?>">

                                            <!-- AVATAR -->

                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border text-[10px] font-extrabold shadow-sm
                                                <?= $estMoi
                                                    ? 'border-[#071B36] bg-[#071B36] text-[#F5B800]'
                                                    : 'border-[#E9E1D2] bg-white text-[#8A6500]' ?>">

                                                <?= $e($initialesExpediteur) ?>

                                            </div>


                                            <!-- BULLE -->

                                            <div class="min-w-0 max-w-[92%] sm:max-w-[80%]">

                                                <div class="mb-2 flex flex-wrap items-center gap-2.5 <?= $estMoi ? 'justify-end' : '' ?>">

                                                    <p class="text-xs font-extrabold text-[#071B36]">

                                                        <?= $e($nomExpediteurComplet) ?>

                                                    </p>

                                                    <span class="text-[10px] text-[#929BA6]">

                                                        <?= $e(
                                                            formatDateMessage(
                                                                $historiqueMessage['created_at'] ?? null
                                                            )
                                                        ) ?>

                                                    </span>

                                                </div>


                                                <div class="min-w-0 rounded-2xl px-4 py-4 text-sm leading-7 shadow-[0_5px_20px_rgba(7,27,54,0.04)] [overflow-wrap:anywhere]
                                                    <?= $estMoi
                                                        ? 'rounded-tr-md bg-[#071B36] text-white'
                                                        : 'rounded-tl-md border border-[#E9E3D8] bg-white text-[#354357]' ?>">

                                                    <?= nl2br(
                                                        $e($historiqueMessage['contenu'] ?? '')
                                                    ) ?>

                                                </div>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>


                                    <?php if (empty($historique)): ?>

                                        <div class="rounded-xl border border-[#E9E1D2] bg-white p-6 text-center">

                                            <p class="text-sm text-[#718096]">
                                                Aucun message dans cette conversation.
                                            </p>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             FORMULAIRE DE RÉPONSE
                        ================================================== -->

                        <div class="border-t border-[#E9E3D8] bg-white p-4 md:p-5">

                            <div class="mx-auto max-w-3xl">

                                <div class="mb-4 flex items-center gap-2">

                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#071B36] text-[#F5B800]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M3 10l9-7 9 7v10a1 1 0 0 1-1 1h-6v-7h-4v7H4a1 1 0 0 1-1-1V10Z"/>

                                        </svg>

                                    </span>

                                    <div>

                                        <h3 class="text-sm font-extrabold text-[#071B36]">
                                            Répondre à <?= $e($nomActiveComplet) ?>
                                        </h3>

                                        <p class="text-[10px] text-[#8490A0]">
                                            Votre réponse sera ajoutée à cette conversation.
                                        </p>

                                    </div>

                                </div>


                                <form
                                    action="/immo-sn/member/messages/repondre"
                                    method="POST"
                                    class="space-y-3"
                                >

                                    <input
                                        type="hidden"
                                        name="_csrf_token"
                                        value="<?= $e(\Core\Session::csrfToken()) ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="message_id"
                                        value="<?= $e($messageId) ?>"
                                    >


                                    <textarea
                                        name="contenu"
                                        rows="4"
                                        minlength="3"
                                        maxlength="5000"
                                        required
                                        placeholder="Écrivez votre réponse ici..."
                                        class="w-full resize-y rounded-xl border border-[#E8E3D9] bg-[#FAF9F6] px-4 py-3 text-sm leading-6 text-[#071B36] outline-none transition placeholder:text-[#9AA3AD] focus:border-[#D7A200] focus:bg-white focus:ring-2 focus:ring-[#F5B800]/15"
                                    ></textarea>


                                    <div class="flex flex-wrap items-center justify-between gap-3">

                                        <p class="text-[10px] text-[#929BA6]">
                                            Entre 3 et 5 000 caractères.
                                        </p>

                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#071B36] px-5 py-3 text-xs font-bold text-white shadow-md transition hover:bg-[#102D50] focus:outline-none focus:ring-2 focus:ring-[#F5B800] focus:ring-offset-2"
                                        >

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4 text-[#F5B800]"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="m22 2-7 20-4-9-9-4 20-7ZM22 2 11 13"/>

                                            </svg>

                                            Envoyer la réponse

                                        </button>

                                    </div>

                                </form>


                                <p class="mt-4 text-center text-[9px] font-medium tracking-wide text-[#A0A5AA]">
                                    IMMO-SN · Votre espace de communication immobilière
                                </p>

                            </div>

                        </div>


                    <?php else: ?>


                        <!-- =================================================
                             AUCUNE CONVERSATION SÉLECTIONNÉE
                        ================================================== -->

                        <div class="flex flex-1 items-center justify-center px-5 py-14 text-center sm:px-8">

                            <div class="max-w-sm">

                                <div class="relative mx-auto flex h-24 w-24 items-center justify-center rounded-[28px] border border-[#E9E1D2] bg-white shadow-sm">

                                    <div class="absolute inset-2 rounded-[21px] border border-dashed border-[#DCCFAF]"></div>

                                    <div class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-[#071B36] text-[#F5B800]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-7 w-7"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"/>

                                        </svg>

                                    </div>

                                </div>

                                <div class="mt-7">

                                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#A18442]">
                                        Centre de communication
                                    </span>

                                    <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-[#071B36]">
                                        Votre messagerie
                                    </h2>

                                    <p class="mt-3 text-sm leading-7 text-[#8290A0]">

                                        <?php if (!empty($messages)): ?>

                                            Sélectionnez une conversation dans la liste pour consulter les échanges et répondre à votre interlocuteur.

                                        <?php else: ?>

                                            Vous n'avez pas encore reçu de message. Les échanges concernant vos biens apparaîtront automatiquement dans cet espace.

                                        <?php endif; ?>

                                    </p>

                                </div>

                                <div class="mx-auto mt-7 flex max-w-[250px] items-center justify-center gap-2 rounded-xl border border-[#E9E1D2] bg-white px-4 py-3">

                                    <span class="h-2 w-2 rounded-full bg-[#D7A200]"></span>

                                    <span class="text-[10px] font-semibold text-[#718096]">
                                        Votre espace est prêt
                                    </span>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                </section>

            </div>

        </div>

    </main>

</div>


<!-- =========================================================
     RECHERCHE DANS LES CONVERSATIONS
========================================================== -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const recherche = document.getElementById('rechercheMessages');

    const conversations = document.querySelectorAll(
        '[data-conversation-search]'
    );

    if (!recherche) {
        return;
    }

    recherche.addEventListener('input', function () {

        const terme = this.value
            .toLowerCase()
            .trim();

        conversations.forEach(function (conversation) {

            const texte = (
                conversation.dataset.conversationSearch || ''
            ).toLowerCase();

            conversation.style.display = texte.includes(terme)
                ? ''
                : 'none';

        });

    });

});
</script>
