<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar Datos en JSON</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@11.0.0/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable@11.0.0/dist/handsontable.full.min.js"></script>
</head>
<body>
    <h1>Interfaz estilo Excel - Guardar en JSON</h1>
    <div id="tablaExcel"></div>
    <button id="guardarDatos">Guardar en archivo JSON</button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('tablaExcel');
            
            // Columnas de tu tabla
            const colHeaders = ['Aid/Oid', 'Cliente', 'Vehículo', 'Tipo de Vehículo', 'BL', 'Destino', 'Paletas', 'Cajas', 'Unidades', 'Pedidos en Proceso', 'Fecha Objetivo', 'Comentario Bodega', 'Comentario Oficina'];
            
            const columns = [
                { data: 0, type: 'text' }, // aid_oid
                { data: 1, type: 'text' }, // cliente
                { data: 2, type: 'text' }, // vehiculo
                { data: 3, type: 'text' }, // tipo de vehículo
                { data: 4, type: 'text' }, // bl
                { data: 5, type: 'text' }, // destino
                { data: 6, type: 'numeric' }, // paletas
                { data: 7, type: 'numeric' }, // cajas
                { data: 8, type: 'numeric' }, // unidades
                { data: 9, type: 'numeric' }, // pedidos en proceso
                { data: 10, type: 'date', dateFormat: 'YYYY-MM-DD' }, // fecha objetivo
                { data: 11, type: 'text' }, // comentario bodega
                { data: 12, type: 'text' }  // comentario oficina
            ];

            // Configuración de Handsontable
            const hot = new Handsontable(container, {
                data: Handsontable.helper.createEmptySpreadsheetData(15, 14), // 15 filas, 14 columnas
                rowHeaders: true,
                colHeaders: colHeaders,
                columns: columns,
                minSpareRows: 0,
                contextMenu: true,
                manualColumnResize: true,
                manualRowResize: true,
                stretchH: 'all',
                licenseKey: 'non-commercial-and-evaluation', // Necesario para la versión gratuita
            });

            // Función para guardar los datos en un archivo JSON
            document.getElementById('guardarDatos').addEventListener('click', function () {
                const data = hot.getData(); // Obtener los datos de la tabla

                fetch('guardar_datos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ data: data })
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message); // Mensaje del servidor
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</body>
</html>
