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

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="position-relative bg-peach">
    <div class="fondoRegistro">
        <div class="container auth-container fondoOpaco d-flex align-items-center justify-content-center min-vh-100 px-3">
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
    </div>

<!-- Libro promocional -->
<div class="container my-5">
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center min-vh-100 bg-peach p-4 rounded">
        
        <!-- Libro a la venta -->
        <div class="col-12 col-lg-4 d-flex flex-column align-items-center bg-light p-4 rounded mb-4 mb-lg-0">
            <img src="{{ asset('images/libroCocina.jpg') }}" alt="Libro de cocina" class="img-fluid mb-3">
            <p class="precio fs-4 fw-bold">14.99€</p>
            <button class="btn btn-success">Comprar</button>
        </div>

        <!-- Info del libro -->
        <div class="col-12 col-lg-6 d-flex align-items-center ps-lg-5">
            <div class="bg-white rounded shadow-sm p-4 text-center text-lg-start w-100">
                <h3 class="mb-3">Compra nuestro recetario</h3>
                <p class="fs-4">Con las mejores recetas de nuestros usuarios, adaptadas y mejoradas por nuestros mejores chefs.</p>
                <p class="fs-4">Adquiere este completo libro a través de nuestra tienda online.</p>
            </div>
        </div>

    </div>
</div>


    <!-- Bootstrap JS + Script de alternancia -->
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

    @include('layouts.footer')
</body>
</html>
