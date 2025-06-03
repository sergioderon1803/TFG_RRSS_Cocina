<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'WeCook!')</title>
    @yield('css')

    <!-- Bootstrap y estilos -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @if(Request::is('admin*'))
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @endif

    @if(Request::is('perfil*'))
        <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    @endif

    @if(Request::is('recetas/crear*') || Request::is('recetas/*/editar'))
        <link rel="stylesheet" href="{{ asset('css/crearReceta.css') }}">
    @endif

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Vite -->
    @vite(['public/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="d-flex min-vh-100 flex-column flex-lg-row position-relative"> <!-- Añadido position-relative -->
        @auth
            <!-- Sidebar de ordenador y tablet -->
            <aside class="d-none d-lg-block">
                @include('layouts.navigation')
            </aside>

            <!-- Sidebar de móvil -->
            <aside class="d-block d-lg-none">
                @include('layouts.navigationResponsive')
            </aside>
        @endauth

        {{-- @guest
            @include('layouts.navigationGuest')
        @endguest --}}

        <!-- Contenido principal -->
        <main id="mainContent" class="main-content bg-light d-flex justify-content-center align-items-start py-4 flex-grow-1">
            <div class="container rounded-3 shadow-sm p-3 bg-white">
                @yield('content')
            </div>
        </main>

        <!-- Footer fuera del contenedor principal para evitar problemas de z-index -->
        @include('layouts.footer')
    </div>
    @yield('js')
    @stack('scripts')
</body>
</html>
