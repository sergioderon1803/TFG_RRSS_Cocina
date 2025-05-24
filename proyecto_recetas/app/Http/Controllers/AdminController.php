<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{

    // Ver de quitar, ahora mismo no es útil
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

    public function listaRecetas(Request $request)
    {
        $tipo = $request->query('tipo');

        $recetas = Receta::with('autor')->paginate(5);
        return view('admin.adminRecetas', compact('recetas'));
    }

    public function listaUsuarios(Request $request)
    {
        $tipo = $request->query('tipo');

        $usuarios = User::paginate(5);
            return view('admin.adminUsuarios', compact('usuarios'));
    }

    public function listaUsuariosAjax(Request $request)
    {
        $usuarios = User::query();

        return Datatables::eloquent($usuarios)
        
        ->addColumn('created_at', function($user){
            return Carbon::parse($user->created_at)->format('d-m-Y');
        })
        
        ->addColumn('action', function($user){
            return '<button data-id="'.$user->id.'" class="btn btn-danger btn-sm delete-user">Eliminar</button>';
        })

        ->rawColumns(['action'])

        ->make(true);
    }

}
