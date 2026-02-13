<?php
require_once('conexion.php');

// Recibe 'correo' por POST o GET
$correo = trim($_POST['correo'] ?? $_GET['correo'] ?? '');
if ($correo === '') {
    header('Location: registro.php?token_error=1');
    exit();
}

$stmt = $conexion->prepare("SELECT id, nombre FROM usuarios WHERE correo = ? LIMIT 1");
if (!$stmt) {
    header('Location: registro.php?token_error=2');
    exit();
}
$stmt->bind_param('s', $correo);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    $stmt->close();
    header('Location: registro.php?token_error=3');
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
$subject = 'Confirma tu correo en AngyGo';
$message = "Hola {$nombre},\n\nPor favor confirma tu correo haciendo clic en el siguiente enlace:\n\n{$link}\n\nSi no solicitaste esto, ignora este correo.\n\nSaludos,\nAngyGo";

$headers = "From: AngyGo <angygo916@gmail.com>\r\n";
$headers .= "Reply-To: angygo916@gmail.com\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

@mail($to, $subject, $message, $headers);

header('Location: registro.php?token_enviado=1');
exit();
