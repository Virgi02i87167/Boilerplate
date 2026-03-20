# Boilerplate Premium Laravel Base

Este es un boilerplate optimizado y unificado para proyectos **Laravel 12**. Se enfoca en una experiencia de usuario (UX) premium y una estructura de componentes simplificada, ideal para bases de proyectos que requieren una estética profesional desde el primer momento.

## 🚀 Características Principales

- **Diseño Premium**: Interfaz moderna con soporte completo para modo oscuro (Dark/Light Mode) y transiciones fluidas.
- **Autenticación Unificada**: Vistas de Laravel Breeze rediseñadas y unificadas bajo componentes premium como `x-input`, reduciendo el número de archivos y mejorando la consistencia.
- **Gestión de Roles y Permisos**: Integración robusta lista con **Spatie Laravel Permission**, posibilitando el control de acceso desde el día uno.
- **Simplificación de Componentes**: Estructura de componentes Blade optimizada (`x-card`, `x-input`, `x-primary-button`) para un desarrollo más ágil y mantenible.
- **Base Sólida y Segura**: Configuración lista para **PHP 8.5+**, **PostgreSQL** y **Vite**, respaldada por una extensa batería de pruebas (Feature Tests).

## 🛠️ Instalación y Configuración

1.  **Clonar el repositorio**:
    ```bash
    git clone <URL_DEL_REPO>
    cd boilerplate
    ```

2.  **Instalar dependencias**:
    ```bash
    composer install
    npm install
    ```

3.  **Configurar entorno**:
    - Copiar el `.env.example` y renombrarlo a `.env`.
    - Configurar las credenciales de conexión de la base de datos de PostgreSQL dentro de `.env`.

4.  **Generar clave y Migrar base de datos**:
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```

5.  **Iniciar Servidores**:
    ```bash
    # En una terminal para compilar el frontend dinámicamente:
    npm run dev
    
    # En otra terminal para el backend:
    php artisan serve
    ```

## 🧪 Ejecución de Pruebas

El boilerplate cuenta con pruebas (tests) completamente configuradas y funcionando con éxito para módulos del sistema y perfiles (gestión de cuentas, reseteos, autenticaciones, etc.). Para correr la suite de pruebas:

```bash
php artisan test
```

## 📦 Estructura de Componentes Principales

- **`x-card`**: Contenedores premium con header y body personalizables.
- **`x-input`**: Componente autónomo que integra de forma conjunta Label, Input y Mensajes de Validación en un solo lugar.
- **`x-primary-button`**: Botones estilizados con interacción (focus/hover/active) moderna de manera consistente.

## 🤝 Estado del Proyecto
Este proyecto ha sido consolidado y pulido para servir como una base de arranque profesional de alto impacto visual. Aporta gran valor eliminando el código repetitivo de los auth scaffolds de Laravel y ofrece una excelente experiencia cohesiva.
