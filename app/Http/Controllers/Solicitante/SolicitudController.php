<?php
namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Categoria;
use App\Models\Oficina;
use App\Http\Requests\GuardarSolicitudRequest;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    public function index()
    {
        $solicitudes = Solicitud::with(['categoria', 'oficina'])
            ->where('usuario_id', Auth::id())
            ->latest()->paginate(10);
        return view('solicitante.solicitudes.index', compact('solicitudes'));
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
        // Seguridad: Validar que la solicitud le pertenezca al usuario logueado
        if ($solicitud->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta solicitud.');
        }

        // Cargamos la relación de asignaciones y equipos por si ya fue aprobada
        $solicitud->load(['categoria', 'oficina', 'asignaciones.equipo']);
        
        return view('solicitante.solicitudes.show', compact('solicitud'));
    }
}