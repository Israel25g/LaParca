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

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

    // Inicializamos resultado con un valor predeterminado para evitar el error
    $resultado = ['mensaje' => ''];

    if (isset($_POST['submit'])) {
        // echo "Formulario enviado.<br>"; // Debug

        $config = include '../config.php';

        try {
            $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
            $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4' // Establecer UTF-8
            ]);

            //echo "Conexión a la base de datos exitosa.<br>"; // Debug

            // Datos del formulario
            $tickets = array(
                "nombrecompleto" => $_POST['nombrecompleto'],
                "correo_sender" => $_POST['correo_sender'],
                "correo_receiver" => $_POST['correo_receiver'] ?? '',
                "ubicacion" => implode(", ", $_POST['ubicacion']),
                "descripcion" => $_POST['descripcion'],
                "urgencia" => implode(", ", $_POST['urgencia']),
                "estado" => $_POST['estado']
            );

            $emails = array_map('trim', explode(',', $tickets['correo_receiver']));

            // Validar correos electrónicos
            $validos = [];
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $validos[] = $email;
                }
            }

            if (!empty($validos)) {
                $tickets['correo_receiver'] = implode(', ', $validos);
            } else {
                $resultado['error'] = true;
                $resultado['mensaje'] = 'No se han ingresado correos electrónicos válidos.';
            }


            // Guardar datos en la base de datos
            $consultaSQL = "INSERT INTO tickets_seguimiento (nombrecompleto, correo_sender, correo_receiver, ubicacion, descripcion, urgencia, estado)";
            $consultaSQL .= " VALUES (:" . implode(", :", array_keys($tickets)) . ")";
            $sentencia = $conexion->prepare($consultaSQL);
            $sentencia->execute($tickets);

            // Guardar datos en la base de datos de registro
            $consultaSQL = "INSERT INTO tickets_seguimiento_r (nombrecompleto, correo_sender, correo_receiver, ubicacion, descripcion, urgencia, estado)";
            $consultaSQL .= " VALUES (:" . implode(", :", array_keys($tickets)) . ")";
            $sentencia = $conexion->prepare($consultaSQL);
            $sentencia->execute($tickets);

            // echo "Datos guardados en la base de datos.<br>"; // Debug

            // Configuración del correo
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ticketpruebas1@gmail.com';
            $mail->Password = 'cyaj xxnu hjof ezrt';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Establecer la codificación de caracteres
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('ticketpruebas1@gmail.com', 'Seguimiento de Temas Pendientes');
            $mail->addAddress($tickets['correo_sender']);

            // $mail->addAddress($tickets['correo_receiver']);
            $mail->addAddress('israel@iplgsc.com'); // Correo adicional
            $mail->addAddress('nohelys@iplgsc.com'); // Correo adicional
            // Añadir destinatarios
            foreach ($validos as $email) {
                $mail->addAddress($email);
            }
            $mail->isHTML(false);
            $mail->Subject = 'Se ha creado una nueva solicitud de seguimiento';
            $mail->Body = "Hola " . $tickets['nombrecompleto'] . ",\n\n" .
                "Queremos informarle que se ha generado una nueva solicitud de seguimiento para un tema importante, acá los detalles:\n\n" .
                "Solicitante: " . $tickets['nombrecompleto'] . ".\n" .
                "Departamento: " . $tickets['ubicacion'] . ".\n" .
                "Urgencia: " . $tickets['urgencia'] . ".\n\n" .
                "Descripción: " . $tickets['descripcion'] . "\n\n" .
                "\n(no responder a este mensaje).";

            $mail->send();

            $resultado['mensaje'] = 'El Ticket ha sido agregado con éxito y el correo se envió correctamente.';
        } catch (PDOException $error) {
            $resultado['error'] = true;
            $resultado['mensaje'] = 'Error en la base de datos: ' . $error->getMessage();
        } catch (Exception $e) {
            $resultado['error'] = true;
            $resultado['mensaje'] = 'Error al enviar el correo: ' . $e->getMessage();
        }
    }
    ?>

    <?php include "../../host_virtual_EEMP/componentesxd/header.php"; ?>



    <div class="tick-main-block">
        <span class="alerta-enviado">
            <?php if (!empty($resultado['mensaje'])) { ?>
                <div class="container mt-3">
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
            <div class="">
                <h2 class="form-tickets"><a href="../index/index_seguimiento.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Ingrese los datos para crear el Ticket</h2>
                <a class="btn btn-success btn-lg" href="../index/index_seguimiento.php">Volver al listado</a>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="nombrecompleto">Nombre completo</label>
                        <input type="text" name="nombrecompleto" id="nombrecompleto" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Adjuntar correo Corporativo</label>
                        <input type="email" name="correo_sender" id="correo_sender" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Adjuntar Destinatario</label>
                        <input type="email" name="correo_receiver" id="correo_receiver" class="form-control" multiple placeholder="Inserte los correos separados por coma: correo1@ejemplo.com,correo2@ejemplo.com,correo3@ejemplo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="ubicacion">Departamento</label>
                        <select class="form-control" name="ubicacion[]" id="ubicacion" required>
                            <option>Seleccione una opción...</option>
                            <option>Trafico</option>
                            <option>Soporte tecnico</option>
                            <option>Mantenimiento</option>
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
                        <input type="text" name="estado" id="estado" value="Recibido" hidden>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Enviar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../../host_virtual_TI/js/script.js"></script>
    <?php include "../templates/footer.php"; ?>
</body>

</html>