<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->query('tipo');

        if ($tipo === 'usuarios') {
            $usuarios = User::paginate(5);
            return view('admin.admin', compact('usuarios'));
        } else {
            $recetas = Receta::with('autor')->paginate(5);
            return view('admin.admin', compact('recetas'));
        }
    }

}
