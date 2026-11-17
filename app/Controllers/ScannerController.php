<?php
namespace App\Controllers;

use Core\Controller;
use Core\Database;

class ScannerController extends Controller
{
    /**
     * Affiche la page scanner
     */
    public function index(): void
    {
        $this->view('pages/scanner', [
            'title' => 'Scanner un QR Code — ImmoSn',
        ]);
    }

    /**
     * Résout un token QR → redirige vers le bien
     */
    public function resolve(string $token): void
    {
        $pdo = Database::getConnection();

        // Cherche le QR code par token
        $stmt = $pdo->prepare("SELECT * FROM qr_codes WHERE token = :token LIMIT 1");
        $stmt->execute(['token' => $token]);
        $qr = $stmt->fetch();

        if (!$qr) {
            http_response_code(404);
            $this->view('pages/scanner-error', [
                'title' => 'QR Code non reconnu',
                'message' => 'Ce QR code n\'existe pas ou a été supprimé.',
            ]);
            return;
        }

        // Incrémente le compteur de scans
        $pdo->prepare("UPDATE qr_codes 
                       SET nb_scans = nb_scans + 1, last_scan_at = NOW() 
                       WHERE id = :id")
            ->execute(['id' => $qr['id']]);

        // Redirige vers la page du bien
       header("Location: /immo-sn/biens/" . rawurlencode($qr['bien_id']));
       exit;
    }
}