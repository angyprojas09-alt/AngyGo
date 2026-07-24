<?php
require_once('../config/conexion.php');

$token = $_GET['token'] ?? '';

if (trim($token) === '') {
    $error = 'Token inválido.';
} else {
    var_dump($conexion);
    exit;
    $stmt = $conexion->prepare("SELECT id, user_id, expires_at, used FROM email_confirmations WHERE token = ? LIMIT 1");

    if ($stmt) {

        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {

            if ((int)$row['used'] === 1) {
                $error = 'Este token ya fue utilizado.';
            } elseif (strtotime($row['expires_at']) < time()) {
                $error = 'El token expiró.';
            } else {

                // Marcar como usado
                $u = $conexion->prepare("UPDATE email_confirmations SET used = 1 WHERE id = ?");
                if ($u) {
                    $u->execute([$row['id']]);
                }

                // Asegurar columna email_confirmado en usuarios
                $colRes = $conexion->query("SHOW COLUMNS FROM usuarios LIKE 'email_confirmado'");
                $col = $colRes->fetch();

                if (!$col) {
                    $conexion->query("ALTER TABLE usuarios ADD COLUMN email_confirmado TINYINT(1) NOT NULL DEFAULT 0");
                }

                // Marcar usuario como confirmado
                $upd = $conexion->prepare("UPDATE usuarios SET email_confirmado = 1 WHERE id = ?");
                if ($upd) {
                    $upd->execute([$row['user_id']]);
                }

                $success = true;
            }
        } else {
            $error = 'Token no encontrado.';
        }
    } else {
        $error = 'Error en la consulta.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Confirmación de correo - AngyGo</title>
    <link rel="stylesheet" href="css/confirmar_email.css">
</head>

<body>
    <div class="card">
        <h2>Confirmación de correo</h2>
        <?php if (!empty($success)): ?>
            <div class="success">✅ Tu correo ha sido confirmado correctamente.</div>
            <a class="button" href="login.php">Ir al login</a>
        <?php else: ?>
            <div class="error">❌ <?php echo htmlspecialchars($error ?? 'Ocurrió un error.'); ?></div>
            <a class="button" href="registro.php">Volver al registro</a>
        <?php endif; ?>
    </div>
</body>

</html>