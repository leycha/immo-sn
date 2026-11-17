<?php

declare(strict_types=1);

require_once __DIR__ . '/CronBase.php';

class CronInactivite extends CronBase
{
    protected function execute(): int
    {
        $sql = "
            SELECT
                COUNT(*) AS total,

                COUNT(*) FILTER (
                    WHERE last_login IS NOT NULL
                      AND last_login >= CURRENT_TIMESTAMP - INTERVAL '90 days'
                ) AS connexions_recentes,

                COUNT(*) FILTER (
                    WHERE last_login IS NOT NULL
                      AND last_login < CURRENT_TIMESTAMP - INTERVAL '90 days'
                ) AS connexions_anciennes,

                COUNT(*) FILTER (
                    WHERE last_login IS NULL
                ) AS connexions_inconnues,

                COUNT(*) FILTER (
                    WHERE last_login IS NULL
                      AND created_at < CURRENT_TIMESTAMP - INTERVAL '90 days'
                ) AS anciens_comptes_sans_suivi

            FROM public.users
        ";

        $result = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new RuntimeException(
                'Impossible de recuperer les statistiques des comptes.'
            );
        }

        echo PHP_EOL;
        echo "=== RAPPORT D'INACTIVITE IMMO-SN ===" . PHP_EOL;
        echo "Seuil d'analyse : 90 jours" . PHP_EOL;
        echo "Total des comptes : "
            . $result['total'] . PHP_EOL;
        echo "Derniere connexion connue de moins de 90 jours : "
            . $result['connexions_recentes'] . PHP_EOL;
        echo "Derniere connexion connue de plus de 90 jours : "
            . $result['connexions_anciennes'] . PHP_EOL;
        echo "Comptes sans date de connexion connue : "
            . $result['connexions_inconnues'] . PHP_EOL;
        echo "Anciens comptes sans suivi de connexion : "
            . $result['anciens_comptes_sans_suivi'] . PHP_EOL;
        echo "Action automatique : aucune" . PHP_EOL;
        echo "===================================" . PHP_EOL;

        return (int) $result['total'];
    }
}

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Execution CLI uniquement.');
}

$cron = new CronInactivite(__FILE__);
exit($cron->run());
