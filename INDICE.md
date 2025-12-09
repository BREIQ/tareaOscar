# 📑 Índice de Archivos del Proyecto

## 📖 Dónde Comenzar

### 🚀 Si es tu primera vez:
1. **Leer primero**: `QUICK_START.md` ⏱️ 2 minutos
2. **Instalación**: Seguir pasos en `QUICK_START.md` ⏱️ 5 minutos
3. **Pruebas**: Usar credenciales de ejemplo

### 📚 Documentación Completa:
- `README.md` - Guía principal (leer si tienes dudas)
- `INSTALACION_WINDOWS.md` - Si usas Windows
- `API_DOCUMENTATION.md` - Si quieres usar los endpoints
- `ARCHITECTURE.md` - Si quieres entender la arquitectura

---

## 📁 Estructura del Proyecto

### 📄 Archivos de Documentación (10)
```
QUICK_START.md             ← LEER PRIMERO (5 min)
README.md                  ← Documentación completa
INSTALACION_WINDOWS.md     ← Específico para Windows
RESUMEN_IMPLEMENTACION.md  ← Resumen técnico
PROYECTO_COMPLETADO.md     ← Descripción final
ARCHITECTURE.md            ← Diagramas y flujos
API_DOCUMENTATION.md       ← Endpoints API
CONFIG_VARIABLES.md        ← Variables de entorno
CHANGELOG.md               ← Historial de versiones
CONTRIBUTING.md            ← Guía de contribución
```

### 💻 Código Backend (8)
```
config/
└── config.php            Configuración principal (MongoDB URI, admin password)

public/
├── index.php            Punto de entrada principal
├── api.php              Endpoints REST
└── .htaccess            Reescritura de URLs

src/Database/
└── Connection.php       Conexión a MongoDB

src/Models/
├── Student.php          CRUD de estudiantes
└── Attendance.php       Registro de asistencias

src/Controllers/
├── AttendanceController.php    Lógica de registro
└── AdminController.php         Lógica de administración
```

### 🎨 Frontend (5)
```
public/css/
└── styles.css           Estilos responsive (1000+ líneas)

public/js/
├── app.js               Lógica página principal
└── admin.js             Lógica panel administrativo

views/
├── home.php             Página de inicio
└── admin.php            Panel de administración
```

### 🔧 Utilidades (4)
```
src/
├── Autoloader.php       Cargador automático de clases
└── Helpers.php          Funciones auxiliares

database/
└── seed.php             Script para insertar datos de prueba

.gitignore              Archivos a ignorar en Git
```

### ⚖️ Legales (1)
```
LICENSE                 Licencia MIT
```

---

## 📊 Resumen de Archivos

| Categoría | Archivos | Descripción |
|-----------|----------|-------------|
| **Documentación** | 10 | Guías, manuales e instrucciones |
| **Backend PHP** | 8 | Lógica de negocio y controladores |
| **Frontend** | 5 | HTML, CSS, JavaScript |
| **Utilidades** | 4 | Helpers, autoloader, seeding |
| **Configuración** | 3 | .gitignore, LICENSE, .htaccess |
| **TOTAL** | **30** | Archivos y carpetas |

---

## 🎯 Guía de Lectura por Tipo de Usuario

### 👨‍💼 Usuario Ejecutivo
1. `PROYECTO_COMPLETADO.md` - Resumen de qué se entregó
2. `QUICK_START.md` - Cómo empezar rápido
3. `API_DOCUMENTATION.md` - Qué endpoints están disponibles

### 👨‍💻 Desarrollador
1. `QUICK_START.md` - Instalación rápida
2. `ARCHITECTURE.md` - Cómo está estructurado
3. `README.md` - Documentación completa
4. Revisar código en `src/`
5. `API_DOCUMENTATION.md` - Para usar los endpoints

### 🔧 Administrador de Sistemas
1. `INSTALACION_WINDOWS.md` - Pasos de instalación
2. `README.md` - Requisitos técnicos
3. `CONFIG_VARIABLES.md` - Cómo configurar
4. `CONTRIBUTING.md` - Cómo mantener

### 🎓 Estudiante/Aprendiz
1. `QUICK_START.md` - Empezar aquí
2. `ARCHITECTURE.md` - Entender la estructura
3. `README.md` - Detalles completos
4. Revisar código en `src/` y `public/`
5. `CONTRIBUTING.md` - Cómo contribuir

---

## 📍 Ubicación de Características

### Registro de Asistencia
- **Frontend**: `views/home.php`, `public/js/app.js`
- **Backend**: `src/Controllers/AttendanceController.php`
- **Modelo**: `src/Models/Attendance.php`
- **Endpoint**: `POST /api/attendance/register`

### Panel Admin
- **Frontend**: `views/admin.php`, `public/js/admin.js`
- **Backend**: `src/Controllers/AdminController.php`
- **Modelos**: `src/Models/Student.php`, `src/Models/Attendance.php`
- **Endpoints**: Múltiples en `public/api.php`

### Base de Datos
- **Conexión**: `src/Database/Connection.php`
- **Config**: `config/config.php`
- **Seeding**: `database/seed.php`

### Estilos
- **CSS Principal**: `public/css/styles.css`
- **Responsive**: Breakpoints en 768px y 480px
- **Colores**: Variables CSS en líneas 8-16

---

## 🔍 Búsqueda Rápida

### ¿Dónde está...?

| Busco | Archivo |
|-------|---------|
| Conectar a MongoDB | `src/Database/Connection.php` |
| Crear un estudiante | `src/Models/Student.php` |
| Registrar asistencia | `src/Controllers/AttendanceController.php` |
| Teclado numérico | `public/js/app.js` (línea ~20) |
| Tabla de alumnos | `public/js/admin.js` (función loadStudents) |
| Estilos de botones | `public/css/styles.css` (línea ~60) |
| Página principal | `views/home.php` |
| API de registro | `public/api.php` (línea ~10) |
| Validaciones | `src/Models/Student.php` |
| Zona horaria | `config/config.php` |

---

## 🚀 Funcionalidades por Archivo

### config/config.php
```php
✅ URI de MongoDB
✅ Nombre de base de datos
✅ Contraseña de admin
✅ Zona horaria
```

### public/index.php
```php
✅ Punto de entrada principal
✅ Enrutamiento básico
✅ Inclusión de vistas
```

### public/api.php
```php
✅ 10 endpoints REST
✅ POST /attendance/register
✅ POST /admin/validate
✅ GET/POST/PUT/DELETE /admin/students
✅ GET /admin/attendance (con filtros)
```

### src/Models/Student.php
```php
✅ Crear estudiante
✅ Buscar por número
✅ Buscar por número y contraseña
✅ Actualizar estudiante
✅ Eliminar estudiante
✅ Obtener todos
```

### src/Models/Attendance.php
```php
✅ Registrar asistencia
✅ Obtener por estudiante
✅ Obtener por día
✅ Obtener por mes
✅ Obtener por año
```

### public/js/app.js
```javascript
✅ Teclado numérico
✅ Validación de PIN
✅ Registro de asistencia
✅ Manejo de modales
✅ Validación de admin
```

### public/js/admin.js
```javascript
✅ Gestión de alumnos (CRUD)
✅ Carga de tablas
✅ Filtros de asistencia
✅ Modales de edición
✅ Validación de formularios
```

### public/css/styles.css
```css
✅ Diseño responsive
✅ Gradientes modernas
✅ Animaciones suaves
✅ Colores vivos
✅ Breakpoints móvil
```

---

## 📚 Lectura Recomendada

### Nivel 1: Principiante
1. `QUICK_START.md` (5 min)
2. `README.md` - Secciones "Características" y "Uso" (15 min)
3. Probar la aplicación (10 min)

### Nivel 2: Intermedio
1. Todos los anteriores
2. `ARCHITECTURE.md` (15 min)
3. `API_DOCUMENTATION.md` (15 min)
4. Revisar `src/Controllers/` (20 min)

### Nivel 3: Avanzado
1. Todos los anteriores
2. Revisar todo el código en `src/`
3. `CONFIG_VARIABLES.md` (10 min)
4. Modificar y extender el código (1+ horas)

---

## ✅ Checklist de Instalación

- [ ] Leer `QUICK_START.md`
- [ ] Instalar MongoDB
- [ ] Verificar PHP 8+
- [ ] Clonar/descargar proyecto
- [ ] Crear base de datos
- [ ] Iniciar servidor
- [ ] Acceder a http://localhost:8000
- [ ] Probar con PIN 051234
- [ ] Probar panel admin con Oscar9234
- [ ] Leer `README.md` completo

---

## 📞 Soporte Rápido

| Problema | Solución |
|----------|----------|
| MongoDB no conecta | Ver "Solución de Problemas" en README.md |
| Error 404 | Verificar .htaccess y URLs |
| No ve estilos | Limpiar caché (Ctrl+Shift+Del) |
| PHP da error | Verificar extensión mongodb instalada |
| Pin no funciona | Crear base de datos con seed.php |

---

## 🔗 Referencias Rápidas

- **MongoDB**: https://www.mongodb.com/
- **PHP**: https://www.php.net/
- **Apache**: https://httpd.apache.org/
- **MDN Web Docs**: https://developer.mozilla.org/

---

**Última actualización**: 9 de diciembre de 2025  
**Versión**: 1.0.0  
**Estado**: ✅ Completo
