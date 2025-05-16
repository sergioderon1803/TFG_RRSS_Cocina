<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller {

    public function store(Request $request) {
        $request->validate([
            'contenido' => 'required|string|max:1000',
            'id_receta' => 'required|exists:recetas,id',
        ]);

        Comentario::create([
            'id_user' => Auth::id(),
            'id_receta' => $request->id_receta,
            'contenido' => $request->contenido,
            'f_creacion' => now(),
        ]);

        return back()->with('success', 'Comentario publicado correctamente.');
    }
}
