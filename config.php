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


// =========================================PRODUCTIVO=========================================
// return [
//   'db' => [
//     'host' => 'localhost',
//     'user' => 'u366386740_admin123',
//     'pass' => '1plGr0up01*',
//     'name' => 'u366386740_db_mainbase',
//     'options' => [
//       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//     ]
//   ]
// ];
// =========================================PRODUCTIVO=========================================


// =========================================TESTING=========================================
// return [
//   'db' => [
//     'host' => 'localhost',
//     'user' => 'u366386740_adminTestMain',
//     'pass' => '1plGr0up01*',
//     'name' => 'u366386740_db_test_main',
//     'options' => [
//       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//     ]
//   ]
// ];
// =========================================TESTING=========================================

// =========================================LOCALHOST=========================================

// return [
//   'db' => [
//     'host' => 'localhost',
//     'user' => 'root',
//     'pass' => '',
//     'name' => 'db_mainbase',
//     'options' => [
//       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//     ]
//   ]
// ];
// =========================================LOCALHOST=========================================

