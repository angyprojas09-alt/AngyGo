<?php
session_start();
require_once('../config/conexion.php');

$error = '';
$success = '';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');

if ($token === '') {
    $error = "Token inválido.";
} else {

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

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña - AngyGo</title>

    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #22c55e, #0f172a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .card {
            background: #fff5f2;
            padding: 30px;
            border-radius: 16px;
            width: 95%;
            max-width: 420px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            margin-top: 15px;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            border: none;
            background: #22c55e;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #16a34a;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .error {
            background: #ffe4e6;
            color: #9f1239;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #0f172a;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
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