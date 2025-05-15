<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WeCook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-toggle-btn.active {
            font-weight: bold;
            border-bottom: 2px solid #0d6efd;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row shadow bg-white rounded overflow-hidden">
            <!-- Imagen -->
            <div class="col-md-6 d-flex align-items-center justify-content-center bg-body-secondary p-4">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="img-fluid" style="max-height: 400px;">
            </div>

            <!-- Formularios -->
            <div class="col-md-6 p-5">
                <!-- Botones -->
                <div class="mb-4 d-flex justify-content-center gap-3">
                    <button class="btn btn-link form-toggle-btn active" onclick="showForm('login')">Login</button>
                    <button class="btn btn-link form-toggle-btn" onclick="showForm('register')">Register</button>
                </div>

                <!-- Formulario Login -->
                <form id="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <h3 class="mb-3">Iniciar sesión</h3>

                    <div class="mb-3">
                        <label for="login-email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="login-password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="login-password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">Recordarme</label>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>

                <!-- Formulario Registro -->
                <form id="register-form" method="POST" action="{{ route('register') }}" style="display: none;">
                    @csrf
                    <h3 class="mb-3">Registrarse</h3>

                    <div class="mb-3">
                        <label for="register-email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="register-email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="register-password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="register-password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100">Registrarse</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS + Toggle Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showForm(type) {
            document.getElementById('login-form').style.display = type === 'login' ? 'block' : 'none';
            document.getElementById('register-form').style.display = type === 'register' ? 'block' : 'none';

            document.querySelectorAll('.form-toggle-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`.form-toggle-btn[onclick="showForm('${type}')"]`).classList.add('active');
        }

        // Mostrar automáticamente el formulario con errores
        @if ($errors->has('email') || $errors->has('password') || $errors->has('password_confirmation'))
            showForm('register');
        @elseif (old('email')) // Si hay email en login, asumimos intento de login fallido
            showForm('login');
        @else
            showForm('login');
        @endif
    </script>
</body>
</html>
