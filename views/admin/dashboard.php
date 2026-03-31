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
$stmt->bind_param("s", $nombre);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - AngyGo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0b3d2e, #14532d);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #f8fafc;
            padding: 40px;
            border-radius: 20px;
            width: 95%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        h1 {
            margin-bottom: 15px;
        }

        p {
            margin: 8px 0;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .home {
            background: #16a34a;
            color: white;
            margin-right: 10px;
        }

        .home:hover {
            background: #15803d;
        }

        .logout {
            background: #ef4444;
            color: white;
        }

        .logout:hover {
            background: #dc2626;
        }
    </style>
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