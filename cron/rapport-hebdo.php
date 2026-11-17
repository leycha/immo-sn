<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Acc?s interdit : ex?cution CLI uniquement.\n");
}

require_once __DIR__ . '/CronBase.php';

$cron = new class('rapport-hebdo.php') extends CronBase
{
    protected function execute(): int
    {
        $lignes = [];
        $lignes[] = "RAPPORT HEBDOMADAIRE IMMO-SN";
        $lignes[] = "G?n?r? le : " . date('d/m/Y H:i:s');
        $lignes[] = "P?riode : les 7 derniers jours";
        $lignes[] = str_repeat('=', 50);
        $lignes[] = "";

        $sqlStats = "
            SELECT
                COALESCE(SUM(total_vues), 0) AS vues,
                COALESCE(SUM(total_contacts), 0) AS contacts,
                COALESCE(AVG(total_biens_actifs), 0) AS moyenne_biens_actifs,
                MAX(total_utilisateurs) AS dernier_total_utilisateurs,
                COUNT(*) AS jours_enregistres
            FROM public.daily_stats
            WHERE date >= CURRENT_DATE - INTERVAL '6 days'
              AND date <= CURRENT_DATE
        ";

        $stats = $this->db->query($sqlStats)->fetch(\PDO::FETCH_ASSOC);

        $lignes[] = "1. STATISTIQUES DE LA SEMAINE";
        $lignes[] = str_repeat('-', 50);
        $lignes[] = "Jours avec statistiques : " . (int) $stats['jours_enregistres'];
        $lignes[] = "Vues cumul?es : " . (int) $stats['vues'];
        $lignes[] = "Contacts cumul?s : " . (int) $stats['contacts'];
        $lignes[] = "Moyenne des annonces actives : "
            . round((float) $stats['moyenne_biens_actifs'], 2);
        $lignes[] = "Dernier total d'utilisateurs enregistr? : "
            . ($stats['dernier_total_utilisateurs'] === null
                ? 'Non disponible'
                : (int) $stats['dernier_total_utilisateurs']);
        $lignes[] = "";

        $requetes = [
            'Annonces cr??es cette semaine' => "
                SELECT COUNT(*) FROM public.biens
                WHERE created_at >= CURRENT_DATE - INTERVAL '6 days'
                  AND created_at < CURRENT_DATE + INTERVAL '1 day'
            ",
            'Utilisateurs inscrits cette semaine' => "
                SELECT COUNT(*) FROM public.users
                WHERE created_at >= CURRENT_DATE - INTERVAL '6 days'
                  AND created_at < CURRENT_DATE + INTERVAL '1 day'
            ",
            'Signalements re?us cette semaine' => "
                SELECT COUNT(*) FROM public.signalements
                WHERE created_at >= CURRENT_DATE - INTERVAL '6 days'
                  AND created_at < CURRENT_DATE + INTERVAL '1 day'
                  AND deleted_at IS NULL
            ",
            '?checs CRON cette semaine' => "
                SELECT COUNT(*) FROM public.cron_logs
                WHERE LOWER(COALESCE(status, '')) IN
                    ('error', 'failed', 'failure', 'echec', '?chec')
                  AND executed_at >= CURRENT_DATE - INTERVAL '6 days'
                  AND executed_at < CURRENT_DATE + INTERVAL '1 day'
            ",
            '?checs e-mail cette semaine' => "
                SELECT COUNT(*) FROM public.mail_logs
                WHERE LOWER(COALESCE(status, '')) IN
                    ('error', 'failed', 'failure', 'echec', '?chec')
                  AND sent_at >= CURRENT_DATE - INTERVAL '6 days'
                  AND sent_at < CURRENT_DATE + INTERVAL '1 day'
            ",
        ];

        $lignes[] = "2. ACTIVIT? ET INCIDENTS";
        $lignes[] = str_repeat('-', 50);

        foreach ($requetes as $libelle => $sql) {
            $lignes[] = $libelle . ' : '
                . (int) $this->db->query($sql)->fetchColumn();
        }

        $lignes[] = "";
        $lignes[] = "3. ANNONCES PAR STATUT";
        $lignes[] = str_repeat('-', 50);

        $sqlStatuts = "
            SELECT COALESCE(NULLIF(BTRIM(statut), ''), 'non renseign?') AS statut,
                   COUNT(*) AS nombre
            FROM public.biens
            GROUP BY COALESCE(NULLIF(BTRIM(statut), ''), 'non renseign?')
            ORDER BY nombre DESC, statut
        ";

        foreach ($this->db->query($sqlStatuts)->fetchAll(\PDO::FETCH_ASSOC) as $ligne) {
            $lignes[] = $ligne['statut'] . ' : ' . (int) $ligne['nombre'];
        }

        $lignes[] = "";
        $lignes[] = "Fin du rapport.";

        $dossier = dirname(__DIR__) . '/storage/reports';

        if (!is_dir($dossier) &&
            !mkdir($dossier, 0775, true) &&
            !is_dir($dossier)
        ) {
            throw new \RuntimeException(
                "Impossible de cr?er le dossier des rapports."
            );
        }

        $fichier = $dossier . '/rapport-hebdo-' . date('Y-m-d_H-i-s') . '.txt';

        if (file_put_contents($fichier, implode(PHP_EOL, $lignes) . PHP_EOL) === false) {
            throw new \RuntimeException(
                "Impossible d'enregistrer le rapport hebdomadaire."
            );
        }

        echo "Rapport hebdomadaire cr?? : " . $fichier . PHP_EOL;

        return 1;
    }
};

exit($cron->run());
