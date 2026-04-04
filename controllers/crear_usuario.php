<?php
session_start();
require_once("../config/conexion.php");

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $rol = $_POST["rol"];
    var_dump($conexion);
    exit;
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $correo, $password, $rol);
    $stmt->execute();

    header("Location: ../views/admin/ver_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Crear Usuario</title>
</head>

<body style="font-family:Arial; text-align:center; margin-top:50px;">

    <h2>Crear Usuario</h2>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required><br><br>
        <input type="email" name="correo" placeholder="Correo" required><br><br>
        <input type="password" name="password" placeholder="Contraseña" required><br><br>

        <select name="rol" required>
            <option value="cliente">Cliente</option>
            <option value="admin">Admin</option>
            <option value="domiciliario">Domiciliario</option>
        </select><br><br>

        <button type="submit">Guardar</button>
    </form>

</body>

</html>