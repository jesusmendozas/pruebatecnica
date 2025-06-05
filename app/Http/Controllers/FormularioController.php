<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FormularioController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::all();

        return Inertia::render('Formulario', [ 
            'proyectos' => $proyectos,
            'usuario' =>Auth::user()->usuario,
        ]);
    }
}
