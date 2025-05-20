<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sobre Nosotros - WeCook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">

    <!-- Tus estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="font-sans antialiased d-flex flex-column min-vh-100 bg-peach">
    @auth
        @include('layouts.navigation')
    @endauth
    @guest
        @include('layouts.navigationGuest')
    @endguest
    <div class="container py-5">
               
        <main class="flex-grow-1">
            <h1 class="text-center mb-4">Sobre Nosotros</h1>

            <p class="text-center lead">
                Somos tres amigos apasionados por la cocina y el desarrollo web. Esta red social permite compartir, descubrir y comentar recetas.
            </p>

            <div class="row text-center mt-5">
                <div class="col-md-4">
                    <img src="{{ asset('storage/about/placeholder.jpg') }}" class="rounded-circle mb-3" alt="Sergio Álvarez de Ron">
                    <h5>Sergio Álvarez de Ron</h5>
                    <p>Alérgico a la vida</p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('storage/about/placeholder.jpg') }}" class="rounded-circle mb-3" alt="Jonathan Hidalgo">
                    <h5>Jonathan Hidalgo</h5>
                    <p>Catador profesional de elixires revitalizantes</p>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('storage/about/placeholder.jpg') }}" class="rounded-circle mb-3" alt="Sergio Montoiro">
                    <h5>Sergio Montoiro</h5>
                    <p>Programador explotado</p>
                </div>
            </div>
        </main>
    </div>
</body>
@include('layouts.footer')
</html>
