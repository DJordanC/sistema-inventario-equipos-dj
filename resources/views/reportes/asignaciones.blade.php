@extends('layouts.app')
@section('title', 'Reporte de Solicitudes')

@section('content')
<div class="container-fluid py-2">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Historial de Solicitudes y Asignaciones</h2>
            <p class="text-muted small mb-0">Consulta cruzada de equipos, solicitudes y oficinas.</p>
        </div>
        
        <a href="{{ route('admin.reportes.exportar.csv', request()->all()) }}" class="btn btn-success shadow-sm">
            <i class="bi bi-file-earmark-excel"></i> Exportar a Excel (CSV)
        </a>
    </div>

    <!-- Tarjeta de Filtros -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reportes.asignaciones') }}" method="GET" class="row g-3 align-items-end">
                
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Oficina Receptora</label>
                    <select name="oficina_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las oficinas</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ request('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Estado de la Solicitud</label>
                    <select name="estado_solicitud" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las solicitudes</option>
                        <option value="aprobada" {{ request('estado_solicitud') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="pendiente" {{ request('estado_solicitud') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="rechazada" {{ request('estado_solicitud') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>

                <div class="col-md-3">
                    @if(request()->anyFilled(['oficina_id', 'estado_equipo', 'estado_solicitud']))
                        <a href="{{ route('admin.reportes.asignaciones') }}" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle"></i> Limpiar Filtros
                        </a>
                    @else
                        <button class="btn btn-outline-secondary w-100" type="button" disabled>
                            <i class="bi bi-funnel"></i> Sin filtros activos
                        </button>
                    @endif
                </div>
            </form>
            <div class="mt-3 text-muted" style="font-size: 0.8rem;">
                <i class="bi bi-info-circle"></i> Nota: Estos reporte solo muestra los equipos que han estado involucrados en una solicitud (Asignación).
            </div>
        </div>
    </div>

    <!-- Tabla de Resultados -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-nowrap align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Fecha</th>
                            <th>Oficina</th>
                            <th>Solicitante</th>
                            <th>Categoría</th>
                            <th>Estado Solicitud</th>
                            <th>Equipos Asignados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $sol)
                            <tr>
                                <td class="ps-4 text-muted">{{ $sol->id }}</td>
                                <td>{{ $sol->created_at->format('d/m/Y h:i A') }}</td>
                                <td><span class="badge bg-info text-dark">{{ $sol->oficina->nombre }}</span></td>
                                <td>{{ $sol->usuario->nombre }}</td>
                                <td>{{ $sol->categoria->nombre }} (x{{ $sol->cantidad }})</td>
                                <td><x-etiqueta :estado="$sol->estado->value" /></td>
                                <td>
                                    @if($sol->estado->value === 'aprobada')
                                        @foreach($sol->asignaciones as $asig)
                                            <span class="badge bg-primary mb-1">
                                                {{ $asig->equipo->codigo_patrimonial }} ({{ $asig->equipo->estado->value }})
                                            </span><br>
                                        @endforeach
                                    @else
                                        <span class="text-muted fst-italic">No asignado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-search fs-1 d-block mb-3"></i>
                                    No se encontraron resultados con los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($solicitudes->hasPages() || $solicitudes->total() > 0)
            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
                <small class="text-muted">
                    Mostrando <strong>{{ $solicitudes->firstItem() ?? 0 }}</strong> a <strong>{{ $solicitudes->lastItem() ?? 0 }}</strong> de <strong>{{ $solicitudes->total() }}</strong> resultados
                </small>
                <div>
                    {{ $solicitudes->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection