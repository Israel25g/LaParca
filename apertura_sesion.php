<?php
session_unset();
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php?error=No has iniciado sesión.');
    exit;
}
?>