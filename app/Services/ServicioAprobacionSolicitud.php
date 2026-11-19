<?php
namespace App\Services;

use App\Models\Solicitud;
use App\Models\Equipo;
use App\Models\Asignacion;
use App\Models\RegistroEstadoEquipo;
use App\Models\Usuario;
use App\Enums\EstadoSolicitud;
use App\Enums\EstadoEquipo;
use Illuminate\Support\Facades\DB;
use Exception;

class ServicioAprobacionSolicitud
{
    public function aprobar(Solicitud $solicitud, array $equipoIds, Usuario $admin): void
    {
        // Iniciamos la transacción atómica
        DB::transaction(function () use ($solicitud, $equipoIds, $admin) {
            
            // 1. Validar que la cantidad seleccionada coincida con la solicitada
            if (count($equipoIds) !== $solicitud->cantidad) {
                throw new Exception("Debe seleccionar exactamente {$solicitud->cantidad} equipos.");
            }

            // 2. Buscar y bloquear los equipos para evitar condiciones de carrera (Stock real)
            $equipos = Equipo::whereIn('id', $equipoIds)
                ->where('estado', EstadoEquipo::Disponible)
                ->where('categoria_id', $solicitud->categoria_id)
                ->lockForUpdate()
                ->get();

            // 3. Validar que realmente estén disponibles y sean de la categoría
            if ($equipos->count() !== $solicitud->cantidad) {
                throw new Exception("Algunos equipos seleccionados ya no están disponibles o no son de la categoría solicitada.");
            }

            // 4. Actualizar la solicitud
            $solicitud->update([
                'estado' => EstadoSolicitud::Aprobada,
                'revisado_por' => $admin->id,
                'revisado_en' => now(),
            ]);

            // 5. Asignar cada equipo y cambiar su estado
            foreach ($equipos as $equipo) {
                // Crear la asignación
                Asignacion::create([
                    'solicitud_id' => $solicitud->id,
                    'equipo_id' => $equipo->id,
                    'asignado_por' => $admin->id,
                    'asignado_en' => now(),
                ]);

                // Registrar el cambio de estado en el historial
                RegistroEstadoEquipo::create([
                    'equipo_id' => $equipo->id,
                    'estado_desde' => EstadoEquipo::Disponible,
                    'estado_hasta' => EstadoEquipo::Asignado,
                    'cambiado_por' => $admin->id,
                    'notas' => "Asignado por aprobación de la solicitud #{$solicitud->id}"
                ]);

                // Actualizar el estado del equipo
                $equipo->update(['estado' => EstadoEquipo::Asignado]);
            }
        });
    }
}