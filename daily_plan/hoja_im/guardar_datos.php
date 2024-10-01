<?php
// Recibir los datos enviados desde el cliente
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // Convertir los datos a formato JSON
    $json_data = json_encode($data['data'], JSON_PRETTY_PRINT);

    // Escribir los datos en un archivo JSON
    $archivo = 'arreglo_im.json'; // Nombre del archivo
    if (file_put_contents($archivo, $json_data)) {
        // Respuesta exitosa
        echo json_encode(['message' => 'Datos de Import seran guardados']);
    } else {
        // Error al guardar los datos
        echo json_encode(['message' => 'Error al guardar los datos ']);
    }
} else {
    // Error al recibir los datos
    echo json_encode(['message' => 'No se recibieron los datos correctamente']);
}
?>