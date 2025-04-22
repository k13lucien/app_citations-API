<?php

namespace App_citations\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Paramètres serveur SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'filskiemde13@gmail.com'; 
            $mail->Password   = 'mzst ivih caxd sahp';
            $mail->SMTPSecure = 'tls';  
            $mail->Port       = 587;  

            // Infos de l'e-mail
            $mail->setFrom('filskiemde134@gmail.com', 'Citations App');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail : {$mail->ErrorInfo}");
        }
    }
}
