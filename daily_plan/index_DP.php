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
          echo '<li class="nav-li"><a href="../access_control/index/index_users.php">Control de Usuarios</a></li>';
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

      <?php
        $url = $url = "https://api.github.com/repos/Israel25g/LaParca/tags";

        // Inicializamos cURL
        $ch = curl_init($url);

        // Configuramos cURL para que nos devuelva el resultado como cadena
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'IPL_Group');

        // Ejecutamos la petición
        $response = curl_exec($ch);
        curl_close($ch);

        // Decodificamos el JSON
        $tags = json_decode($response,true);


        // verificar los tags recibidos

        if(!empty($tags)){
            $lastTag = $tags[0]['name'];
        } else {
            echo "No se encontraron tags";
        }
    ?>

      <!-- Notas de la versión -->
    <div class="version-notes" id="version-sistema" data-bs-toggle="modal" data-bs-target="#version">
        <p class="m-0">Versión <?php echo $lastTag;?></p>
    </div>


    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div
        class="modal fade"
        id="version"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalTitleId"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        aria-hidden="true">
        <div
            class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitleId">
                        Notas de la versión <?php echo $lastTag ?>
                    </h3>
                </div>
                <div class="modal-body">
                    <h3>Feha de implementación 13 de noviembre de 2024</h3>
                    <p><strong>Es importante que revises cuidadosamente las notas de la versión antes de utilizar las nuevas funciones, ya que esto te ayudará a comprender los cambios y evitará confusiones. </strong></p>
                    <p>Estas son algunas de las actualizaciones/mejoras que se han realizado con el objetivo de optimizar la plataforma y mejorar la calidad de uso de esta:</p>

                    <h3>Sistema de Tickets</h3>

                    <p>En cuanto al sistema de tickets, se han escuchado las solicitudes por parte de los administradores y parte del personal que requerían mejor visibilidad y opciones de trazabilizad para poder darle mejor seguimiento a los tickets</p>

                    <ul class="col-12 clean">
                        <li>Se añaderon filtros más precisos para busquedas de tickets, esto permitiendo ver el estado de estos y sus respectivas actualizaciones</li>
                        <li>Se redujeron la cantidad de caracteres que se muestran desde la tabla, esto con el objetivo de mostrar una tabla más limpia. En consecuencia de esto, ahora al presionar sobre el ticket a revisar, se pueden ver todos los detalles de este</li>
                        <li>Se aumentó el límite de caracteres, pasó de 255 caracteres hasta los necesarios</li>
                        <br>
                        <div class="col-12">
                            <img loading="lazy" src="../images/Actualizaciones/version1.0/detalles-del-ticket.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center">Nueva previsualización de tickets</p>
                        </div>
                        <li>Para los administradores, ahora pueden responder directamente seleccionando el ticket a responder, a estos se les muestra el boton de responder.
                            <br>Nota: En el apartado de "respuesta del ticket" es donde puede escribir la respuesta del ticket, la descripción del caso no es un campo editable, de igual forma con el estado del ticket, este es una lista desplegable
                        </li>
                        <div class="col-12">
                            <img loading="lazy" src="../images/Actualizaciones/version1.0/ft_01.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center">Nueva previsualización de tickets</p>
                        </div>
                        <li>Se añadieron elementos estéticos</li>
                        <hr>
                    </ul>

                    <h3>Daily Plan</h3>

                    <p>Para el Daily Plan, tenemos mejores significativas para el uso de la interfaz, desde cambios estéticos para identificar más rápido en qué operación se encuentra, hasta mejoras de funcionalidad. Esta actualización se centra en la mejora de la interfaz de usuario y optimización del sistema de tablas de datos en Daily Plan, con un enfoque en la usabilidad, accesibilidad y rendimiento. A continuación, se presentan los cambios y mejoras clave:</p>
                    <ol>
                        <li>Indicadores Visuales de Tipo de operacion</li>
                        <p>Ahora, se incluyen indicadores visuales para identificar rápidamente el tipo de operación en el que se está trabajando, mejorando la visibilidad y comprensión de cada registro.</p>

                        <br>
                        <div class="col-12">
                            <img loading="lazy" src="../images/Actualizaciones/version1.0/ft_1.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center fst-italic">Nuevo método interno de interación con demas apartados</p>
                        </div>

                        <li>Nuevo Menú de Filtros</li>
                        <p>Se ha añadido un menú de filtros rediseñado, que incorpora dos filtros adicionales y atajos hacia otras operaciones.
                            Los botones de ingreso de datos y visualización de gráficos mantienen sus funciones originales, mejorando el flujo de trabajo.</p>

                        <br>
                        <div class="col-12">
                            <img loading="lazy" src="../images/Actualizaciones/version1.0/ft_2.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center fst-italic"> Filtros adicionales para búsqueda de operaciones por fecha de programación y casilla de alternación entre "Pedidos completados" y "No completados"</p>
                        </div>

                        <li>Incorporación del Menú de Visualización de Datos Extra</li>
                        <p>Nueva columna de “Fecha Programada”: Esta columna es autorrellenable y se diseñó para optimizar la planificación diaria.
                            Controla la visualización de datos en las pantallas de importación y exportación.
                            Se inicializa con la fecha estimada de llegada al crear un nuevo registro y puede ser editada posteriormente.
                            Filtros adicionales: Se agregaron dos filtros nuevos: uno para "Fecha Programada" y otro para "Operaciones Completas".</p>
                        <li>Filtrado de Tablas por Defecto</li>
                        <p>Las tablas están ahora configuradas para mostrar solo los pedidos del día y aquellos que aún no se han completado o que están en proceso.
                            Función "Mostrar Operaciones Completadas": Permite ver todas las operaciones terminadas, facilitando el análisis y la generación de reportes.</p>

                        <li>Nuevo Botón "Mostrar/Ocultar Columnas"</li>
                        <p>Esta función despeja la vista de la tabla para mejorar la experiencia visual y permite seleccionar columnas específicas para exportarlas o imprimirlas.
                            Funciona con los botones de exportación a PDF, Excel, y para imprimir, permitiendo mayor control en la selección de datos visibles.</p>

                        <br>
                        <div class="col-12">
                            <img loading="lazy" src="../images/Actualizaciones/version1.0/ft_5.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center fst-italic">Control de columnas para visualización de interfaces <br> nota: Estas columnas influyen a la hora de copiar e imprimir los registros seleccionados, es decir, de no aparecer una columna mediante este modo de filtrado, esta no será copiada/preparada para imprimir</p>
                        </div>

                        <li>Interfaz de Visualización de Datos Extra Intuitiva</li>
                        <p>El nuevo menú de visualización de datos se despliega al hacer clic en un registro de la tabla y muestra información adicional que generalmente no es visible, mejorando el acceso a datos clave sin saturar la interfaz.</p>

                        <br>
                        <div class="col-12">
                            <img loading="lazy" src="../images/Actualizaciones/version1.0/ft_6.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center fst-italic">Esta funcion permite tanto a operaciones como al resto del equipo ver el estado de una operación específicamente.</p>
                        </div>

                        <li>Optimización de Procesos Internos</li>
                        <p>Se ha trabajado en la optimización del sistema, aligerando varios procesos que impactan en el rendimiento general y la velocidad de respuesta del Daily Plan.</p>
                    </ol>
                    <br>

                </div>
                <div class="modal-footer">
                    <p class="text-center">Para ver la imagen con más detalle, haga click derecho sobre ella y luego "Abre la imagen en nueva pestaña"</p>
                    <br>
                    
                        <input type="hidden" name="version" value= "<?php echo $lastTag ?>">
                        <button
                            type="button"
                            class="btn btn-success"
                            data-bs-dismiss="modal">
                            ¡He visto la actualización!
                        </button>
                </div>
            </div>
        </div>
    </div>


      <!-- Flexbox container for aligning the toasts -->

  <!-- <div class="toast-container position-fixed bottom-0 end-0 p-3">
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
  </script> -->

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