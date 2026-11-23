# 🖥️ SIE - DJ | Sistema de Inventarios de Equipos

![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=flat-square&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-14+-blue?style=flat-square&logo=postgresql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=flat-square&logo=bootstrap)

**SIE - DJ** Su objetivo es automatizar el control de bienes informáticos y la asignación de equipos a las diferentes oficinas de una institución, garantizando la trazabilidad, la integridad de los datos y una experiencia de usuario moderna.

---

## ✨ Características Principales (Alcance Funcional)

El sistema cumple con los 4 módulos solicitados en el enunciado:

### 🔐 Módulo 1: Autenticación y Control de Acceso (RBAC)
*   Implementación de Login y Registro nativo con **Laravel Fortify**.
*   **Dos roles definidos:** `Administrador` (Soporte Técnico) y `Solicitante` (Jefe de Oficina).
*   Protección de rutas mediante **Middlewares** personalizados (`VerificarRolUsuario`).
*   Redirección dinámica a Dashboards específicos según el rol del usuario.

### 📦 Módulo 2: Gestión de Inventario (CRUD Completo)
*   Registro, edición, visualización y eliminación lógica (**Soft Deletes**) de equipos.
*   Validación cruzada: El código patrimonial debe coincidir con el prefijo de la categoría (Ej: `LAP-001` para Laptops).
*   Formularios dinámicos: Los campos de CPU, RAM y Almacenamiento se ocultan automáticamente si el equipo es una impresora o monitor.
*   Gestión de estados: `Disponible`, `Asignado` y `En Mantenimiento`.

### 🔄 Módulo 3: Solicitud y Despacho de Equipos (Proceso Central)
*   El **Solicitante** crea peticiones seleccionando categoría, cantidad, oficina y justificación.
*   El **Administrador** visualiza solicitudes pendientes, aprueba o rechaza.
*   **Asignación Transaccional:** Al aprobar, el administrador selecciona los equipos exactos (por código patrimonial). Todo ocurre dentro de un `DB::transaction` (atomicidad), asegurando que si no hay stock suficiente, la operación se cancele por completo.
*   Cambio automático de estado del equipo a `Asignado` y registro histórico (fecha y hora).

### 📊 Módulo 4: Reportes y Consultas Avanzadas
*   **Dashboard Administrador:** Indicadores en tiempo real (Disponibles, Asignados, Mantenimiento, Solicitudes Pendientes) y gráficos interactivos con Chart.js.
*   **Reporte de Asignaciones:** Historial detallado filtrado por oficina, utilizando **Eager Loading** (`with()`) para evitar el problema de consultas N+1.

---

## 🛠️ Tecnologías Utilizadas

*   **Backend:** PHP 8.2 / Laravel 10
*   **Base de Datos:** PostgreSQL (Uso de `ENUM`, `CHECK`, `FOREIGN KEY` e índices).
*   **Frontend:** Blade Components + Bootstrap 5 (vía CDN) + SweetAlert2.
*   **JavaScript:** Vanilla JS (Lógica de formularios dinámicos y validaciones en tiempo real).

---

## 🏗️ Arquitectura y Criterios de Evaluación Cumplidos

*   **Form Requests:** Separación de la lógica de validación (`GuardarEquipoRequest`, `AprobarSolicitudRequest`, etc.).
*   **Transacciones en BD:** Uso de `DB::transaction` en el `ServicioAprobacionSolicitud`.
*   **Optimización:** Eager Loading (`with()`) en todos los listados y reportes.
*   **Componentes Blade:** Creación y reutilización de `<x-alerta>`, `<x-boton>`, `<x-etiqueta>`, `<x-modal>`.
*   **Cross-Platform:** Archivos `.gitattributes` y `.editorconfig` para asegurar compatibilidad entre Windows y Linux.

---

## 🚀 Instalación y Configuración Local

### Requisitos Previos
*   PHP >= 8.2 (con extensión `pdo_pgsql` habilitada)
*   Composer instalado
*   PostgreSQL instalado y en ejecución
*   Git

### Paso a paso

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/DjorDanC/sistema-inventario-equipos-dj.git
   cd sistema-inventario-equipos-dj

2. **Instalar dependencias de PHP:**

   composer install

4. **Configurar variables de entorno:**

   cp .env.example .env
   ```
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=sie
    DB_USERNAME=tu_usuario
    DB_PASSWORD=tu_contraseña

5. **Iniciar el servidor local:**
    php artisan serve
    ```
    http://127.0.0.1:8000

## 🌿 Flujo de Trabajo (GitFlow)
El proyecto fue desarrollado siguiendo la metodología GitFlow:

**main:** Código estable y listo para producción.

**develop:** Rama de integración de nuevas características.

**feature/inventario-solicitudes:** Rama utilizada para el desarrollo de los módulos principales.


## 👨‍💻 Autor
DjorDanC - Desarrollo completo, diseño de BD y UI/UX.
