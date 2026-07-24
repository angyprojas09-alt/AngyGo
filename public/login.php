<?php
session_start();
require_once("../config/conexion.php");

$error = "";

// Si ya está logueado, redirecciona al dashboard
if (isset($_SESSION["usuario_id"])) {
    switch ($_SESSION["rol"]) {
        case "admin":
            header("Location: ../views/admin/panel_admin.php");
            exit();
        case "domiciliario":
            header("Location: ../views/domiciliario/panel_domiciliario.php");
            exit();
        default:
            header("Location: dashboard.php");
            exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correo = trim($_POST["correo"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($correo) || empty($password)) {
        $error = "Completa todos los campos";
    } else {

        $stmt = $conexion->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE correo = ? LIMIT 1");

        if (!$stmt) {
            die("Error en la consulta");
        }

        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {

            if (password_verify($password, $usuario["password"])) {

                // Seguridad extra
                session_regenerate_id(true);

                // Guardar sesión
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["nombre"]     = $usuario["nombre"];
                $_SESSION["rol"]        = $usuario["rol"];

                // Redirección por rol
                switch ($usuario["rol"]) {
                    case "admin":
                        header("Location: ../views/admin/panel_admin.php");
                        exit();

                    case "domiciliario":
                        header("Location: ../views/domiciliario/panel_domiciliario.php");
                        exit();

                    default:
                        header("Location: dashboard.php");
                        exit();
                }
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login AngyGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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

        <div class="register-link">
            ¿No tienes cuenta? <a href="registro.php">Crear cuenta</a>
        </div>

        <div class="forgot-link">
            <a href="reset_password.php">¿Olvidaste tu contraseña?</a>
        </div>

    </div>

</body>

</html>