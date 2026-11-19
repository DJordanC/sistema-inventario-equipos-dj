@extends('layouts.app')
@section('title', 'Editar Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Editar Equipo: {{ $equipo->codigo_patrimonial }}</h5></div>
            <div class="card-body">
                @if ($errors->any())
                    <x-alerta tipo="danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </x-alerta>
                @endif

                <form action="{{ route('admin.equipos.update', $equipo) }}" method="POST">
                    @csrf @method('PUT')
                    @include('admin.equipos._form')
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.equipos.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <x-boton tipo="submit" variante="primary" icono="save">Actualizar Equipo</x-boton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection