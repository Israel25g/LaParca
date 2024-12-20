<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tamaño de la pantalla</title>
    <?php $test = include '../daily_plan/funcionalidades/config_DP.php';
    var_dump($test)
    ?>
    <style>
        body {
            font-family: montserrat, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        /* Estilos para la cruz en el centro */
        .cross {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .cross::before, .cross::after {
            content: '';
            position: absolute;
            background-color: green;
        }

        .cross::before {
            width: 2px;
            height: 100%;
            left: 50%;
            transform: translateX(-50%);
        }

        .cross::after {
            width: 100%;
            height: 2px;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Estilo del contenedor de la información */
        .info {
            text-align: center;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="info">
        <h2>Tamaño de tu pantalla</h2>
        <p id="screen-size"></p>
    </div>

    <div class="cross"></div>

    <script>
        const screenSize = document.getElementById('screen-size');

        function updateScreenSize() {
            screenSize.textContent = `Ancho: ${window.innerWidth}px, Alto: ${window.innerHeight}px`;
        }

        updateScreenSize();

        // Recargar la página cada segundo
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>

</body>
</html>


