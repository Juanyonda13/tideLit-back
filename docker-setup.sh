#!/bin/bash

# Script de configuración inicial para Docker
# TideLit Backend API

echo "🐳 Configurando TideLit Backend con Docker..."

# Verificar que Docker esté instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker no está instalado. Por favor instala Docker primero."
    exit 1
fi

if ! docker compose version &> /dev/null; then
    echo "❌ Docker Compose no está instalado. Por favor instala Docker Compose primero."
    exit 1
fi

# Construir las imágenes
echo "🔨 Construyendo imagen de la aplicación..."
docker compose build

# Iniciar los servicios
echo "🚀 Iniciando servicios..."
docker compose up -d

# Esperar a que la base de datos esté lista
echo "⏳ Esperando a que la base de datos esté lista..."
sleep 15

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction

# Cargar datos de prueba
echo "🌱 Cargando datos de prueba..."
docker compose exec app php bin/console doctrine:fixtures:load --no-interaction

# Limpiar caché
echo "🧹 Limpiando caché..."
docker compose exec app php bin/console cache:clear

echo "✅ ¡Configuración completada!"
echo ""
echo "🌐 La aplicación está disponible en: http://localhost:8000"
echo "🗄️  MySQL está disponible en: localhost:3306"
echo ""
echo "📋 Comandos útiles:"
echo "  - Ver logs: docker compose logs -f"
echo "  - Parar servicios: docker compose down"
echo "  - Reiniciar: docker compose restart"
echo "  - Acceder al contenedor: docker compose exec app bash"
echo ""
echo "🎯 Endpoints disponibles:"
echo "  - GET http://localhost:8000/api/books"
echo "  - POST http://localhost:8000/api/reviews"
