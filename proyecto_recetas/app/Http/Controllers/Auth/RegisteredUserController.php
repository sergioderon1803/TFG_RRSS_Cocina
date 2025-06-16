<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Perfil;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        if(User::where('email',$request->email)->exists()){
            throw ValidationException::withMessages([
                'email' => trans('Este email ya está registrado, inicie sesión o pruebe uno distinto'),
            ]);

        }else if(strlen($request->password)<8){

            throw ValidationException::withMessages([
                'password' => trans('Contraseña demasiado corta, de tener al menos 8 de longitud'),
            ]);

        }else if($request->password != $request->password_confirmation){

            throw ValidationException::withMessages([
                'password' => trans('Las contraseñas no coinciden, pruebe de nuevo'),
            ]);
            
        }

        $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            // 'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 0,
        ]);

        Perfil::create([
            'id_user' => $user->id,
            'name' => $user->email, //Poner el nombre del usuario
            'img_perfil' => "images/default-profile.jpg",
            'img_banner' => "images/default-banner.jpg",
            'biografia' => "¡Cocinando en WeCook!"
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('recetas');
    }
}
