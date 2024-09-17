<?php
    // cierre de sesión
    session_start();
    session_unset();
    session_destroy();

    header("Location: ../login/index.php");
    exit();
?>