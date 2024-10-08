<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

include '../funciones.php';
$config = include '../config.php';

$resultado = [
    'error' => false,
    'mensaje' => ''
];

if (!isset($_GET['id'])) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'El Ticket no existe';
}

if (isset($_POST['submit'])) {
    try {
        // Conexión a la base de datos
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
        $conexion->exec("set names utf8");

        // Actualización de los datos del ticket
        $tickets = [
            "id"        => $_GET['id'],
            "respuesta" => $_POST['respuesta'],
            "estado"    => $_POST['estado'],
        ];

        $consultaSQL = "UPDATE tickets SET
            respuesta = :respuesta,
            estado = :estado,
            updated_at = NOW()
            WHERE id = :id";

        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute($tickets);

        // Obtener el correo y nombrecompleto
        $consultaInfo = "SELECT correo, nombrecompleto FROM tickets WHERE id = :id";
        $consultaInfoStmt = $conexion->prepare($consultaInfo);
        $consultaInfoStmt->execute(['id' => $_GET['id']]);
        $info = $consultaInfoStmt->fetch(PDO::FETCH_ASSOC);

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ticketpruebas1@gmail.com'; // Cambia esto por el correo adecuado
            $mail->Password = 'nfzs zcii xrhr hyky';      // Cambia esto por la contraseña adecuada
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Configuración del correo
            $mail->setFrom('ticketpruebas1@gmail.com', 'Departamento de TI');
            $mail->addAddress($info['correo'], $info['nombrecompleto']);  // Enviar al correo del solicitante
            $mail->addCC('ricaurte@iplgsc.com');  // Copia a un correo adicional si es necesario

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = "El ticket # " . $_GET['id'] . " se encuentra " . $_POST['estado'];
            $mail->Body    = "
                <p>Hola <strong>{$info['nombrecompleto']}</strong>,</p>
                <p>Gracias por contactarnos. Aquí está la respuesta al ticket que enviaste:</p>
                <ul>
                    <li><strong>Respuesta:</strong> {$_POST['respuesta']}</li>
                    <li><strong>Estado del ticket:</strong> {$_POST['estado']}</li>
                </ul>
                <p>Atentamente,<br>El departamento de TI<br><em>(No responder a este mensaje).</em></p>
            ";

            // Enviar el correo
            $mail->send();
            $resultado['mensaje'] = 'El Ticket ha sido respondido con éxito y el correo se envió correctamente.';

        } catch (Exception $e) {
            $resultado['error'] = true;
            $resultado['mensaje'] = "El Ticket fue actualizado, pero el correo no se pudo enviar. Error: {$mail->ErrorInfo}";
        }

    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

// Obtener los datos del ticket si no se ha enviado aún el formulario
if (!isset($_POST['submit'])) {
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $id = $_GET['id'];
        $consultaSQL = "SELECT * FROM tickets WHERE id = :id";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute(['id' => $id]);

        $tickets = $sentencia->fetch(PDO::FETCH_ASSOC);

        if (!$tickets) {
            $resultado['error'] = true;
            $resultado['mensaje'] = 'No se ha encontrado el ticket';
        }

    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Ticket</title>
    <link rel="stylesheet" href="../../host_virtual_TI/estilosT.css">
    <link rel="shortcut icon" href="../images/ICO.png">
</head>

<body style="margin: 0; padding: 0; background-image: url('../../host_virtual_TI/images/Motivo2.png'); font-family: montserrat;">
    <?php include "../templates/header.php"; ?>

    <!-- Mostrar mensajes de error o éxito -->
    <?php if ($resultado['error']) { ?>
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        <?= $resultado['mensaje'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($_POST['submit']) && !$resultado['error']) { ?>
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <?= $resultado['mensaje'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- Mostrar formulario para responder al ticket -->
    <?php if (isset($tickets) && $tickets) { ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4">Respondiendo al Ticket de <?= escapar($tickets['nombrecompleto']) ?>, sobre: <?= escapar($tickets['descripcion']) ?></h2>
                    <a class="btn btn-success" href="../indexAdmin/indexAdmin_ti.php">Regresar al listado</a>
                    <hr>
                    <form method="post">
                        <div class="form-group">
                            <label for="respuesta">Respuesta del ticket</label>
                            <textarea type="text" name="respuesta" id="respuesta" rows="3" class="form-control" required><?= escapar($tickets['respuesta']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado del Ticket</label>
                            <select class="form-control" name="estado" id="estado" required>
                                <option></option>
                                <option <?= ($tickets['estado'] == 'No iniciado') ? 'selected' : '' ?>>No iniciado</option>
                                <option <?= ($tickets['estado'] == 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                                <option <?= ($tickets['estado'] == 'Terminado') ? 'selected' : '' ?>>Terminado</option>
                                <option <?= ($tickets['estado'] == 'En espera de aprobación') ? 'selected' : '' ?>>En espera de aprobación</option>
                                <option <?= ($tickets['estado'] == 'En cotización') ? 'selected' : '' ?>>En cotización</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary" value="Responder">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php require "../templates/footer.php"; ?>
</body>

</html>
