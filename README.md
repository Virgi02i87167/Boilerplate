# Boilerplate Premium Laravel Base

Este es un boilerplate optimizado y unificado para proyectos Laravel 11. Se enfoca en una experiencia de usuario (UX) premium y una estructura de componentes simplificada, ideal para bases de proyectos que requieren una estética profesional desde el primer momento.

## 🚀 Características Principales

- **Diseño Premium**: Interfaz moderna con soporte completo para modo oscuro (Dark/Light Mode) y transiciones fluidas.
- **Autenticación Unificada**: Vistas de Laravel Breeze rediseñadas y unificadas bajo el componente premium `x-input`, reduciendo el número de archivos.
- **Simplificación de Componentes**: Estructura de componentes optimizada (`x-card`, `x-input`, `x-button`) para un desarrollo más rápido y mantenible.
- **Base Sólida**: Configuración de PostgreSQL y Vite lista para producción, con redirección automática a login.

## 🛠️ Instalación y Configuración

1.  **Clonar el repositorio**:
    ```bash
    git clone <URL_DEL_REPO>
    cd boilerplate
    ```

2.  **Instalar dependencias de PHP**:
    ```bash
    composer install
    ```

3.  **Configurar entorno**:
    - Copiar `.env.example` a `.env`.
    - Configurar las credenciales de base de datos en `.env`.

4.  **Generar clave y Migrar**:
    ```bash
    php artisan key:generate
    php artisan migrate
    php artisan serve
    ```

5.  **Frontend**:
    ```bash
    npm install
    npm run dev
    ```

## 📦 Estructura de Componentes

- **`x-card`**: Contenedores premium con header y body personalizables.
- **`x-input`**: Componente autónomo que integra Label, Input y Mensajes de Validación en un solo lugar.
- **`x-primary-button`**: Botones estilizados con estados de hover y hover consistentes.

## 🤝 Estado del Proyecto
Este proyecto ha sido consolidado y pulido para servir como una base de inicio profesional, eliminando el "ruido" de componentes por defecto de Breeze y ofreciendo una estética cohesiva y cuidada.
