<?php
namespace App\Middleware;
use Core\Session;

final class AgencyMiddleware
{
    /**
     * Autorise uniquement les agences.
     */
    public static function handle(): bool
    {
        if (!Session::isAuthenticated()) {
            header('Location: /immo-sn/login');
            exit;
        }

        $user = Session::user();
        $role = strtolower(trim((string) ($user['role'] ?? '')));

        if ($role !== 'agence') {
            http_response_code(403);
            exit('Accès refusé : cette page est réservée aux agences.');
        }

        return true;
    }
}