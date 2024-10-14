<?php
$servername = "localhost";
$database = "u366386740_db_mainbase";
$username = "u366386740_admin123";
$password = "1plGr0up01*";
// Create connection
$conexion = new mysqli($servername, $username, $password, $database);
// Check connection
if (!$conexion) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


<!-- ==============================PRODUCTIVO======================================
    $servername = "localhost";
    $database = "u366386740_db_mainbase";
    $username = "u366386740_admin123";
    $password = "1plGr0up01*";
    // Create connection
    $conexion = new mysqli($servername, $username, $password, $database);
    // Check connection
    if (!$conexion) {
        die("Connection failed: " . mysqli_connect_error());}
        
    ==============================PRODUCTIVO======================================-->

    <!-- ==============================LOCALHOST======================================
    $servername = "localhost";
    $database = "db_mainbase";
    $username = "root";
    $password = "";
    // Create connection
    $conexion = new mysqli($servername, $username, $password, $database);
    // Check connection
    if (!$conexion) {
        die("Connection failed: " . mysqli_connect_error());}
     ==============================LOCALHOST======================================-->

<!-- ==============================testing======================================
    $servername = "localhost";
    $database = "u366386740_db_test_main";
    $username = "u366386740_adminTestMain";
    $password = "1plGr0up01*";
    // Create connection
    $conexion = new mysqli($servername, $username, $password, $database);
    // Check connection
    if (!$conexion) {
        die("Connection failed: " . mysqli_connect_error());}
    ==============================testing====================================== -->