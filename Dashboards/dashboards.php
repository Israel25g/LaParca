<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../estilos.css">

    <!-- Libreria para alertas ----->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

<body>
    <style>
        body {
            background-image: url('../images/Motivo2.png');
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
                <!-- <li class="nav-li"><a href="Index.html">Inicio</a></li> -->
                <!-- <li class="nav-li"><a href="#">Capacitaciones</a></li> -->
                <li class="nav-li"><a href="../helpdesk.php">Mesa de Ayuda (Tickets)</a></li>
                <li class="nav-li"><a href="../daily_plan/index_DP.php">Daily Plan</a></li>
                <li class="nav-li"><a class="active" href="#">Dashboards</a></li>
                <li class="nav-li"><a class="cierre" href="../login/CerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <!-- Fin Navbar -->

    <div class="container-descripcion">
        <div class="bloque-descripcion">
            <h2>Presentamos los Dashboards de Clientes</h2>
            <p>En este apartado, podrá ver una compilación detallada de cada uno de los clientes®</p>
        </div>

    </div>

    <!-- Dashboard STG -->
    <div class="container">
        <div class="bloques ">
            <a href="./Dashboards/dashboardStg.php" rel="noopener noreferrer">
                <div class="bloque" id="bloque_mantenimiento">
                    <img loading="lazy" class="img_helpdesk" id="logo-stg" src="https://lobbymap.org/site//data/001/361/1361877.png" alt="">
                    <div class="text-overlay">
                        <h3>Dashboard STG</h3>
                        
                    </div>
                </div>
            </a>
            <!-- Fin de Mantenimiento -->

            <!-- IT -->
            <a href="host_virtual_TI/index/index_ti.php">
                <div class="bloque" id="bloque_IT">
                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="images\Tecnología.jpg" alt="">
                    <div class="text-overlay">
                        <h3>Soporte de Tecnología</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar alguna reparación en el apartado de IT (Reparaciones y mantenimiento de equipos, reparación de software interno, etc...).</p>
                    </div>
                </div>
            </a>
            <!-- Fin de IT -->

            <!-- Sobre tiempo -->
            <a target="_blank" href="https://forms.office.com/Pages/ResponsePage.aspx?id=1za0vDzJD0-phmo__OXrx2b99J_0mT9Gmm12a6wX-nhUNU9NN0lCUTY0RzFENE1WRU5USFJEOE4zWC4u">
                <div class="bloque" id="bloque_sobretiempo">

                    <img loading="lazy" class="img_helpdesk" id="img_overtime" src="images\Sobretiempo.jpg" alt="">
                    <div class="text-overlay">
                        <h3>Solicitud de Sobretiempo</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar alguna solicitud de sobretiempo.</p>
                    </div>
                </div>
            </a>
            <!-- Fín de sobretiempo -->

            <!-- Compras -->
            <a href="http://" target="_blank" rel="noopener noreferrer">
                <div class="bloque" id="bloque_cotización">
                    <img loading="lazy" class="img_helpdesk" id="img_cotizacion" src="https://consultorfinancontable.com/wp-content/uploads/2024/03/mujer-asiatica-trabajando-traves-papeleo_53876-138148.jpg" alt="">
                    <div class="text-overlay">
                        <h3>Solicitud de orden de compra</h3>
                        <p>Este helpdesk es exclusivamente para la generación de tickets con el objetivo de realizar alguna solicitud cotizaciones.</p>
                    </div>
                </div>
            </a>
            <!-- Fin de Compras -->
        </div>
    </div>



    <script src="../host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/danfojs@1.1.2/lib/bundle.min.js"></script>


    <script>
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
    </script>

</body>

</html>