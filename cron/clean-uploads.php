<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Acces interdit : execution CLI uniquement.\n");
}

require_once __DIR__ . '/CronBase.php';

$cron = new class('clean-uploads.php') extends CronBase
{
    protected function execute(): int
    {
        $dossier = dirname(__DIR__) . DIRECTORY_SEPARATOR
            . 'stockage' . DIRECTORY_SEPARATOR . 'photos';

        if (!is_dir($dossier)) {
            throw new RuntimeException(
                'Dossier des photos introuvable : ' . $dossier
            );
        }

        $stmt = $this->db->query(
            "SELECT file_path FROM public.photos"
        );

        $cheminsConnus = [];

        foreach ($stmt->fetchAll(\PDO::FETCH_COLUMN) as $chemin) {
            if (!is_string($chemin) || trim($chemin) === '') {
                continue;
            }

            $nom = basename(str_replace('\\', '/', $chemin));

            if ($nom !== '' && $nom !== '.' && $nom !== '..') {
                $cheminsConnus[$nom] = true;
            }
        }

        $fichiers = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dossier,
                FilesystemIterator::SKIP_DOTS
            )
        );

        $total = 0;
        $references = 0;
        $sansReference = 0;

        echo "Diagnostic des photos IMMO-SN" . PHP_EOL;
        echo "Mode securise : aucune suppression." . PHP_EOL;
        echo "----------------------------------------" . PHP_EOL;

        foreach ($fichiers as $fichier) {
            if (!$fichier->isFile()) {
                continue;
            }

            $total++;
            $nom = $fichier->getFilename();

            if (isset($cheminsConnus[$nom])) {
                $references++;

                echo "[REFERENCE] " . $nom . PHP_EOL;
            } else {
                $sansReference++;

                echo "[A VERIFIER] " . $nom . PHP_EOL;
            }
        }

        echo "----------------------------------------" . PHP_EOL;
        echo "Fichiers examines : " . $total . PHP_EOL;
        echo "Fichiers references : " . $references . PHP_EOL;
        echo "Fichiers sans reference : " . $sansReference . PHP_EOL;
        echo "Aucun fichier supprime." . PHP_EOL;

        return $total;
    }
};

exit($cron->run());
