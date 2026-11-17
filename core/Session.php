<?php

namespace Core;

class Session
{
    /**
     * Démarre la session si elle n'est pas déjà démarrée.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Enregistre une valeur dans la session.
     */
    public static function set(string $key, mixed $value): void
    {
        self::start();

        $_SESSION[$key] = $value;
    }

    /**
     * Récupère une valeur de session.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();

        return $_SESSION[$key] ?? $default;
    }

    /**
     * Vérifie si une clé existe dans la session.
     */
    public static function has(string $key): bool
    {
        self::start();

        return isset($_SESSION[$key]);
    }

    /**
     * Supprime une valeur de session.
     */
    public static function remove(string $key): void
    {
        self::start();

        unset($_SESSION[$key]);
    }

    /**
     * Déconnecte complètement l'utilisateur.
     */
    public static function destroy(): void
    {
        self::start();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    /**
     * Vérifie si un utilisateur est connecté.
     */
    public static function isAuthenticated(): bool
    {
        return self::has('user_id');
    }

    /**
     * Retourne l'ID de l'utilisateur connecté.
     */
    public static function userId(): ?string
    {
        $id = self::get('user_id');

        return $id !== null ? (string) $id : null;
    }

    /**
     * Retourne les informations de l'utilisateur connecté.
     */
    public static function user(): ?array
    {
        $user = self::get('user');

        return is_array($user) ? $user : null;
    }

    /**
     * Protège une page nécessitant une connexion.
     */
    public static function requireAuth(string $redirect = '/login'): void
    {
        if (!self::isAuthenticated()) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * Permet de mémoriser un message temporaire.
     */
    public static function flash(string $key, string $message): void
    {
        self::start();

        $_SESSION['_flash'][$key] = $message;
    }

    /**
     * Récupère puis supprime un message temporaire.
     */
    public static function getFlash(string $key): ?string
    {
        self::start();

        if (!isset($_SESSION['_flash'][$key])) {
            return null;
        }

        $message = $_SESSION['_flash'][$key];

        unset($_SESSION['_flash'][$key]);

        return $message;
    }
    
/**
 * Vérifie si l'utilisateur connecté est administrateur.
 */
public static function isAdmin(): bool
{
    if (!self::isAuthenticated()) {
        return false;
    }

    $user = self::user();

    if (!$user || !isset($user['role'])) {
        return false;
    }

    return strtolower(trim((string) $user['role'])) === 'admin';
}

/**
 * Vérifie si l'utilisateur connecté est modérateur.
 */
public static function isModerator(): bool
{
    if (!self::isAuthenticated()) {
        return false;
    }

    $user = self::user();

    if (!$user || !isset($user['role'])) {
        return false;
    }

    return strtolower(trim((string) $user['role'])) === 'moderateur';
}

/**
 * Vérifie si l'utilisateur est administrateur ou modérateur.
 */
public static function isAdminOrModerator(): bool
{
    return self::isAdmin() || self::isModerator();
}

/**
 * Protège une action réservée à l'administration
 * ou à la modération des biens.
 */
public static function requireAdminOrModerator(
    string $redirect = '/immo-sn/login'
): void {
    self::start();

    if (!self::isAuthenticated()) {
        header('Location: ' . $redirect);
        exit;
    }

    if (!self::isAdminOrModerator()) {
        http_response_code(403);
        exit('Accès refusé : cette action est réservée à l’administration et à la modération.');
    }
}


/**
 * Protège une page réservée aux administrateurs.
 */
public static function requireAdmin(
    string $redirect = '/immo-sn/login'
): void {
    self::start();

    if (!self::isAuthenticated()) {
        header('Location: ' . $redirect);
        exit;
    }

    if (!self::isAdmin()) {
        http_response_code(403);
        exit('Accès refusé : cette page est réservée aux administrateurs.');
    }
}
/**
 * Protège une page réservée aux modérateurs.
 */
public static function requireModerator(
    string $redirect = '/immo-sn/login'
): void {
    self::start();

    if (!self::isAuthenticated()) {
        header('Location: ' . $redirect);
        exit;
    }

    if (!self::isModerator()) {
        http_response_code(403);
        exit('Accès refusé : cette page est réservée aux modérateurs.');
    }
}
/**
 * Générer ou récupérer un jeton CSRF.
 */
public static function csrfToken(): string
{
    self::start();

    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf_token'];
}

/**
 * Vérifier un jeton CSRF.
 */
public static function verifyCsrfToken(?string $token): bool
{
    self::start();

    if (
        empty($_SESSION['_csrf_token']) ||
        empty($token)
    ) {
        return false;
    }

    return hash_equals($_SESSION['_csrf_token'], $token);
}
}