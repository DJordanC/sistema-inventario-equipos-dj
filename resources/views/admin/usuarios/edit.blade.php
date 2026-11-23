@extends('layouts.app')
@section('title', 'Editar Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card card-3d">
            <div class="card-header bg-white border-0 pt-4 pb-0 text-center rounded-top" style="border-radius: 1rem 1rem 0 0;">
                <h4 class="fw-bold text-warning"><i class="bi bi-pencil-square"></i> Editar Usuario</h4>
                <p class="text-muted small">Actualice los datos del perfil de {{ $usuario->nombre }}.</p>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <x-alerta tipo="danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></x-alerta>
                @endif

                <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
                    @csrf @method('PUT')
                    @include('admin.usuarios._form')
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-light border me-2">Cancelar</a>
                        <button type="submit" class="btn btn-warning btn-3d px-4 text-dark fw-bold"><i class="bi bi-save"></i> Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection