<?php
include("apertura_sesion.php")
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main-global.css">
    <title>Helpdesk</title>
    <link rel="shortcut icon" href="images\ICO.png">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <!-- Libreria para alertas ----->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

<body style="background-image: url('./images/Motivo2.png')">
    <!-- Header -->
    <div class="header">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <h1>Helpdesk</h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual"></p>
        </div>
    </div>
    <!-- Fin del Header -->

    <!-- Navbar -->
    <div class="container-nav">
        <div class="navbar">
            <ul class="nav" id="detallesOps">
                <!-- <li class="nav-li"><a href="Index.html">Inicio</a></li> -->
                <!-- <li class="nav-li"><a href="#">Capacitaciones</a></li> -->
                <li class="nav-li"><a class="active" href="#">Mesa de Ayuda (Tickets)</a></li>
                <li class="nav-li"><a href="./daily_plan/index_DP.php<?php session_id() ?>">Daily Plan</a></li>
                <!-- <li class="nav-li"><a href="Dashboards/dashboards.php">Dashboards</a></li> -->
                 <?php 
                    if($_SESSION['rol'] === 'Admin'){
                        echo '<li class="nav-li"><a href="access_control/index/index_users.php">Control de Usuarios</a></li>';
                    }
                 ?>
                <li class="nav-li"><a class="cierre" href="./login/CerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>
    <!-- Fin Navbar -->

    <!-- Links -->
    <div class="container-descripcion">
        <div class="bloque-descripcion">
            <h2>¿En qué podemos ayudarte?</h2>
            <h4>Selecciona una de las siguientes opciones para generar un ticket de soporte.</h4>
        </div>
    </div>

    <!-- Mantenimiento -->
    <div class="container-block">
        <div class="bloques-grid">
            <a href="host_virtual_M\index\index_m.php" rel="noopener noreferrer">
                <div class="bloque" id="bloque_mantenimiento">
                    <img loading="lazy" class="img_helpdesk" id="img_mantenimiento" src="images\Mantenimiento.webp" alt="">
                    <div class="text-overlay">
                        <h3>Soporte de mantenimiento</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar alguna reparación en la infraestructura de la empresa y/o departamento.</p>
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

            <!-- EEMP -->
            <a href="host_virtual_EEMP/index/index_eemp.php">
                <div class="bloque" id="bloque_EEMP">
                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="https://cdn.udax.edu.mx/blog/la-clave-del-exito-en-los-negocios-mejora-continua-y-optimizacion-de-procesos_1.jpg" alt="">
                    <div class="text-overlay">
                        <h3>Soporte de Mejoras</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar solicitudes de mejora/DEPIC's en cuanto al sistema DIPROP</p>
                    </div>
                </div>
            </a>
            <!-- Fin de EEMP -->
            
            <!-- Sobre tiempo -->
            <a target="https://forms.office.com/Pages/ShareFormPage.aspx?id=1za0vDzJD0-phmo__OXrx2b99J_0mT9Gmm12a6wX-nhUNU9NN0lCUTY0RzFENE1WRU5USFJEOE4zWC4u&sharetoken=NhdNt0HuLG7WFd5Sc9QH" href="https://forms.office.com/Pages/ResponsePage.aspx?id=1za0vDzJD0-phmo__OXrx2b99J_0mT9Gmm12a6wX-nhUNU9NN0lCUTY0RzFENE1WRU5USFJEOE4zWC4u">
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
            <a href="https://forms.office.com/Pages/DesignPageV2.aspx?subpage=design&FormId=1za0vDzJD0-phmo__OXrx2b99J_0mT9Gmm12a6wX-nhUM1FEV0xDUEU5SkZDM1JTMFdVSk01S0FYMC4u
Microsoft Forms
Easily create surveys, quizzes, and polls.
 " target="_blank" rel="noopener noreferrer">
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

    <script src="host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
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
                window.location.href = "index.php";
                toastr.warning("Sesión cerrada por inactividad.");
            }

            function resetTimer() {
                clearTimeout(temporizador);
                temporizador = setTimeout(cerrarSesion, 100000); // 1000 milisegundos = 1 segundo
            }
            console.log(temporizador);
        }

        registrarInactividad();
    </script>
</body>

</html>