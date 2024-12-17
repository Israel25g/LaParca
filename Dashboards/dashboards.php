<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboards</title>
    <link rel="stylesheet" href="../main-global.css">
    <link rel="shortcut icon" href="../images/ICO.png">

    <!-- Libreria para alertas ----->
    <!-- Incluir Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Incluir ECharts desde el CDN -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.0/dist/echarts.min.js"></script>

</head>

<body>
    <style>
        body {
            background-image: url('../images/Motivo2.png')!important;
            margin: 0;
            padding: 0;
            background-size: cover;
        }
    </style>

    <!-- Header -->
    <div class="header-error">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <h1>Dashboards</h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual">prueba</p>
        </div>
    </div>
    <!-- Fin del Header -->

    <!-- Navbar -->
    <div class="container-nav">
        <div class="my-navbar">
            <ul class="my-nav" id="detallesOps">
                <li class="nav-li"><a href="../helpdesk.php">Helpdesk</a></li>
                <!-- <li class="nav-li"><a href="#">Capacitaciones</a></li> -->
                <li class="nav-li"><a href="../daily_plan/index_DP.php">Daily Plan</a></li>
                <!-- <li class="nav-li"><a href="./Dashboards/dashboards_extern.php">Dashboards</a></li> -->
                <li class="nav-li"><a class="cierre" href="../login/CerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <!-- Fin Navbar -->

    <div class="container-descripcion">
        <div class="bloque-descripcion">
            <h2>Presentamos los Dashboards de IPL Group</h2>
            <span>En este apartado, podrá ver una compilación detallada de cada uno de los reportes, internos y externos de IPL.</span>
        </div>

    </div>

        <!-- Mantenimiento -->
        <div class="container-block">
        <div class="bloques-grid">
            <a href="./clientes/saint_g/Dash_b.php?fecha_inicio=2000-01-01&fecha_final=2000-01-01&Cliente=" rel="noopener noreferrer">
                <div class="bloque" id="bloque_mantenimiento">
                <img loading="lazy" class="img_helpdesk  bg-success" id="logo-stg" src="../images/dashboards_externos.jpg" alt="">
                    <div class="text-overlay">
                        <h3>Dashboards externos</h3>
                        <p>En este apartado podra visualizar el estado de las operaciones de los clientes de IPL Group</p>
                    </div>
                </div>
                <!-- Fin de Mantenimiento -->

            </a>

            <a href="#" rel="noopener noreferrer">
                <div class="bloque" id="bloque_mantenimiento">
                <img loading="lazy" class="img_helpdesk  bg-success" id="logo-stg" alt="">
                    <div class="text-overlay">
                        <h3>Dashboards externos</h3>
                        <p></p>
                    </div>
                </div>
            </a>
        </div>
    </div>


    <script src="../host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/danfojs@1.1.2/lib/bundle.min.js"></script>
</body>

</html>