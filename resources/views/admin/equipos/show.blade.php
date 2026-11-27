@extends('layouts.app')
@section('title', 'Detalles del Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        
        <!-- Botón Volver -->
        <div class="mb-3">
            <a href="{{ route('admin.equipos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver al Inventario
            </a>
        </div>

        <!-- Tarjeta de Detalles del Equipo -->
        <div class="card shadow-sm mb-4 card-3d">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">Detalles del Equipo</h5>
                <x-etiqueta :estado="$equipo->estado->value" />
            </div>
            <div class="card-body">
                <!-- ... (aquí va el código que ya tenías de detalles, marca, modelo, etc) ... -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Código Patrimonial</h6>
                        <p class="fs-5 fw-bold">{{ $equipo->codigo_patrimonial }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Categoría</h6>
                        <p class="fs-5">{{ $equipo->categoria->nombre }}</p>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Marca</h6>
                        <p>{{ $equipo->marca }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Modelo</h6>
                        <p>{{ $equipo->modelo }}</p>
                    </div>
                </div>
                @if($equipo->cpu || $equipo->ram || $equipo->almacenamiento)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted">CPU</h6>
                            <p>{{ $equipo->cpu ?? 'No especificado' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">RAM</h6>
                            <p>{{ $equipo->ram ?? 'No especificado' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Almacenamiento</h6>
                            <p>{{ $equipo->almacenamiento ?? 'No especificado' }}</p>
                        </div>
                    </div>
                @endif
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.equipos.edit', $equipo) }}" class="btn btn-warning">Editar Equipo</a>
                </div>
            </div>
        </div>

        <!-- Información de Asignación (Si aplica) -->
        @if($equipo->estado->value === 'asignado' && $equipo->asignacionActiva)
            <div class="card shadow-sm border-0 mb-4" style="border-left: 5px solid #0d6efd !important;">
                <div class="card-header bg-white pt-3 pb-0 border-0">
                    <h5 class="fw-bold text-primary"><i class="bi bi-info-circle-fill"></i> Información de Asignación</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Departamento / Oficina</h6>
                            <p class="fs-5 fw-bold text-dark">{{ $equipo->asignacionActiva->solicitud->oficina->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Solicitado por</h6>
                            <p class="fs-5">{{ $equipo->asignacionActiva->solicitud->usuario->nombre }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Fecha de Asignación</h6>
                            <p>{{ $equipo->asignacionActiva->asignado_en->format('d/m/Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Solicitud Original</h6>
                            <a href="{{ route('admin.solicitudes.show', $equipo->asignacionActiva->solicitud_id) }}" class="btn btn-sm btn-outline-primary">
                                Ver Solicitud #{{ $equipo->asignacionActiva->solicitud_id }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- NUEVO: Historial de Estados y Mantenimiento -->
        <div class="card shadow-sm border-0 card-3d">
            <div class="card-header bg-white pt-3 pb-2 border-0">
                <h5 class="fw-bold"><i class="bi bi-clock-history"></i> Historial de Estados</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($equipo->registrosEstado()->latest('created_at')->get() as $registro)
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <strong>
                                    @if($registro->estado_desde)
                                        {{ ucfirst(str_replace('_', ' ', $registro->estado_desde->value)) }} 
                                        <i class="bi bi-arrow-right mx-1"></i> 
                                    @endif
                                    {{ ucfirst(str_replace('_', ' ', $registro->estado_hasta->value)) }}
                                </strong>
                                <small class="text-muted">{{ $registro->created_at->format('d/m/Y h:i A') }}</small>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-person"></i> {{ $registro->cambiadoPor->nombre ?? 'Sistema' }}
                                @if($registro->notas)
                                    <br><i class="bi bi-chat-left-text"></i> <em>{{ $registro->notas }}</em>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-muted">No hay historial registrado.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection