<?php
session_start();
require_once("conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["rol"] !== "admin") {
    header("Location: index.php");
    exit();
}

// ===============================
// ESTADÍSTICAS
// ===============================

// Total pedidos
$total_pedidos = $conexion->query("SELECT COUNT(*) AS total FROM pedidos")
    ->fetch_assoc()["total"];

// Pedidos asignados
$asignados = $conexion->query("SELECT COUNT(*) AS total FROM pedidos WHERE domiciliario_id IS NOT NULL")
    ->fetch_assoc()["total"];

// Pedidos pendientes
$pendientes = $conexion->query("SELECT COUNT(*) AS total FROM pedidos WHERE domiciliario_id IS NULL")
    ->fetch_assoc()["total"];

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

    <style>
        body {
            font-family: Arial;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            margin: 0;
            padding: 40px;
            color: white;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .card {
            background: white;
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 20px;
        }

        .stat-box {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: white;
        }

        .total {
            background: #007bff;
        }

        .asignados {
            background: #28a745;
        }

        .pendientes {
            background: #dc3545;
        }

        .stat-box h2 {
            margin: 0;
            font-size: 40px;
        }

        .buttons {
            margin-top: 30px;
            text-align: center;
        }

        button,
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #203a43;
            color: white;
        }

        .btn-primary:hover {
            background: #0f2027;
        }

        .btn-danger {
            background: #c0392b;
            color: white;
        }

        .btn-danger:hover {
            background: #922b21;
        }

        .table {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #203a43;
            color: white;
            padding: 10px;
        }

        .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
        }

        .badge-ok {
            background: #28a745;
        }

        .badge-wait {
            background: #dc3545;
        }
    </style>
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
                <a href="gestionar_usuarios.php" class="btn btn-primary">👤 Gestionar Usuarios</a>
                <a href="ver_pedidos_admin.php" class="btn btn-primary">📦 Ver y Asignar Pedidos</a>
                <a href="logout.php" class="btn btn-danger">🚪 Cerrar Sesión</a>
            </div>

            <h3 style="margin-top:40px;">📋 Últimos pedidos</h3>

            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                </tr>

                <?php while ($p = $ultimos->fetch_assoc()): ?>
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