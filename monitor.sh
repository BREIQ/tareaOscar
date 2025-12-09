#!/bin/bash

# Script para monitorear y mantener la aplicación en producción

set -e

LOG_FILE="/var/log/asistencia-monitor.log"
ALERT_EMAIL="admin@example.com"

# Función de logging
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" >> $LOG_FILE
}

# Función para enviar alertas
alert() {
    log "ALERTA: $1"
    # echo "$1" | mail -s "Alerta Asistencia App" $ALERT_EMAIL
}

# 1. Verificar que los contenedores están corriendo
check_containers() {
    log "Verificando contenedores..."
    
    containers=("asistencia_mongodb" "asistencia_app" "asistencia_nginx")
    
    for container in "${containers[@]}"; do
        if ! docker ps | grep -q $container; then
            alert "Contenedor $container no está corriendo"
            docker-compose up -d
        fi
    done
}

# 2. Verificar espacio en disco
check_disk() {
    log "Verificando espacio en disco..."
    
    usage=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
    
    if [ $usage -gt 80 ]; then
        alert "Uso de disco: ${usage}%"
        # Limpiar logs viejos
        find /var/log -name "*.log" -mtime +30 -delete
    fi
}

# 3. Verificar MongoDB
check_mongodb() {
    log "Verificando MongoDB..."
    
    if ! docker-compose exec -T mongodb mongosh --eval "db.adminCommand('ping')" > /dev/null 2>&1; then
        alert "MongoDB no responde"
        docker-compose restart mongodb
    fi
}

# 4. Verificar respuesta HTTP
check_http() {
    log "Verificando HTTP..."
    
    response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080)
    
    if [ $response -ne 200 ]; then
        alert "HTTP retorna: $response"
    fi
}

# 5. Limpieza de logs
cleanup_logs() {
    log "Limpiando logs antiguos..."
    find /var/lib/docker/containers -name "*.log" -mtime +30 -delete
}

# 6. Backup de MongoDB
backup_mongodb() {
    log "Haciendo backup de MongoDB..."
    
    BACKUP_DIR="/backups/mongodb"
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    mkdir -p $BACKUP_DIR
    
    docker-compose exec -T mongodb mongodump \
        --uri="mongodb://localhost:27017" \
        --db=asistencia_app \
        --out=$BACKUP_DIR/backup_$TIMESTAMP
    
    # Mantener solo últimos 30 días
    find $BACKUP_DIR -type d -mtime +30 -exec rm -rf {} \;
    
    log "Backup completado: $BACKUP_DIR/backup_$TIMESTAMP"
}

# Ejecutar todos los chequeos
main() {
    log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    log "Iniciando monitoreo"
    
    check_containers
    check_disk
    check_mongodb
    check_http
    cleanup_logs
    
    # Backup diario (ejecutar a las 2 AM)
    if [ "$(date +%H)" = "02" ]; then
        backup_mongodb
    fi
    
    log "Monitoreo completado"
    log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
}

# Ejecutar
main

# Agregar a crontab para ejecutar cada 5 minutos:
# */5 * * * * cd /opt/tareaOscar && bash monitor.sh
