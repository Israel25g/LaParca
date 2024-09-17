<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistema de tickets</title>
    <link rel="stylesheet" href="../../host_virtual_TI/estilosT.css">
    <link rel="shortcut icon" href="../images/ICO.png">
  </head>
  <body style=" margin: 0; padding: 0; background-image: url('../../host_virtual_TI/images/Motivo2.png');font-family:montserrat;">
    <?php
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
            $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
            $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

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

            // Consulta unificada para obtener el correo y nombrecompleto
            $consultaInfo = "SELECT correo, nombrecompleto FROM tickets WHERE id = :id";
            $consultaInfoStmt = $conexion->prepare($consultaInfo);
            $consultaInfoStmt->execute(['id' => $_GET['id']]);
            $info = $consultaInfoStmt->fetch(PDO::FETCH_ASSOC);

            // Guardar los datos en form_data_Admin.txt
            $formData =
                "id=" . $_GET['id'] . "\n".
                "respuesta=" . $_POST['respuesta'] . "\n" .
                "estado=" . $_POST['estado'] . "\n" .
                "correo=" . $info['correo'] . "\n" . // Añadir el correo obtenido de la base de datos
                "nombrecompleto=" . $info['nombrecompleto'] . "\n"; // Añadir el nombre completo obtenido de la base de datos

            file_put_contents('form_data_Admin.txt', $formData);

            // Ejecutar el script de Python para enviar el correo y capturar el resultado
            $output = [];
            $return_var = 0;
            exec('python3 send_email_admin.py 2>&1', $output, $return_var);

            if ($return_var === 0) {
                $resultado['mensaje'] = 'El Ticket ha sido respondido con éxito y el correo se envió correctamente.';
            } else {
                $resultado['error'] = true;
                $resultado['mensaje'] = 'El Ticket ha sido espondido con éxito, pero hubo un problema al enviar el correo.';
            }

        } catch(PDOException $error) {
            $resultado['error'] = true;
            $resultado['mensaje'] = $error->getMessage();
        }
    }

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

    } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
    ?>

    <?php include "../templates/header.php"; ?>

    <?php
    if ($resultado['error']) {
      ?>
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
              <?= $resultado['mensaje'] ?>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    ?>

    <?php
    if (isset($_POST['submit']) && !$resultado['error']) {
      ?>
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success" role="alert">
              <?= $resultado['mensaje'] ?>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    ?>

    <?php
    if (isset($tickets) && $tickets) {
      ?>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2 class="mt-4">Respondiendo al Ticket de <?= escapar($tickets['nombrecompleto'])?>, sobre: <?= escapar($tickets['descripcion'])?></h2>
            <a class="btn btn-success" href="../indexAdmin/indexAdmin_ti.php">Regresar al listado</a>
            <hr>
            <form method="post">
              <div class="form-group">
                <label for="respuesta">Respuesta del ticket</label>
                <textarea type="text" name="respuesta" id="respuesta" rows="3" class="form-control" required><?= escapar($tickets['respuesta']) ?></textarea>
              </div>
              <div class="form-group">
                <label for="estado">Estado del Ticket</label>
                <select class="form-control" name="estado" id="estado"required>
                  <option></option>
                  <option <?= ($tickets['estado'] == 'No iniciado') ? 'selected' : '' ?>>No iniciado</option>
                  <option <?= ($tickets['estado'] == 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                  <option <?= ($tickets['estado'] == 'Terminado') ? 'selected' : '' ?>>Terminado</option>
                  <option <?= ($tickets['estado'] == 'En espera de aprobación') ? 'selected' : '' ?>>En espera de aprobación</option>
                  <option <?= ($tickets['estado'] == 'En cotización') ? 'selected' : '' ?>>En cotización</option>
                </select>
              </div>
              <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" id="liveToastBtn" value="Responder">
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php
    }
    ?>

    <?php require "../templates/footer.php"; ?>

  </body>
</html>
