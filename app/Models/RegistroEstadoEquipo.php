<?php
namespace App\Models;

use App\Enums\EstadoEquipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroEstadoEquipo extends Model
{
    use HasFactory;
    
    protected $table = 'registros_estado_equipo';
    
    // Desactivamos las columnas automáticas updated_at
    public $timestamps = false; 
    
    protected $fillable = [
        'equipo_id', 'estado_desde', 'estado_hasta', 'cambiado_por', 'notas', 'created_at'
    ];

    protected $casts = [
        'estado_desde' => EstadoEquipo::class,
        'estado_hasta' => EstadoEquipo::class,
        'created_at'   => 'datetime', // <-- ESTA ES LA LÍNEA MÁGICA QUE FALTABA
    ];

    public function cambiadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cambiado_por');
    }
}