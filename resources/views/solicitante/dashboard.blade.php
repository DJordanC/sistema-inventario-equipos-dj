@extends('layouts.app')
@section('title', 'Panel de Solicitante')

@section('content')
    <style>
        .tarjeta-enlace {
            text-decoration: none;
            color: inherit;
            display: block;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .tarjeta-enlace:hover .card {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Panel de Solicitante</h2>
        <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">Jefe de Oficina</span>
    </div>

    <!-- Fila 1: Tarjetas Estadísticas -->
    <div class="row mb-4">
        
        <!-- Equipos Disponibles (Global) -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-left: 5px solid #198754 !important;">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Equipos Disponibles</h6>
                    <h2 class="mb-0 text-success fw-bold">{{ $equiposDisponibles }}</h2>
                </div>
            </div>
        </div>
        
        <!-- Mis Solicitudes Totales -->
        <div class="col-md-3">
            <a href="{{ route('solicitante.solicitudes.index') }}" class="tarjeta-enlace">
                <div class="card shadow-sm border-0 h-100" style="border-left: 5px solid #6c757d !important;">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Mis Solicitudes</h6>
                        <h2 class="mb-0 text-secondary fw-bold">{{ $misSolicitudes }}</h2>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Mis Pendientes -->
        <div class="col-md-3">
            <a href="{{ route('solicitante.solicitudes.index') }}" class="tarjeta-enlace">
                <div class="card shadow-sm border-0 h-100" style="border-left: 5px solid #ffc107 !important;">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Pendientes</h6>
                        <h2 class="mb-0 text-warning fw-bold">{{ $misPendientes }}</h2>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Mis Aprobadas -->
        <div class="col-md-3">
            <a href="{{ route('solicitante.solicitudes.index') }}" class="tarjeta-enlace">
                <div class="card shadow-sm border-0 h-100" style="border-left: 5px solid #0d6efd !important;">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Aprobadas</h6>
                        <h2 class="mb-0 text-primary fw-bold">{{ $misAprobadas }}</h2>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <!-- Fila 2: Gráficos y Tabla -->
    <div class="row mb-4">
        <!-- Gráfico de Estado de Solicitudes -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold text-center">Estado de mis Solicitudes</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div style="width: 100%; max-width: 220px;">
                        <canvas id="graficaMisSolicitudes"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimas Solicitudes -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Mis Últimos Pedidos</h6>
                    <a href="{{ route('solicitante.solicitudes.create') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-lg"></i> Nueva Solicitud
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Categoría</th>
                                    <th>Cant.</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimasSolicitudes as $sol)
                                    <tr>
                                        <td>{{ $sol->categoria->nombre }}</td>
                                        <td class="fw-bold">{{ $sol->cantidad }}</td>
                                        <td>{{ $sol->created_at->diffForHumans() }}</td>
                                        <td><x-etiqueta :estado="$sol->estado->value" /></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Aún no has realizado ninguna solicitud.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('graficaMisSolicitudes').getContext('2d');
        
        // Solo mostramos el gráfico si hay solicitudes, sino mostramos un mensaje
        const datos = {!! json_encode($estadosData) !!};
        const total = datos.reduce((a, b) => a + b, 0);

        if (total > 0) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pendientes', 'Aprobadas', 'Rechazadas'],
                    datasets: [{
                        data: datos,
                        backgroundColor: ['#ffc107', '#0d6efd', '#dc3545'],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        } else {
            // Si no hay solicitudes, mostramos un texto
            document.getElementById('graficaMisSolicitudes').outerHTML = '<div class="text-center text-muted small py-5"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No tienes solicitudes aún.</div>';
        }
    });
</script>
@endpush