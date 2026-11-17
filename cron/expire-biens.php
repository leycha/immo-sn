<?php
declare(strict_types=1);
/**
 * CRON : EXPIRATION AUTOMATIQUE DES ANNONCES
 * Fichier : cron/expire-biens.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Accès interdit. Exécution CLI uniquement.\n");
}

require_once __DIR__ . '/CronBase.php';

$cron = new class('expire-biens.php') extends CronBase
{
    protected function execute(): int
    {
        $sql = "
            UPDATE public.biens
            SET statut = 'expire',
                updated_at = CURRENT_TIMESTAMP
            WHERE LOWER(BTRIM(COALESCE(statut, '')))
                IN ('actif', 'active', 'valide', 'validé')
              AND expire_at IS NOT NULL
              AND expire_at <= CURRENT_TIMESTAMP
        ";

        $this->db->beginTransaction();

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute();

            $nombreExpire = $statement->rowCount();

            $this->db->commit();

            echo "Annonces expirées : {$nombreExpire}" . PHP_EOL;

            return $nombreExpire;
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }
};

exit($cron->run());
