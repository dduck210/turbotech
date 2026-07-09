<?php

namespace Codemoi\Mail;

use Codemoi\Core\Config;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// PHPMailer is not PSR-4 registered in this project's composer setup (it's a
// vendored copy under `email/PHPMailer/`, not pulled in via `vendor/autoload.php`),
// so it still needs explicit requires here — same as the old `email/index.php`.
require_once __DIR__ . '/../../email/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../email/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../email/PHPMailer/src/SMTP.php';

/**
 * SMTP mail wrapper. Ported from the global-namespace `Mailer` class in
 * `email/index.php` (Phase 05). SMTP credentials now read from
 * `Codemoi\Core\Config` instead of being hardcoded here.
 */
class Mailer
{
    public function sendMail($title, $content, $addressMail)
    {
        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->CharSet = 'utf-8';
            $mail->Host = Config::smtpHost();
            $mail->SMTPAuth = true;
            $mail->Username = Config::smtpUsername();
            $mail->Password = Config::smtpPassword();
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = Config::smtpPort();

            // Recipients
            $mail->setFrom(Config::smtpFromEmail(), Config::smtpFromName());
            $mail->addAddress($addressMail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $content;

            $mail->send();
        } catch (Exception $e) {
            // Mirrors old behavior (`email/index.php:54-56`): swallow send
            // failures rather than surfacing a fatal error to the user.
        }
    }
}
