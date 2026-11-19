<?php
namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Equipo;
use App\Enums\EstadoSolicitud;
use App\Enums\EstadoEquipo;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
    public function index()
    {
        $usuarioId = Auth::id();

        // 1. Tarjetas Estadísticas
        $equiposDisponibles = Equipo::where('estado', EstadoEquipo::Disponible)->count();
        $misSolicitudes = Solicitud::where('usuario_id', $usuarioId)->count();
        $misPendientes = Solicitud::where('usuario_id', $usuarioId)->where('estado', EstadoSolicitud::Pendiente)->count();
        $misAprobadas = Solicitud::where('usuario_id', $usuarioId)->where('estado', EstadoSolicitud::Aprobada)->count();

        // 2. Datos para el Gráfico (Estado de mis solicitudes)
        $estadosData = [
            Solicitud::where('usuario_id', $usuarioId)->where('estado', EstadoSolicitud::Pendiente)->count(),
            Solicitud::where('usuario_id', $usuarioId)->where('estado', EstadoSolicitud::Aprobada)->count(),
            Solicitud::where('usuario_id', $usuarioId)->where('estado', EstadoSolicitud::Rechazada)->count(),
        ];

        // 3. Últimas solicitudes creadas por este usuario
        $ultimasSolicitudes = Solicitud::with('categoria')
                                ->where('usuario_id', $usuarioId)
                                ->latest()
                                ->take(5)
                                ->get();

        return view('solicitante.dashboard', compact(
            'equiposDisponibles', 
            'misSolicitudes', 
            'misPendientes', 
            'misAprobadas', 
            'estadosData', 
            'ultimasSolicitudes'
        ));
    }
}