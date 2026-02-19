<?php
session_start();
require_once("conexion.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correo = trim($_POST["correo"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($correo) || empty($password)) {
        $error = "Completa todos los campos";
    } else {

        $stmt = $conexion->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE correo = ? LIMIT 1");

        if (!$stmt) {
            die("Error en la consulta: " . $conexion->error);
        }

        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $usuario = $resultado->fetch_assoc();

            if (password_verify($password, $usuario["password"])) {

                // Seguridad extra
                session_regenerate_id(true);

                // ===============================
                // GUARDAR SESIÓN CORRECTAMENTE
                // ===============================
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["nombre"]     = $usuario["nombre"]; // 👈 IMPORTANTE
                $_SESSION["rol"]        = $usuario["rol"];

                // Redirección por rol
                switch ($usuario["rol"]) {
                    case "admin":
                        header("Location: panel_admin.php");
                        break;

                    case "domiciliario":
                        header("Location: panel_domiciliario.php");
                        break;

                    default:
                        header("Location: index.php");
                        break;
                }

                exit();
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado";
        }

        $stmt->close();
    }
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login AngyGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0b3d2e, #14532d);
        }

        /* CONTENEDOR */
        .login-container {
            background: #f8fafc;
            padding: 45px 40px;
            width: 360px;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeInUp 0.6s ease;
        }

        /* TÍTULO */
        .titulo-login {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #1f2937;
            position: relative;
        }

        .titulo-login span {
            color: #16a34a;
        }

        .titulo-login::after {
            content: "";
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            display: block;
            margin: 12px auto 0;
            border-radius: 6px;
        }

        /* INPUTS */
        .input-group {
            margin-bottom: 22px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #16a34a;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
            transform: translateY(-1px);
        }

        /* BOTÓN */
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* MENSAJE ERROR */
        .error {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
            animation: pulse 0.6s ease;
        }

        /* LINKS */
        .register-link,
        .forgot-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-link a,
        .forgot-link a {
            text-decoration: none;
            color: #16a34a;
            font-weight: 600;
            transition: 0.3s;
        }

        .register-link a:hover,
        .forgot-link a:hover {
            text-decoration: underline;
        }

        /* ANIMACIONES */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
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

            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
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

</body>

</html>