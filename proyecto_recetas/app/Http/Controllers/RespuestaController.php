<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Respuesta;

class RespuestaController extends Controller {

    public function store(Request $request) {
        $request->validate([
            'id_comentario' => 'required|exists:comentarios,id',
            'contenido' => 'required|string|max:1000',
            'id_receta' => 'required|exists:recetas,id',
        ]);

        Respuesta::create([
            'id_comentario' => $request->id_comentario,
            'id_user' => Auth::id(),
            'contenido' => $request->contenido,
            'id_receta' => $request->id_receta,
            'id_user_respondido' => $request->id_user_respondido,
            'f_creacion' => now(),
        ]);

        return back()->with('success', 'Respuesta publicada correctamente.');
    }

}