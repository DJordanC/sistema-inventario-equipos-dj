@props(['variante' => 'primary', 'tipo' => 'button', 'icono' => null])

<button type="{{ $tipo }}" {{ $attributes->merge(['class' => "btn btn-$variante"]) }}>
    @if($icono)
        <i class="bi bi-{{ $icono }}"></i>
    @endif
    {{ $slot }}
</button>