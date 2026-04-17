<?php
require_once("../config/conexion.php");
require_once("../config/mailer.php");

$message = '';
$error = '';
$show_reset_form = true;
$reset_link = '';
$token = trim($_GET['token'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (isset($_POST['email_form'])) {

        $correo = trim($_POST['correo'] ?? '');

        if (empty($correo)) {
            $error = "introduce tu correo electronico.";
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

                if (enviar_email_recuperacion($correo, $usuario['nombre'], $reset_link)) {
                    $message = "✅ Se ha enviado el enlace de recuperación a tu correo. Revisa tu bandeja de entrada.";
                } else {
                    $message = "⚠️ No se pudo enviar el correo. Aquí tienes el enlace de recuperación:<br><br>" .
                        "<code style='background: #f0f0f0; padding: 10px; border-radius: 5px; display: block; word-break: break-all; margin: 10px 0;'>" .
                        htmlspecialchars($reset_link) .
                        "</code><br><a href='" . htmlspecialchars($reset_link) . "' style='display: inline-block; margin-top: 10px; padding: 10px 20px; background: #16a34a; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;'>Cambiar Contraseña</a>";
                }

                $show_reset_form = false;
            } else {
                $error = "No se encuentran coincidencias.";
            }
        }
    } elseif (isset($_POST['reset_form'])) {

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

include '../views/public/reset_password.php';
