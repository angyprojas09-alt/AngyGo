<?php
session_start();
require_once("../../config/conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../../public/login.php");
    exit();
}

if ($_SESSION["rol"] !== "admin") {
    header("Location: ../../public/index.php");
    exit();
}

// ===============================
// ESTADÍSTICAS
// ===============================

//// Total pedidos
$total_pedidos = $conexion->query("SELECT COUNT(*) AS total FROM pedidos")
    ->fetch(PDO::FETCH_ASSOC)["total"];

// Pedidos asignados
$asignados = $conexion->query("SELECT COUNT(*) AS total FROM pedidos WHERE domiciliario_id IS NOT NULL")
    ->fetch(PDO::FETCH_ASSOC)["total"];

// Pedidos pendientes
$pendientes = $conexion->query("SELECT COUNT(*) AS total FROM pedidos WHERE domiciliario_id IS NULL")
    ->fetch(PDO::FETCH_ASSOC)["total"];

// Últimos 5 pedidos
$ultimos = $conexion->query("
    SELECT p.id, p.nombre, u.nombre AS domiciliario
    FROM pedidos p
    LEFT JOIN usuarios u ON p.domiciliario_id = u.id
    ORDER BY p.id DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Panel Administrador</title>
    <link rel="stylesheet" href="css/panel_admin.css">
</head>

<body>

    <div class="container">

        <div class="card">

            <h1>👑 Panel Administrador</h1>
            <p style="text-align:center;">Bienvenido, <strong><?php echo $_SESSION["nombre"]; ?></strong></p>

            <div class="stats">
                <div class="stat-box total">
                    <h2><?php echo $total_pedidos; ?></h2>
                    Total Pedidos
                </div>

                <div class="stat-box asignados">
                    <h2><?php echo $asignados; ?></h2>
                    Asignados
                </div>

                <div class="stat-box pendientes">
                    <h2><?php echo $pendientes; ?></h2>
                    Pendientes
                </div>
            </div>

            <div class="buttons">
                <a href="./gestionar_usuarios.php" class="btn btn-primary">👤 Gestionar Usuarios</a>
                <a href="./ver_pedidos_admin.php" class="btn btn-primary">📦 Ver y Asignar Pedidos</a>
                <a href="../../controllers/logout.php" class="btn btn-danger">🚪 Cerrar Sesión</a>
            </div>

            <h3 style="margin-top:40px;">📋 Últimos pedidos</h3>

            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                </tr>

                <?php while ($p = $ultimos->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $p["id"]; ?></td>
                        <td><?php echo htmlspecialchars($p["nombre"]); ?></td>
                        <td>
                            <?php if ($p["domiciliario"]): ?>
                                <span class="badge badge-ok">
                                    Asignado a <?php echo htmlspecialchars($p["domiciliario"]); ?>
                                </span>
                            <?php else: ?>
                                <span class="badge badge-wait">Sin asignar</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>

        </div>
    </div>

</body>

</html>