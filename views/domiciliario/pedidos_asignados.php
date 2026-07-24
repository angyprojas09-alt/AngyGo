<?php
session_start();
require_once("../../config/conexion.php");

$domiciliario_id = $_SESSION["usuario_id"] ?? 0;

/* ===========================
   CAMBIAR ESTADO (PDO)
=========================== */
if (isset($_POST["cambiar_estado"])) {

    $pedido_id = $_POST["pedido_id"];
    $nuevo_estado = $_POST["nuevo_estado"];

    $update = $conexion->prepare(
        "UPDATE pedidos 
         SET estado = ? 
         WHERE id = ? AND domiciliario_id = ?"
    );

    $update->execute([$nuevo_estado, $pedido_id, $domiciliario_id]);

    header("Location: pedidos_asignados.php");
    exit();
}

/* ===========================
   FILTRO POR ESTADO (PDO)
=========================== */
$filtro_estado = $_GET["estado"] ?? "";

$sql = "SELECT * FROM pedidos WHERE domiciliario_id = ?";
$params = [$domiciliario_id];

if (!empty($filtro_estado)) {
    $sql .= " AND estado = ?";
    $params[] = $filtro_estado;
}

$stmt = $conexion->prepare($sql);
$stmt->execute($params);

// 🔥 IMPORTANTE: en PDO no existe get_result()
$resultado = $stmt;
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pedidos Asignados</title>
    <link rel="stylesheet" href="css/pedidos_asignados.css">

</head>

<body>

    <h2>🚚 Mis Pedidos Asignados</h2>

    <!-- BOTÓN VOLVER AL PANEL -->
    <a href="panel_domiciliario.php">
        <button class="back-btn">
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

    <?php if ($resultado && $resultado->rowCount() > 0): ?>

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

            <?php while ($pedido = $resultado->fetch(PDO::FETCH_ASSOC)):

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