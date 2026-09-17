<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

class Email {
    private \PHPMailer\PHPMailer\PHPMailer $mail;

    public function __construct() {
        $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mailer->isSMTP();
        $mailer->XMailer    = 'MunichKannadigaru';
        $mailer->Host       = getenv('SMTP_HOST')     ?: 'smtp.hostinger.com';
        $mailer->SMTPAuth   = true;
        $mailer->Username   = getenv('SMTP_USER')     ?: 'info@munichkannadigaru.org';
        $mailer->Password   = getenv('SMTP_PASSWORD') ?: '';
        $mailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port       = 587;
        $mailer->CharSet    = 'UTF-8';
        $mailer->Encoding   = 'base64';
        $mailer->SMTPDebug  = 0;
        $mailer->isHTML(true);
        $mailer->From     = 'info@munichkannadigaru.org';
        $mailer->FromName = '=?utf-8?B?' . base64_encode('ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು') . '?=';

        $this->mail = $mailer;
    }

    /**
     * Send an email.
     *
     * @param string   $to      Recipient address
     * @param string   $subject Subject line
     * @param string   $body    HTML body (use [KEY] placeholders replaced by $params)
     * @param array    $params  Key→value map for placeholder substitution
     * @throws \PHPMailer\PHPMailer\Exception on failure
     */
    public function send(string $to, string $subject, string $body, array $params = []): void {
        $this->mail->clearAddresses();
        $this->mail->clearAttachments();

        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;

        foreach ($params as $key => $value) {
            $body = str_replace('[' . $key . ']', $value, $body);
        }

        $this->mail->Body    = $body;
        $this->mail->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $body));

        $this->mail->send();
    }
}
