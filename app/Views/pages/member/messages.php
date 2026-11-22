
<?php

$messages = is_array($messages ?? null) ? $messages : [];
$conversation = is_array($conversation ?? null) ? $conversation : null;
$selectedId = $selectedId ?? '';

$historique = $conversation['historique'] ?? [];

$prenom = trim((string)($conversation['interlocuteur_prenom'] ?? ''));
$nom = trim((string)($conversation['interlocuteur_nom'] ?? ''));
$nomInterlocuteur = trim($prenom . ' ' . $nom) ?: 'Utilisateur';

$initiales = mb_strtoupper(
    (mb_substr($prenom, 0, 1) . mb_substr($nom, 0, 1)) ?: 'U'
);

$success = Session::getFlash('message_success');
$error = Session::getFlash('message_error');

function dateMessagerie($date): string {
    if (!$date) return '';
    try {
        return (new DateTime($date))->format('d/m/Y à H:i');
    } catch (Throwable $e) {
        return '';
    }
}

?>

<div class="min-h-screen bg-[#F5F3ED]">

    <header class="border-b border-[#E7E0D2] bg-white px-5 py-5">
        <div class="mx-auto max-w-7xl">
            <p class="text-xs font-bold text-[#8A6500]">Espace membre / Messagerie</p>
            <h1 class="mt-1 text-2xl font-extrabold text-[#071B36]">Messagerie</h1>
            <p class="mt-1 text-sm text-[#718096]">
                Retrouvez vos échanges et répondez directement à vos interlocuteurs.
            </p>
        </div>
    </header>

    <main class="px-3 py-5 sm:px-6">
        <div class="mx-auto flex min-h-[700px] max-w-7xl flex-col overflow-hidden rounded-2xl border border-[#E7E0D2] bg-white shadow-sm lg:flex-row">

            <!-- Liste des conversations -->
            <aside class="w-full shrink-0 border-b border-[#E7E0D2] lg:w-[350px] lg:border-b-0 lg:border-r">

                <div class="border-b border-[#EEE9DF] p-5">
                    <h2 class="font-extrabold text-[#071B36]">Conversations</h2>
                    <p class="mt-1 text-xs text-[#8490A0]">
                        <?= count($messages) ?> échange(s)
                    </p>

                    <input
                        id="rechercheMessages"
                        type="search"
                        placeholder="Rechercher une conversation..."
                        class="mt-4 h-11 w-full rounded-xl border border-[#E8E3D9] bg-[#FAF9F6] px-4 text-sm outline-none focus:border-[#D7A200]"
                    >
                </div>

                <div id="listeConversations" class="max-h-[620px] divide-y divide-[#F0ECE4] overflow-y-auto">

                    <?php if (!$messages): ?>

                        <div class="p-10 text-center">
                            <p class="font-bold text-[#071B36]">Aucune conversation</p>
                            <p class="mt-2 text-sm text-[#8290A0]">
                                Les messages reçus ou envoyés apparaîtront ici.
                            </p>
                        </div>

                    <?php else: ?>

                        <?php foreach ($messages as $message): ?>

                            <?php
                            $prenomItem = $message['interlocuteur_prenom'] ?? '';
                            $nomItem = $message['interlocuteur_nom'] ?? '';
                            $nomItemComplet = trim($prenomItem . ' ' . $nomItem) ?: 'Utilisateur';
                            $initialesItem = mb_strtoupper(
                                (mb_substr($prenomItem, 0, 1) . mb_substr($nomItem, 0, 1)) ?: 'U'
                            );
                            $actif = (string)$message['id'] === (string)$selectedId;
                            $nonLu = (int)($message['non_lus_total'] ?? 0) > 0;
                            ?>

                            <a
                                href="/immo-sn/member/messages?id=<?= urlencode($message['id']) ?>"
                                data-search="<?= htmlspecialchars(mb_strtolower($nomItemComplet . ' ' . ($message['sujet'] ?? '') . ' ' . ($message['bien_titre'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
                                class="conversation-item flex items-center gap-3 border-l-[3px] px-4 py-4 transition
                                <?= $actif ? 'border-[#F5B800] bg-[#F8F6EF]' : 'border-transparent hover:bg-[#FAF9F6]' ?>"
                            >

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl
                                    <?= $actif ? 'bg-[#071B36] text-[#F5B800]' : 'bg-[#F5F1E8] text-[#8A6500]' ?> font-extrabold">
                                    <?= htmlspecialchars($initialesItem, ENT_QUOTES, 'UTF-8') ?>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="truncate text-sm <?= $nonLu ? 'font-extrabold' : 'font-semibold' ?> text-[#071B36]">
                                            <?= htmlspecialchars($nomItemComplet, ENT_QUOTES, 'UTF-8') ?>
                                        </p>

                                        <?php if ($nonLu): ?>
                                            <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-[#D7A200] px-1 text-[10px] font-bold text-white">
                                                <?= (int)$message['non_lus_total'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="mt-1 truncate text-xs font-bold text-[#8A6500]">
                                        <?= htmlspecialchars($message['sujet'] ?? 'Message', ENT_QUOTES, 'UTF-8') ?>
                                    </p>

                                    <p class="mt-1 truncate text-xs text-[#8290A0]">
                                        <?= htmlspecialchars($message['contenu'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </p>

                                    <?php if (!empty($message['bien_titre'])): ?>
                                        <p class="mt-1 truncate text-[10px] text-[#718096]">
                                            Bien : <?= htmlspecialchars($message['bien_titre'], ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </a>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>
            </aside>

            <!-- Conversation ouverte -->
            <section class="flex min-w-0 flex-1 flex-col bg-[#F7F6F2]">

                <?php if ($conversation): ?>

                    <div class="flex items-center gap-3 border-b border-[#E9E3D8] bg-white px-5 py-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#071B36] font-bold text-[#F5B800]">
                            <?= htmlspecialchars($initiales, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                        <div class="min-w-0">
                            <h2 class="font-extrabold text-[#071B36]">
                                <?= htmlspecialchars($nomInterlocuteur, ENT_QUOTES, 'UTF-8') ?>
                            </h2>
                            <p class="mt-1 truncate text-xs text-[#8290A0]">
                                <?= htmlspecialchars($conversation['bien_titre'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>
                    </div>

                    <?php if ($success): ?>
                        <div class="mx-4 mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="mx-4 mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Historique -->
                    <div class="flex-1 space-y-5 overflow-y-auto px-4 py-6 sm:px-6">

                        <?php foreach ($historique as $ligne): ?>

                            <?php
                            $estMoi = (string)$ligne['expediteur_id'] === (string)Session::userId();
                            $auteur = trim(($ligne['expediteur_prenom'] ?? '') . ' ' . ($ligne['expediteur_nom'] ?? ''));
                            ?>

                            <div class="flex <?= $estMoi ? 'justify-end' : 'justify-start' ?>">
                                <div class="max-w-[90%] sm:max-w-[75%]">

                                    <div class="mb-1 flex items-center gap-2 <?= $estMoi ? 'justify-end' : '' ?>">
                                        <span class="text-[11px] font-bold text-[#071B36]">
                                            <?= $estMoi ? 'Vous' : htmlspecialchars($auteur ?: $nomInterlocuteur, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <span class="text-[10px] text-[#929BA6]">
                                            <?= dateMessagerie($ligne['created_at'] ?? null) ?>
                                        </span>
                                    </div>

                                    <div class="rounded-2xl px-4 py-3 text-sm leading-7 [overflow-wrap:anywhere]
                                        <?= $estMoi
                                            ? 'rounded-tr-sm bg-[#071B36] text-white'
                                            : 'rounded-tl-sm border border-[#E9E3D8] bg-white text-[#354357] shadow-sm' ?>">

                                        <?= nl2br(htmlspecialchars($ligne['contenu'] ?? '', ENT_QUOTES, 'UTF-8')) ?>

                                    </div>

                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>

                    <!-- Formulaire de réponse -->
                    <div class="border-t border-[#E9E3D8] bg-white p-4 sm:p-5">
                        <form action="/immo-sn/member/messages/repondre" method="POST" class="mx-auto max-w-3xl">

                            <input type="hidden" name="_csrf_token"
                                   value="<?= htmlspecialchars(Session::csrfToken(), ENT_QUOTES, 'UTF-8') ?>">

                            <input type="hidden" name="message_id"
                                   value="<?= htmlspecialchars($selectedId, ENT_QUOTES, 'UTF-8') ?>">

                            <label for="contenuReponse" class="mb-2 block text-sm font-bold text-[#071B36]">
                                Votre réponse
                            </label>

                            <textarea
                                id="contenuReponse"
                                name="contenu"
                                rows="3"
                                minlength="3"
                                maxlength="5000"
                                required
                                placeholder="Écrivez votre réponse ici..."
                                class="w-full resize-y rounded-xl border border-[#E8E3D9] bg-[#FAF9F6] px-4 py-3 text-sm outline-none focus:border-[#D7A200] focus:ring-2 focus:ring-[#F5B800]/15"
                            ></textarea>

                            <div class="mt-3 flex justify-end">
                                <button type="submit"
                                    class="rounded-xl bg-[#071B36] px-5 py-3 text-sm font-extrabold text-[#F5B800] transition hover:bg-[#10294B]">
                                    Envoyer la réponse
                                </button>
                            </div>

                        </form>
                    </div>

                <?php else: ?>

                    <div class="flex flex-1 items-center justify-center p-10 text-center">
                        <div class="max-w-sm">
                            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[#071B36] text-3xl text-[#F5B800]">
                                ✉
                            </div>
                            <h2 class="mt-5 text-xl font-extrabold text-[#071B36]">
                                Ouvrez une conversation
                            </h2>
                            <p class="mt-2 text-sm leading-6 text-[#8290A0]">
                                Sélectionnez un échange dans la liste pour consulter son historique et répondre directement.
                            </p>
                        </div>
                    </div>

                <?php endif; ?>

            </section>

        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const recherche = document.getElementById('rechercheMessages');
    const items = document.querySelectorAll('.conversation-item');

    if (!recherche) return;

    recherche.addEventListener('input', function () {
        const terme = this.value.trim().toLowerCase();

        items.forEach(function (item) {
            const texte = item.dataset.search || '';
            item.style.display = texte.includes(terme) ? '' : 'none';
        });
    });
});
</script>
