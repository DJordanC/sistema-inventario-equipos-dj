<?php
namespace Database\Seeders;
use App\Models\Oficina;
use Illuminate\Database\Seeder;

class OficinaSeeder extends Seeder {
    public function run(): void {
        Oficina::create(['nombre' => 'Soporte Técnico', 'codigo' => 'SOP']);
        Oficina::create(['nombre' => 'Recursos Humanos', 'codigo' => 'RRHH']);
        Oficina::create(['nombre' => 'Administración', 'codigo' => 'ADM']);
    }
}