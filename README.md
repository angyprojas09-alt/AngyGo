# 🚴‍♂️ AngyGo - Sistema de Domicilios

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-10.4-4479A1?style=for-the-badge&logo=mysql)
![License](https://img.shields.io/badge/License-SENA-blue?style=for-the-badge)

**Sistema de gestión de servicios de domicilio para Socorro, Colombia**

[Características](#características) • [Requisitos](#requisitos) • [Instalación](#instalación) • [Estructura](#estructura) • [Uso](#uso) • [Licencia](#licencia)

</div>

---

## 📋 Descripción

**AngyGo** es una aplicación web desarrollada en PHP que ofrece servicios de domicilio en la ciudad de Socorro, Santander (Colombia). El sistema permite a los usuarios registrados realizar pedidos de comida rápida, paquetería y compras express, con integración directa a WhatsApp para la gestión de pedidos.

### 🎯 Propósito

Este proyecto fue desarrollado como parte de un proyecto formativo del SENA (Servicio Nacional de Aprendizaje), permitiendo a los usuarios solicitar servicios de domicilio de manera fácil y rápida.

---

## ✨ Características

| Característica | Descripción |
|----------------|-------------|
| 🔐 **Autenticación** | Sistema completo de registro, login y logout |
| 📧 **Confirmación de Email** | Validación de cuentas mediante token por correo electrónico |
| 🔑 **Recuperación de Contraseña** | Sistema de restablecimiento de contraseña seguro con tokens |
| 📊 **Panel de Usuario** | Dashboard personalizado con información del usuario |
| 🛒 **Gestión de Pedidos** | Formulario de pedidos con validación y almacenamiento en BD |
| 📱 **Integración WhatsApp** | Envío automático de pedidos al negocio vía WhatsApp |
| 🎨 **Diseño Responsivo** | Interfaz moderna y adaptativa para todos los dispositivos |
| 🔒 **Seguridad** | Contraseñas hasheadas con bcrypt, protección contra inyecciones SQL |

---

## 🛠️ Requisitos

### Software Necesario

- **Servidor Web**: Apache o Nginx
- **PHP**: Versión 8.0 o superior
- **MySQL/MariaDB**: Versión 5.7 o superior
- **Extensiones PHP**: 
  - mysqli
  - mbstring
  - session

### Configuración Recomendada

- XAMPP, WAMP, MAMP o Laragon
- Navegador web moderno (Chrome, Firefox, Edge)

---

## 📦 Instalación

### Paso 1: Clonar o Descargar el Proyecto

Coloca los archivos en la raíz de tu servidor web:

```bash
# Para XAMPP
cp -r AngyGo c:/xampp/htdocs/

# Para WAMP
cp -r AngyGo w:/www/
```

### Paso 2: Configurar la Base de Datos

1. Abre phpMyAdmin (http://localhost/phpmyadmin)
2. Crea una nueva base de datos llamada `angygo`
3. Importa el archivo `angygo.sql`:

```bash
# Desde línea de comandos
mysql -u root -p angygo < angygo.sql
```

O desde phpMyAdmin:
- Selecciona la base de datos `angygo`
- Ve a la pestaña **Importar**
- Selecciona el archivo `angygo.sql`
- Haz clic en **Continuar**

### Paso 3: Configurar Conexión a BD

Edita el archivo `conexion.php` si es necesario:

```php
<?php
$servidor = "localhost";
$usuario = "root";
$password = "";  // Tu contraseña de MySQL si la tienes
$bd = "angygo";

$conexion = new mysqli($servidor, $usuario, $password, $bd);
// ...
?>
```

### Paso 4: Configurar Correo (Opcional)

Para que funcione el envío de emails de confirmación y recuperación:

Edita los archivos correspondientes:
- `enviar_token.php`
- `confirmar_email.php`

Configura tu servidor de correo SMTP o utiliza una librería como PHPMailer.

### Paso 5: Acceder a la Aplicación

Abre tu navegador y visita:

```
http://localhost/AngyGo/
```

---

## 📂 Estructura del Proyecto

```
AngyGo/
├── 📄 index.php              # Panel principal / Dashboard
├── 📄 login.php             # Página de inicio de sesión
├── 📄 logout.php            # Cierre de sesión
├── 📄 registro.php          # Formulario de registro de usuarios
├── 📄 confirmar_email.php   # Confirmación de cuenta por email
├── 📄 reset_password.php    # Restablecer contraseña
├── 📄 cambiar_password.php  # Cambiar contraseña (logged in)
├── 📄 enviar_token.php      # Enviar token de recuperación
├── 📄 guardar_pedido.php    # Guardar y procesar pedidos
├── 📄 conexion.php          # Conexión a la base de datos
├── 📄 angygo.sql            # Estructura de la base de datos
├── 📄 README.md             # Este archivo
└── 📁 imagenAngyGo/        # Recursos gráficos e imágenes
    ├── logo.png
    ├── ANGYblanco.jpeg
    ├── ANGYnegro.jpeg
    ├── comidas rapidas.jpeg
    ├── paqueteria.jpeg
    ├── compras .jpeg
    └── Imagen2.png
```

---

## 🖥️ Uso

### Registro de Usuario

1. Ve a `registro.php`
2. Completa el formulario con:
   - Nombre completo
   - Correo electrónico
   - Contraseña
3. Confirma tu correo electrónico haciendo clic en el enlace enviado

### Iniciar Sesión

1. Ve a `login.php`
2. Ingresa tu correo y contraseña
3. Accederás al dashboard principal

### Realizar un Pedido

1. Inicia sesión
2. En el dashboard, completa el formulario de pedido:
   - Nombre
   - Teléfono
   - Dirección
   - Producto
   - Cantidad
   - Comentarios adicionales
3. Haz clic en "Guardar y Enviar por WhatsApp"
4. Serás redirigido a WhatsApp con los datos del pedido

### Recuperar Contraseña

1. Ve a `reset_password.php`
2. Ingresa tu correo electrónico
3. Revisa tu correo y sigue el enlace
4. Crea una nueva contraseña

---

## 🔧 Configuración Adicional

### Cambiar Número de WhatsApp

Edita el archivo `index.php` y busca:

```javascript
const numeroWhatsApp = "3045257674";
```

Cambia el número por el deseado (sin el +).

### Personalizar Colores

Los estilos CSS están embebidos en `index.php`. Busca la sección `<style>` para modificar:

- Colores del header
- Fondo de la página
- Estilos de las tarjetas
- Colores de botones

### Configuración de Sesión

El archivo `conexion.php` establece la conexión a la base de datos. Asegúrate de:

- Crear la base de datos `angygo`
- Importar las tablas del archivo `angygo.sql`
- Verificar las credenciales de acceso

---

## 🔐 Seguridad

- ✅ Contraseñas almacenadas con hash bcrypt
- ✅ Protección contra SQL injection mediante prepared statements
- ✅ Escape de salida con `htmlspecialchars()`
- ✅ Sesiones seguras con configuración apropiada
- ✅ Tokens únicos para confirmaciones y restablecimientos

---

## 📱 Capturas de Pantalla

| Vista | Descripción |
|-------|-------------|
| Login | Formulario de inicio de sesión con diseño moderno |
| Registro | Formulario de registro con validación |
| Dashboard | Panel principal con servicios y formulario de pedidos |
| WhatsApp | Mensaje formateado con los datos del pedido |

---

## 🤝 Contribuir

1. Haz un fork del proyecto
2. Crea una rama (`git checkout -b feature/nueva-funcionalidad`)
3. Commitea tus cambios (`git commit -m 'Agrega nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

---

## 📄 Licencia

Este proyecto fue desarrollado como parte del proyecto formativo del **SENA** (Servicio Nacional de Aprendizaje).

```
© 2026 AngyGo Proyecto SENA
```

---

## 📞 Contacto

- **WhatsApp**: +57 304 525 7674
- **Correo**: angygo916@gmail.com
- **Ubicación**: Socorro, Santander, Colombia

---

<div align="center">

**Desarrollado con ❤️ para la comunidad de Socorro**

¡Gracias por usar AngyGo! 🚴‍♂️

</div>
