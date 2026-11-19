<?php
namespace App\Models;

use App\Enums\EstadoEquipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipos';
    
    protected $fillable = [
        'codigo_patrimonial', 'marca', 'modelo', 'cpu', 'ram', 
        'almacenamiento', 'estado', 'categoria_id'
    ];
    
    protected $casts = [
        'estado' => EstadoEquipo::class,
    ];

    public function categoria(): BelongsTo {
        return $this->belongsTo(Categoria::class);
    }
    
    public function asignaciones(): HasMany {
        return $this->hasMany(Asignacion::class);
    }

    public function asignacionActiva(): HasOne
    {
        return $this->hasOne(Asignacion::class)->whereNull('devuelto_en');
    }
}