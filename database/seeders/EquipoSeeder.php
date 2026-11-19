<?php
namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Equipo;
use Illuminate\Database\Seeder;

class EquipoSeeder extends Seeder {
    public function run(): void {
        $categorias = Categoria::all();

        // 1. Diccionario de prefijos y marcas por categoría
        $configuracionCategorias = [
            'Laptops' => [
                'prefijo' => 'LAP',
                'marcas' => ['Dell', 'Lenovo', 'Asus', 'HP']
            ],
            'Monitores' => [
                'prefijo' => 'MON',
                'marcas' => ['LG', 'Samsung', 'Dell']
            ],
            'Impresoras' => [
                'prefijo' => 'IMP',
                'marcas' => ['Epson', 'HP', 'Canon', 'Brother']
            ],
            'Desktops' => [
                'prefijo' => 'DES',
                'marcas' => ['HP', 'Dell', 'Lenovo']
            ],
        ];

        foreach ($categorias as $categoria) {
            // Obtenemos la configuración según el nombre de la categoría
            $config = $configuracionCategorias[$categoria->nombre] ?? [
                'prefijo' => 'EQ',
                'marcas' => ['Genérico']
            ];
            
            $esImpresora = $categoria->nombre === 'Impresoras';
            
            // Creamos 5 equipos por cada categoría
            for ($i = 1; $i <= 5; $i++) {
                // Generamos el código secuencial: LAP-001, LAP-002, etc.
                $codigoSecuencial = str_pad($i, 3, '0', STR_PAD_LEFT);
                
                Equipo::create([
                    'codigo_patrimonial' => $config['prefijo'] . '-' . $codigoSecuencial,
                    'marca' => fake()->randomElement($config['marcas']),
                    'modelo' => 'MOD-' . fake()->numberBetween(100, 999),
                    // Si es impresora, no tiene CPU, RAM ni Almacenamiento
                    'cpu' => $esImpresora ? null : fake()->randomElement(['Intel i5', 'Intel i7', 'AMD Ryzen 5']),
                    'ram' => $esImpresora ? null : fake()->randomElement(['8GB', '16GB', '32GB']),
                    'almacenamiento' => $esImpresora ? null : fake()->randomElement(['256GB SSD', '512GB SSD', '1TB HDD']),
                    'estado' => 'disponible',
                    'categoria_id' => $categoria->id,
                ]);
            }
        }
    }
}