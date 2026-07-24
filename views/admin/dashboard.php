<?php
session_start();
require_once("../../config/conexion.php");

// 🔐 Verificar si hay sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../public/login.php");
    exit();
}

$nombre = $_SESSION["usuario"];

// Obtener datos completos del usuario
$stmt = $conexion->prepare("SELECT correo FROM usuarios WHERE nombre = ?");
$stmt->execute([$nombre]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - AngyGo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

    <div class="card">
        <h1>Bienvenida a AngyGo 💚</h1>

        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario["correo"]); ?></p>

        <a href="../../public/index.php" class="button home">Ir al Inicio</a>
        <a href="../../controllers/logout.php" class="button logout">Cerrar Sesión</a>
    </div>

</body>

</html>