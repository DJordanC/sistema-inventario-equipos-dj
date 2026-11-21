@extends('layouts.app')
@section('title', 'Nueva Solicitud')

@section('content')
<div class="row justify-content-center">
    <!-- Ampliamos un poco el ancho para que quepan 2 columnas -->
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm border-0">
            
            <!-- Encabezado más compacto -->
            <div class="card-header bg-white pt-4 pb-2 border-0">
                <h4 class="fw-bold text-success mb-1">
                    <i class="bi bi-file-earmark-plus"></i> Crear Nueva Solicitud
                </h4>
                <p class="text-muted small mb-0">Complete los datos para requerir equipos al departamento de Soporte Técnico.</p>
            </div>
            
            <div class="card-body px-4 pb-4 pt-3">
                @if ($errors->any())
                    <x-alerta tipo="danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </x-alerta>
                @endif

                <form action="{{ route('solicitante.solicitudes.store') }}" method="POST">
                    @csrf
                    
                    <!-- Fila 1: Oficina -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Oficina / Departamento Destino</label>
                        <select name="oficina_id" class="form-select" required>
                            <option value="">Seleccione la oficina...</option>
                            @foreach($oficinas as $oficina)
                                <option value="{{ $oficina->id }}" {{ old('oficina_id', Auth::user()->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                    {{ $oficina->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fila 2: Categoría (Ocupa 8 columnas) y Cantidad (Ocupa 4 columnas) -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-muted text-uppercase">Categoría de Equipo</label>
                            <select name="categoria_id" class="form-select" required>
                                <option value="">Seleccione una categoría...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nombre }} (Stock: {{ $cat->equipos_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" min="1" value="{{ old('cantidad', 1) }}" required>
                        </div>
                    </div>

                    <!-- Fila 3: Justificación (Reducimos a 2 filas de alto) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Justificación de la solicitud</label>
                        <textarea name="justificacion" class="form-control" rows="2" required placeholder="Explique brevemente por qué necesita estos equipos...">{{ old('justificacion') }}</textarea>
                    </div>

                    <hr class="my-3">

                    <!-- Botones alineados a la derecha para ahorrar espacio vertical -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('solicitante.solicitudes.index') }}" class="btn btn-outline-secondary px-4">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-send"></i> Enviar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection