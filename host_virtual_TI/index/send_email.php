<?php
// Aquí incluyes la configuración de PHPMailer y los datos de envío
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tickesPrueba1@gmail.com';
    $mail->Password = 'nfzs zcii xrhr hyky';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configuración del correo
    $form_data = json_decode(file_get_contents('form_data_user.json'), true);
    $mail->setFrom('tickesPrueba1@gmail.com', 'Departamento TI');
    $mail->addAddress($form_data['correo']);
    $mail->addAddress('ricaurte@iplgsc.com'); // Correo adicional

    $mail->isHTML(false); // Enviar el mensaje en formato texto
    $mail->Subject = 'Confirmación de recepción del ticket';
    $mail->Body    = "Hola " . $form_data['nombrecompleto'] . ",\n\n" .
                     "Gracias por contactarnos. Aquí están los datos que nos suministraste para confirmar su correcto envío:\n\n" .
                     "Nombre Completo: " . $form_data['nombrecompleto'] . ".\n" .
                     "Descripción: " . $form_data['descripcion'] . "\n" .
                     "Ubicación: " . $form_data['ubicacion'] . ".\n" .
                     "Urgencia: " . $form_data['urgencia'] . ".\n\n" .
                     "Atentamente,\nEl departamento de TI\n(no responder a este mensaje).";

    $mail->send();

    // Redirigir de vuelta a crear_ti.php con un mensaje de éxito
    header('Location: crear_ti.php?status=success');
    exit();
} catch (Exception $e) {
    // Redirigir de vuelta a crear_ti.php con un mensaje de error
    header('Location: crear_ti.php?status=error&message=' . urlencode($mail->ErrorInfo));
    exit();
}

?>

<!-- . ", ricaurte@iplgsc.com" -->