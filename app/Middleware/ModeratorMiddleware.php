<?php
namespace App\Middleware;
use Core\Session;

final class ModeratorMiddleware
{
    /**
     * Autorise uniquement les modérateurs.
     */
    public static function handle(): bool
    {
        Session::requireModerator();

        return true;
    }
}