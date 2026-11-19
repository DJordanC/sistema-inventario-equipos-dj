@extends('layouts.app')
@section('title', 'Reporte de Asignaciones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Historial de Asignaciones por Oficina</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.reportes.asignaciones') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="col-form-label fw-bold">Filtrar por Oficina:</label>
            </div>
            <div class="col-md-4">
                <select name="oficina_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las oficinas</option>
                    @foreach($oficinas as $oficina)
                        <option value="{{ $oficina->id }}" {{ request('oficina_id') == $oficina->id ? 'selected' : '' }}>
                            {{ $oficina->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if(request('oficina_id'))
                <div class="col-auto">
                    <a href="{{ route('admin.reportes.asignaciones') }}" class="btn btn-outline-secondary btn-sm">Limpiar Filtro</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Fecha de Asignación</th>
                        <th>Oficina Receptora</th>
                        <th>Solicitante</th>
                        <th>Equipo Asignado (Código)</th>
                        <th>Marca / Modelo</th>
                        <th>Categoría</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $asignacion)
                        <tr>
                            <td>{{ $asignacion->asignado_en->format('d/m/Y h:i A') }}</td>
                            <td><span class="badge bg-info text-dark">{{ $asignacion->solicitud->oficina->nombre }}</span></td>
                            <td>{{ $asignacion->solicitud->usuario->nombre }}</td>
                            <td class="fw-bold">{{ $asignacion->equipo->codigo_patrimonial }}</td>
                            <td>{{ $asignacion->equipo->marca }} {{ $asignacion->equipo->modelo }}</td>
                            <td>{{ $asignacion->equipo->categoria->nombre }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No hay asignaciones registradas para esta oficina.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $asignaciones->withQueryString()->links() }}</div>
@endsection