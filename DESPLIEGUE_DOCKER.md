# Guía de Despliegue en Servidor Linux con Docker

## 📋 Requisitos Previos

### En tu máquina local:
- Git instalado
- Docker Desktop (si quieres probar localmente)

### En el servidor Linux:
- Docker instalado
- Docker Compose instalado
- Acceso SSH al servidor
- Mínimo 2GB RAM disponible
- Mínimo 10GB de espacio en disco

---

## 🚀 Pasos de Despliegue

### 1. Preparar el Servidor Linux

```bash
# Conectarse al servidor
ssh usuario@tu-servidor.com

# Actualizar sistema
sudo apt update
sudo apt upgrade -y

# Instalar Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Instalar Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verificar instalación
docker --version
docker-compose --version
```

### 2. Clonar el Proyecto

```bash
# Ir a la carpeta de aplicaciones
cd /opt

# Clonar el repositorio
sudo git clone https://github.com/BREIQ/tareaOscar.git
cd tareaOscar

# Dar permisos
sudo chown -R $USER:$USER .
sudo chmod -R 755 .
```

### 3. Configurar Variables de Entorno (Opcional)

```bash
# Crear archivo .env
cat > .env << EOF
MONGODB_URI=mongodb://mongodb:27017
MONGODB_DATABASE=asistencia_app
ADMIN_PASSWORD=Oscar9234
TIMEZONE=America/Mexico_City
PHP_DISPLAY_ERRORS=0
EOF

# Proteger el archivo
chmod 600 .env
```

### 4. Construir e Iniciar Contenedores

```bash
# Construir imagen
sudo docker-compose build

# Iniciar contenedores
sudo docker-compose up -d

# Verificar que están corriendo
sudo docker-compose ps

# Ver logs
sudo docker-compose logs -f
```

### 5. Inicializar Base de Datos

```bash
# Acceder al contenedor PHP
sudo docker-compose exec php bash

# Dentro del contenedor, ejecutar el seeding
php database/seed.php

# Salir del contenedor
exit
```

### 6. Verificar que está Funcionando

```bash
# Ver logs
sudo docker-compose logs php

# Probar conexión MongoDB
sudo docker-compose exec mongodb mongosh asistencia_app

# En el shell de MongoDB:
> db.students.findOne()
> exit
```

---

## 📍 Acceder a la Aplicación

```
http://tu-servidor.com:8080
```

**O si usas un proxy inverso (Nginx/Apache):**
```
http://tu-servidor.com
```

---

## 🔧 Configuración Avanzada

### A. Nginx como Proxy Inverso (Recomendado)

Crear archivo `nginx.conf`:

```nginx
upstream app {
    server php:80;
}

server {
    listen 80;
    server_name tu-servidor.com;

    location / {
        proxy_pass http://app;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Agregar al `docker-compose.yml`:

```yaml
  nginx:
    image: nginx:alpine
    container_name: asistencia_nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
    networks:
      - asistencia_network
```

### B. Usar HTTPS con Let's Encrypt

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtener certificado
sudo certbot certonly --nginx -d tu-servidor.com

# Configurar renovación automática
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

Actualizar `nginx.conf` con SSL:

```nginx
server {
    listen 80;
    server_name tu-servidor.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name tu-servidor.com;

    ssl_certificate /etc/letsencrypt/live/tu-servidor.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/tu-servidor.com/privkey.pem;

    location / {
        proxy_pass http://app;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;
    }
}
```

### C. Backups Automáticos de MongoDB

```bash
# Crear script de backup
cat > backup_mongodb.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/backups/mongodb"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

docker-compose exec -T mongodb mongodump \
    --uri="mongodb://localhost:27017" \
    --db=asistencia_app \
    --out=$BACKUP_DIR/backup_$TIMESTAMP

# Mantener solo últimos 30 días
find $BACKUP_DIR -type d -mtime +30 -exec rm -rf {} \;
EOF

chmod +x backup_mongodb.sh

# Agregar a cron para ejecutar diariamente
(crontab -l 2>/dev/null; echo "0 2 * * * /opt/tareaOscar/backup_mongodb.sh") | crontab -
```

---

## 📊 Comandos Útiles

### Ver estado de contenedores
```bash
sudo docker-compose ps
```

### Ver logs
```bash
# Todos los logs
sudo docker-compose logs -f

# Solo PHP
sudo docker-compose logs -f php

# Solo MongoDB
sudo docker-compose logs -f mongodb
```

### Acceder a contenedor
```bash
# PHP
sudo docker-compose exec php bash

# MongoDB
sudo docker-compose exec mongodb mongosh asistencia_app
```

### Reiniciar servicios
```bash
# Reiniciar todos
sudo docker-compose restart

# Reiniciar solo PHP
sudo docker-compose restart php
```

### Detener servicios
```bash
# Detener sin eliminar
sudo docker-compose stop

# Detener y eliminar
sudo docker-compose down
```

### Actualizar código
```bash
# Traer cambios
git pull

# Reconstruir imagen
sudo docker-compose build

# Reiniciar
sudo docker-compose up -d
```

---

## 🔐 Seguridad

### 1. Cambiar Contraseña de Admin

```bash
# En el servidor
sudo docker-compose exec php bash

# Editar config/config.php
vi config/config.php

# Cambiar la línea:
# define('ADMIN_PASSWORD', 'Oscar9234');

# A tu contraseña:
# define('ADMIN_PASSWORD', 'TuContraseñaSegura');

exit
```

### 2. Usar Variables de Entorno Seguras

```bash
# Crear archivo .env seguro
sudo cat > /opt/tareaOscar/.env << EOF
MONGODB_URI=mongodb://mongodb:27017
MONGODB_DATABASE=asistencia_app
ADMIN_PASSWORD=$(openssl rand -base64 12)
TIMEZONE=America/Mexico_City
PHP_DISPLAY_ERRORS=0
EOF

sudo chmod 600 .env
```

### 3. Firewall

```bash
# Permitir solo puertos necesarios
sudo ufw allow 22/tcp     # SSH
sudo ufw allow 80/tcp     # HTTP
sudo ufw allow 443/tcp    # HTTPS
sudo ufw enable
```

### 4. Monitoreo

```bash
# Instalar Portainer para UI Docker
docker volume create portainer_data
docker run -d \
  -p 8000:8000 \
  -p 9000:9000 \
  --name=portainer \
  --restart=always \
  -v /var/run/docker.sock:/var/run/docker.sock \
  -v portainer_data:/data \
  portainer/portainer-ce
```

Acceder a: `http://tu-servidor.com:9000`

---

## 🐛 Solución de Problemas

### MongoDB no conecta
```bash
# Verificar que MongoDB está corriendo
sudo docker-compose ps

# Ver logs
sudo docker-compose logs mongodb

# Reiniciar
sudo docker-compose restart mongodb
```

### PHP no inicia
```bash
# Ver logs detallados
sudo docker-compose logs php

# Reconstruir
sudo docker-compose build --no-cache
sudo docker-compose up -d
```

### Permisos denegados
```bash
# Dar permisos correctos
sudo chown -R www-data:www-data /opt/tareaOscar
sudo chmod -R 755 /opt/tareaOscar
```

### Puerto 8080 ya en uso
```bash
# Cambiar puerto en docker-compose.yml
# De:  "8080:80"
# A:   "8081:80"

sudo docker-compose restart
```

---

## 📈 Monitoreo y Mantenimiento

### Ver uso de recursos
```bash
sudo docker stats
```

### Limpiar datos no usados
```bash
sudo docker system prune -a
```

### Backup manual
```bash
sudo docker-compose exec mongodb mongodump \
    --uri="mongodb://localhost:27017/asistencia_app" \
    --out=/backups/asistencia_$(date +%Y%m%d)
```

### Restaurar desde backup
```bash
sudo docker-compose exec mongodb mongorestore \
    --uri="mongodb://localhost:27017/asistencia_app" \
    /backups/asistencia_20251209
```

---

## 📞 Checklist de Despliegue

- [ ] Servidor Linux preparado
- [ ] Docker y Docker Compose instalados
- [ ] Proyecto clonado
- [ ] Variables de entorno configuradas
- [ ] Contenedores construidos
- [ ] Contenedores iniciados
- [ ] Base de datos seeded
- [ ] Aplicación accesible
- [ ] HTTPS configurado (recomendado)
- [ ] Backups automáticos configurados
- [ ] Firewall configurado
- [ ] Monitoreo activado

---

## 🎯 URLs de Acceso

```
Aplicación: http://tu-servidor.com:8080
Admin:      http://tu-servidor.com:8080 (Contraseña: Oscar9234)
MongoDB:    mongodb://mongodb:27017
Portainer:  http://tu-servidor.com:9000 (opcional)
```

---

**Versión**: 1.0  
**Última actualización**: 9 de diciembre de 2025
