<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroEstadoEquipo extends Model
{
    use HasFactory;
    protected $table = 'registros_estado_equipo';
    public $timestamps = false; // Solo tiene created_at
    protected $fillable = ['equipo_id', 'estado_desde', 'estado_hasta', 'cambiado_por', 'notas'];
}