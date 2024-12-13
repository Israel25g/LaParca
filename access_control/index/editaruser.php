<?php

include("../../config.php");

if ($_SERVER["REQUEST_METHOD"] = "POST"){

    $id = $_POST['id'];
    $nombre = $_POST['name'];
    $usuario = $_POST['user'];
    $correo = $_POST['email'];
    $departamento = $_POST['departamento'];

    $sql = "UPDATE users SET id='$id', usuario='$nombre', user='$usuario', email='$correo', rol_id='$departamento', updated_at = CONVERT_TZ(NOW(), '+00:00', '-05:00') WHERE id='$id'";
    
    $result = mysqli_query($conexion, $sql);
    if ($result) {
        header ("Location: ../index/index_users2.php?success=Usuario actualizado correctamente");
    } else {
        echo "Error al actualizar el usuario: " . mysqli_error($conexion);
    }


}