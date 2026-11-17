<?php

declare(strict_types=1);

namespace App\Mail;

use RuntimeException;

final class MailTemplate
{
    private string $templateDirectory;

    public function __construct(?string $templateDirectory = null)
    {
        $this->templateDirectory = $templateDirectory
            ?? dirname(__DIR__) . '/Views/emails';
    }

    /**
     * Charge un modèle HTML et remplace toutes ses variables
     * en échappant automatiquement les valeurs.
     */
    public function render(string $name, array $data = []): string
    {
        return $this->renderTemplate($name, $data, []);
    }

    /**
     * Charge un modèle HTML avec certaines variables HTML
     * explicitement autorisées comme contenu brut.
     *
     * Les autres variables restent échappées.
     */
    public function renderWithHtml(
        string $name,
        array $data = [],
        array $trustedHtml = []
    ): string {
        foreach ($trustedHtml as $key => $value) {
            if (
                !is_string($key)
                || !is_string($value)
            ) {
                throw new RuntimeException(
                    'Les variables HTML doivent être des chaînes de caractères.'
                );
            }

            if (!array_key_exists($key, $data)) {
                throw new RuntimeException(
                    'Variable HTML absente des données : ' . $key
                );
            }

            if ($data[$key] !== $value) {
                throw new RuntimeException(
                    'La variable HTML autorisée ne correspond pas aux données.'
                );
            }
        }

        return $this->renderTemplate(
            $name,
            $data,
            $trustedHtml
        );
    }

    /**
     * Effectue le remplacement des variables du modèle.
     */
    private function renderTemplate(
        string $name,
        array $data,
        array $trustedHtml
    ): string {
        if (
            $name === ''
            || !preg_match('/^[a-zA-Z0-9_-]+$/', $name)
        ) {
            throw new RuntimeException(
                'Nom de modèle e-mail invalide.'
            );
        }

        $file = $this->templateDirectory
            . DIRECTORY_SEPARATOR
            . $name
            . '.html';

        if (!is_file($file)) {
            throw new RuntimeException(
                'Modèle e-mail introuvable : ' . $name
            );
        }

        $html = file_get_contents($file);

        if ($html === false) {
            throw new RuntimeException(
                'Impossible de lire le modèle e-mail : ' . $name
            );
        }

        $html = preg_replace_callback(
            '/\{\{\s*([a-zA-Z0-9_.-]+)\s*\}\}/',
            static function (array $matches) use (
                $data,
                $trustedHtml
            ): string {
                $key = $matches[1];

                if (!array_key_exists($key, $data)) {
                    throw new RuntimeException(
                        'Variable manquante dans le modèle e-mail : '
                        . $key
                    );
                }

                $value = $data[$key];

                if (array_key_exists($key, $trustedHtml)) {
                    return $trustedHtml[$key];
                }

                if (
                    !is_string($value)
                    && !is_int($value)
                    && !is_float($value)
                ) {
                    throw new RuntimeException(
                        'Valeur invalide pour la variable : ' . $key
                    );
                }

                return htmlspecialchars(
                    (string) $value,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );
            },
            $html
        );

        if ($html === null) {
            throw new RuntimeException(
                'Erreur lors du remplacement des variables du modèle.'
            );
        }

        if (preg_match('/\{\{\s*[^{}]+\s*\}\}/', $html)) {
            throw new RuntimeException(
                'Le modèle contient des variables non remplacées : '
                . $name
            );
        }

        return $html;
    }
}