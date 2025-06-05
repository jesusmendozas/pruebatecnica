<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsuarioController extends Controller
{
    public function index()
    {
        try {
            $usuarios = User::all();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'usuarios' => $usuarios
                ]);
            }

            return Inertia::render('Usuarios', [
                'usuarios' => $usuarios
            ]);
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar los usuarios: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al cargar los usuarios: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:users',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->name . '@example.com',
                'password' => Hash::make($request->password),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado exitosamente',
                    'user' => $user
                ]);
            }

            return redirect()->route('usuarios')->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el usuario: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255|unique:users,name,' . $id,
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->name = $request->name;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario actualizado exitosamente',
                    'user' => $user
                ]);
            }

            return redirect()->route('usuarios')->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if (auth()->id() == $id) {
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No puedes eliminar tu propio usuario'
                    ], 403);
                }
                return redirect()->back()->with('error', 'No puedes eliminar tu propio usuario');
            }

            $user = User::findOrFail($id);
            $user->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado exitosamente'
                ]);
            }

            return redirect()->route('usuarios')->with('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}
