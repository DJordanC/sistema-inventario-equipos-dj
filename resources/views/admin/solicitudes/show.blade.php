@extends('layouts.app')
@section('title', 'Revisar Solicitud #' . $solicitud->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        
        <!-- BOTÓN VOLVER -->
        <div class="mb-3">
            <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
        </div>

        <!-- Tarjeta de Información de la Solicitud -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">Solicitud #{{ $solicitud->id }} - {{ $solicitud->oficina->nombre }}</h5>
                <x-etiqueta :estado="$solicitud->estado->value" />
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small text-uppercase fw-bold">Solicitante</p>
                        <p class="fs-5">{{ $solicitud->usuario->nombre }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-1 text-muted small text-uppercase fw-bold">Categoría</p>
                        <p class="fs-5">{{ $solicitud->categoria->nombre }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-1 text-muted small text-uppercase fw-bold">Cantidad Requerida</p>
                        <p class="fs-5 fw-bold text-primary">{{ $solicitud->cantidad }}</p>
                    </div>
                </div>
                
                <div class="mb-0">
                    <p class="mb-1 text-muted small text-uppercase fw-bold">Justificación</p>
                    <p class="mb-0 bg-light p-3 rounded border">{{ $solicitud->justificacion }}</p>
                </div>

                @if($solicitud->estado->value !== 'pendiente')
                    <hr class="my-4">
                    <div class="alert alert-secondary mb-0 d-flex justify-content-between align-items-center">
                        <span>
                            <i class="bi bi-info-circle"></i> 
                            Esta solicitud fue procesada el <strong>{{ $solicitud->revisado_en ? $solicitud->revisado_en->format('d/m/Y h:i A') : 'N/A' }}</strong>.
                        </span>
                        @if($solicitud->estado->value === 'rechazada')
                            <span class="text-danger fw-bold">Motivo: {{ $solicitud->motivo_rechazo }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Sección de Aprobación (Solo si está Pendiente) -->
        @if($solicitud->estado->value === 'pendiente')
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3 text-primary"><i class="bi bi-check2-square"></i> Asignación de Equipos</h5>
                    
                    @php
                        $mostrarComponentes = $equiposDisponibles->first() && $equiposDisponibles->first()->cpu !== null;
                    @endphp

                    <form action="{{ route('admin.solicitudes.aprobar', $solicitud) }}" method="POST" id="form-aprobar">
                        @csrf
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <span>Seleccione exactamente <strong>{{ $solicitud->cantidad }}</strong> equipo(s) de la lista a continuación.</span>
                            <span class="badge bg-primary fs-6">Categoría: {{ $solicitud->categoria->nombre }}</span>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60" class="text-center">Sel.</th>
                                        <th>Código Patrimonial</th>
                                        <th>Marca / Modelo</th>
                                        @if($mostrarComponentes)
                                            <th>Componentes (CPU / RAM / Almac.)</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equiposDisponibles as $equipo)
                                        <tr>
                                            <td class="text-center">
                                                <input class="form-check-input fs-4 checkbox-equipo" type="checkbox" name="equipo_ids[]" value="{{ $equipo->id }}">
                                            </td>
                                            <td class="fw-bold">{{ $equipo->codigo_patrimonial }}</td>
                                            <td>{{ $equipo->marca }} {{ $equipo->modelo }}</td>
                                            
                                            @if($mostrarComponentes)
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $equipo->cpu }} <br> 
                                                        {{ $equipo->ram }} | {{ $equipo->almacenamiento }}
                                                    </small>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $mostrarComponentes ? 4 : 3 }}" class="text-center text-danger py-4">
                                                <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                                                No hay equipos de la categoría <strong>{{ $solicitud->categoria->nombre }}</strong> disponibles en el inventario actualmente.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Botón para abrir modal de rechazo -->
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalRechazar">
                                <i class="bi bi-x-circle"></i> Rechazar Solicitud
                            </button>
                            
                            <!-- Botón de Aprobación -->
                            <button type="submit" class="btn btn-success btn-lg" id="btn-aprobar" {{ $equiposDisponibles->count() < $solicitud->cantidad ? 'disabled' : '' }}>
                                <i class="bi bi-check-circle"></i> Aprobar y Asignar
                            </button>
                        </div>
                        <div class="text-end mt-2">
                            <small class="text-muted" id="contador-seleccionados">0 equipos seleccionados</small>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal para Rechazar -->
<div class="modal fade" id="modalRechazar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.solicitudes.rechazar', $solicitud) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Motivo de Rechazo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Por favor, indique al solicitante por qué no se puede procesar su requerimiento.</p>
                    <textarea name="motivo_rechazo" class="form-control" rows="3" required placeholder="Ej: No hay stock presupuestado para este mes..."></textarea>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.checkbox-equipo');
        const contador = document.getElementById('contador-seleccionados');
        const maxEquipos = {{ $solicitud->cantidad }};
        const btnAprobar = document.getElementById('btn-aprobar');

        if (checkboxes.length === 0) return;

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const seleccionados = document.querySelectorAll('.checkbox-equipo:checked').length;
                
                contador.textContent = `${seleccionados} de ${maxEquipos} equipos seleccionados`;
                
                if (seleccionados > maxEquipos) {
                    contador.classList.add('text-danger', 'fw-bold');
                    contador.classList.remove('text-success');
                    btnAprobar.disabled = true;
                    btnAprobar.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Excede la cantidad';
                } else if (seleccionados === maxEquipos) {
                    contador.classList.remove('text-danger', 'fw-bold');
                    contador.classList.add('text-success', 'fw-bold');
                    btnAprobar.disabled = false;
                    btnAprobar.innerHTML = '<i class="bi bi-check-circle"></i> Aprobar y Asignar';
                } else {
                    contador.classList.remove('text-danger', 'fw-bold', 'text-success');
                    btnAprobar.disabled = true;
                    btnAprobar.innerHTML = '<i class="bi bi-check-circle"></i> Aprobar y Asignar';
                }
            });
        });

        document.getElementById('form-aprobar').addEventListener('submit', function(e) {
            const seleccionados = document.querySelectorAll('.checkbox-equipo:checked').length;
            if (seleccionados !== maxEquipos) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Cantidad incorrecta',
                    text: `Debe seleccionar exactamente ${maxEquipos} equipo(s) para poder aprobar.`,
                    confirmButtonColor: '#f59e0b'
                });
            }
        });
    });
</script>
@endpush