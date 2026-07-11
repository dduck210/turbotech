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
    /**
     * @return bool True if the message was actually handed off to the SMTP
     * server successfully, false otherwise. Callers where the email IS the
     * point of the request (e.g. forgot-password OTP) must check this
     * instead of assuming success — see `PasswordController::forgotPassword()`.
     */
    public function sendMail(string $title, string $content, string $addressMail): bool
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
            return true;
        } catch (Exception $e) {
            // Old behavior (`email/index.php:54-56`) swallowed this
            // completely — a bad/expired SMTP password then failed
            // silently with no way to tell from the outside. Log it so
            // it's at least visible in the PHP/Apache error log, and let
            // the caller know so it can decide how to react instead of
            // assuming the email went out.
            error_log('Mailer::sendMail failed for ' . $addressMail . ': ' . $mail->ErrorInfo);
            return false;
        }
    }
}
