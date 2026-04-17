<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - AngyGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/reset_password.css">
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