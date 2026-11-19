<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->restrictOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->restrictOnDelete();
            $table->foreignId('asignado_por')->constrained('usuarios')->restrictOnDelete();
            $table->timestamp('asignado_en')->useCurrent();
            $table->timestamp('devuelto_en')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('asignaciones'); }
};