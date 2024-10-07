<?php
// Archivo de conexión a la base de datos (config_G.php)

// Configuración de la conexión
$servername = "localhost";
$username = "root";
$password = "";  // Sin contraseña
$dbname = "daily_plan";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {

}
?>
