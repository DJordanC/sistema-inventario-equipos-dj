<?php
namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Categoria;
use App\Models\Oficina;
use App\Http\Requests\GuardarSolicitudRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        // Consulta base: Solo las solicitudes del usuario logueado
        $query = Solicitud::with(['categoria', 'oficina'])
            ->where('usuario_id', Auth::id());

        // Filtro por Categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtro por Estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Paginación conservando los filtros
        $solicitudes = $query->latest()->paginate(10)->withQueryString();
        
        // Traemos las categorías para el select del filtro
        $categorias = Categoria::all();

        return view('solicitante.solicitudes.index', compact('solicitudes', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::withCount(['equipos' => function ($query) {
            $query->where('estado', 'disponible');
        }])->get();
        
        $oficinas = Oficina::all();
        
        return view('solicitante.solicitudes.create', compact('categorias', 'oficinas'));
    }

    public function store(GuardarSolicitudRequest $request)
    {
        Solicitud::create([
            'usuario_id'   => Auth::id(),
            'oficina_id'   => $request->oficina_id,
            'categoria_id' => $request->categoria_id,
            'cantidad'     => $request->cantidad,
            'justificacion'=> $request->justificacion,
            'estado'       => 'pendiente',
        ]);

        return redirect()->route('solicitante.solicitudes.index')
                         ->with('success', 'Solicitud enviada exitosamente. Espere la aprobación.');
    }

    public function show(Solicitud $solicitud)
    {
        if ($solicitud->usuario_id !== Auth::id()) abort(403);
        $solicitud->load(['categoria', 'oficina', 'asignaciones.equipo']);
        return view('solicitante.solicitudes.show', compact('solicitud'));
    }

    public function edit(Solicitud $solicitud)
    {
        if ($solicitud->usuario_id !== Auth::id()) abort(403);
        if ($solicitud->estado->value !== 'pendiente') {
            return redirect()->route('solicitante.solicitudes.index')
                             ->with('error', 'Solo puedes renovar solicitudes en estado Pendiente.');
        }

        $categorias = Categoria::withCount(['equipos' => function ($query) {
            $query->where('estado', 'disponible');
        }])->get();
        
        $oficinas = Oficina::all();

        return view('solicitante.solicitudes.edit', compact('solicitud', 'categorias', 'oficinas'));
    }

    public function update(GuardarSolicitudRequest $request, Solicitud $solicitud)
    {
        if ($solicitud->usuario_id !== Auth::id()) abort(403);
        if ($solicitud->estado->value !== 'pendiente') {
            return redirect()->route('solicitante.solicitudes.index')
                             ->with('error', 'Solo puedes renovar solicitudes en estado Pendiente.');
        }

        $solicitud->update([
            'oficina_id'   => $request->oficina_id,
            'categoria_id' => $request->categoria_id,
            'cantidad'     => $request->cantidad,
            'justificacion'=> $request->justificacion,
        ]);

        return redirect()->route('solicitante.solicitudes.index')
                         ->with('success', 'Solicitud renovada y actualizada correctamente.');
    }

    public function destroy(Solicitud $solicitud)
    {
        if ($solicitud->usuario_id !== Auth::id()) abort(403);
        if ($solicitud->estado->value !== 'pendiente') {
            return redirect()->back()->with('error', 'Solo puedes cancelar solicitudes que estén en estado Pendiente.');
        }

        $solicitud->delete();

        return redirect()->route('solicitante.solicitudes.index')
                         ->with('success', 'Solicitud cancelada y eliminada correctamente.');
    }
}