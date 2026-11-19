@props(['tipo' => 'info', 'cerrable' => true])

<div {{ $attributes->merge(['class' => "alert alert-$tipo " . ($cerrable ? 'alert-dismissible fade show' : '')]) }} role="alert">
    {{ $slot }}
    @if($cerrable)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>