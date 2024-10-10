<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de tickets</title>
    <link rel="stylesheet" href="../../estilos.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
</head>
<body style="background-image: url('../../images/Motivo2.png')!important;">
    <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

// Inicializamos resultado con un valor predeterminado
$resultado = ['mensaje' => ''];

if (isset($_POST['submit'])) {

    $config = include '../config.php';

    try {
        // Conexión a la base de datos
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
            "urgencia" => implode(", ", $_POST['urgencia'])
        );

        // Guardar datos en la base de datos
        $consultaSQL = "INSERT INTO tickets (nombrecompleto, correo, ubicacion, descripcion, urgencia)";
        $consultaSQL .= " VALUES (:" . implode(", :", array_keys($tickets)) . ")";
        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($tickets);

        // Configuración del correo
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ticketpruebas1@gmail.com';
        $mail->Password = 'cyaj xxnu hjof ezrt';  // Nota: nunca se debe exponer una contraseña en un archivo de producción
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Establecer la codificación de caracteres
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('ticketpruebas1@gmail.com', 'Departamento TI');
        $mail->addAddress($tickets['correo']);
        $mail->addAddress('ricaurte@iplgsc.com'); // Correo adicional

        $mail->isHTML(false);
        $mail->Subject = 'Confirmación de recepción del ticket';
        $mail->Body = "Hola " . $tickets['nombrecompleto'] . ",\n\n" .
                      "Gracias por contactarnos. Aquí están los datos que nos suministraste para confirmar su correcto envío:\n\n" .
                      "Nombre Completo: " . $tickets['nombrecompleto'] . ".\n" .
                      "Descripción: " . $tickets['descripcion'] . "\n" .
                      "Ubicación o Departamento: " . $tickets['ubicacion'] . ".\n" .
                      "Urgencia: " . $tickets['urgencia'] . ".\n\n" .
                      "Atentamente,\nEl departamento de TI\n(no responder a este mensaje).";

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

<?php include "../componentesxd/header.php"; ?>

<?php if (!empty($resultado['mensaje'])) { ?>
    <script>
        <?php if (isset($resultado['error']) && $resultado['error']) { ?>
            alert("<?= addslashes($resultado['mensaje']) ?>");
        <?php } else { ?>
            alert("<?= addslashes($resultado['mensaje']) ?>");
        <?php } ?>
    </script>
<?php } ?>


<div class="tickM-main-block">
        <div class="row" style="margin-left:400px!important; margin-top: 110px!important">
            <div class="col-lg-8">
                <h2 class="">Ingrese los datos para crear el Ticket</h2>
                <a class="btn btn-success btn-lg" href="../index/index_m.php">Volver al listado</a>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="nombrecompleto">Nombre completo</label>
                        <input type="text" name="nombrecompleto" id="nombrecompleto" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Escriba su correo electronico</label>
                        <input type="email" name="correo" id="correo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="ubicacion">Área a reparar</label>
                        <select class="form-control" name="ubicacion[]" id="ubicacion" required>
                            <option>Seleccione una ubicación...</option>
                            <option>Administracion</option>
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
                        <input type="submit" name="submit" class="btn btn-primary btn-lg " value="Enviar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/script.js"></script>

</body>

</html>