<div class="row">
    <!-- 1. PRIMERO LA CATEGORÍA -->
    <div class="col-12 col-md-6 mb-3">
        <label class="form-label fw-bold text-muted small text-uppercase">Categoría</label>
        <select name="categoria_id" id="categoria_id" class="form-select input-3d" required>
            <option value="">Seleccione...</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" data-nombre="{{ strtolower($cat->nombre) }}" {{ old('categoria_id', $equipo->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- 2. CÓDIGO PATRIMONIAL -->
    <div class="col-12 col-md-6 mb-3">
        <label class="form-label fw-bold text-muted small text-uppercase">Código Patrimonial</label>
        <input type="text" name="codigo_patrimonial" id="codigo_patrimonial" class="form-control input-3d" 
               value="{{ old('codigo_patrimonial', $equipo->codigo_patrimonial ?? '') }}" 
               placeholder="Ej: EQ-001" required>
        <div class="form-text text-muted small" id="ayuda-codigo">Ingrese el código único del equipo.</div>
    </div>
    
    <div class="col-12 col-md-6 mb-3">
        <label class="form-label fw-bold text-muted small text-uppercase">Marca</label>
        <input type="text" name="marca" class="form-control input-3d" value="{{ old('marca', $equipo->marca ?? '') }}" required>
    </div>
    
    <div class="col-12 col-md-6 mb-3">
        <label class="form-label fw-bold text-muted small text-uppercase">Modelo</label>
        <input type="text" name="modelo" class="form-control input-3d" value="{{ old('modelo', $equipo->modelo ?? '') }}" required>
    </div>

    <!-- 3. COMPONENTES (Se ocultan dinámicamente) -->
    <div class="col-12" id="contenedor-componentes" style="display: none;">
        <div class="row">
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label fw-bold text-muted small text-uppercase">CPU</label>
                <input type="text" name="cpu" id="input_cpu" class="form-control input-3d" value="{{ old('cpu', $equipo->cpu ?? '') }}">
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label fw-bold text-muted small text-uppercase">RAM</label>
                <input type="text" name="ram" id="input_ram" class="form-control input-3d" value="{{ old('ram', $equipo->ram ?? '') }}">
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label fw-bold text-muted small text-uppercase">Almacenamiento</label>
                <input type="text" name="almacenamiento" id="input_almacenamiento" class="form-control input-3d" value="{{ old('almacenamiento', $equipo->almacenamiento ?? '') }}">
            </div>
        </div>
    </div>

    <!-- 4. ESTADO -->
    <div class="col-12 col-md-6 mb-3">
        <label class="form-label fw-bold text-muted small text-uppercase">Estado</label>
        <select name="estado" id="estado" class="form-select input-3d" required>
            <option value="disponible" {{ old('estado', $equipo->estado->value ?? '') == 'disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="asignado" {{ old('estado', $equipo->estado->value ?? '') == 'asignado' ? 'selected' : '' }}>Asignado</option>
            <option value="en_mantenimiento" {{ old('estado', $equipo->estado->value ?? '') == 'en_mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
        </select>
    </div>

    <!-- Campo de Notas para Mantenimiento -->
    <div class="col-12 col-md-6 mb-3" id="contenedor-notas" style="display: none;">
        <label class="form-label fw-bold text-warning small text-uppercase" id="label-notas">Notas del Cambio</label>
        <input type="text" name="notas_estado" class="form-control input-3d border-warning" 
               placeholder="Ej: Cambio de pasta térmica...">
    </div>

    <!-- 5. INFO DE ASIGNACIÓN -->
    <div class="col-12 mb-3" id="info-asignacion" style="display: none;">
        <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 shadow-sm">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-building"></i> Asignación de Oficina</h6>
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold small">Oficina / Departamento</label>
                    <select name="oficina_id" id="oficina_id" class="form-select input-3d border-primary">
                        <option value="">Seleccione una oficina...</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" 
                                {{ old('oficina_id', $equipo->asignacionActiva->solicitud->oficina_id ?? '') == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectCategoria = document.getElementById('categoria_id');
        const contenedorComponentes = document.getElementById('contenedor-componentes');
        const inputCpu = document.getElementById('input_cpu');
        const inputRam = document.getElementById('input_ram');
        const inputAlmacenamiento = document.getElementById('input_almacenamiento');
        const inputCodigo = document.getElementById('codigo_patrimonial');
        const ayudaCodigo = document.getElementById('ayuda-codigo');

        const selectEstado = document.getElementById('estado');
        const infoAsignacion = document.getElementById('info-asignacion');
        const selectOficina = document.getElementById('oficina_id');
        const contenedorNotas = document.getElementById('contenedor-notas');
        const labelNotas = document.getElementById('label-notas');

        function obtenerPrefijo(nombreCategoria) {
            if (nombreCategoria.includes('laptop')) return 'LAP';
            if (nombreCategoria.includes('desktop')) return 'DES';
            if (nombreCategoria.includes('impresora')) return 'IMP';
            if (nombreCategoria.includes('monitor')) return 'MON';
            return 'EQ';
        }

        function actualizarFormulario() {
            const opcionSeleccionada = selectCategoria.options[selectCategoria.selectedIndex];
            const nombreCategoria = opcionSeleccionada ? opcionSeleccionada.getAttribute('data-nombre') : '';
            const prefijo = obtenerPrefijo(nombreCategoria || '');

            if (nombreCategoria.includes('impresora') || nombreCategoria.includes('monitor')) {
                contenedorComponentes.style.display = 'none';
                inputCpu.disabled = true; inputRam.disabled = true; inputAlmacenamiento.disabled = true;
                inputCpu.value = ''; inputRam.value = ''; inputAlmacenamiento.value = '';
            } else {
                contenedorComponentes.style.display = 'block';
                inputCpu.disabled = false; inputRam.disabled = false; inputAlmacenamiento.disabled = false;
            }

            inputCodigo.placeholder = `Ej: ${prefijo}-001`;
            ayudaCodigo.textContent = `Sugerencia: Use el prefijo ${prefijo}-`;
        }

        function actualizarEstado() {
            if (selectEstado.value === 'asignado') {
                infoAsignacion.style.display = 'block';
                if(selectOficina) selectOficina.required = true;
            } else {
                infoAsignacion.style.display = 'none';
                if(selectOficina) { selectOficina.required = false; selectOficina.value = ''; }
            }

            if (selectEstado.value === 'en_mantenimiento') {
                contenedorNotas.style.display = 'block';
                labelNotas.textContent = 'Motivo del Mantenimiento';
            } else {
                contenedorNotas.style.display = 'none';
            }
        }

        selectCategoria.addEventListener('change', actualizarFormulario);
        selectEstado.addEventListener('change', actualizarEstado);
        
        actualizarFormulario();
        actualizarEstado();
    });
</script>
@endpush