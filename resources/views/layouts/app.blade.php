<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DIE - DJ') — Sistema de Inventarios</title>
    
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
        
        /* Estilos del Menú */
        .navbar-pastel { background-color: {{ $bgMenu }} !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .navbar-pastel .navbar-brand, .navbar-pastel .nav-link, .navbar-pastel .dropdown-toggle { color: {{ $textMenu }} !important; font-weight: 600; }
        .navbar-pastel .nav-link:hover, .navbar-pastel .navbar-brand:hover { opacity: 0.8; transform: translateY(-1px); transition: all 0.2s; }
        .navbar-pastel .nav-link.active { background-color: rgba(255,255,255,0.5); border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        /* Tarjetas y Formularios 3D */
        .card-3d {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08), 0 6px 6px rgba(0,0,0,0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-3d:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.05);
        }
        .input-3d {
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #ced4da;
            transition: all 0.2s;
        }
        .input-3d:focus {
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05), 0 0 0 0.25rem rgba(13,110,253,.25);
            border-color: #86b7fe;
        }
        .btn-3d {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }
        .btn-3d:active {
            transform: translateY(2px);
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        /* Animación Campana */
        @keyframes ring {
            0% { transform: rotate(0); }
            10% { transform: rotate(15deg); }
            20% { transform: rotate(-10deg); }
            30% { transform: rotate(15deg); }
            40% { transform: rotate(-10deg); }
            50% { transform: rotate(0); }
            100% { transform: rotate(0); }
        }
        .bell-ringing { animation: ring 2s infinite; display: inline-block; transform-origin: top center; color: #dc3545; }
    </style>
</head>
<body>

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-pastel mb-4">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-pc-display"></i> DIE - DJ
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        @if(auth()->user()->rol->value === 'administrador')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-house-door-fill me-1"></i> Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.equipos.*') ? 'active' : '' }}" href="{{ route('admin.equipos.index') }}">
                                    <i class="bi bi-box-seam me-1"></i> Inventario
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.solicitudes.*') ? 'active' : '' }}" href="{{ route('admin.solicitudes.index') }}">
                                    <i class="bi bi-envelope-paper-fill me-1"></i> Solicitudes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}" href="{{ route('admin.reportes.asignaciones') }}">
                                    <i class="bi bi-bar-chart-line-fill me-1"></i> Reportes
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.dashboard') ? 'active' : '' }}" href="{{ route('solicitante.dashboard') }}">
                                    <i class="bi bi-house-door-fill me-1"></i> Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.solicitudes.index') ? 'active' : '' }}" href="{{ route('solicitante.solicitudes.index') }}">
                                    <i class="bi bi-list-task me-1"></i> Mis Solicitudes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('solicitante.solicitudes.create') ? 'active' : '' }}" href="{{ route('solicitante.solicitudes.create') }}">
                                    <i class="bi bi-plus-circle-fill me-1"></i> Nueva Solicitud
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav align-items-center">
                    @auth
                        <!-- CAMPANITA DE NOTIFICACIONES -->
                        <li class="nav-item me-3">
                            @php
                                $linkNotificacion = auth()->user()->rol->value === 'administrador' 
                                    ? route('admin.solicitudes.index') 
                                    : route('solicitante.solicitudes.index');
                            @endphp
                            <a class="nav-link position-relative" href="{{ $linkNotificacion }}" title="Notificaciones">
                                <i class="bi bi-bell-fill fs-5" id="icono-campana"></i>
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
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
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
    <main class="container-fluid container-lg pb-5 px-3 px-md-4">
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
                        title: '¿Estás seguro?', text: "Esta acción eliminará el registro.", icon: 'warning',
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

            // --- LÓGICA DE NOTIFICACIONES ---
            @if(auth()->check())
                const badge = document.getElementById('badge-notificaciones');
                const campana = document.getElementById('icono-campana');
                
                const userId = "{{ auth()->id() }}";
                const rol = "{{ auth()->user()->rol->value }}";
                const localStorageKey = 'notif_vistas_' + rol + '_' + userId;

                const targetUrl = "{{ auth()->user()->rol->value === 'administrador' ? route('admin.solicitudes.index') : route('solicitante.solicitudes.index') }}";
                const currentUrl = window.location.href.split('?')[0];

                function actualizarNotificaciones() {
                    fetch("{{ route('api.notificaciones') }}")
                        .then(response => response.json())
                        .then(data => {
                            let vistas = parseInt(localStorage.getItem(localStorageKey) || 0);
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
                        .catch(error => console.log('Error:', error));
                }
                actualizarNotificaciones();
                setInterval(actualizarNotificaciones, 10000);
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>