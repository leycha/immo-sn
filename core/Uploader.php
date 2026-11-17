<?php

namespace Core;

class Uploader
{
    /**
     * Envoie une image vers Cloudinary.
     * Retourne son URL HTTPS permanente.
     */
    private static function envoyerVersCloudinary(
        string $tmpName,
        string $dossier,
        string $nomFichier
    ): string {
        $cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: '';
        $apiKey = getenv('CLOUDINARY_API_KEY') ?: '';
        $apiSecret = getenv('CLOUDINARY_API_SECRET') ?: '';

        if ($cloudName === '' || $apiKey === '' || $apiSecret === '') {
            throw new \RuntimeException(
                'Configuration Cloudinary incomplète.'
            );
        }

        $timestamp = time();

        $parametres = [
            'folder' => $dossier,
            'public_id' => $nomFichier,
            'timestamp' => $timestamp,
        ];

        ksort($parametres);

        $chaineSignature = [];

        foreach ($parametres as $cle => $valeur) {
            $chaineSignature[] = $cle . '=' . $valeur;
        }

        $signature = sha1(
            implode('&', $chaineSignature) . $apiSecret
        );

        $mime = mime_content_type($tmpName) ?: 'application/octet-stream';

        $url = 'https://api.cloudinary.com/v1_1/'
            . rawurlencode($cloudName)
            . '/image/upload';

        $donnees = [
            'file' => new \CURLFile($tmpName, $mime, $nomFichier),
            'api_key' => $apiKey,
            'timestamp' => $timestamp,
            'folder' => $dossier,
            'public_id' => $nomFichier,
            'signature' => $signature,
        ];

        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $donnees,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 120,
        ]);

        $reponse = curl_exec($curl);
        $erreur = curl_error($curl);
        $codeHTTP = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($reponse === false) {
            throw new \RuntimeException(
                'Erreur de connexion Cloudinary : ' . $erreur
            );
        }

        $resultat = json_decode($reponse, true);

        if (
            $codeHTTP < 200 ||
            $codeHTTP >= 300 ||
            !is_array($resultat) ||
            empty($resultat['secure_url'])
        ) {
            $message = $resultat['error']['message']
                ?? 'Réponse invalide de Cloudinary.';

            throw new \RuntimeException($message);
        }

        return (string) $resultat['secure_url'];
    }

    /**
     * Upload plusieurs photos pour un bien.
     */
    public static function uploadPhotos(
        array $files,
        string $bienId
    ): array {
        if (empty($files) || !isset($files['name']) ||
            !is_array($files['name'])) {
            return [];
        }

        $nombreFichiers = count($files['name']);

        if ($nombreFichiers > 10) {
            throw new \RuntimeException(
                'Vous pouvez ajouter au maximum 10 photos.'
            );
        }

        $typesAutorises = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];

        $photos = [];

        for ($i = 0; $i < $nombreFichiers; $i++) {
            $nomOriginal = $files['name'][$i] ?? '';
            $tmpName = $files['tmp_name'][$i] ?? '';
            $error = $files['error'][$i] ?? UPLOAD_ERR_NO_FILE;
            $size = (int) ($files['size'][$i] ?? 0);

            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($error !== UPLOAD_ERR_OK) {
                throw new \RuntimeException(
                    'Erreur pendant l’envoi de la photo : ' . $nomOriginal
                );
            }

            if ($size <= 0 || $size > 5 * 1024 * 1024) {
                throw new \RuntimeException(
                    'La photo "' . $nomOriginal .
                    '" ne doit pas dépasser 5 Mo.'
                );
            }

            if (!is_uploaded_file($tmpName)) {
                throw new \RuntimeException(
                    'Le fichier "' . $nomOriginal .
                    '" n’est pas un upload valide.'
                );
            }

            $imageInfo = @getimagesize($tmpName);

            if ($imageInfo === false) {
                throw new \RuntimeException(
                    'Le fichier "' . $nomOriginal .
                    '" n’est pas une image valide.'
                );
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo
                ? finfo_file($finfo, $tmpName)
                : ($imageInfo['mime'] ?? '');

            if ($finfo) {
                finfo_close($finfo);
            }

            if (
                !isset($typesAutorises[$mime]) ||
                ($imageInfo['mime'] ?? '') !== $mime
            ) {
                throw new \RuntimeException(
                    'La photo doit être au format JPG ou PNG.'
                );
            }

            $nomFichier = $bienId . '_' . bin2hex(random_bytes(8));

            $url = self::envoyerVersCloudinary(
                $tmpName,
                'immo-sn/photos',
                $nomFichier
            );

            $photos[] = [
                'file_path' => $url,
                'is_principal' => count($photos) === 0,
            ];
        }

        return $photos;
    }

    /**
     * Upload du logo d'une agence.
     */
    public static function uploadLogoAgence(array $file): ?string
    {
        $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException(
                'Une erreur est survenue pendant l’envoi du logo.'
            );
        }

        $tmpName = $file['tmp_name'] ?? '';
        $size = (int) ($file['size'] ?? 0);

        if (!is_uploaded_file($tmpName)) {
            throw new \RuntimeException(
                'Le fichier du logo n’est pas un upload valide.'
            );
        }

        if ($size <= 0 || $size > 2 * 1024 * 1024) {
            throw new \RuntimeException(
                'Le logo ne doit pas dépasser 2 Mo.'
            );
        }

        $imageInfo = @getimagesize($tmpName);

        if ($imageInfo === false) {
            throw new \RuntimeException(
                'Le fichier sélectionné n’est pas une image valide.'
            );
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            throw new \RuntimeException(
                'Impossible de vérifier le type du logo.'
            );
        }

        $mime = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        $typesAutorises = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (
            !isset($typesAutorises[$mime]) ||
            ($imageInfo['mime'] ?? '') !== $mime
        ) {
            throw new \RuntimeException(
                'Le logo doit être au format JPG, PNG ou WebP.'
            );
        }

        $nomFichier = 'agence_' . bin2hex(random_bytes(12));

        return self::envoyerVersCloudinary(
            $tmpName,
            'immo-sn/logos',
            $nomFichier
        );
    }
}
