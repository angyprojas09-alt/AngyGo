<?php
session_start();
require_once("../../config/conexion.php");

$domiciliario_id = $_SESSION["usuario_id"] ?? 0;

/* ===========================
   CAMBIAR ESTADO (OPCIÓN 1)
=========================== */
if (isset($_POST["cambiar_estado"])) {

    $pedido_id = $_POST["pedido_id"];
    $nuevo_estado = $_POST["nuevo_estado"];

    $update = $conexion->prepare("UPDATE pedidos SET estado = ? WHERE id = ? AND domiciliario_id = ?");
    $update->bind_param("sii", $nuevo_estado, $pedido_id, $domiciliario_id);
    $update->execute();

    // Recargar para evitar reenvío de formulario
    header("Location: pedidos_asignados.php");
    exit();
}

/* ===========================
   FILTRO POR ESTADO
=========================== */
$filtro_estado = $_GET["estado"] ?? "";

$sql = "SELECT * FROM pedidos WHERE domiciliario_id = ?";
$params = [$domiciliario_id];
$types = "i";

if (!empty($filtro_estado)) {
    $sql .= " AND estado = ?";
    $params[] = $filtro_estado;
    $types .= "s";
}

$stmt = $conexion->prepare($sql);

if (count($params) == 2) {
    $stmt->bind_param($types, $params[0], $params[1]);
} else {
    $stmt->bind_param($types, $params[0]);
}

$stmt->execute();
$resultado = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pedidos Asignados</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background: #007bff;
            color: white;
            padding: 10px;
        }

        td {
            padding: 8px;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        button {
            padding: 6px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .entregado {
            background: green;
            color: white;
        }

        .estado-btn {
            background: orange;
            color: white;
        }

        .filtro {
            margin-bottom: 15px;
        }
    </style>

</head>

<body>

    <h2>🚚 Mis Pedidos Asignados</h2>

    <!-- BOTÓN VOLVER AL PANEL -->
    <a href="panel_domiciliario.php">
        <button style="margin-bottom:15px; background:#6c757d; color:white;">
            ⬅ Volver al Panel
        </button>
    </a>

    <!-- FILTRO -->
    <div class="filtro">
        <form method="GET">
            <label>Filtrar por estado:</label>
            <select name="estado">
                <option value="">Todos</option>
                <option value="Pendiente">Pendiente</option>
                <option value="En camino">En camino</option>
                <option value="Entregado">Entregado</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
    </div>

    <?php if ($resultado && $resultado->num_rows > 0): ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php while ($pedido = $resultado->fetch_assoc()):

                $id = $pedido["id"];
                $cliente = $pedido["nombre"] ?? "";
                $direccion = $pedido["direccion"] ?? "";
                $producto = $pedido["producto"] ?? "";
                $cantidad = $pedido["cantidad"] ?? 0;
                $estado = $pedido["estado"] ?? "Pendiente";


            ?>

                <tr>
                    <td><?= htmlspecialchars($id) ?></td>
                    <td><?= htmlspecialchars($cliente) ?></td>
                    <td><?= htmlspecialchars($direccion) ?></td>
                    <td><?= htmlspecialchars($producto) ?></td>
                    <td><?= htmlspecialchars($cantidad) ?></td>
                    <td><?= htmlspecialchars($estado) ?></td>

                    <td>

                        <!-- CAMBIAR ESTADO -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="pedido_id" value="<?= $id ?>">
                            <input type="hidden" name="nuevo_estado" value="En camino">
                            <button type="submit" name="cambiar_estado" class="estado-btn">
                                En camino
                            </button>
                        </form>

                        <!-- MARCAR COMO ENTREGADO -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="pedido_id" value="<?= $id ?>">
                            <input type="hidden" name="nuevo_estado" value="Entregado">
                            <button type="submit" name="cambiar_estado" class="entregado">
                                Entregado
                            </button>
                        </form>

                    </td>
                </tr>

            <?php endwhile; ?>
        </table>

    <?php else: ?>
        <p>No tienes pedidos asignados.</p>
    <?php endif; ?>

</body>

</html>