<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('conexion.php');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo = trim($_POST['correo'] ?? '');

    if (empty($correo)) {
        $error = "Introduce tu correo.";
    } else {

        $stmt = $conexion->prepare("SELECT id, nombre FROM usuarios WHERE correo = ? LIMIT 1");

        if ($stmt) {

            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {

                $stmt->bind_result($user_id, $nombre);
                $stmt->fetch();
                $stmt->close();

                // Eliminar tokens anteriores
                $del = $conexion->prepare("DELETE FROM password_resets WHERE user_id = ?");
                $del->bind_param("i", $user_id);
                $del->execute();
                $del->close();

                // Generar token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + 3600);

                $insert = $conexion->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
                $insert->bind_param("iss", $user_id, $token, $expires);
                $insert->execute();
                $insert->close();

                // Crear enlace
                $host = $_SERVER['HTTP_HOST'];
                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $link = $scheme . '://' . $host . dirname($_SERVER['PHP_SELF']) . '/cambiar_password.php?token=' . urlencode($token);

                $message = "Si el correo existe, se envió el enlace de recuperación.";
            } else {
                $error = "No existe una cuenta con ese correo.";
                $stmt->close();
            }
        } else {
            $error = "Error en la base de datos.";
        }
    }
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - AngyGo</title>

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
        <h2>Recuperar contraseña</h2>

        <?php if ($message): ?>
            <div class="success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Correo electrónico</label>
            <input type="email" name="correo" required>
            <button type="submit">Enviar enlace</button>
        </form>

        <a href="login.php">Volver al login</a>
    </div>

</body>

</html>