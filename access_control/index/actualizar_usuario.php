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

    $dic_update = [
      "id" => $_GET['id'],
      "user" => $_POST['user'],
      "departamento" => $_POST['departamento'],
      "estado" => $_POST['estado']
    ];

    $consultaSQL3 = "UPDATE users SET 
    user = :user, 
    departamento = :departamento, 
    estado = :estado
    updated_at = NOW()
    WHERE id = :id";

    $sentencia2 = $conexion->prepare($consultaSQL3);
    $sentencia2->execute($dic_update);
    
  } catch (PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
?>
<script>
  // Redirigir
  // setTimeout(function() {
  //   window.location.href = '../index/index_users.php';
  // });
</script>