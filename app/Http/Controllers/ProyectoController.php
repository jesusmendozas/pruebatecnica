<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ProyectoController extends Controller
{
    public function index()
    {
        try {
            $proyectos = Proyecto::with(['bloques.piezas' => function($query) {
                $query->where('estado', 'Pendiente');
            }])->get();

            return Inertia::render('Proyectos', [
                'proyectos' => $proyectos
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los proyectos: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'IDproyecto' => 'required|string|unique:proyectos,IDproyecto'
            ]);

            $proyecto = Proyecto::create($request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proyecto creado exitosamente',
                    'proyecto' => $proyecto
                ]);
            }

            return redirect()->back()->with('success', 'Proyecto creado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al crear proyecto: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el proyecto: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al crear el proyecto: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
            ]);

            $proyecto = Proyecto::where('IDproyecto', $id)->firstOrFail();
            $proyecto->nombre = $request->nombre;
            $proyecto->save();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proyecto actualizado exitosamente',
                    'proyecto' => $proyecto
                ]);
            }

            return redirect()->back()->with('success', 'Proyecto actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar proyecto: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el proyecto: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $proyecto = Proyecto::where('IDproyecto', $id)->firstOrFail();

            // Verificar si el proyecto tiene bloques o piezas asociadas
            if ($proyecto->bloques()->exists()) {
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar el proyecto porque tiene bloques asociados'
                    ], 400);
                }
                return back()->with('error', 'No se puede eliminar el proyecto porque tiene bloques asociados');
            }

            $proyecto->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proyecto eliminado exitosamente'
                ]);
            }

            return redirect()->back()->with('success', 'Proyecto eliminado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar proyecto: ' . $e->getMessage());
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el proyecto: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al eliminar el proyecto: ' . $e->getMessage());
        }
    }
}
