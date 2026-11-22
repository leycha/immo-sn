<?php

namespace Core;

use RuntimeException;

class MailService
{
    private string $apiKey;
    private string $from;
    private string $fromName;

    public function __construct()
    {
        // Initialisation centrale de IMMO-SN.
        require_once dirname(__DIR__) . '/config/bootstrap.php';

        // Chargement de la configuration Brevo.
        $config = require BASE_PATH . '/config/mail.php';

        $this->apiKey = $config['api_key'];
        $this->from = $config['from'];
        $this->fromName = $config['from_name'];

        // Vérification de la configuration.
        if ($this->apiKey === '') {
            throw new RuntimeException(
                'Configuration Brevo incomplète : BREVO_API_KEY est manquante.'
            );
        }

        if ($this->from === '') {
            throw new RuntimeException(
                'Configuration e-mail incomplète : MAIL_FROM est manquante.'
            );
        }

        if (!filter_var($this->from, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException(
                'MAIL_FROM doit être une adresse e-mail valide.'
            );
        }

        if (!function_exists('curl_init')) {
            throw new RuntimeException(
                'L’extension PHP cURL est nécessaire pour envoyer les e-mails.'
            );
        }
    }

    /**
     * Envoyer un e-mail via l'API HTTPS de Brevo.
     */
    public function send(
        string $to,
        string $subject,
        string $htmlBody,
        ?string $textBody = null
    ): void {

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException(
                'Adresse du destinataire invalide.'
            );
        }

        // Préparation du contenu texte.
        $plainText = $textBody;

        if ($plainText === null) {
            $plainText = str_replace(
                ['<br>', '<br/>', '<br />', '</p>'],
                "\n",
                $htmlBody
            );

            $plainText = html_entity_decode(
                strip_tags($plainText),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            );

            $plainText = trim($plainText);
        }

        // Construction du message.
        $payload = [
            'sender' => [
                'name' => $this->fromName,
                'email' => $this->from,
            ],

            'to' => [
                [
                    'email' => $to,
                ],
            ],

            'subject' => $subject,
            'htmlContent' => $htmlBody,
            'textContent' => $plainText,
        ];

        try {
            $jsonPayload = json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $e) {
            throw new RuntimeException(
                'Impossible de préparer le contenu de l’e-mail : '
                . $e->getMessage(),
                0,
                $e
            );
        }

        // Connexion à l'API officielle de Brevo.
        $curl = curl_init(
            'https://api.brevo.com/v3/smtp/email'
        );

        if ($curl === false) {
            throw new RuntimeException(
                'Impossible d’initialiser la connexion à Brevo.'
            );
        }

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'api-key: ' . $this->apiKey,
                'content-type: application/json',
            ],

            CURLOPT_POSTFIELDS => $jsonPayload,

            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($curl);

        $curlError = curl_error($curl);
        $httpCode = (int) curl_getinfo(
            $curl,
            CURLINFO_HTTP_CODE
        );

        curl_close($curl);

        // Erreur de connexion.
        if ($response === false) {
            throw new RuntimeException(
                'Connexion à l’API Brevo impossible : '
                . $curlError
            );
        }

        // Décodage de la réponse.
        $responseData = json_decode(
            $response,
            true
        );

        // Vérification du résultat.
        if ($httpCode < 200 || $httpCode >= 300) {

            $errorMessage = 'Réponse inattendue de Brevo.';

            if (is_array($responseData)) {
                $errorMessage = (string) (
                    $responseData['message']
                    ?? $responseData['code']
                    ?? $errorMessage
                );
            }

            throw new RuntimeException(
                'Erreur API Brevo (HTTP '
                . $httpCode
                . ') : '
                . $errorMessage
            );
        }

        // Envoi accepté par Brevo.
    }
}
