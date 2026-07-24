<?php
session_start();
require_once("../config/conexion.php");

// 🔐 Proteger la página
if (!isset($_SESSION["usuario_id"])) {
  header("Location: login.php");
  exit();
}

// ✅ Tomar correctamente el nombre de sesión
$usuario = $_SESSION["nombre"] ?? ($_SESSION["usuario"] ?? "Usuario");
$rol = $_SESSION["rol"] ?? "cliente";
?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AngyGo - Servicio de Domicilio</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap">
  <link rel="icon" type="image/png" href="imagenAngyGO/ANGYnegro.jpeg">
  <link rel="stylesheet" href="css/index.css">
</head>

<body>

  <header>
    <div class="logo">🚴‍♂️ ANGY<span>GO</span></div>

    <nav>
      <a href="#inicio">Inicio</a>
      <a href="#servicios">Servicios</a>
      <a href="#contacto">Contacto</a>
    </nav>
  </header>



  <section class="hero" id="inicio">
    <h2>Rápido, Seguro y Siempre en Movimiento</h2>
    <p>Tu servicio de domicilios confiable en Socorro.</p>
  </section>


  <section class="servicios" id="servicios">
    <h2>Nuestros Servicios</h2>
    <div class="cards">
      <div class="card">
        <img src="imagenAngyGo/comidas rapidas.jpeg" alt="Comida rápida">
        <h3>Comida Rápida</h3>
        <p>Llevamos tus platos favoritos de los mejores restaurantes.</p>
      </div>

      <div class="card">
        <img src="imagenAngyGo/paqueteria.jpeg" alt="Paquetería">
        <h3>Paquetería</h3>
        <p>Envía paquetes o documentos sin salir de casa.</p>
      </div>

      <div class="card">
        <img src="imagenAngyGo/compras .jpeg" alt="Compras Express">
        <h3>Compras Express</h3>
        <p>¿Olvidaste algo? Lo conseguimos por ti en minutos.</p>
      </div>
    </div>
  </section>

  <section class="contacto" id="contacto">
    <h2>Contáctanos</h2>
    <p>👤 Usuario: <strong><?php echo htmlspecialchars($usuario); ?></strong></p>
    <p>📱 WhatsApp: <a href="#">+57 304 525 7674</a></p>
    <p>📧 Correo: <a href="mailto:angygo916@gmail.com.com">angygo916@gmail.com</a></p>
    <p>📍 Ciudad: Socorro, Colombia</p>

    <div class="formulario">
      <h3>📋 Realiza tu Pedido</h3>

      <div class="bienvenida">
        Bienvenida <?php echo htmlspecialchars($usuario); ?> 👋
      </div>

      <form action="../controllers/guardar_pedido.php" method="POST" onsubmit="return disableSubmit(this);">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre"
          value="<?php echo htmlspecialchars($usuario); ?>" required>


        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="Ej: 3001234567" required>

        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion" placeholder="Ej: Calle 10 #5-22" required>

        <label for="producto">Producto</label>
        <input type="text" id="producto" name="producto" placeholder="Producto que deseas pedir" required>

        <label for="cantidad">Cantidad</label>
        <input type="number" id="cantidad" name="cantidad" min="1" value="1" required>

        <label for="comentarios">Comentarios adicionales</label>
        <textarea id="comentarios" name="comentarios" rows="4" placeholder="Comentarios adicionales (opcional)"></textarea>

        <button type="submit">Guardar y Enviar por WhatsApp</button>
      </form>

      <?php if (isset($_GET["mensaje"]) && $_GET["mensaje"] == "ok"): ?>
        <div class="success">✅ Pedido registrado correctamente</div>
      <?php endif; ?>

      <?php if (isset($_GET["mensaje"]) && $_GET["mensaje"] == "error"): ?>
        <div class="error">❌ Ocurrió un error registrando el pedido. Intenta de nuevo.</div>
      <?php endif; ?>

      <div style="text-align:center;">
        <a class="logout" href="../controllers/logout.php">Cerrar sesión</a>
      </div>

    </div>
  </section>

  <footer>
    <p>© 2026 AngyGo Proyecto SENA</p>
  </footer>

  <!-- ✅ AUTO WHATSAPP: abre WA después de guardar y limpia URL -->
  <script>
    // Evitar doble envío desde el cliente
    function disableSubmit(form) {
      try {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
          btn.disabled = true;
          btn.innerText = 'Enviando...';
        }
      } catch (e) {
        // ignore
      }
      return true;
    }

    (function() {
      const params = new URLSearchParams(window.location.search);

      if (params.get("wa") === "1") {
        const nombre = params.get("nombre") || "";
        const telefono = params.get("telefono") || "";
        const direccion = params.get("direccion") || "";
        const producto = params.get("producto") || "";
        const cantidad = params.get("cantidad") || "";
        const comentarios = params.get("comentarios") || "";

        // ✅ Número WhatsApp negocio (sin +) 
        const numeroWhatsApp = "573045257674";

        let mensaje =
          "¡Hola ANGYGO! 👋🏻\n" +
          "Quiero registrar este pedido:\n\n" +
          "🚻 Nombre: " + nombre + "\n" +
          "📞 Teléfono: " + telefono + "\n" +
          "📍 Dirección: " + direccion + "\n" +
          "📦 Producto: " + producto + "\n" +
          "🧮 Cantidad: " + cantidad + "\n";

        if (comentarios.trim() !== "") {
          mensaje += "📝 Comentarios: " + comentarios + "\n";
        }

        mensaje += "\nGracias 🙌🏻";

        const url = "https://wa.me/" + numeroWhatsApp + "?text=" + encodeURIComponent(mensaje);
        // Navegar en la misma pestaña (evita bloqueos de popup)
        window.location.href = url;

        // ✅ Limpiar URL para que no se vuelva a abrir al refrescar
        params.delete("wa");
        params.delete("nombre");
        params.delete("telefono");
        params.delete("direccion");
        params.delete("producto");
        params.delete("cantidad");
        params.delete("comentarios");

        const newUrl = window.location.pathname + (params.toString() ? "?" + params.toString() : "");
        window.history.replaceState({}, "", newUrl);
      }
    })();
  </script>

</body>

</html>