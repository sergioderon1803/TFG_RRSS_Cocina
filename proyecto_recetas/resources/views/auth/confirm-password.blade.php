@extends('layouts.app')

@section('titulo', 'Confirmar contraseña')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-4 px-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-check fs-2 me-3"></i>
                        <div>
                            <h1 class="h3 mb-1 fw-bold">Área segura</h1>
                            <p class="mb-0 opacity-75">Confirma tu contraseña para continuar</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info border-0 d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.</div>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock text-secondary me-2"></i>Contraseña
                            </label>
                            <input id="password" type="password" 
                                   class="form-control custom-input @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="bi bi-check-lg me-2"></i>Confirmar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
