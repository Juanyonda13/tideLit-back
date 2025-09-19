# TideLit Backend API

Una API REST desarrollada con Symfony 6.4 para la gestión de libros y reseñas, implementando Clean Architecture y principios CQRS.

## Descripción

TideLit Backend es un sistema de gestión de libros que permite:
- Listar libros con sus ratings promedio
- Crear reseñas para libros existentes
- Validación robusta de datos de entrada
- Persistencia en base de datos MySQL

## Arquitectura

El proyecto implementa Clean Architecture con las siguientes capas:

- **Domain**: Entidades de negocio puras (Book, Review)
- **Application**: Casos de uso y DTOs (Handlers, DTOs)
- **Infrastructure**: Implementaciones técnicas (Controllers, Repositories, Migrations)

## Estructura del Proyecto

```
src/
├── Domain/                           # Entidades de negocio
│   ├── Book.php                     # Entidad Libro
│   └── Review.php                   # Entidad Reseña
├── Application/                     # Lógica de aplicación
│   ├── Dto/                         # Data Transfer Objects
│   │   └── CreateReviewDto.php      # DTO para crear reseñas
│   └── Service/                     # Handlers/Casos de uso
│       ├── CreateReviewHandler.php  # Crear reseña
│       └── ListBooksWithAvgHandler.php # Listar libros con promedio
└── Infrastructure/                  # Implementaciones técnicas
    ├── Controller/                  # Controladores REST
    │   ├── BooksGetController.php   # GET /api/books
    │   └── ReviewsPostController.php # POST /api/reviews
    ├── Persistence/                 # Persistencia de datos
    │   └── Doctrine/
    │       ├── Repository/          # Repositorios
    │       │   ├── BookRepository.php
    │       │   └── ReviewRepository.php
    │       └── Migration/           # Migraciones DB
    │           └── Version20250918120000_CreateBooksAndReviews.php
    └── Fixtures/                    # Datos de prueba
        └── AppFixtures.php
```

## Requisitos del Sistema

- PHP 8.1 o superior
- Composer
- MySQL 8.0 o superior
- Symfony CLI (opcional)

## Instalación

### 1. Clonar el repositorio

```bash
git clone <repository-url>
cd tideLit-back
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

Crear o editar el archivo `.env` con las credenciales de tu base de datos:

```env
DB_USER=tu_usuario_mysql
DB_PASSWORD=tu_password_mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=tideLit_db
DATABASE_URL="mysql://%env(DB_USER)%:%env(DB_PASSWORD)%@%env(DB_HOST)%:%env(DB_PORT)%/%env(DB_NAME)%?serverVersion=8.0.32&charset=utf8mb4"
```

### 4. Crear la base de datos

```bash
php bin/console doctrine:database:create
```

### 5. Ejecutar migraciones

```bash
php bin/console doctrine:migrations:migrate
```

### 6. Cargar datos de prueba (opcional)

```bash
php bin/console doctrine:fixtures:load
```

## Uso

### Con Docker

```bash
# Iniciar todos los servicios
docker-compose up -d

# Ver logs en tiempo real
docker-compose logs -f

# Parar servicios
docker-compose down

# Reiniciar servicios
docker-compose restart

# Acceder al contenedor de la aplicación
docker-compose exec app bash
```

### Instalación local

```bash
# Opción 1: Symfony CLI
symfony serve

# Opción 2: Servidor PHP integrado
php -S localhost:8000 -t public

# Opción 3: Comando de Symfony
php bin/console server:run
```

El servidor estará disponible en `http://localhost:8000`

## API Endpoints

### GET /api/books

Lista todos los libros con su rating promedio.

**Respuesta:**
```json
[
  {
    "title": "Clean Code",
    "author": "Robert C. Martin",
    "published_year": 2008,
    "average_rating": 4.50
  }
]
```

### POST /api/reviews

Crea una nueva reseña para un libro.

**Request Body:**
```json
{
  "book_id": 1,
  "rating": 5,
  "comment": "Excelente libro sobre programación"
}
```

**Respuesta:**
```json
{
  "id": 7,
  "created_at": "2024-01-15T10:30:00+00:00"
}
```

**Errores de validación:**
```json
{
  "errors": [
    "book_id: This value should be positive.",
    "rating: This value should be between 1 and 5."
  ]
}
```

## Modelo de Datos

### Tabla `book`
- `id`: Clave primaria (auto-increment)
- `title`: Título del libro (255 caracteres, indexado)
- `author`: Autor del libro (255 caracteres, indexado)
- `published_year`: Año de publicación

### Tabla `review`
- `id`: Clave primaria (auto-increment)
- `book_id`: Clave foránea hacia `book.id` (con CASCADE DELETE)
- `rating`: Calificación de 1 a 5 estrellas
- `comment`: Comentario de la reseña (texto)
- `created_at`: Fecha de creación (timestamp)

## Validaciones

### CreateReviewDto
- `book_id`: Requerido, debe ser un número positivo
- `rating`: Requerido, debe estar entre 1 y 5
- `comment`: Requerido, no puede estar vacío

## Comandos Útiles

```bash
# Limpiar caché
php bin/console cache:clear

# Ver rutas disponibles
php bin/console debug:router

# Ver configuración de Doctrine
php bin/console doctrine:mapping:info

# Generar nueva migración
php bin/console doctrine:migrations:diff

# Verificar estado de migraciones
php bin/console doctrine:migrations:status
```

## CI/CD y Despliegue Automático

El proyecto utiliza **GitHub Actions** para CI/CD automático con despliegue a servidor Hostinger.

### Archivos de CI/CD

- `.github/workflows/deploy.yml`: Workflow de despliegue automático
- `.github/workflows/ci.yml`: Workflow de tests y verificaciones
- `.github/SECRETS.md`: Configuración de secrets de GitHub
- `docker-compose.prod.yml`: Configuración de producción con red webproxy
- `Dockerfile.prod`: Imagen optimizada para producción

### Configuración de GitHub Secrets

Para activar el CI/CD, configura estos secrets en GitHub:

1. **SERVER_HOST**: `168.231.71.181`
2. **SERVER_USER**: `root`
3. **SERVER_SSH_KEY**: Tu clave SSH privada

Ver `.github/SECRETS.md` para instrucciones detalladas.

### Flujo de CI/CD

#### En cada Pull Request:
- ✅ Tests automáticos
- ✅ Verificaciones de código
- ✅ Build de Docker
- ✅ Verificaciones de seguridad

#### En push a main/master:
- ✅ Todos los tests
- ✅ Despliegue automático al servidor
- ✅ Migraciones automáticas
- ✅ Verificación del despliegue

### Despliegue Manual (si es necesario)

```bash
# Conectar al servidor
ssh root@168.231.71.181

# Ir al directorio del proyecto
cd /root/projects/symfony-backend

# Actualizar código
git pull origin main

# Reiniciar servicios
docker compose -f docker-compose.prod.yml restart

# Verificar estado
docker ps | grep symfony-backend
```

### Configuración de Nginx Proxy Manager

1. Accede a: `http://168.231.71.181:8080`
2. Credenciales: `admin@example.com` / `changeme`
3. Crear Proxy Host:
   - Domain: `api.tu-dominio.com`
   - Forward Hostname/IP: `symfony-backend-app-1`
   - Forward Port: `8000`
4. Habilitar SSL con Let's Encrypt

## Tecnologías Utilizadas

- **Symfony 6.4**: Framework PHP
- **Doctrine ORM 3.5**: Mapeo objeto-relacional
- **MySQL**: Base de datos relacional
- **Symfony Validator**: Validación de datos
- **Doctrine Migrations**: Versionado de esquema de base de datos
- **Doctrine Fixtures**: Carga de datos de prueba
- **Docker**: Containerización
- **Nginx Proxy Manager**: Proxy reverso con SSL
- **GitHub Actions**: CI/CD automático

## Desarrollo

### Estructura de Commits

El proyecto sigue convenciones de commits semánticos:
- `feat:` Nueva funcionalidad
- `fix:` Corrección de bugs
- `docs:` Cambios en documentación
- `refactor:` Refactorización de código
- `test:` Añadir o modificar tests

### Testing

Para ejecutar los tests (cuando estén implementados):

```bash
php bin/phpunit
```

## Licencia

Este proyecto es de uso privado. Todos los derechos reservados.

## Soporte

Para reportar bugs o solicitar nuevas funcionalidades, contactar al equipo de desarrollo.
