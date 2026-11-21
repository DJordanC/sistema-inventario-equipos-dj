<?php
namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Oficina;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    // 1. Método para ver el reporte en pantalla
    public function asignacionesPorOficina(Request $request)
    {
        $oficinas = Oficina::all();

        // Ahora consultamos desde SOLICITUD, no desde Asignacion
        $query = Solicitud::with([
            'usuario', 
            'oficina', 
            'categoria', 
            'asignaciones.equipo' // Eager Loading
        ]);

        // Aplicar filtros
        if ($request->filled('oficina_id')) {
            $query->where('oficina_id', $request->oficina_id);
        }
        if ($request->filled('estado_solicitud')) {
            $query->where('estado', $request->estado_solicitud);
        }
        if ($request->filled('estado_equipo')) {
            $query->whereHas('asignaciones.equipo', function ($q) use ($request) {
                $q->where('estado', $request->estado_equipo);
            });
        }

        $solicitudes = $query->latest()->paginate(15)->withQueryString();

        return view('reportes.asignaciones', compact('solicitudes', 'oficinas'));
    }

    // 2. Método para Exportar a CSV
    public function exportarCSV(Request $request)
    {
        $query = Solicitud::with(['usuario', 'oficina', 'categoria', 'asignaciones.equipo']);

        if ($request->filled('oficina_id')) {
            $query->where('oficina_id', $request->oficina_id);
        }
        if ($request->filled('estado_solicitud')) {
            $query->where('estado', $request->estado_solicitud);
        }
        if ($request->filled('estado_equipo')) {
            $query->whereHas('asignaciones.equipo', function ($q) use ($request) {
                $q->where('estado', $request->estado_equipo);
            });
        }

        $solicitudes = $query->latest()->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=reporte_solicitudes_" . date('Y-m-d_H-i') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($solicitudes) {
            $file = fopen('php://output', 'w');
            
            // Cabeceras del CSV
            fputcsv($file, ['ID Solicitud', 'Fecha', 'Oficina', 'Solicitante', 'Categoria', 'Cantidad', 'Estado Solicitud', 'Equipos Asignados']);
            
            foreach ($solicitudes as $sol) {
                // Si tiene equipos asignados, los concatenamos en un solo texto
                $equiposAsignados = $sol->asignaciones->map(function($asig) {
                    return $asig->equipo->codigo_patrimonial . ' (' . $asig->equipo->estado->value . ')';
                })->implode(' | ');

                fputcsv($file, [
                    $sol->id,
                    $sol->created_at->format('d/m/Y h:i A'),
                    $sol->oficina->nombre,
                    $sol->usuario->nombre,
                    $sol->categoria->nombre,
                    $sol->cantidad,
                    strtoupper($sol->estado->value),
                    $equiposAsignados ?: 'Ninguno'
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}