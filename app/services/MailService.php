<?php

    //https://www.hostinger.com/es/tutoriales/enviar-emails-usando-php-mail/
    require_once '../libraries/PHPMailer/src/Exception.php';
    require_once '../libraries/PHPMailer/src/PHPMailer.php';
    require_once '../libraries/PHPMailer/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class MailService
    {

        public function enviarCorreoRegistro($correo, $token){

            $mail = new PHPMailer(true);

            try{

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'lucasdelgadooram@gmail.com';
                $mail->Password = 'twojuzalnttfhzxq';

                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = "UTF-8";
                $mail->setFrom('lucasdelgadooram@gmail.com','Paluse');

                $mail->addAddress($correo);
                $mail->isHTML(true);
                $mail->Subject = 'Activa tu cuenta';
                $link = BASE_URL . "/auth/verificar?token=".$token;

                $mail->Body = "

                    <h2>Bienvenido a Paluse</h2>

                    <p>Gracias por registrarte.</p>

                    <p>Haz clic en el siguiente enlace para activar su cuenta en la página Paluse.</p>

                    <a href='$link'>ACTIVAR CUENTA</a>

                    <br><br>
                    Este enlace expira en 24 horas.";

                $mail->send();

                return true;

            }catch(Exception $e){

                return false;

            }

        }

    }