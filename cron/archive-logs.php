<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Acc?s interdit : ex?cution CLI uniquement.\n");
}

require_once __DIR__ . '/CronBase.php';

$cron = new class('archive-logs.php') extends CronBase
{
    protected function execute(): int
    {
        $tables = [
            'cron_logs' => 'executed_at',
            'mail_logs' => 'sent_at',
            'audit_logs' => 'created_at',
        ];

        echo "V?rification de l'anciennet? des journaux (90 jours)." . PHP_EOL;
        echo "Mode s?curis? : aucune suppression ni modification." . PHP_EOL;

        $totalAnciens = 0;

        foreach ($tables as $table => $dateColumn) {
            $sql = "
                SELECT
                    COUNT(*) AS total,
                    COUNT(*) FILTER (
                        WHERE {$dateColumn} <
                            CURRENT_TIMESTAMP - INTERVAL '90 days'
                    ) AS anciens
                FROM public.{$table}
            ";

            $resultat = $this->db
                ->query($sql)
                ->fetch(\PDO::FETCH_ASSOC);

            $total = (int) $resultat['total'];
            $anciens = (int) $resultat['anciens'];
            $totalAnciens += $anciens;

            echo "{$table} : {$total} journal(aux), "
                . "{$anciens} plus ancien(s) que 90 jours."
                . PHP_EOL;
        }

        echo "Total des journaux ? examiner pour archivage : "
            . $totalAnciens . PHP_EOL;

        echo "Aucune donn?e n'a ?t? supprim?e ou d?plac?e." . PHP_EOL;

        return $totalAnciens;
    }
};

exit($cron->run());
