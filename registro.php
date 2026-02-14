<?php
include("conexion.php");

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $password_raw = $_POST["password"] ?? '';

    if ($nombre === '' || $correo === '' || $password_raw === '') {
        $error = 'Completa todos los campos.';
    } else {
        $check = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
        if ($check) {
            $check->bind_param('s', $correo);
            $check->execute();
            $res = $check->get_result();
            if ($res && $res->num_rows > 0) {
                $error = 'Ya existe una cuenta con ese correo.';
            }
            $check->close();
        } else {
            $error = 'Error en la verificación de correo.';
        }
    }

    if ($error === '') {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('sss', $nombre, $correo, $password);
            if ($stmt->execute()) {
                $stmt->close();
                $conexion->close();
                header('Location: enviar_token.php?correo=' . urlencode($correo));
                exit();
            } else {
                $error = 'Error al registrar: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = 'Error en la consulta de registro.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro AngyGo</title>
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
            background: linear-gradient(135deg, #027737, #84cca1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* CONTENEDOR */
        .register-container {
            background: #f8fafc;
            padding: 45px 40px;
            width: 380px;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeInUp 0.6s ease;
        }

        /* TÍTULO */
        .titulo-register {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #1f2937;
            position: relative;
        }

        .titulo-register span {
            color: #16a34a;
        }

        .titulo-register::after {
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

        /* MENSAJES */
        .success {
            background: #e6f9f0;
            color: #16a34a;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .error {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        /* LINK LOGIN */
        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            text-decoration: none;
            color: #16a34a;
            font-weight: 600;
            transition: 0.3s;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* ANIMACIÓN */
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
    </style>
</head>

<body>

    <div class="register-container">

        <h2 class="titulo-register">
            Registro <span>AngyGo</span>
        </h2>

        <?php if ($mensaje != ""): ?>
            <div class="success">
                <?php echo $mensaje; ?>
                <br><br>
                <a href="login.php">Ir al Login</a>
            </div>
        <?php endif; ?>

        <?php if ($error != ""): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="input-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" required>
            </div>

            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn">Registrarse</button>
        </form>

        <div class="login-link">
            ¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a>
        </div>

    </div>

</body>

</html>