<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIE') — Sistema de Inventarios</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        @php
            $esAdmin = auth()->check() && auth()->user()->rol->value === 'administrador';
            $bgMenu = $esAdmin ? '#a2d2ff' : '#a7f3d0';
            $bgBody = $esAdmin ? '#f0f8ff' : '#f0fff4';
            $textMenu = '#1e293b';
        @endphp

        body { background-color: {{ $bgBody }}; transition: background-color 0.5s ease; min-height: 100vh; }
        .navbar-pastel { background-color: {{ $bgMenu }} !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .navbar-pastel .navbar-brand, .navbar-pastel .nav-link, .navbar-pastel .dropdown-toggle { color: {{ $textMenu }} !important; font-weight: 600; }
        .navbar-pastel .nav-link:hover, .navbar-pastel .navbar-brand:hover { opacity: 0.7; transform: translateY(-1px); transition: all 0.2s; }
        .navbar-pastel .nav-link.active { background-color: rgba(255,255,255,0.4); border-radius: 0.5rem; }
        .card { border-radius: 1rem; border: none; }

        /* Animación de la campanita */
        @keyframes ring {
            0% { transform: rotate(0); }
            10% { transform: rotate(15deg); }
            20% { transform: rotate(-10deg); }
            30% { transform: rotate(15deg); }
            40% { transform: rotate(-10deg); }
            50% { transform: rotate(0); }
            100% { transform: rotate(0); }
        }
        .bell-ringing {
            animation: ring 2s infinite;
            display: inline-block;
            transform-origin: top center;
            color: #dc3545; /* Rojo */
        }
    </style>
</head>
<body>

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-pastel mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-pc-display"></i> SIE-DJ
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->rol->value === 'administrador')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.equipos.*') ? 'active' : '' }}" href="{{ route('admin.equipos.index') }}">Inventario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.solicitudes.*') ? 'active' : '' }}" href="{{ route('admin.solicitudes.index') }}">Solicitudes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}" href="{{ route('admin.reportes.asignaciones') }}">Reportes</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.dashboard') ? 'active' : '' }}" href="{{ route('solicitante.dashboard') }}">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.solicitudes.index') ? 'active' : '' }}" href="{{ route('solicitante.solicitudes.index') }}">Mis Solicitudes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.solicitudes.create') ? 'active' : '' }}" href="{{ route('solicitante.solicitudes.create') }}">Nueva Solicitud</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav align-items-center">
                    @auth
                        <!-- CAMPANITA (Para ambos roles) -->
                        <li class="nav-item me-3">
                            <!-- El enlace cambia dependiendo del rol -->
                            @php
                                $linkNotificacion = auth()->user()->rol->value === 'administrador' 
                                    ? route('admin.solicitudes.index') 
                                    : route('solicitante.solicitudes.index');
                            @endphp
                            <a class="nav-link position-relative" href="{{ $linkNotificacion }}" title="Notificaciones" id="link-campana">
                                <i class="bi bi-bell-fill fs-4" id="icono-campana"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-notificaciones" style="display: none;">
                                    0
                                </span>
                            </a>
                        </li>

                        <!-- MENÚ DE USUARIO -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><span class="dropdown-item-text text-muted small fw-bold">{{ auth()->user()->rol->etiqueta() }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                
                                @if(auth()->user()->rol->value === 'administrador')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.usuarios.index') }}">
                                            <i class="bi bi-people-fill text-primary"></i> Gestión de Usuarios
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                        </button>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

       <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- SWEETALERT GLOBAL ---
            document.querySelectorAll('.form-eliminar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?', text: "Esta acción eliminará el registro del sistema.", icon: 'warning',
                        showCancelButton: true, confirmButtonColor: '#f87171', cancelButtonColor: '#9ca3af',
                        confirmButtonText: '<i class="bi bi-trash"></i> Sí, eliminar', cancelButtonText: 'Cancelar'
                    }).then((result) => { if (result.isConfirmed) this.submit(); });
                });
            });

            @if(session('success'))
                Swal.fire({ icon: 'success', title: '¡Operación Exitosa!', text: "{{ session('success') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Atención', text: "{{ session('error') }}", confirmButtonColor: '#ef4444', confirmButtonText: 'Entendido' });
            @endif

            // --- LÓGICA DE NOTIFICACIONES CORREGIDA ---
            @if(auth()->check())
                const badge = document.getElementById('badge-notificaciones');
                const campana = document.getElementById('icono-campana');
                
                const userId = "{{ auth()->id() }}";
                const rol = "{{ auth()->user()->rol->value }}";
                const localStorageKey = 'notif_vistas_' + rol + '_' + userId;

                // Obtenemos la URL de la página de solicitudes (donde se leen las notificaciones)
                const targetUrl = "{{ auth()->user()->rol->value === 'administrador' ? route('admin.solicitudes.index') : route('solicitante.solicitudes.index') }}";
                const currentUrl = window.location.href.split('?')[0]; // Quitamos parámetros de URL

                function actualizarNotificaciones() {
                    fetch("{{ route('api.notificaciones') }}")
                        .then(response => response.json())
                        .then(data => {
                            let vistas = parseInt(localStorage.getItem(localStorageKey) || 0);

                            // TRUCO MAESTRO: Si estamos en la página de solicitudes, marcamos todo como leído
                            if (currentUrl === targetUrl) {
                                vistas = data.count;
                                localStorage.setItem(localStorageKey, vistas);
                            }

                            if (data.count > vistas) {
                                let nuevas = data.count - vistas;
                                badge.textContent = nuevas;
                                badge.style.display = 'inline-block';
                                campana.classList.add('bell-ringing');
                            } else {
                                badge.style.display = 'none';
                                campana.classList.remove('bell-ringing');
                            }
                        })
                        .catch(error => console.log('Error al cargar notificaciones:', error));
                }

                // Ejecutar al cargar la página
                actualizarNotificaciones();

                // Consultar cada 10 segundos
                setInterval(actualizarNotificaciones, 10000);
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>