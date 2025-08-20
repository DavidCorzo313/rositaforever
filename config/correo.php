<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function enviarCorreo($destinatario, $codigo) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'davidfelipecorzotorrez@gmail.com'; // Reemplaza con tu correo
        $mail->Password   = 'lwvalrggvtzfnlvt'; // Reemplaza con contraseña o app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Configuración del mensaje
        $mail->setFrom('davidfelipecorzotorrez@gmail.com', 'Soporte');
        $mail->addAddress($destinatario);
        $mail->Subject = 'Código de recuperación de contraseña';
        $mail->Body    = "Tu código de recuperación es: $codigo";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("No se pudo enviar el correo. Error: {$mail->ErrorInfo}");
        return false;
    }
}
