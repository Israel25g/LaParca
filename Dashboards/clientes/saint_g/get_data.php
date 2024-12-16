
<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$servername = "localhost";
$username = "u366386740_IPLGroup";
$password = "1plGr0up01*";
$dbname = "u366386740_dataWarehouse";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// Capturar rango de fechas y Cliente
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final'] : null;
$Cliente = isset($_GET['Cliente']) ? $conn->real_escape_string($_GET['Cliente']) : null;

// Validar formato de fechas (opcional)
if ($fecha_inicio && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_inicio)) {
    die(json_encode(["error" => "Formato de fecha_inicio inválido"]));
}
if ($fecha_final && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_final)) {
    die(json_encode(["error" => "Formato de fecha_final inválido"]));
}

// Generar condición de rango de fechas para import
$dateCondition_im = "";
if ($fecha_inicio && $fecha_final) {
    $dateCondition_im = "ETA BETWEEN '$fecha_inicio' AND '$fecha_final'";
} elseif ($fecha_inicio) {
    $dateCondition_im = "ETA >= '$fecha_inicio'";
} elseif ($fecha_final) {
    $dateCondition_im = "ETA <= '$fecha_final'";
}

// Generar condición de rango de fechas para export
$dateCondition_ex = "";
if ($fecha_inicio && $fecha_final) {
    $dateCondition_ex = "FRD BETWEEN '$fecha_inicio' AND '$fecha_final'";
} elseif ($fecha_inicio) {
    $dateCondition_ex = "FRD >= '$fecha_inicio'";
} elseif ($fecha_final) {
    $dateCondition_ex = "FRD <= '$fecha_final'";
}
// Generar condición de rango de fechas para picking
$dateCondition_pk = "";
if ($fecha_inicio && $fecha_final) {
    $dateCondition_pk = "Confirmado BETWEEN '$fecha_inicio' AND '$fecha_final'";
} elseif ($fecha_inicio) {
    $dateCondition_pk = "Confirmado >= '$fecha_inicio'";
} elseif ($fecha_final) {
    $dateCondition_pk = "Confirmado <= '$fecha_final'";
}

// Generar condición para Cliente
$clientCondition = "";
if ($Cliente) {
    $clientCondition = "CIA = '$Cliente'";
}

// Combinar condiciones (si existen ambas) para import
$whereClause_im = "";
if ($dateCondition_im && $clientCondition) {
    $whereClause_im = "WHERE $clientCondition AND $dateCondition_im";
} elseif ($dateCondition_im) {
    $whereClause_im = "WHERE $dateCondition_im";
} elseif ($clientCondition) {
    $whereClause_im = "WHERE $clientCondition";
}
// Combinar condiciones (si existen ambas) para export
$whereClause_pk = "";
if ($dateCondition_pk && $clientCondition) {
    $whereClause_pk = "WHERE $clientCondition AND $dateCondition_pk";
} elseif ($dateCondition_pk) {
    $whereClause_pk = "WHERE $dateCondition_pk";
} elseif ($clientCondition) {
    $whereClause_pk = "WHERE $clientCondition";
}
// Combinar condiciones (si existen ambas) para picking
$whereClause_ex = "";
if ($dateCondition_ex && $clientCondition) {
    $whereClause_ex = "WHERE $clientCondition AND $dateCondition_ex ";
} elseif ($dateCondition_ex) {
    $whereClause_ex = "WHERE $dateCondition_ex";
} elseif ($clientCondition) {
    $whereClause_ex = "WHERE $clientCondition";
}



// Consultas para múltiples gráficos

// Inician los graficos de imports
// Gráfico 1: Total de und_Recibidas por Cliente y mes

$query1 = "
SELECT 
    DATE_FORMAT(Eta, '%Y-%m-%d') AS mes,
    SUM(paletas) AS total_paletas_Recibidas,
    SUM(cajas) AS total_cajas,
    SUM(KG) AS total_KG,
    SUM(CBM) total_CBM
FROM 
    imports
$whereClause_im
GROUP BY
mes
";

$result1 = $conn->query($query1);
$total_paletas_Recibidas= [];
$total_cajas = [];
$total_KG = [];
$total_CBM = [];

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $total_paletas_Recibidas[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['total_paletas_Recibidas'],
            ],
        ];
        $total_cajas[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['total_cajas'],
            ],
        ];
        $total_KG[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['total_KG'],
            ],
        ];
        $total_CBM[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['total_CBM'],
            ],
        ];
    }
}


// Gráfico 2: Total de paletas por pais y mes

$query2 = "
SELECT 
    DATE_FORMAT(Eta, '%Y-%m-%d') AS mes,
    SUM(CASE WHEN Tamano = 'Grande' THEN und_recibidas ELSE 0 END) AS total_grande,
    SUM(CASE WHEN Tamano = 'Mediano' THEN und_recibidas ELSE 0 END) AS total_mediano,
    SUM(CASE WHEN Tamano = 'Pequeño' THEN und_recibidas ELSE 0 END) AS total_pequeño
FROM 
    imports
$whereClause_im
GROUP BY
    mes
";

$result2 = $conn->query($query2);
$total_grande= [];
$total_mediano=[];
$total_pequeño= [];

if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $total_grande[] = [
            'name' => $row['mes'],
            'value' => [
                (int)$row['total_grande'],
            ],

        ];
        $total_mediano[] = [
            'name' => $row['mes'],
            'value' => [
                (int)$row['total_mediano'],
            ],
        ];
        $total_pequeño[] = [
            'name' => $row['mes'],
            'value' => [
                (int)$row['total_pequeño'],
            ],
        ];
    }
}

// Gráfico 3: Total de cajas por Cliente y mes
// Consulta para obtener los datos
$query3 = "
SELECT 
    DATE_FORMAT(Eta, '%Y-%m-%d') AS mes,
    Vehiculo, 
    COUNT(*) AS total
FROM 
    imports
$whereClause_im
GROUP BY 
    mes, Vehiculo
ORDER BY 
    mes;
";

$result3 = $conn->query($query3);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$seriesData = []; // Series de datos agrupados por vehículo
$vehicles = []; // Lista de vehículos únicos

// Paleta de colores para las series
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];


// Procesar resultados
while ($row = $result3->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $vehiculo = $row['Vehiculo'] ?? 'NO DATA';
    $total = (int)$row['total'];

    // Agregar la fecha al eje X si no está ya
    if (!in_array($fecha, $categories)) {
        $categories[] = $fecha;
    }

    // Agregar el vehículo a la lista de vehículos únicos
    if (!array_key_exists($vehiculo, $seriesData)) {
        $vehicles[] = $vehiculo;
        $seriesData[$vehiculo] = [];
    }

    // Asignar valores al vehículo para esa fecha
    $seriesData[$vehiculo][$fecha] = $total;
}

// Preparar los datos para el gráfico
$series = [];
foreach ($vehicles as $index => $vehiculo) {
    $data = [];
    foreach ($categories as $fecha) {
        // Usar 0 si no hay datos para esa fecha
        $data[] = $seriesData[$vehiculo][$fecha] ?? 0;
    }

    // Asignar un color basado en el índice
    $color = $colorPalette[$index % count($colorPalette)];

    $series[] = [
        'name' => $vehiculo,
        'type' => 'bar',
        'stack' => 'total', // Configuración para apilar las barras
        'data' => $data,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => [
            'color' => $color, // Color personalizado para la serie
        ],
    ];
}

// Preparar el JSON final
$chart3 = [
    'categories' => $categories, // Eje X
    'series' => $series,

];


// Gráfico 4: Ejemplo adicional de consulta
$query4 = "
    SELECT 
        DATE_FORMAT(ETA, '%Y-%m-%d') AS mes,
        Pais,
        Tamano,
        COUNT(Tamano) AS total_carga
    FROM 
        imports
    $whereClause_im
GROUP BY
Tamano
";
$result4 = $conn->query($query4);
$chart4 = [];
if ($result4->num_rows > 0) {
    while ($row = $result4->fetch_assoc()) {
        $chart4[] = [
            'name' => $row['Tamano'] ? $row['Tamano'] : 'NO DATA',
            'value' => [
                (int)$row['mes'],
                (int)$row['total_carga'],
            ],
        ];
    }
}
//Terminan los graficos de imports

// inician los graficos de exports
// Gráfico 5: Paletas empacadas por país

// Consulta para obtener los datos
$query5 = "
SELECT 
    DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
    Pais, 
    SUM(CASE WHEN Empacado IS NOT NULL THEN Paletas ELSE 0 END) AS suma_paletas_ex
FROM 
    exports
$whereClause_ex
GROUP BY 
    mes, Pais
ORDER BY 
    mes;
";

$result5 = $conn->query($query5);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$seriesData = []; // Series de datos agrupados por país
$Paises = []; // Lista de países únicos

// Paleta de colores para las series
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];

// Procesar resultados
while ($row = $result5->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $Pais = $row['Pais'] ?? 'NO DATA';
    $suma_paletas_ex = (int)$row['suma_paletas_ex'];

    // Agregar la fecha al eje X si no está ya
    if (!in_array($fecha, $categories)) {
        $categories[] = $fecha;
    }

    // Agregar el país a la lista de países únicos
    if (!array_key_exists($Pais, $seriesData)) {
        $Paises[] = $Pais;
        $seriesData[$Pais] = [];
    }

    // Asignar valores al país para esa fecha
    $seriesData[$Pais][$fecha] = $suma_paletas_ex;
}

// Preparar los datos para el gráfico
$series = [];
foreach ($Paises as $index => $Pais) {
    $data = [];
    foreach ($categories as $fecha) {
        // Usar 0 si no hay datos para esa fecha
        $data[] = $seriesData[$Pais][$fecha] ?? 0;
    }

    // Asignar un color basado en el índice
    $color = $colorPalette[$index % count($colorPalette)];

    $series[] = [
        'name' => $Pais,
        'type' => 'bar',
        'stack' => 'total', // Configuración para apilar las barras
        'data' => $data,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => [
            'color' => $color, // Color personalizado para la serie
        ],
    ];
}

// Preparar el JSON final
$chart5 = [
    'categories' => $categories, // Eje X
    'series' => $series,

];




// Gráfico 6: Total de paletas por pais y mes
// Consulta para obtener los datos (modificada para incluir las dos sumas)
$query6 = "
SELECT 
DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
Pais, 
    SUM(CASE WHEN Despachado IS NOT NULL THEN Paletas ELSE 0 END) AS suma_paletas_ex,
    SUM(CASE WHEN Despachado IS NULL THEN Paletas ELSE 0 END) AS suma_paletas_pendientes_ex
FROM 
    exports
    $whereClause_ex
    GROUP BY 
    mes, Pais
    ORDER BY 
    mes ;
    ";

    $result6 = $conn->query($query6);
    
    // Inicializar arrays para almacenar los datos estructurados
    $categories = []; // Fechas para el eje X
    $seriesData = []; // Series de datos agrupados por país y tipo
$Paises = []; // Lista de países únicos

// Paleta de colores para las series
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];

// Procesar resultados
while ($row = $result6->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $Pais = $row['Pais'] ?? 'NO DATA';
    $suma_paletas_despachadas_ex = (int)$row['suma_paletas_ex'];
    $suma_paletas_pendientes_ex = (int)$row['suma_paletas_pendientes_ex'];

    // Agregar la fecha al eje X si no está ya
    if (!in_array($fecha, $categories)) {
        $categories[] = $fecha;
    }
    
    // Agregar el país a la lista de países únicos
    if (!array_key_exists($Pais, $seriesData)) {
        $Paises[] = $Pais;
        $seriesData[$Pais] = [
            'despachadas' => [],
            'pendientes' => [],
        ];
    }

    // Asignar valores para las dos series de paletas (despachadas y pendientes) para ese país y fecha
    $seriesData[$Pais]['despachadas'][$fecha] = $suma_paletas_despachadas_ex;
    $seriesData[$Pais]['pendientes'][$fecha] = $suma_paletas_pendientes_ex;
}

// Preparar los datos para el gráfico
$series = [];
foreach ($Paises as $index => $Pais) {
    // Serie de paletas despachadas
    $data_despachadas = [];
    foreach ($categories as $fecha) {
        // Usar 0 si no hay datos para esa fecha
        $data_despachadas[] = $seriesData[$Pais]['despachadas'][$fecha] ?? 0;
    }

    // Serie de paletas pendientes
    $data_pendientes = [];
    foreach ($categories as $fecha) {
        // Usar 0 si no hay datos para esa fecha
        $data_pendientes[] = $seriesData[$Pais]['pendientes'][$fecha] ?? 0;
    }

    // Asignar colores para las dos series (despachadas y pendientes)
    $color_despachadas = $colorPalette[$index % count($colorPalette)];
    $color_pendientes = $colorPalette[($index +3) % count($colorPalette)];

    // Agregar las dos series (despachadas y pendientes) para cada país
    $series[] = [
        'name' => $Pais . ' - Paletas Despachadas',
        'type' => 'bar',
        'data' => $data_despachadas,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => [
            'color' => $color_despachadas, // Color personalizado para la serie de despachadas
        ],
    ];

    $series[] = [
        'name' => $Pais . ' - Paletas Pendientes',
        'type' => 'line',
        'data' => $data_pendientes,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => [
            'color' => $color_pendientes, // Color personalizado para la serie de pendientes
        ],
    ];
}

// Preparar el JSON final
$chart6 = [
    'categories' => $categories, // Eje X
    'series' => $series,

];


// Gráfico 7: Total de cajas por sucursal y mes
$query7 = "
SELECT 
DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
Pais, 
COUNT(CASE WHEN Empacado IS NOT NULL THEN OID ELSE 0 END) AS suma_Pedidos_ex,
SUM(CASE WHEN Empacado IS NOT NULL THEN Cajas ELSE 0 END) AS suma_Pedidos_ex
FROM 
    exports
$whereClause_ex
GROUP BY 
mes, Pais
ORDER BY 
mes;
";

$result7 = $conn->query($query7);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$seriesData = []; // Series de datos agrupados por país y tipo
$Paises = []; // Lista de países únicos

// Paleta de colores para las series
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];
$colorPalette2 = ['#a06b1b','#4d8086','#9b2a28','#24363f','#aa6851','#738f8b','#5d7f6a','#56585b','#424c5a','#9ba3a8'];


// Procesar resultados
while ($row = $result7->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $Pais = $row['Pais'] ?? 'NO DATA';
    $suma_paletas_despachadas_ex = (int)$row['suma_Pedidos_ex'];
    $suma_paletas_pendientes_ex = (int)$row['suma_Pedidos_ex'];
    
    // Agregar la fecha al eje X si no está ya
    if (!in_array($fecha, $categories)) {
        $categories[] = $fecha;
    }

    // Agregar el país a la lista de países únicos
    if (!array_key_exists($Pais, $seriesData)) {
        $Paises[] = $Pais;
        $seriesData[$Pais] = [
            'despachadas' => [],
            'pendientes' => [],
        ];
    }

    // Asignar valores para las dos series de paletas (despachadas y pendientes) para ese país y fecha
    $seriesData[$Pais]['despachadas'][$fecha] = $suma_paletas_despachadas_ex;
    $seriesData[$Pais]['pendientes'][$fecha] = $suma_paletas_pendientes_ex;
}

// Preparar los datos para el gráfico
$series = [];
foreach ($Paises as $index => $Pais) {
    // Serie de paletas despachadas
    $data_despachadas = [];
    foreach ($categories as $fecha) {
        // Usar 0 si no hay datos para esa fecha
        $data_despachadas[] = $seriesData[$Pais]['despachadas'][$fecha] ?? 0;
    }

    // Serie de paletas pendientes
    $data_pendientes = [];
    foreach ($categories as $fecha) {
        // Usar 0 si no hay datos para esa fecha
        $data_pendientes[] = $seriesData[$Pais]['pendientes'][$fecha] ?? 0;
    }
    
    // Asignar colores para las dos series (despachadas y pendientes)
    $color_despachadas = $colorPalette[$index % count($colorPalette)];
    $color_pendientes = $colorPalette2[$index % count($colorPalette2)];
    
    // Agregar las dos series (despachadas y pendientes) para cada país
    $series[] = [
        'name' => $Pais . ' - Pedidos empacados',
        'type' => 'bar',
        'emphasis'=>[ 'focus'=>'series'],
        'data' => $data_despachadas,
        'itemStyle' => [
            'color' => $color_despachadas, // Color personalizado para la serie de despachadas
        ],
    ];
    
    $series[] = [
        'name' => $Pais . ' - Cajas empacadas',
        'type' => 'bar',
        'emphasis'=>[ 'focus'=>'series'],
        'data' => $data_pendientes,
        'itemStyle' => [
            'color' => $color_pendientes, // Color personalizado para la serie de pendientes
        ],
    ];
}
        
        // Preparar el JSON final
$chart7 =[
    'categories' => $categories, // Eje X
    'series' => $series,

];


// Gráfico 8: Ejemplo adicional de consulta
$query8 = "
SELECT
    OID,
    DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
    SUM(CASE WHEN Empacado IS NOT NULL THEN Cajas ELSE 0 END) AS total_cajas_empacadas,
    SUM(CASE WHEN Empacado IS NOT NULL THEN Paletas ELSE 0 END) AS total_paletas_empacadas
    FROM 
    exports
    $whereClause_ex
    GROUP BY 
    mes, OID
    ";

// Realizando las conexiones
$result8 = $conn->query($query8);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$seriesData = []; // Series de datos agrupados por OID
$OIDs = []; // Lista de OIDs únicos

// Paleta de colores para las series
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];
$colorPalette2 = ['#a06b1b','#4d8086','#9b2a28','#24363f','#aa6851','#738f8b','#5d7f6a','#56585b','#424c5a','#9ba3a8'];

// Procesar resultados
while ($row = $result8->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $OID = $row['OID'] ?? 'NO DATA';
    $total_cajas_empacadas = (int)$row['total_cajas_empacadas'];
    $total_paletas_empacadas = (int)$row['total_paletas_empacadas'];
    
    // Agregar la fecha al eje X si no está ya
    if (!in_array($fecha, $categories)) {
        $categories[] = $fecha;
    }

    // Agregar el OID a la lista de OIDs únicos
    if (!array_key_exists($OID, $seriesData)) {
        $OIDs[] = $OID;
        $seriesData[$OID] = [
            'cajas' => [],
            'paletas' => [],
        ];
    }

    // Asignar valores para las dos series de cajas y paletas para ese OID y fecha
    $seriesData[$OID]['cajas'][$fecha] = $total_cajas_empacadas;
    $seriesData[$OID]['paletas'][$fecha] = $total_paletas_empacadas;
}

// Preparar los datos para el gráfico
$series = [];
foreach ($OIDs as $index => $OID) {
    // Serie de cajas empacadas
    $data_cajas = [];
    foreach ($categories as $fecha) {
        // Usar 0 si no hay datos para esa fecha
        $data_cajas[] = $seriesData[$OID]['cajas'][$fecha] ?? 0;
    }

    // Serie de paletas empacadas
    $data_paletas = [];
    foreach ($categories as $fecha) {
        // Usar 0 si no hay datos para esa fecha
        $data_paletas[] = $seriesData[$OID]['paletas'][$fecha] ?? 0;
    }

    // Asignar colores para las dos series
    $color_cajas = $colorPalette[$index % count($colorPalette)];
    $color_paletas = $colorPalette2[$index % count($colorPalette2)];
    
    // Agregar las dos series (cajas y paletas) para cada OID
    $series[] = [
        'name' => $OID . ' - Cajas empacadas',
        'type' => 'bar',
        'data' => $data_cajas,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => [
            'color' => $color_cajas, // Color personalizado para la serie de cajas
        ],
    ];
    
    $series[] = [
        'name' => $OID . ' - Paletas empacadas',
        'type' => 'bar',
        'data' => $data_paletas,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => [
            'color' => $color_paletas, // Color personalizado para la serie de paletas
        ],
    ];
}


// Preparar el JSON final
$chart8 = [
    'categories' => $categories, // Eje X
    'series' => $series,

];



// Gráfico 9: Total de und_Recibidas por Sucursal y mes
$query9 = "
SELECT
Sucursal,
DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
SUM(CASE WHEN Empacado IS NOT NULL THEN Avance_porcentaje ELSE 0 END) AS total_porcentaje_avance,
SUM(CASE WHEN Empacado IS NOT NULL THEN UND ELSE 0 END) AS total_unidades
FROM 
exports
$whereClause_ex
GROUP BY 
    mes, Sucursal
";

// Realizando las conexiones
$result9 = $conn->query($query9);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$seriesData = []; // Series de datos agrupados por Sucursal
$sucursales = []; // Lista de sucursales únicas

// Paleta de colores para las series
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];
$colorPalette2 = ['#a06b1b','#4d8086','#9b2a28','#24363f','#aa6851','#738f8b','#5d7f6a','#56585b','#424c5a','#9ba3a8'];

// Función para inicializar datos
function initializeSeriesData($categories, $values) {
    return array_map(fn($category) => $values[$category] ?? 0, $categories);
}

// Procesar resultados
while ($row = $result9->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $sucursal = $row['Sucursal'] ?? 'NO DATA';
    
    // Inicializar sucursal y fecha si no existen
    if (!in_array($fecha, $categories)) {
        $categories[] = $fecha;
    }
    
    if (!array_key_exists($sucursal, $seriesData)) {
        $sucursales[] = $sucursal;
        $seriesData[$sucursal] = [
            'avance' => [],
            'unidades' => [],
        ];
    }
    
    // Asignar valores
    $seriesData[$sucursal]['avance'][$fecha] = (int)$row['total_porcentaje_avance'];
    $seriesData[$sucursal]['unidades'][$fecha] = (int)$row['total_unidades'];
}

// Preparar los datos para el gráfico
$series = [];
foreach ($sucursales as $index => $sucursal) {
    // Generar datos para ambas series
    $data_avance = initializeSeriesData($categories, $seriesData[$sucursal]['avance']);
    $data_unidades = initializeSeriesData($categories, $seriesData[$sucursal]['unidades']);
    
    // Agregar series con colores personalizados
    $series[] = [
        'name' => "$sucursal - Porcentaje de avance",
        'type' => 'line',
        'stack' => 'total', // Configuración para apilar las barras
        'data' => $data_avance,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => ['color' => $colorPalette[$index % count($colorPalette)]],
    ];
    
    $series[] = [
        'name' => "$sucursal - Suma de unidades",
        'type' => 'bar',
        'stack' => 'total', // Configuración para apilar las barras
        'data' => $data_unidades,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => ['color' => $colorPalette2[$index % count($colorPalette2)]],
    ];
}

// Preparar el JSON final
$chart9 = [
    'categories' => $categories, // Eje X
    'series' => $series,

];
// Termonan los graficos de exports




// Inician los graficos de picking
// Gráfico 10: Total de paletas por pais y mes
$query10 = "
SELECT
OID,
    DATE_FORMAT(FRD, '%Y-%m') AS mes,
    COUNT(CASE WHEN Empacado IS NOT NULL THEN OID ELSE 0 END) AS total_pedido_empacado,
    COUNT(CASE WHEN Despachado IS NOT NULL THEN OID ELSE 0 END) AS total_pedido_despachado,
    (COUNT(Empacado) * 1.0 / COUNT(Despachado)) * 100 AS porcentaje_de_avance
FROM 
    exports
    $whereClause_ex
    GROUP BY 
    mes, OID
";

// Realizando las conexiones
$result10 = $conn->query($query10);

// Ejecutar la consulta
$query10 = str_replace('$whereClause_ex', '', $query10); // Aquí se puede agregar un filtro adicional si es necesario
$result = $conn->query($query10);

// Inicialización de arrays
$categories = [];  // Para almacenar las fechas (eje X)
$seriesData = [];  // Datos agrupados por OID
$oids = [];  // Lista de OIDs únicos
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];  // Paleta de colores

// Procesar los resultados de la consulta
while ($row = $result->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $oid = $row['OID'] ?? 'NO DATA';
    
    // Agregar fechas a las categorías si no están presentes
    if (!in_array($fecha, $categories)) {
        $categories[] = $fecha;
    }

    // Agregar OID a la lista de OIDs si no está
    if (!array_key_exists($oid, $seriesData)) {
        $oids[] = $oid;
        $seriesData[$oid] = [
            'empacado' => [],
            'despachado' => [],
            'porcentaje_avance' => [],
        ];
    }
    
    // Asignar los valores correspondientes
    $seriesData[$oid]['empacado'][$fecha] = (int)$row['total_pedido_empacado'];
    $seriesData[$oid]['despachado'][$fecha] = (int)$row['total_pedido_despachado'];
    $seriesData[$oid]['porcentaje_avance'][$fecha] = (float)$row['porcentaje_de_avance'];
}

// Función para inicializar los datos de las series
$initializeSeriesData = function($categories, $values) {
    return array_map(fn($category) => $values[$category] ?? 0, $categories);
};

// Preparar las series de datos para el gráfico
$series = [];
foreach ($oids as $index => $oid) {
    // Inicializar los datos para cada OID
    $data_empacado = $initializeSeriesData($categories, $seriesData[$oid]['empacado']);
    $data_despachado = $initializeSeriesData($categories, $seriesData[$oid]['despachado']);
    $data_porcentaje_avance = $initializeSeriesData($categories, $seriesData[$oid]['porcentaje_avance']);
    
    // Generar las series para los gráficos
    $series[] = [
        'name' => "$oid - Total Pedido Empacado",
        'type' => 'bar',
        'data' => $data_empacado,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => ['color' => $colorPalette[$index % count($colorPalette)]],
    ];
    
    $series[] = [
        'name' => "$oid - Total Pedido Despachado",
        'type' => 'bar',
        'data' => $data_despachado,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => ['color' => $colorPalette[$index % count($colorPalette)]],
    ];
    
    $series[] = [
        'name' => "$oid - Porcentaje de Avance",
        'type' => 'bar',
        'data' => $data_porcentaje_avance,
        'emphasis'=>[ 'focus'=>'series'],
        'itemStyle' => ['color' => $colorPalette[$index % count($colorPalette)]],
    ];
}

// Devolver los datos en formato JSON para ser utilizados en el gráfico
$chart10 = [
    'categories' => $categories, // Eje X
    'series' => $series,
];


// Gráfico 11: Total de cajas por Cliente y mes
$query11 = "
SELECT
OID,
    DATE_FORMAT(FRD, '%Y-%m') AS mes,
    SUM(CASE WHEN Empacado IS NOT NULL THEN paletas ELSE 0 END) AS total_pedido_empacado,
    SUM(CASE WHEN Despachado IS NOT NULL THEN Paletas ELSE 0 END) AS total_pedido_despachado,
    (COUNT(Empacado) * 1.0 / COUNT(Despachado)) * 100 AS porcentaje_de_avance
FROM 
    exports
    $whereClause_ex
    GROUP BY 
    mes, OID
";

// Realizando las conexiones
$result11 = $conn->query($query11);

// Ejecutar la consulta
$query11 = str_replace('$whereClause_ex', '', $query11); // Aquí se puede agregar un filtro adicional si es necesario
$result = $conn->query($query11);

// Inicialización de arrays
$categories = [];  // Para almacenar las fechas (eje X)
$seriesData = [];  // Datos agrupados por OID
$oids = [];  // Lista de OIDs únicos
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];  // Paleta de colores

// Procesar los resultados de la consulta
while ($row = $result->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $oid = $row['OID'] ?? 'NO DATA';
    
    // Agregar fechas a las categorías si no están presentes
    if (!in_array($fecha, $categories)) {
        $categories[] = $fecha;
    }

    // Agregar OID a la lista de OIDs si no está
    if (!array_key_exists($oid, $seriesData)) {
        $oids[] = $oid;
        $seriesData[$oid] = [
            'empacado' => [],
            'despachado' => [],
            'porcentaje_avance' => [],
        ];
    }
    
    // Asignar los valores correspondientes
    $seriesData[$oid]['empacado'][$fecha] = (int)$row['total_pedido_empacado'];
    $seriesData[$oid]['despachado'][$fecha] = (int)$row['total_pedido_despachado'];
    $seriesData[$oid]['porcentaje_avance'][$fecha] = (float)$row['porcentaje_de_avance'];
}

// Función para inicializar los datos de las series
$initializeSeriesData = function($categories, $values) {
    return array_map(fn($category) => $values[$category] ?? 0, $categories);
};

// Preparar las series de datos para el gráfico
$series = [];
foreach ($oids as $index => $oid) {
    // Inicializar los datos para cada OID
    $data_empacado = $initializeSeriesData($categories, $seriesData[$oid]['empacado']);
    $data_despachado = $initializeSeriesData($categories, $seriesData[$oid]['despachado']);
    $data_porcentaje_avance = $initializeSeriesData($categories, $seriesData[$oid]['porcentaje_avance']);
    
    // Generar las series para los gráficos
    $series[] = [
        'name' => "$oid - Total Pedido Empacado",
        'type' => 'bar',
        'data' => $data_empacado,
        'itemStyle' => ['color' => $colorPalette[$index % count($colorPalette)]],
    ];
    
    $series[] = [
        'name' => "$oid - Total Pedido Despachado",
        'type' => 'bar',
        'data' => $data_despachado,
        'itemStyle' => ['color' => $colorPalette[$index % count($colorPalette)]],
    ];
    
    $series[] = [
        'name' => "$oid - Porcentaje de Avance",
        'type' => 'bar',
        'data' => $data_porcentaje_avance,
        'itemStyle' => ['color' => $colorPalette[$index % count($colorPalette)]],
    ];
}

// Devolver los datos en formato JSON para ser utilizados en el gráfico
$chart11 = [
    'categories' => $categories, // Eje X
    'series' => $series,
];


// Gráfico 12: Consulta para una única categoría
$query12 = "
SELECT 
    DATE_FORMAT(Confirmado, '%Y-%m') AS mes,
    COUNT(CASE WHEN Confirmado IS NOT NULL THEN OID ELSE NULL END) AS total_pedido_Confirmado
FROM 
    picking
$whereClause_pk
GROUP BY 
    mes
";

$result12 = $conn->query($query12);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$dataSeries = []; // Datos de la única categoría

// Paleta de colores para la serie (puedes cambiar si necesitas)


// Procesar resultados
while ($row = $result12->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $total = $row['total_pedido_Confirmado'] ?? 0;

    // Agregar la fecha y el dato correspondiente
    $categories[] = $fecha;
    $dataSeries[] = $total;
}

// Preparar el JSON para el gráfico
$chart12 = [
    'categories' => $categories, // Eje X
    'series' => [[
        'name' => 'Total Confirmado', // Nombre de la serie
        'type' => 'line',             // Tipo de gráfico
        'smooth' => true,
        'data' => $dataSeries,       // Datos de la serie
        'areaStyle' => [
            'opacity' => 0.8,
            'color' => [
                'type' => 'linear',
                'x' => 0,
                'y' => 0,
                'x2' => 0,
                'y2' => 1,
                'colorStops' => [
                    [
                        'offset' => 0,
                        'color' => 'rgba(255, 222, 102, 0.53)', // Color inicial
                    ],
                    [
                        'offset' => 1,
                        'color' => 'rgb(255, 153, 102)',  // Color final
                    ]
                ]
            ]
        ]
    ]],
];



// terminan los graficos de Picking

// inician los graficos de Varios
// Gráfico 13: Total de und_Recibidas por Cliente y mes
$query13 = "
SELECT 
    DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
    SUM(CASE WHEN Confirmado IS NOT NULL THEN Pend ELSE NULL END) AS total_SKU_Confirmado
FROM 
    picking
    $whereClause_pk
GROUP BY 
    mes
    ";

    $result13 = $conn->query($query13);

    // Inicializar arrays para almacenar los datos estructurados
    $categories = []; // Fechas para el eje X
    $dataSeries = []; // Datos de la única categoría
    

    
    // Procesar resultados
    while ($row = $result13->fetch_assoc()) {
        $fecha = $row['mes'] ?? 'NO DATA';
        $total = $row['total_SKU_Confirmado'] ?? 0;
    
        // Agregar la fecha y el dato correspondiente
        $categories[] = $fecha;
        $dataSeries[] = $total;
    }
    
    // Preparar el JSON para el gráfico
    $chart13 = [
        'categories' => $categories, // Eje X
        'series' => [[
            'name' => ' Stock Keeping Unit', // Nombre de la serie
            'type' => 'line',             // Tipo de gráfico
            'smooth' => true,
            'data' => $dataSeries,       // Datos de la serie
            'areaStyle' => [
                'opacity' => 0.8,
                'color' => [
                    'type' => 'linear',
                    'x' => 0,
                    'y' => 0,
                    'x2' => 0,
                    'y2' => 1,
                    'colorStops' => [
                        [
                            'offset' => 0,
                            'color' => 'rgb(255, 221, 102)', // Color inicial
                        ],
                        [
                            'offset' => 1,
                            'color' => 'rgb(102, 51, 153)',  // Color final
                        ]
                    ]
                ]
            ]
        ]],
    ];

// Gráfico 14: Total de paletas por pais y mes
$query14 = "
SELECT 
DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
COUNT(CASE WHEN Confirmado IS NOT NULL THEN Ubic ELSE NULL END) AS Ubic_rec
FROM 
picking
$whereClause_pk
GROUP BY 
    mes
";
$result14 = $conn->query($query14);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$dataSeries = []; // Datos de la única categoría

// Paleta de colores para la serie (puedes cambiar si necesitas)
$colorPalette = ['#c4ccd3'];

// Procesar resultados
while ($row = $result14->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $total = $row['Ubic_rec'] ?? 0;

    // Agregar la fecha y el dato correspondiente
    $categories[] = $fecha;
    $dataSeries[] = $total;
}

// Preparar el JSON para el gráfico
$chart14 = [
    'categories' => $categories, // Eje X
    'series' => [[
        'name' => ' Conteo de ubicaciones recorridas', // Nombre de la serie
        'type' => 'line',             // Tipo de gráfico
        'smooth' => true,
        'data' => $dataSeries,       // Datos de la serie
        'areaStyle' => [
            'opacity' => 0.8,
            'color' => [
                'type' => 'linear',
                'x' => 0,
                'y' => 0,
                'x2' => 0,
                'y2' => 1,
                'colorStops' => [
                    [
                        'offset' => 0,
                        'color' => 'rgb(102, 204, 255)', // Color inicial
                    ],
                    [
                        'offset' => 1,
                        'color' => 'rgb(0, 102, 204)',  // Color final
                    ]
                ]
            ]
        ]
    ]],
];

// Gráfico 15: Total de cajas por Cliente y mes
$query15 = "
SELECT 
DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
SUM(CASE WHEN Confirmado IS NOT NULL THEN Pend ELSE NULL END) AS Ubic_rec
FROM 
picking
$whereClause_pk
GROUP BY 
    mes
";
$result15 = $conn->query($query15);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$dataSeries = []; // Datos de la única categoría

// Paleta de colores para la serie (puedes cambiar si necesitas)
$colorPalette = ['#2f4554'];

// Procesar resultados
while ($row = $result15->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $total = $row['Ubic_rec'] ?? 0;

    // Agregar la fecha y el dato correspondiente
    $categories[] = $fecha;
    $dataSeries[] = $total;
}

// Preparar el JSON para el gráfico
$chart15 = [
    'categories' => $categories, // Eje X
    'series' => [[
        'name' => 'Total de Piezas', // Nombre de la serie
        'type' => 'line',             // Tipo de gráfico
        'smooth' => true,
        'data' => $dataSeries,       // Datos de la serie
        'areaStyle' => [
            'opacity' => 0.8,
            'color' => [
                'type' => 'linear',
                'x' => 0,
                'y' => 0,
                'x2' => 0,
                'y2' => 1,
                'colorStops' => [
                    [
                        'offset' => 0,
                        'color' => 'rgb(153, 102, 255)', // Color inicial
                    ],
                    [
                        'offset' => 1,
                        'color' => 'rgb(102, 51, 153)',  // Color final
                    ]
                ]
            ]
        ]
    ]],
];

// Gráfico 16: Ejemplo adicional de consulta
$query16 = "
SELECT 
DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
Categoria,
SUM(CASE WHEN Confirmado IS NOT NULL THEN CIA ELSE NULL END) AS Ubic_rec
FROM 
picking
$whereClause_pk
GROUP BY 
    mes
";
$result16 = $conn->query($query16);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$dataSeries = []; // Datos de la única categoría

// Paleta de colores para la serie (puedes cambiar si necesitas)
$colorPalette = ['#91c7ae'];

// Procesar resultados
while ($row = $result16->fetch_assoc()) {
    $fecha = $row['Categoria'] ?? 'NO DATA';
    $total = $row['Ubic_rec'] ?? 0;

    // Agregar la fecha y el dato correspondiente
    $categories[] = $fecha;
    $dataSeries[] = $total;
}

// Preparar el JSON para el gráfico
$chart16 = [
    'categories' => $categories, // Eje X
    'series' => [[
        'name' => 'Series Name',  // Nombre de la serie
        'type' => 'line',         // Tipo de gráfico
        'smooth' => true,
        'data' => $dataSeries,    // Datos de la serie
        'areaStyle' => [
            'opacity' => 0.8,
            'color' => [
                'type' => 'linear',
                'x' => 0,
                'y' => 0,
                'x2' => 0,
                'y2' => 1,
                'colorStops' => [
                    [
                        'offset' => 0,
                        'color' => 'rgb(200, 200, 200)', // Color inicial
                    ],
                    [
                        'offset' => 1,
                        'color' => 'rgb(128, 128, 128)',  // Color final
                    ]
                ]
            ]
        ]
    ]]
];



// Gráfico 17: Ejemplo adicional de consulta
$query17 = "
SELECT 
DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
Usuario,
COUNT(CASE WHEN Confirmado IS NOT NULL THEN Usuario ELSE NULL END) AS Ubic_rec
FROM 
picking
$whereClause_pk
GROUP BY 
    Usuario
";
$result17 = $conn->query($query17);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$dataSeries = []; // Datos de la única categoría

// Paleta de colores para la serie (puedes cambiar si necesitas)
$colorPalette = ['#61a0a8'];

// Procesar resultados
while ($row = $result17->fetch_assoc()) {
    $fecha = $row['Usuario'] ?? 'NO DATA';
    $total = $row['Ubic_rec'] ?? 0;

    // Agregar la fecha y el dato correspondiente
    $categories[] = $fecha;
    $dataSeries[] = $total;
}

// Preparar el JSON para el gráfico
$chart17 = [
    'categories' => $categories, // Eje X
    'series' => [[
        'name' => $categories, // Nombre de la serie
        'type' => 'line', 
        'smooth' => true,            // Tipo de gráfico
        'data' => $dataSeries,       // Datos de la serie
        'areaStyle' => [
            'opacity' => 0.8,
            'color' => [
                'type' => 'linear',
                'x' => 0,
                'y' => 0,
                'x2' => 0,
                'y2' => 1,
                'colorStops' => [
                    [
                        'offset' => 0,
                        'color' => 'rgb(128, 255, 165)', // Color inicial
                    ],
                    [
                        'offset' => 1,
                        'color' => 'rgb(1, 191, 236)',  // Color final
                    ]
                ]
            ]
        ]
    ]],
];



// Gráfico 18: Ejemplo adicional de consulta
$query18 = "
SELECT 
DATE_FORMAT(FRD, '%Y-%m') AS mes,
Cliente,
Sum(CASE WHEN FRD IS NOT NULL THEN Cajas ELSE NULL END) AS Ubic_rec
FROM 
exports
$whereClause_ex
GROUP BY 
    mes
";
$result18 = $conn->query($query18);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$dataSeries = []; // Datos de la única categoría

// Paleta de colores para la serie (puedes cambiar si necesitas)
$colorPalette = ['#ca8622','#61a0a8','#c23531','#2f4554','#d48265','#91c7ae','#749f83','#6e7074','#546570','#c4ccd3'];  // Paleta de colores

// Procesar resultados
while ($row = $result18->fetch_assoc()) {
    $fecha = $row['Cliente'] ?? 'NO DATA';
    $total = $row['Ubic_rec'] ?? 0;

    // Agregar la fecha y el dato correspondiente
    $categories[] = $fecha;
    $dataSeries[] = $total;
}

// Preparar el JSON para el gráfico
$chart18 = [
    'categories' => $categories, // Eje X
    'series' => [[
        'name' => $categories, // Nombre de la serie
        'type' => 'bar',             // Tipo de gráfico
        'data' => $dataSeries,       // Datos de la serie
        'itemStyle' => [
            'color' => $colorPalette[4], // Color de la serie
        ],
    ]],
];

// Gráfico 19: Ejemplo adicional de consulta
$query19 = "
SELECT 
DATE_FORMAT(FRD, '%Y-%m') AS mes,
COUNT(CASE WHEN Despachado IS NULL THEN Paletas ELSE NULL END) AS Ubic_rec
FROM 
exports
$whereClause_ex
GROUP BY 
    mes
";
$result19 = $conn->query($query19);

// Inicializar arrays para almacenar los datos estructurados
$categories = []; // Fechas para el eje X
$dataSeries = []; // Datos de la única categoría

// Paleta de colores para la serie (puedes cambiar si necesitas)
$colorPalette = ['#61a0a8'];

// Procesar resultados
while ($row = $result19->fetch_assoc()) {
    $fecha = $row['mes'] ?? 'NO DATA';
    $total = $row['Ubic_rec'] ?? 0;

    // Agregar la fecha y el dato correspondiente
    $categories[] = $fecha;
    $dataSeries[] = $total;
}

// Preparar el JSON para el gráfico
$chart19 = [
    'categories' => $categories, // Eje X
    'series' => [[
        'name' => $categories, // Nombre de la serie
        'type' => 'line',             // Tipo de gráfico
        'data' => $dataSeries,       // Datos de la serie
        'itemStyle' => [
            'color' => $colorPalette[0], // Color de la serie
        ],
    ]],
];


// Gráfico 20: Ejemplo adicional de consulta
$query20 = "
    SELECT 
        Via,
        COUNT(*) * 100.0 / (SELECT COUNT(*) FROM imports $whereClause_im) AS total_carga
    FROM 
        imports
    $whereClause_im
    GROUP BY 
        Via
    ORDER BY 
        Via
";
$result20 = $conn->query($query20);
$chart20 = [];
if ($result20->num_rows > 0) {
    while ($row = $result20->fetch_assoc()) {
        $chart20[] = [
            'name' => $row['Via'] ? $row['Via'] : 'NO DATA',
            'value' => round((float)$row['total_carga'], 2),
        ];
    }
}


// Terminan  los graficos de varios




// Empaqueta los datos en un solo array
$data = [
    // imports

    // variables del grafico 1
    'total_paletas_Recibidas' => $total_paletas_Recibidas,
    'total_cajas' => $total_cajas,
    'total_KG' => $total_KG,
    'total_CBM' => $total_CBM,
    // variables del grafico 1
    
    // variables del grafico 2
    'total_grande' => $total_grande,
    'total_mediano' => $total_mediano,
    'total_pequeño' => $total_pequeño,
    // variables del grafico 2

    'chart3' => $chart3,
    'chart4' => $chart4,
    // imports
    
    // exports
    'chart5' => $chart5,
    'chart6' => $chart6,
    'chart7' => $chart7,
    'chart8' => $chart8,
    'chart9' => $chart9,
    'chart10' => $chart10,
    'chart11' => $chart11,
    // exports
    
    // picking
    'chart12' => $chart12,
    'chart13' => $chart13,
    'chart14' => $chart14,
    'chart15' => $chart15,
    'chart16' => $chart16,
    'chart17' => $chart17,
    // picking
    
    // varios
    'chart18' => $chart18,
    'chart19' => $chart19,
    'chart20' => $chart20,
    // varios
];

// Cierra la conexión
$conn->close();

// Devuelve los datos en formato JSON
echo json_encode($data);

?>

