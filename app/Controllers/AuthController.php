<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\Agence;
use Core\Session;
use Core\Database;
use Core\Uploader;
use App\Mail\Mailer;
use Core\Validator;

class AuthController
{
    /**
     * Affiche la page de connexion.
     */
    public function login(): void
    {
        Session::start();

        if (Session::isAuthenticated()) {
            $utilisateurConnecte = Session::user();

            $roleConnecte = strtolower(
                trim((string) ($utilisateurConnecte['role'] ?? ''))
            );

            if ($roleConnecte === 'admin') {
                header('Location: /immo-sn/admin');
            } elseif ($roleConnecte === 'moderateur') {
               header('Location: /immo-sn/moderateur');
            } elseif ($roleConnecte === 'agence') {
                header('Location: /immo-sn/agence/mon-agence');
            } else {
                header('Location: /immo-sn/member/dashboard');
            }

            exit;
        }

        $error = Session::getFlash('error');
        $success = Session::getFlash('success');

        $this->view('auth/login', [
            'title' => 'Connexion — ImmoSn',
            'error' => $error,
            'success' => $success,
        ]);
    }

    /**
     * Traite la connexion.
     */

public function authenticate(): void
{
    Session::start();

    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /immo-sn/login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (
            !Validator::required($email) ||
            !Validator::required($password) ||
            !Validator::email($email)
        ) {
            header('Location: /immo-sn/login');
            exit;
        }

        $user = User::findByEmail($email);

        if (!$user) {
            header('Location: /immo-sn/login');
            exit;
        }

        if (
            isset($user['status']) &&
            !empty($user['status']) &&
            !in_array(
                strtolower((string) $user['status']),
                ['actif', 'active', 'valide', 'validé', 'approved'],
                true
            )
        ) {
            header('Location: /immo-sn/login');
            exit;
        }

        if (!User::verifyPassword($password, $user['password'])) {
            header('Location: /immo-sn/login');
            exit;
        }

        try {
            $db = Database::getConnection();

            $stmt = $db->prepare("
                UPDATE public.users
                SET last_login = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute(['id' => $user['id']]);

        } catch (\Throwable $e) {
            error_log('[LOGIN - LAST_LOGIN] ' . $e->getMessage());
        }

       $headersFile = '';
$headersLine = 0;

$headersAlreadySent = headers_sent(
    $headersFile,
    $headersLine
);

error_log(
    '[SESSION DIAGNOSTIC] '
    . 'status=' . session_status()
    . ' | active=' . (session_status() === PHP_SESSION_ACTIVE ? 'oui' : 'non')
    . ' | headers_sent=' . ($headersAlreadySent ? 'oui' : 'non')
    . ' | fichier=' . $headersFile
    . ' | ligne=' . $headersLine
    . ' | session_id=' . (session_id() !== '' ? 'présent' : 'absent')
);

if (!session_regenerate_id(true)) {
    throw new \RuntimeException(
        'Impossible de régénérer la session.'
    );
}

        Session::set('user_id', $user['id']);

        Session::set('user', [
            'id' => $user['id'],
            'nom' => $user['nom'] ?? '',
            'prenom' => $user['prenom'] ?? '',
            'email' => $user['email'] ?? '',
            'telephone' => $user['telephone'] ?? '',
            'role' => $user['role'] ?? '',
            'statut' => $user['status'] ?? '',
        ]);

        $role = strtolower(trim((string) ($user['role'] ?? '')));

        switch ($role) {
            case 'admin':
                $destination = '/immo-sn/admin';
                break;

            case 'moderateur':
                $destination = '/immo-sn/moderateur';
                break;

            case 'agence':
                $destination = '/immo-sn/agence/mon-agence';
                break;

            default:
                $destination = '/immo-sn/member/dashboard';
                break;
        }

        header('Location: ' . $destination);
        exit;

    } catch (\Throwable $e) {
        error_log('[LOGIN - ERREUR] ' . $e->getMessage());

        header('Location: /immo-sn/login');
        exit;
    }
}


    /**
     * Affiche le formulaire d'inscription.
     */
    public function register(): void
    {
        Session::start();

        if (Session::isAuthenticated()) {
            $utilisateur = Session::user();

            $role = strtolower(
                trim((string) ($utilisateur['role'] ?? ''))
            );

            if ($role === 'admin') {
                header('Location: /immo-sn/admin');
            } elseif ($role === 'moderateur') {
                header('Location: /immo-sn/moderateur');
            } elseif ($role === 'agence') {
                header('Location: /immo-sn/agence/mon-agence');
            } else {
                header('Location: /immo-sn/member/dashboard');
            }

            exit;
        }

        $this->view('auth/register', [
            'title' => 'Inscription — ImmoSN',
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    /**
     * Traite la création d'un compte particulier
     * ou d'un compte responsable d'agence.
     */
    public function storeRegistration(): void
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /immo-sn/inscription');
            exit;
        }

        // Informations personnelles.
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $ville = trim($_POST['ville'] ?? '');

        $password = $_POST['password'] ?? '';
        $confirmation = $_POST['password_confirmation'] ?? '';

        $role = strtolower(
            trim($_POST['role'] ?? 'particulier')
        );

        // Informations professionnelles de l'agence.
        $nomAgence = trim($_POST['nom_agence'] ?? '');
        $adresseAgence = trim($_POST['adresse_agence'] ?? '');
        $ninea = trim($_POST['ninea'] ?? '');
        $emailAgence = trim($_POST['email_agence'] ?? '');
        $telephoneAgence = trim($_POST['telephone_agence'] ?? '');
        $descriptionAgence = trim($_POST['description_agence'] ?? '');

        if (!in_array($role, ['particulier', 'agence'], true)) {
            Session::flash('error', 'Type de compte invalide.');

            header('Location: /immo-sn/inscription');
            exit;
        }

        /*
         * Vérification des informations personnelles.
         */
        if (
            $nom === '' ||
            $prenom === '' ||
            $email === '' ||
            $password === '' ||
            $confirmation === ''
        ) {
            Session::flash(
                'error',
                'Veuillez remplir les champs obligatoires.'
            );

            header('Location: /immo-sn/inscription');
            exit;
        }

        if (!Validator::email($email)) {
            Session::flash(
                'error',
                'Adresse e-mail personnelle invalide.'
            );

            header('Location: /immo-sn/inscription');
            exit;
        }

        if (!Validator::minLength($password, 8)) {
            Session::flash(
                'error',
                'Le mot de passe doit contenir au moins 8 caractères.'
            );

            header('Location: /immo-sn/inscription');
            exit;
        }

        if (!Validator::matches($password, $confirmation)) {
            Session::flash(
                'error',
                'Les mots de passe ne correspondent pas.'
            );

            header('Location: /immo-sn/inscription');
            exit;
        }

        /*
         * Vérification des informations professionnelles.
         */
        if ($role === 'agence') {
            if (
                $nomAgence === '' ||
                $adresseAgence === '' ||
                $ninea === '' ||
                $emailAgence === '' ||
                $telephoneAgence === ''
            ) {
                Session::flash(
                    'error',
                    'Veuillez renseigner les informations obligatoires de votre agence.'
                );

                header('Location: /immo-sn/inscription');
                exit;
            }

            if (!Validator::email($emailAgence)) {
                Session::flash(
                    'error',
                    'Adresse e-mail professionnelle invalide.'
                );

                header('Location: /immo-sn/inscription');
                exit;
            }
        }

        $db = Database::getConnection();
        $logoAgence = null;

        try {
            $db->beginTransaction();

            /*
             * Création du compte personnel.
             */
            $userId = User::creerCompte([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'ville' => $ville,
                'password' => $password,
                'role' => $role,
            ]);

            /*
             * Création de l'agence.
             */
            if ($role === 'agence') {

                // Téléversement du logo si fourni.
                if (
                    isset($_FILES['logo_agence']) &&
                    ($_FILES['logo_agence']['error'] ?? UPLOAD_ERR_NO_FILE)
                        !== UPLOAD_ERR_NO_FILE
                ) {
                    $logoAgence = Uploader::uploadLogoAgence(
                        $_FILES['logo_agence']
                    );
                }

                Agence::creer([
                    'user_id' => $userId,
                    'nom_agence' => $nomAgence,
                    'logo' => $logoAgence,
                    'description' => $descriptionAgence,
                    'adresse' => $adresseAgence,
                    'ninea' => $ninea,
                    'email' => $emailAgence,
                    'telephone' => $telephoneAgence,
                ]);
            }

            $db->commit();
                        /*
             * Envoi de l'e-mail de bienvenue.
             * Une erreur d'envoi ne doit pas annuler l'inscription.
             */
            try {
                // Initialisation centrale de IMMO-SN.
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';

// Récupération de l'URL depuis la configuration centrale.
$appConfig = require BASE_PATH . '/config/app.php';
$baseUrl = $appConfig['url'];

                $lienConnexion = $baseUrl . '/login';

                if ($role === 'agence') {
                    $messageCompte = 'Votre compte personnel a bien été créé. '
                        . 'Votre agence est maintenant en attente de validation '
                        . 'par notre équipe. Vous serez informé(e) lorsque '
                        . 'la vérification sera terminée.';
                } else {
                    $messageCompte = 'Votre compte a bien été créé. '
                        . 'Vous pouvez dès maintenant vous connecter pour '
                        . 'consulter les annonces immobilières et utiliser '
                        . 'les fonctionnalités accessibles aux particuliers.';
                }

                $mailer = new Mailer();

                $mailer->sendTemplate(
                    $email,
                    'Bienvenue sur IMMO-SN',
                    'welcome',
                    [
                        'prenom' => $prenom,
                        'message_compte' => $messageCompte,
                        'lien_connexion' => $lienConnexion,
                    ],
                    $userId,
                    "Bonjour {$prenom},\n\n"
                        . $messageCompte
                        . "\n\nConnectez-vous ici : "
                        . $lienConnexion
                        . "\n\nL'équipe IMMO-SN"
                );

            } catch (\Throwable $mailError) {
                error_log(
                    '[IMMO-SN WELCOME] Échec de l’envoi de l’e-mail : '
                    . $mailError->getMessage()
                );
            }

            Session::flash(
                'success',
                $role === 'agence'
                    ? 'Votre compte et votre agence ont été créés. Votre agence est en attente de validation.'
                    : 'Votre compte a été créé. Vous pouvez maintenant vous connecter.'
            );

            header('Location: /immo-sn/login');
            exit;

        } catch (\Throwable $e) {

            if ($db->inTransaction()) {
                $db->rollBack();
            }

            /*
             * Suppression du logo si l'enregistrement a échoué.
             */
            if ($logoAgence !== null) {
                $nomFichier = basename($logoAgence);

                $cheminLogo = BASE_PATH
                    . '/stockage/logos/'
                    . $nomFichier;

                if (is_file($cheminLogo)) {
                    unlink($cheminLogo);
                }
            }

            error_log(
                'Erreur inscription IMMO-SN : '
                . $e->getMessage()
            );

            Session::flash(
                'error',
                'Une erreur est survenue lors de la création du compte. Vérifiez les informations et réessayez.'
            );

            header('Location: /immo-sn/inscription');
            exit;
        }
    }

/**
 * Affiche le formulaire « Mot de passe oublié ».
 */
public function forgotPassword(): void
{
    Session::start();

    $this->view('auth/forgot-password', [
        'title' => 'Mot de passe oublié — ImmoSN',
        'error' => Session::getFlash('error'),
        'success' => Session::getFlash('success'),
    ]);
}

/**
 * Envoie un lien de réinitialisation par e-mail.
 */
public function sendResetLink(): void
{
    Session::start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /immo-sn/mot-de-passe-oublie');
        exit;
    }

    if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
        Session::flash('error', 'Votre session a expiré. Réessayez.');
        header('Location: /immo-sn/mot-de-passe-oublie');
        exit;
    }

    $email = trim($_POST['email'] ?? '');

    if (!Validator::email($email)) {
        Session::flash('error', 'Veuillez saisir une adresse e-mail valide.');
        header('Location: /immo-sn/mot-de-passe-oublie');
        exit;
    }

    try {
        $user = User::findByEmail($email);

        // Réponse identique, que le compte existe ou non.
        if ($user) {
            $db = Database::getConnection();

            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);

            // Supprime les anciens liens non utilisés.
            $stmt = $db->prepare("
                DELETE FROM public.password_resets
                WHERE user_id = :user_id
                  AND used_at IS NULL
            ");
            $stmt->execute(['user_id' => $user['id']]);

            // Enregistre uniquement l'empreinte du jeton.
            $stmt = $db->prepare("
                INSERT INTO public.password_resets
                    (user_id, token_hash, expires_at)
                VALUES
                    (:user_id, :token_hash, CURRENT_TIMESTAMP + INTERVAL '30 minutes')
            ");

            $stmt->execute([
                'user_id' => $user['id'],
                'token_hash' => $tokenHash,
            ]);

          // Récupération de l'URL depuis la configuration centrale.
$appConfig = require BASE_PATH . '/config/app.php';
$baseUrl = $appConfig['url'];

            $resetUrl = $baseUrl
                . '/reinitialiser-mot-de-passe?token='
                . urlencode($token);

                       $nom = trim(
                (string) ($user['prenom'] ?? '')
                . ' '
                . (string) ($user['nom'] ?? '')
            );

            if ($nom === '') {
                $nom = 'Utilisateur IMMO-SN';
            }

            $texte = "Bonjour {$nom},\n\n"
                . "Vous avez demandé à réinitialiser votre mot de passe IMMO-SN.\n\n"
                . "Pour choisir un nouveau mot de passe, ouvrez ce lien :\n"
                . $resetUrl
                . "\n\nCe lien est valable pendant 30 minutes et ne peut être utilisé qu'une seule fois.\n\n"
                . "Si vous n'êtes pas à l'origine de cette demande, ignorez cet e-mail.\n\n"
                . "L'équipe IMMO-SN";

            $mailer = new Mailer();

            $mailer->sendTemplate(
                (string) $user['email'],
                'Réinitialisation de votre mot de passe — IMMO-SN',
                'reinitialisation-mot-de-passe',
                [
                    'nom' => $nom,
                    'lien_reinitialisation' => $resetUrl,
                ],
                (string) $user['id'],
                $texte
            );
        }

        Session::flash(
            'success',
            'Si cette adresse correspond à un compte, un lien de réinitialisation vous sera envoyé.'
        );

    } catch (\Throwable $e) {
        error_log('Erreur réinitialisation IMMO-SN : ' . $e->getMessage());

        Session::flash(
            'error',
            'Une erreur technique est survenue. Veuillez réessayer plus tard.'
        );
    }

    header('Location: /immo-sn/mot-de-passe-oublie');
    exit;
}

/**
 * Vérifie le jeton et affiche le formulaire de nouveau mot de passe.
 */
public function resetPassword(): void
{
    Session::start();

    $token = trim($_GET['token'] ?? '');

    if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
        Session::flash('error', 'Ce lien de réinitialisation est invalide.');
        header('Location: /immo-sn/mot-de-passe-oublie');
        exit;
    }

    $db = Database::getConnection();

    $stmt = $db->prepare("
        SELECT id
        FROM public.password_resets
        WHERE token_hash = :token_hash
          AND used_at IS NULL
          AND expires_at > CURRENT_TIMESTAMP
        LIMIT 1
    ");

    $stmt->execute([
        'token_hash' => hash('sha256', $token),
    ]);

    if (!$stmt->fetch(\PDO::FETCH_ASSOC)) {
        Session::flash('error', 'Ce lien est expiré ou a déjà été utilisé.');
        header('Location: /immo-sn/mot-de-passe-oublie');
        exit;
    }

    $this->view('auth/reset-password', [
        'title' => 'Nouveau mot de passe — ImmoSN',
        'token' => $token,
        'error' => Session::getFlash('error'),
    ]);
}

/**
 * Enregistre le nouveau mot de passe.
 */
public function updatePassword(): void
{
    Session::start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /immo-sn/login');
        exit;
    }

    if (!Session::verifyCsrfToken($_POST['_csrf_token'] ?? null)) {
        Session::flash('error', 'Votre session a expiré. Réessayez.');
        header('Location: /immo-sn/mot-de-passe-oublie');
        exit;
    }

    $token = trim($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['password_confirmation'] ?? '';

    if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
        Session::flash('error', 'Lien de réinitialisation invalide.');
        header('Location: /immo-sn/mot-de-passe-oublie');
        exit;
    }

    if (!Validator::minLength($password, 8)) {
        Session::flash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
        header('Location: /immo-sn/reinitialiser-mot-de-passe?token=' . urlencode($token));
        exit;
    }

    if (!Validator::matches($password, $confirmation)) {
        Session::flash('error', 'Les deux mots de passe ne correspondent pas.');
        header('Location: /immo-sn/reinitialiser-mot-de-passe?token=' . urlencode($token));
        exit;
    }

    try {
        $db = Database::getConnection();
        $db->beginTransaction();

        $stmt = $db->prepare("
            SELECT id, user_id
            FROM public.password_resets
            WHERE token_hash = :token_hash
              AND used_at IS NULL
              AND expires_at > CURRENT_TIMESTAMP
            LIMIT 1
            FOR UPDATE
        ");

        $stmt->execute([
            'token_hash' => hash('sha256', $token),
        ]);

        $reset = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$reset) {
            $db->rollBack();

            Session::flash('error', 'Ce lien est expiré ou a déjà été utilisé.');
            header('Location: /immo-sn/mot-de-passe-oublie');
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if ($passwordHash === false) {
            throw new \RuntimeException(
                'Impossible de sécuriser le nouveau mot de passe.'
            );
        }

        $stmt = $db->prepare("
            UPDATE public.users
            SET password = :password,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :user_id
        ");

        $stmt->execute([
            'password' => $passwordHash,
            'user_id' => $reset['user_id'],
        ]);

        if ($stmt->rowCount() !== 1) {
            throw new \RuntimeException(
                'Le compte utilisateur n’a pas été modifié.'
            );
        }

        $stmt = $db->prepare("
            UPDATE public.password_resets
            SET used_at = CURRENT_TIMESTAMP
            WHERE id = :id
              AND used_at IS NULL
        ");

        $stmt->execute(['id' => $reset['id']]);

        if ($stmt->rowCount() !== 1) {
            throw new \RuntimeException(
                'Le jeton de réinitialisation n’a pas été consommé.'
            );
        }

        $db->commit();

        Session::flash(
            'success',
            'Votre mot de passe a été modifié. Vous pouvez maintenant vous connecter.'
        );

        header('Location: /immo-sn/login');
        exit;

    } catch (\Throwable $e) {
        if (isset($db) && $db->inTransaction()) {
            $db->rollBack();
        }

        error_log('Erreur modification mot de passe IMMO-SN : ' . $e->getMessage());

        Session::flash(
            'error',
            'Une erreur est survenue. Veuillez réessayer.'
        );

        header('Location: /immo-sn/mot-de-passe-oublie');
        exit;
    }
}


    /**
     * Déconnexion.
     */
    public function logout(): void
    {
        Session::destroy();

        header('Location: /immo-sn/login');
        exit;
    }

    /**
     * Charge une vue.
     */
    private function view(string $view, array $data = []): void
    {
        extract($data);

        require BASE_PATH . '/app/Views/' . $view . '.php';
    }
}
