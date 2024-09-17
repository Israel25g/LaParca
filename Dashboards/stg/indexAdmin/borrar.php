<?php
include '../funciones.php';

$config = include '../config.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['id'];
  $consultaSQL = "DELETE FROM tickets_m WHERE id =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}
?>
<script>
  // Redirigir
  setTimeout(function() {
    window.location.href = '../indexAdmin/indexAdmin_m.php';
  });
</script>