<?php
include("../apertura_sesion.php");
include '../daily_plan/funcionalidades/funciones.php';
$config = include '../daily_plan/funcionalidades/config_DP.php';
$error = false;

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

    if ($filtro == 'incompletos') {
        $consultaSQL = "SELECT * FROM picking WHERE division_dp < 1.00";
    } else {
        $consultaSQL = "SELECT * FROM picking";
    }

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
    $datos = $sentencia->fetchAll();
} catch (PDOException $error) {
    $error = $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Plan - Picking</title>

  <!-- Incluye jQuery antes de otros scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../main-global.css">
  <link rel="shortcut icon" href="../images/ICO.png">
</head>
<body>
  <div style="margin-top: 90px;">
    <!-- Header -->
    <div class="header-error">
      <h1>Daily plan</h1>
    </div>
    
    <!-- Filtro para la consulta -->
    <form method="GET" class="mb-3">
      <label for="filtro">Elige el filtro:</label>
      <select name="filtro" id="filtro" class="form-control">
        <option value="todos" <?= isset($_GET['filtro']) && $_GET['filtro'] == 'todos' ? 'selected' : '' ?>>Mostrar todos</option>
        <option value="incompletos" <?= isset($_GET['filtro']) && $_GET['filtro'] == 'incompletos' ? 'selected' : '' ?>>Mostrar incompletos (division_dp < 1.00)</option>
      </select>
      <button type="submit" class="btn btn-primary mt-2">Aplicar Filtro</button>
    </form>

    <!-- Mostrar el mensaje sobre el filtro aplicado -->
    <div class="alert alert-info mt-2">
      <?php if ($filtro == 'incompletos'): ?>
        <p>Mostrando solo los registros con avance incompleto (division_dp < 1.00).</p>
      <?php else: ?>
        <p>Mostrando todos los registros de picking.</p>
      <?php endif; ?>
    </div>

    <!-- Tabla 'datos' -->
    <div class="tabla-container">
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <table id="tablaPicking" class="display table ...">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Division DP</th>
            <!-- Añade aquí las demás cabeceras de tu tabla -->
          </tr>
        </thead>
        <tbody>
          <?php if ($datos && $sentencia->rowCount() > 0): ?>
            <?php foreach ($datos as $fila): ?>
              <tr>
                <td><?= htmlspecialchars($fila['id']) ?></td>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= htmlspecialchars($fila['division_dp']) ?></td>
                <!-- Añade aquí las demás celdas de tu tabla -->
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="text-center">No hay registros disponibles.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script>
      $(document).ready(function() {
        new DataTable('#tablaPicking', {
          paging: false,
          scrollCollapse: true,
          scrollY: '350px',
          scrollX: '1700px',

          initComplete: function() {
            this.api()
              .columns()
              .every(function() {
                let column = this;
                let title = column.footer().textContent;

                // Create input element
                let input = document.createElement('input');
                input.placeholder = title;
                column.footer().replaceChildren(input);

                // Event listener for user input
                input.addEventListener('keyup', () => {
                  if (column.search() !== input.value) {
                    column.search(input.value).draw();
                  }
                });
              });
          },
          buttons: [
            {
              extend: 'copy',
              text: 'Copiar',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
              }
            },
            {
              extend: 'csv',
              text: 'CSV',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
              }
            },
            {
              extend: 'excel',
              text: 'Excel',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
              }
            },
            {
              extend: 'pdf',
              text: 'PDF',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
              }
            },
            {
              extend: 'print',
              text: 'Imprimir',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
              }
            }
          ],
          dom: 'Bfrtip', // Asegura que los botones aparezcan en el lugar correcto
          info: false,
          language: {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
              "first": "<◀",
              "last": "▶> ",
              "next": "▶",
              "previous": "◀"
            },
            "buttons": {
              "copy": "Copiar",
              "csv": "CSV",
              "excel": "Excel",
              "pdf": "PDF",
              "print": "Imprimir"
            }
          }
        });
      });
    </script>
  </div>
</body>
</html>
