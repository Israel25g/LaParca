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