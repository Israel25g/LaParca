<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Sistema de Tickets</title>
  <link rel="shortcut icon" href="../../images/ICO.png">
  <!-- estilo bootstrap css -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../../estilos.css">
  <!--estilos ccs-->
  <script src="https://kit.fontawesome.com/4316b07e12.js" crossorigin="anonymous"></script>
  <!--Datatable-->
  <link href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!--estilos ccs-->

</head>
<!-- Header -->
<div class="header">
  <h1 style=" color: white; margin-left:30%; display: flex;font-weight: 800;font-size: 60px; ;font-family: 'Montserrat', 'Roboto', sans-serif;">Sistema de Tickets</h1>
  <div class="logo-container">
    <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
  </div>
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
                <li class="nav-li"><a class="active" href="../../helpdesk.php">Mesa de Ayuda (Tickets)</a></li>
                <li class="nav-li"><a href="./daily_plan/index_DP.php<?php session_id()?>">Daily Plan</a></li>
                <!-- <li class="nav-li"><a href="Dashboards/dashboards.php">Dashboards</a></li> -->
                <li class="nav-li"><a class="cierre" href="../login/CerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>
    <!-- Fin Navbar -->
<!--script-->
<script src="../js/script.js"></script>


