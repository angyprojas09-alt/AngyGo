<?php
require_once('../config/conexion.php');
require_once('../config/mailer.php');

// Recibe 'correo' por POST o GET
$correo = trim($_POST['correo'] ?? $_GET['correo'] ?? '');
if ($correo === '') {
    header('Location: ../public/registro.php?token_error=1');
    exit();
}

$stmt = $conexion->prepare("SELECT id, nombre FROM usuarios WHERE correo = ? LIMIT 1");
if (!$stmt) {
    header('Location: ../public/registro.php?token_error=2');
    exit();
}
$stmt->bind_param('s', $correo);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    $stmt->close();
    header('Location: ../public/registro.php?token_error=3');
    exit();
}
$user = $res->fetch_assoc();
$user_id = (int)$user['id'];
$nombre = $user['nombre'];
$stmt->close();

// Crear tabla de confirmaciones si no existe
$conexion->query(
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

$ins = $conexion->prepare("INSERT INTO email_confirmations (user_id, token, expires_at) VALUES (?, ?, ?)");
if (!$ins) {
    header('Location: registro.php?token_error=4');
    exit();
}
$ins->bind_param('iss', $user_id, $token, $expires);
$ins->execute();
$ins->close();

// Enviar correo
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$link = $scheme . '://' . $host . dirname($_SERVER['PHP_SELF']) . '/confirmar_email.php?token=' . urlencode($token);

$to = $correo;
$subject = 'Confirma tu correo en AngyGo - ¡Bienvenido!';

// Email HTML bonito con botón
$message = "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #1e7e34 0%, #228c3d 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
        .button { display: inline-block; background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        .footer { background: #f0f0f0; padding: 20px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 8px 8px; }
    </style>
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
