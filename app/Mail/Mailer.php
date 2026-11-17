<?php

declare(strict_types=1);

namespace App\Mail;

use Core\Database;
use Core\MailService;
use PDO;
use RuntimeException;
use Throwable;

final class Mailer
{
    private PDO $db;
    private MailTemplate $template;

    public function __construct(
        ?MailTemplate $template = null
    ) {
        $this->db = Database::getConnection();
        $this->template = $template ?? new MailTemplate();
    }

    /**
     * Envoie un e-mail à partir d'un modèle HTML.
     *
     * Les variables sont automatiquement échappées.
     * Seules les variables explicitement déclarées dans
     * $trustedHtml sont insérées comme HTML.
     *
     * @param string       $to
     * @param string       $subject
     * @param string       $template
     * @param array        $data
     * @param string|null  $userId
     * @param string|null  $textBody
     * @param array        $trustedHtml
     */
    public function sendTemplate(
        string $to,
        string $subject,
        string $template,
        array $data = [],
        ?string $userId = null,
        ?string $textBody = null,
        array $trustedHtml = []
    ): void {
        $to = trim($to);
        $subject = trim($subject);

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException(
                'Adresse e-mail du destinataire invalide.'
            );
        }

        if ($subject === '') {
            throw new RuntimeException(
                'L’objet de l’e-mail est obligatoire.'
            );
        }

        // Préparer le contenu avant de créer le journal.
        if ($trustedHtml === []) {
            $htmlBody = $this->template->render(
                $template,
                $data
            );
        } else {
            $htmlBody = $this->template->renderWithHtml(
                $template,
                $data,
                $trustedHtml
            );
        }

        // Enregistrer la tentative avant l'envoi SMTP.
        $sql = '
            INSERT INTO public.mail_logs (
                user_id,
                email_to,
                subject,
                template,
                status,
                error
            )
            VALUES (
                :user_id,
                :email_to,
                :subject,
                :template,
                :status,
                NULL
            )
            RETURNING id
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            ':user_id' => $userId,
            ':email_to' => $to,
            ':subject' => $subject,
            ':template' => $template,
            ':status' => 'pending',
        ]);

        $logId = $statement->fetchColumn();

        if ($logId === false) {
            throw new RuntimeException(
                'Impossible de récupérer le journal de cet e-mail.'
            );
        }

        try {
            // Utiliser le service SMTP existant.
            $mailService = new MailService();

            $mailService->send(
                $to,
                $subject,
                $htmlBody,
                $textBody
            );

        } catch (Throwable $e) {
            // Enregistrer l'échec de l'envoi.
            try {
                $this->updateLog(
                    (string) $logId,
                    'failed',
                    $e->getMessage()
                );
            } catch (Throwable $logError) {
                error_log(
                    '[IMMO-SN MAIL] Impossible de journaliser '
                    . 'l’échec : '
                    . $logError->getMessage()
                );
            }

            throw $e;
        }

        // Le message a été accepté par le service SMTP.
        // Une erreur de mise à jour ne doit pas provoquer
        // un nouvel envoi automatique du même message.
        try {
            $this->updateLog(
                (string) $logId,
                'sent',
                null
            );
        } catch (Throwable $e) {
            error_log(
                '[IMMO-SN MAIL] Message envoyé, mais journal '
                . 'non actualisé : '
                . $e->getMessage()
            );
        }
    }

    /**
     * Met à jour le statut d'une tentative.
     */
    private function updateLog(
        string $logId,
        string $status,
        ?string $error
    ): void {
        $sql = '
            UPDATE public.mail_logs
            SET
                status = :status,
                error = :error
            WHERE id = CAST(:id AS UUID)
        ';

        $statement = $this->db->prepare($sql);

        $statement->execute([
            ':status' => $status,
            ':error' => $error,
            ':id' => $logId,
        ]);

        if ($statement->rowCount() !== 1) {
            throw new RuntimeException(
                'Le journal de l’e-mail n’a pas été actualisé.'
            );
        }
    }
}