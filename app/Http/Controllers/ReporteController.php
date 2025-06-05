<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Pieza;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        try {
            // Reporte de piezas pendientes por proyecto
            $piezasPendientes = Proyecto::with(['bloques.piezas' => function($query) {
                $query->where('estado', 'Pendiente');
            }])
            ->get()
            ->map(function($proyecto) {
                return [
                    'proyecto' => $proyecto->nombre,
                    'IDproyecto' => $proyecto->IDproyecto,
                    'piezas_pendientes' => $proyecto->bloques->flatMap->piezas->count(),
                    'bloques' => $proyecto->bloques->map(function($bloque) {
                        return [
                            'bloque' => $bloque->nombre_bloque,
                            'piezas_pendientes' => $bloque->piezas->count(),
                            'piezas' => $bloque->piezas->map(function($pieza) {
                                return [
                                    'pieza' => $pieza->pieza,
                                    'peso_teorico' => $pieza->peso_teorico,
                                    'estado' => $pieza->estado
                                ];
                            })
                        ];
                    })
                ];
            });

            // Reporte gráfico de piezas por estado
            $estadosPorProyecto = Proyecto::with(['bloques.piezas'])
                ->get()
                ->map(function($proyecto) {
                    $piezas = $proyecto->bloques->flatMap->piezas;
                    return [
                        'proyecto' => $proyecto->nombre,
                        'IDproyecto' => $proyecto->IDproyecto,
                        'pendientes' => $piezas->where('estado', 'Pendiente')->count(),
                        'fabricadas' => $piezas->where('estado', 'Fabricada')->count()
                    ];
                });

            return Inertia::render('Reportes', [
                'piezasPendientes' => $piezasPendientes,
                'estadosPorProyecto' => $estadosPorProyecto
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar los reportes: ' . $e->getMessage());
        }
    }
}
