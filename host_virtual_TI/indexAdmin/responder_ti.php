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
            "id" => $_GET['id'],
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

        // Obtener toda la información del ticket desde la primera tabla (para insertar en la segunda tabla)
        $consultaInfo = "SELECT * FROM tickets WHERE id = :id";
        $consultaInfoStmt = $conexion->prepare($consultaInfo);
        $consultaInfoStmt->execute(['id' => $_GET['id']]);
        $ticketInfo = $consultaInfoStmt->fetch(PDO::FETCH_ASSOC);

        // Insertar en la segunda tabla todos los datos del ticket
        $consultaSQL = "INSERT INTO tickets_r (estado, respuesta, nombrecompleto, descripcion, correo, ubicacion, urgencia, updated_at) 
                         VALUES ( :estado, :respuesta, :nombrecompleto, :descripcion, :correo, :ubicacion, :urgencia, NOW())";

        // Asegurarse de que todos los valores se incluyan correctamente
        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute([
            'estado' => $_POST['estado'],  // Estado actualizado
            'respuesta' => $_POST['respuesta'],  // Respuesta actualizada
            'nombrecompleto' => $ticketInfo['nombrecompleto'],  // Datos originales
            'descripcion' => $ticketInfo['descripcion'],  // Datos originales
            'correo' => $ticketInfo['correo'],  // Datos originales
            'ubicacion' => $ticketInfo['ubicacion'],  // Datos originales
            'urgencia' => $ticketInfo['urgencia'],  // Datos originales
        ]);

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
    <link rel="stylesheet" href="../../main-global.css">
    <link rel="shortcut icon" href="../images/ICO.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
</head>

<body style="margin: 0; padding: 0; background-image: url('../../host_virtual_TI/images/Motivo2.png'); font-family: montserrat;">
    <!-- Header -->
    <div class="header-error">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <h1><a href="../../helpdesk.php">Sistema de Tickets</a></h1>
        <div class="cuadroFecha-error">
            <p id="fecha-actual"></p>
            <p id="hora-actual"></p>
        </div>
    </div>
    <!-- Fin del Header -->
    <div class="espacio"></div>
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
                    <a class="btn btn-success mb-2" href="../index/index_ti.php">Regresar al listado</a>
                    <div class="card text-start">
                        <div class="card-header">
                            <h2>Respondiendo al Ticket #<?= escapar($tickets['id'])?> - Usuario: <?= escapar($tickets['nombrecompleto'])?></h2>
                            <div class="card-body">
                                <div class="card text-start">
                                    <div class="card-body">
                                        <p>Descripción del caso: <br> <?= escapar($tickets['descripcion']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <form method="post">
                            <div class="card text-start">
                                <div class="card-header">
                                    <label for="respuesta">
                                        <h2>Respuesta del ticket</h2>
                                    </label>
                                    <textarea type="text" name="respuesta" id="respuesta" rows="3" class="form-control" required><?= escapar($tickets['respuesta']) ?></textarea>
                                </div>
                            </div>

                            <hr>
                            <div class="card text-start">
                                <div class="card-header">
                                    <h2>Estado del Ticket</h2>
                                    <select class="form-control" name="estado" id="estado" required>
                                        <option></option>
                                        <option <?= ($tickets['estado'] == 'No iniciado') ? 'selected' : '' ?>>No iniciado</option>
                                        <option <?= ($tickets['estado'] == 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                                        <option <?= ($tickets['estado'] == 'Terminado') ? 'selected' : '' ?>>Terminado</option>
                                        <option <?= ($tickets['estado'] == 'En espera de aprobación') ? 'selected' : '' ?>>En espera de aprobación</option>
                                        <option <?= ($tickets['estado'] == 'En cotización') ? 'selected' : '' ?>>En cotización</option>
                                    </select>
                                </div>
                                <input type="submit" name="submit" class="btn btn-primary mt-2 ms-3" value="Responder">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php require "../templates/footer.php"; ?>
        <script src="../js/script.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/plug-ins/2.1.8/dataRender/datetime.js"></script>
        <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.2/kt-2.12.1/r-3.0.3/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>
</body>

</html>