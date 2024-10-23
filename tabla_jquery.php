
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Tabla en Tiempo Real con jQuery y PHP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
  <link rel="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel=" https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Clientes</h2>
    <!-- Tabla de Clientes -->
    <table id="clientes-table" border="1">
        <thead>
            <tr>
                <th>AID</th>
                <th>Cliente</th>
                <th>vehiculo</th>
                <th>tipo de veiculo</th>
                <th>destino</th>
                <th>tipo de carga </th>
                <th>paletas</th>
                <th>cajas</th>
                <th>unidades</th>
                
            </tr>
        </thead>
        <tbody>
            <!-- Las filas se llenarán dinámicamente -->
        </tbody>
    </table>

    <script type="text/javascript">
        // Función para cargar y actualizar la tabla de clientes
        function cargarClientes() {
            $.ajax({
                url: 'api_tabla_jquery.php', // Archivo PHP que devuelve los datos
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Limpiar las filas actuales
                    $('#clientes-table tbody').empty();
                    
                    // Recorrer los datos y agregarlos a la tabla
                    $.each(data, function(index, cliente) {
                        $('#clientes-table tbody').append(
                            '<tr>' +
                            '<td>' + cliente.aid_oid + '</td>' +
                            '<td>' + cliente.cliente + '</td>' +
                            '<td>' + cliente.vehiculo + '</td>' +
                            '<td>' + cliente.t_vehiculo + '</td>' +
                            '<td>' + cliente.bl + '</td>' +
                            '<td>' + cliente.destino + '</td>' +
                            '<td>' + cliente.t_carga + '</td>' +
                            '<td>' + cliente.paletas + '</td>' +
                            '<td>' + cliente.cajas + '</td>' +
                            '<td>' + cliente.unidades + '</td>' +
                            '</tr>'
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error al obtener los datos:", error);
                }
            });
        }

        // Cargar los datos cuando la página se carga por primera vez
        $(document).ready(function() {
            cargarClientes();

            // Opción 1: Actualizar automáticamente cada 10 segundos
            setInterval(cargarClientes, 5000);

        });
    </script>

</body>
</html>
