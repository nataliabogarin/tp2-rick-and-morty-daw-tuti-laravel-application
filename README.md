# Rick & Morty Explorer – Trabajo Práctico N.º 2

**Universidad Tecnológica Nacional – Facultad Regional Santa Fe**
**Tecnicatura Universitaria en Tecnologías de la Información**
**Desarrollo de Aplicaciones Web – 2026**

## Descripción

Aplicación web desarrollada con Laravel a partir de la base de datos construida en el Trabajo Práctico N.º 1. Permite explorar, consultar y organizar la información sobre personajes, episodios y ubicaciones del universo de *Rick and Morty*.

Este documento explica cómo instalar y ejecutar el proyecto en un entorno local de desarrollo.

## Funcionalidades

- **Pantalla principal del explorador:** retoma la interfaz del Trabajo Práctico N.º 1, con filtro por temporada, buscador de personajes y panel de detalle que se actualiza sin recargar la página.
- **Explorador público:** listado y detalle de personajes, episodios y ubicaciones, con navegación entre sus relaciones y paginación de los resultados.
- **Registro y autenticación de usuarios:** registro, inicio y cierre de sesión, y recuperación de contraseña.
- **Listas personalizadas:** disponibles para usuarios autenticados.
  - Cada lista pertenece a un único usuario.
  - Cada lista admite un único tipo de contenido: personajes o episodios.
  - Un elemento no puede repetirse dentro de una misma lista.
- **Funcionalidades adicionales:** favoritos, edición del perfil, administración de usuarios y sincronización de datos con la API para el rol administrador.

## Requisitos previos

| Herramienta | Versión | Verificación |
|---|---|---|
| PHP | 8.4 o superior | `php -v` |
| Composer | 2.x | `composer -V` |
| Base de datos | SQLite 3 o MySQL 8 | — |
| Git | — | `git --version` |

Con versiones de PHP anteriores a la 8.4, la instalación de dependencias falla, ya que el archivo `composer.lock` incluye paquetes que requieren PHP 8.4.1 o superior. La imagen de Docker del proyecto ya utiliza PHP 8.4.

Como alternativa, el proyecto puede ejecutarse con **Docker** y **Docker Compose**. Ese procedimiento se describe más abajo.

## Instalación en entorno local

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd tp2-rick-and-morty-daw-tuti-laravel-application
```

### 2. Instalar las dependencias

```bash
composer install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar la base de datos

**Opción A – SQLite**

Es la configuración por defecto de `.env.example`. Solo hay que crear el archivo de la base de datos:

```bash
touch database/database.sqlite
```

**Opción B – MySQL**

Crear una base de datos vacía y modificar estas variables en el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rick_and_morty
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Ejecutar las migraciones y el seeder

```bash
php artisan migrate --seed
```

Este comando crea el esquema completo de la base de datos y un usuario administrador inicial.

### 6. Cargar los datos de Rick and Morty

```bash
php artisan rick-morty:import
```

El comando obtiene ubicaciones, episodios y personajes de la API pública <https://rickandmortyapi.com> y los guarda conservando los identificadores originales. Requiere conexión a internet.

Con la opción `--fresh` se eliminan los datos existentes del explorador antes de importar. Esta opción no afecta a los usuarios ni a sus listas.

### 7. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación queda disponible en <http://localhost:8000>.

## Instalación con Docker

1. Crear el archivo `.env` a partir de `.env.example`.
2. Configurar en `.env` la conexión al contenedor de base de datos:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=rick_and_morty
   DB_USERNAME=laravel
   DB_PASSWORD=laravel_local
   ```

3. Construir y levantar los contenedores, y preparar la aplicación:

   ```bash
   docker compose up -d --build
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   docker compose exec app php artisan rick-morty:import
   ```

La aplicación queda disponible en <http://localhost:8000>. Para detener los contenedores se utiliza `docker compose down`.

## Usuario administrador

El seeder crea el siguiente usuario de prueba:

| Email | Contraseña |
|---|---|
| `admin@rickandmorty.test` | `admin1234` |

## Recuperación de contraseña

En el entorno local, la variable `MAIL_MAILER=log` hace que los correos no se envíen. El enlace de recuperación se registra en el archivo `storage/logs/laravel.log`.

## Ejecución de pruebas

```bash
php artisan test
```

Las pruebas se ejecutan sobre una base de datos SQLite en memoria, según la configuración de `phpunit.xml`, por lo que no modifican la base de datos de desarrollo. Las respuestas de la API externa se simulan con `Http::fake()`.

## Modelo de datos

Tablas del Trabajo Práctico N.º 1:

| Tabla | Clave primaria | Descripción |
|---|---|---|
| `location` | `id_location` | Ubicaciones |
| `characters` | `id_character` | Personajes. Tienen referencias a su ubicación de origen y a su ubicación actual |
| `episode` | `id_episode` | Episodios |
| `characters_episode` | (`id_character`, `id_episode`) | Relación muchos a muchos entre personajes y episodios |

Tablas incorporadas en el Trabajo Práctico N.º 2:

| Tabla | Descripción |
|---|---|
| `users` | Usuarios registrados. Incluye el indicador `is_admin` |
| `custom_lists` | Listas personalizadas. Tienen propietario, nombre y tipo (`character` o `episode`) |
| `custom_list_items` | Elementos de cada lista. Tienen un índice único que impide duplicados |
| `favorites` | Favoritos de cada usuario. Es una relación polimórfica |

El script SQL original del Trabajo Práctico N.º 1 se conserva como referencia en `database/tp1/rick_and_morty.sql`.

## Estructura del proyecto

```
app/Http/Controllers/      Controladores de la aplicación
app/Models/                Modelos Eloquent
app/Console/Commands/      Comando de importación de datos
app/Policies/              Políticas de autorización
database/migrations/       Migraciones del esquema
database/seeders/          Carga inicial del usuario administrador
resources/views/           Vistas Blade
public/css/                Hojas de estilo de la interfaz
public/js/                 JavaScript del explorador (filtros y panel de detalle)
routes/web.php             Definición de rutas
docs/uso-ia.md             Documentación del uso de Inteligencia Artificial
```

## Problemas frecuentes

| Problema | Solución |
|---|---|
| No se encuentra `vendor/autoload.php` | Ejecutar `composer install`. |
| Error de versión de PHP al instalar dependencias | Actualizar PHP a la versión 8.4 o superior. |
| `No application encryption key has been specified` | Ejecutar `php artisan key:generate`. |
| No existe el archivo de base de datos SQLite | Crear el archivo `database/database.sqlite`. |
| El explorador no muestra resultados | Ejecutar `php artisan rick-morty:import`. |
| Los cambios en `.env` no se aplican | Ejecutar `php artisan config:clear`. |

## Integrantes

- Natalia Bogarín
- Claudio Laborda
- Kareliz Pérez León
- Cristian Hernan Marcos Nozralah
