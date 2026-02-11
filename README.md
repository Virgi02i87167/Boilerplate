# Boilerplate Laboratorio Laravel

Este es un boilerplate inicial para proyectos Laravel con autenticación, roles (RBAC) y auditoría pre-configurados.

## 🚀 Requisitos Previos

- **PHP** >= 8.2 (Extensiones: fileinfo, mbstring, openssl, pdo, pdo_pgsql)
- **Composer** >= 2.x
- **Node.js** & **NPM**
- **PostgreSQL**

## 🛠️ Instalación y Configuración (Dev 1)

1.  **Clonar el repositorio**:
    ```bash
    git clone <URL_DEL_REPO>
    cd boilerplate
    ```

2.  **Instalar dependencias de PHP**:
    ```bash
    composer install
    ```
    > **Nota**: Si tienes problemas con extensiones, asegúrate de habilitarlas en tu `php.ini`.

3.  **Configurar entorno**:
    - Copiar `.env.example` a `.env` (si no existe).
    - Configurar las credenciales de base de datos en `.env`:
      ```ini
      DB_CONNECTION=pgsql
      DB_HOST=127.0.0.1
      DB_PORT=5432
      DB_DATABASE=boilerplate
      DB_USERNAME=postgres
      DB_PASSWORD=secret
      ```

4.  **Generar clave de aplicación**:
    ```bash
    php artisan key:generate
    ```

5.  **Instalar dependencias de Frontend**:
    ```bash
    npm install
    npm run dev
    ```

## 📦 Estructura del Proyecto

- **Roles y Permisos**: Implementado con `spatie/laravel-permission`.
- **Auditoría**: `spatie/laravel-activitylog`.
- **Frontend**: Tailwind CSS + Alpine.js.

## 🤝 Contribución

Sigue el flujo de trabajo definido para cada Dev (1-5).
