<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('../config/conexion.php');
require_once('../config/mailer.php');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo = trim($_POST['correo'] ?? '');

    if (empty($correo)) {
        $error = "Introduce tu correo.";
    } else {

        $stmt = $conexion->prepare("SELECT id, nombre FROM usuarios WHERE correo = ? LIMIT 1");
        $stmt->execute([$correo]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {

            $user_id = $usuario['id'];
            $nombre  = $usuario['nombre'];

            // Eliminar tokens anteriores
            $del = $conexion->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $del->execute([$user_id]);

            // Generar token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600);

            $insert = $conexion->prepare(
                "INSERT INTO password_resets (user_id, token, expires_at) 
                 VALUES (?, ?, ?)"
            );
            $insert->execute([$user_id, $token, $expires]);

            // Crear enlace
            $host = $_SERVER['HTTP_HOST'];
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $link = $scheme . '://' . $host . dirname($_SERVER['PHP_SELF']) . '/cambiar_password.php?token=' . urlencode($token);

            // Enviar correo
            if (enviar_email_recuperacion($correo, $nombre, $link)) {
                $message = "✓ Se ha enviado un enlace de recuperación a tu correo.";
            } else {
                error_log("Error enviando email de recuperación a: $correo");
                $message = "⚠️ Se generó el token pero hubo un error al enviar el email.";
            }
        } else {
            $error = "No existe una cuenta con ese correo.";
        }
    }
}

$conexion = null; // cerrar PDO
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