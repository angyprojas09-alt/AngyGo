<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login AngyGo</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div class="login-container">

        <h2 class="titulo-login">
            Login <span>AngyGo</span>
        </h2>

        <?php if ($error != ""): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" required>
            </div>

            <div class="input-group password-group">
                <label>Contraseña</label>
                <input type="password" name="password" id="passwordLogin" required>
                <button type="button" class="toggle-password" id="togglePasswordLogin">Mostrar</button>
            </div>

            <button type="submit" class="btn">Iniciar sesión</button>
        </form>

        <div class="register-link">
            ¿No tienes cuenta? <a href="registro.php">Crear cuenta</a>
        </div>

        <div class="forgot-link">
            <a href="reset_password.php">¿Olvidaste tu contraseña?</a>
        </div>

    </div>

    <script>
        const passwordInput = document.getElementById('passwordLogin');
        const toggleBtn = document.getElementById('togglePasswordLogin');

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

</body>

</html>