<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->restrictOnDelete();
            $table->foreignId('oficina_id')->constrained('oficinas')->restrictOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
            $table->unsignedSmallInteger('cantidad');
            $table->text('justificacion')->nullable();
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->foreignId('revisado_por')->nullable()->constrained('usuarios')->restrictOnDelete();
            $table->timestamp('revisado_en')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('solicitudes'); }
};