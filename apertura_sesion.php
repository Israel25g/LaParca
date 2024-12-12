<?php
session_unset();
session_start();
date_default_timezone_set('America/Panama');

if (!isset($_SESSION['user'])) {    
    header('Location: index.php?error=No has iniciado sesión.');
    exit;
}
?>