@extends('layouts.app')
@section('title', 'Mis Solicitudes')

@section('content')
<div class="container-fluid py-2">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Mis Solicitudes</h2>
            <p class="text-muted small mb-0">Historial de equipos solicitados a Soporte Técnico.</p>
        </div>
        <a href="{{ route('solicitante.solicitudes.create') }}" class="btn btn-success shadow-sm btn-3d">
            <i class="bi bi-plus-lg"></i> Nueva Solicitud
        </a>
    </div>

    @if(session('success'))
        <x-alerta tipo="success">{{ session('success') }}</x-alerta>
    @endif
    
    @if(session('error'))
        <x-alerta tipo="danger">{{ session('error') }}</x-alerta>
    @endif

    <!-- BARRA DE FILTROS -->
    <div class="card shadow-sm border-0 mb-4 card-3d">
        <div class="card-body">
            <form action="{{ route('solicitante.solicitudes.index') }}" method="GET" class="row g-3 align-items-end">
                
                <!-- Filtro Categoría -->
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Categoría</label>
                    <select name="categoria_id" class="form-select input-3d" onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Estado -->
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Estado</label>
                    <select name="estado" class="form-select input-3d" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="col-md-4 d-flex gap-2">
                    @if(request()->anyFilled(['categoria_id', 'estado']))
                        <a href="{{ route('solicitante.solicitudes.index') }}" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle"></i> Limpiar Filtros
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-secondary w-100" disabled>
                            <i class="bi bi-funnel"></i> Sin filtros activos
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- TABLA DE RESULTADOS -->
    <div class="card shadow-sm border-0 card-3d">
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
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    @if($solicitud->estado->value === 'pendiente')
                                        <a href="{{ route('solicitante.solicitudes.edit', $solicitud) }}" class="btn btn-sm btn-warning shadow-sm" title="Renovar Solicitud">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </a>
                                        <form action="{{ route('solicitante.solicitudes.destroy', $solicitud) }}" method="POST" class="d-inline form-eliminar">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger shadow-sm" title="Cancelar Solicitud">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        <h5>No se encontraron solicitudes</h5>
                                        <p class="small">Prueba cambiando los filtros o crea una nueva solicitud.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Paginación y Contador -->
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