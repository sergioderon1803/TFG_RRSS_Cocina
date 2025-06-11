<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SeguirUsuario;
use App\Models\Receta;

class UserController extends Controller {

    public function mostrarPerfilAutenticado() {
        $user = Auth::user(); // Usuario logado
        $perfil = $user->perfil;
        $recetas = $user->recetas;

        return view('profile.perfil', compact('user', 'perfil', 'recetas'));
    }

    // Eliminar usuario
    public function eliminarUsuarioAdmin($id) {
        $usuario = User::findOrFail($id);

        if($usuario){
            $usuario->delete();
            return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el usuario']);
        }
        
        return response()->json(['status' => 'failed', 'message' => 'Ha ocurrido un error']);
    }

    public function SeguirUsuario($id)
    {
        $userId = Auth::id();

        SeguirUsuario::create([
            'id_user' => $id,
            'id_seguidor' => $userId,
            'f_seguimiento' => now(),
        ]);

        return back()->with('success', 'Completado');
    }

    public function SeguirUsuarioAjax($id)
    {
        $userId = Auth::id();

        SeguirUsuario::create([
            'id_user' => $id,
            'id_seguidor' => $userId,
            'f_seguimiento' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Usuario seguido']);
    }

    public function DejarDeSeguir($id)
    {
        $userId = Auth::id();

        SeguirUsuario::where('id_user', $id)->where('id_seguidor', $userId)->delete();

        return back()->with('success', 'Dejado de seguir');
    }

    public function dejarSeguirUsuarioAjax($id)
    {
        $userId = Auth::id();

        SeguirUsuario::where('id_user', $id)->where('id_seguidor', $userId)->delete();

        return response()->json(['status' => 'success', 'message' => 'Dejado de seguir']);
    }

    // Nombrar admin o quitarlo
    public function hacerAdmin(Request $request) {

        $user = User::findOrFail($request->id);

        if($user){

            if($user->user_type == 0){

                $user->update([
                    'user_type' => 1
                ]);

            }else{

                $user->update([
                    'user_type' => 0
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Usuario actualizado']);
        }
        
        return response()->json(['status' => 'failed', 'message' => 'Ha ocurrido un error']);
    }

}
