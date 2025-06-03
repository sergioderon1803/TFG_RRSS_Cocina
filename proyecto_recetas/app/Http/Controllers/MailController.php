<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMail;

class MailController extends Controller
{
    public function index()
    {
        return view('legal.contacto');
    }

    public function enviar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $datos = $request->only('nombre', 'email', 'asunto', 'mensaje');

        // Enviar correo
        Mail::to('contacto@wecook.com')->send(new ContactoMail($datos));

        return redirect()->route('contacto')->with('success', 'Mensaje enviado correctamente.');
    }
}