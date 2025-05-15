<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Perfil;

class ProfileController extends Controller {

    public function ver($id)
    {
        // Buscar perfil por id_user (clave primaria personalizada)
        $perfil = Perfil::where('id_user', $id)->firstOrFail();

        // Cargar recetas del usuario relacionado
        $recetas = $perfil->user->recetas()->get();

        return view('profile.perfil', compact('perfil', 'recetas'));
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
