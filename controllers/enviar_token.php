<?php
require_once('../config/conexion.php');
require_once('../config/mailer.php');

if (!function_exists('build_app_url')) {
    function build_app_url($path)
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $script_dir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');

        $base_path = '';
        if ($script_dir && $script_dir !== '/') {
            $base_path = str_replace('/public', '', $script_dir);
            $base_path = str_replace('/controllers', '', $base_path);
        }

        return $scheme . '://' . $host . $base_path . '/' . ltrim($path, '/');
    }
}

// Recibe 'correo' por POST o GET
$correo = trim($_POST['correo'] ?? $_GET['correo'] ?? '');
if ($correo === '') {
    header('Location: ../public/registro.php?token_error=1');
    exit();
}

// ✅ PDO correcto
$stmt = $conexion->prepare("SELECT id, nombre FROM usuarios WHERE correo = ? LIMIT 1");

if (!$stmt) {
    header('Location: ../public/registro.php?token_error=2');
    exit();
}

$stmt->execute([$correo]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: ../public/registro.php?token_error=3');
    exit();
}

$user_id = (int)$user['id'];
$nombre = $user['nombre'];

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

// Generar token
try {
    $token = bin2hex(random_bytes(16));
} catch (Exception $e) {
    $token = sha1(uniqid((string)mt_rand(), true));
}

$expires = date('Y-m-d H:i:s', time() + 86400); // 24h

// ✅ PDO correcto
$ins = $conexion->prepare("INSERT INTO email_confirmations (user_id, token, expires_at) VALUES (?, ?, ?)");

if (!$ins) {
    header('Location: registro.php?token_error=4');
    exit();
}

$ins->execute([$user_id, $token, $expires]);

// Enviar correo
$link = build_app_url('controllers/confirmar_email.php?token=' . urlencode($token));

$to = $correo;
$subject = 'Confirma tu correo en AngyGo - ¡Bienvenido!';

// Email HTML bonito con botón
$message = "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <link rel='stylesheet' href='css/email_confirmacion_controlador.css'>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🚴 AngyGo - Confirma tu Correo</h1>
        </div>
        <div class='content'>
            <p>¡Hola <strong>{$nombre}</strong>!</p>
            <p>Gracias por registrarte en <strong>AngyGo</strong>. Para completar tu registro, por favor confirma tu correo haciendo clic en el botón siguiente:</p>
            <center>
                <a href='{$link}' class='button'>✓ Confirmar Correo</a>
            </center>
            <p style='color: #666; font-size: 12px;'>O copia este enlace en tu navegador:<br><code>{$link}</code></p>
            <p style='margin-top: 20px; color: #999; font-size: 12px;'>Este enlace es válido por 24 horas. Si no solicitaste este registro, ignora este correo.</p>
        </div>
        <div class='footer'>
            <p>© 2024 AngyGo - Servicio de Domicilios</p>
        </div>
    </div>
</body>
</html>";

// Enviar correo usando la nueva función de mailer
if (enviar_email($to, $subject, $message)) {
    header('Location: ../public/registro.php?token_enviado=1');
    exit();
} else {
    // Si hay error al enviar
    error_log("Error enviando correo de confirmación a: $to");
    header('Location: ../public/registro.php?token_error=5');
    exit();
}
