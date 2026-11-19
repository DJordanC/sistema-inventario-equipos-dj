<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('registros_estado_equipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->enum('estado_desde', ['disponible', 'asignado', 'en_mantenimiento'])->nullable();
            $table->enum('estado_hasta', ['disponible', 'asignado', 'en_mantenimiento']);
            $table->foreignId('cambiado_por')->constrained('usuarios')->restrictOnDelete();
            $table->text('notas')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('registros_estado_equipo'); }
};