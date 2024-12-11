
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
// echo $query1;
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
    mes DESC;
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
        'itemStyle' => [
            'color' => $color, // Color personalizado para la serie
        ],
    ];
}

// Preparar el JSON final
$chart3 = [
    'categories' => $categories, // Eje X
    'series' => $series,         // Datos de las series
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
    mes DESC;
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
        'itemStyle' => [
            'color' => $color, // Color personalizado para la serie
        ],
    ];
}

// Preparar el JSON final
$chart5 = [
    'categories' => $categories, // Eje X
    'series' => $series,         // Datos de las series
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
    mes DESC;
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
        'name' => $Pais . ' - Despachadas',
        'type' => 'bar',
        'data' => $data_despachadas,
        'itemStyle' => [
            'color' => $color_despachadas, // Color personalizado para la serie de despachadas
        ],
    ];

    $series[] = [
        'name' => $Pais . ' - Pendientes',
        'type' => 'line',
        'data' => $data_pendientes,
        'itemStyle' => [
            'color' => $color_pendientes, // Color personalizado para la serie de pendientes
        ],
    ];
}

// Preparar el JSON final
$chart6 = [
    'categories' => $categories, // Eje X
    'series' => $series,         // Datos de las series
];


// Gráfico 7: Total de cajas por sucursal y mes
$query7 = "
SELECT 
    DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
    sucursal,
    SUM(UND_pick) AS total_cajas
FROM 
    exports
$whereClause_ex
GROUP BY
sucursal
";
$result7 = $conn->query($query7);
$chart7= [];
if ($result7->num_rows > 0) {
while ($row = $result7->fetch_assoc()) {
    $chart7[] = [
        'name' => $row['sucursal'] ? $row['sucursal'] : 'NO DATA',
        'value' => [
            $row['mes'],
            (int)$row['total_cajas'],
        ],
    ];
}
}

// Gráfico 8: Ejemplo adicional de consulta
$query8 = "
SELECT 
    DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
    Sucursal,
    pais,
    SUM(Cajas) AS total_cajas_Recibidas
FROM 
    exports
$whereClause_ex
GROUP BY 
pais
";
$result8 = $conn->query($query8);
$chart8 = [];
if ($result8->num_rows > 0) {
while ($row = $result8->fetch_assoc()) {
    $chart8[] = [
        'name' => $row['pais'] ? $row['pais'] : 'NO DATA',
        'value' => [
            $row['pais'],
            (int)$row['total_cajas_Recibidas'],
        ],
    ];
}
}
// Termonan los graficos de exports

// Inician los graficos de picking
// Gráfico 9: Total de und_Recibidas por Cliente y mes
$query9 = "
    SELECT 
        DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
        Cliente,
            Pend
    FROM 
        picking
    $whereClause_pk
GROUP BY
mes
";
$result9 = $conn->query($query9);
$chart9 = [];
$line9 = [];
if ($result9->num_rows > 0) {
    while ($row = $result9->fetch_assoc()) {
        $chart9[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                $row['mes'],
                (int)$row['Pend'],
            ],
        ];
        $line9[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                $row['mes'],
                (int)$row['Pend'],
            ],
        ];
    }
}

// Gráfico 10: Total de paletas por pais y mes
$query10 = "
    SELECT 
        DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
        Categoria,
        SUM(Pend) AS total_paletas
    FROM 
        picking
    $whereClause_pk
GROUP BY
mes
";
$result10 = $conn->query($query10);
$chart10 = [];
if ($result10->num_rows > 0) {
    while ($row = $result10->fetch_assoc()) {
        $chart10[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                $row['mes'],
                (int)$row['total_paletas'],
            ],
        ];
    }
}

// Gráfico 11: Total de cajas por Cliente y mes
$query11 = "
    SELECT 
        DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
        Cliente,
        SUM(Pend) AS total_und_Recibidas
    FROM 
        picking
    $whereClause_pk
GROUP BY
cliente
";
$result11 = $conn->query($query11);
$chart11 = [];
if ($result11->num_rows > 0) {
    while ($row = $result11->fetch_assoc()) {
        $chart11[] = [
            'name' => $row['Cliente'] ? $row['Cliente'] : 'NO DATA',
            'value' => [
                $row['mes'],
                (int)$row['total_und_Recibidas'],
            ],
        ];
    }
}

// Gráfico 12: Ejemplo adicional de consulta
$query12 = "
    SELECT 
        DATE_FORMAT(Confirmado, '%Y-%m-%d') AS mes,
        Cliente,
        SUM(Pend) AS total_cajas
    FROM 
        picking
    $whereClause_pk
GROUP BY
cliente
";
$result12 = $conn->query($query12);
$chart12 = [];
if ($result12->num_rows > 0) {
    while ($row = $result12->fetch_assoc()) {
        $chart12[] = [
            'name' => $row['Cliente'] ? $row['Cliente'] : 'NO DATA',
            'value' => [
                $row['mes'],
                (int)$row['total_cajas'],
            ],
        ];
    }
}

// terminan los graficos de Picking

// inician los graficos de Varios
// Gráfico 13: Total de und_Recibidas por Cliente y mes
$query13 = "
SELECT 
    DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
    sucursal,
    UND,
    UND_Pick
FROM 
    exports
$whereClause_ex

";
$result13 = $conn->query($query13);
$chart13 = [];
if ($result13->num_rows > 0) {
while ($row = $result13->fetch_assoc()) {
    $chart13[] = [
        'name' => $row['sucursal'] ? $row['sucursal'] : 'NO DATA',
        'value' => [
            (int)$row['UND_Pick'],
            (int)$row['UND'],
        ],
    ];
}
}

// Gráfico 14: Total de paletas por pais y mes
$query14 = "
SELECT 
    DATE_FORMAT(ETA, '%Y-%m-%d') AS mes,
    pais,
    SUM(paletas) AS total_paletas
FROM 
    imports
$whereClause_im

";
$result14 = $conn->query($query14);
$chart14 = [];
if ($result14->num_rows > 0) {
while ($row = $result14->fetch_assoc()) {
    $chart14[] = [
        'name' => $row['pais'] ? $row['pais'] : 'NO DATA',
        'value' => [
            $row['mes'],
            (int)$row['total_paletas'],
        ],
    ];
}
}

// Gráfico 15: Total de cajas por Cliente y mes
$query15 = "
SELECT 
    DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
    pais,
    SUM(Cajas) AS total_cajas
FROM 
    exports
$whereClause_ex

";
$result15 = $conn->query($query15);
$chart15= [];
if ($result15->num_rows > 0) {
while ($row = $result15->fetch_assoc()) {
    $chart15[] = [
        'name' => $row['pais'] ? $row['pais'] : 'NO DATA',
        'value' => [
            $row['mes'],
            (int)$row['total_cajas'],
        ],
    ];
}
}

// Gráfico 16: Ejemplo adicional de consulta
$query16 = "
SELECT 
    DATE_FORMAT(FRD, '%Y-%m-%d') AS mes,
    pais,
    SUM(paletas) AS total_und_Recibidas
FROM 
    exports
$whereClause_ex

";
$result16 = $conn->query($query16);
$chart16 = [];
if ($result16->num_rows > 0) {
while ($row = $result16->fetch_assoc()) {
    $chart16[] = [
        'name' => $row['pais'] ? $row['pais'] : 'NO DATA',
        'value' => [
            $row['mes'],
            (int)$row['total_und_Recibidas'],
        ],
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
    // exports

    // picking
    'chart9' => $chart9,
    'line9' => $line9,
    'chart10' => $chart10,
    'chart11' => $chart11,
    'chart12' => $chart12,
    // picking

    // varios
    'chart13' => $chart13,
    'chart14' => $chart14,
    'chart15' => $chart15,
    'chart16' => $chart16,
    // varios
];

// Cierra la conexión
$conn->close();

// Devuelve los datos en formato JSON
echo json_encode($data);
?>

