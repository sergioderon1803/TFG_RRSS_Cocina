@extends('layouts.app')

@section('titulo', 'Verifica tu correo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-4 px-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope-check-fill fs-2 me-3"></i>
                        <div>
                            <h1 class="h3 mb-1 fw-bold">Verifica tu correo electrónico</h1>
                            <p class="mb-0 opacity-75">Necesitamos confirmar tu dirección de email</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info border-0 d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos otro.</div>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success border-0 d-flex align-items-center mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico proporcionada.</div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-envelope-fill me-2"></i>Reenviar email de verificación
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none">
                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
