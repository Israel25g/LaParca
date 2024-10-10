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
  
  $dic_update = [
    $id => $_GET['id'],
    $user => $_POST['user'],
    $departamento => $_POST['departamento'],
    $estado => $_POST['estado']
  ];

  $consultaSQL = "UPDATE users SET `user` = $user, `departamento` $departamento, `estado` = $estado WHERE id = $id";

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}
?>
<script>
  // Redirigir
  // setTimeout(function() {
  //   window.location.href = '../index/index_users.php';
  // });
</script>