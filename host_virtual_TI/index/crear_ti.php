<?php
// Incluye la configuración de PHPMailer y los datos de envío
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Inicializa PHPMailer
$mail = new PHPMailer(true);

// Inicia la sesión y verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectar a la base de datos
    $config = include '../config.php';
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4' // Establecer UTF-8
        ]);
        
        // Recopila los datos del formulario
        $tickets = array(
            "nombrecompleto" => $_POST['nombrecompleto'],
            "correo" => $_POST['correo'],
            "ubicacion" => implode(", ", $_POST['ubicacion']),
            "descripcion" => $_POST['descripcion'],
            "urgencia" => implode(", ", $_POST['urgencia'])
        );

        // Guardar datos en la base de datos
        $consultaSQL = "INSERT INTO tickets (nombrecompleto, correo, ubicacion, descripcion, urgencia) VALUES (:" . implode(", :", array_keys($tickets)) . ")";
        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($tickets);

        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ticketpruebas1@gmail.com'; // Correo de envío
        $mail->Password = 'nfzs zcii xrhr hyky'; // Tu contraseña o contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Establecer la codificación de caracteres
        $mail->CharSet = 'UTF-8';

        // Configuración del correo
        $mail->setFrom('ticketpruebas1@gmail.com', 'Departamento TI');
        $mail->addAddress($tickets['correo']);
        // $mail->addAddress('ricaurte@iplgsc.com');

        $mail->isHTML(false); // Enviar el mensaje en formato texto
        $mail->Subject = 'Confirmación de recepción del ticket';

        // Crear el cuerpo del correo
        $mail->Body = "Hola " . $tickets['nombrecompleto'] . ",\n\n" .
                      "Gracias por contactarnos. Aquí están los datos que nos suministraste para confirmar su correcto envío:\n\n" .
                      "Nombre Completo: " . $tickets['nombrecompleto'] . ".\n" .
                      "Descripción: " . $tickets['descripcion'] . "\n" .
                      "Ubicación: " . $tickets['ubicacion'] . ".\n" .
                      "Urgencia: " . $tickets['urgencia'] . ".\n\n" .
                      "Atentamente,\nEl departamento de TI\n(no responder a este mensaje).";

        // Enviar el correo
        $mail->send();

        // Redirigir de vuelta a crear_ti.php con un mensaje de éxito
        header('Location: crear_ti.php?status=success');
        exit();
    } catch (PDOException $error) {
        // Redirigir de vuelta a crear_ti.php con un mensaje de error
        header('Location: crear_ti.php?status=error&message=' . urlencode($error->getMessage()));
        exit();
    } catch (Exception $e) {
        // Redirigir de vuelta a crear_ti.php con un mensaje de error
        header('Location: crear_ti.php?status=error&message=' . urlencode($mail->ErrorInfo));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de tickets</title>
    <link rel="stylesheet" href="../../host_virtual_TI/estilosT.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
</head>
<body style=" margin: 0; padding: 0; background-image: url('../../host_virtual_TI/images/Motivo2.png'); font-family:montserrat;">
    <?php include "../templates/header.php"; ?>
    <?php if (isset($_GET['status'])) { ?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?>" role="alert">
                        <?= isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Formulario enviado.' ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Ingrese los datos para crear el Ticket</h2>
                <a class="btn btn-success btn-lg" href="../index/index_ti.php">Volver al listado</a>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="nombrecompleto">Nombre completo</label>
                        <input type="text" name="nombrecompleto" id="nombrecompleto" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Adjuntar correo Corporativo</label>
                        <input type="email" name="correo" id="correo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="ubicacion">Departamento</label>
                        <select class="form-control" name="ubicacion[]" id="ubicacion" required multiple>
                            <option>Seleccione una opción...</option>
                            <option>Trafico</option>
                            <option>Contabilidad</option>
                            <option>RRHH</option>
                            <option>Operaciones planta baja</option>
                            <option>Asistente de gerencia</option>
                            <option>Gerente de operaciones</option>
                            <option>Gerente general</option>
                            <option>Auditoria</option>
                            <option>Inventario</option>
                            <option>mezzanine</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Describa el problema</label>
                        <textarea type="text" name="descripcion" id="descripcion" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="urgencia">Nivel de urgencia</label>
                        <select class="form-control" name="urgencia[]" id="urgencia" required>
                            <option>Seleccione una opción...</option>
                            <option>Regular</option>
                            <option>Urgente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Enviar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include "../templates/footer.php"; ?>
</body>
</html>
