@extends('layouts.app')
@section('title', 'Gestión de Solicitudes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Gestión de Solicitudes</h2>
</div>

@if(session('success'))
    <x-alerta tipo="success">{{ session('success') }}</x-alerta>
@endif

<!-- AVISO DE FILTRO ACTIVO -->
@if(request('estado'))
    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm border-0">
        <span>
            <i class="bi bi-funnel-fill me-1"></i> Mostrando solicitudes en estado: 
            <strong class="text-uppercase">{{ request('estado') }}</strong>
        </span>
        <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-sm btn-outline-dark">
            Quitar Filtro
        </a>
    </div>
@endif

<div class="card shadow-sm border-0 card-3d">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Solicitante</th>
                        <th>Oficina</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                        <tr>
                            <td class="ps-4">{{ $solicitud->id }}</td>
                            <td>{{ $solicitud->usuario->nombre }}</td>
                            <td>{{ $solicitud->oficina->nombre }}</td>
                            <td>{{ $solicitud->categoria->nombre }}</td>
                            <td class="fw-bold">{{ $solicitud->cantidad }}</td>
                            <td>
                                @if($solicitud->estado->value === 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($solicitud->estado->value === 'aprobada')
                                    <span class="badge bg-success">Aprobada</span>
                                @else
                                    <span class="badge bg-danger">Rechazada</span>
                                @endif
                            </td>
                            <td>{{ $solicitud->created_at->format('d/m/Y h:i A') }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.solicitudes.show', $solicitud) }}" class="btn btn-sm btn-primary btn-3d">
                                    <i class="bi bi-search"></i> Revisar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No hay solicitudes registradas con estos criterios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $solicitudes->links() }}</div>
@endsection