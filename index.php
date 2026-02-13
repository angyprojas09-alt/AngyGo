<?php
session_start();

// 🔐 Proteger la página
if (!isset($_SESSION["usuario"])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AngyGo - Servicio de Domicilio</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap">
  <link rel="icon" type="image/png" href="imagenAngyGO/ANGYnegro.jpeg">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #14532d, #0f3d2e, #0b2e26);
      min-height: 100vh;
    }

    /* HEADER MODERNO */
    header {
      background: linear-gradient(90deg, #166534, #14532d);
      padding: 15px 8%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
      position: sticky;
      top: 0;
      z-index: 1000;
    }


    /* LOGO */
    .logo {
      font-size: 1.8em;
      font-weight: 800;
      letter-spacing: 2px;
      color: #ffffff;
      text-transform: uppercase;
      transition: 0.3s ease;
    }

    .logo span {
      color: #22c55e;
    }

    .logo:hover {
      transform: scale(1.05);
    }

    /* NAV */
    nav {
      display: flex;
      gap: 15px;
    }

    nav a {
      color: #ffffff !important;
      /* Letras blancas */
      font-weight: 600;
      transition: 0.3s ease;
    }

    nav a:hover {
      background-color: #22c55e;
      color: #ffffff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }




    header h1 {
      font-size: 2.3em;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: 1px;
    }

    nav {
      margin-top: 10px;
    }

    nav a {
      color: #0f172a;
      text-decoration: none;
      margin: 0 15px;
      font-weight: 600;
      transition: 0.3s;
    }

    nav a:hover {
      color: #ffffff;
    }

    /* HERO */
    .hero {
      background-image: url('imagenAngyGo/logo.png');
      background-size: cover;
      background-position: center;
      height: 55vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      text-align: center;
      padding: 0 20px;
    }

    .hero h2 {
      font-size: 2.6em;
      font-weight: 800;
      color: #ffffff;
      background: linear-gradient(90deg, #0f172a, #22c55e);
      padding: 12px 20px;
      border-radius: 14px;
    }

    .hero p {
      display: inline-block;
      background: #14532d;
      /* Verde oscuro elegante */
      color: #ffffff;
      padding: 12px 28px;
      border-radius: 40px;
      font-size: 1.1em;
      font-weight: 500;
      margin-top: 20px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
    }




    .servicios {
      padding: 100px 10%;
      text-align: center;
    }

    .servicios h2 {
      color: #fff;
      margin-bottom: 30px;
      font-size: 2em;
    }

    .cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 25px;
    }

    .card {
      background: #f0fdf4;
      padding: 25px;
      width: 260px;
      border-radius: 18px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    .card img {
      width: 90px;
      height: 90px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 15px;
    }


    .card h3 {
      color: #14532d;

      margin-bottom: 12px;
      font-weight: 700;
    }

    .card p {
      color: #374151;
      font-size: 0.95em;
    }


    /* CONTACTO */
    .contacto {
      padding: 100px 10%;
      text-align: center;
    }

    .contacto h2 {
      color: #fff;
      margin-bottom: 10px;
      font-size: 2em;
    }

    .contacto p {
      font-size: 1.05em;
      margin-bottom: 10px;
      color: #e5e7eb;
    }

    .contacto a {
      color: #c9eaf1;
      text-decoration: none;
      font-weight: bold;
    }

    .contacto a:hover {
      text-decoration: underline;
    }

    /* FORMULARIO */
    .formulario {
      margin-top: 30px;
      max-width: 650px;
      background-color: #fff5f2;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.18);
      margin-left: auto;
      margin-right: auto;
      text-align: left;
    }

    .formulario h3 {
      color: #0f172a;
      margin-bottom: 18px;
      text-align: center;
      font-size: 1.4em;
    }

    .bienvenida {
      background: #e6f7fc;
      color: #0f172a;
      padding: 12px 14px;
      border-radius: 12px;
      margin-bottom: 18px;
      font-weight: 600;
      text-align: center;
    }

    .formulario input,
    .formulario textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 1em;
      transition: 0.25s;
    }

    .formulario input:focus,
    .formulario textarea:focus {
      border-color: #22c55e;
      box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
    }

    .formulario button {
      background-color: #34e08a;
      color: #ffffff;
      border: none;
      padding: 12px;
      border-radius: 10px;
      font-size: 1em;
      cursor: pointer;
      font-weight: 700;
      transition: 0.3s;
      width: 100%;
    }

    .formulario button:hover {
      background-color: #2ccf7c;
    }

    /* BOTÓN CERRAR SESIÓN */
    .logout {
      display: inline-block;
      margin-top: 14px;
      background-color: #34e08a;
      color: #ffffff !important;
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.85em;
      transition: 0.3s;
    }

    .logout:visited {
      color: #ffffff !important;
    }

    .logout:hover {
      background-color: #2ccf7c;
      color: #ffffff !important;
      text-decoration: none;
    }

    footer {
      background-color: #0b1220;
      color: #e6dce2;
      text-align: center;
      padding: 15px;
      font-size: 0.9em;
      margin-top: 30px;
    }
  </style>
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
    <p>👤 Usuario: <strong><?php echo htmlspecialchars($_SESSION["usuario"]); ?></strong></p>
    <p>📱 WhatsApp: <a href="#">+57 304 525 7674</a></p>
    <p>📧 Correo: <a href="mailto:angygo916@gmail.com.com">angygo916@gmail.com</a></p>
    <p>📍 Ciudad: Socorro, Colombia</p>

    <div class="formulario">
      <h3>📋 Realiza tu Pedido</h3>

      <div class="bienvenida">
        Bienvenida <?php echo htmlspecialchars($_SESSION["usuario"]); ?> 👋
      </div>

      <form action="guardar_pedido.php" method="POST" onsubmit="return disableSubmit(this);">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ej: Nombre y Apellido" value="<?php echo htmlspecialchars($_SESSION["usuario"]); ?>" required>

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
        <a class="logout" href="logout.php">Cerrar sesión</a>
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
        const numeroWhatsApp = "3045257674";

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