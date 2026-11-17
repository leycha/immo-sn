<?php
declare(strict_types=1);
namespace Core;

class Uploader
{
    /**
     * Upload plusieurs photos pour un bien.
     */
    public static function uploadPhotos(array $files, string $bienId): array
    {
        if (empty($files)) {
            return [];
        }

        if (
            !isset($files['name']) ||
            !is_array($files['name'])
        ) {
            return [];
        }

        $directory = BASE_PATH . '/stockage/photos';

        if (!is_dir($directory)) {
            if (
                !mkdir($directory, 0775, true) &&
                !is_dir($directory)
            ) {
                throw new \RuntimeException(
                    'Impossible de créer le dossier stockage/photos.'
                );
            }
        }

        $nombreFichiers = count($files['name']);

        if ($nombreFichiers > 10) {
            throw new \RuntimeException(
                'Vous pouvez ajouter au maximum 10 photos.'
            );
        }

        $photos = [];

        $typesAutorises = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
        ];

        for ($i = 0; $i < $nombreFichiers; $i++) {

            $nomOriginal = $files['name'][$i] ?? '';
            $tmpName = $files['tmp_name'][$i] ?? '';
            $error = $files['error'][$i] ?? UPLOAD_ERR_NO_FILE;
            $size = $files['size'][$i] ?? 0;

            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($error !== UPLOAD_ERR_OK) {
                throw new \RuntimeException(
                    'Une erreur est survenue pendant l\'upload de la photo : '
                    . $nomOriginal
                );
            }

            if ($size > 5 * 1024 * 1024) {
                throw new \RuntimeException(
                    'La photo "' . $nomOriginal .
                    '" dépasse la taille maximale de 5 Mo.'
                );
            }

            if (!is_uploaded_file($tmpName)) {
                throw new \RuntimeException(
                    'Le fichier "' . $nomOriginal .
                    '" n\'est pas un upload valide.'
                );
            }

            $mime = '';

            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                if ($finfo !== false) {
                    $mime = (string) finfo_file(
                        $finfo,
                        $tmpName
                    );

                    finfo_close($finfo);
                }
            }

            $imageInfo = @getimagesize($tmpName);

            if ($imageInfo === false) {
                throw new \RuntimeException(
                    'Le fichier "' . $nomOriginal .
                    '" n\'est pas une image valide.'
                );
            }

            if ($mime === '') {
                $mime = $imageInfo['mime'] ?? '';
            }

            if (!isset($typesAutorises[$mime])) {
                throw new \RuntimeException(
                    'La photo "' . $nomOriginal .
                    '" doit être au format JPG ou PNG.'
                );
            }

            $extension = $typesAutorises[$mime];

            $nomFichier =
                $bienId
                . '_'
                . bin2hex(random_bytes(8))
                . '.'
                . $extension;

            $destination = $directory . '/' . $nomFichier;

            if (!move_uploaded_file($tmpName, $destination)) {
                throw new \RuntimeException(
                    'Impossible d\'enregistrer la photo "' .
                    $nomOriginal . '".'
                );
            }

            $cheminWeb =
                '/immo-sn/stockage/photos/'
                . $nomFichier;

            $photos[] = [
                'file_path' => $cheminWeb,
                'is_principal' => count($photos) === 0,
            ];
        }

        return $photos;
    }

    /**
     * Upload du logo d'une agence.
     *
     * Formats acceptés : JPG, PNG et WebP.
     * Taille maximale : 2 Mo.
     *
     * Retourne le chemin web du logo.
     */
    public static function uploadLogoAgence(array $file): ?string
    {
        $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException(
                'Une erreur est survenue pendant l\'envoi du logo.'
            );
        }

        $tmpName = $file['tmp_name'] ?? '';
        $size = (int) ($file['size'] ?? 0);

        if (!is_uploaded_file($tmpName)) {
            throw new \RuntimeException(
                'Le fichier du logo n\'est pas un upload valide.'
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
                'Le fichier sélectionné n\'est pas une image valide.'
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
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        if (
            !isset($typesAutorises[$mime]) ||
            ($imageInfo['mime'] ?? '') !== $mime
        ) {
            throw new \RuntimeException(
                'Le logo doit être une image JPG, PNG ou WebP.'
            );
        }

        $directory = BASE_PATH . '/stockage/logos';

        if (!is_dir($directory)) {
            if (
                !mkdir($directory, 0775, true) &&
                !is_dir($directory)
            ) {
                throw new \RuntimeException(
                    'Impossible de créer le dossier stockage/logos.'
                );
            }
        }

        $extension = $typesAutorises[$mime];

        $nomFichier =
            'agence_'
            . bin2hex(random_bytes(12))
            . '.'
            . $extension;

        $destination = $directory . '/' . $nomFichier;

        if (!move_uploaded_file($tmpName, $destination)) {
            throw new \RuntimeException(
                'Impossible d\'enregistrer le logo de l\'agence.'
            );
        }

        return '/immo-sn/stockage/logos/' . $nomFichier;
    }
}
