<?php
include '../funcionalidades/funciones.php';
$config = include '../funcionalidades/config_DP.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
  
  // Verificar si 'filtro' e 'id' están presentes y válidos
  if (!isset($_GET['filtro']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    throw new Exception("Parámetros inválidos.");
  }

  $filtro = $_GET['filtro'];
  $id = (int) $_GET['id']; // Convertir a entero para mayor seguridad

  // Determinar la tabla según el filtro
  $tabla = '';
  if ($filtro === 'import') {
    $tabla = 'import';
  } elseif ($filtro === 'export') {
    $tabla = 'export';
  } elseif ($filtro === 'picking') {
    $tabla = 'picking';
  } else {
    throw new Exception("Filtro inválido.");
  }

  // Preparar y ejecutar la consulta SQL usando parámetros seguros
  $consultaSQL = "DELETE FROM $tabla WHERE id = :id";
  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
  $sentencia->execute();

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
} catch(Exception $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}
?>

<?php if (!$resultado['error']): ?>
  <script>
    // Redirigir según el filtro especificado
    setTimeout(function() {
      window.location.href = './tabla_multiple.php?fecha_estimacion_llegada=&filtro=<?= htmlspecialchars($filtro); ?>';
    });
  </script>
<?php else: ?>
  <p>Error: <?= htmlspecialchars($resultado['mensaje']); ?></p>
<?php endif; ?>
