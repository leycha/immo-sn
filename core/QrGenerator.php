<?php

declare(strict_types=1);

namespace Core;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QrGenerator
{
    /**
     * Génère le QR Code d'un bien
     * et enregistre les informations dans la table qr_codes.
     *
     * @param string $bienId
     * @return string Chemin du QR utilisable par le navigateur
     */
    public static function generate(string $bienId): string
    {
        // =====================================================
        // Générer un jeton unique
        // =====================================================

        $jeton = bin2hex(random_bytes(16));

        // =====================================================
        // URL contenue dans le QR Code
        // =====================================================

     $url = 'http://192.168.0.104/immo-sn/scan/' . rawurlencode($jeton);

        // =====================================================
        // Dossier physique des QR Codes
        // =====================================================

        $directory = BASE_PATH . '/stockage/qrcodes';

        // Créer le dossier s'il n'existe pas
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0775, true) && !is_dir($directory)) {
                throw new \RuntimeException(
                    'Impossible de créer le dossier stockage/qrcodes.'
                );
            }
        }

        // =====================================================
        // Nom du fichier
        // =====================================================

        $filename = $bienId . '.png';

        $fullPath = $directory . '/' . $filename;

        // =====================================================
        // Génération du vrai QR Code
        // =====================================================

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(400)
            ->margin(20)
            ->build();

        // =====================================================
        // Enregistrer le fichier PNG
        // =====================================================

        $result->saveToFile($fullPath);

        // =====================================================
        // Chemin enregistré dans la base
        // =====================================================

        $qrPath = '/stockage/qrcodes/' . $filename;

        // =====================================================
        // Enregistrer le QR dans la base de données
        // =====================================================

        $db = Database::getConnection();

  $stmt = $db->prepare("
    INSERT INTO qr_codes (
        bien_id,
        token,
        file_path,
        nb_scans,
        created_at
    )
    VALUES (
        :bien_id,
        :jeton,
        :file_path,
        0,
        NOW()
    )
");
    $stmt->execute([
            'bien_id'   => $bienId,
            'jeton'     => $jeton,
            'file_path' => $qrPath,
        ]);

        // =====================================================
        // Retourner le chemin du QR
        // =====================================================

        return $qrPath;
    }
}