@props(['estado'])

@php
    $clases = match($estado) {
        'disponible' => 'bg-success',
        'asignado' => 'bg-primary',
        'en_mantenimiento' => 'bg-warning text-dark',
        'pendiente' => 'bg-warning text-dark',
        'aprobada' => 'bg-success',
        'rechazada' => 'bg-danger',
        default => 'bg-secondary',
    };
    $texto = match($estado) {
        'disponible' => 'Disponible',
        'asignado' => 'Asignado',
        'en_mantenimiento' => 'En Mantenimiento',
        'pendiente' => 'Pendiente',
        'aprobada' => 'Aprobada',
        'rechazada' => 'Rechazada',
        default => 'Desconocido',
    };
@endphp

<span class="badge {{ $clases }}">{{ $texto }}</span>