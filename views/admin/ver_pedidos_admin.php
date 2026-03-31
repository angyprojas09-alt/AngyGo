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
            $stmt->bind_param("ii", $domiciliario_id, $pedido_id);
            $stmt->execute();
            $stmt->close();

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

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            margin: 0;
            padding: 40px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2a5298;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f2f2f2;
        }

        select {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background: #2a5298;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1e3c72;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
        }

        .badge-asignado {
            background: #28a745;
        }

        .badge-pendiente {
            background: #dc3545;
        }

        .volver {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #444;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
        }

        .volver:hover {
            background: #222;
        }
    </style>
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

            <?php while ($pedido = $pedidos->fetch_assoc()): ?>
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
                                $domiciliarios->data_seek(0);
                                while ($dom = $domiciliarios->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $dom["id"]; ?>"
                                        <?php if ($pedido["domiciliario_id"] == $dom["id"]) echo "selected"; ?>>
                                        <?php echo htmlspecialchars($dom["nombre"]); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>

                            <button type="submit" name="asignar">Asignar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>

        <a class="volver" href="panel_admin.php">⬅ Volver al panel</a>

    </div>

</body>

</html>