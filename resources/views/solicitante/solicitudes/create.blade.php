@extends('layouts.app')
@section('title', 'Nueva Solicitud')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-0 border-0 text-center">
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
                    
                    <!-- Selector de Oficina -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Oficina / Departamento Destino</label>
                        <select name="oficina_id" class="form-select" required>
                            <option value="">Seleccione la oficina...</option>
                            @foreach($oficinas as $oficina)
                                <option value="{{ $oficina->id }}" {{ old('oficina_id', Auth::user()->oficina_id) == $oficina->id ? 'selected' : '' }}>
                                    {{ $oficina->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selector de Categoría (Con Stock) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Categoría de Equipo</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Seleccione una categoría...</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nombre }} (Disponibles: {{ $cat->equipos_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cantidad -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cantidad requerida</label>
                        <input type="number" name="cantidad" class="form-control" min="1" value="{{ old('cantidad', 1) }}" required>
                    </div>

                    <!-- Justificación -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Justificación de la solicitud</label>
                        <textarea name="justificacion" class="form-control" rows="3" required placeholder="Explique brevemente por qué necesita estos equipos...">{{ old('justificacion') }}</textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-send"></i> Enviar Solicitud
                        </button>
                        <a href="{{ route('solicitante.solicitudes.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection