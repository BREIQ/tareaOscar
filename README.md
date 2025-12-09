# Sistema de Asistencia - Aplicación Web con PHP y MongoDB

Una aplicación web moderna para registrar asistencia de alumnos usando PHP 8+ y MongoDB. Diseño minimalista con interfaz responsive y colores vivos.

## Características

### Página Principal
- Botón grande para registrar asistencia
- Teclado numérico interactivo para ingresar PIN (6 dígitos)
- Validación automática de PIN
- Registro inmediato de asistencia con timestamp

### Panel de Administración
- Acceso protegido con contraseña fija (`Oscar9234`)
- Gestión completa de alumnos:
  - Crear nuevos alumnos
  - Editar información
  - Eliminar alumnos
- Consulta de asistencias con filtros:
  - Por día
  - Por mes
  - Por año
  - Todas las asistencias

### Base de Datos
- MongoDB con 2 colecciones:
  - `students`: Almacena información de alumnos
  - `attendance`: Registra cada asistencia con timestamp

## Requisitos Técnicos

- **PHP**: 8.0 o superior
- **MongoDB**: 4.4 o superior
- **MongoDB PHP Driver**: Instalado y configurado
- **Servidor Web**: Apache o similar con soporte para URL rewriting
- **Navegador**: Moderno con soporte para ES6 y Fetch API

## Instalación

### 1. Clonar o descargar el proyecto

```bash
git clone https://github.com/tuusuario/tareaOscar.git
cd tareaOscar
```

### 2. Instalar dependencias PHP

```bash
# Instalar MongoDB PHP Driver (si no está instalado)
# Windows (PECL)
pecl install mongodb

# macOS
brew install php-mongodb

# Linux (Debian/Ubuntu)
sudo apt-get install php-mongodb
```

### 3. Configurar MongoDB

#### Instalación de MongoDB

**Windows:**
```
1. Descargar desde https://www.mongodb.com/try/download/community
2. Ejecutar el instalador
3. MongoDB se iniciará automáticamente como servicio
```

**macOS:**
```bash
brew tap mongodb/brew
brew install mongodb-community
brew services start mongodb-community
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt-get install -y mongodb
sudo systemctl start mongodb
sudo systemctl enable mongodb
```

#### Crear la base de datos

```bash
# Conectar a MongoDB
mongosh

# Usar/crear base de datos
use asistencia_app

# Crear colecciones con índices
db.students.createIndex({ "student_number": 1 })
db.attendance.createIndex({ "student_number": 1 })
db.attendance.createIndex({ "timestamp": 1 })

# Insertar datos de ejemplo
db.students.insertOne({
    "name": "Juan Pérez",
    "student_number": "05",
    "password": "1234"
})
```

### 4. Configurar el servidor web

**Apache:**
1. Habilitar `mod_rewrite`:
```bash
a2enmod rewrite
```

2. Crear archivo `.htaccess` en la raíz o carpeta `public`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php/$1 [L]
</IfModule>
```

**PHP Built-in Server (para desarrollo):**
```bash
cd public
php -S localhost:8000
```

### 5. Acceso a la aplicación

```
Inicio: http://localhost:8000
Admin: Usa contraseña "Oscar9234"
```

## Estructura del Proyecto

```
tareaOscar/
├── public/
│   ├── index.php              # Punto de entrada principal
│   ├── api.php                # Endpoints API REST
│   ├── css/
│   │   └── styles.css         # Estilos responsive
│   └── js/
│       ├── app.js             # Lógica de página principal
│       └── admin.js           # Lógica del panel admin
├── views/
│   ├── home.php               # Vista de página principal
│   └── admin.php              # Vista del panel admin
├── src/
│   ├── Database/
│   │   └── Connection.php     # Conexión a MongoDB
│   ├── Models/
│   │   ├── Student.php        # Modelo de estudiante
│   │   └── Attendance.php     # Modelo de asistencia
│   └── Controllers/
│       ├── AttendanceController.php  # Control de asistencias
│       └── AdminController.php       # Control de admin
├── config/
│   └── config.php             # Configuración general
├── .htaccess                  # Reglas de reescritura URL
└── README.md                  # Este archivo
```

## Uso

### Registrar Asistencia

1. En la página principal, presiona **"Registrar Asistencia"**
2. Aparecerá un teclado numérico
3. Ingresa el PIN de 6 dígitos:
   - **Primeros 2 dígitos**: Número de alumno
   - **Últimos 4 dígitos**: Contraseña del alumno
4. Ejemplo: `051234` (alumno 05, contraseña 1234)
5. Si los datos son correctos, verás un mensaje de confirmación

### Acceso al Panel de Administración

1. En la página principal, presiona **"Acceso Admin"**
2. Ingresa la contraseña: `Oscar9234`
3. Accederás al panel con dos pestañas:

#### Gestión de Alumnos
- **Crear**: Completa nombre, número (2 dígitos) y contraseña (4 dígitos)
- **Editar**: Modifica nombre o contraseña
- **Eliminar**: Borra el alumno de la base de datos

#### Consulta de Asistencias
- Selecciona el tipo de filtro:
  - **Todas**: Muestra todos los registros
  - **Día**: Filtra por una fecha específica
  - **Mes**: Filtra por año y mes
  - **Año**: Filtra por año completo

## Formato de Datos

### Colección `students`
```json
{
  "_id": ObjectId("..."),
  "student_number": "05",
  "password": "1234",
  "name": "Juan Pérez"
}
```

### Colección `attendance`
```json
{
  "_id": ObjectId("..."),
  "student_number": "05",
  "timestamp": ISODate("2025-12-09T10:35:00Z")
}
```

## Validaciones

- PIN debe tener exactamente 6 dígitos
- Número de alumno: 2 dígitos (00-99)
- Contraseña: 4 dígitos (0000-9999)
- Nombre: No puede estar vacío
- Contraseña de admin: Debe ser exactamente "Oscar9234"

## Seguridad

- Inputs sanitizados mediante validación en servidor
- Contraseña de admin en variable de entorno (en producción)
- Uso de prepared queries en MongoDB (previene inyección)
- HTTPS recomendado en producción

## Personalización

### Cambiar contraseña de admin

Edita `config/config.php`:
```php
define('ADMIN_PASSWORD', 'TuNuevaContraseña');
```

### Cambiar URI de MongoDB

Edita `config/config.php`:
```php
define('MONGODB_URI', 'mongodb://usuario:contraseña@host:puerto');
```

### Cambiar zona horaria

Edita `config/config.php`:
```php
date_default_timezone_set('Tu/Zona/Horaria');
```

### Personalizar colores

Edita las variables CSS en `public/css/styles.css`:
```css
:root {
    --primary: #FF6B6B;      /* Color primario */
    --secondary: #4ECDC4;    /* Color secundario */
    --success: #45B7D1;      /* Color de éxito */
    --danger: #FF6B6B;       /* Color de error */
}
```

## Solución de Problemas

### MongoDB no conecta
- Verifica que MongoDB esté corriendo: `mongosh`
- Comprueba la URI de conexión en `config/config.php`
- Asegúrate que PHP MongoDB Driver está instalado: `php -m | grep mongodb`

### Errores 404 en API
- Verifica que `mod_rewrite` está habilitado en Apache
- Comprueba el archivo `.htaccess`
- Asegúrate de estar usando URLs relativas sin `/public` en desarrollo local

### Estilos no cargados
- Limpia la caché del navegador (Ctrl+Shift+Del)
- Verifica la ruta de CSS en el HTML
- Comprueba que `public/css/styles.css` existe

### PIN no registra
- Verifica que la colección `students` tiene datos
- Comprueba que el PIN coincide exactamente (2 dígitos de número + 4 de contraseña)
- Revisa la consola del navegador para errores de red

## Características Implementadas

✅ Página principal con botón grande  
✅ Teclado numérico interactivo  
✅ Validación de PIN en tiempo real  
✅ Registro automático de asistencia  
✅ Panel de administración protegido  
✅ Gestión completa de alumnos  
✅ Consulta de asistencias con filtros  
✅ Interfaz responsive (móvil, tablet, desktop)  
✅ Diseño minimalista con colores vivos  
✅ Base de datos MongoDB  
✅ Código organizado en clases y controladores  
✅ API REST completa  
✅ Validación de inputs en servidor  

## Licencia

Este proyecto está bajo licencia MIT.

## Contacto

Para reportar bugs o sugerencias, contacta al desarrollador.

---

**Versión**: 1.0  
**Última actualización**: 9 de diciembre de 2025
