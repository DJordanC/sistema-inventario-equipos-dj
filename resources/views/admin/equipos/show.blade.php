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
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">Detalles del Equipo</h5>
                <x-etiqueta :estado="$equipo->estado->value" />
            </div>
            <div class="card-body">
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

        <!-- SECCIÓN DE ASIGNACIÓN -->
        @if($equipo->estado->value === 'asignado')
            @if($equipo->asignacionActiva)
                <div class="card shadow-sm border-0" style="border-left: 5px solid #0d6efd !important;">
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
            @else
                <div class="alert alert-warning d-flex align-items-center shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div>
                        <strong>Atención:</strong> Este equipo figura como "Asignado", pero no tiene un registro de oficina en el sistema.
                        <br>
                        Por favor, dale clic en <strong>"Editar Equipo"</strong>, selecciona la oficina y guarda para corregir el error.
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection