<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Pieza;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        try {
            $piezasPendientes = Proyecto::with(['bloques.piezas'])
            ->get()
            ->map(function($proyecto) {
                return [
                    'nombre_proyecto' => $proyecto->nombre,
                    'IDproyecto' => $proyecto->IDproyecto,
                    'total_pendientes' => $proyecto->bloques->flatMap->piezas->count(),
                    'bloques' => $proyecto->bloques->map(function($bloque) {
                        return [
                            'nombre_bloque' => $bloque->nombre_bloque,
                            'IDBloque' => $bloque->IDBloque,
                            'piezas' => $bloque->piezas->map(function($pieza) {
                                return [
                                    'nombre_pieza' => $pieza->pieza,
                                    'IDPieza' => $pieza->IdPieza,
                                    'peso_teorico' => $pieza->peso_teorico,
                                    'peso_real' => $pieza->peso_real,
                                    'estado' => $pieza->estado,
                                    'registrado_por' => $pieza->registrado_por,
                                ];
                            })
                        ];
                    })
                ];
            });

            $estadosPorProyecto = Proyecto::with(['bloques.piezas'])
                ->get()
                ->map(function($proyecto) {
                    $piezas = $proyecto->bloques->flatMap->piezas;
                    return [
                        'nombre_proyecto' => $proyecto->nombre,
                        'IDproyecto' => $proyecto->IDproyecto,
                        'pendientes' => $piezas->where('estado', 'Pendiente')->count(),
                        'fabricadas' => $piezas->where('estado', 'Fabricada')->count()
                    ];
                });

            return Inertia::render('Reportes', [
                'piezasPendientes' => $piezasPendientes,
                'estadosPorProyecto' => $estadosPorProyecto
            ]);
        } catch (Exception $e) {
            return back()->with('error', 'Error al generar los reportes: ' . $e->getMessage());
        }
    }
}
