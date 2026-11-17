<?php
namespace Core;

final class Validator
{
    /**
     * Vérifie qu'une valeur obligatoire n'est pas vide.
     */
    public static function required(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return $value !== [];
        }

        return true;
    }

    /**
     * Vérifie qu'une adresse e-mail est valide.
     */
    public static function email(string $email): bool
    {
        return filter_var(
            trim($email),
            FILTER_VALIDATE_EMAIL
        ) !== false;
    }

    /**
     * Vérifie la longueur minimale d'un texte.
     * Prend en charge les caractères accentués.
     */
    public static function minLength(
        string $value,
        int $minimum
    ): bool {
        if ($minimum < 0) {
            return false;
        }

        $length = function_exists('mb_strlen')
            ? mb_strlen($value, 'UTF-8')
            : strlen($value);

        return $length >= $minimum;
    }

    /**
     * Vérifie la longueur maximale d'un texte.
     * Prend en charge les caractères accentués.
     */
    public static function maxLength(
        string $value,
        int $maximum
    ): bool {
        if ($maximum < 0) {
            return false;
        }

        $length = function_exists('mb_strlen')
            ? mb_strlen($value, 'UTF-8')
            : strlen($value);

        return $length <= $maximum;
    }

    /**
     * Vérifie que deux valeurs sont identiques.
     */
    public static function matches(
        string $value,
        string $confirmation
    ): bool {
        return $value === $confirmation;
    }
}
