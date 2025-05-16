<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receta;

class RecetaController extends Controller {

    public function mostrarComentario($id) {
        $receta = Receta::with(['comentarios.user'])->findOrFail($id);
        return view('recetas.detalle', compact('receta'));
    }
    
    public function listarRecetas(){
        $recetas = Receta::paginate(3);
        return view('recetas.lista', compact('recetas'));
    }

    public function mostrarRecetaIndividual($id){
        $receta = Receta::with([
            'comentarios.user',
            'comentarios.respuestas.user'
        ])->findOrFail($id);

        return view('recetas.detalle', compact('receta'));
    }

    // Mostrar el formulario
    public function formularioReceta(){
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
            'autor' => 1, // temporalmente fijo; si tienes auth, usa auth()->user()->id()
            'estado' => 0
        ]);

        return redirect()->route('recetas.lista')->with('success', 'Receta creada exitosamente.');
    }

    // Mostrar formulario con datos actuales
    public function editarReceta($id) {
        $receta = Receta::findOrFail($id);
        return view('recetas.edicionReceta', compact('receta'));
    }

    // Actualizar receta en BD
    public function actualizarReceta(Request $request, $id) {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'ingredientes' => 'required|string',
            'procedimiento' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $receta = Receta::findOrFail($id);

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('recetas', 'public');
            $receta->imagen = $rutaImagen;
        }

        $receta->update([
            'titulo' => $request->input('titulo'),
            'tipo' => $request->input('tipo'),
            'ingredientes' => $request->input('ingredientes'),
            'procedimiento' => $request->input('procedimiento'),
        ]);

        return redirect()->route('recetas.lista')->with('success', 'Receta actualizada correctamente.');
    }

    // Eliminar receta
    public function eliminarReceta($id) {
        $receta = Receta::findOrFail($id);
        $receta->delete();

        return redirect()->route('recetas.lista')->with('success', 'Receta eliminada.');
    }

}
