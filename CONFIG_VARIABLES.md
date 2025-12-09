# Configuración de Variables de Entorno

Este archivo muestra cómo configurar la aplicación usando variables de entorno.
En producción, es recomendable usar un archivo `.env` en lugar de hardcoding.

## Pasos para usar variables de entorno

### 1. Crear archivo `.env` (raíz del proyecto)

```env
# MongoDB
MONGODB_URI=mongodb://localhost:27017
MONGODB_DATABASE=asistencia_app

# Admin
ADMIN_PASSWORD=Oscar9234

# Timezone
TIMEZONE=America/Mexico_City

# Environment
ENVIRONMENT=development
DEBUG=true
```

### 2. Instalar paquete dotenv (opcional)

Si deseas usar un paquete para cargar variables de entorno:

```bash
composer require vlucas/phpdotenv
```

### 3. Modificar `config/config.php`

```php
<?php

// Cargar variables de entorno (si usas composer)
// require_once __DIR__ . '/../vendor/autoload.php';
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
// $dotenv->load();

// O cargar manualmente desde .env
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return;
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parsear variable
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remover comillas si existen
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            $_ENV[$key] = $value;
        }
    }
}

// Cargar archivo .env
loadEnv(__DIR__ . '/../.env');

// Usar variables de entorno con fallback
define('MONGODB_URI', $_ENV['MONGODB_URI'] ?? 'mongodb://localhost:27017');
define('MONGODB_DATABASE', $_ENV['MONGODB_DATABASE'] ?? 'asistencia_app');
define('ADMIN_PASSWORD', $_ENV['ADMIN_PASSWORD'] ?? 'Oscar9234');

$timezone = $_ENV['TIMEZONE'] ?? 'America/Mexico_City';
date_default_timezone_set($timezone);

$debug = isset($_ENV['DEBUG']) && $_ENV['DEBUG'] === 'true';
if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
```

## Variables Disponibles

| Variable | Descripción | Valor Defecto |
|----------|------------|---------------|
| `MONGODB_URI` | URI de conexión a MongoDB | mongodb://localhost:27017 |
| `MONGODB_DATABASE` | Nombre de la base de datos | asistencia_app |
| `ADMIN_PASSWORD` | Contraseña del panel admin | Oscar9234 |
| `TIMEZONE` | Zona horaria | America/Mexico_City |
| `ENVIRONMENT` | Ambiente (development/production) | development |
| `DEBUG` | Mostrar errores (true/false) | false |

## Seguridad

**En Producción:**

1. ✅ Nunca commitear `.env` a Git
2. ✅ Agregar `.env` a `.gitignore`
3. ✅ Usar variables de entorno del sistema operativo
4. ✅ Usar gestor de secretos (AWS Secrets Manager, HashiCorp Vault, etc.)
5. ✅ Usar hash para la contraseña de admin en lugar de texto plano

### Ejemplo: Hash de contraseña

```php
// En config.php
$adminPassword = $_ENV['ADMIN_PASSWORD'] ?? 'Oscar9234';
define('ADMIN_PASSWORD_HASH', password_hash($adminPassword, PASSWORD_BCRYPT));

// En AdminController.php
public function validatePassword(string $password): bool {
    return password_verify($password, ADMIN_PASSWORD_HASH);
}
```

### Ejemplo: .gitignore

```
.env
.env.local
.env.*.local
/vendor/
/node_modules/
```

## Variables de Entorno en Diferentes Plataformas

### Linux/macOS

```bash
# Exportar en .bashrc o .zshrc
export MONGODB_URI="mongodb://localhost:27017"
export ADMIN_PASSWORD="Oscar9234"
```

### Windows PowerShell

```powershell
$env:MONGODB_URI="mongodb://localhost:27017"
$env:ADMIN_PASSWORD="Oscar9234"
```

### Apache/cPanel

En el archivo `.htaccess`:

```apache
SetEnv MONGODB_URI "mongodb://localhost:27017"
SetEnv ADMIN_PASSWORD "Oscar9234"
```

### Docker

En `docker-compose.yml`:

```yaml
environment:
  - MONGODB_URI=mongodb://mongo:27017
  - MONGODB_DATABASE=asistencia_app
  - ADMIN_PASSWORD=Oscar9234
```

---

**Nota:** Estas son opcionales. La aplicación funciona con valores por defecto.
