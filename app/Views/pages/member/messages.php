<?php
/**
 * Vue : Messagerie membre
 *
 * Variables disponibles :
 * $messages
 */

$messages = is_array($messages ?? []) ? $messages : [];

$conversationActive = $messages[0] ?? null;

/**
 * Formatage de la date.
 */
function formatDateMessage(?string $date): string
{
    if (empty($date)) {
        return '';
    }

    try {
        $dateObj = new DateTime($date);
        return $dateObj->format('d/m/Y à H:i');
    } catch (Throwable $e) {
        return $date;
    }
}

/**
 * Initiales d'un utilisateur.
 */
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

    if ($initiales === '') {
        $initiales = 'U';
    }

    return mb_strtoupper($initiales);
}
?>

<div class="min-h-screen bg-[#F5F3ED]">

    <!-- =========================================================
         HEADER
    ========================================================== -->
    <header class="sticky top-0 z-20 border-b border-[#E5E2D8] bg-white/95 backdrop-blur">

        <div class="flex min-h-[76px] flex-col items-start justify-center gap-2 px-4 py-4 sm:px-5 md:px-8">

            <div>

                <div class="mb-1 flex items-center gap-2 text-xs font-medium text-[#7E9833]">

                    <span>Espace membre</span>

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

                    <span class="text-[#71869A]">
                        Messagerie
                    </span>

                </div>

                <h1 class="break-words text-xl font-bold tracking-tight text-[#101D33] sm:text-2xl">
                    Messagerie
                </h1>

                <p class="mt-1 text-sm text-[#71869A]">
                    Consultez les messages reçus concernant vos biens.
                </p>

            </div>

        </div>

    </header>


    <!-- =========================================================
         CONTENU
    ========================================================== -->
    <main class="px-4 py-5 md:px-6 md:py-7">

        <div class="mx-auto max-w-7xl">

            <div class="flex min-h-[650px] min-w-0 flex-col overflow-hidden rounded-2xl border border-[#E5E2D8] bg-white shadow-sm lg:flex-row">


                <!-- =================================================
                     COLONNE GAUCHE
                ================================================== -->
                <aside class="w-full min-w-0 shrink-0 border-b border-[#E5E2D8] bg-white lg:w-[320px] lg:border-b-0 lg:border-r">

                    <!-- HEADER CONVERSATIONS -->
                    <div class="border-b border-[#E5E2D8] p-5">

                        <div class="flex items-center justify-between gap-3">

                            <div class="min-w-0 flex-1">

                                <h2 class="break-words text-sm font-bold text-[#101D33]">
                                    Messages reçus
                                </h2>

                                <p class="mt-1 text-xs text-[#71869A]">
                                    Vos échanges récents
                                </p>

                            </div>

                            <?php if (!empty($messages)): ?>

                                <span class="rounded-full bg-[#EEF2E4] px-3 py-1 text-xs font-bold text-[#647A28]">
                                    <?= count($messages) ?>
                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- Recherche visuelle -->
                        <div class="relative mt-4">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#7E9833]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z"
                                />
                            </svg>

                            <input
                                type="text"
                                placeholder="Rechercher un message..."
                                class="h-11 w-full rounded-xl border border-[#E5E2D8] bg-[#F9F8F4] pl-9 pr-3 text-xs text-[#101D33] outline-none transition placeholder:text-[#9AA5AF] focus:border-[#7E9833] focus:ring-2 focus:ring-[#7E9833]/15"
                            >

                        </div>

                    </div>


                    <!-- LISTE DES MESSAGES -->
                    <div class="max-h-[570px] divide-y divide-[#EEEDE7] overflow-y-auto">

                        <?php if (!empty($messages)): ?>

                            <?php foreach ($messages as $index => $message): ?>

                                <?php

                                $prenom = trim(
                                    (string) ($message['expediteur_prenom'] ?? '')
                                );

                                $nom = trim(
                                    (string) ($message['expediteur_nom'] ?? '')
                                );

                                $nomComplet = trim($prenom . ' ' . $nom);

                                if ($nomComplet === '') {
                                    $nomComplet = 'Utilisateur';
                                }

                                $initiales = initialesUtilisateur(
                                    $prenom,
                                    $nom
                                );

                                $active = $index === 0;

                                $nonLu = empty($message['lu']);

                                ?>

                                <button
                                    type="button"
                                    class="group flex w-full items-center gap-3 px-4 py-4 text-left transition
                                    <?= $active
                                        ? 'bg-[#F0F3E9] border-l-[3px] border-l-[#7E9833]'
                                        : 'bg-white hover:bg-[#F8F9F5]' ?>"
                                >

                                    <!-- Avatar -->
                                    <div class="relative shrink-0">

                                        <div class="flex h-11 w-11 items-center justify-center rounded-full
                                            <?= $active
                                                ? 'bg-[#101D33] text-white'
                                                : 'bg-[#EEF2E4] text-[#647A28]' ?>
                                            text-xs font-bold">

                                            <?= htmlspecialchars(
                                                $initiales,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                        <?php if ($nonLu): ?>

                                            <span
                                                class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-[#7E9833]"
                                                title="Message non lu"
                                            ></span>

                                        <?php endif; ?>

                                    </div>


                                    <!-- Informations -->
                                    <div class="min-w-0 flex-1">

                                        <div class="flex items-center justify-between gap-2">

                                            <p class="truncate text-sm <?= $nonLu ? 'font-bold' : 'font-semibold' ?> text-[#101D33]">

                                                <?= htmlspecialchars(
                                                    $nomComplet,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </p>

                                            <span class="shrink-0 text-[10px] text-[#71869A]">

                                                <?= htmlspecialchars(
                                                    formatDateMessage(
                                                        $message['created_at'] ?? null
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </span>

                                        </div>


                                        <p class="mt-1 truncate text-xs font-semibold text-[#647A28]">

                                            <?= htmlspecialchars(
                                                $message['sujet'] ?? 'Message',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </p>


                                        <p class="mt-1 truncate text-xs text-[#71869A]">

                                            <?= htmlspecialchars(
                                                $message['contenu'] ?? '',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </p>


                                        <?php if (!empty($message['bien_titre'])): ?>

                                            <p class="mt-1 truncate text-[10px] text-[#7E9833]">

                                                Bien :
                                                <?= htmlspecialchars(
                                                    $message['bien_titre'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </p>

                                        <?php endif; ?>

                                    </div>


                                    <?php if ($nonLu): ?>

                                        <div class="h-2 w-2 shrink-0 rounded-full bg-[#7E9833]"></div>

                                    <?php endif; ?>

                                </button>

                            <?php endforeach; ?>


                        <?php else: ?>


                            <!-- Aucun message -->
                            <div class="px-5 py-14 text-center">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EEF2E4]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-[#7E9833]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"
                                        />
                                    </svg>

                                </div>

                                <p class="mt-4 text-sm font-bold text-[#101D33]">
                                    Aucun message
                                </p>

                                <p class="mt-1 text-xs leading-5 text-[#71869A]">
                                    Les messages concernant vos biens
                                    apparaîtront ici.
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>

                </aside>


                <!-- =================================================
                     COLONNE DROITE
                ================================================== -->
                <section class="flex min-w-0 flex-1 flex-col bg-[#F5F3ED]">

                    <?php if ($conversationActive): ?>

                        <?php

                        $prenomActive = trim(
                            (string) (
                                $conversationActive['expediteur_prenom'] ?? ''
                            )
                        );

                        $nomActive = trim(
                            (string) (
                                $conversationActive['expediteur_nom'] ?? ''
                            )
                        );

                        $nomActiveComplet = trim(
                            $prenomActive . ' ' . $nomActive
                        );

                        if ($nomActiveComplet === '') {
                            $nomActiveComplet = 'Utilisateur';
                        }

                        $initialesActive = initialesUtilisateur(
                            $prenomActive,
                            $nomActive
                        );

                        ?>


                        <!-- HEADER CONVERSATION -->
                        <div class="flex items-center justify-between border-b border-[#E5E2D8] bg-white px-5 py-4 md:px-6">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#101D33] text-xs font-bold text-white">

                                    <?= htmlspecialchars(
                                        $initialesActive,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </div>


                                <div class="min-w-0">

                                    <h2 class="break-words text-sm font-bold text-[#101D33]">

                                        <?= htmlspecialchars(
                                            $nomActiveComplet,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </h2>


                                    <?php if (!empty($conversationActive['bien_titre'])): ?>

                                        <p class="mt-1 break-words text-[11px] text-[#71869A]">

                                            Concernant :
                                            <?= htmlspecialchars(
                                                $conversationActive['bien_titre'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </p>

                                    <?php else: ?>

                                        <p class="mt-1 text-[11px] text-[#71869A]">
                                            Message reçu
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             MESSAGE
                        ================================================== -->
                        <div class="flex-1 overflow-y-auto px-5 py-8 md:px-8">

                            <div class="mx-auto max-w-3xl">


                                <!-- Sujet -->
                                <?php if (!empty($conversationActive['sujet'])): ?>

                                    <div class="mb-6 text-center">

                                        <span class="inline-flex rounded-full border border-[#E5E2D8] bg-white px-4 py-2 text-xs font-bold text-[#647A28] shadow-sm">

                                            <?= htmlspecialchars(
                                                $conversationActive['sujet'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </span>

                                    </div>

                                <?php endif; ?>


                                <!-- MESSAGE REÇU -->
                                <div class="flex items-start gap-3">

                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#EEF2E4] text-[10px] font-bold text-[#647A28]">

                                        <?= htmlspecialchars(
                                            $initialesActive,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>


                                    <div class="min-w-0 max-w-[90%] sm:max-w-[75%]">

                                        <div class="mb-1.5 flex flex-wrap items-center gap-2">

                                            <p class="text-[11px] font-semibold text-[#101D33]">

                                                <?= htmlspecialchars(
                                                    $nomActiveComplet,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </p>

                                            <span class="text-[10px] text-[#71869A]">

                                                <?= htmlspecialchars(
                                                    formatDateMessage(
                                                        $conversationActive['created_at'] ?? null
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </span>

                                        </div>


                                        <div class="min-w-0 break-words rounded-2xl rounded-tl-md border border-[#E5E2D8] bg-white px-4 py-3.5 text-sm leading-6 text-[#26364B] shadow-sm [overflow-wrap:anywhere]">

                                            <?= nl2br(
                                                htmlspecialchars(
                                                    $conversationActive['contenu'] ?? '',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                )
                                            ) ?>

                                        </div>


                                        <?php if (!empty($conversationActive['bien_titre'])): ?>

                                            <p class="mt-2 text-[10px] text-[#7E9833]">

                                                Bien concerné :
                                                <?= htmlspecialchars(
                                                    $conversationActive['bien_titre'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </p>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             INFORMATION
                        ================================================== -->
                        <div class="border-t border-[#E5E2D8] bg-white p-4 md:p-5">

                            <div class="mx-auto max-w-3xl">

                                <div class="rounded-xl border border-[#DCE5C8] bg-[#F3F6EC] px-4 py-3">

                                    <div class="flex items-start gap-3">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="mt-0.5 h-5 w-5 shrink-0 text-[#7E9833]"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 9v3.75m0 3h.008v.008H12V15.75ZM10.29 3.86 2.82 17a2 2 0 0 0 1.73 3h14.9a2 2 0 0 0 1.73-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                                            />
                                        </svg>

                                        <div>

                                            <p class="text-xs font-bold text-[#101D33]">
                                                Réponse depuis la fiche du bien
                                            </p>

                                            <p class="mt-1 text-[11px] leading-5 text-[#71869A]">
                                                Pour répondre à cet utilisateur,
                                                ouvrez la fiche du bien concerné
                                                et utilisez le formulaire de contact.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                    <?php else: ?>


                        <!-- =================================================
                             AUCUN MESSAGE
                        ================================================== -->
                        <div class="flex flex-1 items-center justify-center px-6 text-center">

                            <div class="max-w-sm">

                                <div class="relative mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-[#EEF2E4]">

                                    <div class="absolute inset-2 rounded-2xl border border-[#DCE5C8]"></div>

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="relative h-10 w-10 text-[#7E9833]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"
                                        />
                                    </svg>

                                </div>


                                <h2 class="mt-7 text-2xl font-bold text-[#101D33]">
                                    Votre messagerie
                                </h2>

                                <p class="mt-3 text-sm leading-6 text-[#71869A]">
                                    Les messages concernant vos biens
                                    apparaîtront automatiquement ici.
                                </p>

                            </div>

                        </div>

                    <?php endif; ?>

                </section>

            </div>

        </div>

    </main>

</div>