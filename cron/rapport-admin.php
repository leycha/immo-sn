<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Accès interdit : ce script doit être exécuté en ligne de commande.\n");
}

require_once dirname(__DIR__) . '/core/Database.php';
require_once __DIR__ . '/CronBase.php';

$cron = new class('rapport-admin.php') extends CronBase
{
    protected function execute(): int
    {
        $rapport = [];

        $rapport[] = "RAPPORT ADMINISTRATIF IMMO-SN";
        $rapport[] = "Généré le : " . date('d/m/Y à H:i:s');
        $rapport[] = str_repeat('=', 55);
        $rapport[] = "";

        /*
         * 1. INDICATEURS GÉNÉRAUX
         */
        $requetes = [
            'Utilisateurs inscrits' => "
                SELECT COUNT(*)
                FROM public.users
            ",

            'Annonces totales' => "
                SELECT COUNT(*)
                FROM public.biens
            ",

            'Annonces actives' => "
                SELECT COUNT(*)
                FROM public.biens
                WHERE LOWER(BTRIM(COALESCE(statut, ''))) IN
                    ('actif', 'active', 'valide', 'validé')
                  AND (
                      expire_at IS NULL
                      OR expire_at > CURRENT_TIMESTAMP
                  )
            ",

            'Annonces en attente' => "
                SELECT COUNT(*)
                FROM public.biens
                WHERE LOWER(BTRIM(COALESCE(statut, ''))) IN
                    ('en_attente', 'en-attente', 'pending')
            ",

            'Annonces rejetées' => "
                SELECT COUNT(*)
                FROM public.biens
                WHERE LOWER(BTRIM(COALESCE(statut, ''))) IN
                    (
                        'rejete',
                        'rejeté',
                        'rejetee',
                        'rejetée',
                        'refuse',
                        'refusé'
                    )
            ",

            'Annonces expirées par date' => "
                SELECT COUNT(*)
                FROM public.biens
                WHERE expire_at IS NOT NULL
                  AND expire_at <= CURRENT_TIMESTAMP
            ",

            'Signalements en attente' => "
                SELECT COUNT(*)
                FROM public.signalements
                WHERE deleted_at IS NULL
                  AND LOWER(BTRIM(COALESCE(statut, ''))) IN
                    ('en_attente', 'en-attente', 'pending', 'nouveau')
            ",

            'Tâches CRON en échec (7 jours)' => "
                SELECT COUNT(*)
                FROM public.cron_logs
                WHERE LOWER(BTRIM(COALESCE(status, ''))) IN
                    ('error', 'failed', 'failure', 'echec', 'échec')
                  AND executed_at >=
                      CURRENT_TIMESTAMP - INTERVAL '7 days'
            ",

            'E-mails en échec (7 jours)' => "
                SELECT COUNT(*)
                FROM public.mail_logs
                WHERE LOWER(BTRIM(COALESCE(status, ''))) IN
                    ('error', 'failed', 'failure', 'echec', 'échec')
                  AND sent_at >=
                      CURRENT_TIMESTAMP - INTERVAL '7 days'
            ",
        ];

        $rapport[] = "1. INDICATEURS GÉNÉRAUX";
        $rapport[] = str_repeat('-', 55);

        foreach ($requetes as $libelle => $sql) {
            $valeur = (int) $this->db
                ->query($sql)
                ->fetchColumn();

            $rapport[] = $libelle . ' : ' . $valeur;
        }

        /*
         * 2. DERNIÈRES EXÉCUTIONS CRON
         */
        $rapport[] = "";
        $rapport[] = "2. DERNIÈRES EXÉCUTIONS CRON";
        $rapport[] = str_repeat('-', 55);

        $sqlCron = "
            SELECT
                script,
                status,
                items_processed,
                duration_ms,
                error_message,
                executed_at
            FROM public.cron_logs
            ORDER BY executed_at DESC
            LIMIT 10
        ";

        $derniersCrons = $this->db
            ->query($sqlCron)
            ->fetchAll(\PDO::FETCH_ASSOC);

        if (!$derniersCrons) {
            $rapport[] = "Aucune exécution CRON enregistrée.";
        } else {
            foreach ($derniersCrons as $ligne) {
                $rapport[] = sprintf(
                    "%s | script=%s | statut=%s | éléments=%s | durée=%s ms",
                    $ligne['executed_at'] ?? 'Date inconnue',
                    $ligne['script'] ?? 'Script inconnu',
                    $ligne['status'] ?? 'Statut inconnu',
                    $ligne['items_processed'] ?? 0,
                    $ligne['duration_ms'] ?? 0
                );

                if (!empty($ligne['error_message'])) {
                    $rapport[] = "Erreur : "
                        . $ligne['error_message'];
                }

                $rapport[] = "";
            }
        }

        /*
         * 3. DERNIERS E-MAILS
         */
        $rapport[] = "";
        $rapport[] = "3. DERNIERS E-MAILS";
        $rapport[] = str_repeat('-', 55);

        $sqlMail = "
            SELECT
                email_to,
                subject,
                template,
                status,
                error,
                sent_at
            FROM public.mail_logs
            ORDER BY sent_at DESC
            LIMIT 10
        ";

        $derniersMails = $this->db
            ->query($sqlMail)
            ->fetchAll(\PDO::FETCH_ASSOC);

        if (!$derniersMails) {
            $rapport[] = "Aucun e-mail enregistré.";
        } else {
            foreach ($derniersMails as $mail) {
                $rapport[] = sprintf(
                    "%s | destinataire=%s | statut=%s | modèle=%s",
                    $mail['sent_at'] ?? 'Date inconnue',
                    $mail['email_to'] ?? 'Non renseigné',
                    $mail['status'] ?? 'Statut inconnu',
                    $mail['template'] ?? 'Non renseigné'
                );

                $rapport[] = "Objet : "
                    . ($mail['subject'] ?? 'Sans objet');

                if (!empty($mail['error'])) {
                    $rapport[] = "Erreur : " . $mail['error'];
                }

                $rapport[] = "";
            }
        }

        /*
         * 4. STATISTIQUES JOURNALIÈRES
         */
        $rapport[] = "";
        $rapport[] = "4. STATISTIQUES JOURNALIÈRES";
        $rapport[] = str_repeat('-', 55);

        $sqlStats = "
            SELECT
                date,
                total_vues,
                total_contacts,
                total_biens_actifs,
                total_utilisateurs
            FROM public.daily_stats
            ORDER BY date DESC
            LIMIT 7
        ";

        $stats = $this->db
            ->query($sqlStats)
            ->fetchAll(\PDO::FETCH_ASSOC);

        if (!$stats) {
            $rapport[] = "Aucune statistique journalière enregistrée.";
        } else {
            foreach ($stats as $stat) {
                $rapport[] = sprintf(
                    "%s | vues=%d | contacts=%d | annonces actives=%d | utilisateurs=%d",
                    $stat['date'],
                    (int) ($stat['total_vues'] ?? 0),
                    (int) ($stat['total_contacts'] ?? 0),
                    (int) ($stat['total_biens_actifs'] ?? 0),
                    (int) ($stat['total_utilisateurs'] ?? 0)
                );
            }
        }

        /*
         * 5. ENREGISTREMENT DU RAPPORT
         */
        $rapport[] = "";
        $rapport[] = str_repeat('-', 55);
        $rapport[] = "Fin du rapport.";

        $dossier = dirname(__DIR__) . '/storage/reports';

        if (
            !is_dir($dossier)
            && !mkdir($dossier, 0775, true)
            && !is_dir($dossier)
        ) {
            throw new \RuntimeException(
                "Impossible de créer le dossier des rapports : "
                . $dossier
            );
        }

        $fichier = $dossier
            . '/rapport-admin-'
            . date('Y-m-d_H-i-s')
            . '.txt';

        $contenu = implode(PHP_EOL, $rapport) . PHP_EOL;

        $resultat = file_put_contents($fichier, $contenu);

        if ($resultat === false) {
            throw new \RuntimeException(
                "Impossible d'enregistrer le rapport administratif."
            );
        }

        echo "Rapport administratif créé : "
            . $fichier
            . PHP_EOL;

        return 1;
    }
};

exit($cron->run());
