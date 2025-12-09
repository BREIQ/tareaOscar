#!/bin/bash

# Script de despliegue automático para Docker
# Uso: bash deploy.sh

set -e

echo "╔════════════════════════════════════════════════════════════╗"
echo "║      SCRIPT DE DESPLIEGUE - ASISTENCIA APP                ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para imprimir con color
print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_info() {
    echo -e "${YELLOW}ℹ${NC} $1"
}

# 1. Verificar Docker
echo "1. Verificando Docker..."
if command -v docker &> /dev/null; then
    print_status "Docker instalado"
else
    print_error "Docker no encontrado"
    echo "   Instala Docker: curl -fsSL https://get.docker.com -o get-docker.sh && sudo sh get-docker.sh"
    exit 1
fi

# 2. Verificar Docker Compose
echo "2. Verificando Docker Compose..."
if command -v docker-compose &> /dev/null; then
    print_status "Docker Compose instalado"
else
    print_error "Docker Compose no encontrado"
    exit 1
fi

# 3. Detener contenedores anteriores
echo "3. Deteniendo contenedores anteriores..."
docker-compose down 2>/dev/null || true
print_status "Contenedores detenidos"

# 4. Construir imagen
echo "4. Construyendo imagen Docker..."
docker-compose build
print_status "Imagen construida"

# 5. Iniciar contenedores
echo "5. Iniciando contenedores..."
docker-compose up -d
print_status "Contenedores iniciados"

# 6. Esperar a que MongoDB esté listo
echo "6. Esperando a MongoDB..."
sleep 10
print_status "MongoDB listo"

# 7. Ejecutar seeding
echo "7. Inicializando base de datos..."
docker-compose exec -T php php database/seed.php
print_status "Base de datos inicializada"

# 8. Verificar estado
echo "8. Verificando estado..."
docker-compose ps
print_status "Verificación completada"

echo ""
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                  ¡DESPLIEGUE EXITOSO!                     ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo "Acceder a la aplicación:"
echo "  URL: http://localhost:8080"
echo ""
echo "Credenciales de prueba:"
echo "  PIN: 051234"
echo "  Admin: Oscar9234"
echo ""
echo "Ver logs:"
echo "  docker-compose logs -f"
echo ""
