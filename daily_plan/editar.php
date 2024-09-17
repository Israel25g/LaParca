<?php
include 'config_DP.php';  // Asegúrate de tener tu conexión a la base de datos en este archivo

$query = "SELECT cliente, grafica_dp FROM datos";
$result = $conn->query($query);

$data = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = array(
            'name' => $row['cliente'],
            'value' => (int)$row['grafica_dp']
        );
    }
}

echo json_encode($data);

$conn->close();
?>
