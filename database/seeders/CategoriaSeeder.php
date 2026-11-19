<?php
namespace Database\Seeders;
use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder {
    public function run(): void {
        Categoria::create(['nombre' => 'Laptops', 'descripcion' => 'Computadoras portátiles']);
        Categoria::create(['nombre' => 'Monitores', 'descripcion' => 'Pantallas de escritorio']);
        Categoria::create(['nombre' => 'Impresoras', 'descripcion' => 'Impresoras láser y multifuncionales']);
        Categoria::create(['nombre' => 'Desktops', 'descripcion' => 'Equipos de escritorio']);
    }
}