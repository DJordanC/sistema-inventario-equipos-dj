@extends('layouts.app')
@section('title', 'Dashboard Administrador')

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
        <h2 class="fw-bold">Panel de Administración</h2>
        <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm">Soporte Técnico</span>
    </div>

    <!-- Fila 1: Tarjetas Estadísticas -->
    <div class="row mb-4">
        
        <!-- Disponibles -->
        <div class="col-md-3">
            <a href="{{ route('admin.equipos.index', ['estado' => 'disponible']) }}" class="tarjeta-enlace">
                <div class="card shadow-sm border-0 h-100" style="border-left: 5px solid #198754 !important;">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Disponibles</h6>
                        <h2 class="mb-0 text-success fw-bold">{{ $equiposDisponibles }}</h2>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Asignados -->
        <div class="col-md-3">
            <a href="{{ route('admin.equipos.index', ['estado' => 'asignado']) }}" class="tarjeta-enlace">
                <div class="card shadow-sm border-0 h-100" style="border-left: 5px solid #0d6efd !important;">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Asignados</h6>
                        <h2 class="mb-0 text-primary fw-bold">{{ $equiposAsignados }}</h2>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Mantenimiento -->
        <div class="col-md-3">
            <a href="{{ route('admin.equipos.index', ['estado' => 'en_mantenimiento']) }}" class="tarjeta-enlace">
                <div class="card shadow-sm border-0 h-100" style="border-left: 5px solid #ffc107 !important;">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Mantenimiento</h6>
                        <h2 class="mb-0 text-warning fw-bold">{{ $equiposMantenimiento }}</h2>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Solicitudes Pendientes -->
        <div class="col-md-3">
            <!-- AQUÍ AÑADIMOS EL FILTRO AL ENLACE -->
            <a href="{{ route('admin.solicitudes.index', ['estado' => 'pendiente']) }}" class="tarjeta-enlace">
                <div class="card shadow-sm border-0 h-100" style="border-left: 5px solid #dc3545 !important;">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Sol. Pendientes</h6>
                        <h2 class="mb-0 text-danger fw-bold">{{ $solicitudesPendientes }}</h2>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <!-- Fila 2: Gráficos con Efecto 3D -->
    <div class="row mb-4">
        <!-- Gráfico de Estados -->
        <div class="col-md-5">
            <!-- AÑADIDA LA CLASE card-3d AQUÍ -->
            <div class="card card-3d border h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold text-center">Estado Físico de los Equipos</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div style="width: 100%; max-width: 250px;">
                        <canvas id="graficaEstados"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico por Categoría -->
        <div class="col-md-7">
            <!-- AÑADIDA LA CLASE card-3d AQUÍ -->
            <div class="card card-3d border h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold text-center">Total de Equipos por Categoría</h6>
                </div>
                <div class="card-body">
                    <canvas id="graficaCategorias" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila 3: Últimas Solicitudes -->
    <div class="card card-3d border-0">
        <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Últimas Solicitudes Recibidas</h5>
            <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Solicitante</th>
                            <th>Categoría</th>
                            <th>Cant.</th>
                            <th>Fecha</th>
                            <th class="pe-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasSolicitudes as $sol)
                            <tr>
                                <td class="ps-4">{{ $sol->usuario->nombre }}</td>
                                <td>{{ $sol->categoria->nombre }}</td>
                                <td class="fw-bold">{{ $sol->cantidad }}</td>
                                <td>{{ $sol->created_at->diffForHumans() }}</td>
                                <td class="pe-4"><x-etiqueta :estado="$sol->estado->value" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">No hay solicitudes recientes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Gráfico de Estados (Dona) ---
        const ctxEstados = document.getElementById('graficaEstados').getContext('2d');
        new Chart(ctxEstados, {
            type: 'doughnut',
            data: {
                labels: ['Disponibles', 'Asignados', 'Mantenimiento'],
                datasets: [{
                    data: [{{ $equiposDisponibles }}, {{ $equiposAsignados }}, {{ $equiposMantenimiento }}],
                    backgroundColor: ['#198754', '#0d6efd', '#ffc107'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '70%'
            }
        });

        // --- 2. Gráfico de Categorías (Barras) ---
        const ctxCategorias = document.getElementById('graficaCategorias').getContext('2d');
        new Chart(ctxCategorias, {
            type: 'bar',
            data: {
                labels: {!! json_encode($categorias->pluck('nombre')) !!},
                datasets: [{
                    label: 'Equipos',
                    data: {!! json_encode($categorias->pluck('equipos_count')) !!},
                    backgroundColor: '#a2d2ff',
                    borderColor: '#7cb9e8',
                    borderWidth: 1,
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } } }
            }
        });
    });
</script>
@endpush