@extends('layouts.app')
@section('title', 'Nueva Solicitud')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card card-3d">
            <div class="card-header bg-white border-0 pt-4 pb-0 text-center rounded-top" style="border-radius: 1rem 1rem 0 0;">
                <h4 class="fw-bold text-success"><i class="bi bi-file-earmark-plus"></i> Crear Nueva Solicitud</h4>
                <p class="text-muted small">Complete los datos para requerir equipos al departamento de Soporte Técnico.</p>
            </div>
            
            <div class="card-body p-4">
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
                        <label class="form-label fw-bold text-muted small text-uppercase">Oficina / Departamento Destino</label>
                        <select name="oficina_id" class="form-select input-3d" required>
                            <option value="">Seleccione la oficina...</option>
                            @foreach($oficinas as $oficina)
                                <option value="{{ $oficina->id }}" {{ old('oficina_id', Auth::user()->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                    {{ $oficina->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fila 2: Categoría y Cantidad (Aprovechando el espacio) -->
                    <div class="row mb-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold text-muted small text-uppercase">Categoría de Equipo</label>
                            <select name="categoria_id" class="form-select input-3d" required>
                                <option value="">Seleccione una categoría...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nombre }} (Stock: {{ $cat->equipos_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control input-3d" min="1" value="{{ old('cantidad', 1) }}" required>
                        </div>
                    </div>

                    <!-- Fila 3: Justificación -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Justificación de la solicitud</label>
                        <textarea name="justificacion" class="form-control input-3d" rows="3" required placeholder="Explique brevemente por qué necesita estos equipos...">{{ old('justificacion') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('solicitante.solicitudes.index') }}" class="btn btn-light border px-4">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-3d px-4">
                            <i class="bi bi-send"></i> Enviar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection