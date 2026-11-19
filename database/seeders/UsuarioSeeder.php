<?php
namespace Database\Seeders;
use App\Enums\RolUsuario;
use App\Models\Oficina;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder {
    public function run(): void { 
        $soporte = Oficina::where('codigo', 'SOP')->first();
        $rrhh = Oficina::where('codigo', 'RRHH')->first();

        // 1. Administrador
        Usuario::create([
            'nombre' => 'Admin Soporte',
            'email' => 'admin@sie.test',
            'password' => Hash::make('password'),
            'rol' => RolUsuario::Administrador,
            'oficina_id' => $soporte->id,
        ]);

        // 2. Solicitante
        Usuario::create([
            'nombre' => 'Jefe RRHH',
            'email' => 'jefe@sie.test',
            'password' => Hash::make('password'),
            'rol' => RolUsuario::Solicitante,
            'oficina_id' => $rrhh->id,
        ]);
    }
}