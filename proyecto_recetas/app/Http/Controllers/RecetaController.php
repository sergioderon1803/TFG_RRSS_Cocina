<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receta;

class RecetaController extends Controller {
    
    public function listarRecetas(){
        $recetas = Receta::paginate(2);
        return view('recetas.lista', compact('recetas'));
    }

    public function mostrarRecetaIndividual($id){
        $receta = Receta::findOrFail($id);
        return view('recetas.detalle', compact('receta'));
    }

    // Mostrar el formulario
    public function crearReceta(){
        return view('recetas.creacionReceta');
    }

    // Guardar la receta en la base de datos
    public function guardarReceta(Request $request){
        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'ingredientes' => 'required|string',
            'procedimiento' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('recetas', 'public');
        }

        Receta::create([
            'titulo' => $request->input('titulo'),
            'tipo' => $request->input('tipo'),
            'ingredientes' => $request->input('ingredientes'),
            'procedimiento' => $request->input('procedimiento'),
            'imagen' => $rutaImagen,
            'autor' => 'sergio@email.com', // temporalmente fijo; si tienes auth, usa auth()->user()->email
        ]);

        return redirect()->route('recetas.lista')->with('success', 'Receta creada exitosamente.');
    }
}
