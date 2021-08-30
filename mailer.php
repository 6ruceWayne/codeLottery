<?php
/* This is the mailer. If user wins some prize mailer sends notification not to user directly but to managers.
 User is notified on front-end that he will get prize in next 24 hours on his email */
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions

function sendEmail($emailAddress, $code, $fname, $prizeName, $prizeValue)
{
    $mail = new PHPMailer(true);
    try {
        $from = 'some_email_from';
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL; // Enable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = 'smpt_host_name'; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $from; // SMTP username
        $mail->Password = 'smpt_pass_name'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom($from, 'Mailer');
        $mail->addAddress('someManager1');
        $mail->addAddress('someManager2');

        $mail->addReplyTo($from, 'Information');

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'A NEW DECLUTTERTHON WINNER';
        $mail->Body = 'Hi Team,' . "<br>" . "<br>" . 'There has been a new winner and you need to send them their voucher ' . "<br>" . "<br>" . 'Name: ' . $fname . "<br>" . 'Email Address: ' . $emailAddress . "<br>" . 'Verification code: ' . $code . "<br>" . 'Prize: ' . $prizeName . ' R' . $prizeValue;
        $mail->AltBody = 'Hi Team,' .
            'There has been a new winner and you need to send them their voucher ' .
            'Name: ' . $fname .
            'Email Address: ' . $emailAddress .
            'Verification code: ' . $code .
            'Prize: ' . $prizeName . ' R' . $prizeValue;
        $mail->send();
        return 'Message has been sent';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
