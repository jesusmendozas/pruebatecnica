<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pieza;
use App\Models\Bloque;
use Illuminate\Support\Str;

class PiezaSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener todos los bloques
        $bloques = Bloque::all();

        // Para cada bloque, crear 3 piezas
        foreach ($bloques as $bloque) {
            for ($i = 1; $i <= 3; $i++) {
                Pieza::create([
                    'IdPieza' => 'PZ' . Str::random(8),
                    'pieza' => "Pieza {$i}",
                    'peso_teorico' => rand(100, 1000) / 10,
                    'peso_real' => null,
                    'estado' => 'Pendiente',
                    'IDBloque' => $bloque->IDBloque,
                    'fecha_registro' => now(),
                    'registrado_por' => null
                ]);
            }
        }
    }
}
