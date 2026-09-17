<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

class Email {
    private \PHPMailer\PHPMailer\PHPMailer $mail;

    public function __construct() {
        $host     = getenv('SMTP_HOST')     ?: 'smtp.hostinger.com';
        $user     = getenv('SMTP_USER')     ?: 'info@munichkannadigaru.org';
        $password = getenv('SMTP_PASSWORD') ?: '';

        error_log('[Email] constructing — host=' . $host . ' user=' . $user . ' password_set=' . (!empty($password) ? 'yes' : 'NO'));

        $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mailer->isSMTP();
        $mailer->XMailer    = 'MunichKannadigaru';
        $mailer->Host       = $host;
        $mailer->SMTPAuth   = true;
        $mailer->Username   = $user;
        $mailer->Password   = $password;
        $mailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port       = 587;
        $mailer->CharSet    = 'UTF-8';
        $mailer->Encoding   = 'base64';
        $mailer->SMTPDebug  = 3; // log full SMTP conversation
        $mailer->Debugoutput = function(string $str, int $level) {
            error_log('[PHPMailer SMTP] ' . trim($str));
        };
        $mailer->isHTML(true);
        $mailer->From     = 'info@munichkannadigaru.org';
        $mailer->FromName = '=?utf-8?B?' . base64_encode('ಮ್ಯೂನಿಕ್ ಕನ್ನಡಿಗರು') . '?=';

        $this->mail = $mailer;
    }

    /**
     * @param array $params Key→value map for [KEY] placeholder substitution in $body
     * @throws \PHPMailer\PHPMailer\Exception on SMTP failure
     */
    public function send(string $to, string $subject, string $body, array $params = []): void {
        error_log('[Email] send() called — to=' . $to . ' subject=' . $subject);

        $this->mail->clearAddresses();
        $this->mail->clearAttachments();

        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;

        foreach ($params as $key => $value) {
            $body = str_replace('[' . $key . ']', $value, $body);
        }

        $this->mail->Body    = $body;
        $this->mail->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $body));

        error_log('[Email] calling mail->send()');
        $this->mail->send();
        error_log('[Email] mail->send() completed successfully');
    }
}
