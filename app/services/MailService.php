<?php

require_once __DIR__ . '/../libraries/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libraries/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libraries/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function enviarCorreoRegistro($correo, $token)
    {
        $mail = new PHPMailer(true);

        try {

            // Configuración SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'lucasdelgadooram@gmail.com';
            $mail->Password = 'twojuzalnttfhzxq';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('lucasdelgadooram@gmail.com','Paluse');

            $mail->addAddress($correo);

            $mail->isHTML(true);
            $mail->Subject = 'Activa tu cuenta en Paluse';

            $link = BASE_URL . '/auth/verificar?token=' . urlencode($token);

            $mail->Body = "
                <h2>Bienvenido a Paluse</h2>

                <p>Gracias por registrarte.</p>

                <p>
                    Para continuar con tu registro, debes verificar
                    tu correo electrónico.
                </p>

                <p>
                    <a href='$link'>
                        ACTIVAR CUENTA
                    </a>
                </p>

                <p>
                    Este enlace expirará en 24 horas.
                </p>
            ";

            $mail->send();

            return true;

        } catch (Exception $e) {
            error_log('Error PHPMailer: ' . $mail->ErrorInfo);

            return false;
        }
    }

    public function enviarCorreoRecuperacion($correo, $token){
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'lucasdelgadooram@gmail.com';
            $mail->Password = 'twojuzalnttfhzxq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';

            $mail->setFrom('lucasdelgadooram@gmail.com', 'Paluse' );
            $mail->addAddress($correo);
            $mail->isHTML(true);

            $mail->Subject = 'Restablecer contraseña - Paluse';

            $link = BASE_URL .'/auth/cambiarPassword?token='.urlencode($token);
            $mail->Body = "
                <h2>Restablecer contraseña</h2>

                <p>
                    Hemos recibido una solicitud para cambiar su contraseña
                    la contraseña de tu cuenta en Paluse.
                </p>

                <p>
                    Para crear una nueva contraseña, haz clic
                    en el siguiente enlace:
                </p>

                <p>
                    <a href='$link'>
                        RESTABLECER CONTRASEÑA
                    </a>
                </p>

                <p>
                    Este enlace expirará en 24 horas.
                </p>

                <p>
                    Si tú no solicitaste este cambio,
                    puedes ignorar este correo y borrarlo.
                </p>
            ";

            $mail->send();

            return true;

        } catch (Exception $e) {

            error_log('Error PHPMailer recuperación: ' . $mail->ErrorInfo);

            return false;
        }
    }


}