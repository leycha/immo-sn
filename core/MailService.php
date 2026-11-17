<?php
declare(strict_types=1);
namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;

class MailService
{
    private PHPMailer $mailer;

    public function __construct()
    {
        // Initialisation centrale de IMMO-SN.
        require_once dirname(__DIR__) . '/config/bootstrap.php';

        // Chargement de la configuration SMTP.
        $config = require BASE_PATH . '/config/mail.php';

        $host = $config['host'];
        $port = $config['port'];
        $username = $config['username'];
        $password = $config['password'];
        $from = $config['from'];
        $fromName = $config['from_name'];

        // Vérification de la configuration.
        if (
            $host === '' ||
            $from === '' ||
            $port < 1 ||
            $port > 65535
        ) {
            throw new RuntimeException(
                'Configuration SMTP incomplète : vérifiez MAIL_HOST, MAIL_PORT et MAIL_FROM.'
            );
        }

        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException(
                'MAIL_FROM doit être une adresse e-mail valide.'
            );
        }

        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host = $host;
        $this->mailer->Port = $port;
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->SMTPDebug = 0;
        $this->mailer->Timeout = 20;

        // Authentification SMTP.
        if ($username !== '' || $password !== '') {
            if ($username === '' || $password === '') {
                throw new RuntimeException(
                    'MAIL_USER et MAIL_PASS doivent être renseignés ensemble.'
                );
            }

            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $username;
            $this->mailer->Password = $password;
        } else {
            $this->mailer->SMTPAuth = false;
        }

        // Configuration du chiffrement SMTP.
        if ($port === 465) {
            $this->mailer->SMTPSecure =
                PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($port === 587 || $port === 25) {
            $this->mailer->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            throw new RuntimeException(
                'Port SMTP non pris en charge automatiquement. Vérifiez le port et le chiffrement requis par votre fournisseur.'
            );
        }

        $this->mailer->setFrom($from, $fromName);
        $this->mailer->isHTML(true);
    }

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

        $this->mailer->clearAllRecipients();
        $this->mailer->clearAttachments();
        $this->mailer->clearReplyTos();

        $this->mailer->addAddress($to);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $htmlBody;

        $this->mailer->AltBody = $textBody
            ?? trim(strip_tags(str_replace(
                ['<br>', '<br/>', '<br />', '</p>'],
                "\n",
                $htmlBody
            )));

        $this->mailer->send();
    }
}
