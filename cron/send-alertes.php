<?php

declare(strict_types=1);

use App\Mail\Mailer;
use Core\Database;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/core/Database.php';
require_once __DIR__ . '/CronBase.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Access denied: CLI only.\n");
}

final class SendAlertesCron extends CronBase
{
    protected function execute(): int
    {
        $dryRun = in_array(
            '--dry-run',
            $GLOBALS['argv'] ?? [],
            true
        );

        $db = Database::getConnection();

        $sql = "
            SELECT
                a.id AS alerte_id,
                a.user_id,
                u.nom,
                u.prenom,
                u.email,
                b.id AS bien_id,
                b.titre,
                b.prix,
                b.type_offre,
                v.ville AS nom_ville,
                v.quartier
            FROM public.alertes a
            INNER JOIN public.users u
                ON u.id = a.user_id
            INNER JOIN public.biens b
                ON (a.category_id IS NULL OR a.category_id = b.category_id)
               AND (a.ville_id IS NULL OR a.ville_id = b.ville_id)
               AND b.prix <= a.budget_max
            LEFT JOIN public.villes v
                ON v.id = b.ville_id
            WHERE a.is_active IS TRUE
              AND LOWER(BTRIM(COALESCE(b.statut, ''))) = 'actif'
              AND b.expire_at > CURRENT_TIMESTAMP
              AND NOT EXISTS (
                  SELECT 1
                  FROM public.alerte_logs al
                  WHERE al.alerte_id = a.id
                    AND al.bien_id = b.id
              )
            ORDER BY u.id, b.created_at DESC
        ";

        $matches = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        if ($matches === []) {
            echo "No new property matches for alerts.\n";
            return 0;
        }

        // Regrouper les annonces par utilisateur.
        $parUtilisateur = [];

        foreach ($matches as $match) {
            $userId = (string) $match['user_id'];

            if (!isset($parUtilisateur[$userId])) {
                $parUtilisateur[$userId] = [
                    'user' => [
                        'id' => $userId,
                        'nom' => (string) $match['nom'],
                        'prenom' => (string) $match['prenom'],
                        'email' => trim((string) $match['email']),
                    ],
                    'matches' => [],
                ];
            }

            $parUtilisateur[$userId]['matches'][] = $match;
        }

        $mailer = null;
        $envoyes = 0;
        $erreurs = 0;

        // URL de la plateforme définie dans .env.
       // Récupération de l'URL depuis la configuration centrale.
$appConfig = require BASE_PATH . '/config/app.php';
$baseUrl = $appConfig['url'];

        foreach ($parUtilisateur as $groupe) {
            $user = $groupe['user'];
            $userMatches = $groupe['matches'];

            $email = $user['email'];
            $userId = $user['id'];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs++;

                echo "Invalid email for user {$userId}.\n";
                continue;
            }

            // Le mode test ne déclenche aucun envoi.
            if ($dryRun) {
                echo "[DRY-RUN] {$email}: "
                    . count($userMatches)
                    . " alert/property match(es).\n";

                continue;
            }

            $itemsHtml = '';
            $itemsText = '';

            foreach ($userMatches as $match) {
                $titre = (string) $match['titre'];

                $prix = number_format(
                    (float) $match['prix'],
                    0,
                    ',',
                    ' '
                );

                $ville = trim(
                    (string) ($match['nom_ville'] ?? '')
                );

                $quartier = trim(
                    (string) ($match['quartier'] ?? '')
                );

                $localisation = $ville;

                if ($quartier !== '') {
                    $localisation = $localisation !== ''
                        ? $localisation . ' - ' . $quartier
                        : $quartier;
                }

                // Échapper chaque donnée avant son insertion HTML.
                $itemsHtml .= '<li><strong>'
                    . htmlspecialchars(
                        $titre,
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    )
                    . '</strong> - '
                    . htmlspecialchars(
                        $prix,
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    )
                    . ' FCFA';

                if ($localisation !== '') {
                    $itemsHtml .= ' - '
                        . htmlspecialchars(
                            $localisation,
                            ENT_QUOTES | ENT_SUBSTITUTE,
                            'UTF-8'
                        );
                }

                $itemsHtml .= '</li>';

                // Construire également la version texte.
                $itemsText .= '- '
                    . $titre
                    . ' : '
                    . $prix
                    . ' FCFA';

                if ($localisation !== '') {
                    $itemsText .= ' - ' . $localisation;
                }

                $itemsText .= "\n";
            }

            $nom = trim(
                $user['prenom'] . ' ' . $user['nom']
            );

            if ($nom === '') {
                $nom = 'Cher utilisateur';
            }

            $subject = 'IMMO-SN : de nouveaux biens correspondent à votre alerte';

            $texte = "Bonjour {$nom},\n\n"
                . "De nouveaux biens correspondent à votre alerte IMMO-SN :\n\n"
                . $itemsText
                . "\nConsultez les annonces sur IMMO-SN :\n"
                . $baseUrl
                . "\n\nL'équipe IMMO-SN\n";

            // Envoi via le système centralisé.
            try {
                if ($mailer === null) {
                    $mailer = new Mailer();
                }

                $mailer->sendTemplate(
                    $email,
                    $subject,
                    'alerte-bien',
                    [
                        'nom' => $nom,
                        'liste_biens' => $itemsHtml,
                        'lien_plateforme' => $baseUrl,
                    ],
                    $userId,
                    $texte,
                    [
                        'liste_biens' => $itemsHtml,
                    ]
                );

            } catch (Throwable $e) {
                $erreurs++;

                error_log(
                    '[IMMO-SN send-alertes] '
                    . $email
                    . ' : '
                    . $e->getMessage()
                );

                echo "Email sending failed for {$email}.\n";
                continue;
            }

            // Journaliser les annonces envoyées.
            try {
                $db->beginTransaction();

                $journal = $db->prepare("
                    INSERT INTO public.alerte_logs
                        (id, alerte_id, bien_id, user_id, sent_at)
                    VALUES
                        (
                            gen_random_uuid(),
                            CAST(:alerte_id AS UUID),
                            CAST(:bien_id AS UUID),
                            CAST(:user_id AS UUID),
                            CURRENT_TIMESTAMP
                        )
                ");

                foreach ($userMatches as $match) {
                    $journal->execute([
                        'alerte_id' => $match['alerte_id'],
                        'bien_id' => $match['bien_id'],
                        'user_id' => $userId,
                    ]);
                }

                $db->commit();

                $envoyes++;

                echo "Alert email sent to {$email} ("
                    . count($userMatches)
                    . " match(es)).\n";

            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }

                $erreurs++;

                error_log(
                    '[IMMO-SN send-alertes] Log error for '
                    . $email
                    . ' : '
                    . $e->getMessage()
                );

                echo "Warning: email sent but alert log failed for {$email}.\n";
            }
        }

        echo PHP_EOL . "Alert processing summary:\n";
        echo "- Emails sent: {$envoyes}\n";
        echo "- Errors: {$erreurs}\n";

        return $envoyes + $erreurs;
    }
}

exit((new SendAlertesCron(__FILE__))->run());