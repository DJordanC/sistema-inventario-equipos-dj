<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIE') — Sistema de Inventarios</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Variables de color según el rol */
        @php
            $esAdmin = auth()->check() && auth()->user()->rol->value === 'administrador';
            $bgMenu = $esAdmin ? '#a2d2ff' : '#a7f3d0'; // Azul pastel vs Verde pastel
            $bgBody = $esAdmin ? '#f0f8ff' : '#f0fff4'; // Fondo claro a juego
            $textMenu = '#1e293b'; // Un gris oscuro para que se lea bien sobre pastel
        @endphp

        body { 
            background-color: {{ $bgBody }}; 
            transition: background-color 0.5s ease;
            min-height: 100vh;
        }
        
        .navbar-pastel {
            background-color: {{ $bgMenu }} !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: background-color 0.5s ease;
        }
        
        .navbar-pastel .navbar-brand,
        .navbar-pastel .nav-link,
        .navbar-pastel .dropdown-toggle {
            color: {{ $textMenu }} !important;
            font-weight: 600;
        }

        .navbar-pastel .nav-link:hover,
        .navbar-pastel .navbar-brand:hover {
            opacity: 0.7;
            transform: translateY(-1px);
            transition: all 0.2s;
        }

        /* Resaltar el enlace activo en el menú */
        .navbar-pastel .nav-link.active {
            background-color: rgba(255,255,255,0.4);
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Bordes redondeados para las tarjetas */
        .card {
            border-radius: 1rem;
            border: none;
        }
    </style>
</head>
<body>

    <!-- Barra de Navegación Pastel -->
    <nav class="navbar navbar-expand-lg navbar-pastel mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-pc-display"></i> SIE
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->rol->value === 'administrador')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.equipos.*') ? 'active' : '' }}" href="{{ route('admin.equipos.index') }}">
                                    Inventario
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.solicitudes.*') ? 'active' : '' }}" href="{{ route('admin.solicitudes.index') }}">
                                    Solicitudes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}" href="{{ route('admin.reportes.asignaciones') }}">
                                    Reportes
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.dashboard') ? 'active' : '' }}" href="{{ route('solicitante.dashboard') }}">
                                    Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.solicitudes.index') ? 'active' : '' }}" href="{{ route('solicitante.solicitudes.index') }}">
                                    Mis Solicitudes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.solicitudes.create') ? 'active' : '' }}" href="{{ route('solicitante.solicitudes.create') }}">
                                    Nueva Solicitud
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><span class="dropdown-item-text text-muted small fw-bold">{{ auth()->user()->rol->etiqueta() }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="container pb-5">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts Globales (SweetAlert) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Alerta dinámica para formularios de eliminación
            document.querySelectorAll('.form-eliminar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "El equipo se enviará a la papelera (Borrado Lógico).",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f87171', // Rojo pastel
                        cancelButtonColor: '#9ca3af', // Gris pastel
                        confirmButtonText: '<i class="bi bi-trash"></i> Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // 2. Alerta dinámica para mensajes de ÉXITO
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // 3. Alerta dinámica para mensajes de ERROR (Ej: Fallo en transacción)
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Atención',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Entendido'
                });
            @endif
        });
    </script>

    <!-- AQUÍ SE INYECTAN LOS SCRIPTS ESPECÍFICOS DE CADA VISTA (Como el del formulario dinámico) -->
    @stack('scripts')
</body>
</html>