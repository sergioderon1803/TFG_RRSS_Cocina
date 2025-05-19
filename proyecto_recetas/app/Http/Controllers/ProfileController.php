<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Perfil;
use App\Models\SeguirUsuario;

class ProfileController extends Controller {

    public function ver($id)
    {
        $perfil = Perfil::where('id_user', $id)->firstOrFail();
        $recetas = $perfil->user->recetas()->get();

        $seguido = false;
        $seguidores = SeguirUsuario::where('id_user', $id)->count();
        $seguidos = SeguirUsuario::where('id_seguidor', $id)->count();

        if (Auth::check()) {
            $userId = Auth::id();
            $seguido = SeguirUsuario::where('id_user', $id)
                                    ->where('id_seguidor', $userId)
                                    ->exists();
        }

        // Enviar todo a la vista
        return view('profile.perfil', compact('perfil', 'recetas', 'seguido', 'seguidores', 'seguidos'));
    }

    public function verSeguidores($id)
    {
        $perfil = Perfil::where('id_user', $id)->firstOrFail();
        $usuario = $perfil->user;

        $seguidores = $usuario->seguidores()->with('perfil')->get();

        return view('profile.seguidores', compact('perfil', 'seguidores'));
    }

    public function verSeguidos($id)
    {
        $perfil = Perfil::where('id_user', $id)->firstOrFail();
        $usuario = $perfil->user;

        $seguidos = $usuario->seguidos()->with('perfil')->get();

        return view('profile.seguidos', compact('perfil', 'seguidos'));
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
}
