@extends('layouts.app')
@section('title', 'Nuevo Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-0 border-0 text-center">
                <h4 class="fw-bold text-primary"><i class="bi bi-person-plus"></i> Nuevo Usuario</h4>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <x-alerta tipo="danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></x-alerta>
                @endif

                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    @include('admin.usuarios._form')
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection