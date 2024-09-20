<?php
// Conexión a la base de datos
include('config_DB.php'); 

// Obtener los datos enviados desde la tabla
$data = json_decode(file_get_contents("php://input"), true);

// Verificar que los datos no estén vacíos
if (!empty($data)) {
    $sql = "INSERT INTO tu_tabla (campo1, campo2, campo3) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Subir cada fila a la base de datos
    foreach ($data as $fila) {
        $stmt->bind_param("sss", $fila[0], $fila[1], $fila[2]); // Cambia el tipo según los datos
        $stmt->execute();
    }

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se enviaron datos"]);
}

$conn->close();
?>
