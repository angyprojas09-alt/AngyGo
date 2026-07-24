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
    <link rel="stylesheet" href="css/panel_domiciliario.css">
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