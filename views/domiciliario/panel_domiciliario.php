<?php
session_start();

// Verificar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../../public/login.php");
    exit();
}

// Verificar rol
if ($_SESSION["rol"] !== "domiciliario") {
    header("Location: ../../public/index.php");
    exit();
}

// Obtener nombre
$usuario = $_SESSION["nombre"] ?? "Domiciliario";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Domiciliario</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
        }

        .container {
            text-align: center;
            margin-top: 120px;
        }

        .card {
            background: white;
            color: #333;
            width: 400px;
            margin: auto;
            padding: 30px;
            border-radius: 15px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background: #2a5298;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .logout {
            background: #c0392b;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <h1>🛵 Panel Domiciliario</h1>

            <p>
                Bienvenido,
                <strong><?php echo htmlspecialchars($usuario); ?></strong>
            </p>

            <a href="./pedidos_asignados.php" class="btn">Ver Pedidos Asignados</a>
            <br>
            <a href="../../controllers/logout.php" class="btn logout">Cerrar Sesión</a>
        </div>
    </div>

</body>

</html>