<?php

namespace App\Http\Controllers;

use App\Models\Bloque;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class BloqueController extends Controller
{
    public function index()
    {
        try {
            $bloques = Bloque::with(['proyecto', 'piezas'])->get();
            $proyectos = Proyecto::all();

            return Inertia::render('Bloques', [
                'bloques' => $bloques,
                'proyectos' => $proyectos
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los bloques: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_bloque' => 'required|string|max:255',
                'IDBloque' => 'required|string|unique:bloques,IDBloque',
                'IDproyecto' => 'required|exists:proyectos,IDproyecto'
            ]);

            $bloque = Bloque::create($request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bloque creado exitosamente',
                    'bloque' => $bloque
                ]);
            }

            return redirect()->back()->with('success', 'Bloque creado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al crear bloque: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el bloque: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al crear el bloque: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre_bloque' => 'required|string|max:255',
                'IDBloque' => 'required|string|unique:bloques,IDBloque,' . $id . ',IDBloque',
                'IDproyecto' => 'required|exists:proyectos,IDproyecto'
            ]);

            $bloque = Bloque::where('IDBloque', $id)->firstOrFail();
            $bloque->nombre_bloque = $request->nombre_bloque;
            $bloque->IDproyecto = $request->IDproyecto;
            $bloque->save();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bloque actualizado exitosamente',
                    'bloque' => $bloque
                ]);
            }

            return redirect()->back()->with('success', 'Bloque actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar bloque: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el bloque: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al actualizar el bloque: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $bloque = Bloque::where('IDBloque', $id)->firstOrFail();

            if ($bloque->piezas()->exists()) {
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar el bloque porque tiene piezas asociadas'
                    ], 400);
                }
                return back()->with('error', 'No se puede eliminar el bloque porque tiene piezas asociadas');
            }

            $bloque->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bloque eliminado exitosamente'
                ]);
            }

            return redirect()->back()->with('success', 'Bloque eliminado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar bloque: ' . $e->getMessage());
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el bloque: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al eliminar el bloque: ' . $e->getMessage());
        }
    }
}
