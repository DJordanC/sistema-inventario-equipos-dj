@extends('layouts.app')
@section('title', 'Gestión de Solicitudes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Solicitudes</h2>
</div>

@if(session('success'))
    <x-alerta tipo="success">{{ session('success') }}</x-alerta>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Solicitante</th>
                        <th>Oficina</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                        <tr>
                            <td>{{ $solicitud->id }}</td>
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
                            <td class="text-end">
                                <a href="{{ route('admin.solicitudes.show', $solicitud) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-search"></i> Revisar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4">No hay solicitudes registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $solicitudes->links() }}</div>
@endsection