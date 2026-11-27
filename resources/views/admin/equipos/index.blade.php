@extends('layouts.app')
@section('title', 'Inventario de Equipos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Inventario de Equipos</h2>
    <a href="{{ route('admin.equipos.create') }}" class="btn btn-primary shadow-sm btn-3d">
        <i class="bi bi-plus-lg"></i> Nuevo Equipo
    </a>
</div>

@if(session('success'))
    <x-alerta tipo="success">{{ session('success') }}</x-alerta>
@endif

@if(request('estado'))
    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm border-0">
        <span>
            <i class="bi bi-funnel-fill me-1"></i> Mostrando equipos en estado: 
            <strong class="text-uppercase">{{ str_replace('_', ' ', request('estado')) }}</strong>
        </span>
        <a href="{{ route('admin.equipos.index') }}" class="btn btn-sm btn-outline-dark">
            Quitar Filtro
        </a>
    </div>
@endif

<!-- BARRA DE BÚSQUEDA (CON EFECTO 3D) -->
<div class="card shadow-sm border-0 mb-4 card-3d">
    <div class="card-body">
        <form action="{{ route('admin.equipos.index') }}" method="GET" class="row g-3 align-items-center">
            @if(request('estado'))
                <input type="hidden" name="estado" value="{{ request('estado') }}">
            @endif

            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 input-3d" 
                           placeholder="Buscar por código, marca o modelo..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-3">
                <select name="per_page" class="form-select input-3d" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 registros por página</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 registros por página</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 registros por página</option>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 btn-3d">Filtrar</button>
                @if(request()->anyFilled(['search', 'per_page']))
                    <a href="{{ route('admin.equipos.index') }}" class="btn btn-outline-danger w-100">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA (CON EFECTO 3D) -->
<div class="card shadow-sm border-0 card-3d">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Código Patrimonial</th>
                        <th>Marca / Modelo</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipos as $equipo)
                        <tr>
                            <td class="ps-4"><strong>{{ $equipo->codigo_patrimonial }}</strong></td>
                            <td>{{ $equipo->marca }} {{ $equipo->modelo }}</td>
                            <td>{{ $equipo->categoria->nombre }}</td>
                            <td><x-etiqueta :estado="$equipo->estado->value" /></td>
                            <td class="text-end pe-4">
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
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No se encontraron equipos con los criterios de búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($equipos->hasPages() || $equipos->total() > 0)
        <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
            <small class="text-muted">
                Mostrando <strong>{{ $equipos->firstItem() ?? 0 }}</strong> a <strong>{{ $equipos->lastItem() ?? 0 }}</strong> de <strong>{{ $equipos->total() }}</strong> resultados
            </small>
            <div>
                {{ $equipos->links() }}
            </div>
        </div>
    @endif
</div>
@endsection