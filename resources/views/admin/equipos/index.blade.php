@extends('layouts.app')
@section('title', 'Inventario de Equipos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Inventario de Equipos</h2>
    <a href="{{ route('admin.equipos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Equipo
    </a>
</div>

@if(session('success'))
    <x-alerta tipo="success">{{ session('success') }}</x-alerta>
@endif

<!-- Aviso de Filtro Activo -->
@if(request('estado'))
    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm">
        <span>
            <i class="bi bi-funnel-fill me-1"></i> Mostrando equipos en estado: 
            <strong class="text-uppercase">{{ str_replace('_', ' ', request('estado')) }}</strong>
        </span>
        <a href="{{ route('admin.equipos.index') }}" class="btn btn-sm btn-outline-dark">
            Quitar Filtro
        </a>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Código Patrimonial</th>
                        <th>Marca / Modelo</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipos as $equipo)
                        <tr>
                            <td><strong>{{ $equipo->codigo_patrimonial }}</strong></td>
                            <td>{{ $equipo->marca }} {{ $equipo->modelo }}</td>
                            <td>{{ $equipo->categoria->nombre }}</td>
                            <td><x-etiqueta :estado="$equipo->estado->value" /></td>
                            <td class="text-end">
                                <a href="{{ route('admin.equipos.show', $equipo) }}" class="btn btn-sm btn-info text-white" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.equipos.edit', $equipo) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.equipos.destroy', $equipo) }}" method="POST" class="d-inline form-eliminar">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No hay equipos registrados con estos criterios.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $equipos->links() }}</div>
@endsection