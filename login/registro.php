<?php

    session_start();

    include("Conexion.php");

    if(isset($_POST['user']) && isset($_POST['password'])){
        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $user = validate($_POST['user']);
        $password = $_POST['password'];
        $departamento = $_POST['departamento'];	
        $password_hash = password_hash($_POST['password2'], PASSWORD_DEFAULT);
        

        if(password_verify($password, $password_hash)){
            $sql = "INSERT INTO users(user, pass, rol_id)  VALUES('$user', '$password_hash', '$departamento')";
            $result = mysqli_query($conexion, $sql);

            if($result){
                header("Location: index.php?success=Usuario registrado correctamente");
                
                exit();
            }else{
                header("Location: index_registro.php?error=Error al registrar el usuario");
                exit();
            }

        }
        else{
            header("Location: index_registro.php?error=Las contraseñas no coinciden");
            exit();
        }
}