<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token) // mismo orden que new Email de login controller
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        // Crear el objeto de email
        $mail = new PHPMailer(); // la clase de PHPMailer la tenemos en el composer.json y se agrega al instalar phpmailer
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io'; // TODO ESTO ESTA EN MAILTRAP, EN INTEGRATIOS->PHPMailer(copiamos y pegamos el codigo)
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '351f1c1b056dfc';
        $mail->Password = '48b8432883f8f8';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com'); //cuentas@appsalon.com el profe no le pone el .com a esto pero si no lo pongo no me envia los mails
        $mail->Subject = 'Confirma tu cuenta';

        // SEt HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'> Confirmar Cuenta </a> </p>"; //pasamos el link y a la url le ponemos el token que generamos para identificar al usuario
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido; // agregamos el body del mail todo el contenido que agregamos

        // Enviar el mail
        $mail->send();
    }

    public function enviarInstrucciones() { // es casi igual al de arriba enviarConfirmacion()
        // Crear el objeto de email
        $mail = new PHPMailer(); 
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io'; 
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '351f1c1b056dfc';
        $mail->Password = '48b8432883f8f8';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com'); 
        $mail->Subject = 'Reestablece tu password'; // cambiamos el mensaje

        // SEt HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo</p>"; // modificamos el mensaje
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'> Reestablecer password </a> </p>"; // modificamos el link y mensaje
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido; 

        // Enviar el mail
        $mail->send();

    }
}