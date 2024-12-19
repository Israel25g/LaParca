<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

// Inicializamos la variable de control de envío rápido
if (!isset($button['is_disabled'])) {
    $button['is_disabled'] = false; // Inicialmente, el botón está habilitado
}

$resultado = ['mensaje' => ''];

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $button['is_disabled'] = true; // Deshabilitamos el botón al enviar
    
    $config = include '../config.php';
    
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4' // Establecer UTF-8
        ]);

        // Datos del formulario
        $tickets = array(
            "nombrecompleto" => $_POST['nombrecompleto'],
            "correo" => $_POST['correo'],
            "ubicacion" => implode(", ", $_POST['ubicacion']),
            "descripcion" => $_POST['descripcion'],
            "urgencia" => implode(", ", $_POST['urgencia']),
            "estado" => $_POST['estado']
        );

            // Guardar datos en la base de datos
            $consultaSQL = "INSERT INTO tickets_m (nombrecompleto, correo, ubicacion, descripcion, urgencia, estado)";
            $consultaSQL .= " VALUES (:" . implode(", :", array_keys($tickets)) . ")";
            $sentencia = $conexion->prepare($consultaSQL);
            $sentencia->execute($tickets);

            // Guardar datos en la base de datos de registro
            $consultaSQL = "INSERT INTO tickets_m_r (nombrecompleto, correo, ubicacion, descripcion, urgencia, estado)";
            $consultaSQL .= " VALUES (:" . implode(", :", array_keys($tickets)) . ")";
            $sentencia = $conexion->prepare($consultaSQL);
            $sentencia->execute($tickets);

        // Configuración del correo
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ticketpruebas1@gmail.com';
        $mail->Password = 'cyaj xxnu hjof ezrt';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('ticketpruebas1@gmail.com', 'Departamento Mantenimiento');
        $mail->addAddress($tickets['correo']);
        $mail->addAddress('cecilio@iplgsc.com'); // Correo adicional

        $mail->isHTML(false);
        $mail->Subject = 'Confirmación de recepción del ticket';
        $mail->Body = "Hola " . $tickets['nombrecompleto'] . ",\n\n" .
        "Gracias por contactarnos. Aquí están los datos que nos suministraste para confirmar su correcto envío:\n\n" .
        "Nombre Completo: " . $tickets['nombrecompleto'] . ".\n" .
        "Descripción: " . $tickets['descripcion'] . "\n" .
        "Ubicación o Departamento: " . $tickets['ubicacion'] . ".\n" .
        "Urgencia: " . $tickets['urgencia'] . ".\n\n" .
        "Atentamente,\nEl departamento de Mantenimiento\n(no responder a este mensaje).";
        
        $mail->send();

        $resultado['mensaje'] = 'El Ticket ha sido agregado con éxito y el correo se envió correctamente.';
        $button['is_disabled'] = false; // Reactivamos el botón
    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'Error en la base de datos: ' . $error->getMessage();
        $button['is_disabled'] = false; // Reactivamos el botón
    } catch (Exception $e) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'Error al enviar el correo: ' . $e->getMessage();
        $button['is_disabled'] = false; // Reactivamos el botón
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        function handleSubmit(event) {
            // Desactivar el botón y mostrar spinner
            const submitBtn = document.getElementById("submitBtn");
            submitBtn.disabled = true;

            const spinner = document.getElementById("spinner");
            spinner.style.display = "inline-block";
        }

        function resetButton() {
            // Reactivar el botón y ocultar spinner
            const submitBtn = document.getElementById("submitBtn");
            submitBtn.disabled = false;

            const spinner = document.getElementById("spinner");
            spinner.style.display = "none";
        }

        // Reactivar el botón al cargar la página si hay un mensaje de resultado
        window.onload = function () {
            const resultMessage = document.querySelector(".alerta-enviado");
            if (resultMessage && resultMessage.innerHTML.trim() !== "") {
                resetButton();
            }
        };
    </script>
</head>

<body>
    <?php include "../../host_virtual_EEMP/componentesxd/header.php"; ?>

    <div class="tick-main-block">
        <span class="alerta-enviado">
            <?php if (!empty($resultado['mensaje'])) { ?>
                <div class="container mt-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-<?= isset($resultado['error']) && $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
                                <?= htmlspecialchars($resultado['mensaje']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </span>
        <div class="fondo-tickets">
            <form method="post" onsubmit="handleSubmit(event)">
            <h2 class="form-tickets"><a href="../index/index_m.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Ingrese los datos para crear el Ticket</h2>
            <div style="max-width: auto;"><a class="btn btn-success btn-lg" href="../index/index_m.php">Volver al listado</a></div>
                        <hr>
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
                    <select class="form-control" name="ubicacion[]" id="ubicacion" required>
                            <option>Seleccione una ubicación...</option>
                            <option>Administracion</option>
                            <option>Atencion al cliente</option>
                            <option>Mezzanine</option>
                            <option>Bodega</option>
                            <option>Baño de mujeres administrativo</option>
                            <option>Baño de mujeres bodega</option>
                            <option>Comedor bodega</option>
                            <option>Comedor administrativo</option>
                            <option>Baño de hombres bodega</option>
                            <option>Baño de hombres administrativo</option>
                            <option>Rampa</option>
                            <option>Puerta electrica </option>
                            <option>Recepción</option>
                            <option>Escalera</option>
                            <option>Siatema de iluminación administrativo</option>
                            <option>Pintura de paredes</option>
                            <option>Sistema de iluminacion bodega</option>
                            <option>Estacionamiento</option>
                            <option>Área verde</option>
                            <option>Estanterias</option>
                            <option>Piso de mezzanine</option>
                            <option>Gotera</option>
                        </select>
                </div>
                <div class="form-group">
                    <label for="descripcion">Describa el problema</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="urgencia">Nivel de urgencia</label>
                    <select class="form-control" name="urgencia[]" id="urgencia" required>
                        <option>Seleccione una opción...</option>
                        <option>Regular</option>
                        <option>Urgente</option>
                    </select>
                    <input type="hidden" name="estado" value="Recibido">
                </div>
                <div class="form-group">
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg" <?= $button['is_disabled'] ? 'disabled' : '' ?>>Enviar
                    <div id="spinner" class="spinner-border text-light" role="status" style="display: none;">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
