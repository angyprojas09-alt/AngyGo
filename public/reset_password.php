<?php
require_once("../config/conexion.php");

$message = '';
$error = '';
$show_reset_form = true;
$reset_link = '';
$token = trim($_GET['token'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ===========================
       FORMULARIO 1: SOLICITAR CORREO
    ============================ */
    if (isset($_POST['email_form'])) {

        $correo = trim($_POST['correo'] ?? '');

        if (empty($correo)) {
            $error = "Introduce tu correo.";
        } else {

            $stmt = $conexion->prepare("SELECT id, nombre FROM usuarios WHERE correo = ? LIMIT 1");
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {

                $user_id = $usuario['id'];

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
                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/reset_password.php?token=" . urlencode($token);

                $message = "✅ <strong>Enlace de recuperación generado:</strong><br><br>" .
                    "<code style='background: #f0f0f0; padding: 10px; border-radius: 5px; display: block; word-break: break-all; margin: 10px 0;'>" .
                    htmlspecialchars($reset_link) .
                    "</code><br><a href='" . htmlspecialchars($reset_link) . "' style='display: inline-block; margin-top: 10px; padding: 10px 20px; background: #16a34a; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;'>Cambiar Contraseña</a>";

                $show_reset_form = false;
            } else {
                $error = "No encontramos una cuenta con ese correo.";
            }
        }
    }

    /* ===========================
       FORMULARIO 2: CAMBIAR PASSWORD
    ============================ */ elseif (isset($_POST['reset_form'])) {

        $token = trim($_POST['token'] ?? '');
        $password_new = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($token) || empty($password_new) || empty($password_confirm)) {
            $error = "Completa todos los campos.";
        } elseif ($password_new !== $password_confirm) {
            $error = "Las contraseñas no coinciden.";
        } elseif (strlen($password_new) < 6) {
            $error = "La contraseña debe tener al menos 6 caracteres.";
        } else {

            $check = $conexion->prepare(
                "SELECT user_id 
                 FROM password_resets 
                 WHERE token = ? AND expires_at > NOW() AND used = 0 
                 LIMIT 1"
            );

            $check->execute([$token]);
            $data = $check->fetch(PDO::FETCH_ASSOC);

            if ($data) {

                $user_id = $data['user_id'];

                $password_hashed = password_hash($password_new, PASSWORD_DEFAULT);

                // Actualizar contraseña
                $update = $conexion->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                $update->execute([$password_hashed, $user_id]);

                // Marcar token como usado
                $mark_used = $conexion->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
                $mark_used->execute([$token]);

                $message = "✅ Contraseña actualizada correctamente. Ya puedes <a href='login.php' style='color: #16a34a; font-weight: 600;'>iniciar sesión</a>.";
                $show_reset_form = false;
            } else {
                $error = "❌ Token inválido o expirado.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - AngyGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0b3d2e, #14532d);
        }

        .reset-container {
            background: #f8fafc;
            padding: 45px 40px;
            width: 400px;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeInUp 0.6s ease;
        }

        .titulo {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #1f2937;
        }

        .titulo span {
            color: #16a34a;
        }

        .input-group {
            margin-bottom: 22px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            background: #f9fafb;
        }

        .input-group input:focus {
            border-color: #16a34a;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
        }

        .password-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-group input {
            flex: 1;
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            padding: 5px 8px;
            border-radius: 5px;
            transition: 0.2s ease;
            top: 50%;
            transform: translateY(-50%);
        }

        .toggle-password:hover {
            background: #e5e7eb;
            color: #16a34a;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .error {
            background: #ffe0e0;
            color: #c0392b;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .mensaje {
            background: #dcf8c6;
            color: #0a5f0a;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.6;
        }

        .mensaje code {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            display: block;
            word-break: break-all;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }

        .mensaje a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #16a34a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .mensaje a:hover {
            background: #15803d;
            transform: translateY(-2px);
        }

        .back-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .back-link a {
            text-decoration: none;
            color: #16a34a;
            font-weight: 600;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="reset-container">

        <h2 class="titulo">Recuperar <span>Contraseña</span></h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="mensaje"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($show_reset_form && !$token): ?>
            <!-- FORMULARIO 1: Solicitar correo -->
            <form method="POST">
                <div class="input-group">
                    <label>Correo electrónico registrado</label>
                    <input type="email" name="correo" required>
                </div>
                <button type="submit" name="email_form" class="btn">Enviar Enlace</button>
            </form>

        <?php elseif ($show_reset_form && $token): ?>
            <!-- FORMULARIO 2: Nueva contraseña -->
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div class="input-group">
                    <label>Nueva contraseña</label>
                    <div class="password-group">
                        <input type="password" name="password" id="password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">Mostrar</button>
                    </div>
                </div>

                <div class="input-group">
                    <label>Confirmar contraseña</label>
                    <div class="password-group">
                        <input type="password" name="password_confirm" id="password_confirm" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password_confirm')">Mostrar</button>
                    </div>
                </div>

                <button type="submit" name="reset_form" class="btn">Actualizar Contraseña</button>
            </form>

        <?php endif; ?>

        <div class="back-link">
            <a href="login.php">Volver al login</a>
        </div>

    </div>

    <script>
        function togglePassword(inputId) {
            const inputField = document.getElementById(inputId);
            const button = inputField.nextElementSibling;

            if (inputField.type === 'password') {
                inputField.type = 'text';
                button.textContent = 'Ocultar';
                button.style.color = '#16a34a';
            } else {
                inputField.type = 'password';
                button.textContent = 'Mostrar';
                button.style.color = '#6b7280';
            }
        }
    </script>

</body>

</html>