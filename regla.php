<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auto Scroll Table</title>
    <style>
        .scrollable-table {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="scrollable-table" id="autoScrollTable">
    <table>
        <thead>
            <tr><th>Header 1</th><th>Header 2</th><th>Header 3</th></tr>
        </thead>
        <tbody>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <tr><td>Data 1</td><td>Data 2</td><td>Data 3</td></tr>
            <!-- Añade más filas aquí -->
        </tbody>
    </table>
</div>

<script>
    // Función de autoscroll
    function autoScroll() {
        var table = document.getElementById('autoScrollTable');
        table.scrollTop += 1; // Aumentar el scroll cada vez
        if (table.scrollTop >= table.scrollHeight - table.clientHeight) {
            table.scrollTop = 0; // Reiniciar cuando llega al final
        }
    }

    // Ejecutar el autoscroll cada 50ms
    setInterval(autoScroll, 5);
</script>

</body>
</html>
