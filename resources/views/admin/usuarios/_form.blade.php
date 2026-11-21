<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Nombre Completo</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre ?? '') }}" required>
    </div>
    
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Correo Electrónico</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Contraseña</label>
        <input type="password" name="password" class="form-control" {{ isset($usuario) ? '' : 'required' }}>
        @if(isset($usuario))
            <div class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</div>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Rol / Privilegios</label>
        <select name="rol" class="form-select" required>
            @foreach($roles as $rol)
                <option value="{{ $rol->value }}" {{ old('rol', $usuario->rol->value ?? '') == $rol->value ? 'selected' : '' }}>
                    {{ $rol->etiqueta() }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">Oficina / Departamento</label>
        <select name="oficina_id" class="form-select">
            <option value="">Ninguna (Solo para Administradores)</option>
            @foreach($oficinas as $oficina)
                <option value="{{ $oficina->id }}" {{ old('oficina_id', $usuario->oficina_id ?? '') == $oficina->id ? 'selected' : '' }}>
                    {{ $oficina->nombre }}
                </option>
            @endforeach
        </select>
    </div>
</div>