<?php

namespace App\Controllers;

use App\Models\Alerte;
use App\Models\Bien;
use Core\Controller;
use Core\Session;

class AlerteController extends Controller
{
    /**
     * =========================================================
     * UTILISATEUR CONNECTÉ
     * =========================================================
     */
    private function utilisateurConnecte(): string
    {
        Session::requireAuth('/immo-sn/login');

        $userId = Session::userId();

        if (!$userId) {
            Session::destroy();

            header('Location: /immo-sn/login');
            exit;
        }

        return $userId;
    }


    public function index(): void
{
    $userId = $this->utilisateurConnecte();

    $alertes = Alerte::parUtilisateur($userId);

    $categories = Bien::categories();
    $villes = Bien::villes();

    $this->view(
        'pages/member/alertes',
        [
            'title' => 'Mes alertes — ImmoSn',
            'alertes' => $alertes,
            'categories' => $categories,
            'villes' => $villes,
        ]
    );
}

    /**
     * =========================================================
     * CRÉER UNE ALERTE
     * =========================================================
     */
    public function creer(): void
    {
        $userId = $this->utilisateurConnecte();

        $categories = Bien::categories();
        $villes = Bien::villes();

        /*
         * Affichage du formulaire.
         */
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->view(
                'pages/member/alerte-creer',
                [
                    'title' => 'Créer une alerte — ImmoSn',
                    'categories' => $categories,
                    'villes' => $villes,
                    'error' => null,
                ]
            );

            return;
        }


        /*
         * Traitement.
         */
        try {

            $categoryId = !empty($_POST['category_id'])
                ? (int) $_POST['category_id']
                : null;

            $villeId = !empty($_POST['ville_id'])
                ? (int) $_POST['ville_id']
                : null;

            $budgetMax = trim(
                $_POST['budget_max'] ?? ''
            );


            /*
             * Validation du budget.
             */
            if ($budgetMax === '') {
                throw new \Exception(
                    'Veuillez saisir un budget maximum.'
                );
            }

            if (!is_numeric($budgetMax)) {
                throw new \Exception(
                    'Le budget maximum doit être un nombre valide.'
                );
            }

            $budgetMax = (float) $budgetMax;

            if ($budgetMax <= 0) {
                throw new \Exception(
                    'Le budget maximum doit être supérieur à zéro.'
                );
            }


            /*
             * Création.
             */
            Alerte::creer(
                $userId,
                $categoryId,
                $villeId,
                $budgetMax
            );


            /*
             * Retour à la liste.
             */
            header(
                'Location: /immo-sn/member/alertes?success=created'
            );

            exit;

        } catch (\Throwable $e) {

            $this->view(
                'pages/member/alerte-creer',
                [
                    'title' =>
                        'Créer une alerte — ImmoSn',

                    'categories' =>
                        $categories,

                    'villes' =>
                        $villes,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return;
        }
    }


    /**
     * =========================================================
     * MODIFIER UNE ALERTE
     * =========================================================
     */
    public function modifier(string $id): void
    {
        $userId = $this->utilisateurConnecte();

        $alerte = Alerte::trouverPourUtilisateur(
            $id,
            $userId
        );

        if (!$alerte) {
            http_response_code(404);

            echo 'Alerte introuvable.';

            return;
        }

        $categories = Bien::categories();
        $villes = Bien::villes();


        /*
         * Affichage.
         */
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->view(
                'pages/member/alerte-modifier',
                [
                    'title' =>
                        'Modifier une alerte — ImmoSn',

                    'alerte' =>
                        $alerte,

                    'categories' =>
                        $categories,

                    'villes' =>
                        $villes,

                    'error' =>
                        null,
                ]
            );

            return;
        }


        /*
         * Traitement.
         */
        try {

            $categoryId = !empty($_POST['category_id'])
                ? (int) $_POST['category_id']
                : null;

            $villeId = !empty($_POST['ville_id'])
                ? (int) $_POST['ville_id']
                : null;

            $budgetMax = trim(
                $_POST['budget_max'] ?? ''
            );


            if ($budgetMax === '') {
                throw new \Exception(
                    'Veuillez saisir un budget maximum.'
                );
            }

            if (!is_numeric($budgetMax)) {
                throw new \Exception(
                    'Le budget maximum doit être un nombre valide.'
                );
            }

            $budgetMax = (float) $budgetMax;

            if ($budgetMax <= 0) {
                throw new \Exception(
                    'Le budget maximum doit être supérieur à zéro.'
                );
            }


            Alerte::modifier(
                $id,
                $userId,
                $categoryId,
                $villeId,
                $budgetMax
            );


            header(
                'Location: /immo-sn/member/alertes?success=updated'
            );

            exit;

        } catch (\Throwable $e) {

            /*
             * On remet les valeurs saisies
             * dans la vue en cas d'erreur.
             */
            $alerte['category_id'] =
                $categoryId ?? null;

            $alerte['ville_id'] =
                $villeId ?? null;

            $alerte['budget_max'] =
                $budgetMax ?? $alerte['budget_max'];


            $this->view(
                'pages/member/alerte-modifier',
                [
                    'title' =>
                        'Modifier une alerte — ImmoSn',

                    'alerte' =>
                        $alerte,

                    'categories' =>
                        $categories,

                    'villes' =>
                        $villes,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return;
        }
    }


    /**
     * =========================================================
     * SUPPRIMER UNE ALERTE
     * =========================================================
     */
    public function supprimer(string $id): void
    {
        $userId = $this->utilisateurConnecte();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);

            echo 'Méthode non autorisée.';

            return;
        }

        Alerte::supprimer(
            $id,
            $userId
        );

        header(
            'Location: /immo-sn/member/alertes?success=deleted'
        );

        exit;
    }


    /**
     * =========================================================
     * ACTIVER / DÉSACTIVER
     * =========================================================
     */
    public function basculer(string $id): void
    {
        $userId = $this->utilisateurConnecte();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);

            echo 'Méthode non autorisée.';

            return;
        }


        $alerte = Alerte::trouverPourUtilisateur(
            $id,
            $userId
        );

        if (!$alerte) {

            http_response_code(404);

            echo 'Alerte introuvable.';

            return;
        }


        $nouvelEtat =
            empty($alerte['is_active']);


        Alerte::changerStatut(
            $id,
            $userId,
            $nouvelEtat
        );


        header(
            'Location: /immo-sn/member/alertes'
        );

        exit;
    }
}