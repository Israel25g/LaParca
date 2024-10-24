<?php
$servername = "localhost";
$database = "u366386740_db_mainbase";
$username = "u366386740_admin123";
$password = "1plGr0up01*";
// Create connection
$conexion2 = new mysqli($servername, $username, $password, $database);
// Check connection
if (!$conexion2) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM roles";
$sql2 = "SELECT * FROM estados";
$result = mysqli_query($conexion2, $sql);
$result2 = mysqli_query($conexion2, $sql2);
?>

<form action="access_control\index\actualizar_usuario.php" method="POST">
    <div class="row">
        <div class="col-md-4 ps-3">
            <h4>Nombre de Usuario</h4>
            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="<?= escapar($fila["usuario"]); ?>">
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 ps-3">
            <h4>Usuario</h4>
            <input type="text" name="user" id="user" class="form-control" placeholder="<?= escapar($fila["user"]); ?>">
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 ps-3">
            <h4>Correo</h4>
            <input type="email" name="email" id="email" class="form-control" placeholder="<?= escapar($fila["email"]); ?>">
        </div>
    </div>


    <?php
    if (mysqli_num_rows($result) > 0) {
    ?>
        <div class="row">
            <div class="col-md-4 ps-3">
                <h4>Departamento</h4>
                <select type="text" name="departamento" id="departamento">
                    <option value=" ">Anterior: <?php echo $fila['nombre_rol'] ?></option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['nombre_rol'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    <?php
    }
    ?>


    <?php
    if (mysqli_num_rows($result2) > 0) {
    ?>
        <div class="row">
            <div class="col-md-4 ps-3">
                <h4>Estado</h4>
                <select type="text" name="estado" id="estado">
                    <option value=" ">Anterior: <?php echo $fila['estado'] ?></option>
                    <?php
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                    ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row2['estado'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    <?php
    }
    ?>
    
    <input type="submit" name="submit" class="btn btn-warning m-3" value="Actualizar"> 
</form>