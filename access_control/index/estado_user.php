<?php
session_start();

include ("../../config.php");

if(isset ($_POST['estado'])){
    $estado = $_POST['estado'];
    $id = $_POST['id'];
    
    $sql = "UPDATE users SET estado_id='$estado',updated_at=NOW() WHERE id='$id'";
    $result = mysqli_query($conexion, $sql);
    
    if($result){
        header("Location: ./index_users2.php?error=Estado actualizado correctamente");
        exit();
    }else{
        header("Location: ./index/index_users2.php?error=Error al actualizar el estado");
        exit();
    }
}



?>