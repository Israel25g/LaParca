<?php
include 'apertura_sesion.php';
include 'config.php';

// Jala el id del usuario
$id_usuario = $_SESSION['id'];

// Jala la versión ingresada
$getLastVersion = "SELECT version_number FROM u366386740_versions order by version_number desc limit 1";
$result = mysqli_query($conexion, $getLastVersion);

if ($result && mysqli_num_rows($result) > 0) {
    $lastVersion = mysqli_fetch_array($result)['version_number'];
} else {
    $lastVersion = 0.0;
}

// version del usuario
$getUserVersion = "SELECT last_seen_version_id FROM u366386740_versions_user WHERE user_id = '$id_usuario' ORDER BY last_seen_version_id DESC LIMIT 1";
$userResult = mysqli_query($conexion, $getUserVersion);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $userVersion = mysqli_fetch_array($userResult)['last_seen_version_id'];
} else {
    $userVersion = 0.0;
}

// mostrar modal
$showModal = $userVersion !== null && $lastVersion !== null && $userVersion < $lastVersion;

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk</title>
    <link rel="shortcut icon" href="images\ICO.png">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <!-- Libreria para alertas ----->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- estilos -->
    <link rel="stylesheet" href="main-global.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado completamente');
            var showModal = <?php echo $showModal ? 'true' : 'false'; ?>;
            if (showModal) {
                var myModal = new bootstrap.Modal(document.getElementById('version'), {
                    keyboard: false
                });
                myModal.show();
            } else {
                console.log('No se muestra el modal');
            }
        });
    </script>
</head>

<body style="background-image: url('./images/Motivo2.png')">
    <!-- Header -->
    <div class="header-error">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="images/Salida2.gif" alt="Logo_IPL_Group"></a>
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
        <div class="my-navbar">
            <ul class="my-nav" id="detallesOps">
                <!-- <li class="nav-li"><a href="Index.html">Inicio</a></li> -->
                <!-- <li class="nav-li"><a href="#">Capacitaciones</a></li> -->
                <li class="nav-li"><a class="active" href="#">Mesa de Ayuda (Tickets)</a></li>
                <li class="nav-li"><a href="./daily_plan/index_DP.php<?php session_id() ?>">Daily Plan</a></li>
                <li class="nav-li"><a href="./Dashboards/dashboards_extern.php">Dashboards</a></li>
                <?php
                if ($_SESSION['rol'] === 'Admin' || $_SESSION['rol'] === 'EEMP') {
                    echo '<li class="nav-li"><a href="access_control/index/index_users.php">Control de Usuarios</a></li>';
                }
                ?>
                <li class="nav-li"><a class="cierre" href="login/CerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
            <div class="sessid"><span class="id_sesion">Usuario: <?php echo ($_SESSION['usuario']) ?></span></div>
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
                    <div class="my-text-overlay">
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
                    <div class="my-text-overlay">
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
                    <div class="my-text-overlay">
                        <h3>Soporte de Mejoras</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar solicitudes de mejora/DEPIC's en cuanto al sistema DIPROP</p>
                    </div>
                </div>
            </a>
            <!-- Fin de EEMP -->
            <!-- Seguimiento de temas -->
            <?php
            $usuarios_admitidos = ['igondola', 'agaray', 'nrivas', 'wlemos', 'riromero', 'kdelgado', 'ssalazar', 'abethancourt', 'jgrant', 'rolivero', 'igondola01'];
            if (in_array($_SESSION['user'], $usuarios_admitidos)) {
                echo '<a href="host_virtual_seguimiento/index/index_seguimiento.php">
                                <div class="bloque" id="bloque_seguimiento">
                                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="https://www.marketingdirecto.com/wp-content/uploads/2021/09/atencion-al-cliente.png" alt="">
                                    <div class="my-text-overlay">
                                        <h3>Seguimiento de temas pendientes</h3>
                                        <p>Este formulario es para darle seguimiento a los temas pendientes referentes al flujo de comunicación entre los departamentos de Operaciones y SAC</p>
                                    </div>
                                </div>
                              </a>';
            }
            ?>

            <!-- <a href="host_virtual_seguimiento/index/index_seguimiento.php">
                <div class="bloque" id="bloque_seguimiento">
                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="https://www.marketingdirecto.com/wp-content/uploads/2021/09/atencion-al-cliente.png" alt="">
                    <div class="my-text-overlay">
                        <h3>Seguimiento de temas pendientes</h3>
                        <p>Este formulario es para darle seguimiento a los temas pendientes referentes al flujo de comunicación entre los departamentos de Operaciones y SAC</p>
                    </div>
                </div>
            </a> -->
            <!-- Fin de Seguimiento de temas -->

            <!-- Sobre tiempo -->
            <a target="https://forms.office.com/Pages/ShareFormPage.aspx?id=1za0vDzJD0-phmo__OXrx2b99J_0mT9Gmm12a6wX-nhUNU9NN0lCUTY0RzFENE1WRU5USFJEOE4zWC4u&sharetoken=NhdNt0HuLG7WFd5Sc9QH" href="https://forms.office.com/Pages/ResponsePage.aspx?id=1za0vDzJD0-phmo__OXrx2b99J_0mT9Gmm12a6wX-nhUNU9NN0lCUTY0RzFENE1WRU5USFJEOE4zWC4u">
                <div class="bloque" id="bloque_sobretiempo">

                    <img loading="lazy" class="img_helpdesk" id="img_overtime" src="images\Sobretiempo.jpg" alt="">
                    <div class="my-text-overlay">
                        <h3>Solicitud de Sobretiempo</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar alguna solicitud de sobretiempo.</p>
                    </div>
                </div>
            </a>
            <!-- Fín de sobretiempo -->

            <!-- Compras -->
            <a href="https://forms.office.com/r/JSRrVt475n" target="_blank" rel="noopener noreferrer">
                <div class="bloque" id="bloque_cotización">
                    <img loading="lazy" class="img_helpdesk" id="img_cotizacion" src="https://consultorfinancontable.com/wp-content/uploads/2024/03/mujer-asiatica-trabajando-traves-papeleo_53876-138148.jpg" alt="">
                    <div class="my-text-overlay">
                        <h3>Solicitud de orden de compra</h3>
                        <p>Este helpdesk es exclusivamente para la generación de tickets con el objetivo de realizar alguna solicitud cotizaciones.</p>
                    </div>
                </div>
            </a>
            <!-- Fin de Compras -->
        </div>
    </div>



    <?php
    $url = $url = "https://api.github.com/repos/Israel25g/LaParca/tags";

    // Inicializamos cURL
    $ch = curl_init($url);

    // token
    $token = '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';

    // Configuramos cURL para que nos devuelva el resultado como cadena
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'IPL_Group');

    // Ejecutamos la petición
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificamos el JSON
    $tags = json_decode($response, true);


    // verificar los tags recibidos

    if (!empty($tags)) {
        $lastTag = $tags[0]['name'];
    } else {
        echo "No se encontraron tags";
    }
    ?>

    <!-- Notas de la versión -->
    <div class="version-notes" id="version-sistema" data-bs-toggle="modal" data-bs-target="#version">
        <p class="m-0">Versión <?php echo $lastTag; ?></p>
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
                            <img loading="lazy" src="./images/Actualizaciones/version1.0/detalles-del-ticket.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center">Nueva previsualización de tickets</p>
                        </div>
                        <li>Para los administradores, ahora pueden responder directamente seleccionando el ticket a responder, a estos se les muestra el boton de responder.
                            <br>Nota: En el apartado de "respuesta del ticket" es donde puede escribir la respuesta del ticket, la descripción del caso no es un campo editable, de igual forma con el estado del ticket, este es una lista desplegable
                        </li>
                        <div class="col-12">
                            <img loading="lazy" src="./images/Actualizaciones/version1.0/ft_01.gif" alt="" style="width: 1000px;">
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
                            <img loading="lazy" src="./images/Actualizaciones/version1.0/ft_1.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center fst-italic">Nuevo método interno de interación con demas apartados</p>
                        </div>

                        <li>Nuevo Menú de Filtros</li>
                        <p>Se ha añadido un menú de filtros rediseñado, que incorpora dos filtros adicionales y atajos hacia otras operaciones.
                            Los botones de ingreso de datos y visualización de gráficos mantienen sus funciones originales, mejorando el flujo de trabajo.</p>

                        <br>
                        <div class="col-12">
                            <img loading="lazy" src="./images/Actualizaciones/version1.0/ft_2.gif" alt="" style="width: 1000px;">
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
                            <img loading="lazy" src="./images/Actualizaciones/version1.0/ft_5.gif" alt="" style="width: 1000px;">
                            <br>
                            <p class="text-center fst-italic">Control de columnas para visualización de interfaces <br> nota: Estas columnas influyen a la hora de copiar e imprimir los registros seleccionados, es decir, de no aparecer una columna mediante este modo de filtrado, esta no será copiada/preparada para imprimir</p>
                        </div>

                        <li>Interfaz de Visualización de Datos Extra Intuitiva</li>
                        <p>El nuevo menú de visualización de datos se despliega al hacer clic en un registro de la tabla y muestra información adicional que generalmente no es visible, mejorando el acceso a datos clave sin saturar la interfaz.</p>

                        <br>
                        <div class="col-12">
                            <img loading="lazy" src="./images/Actualizaciones/version1.0/ft_6.gif" alt="" style="width: 1000px;">
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

                    <form action="version.php" method="post">
                        <input type="hidden" name="version" value="<?php echo $lastTag ?>">
                        <button
                            type="submit"
                            class="btn btn-success"
                            data-bs-dismiss="modal">
                            ¡He visto la actualización!
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <?php
    #if (isset($_GET['error'])) {
    ?>
        <script>
            Command: toastr["error"]("No tienes permiso para acceder a este apartado", <?php #$_GET['error'] ?>)
        </script><?php
                #}
                    ?> -->
    <!-- Optional: Place to the bottom of scripts -->
    <!-- <script>
        const myModal = new bootstrap.Modal(
            document.getElementById("version"),
            options,
        );
    </script> -->

    <script src="./host_virtual_TI/js/script.js"></script>
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
</body>

</html>