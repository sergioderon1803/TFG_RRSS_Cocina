<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->user_type !== 1) {
            abort(403, 'Acceso denegado');
        }
        
        return view('admin.admin');
    }

    public function listaRecetasAjax(Request $request)
    {
        if (auth()->user()->user_type !== 1) {
            abort(403, 'Acceso denegado');
        }
        $recetas = Receta::query();

        return Datatables::eloquent($recetas) // Le mando la query al Datatable
        
        // Hago que la columna de fecha se muestre como yo quiero
        ->addColumn('created_at', function($receta){
            return Carbon::parse($receta->created_at)->format('d-m-Y');
        })

        // Hago que en vez del id me muestre el autor
        ->addColumn('autor_receta', function($receta){
            return $receta->autor->perfil->name;
        })

        ->addColumn('estado', function($receta){

            switch($receta->estado){
                case 0:
                    return 'Pública';
                case 1:
                    return 'Oculta';
            }

        })
        
        // Añado una columna de acciones y creo los botones que quiera
        ->addColumn('action', function($receta){

            $acciones = '<div class="btn-group" role="group">';

            switch($receta->estado){
                case 0:
                    $acciones .= '<button data-id="'.$receta->id.'" data-estado="'.$receta->estado.'" class="btn btn-dark btn-sm ocultar-receta">Ocultar</button>';
                    break;
                case 1:
                    $acciones .= '<button data-id="'.$receta->id.'" data-estado="'.$receta->estado.'" class="btn btn-success btn-sm ocultar-receta">Publicar</button>';
                    break;
            }

            $acciones .= '<button data-id="'.$receta->id.'" class="btn btn-danger btn-sm delete-receta">Eliminar</button></div>';

            return $acciones;
        })

        ->rawColumns(['action'])

        ->make(true);
    }

    public function listaUsuariosAjax(Request $request)
    {
        if (auth()->user()->user_type !== 1) {
            abort(403, 'Acceso denegado');
        }

        $usuarios = User::query();

        return Datatables::eloquent($usuarios)
        
        ->addColumn('created_at', function($user){
            return Carbon::parse($user->created_at)->format('d-m-Y');
        })

        ->addColumn('user_type', function($user){

            switch($user->user_type){
                case 0:
                    return 'Usuario';
                case 1:
                    return 'Admin';
                case 2:
                    return 'Baneado';
            }
        })
        
        ->addColumn('action', function($user){

            if(Auth::id() != $user->id){
                $acciones = '<div class="btn-group" role="group">';

                if($user->user_type == 2){

                    $acciones .= '<button data-id="'.$user->id.'" data-rol="'.$user->user_type.'" class="btn btn-success btn-sm banear-user">Desbanear</button>
                    <button data-id="'.$user->id.'" class="btn btn-danger btn-sm delete-user">Eliminar</button>
                    </div>';

                }else{

                    switch($user->user_type){
                        case 0:
                            $acciones .= '<button data-id="'.$user->id.'" data-rol="'.$user->user_type.'" class="btn btn-primary btn-sm rol-usuario">Ascender</button>';
                            break;
                        case 1:
                            $acciones .= '<button data-id="'.$user->id.'" data-rol="'.$user->user_type.'" class="btn btn-dark btn-sm rol-usuario">Degradar</button>';
                            break;
                    }

                    $acciones .= '<button data-id="'.$user->id.'" data-rol="'.$user->user_type.'" class="btn btn-sm banear-user" style="color:white;background-color:purple;">Banear</button>
                    <button data-id="'.$user->id.'" class="btn btn-danger btn-sm delete-user">Eliminar</button>
                    </div>';

                }

                return $acciones;

            }else{
                return '';
            }
        })

        ->addColumn('recetas_creadas', function($user){
            return $user->recetas->count();
        })

        ->rawColumns(['recetas_creadas'],['action'])

        ->make(true);
    }

}
