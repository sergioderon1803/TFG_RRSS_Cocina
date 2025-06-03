<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sobre Nosotros - WeCook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap/icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    @vite(['public/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased d-flex flex-column min-vh-100">
    @auth
        @include('layouts.navigation')
    @endauth
    @guest
        @include('layouts.navigationGuest')
    @endguest

    <main class="flex-grow-1">
        <div class="container py-5">
            <h1 class="text-center mb-4">Sobre Nosotros</h1>

            <p class="text-center lead mb-5">
                Somos tres amigos apasionados por la cocina y el desarrollo web. Esta red social permite compartir, descubrir y comentar recetas.
            </p>

            <div class="row justify-content-center text-center g-5">
                <div class="col-sm-6 col-md-4">
                    <img src="{{ asset('images/default-profile.jpg') }}" class="profile-img" alt="Sergio Álvarez de Ron">
                    <div class="profile-name">Sergio Álvarez de Ron</div>
                    <div class="profile-desc">Alérgico a la vida</div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <img src="{{ asset('images/default-profile.jpg') }}" class="profile-img" alt="Jonathan Hidalgo">
                    <div class="profile-name">Jonathan Hidalgo</div>
                    <div class="profile-desc">Catador profesional de elixires revitalizantes</div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <img src="{{ asset('images/default-profile.jpg') }}" class="profile-img" alt="Sergio Montoiro">
                    <div class="profile-name">Sergio Montoiro</div>
                    <div class="profile-desc">Programador explotado</div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
