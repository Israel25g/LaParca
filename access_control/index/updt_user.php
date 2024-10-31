<?php
// Conexión a la base de datos
include_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    $fields = [];
    if ($username) {
        $fields[] = "username = '$username'";
    }
    if ($email) {
        $fields[] = "email = '$email'";
    }
    if ($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $fields[] = "password = '$hashedPassword'";
    }

    if (!empty($fields)) {
        $fieldsString = implode(', ', $fields);
        $query = "UPDATE users SET $fieldsString WHERE id = $userId";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "Usuario actualizado correctamente.";
        } else {
            echo "Error al actualizar el usuario: " . mysqli_error($conn);
        }
    } else {
        echo "No se proporcionaron campos para actualizar.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>