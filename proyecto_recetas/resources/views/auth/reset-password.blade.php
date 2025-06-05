@extends('layouts.app')

@section('titulo', 'Restablecer contraseña')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-4 px-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-key-fill fs-2 me-3"></i>
                        <div>
                            <h1 class="h3 mb-1 fw-bold">Restablecer contraseña</h1>
                            <p class="mb-0 opacity-75">Crea una nueva contraseña segura</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope text-secondary me-2"></i>Correo electrónico
                            </label>
                            <input id="email" type="email" class="form-control custom-input @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', $request->email) }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock text-secondary me-2"></i>Contraseña
                            </label>
                            <input id="password" type="password" class="form-control custom-input @error('password') is-invalid @enderror" 
                                   name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill text-secondary me-2"></i>Confirmar contraseña
                            </label>
                            <input id="password_confirmation" type="password" class="form-control custom-input" 
                                   name="password_confirmation" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="bi bi-check-lg me-2"></i>Restablecer contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
