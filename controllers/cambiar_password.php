<?php
session_start();
require_once('../config/conexion.php');

$error = '';
$success = '';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');

if ($token === '') {
    $error = "Token inválido.";
} else {
    var_dump($conexion);
    exit;
    $stmt = $conexion->prepare("SELECT id, user_id, expires_at, used FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        $error = "Token no encontrado.";
    } else {

        $row = $res->fetch_assoc();

        if ((int)$row['used'] === 1) {
            $error = "El token ya fue utilizado.";
        } elseif (strtotime($row['expires_at']) < time()) {
            $error = "El token ha expirado.";
        } else {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $pass1 = $_POST['password'] ?? '';
                $pass2 = $_POST['password_confirm'] ?? '';

                if (strlen($pass1) < 6) {
                    $error = "La contraseña debe tener al menos 6 caracteres.";
                } elseif ($pass1 !== $pass2) {
                    $error = "Las contraseñas no coinciden.";
                } else {

                    $hash = password_hash($pass1, PASSWORD_DEFAULT);

                    $up = $conexion->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                    $up->bind_param("si", $hash, $row['user_id']);

                    if ($up->execute()) {

                        $mark = $conexion->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
                        $mark->bind_param("i", $row['id']);
                        $mark->execute();
                        $mark->close();

                        $success = "Contraseña actualizada correctamente.";
                    } else {
                        $error = "No se pudo actualizar la contraseña.";
                    }

                    $up->close();
                }
            }
        }
    }

    $stmt->close();
}
var_dump($conexion);
exit;
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña - AngyGo</title>
    <link rel="stylesheet" href="css/cambiar_password.css">
</head>

<body>

    <div class="card">
        <h2>Cambiar contraseña</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <a href="login.php">Ir al login</a>
        <?php elseif ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <a href="login.php">Volver al login</a>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <label>Nueva contraseña</label>
                <input type="password" name="password" required>

                <label>Confirmar contraseña</label>
                <input type="password" name="password_confirm" required>

                <button type="submit">Cambiar contraseña</button>
            </form>
        <?php endif; ?>

    </div>

</body>

</html>