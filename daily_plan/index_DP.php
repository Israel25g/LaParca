<?php
include("../apertura_sesion.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../main-global.css">
  <title>Daily Plan</title>
  <link rel="shortcut icon" href="../images/ICO.png">
  <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

  <!-- Libreria para alertas ----->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- estilos -->
  <link rel="stylesheet" href="main-global.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="background-image: url('../images/Motivo2.png')">

  <!-- Header -->
  <div class="header-error">
    <div class="logo-container">
      <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/Salida2.gif" alt="Logo_IPL_Group"></a>
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
    <div class="my-navbar">
      <ul class="my-nav" id="detallesOps">
        <!-- <li class="nav-li"><a href="Index.html">Inicio</a></li> -->
        <!-- <li class="nav-li"><a href="#">Capacitaciones</a></li> -->
        <li class="nav-li"><a href="../helpdesk.php">Mesa de Ayuda (Tickets)</a></li>
        <li class="nav-li"><a class="active" href="#"id="liveToastBtn">Daily Plan</a></li>
        <!-- <li class="nav-li"><a href="Dashboards/dashboards.php">Dashboards</a></li> -->
        <?php
        if ($_SESSION['rol'] === 'Admin' || $_SESSION['rol'] === 'EEMP') {
          echo '<li class="nav-li"><a href="access_control/index/index_users.php">Control de Usuarios</a></li>';
        }
        ?>
        <li class="nav-li"><a class="cierre" href="../login/CerrarSesion.php">Cerrar Sesión</a></li>
      </ul>
      <div class="sessid"><span class="id_sesion">Usuario: <?php echo ($_SESSION['usuario']) ?></span></div>
    </div>
  </div>
  <!-- Fin Navbar -->

  <div class="container-descripcion">
    <div class="bloque-descripcion">
      <h2>Tablas y graficos del Daily plan</h2>
      <h4>Seleccione uno de los siguientes apartados a gestionar.</h4>
    </div>
  </div>
  <div class="container">
    <div class="bloques ">
      <!-- import -->
      <div class="container-block">
        <div class="bloques-grid">

          <a href="./operaciones/tabla_multiple.php?fecha_estimacion_llegada=&filtro=import" rel="noopener noreferrer" style="color:black">
            <div class="bloque" id="bloque_cotización">
              <img src="../images/Import.jpg" alt="">
              <div class="my-text-overlay" style="height: 100%;width: 100%;">
                <h3>Import</h3>
                <p>Este apartado es exclusivamente para la edicion de la grafica de Import.</p>
              </div>
            </div>
          </a>
          <!-- Fin de import -->
          <!--export-->
          <a href="./operaciones/tabla_multiple.php?fecha_estimacion_llegada=&filtro=export" rel="noopener noreferrer" style="color:black">
            <div class="bloque" id="bloque_cotización">
              <img src="../images/export.jpg" alt="">
              <div class="my-text-overlay" style="height: 100%;width: 100%;">
                <h3>Export</h3>
                <p>Este apartado es exclusivamente para la edicion de la grafica de Export.</p>
              </div>
            </div>
          </a>
          <!-- Fin de export -->

          <!-- picking -->
          <a href="./operaciones/tabla_multiple.php?fecha_estimacion_llegada=&filtro=picking" rel="noopener noreferrer" style="color:black">
            <div class="bloque" id="bloque_cotización">
              <img class="img_helpdesk" id="img_cotizacion" src="../images/picking.jpg" alt="">
              <div class="my-text-overlay" style="height: 100%;width: 100%;">
                <h3>Picking</h3>
                <p>Este apartado es exclusivamente para la edicion de la grafica de Picking.</p>
              </div>
            </div>
          </a>
          <!-- Fin de picking-->

          <!-- graficos -->
          <a href="../daily_plan/grafico.php" rel="noopener noreferrer" style="color:black">
            <div class="bloque" id="bloque_cotización">
              <img class="img_helpdesk" id="img_cotizacion" src="../images/Graficas.jpg" alt="">
              <div class="my-text-overlay" style="height: 100%;width: 100%;">
                <h3>Graficos</h3>
                <p>Este apartado es exclusivamente para la visualizacion </br> de los graficos del daily plan.</p>
              </div>
            </div>
          </a>
          <!-- Fín de graficos -->
        </div>
      </div>


      <!-- Flexbox container for aligning the toasts -->

      <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header">
            <i class="bi bi-check-all rounded me-2"></i>
            <strong class="me-auto">Bootstrap</strong>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
            Hello, world! This is a toast message.
          </div>
        </div>
      </div>

      <script>
        $(document).ready(function() {
          $('.toast').toast('show');
        });
        const toastTrigger = document.getElementById('liveToastBtn')
        const toastLiveExample = document.getElementById('liveToast')

        if (toastTrigger) {
          const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
          toastTrigger.addEventListener('click', () => {
            toastBootstrap.show()
          })
        }
      </script>

      <script src="../host_virtual_TI/js/script.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
      <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.datatables.net/plug-ins/2.1.8/dataRender/datetime.js"></script>
      <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
      <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.2/kt-2.12.1/r-3.0.3/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>
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