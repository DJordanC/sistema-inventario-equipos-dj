<div class="row">
    <!-- Columna Izquierda -->
    <div class="col-12 col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold text-muted small text-uppercase">Nombre Completo</label>
            <input type="text" name="nombre" class="form-control input-3d" value="{{ old('nombre', $usuario->nombre ?? '') }}" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold text-muted small text-uppercase">Contraseña</label>
            <input type="password" name="password" class="form-control input-3d" {{ isset($usuario) ? '' : 'required' }}>
            @if(isset($usuario))
                <div class="form-text text-muted small">Dejar en blanco para mantener la actual.</div>
            @endif
        </div>
    </div>

    <!-- Columna Derecha -->
    <div class="col-12 col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold text-muted small text-uppercase">Correo Electrónico</label>
            <input type="email" name="email" class="form-control input-3d" value="{{ old('email', $usuario->email ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-muted small text-uppercase">Rol / Privilegios</label>
            <select name="rol" class="form-select input-3d" required>
                @foreach($roles as $rol)
                    <option value="{{ $rol->value }}" {{ old('rol', $usuario->rol->value ?? '') == $rol->value ? 'selected' : '' }}>
                        {{ $rol->etiqueta() }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Fila Completa para la Oficina -->
    <div class="col-12">
        <div class="mb-3">
            <label class="form-label fw-bold text-muted small text-uppercase">Oficina / Departamento</label>
            <select name="oficina_id" class="form-select input-3d">
                <option value="">Ninguna (Solo para Administradores)</option>
                @foreach($oficinas as $oficina)
                    <option value="{{ $oficina->id }}" {{ old('oficina_id', $usuario->oficina_id ?? '') == $oficina->id ? 'selected' : '' }}>
                        {{ $oficina->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>