@extends('layouts.app')
@section('title', 'Nuevo Equipo')

@section('content')
<div class="row justify-content-center">
    <!-- En pantallas grandes ocupa 8 columnas, en móviles 12 -->
    <div class="col-12 col-md-10 col-lg-8">
        
        <div class="card card-3d">
            <div class="card-header bg-white border-0 pt-4 pb-0 text-center rounded-top" style="border-radius: 1rem 1rem 0 0;">
                <h4 class="fw-bold text-primary"><i class="bi bi-pc-display"></i> Registrar Nuevo Equipo</h4>
                <p class="text-muted small">Ingrese los datos del bien informático.</p>
            </div>
            
            <div class="card-body p-4">
                @if ($errors->any())
                    <x-alerta tipo="danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </x-alerta>
                @endif

                <form action="{{ route('admin.equipos.store') }}" method="POST" id="form-equipo">
                    @csrf
                    
                    <!-- Incluimos el formulario parcial con los estilos 3D -->
                    @include('admin.equipos._form')
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="reset" class="btn btn-outline-secondary btn-3d" id="btn-limpiar">
                            <i class="bi bi-eraser"></i> Limpiar
                        </button>
                        
                        <div>
                            <a href="{{ route('admin.equipos.index') }}" class="btn btn-light border me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary btn-3d px-4">
                                <i class="bi bi-save"></i> Guardar Equipo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Lógica para actualizar la interfaz visual cuando se limpia el formulario
    document.addEventListener('DOMContentLoaded', function() {
        const btnLimpiar = document.getElementById('btn-limpiar');
        if(btnLimpiar) {
            btnLimpiar.addEventListener('click', function() {
                setTimeout(() => {
                    const selectCategoria = document.getElementById('categoria_id');
                    if (selectCategoria) selectCategoria.dispatchEvent(new Event('change'));
                    
                    const selectEstado = document.getElementById('estado');
                    if (selectEstado) selectEstado.dispatchEvent(new Event('change'));
                }, 50);
            });
        }
    });
</script>
@endpush