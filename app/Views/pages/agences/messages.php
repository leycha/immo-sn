
<?php

use Core\Session;

/*
|--------------------------------------------------------------------------
| MESSAGERIE — ESPACE AGENCE IMMO-SN
|--------------------------------------------------------------------------
*/

$messages = is_array($messages ?? null) ? $messages : [];
$conversation = is_array($conversation ?? null) ? $conversation : null;
$selectedId = (string) ($selectedId ?? '');
$nombreMessagesNonLus = (int) ($nombreMessagesNonLus ?? 0);

$userId = (string) (Session::userId() ?? '');

$e = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$initiales = static function ($prenom, $nom = ''): string {
    $prenom = trim((string) $prenom);
    $nom = trim((string) $nom);

    $initiale1 = $prenom !== '' ? mb_substr($prenom, 0, 1) : '';
    $initiale2 = $nom !== '' ? mb_substr($nom, 0, 1) : '';

    $initiales = mb_strtoupper($initiale1 . $initiale2);

    return $initiales !== '' ? $initiales : 'U';
};

$formatDate = static function ($date): string {
    if (empty($date)) {
        return '';
    }

    try {
        return (new DateTime($date))->format('d/m/Y à H:i');
    } catch (Throwable $e) {
        return '';
    }
};

$historique = is_array($conversation['historique'] ?? null)
    ? $conversation['historique']
    : [];

$nomInterlocuteur = trim(
    ($conversation['interlocuteur_prenom'] ?? '') . ' ' .
    ($conversation['interlocuteur_nom'] ?? '')
);

$nomInterlocuteur = $nomInterlocuteur !== ''
    ? $nomInterlocuteur
    : 'Utilisateur';

$sujetConversation = $conversation['sujet'] ?? 'Discussion';

$bienTitre = $conversation['bien_titre'] ?? 'Bien immobilier';

$csrfToken = Session::csrfToken();

?>

<?php require_once BASE_PATH . '/app/Views/components/agence-sidebare.php'; ?>

<main class="min-h-screen bg-[#F3F6FA] lg:ml-[270px]">

    <div class="mx-auto max-w-[1600px] px-4 py-7 sm:px-6 lg:px-8">

        <!-- EN-TÊTE -->
        <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

            <div>
                <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-[#D7A200]">
                    <span class="h-2 w-2 rounded-full bg-[#F5B800]"></span>
                    ESPACE AGENCE
                </div>

                <h1 class="text-2xl font-extrabold tracking-tight text-[#071B36] sm:text-3xl">
                    Messagerie
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Échangez directement avec les personnes intéressées par vos biens.
                </p>
            </div>

            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#FFF5CC] text-[#9A7100]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 10h8M8 14h5m-8 6 3-3h9a4 4 0 0 0 4-4V7a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v13Z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium text-slate-500">
                        Messages non lus
                    </p>
                    <p class="text-xl font-extrabold text-[#071B36]">
                        <?= $nombreMessagesNonLus ?>
                    </p>
                </div>

            </div>

        </div>

        <!-- MESSAGERIE -->
        <div class="flex min-h-[680px] min-w-0 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:flex-row">

            <!-- LISTE DES CONVERSATIONS -->
            <aside class="flex w-full min-w-0 flex-col border-b border-slate-200 lg:w-[350px] lg:shrink-0 lg:border-b-0 lg:border-r">

                <div class="border-b border-slate-200 px-5 py-5">
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold text-[#071B36]">
                            Conversations
                        </h2>

                        <span class="rounded-full bg-[#F3F6FA] px-3 py-1 text-xs font-bold text-[#071B36]">
                            <?= count($messages) ?>
                        </span>
                    </div>

                    <p class="mt-1 text-xs text-slate-500">
                        Vos échanges avec les clients
                    </p>
                </div>

                <div class="max-h-[450px] flex-1 overflow-y-auto lg:max-h-none">

                    <?php if (empty($messages)): ?>

                        <div class="flex flex-col items-center px-6 py-16 text-center">

                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#F3F6FA] text-[#071B36]">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-8 w-8"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="1.5"
                                          d="M8 10h8M8 14h5m-8 6 3-3h9a4 4 0 0 0 4-4V7a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v13Z"/>
                                </svg>
                            </div>

                            <h3 class="font-bold text-[#071B36]">
                                Aucune conversation
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Les messages des personnes intéressées par vos biens apparaîtront ici.
                            </p>

                        </div>

                    <?php else: ?>

                        <?php foreach ($messages as $message): ?>

                            <?php
                            $messageId = (string) ($message['id'] ?? '');
                            $estSelectionne = $messageId === $selectedId;

                            $nom = trim(
                                ($message['interlocuteur_prenom'] ?? '') . ' ' .
                                ($message['interlocuteur_nom'] ?? '')
                            );

                            $nom = $nom !== '' ? $nom : 'Utilisateur';

                            $nonLus = (int) ($message['non_lus_total'] ?? 0);

                            $dateMessage = $formatDate(
                                $message['created_at'] ?? null
                            );

                            $extrait = trim(
                                (string) ($message['contenu'] ?? '')
                            );
                            ?>

                            <a
                                href="/immo-sn/agence/messages?id=<?= rawurlencode($messageId) ?>"
                                class="block border-b border-slate-100 px-5 py-4 transition hover:bg-[#F3F6FA] <?= $estSelectionne ? 'border-l-4 border-l-[#F5B800] bg-[#F3F6FA]' : '' ?>"
                            >

                                <div class="flex min-w-0 items-start gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#071B36] text-sm font-bold text-white">
                                        <?= $e($initiales(
                                            $message['interlocuteur_prenom'] ?? '',
                                            $message['interlocuteur_nom'] ?? ''
                                        )) ?>
                                    </div>

                                    <div class="min-w-0 flex-1">

                                        <div class="flex items-center justify-between gap-2">

                                            <h3 class="truncate text-sm font-bold text-[#071B36]">
                                                <?= $e($nom) ?>
                                            </h3>

                                            <?php if ($nonLus > 0): ?>
                                                <span class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-[#F5B800] px-1.5 text-[10px] font-extrabold text-[#071B36]">
                                                    <?= $nonLus ?>
                                                </span>
                                            <?php endif; ?>

                                        </div>

                                        <p class="mt-1 truncate text-xs font-semibold text-slate-600">
                                            <?= $e($message['bien_titre'] ?? 'Bien immobilier') ?>
                                        </p>

                                        <p class="mt-1 truncate text-xs text-slate-500">
                                            <?= $e($extrait) ?>
                                        </p>

                                        <p class="mt-2 text-[10px] text-slate-400">
                                            <?= $e($dateMessage) ?>
                                        </p>

                                    </div>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </aside>

            <!-- CONTENU DE LA CONVERSATION -->
            <section class="flex min-h-[550px] min-w-0 flex-1 flex-col">

                <?php if (!$conversation): ?>

                    <div class="flex flex-1 flex-col items-center justify-center px-6 py-16 text-center">

                        <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-3xl bg-[#F3F6FA] text-[#071B36]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-10 w-10"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="1.5"
                                      d="M8 10h8M8 14h5m-8 6 3-3h9a4 4 0 0 0 4-4V7a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v13Z"/>
                            </svg>
                        </div>

                        <h2 class="text-lg font-bold text-[#071B36]">
                            Votre messagerie
                        </h2>

                        <p class="mt-2 max-w-sm text-sm leading-6 text-slate-500">
                            Sélectionnez une conversation dans la liste pour consulter les messages et répondre à votre interlocuteur.
                        </p>

                    </div>

                <?php else: ?>

                    <!-- EN-TÊTE DE CONVERSATION -->
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4 sm:px-7">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#071B36] font-bold text-white">
                            <?= $e($initiales(
                                $conversation['interlocuteur_prenom'] ?? '',
                                $conversation['interlocuteur_nom'] ?? ''
                            )) ?>
                        </div>

                        <div class="min-w-0 flex-1">

                            <h2 class="truncate font-bold text-[#071B36]">
                                <?= $e($nomInterlocuteur) ?>
                            </h2>

                            <p class="mt-1 truncate text-xs text-slate-500">
                                <?= $e($sujetConversation) ?>
                            </p>

                        </div>

                        <span class="hidden rounded-lg bg-[#FFF5CC] px-3 py-2 text-xs font-bold text-[#8A6500] sm:inline-block">
                            Conversation
                        </span>

                    </div>

                    <!-- BIEN CONCERNÉ -->
                    <div class="border-b border-slate-100 bg-[#F8FAFC] px-5 py-3 sm:px-7">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Bien concerné
                        </p>

                        <p class="mt-1 break-words text-sm font-semibold text-[#071B36]">
                            <?= $e($bienTitre) ?>
                        </p>

                    </div>

                    <!-- HISTORIQUE -->
                    <div class="flex-1 space-y-5 overflow-y-auto bg-[#F8FAFC] px-4 py-6 sm:px-7">

                        <?php if (empty($historique)): ?>

                            <div class="py-10 text-center text-sm text-slate-500">
                                Aucun message dans cette conversation.
                            </div>

                        <?php else: ?>

                            <?php foreach ($historique as $ligne): ?>

                                <?php
                                $estMonMessage =
                                    (string) ($ligne['expediteur_id'] ?? '') === $userId;

                                $dateLigne = $formatDate(
                                    $ligne['created_at'] ?? null
                                );
                                ?>

                                <div class="flex <?= $estMonMessage ? 'justify-end' : 'justify-start' ?>">

                                    <div class="max-w-[88%] min-w-0 sm:max-w-[75%]">

                                        <div class="mb-1 px-1 text-[10px] font-semibold text-slate-400 <?= $estMonMessage ? 'text-right' : 'text-left' ?>">
                                            <?= $estMonMessage ? 'Vous' : $e($nomInterlocuteur) ?>
                                        </div>

                                        <div class="break-words rounded-2xl px-4 py-3 text-sm leading-6 shadow-sm <?= $estMonMessage
                                            ? 'rounded-br-sm bg-[#071B36] text-white'
                                            : 'rounded-bl-sm border border-slate-200 bg-white text-slate-700' ?>">

                                            <?= nl2br($e($ligne['contenu'] ?? '')) ?>

                                        </div>

                                        <p class="mt-1 px-1 text-[10px] text-slate-400 <?= $estMonMessage ? 'text-right' : 'text-left' ?>">
                                            <?= $e($dateLigne) ?>
                                        </p>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </div>

                    <!-- FORMULAIRE DE RÉPONSE -->
                    <div class="border-t border-slate-200 bg-white p-4 sm:p-6">

                        <form
                            action="/immo-sn/agence/messages/repondre"
                            method="POST"
                            class="space-y-3"
                        >

                            <input
                                type="hidden"
                                name="_csrf_token"
                                value="<?= $e($csrfToken) ?>"
                            >

                            <input
                                type="hidden"
                                name="message_id"
                                value="<?= $e($conversation['id'] ?? $selectedId) ?>"
                            >

                            <label
                                for="contenu"
                                class="block text-sm font-bold text-[#071B36]"
                            >
                                Votre réponse
                            </label>

                            <textarea
                                id="contenu"
                                name="contenu"
                                rows="3"
                                required
                                minlength="3"
                                maxlength="5000"
                                placeholder="Écrivez votre réponse ici..."
                                class="w-full resize-y rounded-xl border border-slate-200 bg-[#F8FAFC] px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/20"
                            ></textarea>

                            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                                <p class="text-xs text-slate-400">
                                    Maximum 5 000 caractères.
                                </p>

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#071B36] px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#0B2748] focus:outline-none focus:ring-2 focus:ring-[#F5B800] focus:ring-offset-2"
                                >

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-4 w-4"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m22 2-7 20-4-9-9-4 20-7ZM22 2 11 13"/>
                                    </svg>

                                    Envoyer la réponse

                                </button>

                            </div>

                        </form>

                    </div>

                <?php endif; ?>

            </section>

        </div>

    </div>

</main>
