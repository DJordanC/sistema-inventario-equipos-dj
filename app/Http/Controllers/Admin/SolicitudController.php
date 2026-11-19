<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Equipo;
use App\Http\Requests\AprobarSolicitudRequest;
use App\Services\ServicioAprobacionSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class SolicitudController extends Controller
{
    public function index()
    {
        // Eager Loading para evitar N+1
        $solicitudes = Solicitud::with(['usuario', 'oficina', 'categoria'])
            ->latest()->paginate(10);
        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    public function show(Solicitud $solicitud)
    {
        $solicitud->load(['usuario', 'oficina', 'categoria']);
        
        // Equipos disponibles de la categoría solicitada para el formulario de aprobación
        $equiposDisponibles = Equipo::where('categoria_id', $solicitud->categoria_id)
            ->where('estado', 'disponible')
            ->get();

        return view('admin.solicitudes.show', compact('solicitud', 'equiposDisponibles'));
    }

    public function aprobar(AprobarSolicitudRequest $request, Solicitud $solicitud, ServicioAprobacionSolicitud $servicio)
    {
        try {
            $servicio->aprobar($solicitud, $request->equipo_ids, Auth::user());
            return redirect()->route('admin.solicitudes.index')
                             ->with('success', 'Solicitud aprobada y equipos asignados correctamente.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rechazar(Request $request, Solicitud $solicitud)
    {
        $request->validate(['motivo_rechazo' => 'required|string|max:500']);

        $solicitud->update([
            'estado' => 'rechazada',
            'revisado_por' => Auth::id(),
            'revisado_en' => now(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        return redirect()->route('admin.solicitudes.index')
                         ->with('success', 'Solicitud rechazada.');
    }
}