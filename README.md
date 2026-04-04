# AngyGo - Plataforma de Domicilios 🚀 [![PHP](https://img.shields.io/badge/PHP-8+-777DD6?style=flat&logo=php&logoColor=white)](https://www.php.net/) [![MySQL](https://img.shields.io/badge/MySQL-8.0+-006699?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)

**v1.1** (Actualizado abril 4 2026)

## 🔄 Últimas Actualizaciones
- ✅ Mejoras en paneles admin y domiciliario (asignación y seguimiento de pedidos).
- ✅ Optimización de envíos automáticos a WhatsApp.
- ✅ Badges visuales y documentación completa actualizada.

## 📋 Descripción
AngyGo es una plataforma web completa de servicios de domicilio desarrollada en **PHP** con **MySQL**. Permite a clientes realizar pedidos de comida rápida, paquetería y compras express, que se guardan en base de datos y se notifican automáticamente por **WhatsApp**. Incluye paneles administrativos para admins y domiciliarios.

**Ubicación:** Socorro, Colombia  
**WhatsApp Business:** +57 304 525 7674

## ✨ Características Principales
- ✅ **Registro/Login Seguro** con confirmación por email y recuperación de contraseña
- 📱 **Formulario de Pedidos** intuitivo (guardado en DB + WhatsApp auto)
- 👥 **Sistema de Roles**: Cliente, Admin, Domiciliario
- 🛠️ **Panel Admin**: Dashboard, gestión usuarios (CRUD), ver pedidos
- 🚴‍♂️ **Panel Domiciliario**: Pedidos asignados y actualización de estado
- 🔐 **Autenticación Completa** (PHP Sessions + Tokens)
- 📧 **Emails** de confirmación/reset (PHPMailer + Gmail SMTP)
- 📊 **Base de Datos Robusta** con estados de pedidos (Pendiente, En camino, Entregado)

## 🗄️ Estructura de la Base de Datos
```
angygo (MySQL/MariaDB)
├── usuarios (id, nombre, correo, password, rol, fecha_registro, email_confirmado)
├── pedidos (id, nombre, telefono, direccion, producto, cantidad, comentarios, fecha, estado, usuario_id, domiciliario_id)
├── email_confirmations (id, token, user_id, used)
└── password_resets (id, token, user_id, used)
```

**Importar:** `database/angygo.sql` en phpMyAdmin.

## 🛠️ Instalación y Configuración

### Requisitos
- **XAMPP** (Apache + MySQL + PHP 8.2+)
- **PHPMailer** (incluido en `config/mailer.php`)
- Cuenta **Gmail** con 2FA para emails

### Pasos
1. **Descargar** el proyecto en `c:/xampp/htdocs/AngyGo`
2. **Importar DB:** phpMyAdmin → Importar `database/angygo.sql`
3. **Configurar Email** en `config/mailer.php`:
   ```php
   $mail->Username = 'tuemail@gmail.com';
   $mail->Password = 'tu_app_password';  // Generar en Google > Seguridad > App Passwords
   ```
4. **Conexión DB** en `config/conexion.php` (default: root / vacío)
5. **Iniciar** Apache + MySQL en XAMPP
6. **Acceder:** `http://localhost/AngyGo/public/`

**Troubleshooting:**
- *Gmail no envía:* Activa 2FA y usa App Password (no contraseña normal).
- *Error DB:* Verifica host/user/pass en `config/conexion.php` coincide con phpMyAdmin.

### Usuarios de Prueba
```
Admin: angygo916@gmail.com / 123456 (rol: admin)
Cliente: angyprojas09@gmail.com / 123456 (rol: cliente)
Domiciliario: camiloan09@gmail.com / 123456 (rol: domiciliario)
```

## 📁 Estructura del Proyecto (MVC)
```
AngyGo/
├── config/              # DB (conexion.php), Mailer
├── controllers/         # Login, Registro, Pedidos, CRUD usuarios
├── views/               # Templates
│   ├── admin/           # Dashboard, Usuarios, Pedidos
│   └── domiciliario/    # Pedidos asignados
├── public/              # Entry points (.htaccess)
│   ├── index.php        # Landing + Form pedidos
│   ├── login.php
│   └── registro.php
├── database/            # angygo.sql
└── README.md
```

## 🎯 Flujo de Usuario
1. **Cliente** → Registro/Login → Pedido → WhatsApp notificación
2. **Admin** → Login → Gestionar usuarios/pedidos → Asignar domiciliario
3. **Domiciliario** → Login → Ver pedidos → Actualizar estado

## 🚀 Tecnologías
[![PHP](https://img.shields.io/badge/PHP-8+-777DD6?style=flat&logo=php&logoColor=white)] [![MySQL](https://img.shields.io/badge/MySQL-8.0+-006699?style=flat&logo=mysql&logoColor=white)] [![PHPMailer](https://img.shields.io/badge/PHPMailer-6.9+-2896f6?style=flat&logo=php&logoColor=white)] [![Apache](https://img.shields.io/badge/Apache-D22128?style=flat&logo=apache&logoColor=white)] [![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)] [![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)] [![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)]

- Backend: PHP 8+, MySQL
- Frontend: HTML5, CSS3 (Poppins), Vanilla JS
- Emails: PHPMailer + Gmail SMTP
- Servidor: XAMPP/Apache

## 📞 Integración WhatsApp
Pedidos auto-enviados con formato:
```
¡Nuevo Pedido ANGYGO! 👋
Nombre: [Nombre]
Tel: [Teléfono]
Dir: [Dirección]
Producto: [Producto]
Cant: [Cantidad]
Coment: [Comentarios]
ID Pedido: [ID]
```

## 🔒 Seguridad
- `password_hash()` / `password_verify()`
- Tokens únicos (email/reset) con expiración
- Prepared Statements (PDO)
- Sanitización inputs
- Sessions seguras

## 📈 Estados Pedidos
- `Pendiente`
- `En camino`
- `Entregado`

## 🤝 Contribuir
1. Fork → `git clone`
2. Branch: `git checkout -b feature/xxx`
3. Commit: `git commit -m 'feat: xxx'`
4. PR a `main`

## 📄 Licencia
Proyecto SENA - **Uso libre con atribución** a AngyGo.

## 👨‍💻 Autores & Contacto
**Angy Paola Rojas Loza**  
✉️ [angyprojas09@gmail.com](mailto:angyprojas09@gmail.com)

---

