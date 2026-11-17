
<?php
use Core\Database;
use App\Mail\Mailer;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/core/Database.php';
require_once __DIR__ . '/CronBase.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Accès interdit : ce script doit être exécuté en CLI.\n");
}

final class RappelExpirationCron extends CronBase
{
    private const TEMPLATE = 'rappel-expiration-7j';

    protected function execute(): int
    {
        $dryRun = in_array('--dry-run', $GLOBALS['argv'] ?? [], true);

        $db = Database::getConnection();

        $sql = "
            SELECT
                u.id AS user_id,
                u.nom,
                u.prenom,
                u.email,
                b.id AS bien_id,
                b.titre,
                b.expire_at
            FROM public.biens b
            INNER JOIN public.users u
                ON u.id = b.user_id
            WHERE LOWER(TRIM(COALESCE(b.statut, ''))) = 'actif'
              AND b.expire_at IS NOT NULL
              AND b.expire_at > CURRENT_TIMESTAMP
              AND b.expire_at <= CURRENT_TIMESTAMP + INTERVAL '7 days'
            ORDER BY u.id, b.expire_at ASC
        ";

        $annonces = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        if ($annonces === []) {
            echo "Aucune annonce à rappeler dans les 7 prochains jours.\n";
            return 0;
        }

        $parUtilisateur = [];

        foreach ($annonces as $annonce) {
            $userId = (string) $annonce['user_id'];

            $parUtilisateur[$userId]['user'] = [
                'id' => $userId,
                'nom' => (string) ($annonce['nom'] ?? ''),
                'prenom' => (string) ($annonce['prenom'] ?? ''),
                'email' => (string) ($annonce['email'] ?? ''),
            ];

            $parUtilisateur[$userId]['biens'][] = $annonce;
        }

        $mailer = null;
        $traites = 0;
        $erreurs = 0;
        $ignores = 0;

        foreach ($parUtilisateur as $groupe) {
            $user = $groupe['user'];
            $biens = $groupe['biens'];

            $email = trim($user['email']);
            $userId = $user['id'];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs++;
                echo "Adresse invalide pour l'utilisateur {$userId}.\n";
                continue;
            }

            // Éviter un deuxième rappel le même jour.
            // "success" conserve la compatibilité avec les anciens journaux.
            $verification = $db->prepare("
                SELECT 1
                FROM public.mail_logs
                WHERE user_id = CAST(:user_id AS UUID)
                  AND template = :template
                  AND status IN ('sent', 'success')
                  AND sent_at >= CURRENT_DATE
                LIMIT 1
            ");

            $verification->execute([
                'user_id' => $userId,
                'template' => self::TEMPLATE,
            ]);

            if ($verification->fetchColumn()) {
                $ignores++;
                echo "Rappel déjà envoyé aujourd'hui : {$email}\n";
                continue;
            }

            $nom = trim(
                $user['prenom'] . ' ' . $user['nom']
            );

            if ($nom === '') {
                $nom = 'Utilisateur IMMO-SN';
            }

            $escape = static function ($value): string {
                return htmlspecialchars(
                    (string) $value,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );
            };

            $listeHtml = '';
            $listeTexte = '';

            foreach ($biens as $bien) {
                $titre = (string) (
                    $bien['titre'] ?? 'Annonce immobilière'
                );

                $date = date(
                    'd/m/Y à H:i',
                    strtotime((string) $bien['expire_at'])
                );

                $listeHtml .= '<li><strong>'
                    . $escape($titre)
                    . '</strong> — expiration prévue le '
                    . $escape($date)
                    . '</li>';

                $listeTexte .= '- ' . $titre
                    . ' — expiration prévue le '
                    . $date . "\n";
            }

            $subject = 'IMMO-SN : rappel d’expiration de vos annonces';

           // Récupération de l'URL depuis la configuration centrale.
$appConfig = require BASE_PATH . '/config/app.php';
$baseUrl = $appConfig['url'];

            $lienEspaceMembre = $baseUrl . '/member/dashboard';

            $texte = "Bonjour {$nom},\n\n"
                . "Vos annonces suivantes arriveront à expiration dans les sept prochains jours :\n"
                . $listeTexte
                . "\nConnectez-vous à votre espace membre IMMO-SN pour les vérifier.\n\n"
                . "L'équipe IMMO-SN";

            if ($dryRun) {
                echo "[SIMULATION] {$email} : "
                    . count($biens)
                    . " annonce(s) à rappeler.\n";

                $traites++;
                continue;
            }

            try {
                if ($mailer === null) {
                    $mailer = new Mailer();
                }

                $mailer->sendTemplate(
                    $email,
                    $subject,
                    self::TEMPLATE,
                    [
                        'nom' => $nom,
                        'liste_biens' => $listeHtml,
                        'lien_espace_membre' => $lienEspaceMembre,
                    ],
                    $userId,
                    $texte,
                    [
                        'liste_biens' => $listeHtml,
                    ]
                );

                echo "Rappel envoyé à {$email}.\n";
                $traites++;

            } catch (Throwable $e) {
                $erreurs++;

                $error = mb_substr(
                    $e->getMessage(),
                    0,
                    2000
                );

                error_log(
                    '[IMMO-SN rappel-expiration] '
                    . $email . ' : ' . $error
                );

                echo "Échec de l’envoi pour {$email}.\n";
            }
        }

        echo "\nBilan du rappel d’expiration :\n";
        echo "- Propriétaires traités : {$traites}\n";
        echo "- Rappels ignorés : {$ignores}\n";
        echo "- Erreurs : {$erreurs}\n";

        return $traites + $erreurs;
    }
}

$cron = new RappelExpirationCron(__FILE__);

exit($cron->run());
