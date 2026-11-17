<?php

namespace App\Controllers;

use Core\Controller;
use Core\Database;
use App\Models\Bien;
use PDO;

class HomeController extends Controller
{
    public function index(): void
    {
        $db = Database::getConnection();

        // Biens actifs à afficher sur la page d'accueil
        $biens = Bien::actifs(6);

        // Nombre réel de biens actifs
        $stmt = $db->query("
            SELECT COUNT(*)
            FROM biens
            WHERE statut = 'actif'
              AND (
                    expire_at IS NULL
                    OR expire_at > NOW()
              )
        ");

        $totalBiens = (int) $stmt->fetchColumn();

        // Nombre réel de quartiers couverts
        $stmt = $db->query("
            SELECT COUNT(DISTINCT v.quartier)
            FROM biens b
            INNER JOIN villes v
                ON v.id = b.ville_id
            WHERE b.statut = 'actif'
              AND (
                    b.expire_at IS NULL
                    OR b.expire_at > NOW()
              )
              AND v.quartier IS NOT NULL
              AND TRIM(v.quartier) <> ''
        ");

        $totalQuartiers = (int) $stmt->fetchColumn();

        // Nombre réel d'agences
        $stmt = $db->query("
            SELECT COUNT(*)
            FROM agences
        ");

        $totalAgences = (int) $stmt->fetchColumn();

        // Résumé des statistiques
        $resume = [
            'biens' => $totalBiens,
            'quartiers' => $totalQuartiers,
            'agences' => $totalAgences,
        ];

        // Toutes les catégories avec leur nombre réel de biens
        $stmt = $db->query("
            SELECT
                c.id,
                c.libelle,
                c.slug,
                COUNT(b.id) AS total
            FROM categories_biens c
            LEFT JOIN biens b
                ON b.category_id = c.id
                AND b.statut = 'actif'
                AND (
                    b.expire_at IS NULL
                    OR b.expire_at > NOW()
                )
            GROUP BY
                c.id,
                c.libelle,
                c.slug
            ORDER BY
                total DESC,
                c.libelle ASC
        ");

        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

      

        // Agences
        $agences = \App\Models\Agence::toutes();

        // Affichage de la page
        $this->view('pages/home', [
            'title' => 'ImmoSn — Trouvez votre bien immobilier',
            'biens' => $biens,
           'stats' => $resume,
            'agences' => $agences,
            'resume' => $resume,
            'categories' => $categories,
        ]);
    }
}