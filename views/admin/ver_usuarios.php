<?php
session_start();
require_once("../../config/conexion.php");

// Protección
if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../../public/login.php");
    exit();
}

// Obtener usuarios
$sql = "SELECT id, nombre, correo, rol FROM usuarios";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Usuarios</title>
    <link rel="stylesheet" href="css/ver_usuarios.css">
</head>

<body>

    <h2 style="text-align:center;">Lista de Usuarios</h2>

    <div style="text-align:center; margin-bottom:20px;">
        <a href="../../controllers/crear_usuario.php" style="background:#27ae60; color:white; padding:10px 20px; border-radius:6px; text-decoration:none;">
            ➕ Crear Usuario
        </a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>

        <?php while ($fila = $resultado->fetch()): ?>
            <tr>
                <td><?= $fila["id"] ?></td>
                <td><?= htmlspecialchars($fila["nombre"]) ?></td>
                <td><?= htmlspecialchars($fila["correo"]) ?></td>
                <td><?= $fila["rol"] ?></td>
                <td>

                    <a href="../../controllers/editar_usuario.php?id=<?= $fila["id"] ?>" class="editar">Editar</a>
                    <a href="../../controllers/eliminar_usuarios.php?id=<?= $fila["id"] ?>" class="eliminar"
                        onclick="return confirm('¿Eliminar este usuario?')">
                        Eliminar
                    </a>

                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>

</html>