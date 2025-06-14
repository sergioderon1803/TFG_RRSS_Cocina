<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Perfil;
use App\Models\Receta;
use App\Models\SeguirUsuario;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\GuardarReceta;
use App\Models\GustarReceta;

class ProfileController extends Controller {

    public function ver($id)
    {
        $perfil = Perfil::where('id_user', $id)->firstOrFail();

        if($perfil->user->user_type == 2){
            abort(404, 'Aquí no hay nada');
        }

        $seguido = false;

        if (Auth::check()) {
            $userId = Auth::id();
            $seguido = SeguirUsuario::where('id_user', $id)
                                    ->where('id_seguidor', $userId)
                                    ->exists();
        }

        // Enviar todo a la vista
        return view('profile.perfil', compact('perfil', 'seguido'));
    }

    // Buscador de la principal

    public function buscarPerfiles(Request $request)
    {

        $usuariosBaneados = User::where('user_type',2)->select('id')->get();

        $usuarios = Perfil::where('name','LIKE',$request->input.'%')->whereNot('id_user',Auth::id())->whereNotIn('id_user',$usuariosBaneados)->take(6)->get(); // Cojo 6 que coincidan y no sean el usuario logado

        $listaUsuarios = '';

        // Si hay más de 0, los imprimo, si no, no hay resultados

        if(count($usuarios)>0){

            $listaUsuarios = '<ul class="list-group" style="display:block;position:absolute;z-index:1;">';

            foreach($usuarios as $perfil){

                $imgPerfil = str_starts_with($perfil->img_perfil, 'perfiles/')
                            ? asset('storage/' . $perfil->img_perfil)
                            : asset('images/default-profile.jpg');

                $url = url('perfil/' .$perfil->id_user);

                $listaUsuarios .= '<li class="list-group-item usuarioCoincidencia w-100 p-0">
                                        <a href="'.$url.'" class="text-decoration-none text-muted d-flex align-items-center w-100 p-2">
                                            <img src="'.$imgPerfil.'" 
                                                class="rounded-circle shadow-sm me-2"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                            <p class="pb-0 mb-0">'.$perfil->name.'</p>
                                        </a>
                                    </li>';
            }

            $listaUsuarios .= '</ul>';

        }else{
            $listaUsuarios = '';
        }

        return $listaUsuarios;
    }

    public function busqueda(Request $request)
    {
        $usuariosBaneados = User::where('user_type',2)->select('id')->get();

        $filtro = $request->busqueda;

        $recetas = Receta::where('titulo','LIKE',$request->busqueda."%")->whereNot('autor_receta',Auth::id())->where('estado',0)->whereNotIn('autor_receta',$usuariosBaneados)->take(9)->get();

        foreach($recetas as $receta){

            $receta['like'] = GustarReceta::where('id_receta',$receta->id)->where('id_user',Auth::id())->exists();
            $receta['guardado'] = GuardarReceta::where('id_receta',$receta->id)->where('id_user',Auth::id())->exists();
        }

        $usuarios = Perfil::where('name','LIKE',$request->busqueda.'%')->whereNot('id_user',Auth::id())->whereNotIn('id_user',$usuariosBaneados)->take(6)->get(); // Cojo 6 que coincidan y no sean el usuario logado

        return view('profile.busqueda', compact('recetas', 'usuarios','filtro'));
    }

    public function verSeguidores($id)
    {
        $perfil = Perfil::where('id_user', $id)->firstOrFail();
        $usuario = $perfil->user;

        $seguidores = $usuario->seguidores()->with('perfil')->get();

        return view('profile.seguidores', compact('perfil', 'seguidores'));
    }

    public function verSeguidoresAjax($id)
    {
        $usuariosBaneados = User::where('user_type',2)->select('id')->get();

        $perfil = Perfil::where('id_user', $id)->firstOrFail();
        $usuario = $perfil->user;

        $seguidores = $usuario->seguidores()->with('perfil')->whereNotIn('id_seguidor',$usuariosBaneados)->get();

        return response(json_encode($seguidores),200)->header('Content-type','text/plain');
    }

    public function verSeguidos($id)
    {
        $perfil = Perfil::where('id_user', $id)->firstOrFail();
        $usuario = $perfil->user;

        $seguidos = $usuario->seguidos()->with('perfil')->get();

        return view('profile.seguidos', compact('perfil', 'seguidos'));
    }

    public function verSeguidosAjax($id)
    {
        $usuariosBaneados = User::where('user_type',2)->select('id')->get();

        $perfil = Perfil::where('id_user', $id)->firstOrFail();
        $usuario = $perfil->user;

        $seguidos = $usuario->seguidos()->with('perfil')->whereNotIn('id_user',$usuariosBaneados)->get();

        return response(json_encode($seguidos),200)->header('Content-type','text/plain');
    }

    public function editar($id)
    {
        $perfil = Perfil::where('id_user', $id)->firstOrFail();

        return view('profile.edicionPerfil', compact('perfil'));
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'img_perfil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'img_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $perfil = Perfil::where('id_user', $id)->firstOrFail();

        $perfil->name = $request->nombre;
        $perfil->biografia = $request->descripcion;

        if ($request->hasFile('img_perfil')) {
            $perfil->img_perfil = $request->file('img_perfil')->store('perfiles', 'public');
        }

        if ($request->hasFile('img_banner')) {
            $perfil->img_banner = $request->file('img_banner')->store('perfiles', 'public');
        }

        $perfil->save();

        return redirect()->route('perfil.ver', ['id' => $perfil->id_user])
                         ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function ajustesCuenta()
    {
        return view('profile.cuenta');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actualizarEmail(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'email' => $request->email,
            'email_verified_at' => null,
        ]);

        return back()->with('success', 'Email actualizado correctamente. Por favor, verifica tu nuevo email.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actualizarPassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
