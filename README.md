# Sistema de Gestión de Proyectos y Piezas

Sistema desarrollado para la gestión y seguimiento de proyectos, bloques y piezas en un entorno de fabricación.

## Características Principales

- Gestión de Usuarios
  - Creación y administración de usuarios
  - Autenticación segura
  - Control de acceso basado en roles

- Gestión de Proyectos
  - Creación y edición de proyectos
  - Asignación de bloques a proyectos
  - Seguimiento del estado de los proyectos

- Gestión de Bloques
  - Organización de piezas en bloques
  - Asignación de bloques a proyectos
  - Control de piezas pendientes por bloque

- Gestión de Piezas
  - Registro de piezas con peso teórico
  - Seguimiento del estado de fabricación
  - Asignación a bloques específicos

- Reportes
  - Vista gráfica del estado de proyectos
  - Listado de piezas pendientes por proyecto
  - Estadísticas de fabricación

## Optimización de Base de Datos

La estructura de la base de datos ha sido optimizada para mejorar el rendimiento y la eficiencia:

- Uso de claves primarias personalizadas (IDproyecto, IDBloque, IDPieza) para optimizar las búsquedas
- Relaciones optimizadas entre tablas para reducir la complejidad de las consultas
- Índices estratégicos para mejorar el rendimiento de las búsquedas frecuentes
- Estructura normalizada para mantener la integridad de los datos

## Requisitos del Sistema

- PHP >= 8.1
- MySQL >= 5.7
- Node.js >= 16
- Composer
- NPM

## Instalación

1. Clonar el repositorio:
```bash
git clone [url-del-repositorio]
```

2. Instalar dependencias de PHP:
```bash
composer install
```

3. Instalar dependencias de Node:
```bash
npm install
```

4. Configurar el archivo .env:
```bash
cp .env.example .env
```

5. Generar clave de aplicación:
```bash
php artisan key:generate
```

6. Ejecutar migraciones:
```bash
php artisan migrate
```

7. Compilar assets:
```bash
npm run dev
```

8. Iniciar el servidor:
```bash
php artisan serve
```

## Estructura del Proyecto

```
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Controladores de la aplicación
│   │   └── Middleware/     # Middleware de autenticación y validación
│   └── Models/             # Modelos de la base de datos
├── database/
│   └── migrations/         # Migraciones de la base de datos
├── resources/
│   └── js/
│       ├── Pages/          # Componentes Vue de las páginas
│       └── Layouts/        # Layouts de la aplicación
└── routes/
    ├── web.php            # Rutas web
    └── api.php            # Rutas API
```

## Tecnologías Utilizadas

- Backend:
  - Laravel 10
  - PHP 8.1
  - MySQL

- Frontend:
  - Vue.js 3
  - Inertia.js
  - Tailwind CSS
  - Chart.js

## Seguridad

- Autenticación mediante Laravel Sanctum
- Protección CSRF en todas las rutas
- Validación de datos en el servidor
- Sanitización de entradas
- Control de acceso basado en roles

## Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.
