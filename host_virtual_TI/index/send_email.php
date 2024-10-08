<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar el autoloader de Composer
require 'vendor/autoload.php';

// Función para leer datos del archivo JSON
function read_form_data($file_path) {
    if (!file_exists($file_path)) {
        throw new Exception("El archivo no existe: " . $file_path);
    }

    $json_content = file_get_contents($file_path);
    $data = json_decode($json_content, true);  // Convierte el contenido JSON a un array asociativo

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
    }

    return $data;
}

// Ruta al archivo que contiene los datos del formulario (en formato JSON)
$file_path = 'form_data_user.json';

try {
    // Leer datos del archivo JSON
    $form_data = read_form_data($file_path);

    // Configuración del correo
    $email_subject = "Confirmación de recepción del ticket";
    $sender_email_address = "ticketpruebas1@gmail.com";
    $email_password = "nfzs zcii xrhr hyky";
    $email_smtp = "smtp.gmail.com";
    $recipient_email = $form_data['correo'];

    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);

    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = $email_smtp;
    $mail->SMTPAuth = true;
    $mail->Username = $sender_email_address;
    $mail->Password = $email_password;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Configurar el remitente y destinatarios
    $mail->setFrom($sender_email_address, 'Departamento de TI');
    $mail->addAddress($form_data['correo']);
    $mail->addAddress('ricaurte@iplgsc.com');

    // Asunto del correo
    $mail->Subject = $email_subject;

    // Contenido del correo
    $mail->Body = "Hola " . $form_data['nombrecompleto'] . ",\n\n" .
                  "Gracias por contactarnos, aqui apareceran los datos que nos suministro, para confirmar su correcto envio:\n\n" .
                  "Nombre Completo: " . $form_data['nombrecompleto'] . ".\n" .
                  "Descripción: " . $form_data['descripcion'] . "\n" .
                  "Ubicación: " . $form_data['ubicacion'] . ".\n" .
                  "Urgencia: " . $form_data['urgencia'] . ".\n\n" .
                  "Atentamente,\nEl departamento de TI\n(no responder a este mensaje).";

    // Intentar enviar el correo
    $mail->send();
    echo "Correo enviado exitosamente.";
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- . ", ricaurte@iplgsc.com" -->