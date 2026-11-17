<?php

declare(strict_types=1);

/**
 * IMMO-SN — Base commune des tâches CRON.
 * Fichier : cron/CronBase.php
 */

require_once dirname(__DIR__) . '/core/Database.php';

abstract class CronBase
{
    protected PDO $db;

    private string $script;
    private float $startTime;
    private int $itemsProcessed = 0;

    public function __construct(string $script)
    {
        $this->script = basename($script);
        $this->db = \Core\Database::getConnection();
        $this->startTime = microtime(true);
    }

    /**
     * Exécute le traitement propre à chaque tâche CRON.
     */
    abstract protected function execute(): int;

    /**
     * Lance la tâche et enregistre son résultat.
     */
    public function run(): int
    {
        $status = 'success';
        $errorMessage = null;
        $exitCode = 0;

        try {
            $this->itemsProcessed = $this->execute();
        } catch (\Throwable $e) {
            $status = 'error';
            $errorMessage = $e->getMessage();
            $exitCode = 1;

            error_log(
                '[IMMO-SN CRON] '
                . $this->script
                . ' : '
                . $errorMessage
            );
        }

        $durationMs = (int) round(
            (microtime(true) - $this->startTime) * 1000
        );

        try {
            $this->logExecution(
                $status,
                $this->itemsProcessed,
                $durationMs,
                $errorMessage
            );
        } catch (\Throwable $e) {
            error_log(
                '[IMMO-SN CRON] Erreur de journalisation : '
                . $e->getMessage()
            );

            $exitCode = 1;
        }

        echo sprintf(
            '[IMMO-SN CRON] %s | statut=%s | éléments=%d | durée=%d ms',
            $this->script,
            $status,
            $this->itemsProcessed,
            $durationMs
        ) . PHP_EOL;

        if ($errorMessage !== null) {
            echo 'Erreur : ' . $errorMessage . PHP_EOL;
        }

        return $exitCode;
    }

    /**
     * Enregistre l'exécution dans la table cron_logs.
     */
    private function logExecution(
        string $status,
        int $itemsProcessed,
        int $durationMs,
        ?string $errorMessage
    ): void {
        $sql = '
            INSERT INTO public.cron_logs (
                id,
                script,
                status,
                items_processed,
                duration_ms,
                error_message,
                executed_at
            )
            VALUES (
                gen_random_uuid(),
                :script,
                :status,
                :items_processed,
                :duration_ms,
                :error_message,
                CURRENT_TIMESTAMP
            )
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            ':script' => $this->script,
            ':status' => $status,
            ':items_processed' => $itemsProcessed,
            ':duration_ms' => $durationMs,
            ':error_message' => $errorMessage,
        ]);
    }
}