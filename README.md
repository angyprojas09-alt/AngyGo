# AngyGo - Plataforma de Domicilios 🚀

## 📋 Descripción
AngyGo es una plataforma web completa de servicios de domicilio desarrollada en **PHP** con **MySQL**. Permite a clientes realizar pedidos de comida rápida, paquetería y compras express, que se guardan en base de datos y se notifican automáticamente por **WhatsApp**. Incluye paneles administrativos para admins y domiciliarios.

**Ubicación:** Socorro, Colombia  
**WhatsApp Business:** +57 304 525 7674

## ✨ Características Principales
- ✅ **Registro/Login Seguro** con confirmación por email y recuperación de contraseña
- 📱 **Formulario de Pedidos** intuitivo (guardado en DB + WhatsApp auto)
- 👥 **Sistema de Roles**: Cliente, Admin, Domiciliario
- 🛠️ **Panel Admin**: Gestión usuarios + ver pedidos
- 🚴‍♂️ **Panel Domiciliario**: Pedidos asignados
- 🔐 **Autenticación Completa** (PHP Sessions)
- 📧 **Emails** de confirmación/reset (PHPMailer)
- 📊 **Base de Datos Robusta** con estados de pedidos (Pendiente, En camino, Entregado)

## 🗄️ Estructura de la Base de Datos
```
angygo (MySQL/MariaDB)
├── usuarios (id, nombre, correo, password, rol, fecha_registro)
├── pedidos (id, nombre, telefono, direccion, producto, cantidad, comentarios, estado, usuario_id, domiciliario_id)
├── email_confirmations (tokens para verificación)
└── password_resets (tokens para reset contraseña)
```

**Importar:** `database/angygo.sql`

## 🛠️ Instalación y Configuración

### Requisitos
- **XAMPP** (Apache + MySQL + PHP 8.2+)
- **PHPMailer** (incluido en `config/mailer.php`)
- Cuenta **Gmail** para emails (configurar en `config/mailer.php`)

### Pasos
1. **Clonar/Descargar** el proyecto en `c:/xampp/htdocs/AngyGo`
2. **Importar DB:** Abrir phpMyAdmin → Importar `database/angygo.sql`
3. **Configurar Email:** Editar `config/mailer.php` con tus credenciales Gmail:
   ```php
   $mail->Username = 'tuemail@gmail.com';
   $mail->Password = 'tu_app_password';
   ```
4. **Conexión DB:** `config/conexion.php` (por defecto: root sin password)
5. **Iniciar XAMPP** (Apache + MySQL)
6. **Acceder:** `http://localhost/AngyGo/public/`

### Usuarios de Prueba
```
Admin: angygo916@gmail.com (rol: admin)
Cliente: angyprojas09@gmail.com (rol: cliente)
Domiciliario: camiloan09@gmail.com (rol: domiciliario)
```

## 📁 Estructura del Proyecto (MVC)
```
AngyGo/
├── config/           # Configuraciones (DB, Mailer)
├── controllers/      # Lógica de negocio (CRUD, Auth)
├── views/            # Plantillas
│   ├── admin/        # Dashboard, Usuarios, Pedidos
│   └── domiciliario/ # Pedidos asignados
├── public/           # Frontend público
│   ├── index.php     # Landing + Formulario pedidos
│   ├── login.php
│   └── registro.php
├── database/         # SQL dump
└── README.md
```

## 🎯 Flujo de Usuario
1. **Cliente:** Registro/Login → Realizar pedido → WhatsApp auto → Seguimiento
2. **Admin:** Login → Ver/gestionar usuarios y pedidos
3. **Domiciliario:** Login → Ver pedidos asignados → Actualizar estado

## 📱 Demo del Formulario de Pedidos
![Formulario Pedidos](public/imagenAngyGo/logo.png)
*(Captura del formulario principal que envía a WhatsApp)*

## 🚀 Tecnologías
- **Backend:** PHP 8+, MySQL/MariaDB
- **Frontend:** HTML5, CSS3 (Poppins), JavaScript vanilla
- **Emails:** PHPMailer + Gmail SMTP
- **Servidor:** XAMPP/Apache
- **Autenticación:** PHP Password Hash + Sessions + Tokens

## 📞 Integración WhatsApp
Los pedidos se envían automáticamente a WhatsApp Business con formato:
```
¡Hola ANGYGO! 👋
Nombre: Juan Pérez
Teléfono: 3001234567
Dirección: Calle 10 #5-22
Producto: Hamburguesa
Cantidad: 2
Comentarios: Sin cebolla
```

## 🔒 Seguridad Implementada
- Hash de contraseñas (password_hash)
- Tokens únicos para email/reset (expiran)
- Protección CSRF básica
- Sanitización de inputs
- Prepared statements (parcial)

## 📈 Estados de Pedidos
- `Pendiente`
- `En camino`
- `Entregado`

## 🤝 Contribuir
1. Fork el proyecto
2. Crear branch `feature/xxx`
3. Commit changes
4. Pull Request

## 📄 Licencia
Proyecto SENA - Uso educativo/comercial con atribución.

## 👨‍💻 Autores
- **Desarrollador Principal:** Angy Paola Rojas Loza
- **Contacto:** [angyprojas09@gmail.com](mailto:angyprojas09@gmail.com)

---

**¡Gracias por usar AngyGo! 🚴‍♂️✨**

