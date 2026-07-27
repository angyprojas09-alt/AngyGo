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
        $error = 'Error: ' . ($validacion_email['mensaje'] ?? 'No se pudo validar el correo.');
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

                    // Crear tabla de confirmaciones si no existe
                    $conexion->exec(
                        "CREATE TABLE IF NOT EXISTS email_confirmations (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            user_id INT NOT NULL,
                            token VARCHAR(128) NOT NULL,
                            expires_at DATETIME NOT NULL,
                            used TINYINT(1) DEFAULT 0,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
                    );

                    // Guardar token en BD con fecha de expiración
                    $expires_at = date('Y-m-d H:i:s', time() + 86400);
                    $query_token = $conexion->prepare("INSERT INTO email_confirmations (user_id, token, expires_at, used, created_at) VALUES (?, ?, ?, 0, NOW())");
                    if ($query_token) {
                        $query_token->execute([$user_id, $token, $expires_at]);
                    }

                    // Enviar email de confirmación
                    $enlace = build_app_url('controllers/confirmar_email.php?token=' . urlencode($token));

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
    <link rel="stylesheet" href="css/registro.css">
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