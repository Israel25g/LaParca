<?php

session_start();

include("Conexion.php");

if (isset($_POST['user']) && isset($_POST['password'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $user = validate($_POST['user']);
    $password = $_POST['password'];
    $departamento = $_POST['departamento'];
    $password_hash = password_hash($_POST['password2'], PASSWORD_DEFAULT);
    
    $bd_user = "SELECT user FROM users WHERE user='$user'";
    $result_user = mysqli_query($conexion, $bd_user);

    if (mysqli_num_rows($result_user) > 0) {        
        header("Location: index_registro.php?error=El usuario ya existe");  
        exit();
    } else {
        if (password_verify($password, $password_hash)) {
            $sql = "INSERT INTO users(user, pass, rol_id)  VALUES('$user', '$password_hash', '$departamento')";
            $result = mysqli_query($conexion, $sql);
    
            if ($result) {
                header("Location: ../access_control/index/index_users.php?success=Usuario registrado correctamente");
    
                exit();
            } else {
                header("Location: index_registro.php?error=Error al registrar el usuario");
                exit();
            }
        } else {
            header("Location: index_registro.php?error=Las contraseñas no coinciden");
            exit();
        }
    }
}
