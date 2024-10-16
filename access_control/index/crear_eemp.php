<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de tickets</title>
    <link rel="stylesheet" href="../../host_virtual_EEMP/estilosT.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
</head>

<body style=" margin: 0; padding: 0; background-image: url('../../host_virtual_TI/images/Motivo2.png'); font-family:montserrat;">
    <?php
    include '../funciones.php';

    if (isset($_POST['submit'])) {
        $resultado = [
            'error' => false,
            'mensaje' => 'El Ticket de ' . $_POST['nombrecompleto'] . ' ha sido agregado con éxito'
        ];
        $config = include '../config.php';

        try {
            $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
            $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

            $tickets = array(
                "nombrecompleto"   => $_POST['nombrecompleto'],
                "correo"           => $_POST['correo'],
                "ubicacion"        => implode(", ", $_POST['ubicacion']), // Convertir array a string
                "descripcion"      => $_POST['descripcion'],
                "urgencia"         => implode(", ", $_POST['urgencia']) // Convertir array a string
            );

            $consultaSQL = "INSERT INTO tickets_eemp (nombrecompleto, correo, ubicacion, descripcion, urgencia)";
            $consultaSQL .= " VALUES (:" . implode(", :", array_keys($tickets)) . ")";

            $sentencia = $conexion->prepare($consultaSQL);
            $sentencia->execute($tickets);

            // Guardar datos en un archivo para que el script Python los lea
            $form_data = "nombrecompleto=" . htmlspecialchars($_POST['nombrecompleto']) . "\n" .
                "correo=" . htmlspecialchars($_POST['correo']) . "\n" .
                "descripcion=" . htmlspecialchars($_POST['descripcion']) . "\n" .
                "ubicacion=" . htmlspecialchars(implode(", ", $_POST['ubicacion'])) . "\n" .
                "urgencia=" . htmlspecialchars(implode(", ", $_POST['urgencia'])) . "\n";
            file_put_contents('form_data_user.txt', $form_data);

            // Ejecutar el script Python
            $command = 'python3 send_email.py';
            exec($command, $output, $return_var);

            if ($return_var === 0) {
                $resultado['mensaje'] .= "<br>El correo se envio correctamente.";
            } else {
                $resultado['error'] = true;
                $resultado['mensaje'] .= "<br>Error al enviar el correo.";
            }
        } catch (PDOException $error) {
            $resultado['error'] = true;
            $resultado['mensaje'] = $error->getMessage();
        }
    }
    ?>
    <?php include "../templates/header.php"; ?>
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
    <?php } ?>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Ingrese los datos para crear el Ticket</h2>
                <a class="btn btn-success btn-lg" href="../index/index_eemp.php">Volver al listado</a>
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
                        <select class="form-control" name="ubicacion[]" id="ubicacion" required>
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
                        <input type="submit" name="submit" class="btn btn-primary btn-lg " value="Enviar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include "../templates/footer.php"; ?>
</body>

</html>