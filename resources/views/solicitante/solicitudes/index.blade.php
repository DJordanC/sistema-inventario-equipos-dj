@extends('layouts.app')
@section('title', 'Mis Solicitudes')

@section('content')
<div class="container-fluid py-2">
    
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Mis Solicitudes</h2>
            <p class="text-muted small mb-0">Historial de equipos solicitados a Soporte Técnico.</p>
        </div>
        <a href="{{ route('solicitante.solicitudes.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg"></i> Nueva Solicitud
        </a>
    </div>

    @if(session('success'))
        <x-alerta tipo="success">{{ session('success') }}</x-alerta>
    @endif

    <!-- Tarjeta principal de la tabla -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Fecha de Solicitud</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td class="ps-4 text-muted">{{ $solicitud->id }}</td>
                                <td class="fw-bold">{{ $solicitud->categoria->nombre }}</td>
                                <td><span class="badge bg-secondary rounded-pill">{{ $solicitud->cantidad }}</span></td>
                                <td>{{ $solicitud->created_at->format('d/m/Y h:i A') }}</td>
                                <td><x-etiqueta :estado="$solicitud->estado->value" /></td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('solicitante.solicitudes.show', $solicitud) }}" class="btn btn-sm btn-info text-white shadow-sm" title="Ver Detalles">
                                        <i class="bi bi-eye"></i> Ver Detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        <h5>Aún no has realizado ninguna solicitud</h5>
                                        <p class="small">Haz clic en "Nueva Solicitud" para empezar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- PIE DE LA TARJETA: Aquí va la paginación y el contador -->
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