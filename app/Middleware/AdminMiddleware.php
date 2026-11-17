<?php
namespace App\Middleware;
use Core\Session;

final class AdminMiddleware
{
    /**
     * Autorise uniquement les administrateurs.
     */
    public static function handle(): bool
    {
        Session::requireAdmin();

        return true;
    }
}