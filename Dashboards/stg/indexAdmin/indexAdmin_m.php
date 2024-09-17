<?php
session_start();
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
      <h2 class="mt-3">Listado de tickets de Mantenimiento</h2>
      <table class="table shadow-sm p-3 mb-5 bg-body-tertiary" style="--bs-border-opacity: .5;">
        <thead>
        <tr>
            <th>TID</th>
            <th>Solicitante</th>
            <th>Correo</th>
            <th>Área</th>
            <th>Tipo de problema</th>
            <th>Nivel de urgencia</th>
            <th>Respuesta</th>
            <th>Estado</th>
            <th>Fecha de creacion</th>
            <th>Ultima actualizacion</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($tickets && $sentencia->rowCount() > 0) {
            foreach ($tickets as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id"]); ?></td>
                <td><?php echo escapar($fila["nombrecompleto"]); ?></td>
                <td><?php echo escapar($fila["correo"]); ?></td>
                <td><?php echo escapar($fila["ubicacion"]); ?></td>
                <td><?php echo escapar($fila["descripcion"]); ?></td>
                <td><?php echo escapar($fila["urgencia"]); ?></td> 
                <td><?php echo escapar($fila["respuesta"]); ?></td> 
                <td><?php echo escapar($fila["estado"]); ?></td> 
                <td><?php echo escapar($fila["created_at"]); ?></td> 
                <td><?php echo escapar($fila["updated_at"]); ?></td> 
            <td>  
            <a type="button" class="btn btn-outline-danger fs-1" data-toggle="modal" data-target="#login-<?= $fila["id"] ?>"><i class="bi bi-trash3-fill"></i></a>
            <form class="form-inline">
                <!-- Modal -->
                    <div id="login-<?= $fila["id"] ?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">¿Está seguro de eliminar este registro?</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <p>Este no podra ser recuperado.</p>
                          </div>
                          <div class="modal-footer">
                            <a type="button" class="btn btn-default" data-dismiss="modal">Cerrar</a>
                            <a class="btn btn-danger" href="<?='borrar.php?id=' . escapar($fila["id"]) ?>">Borrar</a>
                          </div>
                        </div>
                      </div>
                    </div>
                <!--modal -->
            </form>
            <a class="btn btn-outline-success fs-1 "  href="<?='responder_m.php?id=' . escapar($fila["id"]) ?>"><i class="bi bi-envelope-fill"></i></a>
            </td>
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