<?php
session_start();
// Siempre usa ../ para salir de la carpeta vistas
require_once("../config/conexion.php"); 

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['cambiar_rol'])) {
    $id = $_POST['id'];
    $nuevo_rol = $_POST['rol'];

    $stmt = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
    $stmt->execute([$nuevo_rol, $id]);
}


if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}

/* FILTRO */
$filtro = "";
if (isset($_GET['rol']) && $_GET['rol'] != "") {
    $rol = $_GET['rol'];
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE rol = ?");
    $stmt->execute([$rol]);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $resultado = $conexion->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #667eea;
            color: white;
            padding: 12px;
        }

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f2f2f2;
        }

        button {
            padding: 6px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-guardar {
            background: #28a745;
            color: white;
        }

        .btn-eliminar {
            background: #dc3545;
            color: white;
        }

        select {
            padding: 5px;
            border-radius: 6px;
        }

        .filtro {
            margin-bottom: 15px;
            text-align: right;
        }

        .btn-filtrar {
            background: #007bff;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>👥 Gestionar Usuarios</h2>

        <div class="filtro">
            <form method="GET">
                <select name="rol">
                    <option value="">Todos</option>
                    <option value="admin">Admin</option>
                    <option value="cliente">Cliente</option>
                    <option value="domiciliario">Domiciliario</option>
                </select>
                <button class="btn-filtrar">Filtrar</button>
            </form>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>

            <?php foreach ($resultado as $fila) { ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= $fila['nombre'] ?></td>
                    <td><?= $fila['correo'] ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:5px; justify-content:center;">
                            <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                            <select name="rol">
                                <option value="admin" <?= $fila['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="cliente" <?= $fila['rol'] == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                                <option value="domiciliario" <?= $fila['rol'] == 'domiciliario' ? 'selected' : '' ?>>Domiciliario</option>
                            </select>
                            <button type="submit" name="cambiar_rol" class="btn-guardar">Guardar</button>
                        </form>
                    </td>
                    <td><?= $fila['fecha_registro'] ?></td>
                    <td>
                        <a href="?eliminar=<?= $fila['id'] ?>"
                            onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                            <button class="btn-eliminar">Eliminar</button>
                        </a>
                    </td>
                </tr>
            <?php } ?>

        </table>
    </div>

</body>

</html>