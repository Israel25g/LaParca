
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Tabla en Tiempo Real con jQuery y PHP</title>
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
        <?php include "../sistema_de_tickets/daily_plan/datatable.php" ?>
    <script>
      $(document).ready(function() {
        new DataTable('#clientes-table', {
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
                  if (column.search() !== this.value) {
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
                        columns: [0, 1, 2,3,4,5,6,7,8] 

                      }
                    },
                    {
                      extend: 'csv',
                      text: 'CSV',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8]
                      }
                    },
                    {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8]
                      }
                    },
                    {
                      extend: 'pdf',
                      text: 'PDF',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8]
                      }
                    },
                    {
                      extend: 'print',
                      text: 'Imprimir',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8]
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

</body>
</html>
