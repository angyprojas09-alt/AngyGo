<?php

// Configuración de Gmail
define('GMAIL_USER', 'tu_email@gmail.com');      // Reemplazar con tu Gmail
define('GMAIL_PASSWORD', 'tu_contraseña_de_app'); // Reemplazar con contraseña de app

// Función para validar email real
function validar_email_real($email)
{
    $resultado = [
        'valido' => false,
        'mensaje' => ''
    ];

    // Validar formato básico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $resultado['mensaje'] = 'El email no tiene un formato válido.';
        return $resultado;
    }

    // Extraer dominio
    $dominio = substr(strrchr($email, "@"), 1);

    // Bloquear emails temporales/fake
    $dominios_bloqueados = [
        'tempmail.com',
        'temp-mail.com',
        '10minutemail.com',
        'guerrillamail.com',
        'mailinator.com',
        'trashmail.com',
        'fakeinbox.com',
        'temp.email'
    ];

    if (in_array(strtolower($dominio), $dominios_bloqueados)) {
        $resultado['mensaje'] = 'No puedes usar emails temporales.';
        return $resultado;
    }

    // Verificar registros MX del dominio (validar que el dominio puede recibir correos)
    if (!checkdnsrr($dominio, 'MX')) {
        // Si no hay registros MX, intentar con A records como alternativa
        if (!checkdnsrr($dominio, 'A') && !checkdnsrr($dominio, 'AAAA')) {
            $resultado['mensaje'] = 'El dominio del email no existe o no puede recibir correos.';
            return $resultado;
        }
    }

    $resultado['valido'] = true;
    $resultado['mensaje'] = 'Email válido.';
    return $resultado;
}

// Función para validar contraseña segura
function validar_password_segura($password)
{
    $resultado = [
        'valido' => false,
        'mensaje' => '',
        'requisitos' => [
            'longitud' => strlen($password) >= 8,
            'mayuscula' => preg_match('/[A-Z]/', $password),
            'minuscula' => preg_match('/[a-z]/', $password),
            'numero' => preg_match('/[0-9]/', $password),
            'especial' => preg_match('/[!@#$%^&*()_+\-=\[\]{};:"\'<>,.?\/\\|`~]/', $password)
        ]
    ];

    if (!$resultado['requisitos']['longitud']) {
        $resultado['mensaje'] = 'La contraseña debe tener al menos 8 caracteres.';
        return $resultado;
    }

    if (!$resultado['requisitos']['mayuscula']) {
        $resultado['mensaje'] = 'La contraseña debe contener al menos una mayúscula (A-Z).';
        return $resultado;
    }

    if (!$resultado['requisitos']['minuscula']) {
        $resultado['mensaje'] = 'La contraseña debe contener al menos una minúscula (a-z).';
        return $resultado;
    }

    if (!$resultado['requisitos']['numero']) {
        $resultado['mensaje'] = 'La contraseña debe contener al menos un número (0-9).';
        return $resultado;
    }

    if (!$resultado['requisitos']['especial']) {
        $resultado['mensaje'] = 'La contraseña debe contener al menos un carácter especial (!@#$%^&*()_+-=[]{};:"\'<>,.?/\\|`~).';
        return $resultado;
    }

    $resultado['valido'] = true;
    $resultado['mensaje'] = 'Contraseña válida.';
    return $resultado;
}
function enviar_email_confirmacion($email, $nombre, $enlace_confirmacion)
{
    $asunto = "Confirmar tu correo - AngyGo";

    $mensaje_html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='css/email_confirmacion.css'>
    </head>
    <body>
        <div class='container'>
            <div class='card'>
                <div class='header'>
                    <h2>¡Bienvenido a AngyGo!</h2>
                </div>
                <p>Hola <strong>$nombre</strong>,</p>
                <p>Gracias por registrarte en AngyGo. Para activar tu cuenta, por favor confirma tu correo electrónico.</p>
                <a href='$enlace_confirmacion' class='button'>Confirmar Email</a>
                <p>O copia este enlace en tu navegador:</p>
                <p style='word-break: break-all; color: #666;'>$enlace_confirmacion</p>
                <div class='footer'>
                    <p>Si no creaste esta cuenta, ignora este email.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";

    return enviar_email($email, $asunto, $mensaje_html);
}

// Función para enviar email de recuperación de contraseña
function enviar_email_recuperacion($email, $nombre, $enlace_recuperacion)
{
    $asunto = "Recupera tu contraseña - AngyGo";

    $mensaje_html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='css/email_recuperacion.css'>
    </head>
    <body>
        <div class='container'>
            <div class='card'>
                <div class='header'>
                    <h2>🔐 Recuperar Contraseña</h2>
                </div>
                <p>Hola <strong>$nombre</strong>,</p>
                <p>Recibimos una solicitud para recuperar tu contraseña. Haz clic en el botón de abajo para establecer una nueva contraseña.</p>
                <a href='$enlace_recuperacion' class='button'>Recuperar Contraseña</a>
                <p>O copia este enlace en tu navegador:</p>
                <p style='word-break: break-all; color: #666;'>$enlace_recuperacion</p>
                <p><strong>Este enlace expira en 1 hora.</strong></p>
                <div class='footer'>
                    <p>Si no solicitaste esto, ignora este email.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";

    return enviar_email($email, $asunto, $mensaje_html);
}

// Función para enviar email (genérica)
function enviar_email($destinatario, $asunto, $mensaje_html)
{
    // Por ahora retorna true para que funcione sin Gmail
    // Once user provides Gmail credentials, we'll implement it
    return true;
}
