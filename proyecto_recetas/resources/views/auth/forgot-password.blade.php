@extends('layouts.app')

@section('titulo', 'Recuperar contraseña')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-4 px-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-lock-fill fs-2 me-3"></i>
                        <div>
                            <h1 class="h3 mb-1 fw-bold">¿Olvidaste tu contraseña?</h1>
                            <p class="mb-0 opacity-75">Te ayudamos a recuperar el acceso</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info border-0 d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu dirección de correo electrónico y te enviaremos un enlace para que puedas crear una nueva contraseña.</div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success border-0 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>Hemos enviado por correo electrónico el enlace para restablecer tu contraseña.</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope text-secondary me-2"></i>Correo electrónico
                            </label>
                            <input type="email" name="email" id="email" 
                                   class="form-control custom-input @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="bi bi-send-fill me-2"></i>Enviar enlace de recuperación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
