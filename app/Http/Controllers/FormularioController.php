<?php

namespace App\Http\Controllers;

use App\Models\Bloque;
use App\Models\Pieza;
use App\Models\Proyecto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class FormularioController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::all();
        $bloques = Bloque::with('proyecto')->get();
        $piezas = Pieza::all();
        $usuario = Auth::user();

        return Inertia::render('Formulario', [
            'proyectos' => $proyectos,
            'bloques' => $bloques,
            'piezas' => $piezas,
            'usuario' => $usuario
        ]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Iniciando registro de pieza', [
                'request_data' => $request->all(),
                'user' => Auth::user()->usuario
            ]);

            $request->validate([
                'id_pieza' => 'required|string|exists:piezas,IdPieza',
                'peso_real' => 'required|numeric|min:0',
            ]);

            Log::info('Validación exitosa, buscando pieza', ['id_pieza' => $request->id_pieza]);

            $pieza = Pieza::findOrFail($request->id_pieza);

            Log::info('Pieza encontrada', [
                'pieza' => $pieza->toArray(),
                'nuevo_peso' => $request->peso_real
            ]);

            $updateData = [
                'peso_real' => $request->peso_real,
                'registrado_por' => Auth::user()->usuario,
                'fecha_registro' => now()->format('Y-m-d')
            ];

            Log::info('Intentando actualizar pieza con datos', $updateData);

            $updated = $pieza->update($updateData);

            if (!$updated) {
                throw new \Exception('No se pudo actualizar la pieza');
            }

            Log::info('Pieza actualizada exitosamente', [
                'pieza_id' => $pieza->IdPieza,
                'nuevo_peso' => $request->peso_real
            ]);

            return back()->with('success', 'Pieza registrada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al registrar pieza', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user' => Auth::user()->usuario ?? 'no autenticado'
            ]);
            return back()->with('error', 'Error al registrar la pieza: ' . $e->getMessage());
        }
    }
}
