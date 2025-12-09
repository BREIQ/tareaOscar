# Guía Rápida de Instalación - Windows

## Pasos para ejecutar la aplicación en Windows

### 1. Instalar MongoDB Community

1. Descargar desde: https://www.mongodb.com/try/download/community
2. Seleccionar **Windows x64** y descargar
3. Ejecutar el instalador `.msi`
4. Seguir el asistente (dejar opciones por defecto)
5. MongoDB se instalará como servicio de Windows

### 2. Verificar MongoDB está corriendo

1. Abre PowerShell como administrador
2. Ejecuta:
```powershell
mongosh
```

Si ves una conexión exitosa, MongoDB está funcionando.

### 3. Instalar PHP con MongoDB Driver

**Opción A: Usar XAMPP (Recomendado para principiantes)**

1. Descargar XAMPP desde: https://www.apachefriends.org/
2. Instalar (elegir las opciones de Apache, PHP, MySQL)
3. Abrir **XAMPP Control Panel**
4. Iniciar **Apache** haciendo clic en "Start"

**Opción B: PHP Standalone**

1. Descargar PHP desde: https://windows.php.net/download/
2. Extraer en `C:\php` (o ruta de tu preferencia)
3. Instalar MongoDB Driver:
   - Descargar desde: https://pecl.php.net/package/mongodb
   - O usar: `pecl install mongodb`

### 4. Configurar el proyecto

1. Clonar o descargar el proyecto en:
   - Con XAMPP: `C:\xampp\htdocs\tareaOscar`
   - Sin XAMPP: Cualquier directorio

2. Copiar el proyecto a la carpeta correcta

### 5. Crear la base de datos

1. Abrir PowerShell o CMD
2. Conectar a MongoDB:
```bash
mongosh
```

3. Ejecutar estos comandos:
```javascript
use asistencia_app

db.students.insertOne({
    "name": "Juan Pérez",
    "student_number": "05",
    "password": "1234"
})
```

### 6. Iniciar la aplicación

**Con XAMPP:**
1. Abrir XAMPP Control Panel
2. Hacer clic en "Start" en Apache
3. Ir a: `http://localhost/tareaOscar/public`

**Sin XAMPP (PHP Built-in Server):**
1. Abrir PowerShell o CMD
2. Navegar a la carpeta del proyecto:
```bash
cd C:\ruta\del\proyecto\public
```

3. Ejecutar:
```bash
php -S localhost:8000
```

4. Abrir navegador: `http://localhost:8000`

### 7. Usar la aplicación

**Registrar Asistencia:**
- Botón: "Registrar Asistencia"
- PIN ejemplo: `051234` (alumno 05, contraseña 1234)

**Panel Admin:**
- Botón: "Acceso Admin"
- Contraseña: `Oscar9234`

---

## Solución de Problemas Comunes

### MongoDB no inicia

1. Abre Services (services.msc)
2. Busca "MongoDB Server"
3. Si está detenido, haz clic derecho → "Start"

### PHP no encuentra MongoDB

1. Editar `php.ini`
2. Agregar al final:
```
extension=mongodb
```
3. Reiniciar Apache

### Página muestra error 404

1. Verificar que la ruta es correcta
2. Asegurarse que se accede a través de `/public`
3. Limpiar caché del navegador (Ctrl+Shift+Del)

### No me deja crear alumnos

1. Verificar MongoDB está corriendo
2. Verificar la base de datos existe:
   ```
   mongosh
   show databases
   use asistencia_app
   ```

---

## Contacto

Para ayuda adicional, consultar el README.md principal.
