<?php
namespace App\Middleware;
use Core\Session;

final class AuthMiddleware
{
    /**
     * Vérifie que l'utilisateur est authentifié.
     */
    public static function handle(): bool
    {
        if (!Session::isAuthenticated()) {
            header('Location: /immo-sn/login');
            exit;
        }

        return true;
    }
}
