<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use Illuminate\Database\Seeder;

class ProyectoSeeder extends Seeder
{
    public function run(): void
    {
        $proyectos = [
            [
                'IDproyecto' => 'PROJ1',
                'nombre' => 'Proyecto1'
            ],
            [
                'IDproyecto' => 'PROJ2',
                'nombre' => 'Proyecto2'
            ],
            [
                'IDproyecto' => 'PROJ3',
                'nombre' => 'Proyecto3'
            ],
            [
                'IDproyecto' => 'PROJ4',
                'nombre' => 'Proyecto4'
            ]
        ];

        foreach ($proyectos as $proyecto) {
            Proyecto::create($proyecto);
        }
    }
}
