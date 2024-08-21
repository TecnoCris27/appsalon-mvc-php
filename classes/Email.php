<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        // Crear el objeto de email
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV['EMAIL_HOST'];    
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = 'c n e x e r t t q m y w s j h n';

        $phpmailer->setFrom('ap7232151@gmail.com', 'JhonnesBarber'); // Datos de mi correo y mi  nombre
        $phpmailer->addAddress($this->email, $this->nombre); // Datos del correo destinatario y nombre 

        $phpmailer->Subject = 'Confirma tu Cuenta'; // Asunto del correo

        // Set HTML
        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $phpmailer->Body = "
        
            <html>
                <head>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap');
                        h2 {
                            font-size: 25px;
                            font-weight: 500;
                            line-height: 25px;
                        }
                    
                        body {
                            font-family: 'Poppins', sans-serif;
                            background-color: #ffffff;
                            max-width: 400px;
                            margin: 0 auto;
                            padding: 20px;
                        }
                    
                        p {
                            line-height: 18px;
                        }
                    
                        a {
                            position: relative;
                            z-index: 0;
                            display: inline-block;
                            margin: 20px 0;
                        }
                    
                        a button {
                            padding: 0.7em 2em;
                            font-size: 16px !important;
                            font-weight: 500;
                            background: #000000;
                            color: #ffffff;
                            border: none;
                            text-transform: uppercase;
                            cursor: pointer;
                        }
                        a:hover{
                            cursor: pointer;
                        }
                        p span {
                            font-size: 12px;
                        }
                        .linea{
                            border-bottom: 1px solid #000000;
                            border-top: none;
                            margin-top: 40px;
                        }
                    </style>
                </head>
                <body>
                    <h1>JhonessBarber</h1>
                    <h2>¡Bienvenido a <strong>JhonnesBarber</strong>!</h2>
                    <p>Hola <strong>" . ucfirst($this->nombre) . "</strong>,</p>
                    <p>Para completar tu registro y comenzar a disfrutar de todos nuestros servicios, por favor confirma tu cuenta haciendo clic en el siguiente enlace:</p>
                    <a href='" . $_ENV['APP_URL'] ."/confirmar-cuenta?token=". $this->token . "' ><button>Confirmar Cuenta</button></a>
                    <p>Si no creaste esta cuenta, no te preocupes. Simplemente ignora este correo y no se realizará ninguna acción.</p>
                    <p>¡Gracias por unirte a nosotros!</p>
                    <p>Saludos cordiales,<br>El equipo de JhonesBarber</p>
                    <div><p class='linea'></p></div>
                    <p><span>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos respondiendo a este correo. Estamos aquí para ayudarte.</span></p>
                </body>
            </html>";

        //Enviar el email
        $phpmailer->send();
    }

    public function enviarInstrucciones(){
        // Crear el objeto de email
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV['EMAIL_HOST'];    
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = 'c n e x e r t t q m y w s j h n';

        $phpmailer->setFrom('ap7232151@gmail.com', 'JhonnesBarber'); // Datos de mi correo y mi  nombre
        $phpmailer->addAddress($this->email, $this->nombre); // Datos del correo destinatario y nombre 

        $phpmailer->Subject = 'Reestablece tu Contraseña'; // Asunto del correo

        // Set HTML
        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $phpmailer->Body = "
        
            <html>
                <head>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap');
                        h2 {
                            font-size: 25px;
                            font-weight: 500;
                            line-height: 25px;
                        }
                    
                        body {
                            font-family: 'Poppins', sans-serif;
                            background-color: #ffffff;
                            max-width: 400px;
                            margin: 0 auto;
                            padding: 20px;
                        }
                    
                        p {
                            line-height: 18px;
                        }
                    
                        a {
                            position: relative;
                            z-index: 0;
                            display: inline-block;
                            margin: 20px 0;
                        }
                    
                        a button {
                            padding: 0.7em 2em;
                            font-size: 16px !important;
                            font-weight: 500;
                            background: #000000;
                            color: #ffffff;
                            border: none;
                            text-transform: uppercase;
                            cursor: pointer;
                        }
                        a:hover{
                            cursor: pointer;
                        }
                        p span {
                            font-size: 12px;
                        }
                        .linea{
                            border-bottom: 1px solid #000000;
                            border-top: none;
                            margin-top: 40px;
                        }
                    </style>
                </head>
                <body>
                    <h1>JhonessBarber</h1>
                    <p>Hola <strong>" . ucfirst($this->nombre) . "</strong>,</p>
                    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Si no solicitaste este cambio, por favor, ignora este mensaje. De lo contrario, sigue las instrucciones a continuación para restablecer tu contraseña.</p>
                    <p> <strong>Instrucciones para restablecer tu contraseña:</strong> </p>
                    <ol>
                        <li>Haz clic en el siguiente enlace para restablecer tu contraseña:</li>
                        <a href='" . $_ENV['APP_URL'] ."/recuperar?token=". $this->token . "' >Reestablecer Contraseña</a>

                        <li>Serás redirigido a una página donde podrás ingresar una nueva contraseña.</li>
                        <li>Introduce y confirma tu nueva contraseña. Asegúrate de que sea segura y fácil de recordar para ti.</li>
                    </ol>
                    
                    <p><strong>Enlace para restablecer la contraseña:</strong></p>
                    <a href='" . $_ENV['APP_URL'] ."/recuperar?token=". $this->token . "' ><button>Reestablecer Contraseña</button></a>
                    <p>Por razones de seguridad, este enlace expirará en [número de horas] horas. Si necesitas un nuevo enlace, por favor, solicita otro restablecimiento de contraseña.</p>
                    <p>Gracias,</p>
                    <div><p class='linea'></p></div>
                    <p><span>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos respondiendo a este correo. Estamos aquí para ayudarte.</span></p>
                </body>
            </html>";

        //Enviar el email
        $phpmailer->send();
    }
}