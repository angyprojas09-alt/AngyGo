<?php
require_once('../config/conexion.php');

$token = $_GET['token'] ?? '';
if (trim($token) === '') {
    $error = 'Token inválido.';
} else {
    $stmt = $conexion->prepare("SELECT id, user_id, expires_at, used FROM email_confirmations WHERE token = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            if ((int)$row['used'] === 1) {
                $error = 'Este token ya fue utilizado.';
            } elseif (strtotime($row['expires_at']) < time()) {
                $error = 'El token expiró.';
            } else {
                // Marcar como usado
                $u = $conexion->prepare("UPDATE email_confirmations SET used = 1 WHERE id = ?");
                if ($u) {
                    $u->bind_param('i', $row['id']);
                    $u->execute();
                    $u->close();
                }

                // Asegurar columna email_confirmado en usuarios
                $colRes = $conexion->query("SHOW COLUMNS FROM usuarios LIKE 'email_confirmado'");
                if ($colRes && $colRes->num_rows === 0) {
                    $conexion->query("ALTER TABLE usuarios ADD COLUMN email_confirmado TINYINT(1) NOT NULL DEFAULT 0");
                }

                // Marcar usuario como confirmado
                $upd = $conexion->prepare("UPDATE usuarios SET email_confirmado = 1 WHERE id = ?");
                if ($upd) {
                    $upd->bind_param('i', $row['user_id']);
                    $upd->execute();
                    $upd->close();
                }

                $success = true;
            }
        } else {
            $error = 'Token no encontrado.';
        }
        $stmt->close();
    } else {
        $error = 'Error en la consulta.';
    }
}

$conexion->close();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Confirmación de correo - AngyGo</title>
    <style>
        body {
            font-family: Poppins, Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #22c55e, #0f172a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0
        }

        .card {
            background: #fff;
            padding: 28px;
            border-radius: 12px;
            max-width: 520px;
            width: 96%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            text-align: center
        }

        .success {
            color: #166534;
            background: #dcfce7;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px
        }

        .error {
            color: #9f1239;
            background: #ffe4e6;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px
        }

        a.button {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            background: #62c5f3;
            color: #fff;
            text-decoration: none;
            font-weight: 700
        }
    </style>
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