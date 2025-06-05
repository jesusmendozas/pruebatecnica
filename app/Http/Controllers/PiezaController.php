<?php

namespace App\Http\Controllers;

use App\Models\Pieza;
use App\Models\Bloque;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PiezaController extends Controller
{
    public function index()
    {
        try {
            $piezas = Pieza::with(['bloque.proyecto'])->get();
            $bloques = Bloque::with('proyecto')->get();
            return Inertia::render('Piezas', [
                'piezas' => $piezas,
                'bloques' => $bloques
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las piezas: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'IdPieza' => 'required|string|unique:piezas,IdPieza',
                'pieza' => 'required|string',
                'peso_teorico' => 'required|numeric|min:0',
                'IDBloque' => 'required|exists:bloques,IDBloque',
            ]);

            $pieza = Pieza::create([
                ...$validated,
                'estado' => 'Pendiente',
                'fecha_registro' => now(),
                'registrado_por' => Auth::user()->usuario,
            ]);

            return redirect()->back()->with('success', 'Pieza creada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear la pieza: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Pieza $pieza)
    {
        try {
            $validated = $request->validate([
                'pieza' => 'required|string',
                'peso_teorico' => 'required|numeric|min:0',
                'peso_real' => 'nullable|numeric|min:0',
                'estado' => 'required|in:Pendiente,Fabricada',
                'IDBloque' => 'required|exists:bloques,IDBloque',
            ]);

            $pieza->update($validated);
            return redirect()->back()->with('success', 'Pieza actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la pieza: ' . $e->getMessage());
        }
    }

    public function destroy(Pieza $pieza)
    {
        try {
            $pieza->delete();
            return redirect()->back()->with('success', 'Pieza eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la pieza: ' . $e->getMessage());
        }
    }
}
