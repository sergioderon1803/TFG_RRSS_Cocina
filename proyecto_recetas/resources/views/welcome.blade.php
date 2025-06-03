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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body class="position-relative bg-peach">
    <div class="fondoRegistro">
        <div class="container auth-container fondoOpaco d-flex align-items-center justify-content-center min-vh-100 px-3">
            <div class="row shadow-lg bg-white rounded-4 overflow-hidden w-100 flex-column flex-md-row">
                <!-- Panel izquierdo con imagen/logo -->
                <div class="col-md-6 left-panel p-5 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="WeCook Logo" class="img-fluid mb-4">
                    <h2 class="text-white text-center mb-3">Comparte tus recetas</h2>
                    <p class="text-white text-center opacity-75">Únete a nuestra comunidad de amantes de la cocina</p>
                </div>

                <!-- Panel derecho con formularios -->
                <div class="col-md-6 p-5 position-relative">
                    <div class="welcome-title">Bienvenido a WeCook</div>

                    <!-- Botones de selección -->
                    <div class="mb-4 d-flex justify-content-center gap-3">
                        <button class="btn btn-link form-toggle-btn active" onclick="showForm('login')">Iniciar Sesión</button>
                        <button class="btn btn-link form-toggle-btn" onclick="showForm('register')">Registrarse</button>
                    </div>

                    <div class="auth-form-container">
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
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="login-password" name="password" required>
                                    <button type="button" class="btn-toggle-password" onclick="togglePassword('login-password', this)">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
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
                        <form id="register-form" method="POST" action="{{ route('register') }}" class="form-section">
                            @csrf
                            <h3 class="mb-3">Únete a WeCook</h3>

                            <div class="mb-3">
                                <label for="register-email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="register-email" name="email" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="register-password" class="form-label">Contraseña</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="register-password" name="password" required>
                                    <button type="button" class="btn-toggle-password" onclick="togglePassword('register-password', this)">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                                    <button type="button" class="btn-toggle-password" onclick="togglePassword('password_confirmation', this)">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Crear cuenta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-grow-1 fondoAbout p-5">
        <div class="container py-5 bg-light rounded shadow">
            <h1 class="text-center mb-4">Sobre Nosotros</h1>

            <p class="text-center lead mb-5">
                Somos tres amigos apasionados por la cocina y el desarrollo web. Esta red social permite compartir, descubrir y comentar recetas.
            </p>

            <div class="row justify-content-center text-center g-5">
                <div class="col-sm-6 col-md-4">
                    <img src="{{ asset('storage/about/placeholder.jpg') }}" class="profile-img" alt="Sergio Álvarez de Ron">
                    <div class="profile-name">Sergio Álvarez de Ron</div>
                    <div class="profile-desc">Alérgico a la vida</div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <img src="{{ asset('storage/about/placeholder.jpg') }}" class="profile-img" alt="Jonathan Hidalgo">
                    <div class="profile-name">Jonathan Hidalgo</div>
                    <div class="profile-desc">Catador profesional de elixires revitalizantes</div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <img src="{{ asset('storage/about/placeholder.jpg') }}" class="profile-img" alt="Sergio Montoiro">
                    <div class="profile-name">Sergio Montoiro</div>
                    <div class="profile-desc">Programador explotado</div>
                </div>
            </div>
        </div>

    <!--Noticias-->
    <div class="container bg-light shadow rounded mt-5 p-5">
        <p class="text-center fs-1 mb-5">Noticias</p>
    <!-- Fresas Beneficios -->
    <div class="d-flex">
    <div class="row mb-4 align-items-center">
      <div class="col-md-4 tamaño">
          <img src="{{ asset('images/fresasNoticia.jpg') }}" alt="Noticia fresas" class="img-fluid rounded">
      </div>
      <div class="col-md-6 text-center">
        <div class="product-card">
          <h3 class="fw-bold titulo text-center">¿Te gustaría saber más acerca de esta deliciosa fruta?</h3>
          <p class="text-center contenido"> Entonces no te pierdas el siguiente artículo de EcologíaVerde donde hablaremos de las propiedades de las fresas, sus beneficios y sus contraindicaciones.</p>
          <button type="button" class="text-light text-center rounded fs-6 mt-3 sabermas"><a class="enlace" href="https://www.ecologiaverde.com/fresas-propiedades-beneficios-y-contraindicaciones-4519.html">Saber más...</a></button>
        </div>
      </div>
    </div>
    </div>
    <hr>
    <!-- Fresas Beneficios -->
    <div class="d-flex">
    <div class="row mb-4 align-items-center">
      <div class="col-md-4 tamaño">
          <img src="{{ asset('images/fresasNoticia.jpg') }}" alt="Noticia fresas" class="img-fluid rounded">
      </div>
      <div class="col-md-6 text-center">
        <div class="product-card">
          <h3 class="fw-bold fs-5 text-center">¿Te gustaría saber más acerca de esta deliciosa fruta?</h3>
          <p class="text-center fs-6"> Entonces no te pierdas el siguiente artículo de EcologíaVerde donde hablaremos de las propiedades de las fresas, sus beneficios y sus contraindicaciones.</p>
          <button type="button" class="text-light text-center rounded fs-6 mt-3 sabermas"><a class="enlace" href="https://www.ecologiaverde.com/fresas-propiedades-beneficios-y-contraindicaciones-4519.html">Saber más...</a></button>
        </div>
      </div>
    </div>
    </div>
    <hr>
    <!-- Fresas Beneficios -->
    <div class="d-flex">
    <div class="row mb-4 align-items-center">
      <div class="col-md-4 tamaño">
          <img src="{{ asset('images/fresasNoticia.jpg') }}" alt="Noticia fresas" class="img-fluid rounded">
      </div>
      <div class="col-md-6 text-center">
        <div class="product-card">
          <h3 class="fw-bold fs-5 text-center">¿Te gustaría saber más acerca de esta deliciosa fruta?</h3>
          <p class="text-center fs-6"> Entonces no te pierdas el siguiente artículo de EcologíaVerde donde hablaremos de las propiedades de las fresas, sus beneficios y sus contraindicaciones.</p>
          <button type="button" class="text-light text-center rounded fs-6 mt-3 sabermas"><a class="enlace" href="https://www.ecologiaverde.com/fresas-propiedades-beneficios-y-contraindicaciones-4519.html">Saber más...</a></button>
        </div>
      </div>
    </div>
    </div>
    </div>
    </div>

    </main>
    


    <!-- Bootstrap JS + Script de alternancia -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showForm(type) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            
            if (type === 'login') {
                registerForm.classList.remove('show');
                registerForm.classList.add('hide-left');
                loginForm.classList.add('show');
                loginForm.classList.remove('hide-left');
            } else {
                loginForm.classList.remove('show');
                loginForm.classList.add('hide-left');
                registerForm.classList.add('show');
                registerForm.classList.remove('hide-left');
            }
            
            document.querySelectorAll('.form-toggle-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.form-toggle-btn[onclick="showForm('${type}')"]`).classList.add('active');
        }

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

        // Mostrar formulario correcto si hay errores
        @if ($errors->has('email') || $errors->has('password') || $errors->has('password_confirmation'))
            showForm('register');
        @else
            showForm('login');
        @endif
    </script>
</body>
</html>
