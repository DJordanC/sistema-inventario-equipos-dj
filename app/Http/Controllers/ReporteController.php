<?php
namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Oficina;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function asignacionesPorOficina(Request $request)
    {
        // Obtenemos todas las oficinas para el select del filtro
        $oficinas = Oficina::all();

        // Construimos la consulta con Eager Loading
        $query = Asignacion::with([
            'equipo.categoria', 
            'solicitud.oficina', 
            'solicitud.usuario'
        ]);

        // Si el usuario seleccionó una oficina, filtramos
        if ($request->filled('oficina_id')) {
            $query->whereHas('solicitud', function ($q) use ($request) {
                $q->where('oficina_id', $request->oficina_id);
            });
        }

        $asignaciones = $query->latest('asignado_en')->paginate(15);

        return view('reportes.asignaciones', compact('asignaciones', 'oficinas'));
    }
}