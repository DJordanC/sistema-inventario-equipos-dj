@extends('layouts.app')
@section('title', 'Detalle de la Solicitud #' . $solicitud->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        
        <!-- Botón Volver -->
        <div class="mb-3">
            <a href="{{ route('solicitante.solicitudes.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver a mis solicitudes
            </a>
        </div>

        <!-- Tarjeta Principal -->
        <div class="card shadow-sm border-0 mb-4 card-3d">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">Solicitud #{{ $solicitud->id }}</h5>
                <x-etiqueta :estado="$solicitud->estado->value" />
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted small fw-bold text-uppercase">Oficina Destino</h6>
                        <p class="fs-5">{{ $solicitud->oficina->nombre }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted small fw-bold text-uppercase">Categoría Solicitada</h6>
                        <p class="fs-5">{{ $solicitud->categoria->nombre }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted small fw-bold text-uppercase">Cantidad</h6>
                        <p class="fs-5 fw-bold text-primary">{{ $solicitud->cantidad }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted small fw-bold text-uppercase">Fecha de Solicitud</h6>
                        <p>{{ $solicitud->created_at->format('d/m/Y h:i A') }}</p>
                    </div>
                </div>

                <div class="mb-0">
                    <h6 class="text-muted small fw-bold text-uppercase">Justificación</h6>
                    <p class="bg-light p-3 rounded border">{{ $solicitud->justificacion }}</p>
                </div>

                @if($solicitud->estado->value === 'rechazada')
                    <div class="alert alert-danger mt-3">
                        <h6 class="fw-bold"><i class="bi bi-x-circle"></i> Motivo del Rechazo:</h6>
                        <p class="mb-0">{{ $solicitud->motivo_rechazo }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- SECCIÓN DE EQUIPOS ASIGNADOS (Solo si fue aprobada) -->
        @if($solicitud->estado->value === 'aprobada')
            <div class="card shadow-sm border-0" style="border-left: 5px solid #198754 !important;">
                <div class="card-header bg-white pt-3 pb-0 border-0">
                    <h5 class="fw-bold text-success"><i class="bi bi-check-circle-fill"></i> Equipos Asignados</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Los siguientes equipos han sido asignados a su oficina:</p>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Código Patrimonial</th>
                                    <th>Marca / Modelo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($solicitud->asignaciones as $asignacion)
                                    <tr>
                                        <td class="fw-bold">{{ $asignacion->equipo->codigo_patrimonial }}</td>
                                        <td>{{ $asignacion->equipo->marca }} {{ $asignacion->equipo->modelo }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @elseif($solicitud->estado->value === 'pendiente')
            <div class="alert alert-warning shadow-sm border-0 card-3d">
                <i class="bi bi-clock-history fs-5 me-2"></i>
                <strong>En espera:</strong> El departamento de Soporte Técnico aún está revisando su solicitud. 
                Una vez aprobada, verá aquí la lista de equipos asignados.
            </div>
        @endif

    </div>
</div>
@endsection