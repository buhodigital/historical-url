# Guía de Despliegue en Render.com

Sigue estos pasos para publicar tu aplicación "URL Histórico" en Render.com.

## Prerrequisitos

1.  Una cuenta en [GitHub](https://github.com/) con este código en un repositorio.
2.  Una cuenta en [Render.com](https://render.com/).
3.  Una base de datos MySQL externa (Render ofrece PostgreSQL nativo, pero para MySQL necesitarás un servicio externo como *Clever Cloud*, *PlanetScale* o montar un servicio MySQL en Render usando Docker, aunque esto último es más complejo).

## Opción 1: Despliegue Automático (Blueprint)

1.  En el dashboard de Render, ve a **Blueprints**.
2.  Haz clic en **New Blueprint Instance**.
3.  Conecta tu repositorio de GitHub.
4.  Render detectará automáticamente el archivo `render.yaml`.
5.  Se te pedirá que ingreses los valores para las variables de entorno de la base de datos:
    *   `DB_HOST`: El host de tu base de datos (ej. `bx89...clever-cloud.com`).
    *   `DB_NAME`: El nombre de la base de datos.
    *   `DB_USER`: Tu usuario de base de datos.
    *   `DB_PASS`: Tu contraseña de base de datos.
6.  Haz clic en **Apply**. Render desplegará tu aplicación.

## Opción 2: Despliegue Manual (Web Service)

1.  En el dashboard de Render, haz clic en **New +** y selecciona **Web Service**.
2.  Conecta tu repositorio de GitHub.
3.  Configura los siguientes detalles:
    *   **Name**: Un nombre para tu servicio.
    *   **Region**: La más cercana a tus usuarios.
    *   **Branch**: `main` (o la rama donde tengas estos cambios).
    *   **Runtime**: `PHP`.
    *   **Build Command**: `cp config/config.example.php config/config.php`
    *   **Start Command**: `php -S 0.0.0.0:8080`
4.  Ve a la sección **Environment Variables** y añade las siguientes:
    *   `DB_HOST`
    *   `DB_NAME`
    *   `DB_USER`
    *   `DB_PASS`
5.  Haz clic en **Create Web Service**.

## Base de Datos

Recuerda que debes importar la estructura de la base de datos (`db_structure.sql`) en tu proveedor de MySQL antes de usar la aplicación. Puedes hacerlo usando una herramienta como *phpMyAdmin* o *MySQL Workbench*.

El archivo `db_structure.sql` ya incluye las actualizaciones necesarias (longitud de contraseña aumentada).
