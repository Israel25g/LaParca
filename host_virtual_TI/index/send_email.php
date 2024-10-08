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
    $mail->Password = 'nfzs zcii xrhr hyky'; // Asegúrate de que la contraseña es correcta
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Conectar a la base de datos
    $config = include '../config.php'; // Asegúrate de que este archivo tiene la configuración de la base de datos
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=u366386740_db_mainbase;charset=utf8';
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Obtener el último ticket
    $query = "SELECT nombrecompleto, correo, ubicacion, descripcion, urgencia FROM tickets ORDER BY id DESC LIMIT 1"; // Asegúrate de que la columna 'id' existe
    $stmt = $conexion->query($query);
    $ticket = $stmt->fetch();

    if (!$ticket) {
        throw new Exception("No se encontró ningún ticket en la base de datos.");
    }

    // Configuración del correo
    $mail->setFrom('tickesPrueba1@gmail.com', 'Departamento TI');
    $mail->addAddress($ticket['correo']);
    $mail->addAddress('ricaurte@iplgsc.com'); // Correo adicional

    $mail->isHTML(false); // Enviar el mensaje en formato texto
    $mail->Subject = 'Confirmación de recepción del ticket';
    $mail->Body    = "Hola " . $ticket['nombrecompleto'] . ",\n\n" .
                     "Gracias por contactarnos. Aquí están los datos que nos suministraste para confirmar su correcto envío:\n\n" .
                     "Nombre Completo: " . $ticket['nombrecompleto'] . ".\n" .
                     "Descripción: " . $ticket['descripcion'] . "\n" .
                     "Ubicación: " . $ticket['ubicacion'] . ".\n" .
                     "Urgencia: " . $ticket['urgencia'] . ".\n\n" .
                     "Atentamente,\nEl departamento de TI\n(no responder a este mensaje).";

    $mail->send();

    // Redirigir de vuelta a crear_ti.php con un mensaje de éxito
    header('Location: crear_ti.php?status=success');
    exit();
} catch (Exception $e) {
    // Redirigir de vuelta a crear_ti.php con un mensaje de error
    header('Location: crear_ti.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
?>


<!-- . ", ricaurte@iplgsc.com" -->