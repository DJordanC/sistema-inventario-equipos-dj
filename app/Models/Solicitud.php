<?php
namespace App\Models;

use App\Enums\EstadoSolicitud;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitud extends Model
{
    use HasFactory;
    
    protected $table = 'solicitudes';
    
    protected $fillable = [
        'usuario_id', 'oficina_id', 'categoria_id', 'cantidad', 
        'justificacion', 'estado', 'revisado_por', 'revisado_en', 'motivo_rechazo'
    ];
    
    protected $casts = [
        'estado' => EstadoSolicitud::class,
        'revisado_en' => 'datetime',
    ];

    public function usuario(): BelongsTo 
    { 
        return $this->belongsTo(Usuario::class, 'usuario_id'); 
    }
    
    public function oficina(): BelongsTo 
    { 
        return $this->belongsTo(Oficina::class); 
    }
    
    public function categoria(): BelongsTo 
    { 
        return $this->belongsTo(Categoria::class); 
    }

    public function asignaciones(): HasMany 
    { 
        return $this->hasMany(Asignacion::class); 
    }
}