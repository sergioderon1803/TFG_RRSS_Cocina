@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center fw-bold">
        <i class="bi bi-gear-fill me-2"></i>
        Ajustes de usuario
    </h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert custom-alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-envelope-fill me-2"></i>
                        Cambiar email
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('ajustes.actualizar-email') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="bi bi-at me-2"></i>Nuevo email
                            </label>
                            <div class="position-relative">
                                <input type="email" class="form-control custom-input" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn fondoExito text-white">
                                <i class="bi bi-check-lg me-2"></i>Actualizar email
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-lock-fill me-2"></i>
                        Cambiar contraseña
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('ajustes.actualizar-password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold">
                                <i class="bi bi-shield-lock me-2"></i>Contraseña actual
                            </label>
                            <div class="position-relative">
                                <input type="password" class="form-control custom-input" id="current_password" name="current_password" required>
                                <button type="button" class="btn-toggle-password" onclick="togglePassword('current_password', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="bi bi-key me-2"></i>Nueva contraseña
                            </label>
                            <div class="position-relative">
                                <input type="password" class="form-control custom-input" id="password" name="password" required>
                                <button type="button" class="btn-toggle-password" onclick="togglePassword('password', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">
                                <i class="bi bi-key-fill me-2"></i>Confirmar nueva contraseña
                            </label>
                            <div class="position-relative">
                                <input type="password" class="form-control custom-input" id="password_confirmation" name="password_confirmation" required>
                                <button type="button" class="btn-toggle-password" onclick="togglePassword('password_confirmation', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn fondoExito text-white">
                                <i class="bi bi-check-lg me-2"></i>Actualizar contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
