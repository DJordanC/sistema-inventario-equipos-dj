@extends('layouts.app')
@section('title', 'Gestión de Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Usuarios</h2>
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> Nuevo Usuario
    </a>
</div>

@if(session('success'))
    <x-alerta tipo="success">{{ session('success') }}</x-alerta>
@endif
@if(session('error'))
    <x-alerta tipo="danger">{{ session('error') }}</x-alerta>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Oficina</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $user->nombre }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->rol->value === 'administrador' ? 'bg-primary' : 'bg-success' }}">
                                    {{ $user->rol->etiqueta() }}
                                </span>
                            </td>
                            <td>{{ $user->oficina->nombre ?? 'N/A' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.usuarios.edit', $user) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" class="d-inline form-eliminar">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $usuarios->links() }}</div>
@endsection