<?php

namespace App\Http\Controllers;

use App\Models\Bloque;
use App\Models\Pieza;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class FormularioController extends Controller
{
    public function index()
    {

        $proyectos = Proyecto::all();
        $bloques = Bloque::all();
        $piezas = Pieza::all();
        $usuario = auth()->user();

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
            $request->validate([
                'id_pieza' => 'required|exists:piezas,IdPieza',
                'peso_real' => 'required|numeric|min:0',
            ]);

            $pieza = Pieza::findOrFail($request->id_pieza);

            $pieza->update([
                'peso_real' => $request->peso_real,
                'registrado_por' => Auth::user()->usuario,
                'fecha_registro' => now()
            ]);

            $pieza->refresh();

            return redirect()->back()->with('success', 'Pieza registrada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al registrar pieza: ' . $e->getMessage());
            return back()->with('error', 'Error al registrar la pieza: ' . $e->getMessage());
        }
    }
}
