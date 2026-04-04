<?php
require_once("../config/conexion.php");
require_once("../config/mailer.php");

$mensaje = "";
$error = "";
$nombre = '';
$correo = '';

// Verificar si viene un error o confirmación de token enviado
if (isset($_GET['token_enviado']) && $_GET['token_enviado'] == 1) {
    $mensaje = '✓ Registro exitoso. Se ha enviado un enlace de confirmación a tu correo. Revisa tu bandeja de entrada (o spam) para confirmar tu cuenta.';
} elseif (isset($_GET['token_error'])) {
    $error_code = $_GET['token_error'];
    $errores = array(
        1 => 'No se especificó correo.',
        2 => 'Error en la base de datos.',
        3 => 'Usuario no encontrado.',
        4 => 'Error al crear token de confirmación.',
        5 => 'Error al enviar correo de confirmación. Por favor intenta más tarde.'
    );
    $error = isset($errores[$error_code]) ? $errores[$error_code] : 'Error desconocido.';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $password_raw = $_POST["password"] ?? '';
    $rol = $_POST["rol"] ?? '';

    // Validar campos vacíos
    if ($nombre === '' || $correo === '' || $password_raw === '' || $rol === '') {
        $error = 'Completa todos los campos.';
    }
    // Validar formato de correo
    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Correo electrónico no válido.';
    }
    // Validar que el email sea real (no temporal)
    elseif (!($validacion_email = validar_email_real($correo))['valido']) {
        $error = 'Error: ' . $validacion_email['error'];
    }
    // Validar contraseña mínima
    elseif (strlen($password_raw) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {

        // Verificar si ya existe el correo
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");

        if ($check) {

            $check->execute([$correo]);
            $res = $check->fetchAll();

            if ($res && count($res) > 0) {
                $error = 'Ya existe una cuenta con ese correo.';
            }
        } else {
            $error = 'Error en la verificación de correo.';
        }

        // Si no hay error, registrar
        if ($error === '') {

            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)");

            if ($stmt) {

                if ($stmt->execute([$nombre, $correo, $password, $rol])) {

                    // Obtener ID del usuario registrado
                    $user_id = $conexion->lastInsertId();

                    // Generar token de confirmación
                    $token = bin2hex(random_bytes(32));

                    // Guardar token en BD
                    $query_token = $conexion->prepare("INSERT INTO email_confirmations (user_id, token, created_at) VALUES (?, ?, NOW())");
                    if ($query_token) {
                        $query_token->execute([$user_id, $token]);
                    }

                    // Enviar email de confirmación
                    $enlace = "http://localhost:85/AngyGo/public/confirmar_email.php?token=" . $token;

                    if (enviar_email_confirmacion($correo, $nombre, $enlace)) {
                        echo "<script>alert('¡Cuenta creada exitosamente! Por favor revisa tu correo para confirmarla.'); window.location.href='login.php';</script>";
                        exit();
                    } else {
                        $error = 'Usuario creado pero no se pudo enviar email de confirmación. Intenta recuperar tu contraseña.';
                    }
                } else {
                    $error = 'Error al guardar los datos.';
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro AngyGo</title>
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

        .registro-container {
            background: #f8fafc;
            padding: 45px 40px;
            width: 400px;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeInUp 0.6s ease;
        }

        .titulo-registro {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #1f2937;
            position: relative;
        }

        .titulo-registro span {
            color: #16a34a;
        }

        .titulo-registro::after {
            content: "";
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            display: block;
            margin: 12px auto 0;
            border-radius: 6px;
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

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #16a34a;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
            transform: translateY(-1px);
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
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .mensaje {
            background: #dcf8c6;
            color: #0a5f0a;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            text-decoration: none;
            color: #16a34a;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
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

        /* PASSWORD TOGGLE */
        .password-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 38px;
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            background: #f0fdf4;
            color: #16a34a;
        }

        .toggle-password.visible {
            color: #16a34a;
        }
    </style>
</head>

<body>

    <div class="registro-container">

        <h2 class="titulo-registro">
            Crear Cuenta <span>AngyGo</span>
        </h2>

        <?php if ($error != ""): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div>

            <div class="input-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required>
            </div>

            <div class="input-group password-group">
                <label>Contraseña (mínimo 6 caracteres)</label>
                <input type="password" name="password" id="passwordRegistro" required>
                <button type="button" class="toggle-password" id="togglePasswordRegistro">Mostrar</button>
            </div>

            <!-- NUEVO CAMPO ROL -->
            <div class="input-group">
                <label>Rol</label>
                <select name="rol" required>
                    <option value="">Seleccionar rol</option>
                    <option value="cliente">Cliente</option>
                    <option value="domiciliario">Domiciliario</option>
                </select>
            </div>

            <button type="submit" class="btn">Crear Cuenta</button>
        </form>

        <script>
            const passwordInput = document.getElementById('passwordRegistro');
            const toggleBtn = document.getElementById('togglePasswordRegistro');

            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleBtn.textContent = 'Ocultar';
                    toggleBtn.classList.add('visible');
                } else {
                    passwordInput.type = 'password';
                    toggleBtn.textContent = 'Mostrar';
                    toggleBtn.classList.remove('visible');
                }
                passwordInput.focus();
            });
        </script>

        <div class="login-link">
            ¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a>
        </div>

    </div>

</body>

</html>