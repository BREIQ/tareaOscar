# 🐳 Despliegue Docker - Resumen Rápido

## ⚡ Despliegue en 10 Minutos

### En tu servidor Linux:

```bash
# 1. Conectarse al servidor
ssh usuario@tu-servidor.com

# 2. Instalar Docker (si no está instalado)
curl -fsSL https://get.docker.com | sudo sh
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# 3. Clonar proyecto
cd /opt
sudo git clone https://github.com/BREIQ/tareaOscar.git
cd tareaOscar

# 4. Desplegar (opción simple)
sudo docker-compose up -d

# 5. Inicializar BD
sudo docker-compose exec php php database/seed.php

# ¡Listo! Accede a: http://tu-servidor.com:8080
```

---

## 📁 Archivos de Despliegue Incluidos

| Archivo | Propósito |
|---------|-----------|
| **Dockerfile** | Define imagen Docker con PHP + Apache |
| **docker-compose.yml** | Orquestación de contenedores (dev/test) |
| **docker-compose.prod.yml** | Configuración para producción con Nginx |
| **nginx.conf** | Configuración de Nginx como proxy |
| **apache.conf** | Configuración de Apache |
| **.env.example** | Variables de entorno (copia a .env) |
| **deploy.sh** | Script automatizado de despliegue |
| **monitor.sh** | Monitoreo continuo y backups |
| **DESPLIEGUE_DOCKER.md** | Guía completa (80+ líneas) |

---

## 🎯 Opciones de Despliegue

### Opción 1: Despliegue Simple (Desarrollo/Test)
```bash
sudo docker-compose up -d
# Puerto: 8080
# Acceso: http://tu-servidor.com:8080
```

### Opción 2: Con Nginx (Recomendado)
```bash
sudo docker-compose -f docker-compose.prod.yml up -d
# Puerto: 80 y 443
# Acceso: http://tu-servidor.com
```

### Opción 3: Despliegue Automatizado
```bash
bash deploy.sh
# Ejecuta todo automáticamente
```

---

## 🔐 Seguridad en Producción

### 1. Cambiar Contraseña Admin
```bash
sudo docker-compose exec php nano config/config.php
# Cambiar: define('ADMIN_PASSWORD', 'Oscar9234');
```

### 2. HTTPS con Let's Encrypt
```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtener certificado
sudo certbot certonly --nginx -d tu-dominio.com

# Actualizar nginx.conf con rutas SSL
# Reiniciar: sudo docker-compose restart nginx
```

### 3. Firewall
```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

## 📊 Comandos Esenciales

```bash
# Ver estado
sudo docker-compose ps

# Ver logs
sudo docker-compose logs -f

# Reiniciar
sudo docker-compose restart

# Acceder a contenedor
sudo docker-compose exec php bash

# Backup MongoDB
sudo docker-compose exec mongodb mongodump \
    --uri="mongodb://localhost:27017" \
    --db=asistencia_app \
    --out=/backups/asistencia_$(date +%Y%m%d)

# Actualizar código
git pull && sudo docker-compose build && sudo docker-compose restart
```

---

## 📈 Monitoreo

### Ejecutar monitoreo cada 5 minutos
```bash
# Hacer script ejecutable
chmod +x monitor.sh

# Agregar a crontab
(crontab -l 2>/dev/null; echo "*/5 * * * * cd /opt/tareaOscar && bash monitor.sh") | crontab -
```

Comprueba el log:
```bash
tail -f /var/log/asistencia-monitor.log
```

---

## 🔧 Solución de Problemas

| Problema | Solución |
|----------|----------|
| Puerto 8080 en uso | Cambiar en docker-compose.yml: `"8081:80"` |
| MongoDB no conecta | Ver logs: `sudo docker-compose logs mongodb` |
| Permisos denegados | `sudo chown -R $USER:$USER /opt/tareaOscar` |
| Certificado SSL no funciona | Asegurar ruta correcta en nginx.conf |

---

## 📍 URLs de Acceso

```
Aplicación:  http://tu-servidor.com:8080 (o :80 con Nginx)
Admin:       misma URL + Contraseña: Oscar9234
MongoDB:     mongodb://mongodb:27017
Logs:        sudo docker-compose logs -f
Portainer:   http://tu-servidor.com:9000 (opcional)
```

---

## ✅ Checklist de Despliegue

- [ ] Docker y Docker Compose instalados
- [ ] Proyecto clonado en /opt/tareaOscar
- [ ] Variables de entorno configuradas (.env)
- [ ] Contenedores iniciados: `sudo docker-compose up -d`
- [ ] Base de datos seeded: `sudo docker-compose exec php php database/seed.php`
- [ ] Aplicación accesible: http://tu-servidor.com:8080
- [ ] Firewall configurado
- [ ] Backups automáticos configurados
- [ ] Monitoreo activo (opcional)
- [ ] HTTPS configurado (recomendado)

---

## 📞 Referencia Rápida

```bash
# Iniciar
sudo docker-compose up -d

# Detener
sudo docker-compose down

# Logs
sudo docker-compose logs -f

# Ejecutar comando
sudo docker-compose exec php php archivo.php

# Reconstruir
sudo docker-compose build --no-cache

# Limpieza
sudo docker system prune -a
```

---

## 🎓 Recursos

- **Documentación completa**: Ver `DESPLIEGUE_DOCKER.md`
- **Dockerfile**: `Dockerfile` (configurable)
- **Compose files**: `docker-compose.yml`, `docker-compose.prod.yml`
- **Nginx**: `nginx.conf` (personalizable)
- **Monitoreo**: `monitor.sh` (automático)

---

**¡Tu aplicación está lista para producción! 🚀**

Próximos pasos:
1. Lee `DESPLIEGUE_DOCKER.md` para detalles
2. Configura tu dominio DNS
3. Instala certificados SSL
4. Activa monitoreo
5. Haz backups regulares

