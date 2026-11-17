<?php
namespace App\Middleware;
use Core\Session;

final class MemberMiddleware
{
    /**
     * Autorise uniquement les particuliers.
     */
    public static function handle(): bool
    {
        if (!Session::isAuthenticated()) {
            header('Location: /immo-sn/login');
            exit;
        }

        $user = Session::user();
        $role = strtolower(trim((string) ($user['role'] ?? '')));

        if ($role !== 'particulier') {
            http_response_code(403);
            exit('Accès refusé : cette page est réservée aux particuliers.');
        }

        return true;
    }
}