<?php

namespace Database\Seeders;

use App\Models\Bloque;
use Illuminate\Database\Seeder;

class BloqueSeeder extends Seeder
{
    public function run(): void
    {
        $bloques = [
            [
                'IDBloque' => 'IDBLOQUE1',
                'nombre_bloque' => 'Bloque 1',
                'IDproyecto' => 'PROJ1'
            ],
            [
                'IDBloque' => 'IDBLOQUE2',
                'nombre_bloque' => 'Bloque 2',
                'IDproyecto' => 'PROJ2'
            ],
            [
                'IDBloque' => 'IDBLOQUE3',
                'nombre_bloque' => 'Bloque 3',
                'IDproyecto' => 'PROJ1'
            ],
            [
                'IDBloque' => 'IDBLOQUE4',
                'nombre_bloque' => 'Bloque 4',
                'IDproyecto' => 'PROJ3'
            ],
            [
                'IDBloque' => 'IDBLOQUE5',
                'nombre_bloque' => 'Bloque 5',
                'IDproyecto' => 'PROJ1'
            ],
            [
                'IDBloque' => 'IDBLOQUE6',
                'nombre_bloque' => 'Bloque 6',
                'IDproyecto' => 'PROJ4'
            ]
        ];

        foreach ($bloques as $bloque) {
            Bloque::create($bloque);
        }
    }
}
