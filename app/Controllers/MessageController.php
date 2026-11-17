<?php
namespace App\Controllers;

use App\Models\Message;
use Core\Controller;
use Core\Database;
use Core\Session;
use Core\Validator;
use PDO;

class MessageController extends Controller
{
    /**
     * Envoyer un message au propriétaire d'un bien.
     */
    public function envoyer(): void
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

        $expediteurId = Session::userId();

        if ($expediteurId === null || $expediteurId === '') {
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
        $contenu = trim($_POST['contenu'] ?? '');

        try {

            /*
             * ======================================================
             * VALIDATION DE L'UUID DU BIEN
             * ======================================================
             */

            if (!$this->isValidUuid($bienId)) {
                throw new \Exception(
                    'Le bien sélectionné est invalide.'
                );
            }

            /*
             * ======================================================
             * VALIDATION DU MESSAGE
             * ======================================================
             */

            if (!Validator::required($contenu)) {
                throw new \Exception(
                    'Veuillez écrire un message.'
                );
            }

            if (!Validator::minLength($contenu, 3)) {
                throw new \Exception(
                    'Votre message est trop court.'
                );
            }

            if (!Validator::maxLength($contenu, 5000)) {
                throw new \Exception(
                    'Votre message est trop long.'
                );
            }

            /*
             * ======================================================
             * RÉCUPÉRATION DU BIEN ET DU PROPRIÉTAIRE
             * ======================================================
             *
             * IMPORTANT :
             * Nous ne faisons pas confiance au destinataire_id
             * envoyé par le navigateur.
             *
             * Le véritable propriétaire est récupéré depuis
             * la base de données.
             */

            $stmt = Database::getConnection()->prepare('
                SELECT
                    id,
                    titre,
                    user_id
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
             * PROPRIÉTAIRE
             * ======================================================
             */

            $destinataireId = trim(
                (string) ($bien['user_id'] ?? '')
            );

            if (!Validator::required($destinataireId)) {
                throw new \Exception(
                    'Le propriétaire de ce bien est introuvable.'
                );
            }

            /*
             * ======================================================
             * VÉRIFICATION UUID PROPRIÉTAIRE
             * ======================================================
             */

            if (!$this->isValidUuid($destinataireId)) {
                throw new \Exception(
                    'L’identifiant du propriétaire est invalide.'
                );
            }

            /*
             * ======================================================
             * EMPÊCHER L'AUTO-MESSAGE
             * ======================================================
             */

            if (
                strtolower($expediteurId)
                === strtolower($destinataireId)
            ) {
                throw new \Exception(
                    'Vous ne pouvez pas vous envoyer un message à vous-même.'
                );
            }

            /*
             * ======================================================
             * ENVOI DU MESSAGE
             * ======================================================
             */

            Message::envoyer(
                $bienId,
                $expediteurId,
                $destinataireId,
                'Demande concernant : ' . $bien['titre'],
                $contenu
            );

            /*
             * ======================================================
             * RETOUR SUR LA FICHE DU BIEN
             * ======================================================
             */

            header(
                'Location: /immo-sn/biens/'
                . rawurlencode($bienId)
                . '?message=success'
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
                    . '?message=error&error='
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
