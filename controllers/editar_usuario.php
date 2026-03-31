<?php
session_start();
require_once("../config/conexion.php");

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: login.php");
    exit();
}

$id = $_GET["id"];

$stmt = $conexion->prepare("SELECT nombre, correo, rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $rol = $_POST["rol"];

    $stmt = $conexion->prepare("UPDATE usuarios SET nombre=?, correo=?, rol=? WHERE id=?");
    $stmt->bind_param("sssi", $nombre, $correo, $rol, $id);
    $stmt->execute();

    header("Location: ver_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Usuario</title>
</head>

<body style="font-family:Arial; text-align:center; margin-top:50px;">

    <h2>Editar Usuario</h2>

    <form method="POST">
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario["nombre"]) ?>" required><br><br>
        <input type="email" name="correo" value="<?= htmlspecialchars($usuario["correo"]) ?>" required><br><br>

        <select name="rol" required>
            <option value="cliente" <?= $usuario["rol"] == "cliente" ? "selected" : "" ?>>Cliente</option>
            <option value="admin" <?= $usuario["rol"] == "admin" ? "selected" : "" ?>>Admin</option>
            <option value="domiciliario" <?= $usuario["rol"] == "domiciliario" ? "selected" : "" ?>>Domiciliario</option>
        </select><br><br>

        <button type="submit">Actualizar</button>
    </form>

</body>

</html>