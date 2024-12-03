
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

// Generar condición de rango de fechas
$dateCondition = "";
if ($fecha_inicio && $fecha_final) {
    $dateCondition = "EJE BETWEEN '$fecha_inicio' AND '$fecha_final'";
} elseif ($fecha_inicio) {
    $dateCondition = "EJE >= '$fecha_inicio'";
} elseif ($fecha_final) {
    $dateCondition = "EJE <= '$fecha_final'";
}

// Generar condición para Cliente
$clientCondition = "";
if ($Cliente) {
    $clientCondition = "CIA = '$Cliente'";
}

// Combinar condiciones (si existen ambas)
$whereClause = "";
if ($dateCondition && $clientCondition) {
    $whereClause = "WHERE $clientCondition AND $dateCondition ";
} elseif ($dateCondition) {
    $whereClause = "WHERE $dateCondition";
} elseif ($clientCondition) {
    $whereClause = "WHERE $clientCondition";
}

// Consultas para múltiples gráficos

// Inician los graficos de imports
// Gráfico 1: Total de und_Recibidas por Cliente y mes

$query1 = "
SELECT 
    DATE_FORMAT(UPD_Eta, '%Y-%m-%d') AS mes,
    Cliente,
    und_Recibidas,
    SUM(und_Recibidas) AS total_und_Recibidas,
    SUM(cajas) AS total_cajas,
    SUM(KG) AS total_KG,
    SUM(CBM) total_CBM,
    SUM(SKUs) AS SKUs_total,
    SUM(und_esperadas) as total_und_esperadas
FROM 
    imports
$whereClause
GROUP BY
mes
";
// echo $query1;
$result1 = $conn->query($query1);
$chart1 = [];
$line1 = [];
$line2 = [];
$line3 = [];
$line4 = [];
$line5 = [];
$line6 = [];

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $chart1[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['und_Recibidas'],
                (int)$row['total_und_Recibidas'],
            ],
        ];
        $line1[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['und_Recibidas'],
                (int)$row['total_und_esperadas'],
            ],
        ];
        $line2[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['und_Recibidas'],
                (int)$row['total_cajas'],
            ],
        ];
        $line3[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['und_Recibidas'],
                (int)$row['total_KG'],
            ],
        ];
        $line4[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['und_Recibidas'],
                (int)$row['total_CBM'],
            ],
        ];
        $line5[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                (int)$row['und_Recibidas'],
                (int)$row['SKUs_total'],
            ],
        ];
    }
}

// Gráfico 2: Total de paletas por País y mes
$query2 = "
    SELECT 
        DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
        sucursal,
        SUM(paletas) AS total_paletas
    FROM 
        imports
    $whereClause
GROUP BY
mes
";
$result2 = $conn->query($query2);
$chart2 = [];
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $chart2[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                $row['sucursal'],
                (int)$row['total_paletas'],
            ],
        ];
    }
}

// Gráfico 3: Total de cajas por Cliente y mes
$query3 = "
    SELECT 
        DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
        Cliente,
        SUM(cajas) AS total_cajas
    FROM 
        imports
    $whereClause
    group BY
mes
";
$result3 = $conn->query($query3);
$chart3 = [];
if ($result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
        $chart3[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                $row['mes'],
                (int)$row['total_cajas'],
            ],
        ];
    }
}

// Gráfico 4: Ejemplo adicional de consulta
$query4 = "
    SELECT 
        DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
        Cliente,
        Tamaño,
        COUNT(Tamaño) AS total_carga
    FROM 
        imports
    $whereClause
GROUP BY
Tamaño

";
$result4 = $conn->query($query4);
$chart4 = [];
if ($result4->num_rows > 0) {
    while ($row = $result4->fetch_assoc()) {
        $chart4[] = [
            'name' => $row['Tamaño'] ? $row['Tamaño'] : 'NO DATA',
            'value' => [
                (int)$row['mes'],
                (int)$row['total_carga'],
            ],
        ];
    }
}
//Terminan los graficos de imports

// inician los graficos de exports
// Gráfico 5: Total de und_Recibidas por sucursal y mes
$query5 = "
SELECT 
    DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
    sucursal,
    SUM(piezas) AS total_und_Recibidas
FROM 
    exports
$whereClause
GROUP BY
sucursal
";
$result5 = $conn->query($query5);
$chart5 = [];
if ($result5->num_rows > 0) {
while ($row = $result5->fetch_assoc()) {
    $chart5[] = [
        'name' => $row['sucursal'] ? $row['sucursal'] : 'NO DATA',
        'value' => [
            $row['mes'],
            (int)$row['total_und_Recibidas'],
        ],
    ];
}
}

// Gráfico 6: Total de paletas por País y mes
$query6 = "
SELECT 
    DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
    País,
    SUM(paletas) AS total_paletas
FROM 
    exports
$whereClause
GROUP BY
País
";
$result6 = $conn->query($query6);
$chart6 = [];
if ($result6->num_rows > 0) {
while ($row = $result6->fetch_assoc()) {
    $chart6[] = [
        'name' => $row['País'] ? $row['País'] : 'NO DATA',
        'value' => [
            $row['mes'],
            (int)$row['total_paletas'],
        ],
    ];
}
}

// Gráfico 7: Total de cajas por sucursal y mes
$query7 = "
SELECT 
    DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
    sucursal,
    SUM(Piezas_pick) AS total_cajas
FROM 
    exports
$whereClause
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
    DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
    sucursal,
    CBM,
    País,
    SUM(KG) AS total_und_Recibidas
FROM 
    exports
$whereClause
GROUP BY 
País
";
$result8 = $conn->query($query8);
$chart8 = [];
if ($result8->num_rows > 0) {
while ($row = $result8->fetch_assoc()) {
    $chart8[] = [
        'name' => $row['País'] ? $row['País'] : 'NO DATA',
        'value' => [
            $row['País'],
            (int)$row['total_und_Recibidas'],
        ],
    ];
}
}
// Termonan los graficos de exports

// Inician los graficos de picking
// Gráfico 9: Total de und_Recibidas por Cliente y mes
$query9 = "
    SELECT 
        DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
        Cliente,
        SUM(piezas) AS total_und_Recibidas,
        SUM(Piezas_Pick) AS total_und_pick
    FROM 
        picking
    $whereClause
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
                (int)$row['total_und_Recibidas'],
            ],
        ];
        $line9[] = [
            'name' => $row['mes'] ? $row['mes'] : 'NO DATA',
            'value' => [
                $row['mes'],
                (int)$row['total_und_pick'],
            ],
        ];
    }
}

// Gráfico 10: Total de paletas por País y mes
$query10 = "
    SELECT 
        DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
        País,
        SUM(paletas) AS total_paletas
    FROM 
        picking
    $whereClause
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
        DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
        Cliente,
        SUM(piezas_pick) AS total_und_Recibidas
    FROM 
        picking
    $whereClause
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
        DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
        Cliente,
        SUM(KG) AS total_cajas
    FROM 
        picking
    $whereClause
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
    DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
    sucursal,
    Piezas,
    Piezas_pick
FROM 
    exports
$whereClause

";
$result13 = $conn->query($query13);
$chart13 = [];
if ($result13->num_rows > 0) {
while ($row = $result13->fetch_assoc()) {
    $chart13[] = [
        'name' => $row['sucursal'] ? $row['sucursal'] : 'NO DATA',
        'value' => [
            (int)$row['Piezas_pick'],
            (int)$row['Piezas'],
        ],
    ];
}
}

// Gráfico 14: Total de paletas por País y mes
$query14 = "
SELECT 
    DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
    Cliente,
    SUM(paletas) AS total_paletas
FROM 
    imports
$whereClause

";
$result14 = $conn->query($query14);
$chart14 = [];
if ($result14->num_rows > 0) {
while ($row = $result14->fetch_assoc()) {
    $chart14[] = [
        'name' => $row['Cliente'] ? $row['Cliente'] : 'NO DATA',
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
    DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
    País,
    SUM(KG) AS total_cajas
FROM 
    exports
$whereClause

";
$result15 = $conn->query($query15);
$chart15= [];
if ($result15->num_rows > 0) {
while ($row = $result15->fetch_assoc()) {
    $chart15[] = [
        'name' => $row['País'] ? $row['País'] : 'NO DATA',
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
    DATE_FORMAT(EJE, '%Y-%m-%d') AS mes,
    País,
    SUM(paletas) AS total_und_Recibidas
FROM 
    exports
$whereClause

";
$result16 = $conn->query($query16);
$chart16 = [];
if ($result16->num_rows > 0) {
while ($row = $result16->fetch_assoc()) {
    $chart16[] = [
        'name' => $row['País'] ? $row['País'] : 'NO DATA',
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
    'chart1' => $chart1,
    'line1' =>$line1,
    'line2' =>$line2,
    'line3' =>$line3,
    'line4' =>$line4,
    'line5' =>$line5,
    'chart2' => $chart2,
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

