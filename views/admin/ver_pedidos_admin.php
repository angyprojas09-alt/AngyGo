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
// ASIGNAR DOMICILIARIO
// ===============================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["asignar"])) {

    if (!empty($_POST["pedido_id"]) && !empty($_POST["domiciliario_id"])) {

        $pedido_id = intval($_POST["pedido_id"]);
        $domiciliario_id = intval($_POST["domiciliario_id"]);

        if ($domiciliario_id > 0) {

            $stmt = $conexion->prepare("UPDATE pedidos SET domiciliario_id = ? WHERE id = ?");

            if ($stmt) {
                $stmt->execute([$domiciliario_id, $pedido_id]);
            }

            header("Location: ./ver_pedidos_admin.php");
            exit();
        }
    }
}

$sql_pedidos = "
    SELECT 
        p.id,
        p.nombre AS cliente_nombre,
        p.producto,
        p.domiciliario_id,
        u.nombre AS nombre_domiciliario
    FROM pedidos p
    LEFT JOIN usuarios u ON p.domiciliario_id = u.id
    ORDER BY p.id DESC
";

$pedidos = $conexion->query($sql_pedidos);

$sql_domiciliarios = "
    SELECT id, nombre 
    FROM usuarios 
    WHERE rol = 'domiciliario'
";

$domiciliarios = $conexion->query($sql_domiciliarios);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="css/ver_pedidos_admin.css">
</head>

<body>

    <div class="container">

        <h2>📦 Gestión de Pedidos</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Estado</th>
                <th>Asignar</th>
            </tr>

            <?php while ($pedido = $pedidos->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $pedido["id"]; ?></td>
                    <td><?php echo htmlspecialchars($pedido["cliente_nombre"]); ?></td>
                    <td><?php echo htmlspecialchars($pedido["producto"]); ?></td>

                    <td>
                        <?php if (!empty($pedido["nombre_domiciliario"])): ?>
                            <span class="badge badge-asignado">
                                <?php echo htmlspecialchars($pedido["nombre_domiciliario"]); ?>
                            </span>
                        <?php else: ?>
                            <span class="badge badge-pendiente">Sin asignar</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <form method="POST" action="ver_pedidos_admin.php">
                            <input type="hidden" name="pedido_id" value="<?php echo $pedido["id"]; ?>">

                            <select name="domiciliario_id" required>
                                <option value="">Seleccionar</option>

                                <?php
                                // 🔥 SOLUCIÓN PDO: traer todos los domiciliarios una sola vez
                                if (!isset($domiciliarios_lista)) {
                                    $domiciliarios_lista = $domiciliarios->fetchAll(PDO::FETCH_ASSOC);
                                }

                                foreach ($domiciliarios_lista as $dom):
                                ?>
                                    <option value="<?php echo $dom["id"]; ?>"
                                        <?php if ($pedido["domiciliario_id"] == $dom["id"]) echo "selected"; ?>>
                                        <?php echo htmlspecialchars($dom["nombre"]); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" name="asignar">Asignar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

            <button type="submit" name="asignar">Asignar</button>
            </form>
            </td>
            </tr>

        </table>

        <a class="volver" href="panel_admin.php">⬅ Volver al panel</a>

    </div>

</body>

</html>