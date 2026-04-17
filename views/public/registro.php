<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro AngyGo</title>
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