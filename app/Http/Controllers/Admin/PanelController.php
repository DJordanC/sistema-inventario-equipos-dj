<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Solicitud;
use App\Models\Categoria;
use App\Enums\EstadoEquipo;
use App\Enums\EstadoSolicitud;

class PanelController extends Controller
{
    public function index()
    {
        // Tarjetas
        $equiposDisponibles = Equipo::where('estado', EstadoEquipo::Disponible)->count();
        $equiposMantenimiento = Equipo::where('estado', EstadoEquipo::EnMantenimiento)->count();
        $equiposAsignados = Equipo::where('estado', EstadoEquipo::Asignado)->count();
        $solicitudesPendientes = Solicitud::where('estado', EstadoSolicitud::Pendiente)->count();

        // Datos para gráficos (Cuenta los equipos por categoría, excluyendo los eliminados lógicamente)
        $categorias = Categoria::withCount('equipos')->get();
        
        // Últimas solicitudes
        $ultimasSolicitudes = Solicitud::with(['usuario', 'categoria'])
                                ->latest()
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact(
            'equiposDisponibles', 
            'equiposMantenimiento', 
            'equiposAsignados',
            'solicitudesPendientes',
            'categorias',
            'ultimasSolicitudes'
        ));
    }
}