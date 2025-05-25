<?php

namespace App\Http\Controllers;

use App\Models\GuardarReceta;
use App\Models\GustarReceta;
use App\Models\Comentario;
use App\Models\Perfil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Receta;
use Symfony\Component\HttpKernel\Profiler\Profile;

class RecetaController extends Controller {

    public function guardarRecetaUsuario($id)
    {
        $userId = Auth::id();

        // Verificar si ya está guardada
        $yaExiste = GuardarReceta::where('id_receta', $id)->where('id_user', $userId)->exists();

        if (!$yaExiste) {
            GuardarReceta::create([
                'id_receta' => $id,
                'id_user' => $userId,
                'f_guardar' => now(),
            ]);
        }

        return back()->with('success', 'Receta guardada.');
    }

    public function eliminarGuardado($id)
    {
        $userId = Auth::id();

        GuardarReceta::where('id_receta', $id)->where('id_user', $userId)->delete();

        return back()->with('success', 'Guardado eliminado.');
    }

    public function gustarRecetaUsuario($id)
    {
        $userId = Auth::id();

        // Verificar si ya le dio me gusta
        $yaExiste = GustarReceta::where('id_receta', $id)->where('id_user', $userId)->exists();

        if (!$yaExiste) {
            GustarReceta::create([
                'id_receta' => $id,
                'id_user' => $userId,
                'f_gustar' => now(),
            ]);
        }

        return back()->with('success', 'Te ha gustado la receta.');
    }

    public function eliminarMeGusta($id)
    {
        $userId = Auth::id();

        GustarReceta::where('id_receta', $id)->where('id_user', $userId)->delete();

        return back()->with('success', 'Ya no te gusta esta receta.');
    }

    public function mostrarComentario($id) {
        $receta = Receta::with(['comentarios.user'])->findOrFail($id);
        return view('recetas.detalle', compact('receta'));
    }
    
    public function listarRecetas(){
        $recetas = Receta::paginate(6);
        return view('recetas.lista', compact('recetas'));
    }

    public function mostrarRecetaIndividual($id)
    {
        $receta = Receta::with([
            'autor.perfil',
            'comentarios.user',
            'comentarios.respuestas.user'
        ])->findOrFail($id);

        $guardada = false;
        $gustada = false;

        if (Auth::check()) {
            $userId = Auth::id();
            $guardada = GuardarReceta::where('id_receta', $id)
                                    ->where('id_user', $userId)
                                    ->exists();

            $gustada = GustarReceta::where('id_receta', $id)
                                ->where('id_user', $userId)
                                ->exists();
        }


        return view('recetas.detalle', compact('receta', 'guardada', 'gustada'));
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
            'autor_receta' => Auth::id(),
            'estado' => 0,
            'created_at' => now()
        ]);

        return redirect()->route('recetas.lista')->with('success', 'Receta creada exitosamente.');
    }

    // Mostrar formulario con datos actuales
    public function editarReceta($id) {
        $receta = Receta::findOrFail($id);
        if (Auth::id() !== $receta->autor_receta) {
            abort(403, 'No autorizado.');
        }
        return view('recetas.detalle', compact('receta'));
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
        if (Auth::id() !== $receta->autor_receta) {
            abort(403, 'No autorizado.');
        }

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
        if (Auth::id() !== $receta->autor_receta) {
            abort(403, 'No autorizado.');
        }
        $receta->delete();

        return redirect()->route('recetas.lista')->with('success', 'Receta eliminada.');
    }

    // Eliminar admin receta
    public function eliminarRecetaAdmin($id) {

        $receta = Receta::findOrFail($id);

        if($receta){
            $receta->delete();
            return response()->json(['status' => 'success', 'message' => 'Se ha eliminado la receta']);
        }
        
        return response()->json(['status' => 'failed', 'message' => 'Ha ocurrido un error']);
    }

}
