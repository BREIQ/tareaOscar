# 📋 Sistema de Asistencia - Inicio Rápido

## ⚡ Instalación Express (5 minutos)

### 1️⃣ Requisitos Previos
```bash
# Verificar que tienes MongoDB corriendo
mongosh

# Verificar PHP 8+
php -v
```

### 2️⃣ Clonar Proyecto
```bash
git clone https://github.com/BREIQ/tareaOscar.git
cd tareaOscar
```

### 3️⃣ Crear Base de Datos
```bash
mongosh
use asistencia_app
db.students.insertOne({"name":"Juan Pérez","student_number":"05","password":"1234"})
exit
```

### 4️⃣ Iniciar Servidor
```bash
cd public
php -S localhost:8000
```

### 5️⃣ Abrir en Navegador
```
http://localhost:8000
```

---

## 🎯 Uso Inmediato

### Registrar Asistencia
1. Presiona "Registrar Asistencia"
2. Ingresa PIN: **051234** (alumno 05, contraseña 1234)
3. ¡Listo! Se registró la asistencia

### Acceder a Admin
1. Presiona "Acceso Admin"
2. Contraseña: **Oscar9234**
3. Ahora puedes gestionar alumnos y consultar asistencias

---

## 📁 Archivos Importantes

| Archivo | Propósito |
|---------|----------|
| `public/index.php` | Punto de entrada |
| `public/api.php` | Endpoints API |
| `config/config.php` | Configuración |
| `database/seed.php` | Datos de prueba |
| `README.md` | Documentación completa |

---

## 🔧 Configuración Avanzada

### Cambiar Contraseña de Admin
Edita `config/config.php`:
```php
define('ADMIN_PASSWORD', 'TuNuevaContraseña');
```

### Cambiar Base de Datos
Edita `config/config.php`:
```php
define('MONGODB_URI', 'mongodb://usuario:pass@host:27017');
define('MONGODB_DATABASE', 'otra_db');
```

---

## 📚 Documentación

- **README.md** - Guía completa
- **INSTALACION_WINDOWS.md** - Pasos para Windows
- **API_DOCUMENTATION.md** - Endpoints API
- **CHANGELOG.md** - Versiones y cambios
- **CONFIG_VARIABLES.md** - Variables de entorno

---

## ❓ Preguntas Frecuentes

**P: ¿MongoDB no conecta?**  
R: Asegúrate que MongoDB está corriendo (`mongosh`)

**P: ¿Error 404 en API?**  
R: Verifica que estás usando la URL correcta (`/api/...`)

**P: ¿Puedo cambiar los colores?**  
R: Sí, edita `public/css/styles.css` líneas 8-16

**P: ¿Cómo agrego más alumnos?**  
R: En Admin → Nuevo Alumno, o ejecuta `php database/seed.php`

---

## 📱 Características

✅ Teclado numérico interactivo  
✅ Registro automático con timestamp  
✅ Panel admin protegido  
✅ Gestión de alumnos (CRUD)  
✅ Consultas de asistencia (día/mes/año)  
✅ Interfaz responsive  
✅ Diseño moderno con gradientes  
✅ API REST completa  
✅ Código organizado  
✅ Documentación completa  

---

## 🚀 Próximos Pasos

1. ✅ Instalar y probar
2. 📖 Leer README.md para detalles
3. 🔧 Personalizar según necesidades
4. 📊 Agregar más alumnos
5. 🌐 Desplegar en servidor

---

## 📞 Soporte

Consulta los siguientes archivos para más información:
- Problemas técnicos → README.md (Solución de Problemas)
- Uso de API → API_DOCUMENTATION.md
- Configuración avanzada → CONFIG_VARIABLES.md
- Instalación en Windows → INSTALACION_WINDOWS.md

---

**¡Listo para usar! 🎉**

*Versión 1.0.0 | Diciembre 2025*
