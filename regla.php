<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablas con Auto-scroll</title>
    <style>
        .table-container {
            width: 50%; 
            height: 300px;
            overflow-y: auto; 
            border: 1px solid #ccc;
            margin-bottom: 20px; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

    <h2>Tablas con Auto-scroll</h2>
    
    <!-- Tabla 1 -->
    <div class="table-container scrollable-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <!-- Añade varias filas para observar el scroll -->
                <tr><td>1</td><td>Cliente 1</td></tr>
                <tr><td>2</td><td>Cliente 2</td></tr>
                <tr><td>3</td><td>Cliente 3</td></tr>
                <tr><td>4</td><td>Cliente 4</td></tr>
                <tr><td>5</td><td>Cliente 5</td></tr>
                <tr><td>6</td><td>Cliente 6</td></tr>
                <tr><td>6</td><td>Cliente 6</td></tr>
                <tr><td>6</td><td>Cliente 6</td></tr>
                <tr><td>6</td><td>Cliente 6</td></tr>
                <tr><td>6</td><td>Cliente 6</td></tr>
                <tr><td>6</td><td>Cliente 6</td></tr>
                <!-- Puedes añadir más filas -->
            </tbody>
        </table>
    </div>

    <!-- Tabla 2 (con menos contenido para probar si tiene scroll) -->
    <div class="table-container scrollable-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Cliente 1</td></tr>
                <tr><td>2</td><td>Cliente 2</td></tr>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Seleccionamos todos los contenedores con la clase 'scrollable-table'
            var $tableContainers = $('.scrollable-table');
            var scrollSpeed = 50; // Milisegundos por píxel
            var scrollingIntervals = [];

            // Función para iniciar el auto-scroll en una tabla específica
            function startAutoScroll($container) {
                var containerHeight = $container.height();
                var scrollHeight = $container[0].scrollHeight;
                var scrollDirection = 1; // 1 para abajo, -1 para arriba

                // Solo aplica el scroll si el contenido es más alto que el contenedor
                if (scrollHeight > containerHeight) {
                    var interval = setInterval(function() {
                        var currentScrollPos = $container.scrollTop();
                        var newScrollPos = currentScrollPos + scrollDirection;

                        // Cambia de dirección cuando llega al fondo o al inicio
                        if (newScrollPos >= scrollHeight - containerHeight || newScrollPos <= 0) {
                            scrollDirection *= -1; // Cambia la dirección del scroll
                        }

                        $container.scrollTop(newScrollPos);
                    }, scrollSpeed);

                    // Guardamos el intervalo de cada tabla
                    scrollingIntervals.push(interval);
                }
            }

            // Inicia el auto-scroll en cada tabla que lo necesite
            $tableContainers.each(function() {
                startAutoScroll($(this));
            });

            // Pausa el auto-scroll cuando el usuario interactúa con alguna tabla
            $tableContainers.on('mouseenter', function() {
                var index = $tableContainers.index(this);
                clearInterval(scrollingIntervals[index]);
            });

            // Reinicia el auto-scroll cuando el usuario deja de interactuar
            $tableContainers.on('mouseleave', function() {
                var index = $tableContainers.index(this);
                startAutoScroll($(this));
            });
        });
    </script>
</body>
</html>

