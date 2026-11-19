<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;
    protected $table = 'asignaciones';
    protected $fillable = ['solicitud_id', 'equipo_id', 'asignado_por', 'asignado_en', 'devuelto_en'];
    
    protected $casts = [
        'asignado_en' => 'datetime',
        'devuelto_en' => 'datetime',
    ];

    public function solicitud() { return $this->belongsTo(Solicitud::class); }
    public function equipo() { return $this->belongsTo(Equipo::class); }
}