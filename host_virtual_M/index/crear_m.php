<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de tickets</title>
    <link rel="stylesheet" href="../../main-global.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../estilos.css">
</head>

<body style="background-image: url('../../host_virtual_TI/images/Motivo2.png');">

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

    $resultado = []; // Variable para mensajes de resultado
    include '../funciones.php';

    if (isset($_POST['submit'])) {
        $resultado = [
            $resultado = [
                'error' => false,
                'mensaje' => 'El Ticket de ' . htmlspecialchars($_POST['nombrecompleto']) . ' ha sido agregado con éxito'
            ]
            
        ];
        $config = include '../config.php';

        try {
            $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
            $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

            $tickets = array(
                "nombrecompleto" => isset($_POST['nombrecompleto']) ? $_POST['nombrecompleto'] : '',
                "correo" => isset($_POST['correo']) ? $_POST['correo'] : '',
                "ubicacion" => isset($_POST['ubicacion']) ? implode(", ", $_POST['ubicacion']) : '',
                "descripcion" => isset($_POST['descripcion']) ? $_POST['descripcion'] : '',
                "urgencia" => isset($_POST['urgencia']) ? implode(", ", $_POST['urgencia']) : ''
            );
            

            // Guardar datos en la base de datos
            $consultaSQL = "INSERT INTO tickets_m (nombrecompleto, correo, ubicacion, descripcion, urgencia)";
            $consultaSQL .= " VALUES (:" . implode(", :", array_keys($tickets)) . ")";
            $sentencia = $conexion->prepare($consultaSQL);
            $sentencia->execute($tickets);

            // Configuración del correo
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'ticketpruebas1@gmail.com';
            $mail->Password = 'nfzs zcii xrhr hyky'; // Asegúrate de usar la contraseña correcta
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Establecer contenido del correo
            $mail->setFrom('ticketpruebas1@gmail.com', 'Departamento de Mantenimiento');
            $mail->addAddress($tickets['correo']); // Correo del usuario
            $mail->addAddress('cecilio@iplgsc.com'); // Correo adicional

            $mail->CharSet = 'UTF-8'; // Asegurando la codificación UTF-8
            $mail->isHTML(false); // Enviar el mensaje en formato texto
            
            $mail->Subject = 'Confirmación de recepción del ticket';

            $mail->Body    = "Hola " . htmlspecialchars($tickets['nombrecompleto']) . ",\n\n" .
            "Gracias por contactarnos. Aquí están los datos que nos suministraste para confirmar su correcto envío:\n\n" .
            "Nombre Completo: " . htmlspecialchars($tickets['nombrecompleto']) . ".\n" .
            "Descripción: " . htmlspecialchars($tickets['descripcion']) . "\n" .
            "Ubicación: " . htmlspecialchars($tickets['ubicacion']) . ".\n" .
            "Urgencia: " . htmlspecialchars($tickets['urgencia']) . ".\n\n" .
            "Atentamente,\nEl departamento de TI\n(no responder a este mensaje).";


            $mail->CharSet = 'UTF-8'; // Asegurando la codificación UTF-8
            $mail->send();

            $resultado['mensaje'] .= "<br>El correo se envió correctamente.";
            
        } catch (PDOException $error) {
            $resultado['error'] = true;
            $resultado['mensaje'] = $error->getMessage();
        } catch (Exception $e) {
            $resultado['error'] = true;
            $resultado['mensaje'] = "Error al enviar el correo: " . $e->getMessage();
        }
    }
    ?>
    <!-- Header -->
    <div class="header">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <h1><a href="../../helpdesk.php">Sistema de Tickets</a></h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual"></p>
        </div>
    </div>
    <!-- Fin del Header -->
    <div class="espacio">
        <span class="margin10"></span>
    </div>

    <?php
    if (isset($resultado)) {
    ?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
                        <?= $resultado['mensaje'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="tickM-main-block">
        <div class="row">
            <div class="col-md-12">
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
