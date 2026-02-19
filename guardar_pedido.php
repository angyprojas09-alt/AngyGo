<?php
session_start();
require_once("conexion.php");

header('Content-Type: text/html; charset=UTF-8');
$conexion->set_charset("utf8mb4");

$ok = false;
$error = "";

// 🔐 Verificar sesión activa
if (!isset($_SESSION["usuario_id"])) {
  header("Location: login.php");
  exit();
}

$usuario_id = $_SESSION["usuario_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $nombre = trim($_POST['nombre'] ?? $_SESSION['usuario']);
  $telefono = trim($_POST['telefono'] ?? '');
  $direccion = trim($_POST['direccion'] ?? '');
  $producto = trim($_POST['producto'] ?? '');
  $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
  $comentarios = trim($_POST['comentarios'] ?? '');

  if ($telefono === '' || $direccion === '' || $producto === '' || $cantidad < 1) {
    header('Location: index.php?mensaje=error');
    exit();
  }

  // ✅ Insertar incluyendo usuario_id
  $stmt = $conexion->prepare(
    "INSERT INTO pedidos (usuario_id, nombre, telefono, direccion, producto, cantidad, comentarios)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
  );

  if ($stmt) {

    $stmt->bind_param("issssis", $usuario_id, $nombre, $telefono, $direccion, $producto, $cantidad, $comentarios);

    if ($stmt->execute()) {

      $lastId = $conexion->insert_id;
      header("Location: guardar_pedido.php?pedido_id=" . $lastId);
      exit();
    } else {
      $error = "Error al guardar el pedido.";
    }

    $stmt->close();
  }

  $conexion->close();
} elseif (isset($_GET['pedido_id'])) {

  $id = (int)$_GET['pedido_id'];

  // 🔐 Solo permitir ver pedidos del usuario logueado
  $stmt = $conexion->prepare("SELECT * FROM pedidos WHERE id = ? AND usuario_id = ? LIMIT 1");
  $stmt->bind_param("ii", $id, $usuario_id);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows > 0) {

    $pedido = $resultado->fetch_assoc();
    $ok = true;

    $nombre = $pedido['nombre'];
    $telefono = $pedido['telefono'];
    $direccion = $pedido['direccion'];
    $producto = $pedido['producto'];
    $cantidad = $pedido['cantidad'];
    $comentarios = $pedido['comentarios'];
  } else {
    $error = "Pedido no encontrado o no autorizado.";
  }

  $stmt->close();
  $conexion->close();
} else {
  header("Location: index.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AngyGo - Pedido</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #22c55e, #0f172a);
      padding: 20px
    }

    .card {
      width: 100%;
      max-width: 650px;
      background: #fff;
      border-radius: 18px;
      padding: 30px;
      box-shadow: 0 12px 35px rgba(0, 0, 0, .25);
      text-align: center
    }

    .logo {
      font-size: 28px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 10px
    }

    .subtitle {
      color: #64748b;
      margin-bottom: 20px
    }

    .status {
      padding: 16px;
      border-radius: 14px;
      margin: 18px 0;
      font-size: 15px;
      line-height: 1.4;
      text-align: left
    }

    .success {
      background: #dcfce7;
      border: 1px solid rgba(22, 101, 52, .2);
      color: #166534
    }

    .danger {
      background: #ffe4e6;
      border: 1px solid rgba(159, 18, 57, .18);
      color: #9f1239
    }

    .details {
      margin-top: 12px;
      background: #f8fafc;
      border: 1px solid rgba(15, 23, 42, .08);
      border-radius: 14px;
      padding: 16px;
      text-align: left
    }

    .btns {
      margin-top: 20px;
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: center
    }

    .btn {
      display: inline-block;
      text-decoration: none;
      padding: 12px 16px;
      border-radius: 12px;
      font-weight: 700;
      transition: .25s
    }

    .btn-primary {
      background: #62c5f3;
      color: #fff
    }

    .btn-secondary {
      background: #e2e8f0;
      color: #0f172a
    }

    .small {
      margin-top: 14px;
      color: #94a3b8;
      font-size: 13px
    }
  </style>
</head>

<body>

  <div class="card">

    <div class="logo">🚴‍♂️ ANGYGO.</div>
    <div class="subtitle">Tu pedido en movimiento 🚀</div>

    <?php if ($ok): ?>

      <div class="status success">
        <strong>✅ Pedido registrado correctamente</strong><br>
        En breve te contactaremos para confirmar el domicilio.
      </div>

      <div class="details">
        <h3>📦 Resumen del pedido</h3>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($nombre) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($telefono) ?></p>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($direccion) ?></p>
        <p><strong>Producto:</strong> <?= htmlspecialchars($producto) ?></p>
        <p><strong>Cantidad:</strong> <?= (int)$cantidad ?></p>
        <?php if (trim($comentarios) !== ""): ?>
          <p><strong>Comentarios:</strong> <?= nl2br(htmlspecialchars($comentarios)) ?></p>
        <?php endif; ?>
      </div>

      <div class="btns">
        <a class="btn btn-primary" href="index.php">Volver al inicio</a>
        <a class="btn btn-secondary" href="logout.php">Cerrar sesión</a>
      </div>

      <div class="small">© 2026 AngyGo. Todos los derechos reservados.</div>

      <script>
        (function() {
          const numeroWhatsApp = "573045257674";

          let mensaje =
            `¡Hola AngyGo! 👋🏻
Quiero registrar este pedido:

🚻 Nombre: <?= addslashes($nombre) ?>

📞 Teléfono: <?= addslashes($telefono) ?>

📍Dirección: <?= addslashes($direccion) ?>

📦 Producto: <?= addslashes($producto) ?>

🧮 Cantidad: <?= $cantidad ?>

<?php if (trim($comentarios) !== ""): ?>
📝 Comentarios: <?= addslashes($comentarios) ?>
<?php endif; ?>

Gracias 🙌🏻`;

          const url = "https://wa.me/" + numeroWhatsApp + "?text=" + encodeURIComponent(mensaje);
          window.location.href = url;
        })();
      </script>

    <?php else: ?>

      <div class="status danger">
        <strong>❌ Error</strong><br>
        <?= htmlspecialchars($error) ?>
      </div>

      <div class="btns">
        <a class="btn btn-primary" href="index.php">Volver</a>
      </div>

    <?php endif; ?>

  </div>
</body>

</html>