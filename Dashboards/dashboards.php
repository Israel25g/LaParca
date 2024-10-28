<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboards</title>
    <link rel="stylesheet" href="../estilos.css">
    <link rel="shortcut icon" href="../images/ICO.png">

    <!-- Libreria para alertas ----->
    <!-- Incluir Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../daily_plan/css/estilos.css">
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
    <div class="header">
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
        <div class="navbar">
            <ul class="nav" id="detallesOps">
                <li class="nav-li"><a href="../helpdesk.php">Mesa de Ayuda</a></li>
                <li class="nav-li"><a href="../daily_plan/index_DP.php">Daily Plan</a></li>
                <li class="nav-li"><a class="active" href="#">Dashboards</a></li>
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

    <!-- Dashboard externos -->
    <div class="container">
        <div class="bloques ">
            <a href="../Dashboards/dashboard_extern.php" rel="noopener noreferrer">
                <div class="bloque" id="bloque_mantenimiento">
                    <img loading="lazy" class="img_helpdesk  bg-success" id="logo-stg" src="../images/dashboards_externos.jpg" alt="">
                    <div class="text-overlay">
                        <h3>Dashboards externos</h3>
                        <p>En este apartado podra visualizar el estado de las operaciones de los clientes de IPL Group</p>
                    </div>
                </div>
            </a>
            <!-- Fin de Dashboard externos -->

            <!-- Dashboard internos -->
            <a href="host_virtual_TI/index/index_ti.php">
                <div class="bloque " id="bloque_IT">
                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="../images/ipl_logo_gris.jpg" alt="">
                    <div class="text-overlay  bg-dark">
                        <h3>Dashboards internos</h3>
                        <p>En este apartado podra visualizar el estado de las operaciones de IPL Group</p>
                    </div>
                </div>
            </a>
            <!-- Fin de Dashboard internos -->

            <!-- placeholder -->
            <a  href="#">
                <div class="bloque" id="bloque_sobretiempo">
                    <!-- <img loading="lazy" class="img_helpdesk" id="img_overtime" src="#" alt=""> -->
                    <div class="text-overlay">
                    </div>
                </div>
            </a>
            <!-- Fín de placeholder -->

            <!-- placeholder -->
            <a href="#"  rel="noopener noreferrer">
                <div class="bloque" id="bloque_cotización">
                    <!-- <img loading="lazy" class="img_helpdesk" id="img_cotizacion" src="#" alt=""> -->
                    <div class="text-overlay">
                    </div>
                </div>
            </a>
            <!-- Fin de placeholder -->
        </div>
    </div>



    <script src="../host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/danfojs@1.1.2/lib/bundle.min.js"></script>


    <!-- <script>
        // temporizador

        let registrarInactividad = function() {
            var temporizador;
            window.onload = resetTimer;
            // DOM Events
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.onmousedown = resetTimer;
            document.ontouchstart = resetTimer;
            document.onclick = resetTimer;
            document.onscroll = resetTimer;

            function cerrarSesion() {
                toastr.warning("Sesión cerrada por inactividad.");
                window.location.href = "../login/index.php";
            }

            function resetTimer() {
                clearTimeout(temporizador);
                temporizador = setTimeout(cerrarSesion, 1000000); // 1000 milisegundos = 1 segundo
            }

        }

        registrarInactividad();
    </script> -->

</body>

</html>