
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de tickets</title>
  <link rel="stylesheet" href="../../host_virtual_TI/estilosT.css">
  <link rel="shortcut icon" href="../../images/ICO.png">

</head>

<body style=" font-family:montserrat; background-image: url('../../host_virtual_TI/images/Motivo2.png');margin: 0; padding: 0;">

<?php
include '../funciones.php';

$error = false;
$config = include '../config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  $consultaSQL = "SELECT * FROM tickets_m";

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $tickets = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}
?>

<?php include "../templates/header.php"; ?>

<?php
if ($error) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <a href="crear_m.php"  class="btn btn-success "><i class="bi bi-pen-fill"></i> Crear Ticket</a>
      <hr>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3">Listado de ticket de Mantenimiento</h2>
      <table class="table shadow-sm p-3 mb-5 bg-body-tertiary rounded table-striped">
        <thead>
          <tr>
            <th>TID</th>
            <th>Solicitante</th>
            <th>correo</th>
            <th>Departamento</th>
            <th>Estado del problema</th>
            <th>nivel de urgencia</th>
            <th>Respuesta</th>
            <th>Estado</th>
            <th>fecha de creacion</th>
            <th>ultima actualizacion</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($tickets && $sentencia->rowCount() > 0) {
            foreach ($tickets as $fila) {
              ?>
              <tr>
                <td class="text-break"><?php echo escapar($fila["id"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["nombrecompleto"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["correo"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["ubicacion"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["descripcion"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["urgencia"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["respuesta"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["estado"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["created_at"]); ?></td>
                <td class="text-break"><?php echo escapar($fila["updated_at"]); ?></td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
    </div>
  </div>
</div>

<?php include "../templates/footer.php"; ?>

  
</body>
</html>