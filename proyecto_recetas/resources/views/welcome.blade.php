<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WeCook - Comparte tus recetas al mundo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        /* Habría que poner todo el CSS en un archivo a parte */
        :root {
            --main-color: #ff5722;
            --success-color: #43a047;
            --bg-light: #fff6f3;
            --text-dark: #333;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: url('{{ asset('images/landing_bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-dark);
            transition: var(--transition);
            position: relative;
            z-index: 0;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6); /* filtro claro sobre la imagen */
            z-index: -1;
            transition: var(--transition);
            pointer-events: none;
        }

        .auth-container {
            max-width: 1000px;
        }

        .left-panel {
            /* Fondo de color */
            background: var(--main-color);
            position: relative;
            transition: var(--transition);
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .left-panel::before {
            content: "";
            background: rgba(255, 255, 255, 0.85);
            position: absolute;
            inset: 0;
            transition: var(--transition);
            z-index: 0;
        }

        .left-panel img {
            position: relative;
            z-index: 1;
            max-height: 400px;
            width: auto;
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--main-color);
            text-align: center;
        }

        .form-toggle-btn.active {
            font-weight: bold;
            border-bottom: 3px solid var(--main-color);
            color: var(--main-color) !important;
        }

        .btn-primary {
            background-color: var(--main-color);
            border: none;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: #e64a19;
        }

        .btn-success {
            background-color: var(--success-color);
            border: none;
            transition: var(--transition);
        }

        .btn-success:hover {
            background-color: #388e3c;
        }

        .form-toggle-btn {
            transition: var(--transition);
        }

        .form-section {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .form-section.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 767px) {
            .row.flex-column.flex-md-row {
                flex-direction: column !important;
            }

            .left-panel {
                order: -1 !important; /* Logo arriba en móvil */
                min-height: 200px;
                padding: 1.5rem;
            }

            .left-panel img {
                max-height: 200px;
            }

            .col-md-6.p-5 {
                padding: 2rem 1rem !important;
            }

            .form-toggle-btn {
                font-size: 1rem;
            }

            .welcome-title {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body class="position-relative">

    <div class="container auth-container d-flex align-items-center justify-content-center min-vh-100 px-3">
        <div class="row shadow-lg bg-white rounded overflow-hidden w-100 flex-column flex-md-row">
            <!-- Panel izquierdo con imagen/logo -->
            <div class="col-md-6 left-panel p-4 d-flex align-items-center justify-content-center">
                <img src="{{ asset('images/logo.svg') }}" alt="WeCook Logo" class="img-fluid">
            </div>

            <!-- Panel derecho con formularios -->
            <div class="col-md-6 p-5 position-relative d-flex flex-column justify-content-start">
                <div class="welcome-title">Bienvenido a WeCook</div>

                <!-- Botones de selección -->
                <div class="mb-4 d-flex justify-content-center gap-3">
                    <button class="btn btn-link form-toggle-btn active" onclick="showForm('login')">Iniciar Sesión</button>
                    <button class="btn btn-link form-toggle-btn" onclick="showForm('register')">Registrarse</button>
                </div>

                <!-- Formulario Login -->
                <form id="login-form" method="POST" action="{{ route('login') }}" class="form-section show">
                    @csrf
                    <h3 class="mb-3">Bienvenido de nuevo</h3>

                    <div class="mb-3">
                        <label for="login-email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="login-password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="login-password" name="password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">Recordarme</label>
                    </div>

                    <div class="mb-3 text-end">
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>

                <!-- Formulario Registro -->
                <form id="register-form" method="POST" action="{{ route('register') }}" class="form-section" style="display: none;">
                    @csrf
                    <h3 class="mb-3">Únete a WeCook</h3>

                    <div class="mb-3">
                        <label for="register-email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="register-email" name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="register-password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="register-password" name="password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100">Crear cuenta</button>
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

            document.querySelectorAll('.form-section').forEach(form => form.classList.remove('show'));
            setTimeout(() => {
                document.getElementById(`${type}-form`).classList.add('show');
            }, 50);
        }

        // Mostrar formulario correcto si hay errores
        @if ($errors->has('email') || $errors->has('password') || $errors->has('password_confirmation'))
            showForm('register');
        @else
            showForm('login');
        @endif
    </script>
</body>
</html>
