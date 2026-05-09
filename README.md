# AngyGo - Plataforma de Domicilios 🚀

[![PHP](https://img.shields.io/badge/PHP-8+-777DD6?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-006699?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![PHPMailer](https://img.shields.io/badge/PHPMailer-6.9+-2896f6?style=flat&logo=php&logoColor=white)]()

**v1.1** (Actualizado: 2026)

---

## 📌 Descripción
AngyGo es una plataforma web de servicios de domicilio desarrollada en **PHP** con **MySQL**. Permite que clientes realicen pedidos (comida rápida, paquetería y compras express) que se guardan en base de datos y se notifican automáticamente.

Incluye:
- **Panel Admin**: gestionar usuarios y administrar/ver pedidos.
- **Panel Domiciliario**: ver pedidos asignados y actualizar el estado.

**Ubicación:** Socorro, Colombia  
**WhatsApp Business:** +57 304 525 7674

---

## ✨ Características principales
- ✅ Registro/Login seguro
- ✅ Confirmación de email y recuperación de contraseña con tokens
- 📄 Panel Admin: Dashboard, gestión de usuarios (CRUD) y pedidos
- 🛵 Panel Domiciliario: pedidos asignados y cambio de estado
- 📲 Notificaciones automáticas por WhatsApp
- 🗄️ Base de datos con estados de pedidos

---

## 🧩 Tecnologías
- Backend: **PHP 8+**, **MySQL**
- Frontend: **HTML5**, **CSS3**, Vanilla JS
- Emails: **PHPMailer** + SMTP (Gmail)
- Servidor: **XAMPP / Apache**

---

## 🛠️ Instalación y configuración (XAMPP)

### Requisitos
- XAMPP (Apache + MySQL)
- PHP 8.2+
- phpMyAdmin
- (Opcional) Cuenta Gmail para SMTP de emails

### Pasos
1. Copia el proyecto en:
   - `c:/xampp/htdocs/AngyGo`
2. Importa la base de datos:
   - phpMyAdmin → Importar `database/angygo.sql`
3. Configura la conexión en `config/conexion.php`.
4. Configura el envío de emails en `config/mailer.php` (si usarás confirmación/reset):
   - `Username` (tu Gmail)
   - `Password` (App Password de Google)
5. Inicia Apache y MySQL desde XAMPP.
6. Abre:
   - `http://localhost/AngyGo/public/`

### Troubleshooting
- Si Gmail no envía: activa 2FA y usa **App Password**.
- Si hay error de DB: verifica host/user/pass en `config/conexion.php`.

---

## 📁 Estructura del proyecto (MVC)
```
AngyGo/
├─ config/              # conexion.php, mailer.php
├─ controllers/         # lógica del sistema
├─ views/               # vistas (admin, domiciliario, público)
├─ public/              # entrypoints + recursos estáticos
├─ database/            # angygo.sql
└─ README.md
```

---

## 📌 Flujo de uso
1. **Cliente**: registra / inicia sesión → crea pedido → notificación por WhatsApp.
2. **Admin**: inicia sesión → gestiona usuarios → administra/ver pedidos.
3. **Domiciliario**: inicia sesión → ve pedidos asignados → actualiza el estado.

---

## 💬 Integración WhatsApp
El sistema envía mensajes con un formato similar a:

- ¡Nuevo Pedido ANGYGO! 📄
- Nombre: [Nombre]
- Tel: [Teléfono]
- Dir: [Dirección]
- Producto: [Producto]
- Cant: [Cantidad]
- Coment: [Comentarios]
- ID Pedido: [ID]

---

## 🔐 Seguridad
- `password_hash()` / `password_verify()`
- Tokens únicos para email/reset
- Consultas preparadas con PDO
- Sessions de PHP

---

## 🤝 Contribuir
1. Fork del proyecto
2. Crea rama: `git checkout -b feature/xxx`
3. Commit: `git commit -m "feat: xxx"`
4. PR hacia `main`

---

## 📄 Licencia
Proyecto SENA - **Uso libre con atribución** a AngyGo.

---

## 👩‍💻 Autores & Contacto
**Angy Paola Rojas Loza**  
📧 [angyprojas09@gmail.com](mailto:angyprojas09@gmail.com)

