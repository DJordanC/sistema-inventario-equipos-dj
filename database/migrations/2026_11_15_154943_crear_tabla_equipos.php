<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_patrimonial', 50)->unique();
            $table->string('marca', 100);
            $table->string('modelo', 100);
            $table->string('cpu', 100)->nullable();
            $table->string('ram', 50)->nullable();
            $table->string('almacenamiento', 50)->nullable();
            $table->enum('estado', ['disponible', 'asignado', 'en_mantenimiento'])->default('disponible');
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes(); // Borrado lógico
        });
    }
    public function down(): void { Schema::dropIfExists('equipos'); }
};