<?php

namespace App\Http\Controllers;

use App\Models\GuardarReceta;
use App\Models\GustarReceta;
use App\Models\Comentario;
use App\Models\Perfil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function guardarRecetaUsuarioAjax($id)
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

            return response()->json(['status' => 'success', 'message' => 'Guardada']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Ha ocurrido un error']);
    }

    public function eliminarGuardado($id)
    {
        $userId = Auth::id();

        GuardarReceta::where('id_receta', $id)->where('id_user', $userId)->delete();

        return back()->with('success', 'Guardado eliminado.');
    }

    public function eliminarGuardadoAjax($id)
    {
        $receta = GuardarReceta::where('id_receta', $id)->where('id_user', Auth::id());

        if($receta){
            $receta->delete();
            return response()->json(['status' => 'success', 'message' => 'Se ha eliminado la receta']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Ha ocurrido un error']);
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

    public function gustarRecetaUsuarioAjax($id)
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

            return response()->json(['status' => 'success', 'message' => 'Dado me gusta']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Ha ocurrido un error']);
    }

    public function eliminarMeGusta($id)
    {
        $userId = Auth::id();

        GustarReceta::where('id_receta', $id)->where('id_user', $userId)->delete();

        return back()->with('success', 'Ya no te gusta esta receta.');
    }

    public function eliminarMeGustaAjax($id)
    {
        $receta = GustarReceta::where('id_receta', $id)->where('id_user', Auth::id());

        if($receta){
            $receta->delete();
            return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el me gusta']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Ha ocurrido un error']);
    }

    public function mostrarComentario($id) {
        $receta = Receta::with(['comentarios.user.perfil'])->findOrFail($id);
        return view('recetas.detalle', compact('receta'));
    }
    
    public function listarRecetas(){
        return view('recetas.lista');
    }

    public function listarRecetasPrincipalAjax(Request $request){

        $listaIds = [];
        $filtro = $request->tipo;

        if(isset($request->recetas)){

            $listaIds = array($request->recetas);

            for($i = 0; $i<count($listaIds[0]);$i++){
                $listaIds[0][$i] = intval($listaIds[0][$i]);
            }

            if($filtro == "Todas"){

                $recetas = Receta::whereNotIn('id',$listaIds[0])->whereNot('autor_receta',Auth::id())->where('estado',0)->get();

            }else{

                $recetas = Receta::whereNotIn('id',$listaIds[0])->where('tipo',$filtro)->whereNot('autor_receta',Auth::id())->where('estado',0)->get();

            }

        }else{

            if($filtro == "Todas"){

                $recetas = Receta::orderBy('created_at', 'desc')->whereNot('autor_receta',Auth::id())->where('estado',0)->get();

            }else{

                $recetas = Receta::orderBy('created_at', 'desc')->where('tipo',$filtro)->whereNot('autor_receta',Auth::id())->where('estado',0)->get();

            }

        }

        $recetas = $recetas->shuffle()->take(9);

        foreach($recetas as $r){

            $r['meGustas'] = count($r->usuariosQueGustaron);
            $r['vecesGuardados'] = count($r->usuariosQueGuardaron);
            $r['nombreAutor'] = $r->autor->perfil->name;
            $r['imgAutor'] = $r->autor->perfil->img_perfil ?? null;
            $r['like'] = GustarReceta::where('id_receta',$r->id)->where('id_user',Auth::id())->exists();
            $r['guardado'] = GuardarReceta::where('id_receta',$r->id)->where('id_user',Auth::id())->exists();

        }

        return response(json_encode($recetas),200)->header('Content-type','text/plain');
    }

    // Listado recetas del usuario

    public function listarRecetasAjax(Request $request){

        if($request->id == Auth::id() || auth()->user()->user_type == 1){

            $recetas = Receta::where('autor_receta', $request->id)->get();

        }else{

            $recetas = Receta::where('autor_receta', $request->id)->where('estado',0)->get();
        }

        return response(json_encode($recetas),200)->header('Content-type','text/plain');
    }

    // Listado recetas que le gustan al usuario

    public function listarMeGustaAjax(Request $request){

        $meGustas = GustarReceta::where('id_user',$request->id)->select('id_receta')->get();
        $recetas = Receta::whereIn('id', $meGustas)->where('estado',0)->get();
        return response(json_encode($recetas),200)->header('Content-type','text/plain');
    }

    public function mostrarRecetaIndividual($id)
    {
        $receta = Receta::with([
            'autor.perfil',
            'comentarios.user.perfil',
            'comentarios.respuestas.user.perfil'
        ])->findOrFail($id);

        if (auth()->user()->user_type !== 1 && $receta->estado == 1 && Auth::id() != $receta->autor_receta) {
            abort(403, 'Acceso denegado');
        }

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


    public function recetasGuardadasVista(){
        return view('recetas.recetasGuardadas');
    }

    public function listarRecetasGuardadasAjax(Request $request){

        $guardadas = GuardarReceta::where('id_user',Auth::id())->select('id_receta')->get();
        $recetas = Receta::whereIn('id', $guardadas)->get();

        foreach($recetas as $r){

            $r['meGustas'] = count($r->usuariosQueGustaron);
            $r['vecesGuardados'] = count($r->usuariosQueGuardaron);
            $r['nombreAutor'] = $r->autor->perfil->name;
            $r['imgAutor'] = $r->autor->perfil->img_perfil ?? null;

            if(GustarReceta::where('id_receta',$r->id)->where('id_user',Auth::id())->exists()){

                $r['like'] = true;

            }
            else
            {
                $r['like'] = false;
            }
        }

        return response(json_encode($recetas),200)->header('Content-type','text/plain');
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

    // Ocultar receta
    public function ocultarReceta(Request $request) {

        $receta = Receta::findOrFail($request->id);

        if($receta){

            if($receta->estado == 0){

                $receta->update([
                    'estado' => 1
                ]);

            }else{

                $receta->update([
                    'estado' => 0
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Receta actualizada']);
        }
        
        return response()->json(['status' => 'failed', 'message' => 'Ha ocurrido un error']);
    }

}
