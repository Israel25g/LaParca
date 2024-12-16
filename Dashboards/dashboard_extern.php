<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="shortcut icon" href="../images/ICO.png">
    <link rel="stylesheet" href="../estilos.css">

    <!-- Libreria para alertas ----->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
        <h1><a class="link_h1" href="../Dashboards/dashboards.php">Dashboards Externos</a></h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual">prueba</p>
        </div>
    </div>
    <!-- Fin del Header -->

     <!-- Navbar -->
    <!-- <div class="container-nav">
        <div class="navbar">
            <ul class="nav" id="detallesOps">
                <li class="nav-li"><a href="Index.html">Inicio</a></li>
                <li class="nav-li"><a href="#">Capacitaciones</a></li>
                <li class="nav-li"><a href="../helpdesk.php">Mesa de Ayuda (Tickets)</a></li>
                <li class="nav-li"><a href="../daily_plan/index_DP.php">Daily Plan</a></li>
                <li class="nav-li"><a class="active" href="#">Dashboards</a></li>
                <li class="nav-li"><a class="cierre" href="../login/CerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div> -->

    <!-- Fin Navbar  -->

    <!-- detalles -->
    <!-- <div class="margin_top">
        <span style="margin-top: 30px;"></span>
    </div>
    <div class="dashboards-container">
        <div class="dasboards-header">
            <h3>Dashboard de Tickets</h3>
        </div>
        <button type="button" class="btn btn-primary position-relative">
            Inbox
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                10
                <span class="visually-hidden">unread messages</span> !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! importante 
            </span>
        </button>
    </div> -->

    <!-- fin de detalles -->
    
    <div class="container-descripcion">
        <div class="bloque-descripcion">
            <h2>Presentamos los Dashboards de Clientes</h2>
            <p>En este apartado, podrá ver una compilación detallada de cada uno de los clientes®</p>
        </div>

    </div>

    <!-- Dashboard STG -->
    <div class="container">
        <div class="bloques ">
            <a href="../Dashboards/clientes/saint_g/Dash_b.php?fecha_inicio=&fecha_final=&Cliente=" rel="noopener noreferrer">
                <div class="bloque" id="bloque_mantenimiento">
                    <img  class="img_helpdesk " id="logo-stg" style="height: auto; margin-top: 15px" src="https://lobbymap.org/site//data/001/361/1361877.png" alt="">
                </div>
            </a>
            <!-- Fin de Mantenimiento -->

            <!-- IT -->
            <a href="host_virtual_TI/index/index_ti.php">
                <div class="bloque " id="bloque_IT">
                    <!-- <img loading="lazy" class="img_helpdesk" id="img_IT" src="../images/ipl_logo_gris.jpg" alt=""> -->
                </div>
            </a>
            <!-- Fin de IT -->

            <!-- Sobre tiempo -->
            <a  href="#">
                <div class="bloque" id="bloque_sobretiempo">
                    <!-- <img loading="lazy" class="img_helpdesk" id="img_overtime" src="#" alt=""> -->
                </div>
            </a>
            <!-- Fín de sobretiempo -->

            <!-- Compras -->
            <a href="#"  rel="noopener noreferrer">
                <div class="bloque" id="bloque_cotización">
                    <!-- <img loading="lazy" class="img_helpdesk" id="img_cotizacion" src="#" alt=""> -->

                </div>
            </a>
            <!-- Fin de Compras -->
        </div>
    </div>













    <!--======================================================================================================== evitar tocar ================================================================================ -->
    <script src="../host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
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
                window.location.href = "login/index.php";
            }

            function resetTimer() {
                clearTimeout(temporizador);
                temporizador = setTimeout(cerrarSesion, 100000); // 1000 milisegundos = 1 segundo
            }
            console.log(temporizador);
        }

        registrarInactividad();
    </script> -->

</body>

</html>