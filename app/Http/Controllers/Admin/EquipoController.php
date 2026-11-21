<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Categoria;
use App\Models\Oficina;
use App\Models\Solicitud;
use App\Models\Asignacion;
use App\Models\RegistroEstadoEquipo;
use App\Http\Requests\GuardarEquipoRequest;
use App\Http\Requests\ActualizarEquipoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipo::with('categoria');
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        $equipos = $query->latest()->paginate(10)->withQueryString();
        return view('admin.equipos.index', compact('equipos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $oficinas = Oficina::all();
        return view('admin.equipos.create', compact('categorias', 'oficinas'));
    }

    public function store(GuardarEquipoRequest $request)
    {
        $equipo = Equipo::create($request->validated());

        // Registrar el estado inicial en el historial
        RegistroEstadoEquipo::create([
            'equipo_id' => $equipo->id,
            'estado_desde' => null,
            'estado_hasta' => $equipo->estado->value,
            'cambiado_por' => Auth::id(),
            'notas' => 'Registro inicial del equipo en el inventario.'
        ]);

        if ($request->estado === 'asignado' && $request->oficina_id) {
            $this->asignarEquipo($equipo, $request->oficina_id);
        }

        return redirect()->route('admin.equipos.index')->with('success', 'Equipo registrado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        $equipo->load([
            'categoria', 
            'asignacionActiva.solicitud.oficina', 
            'asignacionActiva.solicitud.usuario',
            'registrosEstado.cambiadoPor' // Cargamos el historial para mostrarlo en la vista
        ]);
        return view('admin.equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo)
    {
        $equipo->load([
            'categoria', 
            'asignacionActiva.solicitud.oficina', 
            'asignacionActiva.solicitud.usuario'
        ]);
        
        $categorias = Categoria::all();
        $oficinas = Oficina::all();
        
        return view('admin.equipos.edit', compact('equipo', 'categorias', 'oficinas'));
    }

    public function update(ActualizarEquipoRequest $request, Equipo $equipo)
    {
        $estadoAnterior = $equipo->estado->value;
        $equipo->update($request->validated());

        // Si el estado cambió, registrar en el historial con las notas
        if ($estadoAnterior !== $equipo->estado->value || $request->filled('notas_estado')) {
            RegistroEstadoEquipo::create([
                'equipo_id' => $equipo->id,
                'estado_desde' => $estadoAnterior,
                'estado_hasta' => $equipo->estado->value,
                'cambiado_por' => Auth::id(),
                'notas' => $request->notas_estado ?? 'Actualización de estado.'
            ]);
        }

        // Lógica de asignación
        if ($equipo->estado->value === 'asignado') {
            if ($request->oficina_id) {
                $this->asignarEquipo($equipo, $request->oficina_id);
            }
        } else {
            if ($estadoAnterior === 'asignado') {
                $asignacion = $equipo->asignacionActiva;
                if ($asignacion) {
                    $asignacion->update(['devuelto_en' => now()]);
                }
            }
        }

        return redirect()->route('admin.equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('admin.equipos.index')->with('success', 'Equipo eliminado (borrado lógico).');
    }

    private function asignarEquipo(Equipo $equipo, $oficinaId)
    {
        $asignacion = $equipo->asignacionActiva;
        
        if ($asignacion) {
            $asignacion->solicitud->update(['oficina_id' => $oficinaId]);
        } else {
            $solicitud = Solicitud::create([
                'usuario_id' => Auth::id(),
                'oficina_id' => $oficinaId,
                'categoria_id' => $equipo->categoria_id,
                'cantidad' => 1,
                'justificacion' => 'Asignación manual por Administrador',
                'estado' => 'aprobada',
                'revisado_por' => Auth::id(),
                'revisado_en' => now(),
            ]);

            Asignacion::create([
                'solicitud_id' => $solicitud->id,
                'equipo_id' => $equipo->id,
                'asignado_por' => Auth::id(),
                'asignado_en' => now(),
            ]);
        }
    }
}