<?php
declare(strict_types=1);
/**
 * CRON : STATISTIQUES QUOTIDIENNES — IMMO-SN
 * Fichier : cron/daily-stats.php
 * Exécution : PHP CLI uniquement
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Accès interdit. Exécution CLI uniquement.\n");
}

require_once __DIR__ . '/CronBase.php';

$cron = new class('daily-stats.php') extends CronBase
{
    protected function execute(): int
    {
        // 1. Nombre de vues enregistrées aujourd'hui.
        $sqlVues = "
            SELECT COUNT(*)
            FROM public.vues_biens
            WHERE created_at >= CURRENT_DATE
              AND created_at < CURRENT_DATE + INTERVAL '1 day'
        ";

        $totalVues = (int) $this->db
            ->query($sqlVues)
            ->fetchColumn();

        // 2. Nombre de messages créés aujourd'hui.
        $sqlMessages = "
            SELECT COUNT(*)
            FROM public.messages
            WHERE created_at >= CURRENT_DATE
              AND created_at < CURRENT_DATE + INTERVAL '1 day'
        ";

        $totalMessages = (int) $this->db
            ->query($sqlMessages)
            ->fetchColumn();

        // 3. Nombre de biens actifs et non expirés.
        // Une date d'expiration NULL signifie qu'aucune
        // expiration n'est définie.
        $sqlBiens = "
            SELECT COUNT(*)
            FROM public.biens
            WHERE LOWER(BTRIM(COALESCE(statut, ''))) = 'actif'
              AND (
                  expire_at IS NULL
                  OR expire_at > CURRENT_TIMESTAMP
              )
        ";

        $totalBiens = (int) $this->db
            ->query($sqlBiens)
            ->fetchColumn();

        // 4. Nombre total d'utilisateurs.
        $sqlUtilisateurs = "
            SELECT COUNT(*)
            FROM public.users
        ";

        $totalUtilisateurs = (int) $this->db
            ->query($sqlUtilisateurs)
            ->fetchColumn();

        // 5. Enregistrer ou actualiser les statistiques du jour.
        $sqlStats = "
            INSERT INTO public.daily_stats (
                date,
                total_vues,
                total_contacts,
                total_biens_actifs,
                total_utilisateurs
            )
            VALUES (
                CURRENT_DATE,
                :vues,
                :messages,
                :biens,
                :utilisateurs
            )
            ON CONFLICT (date)
            DO UPDATE SET
                total_vues = EXCLUDED.total_vues,
                total_contacts = EXCLUDED.total_contacts,
                total_biens_actifs = EXCLUDED.total_biens_actifs,
                total_utilisateurs = EXCLUDED.total_utilisateurs
        ";

        $stmt = $this->db->prepare($sqlStats);

        $stmt->execute([
            'vues' => $totalVues,
            'messages' => $totalMessages,
            'biens' => $totalBiens,
            'utilisateurs' => $totalUtilisateurs,
        ]);

        echo "Statistiques quotidiennes enregistrées :"
            . PHP_EOL;
        echo "- Vues : {$totalVues}" . PHP_EOL;
        echo "- Messages : {$totalMessages}" . PHP_EOL;
        echo "- Biens actifs : {$totalBiens}" . PHP_EOL;
        echo "- Utilisateurs : {$totalUtilisateurs}" . PHP_EOL;

        // Une ligne de statistiques a été insérée ou actualisée.
        return 1;
    }
};

exit($cron->run());
