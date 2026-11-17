<?php
namespace App\Controllers;

use App\Models\Signalement;
use Core\Controller;
use Core\Database;
use Core\Session;
use Core\Validator;
use PDO;

class SignalementController extends Controller
{
    /**
     * Enregistrer un signalement.
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Méthode non autorisée.';
            return;
        }

        /*
         * ==========================================================
         * VÉRIFICATION DE L'AUTHENTIFICATION
         * ==========================================================
         */

        Session::start();

        if (!Session::isAuthenticated()) {
            header('Location: /immo-sn/login');
            exit;
        }

        /*
         * ==========================================================
         * UTILISATEUR CONNECTÉ
         * ==========================================================
         */

        $userId = Session::userId();

        if ($userId === null || $userId === '') {
            Session::flash(
                'error',
                'Votre session a expiré. Veuillez vous reconnecter.'
            );

            header('Location: /immo-sn/login');
            exit;
        }

        /*
         * ==========================================================
         * DONNÉES DU FORMULAIRE
         * ==========================================================
         */

        $bienId = trim($_POST['bien_id'] ?? '');
        $motif = trim($_POST['motif'] ?? '');

        try {

            /*
             * ======================================================
             * VALIDATION UUID DU BIEN
             * ======================================================
             */

            if (!$this->isValidUuid($bienId)) {
                throw new \Exception(
                    'Le bien sélectionné est invalide.'
                );
            }

            /*
             * ======================================================
             * VALIDATION DU MOTIF
             * ======================================================
             */

            if (!Validator::required($motif)) {
                throw new \Exception(
                    'Veuillez sélectionner un motif.'
                );
            }

            if (!Validator::maxLength($motif, 1000)) {
                throw new \Exception(
                    'Le motif du signalement est trop long.'
                );
            }

            /*
             * ======================================================
             * VÉRIFICATION DU BIEN
             * ======================================================
             */

            $stmt = Database::getConnection()->prepare('
                SELECT
                    id
                FROM biens
                WHERE id = :id
                LIMIT 1
            ');

            $stmt->execute([
                'id' => $bienId,
            ]);

            $bien = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$bien) {
                throw new \Exception(
                    'Le bien est introuvable.'
                );
            }

            /*
             * ======================================================
             * CRÉATION DU SIGNALEMENT
             * ======================================================
             */

            Signalement::creer(
                $bienId,
                $userId,
                $motif
            );

            /*
             * ======================================================
             * RETOUR SUR LA FICHE DU BIEN
             * ======================================================
             */

            header(
                'Location: /immo-sn/biens/'
                . rawurlencode($bienId)
                . '?signalement=success'
            );

            exit;

        } catch (\Throwable $e) {

            /*
             * ======================================================
             * RETOUR EN CAS D'ERREUR
             * ======================================================
             */

            if ($this->isValidUuid($bienId)) {

                header(
                    'Location: /immo-sn/biens/'
                    . rawurlencode($bienId)
                    . '?signalement=error&error='
                    . rawurlencode($e->getMessage())
                );

                exit;
            }

            http_response_code(400);

            echo htmlspecialchars(
                $e->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            );
        }
    }

    /**
     * Vérifier qu'une valeur est un UUID valide.
     */
    private function isValidUuid(string $uuid): bool
    {
        return (bool) preg_match(
            '/^[0-9a-fA-F]{8}-'
            . '[0-9a-fA-F]{4}-'
            . '[1-5][0-9a-fA-F]{3}-'
            . '[89abAB][0-9a-fA-F]{3}-'
            . '[0-9a-fA-F]{12}$/',
            $uuid
        );
    }
}
