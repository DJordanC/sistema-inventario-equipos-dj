@extends('layouts.app')
@section('title', 'Nuevo Usuario')

@section('content')
<div class="row justify-content-center">
    <!-- AMPLIAMOS EL ANCHO PARA APROVECHAR EL ESPACIO -->
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card card-3d">
            <div class="card-header bg-white border-0 pt-4 pb-0 text-center rounded-top" style="border-radius: 1rem 1rem 0 0;">
                <h4 class="fw-bold text-primary"><i class="bi bi-person-plus"></i> Nuevo Usuario</h4>
                <p class="text-muted small">Cree una nueva cuenta y asigne sus privilegios.</p>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <x-alerta tipo="danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></x-alerta>
                @endif

                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    @include('admin.usuarios._form')
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-light border me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary btn-3d px-4"><i class="bi bi-save"></i> Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection