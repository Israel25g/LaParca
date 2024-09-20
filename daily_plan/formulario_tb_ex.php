<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos.css">
    <title>Helpdesk</title>
    <link rel="shortcut icon" href="images\ICO.png">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <!-- Libreria para alertas ----->


</head>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>formulario_tb_ex</title>
    <!-- Incluir CSS de Bootstrap y DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body class="container mt-5">

    <h1 class="mb-4">Subir datos (Máximo 15 filas)</h1>

    <!-- Tabla con DataTables -->
    <div class="table-responsive">
    <table class="table table-bordered table-hover table-striped" id="editableTable">
            <thead class="table-dark">
                <tr>
                    <th>OID</th>
                    <th>cliente</th>
                    <th>vehículo</th>
                    <th>tipo de vehículo</th>
                    <th>BL</th>
                    <th>Destino</th>
                    <th>Paletas</th>
                    <th>Cajas</th>
                    <th>Unidades</th>
                    <th>pedidos en proceso</th>
                    <th>Fecha objetivo</th>
                    <th>Comentario de oficina</th>
                    <th>Comentario de bodega</th>
                </tr>
            </thead>
            <tbody>
                <!-- Generar 15 filas automáticamente -->
                <script>
                    for (let i = 0; i < 15; i++) {
                        document.write('<tr> <td><input type="text" class="form-control" id="oid"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td> <td><input type="text" class="form-control"></td>  <td><input type="text" class="form-control"></td></tr>');
                    }
                </script>
            </tbody>
        </table>
    </div>

    <div class="d-grid gap-2 col-6 mx-auto mt-3">
        <button class="btn btn-primary" onclick="subirDatos()">Subir Datos</button>
    </div>

    <!-- Scripts de Bootstrap, jQuery y DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#editableTable').DataTable({
                "paging": false,   // No paginación (solo si quieres que siga con 15 filas fijas)
                "searching": true, // Añadir búsqueda
                "ordering": true,  // Permitir ordenar columnas
                "info": false      // Ocultar el texto "Mostrando X de X entradas"
            });
        });

        function subirDatos() {
            let datos = [];
            const filas = document.querySelectorAll("#editableTable tbody tr");

            filas.forEach((fila) => {
                let filaDatos = [];
                fila.querySelectorAll("input").forEach(input => {
                    filaDatos.push(input.value);
                });
                if (filaDatos.some(dato => dato !== "")) {
                    datos.push(filaDatos);
                }
            });

            if (datos.length > 15) {
                alert("Solo puedes subir un máximo de 15 filas.");
                return;
            }

            fetch('subir_datos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                alert("Datos subidos correctamente.");
            })
            .catch(error => {
                console.error('Error al subir los datos:', error);
            });
        }
    </script>

</body>
</html>
