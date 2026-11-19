@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <h4 class="mb-4 text-center fw-bold text-dark">Iniciar Sesión</h4>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm small mb-4">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Campo Correo -->
            <div class="input-wrapper">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" class="form-control form-control-custom" 
                       value="{{ old('email') }}" placeholder="Correo Electrónico" required autofocus>
            </div>
            
            <!-- Campo Contraseña -->
            <div class="input-wrapper mb-4">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" id="passwordInput" name="password" class="form-control form-control-custom" 
                       placeholder="Contraseña" required>
                <!-- Ojito -->
                <i class="bi bi-eye password-toggle" id="togglePassword"></i>
            </div>
            
            <!-- Botón Entrar -->
            <button type="submit" class="btn btn-primary btn-custom w-100 text-white">
                Entrar al Sistema
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');

        togglePassword.addEventListener('click', function () {
            // Alternar tipo de input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Alternar el ícono (ojo abierto / ojo tachado)
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
            
            // Pintar de azul cuando está visible
            if(type === 'text') {
                this.style.color = '#3b82f6';
            } else {
                this.style.color = '#a0aec0';
            }
        });
    });
</script>
@endpush