<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos.css">
    <title>Helpdesk</title>
    <link rel="shortcut icon" href="images\ICO.png">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <!-- Libreria para alertas ----->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

<body>
    <style>
        body {
            background-image: url('./images/Motivo2.png');
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
        <h1>Daily Plan</h1>
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
                <li class="nav-li"><a class="active" href="#">Daily Plan</a></li>
                <li class="nav-li"><a href="../Dashboards/dashboards.php">Dashboards</a></li>
                <li class="nav-li"><a class="cierre" href="./login/CerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <!-- Fin Navbar -->
  <div class="container-descripcion">
        <div class="bloque-descripcion">
            <h2>Acceso a las tablas y graficos del Daily plan</h2>
            <p>Seleccione uno de los siguientes apartados a gestionar.</p>
        </div>
    </div>
    <div class="container">
        <div class="bloques ">
            <!--export-->
            <a href="../daily_plan/tabla_ex.php"  rel="noopener noreferrer" style="color:black">
              <div class="bloque" id="bloque_cotización">
              <img  src="../images/export.jpg" alt="">
                <div  class="text-overlay" style="height: 100%;width: 100%;">
                <h3>Export</h3>
                <p>Este apartado es exclusivamente para la edicion  de la grafica de Export.</p>
                </div>
              </div>
            </a>
            <!-- Fin de export -->
            
             <!-- import -->
            
              <a href="../daily_plan/tabla_im.php"  rel="noopener noreferrer" style="color:black">
                <div class="bloque" id="bloque_cotización">
                  <img   src="../images/Import.jpg" alt="">
                  <div class="text-overlay" style="height: 100%;width: 100%;">
                    <h3>Import</h3>
                    <p>Este apartado es exclusivamente para la edicion  de la grafica de Import.</p>
                  </div>
                </div>
              </a>
            <!-- Fin de import -->
             <!-- picking -->
            <a href="../daily_plan/tabla_pk.php"  rel="noopener noreferrer" style="color:black">
              <div class="bloque" id="bloque_cotización">
                <img class="img_helpdesk" id="img_cotizacion" src="../images/picking.jpg" alt="">
                <div class="text-overlay"  style="height: 100%;width: 100%;">
                  <h3>Picking</h3>
                  <p>Este apartado es exclusivamente para la edicion  de la grafica de Picking.</p>
                </div>
              </div>
            </a>
            <!-- Fin de picking-->

             <!-- graficos -->
            <a href="../daily_plan/grafico.php"  rel="noopener noreferrer" style="color:black">
              <div class="bloque" id="bloque_cotización">
                <img class="img_helpdesk" id="img_cotizacion" src="../images/Graficas.jpg" alt="">
                <div class="text-overlay"  style="height: 100%;width: 100%;">
                  <h3>Graficos</h3>
                  <p>Este apartado es exclusivamente para la visualizacion de  los graficos del daily plan.</p>
                </div>
              </div>
            </a>
            <!-- Fín de graficos -->
        </div>
    </div>

      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="../host_virtual_TI/js/script.js"></script>
      <script>
        function on() {
document.getElementById("overlay").style.display = "block";
}

function off() {
document.getElementById("overlay").style.display = "none";
}
      </script>

    </div>
  </body>
</html>
