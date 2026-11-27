@extends('layouts.app')
@section('title', 'Reporte de Solicitudes')

@section('content')
<div class="container-fluid py-2">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Historial de Solicitudes y Asignaciones</h2>
            <p class="text-muted small mb-0">Consulta cruzada de equipos, solicitudes y oficinas.</p>
        </div>
        
        <div class="no-print">
            <!-- BOTÓN ÚNICO DE EXPORTACIÓN -->
            <button onclick="exportarReporte()" class="btn btn-success shadow-sm btn-3d">
                <i class="bi bi-download"></i> Exportar Reporte
            </button>
        </div>
    </div>

    <!-- Tarjeta de Filtros (Oculta al imprimir) -->
    <div class="card shadow-sm border mb-4 no-print card-3d">
        <div class="card-body">
            <form action="{{ route('admin.reportes.asignaciones') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Oficina Receptora</label>
                    <select name="oficina_id" class="form-select input-3d" onchange="this.form.submit()">
                        <option value="">Todas las oficinas</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ request('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Estado del Equipo</label>
                    <select name="estado_equipo" class="form-select input-3d" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="asignado" {{ request('estado_equipo') == 'asignado' ? 'selected' : '' }}>Asignado</option>
                        <option value="en_mantenimiento" {{ request('estado_equipo') == 'en_mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                        <option value="disponible" {{ request('estado_equipo') == 'disponible' ? 'selected' : '' }}>Disponible (Devuelto)</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Estado de la Solicitud</label>
                    <select name="estado_solicitud" class="form-select input-3d" onchange="this.form.submit()">
                        <option value="">Todas las solicitudes</option>
                        <option value="aprobada" {{ request('estado_solicitud') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="pendiente" {{ request('estado_solicitud') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="rechazada" {{ request('estado_solicitud') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>

                <div class="col-md-3">
                    @if(request()->anyFilled(['oficina_id', 'estado_equipo', 'estado_solicitud']))
                        <a href="{{ route('admin.reportes.asignaciones') }}" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle"></i> Limpiar Filtros
                        </a>
                    @else
                        <button class="btn btn-outline-secondary w-100" type="button" disabled>
                            <i class="bi bi-funnel"></i> Sin filtros activos
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Resultados -->
    <div class="card shadow-sm border card-3d">
        <div class="card-body p-0">
            <div class="table-responsive">
                <!-- ID para extraer la tabla desde JS -->
                <table class="table table-hover mb-0 text-nowrap align-middle" id="tabla-reporte">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Fecha</th>
                            <th>Oficina</th>
                            <th>Solicitante</th>
                            <th>Categoría</th>
                            <th>Estado Solicitud</th>
                            <th>Equipos Asignados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $sol)
                            <tr>
                                <td class="ps-4 text-muted">{{ $sol->id }}</td>
                                <td>{{ $sol->created_at->format('d/m/Y h:i A') }}</td>
                                <td><span class="badge bg-info text-dark">{{ $sol->oficina->nombre }}</span></td>
                                <td>{{ $sol->usuario->nombre }}</td>
                                <td>{{ $sol->categoria->nombre }} (x{{ $sol->cantidad }})</td>
                                <td><x-etiqueta :estado="$sol->estado->value" /></td>
                                <td>
                                    @if($sol->estado->value === 'aprobada')
                                        @foreach($sol->asignaciones as $asig)
                                            <span class="badge bg-primary mb-1">
                                                {{ $asig->equipo->codigo_patrimonial }} ({{ $asig->equipo->estado->value }})
                                            </span><br>
                                        @endforeach
                                    @else
                                        <span class="text-muted fst-italic">No asignado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-search fs-1 d-block mb-3"></i>
                                    No se encontraron resultados con los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- PAGINACIÓN FORZADA -->
        <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3 no-print">
            <small class="text-muted">
                Mostrando <strong>{{ $solicitudes->firstItem() ?? 0 }}</strong> a <strong>{{ $solicitudes->lastItem() ?? 0 }}</strong> de <strong>{{ $solicitudes->total() }}</strong> resultados
            </small>
            
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item {{ $solicitudes->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $solicitudes->previousPageUrl() ?? '#' }}">&laquo; Anterior</a>
                </li>
                <li class="page-item disabled"><span class="page-link bg-light text-dark">Pág. {{ $solicitudes->currentPage() }} de {{ $solicitudes->lastPage() }}</span></li>
                <li class="page-item {{ !$solicitudes->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $solicitudes->nextPageUrl() ?? '#' }}">Siguiente &raquo;</a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function exportarReporte() {
        const queryString = window.location.search;

        Swal.fire({
            title: 'Exportar Reporte',
            text: '¿En qué formato deseas descargar el archivo?',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: '<i class="bi bi-file-earmark-pdf"></i> PDF',
            denyButtonText: '<i class="bi bi-file-earmark-excel"></i> Excel',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            denyButtonColor: '#198754',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                generarPDF();
            } else if (result.isDenied) {
                window.location.href = "{{ route('admin.reportes.exportar.csv') }}" + queryString;
            }
        });
    }

    function generarPDF() {
        // 1. Extraemos el HTML de la tabla
        const tablaOriginal = document.getElementById('tabla-reporte');
        const tablaClon = tablaOriginal.cloneNode(true);

        // 2. Limpiamos las clases de Bootstrap que puedan estorbar y adaptamos estilos
        tablaClon.className = 'table table-bordered';
        tablaClon.querySelectorAll('.badge').forEach(b => {
            b.className = 'badge'; 
            b.style.border = '1px solid #000';
            b.style.color = '#000';
            b.style.backgroundColor = 'transparent';
        });

        // 3. Abrimos una ventana nueva en blanco
        const ventana = window.open('', '_blank');
        
        // 4. Escribimos un HTML totalmente limpio solo para el PDF
        ventana.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Reporte PDF - SIE-DJ</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                    .header h2 { margin: 0; }
                    .header p { margin: 5px 0 0 0; font-size: 12px; color: #555; }
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 11px; }
                    th, td { border: 1px solid #444; padding: 6px; text-align: left; }
                    th { background-color: #f0f0f0; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>SIE-DJ | Historial de Solicitudes</h2>
                    <p>Generado el: ${new Date().toLocaleString()}</p>
                </div>
                ${tablaClon.outerHTML}
            </body>
            </html>
        `);
        
        // 5. Esperamos a que cargue y lanzamos la impresión
        ventana.document.close();
        ventana.focus();
        setTimeout(() => {
            ventana.print();
            ventana.close(); // Se cierra automáticamente después de imprimir
        }, 500);
    }
</script>
@endpush